<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kontrak_pusat extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('Kontrak_pusat_model');
		$this->load->model('PenyediaPusatModel');
		$this->load->model('JenisBarangPusatModel');
	}

	public function index()
	{
		$this->load->library('parser');
		$param['kontrak_pusat'] = $this->Kontrak_pusat_model->get();
		$param['total_unit'] = $this->Kontrak_pusat_model->total_unit();
		$param['total_nilai'] = $this->Kontrak_pusat_model->total_nilai();
		$param['total_kontrak'] = $this->Kontrak_pusat_model->total_kontrak();
		$param['total_merk'] = $this->Kontrak_pusat_model->total_merk();
		$data = array(
	        'title' => 'Data Kontrak Pusat',
	        'content-path' => 'PENGADAAN PUSAT / DATA KONTRAK',
	        'content' => $this->load->view('kontrak-pusat/index', $param, TRUE),
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
		if($order == 'cukup/kurang'){
			$order = '';
		}
        $dir = $this->input->post('order')[0]['dir'];

		$totalData = $this->Kontrak_pusat_model->get();
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
        				$filtercond .= " and (hd.nama_file != '' and hd.nama_file != 'null' and hd.nama_file is not null)";
        			}
        			else if($this->input->post('columns')[$i]['search']['value'] == 'TDKTERSEDIA'){
        				$filtercond .= " and (hd.nama_file = '' or hd.nama_file = 'null' or hd.nama_file is null ) ";
        			}
        		}
        		
        	}
        		        	
        }

		$search = $this->input->post('search')['value']; 
		$posts_all_search =  $this->Kontrak_pusat_model->get(null, null, null, null, null, $filtercond, $search);
		$totalFiltered = count($posts_all_search);
		$posts =  $this->Kontrak_pusat_model->get(null,$_POST['start'], $_POST['length'], $order, $dir, $filtercond, $search);

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

                $nestedData['status'] = (($post->jumlah_barang - $post->jumlah_alokasi == 0) ? '<a class="btn btn-success">CUKUP</a>' : '<a class="btn btn-danger">KURANG</a>');
                $nestedData['ketfoto'] = (($post->nama_file != '' and $post->nama_file != '[]' and $post->nama_file != 'null' and !is_null($post->nama_file)) ?  '<a class="btn btn-success"><i class="glyphicon glyphicon-ok"></i></a>' : '<a class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i></a>');
            
				$tools = '<div class="dropdown">
				<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				  -- Pilih menu --
				</button>
				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					<a class="dropdown-item" href="'.base_url('Alokasi_pusat/index?id_kontrak_pusat=').$post->id.'">Input Alokasi</a>
					<a class="dropdown-item" href="'.base_url('Sp2d/index?id_kontrak_pusat=').$post->id.'">Input SP2D</a>
					<a class="dropdown-item" href="'.base_url('Bapb/index?id_kontrak_pusat=').$post->id.'">Input BAPB</a>
					<a class="dropdown-item" href="'.base_url('Bart/index?id_kontrak_pusat=').$post->id.'">Input BART</a>
					<a class="dropdown-item" href="'.base_url('Kontrak_pusat/termin?id=').$post->id.'">Setting Termin Pembayaran</a>
                	<a class="dropdown-item" href="#" data-href="#" data-toggle="modal" data-record-title="'.$post->id.'" data-target="#upload-modal" data-record-id="'.$post->id.'">Upload Dokumen Kontrak</a>
                    <a class="dropdown-item" href="#" onclick="LoadData('.$post->id.')">Lihat Data Kontrak</a>';
                if($bolehedit)
				$tools .= '<a class="dropdown-item" href="'.base_url('Kontrak_pusat/edit?id=').$post->id.'">Ubah Data Kontrak</a>';				
				if(!in_array($this->session->userdata('logged_in')->role_pengguna,array('ADMIN PENYEDIA PUSAT'))){
					$tools .= '<a class="dropdown-item" href="'.base_url('Rekap_kontrak_pusat/rekap?id_kontrak_pusat=').$post->id.'">Lihat Rekap</a>';
				}
                if($bolehhapus)
				$tools .= '<a class="dropdown-item"  href="#" data-toggle="modal" data-record-title="'.$post->id.'" data-target="#confirm-delete" data-href="'.base_url('Kontrak_pusat/destroy/').$post->id.'">Hapus Data Kontrak</a>
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
		$param['penyedia_pusat'] = $this->PenyediaPusatModel->GetAll();
		$param['jenis_barang_pusat'] = $this->JenisBarangPusatModel->GetAll();
		$data = array(
	        'title' => 'Data Kontrak Pusat',
	        'content-path' => 'PENGADAAN PUSAT / DATA KONTRAK / TAMBAH DATA',
	        'content' => $this->load->view('kontrak-pusat/add', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function store()
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
     		redirect('Kontrak_pusat/create');
		}
		else{
			$periode_mulai = DateTime::createFromFormat('d-m-Y', $periode_mulai)->format('Y-m-d');
			$periode_selesai = DateTime::createFromFormat('d-m-Y', $periode_selesai)->format('Y-m-d');

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
				'created_by' => $this->session->userdata('logged_in')->id_pengguna,
				'created_at' => NOW,
			);
			$this->Kontrak_pusat_model->store($data);
			$this->session->set_flashdata('info','Data inserted successfully.');
			redirect('Kontrak_pusat');
		}
		
	}

	public function edit()
	{
		$id = $this->input->get('id');

		$param['penyedia_pusat'] = $this->PenyediaPusatModel->GetAll();
		$param['jenis_barang_pusat'] = $this->JenisBarangPusatModel->GetAll();

		$param['kontrak_pusat'] = $this->Kontrak_pusat_model->get($id);

		$this->load->library('parser');
		$data = array(
	        'title' => 'Data Kontrak Pusat',
	        'content-path' => 'PENGADAAN PUSAT / DATA KONTRAK / UBAH DATA',
	        'content' => $this->load->view('kontrak-pusat/edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function update()
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
     		redirect('Kontrak_pusat/edit?id='.$id);
		}
		else{			
			$kontrak_pusat = $this->Kontrak_pusat_model->get($id);
			$nama_file = $kontrak_pusat->nama_file;

			if($removed_images){
				foreach($removed_images as $imgname){
					unlink($this->config->item('doc_root').'/upload/kontrak_pusat/'.$imgname);
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
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->Kontrak_pusat_model->Update($id, $data);
			$this->session->set_flashdata('info','Data updated successfully.');
			redirect('Kontrak_pusat');
		}
		
	}

	public function destroy($id)
	{			
		$kontrak_pusat = $this->Kontrak_pusat_model->Get($id);
		if($kontrak_pusat->nama_file != '' and $kontrak_pusat->nama_file != 'null' and !is_null($kontrak_pusat->nama_file) and $kontrak_pusat->nama_file != '[]')
			$images = json_decode($kontrak_pusat->nama_file);
		else
			$images = [];

		foreach($images as $image){
			unlink($this->config->item('doc_root').'/upload/kontrak_pusat/'.$image);	
		}

		$this->Kontrak_pusat_model->destroy($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('Kontrak_pusat');		
	}
	
	public function remove_image()
	{
	    
		$id = $this->input->get('id_kontrak');
		$urutanfile = $this->input->get('urutanfile');

		$kontrak_pusat = $this->Kontrak_pusat_model->Get($id);

		if($kontrak_pusat->nama_file != '' and $kontrak_pusat->nama_file != 'null' and !is_null($kontrak_pusat->nama_file) and $kontrak_pusat->nama_file != '[]')
			$images = json_decode($kontrak_pusat->nama_file);
		else
			$images = [];

		$nama_file = $images[$urutanfile];
		unlink($this->config->item('doc_root').'/upload/kontrak_pusat/'.$nama_file);	

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
		$this->Kontrak_pusat_model->Update($id, $data);

		$this->session->set_flashdata('info','File berhasil dihapus.');
		exit('success');
	}

	public function get_json()
	{
		$data = $this->Kontrak_pusat_model->get($this->input->get('id'));
		$hasil = json_encode($data);
		header('HTTP/1.1: 200');
		header('Status: 200');
		header('Content-Length: '.strlen($hasil));
		exit($hasil);		
	}
	
    public function upload_file()
    {
		$id_kontrak_pusat = $this->input->post('id_kontrak_pusat');

		$kontrak_pusat = $this->Kontrak_pusat_model->get($id_kontrak_pusat);

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
		        $targetFile =  $target_file = $this->config->item('doc_root').'/upload/kontrak_pusat/'.$kodefile_upload.basename($_FILES['file']['name'][$key]);
	        	move_uploaded_file($tempFile, $target_file);

		    }

		    $nama_file = json_encode($nama_file);
        	$data = array(
				'nama_file' => $nama_file,
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->Kontrak_pusat_model->update($id_kontrak_pusat, $data);

			$this->session->set_flashdata('info','File uploaded successfully.');
			exit('success');
        }
    }
    
    public function remove_file()
    {
		$id_kontrak_pusat = $this->input->get('id_kontrak_pusat');
		$urutanfile = $this->input->get('urutanfile');

		$kontrak_pusat = $this->Kontrak_pusat_model->get($id_kontrak_pusat);

		if($kontrak_pusat->nama_file != '' and $kontrak_pusat->nama_file != 'null' and !is_null($kontrak_pusat->nama_file) and $kontrak_pusat->nama_file != '[]')
			$images = json_decode($kontrak_pusat->nama_file);
		else
			$images = [];

		$nama_file = $images[$urutanfile];
		unlink($this->config->item('doc_root').'/upload/kontrak_pusat/'.$nama_file);	

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
		$this->Kontrak_pusat_model->update($id_kontrak_pusat, $data);

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

		$param['kontrak_pusat'] = $this->Kontrak_pusat_model->get($id);

		$this->load->library('parser');
		$data = array(
	        'title' => 'Setting Termin Pembayaran',
	        'content-path' => 'PENGADAAN PUSAT / DATA KONTRAK / SETTING TERMIN PEMBAYARAN',
	        'content' => $this->load->view('kontrak-pusat/edit_termin', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function update_termin()
	{	
		$cols = array('jumlah_termin','uang_muka','termin_1','termin_2','termin_3','termin_4','termin_5');
		$id = $this->input->post('id');
		$kontrak_pusat = $this->Kontrak_pusat_model->get($id);
		
		$data = array();
		foreach($cols as $key=>$val){
			$data[$val] = $this->input->post($val);
		}		
		$total = 0;
		$total = $data['uang_muka']+$data['termin_1']+$data['termin_2']+$data['termin_3']+$data['termin_4']+$data['termin_5'];
		if($total != 100){
        	$this->session->set_flashdata('error','Jumlah termin pembayaran harus 100%');
            redirect('Kontrak_pusat/termin?id='.$id);
        }
		// var_dump($data);exit();

		$this->Kontrak_pusat_model->update($id, $data);
		$this->session->set_flashdata('info','Termin pembayaran updated successfully.');
		redirect('Kontrak_pusat');
	}
}
	