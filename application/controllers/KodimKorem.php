<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KodimKorem extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('KodimKoremModel');
	}

	public function index()
	{
		$this->load->library('parser');
		$param['kodim_korem'] = array();
		$data = array(
	        'title' => 'Data Kodim/Korem',
	        'content-path' => 'ADMINISTRATOR / DATA KODIM/KOREM',
	        'content' => $this->load->view('kodim-korem-index', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function AjaxGetAll()
	{
		$data = $this->KodimKoremModel->GetAll();
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
            0 =>'brigade', 
            1 =>'nama_kodim_korem',
            2=> 'id',
        );

        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

		$totalData = $this->KodimKoremModel->GetAllAjaxCount();
            
        $totalFiltered = $totalData; 

		if(empty($this->input->post('search')['value']))
        {            
            $posts = $this->KodimKoremModel->GetAllForAjax($_POST['start'], $_POST['length'], $order, $dir);
        }
        else {
            $search = $this->input->post('search')['value']; 

            $posts =  $this->KodimKoremModel->GetSearchAjax($_POST['start'], $_POST['length'], $search, $order, $dir);

            $totalFiltered = $this->KodimKoremModel->GetSearchAjaxCount($search);
        }

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData['brigade'] = $post->brigade;
                $nestedData['nama_kodim_korem'] = $post->nama_kodim_korem;

                $tools = "";
                if($bolehedit)
                	$tools .= "<a class='btn btn-xs btn-primary btn-sm' href='".base_url('KodimKorem/Edit?id=').$post->id."'><i class='glyphicon glyphicon-pencil'></i></a>";
                if($bolehhapus)
                	$tools .= "<a class='btn btn-xs btn-danger btn-sm' data-href='".base_url('KodimKorem/doDelete?id=').$post->id."' data-toggle='modal' data-record-title='".$post->nama_kodim_korem."' data-target='#confirm-delete'><i class='glyphicon glyphicon-trash'></i></a>";
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
	        'content' => $this->load->view('kodim-korem-add', NULL, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function doAdd()
	{	
		if($this->input->post('brigade') == '' or $this->input->post('nama_kodim_korem') == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('KodimKorem/Add');
		}
		else{
			$data = array(
				'brigade' => $this->input->post('brigade'),
				'nama_kodim_korem' => strtoupper($this->input->post('nama_kodim_korem')),
				'created_by' => $this->session->userdata('logged_in')->id_pengguna,
				'created_at' => NOW,
			);
			$this->KodimKoremModel->Insert($data);
			$this->session->set_flashdata('info','Data inserted successfully.');
			redirect('KodimKorem');
		}
		
	}

	public function Edit()
	{
		$id = $this->input->get('id');
		$param['kodim_korem'] = $this->KodimKoremModel->Get($id);

		$this->load->library('parser');
		$data = array(
	        'title' => 'Data Kodim/Korem',
	        'content-path' => 'ADMINISTRATOR / DATA KODIM/KOREM / UBAH DATA',
	        'content' => $this->load->view('kodim-korem-edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function doEdit()
	{	
		$id = $this->input->post('id');
		if($this->input->post('brigade') == '' or $this->input->post('nama_kodim_korem') == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('KodimKorem/Edit?id='.$id);
		}
		else{
			$data = array(
				'brigade' => $this->input->post('brigade'),
				'nama_kodim_korem' => strtoupper($this->input->post('nama_kodim_korem')),
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->KodimKoremModel->Update($id, $data);
			$this->session->set_flashdata('info','Data updated successfully.');
			redirect('KodimKorem');
		}
		
	}

	public function doDelete()
	{	
		$id = $this->input->get('id');

		$this->KodimKoremModel->Delete($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('KodimKorem');
		
	}
}
