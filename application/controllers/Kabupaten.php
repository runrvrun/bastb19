<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kabupaten extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('KabupatenModel');
		$this->load->model('ProvinsiModel');
	}

	public function index()
	{
		$this->load->library('parser');
		// $param['kabupaten'] = $this->KabupatenModel->GetAll();
		$param['kabupaten'] = array();
		$data = array(
	        'title' => 'Data Kabupaten/Kota',
	        'content-path' => 'ADMINISTRATOR / DATA KABUPATEN/KOTA',
	        'content' => $this->load->view('kabupaten-index', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function AjaxGetAll()
	{

		$data = $this->KabupatenModel->GetSemua();
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
            0 =>'kode_kabupaten', 
            1 =>'nama_provinsi',
            2 =>'nama_kabupaten',
            3 => 'id',
        );

        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

		$totalData = $this->KabupatenModel->GetAllAjaxCount();
            
        $totalFiltered = $totalData; 

        //search data percolumn
        $filtercond = '';
        for($i=0;$i<count($columns);$i++){
        	if(!empty($this->input->post('columns')[$i]['search']['value'])){
        		$search = $this->input->post('columns')[$i]['search']['value'];
        		$filtercond  .= " and ".$columns[$i]." LIKE '%".$search."%'"; 
        	}	        	
        }

		if(empty($this->input->post('search')['value']))
        {            
            $posts = $this->KabupatenModel->GetAllForAjax($_POST['start'], $_POST['length'], $order, $dir, $filtercond);
            $totalFiltered = $this->KabupatenModel->GetFilterAjaxCount($filtercond);
        }
        else {
            $search = $this->input->post('search')['value']; 

            $posts =  $this->KabupatenModel->GetSearchAjax($_POST['start'], $_POST['length'], $search, $order, $dir, $filtercond);

            $totalFiltered = $this->KabupatenModel->GetSearchAjaxCount($search, $filtercond);
        }

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData['kode_kabupaten'] = $post->kode_kabupaten;
                $nestedData['nama_provinsi'] = $post->nama_provinsi;
                $nestedData['nama_kabupaten'] = $post->nama_kabupaten;

                $tools = "";
                
                if($bolehedit)
                	$tools .= "<a class='btn btn-xs btn-primary btn-sm' href='".base_url('Kabupaten/Edit?id=').$post->id."'><i class='glyphicon glyphicon-pencil'></i></a>";
                if($bolehhapus)
                	$tools .= "<a class='btn btn-xs btn-danger btn-sm' data-href='".base_url('Kabupaten/doDelete?id=').$post->id."' data-toggle='modal' data-record-title='".$post->nama_kabupaten."' data-target='#confirm-delete'><i class='glyphicon glyphicon-trash'></i></a>";

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
		$param['provinsi'] = $this->ProvinsiModel->GetAll();
		$data = array(
	        'title' => 'Data Kabupaten/Kota',
	        'content-path' => 'ADMINISTRATOR / DATA KABUPATEN/KOTA / TAMBAH DATA',
	        'content' => $this->load->view('kabupaten-add', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function doAdd()
	{	
		if($this->input->post('kode_kabupaten') == '' or $this->input->post('nama_kabupaten') == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('Kabupaten/Add');
		}
		else{
			$data = array(
				'kode_kabupaten' => $this->input->post('kode_kabupaten'),
				'nama_kabupaten' => strtoupper($this->input->post('nama_kabupaten')),
				'id_provinsi' => strtoupper($this->input->post('id_provinsi')),
				'created_by' => $this->session->userdata('logged_in')->id_pengguna,
				'created_at' => NOW,
			);
			$this->KabupatenModel->Insert($data);
			$this->session->set_flashdata('info','Data inserted successfully.');
			redirect('Kabupaten');
		}
		
	}

	public function Edit()
	{
		$id = $this->input->get('id');
		$param['kabupaten'] = $this->KabupatenModel->Get($id);
		$param['provinsi'] = $this->ProvinsiModel->GetAll();

		$this->load->library('parser');
		$data = array(
	        'title' => 'Data Kabupaten/Kota',
	        'content-path' => 'ADMINISTRATOR / DATA KABUPATEN/KOTA / UBAH DATA',
	        'content' => $this->load->view('kabupaten-edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function doEdit()
	{	
		$id = $this->input->post('id');
		if($this->input->post('kode_kabupaten') == '' or $this->input->post('nama_kabupaten') == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('Kabupaten/Edit?id='.$id);
		}
		else{
			$data = array(
				'kode_kabupaten' => $this->input->post('kode_kabupaten'),
				'nama_kabupaten' => strtoupper($this->input->post('nama_kabupaten')),
				'id_provinsi' => strtoupper($this->input->post('id_provinsi')),
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->KabupatenModel->Update($id, $data);
			$this->session->set_flashdata('info','Data updated successfully.');
			redirect('Kabupaten');
		}
		
	}

	public function doDelete()
	{	
		$id = $this->input->get('id');

		$this->KabupatenModel->Delete($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('Kabupaten');
		
	}

	public function GetByProvinsi()
	{
		$id_provinsi = $this->input->get('id_provinsi');
		$data = $this->KabupatenModel->GetByProvinsi($id_provinsi);
		$hasil = json_encode($data);
		header('HTTP/1.1: 200');
		header('Status: 200');
		header('Content-Length: '.strlen($hasil));
		exit($hasil);
	}

	public function GetByListProvinsi()
	{
		$list_id_provinsi = $this->input->get('list_id_provinsi');

		if($list_id_provinsi){
			$data = $this->KabupatenModel->GetByListProvinsi($list_id_provinsi);
			$hasil = json_encode($data);
			header('HTTP/1.1: 200');
			header('Status: 200');
			header('Content-Length: '.strlen($hasil));
			exit($hasil);
		}
		
	}
}
