<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KontrakProvinsi extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('KontrakProvinsiModel');
		$this->load->model('PenyediaProvinsiModel');
		$this->load->model('ProvinsiModel');
		$this->load->model('JenisBarangProvinsiModel');
	}

	public function index()
	{
		$this->load->library('parser');
		$param['kontrak_provinsi'] = $this->KontrakProvinsiModel->GetAll();
		$param['total_unit'] = $this->KontrakProvinsiModel->GetTotalUnit();
		$param['total_nilai'] = $this->KontrakProvinsiModel->GetTotalNilai();
		$param['total_kontrak'] = $this->KontrakProvinsiModel->GetTotalKontrak();
		$param['total_merk'] = $this->KontrakProvinsiModel->GetTotalMerk();
		$data = array(
	        'title' => 'Data Kontrak TP Provinsi',
	        'content-path' => 'PENGADAAN TP PROVINSI / DATA KONTRAK',
	        'content' => $this->load->view('kontrak-provinsi-index', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function AjaxGetAllData()
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

		$columns = array( 
            0 =>'tahun_anggaran', 
            1 =>'nama_provinsi',
            2 =>'nama_penyedia_provinsi',
            3 => 'no_kontrak',
            4 => 'periode_mulai',
            5 => 'nama_barang',
            6 => 'merk',
            7 => 'jumlah_barang',
            8 => 'nilai_barang',
            9 => 'nilai_barang',
            10 => 'jumlah_alokasi',
            11 => 'nilai_alokasi',
            12 => 'cukup/kurang',
            13 => 'tersedia/tdktersedia',
            14 => 'id',
        );

        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

		$totalData = $this->KontrakProvinsiModel->GetAllAjaxCount();
            
        $totalFiltered = $totalData; 
        
        //search data percolumn
        $filtercond = '';
        for($i=0;$i<count($columns);$i++){
        	if($i < 11){
        		if(!empty($this->input->post('columns')[$i]['search']['value'])){
	        		$search = $this->input->post('columns')[$i]['search']['value'];
	        		$filtercond  .= " and ".$columns[$i]." LIKE '%".$search."%'"; 
	        	}
        	}
        	else{
        		if($i==11){ //cukup/kurang
        			if($this->input->post('columns')[$i]['search']['value'] == 'CUKUP'){
        				$filtercond .= " and (ifnull(jumlah_barang, 0) - ifnull(jumlah_alokasi, 0) = 0 )";
        			}
        			else if($this->input->post('columns')[$i]['search']['value'] == 'KURANG'){
        				$filtercond .= " and (ifnull(jumlah_barang, 0) - ifnull(jumlah_alokasi, 0) != 0 ) ";
        			}
        		}
        		if($i==12){ 
        			if($this->input->post('columns')[$i]['search']['value'] == 'TERSEDIA'){
        				$filtercond .= " and (nama_file != '' and nama_file != 'null' and nama_file is not null)";
        			}
        			else if($this->input->post('columns')[$i]['search']['value'] == 'TDKTERSEDIA'){
        				$filtercond .= " and (nama_file = '' or nama_file = 'null' or nama_file is null ) ";
        			}
        		}
        		
        	}
        		        	
        }

		if(empty($this->input->post('search')['value']))
        {            
	        $posts = $this->KontrakProvinsiModel->GetAllForAjax($_POST['start'], $_POST['length'], $order, $dir, $filtercond);
            $totalFiltered = $this->KontrakProvinsiModel->GetFilterAjaxCount($filtercond);
        }
        else {
            $search = $this->input->post('search')['value']; 

            $posts =  $this->KontrakProvinsiModel->GetSearchAjax($_POST['start'], $_POST['length'], $search, $order, $dir, $filtercond);

            $totalFiltered = $this->KontrakProvinsiModel->GetSearchAjaxCount($search, $filtercond);
        }

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData['tahun_anggaran'] = $post->tahun_anggaran;
                $nestedData['nama_provinsi'] = $post->nama_provinsi;
                $nestedData['nama_penyedia'] = $post->nama_penyedia_provinsi;
                $nestedData['no_kontrak'] = $post->no_kontrak;
                $nestedData['periode'] = date('d-m-Y', strtotime($post->periode_mulai)). " s/d ".date('d-m-Y', strtotime($post->periode_selesai));
                $nestedData['nama_barang'] = $post->nama_barang;
                $nestedData['merk'] = $post->merk;

                $nestedData['jumlah_barang'] = number_format($post->jumlah_barang, 0);
                $nestedData['nilai_barang'] = number_format($post->nilai_barang, 0);
                $nestedData['harga_satuan'] = number_format(($post->nilai_barang/$post->jumlah_barang), 0);

                $nestedData['jumlah_alokasi'] = number_format($post->jumlah_alokasi, 0);
                $nestedData['nilai_alokasi'] = number_format($post->nilai_alokasi, 0);

                $nestedData['status'] = (($post->jumlah_barang - $post->jumlah_alokasi == 0) ? 'CUKUP' : 'KURANG');
                $nestedData['ketfoto'] = (($post->nama_file != '' and $post->nama_file != '[]' and $post->nama_file != 'null' and !is_null($post->nama_file)) ?  'TERSEDIA' : 'TDK TERSEDIA');

                $tools = "";
                $tools .= "<a class='btn btn-xs btn-warning btn-sm' href='".base_url('KontrakProvinsi/ShowDetail?id=').$post->id."'><i class='glyphicon glyphicon-plus'></i></a><a class='btn btn-xs btn-success btn-sm'><i class='glyphicon glyphicon-zoom-in' onclick='LoadData(".$post->id.")'></i></a>";

                if($bolehedit)
                	$tools .= "<a class='btn btn-xs btn-primary btn-sm' href='".base_url('KontrakProvinsi/Edit?id=').$post->id."'><i class='glyphicon glyphicon-pencil'></i></a><a class='btn btn-xs btn-info btn-sm' data-href='#' data-toggle='modal' data-record-title='".$post->no_kontrak."' data-target='#upload-modal' data-record-id='".$post->id."'><i class='glyphicon glyphicon-open-file'></i></a>";

                if($bolehhapus)
                	$tools .= "<a class='btn btn-xs btn-danger btn-sm' data-href='".base_url('KontrakProvinsi/doDelete?id=').$post->id."' data-toggle='modal' data-record-title='".$post->no_kontrak."' data-target='#confirm-delete'><i class='glyphicon glyphicon-trash'></i></a>";

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

	public function Add()
	{
		$this->load->library('parser');
		$param['penyedia_provinsi'] = $this->PenyediaProvinsiModel->GetAll();
		$param['provinsi'] = $this->ProvinsiModel->GetAll();
		$param['jenis_barang_provinsi'] = $this->JenisBarangProvinsiModel->GetAll();
		$data = array(
	        'title' => 'Data Kontrak TP Provinsi',
	        'content-path' => 'PEGADAAN TP PROVINSI / DATA KONTRAK / TAMBAH DATA',
	        'content' => $this->load->view('kontrak-provinsi-add', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function Test()
	{

			$id = $this->input->post('id_kontrak');

			$kontrak_provinsi = $this->KontrakProvinsiModel->Get($id);

			$kodefile_upload = strtotime(NOW);

			if($kontrak_provinsi->nama_file != '' and $kontrak_provinsi->nama_file != 'null' and !is_null($kontrak_provinsi->nama_file) and $kontrak_provinsi->nama_file != '[]')
				$nama_file = json_decode($kontrak_provinsi->nama_file);
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
			        $targetFile =  $target_file = $_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_provinsi/'.$kodefile_upload.basename($_FILES['file']['name'][$key]);
		        	move_uploaded_file($tempFile, $target_file);

			    }

			    $nama_file = json_encode($nama_file);
	        	$data = array(
					'nama_file' => $nama_file,
					'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
					'updated_at' => NOW,
				);
				$this->KontrakProvinsiModel->Update($id, $data);

				$this->session->set_flashdata('info','File uploaded successfully.');
				exit('success');
	        }

	}

	public function RemoveImage()
	{
	    
		$id = $this->input->get('id_kontrak');
		$urutanfile = $this->input->get('urutanfile');

		$kontrak_provinsi = $this->KontrakProvinsiModel->Get($id);

		if($kontrak_provinsi->nama_file != '' and $kontrak_provinsi->nama_file != 'null' and !is_null($kontrak_provinsi->nama_file) and $kontrak_provinsi->nama_file != '[]')
			$images = json_decode($kontrak_provinsi->nama_file);
		else
			$images = [];

		$nama_file = $images[$urutanfile];
		unlink($_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_provinsi/'.$nama_file);	

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

		// echo $nama_file;
		// exit(1);

		$data = array(
			'nama_file' => $nama_file,
			'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
			'updated_at' => NOW,
		);
		$this->KontrakProvinsiModel->Update($id, $data);

		$this->session->set_flashdata('info','File berhasil dihapus.');
		exit('success');
	}

	public function doAdd()
	{	

		$tahun_anggaran = $this->input->post('tahun_anggaran');
		$id_provinsi = $this->input->post('id_provinsi');
		$id_penyedia_provinsi = $this->input->post('id_penyedia_provinsi');
		$no_kontrak = $this->input->post('no_kontrak');
		$periode_mulai = $this->input->post('periode_mulai');
		$periode_selesai = $this->input->post('periode_selesai');
		$nama_barang = $this->input->post('nama_barang');
		$merk = $this->input->post('merk');
		$jumlah_barang = (float)str_replace(',', '', $this->input->post('jumlah_barang'));
		$nilai_barang = (float)str_replace(',', '', $this->input->post('nilai_barang'));
		// $nama_file = $target_file;

		if($tahun_anggaran == '' or $id_penyedia_provinsi == '' or $no_kontrak == '' or $nama_barang == '' or $merk == '' or$jumlah_barang == '' or $nilai_barang == '' or $periode_mulai == '' or $periode_selesai == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('KontrakProvinsi/Add');
		}
		else{
			$periode_mulai = DateTime::createFromFormat('d-m-Y', $periode_mulai)->format('Y-m-d');
			$periode_selesai = DateTime::createFromFormat('d-m-Y', $periode_selesai)->format('Y-m-d');

			$target_file = '';
			$nama_file = '';
			if(file_exists($_FILES['image_upload']['tmp_name']) and is_uploaded_file($_FILES['image_upload']['tmp_name'])) {
		    	$target_file = $_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_provinsi/'.basename($_FILES["image_upload"]["name"]);
		    	$nama_file = basename($_FILES["image_upload"]["name"]);
				if (file_exists($target_file)) {
				    $this->session->set_flashdata('error','Sorry. File already exists.');
		     		redirect('KontrakProvinsi/Add');
				}
				else{
					if(!move_uploaded_file($_FILES["image_upload"]["tmp_name"], $target_file)){
						$this->session->set_flashdata('error','Image failed to upload.');
	     				redirect('KontrakProvinsi/Add');
					}
				}
			}

			$data = array(
				'tahun_anggaran' => $tahun_anggaran,
				'id_provinsi' => $id_provinsi,
				'id_penyedia_provinsi' => $id_penyedia_provinsi,
				'no_kontrak' => strtoupper($no_kontrak),
				'periode_mulai' => $periode_mulai,
				'periode_selesai' => $periode_selesai,
				'nama_barang' => $nama_barang,
				'merk' => $merk,
				'jumlah_barang' => $jumlah_barang,
				'nilai_barang' => $nilai_barang,
				'nama_file' => $nama_file,
				'created_by' => $this->session->userdata('logged_in')->id_pengguna,
				'created_at' => NOW,
			);
			$this->KontrakProvinsiModel->Insert($data);
			$this->session->set_flashdata('info','Data inserted successfully.');
			redirect('KontrakProvinsi');
		}
		
	}

	public function Edit()
	{
		$id = $this->input->get('id');

		$param['penyedia_provinsi'] = $this->PenyediaProvinsiModel->GetAll();
		$param['jenis_barang_provinsi'] = $this->JenisBarangProvinsiModel->GetAll();
		$param['provinsi'] = $this->ProvinsiModel->GetAll();
		$param['kontrak_provinsi'] = $this->KontrakProvinsiModel->Get($id);

		$this->load->library('parser');
		$data = array(
	        'title' => 'Data Kontrak TP Provinsi',
	        'content-path' => 'PENGADAAN TP PROVINSI / DATA KONTRAK / UBAH DATA',
	        'content' => $this->load->view('kontrak-provinsi-edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function doEdit()
	{	
		$id = $this->input->post('id');

		$tahun_anggaran = $this->input->post('tahun_anggaran');
		$id_provinsi = $this->input->post('id_provinsi');
		$id_penyedia_provinsi = $this->input->post('id_penyedia_provinsi');
		$no_kontrak = $this->input->post('no_kontrak');
		$periode_mulai = $this->input->post('periode_mulai');
		$periode_selesai = $this->input->post('periode_selesai');
		$nama_barang = $this->input->post('nama_barang');
		$merk = $this->input->post('merk');
		$jumlah_barang = (float)str_replace(',', '', $this->input->post('jumlah_barang'));
		$nilai_barang = (float)str_replace(',', '', $this->input->post('nilai_barang'));
		// $nama_file = $target_file;

		$removed_images = explode(",", $this->input->post('removedImages'));

		
		$periode_mulai = DateTime::createFromFormat('d-m-Y', $periode_mulai)->format('Y-m-d');
		$periode_selesai = DateTime::createFromFormat('d-m-Y', $periode_selesai)->format('Y-m-d');

		if($tahun_anggaran == '' or $id_penyedia_provinsi == '' or $no_kontrak == '' or $nama_barang == '' or $merk == '' or $jumlah_barang == '' or $nilai_barang == '' or $periode_mulai == '' or $periode_selesai == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('KontrakProvinsi/Edit?id='.$id);
		}
		else{
			
			$kontrak_provinsi = $this->KontrakProvinsiModel->Get($id);
			$nama_file = $kontrak_provinsi->nama_file;

			if($removed_images){
				foreach($removed_images as $imgname){
					unlink($_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_provinsi/'.$imgname);
				}

				$images = json_decode($nama_file);
				foreach($images as $image){
					foreach($removed_images as $removeimage){
						if($image == $removeimage){
							if (($key = array_search($removeimage, $images)) !== false) {
    							unset($images[$key]);
							}
						}
					}
				}

				$nama_file = json_encode($images);
			}

			if($nama_file == '[]' or $nama_file == NULL or $nama_file == 'null' or is_null($nama_file)){
				$nama_file = '';
			}

			$data = array(
				'tahun_anggaran' => $tahun_anggaran,
				'id_provinsi' => $id_provinsi,
				'id_penyedia_provinsi' => $id_penyedia_provinsi,
				'no_kontrak' => strtoupper($no_kontrak),
				'periode_mulai' => $periode_mulai,
				'periode_selesai' => $periode_selesai,
				'nama_barang' => $nama_barang,
				'merk' => $merk,
				'jumlah_barang' => $jumlah_barang,
				'nilai_barang' => $nilai_barang,
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->KontrakProvinsiModel->Update($id, $data);
			$this->session->set_flashdata('info','Data updated successfully.');
			redirect('KontrakProvinsi');
		}
		
	}

	public function doDelete()
	{	
		$id = $this->input->get('id');

		$kontrak_provinsi = $this->KontrakProvinsiModel->Get($id);
		if($kontrak_provinsi->nama_file != '' and $kontrak_provinsi->nama_file != 'null' and !is_null($kontrak_provinsi->nama_file) and $kontrak_provinsi->nama_file != '[]')
			$images = json_decode($kontrak_provinsi->nama_file);
		else
			$images = [];

		foreach($images as $image){
			unlink($_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_provinsi/'.$image);	
		}

		$this->KontrakProvinsiModel->Delete($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('KontrakProvinsi');
		
	}

	public function GetKontrak()
	{
		$id_kontrak = $this->input->get('id_kontrak');

		$data = $this->KontrakProvinsiModel->Get($id_kontrak);
		$hasil = json_encode($data);
		header('HTTP/1.1: 200');
		header('Status: 200');
		header('Content-Length: '.strlen($hasil));
		exit($hasil);
		
	}

	public function GetKontrakByBarang()
	{
		$nama_barang = $this->input->get('nama_barang');
		$merk = $this->input->get('merk');
		$id_provinsi = $this->input->get('id_provinsi');

		$data = $this->KontrakProvinsiModel->GetByBarang($nama_barang, $merk, $id_provinsi);
		$hasil = json_encode($data);
		header('HTTP/1.1: 200');
		header('Status: 200');
		header('Content-Length: '.strlen($hasil));
		exit($hasil);
		
	}

	// detail

	public function GetKontrakDetail()
	{
		$id_kontrak_detail = $this->input->get('id_kontrak_detail');

		$data = $this->KontrakProvinsiModel->GetDetailData($id_kontrak_detail);

		$hasil = json_encode($data);
		header('HTTP/1.1: 200');
		header('Status: 200');
		header('Content-Length: '.strlen($hasil));
		exit($hasil);
		
	}

	public function UploadFileDetail(){
		$id_kontrak_detail = $this->input->post('id_kontrak_detail');

		$kontrak_provinsi_detail = $this->KontrakProvinsiModel->GetDetailData($id_kontrak_detail);

		$kodefile_upload = strtotime(NOW);

		if($kontrak_provinsi_detail->nama_file != '' and $kontrak_provinsi_detail->nama_file != 'null' and !is_null($kontrak_provinsi_detail->nama_file) and $kontrak_provinsi_detail->nama_file != '[]')
			$nama_file = json_decode($kontrak_provinsi_detail->nama_file);
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
		        $targetFile =  $target_file = $_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_provinsi_detail/'.$kodefile_upload.basename($_FILES['file']['name'][$key]);
	        	move_uploaded_file($tempFile, $target_file);

		    }

		    $nama_file = json_encode($nama_file);
        	$data = array(
				'nama_file' => $nama_file,
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->KontrakProvinsiModel->UpdateDetail($id_kontrak_detail, $data);

			$this->session->set_flashdata('info','File uploaded successfully.');
			exit('success');
        }
	}
	public function RemoveFileDetail(){
		$id_kontrak_detail = $this->input->get('id_kontrak_detail');
		$urutanfile = $this->input->get('urutanfile');

		$kontrak_provinsi_detail = $this->KontrakProvinsiModel->GetDetailData($id_kontrak_detail);

		if($kontrak_provinsi_detail->nama_file != '' and $kontrak_provinsi_detail->nama_file != 'null' and !is_null($kontrak_provinsi_detail->nama_file) and $kontrak_provinsi_detail->nama_file != '[]')
			$images = json_decode($kontrak_provinsi_detail->nama_file);
		else
			$images = [];

		$nama_file = $images[$urutanfile];
		unlink($_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_provinsi_detail/'.$nama_file);	

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
		$this->KontrakProvinsiModel->UpdateDetail($id_kontrak_detail, $data);

		$this->session->set_flashdata('info','File berhasil dihapus.');
		exit('success');
	}

	public function ShowDetail()
	{
		$id = $this->input->get('id');

		$kontrak_provinsi = $this->KontrakProvinsiModel->Get($id);;
		$param['kontrak_provinsi'] = $kontrak_provinsi;

		$param['kontrak_provinsi_detail'] = array();
		$param['total_unit'] = $this->KontrakProvinsiModel->GetTotalUnitDetail($kontrak_provinsi->id);
		$param['total_nilai'] = $this->KontrakProvinsiModel->GetTotalNilaiDetail($kontrak_provinsi->id);

		$this->load->library('parser');
		$data = array(
	        'title' => 'Data Detail Kontrak TP Provinsi',
	        'content-path' => 'PENGADAAN TP PROVINSI / DATA DETAIL KONTRAK',
	        'content' => $this->load->view('kontrak-provinsi-detail-index', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function AjaxGetAllDataDetail()
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

		$columns = array( 
            0 =>'nama_provinsi', 
            1 =>'nama_kabupaten',
            2 => 'jumlah_barang_detail',
            3 => 'nilai_barang_detail',
            4 => 'harga_satuan_detail',
            5 => 'regcad',
            6 => 'dinas',
            7 => 'no_adendum_1',
            8 => 'jumlah_barang_rev_1',
            9 => 'nilai_barang_rev_1',
            10 => 'harga_satuan_rev_1',
            11 => 'no_adendum_2',
            12 => 'jumlah_barang_rev_2',
            13 => 'nilai_barang_rev_2',
            14 => 'harga_satuan_rev_2',
            15 => 'status_alokasi',
            16 => 'id',
        );

        $id_kontrak_provinsi = $this->input->post('id_kontrak_provinsi');

        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

		$totalData = $this->KontrakProvinsiModel->GetAllAjaxCountDetail($id_kontrak_provinsi);
            
        $totalFiltered = $totalData; 
        
        //search data percolumn
        $filtercond = '';
        for($i=0;$i<count($columns);$i++){
        	if($i != 4 and $i != 10 and $i != 14){
        		if(!empty($this->input->post('columns')[$i]['search']['value'])){
	        		$search = $this->input->post('columns')[$i]['search']['value'];
	        		$filtercond  .= " and ".$columns[$i]." LIKE '%".$search."%'"; 
	        	}
        	}
        	else{
        		
        		
        	}
        		        	
        }

		if(empty($this->input->post('search')['value']))
        {            
	        $posts = $this->KontrakProvinsiModel->GetAllForAjaxDetail($id_kontrak_provinsi, $_POST['start'], $_POST['length'], $order, $dir, $filtercond);
            $totalFiltered = $this->KontrakProvinsiModel->GetFilterAjaxCountDetail($id_kontrak_provinsi, $filtercond);
        }
        else {
            $search = $this->input->post('search')['value']; 

            $posts =  $this->KontrakProvinsiModel->GetSearchAjaxDetail($id_kontrak_provinsi, $_POST['start'], $_POST['length'], $search, $order, $dir, $filtercond);

            $totalFiltered = $this->KontrakProvinsiModel->GetSearchAjaxCountDetail($id_kontrak_provinsi, $search, $filtercond);
        }

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData['nama_provinsi'] = $post->nama_provinsi;
                $nestedData['nama_kabupaten'] = $post->nama_kabupaten;
                $nestedData['jumlah_barang_detail'] = ($post->jumlah_barang_detail == 0 ? '' : number_format($post->jumlah_barang_detail, 0));
                $nestedData['nilai_barang_detail'] = ($post->nilai_barang_detail == 0 ? '' : number_format($post->nilai_barang_detail, 0));
                $nestedData['harga_satuan_detail'] = ($post->jumlah_barang_detail == 0 ? '' : number_format(($post->nilai_barang_detail/$post->jumlah_barang_detail), 0));
                $nestedData['regcad'] = $post->regcad;
                $nestedData['dinas'] = $post->dinas;

                $nestedData['no_adendum_1'] = $post->no_adendum_1;
                $nestedData['jumlah_barang_rev_1'] = ($post->jumlah_barang_rev_1 == 0 ? '' : number_format($post->jumlah_barang_rev_1, 0));
                $nestedData['nilai_barang_rev_1'] = ($post->nilai_barang_rev_1 == 0 ? '' : number_format($post->nilai_barang_rev_1, 0));
                $nestedData['harga_satuan_rev_1'] = ($post->jumlah_barang_rev_1 == 0 ? '' : number_format(($post->nilai_barang_rev_1/$post->jumlah_barang_rev_1), 0));
                
                $nestedData['no_adendum_2'] = $post->no_adendum_2;
                $nestedData['jumlah_barang_rev_2'] = ($post->jumlah_barang_rev_2 == 0 ? '' : number_format($post->jumlah_barang_rev_2, 0));
                $nestedData['nilai_barang_rev_2'] = ($post->nilai_barang_rev_2 == 0 ? '' : number_format($post->nilai_barang_rev_2, 0));
                $nestedData['harga_satuan_rev_2'] = ($post->jumlah_barang_rev_2 == 0 ? '' : number_format(($post->nilai_barang_rev_2/$post->jumlah_barang_rev_2), 0));

                $nestedData['status_alokasi'] = $post->status_alokasi;

                $tools = "";

                $tools .= "<a class='btn btn-xs btn-success btn-sm'><i class='glyphicon glyphicon-zoom-in' onclick='LoadData(".$post->id.");'></i></a>";
				
				if($bolehedit)
					$tools .= "<a class='btn btn-xs btn-primary btn-sm' href='".base_url('KontrakProvinsi/EditDetail?id=').$post->id."'><i class='glyphicon glyphicon-pencil'></i></a><a class='btn btn-xs btn-info btn-sm' data-href='#' data-toggle='modal' data-record-title='".$post->no_kontrak."' data-target='#upload-modal' data-record-id='".$post->id."'><i class='glyphicon glyphicon-open-file'></i></a>";

				if($bolehhapus)
					$tools .= "<a class='btn btn-xs btn-danger btn-sm' data-href='".base_url('KontrakProvinsi/doDeleteDetail?id=').$post->id."' data-toggle='modal' data-record-title='".$post->no_kontrak."' data-target='#confirm-delete'><i class='glyphicon glyphicon-trash'></i></a>";


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

	public function AddDetail()
	{
		$id = $this->input->get('id');

		$param['kontrak_provinsi'] = $this->KontrakProvinsiModel->Get($id);

		$this->load->library('parser');
		$this->load->model('ProvinsiModel');

		$param['provinsi'] = $this->ProvinsiModel->GetAll();

		$data = array(
	        'title' => 'Data Detail Kontrak TP Provinsi',
	        'content-path' => 'PEGADAAN TP PROVINSI / DATA DETAIL KONTRAK / TAMBAH DATA',
	        'content' => $this->load->view('kontrak-provinsi-detail-add', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function doAddDetail()
	{	

		$id_kontrak_provinsi = $this->input->post('id_kontrak_provinsi');

		$kontrak_provinsi = $this->KontrakProvinsiModel->Get($id_kontrak_provinsi);
		$jumlah_barang_kontrak_provinsi = $kontrak_provinsi->jumlah_barang;

		$jumlah_kontrak_detail = $this->KontrakProvinsiModel->HitungJumlahKontrakDetail($id_kontrak_provinsi);

		$id_provinsi = $this->input->post('id_provinsi');
		$id_kabupaten = $this->input->post('id_kabupaten');
		$jumlah_barang_detail = (float)str_replace(',', '', $this->input->post('jumlah_barang_detail'));
		$nilai_barang_detail = (float)str_replace(',', '', $this->input->post('nilai_barang_detail'));
		$regcad = $this->input->post('regcad');
		$dinas = $this->input->post('dinas');

		$no_adendum_1 = $this->input->post('no_adendum_1');
		$jumlah_barang_rev_1 = (float)str_replace(',', '', $this->input->post('jumlah_barang_rev_1'));
		$nilai_barang_rev_1 = (float)str_replace(',', '', $this->input->post('nilai_barang_rev_1'));

		$no_adendum_2 = $this->input->post('no_adendum_2');
		$jumlah_barang_rev_2 = (float)str_replace(',', '', $this->input->post('jumlah_barang_rev_2'));
		$nilai_barang_rev_2 = (float)str_replace(',', '', $this->input->post('nilai_barang_rev_2'));

		$status_alokasi = $this->input->post('status_alokasi');

		if($status_alokasi == 'DATA ADENDUM 1'){
			if( $jumlah_barang_rev_1 > ($jumlah_barang_kontrak_provinsi - $jumlah_kontrak_detail) ){
				$this->session->set_flashdata('error','Jumlah barang detail tidak dapat melebihi jumlah barang kontrak.');
	 			redirect('KontrakProvinsi/AddDetail?id='.$id_kontrak_provinsi);
			}
		}
		else if($status_alokasi == 'DATA ADENDUM 2'){
			if( $jumlah_barang_rev_2 > ($jumlah_barang_kontrak_provinsi - $jumlah_kontrak_detail) ){
				$this->session->set_flashdata('error','Jumlah barang detail tidak dapat melebihi jumlah barang kontrak.');
	 			redirect('KontrakProvinsi/AddDetail?id='.$id_kontrak_provinsi);
			}
		}
		else if($status_alokasi == 'DATA KONTRAK AWAL'){
			if( $jumlah_barang_detail > ($jumlah_barang_kontrak_provinsi - $jumlah_kontrak_detail) ){
				$this->session->set_flashdata('error','Jumlah barang detail tidak dapat melebihi jumlah barang kontrak.');
	 			redirect('KontrakProvinsi/AddDetail?id='.$id_kontrak_provinsi);
			}
		}

		
		
		if($id_kontrak_provinsi == '' or $id_provinsi == '' or $id_kabupaten == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('KontrakProvinsi/AddDetail?id='.$id_kontrak_provinsi);
		}
		else{
			$target_file_1 = '';
			$target_file_2 = '';
			$nama_file_adendum_1 = '';
			$nama_file_adendum_2 = '';

			if(file_exists($_FILES['nama_file_adendum_1']['tmp_name']) and is_uploaded_file($_FILES['nama_file_adendum_1']['tmp_name'])) {

		    	$target_file_1 = $_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_provinsi/detail/'.basename($_FILES["nama_file_adendum_1"]["name"]);

		    	$nama_file_adendum_1 = basename($_FILES["nama_file_adendum_1"]["name"]);
				if (file_exists($target_file_1)) {
				    $this->session->set_flashdata('error','Sorry. File already exists.');
		     		redirect('KontrakProvinsi/AddDetail?id='.$id_kontrak_provinsi);
				}
				else{
					if(!move_uploaded_file($_FILES["nama_file_adendum_1"]["tmp_name"], $target_file_1)){
						$this->session->set_flashdata('error','Image failed to upload.');
	     				redirect('KontrakProvinsi/AddDetail?id='.$id_kontrak_provinsi);
					}
				}
			}

			if(file_exists($_FILES['nama_file_adendum_2']['tmp_name']) and is_uploaded_file($_FILES['nama_file_adendum_2']['tmp_name'])) {

		    	$target_file_2 = $_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_provinsi/detail/'.basename($_FILES["nama_file_adendum_2"]["name"]);

		    	$nama_file_adendum_2 = basename($_FILES["nama_file_adendum_2"]["name"]);
				if (file_exists($target_file_2)) {
				    $this->session->set_flashdata('error','Sorry. File already exists.');
		     		redirect('KontrakProvinsi/AddDetail?id='.$id_kontrak_provinsi);
				}
				else{
					if(!move_uploaded_file($_FILES["nama_file_adendum_2"]["tmp_name"], $target_file_2)){
						unlink($taget_file_1);
						$this->session->set_flashdata('error','Image failed to upload.');
	     				redirect('KontrakProvinsi/AddDetail?id='.$id_kontrak_provinsi);
					}
				}
			}

			$data = array(
				'id_kontrak_provinsi' => $id_kontrak_provinsi,
				'id_provinsi' => $id_provinsi,
				'id_kabupaten' => $id_kabupaten,
				'jumlah_barang_detail' => $jumlah_barang_detail,
				'nilai_barang_detail' => $nilai_barang_detail,
				'regcad' => $regcad,
				'dinas' => $dinas,
				'no_adendum_1' => $no_adendum_1,
				'jumlah_barang_rev_1' => $jumlah_barang_rev_1,
				'nilai_barang_rev_1' => $nilai_barang_rev_1,
				'no_adendum_2' => $no_adendum_2,
				'jumlah_barang_rev_2' => $jumlah_barang_rev_2,
				'nilai_barang_rev_2' => $nilai_barang_rev_2,
				'nama_file_adendum_1' => $nama_file_adendum_1,
				'nama_file_adendum_2' => $nama_file_adendum_2,
				'status_alokasi' => $status_alokasi,
				'nama_file' => '',
				'created_by' => $this->session->userdata('logged_in')->id_pengguna,
				'created_at' => NOW,
			);
			$this->KontrakProvinsiModel->InsertDetail($data);
			$this->session->set_flashdata('info','Data inserted successfully.');
			redirect('KontrakProvinsi/ShowDetail?id='.$id_kontrak_provinsi);
		}
		
	}

	public function EditDetail()
	{
		$id = $this->input->get('id');

		$kontrak_provinsi_detail = $this->KontrakProvinsiModel->GetDetailData($id);
		$param['kontrak_provinsi_detail'] = $kontrak_provinsi_detail;
		$param['kontrak_provinsi'] = $this->KontrakProvinsiModel->Get($kontrak_provinsi_detail->id_kontrak_provinsi);

		$this->load->library('parser');
		$this->load->model('ProvinsiModel');

		$param['provinsi'] = $this->ProvinsiModel->GetAll();

		$data = array(
	        'title' => 'Data Detail Kontrak TP Provinsi',
	        'content-path' => 'PEGADAAN TP PROVINSI / DATA DETAIL KONTRAK / UBAH DATA',
	        'content' => $this->load->view('kontrak-provinsi-detail-edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function doEditDetail()
	{	

		$id = $this->input->post('id');

		$kontrak_provinsi_detail = $this->KontrakProvinsiModel->GetDetailData($id);
		$id_kontrak_provinsi = $kontrak_provinsi_detail->id_kontrak_provinsi;

		$kontrak_provinsi = $this->KontrakProvinsiModel->Get($id_kontrak_provinsi);
		$jumlah_barang_kontrak_provinsi = $kontrak_provinsi->jumlah_barang;
		$jumlah_kontrak_detail = $this->KontrakProvinsiModel->HitungJumlahKontrakDetailSelainDetailIni($id_kontrak_provinsi, $id);

		$id_provinsi = $this->input->post('id_provinsi');
		$id_kabupaten = $this->input->post('id_kabupaten');
		$jumlah_barang_detail = (float)str_replace(',', '', $this->input->post('jumlah_barang_detail'));
		$nilai_barang_detail = (float)str_replace(',', '', $this->input->post('nilai_barang_detail'));
		$regcad = $this->input->post('regcad');
		$dinas = $this->input->post('dinas');

		$no_adendum_1 = $this->input->post('no_adendum_1');
		$jumlah_barang_rev_1 = (float)str_replace(',', '', $this->input->post('jumlah_barang_rev_1'));
		$nilai_barang_rev_1 = (float)str_replace(',', '', $this->input->post('nilai_barang_rev_1'));

		$no_adendum_2 = $this->input->post('no_adendum_2');
		$jumlah_barang_rev_2 = (float)str_replace(',', '', $this->input->post('jumlah_barang_rev_2'));
		$nilai_barang_rev_2 = (float)str_replace(',', '', $this->input->post('nilai_barang_rev_2'));

		$status_alokasi = $this->input->post('status_alokasi');

		if($status_alokasi == 'DATA ADENDUM 1'){
			if( $jumlah_barang_rev_1 > ($jumlah_barang_kontrak_provinsi - $jumlah_kontrak_detail) ){
				$this->session->set_flashdata('error','Jumlah barang detail tidak dapat melebihi jumlah barang kontrak.');
	 			redirect('KontrakProvinsi/EditDetail?id='.$id);
			}
		}
		else if($status_alokasi == 'DATA ADENDUM 2'){
			if( $jumlah_barang_rev_2 > ($jumlah_barang_kontrak_provinsi - $jumlah_kontrak_detail) ){
				$this->session->set_flashdata('error','Jumlah barang detail tidak dapat melebihi jumlah barang kontrak.');
	 			redirect('KontrakProvinsi/EditDetail?id='.$id);
			}
		}
		else if($status_alokasi == 'DATA KONTRAK AWAL'){
			if( $jumlah_barang_detail > ($jumlah_barang_kontrak_provinsi - $jumlah_kontrak_detail) ){
				$this->session->set_flashdata('error','Jumlah barang detail tidak dapat melebihi jumlah barang kontrak.');
	 			redirect('KontrakProvinsi/EditDetail?id='.$id);
			}
		}
		
		if($id_kontrak_provinsi == '' or $id_provinsi == '' or $id_kabupaten == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('KontrakProvinsi/EditDetail?id='.$id);
		}
		else{
			$target_file_1 = '';
			$target_file_2 = '';
			$nama_file_adendum_1 = '';
			$nama_file_adendum_2 = '';

			if(file_exists($_FILES['nama_file_adendum_1']['tmp_name']) and is_uploaded_file($_FILES['nama_file_adendum_1']['tmp_name'])) {

		    	$target_file_1 = $_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_provinsi/detail/'.basename($_FILES["nama_file_adendum_1"]["name"]);

		    	$nama_file_adendum_1 = basename($_FILES["nama_file_adendum_1"]["name"]);
				if (file_exists($target_file_1)) {
				    $this->session->set_flashdata('error','Sorry. File already exists.');
		     		redirect('KontrakProvinsi/EditDetail?id='.$id);
				}
				else{
					if(!move_uploaded_file($_FILES["nama_file_adendum_1"]["tmp_name"], $target_file_1)){
						$this->session->set_flashdata('error','Image failed to upload.');
	     				redirect('KontrakProvinsi/EditDetail?id='.$id);
					}
					else{
						if($kontrak_provinsi_detail->nama_file_adendum_1 != ''){
							unlink($_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_provinsi/detail/'.$kontrak_pusat_detail->nama_file_adendum_1);
						}
					}
				}
			}

			if(file_exists($_FILES['nama_file_adendum_2']['tmp_name']) and is_uploaded_file($_FILES['nama_file_adendum_2']['tmp_name'])) {

		    	$target_file_2 = $_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_provinsi/detail/'.basename($_FILES["nama_file_adendum_2"]["name"]);

		    	$nama_file_adendum_2 = basename($_FILES["nama_file_adendum_2"]["name"]);
				if (file_exists($target_file_2)) {
				    $this->session->set_flashdata('error','Sorry. File already exists.');
		     		redirect('KontrakProvinsi/EditDetail?id='.$id);
				}
				else{
					if(!move_uploaded_file($_FILES["nama_file_adendum_2"]["tmp_name"], $target_file_2)){
						unlink($taget_file_1);
						$this->session->set_flashdata('error','Image failed to upload.');
	     				redirect('KontrakProvinsi/EditDetail?id='.$id);
					}
					else{
						if($kontrak_provinsi_detail->nama_file_adendum_2 != ''){
							unlink($_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_provinsi/detail/'.$kontrak_provinsi_detail->nama_file_adendum_2);
						}
					}
				}
			}

			$data = array(
				'id_kontrak_provinsi' => $id_kontrak_provinsi,
				'id_provinsi' => $id_provinsi,
				'id_kabupaten' => $id_kabupaten,
				'jumlah_barang_detail' => $jumlah_barang_detail,
				'nilai_barang_detail' => $nilai_barang_detail,
				'regcad' => $regcad,
				'dinas' => $dinas,
				'no_adendum_1' => $no_adendum_1,
				'jumlah_barang_rev_1' => $jumlah_barang_rev_1,
				'nilai_barang_rev_1' => $nilai_barang_rev_1,
				'no_adendum_2' => $no_adendum_2,
				'jumlah_barang_rev_2' => $jumlah_barang_rev_2,
				'nilai_barang_rev_2' => $nilai_barang_rev_2,
				'nama_file_adendum_1' => $nama_file_adendum_1,
				'nama_file_adendum_2' => $nama_file_adendum_2,
				'status_alokasi' => $status_alokasi,
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->KontrakProvinsiModel->UpdateDetail($id, $data);
			$this->session->set_flashdata('info','Data updated successfully.');
			redirect('KontrakProvinsi/ShowDetail?id='.$id_kontrak_provinsi);
		}
		
	}

	public function doDeleteDetail()
	{	
		$id = $this->input->get('id');

		$kontrak_provinsi_detail = $this->KontrakProvinsiModel->GetDetailData($id);
		$id_kontrak_provinsi = $kontrak_provinsi_detail->id_kontrak_provinsi;

		if($kontrak_provinsi_detail->nama_file != '' and $kontrak_provinsi_detail->nama_file != 'null' and !is_null($kontrak_provinsi_detail->nama_file) and $kontrak_provinsi_detail->nama_file != '[]')
			$images = json_decode($kontrak_provinsi_detail->nama_file);
		else
			$images = [];

		foreach($images as $image){
			unlink($_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_provinsi_detail/'.$image);	
		}

		$this->KontrakProvinsiModel->DeleteDetail($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('KontrakProvinsi/ShowDetail?id='.$id_kontrak_provinsi);
		
	}
}
	