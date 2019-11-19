<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Norangka extends CI_Controller {
    public $cols = array(
		array("column" => "norangka", "caption" => "Nomor Rangka", "dbcolumn" => "norangka"),
	  	array("column" => "nomesin", "caption" => "Nomor Mesin", "dbcolumn" => "nomesin"),
	  	array("column" => "no_baphp", "caption" => "Nomor BAPHP", "dbcolumn" => "no_baphp")
	);    

    function __construct()
    {
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('Bapb_model');
		$this->load->model('Norangka_model');
        $this->load->helper('url');
		$this->load->library('xlsxwriter');
	}

    public function index($id = null)
    {
        $this->load->library('parser');
		$id_bapb = $this->input->get('id_bapb');
		$param['cols'] = $this->cols;
		$param['norangka'] = $this->Norangka_model->get(null,array('id_bapb'=>$id_bapb));
        $param['bapb'] = $this->Bapb_model->get($id_bapb);
        $param['id_bapb'] = $id_bapb;

        $data = array(
			'title' => 'Data Nomor Rangka & Nomor Mesin',
            'content-path' => strtoupper('Kontrak Pusat / BAPB / Nomor Rangka & Nomor Mesin'),
            'content' => $this->load->view('norangka/index', $param, TRUE),
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
        
		$id_bapb = $this->input->post('id_bapb');
		$id_baphp = $this->input->post('id_baphp');
		$id_kontrak_pusat = $this->input->post('id_kontrak_pusat');
        $start = empty($this->input->post('start'))? 0:$this->input->post('start');
        $length = empty($this->input->post('length'))? null:$this->input->post('length');
        $order = empty($this->input->post('order')[0]['column'])? null:$this->input->post('order')[0]['column'];
        $dir = empty($this->input->post('order')[0]['dir'])? null:$this->input->post('order')[0]['dir'];
        // var_dump($id_kontrak_pusat);exit();
		$columns = array();
		foreach($this->cols as $key=>$val){
			array_push($columns,$val['column']);
		}

		$dbcolumns = array();
		foreach($this->cols as $key=>$val){
			array_push($dbcolumns,$val['dbcolumn']);
		}
     
        $totalData = $this->Norangka_model->get(null,array('id_bapb'=>$id_bapb));
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
		$posts_all_search =  $this->Norangka_model->get(null,array('id_bapb'=>$id_bapb,'id_baphp'=>$id_baphp,'id_kontrak_pusat'=>$id_kontrak_pusat, 'filter'=>$filtercond, 'search'=>$search));
        $totalFiltered = count($posts_all_search);
		$posts =  $this->Norangka_model->get(null,array('id_bapb'=>$id_bapb,'id_baphp'=>$id_baphp, 'id_kontrak_pusat'=>$id_kontrak_pusat, 'start'=>$start, 'length'=>$length, 'order'=>$order, 'dir'=>$dir, 'filter'=>$filtercond, 'search'=>$search));

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
	
	public function index_unselbaphp_json()
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
		
		$totalData = $this->Norangka_model->get_unselbaphp();
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
		$posts_all_search =  $this->Norangka_model->get_unselbaphp(null,array('id_kontrak_pusat'=>$id_kontrak_pusat, 'filter'=>$filtercond, 'search'=>$search));
		$posts =  $this->Norangka_model->get_unselbaphp(null,array('id_kontrak_pusat'=>$id_kontrak_pusat, 'start'=>$start, 'length'=>$length, 'order'=>$order, 'dir'=>$dir, 'filter'=>$filtercond, 'search'=>$search));
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
        
		$id_kontrak_pusat = $this->input->post('id_kontrak_pusat');
		$id_alokasi_pusat = $this->input->post('id_alokasi_pusat');
		$id_alokasi_persediaan_pusat = $this->input->post('id_alokasi_persediaan_pusat');
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
		
		$totalData = $this->Norangka_model->get_unselbastb();
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
		$posts_all_search =  $this->Norangka_model->get_unselbastb(null,array('id_kontrak_pusat'=>$id_kontrak_pusat, 'id_alokasi_pusat'=>$id_alokasi_pusat, 'id_alokasi_persediaan_pusat'=>$id_alokasi_persediaan_pusat, 'filter'=>$filtercond, 'search'=>$search));
		$posts =  $this->Norangka_model->get_unselbastb(null,array('id_kontrak_pusat'=>$id_kontrak_pusat, 'id_alokasi_pusat'=>$id_alokasi_pusat, 'id_alokasi_persediaan_pusat'=>$id_alokasi_persediaan_pusat, 'start'=>$start, 'length'=>$length, 'order'=>$order, 'dir'=>$dir, 'filter'=>$filtercond, 'search'=>$search));
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

    public function show($id)
    {
		$norangka = $this->Norangka_model->get($id);

		$this->load->library('parser');
		$data = array(
			'title' => 'Data Nomor Rangka & Nomor Mesin',
            'content-path' => strtoupper('Kontrak Pusat / BAPB / Nomor Rangka & Nomor Mesin'),
	        'content' => $this->load->view('norangka/index', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

    public function get_json()
    {
        $id = $this->input->get('id');
		$data = $this->Norangka_model->get($id);
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
		$param['id_bapb'] = $this->input->get('id_bapb');
		$bapb = $this->Bapb_model->get($this->input->get('id_bapb'));
		$param['id_kontrak_pusat'] = $bapb->id_kontrak_pusat;

		$data = array(
			'title' => 'Input Nomor Rangka & Nomor Mesin',
            'content-path' => strtoupper('Kontrak Pusat / BAPB / Nomor Rangka & Nomor Mesin / Input Data'),
	        'content' => $this->load->view('norangka/add', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function store()
	{	
		$id_bapb = $this->input->post('id_bapb');
		$id_kontrak_pusat = $this->input->post('id_kontrak_pusat');
  
        $data = array(
			'id_bapb' => $id_bapb,
			'id_kontrak_pusat' => $id_kontrak_pusat,
            'created_by' => $this->session->userdata('logged_in')->id_pengguna,
            'created_at' => NOW,
		);
		
		$norangkanomesin = $this->input->post('norangkanomesin');
		$norangkanomesin = str_replace(' ','',$norangkanomesin);// remove all space
		$nono = preg_split('/\r\n|[\r\n]/', $norangkanomesin);
		//check duplicate from inputted norangka nomesin
		foreach($nono as $key=>$val){
			$val = trim($val);
			$val = str_replace(' ','',$val);// remove all space
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
					redirect('Norangka/create?id_bapb='.$id_bapb);
				}
			}
			for($i=0;$i<count($inpnomesinsort)-1;$i++){
				if($inpnomesinsort[$i] == $inpnomesinsort[$i+1]){
					$this->session->set_flashdata('error','Nomor mesin duplikat: '.$val);
					redirect('Norangka/create?id_bapb='.$id_bapb);
				}
			}
		}

		//check duplicate againts existing norangka nomesin
		$extnorangkaq = $this->Norangka_model->get_norangka();
		foreach($extnorangkaq as $key=>$val){
			$extnorangka[] = $val->norangka;
		}
		$extnomesinq = $this->Norangka_model->get_nomesin();
		foreach($extnomesinq as $key=>$val){
			$extnomesin[] = $val->nomesin;
		}
		$dupnorangka = array_intersect($inpnorangka, $extnorangka);
		$dupnomesin = array_intersect($inpnomesin, $extnomesin);
		// var_dump($inpnomesin); var_dump($extnomesin); var_dump($dupnomesin);exit();
		if(count($dupnorangka) > 0){
			$this->session->set_flashdata('error','Nomor rangka sudah ada di database: '.join(', ', $dupnorangka));
			redirect('Norangka/create?id_bapb='.$id_bapb);
		}
		if(count($dupnomesin) > 0){
			$this->session->set_flashdata('error','Nomor mesin sudah ada di database: '.join(', ', $dupnomesin));
			redirect('Norangka/create?id_bapb='.$id_bapb);
		}
        
		//cek row limit
		$jumlah_input = count($nono);
		$norangka = $this->Norangka_model->get(null,array('id_bapb'=>$this->input->post('id_bapb')));
		$bapb = $this->Bapb_model->get($this->input->post('id_bapb'));
        if(count($norangka)+$jumlah_input > $bapb->jumlah_unit){
			$this->session->set_flashdata('error','Jumlah nomor rangka tidak dapat melebihi jumlah unit di kontrak.');
			redirect('Norangka/create?id_bapb='.$id_bapb);
		}
		
        foreach($nono as $key=>$val){
			$data['id_bapb'] = $id_bapb;
			$data['id_kontrak_pusat'] = $id_kontrak_pusat;
			$data['norangka'] = strtoupper($inpnorangka[$key]);
			$data['nomesin'] = strtoupper($inpnomesin[$key]);
			if(!empty($data['norangka'])){
				$this->Norangka_model->store($data);
			}
		}
        $this->session->set_flashdata('info','Data inserted successfully.');
		redirect('Norangka/index?id_bapb='.$id_bapb);
	}

	public function edit()
	{
		$id = $this->input->get('id');
		$param['cols'] = $this->cols;

		$this->load->library('parser');
        
		$param['norangka'] = $this->Norangka_model->get($id);

        $data = array(
			'title' => 'Ubah Nomor Rangka & Nomor Mesin',
            'content-path' => strtoupper('Kontrak Pusat / BAPB / Nomor Rangka & Nomor Mesin/ Ubah Data'),
	        'content' => $this->load->view('norangka/edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function update()
	{	
		$id = $this->input->post('id');
		$id_bapb = $this->input->post('id_bapb');
    	$norangka = $this->Norangka_model->get($id);

		$data = array(
			'id_bapb' => $id_bapb,
            'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
            'updated_at' => NOW,
        );

        foreach($this->cols as $key=>$val){
			if($val['column']=='no_baphp') continue;
			if(!in_array($val['dbcolumn'], array('tanggal'))){
				$data[$val['dbcolumn']] = $this->input->post($val['column']);
			}
		}
 
		//check duplicate againts existing norangka nomesin
		$extnorangkaq = $this->Norangka_model->get_norangka();
		foreach($extnorangkaq as $key=>$val){
			$extnorangka[] = $val->norangka;
		}
		//remove current norangka
		if (($key = array_search($norangka->norangka, $extnorangka)) !== false) {
			unset($extnorangka[$key]);
		}
		$extnomesinq = $this->Norangka_model->get_nomesin();
		foreach($extnomesinq as $key=>$val){
			$extnomesin[] = $val->nomesin;
		}
		//remove current nomesin
		if (($key = array_search($norangka->nomesin, $extnomesin)) !== false) {
			unset($extnomesin[$key]);
		}
		if(in_array($data['norangka'], $extnorangka)){
			$this->session->set_flashdata('error','Nomor rangka sudah ada di database');
			redirect('Norangka/edit?id='.$id);
		}
		if(in_array($data['nomesin'], $extnomesin)){
			$this->session->set_flashdata('error','Nomor mesin sudah ada di database');
			redirect('Norangka/edit?id='.$id);
		}
        
        $this->Norangka_model->update($id, $data);
        $this->session->set_flashdata('info','Data updated successfully.');
        redirect('Norangka/index?id_bapb='.$id_bapb);		
	}

	public function destroy()
	{	
        $id = $this->input->get('id');
		$norangka = $this->Norangka_model->get($id);
		$id_bapb = $norangka->id_bapb;

		if($norangka->nama_file != '' and $norangka->nama_file != 'null' and !is_null($norangka->nama_file) and $norangka->nama_file != '[]')
			$images = json_decode($norangka->nama_file);
		else
			$images = [];

		foreach($images as $image){
			unlink($this->config->item('doc_root').'/upload/norangka'.$image);	
		}

		$this->Norangka_model->destroy($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('Norangka/index?id_bapb='.$id_bapb);
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
            $data = $this->Norangka_model->ExportAllForAjax($order, $dir, $filtercond);
        }
        else {
            $search = $this->input->post('search')['value']; 
            $data =  $this->Norangka_model->ExportSearchAjax($search, $order, $dir, $filtercond);
        }

        $visible_columns = $this->input->post('visible_columns');
        $visible_header_columns = array();
        $this->xlsxwriter->writeSheetHeader('Norangka', $visible_header_columns, array('font-style'=>'bold'));
        
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
            $this->xlsxwriter->writeSheetRow('Norangka', $newRow);
        }
        
        $uniq_id = substr(md5(uniqid(rand(), true)), 0, 5);
        $file = "upload/BASTB App Norangka - $uniq_id.xlsx";
        $this->xlsxwriter->writeToFile($file);

        header('Content-Type: application/json');
        echo json_encode(array('filename' => base_url().'Norangka/download?filename='.$file));
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

		$norangka = $this->Norangka_model->get($id);

		$kodefile_upload = strtotime(NOW);

		if($norangka->nama_file != '' and $norangka->nama_file != 'null' and !is_null($norangka->nama_file) and $norangka->nama_file != '[]')
			$nama_file = json_decode($norangka->nama_file);
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
		        $targetFile =  $target_file = $this->config->item('doc_root').'/upload/norangka'.$kodefile_upload.basename($_FILES['file']['name'][$key]);
	        	move_uploaded_file($tempFile, $target_file);

		    }

		    $nama_file = json_encode($nama_file);
        	$data = array(
				'nama_file' => $nama_file,
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->Norangka_model->update($id, $data);

			$this->session->set_flashdata('info','File uploaded successfully.');
			exit('success');
        }
    }
    
    public function remove_file()
    {
		$id = $this->input->get('id_norangka');
		$urutanfile = $this->input->get('urutanfile');

		$norangka = $this->Norangka_model->get($id);

		if($norangka->nama_file != '' and $norangka->nama_file != 'null' and !is_null($norangka->nama_file) and $norangka->nama_file != '[]')
			$images = json_decode($norangka->nama_file);
		else
			$images = [];

		$nama_file = $images[$urutanfile];
		unlink($this->config->item('doc_root').'/upload/norangka'.$nama_file);	

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
		$this->Norangka_model->update($id, $data);

		$this->session->set_flashdata('info','File berhasil dihapus.');
		exit('success');
	}
}
	