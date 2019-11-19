<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_pemanfaatan extends CI_Controller {
	public $cols = array(
		array("column" => "periode_mulai", "caption" => "Periode Tanggal Mulai Digunakan", "dbcolumn" => "periode_mulai"),
		array("column" => "periode_selesai", "caption" => "Periode Tanggal Selesai Digunakan", "dbcolumn" => "periode_selesai"),
		array("column" => "total_area", "caption" => "Total Area Yang Dikerjakan (Ha)", "dbcolumn" => "total_area"),
		array("column" => "kondisi", "caption" => "Kondisi Alsintan Saat Ini", "dbcolumn" => "kondisi"),
		array("column" => "perawatan", "caption" => "Perawatan Yang Telah Dilakukan", "dbcolumn" => "perawatan"),
		array("column" => "keterangan", "caption" => "Keterangan", "dbcolumn" => "keterangan"),
		array("column" => "tanggal_laporan", "caption" => "Tanggal Kirim Laporan", "dbcolumn" => "tanggal_laporan"),
		array("column" => "pengguna", "caption" => "Pengguna & Lokasi", "dbcolumn" => "pengguna"),
	);

    function __construct()
    {
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('Laporan_pemanfaatan_model');
		$this->load->model('Bastb_pusat_model');
        $this->load->helper('url');
		$this->load->library('xlsxwriter');
	}

    public function index($id = null)
    {
		$param['cols'] = $this->cols;
		
        $id_bastb_pusat = $this->input->get('id_bastb_pusat');
        if(!empty($id_bastb_pusat)){
			$param['bastb_pusat'] = $this->Bastb_pusat_model->get($id_bastb_pusat);
			if(empty($param['bastb_pusat'])) show_404();
			$param['Laporan_pemanfaatan'] = $this->Laporan_pemanfaatan_model->get(null,array('id_bastb_pusat'=>$id_bastb_pusat));
		}
        $this->load->library('parser');
	
        $data = array(
            'title' => 'Data Laporan Pemanfaatan Kontrak Pusat',
            'content-path' => 'PENGADAAN PUSAT / KONTRAK PUSAT / Laporan Pemanfaatan',
            'content' => $this->load->view('laporan-pemanfaatan/index', $param, TRUE),
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
        $start = empty($this->input->post('start'))? 0:$this->input->post('start');
        $length = empty($this->input->post('length'))? null:$this->input->post('length');
        $order = empty($this->input->post('order')[0]['column'])? null:$this->input->post('order')[0]['column'];
        $dir = empty($this->input->post('order')[0]['dir'])? null:$this->input->post('order')[0]['dir'];
        
		$id_bastb_pusat = $this->input->post('id_bastb_pusat');

		$columns = array();
		foreach($this->cols as $key=>$val){
			array_push($columns,$val['column']);
		}

		$dbcolumns = array();
		foreach($this->cols as $key=>$val){
			array_push($dbcolumns,$val['dbcolumn']);
		}
	 
        if(!empty($id_bastb_pusat)){		
			$totalData = $this->Laporan_pemanfaatan_model->get(null,array('id_bastb_pusat'=>$id_bastb_pusat));
		}
		$totalData = count($totalData);
            
        $totalFiltered = $totalData; 

        //search data percolumn
        $filtercond = '';
        for($i=0;$i<count($columns);$i++){
            if(!empty($this->input->post('columns')[$i]['search']['value'])){
                $search = $this->input->post('columns')[$i]['search']['value'];
                $filtercond  .= " and ".$dbcolumns[$i]." LIKE '%".$search."%'"; 
            }
        }

		$search = $this->input->post('search')['value']; 
        if(!empty($id_bastb_pusat)){		
			$posts_all_search =  $this->Laporan_pemanfaatan_model->get(null, array('id_bastb_pusat'=>$id_bastb_pusat, 'filter'=>$filtercond, 'search'=>$search));
			$posts =  $this->Laporan_pemanfaatan_model->get(null, array('id_bastb_pusat'=>$id_bastb_pusat, 'start'=>$start, 'length'=>$length, 'order'=>$order, 'dir'=>$dir, 'filter'=>$filtercond, 'search'=>$search));
		}
        $totalFiltered = count($posts_all_search);

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();
				foreach($this->cols as $val){
					$nestedData[$val['column']] = $post->{$val['column']};
				}
				$nestedData['periode_mulai'] = date('d-m-Y', strtotime($nestedData['periode_mulai']));
				$nestedData['periode_selesai'] = date('d-m-Y', strtotime($nestedData['periode_selesai']));
				$nestedData['periode_mulai'] = $nestedData['periode_mulai'].' s/d '.$nestedData['periode_selesai'];
				unset($nestedData['periode_selesai']);
				$nestedData['tanggal_laporan'] = date('d-m-Y', strtotime($nestedData['tanggal_laporan']));

				$tools = '<div class="dropdown">
				<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				-- Pilih menu --
				</button>
				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					<a class="dropdown-item" href="#" data-href="#" data-toggle="modal" data-record-title="'.$post->id.'" data-target="#upload-modal" data-record-id="'.$post->id.'">Upload Dokumen</a>
					<a class="dropdown-item" href="#" onclick="LoadData('.$post->id.')">Lihat Data</a>';
				if($bolehedit)
					$tools .= '<a class="dropdown-item" href="'.base_url('Laporan_pemanfaatan/edit?id='.$post->id).'">Ubah Data</a>';
				if($bolehhapus)
					$tools .= '<a class="dropdown-item"  href="#" data-toggle="modal" data-record-title="'.$post->id.'" data-target="#confirm-delete" data-href="'.base_url('Laporan_pemanfaatan/destroy/').$post->id.'">Hapus Data</a>';
				$tools .= '</div>
				</div>';
				
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
		$alokasi_pusat = $this->Alokasi_pusat_model->get($id);;
		$bastb_pusat = $this->Bastb_pusat_model->get($alokasi_pusat->id_bastb_pusat);;
		$param['bastb_pusat'] = $bastb_pusat;

		$this->load->library('parser');
		$data = array(
	        'title' => 'Data Laporan Pemanfaatan Kontrak Pusat',
	        'content-path' => 'PENGADAAN PUSAT / KONTRAK PUSAT / DATA Laporan Pemanfaatan',
	        'content' => $this->load->view('Laporan-pemanfaatan/index', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

    public function get_json()
    {
		$id = $this->input->get('id');

		$data = $this->Laporan_pemanfaatan_model->get($id);
		if($data->tanggal_laporan < '1900-00-00'){
			$data->tanggal_laporan = '';
		}else{
			$data->tanggal_laporan = date('d-m-Y',strtotime($data->tanggal_laporan));
		}
		if($data->periode_mulai < '1900-00-00'){
			$data->periode_mulai = '';
		}else{
			$data->periode_mulai = date('d-m-Y',strtotime($data->periode_mulai));
		}
		if($data->periode_selesai < '1900-00-00'){
			$data->periode_selesai = '';
		}else{
			$data->periode_selesai = date('d-m-Y',strtotime($data->periode_selesai));
		}
		$data->periode_mulai = $data->periode_mulai.' - '.$data->periode_selesai;
		unset($data->periode_selesai);
		$hasil = json_encode($data);
		header('HTTP/1.1: 200');
		header('Status: 200');
		header('Content-Length: '.strlen($hasil));
		exit($hasil);
    }
    
    public function create()
	{
		$param['cols'] = $this->cols;

		$id_bastb_pusat = $this->input->get('id_bastb_pusat');
		if(!empty($id_bastb_pusat)){
			$param['bastb_pusat'] = $this->Bastb_pusat_model->get($id_bastb_pusat);
			if(empty($param['bastb_pusat'])) show_404();
			$param['id_bastb_pusat'] = $id_bastb_pusat;
		}
		
		$this->load->library('parser');
		$this->load->model('ProvinsiModel');

		$param['provinsi'] = $this->ProvinsiModel->GetAll();

		$data = array(
	        'title' => 'Input Laporan Pemanfaatan Kontrak Pusat',
	        'content-path' => 'PENGADAAN PUSAT / KONTRAK PUSAT / DATA Laporan Pemanfaatan / TAMBAH DATA',
	        'content' => $this->load->view('laporan-pemanfaatan/add', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function store()
	{	
		$data = array(
			'periode_mulai' => DateTime::createFromFormat('d-m-Y', $this->input->post('periode_mulai'))->format('Y-m-d'),
			'periode_selesai' => DateTime::createFromFormat('d-m-Y', $this->input->post('periode_selesai'))->format('Y-m-d'),
			'tanggal_laporan' => DateTime::createFromFormat('d-m-Y', $this->input->post('tanggal_laporan'))->format('Y-m-d'),
			'created_by' => $this->session->userdata('logged_in')->id_pengguna,
			'created_at' => NOW,
		);
		if(!empty($this->input->post('id_bastb_pusat'))){
			$data['id_bastb_pusat'] = $this->input->post('id_bastb_pusat');
		}
		
		foreach($this->cols as $key=>$val){
			if(!in_array($val['dbcolumn'], array('periode_mulai','periode_selesai','tanggal_laporan'))){
				$data[$val['dbcolumn']] = $this->input->post($val['column']);
			}
		}
		$data['total_area'] = str_replace(',','.',$data['total_area']);
		
		$this->Laporan_pemanfaatan_model->store($data);
		$this->session->set_flashdata('info','Data inserted successfully.');
		if(!empty($this->input->post('id_bastb_pusat'))){
			redirect('Laporan_pemanfaatan/index?id_bastb_pusat='.$this->input->post('id_bastb_pusat'));
		}		
	}

	public function edit()
	{
		$id = $this->input->get('id');
		$param['cols'] = $this->cols;

		$param['laporan_pemanfaatan'] = $this->Laporan_pemanfaatan_model->get($id);
		if(!empty($param['laporan_pemanfaatan']->id_bastb_pusat)){
			$param['bastb_pusat'] = $this->Bastb_pusat_model->get($param['laporan_pemanfaatan']->id_bastb_pusat);
		}

		$this->load->library('parser');

		$data = array(
	        'title' => 'Data Laporan Pemanfaatan Kontrak Pusat',
	        'content-path' => 'PENGADAAN PUSAT / KONTRAK PUSAT / DATA Laporan Pemanfaatan / UBAH DATA',
	        'content' => $this->load->view('laporan-pemanfaatan/edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function update()
	{	
		$id = $this->input->post('id');
		$periode_mulai = DateTime::createFromFormat('d-m-Y', $this->input->post('periode_mulai'))->format('Y-m-d');
		$periode_selesai = DateTime::createFromFormat('d-m-Y', $this->input->post('periode_selesai'))->format('Y-m-d');
		$tanggal_laporan = DateTime::createFromFormat('d-m-Y', $this->input->post('tanggal_laporan'))->format('Y-m-d');
		$laporan_pemanfaatan = $this->Laporan_pemanfaatan_model->get($id);
		$data = array(
			'periode_mulai' => $periode_mulai,
			'periode_selesai' => $periode_selesai,
			'tanggal_laporan' => $tanggal_laporan,
			'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
			'updated_at' => NOW,
		);

		foreach($this->cols as $key=>$val){
			if(!in_array($val['dbcolumn'], array('periode_mulai','periode_selesai','tanggal_laporan'))){
				$data[$val['dbcolumn']] = $this->input->post($val['column']);
			}
		}
		$data['total_area'] = str_replace(',','.',$data['total_area']);

		$this->Laporan_pemanfaatan_model->update($id, $data);
		$this->session->set_flashdata('info','Data updated successfully.');
		if(!empty($laporan_pemanfaatan->id_bastb_pusat)){
			redirect('Laporan_pemanfaatan/index?id_bastb_pusat='.$laporan_pemanfaatan->id_bastb_pusat);
		}
	}

	public function destroy($id)
	{	
		$laporan_pemanfaatan = $this->Laporan_pemanfaatan_model->get($id);
		$id_bastb_pusat = $laporan_pemanfaatan->id_bastb_pusat;

		if($laporan_pemanfaatan->nama_file != '' and $laporan_pemanfaatan->nama_file != 'null' and !is_null($laporan_pemanfaatan->nama_file) and $laporan_pemanfaatan->nama_file != '[]')
			$images = json_decode($laporan_pemanfaatan->nama_file);
		else
			$images = [];

		foreach($images as $image){
			unlink($this->config->item('doc_root').'/upload/laporan_pemanfaatan/'.$image);	
		}

		$this->Laporan_pemanfaatan_model->destroy($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('Laporan_pemanfaatan/index?id_bastb_pusat='.$id_bastb_pusat);
		
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
            $data = $this->Laporan_pemanfaatan_model->ExportAllForAjax($order, $dir, $filtercond);
        }
        else {
            $search = $this->input->post('search')['value']; 
            $data =  $this->Laporan_pemanfaatan_model->ExportSearchAjax($search, $order, $dir, $filtercond);
        }

        $visible_columns = $this->input->post('visible_columns');
        $visible_header_columns = array();
        foreach($visible_columns as $value) {
            switch($value['title']) {
				case 'Tahun Anggaran':
				case 'Alokasi_persediaan': 
					$visible_header_columns[$value['title']] = 'integer';
					break;
                case 'Nilai (Rp)': 
                case 'Harga Satuan (Rp)': 
					$visible_header_columns[$value['title']] = '#,##0';
					break;
				default :
					$visible_header_columns[$value['title']] = 'string';
			}
        }
        $this->xlsxwriter->writeSheetHeader('Laporan Pemanfaatan Pusat', $visible_header_columns, array('font-style'=>'bold'));
        
        foreach($data as $row) {
            $newRow = array();
            foreach($visible_columns as $key => $value) {
                $defaultValue = '';
                if(isset($row[$value['id']])) {
                    $defaultValue = $row[$value['id']];
                }

                $jumlahBarang = 0;
                $nilaiBarang = 0;
                $hargaSatuan = 0;

                if($jumlahBarang > 0) {
                    $hargaSatuan = round($nilaiBarang / $jumlahBarang);
                }

                switch($value['id']) {
                    case 'tahun_anggaran': 
                        $newRow[$key] = $row['tahun_anggaran'];
                        break;
                    case 'periode':
                        $newRow[$key] = $row['periode_mulai'].' s/d '.$row['periode_selesai'];
                        break;
                    case 'Alokasi_persediaan': 
                        $newRow[$key] = $jumlahBarang;
                        break;
                    case 'nilai_barang': 
                        $newRow[$key] = $nilaiBarang;
                        break;
                    case 'harga_satuan': 
                        $newRow[$key] = $hargaSatuan;
                        break;
                    case 'nama_penyedia': 
                        $newRow[$key] = $row['nama_penyedia_pusat'];
                        break;
                    default: 
                        $newRow[$key] = $defaultValue;
                }
            }
            $this->xlsxwriter->writeSheetRow('Laporan Pemanfaatan Pusat', $newRow);
        }
        
        $uniq_id = substr(md5(uniqid(rand(), true)), 0, 5);
        $file = "upload/BASTB App Data Laporan Pemanfaatan Kontrak Pusat - $uniq_id.xlsx";
        $this->xlsxwriter->writeToFile($file);

        header('Content-Type: application/json');
        echo json_encode(array('filename' => base_url().'Laporan_pemanfaatan/download?filename='.$file));
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
		$id = $this->input->post('id');

		$laporan_pemanfaatan = $this->Laporan_pemanfaatan_model->get($id);

		$kodefile_upload = strtotime(NOW);

		if($laporan_pemanfaatan->nama_file != '' and $laporan_pemanfaatan->nama_file != 'null' and !is_null($laporan_pemanfaatan->nama_file) and $laporan_pemanfaatan->nama_file != '[]')
			$nama_file = json_decode($laporan_pemanfaatan->nama_file);
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
		        $targetFile =  $target_file = $this->config->item('doc_root').'/upload/laporan_pemanfaatan/'.$kodefile_upload.basename($_FILES['file']['name'][$key]);
	        	move_uploaded_file($tempFile, $target_file);

		    }

		    $nama_file = json_encode($nama_file);
        	$data = array(
				'nama_file' => $nama_file,
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->Laporan_pemanfaatan_model->update($id, $data);

			$this->session->set_flashdata('info','File uploaded successfully.');
			exit('success');
        }
    }
    
    public function remove_file()
    {
		$id = $this->input->get('id');
		$urutanfile = $this->input->get('urutanfile');

		$laporan_pemanfaatan = $this->Laporan_pemanfaatan_model->get($id);

		if($laporan_pemanfaatan->nama_file != '' and $laporan_pemanfaatan->nama_file != 'null' and !is_null($laporan_pemanfaatan->nama_file) and $laporan_pemanfaatan->nama_file != '[]')
			$images = json_decode($laporan_pemanfaatan->nama_file);
		else
			$images = [];

		$nama_file = $images[$urutanfile];
		unlink($this->config->item('doc_root').'/upload/laporan_pemanfaatan/'.$nama_file);	

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
		$this->Laporan_pemanfaatan_model->update($id, $data);

		$this->session->set_flashdata('info','File berhasil dihapus.');
		exit('success');
	}
}
	