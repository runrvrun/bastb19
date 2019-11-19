<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ongkir_persediaan extends CI_Controller {
    public $cols = array(
		array("column" => "id_kontrak_ongkir", "caption" => "Nomor Kontrak Ongkir", "dbcolumn" => "id_kontrak_ongkir"),
	  	array("column" => "ongkir", "caption" => "Nilai Ongkir", "dbcolumn" => "ongkir"),
	  	array("column" => "nomor_surat_permohonan", "caption" => "Nomor Surat Permohonan", "dbcolumn" => "nomor_surat_permohonan"),
	  	array("column" => "tanggal_surat_permohonan", "caption" => "Tanggal Surat Permohonan", "dbcolumn" => "tanggal_surat_permohonan"),
	  	array("column" => "nomor_surat_ke_penyedia", "caption" => "Nomor Surat ke Penyedia", "dbcolumn" => "nomor_surat_ke_penyedia"),
	  	array("column" => "tanggal_surat_ke_penyedia", "caption" => "Tanggal Surat ke Penyedia", "dbcolumn" => "tanggal_surat_ke_penyedia"),
	  	array("column" => "nomor_surat_ke_dinas", "caption" => "Nomor Surat ke Dinas", "dbcolumn" => "nomor_surat_ke_dinas"),
	  	array("column" => "tanggal_surat_ke_dinas", "caption" => "Tanggal Surat ke Dinas", "dbcolumn" => "tanggal_surat_ke_dinas"),
	  	array("column" => "keterangan", "caption" => "Keterangan", "dbcolumn" => "keterangan"),
	);
	// public $cols = array(
	// 	array("column" => "nomor", "caption" => "No. Kontrak", "dbcolumn" => "tb_kontrak_ongkir.nomor"),
	// 	array("column" => "tanggal", "caption" => "Tanggal", "dbcolumn" => "tb_kontrak_ongkir.tanggal"),
	// 	array("column" => "ekspedisi", "caption" => "Ekspedisi", "dbcolumn" => "ekspedisi"),
    //     array("column" => "kontrak_ongkir", "caption" => "Nilai (Rp)", "dbcolumn" => "tb_kontrak_ongkir.ongkir"),
    //     array("column" => "no_baphp", "caption" => "Nomor BAPHP Persediaan", "dbcolumn" => "tb_baphp_persediaan.no_baphp"),
    //     array("column" => "tanggal_baphp", "caption" => "Tanggal BAPHP Persediaan", "dbcolumn" => "tb_baphp_persediaan.tanggal"),
    //     array("column" => "nama_provinsi", "caption" => "Provinsi", "dbcolumn" => "nama_provinsi"),
    //     array("column" => "nama_kabupaten", "caption" => "Kabupaten", "dbcolumn" => "nama_kabupaten"),
    //     array("column" => "nama_barang", "caption" => "Nama Barang", "dbcolumn" => "tb_kontrak_pusat.nama_barang"),
    //     array("column" => "merk", "caption" => "Merk Barang", "dbcolumn" => "tb_kontrak_pusat.merk"),
    //     array("column" => "ongkir", "caption" => "Nilai (Rp)", "dbcolumn" => "tb_ongkir_persediaan.ongkir"),
    //     array("column" => "nomor_surat_permohonan", "caption" => "No Surat Permohonan", "dbcolumn" => "nomor_surat_permohonan"),
    //     array("column" => "tanggal_surat_permohonan", "caption" => "Tanggal Surat Permohonan", "dbcolumn" => "tanggal_surat_permohonan"),
    //     array("column" => "nomor_surat_ke_penyedia", "caption" => "No Surat Ke Penyedia", "dbcolumn" => "nomor_surat_ke_penyedia"),
    //     array("column" => "tanggal_surat_ke_penyedia", "caption" => "Tanggal Surat Ke Penyedia", "dbcolumn" => "tanggal_surat_ke_penyedia"),
    //     array("column" => "nomor_surat_ke_dinas", "caption" => "No Surat Ke Dinas", "dbcolumn" => "nomor_surat_ke_dinas"),
    //     array("column" => "tanggal_surat_ke_dinas", "caption" => "Tanggal Surat Ke Dinas", "dbcolumn" => "tanggal_surat_ke_dinas"),
    //     array("column" => "keterangan", "caption" => "Keterangan", "dbcolumn" => "keterangan"), 
	// );   

    function __construct()
    {
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('Baphp_persediaan_model');
		$this->load->model('Ongkir_persediaan_model');
		$this->load->model('Kontrak_ongkir_model');
        $this->load->helper('url');
		$this->load->library('xlsxwriter');
	}

    public function index($id = null)
    {
        $this->load->library('parser');
		$id_baphp_persediaan = $this->input->get('id_baphp_persediaan');
		$param['cols'] = $this->cols;
		$param['ongkir_persediaan'] = $this->Ongkir_persediaan_model->get();
        $param['total_nilai'] = $this->Ongkir_persediaan_model->total_nilai($id_baphp_persediaan);
        $param['baphp_persediaan'] = $this->Baphp_persediaan_model->get($id_baphp_persediaan);
        $param['id_baphp_persediaan'] = $id_baphp_persediaan;

        $data = array(
			'title' => 'Data Ongkir Kontrak Pusat',
            'content-path' => strtoupper('Alokasi / BAPHP Persediaan / Data Ongkir'),
            'content' => $this->load->view('ongkir-persediaan/index', $param, TRUE),
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
        
		$id_baphp_persediaan = $this->input->post('id_baphp_persediaan');
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
     
        $totalData = $this->Ongkir_persediaan_model->get(null,$id_baphp_persediaan);
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
		$posts_all_search =  $this->Ongkir_persediaan_model->get(null, $id_baphp_persediaan,null, null, null, null, $filtercond, $search);
        $totalFiltered = count($posts_all_search);
		$posts =  $this->Ongkir_persediaan_model->get(null, $id_baphp_persediaan, $start, $length, $order, $dir, $filtercond, $search);

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();
				foreach($this->cols as $val){
					$nestedData[$val['column']] = $post->{$val['column']};
				}
				$nestedData['id_kontrak_ongkir'] = $post->nomor;
				// $nestedData['kontrak_ongkir'] = number_format($post->kontrak_ongkir,0);
				$nestedData['ongkir'] = number_format($post->ongkir,0);
				if($post->tanggal_surat_permohonan > '1900-00-00'){
					$nestedData['tanggal_surat_permohonan'] = date('d-m-Y',strtotime($post->tanggal_surat_permohonan));
				}else{
					$nestedData['tanggal_surat_permohonan'] = '';
				}
				if($post->tanggal_surat_ke_penyedia > '1900-00-00'){
					$nestedData['tanggal_surat_ke_penyedia'] = date('d-m-Y',strtotime($post->tanggal_surat_ke_penyedia));
				}else{
					$nestedData['tanggal_surat_ke_penyedia'] = '';
				}
				if($post->tanggal_surat_ke_dinas > '1900-00-00'){
					$nestedData['tanggal_surat_ke_dinas'] = date('d-m-Y',strtotime($post->tanggal_surat_ke_dinas));
				}else{
					$nestedData['tanggal_surat_ke_dinas'] = '';
				}

                $tools =  "<a class='btn btn-xs btn-success btn-sm'><i class='glyphicon glyphicon-zoom-in' onclick='LoadData(".$post->id.")'></i></a>";
                if($bolehedit)
                    $tools .= "<a class='btn btn-xs btn-primary btn-sm' href='".base_url('Ongkir_persediaan/edit?id=').$post->id."'><i class='glyphicon glyphicon-pencil'></i></a>
                <a class='btn btn-xs btn-info btn-sm' data-href='#' data-toggle='modal' data-record-title='".$post->id."' data-target='#upload-modal' data-record-id='".$post->id."'><i class='glyphicon glyphicon-open-file'></i></a>";
                if($bolehhapus)
                    $tools .= "<a class='btn btn-xs btn-danger btn-sm' data-href='".base_url('Ongkir_persediaan/destroy?id=').$post->id."' data-toggle='modal' data-record-title='".$post->id."' data-target='#confirm-delete'><i class='glyphicon glyphicon-trash'></i></a>";
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
		$ongkir_persediaan = $this->Ongkir_persediaan_model->Get($id)[0];

		$this->load->library('parser');
		$data = array(
	        'title' => 'Data Ongkir',
	        'content-path' => 'Alokasi / BAPHP Persediaan / Data Ongkir',
	        'content' => $this->load->view('ongkir-persediaan/index', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

    public function get_json()
    {
        $id = $this->input->get('id');
		$data = $this->Ongkir_persediaan_model->get($id);
		if($data->tanggal_surat_permohonan < '1900-00-00'){
			$data->tanggal_surat_permohonan = '';
		}else{
			$data->tanggal_surat_permohonan = date('d-m-Y',strtotime($data->tanggal_surat_permohonan));
		}
		if($data->tanggal_surat_ke_penyedia < '1900-00-00'){
			$data->tanggal_surat_ke_penyedia = '';
		}else{
			$data->tanggal_surat_ke_penyedia = date('d-m-Y',strtotime($data->tanggal_surat_ke_penyedia));
		}
		if($data->tanggal_surat_ke_dinas < '1900-00-00'){
			$data->tanggal_surat_ke_dinas = '';
		}else{
			$data->tanggal_surat_ke_dinas = date('d-m-Y',strtotime($data->tanggal_surat_ke_dinas));
		}
		$data->ongkir = number_format($data->ongkir,0);
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
		$param['id_baphp_persediaan'] = $this->input->get('id_baphp_persediaan');
		$param['kontrak_ongkir'] = $this->Kontrak_ongkir_model->get();

		$data = array(
	        'title' => 'Input Data Ongkir', 
	        'content-path' => 'BAPHP Persediaan/ Data Ongkir / TAMBAH DATA',
	        'content' => $this->load->view('ongkir-persediaan/add', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function store()
	{	
		$id_baphp_persediaan = $this->input->post('id_baphp_persediaan');
        $tanggal_surat_permohonan = $this->input->post('tanggal_surat_permohonan');
		$tanggal_surat_permohonan = DateTime::createFromFormat('d-m-Y', $tanggal_surat_permohonan)->format('Y-m-d');
        $tanggal_surat_ke_penyedia = $this->input->post('tanggal_surat_ke_penyedia');
		$tanggal_surat_ke_penyedia = DateTime::createFromFormat('d-m-Y', $tanggal_surat_ke_penyedia)->format('Y-m-d');
        $tanggal_surat_ke_dinas = $this->input->post('tanggal_surat_ke_dinas');
		$tanggal_surat_ke_dinas = DateTime::createFromFormat('d-m-Y', $tanggal_surat_ke_dinas)->format('Y-m-d');

        $data = array(
			'tanggal_surat_permohonan' => $tanggal_surat_permohonan,
			'tanggal_surat_ke_penyedia' => $tanggal_surat_ke_penyedia,
			'tanggal_surat_ke_dinas' => $tanggal_surat_ke_dinas,
			'id_kontrak_ongkir' => $this->input->post('id_kontrak_ongkir'),
			'id_baphp_persediaan' => $id_baphp_persediaan,
            'created_by' => $this->session->userdata('logged_in')->id_pengguna,
            'created_at' => NOW,
        );

		//cek ongkir limit
		$ongkir = $this->input->post('ongkir');
		$kontrak_ongkir = $this->Kontrak_ongkir_model->get($this->input->post('id_kontrak_ongkir'));
		$total_ongkir = $this->Ongkir_persediaan_model->total_nilai($id_baphp_persediaan);
		// var_dump($kontrak_ongkir->ongkir);exit();
        if($total_ongkir + $ongkir > $kontrak_ongkir->ongkir){
			$this->session->set_flashdata('error','Nilai ongkir tidak dapat melebihi nilai di kontrak ongkir.');
			redirect('Ongkir_persediaan/create?id_baphp_persediaan='.$id_baphp_persediaan);
		}

        foreach($this->cols as $key=>$val){
			if(!in_array($val['dbcolumn'], array('id_kontrak_ongkir','tanggal_surat_permohonan','tanggal_surat_ke_penyedia','tanggal_surat_ke_dinas'))){
				$data[$val['dbcolumn']] = $this->input->post($val['column']);
			}
		}

        $this->Ongkir_persediaan_model->store($data);
        $this->session->set_flashdata('info','Data inserted successfully.');
		redirect('Ongkir_persediaan/index?id_baphp_persediaan='.$id_baphp_persediaan);
	}

	public function edit()
	{
		$id = $this->input->get('id');
		$param['cols'] = $this->cols;

		$this->load->library('parser');
        
		$param['ongkir_persediaan'] = $this->Ongkir_persediaan_model->get($id);
		$param['kontrak_ongkir'] = $this->Kontrak_ongkir_model->get();

        $data = array(
	        'title' => 'Ubah Data Ongkir',
	        'content-path' => 'Alokasi / BAPHP Persediaan / Data Ongkir / UBAH DATA',
	        'content' => $this->load->view('ongkir-persediaan/edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function update()
	{	
		$id = $this->input->post('id');
		$id_baphp_persediaan = $this->input->post('id_baphp_persediaan');
        $tanggal_surat_permohonan = $this->input->post('tanggal_surat_permohonan');
		$tanggal_surat_permohonan = DateTime::createFromFormat('d-m-Y', $tanggal_surat_permohonan)->format('Y-m-d');
        $tanggal_surat_ke_penyedia = $this->input->post('tanggal_surat_ke_penyedia');
		$tanggal_surat_ke_penyedia = DateTime::createFromFormat('d-m-Y', $tanggal_surat_ke_penyedia)->format('Y-m-d');
        $tanggal_surat_ke_dinas = $this->input->post('tanggal_surat_ke_dinas');
		$tanggal_surat_ke_dinas = DateTime::createFromFormat('d-m-Y', $tanggal_surat_ke_dinas)->format('Y-m-d');

		$ongkir_persediaan = $this->Ongkir_persediaan_model->get($id);
		
		//cek ongkir limit
		$ongkir = $this->input->post('ongkir');
		$kontrak_ongkir = $this->Kontrak_ongkir_model->get($this->input->post('id_kontrak_ongkir'));
		$total_ongkir = $this->Ongkir_persediaan_model->total_nilai(null,$kontrak_ongkir->id);
		// var_dump($ongkir_persediaan->ongkir);exit();
        if(($total_ongkir + $ongkir - $ongkir_persediaan->ongkir) > $kontrak_ongkir->ongkir){
			$this->session->set_flashdata('error','Nilai ongkir tidak dapat melebihi nilai di kontrak ongkir.');
			redirect('Ongkir_persediaan/edit?id='.$id);
		}

		$data = array(
			'tanggal_surat_permohonan' => $tanggal_surat_permohonan,
			'tanggal_surat_ke_penyedia' => $tanggal_surat_ke_penyedia,
			'tanggal_surat_ke_dinas' => $tanggal_surat_ke_dinas,
			'id_kontrak_ongkir' => $this->input->post('id_kontrak_ongkir'),
			'id_baphp_persediaan' => $id_baphp_persediaan,
            'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
            'updated_at' => NOW,
        );

        foreach($this->cols as $key=>$val){
			if(!in_array($val['dbcolumn'], array('tanggal','tanggal_surat_permohonan','tanggal_surat_ke_penyedia','tanggal_surat_ke_dinas'))){
				$data[$val['dbcolumn']] = $this->input->post($val['column']);
			}
        }
        
        $this->Ongkir_persediaan_model->update($id, $data);
        $this->session->set_flashdata('info','Data updated successfully.');
        redirect('Ongkir_persediaan/index?id_baphp_persediaan='.$id_baphp_persediaan);
		
	}

	public function destroy()
	{	
        $id = $this->input->get('id');
		$ongkir_persediaan = $this->Ongkir_persediaan_model->get($id);
		$id_baphp_persediaan = $ongkir_persediaan->id_baphp_persediaan;

		if($ongkir_persediaan->nama_file != '' and $ongkir_persediaan->nama_file != 'null' and !is_null($ongkir_persediaan->nama_file) and $ongkir_persediaan->nama_file != '[]')
			$images = json_decode($ongkir_persediaan->nama_file);
		else
			$images = [];

		foreach($images as $image){
			unlink($this->config->item('doc_root').'/upload/ongkir_persediaan'.$image);	
		}

		$this->Ongkir_persediaan_model->destroy($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('Ongkir_persediaan/index?id_baphp_persediaan='.$id_baphp_persediaan);
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
            $data = $this->Ongkir_persediaan_model->ExportAllForAjax($order, $dir, $filtercond);
        }
        else {
            $search = $this->input->post('search')['value']; 
            $data =  $this->Ongkir_persediaan_model->ExportSearchAjax($search, $order, $dir, $filtercond);
        }

        $visible_columns = $this->input->post('visible_columns');
        $visible_header_columns = array();
        $this->xlsxwriter->writeSheetHeader('Ongkir_persediaan', $visible_header_columns, array('font-style'=>'bold'));
        
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
            $this->xlsxwriter->writeSheetRow('Ongkir', $newRow);
        }
        
        $uniq_id = substr(md5(uniqid(rand(), true)), 0, 5);
        $file = "upload/BASTB App Ongkir - $uniq_id.xlsx";
        $this->xlsxwriter->writeToFile($file);

        header('Content-Type: application/json');
        echo json_encode(array('filename' => base_url().'Ongkir/download?filename='.$file));
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

		$ongkir_persediaan = $this->Ongkir_persediaan_model->get($id);

		$kodefile_upload = strtotime(NOW);

		if($ongkir_persediaan->nama_file != '' and $ongkir_persediaan->nama_file != 'null' and !is_null($ongkir_persediaan->nama_file) and $ongkir_persediaan->nama_file != '[]')
			$nama_file = json_decode($ongkir_persediaan->nama_file);
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
		        $targetFile =  $target_file = $this->config->item('doc_root').'/upload/ongkir_persediaan/'.$kodefile_upload.basename($_FILES['file']['name'][$key]);
	        	move_uploaded_file($tempFile, $target_file);

		    }

		    $nama_file = json_encode($nama_file);
        	$data = array(
				'nama_file' => $nama_file,
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->Ongkir_persediaan_model->update($id, $data);

			$this->session->set_flashdata('info','File uploaded successfully.');
			exit('success');
        }
    }
    
    public function remove_file()
    {
		$id = $this->input->get('id');
		$urutanfile = $this->input->get('urutanfile');

		$ongkir_persediaan = $this->Ongkir_persediaan_model->get($id);

		if($ongkir_persediaan->nama_file != '' and $ongkir_persediaan->nama_file != 'null' and !is_null($ongkir_persediaan->nama_file) and $ongkir_persediaan->nama_file != '[]')
			$images = json_decode($ongkir_persediaan->nama_file);
		else
			$images = [];

		$nama_file = $images[$urutanfile];
		unlink($this->config->item('doc_root').'/upload/ongkir_persediaan/'.$nama_file);	

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
		$this->Ongkir_persediaan_model->update($id, $data);

		$this->session->set_flashdata('info','File berhasil dihapus.');
		exit('success');
	}
}
	