<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LupaPassword extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('AdminModel');
	}

	public function index()
	{
		// $this->load->library('parser');

		$data = array(
	        'title' => 'Home',
	        'content-path' => 'HOME / LUPA PASSWORD',
	        'content' => $this->load->view('home', NULL, TRUE),
		);
		// $this->parser->parse('default_template', $data);
		$this->load->view('lupa-password');
	}

	public function doSubmit()
	{
		$emailaddress = $this->input->post('txtEmailAddress');

		if($emailaddress == ''){
			$this->session->set_flashdata('error','Alamat Email harus Diisi.');
			redirect('LupaPassword');
		}

		$admin = $this->AdminModel->GetByIdPengguna($emailaddress);

		if(!$admin){
			$this->session->set_flashdata('error','Alamat Email tidak ditemukan.');
			redirect('LupaPassword');
		}
		else{

			if(!filter_var($emailaddress, FILTER_VALIDATE_EMAIL)){
				$this->session->set_flashdata('error','Alamat Email tidak valid / bukan merupakan Alamat Email yang benar.');
				redirect('LupaPassword');
			}

			$characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
			$newpass = '';
			for ($i = 0; $i < 8; $i++) {
			  $newpass .= $characters[rand(0, strlen($characters) - 1)];
			}

			$this->load->library('email');

			$message = '';
			
			$message .= "Yth. User BASTB Online, <br/><br/>";
			$message .= "Berikut ini adalah password baru anda: <h2>".$newpass."</h2>";
	        $message .= "<br/>Silahkan login ke aplikasi BASTB Online dengan password baru tersebut. Anda dapat mengganti password tersebut di menu Profil setelah anda login.";
	        $message .= "<br/><br/>Terima kasih.<br/>Admin Pusat BASTB Online";

	        $this->email->set_newline("\r\n");
	        $this->email->from('BASTB AUTO EMAIL');
	        $this->email->to($emailaddress);
	        $this->email->subject('BASTB Reset Password');
	        $this->email->message($message);

	        if($this->email->send()){
	          // $this->session->set_flashdata('info','Your new password has been sent to '.$emailaddress.'. Please check your email inbox and re-login with your new password.');
	          // redirect('main');
	        	$data = array(
					'password' => strtoupper(md5($newpass)),
					'updated_by' => 'RESET PASSWORD',
					'updated_at' => NOW,
				);

				$this->AdminModel->Update($admin->id, $data);

	        	$this->session->set_flashdata('error','Password baru telah dikirimkan ke email anda. Silahkan coba login dengan password baru anda.');
				redirect('Auth/Login');
	        }
	        else{
	          show_error($this->email->print_debugger());
	        }

		}


		
		// $this->session->sess_destroy();
		// redirect('Welcome','refresh');
	}
}
