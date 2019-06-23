<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BASTBProvinsi extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('BASTBProvinsiModel');
		$this->load->model('PenyediaProvinsiModel');
		$this->load->model('JenisBarangProvinsiModel');
		$this->load->model('ProvinsiModel');
		$this->load->model('AlokasiProvinsiModel');
		$this->load->helper('url');
		$this->load->library('xlsxwriter');
	}

	public function index()
	{
		$this->load->library('parser');
		$param['bastb_provinsi'] = array();
		$param['total_unit'] = $this->BASTBProvinsiModel->GetTotalUnit();
		$param['total_nilai'] = $this->BASTBProvinsiModel->GetTotalNilai();
		$param['total_unit_alokasi'] = $this->AlokasiProvinsiModel->GetTotalUnit();
		$param['total_nilai_alokasi'] = $this->AlokasiProvinsiModel->GetTotalNilai();

		$data = array(
	        'title' => 'Berita Acara Serah Terima Barang (BASTB)',
	        'content-path' => 'PENGADAAN TP PROVINSI / BASTB',
	        'content' => $this->load->view('bastb-provinsi-index', $param, TRUE),
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
            1 =>'kelompok_penerima',
            2 => 'no_bastb',
            3 => 'tanggal',
            4 => 'pihak_penyerah',
            5 => 'nama_penyerah',
            6 => 'jabatan_penyerah',
            7 => 'notelp_penyerah',
            8 => 'alamat_penyerah',
            9 => 'prov_peny.nama_provinsi',
            10 => 'kab_peny.nama_kabupaten',
            11 => 'pihak_penerima',
            12 => 'nama_penerima',
            13 => 'jabatan_penerima',
            14 => 'notelp_penerima',
            15 => 'alamat_penerima',
            16 => 'prov_pene.nama_provinsi',
            17 => 'kab_pene.nama_kabupaten',
            18 => 'kec_pene.nama_kecamatan',
            19 => 'kel_pene.nama_kelurahan',
            20 => 'no_kontrak',
            21 => 'nama_barang',
            22 => 'merk',
            23 => 'jumlah_barang',
            24 => 'nilai_barang',
            25 => 'nama_mengetahui',
            26 => 'jabatan_mengetahui',
            27 => 'id',
            28 => 'id',
        );

        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

		$totalData = $this->BASTBProvinsiModel->GetAllAjaxCount();
            
        $totalFiltered = $totalData;

        $filtercond = '';
        for($i=0;$i<count($columns);$i++){
        	if($i < 27){
        		if(!empty($this->input->post('columns')[$i]['search']['value'])){
	        		$search = $this->input->post('columns')[$i]['search']['value'];
	        		$filtercond  .= " and ".$columns[$i]." LIKE '%".$search."%'"; 
	        	}
        	}
        	else{
        		if($i==27){
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
            $posts = $this->BASTBProvinsiModel->GetAllForAjax($_POST['start'], $_POST['length'], $order, $dir, $filtercond);
            $totalFiltered = $this->BASTBProvinsiModel->GetFilterAjaxCount($filtercond);
        }
        else {
            $search = $this->input->post('search')['value']; 

            $posts =  $this->BASTBProvinsiModel->GetSearchAjax($_POST['start'], $_POST['length'], $search, $order, $dir, $filtercond);

            $totalFiltered = $this->BASTBProvinsiModel->GetSearchAjaxCount($search, $filtercond);
        }

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData['tahun_anggaran'] = $post->tahun_anggaran;
                $nestedData['kelompok_penerima'] = $post->kelompok_penerima;
                $nestedData['no_bastb'] = $post->no_bastb;
                $nestedData['tanggal'] = date('d-m-Y', strtotime($post->tanggal));

                $nestedData['pihak_penyerah'] = $post->pihak_penyerah;
                $nestedData['nama_penyerah'] = $post->nama_penyerah;
                $nestedData['jabatan_penyerah'] = $post->jabatan_penyerah;
                $nestedData['notelp_penyerah'] = $post->notelp_penyerah;
                $nestedData['alamat_penyerah'] = $post->alamat_penyerah;
                $nestedData['provinsi_penyerah'] = $post->provinsi_penyerah;
                $nestedData['kabupaten_penyerah'] = $post->kabupaten_penyerah;

                $nestedData['pihak_penerima'] = $post->pihak_penerima;
                $nestedData['nama_penerima'] = $post->nama_penerima;
                $nestedData['jabatan_penerima'] = $post->jabatan_penerima;
                $nestedData['notelp_penerima'] = $post->notelp_penerima;
                $nestedData['alamat_penerima'] = $post->alamat_penerima;
                $nestedData['provinsi_penerima'] = $post->provinsi_penerima;
                $nestedData['kabupaten_penerima'] = $post->kabupaten_penerima;
                $nestedData['kecamatan_penerima'] = $post->kecamatan_penerima;
                $nestedData['kelurahan_penerima'] = $post->kelurahan_penerima;

                $nestedData['no_kontrak'] = $post->no_kontrak;
                $nestedData['nama_barang'] = $post->nama_barang;
                $nestedData['merk'] = $post->merk;
                $nestedData['jumlah_barang'] = number_format($post->jumlah_barang, 0);
                $nestedData['nilai_barang'] = number_format($post->nilai_barang, 0);

                $nestedData['nama_mengetahui'] = $post->nama_mengetahui;
                $nestedData['jabatan_mengetahui'] = $post->jabatan_mengetahui;

                $nestedData['ketfoto'] = (($post->nama_file != '' and $post->nama_file != '[]' and $post->nama_file != 'null' and !is_null($post->nama_file)) ?  'TERSEDIA' : 'TDK TERSEDIA');

                $tools = "";
                $tools .= "<a class='btn btn-xs btn-success btn-sm'><i class='glyphicon glyphicon-zoom-in' onclick='LoadData(".$post->id.")'></i></a>";

                if($bolehedit)
                	$tools .= "<a class='btn btn-xs btn-primary btn-sm' href='".base_url('BASTBProvinsi/Edit?id=').$post->id."'><i class='glyphicon glyphicon-pencil'></i></a><a class='btn btn-xs btn-info btn-sm' data-href='#' data-toggle='modal' data-record-title='".$post->no_bastb."' data-target='#upload-modal' data-record-id='".$post->id."'><i class='glyphicon glyphicon-open-file'></i></a>";

                if($bolehhapus)
                	$tools .= "<a class='btn btn-xs btn-danger btn-sm' data-href='".base_url('BASTBProvinsi/doDelete?id=').$post->id."' data-toggle='modal' data-record-title='".$post->no_bastb."' data-target='#confirm-delete'><i class='glyphicon glyphicon-trash'></i></a>";

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
		$param['jenis_barang_provinsi'] = $this->JenisBarangProvinsiModel->GetAll();
		$param['provinsi'] = $this->ProvinsiModel->GetAll();
		$data = array(
	        'title' => 'INPUT LAPORAN BASTB',
	        'content-path' => 'PEGADAAN TP PROVINSI / BASTB / TAMBAH DATA',
	        'content' => $this->load->view('bastb-provinsi-add', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function doAdd()
	{	

		$tahun_anggaran = $this->input->post('tahun_anggaran');
		$kelompok_penerima = $this->input->post('kelompok_penerima');

		$no_bastb = $this->input->post('no_bastb');
		$tanggal = $this->input->post('tanggal');

		$pihak_penyerah = $this->input->post('pihak_penyerah');
		$nama_penyerah = $this->input->post('nama_penyerah');
		$jabatan_penyerah = $this->input->post('jabatan_penyerah');
		$notelp_penyerah = $this->input->post('notelp_penyerah');
		$alamat_penyerah = $this->input->post('alamat_penyerah');
		$id_provinsi_penyerah = $this->input->post('id_provinsi_penyerah');
		$id_kabupaten_penyerah = $this->input->post('id_kabupaten_penyerah');

		$pihak_penerima = $this->input->post('pihak_penerima');
		$nama_penerima = $this->input->post('nama_penerima');
		$jabatan_penerima = $this->input->post('jabatan_penerima');
		$notelp_penerima = $this->input->post('notelp_penerima');
		$alamat_penerima = $this->input->post('alamat_penerima');
		$id_provinsi_penerima = $this->input->post('id_provinsi_penerima');
		$id_kabupaten_penerima = $this->input->post('id_kabupaten_penerima');
		$id_kecamatan_penerima = $this->input->post('id_kecamatan_penerima');
		$id_kelurahan_penerima = $this->input->post('id_kelurahan_penerima');

		$no_kontrak = $this->input->post('no_kontrak');
		$nama_barang = $this->input->post('nama_barang');
		$merk = $this->input->post('merk');
		$jumlah_barang = (float)str_replace(',', '', $this->input->post('jumlah_barang'));
		$nilai_barang = (float)str_replace(',', '', $this->input->post('nilai_barang'));

		$nama_mengetahui = $this->input->post('nama_mengetahui');
		$jabatan_mengetahui = $this->input->post('jabatan_mengetahui');

		if($tahun_anggaran == '' or $pihak_penyerah == '' or $no_kontrak == '' or $nama_barang == '' or $merk == '' or$jumlah_barang == '' or $nilai_barang == '' or $tanggal == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('BASTBProvinsi/Add');
		}
		else{
			$tanggal = DateTime::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');

			$data = array(
				'tahun_anggaran' => $tahun_anggaran,
				'kelompok_penerima' => $kelompok_penerima,
				'no_bastb' => $no_bastb,
				'tanggal' => $tanggal,
				'pihak_penyerah' => $pihak_penyerah,
				'nama_penyerah' => $nama_penyerah,
				'jabatan_penyerah' => $jabatan_penyerah,
				'notelp_penyerah' => $notelp_penyerah,
				'alamat_penyerah' => $alamat_penyerah,
				'id_provinsi_penyerah' => $id_provinsi_penyerah,
				'id_kabupaten_penyerah' => $id_kabupaten_penyerah,
				'pihak_penerima' => $pihak_penerima,
				'nama_penerima' => $nama_penerima,
				'jabatan_penerima' => $jabatan_penerima,
				'notelp_penerima' => $notelp_penerima,
				'alamat_penerima' => $alamat_penerima,
				'id_provinsi_penerima' => $id_provinsi_penerima,
				'id_kabupaten_penerima' => $id_kabupaten_penerima,
				'id_kecamatan_penerima' => $id_kecamatan_penerima,
				'id_kelurahan_penerima' => $id_kelurahan_penerima,
				'no_kontrak' => strtoupper($no_kontrak),
				'nama_barang' => $nama_barang,
				'merk' => $merk,
				'jumlah_barang' => $jumlah_barang,
				'nilai_barang' => $nilai_barang,
				'nama_mengetahui' => $nama_mengetahui,
				'jabatan_mengetahui' => $jabatan_mengetahui,
				'nama_file' => '',
				'created_by' => $this->session->userdata('logged_in')->id_pengguna,
				'created_at' => NOW,
			);
			$this->BASTBProvinsiModel->Insert($data);
			$this->session->set_flashdata('info','Data inserted successfully.');
			redirect('BASTBProvinsi');

				
		}
	 	
	}


	public function Test()
	{
	    

			$id = $this->input->post('id_bastb');

			$bastb_provinsi = $this->BASTBProvinsiModel->Get($id);

			$kodefile_upload = strtotime(NOW);

			if($bastb_provinsi->nama_file != '' and $bastb_provinsi->nama_file != 'null' and !is_null($bastb_provinsi->nama_file) and $bastb_provinsi->nama_file != '[]')
				$nama_file = json_decode($bastb_provinsi->nama_file);
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
			        $targetFile =  $target_file = $_SERVER['DOCUMENT_ROOT'].'/upload/bastb_provinsi/'.$kodefile_upload.basename($_FILES['file']['name'][$key]);
		        	move_uploaded_file($tempFile, $target_file);

			    }

			    $nama_file = json_encode($nama_file);
	        	$data = array(
					'nama_file' => $nama_file,
					'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
					'updated_at' => NOW,
				);
				$this->BASTBProvinsiModel->Update($id, $data);

				$this->session->set_flashdata('info','File uploaded successfully.');
				exit('success');
	        }

		    
	}

	public function RemoveImage()
	{
	    
		$id = $this->input->get('id_bastb');
		$urutanfile = $this->input->get('urutanfile');

		$bastb_provinsi = $this->BASTBProvinsiModel->Get($id);

		if($bastb_provinsi->nama_file != '' and $bastb_provinsi->nama_file != 'null' and !is_null($bastb_provinsi->nama_file) and $bastb_provinsi->nama_file != '[]')
			$images = json_decode($bastb_provinsi->nama_file);
		else
			$images = [];

		$nama_file = $images[$urutanfile];
		unlink($_SERVER['DOCUMENT_ROOT'].'/upload/bastb_provinsi/'.$nama_file);	

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
		$this->BASTBProvinsiModel->Update($id, $data);

		$this->session->set_flashdata('info','File berhasil dihapus.');
		exit('success');
	}

	public function Edit()
	{
		$id = $this->input->get('id');

		$param['bastb_provinsi'] = $this->BASTBProvinsiModel->Get($id);

		$param['penyedia_provinsi'] = $this->PenyediaProvinsiModel->GetAll();
		$param['jenis_barang_provinsi'] = $this->JenisBarangProvinsiModel->GetAll();
		$param['provinsi'] = $this->ProvinsiModel->GetAll();

		$this->load->library('parser');
		$data = array(
	        'title' => 'UBAH LAPORAN BASTB',
	        'content-path' => 'PEGADAAN TP PROVINSI / BASTB / UBAH DATA',
	        'content' => $this->load->view('bastb-provinsi-edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);

	}

	public function doEdit()
	{	
		$id = $this->input->post('id');

		$tahun_anggaran = $this->input->post('tahun_anggaran');
		$kelompok_penerima = $this->input->post('kelompok_penerima');

		$no_bastb = $this->input->post('no_bastb');
		$tanggal = $this->input->post('tanggal');

		$pihak_penyerah = $this->input->post('pihak_penyerah');
		$nama_penyerah = $this->input->post('nama_penyerah');
		$jabatan_penyerah = $this->input->post('jabatan_penyerah');
		$notelp_penyerah = $this->input->post('notelp_penyerah');
		$alamat_penyerah = $this->input->post('alamat_penyerah');
		$id_provinsi_penyerah = $this->input->post('id_provinsi_penyerah');
		$id_kabupaten_penyerah = $this->input->post('id_kabupaten_penyerah');

		$pihak_penerima = $this->input->post('pihak_penerima');
		$nama_penerima = $this->input->post('nama_penerima');
		$jabatan_penerima = $this->input->post('jabatan_penerima');
		$notelp_penerima = $this->input->post('notelp_penerima');
		$alamat_penerima = $this->input->post('alamat_penerima');
		$id_provinsi_penerima = $this->input->post('id_provinsi_penerima');
		$id_kabupaten_penerima = $this->input->post('id_kabupaten_penerima');
		$id_kecamatan_penerima = $this->input->post('id_kecamatan_penerima');
		$id_kelurahan_penerima = $this->input->post('id_kelurahan_penerima');

		$no_kontrak = $this->input->post('no_kontrak');
		$nama_barang = $this->input->post('nama_barang');
		$merk = $this->input->post('merk');
		$jumlah_barang = (float)str_replace(',', '', $this->input->post('jumlah_barang'));
		$nilai_barang = (float)str_replace(',', '', $this->input->post('nilai_barang'));

		$nama_mengetahui = $this->input->post('nama_mengetahui');
		$jabatan_mengetahui = $this->input->post('jabatan_mengetahui');

		if($tahun_anggaran == '' or $pihak_penyerah == '' or $no_kontrak == '' or $nama_barang == '' or $merk == '' or$jumlah_barang == '' or $nilai_barang == '' or $tanggal == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('BASTBProvinsi/Edit?id='.$id);
		}
		else{
			$tanggal = DateTime::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');

			$data = array(
				'tahun_anggaran' => $tahun_anggaran,
				'kelompok_penerima' => $kelompok_penerima,
				'no_bastb' => $no_bastb,
				'tanggal' => $tanggal,
				'pihak_penyerah' => $pihak_penyerah,
				'nama_penyerah' => $nama_penyerah,
				'jabatan_penyerah' => $jabatan_penyerah,
				'notelp_penyerah' => $notelp_penyerah,
				'alamat_penyerah' => $alamat_penyerah,
				'id_provinsi_penyerah' => $id_provinsi_penyerah,
				'id_kabupaten_penyerah' => $id_kabupaten_penyerah,
				'pihak_penerima' => $pihak_penerima,
				'nama_penerima' => $nama_penerima,
				'jabatan_penerima' => $jabatan_penerima,
				'notelp_penerima' => $notelp_penerima,
				'alamat_penerima' => $alamat_penerima,
				'id_provinsi_penerima' => $id_provinsi_penerima,
				'id_kabupaten_penerima' => $id_kabupaten_penerima,
				'id_kecamatan_penerima' => $id_kecamatan_penerima,
				'id_kelurahan_penerima' => $id_kelurahan_penerima,
				'no_kontrak' => strtoupper($no_kontrak),
				'nama_barang' => $nama_barang,
				'merk' => $merk,
				'jumlah_barang' => $jumlah_barang,
				'nilai_barang' => $nilai_barang,
				'nama_mengetahui' => $nama_mengetahui,
				'jabatan_mengetahui' => $jabatan_mengetahui,
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->BASTBProvinsiModel->Update($id, $data);
			$this->session->set_flashdata('info','Data updated successfully.');
			redirect('BASTBProvinsi');

				
		}
		
	}

	public function doDelete()
	{	
		$id = $this->input->get('id');

		$bastb_provinsi = $this->BASTBProvinsiModel->Get($id);

		if($bastb_provinsi->nama_file != '' and $bastb_provinsi->nama_file != 'null' and !is_null($bastb_provinsi->nama_file) and $bastb_provinsi->nama_file != '[]')
			$images = json_decode($bastb_provinsi->nama_file);
		else
			$images = [];

		foreach($images as $image){
			unlink($_SERVER['DOCUMENT_ROOT'].'/upload/bastb_provinsi/'.$image);	
		}
		
		$this->BASTBProvinsiModel->Delete($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('BASTBProvinsi');
		
	}

	public function GetBASTB()
	{
		$id_bastb = $this->input->get('id_bastb');

		$data = $this->BASTBProvinsiModel->Get($id_bastb);
		$hasil = json_encode($data);
		header('HTTP/1.1: 200');
		header('Status: 200');
		header('Content-Length: '.strlen($hasil));
		exit($hasil);
		
	}

	public function doExport() {
		$columns = array( 
			0 =>'tahun_anggaran', 
			1 =>'kelompok_penerima',
			2 => 'no_bastb',
			3 => 'tanggal',
			4 => 'pihak_penyerah',
			5 => 'nama_penyerah',
			6 => 'jabatan_penyerah',
			7 => 'notelp_penyerah',
			8 => 'alamat_penyerah',
			9 => 'prov_peny.nama_provinsi',
			10 => 'kab_peny.nama_kabupaten',
			11 => 'pihak_penerima',
			12 => 'nama_penerima',
			13 => 'jabatan_penerima',
			14 => 'notelp_penerima',
			15 => 'alamat_penerima',
			16 => 'prov_pene.nama_provinsi',
			17 => 'kab_pene.nama_kabupaten',
			18 => 'kec_pene.nama_kecamatan',
			19 => 'kel_pene.nama_kelurahan',
			20 => 'no_kontrak',
			21 => 'nama_barang',
			22 => 'merk',
			23 => 'jumlah_barang',
			24 => 'nilai_barang',
			25 => 'nama_mengetahui',
			26 => 'jabatan_mengetahui',
			27 => 'id',
			28 => 'id',
		);

		$order = $columns[$this->input->post('order')[0]['column']];
		$dir = $this->input->post('order')[0]['dir'];
		$filtercond = '';
		for($i=0;$i<count($columns);$i++){
			if($i < 27){
				if(!empty($this->input->post('columns')[$i]['search']['value'])){
					$search = $this->input->post('columns')[$i]['search']['value'];
					$filtercond  .= " and ".$columns[$i]." LIKE '%".$search."%'"; 
				}
			}
			else{
				if($i==27){
					if($this->input->post('columns')[$i]['search']['value'] == 'TERSEDIA'){
						$filtercond .= " and (nama_file != '' and nama_file != 'null' and nama_file is not null)";
					}
					else if($this->input->post('columns')[$i]['search']['value'] == 'TDKTERSEDIA'){
						$filtercond .= " and (nama_file = '' or nama_file = 'null' or nama_file is null ) ";
					}
				}
			}					
		}
		
		$data = array();
		if(empty($this->input->post('search')['value'])) {            
			$data = $this->BASTBProvinsiModel->ExportAllForAjax($order, $dir, $filtercond);
		}
		else {
			$search = $this->input->post('search')['value']; 
			$data =  $this->BASTBProvinsiModel->ExportSearchAjax($search, $order, $dir, $filtercond);
		}

		$visible_columns = $this->input->post('visible_columns');
		$visible_header_columns = array();
		foreach($visible_columns as $value) {
			switch($value['title']) {
				case 'Tahun Anggaran':
				case 'Jumlah': 
					$visible_header_columns[$value['title']] = 'integer';
					break;
				case 'Nilai (Rp)': 
					$visible_header_columns[$value['title']] = '#,##0';
					break;
				default :
					$visible_header_columns[$value['title']] = 'string';
			}
    }
		$this->xlsxwriter->writeSheetHeader('BASTB Provinsi', $visible_header_columns, array('font-style'=>'bold'));

		foreach($data as $row) {
			$newRow = array();
			foreach($visible_columns as $key => $value) {
				$defaultValue = '';
				if(isset($row[$value['id']])) {
					$defaultValue = $row[$value['id']];
				}
				switch($value['id']) {
					case 'tahun_anggaran': 
						$newRow[$key] = $row['tahun_anggaran'];
						break;
					case 'ketfoto': 
						$newRow[$key] = $row['nama_file'] === "" ? "TDK TERSEDIA" : "TERSEDIA";
						break;
					default: 
						$newRow[$key] = $defaultValue;
				}
			}
			$this->xlsxwriter->writeSheetRow('BASTB Provinsi', $newRow);
		}

		$uniq_id = substr(md5(uniqid(rand(), true)), 0, 5);
		$file = "upload/BASTB App Data BASTB Provinsi - $uniq_id.xlsx";
		$this->xlsxwriter->writeToFile($file);

		header('Content-Type: application/json');
		echo json_encode(array('filename' => base_url().'BASTBProvinsi/doDownload?filename='.$file));
	}

	public function doDownload() {
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
}
	