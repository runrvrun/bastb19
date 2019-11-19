<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Informasi_terkait extends CI_Controller {
    function __construct()
    {
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('Laporan_pengawasan_model');
        $this->load->helper('url');
		$this->load->library('xlsxwriter');
	}

    public function index($id = null)
    {
        $this->load->library('parser');
        $param['laporan_pengawasan'] = $this->Laporan_pengawasan_model->get();

        $data = array(
            'title' => 'Informasi Terkait',
            'content-path' => 'INFORMASI TERKAIT',
            'content' => $this->load->view('laporan-pengawasan/index', $param, TRUE),
        );
        $this->parser->parse('default_template', $data);
	}

    public function index_json()
    { 
		$bolehtambah = 0;
		$bolehedit = 0;
        $bolehhapus = 0;

        if($this->session->userdata('logged_in')->crud_items){
			foreach($this->session->userdata('logged_in')->crud_items as $crud){
				if(rtrim($crud->crud_action) == 'TAMBAH DATA')
				  $bolehtambah = 1;
				if(rtrim($crud->crud_action) == 'EDIT DATA')
				  $bolehedit = 1;
				if(rtrim($crud->crud_action) == 'HAPUS DATA')
				  $bolehhapus = 1;
			}			
		}
        
        $start = empty($this->input->post('start'))? 0:$this->input->post('start');
        $length = empty($this->input->post('length'))? null:$this->input->post('length');
        $order = empty($this->input->post('order')[0]['column'])? null:$this->input->post('order')[0]['column'];
        $dir = empty($this->input->post('order')[0]['dir'])? null:$this->input->post('order')[0]['dir'];
        
		$columns = array( 
            0 =>'tanggal', 
            1 => 'nama_provinsi',
            2 => 'nama_kabupaten',
            3 => 'judul',
            4 => 'deskripsi',
            5 => 'id',
        );
     
        $totalData = $this->Laporan_pengawasan_model->get();
        $totalData = count($totalData);
            
        $totalFiltered = $totalData; 

        //search data percolumn
        $filtercond = '';
        for($i=0;$i<count($columns);$i++){
            if(!empty($this->input->post('columns')[$i]['search']['value'])){
                $search = $this->input->post('columns')[$i]['search']['value'];
                $filtercond  .= " and ".$columns[$i]." LIKE '%".$search."%'"; 
            }  	
        }

		$search = $this->input->post('search')['value']; 
		$posts_all_search =  $this->Laporan_pengawasan_model->get(null,null, null, null, null, $filtercond, $search);
        $totalFiltered = count($posts_all_search);
		$posts =  $this->Laporan_pengawasan_model->get(null, $start, $length, $order, $dir, $filtercond, $search);

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData['tanggal'] = date('d-m-Y',strtotime($post->tanggal)); 
                $nestedData['nama_provinsi'] = $post->nama_provinsi;
                $nestedData['nama_kabupaten'] = $post->nama_kabupaten;
                $nestedData['judul'] = $post->judul;
                $nestedData['deskripsi'] = $post->deskripsi;

                $tools =  "<a class='btn btn-xs btn-success btn-sm'><i class='glyphicon glyphicon-zoom-in' onclick='LoadData(".$post->id.")'></i></a>";
                if($bolehedit)
                    $tools .= "<a class='btn btn-xs btn-primary btn-sm' href='".base_url('Informasi_terkait/edit?id=').$post->id."'><i class='glyphicon glyphicon-pencil'></i></a>
                    <a class='btn btn-xs btn-info btn-sm' data-href='#' data-toggle='modal' data-record-title='".$post->id."' data-target='#upload-modal' data-record-id='".$post->id."'><i class='glyphicon glyphicon-open-file'></i></a>";
                if($bolehhapus)
                	$tools .= "<a class='btn btn-xs btn-danger btn-sm' data-href='".base_url('Informasi_terkait/destroy?id=').$post->id."' data-toggle='modal' data-record-title='".$post->id."' data-target='#confirm-delete'><i class='glyphicon glyphicon-trash'></i></a>";
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

    public function show($id)
    {
		$laporan_pengawasan = $this->Laporan_pengawasan_model->Get($id)[0];

		$this->load->library('parser');
		$data = array(
	        'title' => 'Informasi Terkait',
	        'content-path' => 'INFORMASI TERKAIT',
	        'content' => $this->load->view('laporan-pengawasan/index', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

    public function get_json()
    {
        $id = $this->input->get('id');
		$data = $this->Laporan_pengawasan_model->get($id);
		$hasil = json_encode($data);
		header('HTTP/1.1: 200');
		header('Status: 200');
		header('Content-Length: '.strlen($hasil));
		exit($hasil);
    }
    
    public function create()
	{
		$this->load->library('parser');
		$this->load->model('ProvinsiModel');
		$this->load->model('KabupatenModel');

		$param['provinsi'] = $this->ProvinsiModel->GetAll();

		$data = array(
	        'title' => 'Input Informasi Terkait',
	        'content-path' => 'INFORMASI TERKAIT / TAMBAH DATA',
	        'content' => $this->load->view('laporan-pengawasan/add', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function store()
	{	
        $tanggal = $this->input->post('tanggal');
		$tanggal = DateTime::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');
		$id_provinsi = $this->input->post('id_provinsi');
		$id_kabupaten = $this->input->post('id_kabupaten');
		$deskripsi = $this->input->post('deskripsi');
		$judul = $this->input->post('judul');

        $data = array(
        'tanggal' => $tanggal,
        'id_provinsi' => $id_provinsi,
        'id_kabupaten' => $id_kabupaten,
        'deskripsi' => $deskripsi,
        'judul' => $judul,
        'created_by' => $this->session->userdata('logged_in')->id_pengguna,
        'created_at' => NOW,
    );
    $this->Laporan_pengawasan_model->store($data);
    $this->session->set_flashdata('info','Data inserted successfully.');
			redirect('Laporan_pengawasan');
	}

	public function edit()
	{
		$id = $this->input->get('id');

		$this->load->library('parser');
		$this->load->model('ProvinsiModel');
        $this->load->model('KabupatenModel');
        
		$param['laporan_pengawasan'] = $this->Laporan_pengawasan_model->get($id);
		$param['provinsi'] = $this->ProvinsiModel->GetAll();
		$param['kabupaten'] = $this->KabupatenModel->GetAll();

        $data = array(
	        'title' => 'Informasi Terkait',
	        'content-path' => 'INFORMASI TERKAIT / UBAH DATA',
	        'content' => $this->load->view('laporan-pengawasan/edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function update()
	{	

		$id = $this->input->post('id');

		$pengawasan = $this->Laporan_pengawasan_model->get($id);

        $tanggal = $this->input->post('tanggal');
		$tanggal = DateTime::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');
		$id_provinsi = $this->input->post('id_provinsi');
		$id_kabupaten = $this->input->post('id_kabupaten');
		$deskripsi = $this->input->post('deskripsi');
		$judul = $this->input->post('judul');

        $target_file_1 = '';
        $target_file_2 = '';
        $target_file_3 = '';
        $nama_file_adendum_1 = '';
        $nama_file_adendum_2 = '';
        $nama_file_adendum_3 = '';

        $data = array(
            'tanggal' => $tanggal,
            'id_provinsi' => $id_provinsi,
            'id_kabupaten' => $id_kabupaten,
            'deskripsi' => $deskripsi,
            'judul' => $judul,
            'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
            'updated_at' => NOW,
        );
        $this->Laporan_pengawasan_model->update($id, $data);
        $this->session->set_flashdata('info','Data updated successfully.');
        redirect('Laporan_pengawasan');
		
	}

	public function destroy()
	{	
        $id = $this->input->get('id');
		$pengawasan = $this->Laporan_pengawasan_model->get($id);
		$id_kontrak_pusat = $pengawasan->id_kontrak_pusat;

		if($pengawasan->nama_file != '' and $pengawasan->nama_file != 'null' and !is_null($pengawasan->nama_file) and $pengawasan->nama_file != '[]')
			$images = json_decode($pengawasan->nama_file);
		else
			$images = [];

		foreach($images as $image){
			unlink($this->config->item('doc_root').'/upload/laporan_pengawasan/'.$image);	
		}

		$this->Laporan_pengawasan_model->destroy($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('Laporan_pengawasan');
	}

    public function export()
    {
        $columns = array( 
            0 => 'tanggal', 
            1 => 'nama_provinsi',
            2 => 'nama_kabupaten',
            3 => 'judul',
            4 => 'deskripsi',
            5 => 'id'
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
            $data = $this->Laporan_pengawasan_model->ExportAllForAjax($order, $dir, $filtercond);
        }
        else {
            $search = $this->input->post('search')['value']; 
            $data =  $this->Laporan_pengawasan_model->ExportSearchAjax($search, $order, $dir, $filtercond);
        }

        $visible_columns = $this->input->post('visible_columns');
        $visible_header_columns = array();
        $this->xlsxwriter->writeSheetHeader('Informasi Terkait', $visible_header_columns, array('font-style'=>'bold'));
        
        foreach($data as $row) {
            $newRow = array();
            foreach($visible_columns as $key => $value) {
                $defaultValue = '';
                if(isset($row[$value['id']])) {
                    $defaultValue = $row[$value['id']];
                }

                switch($value['id']) {
                    case 'tanggal': 
                        $newRow[$key] = $row['tanggal'];
                        break;
                    default: 
                        $newRow[$key] = $defaultValue;
                }
            }
            $this->xlsxwriter->writeSheetRow('Informasi Terkait', $newRow);
        }
        
        $uniq_id = substr(md5(uniqid(rand(), true)), 0, 5);
        $file = "upload/BASTB App Informasi Terkait - $uniq_id.xlsx";
        $this->xlsxwriter->writeToFile($file);

        header('Content-Type: application/json');
        echo json_encode(array('filename' => base_url().'Informasi_terkait/download?filename='.$file));
    }

    public function download()
    {
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

    public function upload_file()
    {
		$id_pengawasan = $this->input->post('id_pengawasan');

		$pengawasan = $this->Laporan_pengawasan_model->get($id_pengawasan);

		$kodefile_upload = strtotime(NOW);

		if($pengawasan->nama_file != '' and $pengawasan->nama_file != 'null' and !is_null($pengawasan->nama_file) and $pengawasan->nama_file != '[]')
			$nama_file = json_decode($pengawasan->nama_file);
		else
			$nama_file = [];

		foreach($_FILES['file']['tmp_name'] as $key => $value) {
	        array_push($nama_file, $kodefile_upload.basename($_FILES['file']['name'][$key]));
	    }

        if(count($nama_file) > 10){
        	$this->session->set_flashdata('error','Jumlah file tidak boleh lebih dari 10. Upload dibatalkan.');
			exit('success');
        }
        else{

		    foreach($_FILES['file']['tmp_name'] as $key => $value) {
		        $tempFile = $_FILES['file']['tmp_name'][$key];
		        $targetFile =  $target_file = $this->config->item('doc_root').'/upload/laporan_pengawasan/'.$kodefile_upload.basename($_FILES['file']['name'][$key]);
	        	move_uploaded_file($tempFile, $target_file);

		    }

		    $nama_file = json_encode($nama_file);
        	$data = array(
				'nama_file' => $nama_file,
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->Laporan_pengawasan_model->update($id_pengawasan, $data);

			$this->session->set_flashdata('info','File uploaded successfully.');
			exit('success');
        }
    }
    
    public function remove_file()
    {
		$id_pengawasan = $this->input->get('id_pengawasan');
		$urutanfile = $this->input->get('urutanfile');

		$pengawasan = $this->Laporan_pengawasan_model->get($id_pengawasan);

		if($pengawasan->nama_file != '' and $pengawasan->nama_file != 'null' and !is_null($pengawasan->nama_file) and $pengawasan->nama_file != '[]')
			$images = json_decode($pengawasan->nama_file);
		else
			$images = [];

		$nama_file = $images[$urutanfile];
		unlink($this->config->item('doc_root').'/upload/laporan_pengawasan/'.$nama_file);	

		$new_nama_file = [];
		foreach($images as $image){
			if($image != $nama_file){
				array_push($new_nama_file, $image);
			}
		}

		$nama_file = json_encode($new_nama_file);

		if($nama_file == '[]' or $nama_file == NULL or $nama_file == 'null' or is_null($nama_file)){
			$nama_file = '';
		}

		$data = array(
			'nama_file' => $nama_file,
			'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
			'updated_at' => NOW,
		);
		$this->Laporan_pengawasan_model->update($id_pengawasan, $data);

		$this->session->set_flashdata('info','File berhasil dihapus.');
		exit('success');
	}
}
	