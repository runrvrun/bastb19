<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('LoginModel');
		$this->load->model('TahunPengadaanModel');
		$this->load->model('AksesModel');
	}

	public function index()
	{
		$this->load->view('login');
	}

	public function Login()
	{
		$data['tahun_pengadaan'] = $this->TahunPengadaanModel->GetAll();
		$this->load->view('login', $data);
	}

	public function doLogin()
	{
		$userlogin = $this->input->post('Username');
		$password = $this->input->post('Password');

		$tahun_pengadaan = $this->input->post('tahun_pengadaan');

		if($userlogin == '' or $password == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('Auth/Login');
		}
		else{
			$berhasil = $this->LoginModel->CheckLogin($userlogin, $password);
			if($berhasil){
				$userlogin = $this->LoginModel->GetUserLogin($userlogin, $password);
				$userlogin = (array)$userlogin;

				$userlogin['tahun_pengadaan'] = $tahun_pengadaan;

				$userlogin['menu_items'] = $this->AksesModel->GetMenuForRole($userlogin['role_pengguna']);
				$userlogin['crud_items'] = $this->AksesModel->GetCrudForRole($userlogin['role_pengguna']);

				$userlogin = (object)$userlogin;

         		$this->session->set_userdata('logged_in', $userlogin);
         		// print_r($this->session->userdata('logged_in'));
         		// exit(1);

         		// update terakhir login
         		$data = array(
         				"last_login" => NOW,
         		);

         		if($this->input->post('Username') != 'superadmin')
         			$this->LoginModel->UpdateLastLogin($data, $this->input->post('Username'));

         		redirect('Home');
			}
			else{
				$this->session->set_flashdata('error','Invalid login credentials.');
     			redirect('Auth/Login');
			}
		}
	}
}
