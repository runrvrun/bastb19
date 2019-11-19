<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kontrak_provinsi extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('Kontrak_provinsi_model');
		$this->load->model('ProvinsiModel');
		$this->load->model('PenyediaProvinsiModel');
		$this->load->model('JenisBarangProvinsiModel');
	}

	public function index()
	{
		$this->load->library('parser');
		$param['kontrak_provinsi'] = $this->Kontrak_provinsi_model->get();
		$param['total_unit'] = $this->Kontrak_provinsi_model->total_unit();
		$param['total_nilai'] = $this->Kontrak_provinsi_model->total_nilai();
		$param['total_kontrak'] = $this->Kontrak_provinsi_model->total_kontrak();
		$param['total_merk'] = $this->Kontrak_provinsi_model->total_merk();
		$data = array(
	        'title' => 'Data Kontrak Provinsi',
	        'content-path' => 'PENGADAAN PROVINSI / DATA KONTRAK',
	        'content' => $this->load->view('kontrak-provinsi/index', $param, TRUE),
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

		$columns = array( 
            0 =>'nama_provinsi', 
            1 =>'tahun_anggaran', 
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
            13 => 'id',
            14 => 'id',
        );

		$order = $columns[$this->input->post('order')[0]['column']];
		if($order == 'cukup/kurang'){
			$order = '';
		}
        $dir = $this->input->post('order')[0]['dir'];

		$totalData = $this->Kontrak_provinsi_model->get();
		$totalData = count($totalData);
            
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
        				$filtercond .= " and (ifnull(jumlah_barang, 0) - ifnull(jumlah_alokasi, 0) = 0  )";
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

		$search = $this->input->post('search')['value']; 
		$posts_all_search =  $this->Kontrak_provinsi_model->get(null, null, null, null, null, $filtercond, $search);
		$totalFiltered = count($posts_all_search);
		$posts =  $this->Kontrak_provinsi_model->get(null,$_POST['start'], $_POST['length'], $order, $dir, $filtercond, $search);

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData['nama_provinsi'] = $post->nama_provinsi;
                $nestedData['tahun_anggaran'] = $post->tahun_anggaran;
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

                $nestedData['status'] = (($post->jumlah_barang - $post->jumlah_alokasi == 0) ? '<a class="btn btn-success">CUKUP</a>' : '<a class="btn btn-danger">KURANG</a>');
                $nestedData['ketfoto'] = (($post->nama_file != '' and $post->nama_file != '[]' and $post->nama_file != 'null' and !is_null($post->nama_file)) ?  '<a class="btn btn-success"><i class="glyphicon glyphicon-ok"></i></a>' : '<a class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i></a>');
            
				$tools = '<div class="dropdown">
				<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				  -- Pilih menu --
				</button>
				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					<a class="dropdown-item" href="'.base_url('Alokasi_provinsi/index?id_kontrak_provinsi=').$post->id.'">Input Alokasi</a>
                	<a class="dropdown-item" href="#" data-href="#" data-toggle="modal" data-record-title="'.$post->id.'" data-target="#upload-modal" data-record-id="'.$post->id.'">Upload Dokumen Kontrak</a>
                    <a class="dropdown-item" href="#" onclick="LoadData('.$post->id.')">Lihat Data Kontrak</a>';
                if($bolehedit)
				$tools .= '<a class="dropdown-item" href="'.base_url('Kontrak_provinsi/edit?id=').$post->id.'">Ubah Data Kontrak</a>';
				$tools .= '<a class="dropdown-item" href="'.base_url('Rekap_kontrak_provinsi/index?id_kontrak_provinsi=').$post->id.'">Lihat Rekap</a>';
                if($bolehhapus)
				$tools .= '<a class="dropdown-item"  href="#" data-toggle="modal" data-record-title="'.$post->id.'" data-target="#confirm-delete" data-href="'.base_url('Kontrak_provinsi/destroy/').$post->id.'">Hapus Data Kontrak</a>
				</div>
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

	public function create()
	{
		$this->load->library('parser');
		$param['provinsi'] = $this->ProvinsiModel->GetAll();
		$param['penyedia_provinsi'] = $this->PenyediaProvinsiModel->GetAll();
		$param['jenis_barang_provinsi'] = $this->JenisBarangProvinsiModel->GetAll();
        if(isset($this->session->userdata('logged_in')->id_provinsi)){
			$param['user_provinsi'] = $this->ProvinsiModel->Get($this->session->userdata('logged_in')->id_provinsi);
		}

		$data = array(
	        'title' => 'Data Kontrak Provinsi',
	        'content-path' => 'PENGADAAN PROVINSI / DATA KONTRAK / TAMBAH DATA',
	        'content' => $this->load->view('kontrak-provinsi/add', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function store()
	{	
		$id_provinsi = $this->input->post('id_provinsi');
		$tahun_anggaran = $this->input->post('tahun_anggaran');
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
     		redirect('Kontrak_provinsi/create');
		}
		else{
			$periode_mulai = DateTime::createFromFormat('d-m-Y', $periode_mulai)->format('Y-m-d');
			$periode_selesai = DateTime::createFromFormat('d-m-Y', $periode_selesai)->format('Y-m-d');

			$data = array(
				'id_provinsi' => $id_provinsi,
				'tahun_anggaran' => $tahun_anggaran,
				'id_penyedia_provinsi' => $id_penyedia_provinsi,
				'no_kontrak' => strtoupper($no_kontrak),
				'periode_mulai' => $periode_mulai,
				'periode_selesai' => $periode_selesai,
				'nama_barang' => $nama_barang,
				'merk' => $merk,
				'jumlah_barang' => $jumlah_barang,
				'nilai_barang' => $nilai_barang,
				'created_by' => $this->session->userdata('logged_in')->id_pengguna,
				'created_at' => NOW,
			);
			$this->Kontrak_provinsi_model->store($data);
			$this->session->set_flashdata('info','Data inserted successfully.');
			redirect('Kontrak_provinsi');
		}
		
	}

	public function edit()
	{
		$id = $this->input->get('id');

		$param['penyedia_provinsi'] = $this->PenyediaProvinsiModel->GetAll();
		$param['jenis_barang_provinsi'] = $this->JenisBarangProvinsiModel->GetAll();

		$param['kontrak_provinsi'] = $this->Kontrak_provinsi_model->get($id);

		$this->load->library('parser');
		$data = array(
	        'title' => 'Data Kontrak Provinsi',
	        'content-path' => 'PENGADAAN PROVINSI / DATA KONTRAK / UBAH DATA',
	        'content' => $this->load->view('kontrak-provinsi/edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function update()
	{	
		$id = $this->input->post('id');

		$tahun_anggaran = $this->input->post('tahun_anggaran');
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
     		redirect('Kontrak_provinsi/edit?id='.$id);
		}
		else{			
			$kontrak_provinsi = $this->Kontrak_provinsi_model->get($id);
			$nama_file = $kontrak_provinsi->nama_file;

			if($removed_images){
				foreach($removed_images as $imgname){
					unlink($this->config->item('doc_root').'/upload/kontrak_provinsi/'.$imgname);
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
			$this->Kontrak_provinsi_model->Update($id, $data);
			$this->session->set_flashdata('info','Data updated successfully.');
			redirect('Kontrak_provinsi');
		}
		
	}

	public function destroy($id)
	{			
		$kontrak_provinsi = $this->Kontrak_provinsi_model->Get($id);
		if($kontrak_provinsi->nama_file != '' and $kontrak_provinsi->nama_file != 'null' and !is_null($kontrak_provinsi->nama_file) and $kontrak_provinsi->nama_file != '[]')
			$images = json_decode($kontrak_provinsi->nama_file);
		else
			$images = [];

		foreach($images as $image){
			unlink($this->config->item('doc_root').'/upload/kontrak_provinsi/'.$image);	
		}

		$this->Kontrak_provinsi_model->destroy($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('Kontrak_provinsi');		
	}
	
	public function remove_image()
	{
	    
		$id = $this->input->get('id_kontrak');
		$urutanfile = $this->input->get('urutanfile');

		$kontrak_provinsi = $this->Kontrak_provinsi_model->Get($id);

		if($kontrak_provinsi->nama_file != '' and $kontrak_provinsi->nama_file != 'null' and !is_null($kontrak_provinsi->nama_file) and $kontrak_provinsi->nama_file != '[]')
			$images = json_decode($kontrak_provinsi->nama_file);
		else
			$images = [];

		$nama_file = $images[$urutanfile];
		unlink($this->config->item('doc_root').'/upload/kontrak_provinsi/'.$nama_file);	

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
		$this->Kontrak_provinsi_model->Update($id, $data);

		$this->session->set_flashdata('info','File berhasil dihapus.');
		exit('success');
	}

	public function get_json()
	{
		$data = $this->Kontrak_provinsi_model->get($this->input->get('id'));
		$hasil = json_encode($data);
		header('HTTP/1.1: 200');
		header('Status: 200');
		header('Content-Length: '.strlen($hasil));
		exit($hasil);		
	}
	
    public function upload_file()
    {
		$id_kontrak_provinsi = $this->input->post('id_kontrak_provinsi');

		$kontrak_provinsi = $this->Kontrak_provinsi_model->get($id_kontrak_provinsi);

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
		        $targetFile =  $target_file = $this->config->item('doc_root').'/upload/kontrak_provinsi/'.$kodefile_upload.basename($_FILES['file']['name'][$key]);
	        	move_uploaded_file($tempFile, $target_file);

		    }

		    $nama_file = json_encode($nama_file);
        	$data = array(
				'nama_file' => $nama_file,
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->Kontrak_provinsi_model->update($id_kontrak_provinsi, $data);

			$this->session->set_flashdata('info','File uploaded successfully.');
			exit('success');
        }
    }
    
    public function remove_file()
    {
		$id_kontrak_provinsi = $this->input->get('id_kontrak_provinsi');
		$urutanfile = $this->input->get('urutanfile');

		$kontrak_provinsi = $this->Kontrak_provinsi_model->get($id_kontrak_provinsi);

		if($kontrak_provinsi->nama_file != '' and $kontrak_provinsi->nama_file != 'null' and !is_null($kontrak_provinsi->nama_file) and $kontrak_provinsi->nama_file != '[]')
			$images = json_decode($kontrak_provinsi->nama_file);
		else
			$images = [];

		$nama_file = $images[$urutanfile];
		unlink($this->config->item('doc_root').'/upload/kontrak_provinsi/'.$nama_file);	

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
		$this->Kontrak_provinsi_model->update($id_kontrak_provinsi, $data);

		$this->session->set_flashdata('info','File berhasil dihapus.');
		exit('success');
	}

	public function termin()
	{
		$param['cols'] = array(
			array("column" => "uang_muka", "caption" => "Uang Muka", "dbcolumn" => "uang_muka"),
			array("column" => "termin_1", "caption" => "Termin 1 (%)", "dbcolumn" => "termin_1"),
			array("column" => "termin_2", "caption" => "Termin 2 (%)", "dbcolumn" => "termin_2"),
			array("column" => "termin_3", "caption" => "Termin 3 (%)", "dbcolumn" => "termin_3"),
			array("column" => "termin_4", "caption" => "Termin 4 (%)", "dbcolumn" => "termin_4"),
			array("column" => "termin_5", "caption" => "Termin 5 (%)", "dbcolumn" => "termin_5")
		);

		$id = $this->input->get('id');

		$param['kontrak_provinsi'] = $this->Kontrak_provinsi_model->get($id);

		$this->load->library('parser');
		$data = array(
	        'title' => 'Setting Termin Pembayaran',
	        'content-path' => 'PENGADAAN PROVINSI / DATA KONTRAK / SETTING TERMIN PEMBAYARAN',
	        'content' => $this->load->view('kontrak-provinsi/edit_termin', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function update_termin()
	{	
		$cols = array('jumlah_termin','uang_muka','termin_1','termin_2','termin_3','termin_4','termin_5');
		$id = $this->input->post('id');
		$kontrak_provinsi = $this->Kontrak_provinsi_model->get($id);
		
		$data = array();
		foreach($cols as $key=>$val){
			$data[$val] = $this->input->post($val);
		}		
		$total = 0;
		$total = $data['uang_muka']+$data['termin_1']+$data['termin_2']+$data['termin_3']+$data['termin_4']+$data['termin_5'];
		if($total != 100){
        	$this->session->set_flashdata('error','Jumlah termin pembayaran harus 100%');
            redirect('Kontrak_provinsi/termin?id='.$id);
        }
		// var_dump($data);exit();

		$this->Kontrak_provinsi_model->update($id, $data);
		$this->session->set_flashdata('info','Termin pembayaran updated successfully.');
		redirect('Kontrak_provinsi');
	}
}
	