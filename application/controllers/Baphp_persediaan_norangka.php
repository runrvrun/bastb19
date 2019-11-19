<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Baphp_persediaan_norangka extends CI_Controller {
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
		$this->load->model('Baphp_persediaan_model');
		$this->load->model('Norangka_model');
		$this->load->model('Baphp_persediaan_norangka_model');
		$this->load->model('Baphp_persediaan_norangka_model');
        $this->load->helper('url');
		$this->load->library('xlsxwriter');
	}

    public function index($id = null)
    {
        $this->load->library('parser');
		$id_baphp_persediaan = $this->input->get('id_baphp_persediaan');
		$param['cols'] = $this->cols;
		$param['baphp_persediaan_norangka'] = $this->Baphp_persediaan_norangka_model->get(null,$id_baphp_persediaan);
        $param['baphp_persediaan'] = $this->Baphp_persediaan_model->get($id_baphp_persediaan);
        $param['id_baphp_persediaan'] = $id_baphp_persediaan;

        $data = array(
			'title' => 'Data Nomor Rangka & Nomor Mesin',
            'content-path' => strtoupper('Kontrak Pusat / BAPHP / Nomor Rangka & Nomor Mesin'),
            'content' => $this->load->view('baphp-persediaan-norangka/index', $param, TRUE),
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
        
		$id_baphp_persediaan = $this->input->post('id_baphp_persediaan');
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
     
        $totalData = $this->Baphp_persediaan_norangka_model->get(null,$id_baphp_persediaan);
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
		$posts_all_search =  $this->Baphp_persediaan_norangka_model->get(null, $id_baphp_persediaan,null, null, null, null, $filtercond, $search);
        $totalFiltered = count($posts_all_search);
		$posts =  $this->Baphp_persediaan_norangka_model->get(null, $id_baphp_persediaan, $start, $length, $order, $dir, $filtercond, $search);

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();
				foreach($this->cols as $val){
					$nestedData[$val['column']] = $post->{$val['column']};
				}

                if($bolehhapus)
                    $tools = "<a class='btn btn-xs btn-danger btn-sm' data-href='".base_url('Baphp_persediaan_norangka/destroy?id=').$post->id."' data-toggle='modal' data-record-title='".$post->id."' data-target='#confirm-delete'><i class='glyphicon glyphicon-trash'></i></a>";
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
		$baphp_persediaan_norangka = $this->Baphp_persediaan_norangka_model->Get($id)[0];

		$this->load->library('parser');
		$data = array(
			'title' => 'Data Nomor Rangka & Nomor Mesin',
            'content-path' => strtoupper('Kontrak Pusat / BAPHP / Nomor Rangka & Nomor Mesin'),
	        'content' => $this->load->view('baphp-persediaan-norangka/index', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

    public function get_json()
    {
        $id = $this->input->get('id');
		$data = $this->Baphp_persediaan_norangka_model->get($id);
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
		$param['id_baphp_persediaan'] = $this->input->get('id_baphp_persediaan');
		$param['baphp_persediaan'] = $this->Baphp_persediaan_model->get($this->input->get('id_baphp_persediaan'));
		$param['id_kontrak_pusat'] = $param['baphp_persediaan']->id_kontrak_pusat;
		$param['norangka'] = $this->Norangka_model->get(null,array('id_kontrak_pusat'=>$param['baphp_persediaan']->id_kontrak_pusat));

		$data = array(
			'title' => 'Pilih Nomor Rangka & Nomor Mesin',
            'content-path' => strtoupper('Kontrak Pusat / BAPHP / Nomor Rangka & Nomor Mesin / Pilih Data'),
	        'content' => $this->load->view('baphp-persediaan-norangka/add', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function store()
	{	
		$id_norangka_array = $this->input->post('id');
		$id_baphp_persediaan = $this->input->post('id_baphp_persediaan');
		$id_kontrak_pusat = $this->input->post('id_kontrak_pusat');
  
        $data = array(
			'id_baphp_persediaan' => $id_baphp_persediaan,
            'created_by' => $this->session->userdata('logged_in')->id_pengguna,
            'created_at' => NOW,
		);
		
		//cek row limit
		$jumlah_input = count($id_norangka_array);
		$baphp_persediaan_norangka = $this->Baphp_persediaan_norangka_model->get(null,$this->input->post('id_baphp_persediaan'));
		$baphp = $this->Baphp_persediaan_model->get($this->input->post('id_baphp_persediaan'));
		// var_dump($baphp_persediaan_norangka);exit();
        if(count($baphp_persediaan_norangka)+$jumlah_input > $baphp->jumlah_barang){
			$this->session->set_flashdata('error','Jumlah nomor rangka tidak dapat melebihi jumlah unit di kontrak.');
			redirect('Baphp_persediaan_norangka/create?id_baphp_persediaan='.$id_baphp_persediaan);
		}

		foreach($id_norangka_array as $key=>$val){
			$data['id_baphp_persediaan'] = $id_baphp_persediaan;
			$data['id_norangka'] = $val;
			$this->Baphp_persediaan_norangka_model->store($data);
		}
        $this->session->set_flashdata('info','Data inserted successfully.');
		redirect('Baphp_persediaan_norangka/index?id_baphp_persediaan='.$id_baphp_persediaan);
	}

	public function destroy()
	{	
        $id = $this->input->get('id');
		$baphp_persediaan_norangka = $this->Baphp_persediaan_norangka_model->get($id);
		$id_baphp_persediaan = $baphp_persediaan_norangka->id_baphp_persediaan;

		if($baphp_persediaan_norangka->nama_file != '' and $baphp_persediaan_norangka->nama_file != 'null' and !is_null($baphp_persediaan_norangka->nama_file) and $baphp_persediaan_norangka->nama_file != '[]')
			$images = json_decode($baphp_persediaan_norangka->nama_file);
		else
			$images = [];

		foreach($images as $image){
			unlink($this->config->item('doc_root').'/upload/baphp_persediaan_norangka'.$image);	
		}

		$this->Baphp_persediaan_norangka_model->destroy($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('Baphp_persediaan_norangka/index?id_baphp_persediaan='.$id_baphp_persediaan);
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
            $data = $this->Baphp_persediaan_norangka_model->ExportAllForAjax($order, $dir, $filtercond);
        }
        else {
            $search = $this->input->post('search')['value']; 
            $data =  $this->Baphp_persediaan_norangka_model->ExportSearchAjax($search, $order, $dir, $filtercond);
        }

        $visible_columns = $this->input->post('visible_columns');
        $visible_header_columns = array();
        $this->xlsxwriter->writeSheetHeader('Baphp_persediaan_norangka', $visible_header_columns, array('font-style'=>'bold'));
        
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
            $this->xlsxwriter->writeSheetRow('Baphp_persediaan_norangka', $newRow);
        }
        
        $uniq_id = substr(md5(uniqid(rand(), true)), 0, 5);
        $file = "upload/BASTB App Baphp_persediaan_norangka - $uniq_id.xlsx";
        $this->xlsxwriter->writeToFile($file);

        header('Content-Type: application/json');
        echo json_encode(array('filename' => base_url().'Baphp_persediaan_norangka/download?filename='.$file));
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

		$baphp_persediaan_norangka = $this->Baphp_persediaan_norangka_model->get($id);

		$kodefile_upload = strtotime(NOW);

		if($baphp_persediaan_norangka->nama_file != '' and $baphp_persediaan_norangka->nama_file != 'null' and !is_null($baphp_persediaan_norangka->nama_file) and $baphp_persediaan_norangka->nama_file != '[]')
			$nama_file = json_decode($baphp_persediaan_norangka->nama_file);
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
		        $targetFile =  $target_file = $this->config->item('doc_root').'/upload/baphp_persediaan_norangka'.$kodefile_upload.basename($_FILES['file']['name'][$key]);
	        	move_uploaded_file($tempFile, $target_file);

		    }

		    $nama_file = json_encode($nama_file);
        	$data = array(
				'nama_file' => $nama_file,
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->Baphp_persediaan_norangka_model->update($id, $data);

			$this->session->set_flashdata('info','File uploaded successfully.');
			exit('success');
        }
    }
    
    public function remove_file()
    {
		$id = $this->input->get('id_norangka');
		$urutanfile = $this->input->get('urutanfile');

		$baphp_persediaan_norangka = $this->Baphp_persediaan_norangka_model->get($id);

		if($baphp_persediaan_norangka->nama_file != '' and $baphp_persediaan_norangka->nama_file != 'null' and !is_null($baphp_persediaan_norangka->nama_file) and $baphp_persediaan_norangka->nama_file != '[]')
			$images = json_decode($baphp_persediaan_norangka->nama_file);
		else
			$images = [];

		$nama_file = $images[$urutanfile];
		unlink($this->config->item('doc_root').'/upload/baphp_persediaan_norangka'.$nama_file);	

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
		$this->Baphp_persediaan_norangka_model->update($id, $data);

		$this->session->set_flashdata('info','File berhasil dihapus.');
		exit('success');
	}
}
	