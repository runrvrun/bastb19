<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kontrak_ongkir extends CI_Controller {
    public $cols = array(
		array("column" => "tahun_anggaran", "caption" => "Tahun Anggaran", "dbcolumn" => "tahun_anggaran"),
		array("column" => "nomor", "caption" => "Nomor", "dbcolumn" => "nomor"),
	  	array("column" => "tanggal", "caption" => "Tanggal", "dbcolumn" => "tanggal"),
		array("column" => "ekspedisi", "caption" => "Nama Ekspedisi", "dbcolumn" => "ekspedisi"),
        array("column" => "ongkir", "caption" => "Nilai Ongkir (Rp)", "dbcolumn" => "ongkir"),
    );    

    function __construct()
    {
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('Kontrak_ongkir_model');
        $this->load->helper('url');
		$this->load->library('xlsxwriter');
	}

    public function index($id = null)
    {
        $this->load->library('parser');
		$param['cols'] = $this->cols;
        $param['kontrak_ongkir'] = $this->Kontrak_ongkir_model->get();
        $param['total_nilai'] = $this->Kontrak_ongkir_model->total_nilai();

        $data = array(
            'title' => 'KONTRAK ONGKIR',
            'content-path' => 'KONTRAK ONGKIR',
            'content' => $this->load->view('kontrak-ongkir/index', $param, TRUE),
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
        
		$columns = array();
		foreach($this->cols as $key=>$val){
			if($val['column'] == 'tanggal'){
				array_push($columns,date('d-m-Y', strtotime($val['column'])));
			}else{
				array_push($columns,$val['column']);
			}
		}

		$dbcolumns = array();
		foreach($this->cols as $key=>$val){
			array_push($dbcolumns,$val['dbcolumn']);
		}
     
        $totalData = $this->Kontrak_ongkir_model->get();
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
		$posts_all_search =  $this->Kontrak_ongkir_model->get(null,null, null, null, null, $filtercond, $search);
        $totalFiltered = count($posts_all_search);
		$posts =  $this->Kontrak_ongkir_model->get(null, $start, $length, $order, $dir, $filtercond, $search);

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData = array();
				foreach($this->cols as $val){
					$nestedData[$val['column']] = $post->{$val['column']};
				}
				$nestedData['tanggal'] = date('d-m-Y',strtotime($post->tanggal));
				$nestedData['ongkir'] = number_format($nestedData['ongkir'],0);

                $tools =  "<a class='btn btn-xs btn-success btn-sm'><i class='glyphicon glyphicon-zoom-in' onclick='LoadData(".$post->id.")'></i></a>";
                if($bolehedit)
                    $tools .= "<a class='btn btn-xs btn-primary btn-sm' href='".base_url('Kontrak_ongkir/edit?id=').$post->id."'><i class='glyphicon glyphicon-pencil'></i></a>
                <a class='btn btn-xs btn-info btn-sm' data-href='#' data-toggle='modal' data-record-title='".$post->id."' data-target='#upload-modal' data-record-id='".$post->id."'><i class='glyphicon glyphicon-open-file'></i></a>";
                if($bolehhapus)
                    $tools .= "<a class='btn btn-xs btn-danger btn-sm' data-href='".base_url('Kontrak_ongkir/destroy?id=').$post->id."' data-toggle='modal' data-record-title='".$post->id."' data-target='#confirm-delete'><i class='glyphicon glyphicon-trash'></i></a>";
                	$tools .=  "<a class='btn btn-xs btn-success btn-sm' href='".base_url('Penggunaan_ongkir/index?id_kontrak_ongkir=').$post->id."'><i class='glyphicon glyphicon-th-list' title='Lihat Detail'></i></a>";
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
		$kontrak_ongkir = $this->Kontrak_ongkir_model->get($id);

		$this->load->library('parser');
		$data = array(
	        'title' => 'KONTRAK ONGKIR',
	        'content-path' => 'KONTRAK ONGKIR',
	        'content' => $this->load->view('kontrak-ongkir/index', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

    public function get_json()
    {
		$id = $this->input->get('id');
		$data = $this->Kontrak_ongkir_model->get($id);
		if($data->tanggal < '1900-00-00'){
			$data->tanggal = '';
		}else{
			$data->tanggal = date('d-m-Y',strtotime($data->tanggal));
		}
		$data->ongkir = number_format($data->ongkir,0);
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

		$data = array(
	        'title' => 'Input KONTRAK ONGKIR', 
	        'content-path' => 'KONTRAK ONGKIR / TAMBAH DATA',
	        'content' => $this->load->view('kontrak-ongkir/add', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function store()
	{	
        $tanggal = $this->input->post('tanggal');
		$tanggal = DateTime::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');

        $data = array(
            'tanggal' => $tanggal,
            'created_by' => $this->session->userdata('logged_in')->id_pengguna,
            'created_at' => NOW,
        );

        foreach($this->cols as $key=>$val){
			if(!in_array($val['dbcolumn'], array('tanggal'))){
				$data[$val['dbcolumn']] = $this->input->post($val['column']);
			}
		}

        $this->Kontrak_ongkir_model->store($data);
        $this->session->set_flashdata('info','Data inserted successfully.');
		redirect('Kontrak_ongkir');
	}

	public function edit()
	{
		$id = $this->input->get('id');
		$param['cols'] = $this->cols;

		$this->load->library('parser');
        
		$param['kontrak_ongkir'] = $this->Kontrak_ongkir_model->get($id);

        $data = array(
	        'title' => 'KONTRAK ONGKIR',
	        'content-path' => 'KONTRAK ONGKIR / UBAH DATA',
	        'content' => $this->load->view('kontrak-ongkir/edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function update()
	{	

		$id = $this->input->post('id');

		$kontrak_ongkir = $this->Kontrak_ongkir_model->get($id);

        $tanggal = $this->input->post('tanggal');
		$tanggal = DateTime::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');

        foreach($this->cols as $key=>$val){
			if(!in_array($val['dbcolumn'], array('tanggal'))){
				$data[$val['dbcolumn']] = $this->input->post($val['column']);
			}
        }
        
        $this->Kontrak_ongkir_model->update($id, $data);
        $this->session->set_flashdata('info','Data updated successfully.');
        redirect('Kontrak_ongkir');
		
	}

	public function destroy()
	{	
        $id = $this->input->get('id');
		$kontrak_ongkir = $this->Kontrak_ongkir_model->get($id);
		$id_kontrak_pusat = $kontrak_ongkir->id_kontrak_pusat;

		if($kontrak_ongkir->nama_file != '' and $kontrak_ongkir->nama_file != 'null' and !is_null($kontrak_ongkir->nama_file) and $kontrak_ongkir->nama_file != '[]')
			$images = json_decode($kontrak_ongkir->nama_file);
		else
			$images = [];

		foreach($images as $image){
			unlink($this->config->item('doc_root').'/upload/kontrak_ongkir/'.$image);	
		}

		$this->Kontrak_ongkir_model->destroy($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('Kontrak_ongkir');
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
            $data = $this->Kontrak_ongkir_model->ExportAllForAjax($order, $dir, $filtercond);
        }
        else {
            $search = $this->input->post('search')['value']; 
            $data =  $this->Kontrak_ongkir_model->ExportSearchAjax($search, $order, $dir, $filtercond);
        }

        $visible_columns = $this->input->post('visible_columns');
        $visible_header_columns = array();
        $this->xlsxwriter->writeSheetHeader('KONTRAK ONGKIR', $visible_header_columns, array('font-style'=>'bold'));
        
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
            $this->xlsxwriter->writeSheetRow('KONTRAK ONGKIR', $newRow);
        }
        
        $uniq_id = substr(md5(uniqid(rand(), true)), 0, 5);
        $file = "upload/BASTB App KONTRAK ONGKIR - $uniq_id.xlsx";
        $this->xlsxwriter->writeToFile($file);

        header('Content-Type: application/json');
        echo json_encode(array('filename' => base_url().'Kontrak_ongkir/download?filename='.$file));
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
		$id = $this->input->post('id');
		$kontrak_ongkir = $this->Kontrak_ongkir_model->get($id);

		$kodefile_upload = strtotime(NOW);

		if($kontrak_ongkir->nama_file != '' and $kontrak_ongkir->nama_file != null)
			$nama_file = json_decode($kontrak_ongkir->nama_file);
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
		        $targetFile =  $target_file = $this->config->item('doc_root').'/upload/kontrak_ongkir/'.$kodefile_upload.basename($_FILES['file']['name'][$key]);
	        	move_uploaded_file($tempFile, $target_file);
		    }

		    $nama_file = json_encode($nama_file);
        	$data = array(
				'nama_file' => $nama_file,
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->Kontrak_ongkir_model->update($id, $data);

			$this->session->set_flashdata('info','File uploaded successfully.');
			exit('success');
        }
    }
    
    public function remove_file()
    {
		$id = $this->input->get('id');
		$urutanfile = $this->input->get('urutanfile');

		$kontrak_ongkir = $this->Kontrak_ongkir_model->get($id);

		if($kontrak_ongkir->nama_file != '' and $kontrak_ongkir->nama_file != 'null' and !is_null($kontrak_ongkir->nama_file) and $kontrak_ongkir->nama_file != '[]')
			$images = json_decode($kontrak_ongkir->nama_file);
		else
			$images = [];

		$nama_file = $images[$urutanfile];
		unlink($this->config->item('doc_root').'/upload/kontrak_ongkir/'.$nama_file);	

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
		$this->Kontrak_ongkir_model->update($id, $data);

		$this->session->set_flashdata('info','File berhasil dihapus.');
		exit('success');
	}
}
	