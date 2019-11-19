<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bart extends CI_Controller {
    public $cols = array(
		array("column" => "nomor", "caption" => "Nomor", "dbcolumn" => "nomor"),
	  	array("column" => "tanggal", "caption" => "Tanggal", "dbcolumn" => "tanggal"),
		array("column" => "jumlah_unit", "caption" => "Jumlah Unit", "dbcolumn" => "jumlah_unit"),
        array("column" => "lokasi_pemeriksaan", "caption" => "Lokasi Pemeriksaan", "dbcolumn" => "lokasi_pemeriksaan"),
    );    

    function __construct()
    {
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('Kontrak_pusat_model');
		$this->load->model('Bart_model');
        $this->load->helper('url');
		$this->load->library('xlsxwriter');
	}

    public function index($id = null)
    {
        $this->load->library('parser');
		$id_kontrak_pusat = $this->input->get('id_kontrak_pusat');
		$param['cols'] = $this->cols;
		$param['bart'] = $this->Bart_model->get();
        $param['total_unit'] = $this->Bart_model->total_unit($id_kontrak_pusat);

        if(!empty($id_kontrak_pusat)){
            $param['kontrak_pusat'] = $this->Kontrak_pusat_model->get($id_kontrak_pusat);
		}
        $data = array(
	        'title' => 'Berita Acara Running Test (BART)',
            'content-path' => 'Bart',
            'content' => $this->load->view('bart/index', $param, TRUE),
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
        
		$id_kontrak_pusat = $this->input->post('id_kontrak_pusat');
        $start = empty($this->input->post('start'))? 0:$this->input->post('start');
        $length = empty($this->input->post('length'))? null:$this->input->post('length');
        $order = empty($this->input->post('order')[0]['column'])? null:$this->input->post('order')[0]['column'];
        $dir = empty($this->input->post('order')[0]['dir'])? null:$this->input->post('order')[0]['dir'];
        
		$columns = array();
		foreach($this->cols as $key=>$val){
			array_push($columns,$val['column']);
		}

		$dbcolumns = array();
		foreach($this->cols as $key=>$val){
			array_push($dbcolumns,$val['dbcolumn']);
		}
     
        $totalData = $this->Bart_model->get(null,$id_kontrak_pusat);
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
		$posts_all_search =  $this->Bart_model->get(null, $id_kontrak_pusat,null, null, null, null, $filtercond, $search);
        $totalFiltered = count($posts_all_search);
		$posts =  $this->Bart_model->get(null, $id_kontrak_pusat, $start, $length, $order, $dir, $filtercond, $search);

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData = array();
				foreach($this->cols as $val){
					$nestedData[$val['column']] = $post->{$val['column']};
				}
				$nestedData['tanggal'] = date('d-m-Y', strtotime($post->tanggal));

                $tools = '<div class="dropdown">
				<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				-- Pilih menu --
				</button>
				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					<a class="dropdown-item" href="#" data-href="#" data-toggle="modal" data-record-title="'.$post->id.'" data-target="#upload-modal" data-record-id="'.$post->id.'">Upload Dokumen</a>
					<a class="dropdown-item" href="'.base_url('Bart_norangka?id_bart='.$post->id).'">Input No Rangka & No Mesin</a>
					<a class="dropdown-item" href="#" onclick="LoadData('.$post->id.')">Lihat Data</a>';
				if($bolehedit)
					$tools .= '<a class="dropdown-item" href="'.base_url('Bart/edit?id='.$post->id).'">Ubah Data</a>';
				if($bolehhapus)
					$tools .= '<a class="dropdown-item" href="#" data-href="'.base_url('Bart/destroy/').$post->id.'" data-toggle="modal" data-record-title="'.$post->id.'" data-target="#confirm-delete">Hapus Data</a>';
				$tools .= '</div>
				</div>';
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
		$bart = $this->Bart_model->Get($id)[0];

		$this->load->library('parser');
		$data = array(
	        'title' => 'Berita Acara Running Test (BART)',
	        'content-path' => 'Bart',
	        'content' => $this->load->view('bart/index', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

    public function get_json()
    {
        $id = $this->input->get('id');
		$data = $this->Bart_model->get($id);
		if($data->tanggal < '1900-00-00'){
			$data->tanggal = '';
		}else{
			$data->tanggal = date('d-m-Y',strtotime($data->tanggal));
		}
		$hasil = json_encode($data);
		header('HTTP/1.1: 200');
		header('Status: 200');
		header('Content-Length: '.strlen($hasil));
		exit($hasil);
    }
    
    public function create()
	{
		$this->load->library('parser');
		$param['cols'] = $this->cols;
		$param['id_kontrak_pusat'] = $this->input->get('id_kontrak_pusat');

		$data = array(
	        'title' => 'Input Berita Acara Running Test (BART)',
	        'content-path' => 'Bart / TAMBAH DATA',
	        'content' => $this->load->view('bart/add', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function store()
	{	
        $tanggal = $this->input->post('tanggal');
		$tanggal = DateTime::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');

        $data = array(
			'tanggal' => $tanggal,
			'id_kontrak_pusat' => $this->input->post('id_kontrak_pusat'),
            'created_by' => $this->session->userdata('logged_in')->id_pengguna,
            'created_at' => NOW,
        );

		//cek unit limit
		$id_kontrak_pusat = $this->input->post('id_kontrak_pusat');
		$jumlah_unit = $this->input->post('jumlah_unit');
		$total_unit = $this->Bart_model->total_unit($id_kontrak_pusat);
		$kontrak_pusat = $this->Kontrak_pusat_model->get($id_kontrak_pusat);
        if($jumlah_unit+$total_unit > $kontrak_pusat->jumlah_barang){
			$this->session->set_flashdata('error','Jumlah unit tidak dapat melebihi jumlah unit kontrak.');
			redirect('Bart/create?id_kontrak_pusat='.$id_kontrak_pusat);
		}

        foreach($this->cols as $key=>$val){
			if(!in_array($val['dbcolumn'], array('tanggal'))){
				$data[$val['dbcolumn']] = $this->input->post($val['column']);
			}
		}

        $this->Bart_model->store($data);
        $this->session->set_flashdata('info','Data inserted successfully.');
		redirect('Bart/index?id_kontrak_pusat='.$id_kontrak_pusat);
	}

	public function edit()
	{
		$id = $this->input->get('id');
		$param['cols'] = $this->cols;

		$this->load->library('parser');
        
		$param['bart'] = $this->Bart_model->get($id);

        $data = array(
	        'title' => 'Berita Acara Running Test (BART)',
	        'content-path' => 'Bart / UBAH DATA',
	        'content' => $this->load->view('bart/edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function update()
	{	

		$id = $this->input->post('id');

		$bart = $this->Bart_model->get($id);

        $tanggal = $this->input->post('tanggal');
		$tanggal = DateTime::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');

		//cek unit limit
		$id_kontrak_pusat = $this->input->post('id_kontrak_pusat');
		$jumlah_unit = $this->input->post('jumlah_unit');
		$total_unit = $this->Bart_model->total_unit($id_kontrak_pusat);
		$kontrak_pusat = $this->Kontrak_pusat_model->get($id_kontrak_pusat);
        if($jumlah_unit+$total_unit-$bart->jumlah_unit > $kontrak_pusat->jumlah_barang){
			$this->session->set_flashdata('error','Jumlah unit tidak dapat melebihi jumlah unit kontrak.');
			redirect('Bart/edit?id='.$bart->id);
		}

        foreach($this->cols as $key=>$val){
			if(!in_array($val['dbcolumn'], array('tanggal'))){
				$data[$val['dbcolumn']] = $this->input->post($val['column']);
			}
        }
        
        $this->Bart_model->update($id, $data);
        $this->session->set_flashdata('info','Data updated successfully.');
        redirect('Bart/index?id_kontrak_pusat='.$id_kontrak_pusat);
		
	}

	public function destroy($id)
	{	
		$bart = $this->Bart_model->get($id);
		$id_kontrak_pusat = $bart->id_kontrak_pusat;

		if($bart->nama_file != '' and $bart->nama_file != 'null' and !is_null($bart->nama_file) and $bart->nama_file != '[]')
			$images = json_decode($bart->nama_file);
		else
			$images = [];

		foreach($images as $image){
			unlink($this->config->item('doc_root').'/upload/bart/'.$image);	
		}

		$this->Bart_model->destroy($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('Bart/index?id_kontrak_pusat='.$id_kontrak_pusat);
	}

    public function export()
    {
        $columns = array();
		foreach($this->cols as $key=>$val){
			array_push($columns,$val['column']);
		}

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
            $data = $this->Bart_model->ExportAllForAjax($order, $dir, $filtercond);
        }
        else {
            $search = $this->input->post('search')['value']; 
            $data =  $this->Bart_model->ExportSearchAjax($search, $order, $dir, $filtercond);
        }

        $visible_columns = $this->input->post('visible_columns');
        $visible_header_columns = array();
        $this->xlsxwriter->writeSheetHeader('Bart', $visible_header_columns, array('font-style'=>'bold'));
        
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
            $this->xlsxwriter->writeSheetRow('Bart', $newRow);
        }
        
        $uniq_id = substr(md5(uniqid(rand(), true)), 0, 5);
        $file = "upload/BASTB App Bart - $uniq_id.xlsx";
        $this->xlsxwriter->writeToFile($file);

        header('Content-Type: application/json');
        echo json_encode(array('filename' => base_url().'Bart/download?filename='.$file));
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
		$id = $this->input->post('id_bart');

		$bart = $this->Bart_model->get($id);

		$kodefile_upload = strtotime(NOW);

		if($bart->nama_file != '' and $bart->nama_file != 'null' and !is_null($bart->nama_file) and $bart->nama_file != '[]')
			$nama_file = json_decode($bart->nama_file);
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
		        $targetFile =  $target_file = $this->config->item('doc_root').'/upload/bart/'.$kodefile_upload.basename($_FILES['file']['name'][$key]);
	        	move_uploaded_file($tempFile, $target_file);

		    }

		    $nama_file = json_encode($nama_file);
        	$data = array(
				'nama_file' => $nama_file,
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->Bart_model->update($id, $data);

			$this->session->set_flashdata('info','File uploaded successfully.');
			exit('success');
        }
    }
    
    public function remove_file()
    {
		$id = $this->input->get('id_bart');
		$urutanfile = $this->input->get('urutanfile');

		$bart = $this->Bart_model->get($id);

		if($bart->nama_file != '' and $bart->nama_file != 'null' and !is_null($bart->nama_file) and $bart->nama_file != '[]')
			$images = json_decode($bart->nama_file);
		else
			$images = [];

		$nama_file = $images[$urutanfile];
		unlink($this->config->item('doc_root').'/upload/bart/'.$nama_file);	

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
		$this->Bart_model->update($id, $data);

		$this->session->set_flashdata('info','File berhasil dihapus.');
		exit('success');
	}
}
	