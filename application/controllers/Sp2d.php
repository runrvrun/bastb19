<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once './application/libraries/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

class Sp2d extends CI_Controller {
    public $cols = array(
		array("column" => "nomor", "caption" => "No. SP2D", "dbcolumn" => "nomor"),
	  	array("column" => "tanggal", "caption" => "Tanggal SP2D", "dbcolumn" => "tanggal"),
		array("column" => "no_spm", "caption" => "No. SPM", "dbcolumn" => "no_spm"),
		array("column" => "tanggal_spm", "caption" => "Tanggal SPM", "dbcolumn" => "tanggal_spm"),
		array("column" => "nilai_sebelum_pajak", "caption" => "Nilai (Rp) Sebelum Pajak", "dbcolumn" => "nilai_sebelum_pajak"),
		array("column" => "nilai_setelah_pajak", "caption" => "Nilai (Rp) Setelah Pajak", "dbcolumn" => "nilai_setelah_pajak"),
        array("column" => "keterangan", "caption" => "Keterangan", "dbcolumn" => "keterangan"),
    );    

    function __construct()
    {
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('Kontrak_pusat_model');
		$this->load->model('Alokasi_pusat_model');
		$this->load->model('Sp2d_model');
		$this->load->model('Baphp_model');
        $this->load->helper('url');
		$this->load->library('xlsxwriter');
	}

    public function index($id = null)
    {
        $this->load->library('parser');
		$id_kontrak_pusat = $this->input->get('id_kontrak_pusat');
		if(!empty($id_kontrak_pusat)){
            $param['kontrak_pusat'] = $this->Kontrak_pusat_model->get($id_kontrak_pusat);
			//Data tidak ditemukan
			if(empty($param['kontrak_pusat'])){
				show_404();
			}
		}
		$param['cols'] = $this->cols;
		$param['sp2d'] = $this->Sp2d_model->get(null, $id_kontrak_pusat);
        $param['total_unit'] = $this->Baphp_model->total_unit(null, $id_kontrak_pusat);
        $param['total_nilai'] = $this->Sp2d_model->total_nilai($id_kontrak_pusat);

        $data = array(
			'title' => 'DATA SURAT PERINTAH PENCAIRAN DANA (SP2D)',
            'content-path' => 'SP2D',
            'content' => $this->load->view('sp2d/index', $param, TRUE),
        );
        $this->parser->parse('default_template', $data);
	}

