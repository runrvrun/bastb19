<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelurahan extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('KelurahanModel');
		$this->load->model('KecamatanModel');
		$this->load->model('KabupatenModel');
		$this->load->model('ProvinsiModel');
		$this->load->helper('url');
		$this->load->library('xlsxwriter');
		// ini_set('memory_limit', '-1');
	}

	public function index()
	{
		$this->load->library('parser');
		// $param['kelurahan'] = $this->KelurahanModel->GetAll();
		$param['kelurahan'] = array();
		$data = array(
	        'title' => 'Data Kelurahan/Desa',
	        'content-path' => 'ADMINISTRATOR / DATA KELURAHAN/DESA',
	        'content' => $this->load->view('kelurahan-index', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function AjaxGetAllData()
	{
		// $data['responseData'] = $this->KelurahanModel->GetAllForAjax();
		// $hasil = json_encode($data);
		// header('HTTP/1.1: 200');
		// header('Status: 200');
		// header('Content-Length: '.strlen($hasil));
		// exit($hasil);
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
            0 =>'kode_kelurahan', 
            1 =>'nama_provinsi',
            2=> 'nama_kabupaten',
            3=> 'nama_kecamatan',
            4=> 'nama_kelurahan',
            5=> 'id',
        );

        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

		$totalData = $this->KelurahanModel->GetAllAjaxCount();
            
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
            $posts = $this->KelurahanModel->GetAllForAjax($_POST['start'], $_POST['length'], $order, $dir, $filtercond);

            $totalFiltered = $this->KelurahanModel->GetFilterAjaxCount($filtercond);
        }
        else {
            $search = $this->input->post('search')['value']; 

            $posts =  $this->KelurahanModel->GetSearchAjax($_POST['start'], $_POST['length'], $search, $order, $dir, $filtercond);

            $totalFiltered = $this->KelurahanModel->GetSearchAjaxCount($search, $filtercond);
        }

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData['kode_kelurahan'] = $post->kode_kelurahan;
                $nestedData['nama_provinsi'] = $post->nama_provinsi;
                $nestedData['nama_kabupaten'] = $post->nama_kabupaten;
                $nestedData['nama_kecamatan'] = $post->nama_kecamatan;
                $nestedData['nama_kelurahan'] = $post->nama_kelurahan;

                $tools = "";
                
                if($bolehedit)
                	$tools .= "<a class='btn btn-xs btn-primary btn-sm' href='".base_url('Kelurahan/Edit?id=').$post->id."'><i class='glyphicon glyphicon-pencil'></i></a>";
                if($bolehhapus)
                	$tools .= "<a class='btn btn-xs btn-danger btn-sm' data-href='".base_url('Kelurahan/doDelete?id=').$post->id."' data-toggle='modal' data-record-title='".$post->nama_kelurahan."' data-target='#confirm-delete'><i class='glyphicon glyphicon-trash'></i></a>";
                
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
	        'title' => 'Data Kelurahan/Desa',
	        'content-path' => 'ADMINISTRATOR / DATA KELURAHAN/DESA / TAMBAH DATA',
	        'content' => $this->load->view('kelurahan-add', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function doAdd()
	{	
		if($this->input->post('kode_kelurahan') == '' or $this->input->post('nama_kelurahan') == '' or $this->input->post('id_provinsi') == '' or $this->input->post('id_kabupaten') == '' or $this->input->post('id_kecamatan') == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('Kelurahan/Add');
		}
		else{
			$data = array(
				'kode_kelurahan' => $this->input->post('kode_kelurahan'),
				'nama_kelurahan' => strtoupper($this->input->post('nama_kelurahan')),
				'id_provinsi' => $this->input->post('id_provinsi'),
				'id_kabupaten' => $this->input->post('id_kabupaten'),
				'id_kecamatan' => $this->input->post('id_kecamatan'),
				'created_by' => $this->session->userdata('logged_in')->id_pengguna,
				'created_at' => NOW,
			);
			$this->KelurahanModel->Insert($data);
			$this->session->set_flashdata('info','Data inserted successfully.');
			redirect('Kelurahan');
		}
		
	}

	public function Edit()
	{
		$id = $this->input->get('id');

		$param['kelurahan'] = $this->KelurahanModel->Get($id);
		$param['provinsi'] = $this->ProvinsiModel->GetAll();

		$this->load->library('parser');
		$data = array(
	        'title' => 'Data Kelurahan/Desa',
	        'content-path' => 'ADMINISTRATOR / DATA KELURAHAN/DESA / UBAH DATA',
	        'content' => $this->load->view('kelurahan-edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function doEdit()
	{	
		$id = $this->input->post('id');
		if($this->input->post('kode_kelurahan') == '' or $this->input->post('nama_kelurahan') == '' or $this->input->post('id_provinsi') == '' or $this->input->post('id_kabupaten') == '' or $this->input->post('id_kecamatan') == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('Kelurahan/Edit?id='.$id);
		}
		else{
			$data = array(
				'kode_kelurahan' => $this->input->post('kode_kelurahan'),
				'nama_kelurahan' => strtoupper($this->input->post('nama_kelurahan')),
				'id_provinsi' => $this->input->post('id_provinsi'),
				'id_kabupaten' => $this->input->post('id_kabupaten'),
				'id_kecamatan' => $this->input->post('id_kecamatan'),
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->KelurahanModel->Update($id, $data);
			$this->session->set_flashdata('info','Data updated successfully.');
			redirect('Kelurahan');
		}
		
	}

	public function doDelete()
	{	
		$id = $this->input->get('id');

		$this->KelurahanModel->Delete($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('Kelurahan');
		
	}

	public function GetByKecamatan()
	{
		$id_provinsi = $this->input->get('id_provinsi');
		$id_kabupaten = $this->input->get('id_kabupaten');
		$id_kecamatan = $this->input->get('id_kecamatan');
		$data = $this->KelurahanModel->GetByKecamatan($id_provinsi, $id_kabupaten, $id_kecamatan);
		$hasil = json_encode($data);
		header('HTTP/1.1: 200');
		header('Status: 200');
		header('Content-Length: '.strlen($hasil));
		exit($hasil);
	}

	public function doExport() {
		$columns = array( 
			0 =>'kode_kelurahan', 
			1 =>'nama_provinsi',
			2=> 'nama_kabupaten',
			3=> 'nama_kecamatan',
			4=> 'nama_kelurahan',
			5=> 'id',
		);

		$order = $columns[$this->input->post('order')[0]['column']];
		$dir = $this->input->post('order')[0]['dir'];
		$filtercond = '';
		for($i=0;$i<count($columns);$i++){
			if(!empty($this->input->post('columns')[$i]['search']['value'])){
				$search = $this->input->post('columns')[$i]['search']['value'];
				$filtercond  .= " and ".$columns[$i]." LIKE '%".$search."%'"; 
			}	        	
		}

		$data = array();
		if(empty($this->input->post('search')['value'])){            
			$data = $this->KelurahanModel->ExportAllForAjax($order, $dir, $filtercond);
		}
		else {
			$search = $this->input->post('search')['value'];
			$data =  $this->KelurahanModel->ExportSearchAjax($search, $order, $dir, $filtercond);
		}

		$visible_columns = $this->input->post('visible_columns');
		$visible_header_columns = array();
		foreach($visible_columns as $value) {
			$visible_header_columns[$value['title']] = 'string';
    }
		$this->xlsxwriter->writeSheetHeader('Kelurahan', $visible_header_columns, array('font-style'=>'bold'));

		foreach($data as $row) {
			$newRow = array();
			foreach($visible_columns as $key => $value) {
				$defaultValue = '';
				if(isset($row[$value['id']])) {
					$defaultValue = $row[$value['id']];
				}
				switch($value['id']) {
					case 'nama_provinsi': 
						$newRow[$key] = $row['nama_provinsi'];
						break;
					default: 
						$newRow[$key] = $defaultValue;
				}
			}
			$this->xlsxwriter->writeSheetRow('Kelurahan', $newRow);
		}

		$uniq_id = substr(md5(uniqid(rand(), true)), 0, 5);
		$file = "upload/BASTB App Data Kelurahan - $uniq_id.xlsx";
		$this->xlsxwriter->writeToFile($file);

		header('Content-Type: application/json');
		echo json_encode(array('filename' => base_url().'Kelurahan/doDownload?filename='.$file));
	}

	public function doDownload() {
		if(isset($_GET['filename'])) {
			$file = $_GET['filename'];
			if(file_exists($file)) {
				header('Content-Description: File Transfer');
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment; filename='.basename($file));
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($file));
				readfile($file);
				unlink($file);
			}
		}
  }
}
