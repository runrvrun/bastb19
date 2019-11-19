<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PenyediaProvinsi extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('PenyediaProvinsiModel');
		$this->load->model('ProvinsiModel');
	}

	public function index()
	{
		$this->load->library('parser');
		// $param['penyedia_provinsi'] = $this->PenyediaProvinsiModel->GetAll();
		$param['penyedia_provinsi'] = array();
		$data = array(
	        'title' => 'Data Penyedia TP Provinsi',
	        'content-path' => 'ADMINISTRATOR / DATA PENYEDIA TP PROVINSI',
	        'content' => $this->load->view('penyedia-provinsi-index', $param, TRUE),
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
            0 =>'kode_penyedia_provinsi', 
            1 =>'nama_provinsi',
            2 =>'nama_penyedia_provinsi',
            3 => 'id',
        );

        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

		$totalData = $this->PenyediaProvinsiModel->GetAllAjaxCount();
            
        $totalFiltered = $totalData; 

		if(empty($this->input->post('search')['value']))
        {            
            $posts = $this->PenyediaProvinsiModel->GetAllForAjax($_POST['start'], $_POST['length'], $order, $dir);
        }
        else {
            $search = $this->input->post('search')['value']; 

            $posts =  $this->PenyediaProvinsiModel->GetSearchAjax($_POST['start'], $_POST['length'], $search, $order, $dir);

            $totalFiltered = $this->PenyediaProvinsiModel->GetSearchAjaxCount($search);
        }

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData['kode_penyedia_provinsi'] = $post->kode_penyedia_provinsi;
                $nestedData['nama_provinsi'] = $post->nama_provinsi;
                $nestedData['nama_penyedia_provinsi'] = $post->nama_penyedia_provinsi;

                $tools = "";
                if($bolehedit)
                	$tools .= "<a class='btn btn-xs btn-primary btn-sm' href='".base_url('PenyediaProvinsi/Edit?id=').$post->id."'><i class='glyphicon glyphicon-pencil'></i></a>";
                if($bolehhapus)
                	$tools .= "<a class='btn btn-xs btn-danger btn-sm' data-href='".base_url('PenyediaProvinsi/doDelete?id=').$post->id."' data-toggle='modal' data-record-title='".$post->nama_penyedia_provinsi."' data-target='#confirm-delete'><i class='glyphicon glyphicon-trash'></i></a>";

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
		$param['provinsi'] = $this->ProvinsiModel->GetAll();
		$data = array(
	        'title' => 'Data Penyedia TP Provinsi',
	        'content-path' => 'ADMINISTRATOR / DATA PENYEDIA TP PROVINSI / TAMBAH DATA',
	        'content' => $this->load->view('penyedia-provinsi-add', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function doAdd()
	{	
		if($this->input->post('nama_penyedia_provinsi') == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('PenyediaProvinsi/Add');
		}
		else{
			$data = array(
				'nama_penyedia_provinsi' => strtoupper($this->input->post('nama_penyedia_provinsi')),
				'id_provinsi' => strtoupper($this->input->post('id_provinsi')),
				'created_by' => $this->session->userdata('logged_in')->id_pengguna,
				'created_at' => NOW,
			);
			$this->PenyediaProvinsiModel->Insert($data);
			$this->session->set_flashdata('info','Data inserted successfully.');
			redirect('PenyediaProvinsi');
		}
		
	}

	public function Edit()
	{
		$id = $this->input->get('id');
		$param['penyedia_provinsi'] = $this->PenyediaProvinsiModel->Get($id);
		$param['provinsi'] = $this->ProvinsiModel->GetAll();

		$this->load->library('parser');
		$data = array(
	        'title' => 'Data Penyedia TP Provinsi',
	        'content-path' => 'ADMINISTRATOR / DATA PENYEDIA TP PROVINSI / UBAH DATA',
	        'content' => $this->load->view('penyedia-provinsi-edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function doEdit()
	{	
		$id = $this->input->post('id');
		if($this->input->post('nama_penyedia_provinsi') == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('PenyediaProvinsi/Edit?id='.$id);
		}
		else{
			$data = array(
				'nama_penyedia_provinsi' => strtoupper($this->input->post('nama_penyedia_provinsi')),
				'id_provinsi' => strtoupper($this->input->post('id_provinsi')),
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->PenyediaProvinsiModel->Update($id, $data);
			$this->session->set_flashdata('info','Data updated successfully.');
			redirect('PenyediaProvinsi');
		}
		
	}

	public function doDelete()
	{	
		$id = $this->input->get('id');

		$this->PenyediaProvinsiModel->Delete($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('PenyediaProvinsi');
		
	}

	public function GetByProvinsi()
	{
		$id_provinsi = $this->input->get('id_provinsi');
		$data = $this->PenyediaProvinsiModel->GetByProvinsi($id_provinsi);
		$hasil = json_encode($data);
		header('HTTP/1.1: 200');
		header('Status: 200');
		header('Content-Length: '.strlen($hasil));
		exit($hasil);
	}

	public function GetByListProvinsi()
	{
		$list_id_provinsi = $this->input->get('list_id_provinsi');

		if($list_id_provinsi){
			$data = $this->PenyediaProvinsiModel->GetByListProvinsi($list_id_provinsi);
			$hasil = json_encode($data);
			header('HTTP/1.1: 200');
			header('Status: 200');
			header('Content-Length: '.strlen($hasil));
			exit($hasil);
		}
		
	}
	
	public function autofill()
	{
		$id = $this->session->userdata('logged_in')->id_penyedia_provinsi;
		$param['penyedia_provinsi'] = $this->PenyediaProvinsiModel->Get($id);

		$this->load->library('parser');
		$this->load->model('ProvinsiModel');
		$param['provinsi'] = $this->ProvinsiModel->GetAll();
		
		$data = array(
	        'title' => 'Data Penyedia Provinsi',
	        'content-path' => 'ADMINISTRATOR / DATA PENYEDIA TP PROVINSI / UBAH DATA AUTOFILL',
	        'content' => $this->load->view('penyedia-provinsi-edit-autofill', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function update_autofill()
	{
		$id = $this->input->post('id');
		$data = array(
			'nama_penyerah' => strtoupper($this->input->post('nama_penyerah')),
			'jabatan_penyerah' => strtoupper($this->input->post('jabatan_penyerah')),
			'notelp_penyerah' => strtoupper($this->input->post('notelp_penyerah')),
			'alamat_penyerah' => strtoupper($this->input->post('alamat_penyerah')),
			'id_provinsi_penyerah' => strtoupper($this->input->post('id_provinsi_penyerah')),
			'id_kabupaten_penyerah' => strtoupper($this->input->post('id_kabupaten_penyerah')),
			'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
			'updated_at' => NOW,
		);
		$this->PenyediaProvinsiModel->Update($id, $data);
		$this->session->set_flashdata('info','Data updated successfully.');
		redirect('Home');
	}
}
