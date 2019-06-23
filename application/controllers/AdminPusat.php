<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminPusat extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('AdminModel');
	}

	public function index()
	{
		$this->load->library('parser');
		$param['admin_pusat'] = $this->AdminModel->GetAll('ADMIN PUSAT');
		$data = array(
	        'title' => 'Data Admin Pusat',
	        'content-path' => 'ADMINISTRATOR / ADMIN PUSAT',
	        'content' => $this->load->view('admin-pusat-index', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function Add()
	{
		$this->load->library('parser');
		$data = array(
	        'title' => 'Data Admin Pusat',
	        'content-path' => 'ADMINISTRATOR / DATA ADMIN PUSAT / TAMBAH DATA',
	        'content' => $this->load->view('admin-pusat-add', NULL, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function doAdd()
	{	
		if($this->input->post('id_pengguna') == '' or $this->input->post('nama') == '' or $this->input->post('no_telepon') == '' or $this->input->post('password') == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('AdminPusat/Add');
		}
		else{

			//check user id sudah terpakai
			$checkuser = $this->AdminModel->GetByIdPengguna($this->input->post('id_pengguna'));
			if($checkuser){
				$this->session->set_flashdata('error','ID Pengguna sudah digunakan. Harap gunakan ID Pengguna lain.');
     			redirect('AdminPusat/Add');
			}


			$data = array(
				'id_pengguna' => $this->input->post('id_pengguna'),
				'role_pengguna' => 'ADMIN PUSAT',
				'password' => strtoupper(md5($this->input->post('password'))),
				'nama' => strtoupper($this->input->post('nama')),
				'no_telepon' => strtoupper($this->input->post('no_telepon')),
				'is_active' => $this->input->post('is_active'),
				'created_by' => $this->session->userdata('logged_in')->id_pengguna,
				'created_at' => NOW,
			);
			$this->AdminModel->Insert($data);
			$this->session->set_flashdata('info','Data inserted successfully.');
			redirect('AdminPusat');
		}
		
	}

	public function Edit()
	{
		$id = $this->input->get('id');
		$param['admin_pusat'] = $this->AdminModel->Get($id);

		$this->load->library('parser');
		$data = array(
	        'title' => 'Data Admin Pusat',
	        'content-path' => 'ADMINISTRATOR / DATA ADMIN PUSAT / UBAH DATA',
	        'content' => $this->load->view('admin-pusat-edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function doEdit()
	{	
		$id = $this->input->post('id');
		if($this->input->post('id_pengguna') == '' or $this->input->post('nama') == '' or $this->input->post('no_telepon') == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('AdminPusat/Edit?id='.$id);
		}
		else{
			
			if($this->input->post('password') != ''){
				$data = array(
					'id_pengguna' => $this->input->post('id_pengguna'),
					'password' => strtoupper(md5($this->input->post('password'))),
					'nama' => strtoupper($this->input->post('nama')),
					'no_telepon' => strtoupper($this->input->post('no_telepon')),
					'is_active' => $this->input->post('is_active'),
					'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
					'updated_at' => NOW,
				);
			}
			else{
				$data = array(
					'id_pengguna' => $this->input->post('id_pengguna'),
					'nama' => strtoupper($this->input->post('nama')),
					'no_telepon' => strtoupper($this->input->post('no_telepon')),
					'is_active' => $this->input->post('is_active'),
					'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
					'updated_at' => NOW,
				);
			}
			
			$this->AdminModel->Update($id, $data);
			$this->session->set_flashdata('info','Data updated successfully.');
			redirect('AdminPusat');
		}
		
	}

	public function doDelete()
	{	
		$id = $this->input->get('id');

		$this->AdminModel->Delete($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('AdminPusat');
		
	}
}
