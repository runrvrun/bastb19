<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bastb_provinsi_norangka extends CI_Controller {
    public $cols = array(
		array("column" => "norangka", "caption" => "Nomor Rangka", "dbcolumn" => "norangka"),
	  	array("column" => "nomesin", "caption" => "Nomor Mesin", "dbcolumn" => "nomesin")
	);    

    function __construct()
    {
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('Bastb_provinsi_model');
		$this->load->model('Norangka_model');
		$this->load->model('Bastb_provinsi_norangka_model');
		$this->load->model('Baphp_provinsi_norangka_model');
		$this->load->model('Bastb_pusat_model');
        $this->load->helper('url');
		$this->load->library('xlsxwriter');
	}

    public function index($id = null)
    {
        $this->load->library('parser');
		$id_bastb_provinsi = $this->input->get('id_bastb_provinsi');
		$param['cols'] = $this->cols;
		$param['bastb_provinsi_norangka'] = $this->Bastb_provinsi_norangka_model->get(null,$id_bastb_provinsi);
        $param['bastb_provinsi'] = $this->Bastb_provinsi_model->get($id_bastb_provinsi);
		if(empty($param['bastb_provinsi'])) show_404();
        $param['id_bastb_provinsi'] = $id_bastb_provinsi;

        $data = array(
			'title' => 'Data Nomor Rangka & Nomor Mesin',
            'content-path' => strtoupper('Kontrak Provinsi / BASTB Provinsi / Nomor Rangka & Nomor Mesin'),
            'content' => $this->load->view('bastb-provinsi-norangka/index', $param, TRUE),
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
        
		$id_bastb_provinsi = $this->input->post('id_bastb_provinsi');
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
     
        $totalData = $this->Bastb_provinsi_norangka_model->get(null,$id_bastb_provinsi);
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
		$posts_all_search =  $this->Bastb_provinsi_norangka_model->get(null, $id_bastb_provinsi,null, null, null, null, $filtercond, $search);
        $totalFiltered = count($posts_all_search);
		$posts =  $this->Bastb_provinsi_norangka_model->get(null, $id_bastb_provinsi, $start, $length, $order, $dir, $filtercond, $search);

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();
				foreach($this->cols as $val){
					$nestedData[$val['column']] = $post->{$val['column']};
				}
				if($bolehedit)
					$tools = "<a class='btn btn-xs btn-primary btn-sm' href='".base_url('Bastb_provinsi_norangka/edit?id=').$post->id."'><i class='glyphicon glyphicon-pencil'></i></a>";			
                if($bolehhapus)
                    $tools .= "<a class='btn btn-xs btn-danger btn-sm' data-href='".base_url('Bastb_provinsi_norangka/destroy?id=').$post->id."' data-toggle='modal' data-record-title='".$post->id."' data-target='#confirm-delete'><i class='glyphicon glyphicon-trash'></i></a>";
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
		$bastb_provinsi_norangka = $this->Bastb_provinsi_norangka_model->Get($id)[0];

		$this->load->library('parser');
		$data = array(
			'title' => 'Data Nomor Rangka & Nomor Mesin',
            'content-path' => strtoupper('Kontrak Provinsi / BASTB Provinsi / Nomor Rangka & Nomor Mesin'),
	        'content' => $this->load->view('bastb-provinsi-norangka/index', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

    public function get_json()
    {
        $id = $this->input->get('id');
		$data = $this->Bastb_provinsi_norangka_model->get($id);
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
		$param['id_bastb_provinsi'] = $this->input->get('id_bastb_provinsi');
		$param['bastb_provinsi'] = $this->Bastb_provinsi_model->get($this->input->get('id_bastb_provinsi'));
		if(empty($param['bastb_provinsi'])) show_404();
		$param['id_kontrak_provinsi'] = $param['bastb_provinsi']->id_kontrak_provinsi;

		$data = array(
			'title' => 'Pilih Nomor Rangka & Nomor Mesin',
            'content-path' => strtoupper('Kontrak Provinsi / BASTB Provinsi / Nomor Rangka & Nomor Mesin / Pilih Data'),
	        'content' => $this->load->view('bastb-provinsi-norangka/add', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function storeinputmanual()
	{	
		$id_bastb_provinsi = $this->input->post('id_bastb_provinsi');
		$id_kontrak_provinsi = $this->input->post('id_kontrak_provinsi');
  
        $data = array(
			'id_bastb_provinsi' => $id_bastb_provinsi,
            'created_by' => $this->session->userdata('logged_in')->id_pengguna,
            'created_at' => NOW,
		);
		
		$norangkanomesin = $this->input->post('norangkanomesin');
		$nono = preg_split('/\r\n|[\r\n]/', $norangkanomesin);

		//check duplicate from inputted norangka nomesin
		foreach($nono as $key=>$val){
			$val = trim($val);
			$val = str_replace(';',',',$val);//so only 1 explode
			$nonox = explode(',',$val);
			if(isset($nonox[1]) && !empty($nonox[0])) {
				$nonoxe = preg_replace("/[^a-zA-Z0-9]+/", "", $nonox[0]);
				$nonoxe = trim($nonoxe);
				$inpnorangka[] = strtoupper($nonoxe); 
			}
			if(isset($nonox[1]) && !empty($nonox[1])) {
				$nonoxe = preg_replace("/[^a-zA-Z0-9]+/", "", $nonox[1]);
				$nonoxe = trim($nonoxe);
				$inpnomesin[] = strtoupper($nonoxe); 
			}
		}
		sort($inpnorangka);
		sort($inpnomesin);
		if(count($inpnorangka) > 1){
			for($i=0;$i<count($inpnorangka)-1;$i++){
				if($inpnorangka[$i] == $inpnorangka[$i+1]){
					$this->session->set_flashdata('error','Nomor rangka duplikat: '.$val);
					redirect('Bastb_provinsi_norangka/create?id_bastb_provinsi='.$id_bastb_provinsi);
				}
			}
			for($i=0;$i<count($inpnomesin)-1;$i++){
				if($inpnomesin[$i] == $inpnomesin[$i+1]){
					$this->session->set_flashdata('error','Nomor mesin duplikat: '.$val);
					redirect('Bastb_provinsi_norangka/create?id_bastb_provinsi='.$id_bastb_provinsi);
				}
			}
		}

		//check duplicate againts existing norangka nomesin
		$extnorangkaq = $this->Bastb_provinsi_norangka_model->get_norangka();
		if(count($extnorangkaq) > 0){
			foreach($extnorangkaq as $key=>$val){
				$extnorangka[] = $val->norangka;
			}
			$extnomesinq = $this->Bastb_provinsi_norangka_model->get_nomesin();
			foreach($extnomesinq as $key=>$val){
				$extnomesin[] = $val->nomesin;
			}
			$dupnorangka = array_intersect($inpnorangka, $extnorangka);
			$dupnomesin = array_intersect($inpnomesin, $extnomesin);
			// var_dump($inpnomesin); var_dump($extnomesin); var_dump($dupnomesin);exit();
			if(count($dupnorangka) > 0){
				$this->session->set_flashdata('error','Nomor rangka sudah ada di database: '.join(', ', $dupnorangka));
				redirect('Bastb_provinsi_norangka/create?id_bastb_provinsi='.$id_bastb_provinsi);
			}
			if(count($dupnomesin) > 0){
				$this->session->set_flashdata('error','Nomor mesin sudah ada di database: '.join(', ', $dupnomesin));
				redirect('Bastb_provinsi_norangka/create?id_bastb_provinsi='.$id_bastb_provinsi);
			}
		}
        
		//cek row limit
		$jumlah_input = count($nono);
		$norangka = $this->Bastb_provinsi_norangka_model->get(null,$this->input->post('id_bastb_provinsi'));
		$bastb_provinsi = $this->Bastb_provinsi_model->get($this->input->post('id_bastb_provinsi'));
        if(count($norangka)+$jumlah_input > $bastb_provinsi->jumlah_barang){
			$this->session->set_flashdata('error','Jumlah nomor rangka tidak dapat melebihi jumlah unit di kontrak.');
			redirect('Bastb_provinsi_norangka/create?id_bastb_provinsi='.$id_bastb_provinsi);
		}
		
        foreach($nono as $key=>$val){
			$val = str_replace(' ','',$val);
			$val = str_replace(';',',',$val);
			$nonox = explode(',',$val);
			$data['id_bastb_provinsi'] = $id_bastb_provinsi;
			$data['norangka'] = strtoupper($inpnorangka[$key]);
			$data['nomesin'] = strtoupper($inpnomesin[$key]);
			$this->Bastb_provinsi_norangka_model->store($data);
		}        
        $this->session->set_flashdata('info','Data inserted successfully.');
		redirect('Bastb_provinsi_norangka/index?id_bastb_provinsi='.$id_bastb_provinsi);
	}

	public function store()
	{	
		$id_norangka_array = $this->input->post('id');
		$id_bastb_provinsi = $this->input->post('id_bastb_provinsi');
		$id_kontrak_provinsi = $this->input->post('id_kontrak_provinsi');
  
        $data = array(
			'id_bastb_provinsi' => $id_bastb_provinsi,
            'created_by' => $this->session->userdata('logged_in')->id_pengguna,
            'created_at' => NOW,
		); 
		
		//cek row limit
		$jumlah_input = count($id_norangka_array);
		$bastb_provinsi_norangka = $this->Bastb_provinsi_norangka_model->get(null,$this->input->post('id_bastb_provinsi'));
		$bastb_provinsi = $this->Bastb_provinsi_model->get($this->input->post('id_bastb_provinsi'));
        if(count($bastb_provinsi_norangka)+$jumlah_input > $bastb_provinsi->jumlah_barang){
			$this->session->set_flashdata('error','Jumlah nomor rangka tidak dapat melebihi jumlah unit di BASTB.');
			redirect('Bastb_provinsi_norangka/create?id_bastb_provinsi='.$id_bastb_provinsi);
		}

		foreach($id_norangka_array as $key=>$val){
			$data['id_bastb_provinsi'] = $id_bastb_provinsi;
			$id_baphp_provinsi_norangka = $val;
			$baphp_provinsi_norangka = $this->Baphp_provinsi_norangka_model->get($id_baphp_provinsi_norangka);
			$data['norangka'] = $baphp_provinsi_norangka->norangka;
			$data['nomesin'] = $baphp_provinsi_norangka->nomesin;
			$this->Bastb_provinsi_norangka_model->store($data);
		}
        $this->session->set_flashdata('info','Data inserted successfully.');
		redirect('Bastb_provinsi_norangka/index?id_bastb_provinsi='.$id_bastb_provinsi);
	}
	
	public function edit()
	{
		$id = $this->input->get('id');
		$param['cols'] = $this->cols;

		$this->load->library('parser');
		$param['bastb_provinsi_norangka'] = $this->Bastb_provinsi_norangka_model->get($this->input->get('id'));
		$param['bastb_provinsi'] = $this->Bastb_provinsi_model->get($param['bastb_provinsi_norangka']->id_bastb_provinsi);

		$data = array(
	        'title' => 'Ubah Data Nomor Rangka dan Rangka Mesin',
	        'content-path' => 'PENGADAAN PROVINSI / DATA NOMOR RANGKA DAN NOMOR MESIN / UBAH DATA',
	        'content' => $this->load->view('bastb-provinsi-norangka/edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function update()
	{	
		$id = $this->input->post('id');
		$bastb_provinsi_norangka = $this->Bastb_provinsi_norangka_model->get($id);
		
		$data = array(
			'id' => $this->input->post('id'),
			'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
			'updated_at' => NOW,
		);
		
		foreach($this->cols as $key=>$val){
			$data[$val['dbcolumn']] = $this->input->post($val['column']);
		}
		
		$this->Bastb_provinsi_norangka_model->update($id, $data);
		$this->session->set_flashdata('info','Data updated successfully.');
		redirect('Bastb_provinsi_norangka/index?id_bastb_provinsi='.$bastb_provinsi_norangka->id_bastb_provinsi);
	}

	public function destroy()
	{	
        $id = $this->input->get('id');
		$bastb_provinsi_norangka = $this->Bastb_provinsi_norangka_model->get($id);
		$id_bastb_provinsi = $bastb_provinsi_norangka->id_bastb_provinsi;

		if($bastb_provinsi_norangka->nama_file != '' and $bastb_provinsi_norangka->nama_file != 'null' and !is_null($bastb_provinsi_norangka->nama_file) and $bastb_provinsi_norangka->nama_file != '[]')
			$images = json_decode($bastb_provinsi_norangka->nama_file);
		else
			$images = [];

		foreach($images as $image){
			unlink($this->config->item('doc_root').'/upload/bastb_provinsi_norangka'.$image);	
		}

		$this->Bastb_provinsi_norangka_model->destroy($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('Bastb_provinsi_norangka/index?id_bastb_provinsi='.$id_bastb_provinsi);
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
            $data = $this->Bastb_provinsi_norangka_model->ExportAllForAjax($order, $dir, $filtercond);
        }
        else {
            $search = $this->input->post('search')['value']; 
            $data =  $this->Bastb_provinsi_norangka_model->ExportSearchAjax($search, $order, $dir, $filtercond);
        }

        $visible_columns = $this->input->post('visible_columns');
        $visible_header_columns = array();
        $this->xlsxwriter->writeSheetHeader('Bastb_provinsi_norangka', $visible_header_columns, array('font-style'=>'bold'));
        
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
            $this->xlsxwriter->writeSheetRow('Bastb_provinsi_norangka', $newRow);
        }
        
        $uniq_id = substr(md5(uniqid(rand(), true)), 0, 5);
        $file = "upload/BASTB App Bastb_provinsi_norangka - $uniq_id.xlsx";
        $this->xlsxwriter->writeToFile($file);

        header('Content-Type: application/json');
        echo json_encode(array('filename' => base_url().'Bastb_provinsi_norangka/download?filename='.$file));
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
		$id = $this->input->post('id_norangka');

		$bastb_provinsi_norangka = $this->Bastb_provinsi_norangka_model->get($id);

		$kodefile_upload = strtotime(NOW);

		if($bastb_provinsi_norangka->nama_file != '' and $bastb_provinsi_norangka->nama_file != 'null' and !is_null($bastb_provinsi_norangka->nama_file) and $bastb_provinsi_norangka->nama_file != '[]')
			$nama_file = json_decode($bastb_provinsi_norangka->nama_file);
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
		        $targetFile =  $target_file = $this->config->item('doc_root').'/upload/bastb_provinsi_norangka'.$kodefile_upload.basename($_FILES['file']['name'][$key]);
	        	move_uploaded_file($tempFile, $target_file);

		    }

		    $nama_file = json_encode($nama_file);
        	$data = array(
				'nama_file' => $nama_file,
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->Bastb_provinsi_norangka_model->update($id, $data);

			$this->session->set_flashdata('info','File uploaded successfully.');
			exit('success');
        }
    }
    
    public function remove_file()
    {
		$id = $this->input->get('id_norangka');
		$urutanfile = $this->input->get('urutanfile');

		$bastb_provinsi_norangka = $this->Bastb_provinsi_norangka_model->get($id);

		if($bastb_provinsi_norangka->nama_file != '' and $bastb_provinsi_norangka->nama_file != 'null' and !is_null($bastb_provinsi_norangka->nama_file) and $bastb_provinsi_norangka->nama_file != '[]')
			$images = json_decode($bastb_provinsi_norangka->nama_file);
		else
			$images = [];

		$nama_file = $images[$urutanfile];
		unlink($this->config->item('doc_root').'/upload/bastb_provinsi_norangka'.$nama_file);	

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
		$this->Bastb_provinsi_norangka_model->update($id, $data);

		$this->session->set_flashdata('info','File berhasil dihapus.');
		exit('success');
	}
}
	