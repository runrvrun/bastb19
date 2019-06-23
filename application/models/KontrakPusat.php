<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KontrakPusat extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('KontrakPusatModel');
		$this->load->model('PenyediaPusatModel');
		$this->load->model('JenisBarangPusatModel');
	}

	public function index()
	{
		$this->load->library('parser');
		$param['kontrak_pusat'] = $this->KontrakPusatModel->GetAll();
		$param['total_unit'] = $this->KontrakPusatModel->GetTotalUnit();
		$param['total_nilai'] = $this->KontrakPusatModel->GetTotalNilai();
		$param['total_kontrak'] = $this->KontrakPusatModel->GetTotalKontrak();
		$param['total_merk'] = $this->KontrakPusatModel->GetTotalMerk();
		$data = array(
	        'title' => 'Data Kontrak Pusat',
	        'content-path' => 'PENGADAAN PUSAT / DATA KONTRAK',
	        'content' => $this->load->view('kontrak-pusat-index', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function AjaxGetAllData()
	{
		$bolehtambah = 0;
		$bolehedit = 0;
		$bolehhapus = 0;
		foreach($this->session->userdata('logged_in')->crud_items as $crud){
			if(rtrim($crud->crud_action) == 'TAMBAH DATA')
			  $bolehtambah = 1;
			if(rtrim($crud->crud_action) == 'EDIT DATA')
			  $bolehedit = 1;
			if(rtrim($crud->crud_action) == 'HAPUS DATA')
			  $bolehhapus = 1;
		}

		$columns = array( 
            0 =>'tahun_anggaran', 
            1 =>'nama_penyedia_pusat',
            2 => 'no_kontrak',
            3 => 'periode_mulai',
            4 => 'nama_barang',
            5 => 'merk',
            6 => 'jumlah_barang',
            7 => 'nilai_barang',
            8 => 'nilai_barang',
            9 => 'jumlah_alokasi',
            10 => 'nilai_alokasi',
            11 => 'cukup/kurang',
            12 => 'id',
            13 => 'id',
        );

        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

		$totalData = $this->KontrakPusatModel->GetAllAjaxCount();
            
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
        				$filtercond .= " and (ifnull(jumlah_barang, 0) - ifnull(jumlah_alokasi, 0) = 0 and ifnull(nilai_barang, 0) - ifnull(nilai_alokasi, 0) = 0 )";
        			}
        			else if($this->input->post('columns')[$i]['search']['value'] == 'KURANG'){
        				$filtercond .= " and (ifnull(jumlah_barang, 0) - ifnull(jumlah_alokasi, 0) != 0 or ifnull(nilai_barang, 0) - ifnull(nilai_alokasi, 0) != 0 ) ";
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
	        $posts = $this->KontrakPusatModel->GetAllForAjax($_POST['start'], $_POST['length'], $order, $dir, $filtercond);
            $totalFiltered = $this->KontrakPusatModel->GetFilterAjaxCount($filtercond);
        }
        else {
            $search = $this->input->post('search')['value']; 

            $posts =  $this->KontrakPusatModel->GetSearchAjax($_POST['start'], $_POST['length'], $search, $order, $dir, $filtercond);

            $totalFiltered = $this->KontrakPusatModel->GetSearchAjaxCount($search, $filtercond);
        }

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData['tahun_anggaran'] = $post->tahun_anggaran;
                $nestedData['nama_penyedia'] = $post->nama_penyedia_pusat;
                $nestedData['no_kontrak'] = $post->no_kontrak;
                $nestedData['periode'] = date('d-m-Y', strtotime($post->periode_mulai)). " s/d ".date('d-m-Y', strtotime($post->periode_selesai));
                $nestedData['nama_barang'] = $post->nama_barang;
                $nestedData['merk'] = $post->merk;

                $nestedData['jumlah_barang'] = number_format($post->jumlah_barang, 0);
                $nestedData['nilai_barang'] = number_format($post->nilai_barang, 0);
                $nestedData['harga_satuan'] = number_format(($post->nilai_barang/$post->jumlah_barang), 0);

                $nestedData['jumlah_alokasi'] = number_format($post->jumlah_alokasi, 0);
                $nestedData['nilai_alokasi'] = number_format($post->nilai_alokasi, 0);

                $nestedData['status'] = (($post->jumlah_barang - $post->jumlah_alokasi == 0 and $post->nilai_barang - $post->nilai_alokasi == 0) ? 'CUKUP' : 'KURANG');
                $nestedData['ketfoto'] = (($post->nama_file != '' and $post->nama_file != '[]' and $post->nama_file != 'null' and !is_null($post->nama_file)) ?  'TERSEDIA' : 'TDK TERSEDIA');
              //   if($post->status_alokasi == 'DATA KONTRAK AWAL'){
	             //    $nestedData['alokasi'] = number_format($post->jumlah_barang_detail, 0);
	             //    $nestedData['nilai_barang'] = number_format($post->nilai_barang_detail, 0);
	             //    $nestedData['harga_satuan'] = number_format(($post->nilai_barang_detail/$post->jumlah_barang_detail), 0);

	             //    $nestedData['no_adendum'] = '';
              //   }
              //   else if($post->status_alokasi == 'DATA ADENDUM 1'){
	            	// $nestedData['alokasi'] = number_format($post->jumlah_barang_rev_1, 0);
	             //    $nestedData['nilai_barang'] = number_format($post->nilai_barang_rev_1, 0);
	             //    $nestedData['harga_satuan'] = number_format(($post->nilai_barang_rev_1/$post->jumlah_barang_rev_1), 0);
	             //    $nestedData['no_adendum'] = $post->no_adendum_1;
              //   }
              //   else if($post->status_alokasi == 'DATA ADENDUM 2'){
              //   	$nestedData['alokasi'] = number_format($post->jumlah_barang_rev_2, 0);
	             //    $nestedData['nilai_barang'] = number_format($post->nilai_barang_rev_2, 0);
	             //    $nestedData['harga_satuan'] = number_format(($post->nilai_barang_rev_2/$post->jumlah_barang_rev_2), 0);
	             //    $nestedData['no_adendum'] = $post->no_adendum_2;
              //   }
                $tools = "";
                $tools .=  "<a class='btn btn-xs btn-warning btn-sm' href='".base_url('KontrakPusat/ShowDetail?id=').$post->id."'><i class='glyphicon glyphicon-plus'></i></a><a class='btn btn-xs btn-success btn-sm'><i class='glyphicon glyphicon-zoom-in' onclick='LoadData(".$post->id.")'></i></a>";

                if($bolehedit)
                	$tools .= "<a class='btn btn-xs btn-primary btn-sm' href='".base_url('KontrakPusat/Edit?id=').$post->id."'><i class='glyphicon glyphicon-pencil'></i></a><a class='btn btn-xs btn-info btn-sm' data-href='#' data-toggle='modal' data-record-title='".$post->no_kontrak."' data-target='#upload-modal' data-record-id='".$post->id."'><i class='glyphicon glyphicon-open-file'></i></a>";

                if($bolehhapus)
                	$tools .= "<a class='btn btn-xs btn-danger btn-sm' data-href='".base_url('KontrakPusat/doDelete?id=').$post->id."' data-toggle='modal' data-record-title='".$post->no_kontrak."' data-target='#confirm-delete'><i class='glyphicon glyphicon-trash'></i></a>";

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
		$param['penyedia_pusat'] = $this->PenyediaPusatModel->GetAll();
		$param['jenis_barang_pusat'] = $this->JenisBarangPusatModel->GetAll();
		$data = array(
	        'title' => 'Data Kontrak Pusat',
	        'content-path' => 'PEGADAAN PUSAT / DATA KONTRAK / TAMBAH DATA',
	        'content' => $this->load->view('kontrak-pusat-add', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function Test()
	{
	    
	 //    $tahun_anggaran = $this->input->post('tahun_anggaran');
	 //    $id_penyedia_pusat = $this->input->post('id_penyedia_pusat');
		// $no_kontrak = $this->input->post('no_kontrak');
		// $periode_mulai = $this->input->post('periode_mulai');
		// $periode_selesai = $this->input->post('periode_selesai');
		// $nama_barang = $this->input->post('nama_barang');
		// $merk = $this->input->post('merk');
		// $jumlah_barang = (float)str_replace(',', '', $this->input->post('jumlah_barang'));
		// $nilai_barang = (float)str_replace(',', '', $this->input->post('nilai_barang'));
	    

	 //    if($tahun_anggaran == '' or $id_penyedia_pusat == '' or $no_kontrak == '' or $nama_barang == '' or $merk == '' or$jumlah_barang == '' or $nilai_barang == '' or $periode_mulai == '' or $periode_selesai == ''){
	 //    	$this->session->set_flashdata('error','All fields must be filled.');
		// 	exit('All fields must be filled.');
		// }
		// else{
		// 	$periode_mulai = DateTime::createFromFormat('d-m-Y', $periode_mulai)->format('Y-m-d');
		// 	$periode_selesai = DateTime::createFromFormat('d-m-Y', $periode_selesai)->format('Y-m-d');

			$id = $this->input->post('id_kontrak');

			$kontrak_pusat = $this->KontrakPusatModel->Get($id);

			$kodefile_upload = strtotime(NOW);

			if($kontrak_pusat->nama_file != '' and $kontrak_pusat->nama_file != 'null' and !is_null($kontrak_pusat->nama_file) and $kontrak_pusat->nama_file != '[]')
				$nama_file = json_decode($kontrak_pusat->nama_file);
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
			        $targetFile =  $target_file = $_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_pusat/'.$kodefile_upload.basename($_FILES['file']['name'][$key]);
		        	move_uploaded_file($tempFile, $target_file);

			    }

			    $nama_file = json_encode($nama_file);
	        	$data = array(
					'nama_file' => $nama_file,
					'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
					'updated_at' => NOW,
				);
				$this->KontrakPusatModel->Update($id, $data);

				$this->session->set_flashdata('info','File uploaded successfully.');
				exit('success');
	        }

		    

		 	//    $data = array(
			// 	'tahun_anggaran' => $tahun_anggaran,
			// 	'id_penyedia_pusat' => $id_penyedia_pusat,
			// 	'no_kontrak' => strtoupper($no_kontrak),
			// 	'periode_mulai' => $periode_mulai,
			// 	'periode_selesai' => $periode_selesai,
			// 	'nama_barang' => $nama_barang,
			// 	'merk' => $merk,
			// 	'jumlah_barang' => $jumlah_barang,
			// 	'nilai_barang' => $nilai_barang,
			// 	'nama_file' => $nama_file,
			// 	'created_by' => $this->session->userdata('logged_in')->id_pengguna,
			// 	'created_at' => NOW,
			// );
			// $this->KontrakPusatModel->Insert($data);

			
		// }
	}

	public function RemoveImage()
	{
	    
		$id = $this->input->get('id_kontrak');
		$urutanfile = $this->input->get('urutanfile');

		$kontrak_pusat = $this->KontrakPusatModel->Get($id);

		if($kontrak_pusat->nama_file != '' and $kontrak_pusat->nama_file != 'null' and !is_null($kontrak_pusat->nama_file) and $kontrak_pusat->nama_file != '[]')
			$images = json_decode($kontrak_pusat->nama_file);
		else
			$images = [];

		$nama_file = $images[$urutanfile];
		unlink($_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_pusat/'.$nama_file);	

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
		$this->KontrakPusatModel->Update($id, $data);

		$this->session->set_flashdata('info','File berhasil dihapus.');
		exit('success');
	}

	public function doAdd()
	{	

		$tahun_anggaran = $this->input->post('tahun_anggaran');
		$id_penyedia_pusat = $this->input->post('id_penyedia_pusat');
		$no_kontrak = $this->input->post('no_kontrak');
		$periode_mulai = $this->input->post('periode_mulai');
		$periode_selesai = $this->input->post('periode_selesai');
		$nama_barang = $this->input->post('nama_barang');
		$merk = $this->input->post('merk');
		$jumlah_barang = (float)str_replace(',', '', $this->input->post('jumlah_barang'));
		$nilai_barang = (float)str_replace(',', '', $this->input->post('nilai_barang'));
		// $nama_file = $target_file;

		if($tahun_anggaran == '' or $id_penyedia_pusat == '' or $no_kontrak == '' or $nama_barang == '' or $merk == '' or$jumlah_barang == '' or $nilai_barang == '' or $periode_mulai == '' or $periode_selesai == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('KontrakPusat/Add');
		}
		else{
			$periode_mulai = DateTime::createFromFormat('d-m-Y', $periode_mulai)->format('Y-m-d');
			$periode_selesai = DateTime::createFromFormat('d-m-Y', $periode_selesai)->format('Y-m-d');

			$target_file = '';
			$nama_file = '';
			if(file_exists($_FILES['image_upload']['tmp_name']) and is_uploaded_file($_FILES['image_upload']['tmp_name'])) {
		    	$target_file = $_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_pusat/'.basename($_FILES["image_upload"]["name"]);
		    	$nama_file = basename($_FILES["image_upload"]["name"]);
				if (file_exists($target_file)) {
				    $this->session->set_flashdata('error','Sorry. File already exists.');
		     		redirect('KontrakPusat/Add');
				}
				else{
					if(!move_uploaded_file($_FILES["image_upload"]["tmp_name"], $target_file)){
						$this->session->set_flashdata('error','Image failed to upload.');
	     				redirect('KontrakPusat/Add');
					}
				}
			}

			$data = array(
				'tahun_anggaran' => $tahun_anggaran,
				'id_penyedia_pusat' => $id_penyedia_pusat,
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
			$this->KontrakPusatModel->Insert($data);
			$this->session->set_flashdata('info','Data inserted successfully.');
			redirect('KontrakPusat');
		}
		
	}

	public function Edit()
	{
		$id = $this->input->get('id');

		$param['penyedia_pusat'] = $this->PenyediaPusatModel->GetAll();
		$param['jenis_barang_pusat'] = $this->JenisBarangPusatModel->GetAll();

		$param['kontrak_pusat'] = $this->KontrakPusatModel->Get($id);

		$this->load->library('parser');
		$data = array(
	        'title' => 'Data Kontrak Pusat',
	        'content-path' => 'PENGADAAN PUSAT / DATA KONTRAK / UBAH DATA',
	        'content' => $this->load->view('kontrak-pusat-edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function doEdit()
	{	
		$id = $this->input->post('id');

		$tahun_anggaran = $this->input->post('tahun_anggaran');
		$id_penyedia_pusat = $this->input->post('id_penyedia_pusat');
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

		if($tahun_anggaran == '' or $id_penyedia_pusat == '' or $no_kontrak == '' or $nama_barang == '' or $merk == '' or $jumlah_barang == '' or $nilai_barang == '' or $periode_mulai == '' or $periode_selesai == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('KontrakPusat/Edit?id='.$id);
		}
		else{
			// $target_file = '';
			// $nama_file = '';
			// if(file_exists($_FILES['image_upload']['tmp_name']) and is_uploaded_file($_FILES['image_upload']['tmp_name'])) {
		 //    	$target_file = $_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_pusat/'.basename($_FILES["image_upload"]["name"]);
		 //    	$nama_file = basename($_FILES["image_upload"]["name"]);
			// 	if (file_exists($target_file)) {
			// 	    $this->session->set_flashdata('error','Sorry. File already exists.');
		 //     		redirect('KontrakPusat/Edit');
			// 	}
			// 	else{
			// 		if(!move_uploaded_file($_FILES["image_upload"]["tmp_name"], $target_file)){
			// 			$this->session->set_flashdata('error','Image failed to upload.');
	  //    				redirect('KontrakPusat/Edit');
			// 		}
			// 		else{
			// 			$kontrak_pusat = $this->KontrakPusatModel->Get($id);
			// 			if($kontrak_pusat->nama_file != ''){
			// 				unlink($_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_pusat/'.$kontrak_pusat->nama_file);
			// 			}
			// 		}
			// 	}
			// }
			$kontrak_pusat = $this->KontrakPusatModel->Get($id);
			$nama_file = $kontrak_pusat->nama_file;

			if($removed_images){
				foreach($removed_images as $imgname){
					unlink($_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_pusat/'.$imgname);
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
				'id_penyedia_pusat' => $id_penyedia_pusat,
				'no_kontrak' => strtoupper($no_kontrak),
				'periode_mulai' => $periode_mulai,
				'periode_selesai' => $periode_selesai,
				'nama_barang' => $nama_barang,
				'merk' => $merk,
				'jumlah_barang' => $jumlah_barang,
				'nilai_barang' => $nilai_barang,
				'nama_file' => $nama_file,
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->KontrakPusatModel->Update($id, $data);
			$this->session->set_flashdata('info','Data updated successfully.');
			redirect('KontrakPusat');
		}
		
	}

	public function doDelete()
	{	
		$id = $this->input->get('id');

		$kontrak_pusat = $this->KontrakPusatModel->Get($id);
		if($kontrak_pusat->nama_file != '' and $kontrak_pusat->nama_file != 'null' and !is_null($kontrak_pusat->nama_file) and $kontrak_pusat->nama_file != '[]')
			$images = json_decode($kontrak_pusat->nama_file);
		else
			$images = [];

		foreach($images as $image){
			unlink($_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_pusat/'.$image);	
		}

		$this->KontrakPusatModel->Delete($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('KontrakPusat');
		
	}

	public function GetKontrak()
	{
		$id_kontrak = $this->input->get('id_kontrak');

		$data = $this->KontrakPusatModel->Get($id_kontrak);
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

		$data = $this->KontrakPusatModel->GetByBarang($nama_barang, $merk);
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

		$data = $this->KontrakPusatModel->GetDetailData($id_kontrak_detail);

		$hasil = json_encode($data);
		header('HTTP/1.1: 200');
		header('Status: 200');
		header('Content-Length: '.strlen($hasil));
		exit($hasil);
		
	}

	public function UploadFileDetail(){
		$id_kontrak_detail = $this->input->post('id_kontrak_detail');

		$kontrak_pusat_detail = $this->KontrakPusatModel->GetDetailData($id_kontrak_detail);

		$kodefile_upload = strtotime(NOW);

		if($kontrak_pusat_detail->nama_file != '' and $kontrak_pusat_detail->nama_file != 'null' and !is_null($kontrak_pusat_detail->nama_file) and $kontrak_pusat_detail->nama_file != '[]')
			$nama_file = json_decode($kontrak_pusat_detail->nama_file);
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
		        $targetFile =  $target_file = $_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_pusat_detail/'.$kodefile_upload.basename($_FILES['file']['name'][$key]);
	        	move_uploaded_file($tempFile, $target_file);

		    }

		    $nama_file = json_encode($nama_file);
        	$data = array(
				'nama_file' => $nama_file,
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->KontrakPusatModel->UpdateDetail($id_kontrak_detail, $data);

			$this->session->set_flashdata('info','File uploaded successfully.');
			exit('success');
        }
	}
	public function RemoveFileDetail(){
		$id_kontrak_detail = $this->input->get('id_kontrak_detail');
		$urutanfile = $this->input->get('urutanfile');

		$kontrak_pusat_detail = $this->KontrakPusatModel->GetDetailData($id_kontrak_detail);

		if($kontrak_pusat_detail->nama_file != '' and $kontrak_pusat_detail->nama_file != 'null' and !is_null($kontrak_pusat_detail->nama_file) and $kontrak_pusat_detail->nama_file != '[]')
			$images = json_decode($kontrak_pusat_detail->nama_file);
		else
			$images = [];

		$nama_file = $images[$urutanfile];
		unlink($_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_pusat_detail/'.$nama_file);	

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
		$this->KontrakPusatModel->UpdateDetail($id_kontrak_detail, $data);

		$this->session->set_flashdata('info','File berhasil dihapus.');
		exit('success');
	}

	public function ShowDetail()
	{
		$id = $this->input->get('id');

		$kontrak_pusat = $this->KontrakPusatModel->Get($id);;
		$param['kontrak_pusat'] = $kontrak_pusat;

		// $param['kontrak_pusat_detail'] = $this->KontrakPusatModel->GetAllDetail($id);
		$param['kontrak_pusat_detail'] = array();
		$param['total_unit'] = $this->KontrakPusatModel->GetTotalUnitDetail($kontrak_pusat->id);
		$param['total_nilai'] = $this->KontrakPusatModel->GetTotalNilaiDetail($kontrak_pusat->id);

		$this->load->library('parser');
		$data = array(
	        'title' => 'Data Detail Kontrak Pusat',
	        'content-path' => 'PENGADAAN PUSAT / DATA DETAIL KONTRAK',
	        'content' => $this->load->view('kontrak-pusat-detail-index', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function AjaxGetAllDataDetail()
	{
		$bolehtambah = 0;
		$bolehedit = 0;
		$bolehhapus = 0;
		foreach($this->session->userdata('logged_in')->crud_items as $crud){
			if(rtrim($crud->crud_action) == 'TAMBAH DATA')
			  $bolehtambah = 1;
			if(rtrim($crud->crud_action) == 'EDIT DATA')
			  $bolehedit = 1;
			if(rtrim($crud->crud_action) == 'HAPUS DATA')
			  $bolehhapus = 1;
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

        $id_kontrak_pusat = $this->input->post('id_kontrak_pusat');

        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

		$totalData = $this->KontrakPusatModel->GetAllAjaxCountDetail($id_kontrak_pusat);
            
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
	        $posts = $this->KontrakPusatModel->GetAllForAjaxDetail($id_kontrak_pusat, $_POST['start'], $_POST['length'], $order, $dir, $filtercond);
            $totalFiltered = $this->KontrakPusatModel->GetFilterAjaxCountDetail($id_kontrak_pusat, $filtercond);
        }
        else {
            $search = $this->input->post('search')['value']; 

            $posts =  $this->KontrakPusatModel->GetSearchAjaxDetail($id_kontrak_pusat, $_POST['start'], $_POST['length'], $search, $order, $dir, $filtercond);

            $totalFiltered = $this->KontrakPusatModel->GetSearchAjaxCountDetail($id_kontrak_pusat, $search, $filtercond);
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
                	$tools .= "<a class='btn btn-xs btn-primary btn-sm' href='".base_url('KontrakPusat/EditDetail?id=').$post->id."'><i class='glyphicon glyphicon-pencil'></i></a><a class='btn btn-xs btn-info btn-sm' data-href='#' data-toggle='modal' data-record-title='".$post->no_kontrak."' data-target='#upload-modal' data-record-id='".$post->id."'><i class='glyphicon glyphicon-open-file'></i></a>";

                if($bolehhapus)
                	$tools .= "<a class='btn btn-xs btn-danger btn-sm' data-href='".base_url('KontrakPusat/doDeleteDetail?id=').$post->id."' data-toggle='modal' data-record-title='".$post->no_kontrak."' data-target='#confirm-delete'><i class='glyphicon glyphicon-trash'></i></a>";

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

		$param['kontrak_pusat'] = $this->KontrakPusatModel->Get($id);

		$this->load->library('parser');
		$this->load->model('ProvinsiModel');

		$param['provinsi'] = $this->ProvinsiModel->GetAll();

		$data = array(
	        'title' => 'Data Detail Kontrak Pusat',
	        'content-path' => 'PEGADAAN PUSAT / DATA DETAIL KONTRAK / TAMBAH DATA',
	        'content' => $this->load->view('kontrak-pusat-detail-add', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function doAddDetail()
	{	

		$id_kontrak_pusat = $this->input->post('id_kontrak_pusat');

		$kontrak_pusat = $this->KontrakPusatModel->Get($id_kontrak_pusat);
		$jumlah_barang_kontrak_pusat = $kontrak_pusat->jumlah_barang;

		$jumlah_kontrak_detail = $this->KontrakPusatModel->HitungJumlahKontrakDetail($id_kontrak_pusat);

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
			if( $jumlah_barang_rev_1 > ($jumlah_barang_kontrak_pusat - $jumlah_kontrak_detail) ){
				$this->session->set_flashdata('error','Jumlah barang detail tidak dapat melebihi jumlah barang kontrak.');
	 			redirect('KontrakPusat/AddDetail?id='.$id_kontrak_pusat);
			}
		}
		else if($status_alokasi == 'DATA ADENDUM 2'){
			if( $jumlah_barang_rev_2 > ($jumlah_barang_kontrak_pusat - $jumlah_kontrak_detail) ){
				$this->session->set_flashdata('error','Jumlah barang detail tidak dapat melebihi jumlah barang kontrak.');
	 			redirect('KontrakPusat/AddDetail?id='.$id_kontrak_pusat);
			}
		}
		else if($status_alokasi == 'DATA KONTRAK AWAL'){
			if( $jumlah_barang_detail > ($jumlah_barang_kontrak_pusat - $jumlah_kontrak_detail) ){
				$this->session->set_flashdata('error','Jumlah barang detail tidak dapat melebihi jumlah barang kontrak.');
	 			redirect('KontrakPusat/AddDetail?id='.$id_kontrak_pusat);
			}
		}

		
		
		if($id_kontrak_pusat == '' or $id_provinsi == '' or $id_kabupaten == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('KontrakPusat/AddDetail?id='.$id_kontrak_pusat);
		}
		else{
			$target_file_1 = '';
			$target_file_2 = '';
			$nama_file_adendum_1 = '';
			$nama_file_adendum_2 = '';

			if(file_exists($_FILES['nama_file_adendum_1']['tmp_name']) and is_uploaded_file($_FILES['nama_file_adendum_1']['tmp_name'])) {

		    	$target_file_1 = $_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_pusat/detail/'.basename($_FILES["nama_file_adendum_1"]["name"]);

		    	$nama_file_adendum_1 = basename($_FILES["nama_file_adendum_1"]["name"]);
				if (file_exists($target_file_1)) {
				    $this->session->set_flashdata('error','Sorry. File already exists.');
		     		redirect('KontrakPusat/AddDetail?id='.$id_kontrak_pusat);
				}
				else{
					if(!move_uploaded_file($_FILES["nama_file_adendum_1"]["tmp_name"], $target_file_1)){
						$this->session->set_flashdata('error','Image failed to upload.');
	     				redirect('KontrakPusat/AddDetail?id='.$id_kontrak_pusat);
					}
				}
			}

			if(file_exists($_FILES['nama_file_adendum_2']['tmp_name']) and is_uploaded_file($_FILES['nama_file_adendum_2']['tmp_name'])) {

		    	$target_file_2 = $_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_pusat/detail/'.basename($_FILES["nama_file_adendum_2"]["name"]);

		    	$nama_file_adendum_2 = basename($_FILES["nama_file_adendum_2"]["name"]);
				if (file_exists($target_file_2)) {
				    $this->session->set_flashdata('error','Sorry. File already exists.');
		     		redirect('KontrakPusat/AddDetail?id='.$id_kontrak_pusat);
				}
				else{
					if(!move_uploaded_file($_FILES["nama_file_adendum_2"]["tmp_name"], $target_file_2)){
						unlink($taget_file_1);
						$this->session->set_flashdata('error','Image failed to upload.');
	     				redirect('KontrakPusat/AddDetail?id='.$id_kontrak_pusat);
					}
				}
			}

			$data = array(
				'id_kontrak_pusat' => $id_kontrak_pusat,
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
			$this->KontrakPusatModel->InsertDetail($data);
			$this->session->set_flashdata('info','Data inserted successfully.');
			redirect('KontrakPusat/ShowDetail?id='.$id_kontrak_pusat);
		}
		
	}

	public function EditDetail()
	{
		$id = $this->input->get('id');

		$kontrak_pusat_detail = $this->KontrakPusatModel->GetDetailData($id);
		$param['kontrak_pusat_detail'] = $kontrak_pusat_detail;
		$param['kontrak_pusat'] = $this->KontrakPusatModel->Get($kontrak_pusat_detail->id_kontrak_pusat);

		$this->load->library('parser');
		$this->load->model('ProvinsiModel');

		$param['provinsi'] = $this->ProvinsiModel->GetAll();

		$data = array(
	        'title' => 'Data Detail Kontrak Pusat',
	        'content-path' => 'PEGADAAN PUSAT / DATA DETAIL KONTRAK / UBAH DATA',
	        'content' => $this->load->view('kontrak-pusat-detail-edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function doEditDetail()
	{	

		$id = $this->input->post('id');

		$kontrak_pusat_detail = $this->KontrakPusatModel->GetDetailData($id);
		$id_kontrak_pusat = $kontrak_pusat_detail->id_kontrak_pusat;

		$kontrak_pusat = $this->KontrakPusatModel->Get($id_kontrak_pusat);
		$jumlah_barang_kontrak_pusat = $kontrak_pusat->jumlah_barang;
		$jumlah_kontrak_detail = $this->KontrakPusatModel->HitungJumlahKontrakDetailSelainDetailIni($id_kontrak_pusat, $id);

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
		echo $status_alokasi;
		exit(1);
		if($status_alokasi == 'DATA ADENDUM 1'){
			if( $jumlah_barang_rev_1 > ($jumlah_barang_kontrak_pusat - $jumlah_kontrak_detail) ){
				$this->session->set_flashdata('error','Jumlah barang detail tidak dapat melebihi jumlah barang kontrak.');
	 			redirect('KontrakPusat/EditDetail?id='.$id);
			}
		}
		else if($status_alokasi == 'DATA ADENDUM 2'){
			if( $jumlah_barang_rev_2 > ($jumlah_barang_kontrak_pusat - $jumlah_kontrak_detail) ){
				$this->session->set_flashdata('error','Jumlah barang detail tidak dapat melebihi jumlah barang kontrak.');
	 			redirect('KontrakPusat/EditDetail?id='.$id);
			}
		}
		else if($status_alokasi == 'DATA KONTRAK AWAL'){
			if( $jumlah_barang_detail > ($jumlah_barang_kontrak_pusat - $jumlah_kontrak_detail) ){
				$this->session->set_flashdata('error','Jumlah barang detail tidak dapat melebihi jumlah barang kontrak.');
	 			redirect('KontrakPusat/EditDetail?id='.$id);
			}
		}

		
		
		if($id_kontrak_pusat == '' or $id_provinsi == '' or $id_kabupaten == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('KontrakPusat/EditDetail?id='.$id);
		}
		else{
			$target_file_1 = '';
			$target_file_2 = '';
			$nama_file_adendum_1 = '';
			$nama_file_adendum_2 = '';

			if(file_exists($_FILES['nama_file_adendum_1']['tmp_name']) and is_uploaded_file($_FILES['nama_file_adendum_1']['tmp_name'])) {

		    	$target_file_1 = $_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_pusat/detail/'.basename($_FILES["nama_file_adendum_1"]["name"]);

		    	$nama_file_adendum_1 = basename($_FILES["nama_file_adendum_1"]["name"]);
				if (file_exists($target_file_1)) {
				    $this->session->set_flashdata('error','Sorry. File already exists.');
		     		redirect('KontrakPusat/EditDetail?id='.$id);
				}
				else{
					if(!move_uploaded_file($_FILES["nama_file_adendum_1"]["tmp_name"], $target_file_1)){
						$this->session->set_flashdata('error','Image failed to upload.');
	     				redirect('KontrakPusat/EditDetail?id='.$id);
					}
					else{
						if($kontrak_pusat_detail->nama_file_adendum_1 != ''){
							unlink($_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_pusat/detail/'.$kontrak_pusat_detail->nama_file_adendum_1);
						}
					}
				}
			}

			if(file_exists($_FILES['nama_file_adendum_2']['tmp_name']) and is_uploaded_file($_FILES['nama_file_adendum_2']['tmp_name'])) {

		    	$target_file_2 = $_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_pusat/detail/'.basename($_FILES["nama_file_adendum_2"]["name"]);

		    	$nama_file_adendum_2 = basename($_FILES["nama_file_adendum_2"]["name"]);
				if (file_exists($target_file_2)) {
				    $this->session->set_flashdata('error','Sorry. File already exists.');
		     		redirect('KontrakPusat/EditDetail?id='.$id);
				}
				else{
					if(!move_uploaded_file($_FILES["nama_file_adendum_2"]["tmp_name"], $target_file_2)){
						unlink($taget_file_1);
						$this->session->set_flashdata('error','Image failed to upload.');
	     				redirect('KontrakPusat/EditDetail?id='.$id);
					}
					else{
						if($kontrak_pusat_detail->nama_file_adendum_2 != ''){
							unlink($_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_pusat/detail/'.$kontrak_pusat_detail->nama_file_adendum_2);
						}
					}
				}
			}

			$data = array(
				'id_kontrak_pusat' => $id_kontrak_pusat,
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
			$this->KontrakPusatModel->UpdateDetail($id, $data);
			$this->session->set_flashdata('info','Data updated successfully.');
			redirect('KontrakPusat/ShowDetail?id='.$id_kontrak_pusat);
		}
		
	}

	public function doDeleteDetail()
	{	
		$id = $this->input->get('id');

		$kontrak_pusat_detail = $this->KontrakPusatModel->GetDetailData($id);
		$id_kontrak_pusat = $kontrak_pusat_detail->id_kontrak_pusat;

		// if($kontrak_pusat->nama_file != ''){
		// 	unlink($_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_pusat/'.$kontrak_pusat->nama_file);
		// }
		if($kontrak_pusat_detail->nama_file != '' and $kontrak_pusat_detail->nama_file != 'null' and !is_null($kontrak_pusat_detail->nama_file) and $kontrak_pusat_detail->nama_file != '[]')
			$images = json_decode($kontrak_pusat_detail->nama_file);
		else
			$images = [];

		foreach($images as $image){
			unlink($_SERVER['DOCUMENT_ROOT'].'/upload/kontrak_pusat_detail/'.$image);	
		}

		$this->KontrakPusatModel->DeleteDetail($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('KontrakPusat/ShowDetail?id='.$id_kontrak_pusat);
		
	}
}
	