<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Provinsi extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('ProvinsiModel');
	}

	public function index()
	{
		$this->load->library('parser');
		$param['provinsi'] = array();
		$data = array(
	        'title' => 'Data Provinsi',
	        'content-path' => 'ADMINISTRATOR / DATA PROVINSI',
	        'content' => $this->load->view('provinsi-index', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function AjaxGetAll()
	{
		$data = $this->ProvinsiModel->GetAll();
		$hasil = json_encode($data);
		header('HTTP/1.1: 200');
		header('Status: 200');
		header('Content-Length: '.strlen($hasil));
		exit($hasil);
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
            0 =>'kode_provinsi', 
            1 =>'nama_provinsi',
            2=> 'id',
        );

        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

		$totalData = $this->ProvinsiModel->GetAllAjaxCount();
            
        $totalFiltered = $totalData; 

		if(empty($this->input->post('search')['value']))
        {            
            $posts = $this->ProvinsiModel->GetAllForAjax($_POST['start'], $_POST['length'], $order, $dir);
        }
        else {
            $search = $this->input->post('search')['value']; 

            $posts =  $this->ProvinsiModel->GetSearchAjax($_POST['start'], $_POST['length'], $search, $order, $dir);

            $totalFiltered = $this->ProvinsiModel->GetSearchAjaxCount($search);
        }

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData['kode_provinsi'] = $post->kode_provinsi;
                $nestedData['nama_provinsi'] = $post->nama_provinsi;

                $tools = "";

                if($bolehedit)
                	$tools .= "<a class='btn btn-xs btn-primary btn-sm' href='".base_url('Provinsi/Edit?id=').$post->id."'><i class='glyphicon glyphicon-pencil'></i></a>";
                if($bolehhapus)
                	$tools .= "<a class='btn btn-xs btn-danger btn-sm' data-href='".base_url('Provinsi/doDelete?id=').$post->id."' data-toggle='modal' data-record-title='".$post->nama_provinsi."' data-target='#confirm-delete'><i class='glyphicon glyphicon-trash'></i></a>";
                
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
		$data = array(
	        'title' => 'Data Provinsi',
	        'content-path' => 'ADMINISTRATOR / DATA PROVINSI / TAMBAH DATA',
	        'content' => $this->load->view('provinsi-add', NULL, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function doAdd()
	{	
		if($this->input->post('kode_provinsi') == '' or $this->input->post('nama_provinsi') == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('Provinsi/Add');
		}
		else{
			$data = array(
				'kode_provinsi' => $this->input->post('kode_provinsi'),
				'nama_provinsi' => strtoupper($this->input->post('nama_provinsi')),
				'created_by' => $this->session->userdata('logged_in')->id_pengguna,
				'created_at' => NOW,
			);
			$this->ProvinsiModel->Insert($data);
			$this->session->set_flashdata('info','Data inserted successfully.');
			redirect('Provinsi');
		}
		
	}

	public function Edit()
	{
		$id = $this->input->get('id');
		$param['provinsi'] = $this->ProvinsiModel->Get($id);

		$this->load->library('parser');
		$data = array(
	        'title' => 'Data Provinsi',
	        'content-path' => 'ADMINISTRATOR / DATA PROVINSI / UBAH DATA',
	        'content' => $this->load->view('provinsi-edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function doEdit()
	{	
		$id = $this->input->post('id');
		if($this->input->post('kode_provinsi') == '' or $this->input->post('nama_provinsi') == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('Provinsi/Edit?id='.$id);
		}
		else{
			$data = array(
				'kode_provinsi' => $this->input->post('kode_provinsi'),
				'nama_provinsi' => strtoupper($this->input->post('nama_provinsi')),
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->ProvinsiModel->Update($id, $data);
			$this->session->set_flashdata('info','Data updated successfully.');
			redirect('Provinsi');
		}
		
	}

	public function doDelete()
	{	
		$id = $this->input->get('id');

		$this->ProvinsiModel->Delete($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('Provinsi');	
	}
	
	public function autofill()
	{
		$id = $this->session->userdata('logged_in')->id_provinsi;
		$param['provinsi'] = $this->ProvinsiModel->Get($id);
		$param['ddprovinsi'] = $this->ProvinsiModel->GetAll($id);

		$this->load->library('parser');
		$this->load->model('ProvinsiModel');
		
		$data = array(
	        'title' => 'Data Provinsi',
	        'content-path' => 'ADMINISTRATOR / DATA PROVINSI / UBAH DATA AUTOFILL',
	        'content' => $this->load->view('provinsi-edit-autofill', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function update_autofill()
	{
		$id = $this->input->post('id');
		$data = array(
			'nama_dinas' => strtoupper($this->input->post('nama_dinas')),
			'nama_penyerah' => strtoupper($this->input->post('nama_penyerah')),
			'jabatan_penyerah' => strtoupper($this->input->post('jabatan_penyerah')),
			'notelp_penyerah' => strtoupper($this->input->post('notelp_penyerah')),
			'alamat_penyerah' => strtoupper($this->input->post('alamat_penyerah')),
			'id_provinsi_penyerah' => strtoupper($this->input->post('id_provinsi_penyerah')),
			'id_kabupaten_penyerah' => strtoupper($this->input->post('id_kabupaten_penyerah')),
			'nama_mengetahui' => strtoupper($this->input->post('nama_mengetahui')),
			'jabatan_mengetahui' => strtoupper($this->input->post('jabatan_mengetahui')),
			'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
			'updated_at' => NOW,
		);
		$this->ProvinsiModel->Update($id, $data);
		$this->session->set_flashdata('info','Data updated successfully.');
		redirect('Home');
	}
}
