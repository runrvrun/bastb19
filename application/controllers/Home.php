<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			$this->Logout();
		}
	}

	public function index()
	{
		// $this->load->library('parser');

		$data = array(
	        'title' => 'Home',
	        'content-path' => 'HOME /',
	        'content' => $this->load->view('home', NULL, TRUE),
		);
		// $this->parser->parse('default_template', $data);
		$this->load->view('home');
	}

	public function Logout()
	{
		$this->session->sess_destroy();
		redirect('Welcome','refresh');
	}
}
