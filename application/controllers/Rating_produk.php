<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rating_produk extends CI_Controller {
    public $cols = array(
		array("column" => "merk", "caption" => "Merk", "dbcolumn" => "merk"),
		array("column" => "nama_barang", "caption" => "Nama Barang", "dbcolumn" => "nama_barang"),
	  	array("column" => "overall", "caption" => "Rating", "dbcolumn" => "overall")
	);
	
	public $colsi = array(
		array("column" => "nama_barang", "caption" => "Nama Barang", "dbcolumn" => "nama_barang"),
	  	array("column" => "mutu", "caption" => "Mutu", "dbcolumn" => "mutu"),
	  	array("column" => "daya_tahan", "caption" => "Daya Tahan", "dbcolumn" => "daya_tahan"),
	  	array("column" => "kesesuaian", "caption" => "Kesesuaian", "dbcolumn" => "kesesuaian"),
	  	array("column" => "ketersediaan_suku_cadang", "caption" => "Ketersediaan Suku Cadang", "dbcolumn" => "ketersediaan_suku_cadang"),
	  	array("column" => "perawatan", "caption" => "Perawatan", "dbcolumn" => "perawatan"),
	  	array("column" => "cara_pengoperasian", "caption" => "Cara Pengoperasian", "dbcolumn" => "cara_pengoperasian"),
	  	array("column" => "overall", "caption" => "Rating Keseluruhan", "dbcolumn" => "overall")
	);
	
    function __construct()
    {
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('Rating_produk_model');
        $this->load->helper('url');
		$this->load->library('xlsxwriter');
	}

    public function index($id = null)
    {
        $this->load->library('parser');
		$param['cols'] = $this->cols;
		$param['colsi'] = $this->colsi;
        $param['rating_produk'] = $this->Rating_produk_model->get();

        $data = array(
            'title' => 'RATING PRODUK',
            'content-path' => 'RATING PRODUK',
            'content' => $this->load->view('rating-produk/index', $param, TRUE),
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
        
        $id = $this->input->post('id');
        $id_jenis_barang_pusat = $this->input->post('id_jenis_barang_pusat');
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
     
        $totalData = $this->Rating_produk_model->get();
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
		$posts_all_search =  $this->Rating_produk_model->get(null,null,null, null, null, null, $filtercond, $search);
        $totalFiltered = count($posts_all_search);
		$posts =  $this->Rating_produk_model->get($id, $id_jenis_barang_pusat, $start, $length, $order, $dir, $filtercond, $search);

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData = array();
				foreach($this->cols as $val){
					$nestedData[$val['column']] = $post->{$val['column']};
				}
				if(!empty($nestedData['overall']))
					$nestedData['overall'] = '<div class="rateit rateit-font" data-rateit-value="4" data-rateit-mode="font" style="font-size:32px" data-rateit-ispreset="true" data-rateit-readonly="true"><button id="rateit-reset-2" type="button" data-role="none" class="rateit-reset" aria-label="reset rating" aria-controls="rateit-range-2" style="display: none;"><span></span></button><div id="rateit-range-2" class="rateit-range" role="slider" aria-label="rating" aria-owns="rateit-reset-2" aria-valuemin="0" aria-valuemax="5" aria-valuenow="4" aria-readonly="true"><div class="rateit-empty">★★★★★</div><div class="rateit-selected rateit-preset" style="width: '.($nestedData['overall']/5*133).'px;">★★★★★</div><div class="rateit-hover">★★★★★</div></div></div>';

                $tools =  "<a class='btn btn-xs btn-success btn-sm'><i class='glyphicon glyphicon-zoom-in' onclick='LoadData(".$post->id.")'></i></a>";
                if($bolehedit)
                    $tools .= "<a class='btn btn-xs btn-primary btn-sm' href='".base_url('Rating_produk/edit')."?id=".$post->id."&id_jenis_barang_pusat=".$post->id_jenis_barang_pusat."'><i class='glyphicon glyphicon-star'></i><i class='glyphicon glyphicon-pencil'></i></a>";
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
		$rating_produk = $this->Rating_produk_model->Get($id)[0];

		$this->load->library('parser');
		$data = array(
	        'title' => 'RATING PRODUK',
	        'content-path' => 'RATING PRODUK',
	        'content' => $this->load->view('rating-produk/index', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

    public function get_json()
    {
        $id = $this->input->get('id');
		$data = $this->Rating_produk_model->get($id);
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
	        'title' => 'Input RATING PRODUK', 
	        'content-path' => 'RATING PRODUK / TAMBAH DATA',
	        'content' => $this->load->view('rating-produk/add', $param, TRUE),
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

        $this->Rating_produk_model->store($data);
        $this->session->set_flashdata('info','Data inserted successfully.');
		redirect('Rating_produk');
	}

	public function edit()
	{
		$id_jenis_barang_pusat = $this->input->get('id_jenis_barang_pusat');
		$param['cols'] = $this->colsi;

		$this->load->library('parser');
        
		$param['rating_produk'] = $this->Rating_produk_model->get(null,$id_jenis_barang_pusat);

        $data = array(
	        'title' => 'RATING PRODUK',
	        'content-path' => 'RATING PRODUK / UBAH DATA',
	        'content' => $this->load->view('rating-produk/edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function update()
	{	

		$id = $this->input->post('id');
		$id_jenis_barang_pusat = $this->input->post('id_jenis_barang_pusat');

		if(!empty($id)){
			$rating_produk = $this->Rating_produk_model->get($id);
		}

        foreach($this->colsi as $key=>$val){
			if(!in_array($val['dbcolumn'], array('tanggal'))){
				$data[$val['dbcolumn']] = $this->input->post($val['column']);
			}
		}
		unset($data['nama_barang']);
		$data['id_jenis_barang_pusat'] = $id_jenis_barang_pusat;

		if(!empty($id)){
			$this->Rating_produk_model->update($id, $data);
		}else{
			$this->Rating_produk_model->store($data);       
		}
        $this->session->set_flashdata('info','Data updated successfully.');
        redirect('Rating_produk');		
	}

	public function destroy()
	{	
        $id = $this->input->get('id');
		$rating_produk = $this->Rating_produk_model->get($id);

		if($rating_produk->nama_file != '' and $rating_produk->nama_file != 'null' and !is_null($rating_produk->nama_file) and $rating_produk->nama_file != '[]')
			$images = json_decode($rating_produk->nama_file);
		else
			$images = [];

		foreach($images as $image){
			unlink($this->config->item('doc_root').'/upload/rating-produk'.$image);	
		}

		$this->Rating_produk_model->destroy($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('Rating_produk');
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
            $data = $this->Rating_produk_model->ExportAllForAjax($order, $dir, $filtercond);
        }
        else {
            $search = $this->input->post('search')['value']; 
            $data =  $this->Rating_produk_model->ExportSearchAjax($search, $order, $dir, $filtercond);
        }

        $visible_columns = $this->input->post('visible_columns');
        $visible_header_columns = array();
        $this->xlsxwriter->writeSheetHeader('RATING PRODUK', $visible_header_columns, array('font-style'=>'bold'));
        
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
            $this->xlsxwriter->writeSheetRow('RATING PRODUK', $newRow);
        }
        
        $uniq_id = substr(md5(uniqid(rand(), true)), 0, 5);
        $file = "upload/BASTB App RATING PRODUK - $uniq_id.xlsx";
        $this->xlsxwriter->writeToFile($file);

        header('Content-Type: application/json');
        echo json_encode(array('filename' => base_url().'Rating_produk/download?filename='.$file));
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
		$id_rating_produk = $this->input->post('id_rating_produk');

		$rating_produk = $this->Rating_produk_model->get($id_rating_produk);

		$kodefile_upload = strtotime(NOW);

		if($rating_produk->nama_file != '' and $rating_produk->nama_file != 'null' and !is_null($rating_produk->nama_file) and $rating_produk->nama_file != '[]')
			$nama_file = json_decode($rating_produk->nama_file);
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
		        $targetFile =  $target_file = $this->config->item('doc_root').'/upload/rating-produk'.$kodefile_upload.basename($_FILES['file']['name'][$key]);
	        	move_uploaded_file($tempFile, $target_file);

		    }

		    $nama_file = json_encode($nama_file);
        	$data = array(
				'nama_file' => $nama_file,
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->Rating_produk_model->update($id_rating_produk, $data);

			$this->session->set_flashdata('info','File uploaded successfully.');
			exit('success');
        }
    }
    
    public function remove_file()
    {
		$id_rating_produk = $this->input->get('id_rating_produk');
		$urutanfile = $this->input->get('urutanfile');

		$rating_produk = $this->Rating_produk_model->get($id_rating_produk);

		if($rating_produk->nama_file != '' and $rating_produk->nama_file != 'null' and !is_null($rating_produk->nama_file) and $rating_produk->nama_file != '[]')
			$images = json_decode($rating_produk->nama_file);
		else
			$images = [];

		$nama_file = $images[$urutanfile];
		unlink($this->config->item('doc_root').'/upload/rating-produk'.$nama_file);	

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
		$this->Rating_produk_model->update($id_rating_produk, $data);

		$this->session->set_flashdata('info','File berhasil dihapus.');
		exit('success');
	}
}
	