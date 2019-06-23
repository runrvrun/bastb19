<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PenyediaPusat extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('PenyediaPusatModel');
	}

	public function index()
	{
		$this->load->library('parser');
		// $param['penyedia_pusat'] = $this->PenyediaPusatModel->GetAll();
		$param['penyedia_pusat'] = array();
		$data = array(
	        'title' => 'Data Penyedia Pusat',
	        'content-path' => 'ADMINISTRATOR / DATA PENYEDIA PUSAT',
	        'content' => $this->load->view('penyedia-pusat-index', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
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
            0 =>'kode_penyedia_pusat', 
            1 =>'nama_penyedia_pusat',
            2 => 'id',
        );

        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

		$totalData = $this->PenyediaPusatModel->GetAllAjaxCount();
            
        $totalFiltered = $totalData; 

		if(empty($this->input->post('search')['value']))
        {            
            $posts = $this->PenyediaPusatModel->GetAllForAjax($_POST['start'], $_POST['length'], $order, $dir);
        }
        else {
            $search = $this->input->post('search')['value']; 

            $posts =  $this->PenyediaPusatModel->GetSearchAjax($_POST['start'], $_POST['length'], $search, $order, $dir);

            $totalFiltered = $this->PenyediaPusatModel->GetSearchAjaxCount($search);
        }

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData['kode_penyedia_pusat'] = $post->kode_penyedia_pusat;
                $nestedData['nama_penyedia_pusat'] = $post->nama_penyedia_pusat;
                $tools = "";

                if($bolehedit)
                	$tools .= "<a class='btn btn-xs btn-primary btn-sm' href='".base_url('PenyediaPusat/Edit?id=').$post->id."'><i class='glyphicon glyphicon-pencil'></i></a>";

                if($bolehhapus)
                	$tools .= "<a class='btn btn-xs btn-danger btn-sm' data-href='".base_url('PenyediaPusat/doDelete?id=').$post->id."' data-toggle='modal' data-record-title='".$post->nama_penyedia_pusat."' data-target='#confirm-delete'><i class='glyphicon glyphicon-trash'></i></a>";
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
	        'title' => 'Data Penyedia Pusat',
	        'content-path' => 'ADMINISTRATOR / DATA PENYEDIA PUSAT / TAMBAH DATA',
	        'content' => $this->load->view('penyedia-pusat-add', NULL, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function doAdd()
	{	
		if($this->input->post('nama_penyedia_pusat') == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('PenyediaPusat/Add');
		}
		else{
			$data = array(
				'nama_penyedia_pusat' => strtoupper($this->input->post('nama_penyedia_pusat')),
				'created_by' => $this->session->userdata('logged_in')->id_pengguna,
				'created_at' => NOW,
			);
			$this->PenyediaPusatModel->Insert($data);
			$this->session->set_flashdata('info','Data inserted successfully.');
			redirect('PenyediaPusat');
		}
		
	}

	public function Edit()
	{
		$id = $this->input->get('id');
		$param['penyedia_pusat'] = $this->PenyediaPusatModel->Get($id);

		$this->load->library('parser');
		$data = array(
	        'title' => 'Data Penyedia Pusat',
	        'content-path' => 'ADMINISTRATOR / DATA PENYEDIA PUSAT / UBAH DATA',
	        'content' => $this->load->view('penyedia-pusat-edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function doEdit()
	{	
		$id = $this->input->post('id');
		if($this->input->post('nama_penyedia_pusat') == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('PenyediaPusat/Edit?id='.$id);
		}
		else{
			$data = array(
				'nama_penyedia_pusat' => strtoupper($this->input->post('nama_penyedia_pusat')),
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->PenyediaPusatModel->Update($id, $data);
			$this->session->set_flashdata('info','Data updated successfully.');
			redirect('PenyediaPusat');
		}
		
	}

	public function doDelete()
	{	
		$id = $this->input->get('id');

		$this->PenyediaPusatModel->Delete($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('PenyediaPusat');
		
	}
}