    public function index_json()
    { 
		$bolehtambah = 0;
		$bolehedit = 0;
        $bolehhapus = 0;

        if($this->session->userdata('logged_in')->crud_items){
			foreach($this->session->userdata('logged_in')->crud_items as $crud){
				if(rtrim($crud->crud_action) == 'TAMBAH DATA')
				$bolehtambah = 1;
				if(rtrim($crud->crud_action) == 'EDIT DATA')
				$bolehedit = 1;
				if(rtrim($crud->crud_action) == 'HAPUS DATA')
				$bolehhapus = 1;
			}			
		}
        
		$id_kontrak_pusat = $this->input->post('id_kontrak_pusat');
		$kontrak_pusat = $this->Kontrak_pusat_model->get($id_kontrak_pusat);
        $start = empty($this->input->post('start'))? 0:$this->input->post('start');
        $length = empty($this->input->post('length'))? null:$this->input->post('length');
        $order = empty($this->input->post('order')[0]['column'])? null:$this->input->post('order')[0]['column'];
        $dir = empty($this->input->post('order')[0]['dir'])? null:$this->input->post('order')[0]['dir'];
        
		$columns = array();
		foreach($this->cols as $key=>$val){
			array_push($columns,$val['column']);
		}

		$dbcolumns = array();
		foreach($this->cols as $key=>$val){
			array_push($dbcolumns,$val['dbcolumn']);
		}
     
        $totalData = $this->Sp2d_model->get(null,$id_kontrak_pusat);
        $totalData = count($totalData);
            
        $totalFiltered = $totalData; 

        //search data percolumn
        $filtercond = '';
        for($i=0;$i<count($columns);$i++){
            if(!empty($this->input->post('columns')[$i]['search']['value'])){
                $search = $this->input->post('columns')[$i]['search']['value'];
                $filtercond  .= " and ".$columns[$i]." LIKE '%".$search."%'"; 
            }  	
        }

		$search = $this->input->post('search')['value']; 
		$posts_all_search =  $this->Sp2d_model->get(null, $id_kontrak_pusat,null, null, null, null, $filtercond, $search);
        $totalFiltered = count($posts_all_search);
		$posts =  $this->Sp2d_model->get(null, $id_kontrak_pusat, $start, $length, $order, $dir, $filtercond, $search);

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData = array();
				foreach($this->cols as $val){
					$nestedData[$val['column']] = $post->{$val['column']};
				}
				if($post->tanggal > '1900-00-00'){
					$nestedData['tanggal'] = date('d-m-Y',strtotime($post->tanggal));
				}else{
					$nestedData['tanggal'] = '';
				}
				if($post->tanggal_spm > '1900-00-00'){
					$nestedData['tanggal_spm'] = date('d-m-Y',strtotime($post->tanggal_spm));
				}else{
					$nestedData['tanggal_spm'] = '';
				}
				//number format
				$nestedData['nilai_sebelum_pajak'] = number_format($nestedData['nilai_sebelum_pajak'],0);
				$nestedData['nilai_setelah_pajak'] = number_format($nestedData['nilai_setelah_pajak'],0);

				$tools =  "<a class='btn btn-xs btn-success btn-sm'><i class='glyphicon glyphicon-zoom-in' onclick='LoadData(".$post->id.")'></i></a>";
				//cek pencapaian termin before print
				$field_termin = strtolower(str_replace(' ','_',$post->keterangan));
				$jumlah_termin = $kontrak_pusat->jumlah_termin;
				if('termin_'.$jumlah_termin == $field_termin){
					//termin terakhir, harus 100%
					$addwhere = 'termin is not null';
					$total_unit = $this->Baphp_model->total_unit(null,$kontrak_pusat->id,$addwhere);
					$pencapaian_termin = $total_unit/$kontrak_pusat->jumlah_barang*100;
					if($pencapaian_termin >= 100){
						$tools .=  "<a class='btn btn-xs btn-warning btn-sm' href='".base_url('Sp2d/cetak_pengajuan?id_kontrak_pusat=').$id_kontrak_pusat."&keterangan=".$post->keterangan."'><i class='glyphicon glyphicon-print'></i></a>";
					}else{
						$tools .=  "<a class='btn btn-xs btn-secondary btn-sm' onclick='alert(\"Termin kontrak belum terpenuhi. Termin terakhir (100%). Alokasi: ".number_format($pencapaian_termin,2)."% \")' disabled><i class='glyphicon glyphicon-print'></i></a>";
					}
				}else{
					//termin sebelum terakhir, sesuai target terminnya
					$persen_termin = $kontrak_pusat->{$field_termin};
					$pencapaian_termin = $post->total_jumlah_termin_alokasi/$kontrak_pusat->jumlah_barang*100;
					if($pencapaian_termin >= $persen_termin){
						$tools .=  "<a class='btn btn-xs btn-warning btn-sm' href='".base_url('Sp2d/cetak_pengajuan?id_kontrak_pusat=').$id_kontrak_pusat."&keterangan=".$post->keterangan."'><i class='glyphicon glyphicon-print'></i></a>";
					}else{
						$tools .=  "<a class='btn btn-xs btn-secondary btn-sm' onclick='alert(\"Termin kontrak belum terpenuhi. Termin: ".$persen_termin."%. Alokasi: ".number_format($pencapaian_termin,2)."% \")' disabled><i class='glyphicon glyphicon-print'></i></a>";
					}
				}
				
				if($bolehedit)
                    $tools .= "<a class='btn btn-xs btn-primary btn-sm' href='".base_url('Sp2d/edit?id=').$post->id."'><i class='glyphicon glyphicon-pencil'></i></a>
                <a class='btn btn-xs btn-info btn-sm' data-href='#' data-toggle='modal' data-record-title='".$post->id."' data-target='#upload-modal' data-record-id='".$post->id."'><i class='glyphicon glyphicon-open-file'></i></a>";
                if($bolehhapus)
                    $tools .= "<a class='btn btn-xs btn-danger btn-sm' data-href='".base_url('Sp2d/destroy?id=').$post->id."' data-toggle='modal' data-record-title='".$post->id."' data-target='#confirm-delete'><i class='glyphicon glyphicon-trash'></i></a>";
                $nestedData['tools'] = $tools;
               
                $data[] = $nestedData;

            }
        }
        else{
        	$data = array();
        }

		$json_data = array(
            "draw"            => $_POST['draw'],
			"recordsTotal"    => intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data"            => $data
		);

        echo json_encode($json_data);
    }

    public function show($id)
    {
		$sp2d = $this->Sp2d_model->Get($id)[0];

		$this->load->library('parser');
		$data = array(
	        'title' => 'DATA SURAT PERINTAH PENCAIRAN DANA (SP2D)',
	        'content-path' => 'SP2D',
	        'content' => $this->load->view('sp2d/index', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

    public function get_json()
    {
        $id = $this->input->get('id');
		$data = $this->Sp2d_model->get($id);
		if($data->tanggal < '1900-00-00'){
			$data->tanggal = '';
		}else{
			$data->tanggal = date('d-m-Y',strtotime($data->tanggal));
		}
		if($data->tanggal_spm < '1900-00-00'){
			$data->tanggal_spm = '';
		}else{
			$data->tanggal_spm = date('d-m-Y',strtotime($data->tanggal_spm));
		}
		$hasil = json_encode($data);
		header('HTTP/1.1: 200');
		header('Status: 200');
		header('Content-Length: '.strlen($hasil));
		exit($hasil);
    }
    
    public function create()
	{
		$this->load->library('parser');
		$param['cols'] = $this->cols;
		$param['id_kontrak_pusat'] = $this->input->get('id_kontrak_pusat');
		$param['kontrak_pusat'] = $this->Kontrak_pusat_model->get($this->input->get('id_kontrak_pusat'));
		
		//cek termin yang sudah ada
		$termincek = $this->Sp2d_model->termincek(null,$param['id_kontrak_pusat']);
		$param['termin_exist'] = array();
		foreach($termincek as $val){
			$param['termin_exist'][] = $val->keterangan;
		}

		$data = array(
	        'title' => 'DATA SURAT PERINTAH PENCAIRAN DANA (SP2D)', 
	        'content-path' => 'SP2D / TAMBAH DATA',
	        'content' => $this->load->view('sp2d/add', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function store()
	{	
        $tanggal = $this->input->post('tanggal');
		if(!empty($tanggal)){
			$tanggal = DateTime::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');
		}
		$tanggal_spm = $this->input->post('tanggal_spm');
		if(!empty($tanggal_spm)){
			$tanggal_spm = DateTime::createFromFormat('d-m-Y', $tanggal_spm)->format('Y-m-d');
		}
		$id_kontrak_pusat = $this->input->post('id_kontrak_pusat');

		//cek termin yang sudah ada
		$termincek = $this->Sp2d_model->termincek($this->input->post('keterangan'),$id_kontrak_pusat);
		if(count($termincek)){
			$this->session->set_flashdata('error','SP2D termin ini sudah pernah dibuat.');
            redirect('Sp2d/create?id_kontrak_pusat='.$id_kontrak_pusat);
		}

		//cek limit nilai
		$nilai_sp = $this->input->post('nilai_sebelum_pajak');
		$kontrak_pusat = $this->Kontrak_pusat_model->get($id_kontrak_pusat);
		$total_nilai = $this->Sp2d_model->total_nilai($id_kontrak_pusat);
		if($total_nilai+$nilai_sp > $kontrak_pusat->nilai_barang){
			$this->session->set_flashdata('error','Nilai tidak dapat melebihi nilai kontrak.');
            redirect('Sp2d/create?id_kontrak_pusat='.$id_kontrak_pusat);
		}

        $data = array(
			'tanggal' => $tanggal,
			'tanggal_spm' => $tanggal_spm,
			'id_kontrak_pusat' => $id_kontrak_pusat,
            'created_by' => $this->session->userdata('logged_in')->id_pengguna,
            'created_at' => NOW,
        );

        foreach($this->cols as $key=>$val){
			if(!in_array($val['dbcolumn'], array('tanggal','tanggal_spm'))){
				$data[$val['dbcolumn']] = $this->input->post($val['column']);
			}
		}

        $this->Sp2d_model->store($data);
        $this->session->set_flashdata('info','Data inserted successfully.');
		redirect('Sp2d/index?id_kontrak_pusat='.$id_kontrak_pusat);
	}

	public function edit()
	{
		$id = $this->input->get('id');
		$param['cols'] = $this->cols;

		$this->load->library('parser');
        
		$param['sp2d'] = $this->Sp2d_model->get($id);
		$param['kontrak_pusat'] = $this->Kontrak_pusat_model->get($param['sp2d']->id_kontrak_pusat);

        $data = array(
	        'title' => 'DATA SURAT PERINTAH PENCAIRAN DANA (SP2D)',
	        'content-path' => 'SP2D / UBAH DATA',
	        'content' => $this->load->view('sp2d/edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function update()
	{	
		$id = $this->input->post('id');
		$sp2d = $this->Sp2d_model->get($id);
		$id_kontrak_pusat = $this->input->post('id_kontrak_pusat');

		$tanggal = $this->input->post('tanggal');
		if(!empty($tanggal)){
			$tanggal = DateTime::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');
		}
		$tanggal_spm = $this->input->post('tanggal_spm');
		if(!empty($tanggal_spm)){
			$tanggal_spm = DateTime::createFromFormat('d-m-Y', $tanggal_spm)->format('Y-m-d');
		}

		//cek limit nilai
		$nilai_sp = $this->input->post('nilai_sebelum_pajak');
		$kontrak_pusat = $this->Kontrak_pusat_model->get($id_kontrak_pusat);
		$total_nilai = $this->Sp2d_model->total_nilai($id_kontrak_pusat);
		if($total_nilai+$nilai_sp-$sp2d->nilai_sebelum_pajak > $kontrak_pusat->nilai_barang){
			$this->session->set_flashdata('error','Nilai tidak dapat melebihi nilai kontrak.');
            redirect('Sp2d/create?id_kontrak_pusat='.$id_kontrak_pusat);
		}

        $data = array(
			'tanggal' => $tanggal,
			'tanggal_spm' => $tanggal_spm,
			'id_kontrak_pusat' => $id_kontrak_pusat,
            'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
            'updated_at' => NOW,
		);
		
        foreach($this->cols as $key=>$val){
			if(!in_array($val['dbcolumn'], array('tanggal','tanggal_spm'))){
				$data[$val['dbcolumn']] = $this->input->post($val['column']);
			}
        }
        
        $this->Sp2d_model->update($id, $data);
        $this->session->set_flashdata('info','Data updated successfully.');
        redirect('Sp2d/index?id_kontrak_pusat='.$id_kontrak_pusat);
		
	}

	public function destroy()
	{	
        $id = $this->input->get('id');
		$sp2d = $this->Sp2d_model->get($id);
		$id_kontrak_pusat = $sp2d->id_kontrak_pusat;

		if($sp2d->nama_file != '' and $sp2d->nama_file != 'null' and !is_null($sp2d->nama_file) and $sp2d->nama_file != '[]')
			$images = json_decode($sp2d->nama_file);
		else
			$images = [];

		foreach($images as $image){
			unlink($this->config->item('doc_root').'/upload/sp2d/'.$image);	
		}

		$this->Sp2d_model->destroy($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('Sp2d/index?id_kontrak_pusat='.$id_kontrak_pusat);
	}

    public function export()
    {
        $columns = array();
		foreach($this->cols as $key=>$val){
			array_push($columns,$val['column']);
		}

        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $filtercond = '';
        for($i=0;$i<count($columns);$i++){
            if(!empty($this->input->post('columns')[$i]['search']['value'])){
                $search = $this->input->post('columns')[$i]['search']['value'];
                $filtercond  .= " and ".$columns[$i]." LIKE '%".$search."%'"; 
            }           
        }

        $data = array();
        if(empty($this->input->post('search')['value'])){            
            $data = $this->Sp2d_model->ExportAllForAjax($order, $dir, $filtercond);
        }
        else {
            $search = $this->input->post('search')['value']; 
            $data =  $this->Sp2d_model->ExportSearchAjax($search, $order, $dir, $filtercond);
        }

        $visible_columns = $this->input->post('visible_columns');
        $visible_header_columns = array();
        $this->xlsxwriter->writeSheetHeader('SP2D', $visible_header_columns, array('font-style'=>'bold'));
        
        foreach($data as $row) {
            $newRow = array();
            foreach($visible_columns as $key => $value) {
                $defaultValue = '';
                if(isset($row[$value['id']])) {
                    $defaultValue = $row[$value['id']];
                }

                switch($value['id']) {
                    case 'tanggal': 
                        $newRow[$key] = $row['tanggal'];
                        break;
                    default: 
                        $newRow[$key] = $defaultValue;
                }
            }
            $this->xlsxwriter->writeSheetRow('SP2D', $newRow);
        }
        
        $uniq_id = substr(md5(uniqid(rand(), true)), 0, 5);
        $file = "upload/BASTB App SP2D - $uniq_id.xlsx";
        $this->xlsxwriter->writeToFile($file);

        header('Content-Type: application/json');
        echo json_encode(array('filename' => base_url().'Sp2d/download?filename='.$file));
    }

    public function download()
    {
        if(isset($_GET['filename'])) {
            $file = $_GET['filename'];
            if(file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment; filename='.basename($file));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                readfile($file);
                unlink($file);
            }
        }
    }

    public function upload_file()
    {
		$id = $this->input->post('id_sp2d');

		$sp2d = $this->Sp2d_model->get($id);

		$kodefile_upload = strtotime(NOW);

		if($sp2d->nama_file != '' and $sp2d->nama_file != 'null' and !is_null($sp2d->nama_file) and $sp2d->nama_file != '[]')
			$nama_file = json_decode($sp2d->nama_file);
		else
			$nama_file = [];

		foreach($_FILES['file']['tmp_name'] as $key => $value) {
	        array_push($nama_file, $kodefile_upload.basename($_FILES['file']['name'][$key]));
	    }

        if(count($nama_file) > 10){
        	$this->session->set_flashdata('error','Jumlah file tidak boleh lebih dari 10. Upload dibatalkan.');
			exit('success');
        }
        else{

		    foreach($_FILES['file']['tmp_name'] as $key => $value) {
		        $tempFile = $_FILES['file']['tmp_name'][$key];
		        $targetFile =  $target_file = $this->config->item('doc_root').'/upload/sp2d/'.$kodefile_upload.basename($_FILES['file']['name'][$key]);
	        	move_uploaded_file($tempFile, $target_file);

		    }

		    $nama_file = json_encode($nama_file);
        	$data = array(
				'nama_file' => $nama_file,
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->Sp2d_model->update($id, $data);

			$this->session->set_flashdata('info','File uploaded successfully.');
			exit('success');
        }
    }
    
    public function remove_file()
    {
		$id = $this->input->get('id_sp2d');
		$urutanfile = $this->input->get('urutanfile');

		$sp2d = $this->Sp2d_model->get($id);

		if($sp2d->nama_file != '' and $sp2d->nama_file != 'null' and !is_null($sp2d->nama_file) and $sp2d->nama_file != '[]')
			$images = json_decode($sp2d->nama_file);
		else
			$images = [];

		$nama_file = $images[$urutanfile];
		unlink($this->config->item('doc_root').'/upload/sp2d/'.$nama_file);	

		$new_nama_file = [];
		foreach($images as $image){
			if($image != $nama_file){
				array_push($new_nama_file, $image);
			}
		}

		$nama_file = json_encode($new_nama_file);

		if($nama_file == '[]' or $nama_file == NULL or $nama_file == 'null' or is_null($nama_file)){
			$nama_file = '';
		}

		$data = array(
			'nama_file' => $nama_file,
			'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
			'updated_at' => NOW,
		);
		$this->Sp2d_model->update($id, $data);

		$this->session->set_flashdata('info','File berhasil dihapus.');
		exit('success');
	}

    public function cetak_pengajuan()
	{
		$cols = array(
			array("column" => "no_baphp", "caption" => "Nomor BAPHP", "dbcolumn" => "no_baphp"),
			array("column" => "tanggal_baphp", "caption" => "Tanggal BAPHP", "dbcolumn" => "tanggal_baphp"),
			array("column" => "provinsi_penerima", "caption" => "Provinsi Penerima", "dbcolumn" => "provinsi_penerima"),
			array("column" => "kabupaten_penerima", "caption" => "Kabupaten Penerima", "dbcolumn" => "kabupaten_penerima"),
			array("column" => "unit", "caption" => "Unit", "dbcolumn" => "unit"),
			array("column" => "nilai", "caption" => "Nilai", "dbcolumn" => "nilai"),
			array("column" => "dokumen", "caption" => "Dokumen", "dbcolumn" => "dokumen"),
		);

		$this->load->library('parser');
		$id_kontrak_pusat = $this->input->get('id_kontrak_pusat');
		$param['cols'] = $cols;
        $param['keterangan'] = $this->input->get('keterangan');
		$param['kontrak_pusat'] = $this->Kontrak_pusat_model->get($id_kontrak_pusat);
		$param['sp2d'] = $this->Sp2d_model->get(null, $id_kontrak_pusat);
        $param['total_unit'] = $this->Baphp_model->total_unit(null, $id_kontrak_pusat);
        $param['total_nilai'] = $this->Baphp_model->total_nilai(null, $id_kontrak_pusat);

        if(!empty($id_kontrak_pusat)){
            $param['kontrak_pusat'] = $this->Kontrak_pusat_model->get($id_kontrak_pusat);
		}
        $data = array(
			'title' => 'Cetak Pengajuan SP2D',
            'content-path' => 'SP2D / Cetak Pengajuan',
            'content' => $this->load->view('sp2d/index_cetak_pengajuan', $param, TRUE),
        );
        $this->parser->parse('default_template', $data);
	}

    public function cetak_pengajuan_json()
    { 
		$cols = array(
			array("column" => "no_baphp", "caption" => "Nomor BAPHP", "dbcolumn" => "no_baphp"),
			array("column" => "tanggal_baphp", "caption" => "Tanggal BAPHP", "dbcolumn" => "tanggal_baphp"),
			array("column" => "provinsi_penerima", "caption" => "Provinsi Penerima", "dbcolumn" => "provinsi_penerima"),
			array("column" => "kabupaten_penerima", "caption" => "Kabupaten Penerima", "dbcolumn" => "kabupaten_penerima"),
			array("column" => "unit", "caption" => "Unit", "dbcolumn" => "unit"),
			array("column" => "nilai", "caption" => "Nilai", "dbcolumn" => "nilai"),
			array("column" => "dokumen", "caption" => "Dokumen", "dbcolumn" => "dokumen"),
		);

		$bolehtambah = 0;
		$bolehedit = 0;
        $bolehhapus = 0;

        if($this->session->userdata('logged_in')->crud_items){
			foreach($this->session->userdata('logged_in')->crud_items as $crud){
				if(rtrim($crud->crud_action) == 'TAMBAH DATA')
				$bolehtambah = 1;
				if(rtrim($crud->crud_action) == 'EDIT DATA')
				$bolehedit = 1;
				if(rtrim($crud->crud_action) == 'HAPUS DATA')
				$bolehhapus = 1;
			}			
		}
        
		$id_kontrak_pusat = $this->input->post('id_kontrak_pusat');
		$keterangan = $this->input->post('keterangan');
        $start = empty($this->input->post('start'))? 0:$this->input->post('start');
        $length = empty($this->input->post('length'))? null:$this->input->post('length');
        $order = empty($this->input->post('order')[0]['column'])? null:$this->input->post('order')[0]['column'];
        $dir = empty($this->input->post('order')[0]['dir'])? null:$this->input->post('order')[0]['dir'];
        
		$columns = array();
		foreach($cols as $key=>$val){
			array_push($columns,$val['column']);
		}

		$dbcolumns = array();
		foreach($cols as $key=>$val){
			array_push($dbcolumns,$val['dbcolumn']);
		}
     
        $totalData = $this->Sp2d_model->get_cetak_pengajuan(null,array('id_kontrak_pusat'=>$id_kontrak_pusat));
        $totalData = count($totalData);
            
        $totalFiltered = $totalData; 

        //search data percolumn
        $filtercond = '';
        for($i=0;$i<count($columns);$i++){
            if(!empty($this->input->post('columns')[$i]['search']['value'])){
                $search = $this->input->post('columns')[$i]['search']['value'];
                $filtercond  .= " and ".$columns[$i]." LIKE '%".$search."%'"; 
            }  	
        }

		$search = $this->input->post('search')['value']; 
		$posts_all_search =  $this->Sp2d_model->get_cetak_pengajuan(null, array('id_kontrak_pusat'=>$id_kontrak_pusat,'keterangan'=>$keterangan,'filter'=>$filtercond,'search'=>$search));
        $totalFiltered = count($posts_all_search);
		$posts =  $this->Sp2d_model->get_cetak_pengajuan(null, array('id_kontrak_pusat'=>$id_kontrak_pusat,'keterangan'=>$keterangan, 'start'=>$start, 'length'=>$length, 'order'=>$order, 'dir'=>$dir,'filter'=>$filtercond,'search'=>$search));

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData = array();
				foreach($cols as $val){
					$nestedData[$val['column']] = $post->{$val['column']};
				}
				$nestedData['nilai'] = number_format($post->nilai,0);
				$nestedData['dokumen'] = ($nestedData['dokumen']=='V')? '<a class="btn btn-success">TERSEDIA</a>':'<a class="btn btn-danger">TIDAK ADA</a>';
				$nestedData['tanggal_baphp'] = date('d-m-Y',strtotime($post->tanggal_baphp));

                $data[] = $nestedData;

            }
        }
        else{
        	$data = array();
        }

		$json_data = array(
            "draw"            => $_POST['draw'],
			"recordsTotal"    => intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data"            => $data
		);

        echo json_encode($json_data);
	}
	
	public function cetak_pengajuan_pdf(){
		$cols = array(
			array("column" => "no_baphp", "caption" => "Nomor BAPHP", "dbcolumn" => "no_baphp"),
			array("column" => "tanggal_baphp", "caption" => "Tanggal BAPHP", "dbcolumn" => "tanggal_baphp"),
			array("column" => "provinsi_penerima", "caption" => "Provinsi Penerima", "dbcolumn" => "provinsi_penerima"),
			array("column" => "kabupaten_penerima", "caption" => "Kabupaten Penerima", "dbcolumn" => "kabupaten_penerima"),
			array("column" => "unit", "caption" => "Unit", "dbcolumn" => "unit"),
			array("column" => "nilai", "caption" => "Nilai", "dbcolumn" => "nilai"),
			array("column" => "dokumen", "caption" => "Dokumen", "dbcolumn" => "dokumen"),
		);

		$this->load->library('parser');
		$id_kontrak_pusat = $this->input->get('id_kontrak_pusat');
		$keterangan = $this->input->get('keterangan');
		$param['cols'] = $cols;
		$param['sp2d'] = $this->Sp2d_model->get(null, $id_kontrak_pusat);
        $param['total_unit'] = $this->Baphp_model->total_unit(null, $id_kontrak_pusat);
		$param['total_nilai'] = $this->Baphp_model->total_nilai(null, $id_kontrak_pusat);
        $param['sp2d'] = $this->Sp2d_model->get_cetak_pengajuan(null,array('id_kontrak_pusat'=>$id_kontrak_pusat,'keterangan'=>$keterangan));		
// var_dump($param['sp2d']);exit();
        if(!empty($id_kontrak_pusat)){
            $param['kontrak_pusat'] = $this->Kontrak_pusat_model->get($id_kontrak_pusat);
		}

		$dompdf = new Dompdf();
		$html = $this->load->view('sp2d/index_cetak_pengajuan_print', $param, TRUE);
		$dompdf->loadHtml($html);
		// $dompdf->loadHtml('hello world');
		$dompdf->setPaper('A4', 'landscape');
		$dompdf->render();
	  	$dompdf->stream("Pengajuan_SP2D.pdf");
	}
}
	