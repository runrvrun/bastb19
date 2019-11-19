<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class JenisBarangPusat extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('JenisBarangPusatModel');
		$this->load->model('PenyediaPusatModel');
	}

	public function index()
	{
		$this->load->library('parser');
		// $param['jenis_barang_pusat'] = $this->JenisBarangPusatModel->GetAll();
		$param['jenis_barang_pusat'] = array();
		$data = array(
	        'title' => 'Data Jenis Barang Pusat',
	        'content-path' => 'ADMINISTRATOR / DATA JENIS BARANG PUSAT',
	        'content' => $this->load->view('jenis-barang-pusat-index', $param, TRUE),
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
            0 =>'jenis_barang', 
            1 =>'nama_barang',
            2=> 'merk',
            3=> 'nama_penyedia_pusat',
            4=> 'kode_barang',
            5=> 'akun',
            6=> 'id',
        );

        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

		$totalData = $this->JenisBarangPusatModel->GetAllAjaxCount();
            
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
            $posts = $this->JenisBarangPusatModel->GetAllForAjax($_POST['start'], $_POST['length'], $order, $dir, $filtercond);
            $totalFiltered = $this->JenisBarangPusatModel->GetFilterAjaxCount($filtercond);
        }
        else {
            $search = $this->input->post('search')['value']; 

            $posts =  $this->JenisBarangPusatModel->GetSearchAjax($_POST['start'], $_POST['length'], $search, $order, $dir, $filtercond);

            $totalFiltered = $this->JenisBarangPusatModel->GetSearchAjaxCount($search, $filtercond);
        }

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData['jenis_barang'] = $post->jenis_barang;
                $nestedData['nama_barang'] = $post->nama_barang;
                $nestedData['merk'] = $post->merk;
                $nestedData['nama_penyedia_pusat'] = $post->nama_penyedia_pusat;
                $nestedData['kode_barang'] = $post->kode_barang;
                $nestedData['akun'] = $post->akun;
                $tools = "";
                if($bolehedit)
                	$tools .= "<a class='btn btn-xs btn-primary btn-sm' href='".base_url('JenisBarangPusat/Edit?id=').$post->id."'><i class='glyphicon glyphicon-pencil'></i></a>";
                if($bolehhapus)
                	$tools .= "<a class='btn btn-xs btn-danger btn-sm' data-href='".base_url('JenisBarangPusat/doDelete?id=').$post->id."' data-toggle='modal' data-record-title='".$post->jenis_barang." ".$post->nama_barang." Merk : ".$post->merk."' data-target='#confirm-delete'><i class='glyphicon glyphicon-trash'></i></a>";
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
		$param['penyedia_pusat'] = $this->PenyediaPusatModel->GetAll();
		$data = array(
	        'title' => 'Data Jenis Barang Pusat',
	        'content-path' => 'ADMINISTRATOR / DATA JENIS BARANG PUSAT / TAMBAH DATA',
	        'content' => $this->load->view('jenis-barang-pusat-add', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function doAdd()
	{	
		if($this->input->post('jenis_barang') == '' or $this->input->post('nama_barang') == '' or $this->input->post('merk') == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('JenisBarangPusat/Add');
		}
		else{
			$data = array(
				'jenis_barang' => strtoupper($this->input->post('jenis_barang')),
				'nama_barang' => strtoupper($this->input->post('nama_barang')),
				'merk' => strtoupper($this->input->post('merk')),
				'id_penyedia_pusat' => strtoupper($this->input->post('id_penyedia_pusat')),
				'kode_barang' => strtoupper($this->input->post('kode_barang')),
				'akun' => strtoupper($this->input->post('akun')),
				'created_by' => $this->session->userdata('logged_in')->id_pengguna,
				'created_at' => NOW,
			);
			$this->JenisBarangPusatModel->Insert($data);
			$this->session->set_flashdata('info','Data inserted successfully.');
			redirect('JenisBarangPusat');
		}
		
	}

	public function Edit()
	{
		$id = $this->input->get('id');
		$param['jenis_barang_pusat'] = $this->JenisBarangPusatModel->Get($id);
		$param['penyedia_pusat'] = $this->PenyediaPusatModel->GetAll();

		$this->load->library('parser');
		$data = array(
	        'title' => 'Data Jenis Barang Pusat',
	        'content-path' => 'ADMINISTRATOR / DATA JENIS BARANG PUSAT / UBAH DATA',
	        'content' => $this->load->view('jenis-barang-pusat-edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function doEdit()
	{	
		$id = $this->input->post('id');
		if($this->input->post('jenis_barang') == '' or $this->input->post('nama_barang') == '' or $this->input->post('merk') == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('JenisBarangPusat/Edit?id='.$id);
		}
		else{
			$data = array(
				'jenis_barang' => strtoupper($this->input->post('jenis_barang')),
				'nama_barang' => strtoupper($this->input->post('nama_barang')),
				'merk' => strtoupper($this->input->post('merk')),
				'id_penyedia_pusat' => strtoupper($this->input->post('id_penyedia_pusat')),
				'kode_barang' => strtoupper($this->input->post('kode_barang')),
				'akun' => strtoupper($this->input->post('akun')),
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->JenisBarangPusatModel->Update($id, $data);
			$this->session->set_flashdata('info','Data updated successfully.');
			redirect('JenisBarangPusat');
		}
		
	}

	public function doDelete()
	{	
		$id = $this->input->get('id');

		$this->JenisBarangPusatModel->Delete($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('JenisBarangPusat');
		
	}

	public function GetByPenyedia()
	{
		$id_penyedia_pusat = urldecode($this->input->get('id_penyedia_pusat'));

		$data = $this->JenisBarangPusatModel->GetByPenyedia($id_penyedia_pusat);
		$hasil = json_encode($data);
		header('HTTP/1.1: 200');
		header('Status: 200');
		header('Content-Length: '.strlen($hasil));
		exit($hasil);
	}

	public function GetMerk()
	{
		// $nama_barang = urldecode($this->input->get('nama_barang'));
		$nama_barang = $this->input->get('nama_barang');
		$id_penyedia_pusat = urldecode($this->input->get('id_penyedia_pusat'));

		// $data = $this->JenisBarangPusatModel->GetMerk($id_jenis_barang_pusat);
		$data = $this->JenisBarangPusatModel->GetMerkByNamaBarang($id_penyedia_pusat, $nama_barang);
		$hasil = json_encode($data);
		header('HTTP/1.1: 200');
		header('Status: 200');
		header('Content-Length: '.strlen($hasil));
		exit($hasil);
	}
}
