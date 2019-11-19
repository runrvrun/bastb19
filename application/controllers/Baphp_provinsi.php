<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Baphp_provinsi extends CI_Controller {
	public $cols = array(
		array("column" => "tahun_anggaran", "caption" => "Tahun Anggaran", "dbcolumn" => "tahun_anggaran"),
		array("column" => "titik_serah", "caption" => "Titik Serah", "dbcolumn" => "titik_serah"),
		array("column" => "nama_wilayah", "caption" => "Nama Wilayah", "dbcolumn" => "nama_wilayah"),
		array("column" => "no_baphp", "caption" => "Nomor BAPHP", "dbcolumn" => "no_baphp"),
		array("column" => "tanggal", "caption" => "Tanggal", "dbcolumn" => "tanggal"),
		array("column" => "pihak_penyerah", "caption" => "Pihak Yang Menyerahkan", "dbcolumn" => "peny.nama_penyedia_provinsi"),
		array("column" => "nama_penyerah", "caption" => "Nama Yang Menyerahkan", "dbcolumn" => "nama_penyerah"),
		array("column" => "jabatan_penyerah", "caption" => "Jabatan Yang Menyerahkan", "dbcolumn" => "jabatan_penyerah"),
		array("column" => "notelp_penyerah", "caption" => "No Telepon Yang Menyerahkan", "dbcolumn" => "notelp_penyerah"),
		array("column" => "alamat_penyerah", "caption" => "Alamat Yang Menyerahkan", "dbcolumn" => "alamat_penyerah"),
		array("column" => "provinsi_penyerah", "caption" => "Provinsi Yang Menyerahkan", "dbcolumn" => "prov_peny.nama_provinsi"),
		array("column" => "kabupaten_penyerah", "caption" => "Kabupaten/Kota Yang Menyerahkan", "dbcolumn" => "kab_peny.nama_kabupaten"),
		array("column" => "pihak_penerima", "caption" => "Pihak Yang Menerima", "dbcolumn" => "pihak_penerima"),
		array("column" => "nama_penerima", "caption" => "Nama Yang Menerima", "dbcolumn" => "nama_penerima"),
		array("column" => "jabatan_penerima", "caption" => "Jabatan Yang Menerima", "dbcolumn" => "jabatan_penerima"),
		array("column" => "notelp_penerima", "caption" => "No Telepon Yang Menerima", "dbcolumn" => "notelp_penerima"),
		array("column" => "alamat_penerima", "caption" => "Alamat Yang Menerima", "dbcolumn" => "alamat_penerima"),
		array("column" => "provinsi_penerima", "caption" => "Provinsi Yang Menerima", "dbcolumn" => "prov_pene.nama_provinsi"),
		array("column" => "kabupaten_penerima", "caption" => "Kabupaten/Kota Yang Menerima", "dbcolumn" => "kab_pene.nama_kabupaten"),
		array("column" => "nama_barang", "caption" => "Nama Barang", "dbcolumn" => "nama_barang"),
		array("column" => "merk", "caption" => "Merk Barang", "dbcolumn" => "merk"),
		array("column" => "jumlah_barang", "caption" => "Jumlah Barang", "dbcolumn" => "jumlah_barang"),
		array("column" => "nilai_barang", "caption" => "Nilai Barang (Rp)", "dbcolumn" => "nilai_barang"),
		array("column" => "harga_satuan", "caption" => "Harga Satuan (Rp)", "dbcolumn" => "harga_satuan"),
		array("column" => "no_kontrak", "caption" => "Nomor Kontrak", "dbcolumn" => "no_kontrak"),
		array("column" => "nama_mengetahui", "caption" => "Nama Mengetahui", "dbcolumn" => "nama_mengetahui"),
		array("column" => "jabatan_mengetahui", "caption" => "Jabatan Mengetahui", "dbcolumn" => "jabatan_mengetahui"),
		array("column" => "no_bart", "caption" => "Nomor BART", "dbcolumn" => "no_bart"),
		array("column" => "tanggal_bart", "caption" => "Tanggal BART", "dbcolumn" => "tanggal_bart"),
		array("column" => "no_sp2d", "caption" => "Nomor SP2D", "dbcolumn" => "no_sp2d"),
		array("column" => "tanggal_sp2d", "caption" => "Tanggal SP2D", "dbcolumn" => "tanggal_sp2d"),
		array("column" => "no_spm", "caption" => "Nomor SPM", "dbcolumn" => "no_spm"),
		array("column" => "tanggal_spm", "caption" => "Tanggal SPM", "dbcolumn" => "tanggal_spm"),
	);

    function __construct()
    {
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('Baphp_provinsi_model');
        $this->load->model('Alokasi_provinsi_model');
        $this->load->model('Alokasi_provinsi_model');
		$this->load->model('Kontrak_provinsi_model');
		$this->load->model('PenyediaProvinsiModel');
		$this->load->model('KodimKoremModel');
		$this->load->model('Bart_model');
		$this->load->model('KabupatenModel');
		$this->load->model('Bapb_provinsi_model');
        $this->load->helper('url');
		$this->load->library('xlsxwriter');
	}

    public function index($id = null)
    {
		// $param['regcad'] = $this->input->get('regcad');
		$param['cols'] = $this->cols;
		
        $id_alokasi_provinsi = $this->input->get('id_alokasi_provinsi');
        $this->load->library('parser');
		
        if(!empty($id_alokasi_provinsi)){
			$param['Baphp_provinsi'] = $this->Baphp_provinsi_model->get(null,$id_alokasi_provinsi);
            $param['alokasi_provinsi'] = $this->Alokasi_provinsi_model->get($id_alokasi_provinsi);
            $param['kontrak_provinsi'] = $this->Kontrak_provinsi_model->get($param['alokasi_provinsi']->id_kontrak_provinsi);
        }

		$param['total_unit'] = $this->Baphp_provinsi_model->total_unit($id_alokasi_provinsi);
        $param['total_nilai'] = $this->Baphp_provinsi_model->total_nilai($id_alokasi_provinsi);

        $param['total_unit_kontrak'] = $this->Kontrak_provinsi_model->total_unit();
        $param['total_nilai_kontrak'] = $this->Kontrak_provinsi_model->total_nilai();

        $data = array(
            'title' => 'Data BAPHP Kontrak Provinsi',
            'content-path' => 'PENGADAAN PROVINSI / BAPHP PROVINSI',
            'content' => $this->load->view('baphp-provinsi/index', $param, TRUE),
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
        $regcad = empty($this->input->post('regcad'))? 'REG':$this->input->post('regcad');
        
        $start = empty($this->input->post('start'))? 0:$this->input->post('start');
        $length = empty($this->input->post('length'))? null:$this->input->post('length');
        $order = empty($this->input->post('order')[0]['column'])? null:$this->input->post('order')[0]['column'];
        $dir = empty($this->input->post('order')[0]['dir'])? null:$this->input->post('order')[0]['dir'];
        
		$id_alokasi_provinsi = $this->input->post('id_alokasi_provinsi');

		$columns = array();
		foreach($this->cols as $key=>$val){
			array_push($columns,$val['column']);
		}

		$dbcolumns = array();
		foreach($this->cols as $key=>$val){
			array_push($dbcolumns,$val['dbcolumn']);
		}
     
        $totalData = $this->Baphp_provinsi_model->get(null,$id_alokasi_provinsi);
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
		$posts_all_search =  $this->Baphp_provinsi_model->get(null, $id_alokasi_provinsi, null, null, null, null, $filtercond, $search);
        $totalFiltered = count($posts_all_search);
		$posts =  $this->Baphp_provinsi_model->get(null, $id_alokasi_provinsi, $start, $length, $order, $dir, $filtercond, $search);

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();
				foreach($this->cols as $val){
					$nestedData[$val['column']] = $post->{$val['column']};
				}
				$nestedData['tanggal'] = date('d-m-Y', strtotime($post->tanggal));
				$nestedData['nilai_barang'] = number_format($post->nilai_barang,0);
				$nestedData['harga_satuan'] = number_format($post->harga_satuan,0);
                $nestedData['ketdokumen'] = (($post->nama_file != '' and $post->nama_file != '[]' and $post->nama_file != 'null' and !is_null($post->nama_file)) ?  '<a class="btn btn-success"><i class="glyphicon glyphicon-ok"></i></a>' : '<a class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i></a>');
                $nestedData['ketfoto'] = (($post->nama_filefoto != '' and $post->nama_filefoto != '[]' and $post->nama_filefoto != 'null' and !is_null($post->nama_filefoto)) ?  '<a class="btn btn-success"><i class="glyphicon glyphicon-ok"></i></a>' : '<a class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i></a>');

				if($nestedData['tanggal_bart'] < '1900-01-01'){
					$nestedData['tanggal_bart'] = '';
				}else{
					$nestedData['tanggal_bart'] = date('d-m-Y', strtotime($post->tanggal_bart));
				}
				if($nestedData['tanggal_sp2d'] < '1900-01-01'){
					$nestedData['tanggal_sp2d'] = '';
				}else{
					$nestedData['tanggal_sp2d'] = date('d-m-Y', strtotime($post->tanggal_sp2d));
				}
				if($nestedData['tanggal_spm'] < '1900-01-01'){
					$nestedData['tanggal_spm'] = '';
				}else{
					$nestedData['tanggal_spm'] = date('d-m-Y', strtotime($post->tanggal_spm));
				}
				
				$tools = '<div class="dropdown">
				<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				-- Pilih menu --
				</button>
				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					<a class="dropdown-item" href="#" data-href="#" data-toggle="modal" data-record-title="'.$post->id.'" data-target="#upload-modal" data-record-id="'.$post->id.'">Upload Dokumen</a>
					<a class="dropdown-item" href="#" data-href="#" data-toggle="modal" data-record-title="'.$post->id.'" data-target="#upload-modalfoto" data-record-id="'.$post->id.'">Upload Foto</a>
					<a class="dropdown-item" href="#" onclick="LoadData('.$post->id.')">Lihat Data</a>';
				if($bolehedit)
					// $tools .= '<a class="dropdown-item" href="'.base_url('Ongkir_provinsi?id_baphp_provinsi=').$post->id.'">Data Ongkir</a>';
					$tools .= '<a class="dropdown-item" href="'.base_url('Baphp_provinsi_norangka?id_baphp_provinsi=').$post->id.'">No. Rangka & No. Mesin</a>';
					$tools .= '<a class="dropdown-item" href="'.base_url('Baphp_provinsi/edit?id='.$post->id).'">Ubah Data</a>';
				if($bolehhapus)
					$tools .= '<a class="dropdown-item"  href="#" data-toggle="modal" data-record-title="'.$post->id.'" data-target="#confirm-delete" data-href="'.base_url('Baphp_provinsi/destroy/').$post->id.'">Hapus Data</a>';
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
		$baphp_provinsi = $this->Baphp_provinsi_model->get($id);
		$alokasi_provinsi = $this->Alokasi_persedian_provinsi_model->get($baphp_provinsi->id_alokasi_provinsi);
		$alokasi_provinsi = $this->Alokasi_provinsi_model->get($alokasi_provinsi->id_alokasi);
		$kontrak_provinsi = $this->Kontrak_provinsi_model->get($alokasi_provinsi->id_kontrak_provinsi);
		$param['kontrak_provinsi'] = $kontrak_provinsi;

		$param['baphp_provinsi'] = array();
		$param['total_unit'] = $this->Kontrak_provinsi_model->total_unit($kontrak_provinsi->id);
		$param['total_nilai'] = $this->Kontrak_provinsi_model->total_nilai($kontrak_provinsi->id);

		$this->load->library('parser');
		$data = array(
	        'title' => 'Data BAPHP Kontrak Provinsi',
	        'content-path' => 'PENGADAAN PROVINSI / DATA BAPHP PROVINSI',
	        'content' => $this->load->view('Alokasi-provinsi-provinsi/index', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

    public function get_json()
    {
		$id = $this->input->get('id');

		$data = $this->Baphp_provinsi_model->get($id);
		if($data->tanggal < '1900-00-00'){
			$data->tanggal = '';
		}else{
			$data->tanggal = date('d-m-Y',strtotime($data->tanggal));
		}
		if($data->tanggal_bart < '1900-00-00'){
			$data->tanggal_bart = '';
		}else{
			$data->tanggal_bart = date('d-m-Y',strtotime($data->tanggal_bart));
		}
		$data->nilai_barang = number_format($data->nilai_barang,0);
		$data->harga_satuan = number_format($data->harga_satuan,0);
		$hasil = json_encode($data);
		header('HTTP/1.1: 200');
		header('Status: 200');
		header('Content-Length: '.strlen($hasil));
		exit($hasil);
    }
    
    public function create()
	{
		$param['cols'] = $this->cols;

		$id_alokasi_provinsi = $this->input->get('id_alokasi_provinsi');
		$param['alokasi_provinsi'] = $this->Alokasi_provinsi_model->get($id_alokasi_provinsi);
		$param['kontrak_provinsi'] = $this->Kontrak_provinsi_model->get($param['alokasi_provinsi']->id_kontrak_provinsi);
		$param['penyedia_provinsi'] = $this->PenyediaProvinsiModel->Get($param['kontrak_provinsi']->id_penyedia_provinsi);
		// $param['penyedia_provinsi'] = $this->PenyediaProvinsiModel->GetAll();
		$param['id_alokasi_provinsi'] = $id_alokasi_provinsi;
		$param['bart'] = $this->Bart_model->get(null,$param['kontrak_provinsi']->id);

		$this->load->library('parser');
		$this->load->model('ProvinsiModel');

		$param['provinsi'] = $this->ProvinsiModel->GetAll(1);
		$param['kodim'] = $this->KodimKoremModel->GetAll();
		$param['kabupaten'] = $this->KabupatenModel->Get($param['alokasi_provinsi']->id_kabupaten);

		$data = array(
	        'title' => 'Input BAPHP PROVINSI Kontrak Provinsi',
	        'content-path' => 'PENGADAAN PROVINSI / DATA BAPHP PROVINSI / TAMBAH DATA',
	        'content' => $this->load->view('baphp-provinsi/add', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function store()
	{	
		$data = array(
			'id' => $this->input->post('id'),
			'tahun_anggaran' => $this->input->post('tahun_anggaran'),
			'id_alokasi_provinsi' => $this->input->post('id_alokasi_provinsi'),
			'id_provinsi_penyerah' => $this->input->post('id_provinsi_penyerah'),
			'id_kabupaten_penyerah' => $this->input->post('id_kabupaten_penyerah'),
			'id_provinsi_penerima' => $this->input->post('id_provinsi_penerima'),
			'id_kabupaten_penerima' => $this->input->post('id_kabupaten_penerima'),
			'id_penyerah' => $this->input->post('id_penyerah'),
			'created_by' => $this->session->userdata('logged_in')->id_pengguna,
			'created_at' => NOW,
		);
		//check mandatory fields
		$mandatory = array('tanggal');
		foreach($mandatory as $val){
			if(empty($this->input->post($val))){
				$this->session->set_flashdata('error','Isian '.$val.' harus diisi.');
	            redirect('Baphp/create?id_alokasi='.$this->input->post('id_alokasi'));
			}
		}
		//cek limit nilai
		$jumlah_barang = $this->input->post('jumlah_barang');
		$nilai_barang = (float)str_replace(',', '', $this->input->post('nilai_barang'));
		$alokasi_provinsi = $this->Alokasi_provinsi_model->get($this->input->post('id_alokasi_provinsi'));
		$total_unit = $this->Baphp_provinsi_model->total_unit($alokasi_provinsi->id);
		$total_nilai = $this->Baphp_provinsi_model->total_nilai($alokasi_provinsi->id);
		if(($total_nilai+$nilai_barang > $alokasi_provinsi->nilai_barang) || ($total_unit+$jumlah_barang > $alokasi_provinsi->jumlah_barang)){
			$this->session->set_flashdata('error','Jumlah atau nilai tidak dapat melebihi nilai alokasi.');
            redirect('Baphp_provinsi/create?id_alokasi_provinsi='.$alokasi_provinsi->id);
		}
		
		foreach($this->cols as $key=>$val){
			if(!in_array($val['dbcolumn'], array('peny.nama_penyedia_provinsi','prov_pene.nama_provinsi','kab_pene.nama_kabupaten',
			'prov_peny.nama_provinsi','kab_peny.nama_kabupaten','tanggal','tanggal_bart'))){
				$data[$val['dbcolumn']] = $this->input->post($val['column']);
			}
		}
		if(!empty($this->input->post('tanggal'))){
			$data['tanggal'] = DateTime::createFromFormat('d-m-Y', $this->input->post('tanggal'))->format('Y-m-d');
		}
		if(!empty($this->input->post('tanggal_bart'))){
			$data['tanggal_bart'] = DateTime::createFromFormat('d-m-Y', $this->input->post('tanggal_bart'))->format('Y-m-d');
		}
		if(!empty($this->input->post('tanggal_sp2d'))){
			$data['tanggal_sp2d'] = DateTime::createFromFormat('d-m-Y', $this->input->post('tanggal_sp2d'))->format('Y-m-d');
		}
		if(!empty($this->input->post('tanggal_spm'))){
			$data['tanggal_spm'] = DateTime::createFromFormat('d-m-Y', $this->input->post('tanggal_spm'))->format('Y-m-d');
		}
		// var_dump($data);exit();
		$data['jumlah_barang'] = str_replace(',','',$data['jumlah_barang']);
		$data['nilai_barang'] = str_replace(',','',$data['nilai_barang']);
		unset($data['harga_satuan']);
		
		$this->Baphp_provinsi_model->store($data);
		$this->session->set_flashdata('info','Data inserted successfully.');
		redirect('Baphp_provinsi/index?id_alokasi_provinsi='.$this->input->post('id_alokasi_provinsi'));
	}

	public function edit()
	{
		$id = $this->input->get('id');
		$param['cols'] = $this->cols;

		$param['baphp_provinsi'] = $this->Baphp_provinsi_model->get($id);
		$param['alokasi_provinsi'] = $this->Alokasi_provinsi_model->get($param['baphp_provinsi']->id_alokasi_provinsi);
		$param['kontrak_provinsi'] = $this->Kontrak_provinsi_model->get($param['alokasi_provinsi']->id_kontrak_provinsi);
		// $param['penyedia_provinsi'] = $this->PenyediaProvinsiModel->GetAll();
		$param['penyedia_provinsi'] = $this->PenyediaProvinsiModel->Get($param['kontrak_provinsi']->id_penyedia_provinsi);
		$param['bart'] = $this->Bart_model->get(null,$param['kontrak_provinsi']->id);

		$this->load->library('parser');
		$this->load->model('ProvinsiModel');

		$param['provinsi'] = $this->ProvinsiModel->GetAll(1);
		$param['kodim'] = $this->KodimKoremModel->GetAll();

		$data = array(
	        'title' => 'Ubah Data BAPHP Kontrak Provinsi',
	        'content-path' => 'PENGADAAN PROVINSI / DATA BAPHP PROVINSI / UBAH DATA',
	        'content' => $this->load->view('baphp-provinsi/edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function update()
	{	
		$id = $this->input->post('id');
		$baphp_provinsi = $this->Baphp_provinsi_model->get($id);
		
		$data = array(
			'id' => $this->input->post('id'),
			// 'regcad' => $this->input->post('regcad'),
			'id_provinsi_penyerah' => $this->input->post('id_provinsi_penyerah'),
			'id_kabupaten_penyerah' => $this->input->post('id_kabupaten_penyerah'),
			'id_kabupaten_penerima' => $this->input->post('id_kabupaten_penerima'),
			'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
			'updated_at' => NOW,
		);
		foreach($this->cols as $key=>$val){
			if(!in_array($val['dbcolumn'], array('peny.nama_penyedia_provinsi','prov_pene.nama_provinsi','kab_pene.nama_kabupaten',
			'bap.nama_barang','bap.merk','bap.jumlah_barang','bap.nilai_barang','prov_peny.nama_provinsi','kab_peny.nama_kabupaten',
			'tanggal','tanggal_bart','tanggal_sp2d','tanggal_spm'))){
				$data[$val['dbcolumn']] = $this->input->post($val['column']);
			}
		}
		if(!empty($this->input->post('tanggal'))){
			$data['tanggal'] = DateTime::createFromFormat('d-m-Y', $this->input->post('tanggal'))->format('Y-m-d');
		}
		if(!empty($this->input->post('tanggal_bart'))){
			$data['tanggal_bart'] = DateTime::createFromFormat('d-m-Y', $this->input->post('tanggal_bart'))->format('Y-m-d');
		}
		if(!empty($this->input->post('tanggal_sp2d'))){
			$data['tanggal_sp2d'] = DateTime::createFromFormat('d-m-Y', $this->input->post('tanggal_sp2d'))->format('Y-m-d');
		}
		if(!empty($this->input->post('tanggal_spm'))){
			$data['tanggal_spm'] = DateTime::createFromFormat('d-m-Y', $this->input->post('tanggal_spm'))->format('Y-m-d');
		}
		
		$data['jumlah_barang'] = str_replace(',','',$data['jumlah_barang']);
		$data['nilai_barang'] = str_replace(',','',$data['nilai_barang']);
		unset($data['harga_satuan']);
		$this->Baphp_provinsi_model->update($id, $data);
		$this->session->set_flashdata('info','Data updated successfully.');
		redirect('Baphp_provinsi/index?id_alokasi_provinsi='.$baphp_provinsi->id_alokasi_provinsi);
	}

	public function destroy($id)
	{	
		$baphp_provinsi = $this->Baphp_provinsi_model->get($id);

		if($baphp_provinsi->nama_file != '' and $baphp_provinsi->nama_file != 'null' and !is_null($baphp_provinsi->nama_file) and $baphp_provinsi->nama_file != '[]')
			$images = json_decode($baphp_provinsi->nama_file);
		else
			$images = [];

		foreach($images as $image){
			unlink($this->config->item('doc_root').'/upload/baphp_provinsi/'.$image);	
		}

		$this->Baphp_provinsi_model->destroy($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('Baphp_provinsi/index?id_alokasi_provinsi='.$baphp_provinsi->id_alokasi_provinsi);
		
	}

    public function export()
    {
		$columns = array();
		foreach($this->cols as $key=>$val){
			array_push($columns,$val['column']);
		}

        // $order = $columns[$this->input->post('order')[0]['column']];
		// $dir = $this->input->post('order')[0]['dir'];
		$order='id';
		$dir='ASC';
		$id_alokasi=$this->input->post('id_alokasi_provinsi');
        $filtercond = '';
        for($i=0;$i<count($columns);$i++){
            if(!empty($this->input->post('columns')[$i]['search']['value'])){
                $search = $this->input->post('columns')[$i]['search']['value'];
                $filtercond  .= " and ".$columns[$i]." LIKE '%".$search."%'"; 
            }
        }

        $data = array();
        if(empty($this->input->post('search')['value'])){            
            $data = $this->Baphp_provinsi_model->get(null,$id_alokasi,null,null,$order, $dir, $filtercond);
        }
        else {
            $search = $this->input->post('search')['value']; 
            $data =  $this->Baphp_provinsi_model->get(null,$id_alokasi,null,null, $order, $dir, $filtercond,$search);
        }

        // $visible_columns = $this->input->post('visible_columns');
        $visible_columns = $this->cols; 
        $visible_header_columns = array();
        foreach($visible_columns as $value) {
            switch($value['caption']) {
				case 'Tahun Anggaran':
				case 'Alokasi_provinsi': 
					$visible_header_columns[$value['caption']] = 'integer';
					break;
                case 'Nilai (Rp)': 
                case 'Harga Satuan (Rp)': 
					$visible_header_columns[$value['caption']] = '#,##0';
					break;
				default :
					$visible_header_columns[$value['caption']] = 'string';
			}
        }
		$visible_header_columns['Keterangan Dokumen'] = 'string';		
		$visible_header_columns['Keterangan Foto'] = 'string';		
        $this->xlsxwriter->writeSheetHeader('Rekap BAPHP Provinsi', $visible_header_columns, array('font-style'=>'bold'));
        
        foreach($data as $row) {
            $newRow = array();
            foreach($visible_columns as $key => $value) {
                $defaultValue = '';
                if(isset($row->{$value['column']})) {
                    $defaultValue = $row->{$value['column']};
                }

                switch($value['column']) {
                    default: 
                        $newRow[$key] = $defaultValue;
                }
            }
			$newRow[$key+1] = empty($row->nama_file)? 'TIDAK ADA':'ADA';
			$newRow[$key+2] = empty($row->nama_filefoto)? 'TIDAK ADA':'ADA';
            $this->xlsxwriter->writeSheetRow('Rekap BAPHP Provinsi', $newRow);
        }
        
        $uniq_id = substr(md5(uniqid(rand(), true)), 0, 5);
        $file = "tmp_export/Rekap BAPHP Provinsi - $uniq_id.xlsx";
        $this->xlsxwriter->writeToFile($file);
        header('Content-Type: application/json');
        echo json_encode(array('filename' => base_url().'Baphp_provinsi/download?filename='.$file));
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

		$baphp_provinsi = $this->Baphp_provinsi_model->get($id);

		$kodefile_upload = strtotime(NOW);

		if($baphp_provinsi->nama_file != '' and $baphp_provinsi->nama_file != 'null' and !is_null($baphp_provinsi->nama_file) and $baphp_provinsi->nama_file != '[]')
			$nama_file = json_decode($baphp_provinsi->nama_file);
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
		        $targetFile =  $target_file = $this->config->item('doc_root').'/upload/baphp_provinsi/'.$kodefile_upload.basename($_FILES['file']['name'][$key]);
	        	move_uploaded_file($tempFile, $target_file);

		    }

		    $nama_file = json_encode($nama_file);
        	$data = array(
				'nama_file' => $nama_file,
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->Baphp_provinsi_model->update($id, $data);

			$this->session->set_flashdata('info','File uploaded successfully.');
			exit('success');
        }
	}
	
	public function upload_filefoto()
    {
		$id = $this->input->post('id');
		
		$baphp_provinsi = $this->Baphp_provinsi_model->get($id);

		$kodefile_upload = strtotime(NOW);

		if($baphp_provinsi->nama_filefoto != '' and $baphp_provinsi->nama_filefoto != 'null' and !is_null($baphp_provinsi->nama_filefoto) and $baphp_provinsi->nama_filefoto != '[]')
			$nama_filefoto = json_decode($baphp_provinsi->nama_filefoto);
		else
			$nama_filefoto = [];

		foreach($_FILES['file']['tmp_name'] as $key => $value) {
	        array_push($nama_filefoto, $kodefile_upload.basename($_FILES['file']['name'][$key]));
	    }

        if(count($nama_filefoto) > 10){
        	$this->session->set_flashdata('error','Jumlah file tidak boleh lebih dari 10. Upload dibatalkan.');
			exit('success');
        }
        else{

		    foreach($_FILES['file']['tmp_name'] as $key => $value) {
		        $tempFile = $_FILES['file']['tmp_name'][$key];
		        $targetFile =  $target_file = $this->config->item('doc_root').'/upload/baphp_provinsi/'.$kodefile_upload.basename($_FILES['file']['name'][$key]);
	        	move_uploaded_file($tempFile, $target_file);

		    }

		    $nama_filefoto = json_encode($nama_filefoto);
        	$data = array(
				'nama_filefoto' => $nama_filefoto,
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->Baphp_provinsi_model->update($id, $data);

			$this->session->set_flashdata('info','File uploaded successfully.');
			exit('success');
        }
    }
    
    public function remove_file()
    {
		$id = $this->input->get('id');
		$urutanfile = $this->input->get('urutanfile');

		$baphp_provinsi = $this->Baphp_provinsi_model->get($id);

		if($baphp_provinsi->nama_file != '' and $baphp_provinsi->nama_file != 'null' and !is_null($baphp_provinsi->nama_file) and $baphp_provinsi->nama_file != '[]')
			$images = json_decode($baphp_provinsi->nama_file);
		else
			$images = [];

		$nama_file = $images[$urutanfile];
		@unlink($this->config->item('doc_root').'/upload/baphp_provinsi/'.$nama_file);	

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
		$this->Baphp_provinsi_model->update($id, $data);

		$this->session->set_flashdata('info','File berhasil dihapus.');
		exit('success');
	}

	public function remove_filefoto()
    {
		$id = $this->input->get('id');
		$urutanfile = $this->input->get('urutanfile');

		$baphp_provinsi = $this->Baphp_provinsi_model->get($id);

		if($baphp_provinsi->nama_filefoto != '' and $baphp_provinsi->nama_filefoto != 'null' and !is_null($baphp_provinsi->nama_filefoto) and $baphp_provinsi->nama_filefoto != '[]')
			$images = json_decode($baphp_provinsi->nama_filefoto);
		else
			$images = [];

		$nama_filefoto = $images[$urutanfile];
		unlink($this->config->item('doc_root').'/upload/baphp_provinsi/'.$nama_filefoto);	

		$new_nama_filefoto = [];
		foreach($images as $image){
			if($image != $nama_filefoto){
				array_push($new_nama_filefoto, $image);
			}
		}

		$nama_filefoto = json_encode($new_nama_filefoto);

		if($nama_filefoto == '[]' or $nama_filefoto == NULL or $nama_filefoto == 'null' or is_null($nama_filefoto)){
			$nama_filefoto = '';
		}

		$data = array(
			'nama_filefoto' => $nama_filefoto,
			'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
			'updated_at' => NOW,
		);
		$this->Baphp_provinsi_model->update($id, $data);

		$this->session->set_flashdata('info','Foto berhasil dihapus.');
		exit('success');
	}
}
	