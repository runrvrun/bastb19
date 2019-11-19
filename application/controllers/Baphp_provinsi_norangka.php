<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Baphp_provinsi_norangka extends CI_Controller {
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
		$this->load->model('Baphp_provinsi_model');
		$this->load->model('Baphp_provinsi_norangka_model');
		$this->load->model('Baphp_provinsi_norangka_model');
		$this->load->model('Baphp_provinsi_norangka_model');
        $this->load->helper('url');
		$this->load->library('xlsxwriter');
	}

    public function index($id = null)
    {
        $this->load->library('parser');
		$id_baphp_provinsi = $this->input->get('id_baphp_provinsi');
		$param['cols'] = $this->cols;
		$param['baphp_provinsi_norangka'] = $this->Baphp_provinsi_norangka_model->get(null,array('id_baphp_provinsi'=>$id_baphp_provinsi));
        $param['baphp_provinsi'] = $this->Baphp_provinsi_model->get($id_baphp_provinsi);
        $param['id_baphp_provinsi'] = $id_baphp_provinsi;

        $data = array(
			'title' => 'Data Nomor Rangka & Nomor Mesin',
            'content-path' => strtoupper('Kontrak Provinsi / BAPHP / Nomor Rangka & Nomor Mesin'),
            'content' => $this->load->view('baphp-provinsi-norangka/index', $param, TRUE),
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
        
		$id_baphp_provinsi = $this->input->post('id_baphp_provinsi');
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
     
        $totalData = $this->Baphp_provinsi_norangka_model->get(null,array('id_baphp_provinsi'=>$id_baphp_provinsi));
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
		$posts_all_search =  $this->Baphp_provinsi_norangka_model->get(null, array('id_baphp_provinsi'=>$id_baphp_provinsi,'filter'=>$filtercond,'search'=>$search));
        $totalFiltered = count($posts_all_search);
		$posts =  $this->Baphp_provinsi_norangka_model->get(null, array('id_baphp_provinsi'=>$id_baphp_provinsi,'filter'=>$filtercond,'search'=>$search,'start'=>$start,'length'=>$length,'order'=>$order,'dir'=>$dir));

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();
				foreach($this->cols as $val){
					$nestedData[$val['column']] = $post->{$val['column']};
				}
				if($bolehedit)
					$tools = "<a class='btn btn-xs btn-primary btn-sm' href='".base_url('Baphp_provinsi_norangka/edit?id=').$post->id."'><i class='glyphicon glyphicon-pencil'></i></a>";
                if($bolehhapus)
                    $tools .= "<a class='btn btn-xs btn-danger btn-sm' data-href='".base_url('Baphp_provinsi_norangka/destroy?id=').$post->id."' data-toggle='modal' data-record-title='".$post->id."' data-target='#confirm-delete'><i class='glyphicon glyphicon-trash'></i></a>";
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
		$baphp_provinsi_norangka = $this->Baphp_provinsi_norangka_model->Get($id)[0];

		$this->load->library('parser');
		$data = array(
			'title' => 'Data Nomor Rangka & Nomor Mesin',
            'content-path' => strtoupper('Kontrak Provinsi / BAPHP / Nomor Rangka & Nomor Mesin'),
	        'content' => $this->load->view('baphp-provinsi-norangka/index', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

    public function get_json()
    {
        $id = $this->input->get('id');
		$data = $this->Baphp_provinsi_norangka_model->get($id);
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
		$param['id_baphp_provinsi'] = $this->input->get('id_baphp_provinsi');
		$param['baphp_provinsi'] = $this->Baphp_provinsi_model->get($this->input->get('id_baphp_provinsi'));
		$param['id_kontrak_provinsi'] = $param['baphp_provinsi']->id_kontrak_provinsi;
		$param['norangka'] = $this->Baphp_provinsi_norangka_model->get(null,array('id_kontrak_provinsi'=>$param['baphp_provinsi']->id_kontrak_provinsi));

		$data = array(
			'title' => 'Input Nomor Rangka & Nomor Mesin',
            'content-path' => strtoupper('Kontrak Provinsi / BAPHP / Nomor Rangka & Nomor Mesin'),
	        'content' => $this->load->view('baphp-provinsi-norangka/add', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function store()
	{	
		$id_baphp_provinsi = $this->input->post('id_baphp_provinsi');
		$id_kontrak_provinsi = $this->input->post('id_kontrak_provinsi');
  
        $data = array(
			'id_baphp_provinsi' => $id_baphp_provinsi,
			'id_kontrak_provinsi' => $id_kontrak_provinsi,
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
		$inpnorangkasort = $inpnorangka;
		$inpnomesinsort = $inpnomesin;
		sort($inpnorangkasort);
		sort($inpnomesinsort);
		if(count($inpnorangkasort) > 1){
			for($i=0;$i<count($inpnorangkasort)-1;$i++){
				if($inpnorangkasort[$i] == $inpnorangkasort[$i+1]){
					$this->session->set_flashdata('error','Nomor rangka duplikat: '.$val);
					redirect('Baphp_provinsi_norangka/create?id_baphp_provinsi='.$id_baphp_provinsi);
				}
			}
			for($i=0;$i<count($inpnomesinsort)-1;$i++){
				if($inpnomesinsort[$i] == $inpnomesinsort[$i+1]){
					$this->session->set_flashdata('error','Nomor mesin duplikat: '.$val);
					redirect('Baphp_provinsi_norangka/create?id_baphp_provinsi='.$id_baphp_provinsi);
				}
			}
		}

		//check duplicate againts existing norangka nomesin
		$extnorangkaq = $this->Baphp_provinsi_norangka_model->get_norangka();
		if(count($extnorangkaq) > 0){
			foreach($extnorangkaq as $key=>$val){
				$extnorangka[] = $val->norangka;
			}
			$extnomesinq = $this->Baphp_provinsi_norangka_model->get_nomesin();
			foreach($extnomesinq as $key=>$val){
				$extnomesin[] = $val->nomesin;
			}
			$dupnorangka = array_intersect($inpnorangka, $extnorangka);
			$dupnomesin = array_intersect($inpnomesin, $extnomesin);
			// var_dump($inpnomesin); var_dump($extnomesin); var_dump($dupnomesin);exit();
			if(count($dupnorangka) > 0){
				$this->session->set_flashdata('error','Nomor rangka sudah ada di database: '.join(', ', $dupnorangka));
				redirect('Baphp_provinsi_norangka/create?id_baphp_provinsi='.$id_baphp_provinsi);
			}
			if(count($dupnomesin) > 0){
				$this->session->set_flashdata('error','Nomor mesin sudah ada di database: '.join(', ', $dupnomesin));
				redirect('Baphp_provinsi_norangka/create?id_baphp_provinsi='.$id_baphp_provinsi);
			}
		}
        
		//cek row limit
		$jumlah_input = count($nono);
		$norangka = $this->Baphp_provinsi_norangka_model->get(null,array('id_baphp_provinsi'=>$this->input->post('id_baphp_provinsi')));
		$baphp_provinsi = $this->Baphp_provinsi_model->get($this->input->post('id_baphp_provinsi'));
        if(count($norangka)+$jumlah_input > $baphp_provinsi->jumlah_barang){
			$this->session->set_flashdata('error','Jumlah nomor rangka tidak dapat melebihi jumlah unit di kontrak.');
			redirect('Baphp_provinsi_norangka/create?id_baphp_provinsi='.$id_baphp_provinsi);
		}
		
        foreach($nono as $key=>$val){
			$val = str_replace(' ','',$val);
			$val = str_replace(';',',',$val);
			$nonox = explode(',',$val);
			$data['id_baphp_provinsi'] = $id_baphp_provinsi;
			$data['id_kontrak_provinsi'] = $id_kontrak_provinsi;
			$data['norangka'] = strtoupper($inpnorangka[$key]);
			$data['nomesin'] = strtoupper($inpnomesin[$key]);
			if(!empty($data['norangka'])){
				$this->Baphp_provinsi_norangka_model->store($data);
			}
		}        
        $this->session->set_flashdata('info','Data inserted successfully.');
		redirect('Baphp_provinsi_norangka/index?id_baphp_provinsi='.$id_baphp_provinsi);
	}

	
	public function edit()
	{
		$id = $this->input->get('id');
		$param['cols'] = $this->cols;

		$this->load->library('parser');
		$param['baphp_provinsi_norangka'] = $this->Baphp_provinsi_norangka_model->get($id);
		$param['baphp_provinsi'] = $this->Baphp_provinsi_model->get($param['baphp_provinsi_norangka']->id_baphp_provinsi);

		$data = array(
	        'title' => 'Ubah Data Nomor Rangka dan Rangka Mesin',
	        'content-path' => 'PENGADAAN PROVINSI / DATA NOMOR RANGKA DAN NOMOR MESIN / UBAH DATA',
	        'content' => $this->load->view('baphp-provinsi-norangka/edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function update()
	{	
		$id = $this->input->post('id');
		$baphp_provinsi_norangka = $this->Baphp_provinsi_norangka_model->get($id);
		
		$data = array(
			'id' => $this->input->post('id'),
			'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
			'updated_at' => NOW,
		);
		
		foreach($this->cols as $key=>$val){
			$data[$val['dbcolumn']] = $this->input->post($val['column']);
		}
		
		$this->Baphp_provinsi_norangka_model->update($id, $data);
		$this->session->set_flashdata('info','Data updated successfully.');
		redirect('Baphp_provinsi_norangka/index?id_baphp_provinsi='.$baphp_provinsi_norangka->id_baphp_provinsi);
	}

	public function destroy()
	{	
        $id = $this->input->get('id');
		$baphp_provinsi_norangka = $this->Baphp_provinsi_norangka_model->get($id);
		$id_baphp_provinsi = $baphp_provinsi_norangka->id_baphp_provinsi;

		if($baphp_provinsi_norangka->nama_file != '' and $baphp_provinsi_norangka->nama_file != 'null' and !is_null($baphp_provinsi_norangka->nama_file) and $baphp_provinsi_norangka->nama_file != '[]')
			$images = json_decode($baphp_provinsi_norangka->nama_file);
		else
			$images = [];

		foreach($images as $image){
			unlink($this->config->item('doc_root').'/upload/baphp_provinsi_norangka'.$image);	
		}

		$this->Baphp_provinsi_norangka_model->destroy($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('Baphp_provinsi_norangka/index?id_baphp_provinsi='.$id_baphp_provinsi);
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
            $data = $this->Baphp_provinsi_norangka_model->ExportAllForAjax($order, $dir, $filtercond);
        }
        else {
            $search = $this->input->post('search')['value']; 
            $data =  $this->Baphp_provinsi_norangka_model->ExportSearchAjax($search, $order, $dir, $filtercond);
        }

        $visible_columns = $this->input->post('visible_columns');
        $visible_header_columns = array();
        $this->xlsxwriter->writeSheetHeader('Baphp_provinsi_norangka', $visible_header_columns, array('font-style'=>'bold'));
        
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
            $this->xlsxwriter->writeSheetRow('Baphp_provinsi_norangka', $newRow);
        }
        
        $uniq_id = substr(md5(uniqid(rand(), true)), 0, 5);
        $file = "upload/BASTB App Baphp_provinsi_norangka - $uniq_id.xlsx";
        $this->xlsxwriter->writeToFile($file);

        header('Content-Type: application/json');
        echo json_encode(array('filename' => base_url().'Baphp_provinsi_norangka/download?filename='.$file));
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

		$baphp_provinsi_norangka = $this->Baphp_provinsi_norangka_model->get($id);

		$kodefile_upload = strtotime(NOW);

		if($baphp_provinsi_norangka->nama_file != '' and $baphp_provinsi_norangka->nama_file != 'null' and !is_null($baphp_provinsi_norangka->nama_file) and $baphp_provinsi_norangka->nama_file != '[]')
			$nama_file = json_decode($baphp_provinsi_norangka->nama_file);
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
		        $targetFile =  $target_file = $this->config->item('doc_root').'/upload/baphp_provinsi_norangka'.$kodefile_upload.basename($_FILES['file']['name'][$key]);
	        	move_uploaded_file($tempFile, $target_file);

		    }

		    $nama_file = json_encode($nama_file);
        	$data = array(
				'nama_file' => $nama_file,
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->Baphp_provinsi_norangka_model->update($id, $data);

			$this->session->set_flashdata('info','File uploaded successfully.');
			exit('success');
        }
    }
    
    public function remove_file()
    {
		$id = $this->input->get('id_norangka');
		$urutanfile = $this->input->get('urutanfile');

		$baphp_provinsi_norangka = $this->Baphp_provinsi_norangka_model->get($id);

		if($baphp_provinsi_norangka->nama_file != '' and $baphp_provinsi_norangka->nama_file != 'null' and !is_null($baphp_provinsi_norangka->nama_file) and $baphp_provinsi_norangka->nama_file != '[]')
			$images = json_decode($baphp_provinsi_norangka->nama_file);
		else
			$images = [];

		$nama_file = $images[$urutanfile];
		unlink($this->config->item('doc_root').'/upload/baphp_provinsi_norangka'.$nama_file);	

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
		$this->Baphp_provinsi_norangka_model->update($id, $data);

		$this->session->set_flashdata('info','File berhasil dihapus.');
		exit('success');
	}

	public function index_unselbastb_json()
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
		
		$id_kontrak_provinsi = null;
		$id_alokasi_provinsi = $this->input->post('id_alokasi_provinsi');
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
		
		$totalData = $this->Baphp_provinsi_norangka_model->get_unselbastb();
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
		$posts_all_search =  $this->Baphp_provinsi_norangka_model->get_unselbastb(null,array('id_kontrak_provinsi'=>$id_kontrak_provinsi, 'id_alokasi_provinsi'=>$id_alokasi_provinsi, 'filter'=>$filtercond, 'search'=>$search));
		$posts =  $this->Baphp_provinsi_norangka_model->get_unselbastb(null,array('id_kontrak_provinsi'=>$id_kontrak_provinsi, 'id_alokasi_provinsi'=>$id_alokasi_provinsi, 'start'=>$start, 'length'=>$length, 'order'=>$order, 'dir'=>$dir, 'filter'=>$filtercond, 'search'=>$search));
		$totalFiltered = count($posts_all_search);
		
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();
				$nestedData['id'] = $post->id;
				foreach($this->cols as $val){
					$nestedData[$val['column']] = $post->{$val['column']};
				}

                if($bolehedit)
                    $tools = "<a class='btn btn-xs btn-primary btn-sm' href='".base_url('Norangka/edit?id=').$post->id."'><i class='glyphicon glyphicon-pencil'></i></a>";
                if($bolehhapus)
                    $tools .= "<a class='btn btn-xs btn-danger btn-sm' data-href='".base_url('Norangka/destroy?id=').$post->id."' data-toggle='modal' data-record-title='".$post->id."' data-target='#confirm-delete'><i class='glyphicon glyphicon-trash'></i></a>";
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
}
	