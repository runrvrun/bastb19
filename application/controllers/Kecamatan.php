<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kecamatan extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('KecamatanModel');
		$this->load->model('KabupatenModel');
		$this->load->model('ProvinsiModel');
	}

	public function index()
	{
		$this->load->library('parser');
		// $param['kecamatan'] = $this->KecamatanModel->GetAll();
		$param['kecamatan'] = array();
		$data = array(
	        'title' => 'Data Kecamatan',
	        'content-path' => 'ADMINISTRATOR / DATA KECAMATAN',
	        'content' => $this->load->view('kecamatan-index', $param, TRUE),
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
            0 =>'kode_kecamatan', 
            1 =>'nama_provinsi',
            2=> 'nama_kabupaten',
            3=> 'nama_kecamatan',
            4=> 'id',
        );

        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

		$totalData = $this->KecamatanModel->GetAllAjaxCount();
            
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
            $posts = $this->KecamatanModel->GetAllForAjax($_POST['start'], $_POST['length'], $order, $dir, $filtercond);
            $totalFiltered = $this->KecamatanModel->GetFilterAjaxCount($filtercond);
        }
        else {
            $search = $this->input->post('search')['value']; 

            $posts =  $this->KecamatanModel->GetSearchAjax($_POST['start'], $_POST['length'], $search, $order, $dir, $filtercond);

            $totalFiltered = $this->KecamatanModel->GetSearchAjaxCount($search, $filtercond);
        }

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData['kode_kecamatan'] = $post->kode_kecamatan;
                $nestedData['nama_provinsi'] = $post->nama_provinsi;
                $nestedData['nama_kabupaten'] = $post->nama_kabupaten;
                $nestedData['nama_kecamatan'] = $post->nama_kecamatan;

                $tools = "";

                if($bolehedit)
                	$tools .= "<a class='btn btn-xs btn-primary btn-sm' href='".base_url('Kecamatan/Edit?id=').$post->id."'><i class='glyphicon glyphicon-pencil'></i></a>";

                if($bolehhapus)
                	$tools .= "<a class='btn btn-xs btn-danger btn-sm' data-href='".base_url('Kecamatan/doDelete?id=').$post->id."' data-toggle='modal' data-record-title='".$post->nama_kecamatan."' data-target='#confirm-delete'><i class='glyphicon glyphicon-trash'></i></a>";

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
	        'title' => 'Data Kecamatan',
	        'content-path' => 'ADMINISTRATOR / DATA KECAMATAN / TAMBAH DATA',
	        'content' => $this->load->view('kecamatan-add', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function doAdd()
	{	
		if($this->input->post('kode_kecamatan') == '' or $this->input->post('nama_kecamatan') == '' or $this->input->post('id_provinsi') == '' or $this->input->post('id_kabupaten') == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('Kecamatan/Add');
		}
		else{
			$data = array(
				'kode_kecamatan' => $this->input->post('kode_kecamatan'),
				'nama_kecamatan' => strtoupper($this->input->post('nama_kecamatan')),
				'id_provinsi' => $this->input->post('id_provinsi'),
				'id_kabupaten' => $this->input->post('id_kabupaten'),
				'created_by' => $this->session->userdata('logged_in')->id_pengguna,
				'created_at' => NOW,
			);
			$this->KecamatanModel->Insert($data);
			$this->session->set_flashdata('info','Data inserted successfully.');
			redirect('Kecamatan');
		}
		
	}

	public function Edit()
	{
		$id = $this->input->get('id');

		$param['kecamatan'] = $this->KecamatanModel->Get($id);
		$param['provinsi'] = $this->ProvinsiModel->GetAll();

		$this->load->library('parser');
		$data = array(
	        'title' => 'Data Kecamatan',
	        'content-path' => 'ADMINISTRATOR / DATA KECAMATAN / UBAH DATA',
	        'content' => $this->load->view('kecamatan-edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function doEdit()
	{	
		$id = $this->input->post('id');
		if($this->input->post('kode_kecamatan') == '' or $this->input->post('nama_kecamatan') == '' or $this->input->post('id_provinsi') == '' or $this->input->post('id_kabupaten') == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('Kecamatan/Edit?id='.$id);
		}
		else{
			$data = array(
				'kode_kecamatan' => $this->input->post('kode_kecamatan'),
				'nama_kecamatan' => strtoupper($this->input->post('nama_kecamatan')),
				'id_provinsi' => $this->input->post('id_provinsi'),
				'id_kabupaten' => $this->input->post('id_kabupaten'),
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->KecamatanModel->Update($id, $data);
			$this->session->set_flashdata('info','Data updated successfully.');
			redirect('Kecamatan');
		}
		
	}

	public function doDelete()
	{	
		$id = $this->input->get('id');

		$this->KecamatanModel->Delete($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('Kecamatan');
		
	}

	public function GetByKabupaten()
	{
		$id_provinsi = $this->input->get('id_provinsi');
		$id_kabupaten = $this->input->get('id_kabupaten');
		$data = $this->KecamatanModel->GetByKabupaten($id_provinsi, $id_kabupaten);
		$hasil = json_encode($data);
		header('HTTP/1.1: 200');
		header('Status: 200');
		header('Content-Length: '.strlen($hasil));
		exit($hasil);
	}
}
