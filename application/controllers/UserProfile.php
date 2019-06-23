<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserProfile extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('AdminModel');
		$this->load->model('LoginModel');
	}

	public function index()
	{
		$this->load->library('parser');
		$param['user_data'] = $this->AdminModel->GetByIdPengguna($this->session->userdata('logged_in')->id_pengguna);

		$data = array(
	        'title' => 'User Profile',
	        'content-path' => 'PROFIL',
	        'content' => $this->load->view('user-profile-index', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function UpdateData()
	{	
		$id = $this->input->post('id');
		$id_pengguna = $this->input->post('id_pengguna');
		$nama = $this->input->post('nama');
		$no_telepon = $this->input->post('no_telepon');

		$user_data = $this->AdminModel->GetByIdPengguna($id_pengguna);

		$file_avatar = $user_data->file_avatar;
		$gantigambar = 0;

		if($nama == ''){
			$this->session->set_flashdata('error','Nama Lengkap harus Diisi.');
     		redirect('UserProfile');
		}

		if($this->input->post('hdnIsChangePassword') == 1){
			if($this->input->post('currentPassword') == '' or $this->input->post('newPassword') == '' or $this->input->post('reNewPassword') == ''){
				$this->session->set_flashdata('error','Password Lama dan Password Baru harus Diisi.');
     			redirect('UserProfile');
			}

			if($this->input->post('currentPassword') == $this->input->post('newPassword')){
				$this->session->set_flashdata('error','Password Baru harus berbeda dari Password saat ini.');
     			redirect('UserProfile');
			}

			if($this->input->post('newPassword') != $this->input->post('reNewPassword')){
				$this->session->set_flashdata('error','Password Baru dan  Konfirmasi Password Baru tidak sama.');
     			redirect('UserProfile');
			}

			if(!$this->LoginModel->CheckLogin($id_pengguna, $this->input->post('currentPassword'))){
				$this->session->set_flashdata('error','Password saat ini salah.');
     			redirect('UserProfile');
			}


		}
		if(file_exists($_FILES['file_avatar']['tmp_name']) and is_uploaded_file($_FILES['file_avatar']['tmp_name'])) {

			$kodefile_upload = strtotime(NOW);
			$tempFile = $_FILES['file_avatar']['tmp_name'];
	        $targetFile =  $target_file = $_SERVER['DOCUMENT_ROOT'].'/upload/user_profile/'.$kodefile_upload.basename($_FILES['file_avatar']['name']);
	        move_uploaded_file($tempFile, $target_file);
	        $file_avatar = $kodefile_upload.basename($_FILES['file_avatar']['name']);
	        $gantigambar = 1;
		}
		
		$data = array(
			'nama' => $nama,
			'no_telepon' => $no_telepon,
			'file_avatar' => $file_avatar,
			'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
			'updated_at' => NOW,
		);
		$this->AdminModel->Update($id, $data);

		if($this->input->post('hdnIsChangePassword') == 1){
			$data = array(
				'password' => strtoupper(md5($this->input->post('newPassword'))),
			);
			$this->AdminModel->Update($id, $data);
		}

		if($this->input->post('hdnIsChangePassword') == 1 or $gantigambar == 1){

			echo "<script>
				alert('Data berhasil diubah. Silahkan login ulang.');
				window.location.href='".base_url('Home/Logout')."';
				</script>";
		}
		else{
			$this->session->set_flashdata('info','Data updated successfully.');
			redirect('UserProfile');
		}
		

	}
	
}
