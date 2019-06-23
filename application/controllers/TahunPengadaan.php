<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TahunPengadaan extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('TahunPengadaanModel');
	}

	public function index()
	{
		$this->load->library('parser');
		$param['tahun_pengadaan'] = $this->TahunPengadaanModel->GetAll();
		$data = array(
	        'title' => 'Data Tahun Pengadaan',
	        'content-path' => 'ADMINISTRATOR / DATA TAHUN PENGADAAN',
	        'content' => $this->load->view('tahun-pengadaan-index', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function Add()
	{
		$this->load->library('parser');
		$data = array(
	        'title' => 'Data Tahun Pengadaan',
	        'content-path' => 'ADMINISTRATOR / DATA TAHUN PENGADAAN / TAMBAH DATA',
	        'content' => $this->load->view('tahun-pengadaan-add', NULL, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function doAdd()
	{	
		if($this->input->post('tahun_pengadaan') == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('TahunPengadaan/Add');
		}
		else{
			$data = array(
				'tahun_pengadaan' => $this->input->post('tahun_pengadaan'),
				'created_by' => $this->session->userdata('logged_in')->id_pengguna,
				'created_at' => NOW,
			);
			$this->TahunPengadaanModel->Insert($data);
			$this->session->set_flashdata('info','Data inserted successfully.');
			redirect('TahunPengadaan');
		}
		
	}


	public function doDelete()
	{	
		$id = $this->input->get('id');

		$this->TahunPengadaanModel->Delete($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('TahunPengadaan');
		
	}
}
