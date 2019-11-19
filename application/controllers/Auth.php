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
				 // autofill
				//  var_dump($userlogin);exit();
				 if($userlogin->role_pengguna == 'ADMIN PENYEDIA PUSAT'){
					$this->load->model('PenyediaPusatModel');
					$autofill = $this->PenyediaPusatModel->Get($userlogin->id_penyedia_pusat);
					$this->session->set_userdata('autofill', $autofill);
				 }
				 if($userlogin->role_pengguna == 'ADMIN PENYEDIA PROVINSI'){
					$this->load->model('PenyediaProvinsiModel');
					$autofill = $this->PenyediaProvinsiModel->Get($userlogin->id_penyedia_provinsi);
					$this->session->set_userdata('autofill', $autofill);
				}
				 if($userlogin->role_pengguna == 'ADMIN PROVINSI'){
					$this->load->model('ProvinsiModel');
					$autofill = $this->ProvinsiModel->Get($userlogin->id_provinsi);
					$this->session->set_userdata('autofill', $autofill);
				}
				 if($userlogin->role_pengguna == 'ADMIN KABUPATEN'){
					$this->load->model('KabupatenModel');
					$autofill = $this->KabupatenModel->Get($userlogin->id_kabupaten);
					$this->session->set_userdata('autofill', $autofill);
				}

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
