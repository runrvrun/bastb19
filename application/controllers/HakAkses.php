<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HakAkses extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('AdminModel');
		$this->load->model('AksesModel');
	}

	public function index()
	{
		$this->load->library('parser');
		$param['menus'] = $this->AksesModel->GetAllMenu();
		$param['cruds'] = $this->AksesModel->GetAllCrud();

		$data = array(
	        'title' => 'Hak Akses',
	        'content-path' => 'ADMINISTRATOR / HAK AKSES',
	        'content' => $this->load->view('hak-akses-index', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function Simpan()
	{	
		$menus = $this->AksesModel->GetAllMenu();
		$cruds = $this->AksesModel->GetAllCrud();

		foreach($menus as $menu){
			
			$data = array (
	            'admin_lo_acc'  => (isset($_POST[$menu->id."_lo"])) ? 1 : 0,
	        );
	        $this->AksesModel->Update($data, $menu->id);

	        $data = array (
	            'admin_provinsi_acc'  => (isset($_POST[$menu->id."_provinsi"])) ? 1 : 0,
	        );
	        $this->AksesModel->Update($data, $menu->id);

	        $data = array (
	            'admin_kabupaten_acc'  => (isset($_POST[$menu->id."_kabupaten"])) ? 1 : 0,
	        );
	        $this->AksesModel->Update($data, $menu->id);

	        $data = array (
	            'admin_penyedia_pusat_acc'  => (isset($_POST[$menu->id."_penypusat"])) ? 1 : 0,
	        );
	        $this->AksesModel->Update($data, $menu->id);

	        $data = array (
	            'admin_penyedia_provinsi_acc'  => (isset($_POST[$menu->id."_penyprov"])) ? 1 : 0,
	        );
	        $this->AksesModel->Update($data, $menu->id);

	        $data = array (
	            'admin_hibah_acc'  => (isset($_POST[$menu->id."_hibah"])) ? 1 : 0,
	        );
	        $this->AksesModel->Update($data, $menu->id);

	        $data = array (
	            'admin_khusus_acc'  => (isset($_POST[$menu->id."_khusus"])) ? 1 : 0,
	        );
	        $this->AksesModel->Update($data, $menu->id);
		}
		
		foreach($cruds as $crud){

			$data = array (
	            'admin_lo_acc'  => (isset($_POST[$crud->id."_lo_crud"])) ? 1 : 0,
	        );
	        $this->AksesModel->UpdateCrud($data, $crud->id);

	        $data = array (
	            'admin_provinsi_acc'  => (isset($_POST[$crud->id."_provinsi_crud"])) ? 1 : 0,
	        );
	        $this->AksesModel->UpdateCrud($data, $crud->id);

	        $data = array (
	            'admin_kabupaten_acc'  => (isset($_POST[$crud->id."_kabupaten_crud"])) ? 1 : 0,
	        );
	        $this->AksesModel->UpdateCrud($data, $crud->id);

	        $data = array (
	            'admin_penyedia_pusat_acc'  => (isset($_POST[$crud->id."_penypusat_crud"])) ? 1 : 0,
	        );
	        $this->AksesModel->UpdateCrud($data, $crud->id);

	        $data = array (
	            'admin_penyedia_provinsi_acc'  => (isset($_POST[$crud->id."_penyprov_crud"])) ? 1 : 0,
	        );
	        $this->AksesModel->UpdateCrud($data, $crud->id);

	        $data = array (
	            'admin_hibah_acc'  => (isset($_POST[$crud->id."_hibah_crud"])) ? 1 : 0,
	        );
	        $this->AksesModel->UpdateCrud($data, $crud->id);

	        $data = array (
	            'admin_khusus_acc'  => (isset($_POST[$crud->id."_khusus_crud"])) ? 1 : 0,
	        );
	        $this->AksesModel->UpdateCrud($data, $crud->id);


		}
		$this->session->set_flashdata('info','Data updated successfully.');
		redirect('HakAkses');

	}

	public function GetByMenuRole()
	{
		$id_menu = $this->input->get('id_menu');
		$role_pengguna = $this->input->get('role_pengguna');
		$data = $this->AksesModel->GetByMenuRole($id_menu, $role_pengguna);
		$hasil = json_encode($data);
		header('HTTP/1.1: 200');
		header('Status: 200');
		header('Content-Length: '.strlen($hasil));
		exit($hasil);
	}
	
}
