<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alokasi_persediaan_pusat extends CI_Controller {
    function __construct()
    {
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('Alokasi_persediaan_pusat_model');
        $this->load->model('Alokasi_pusat_model');
        $this->load->model('Kontrak_pusat_model');
        $this->load->helper('url');
		$this->load->library('xlsxwriter');
	}

    public function index($id = null)
    {
		$id_alokasi = $this->input->get('id_alokasi');
        $this->load->library('parser');
        $param['alokasi_persediaan_pusat'] = $this->Alokasi_persediaan_pusat_model->get(null,$id_alokasi);

        if(!empty($id_alokasi)){
            $param['alokasi_pusat'] = $this->Alokasi_pusat_model->get($id_alokasi);
            $param['kontrak_pusat'] = $this->Kontrak_pusat_model->get($param['alokasi_pusat']->id_kontrak_pusat);
            $param['id_kontrak_pusat'] = $param['kontrak_pusat']->id;
        }

        $param['total_unit'] = $this->Alokasi_persediaan_pusat_model->total_unit($id_alokasi);
        $param['total_nilai'] = $this->Alokasi_persediaan_pusat_model->total_nilai($id_alokasi);

        $param['total_unit_kontrak'] = $this->Kontrak_pusat_model->total_unit();
        $param['total_nilai_kontrak'] = $this->Kontrak_pusat_model->total_nilai();

        $data = array(
            'title' => 'Data Alokasi Persediaan Kontrak Pusat',
            'content-path' => 'PENGADAAN PUSAT / ALOKASI PERSEDIAAN',
            'content' => $this->load->view('alokasi-persediaan-pusat/index', $param, TRUE),
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
        
        $id_alokasi = $this->input->post('id_alokasi');
		$columns = array( 
            0 =>'tahun_anggaran', 
            1 =>'no_kontrak',
            2 => 'periode_mulai',
            3 => 'hd.nama_barang',
            4 => 'hd.merk',
            5 => 'nama_provinsi',
            6 => 'nama_kabupaten',
            7 => 'jumlah_barang',
            8 => 'nilai_barang',
            9 => 'harga_satuan',
            10 => 'dinas',
            11 => 'nama_penyedia_pusat',
            12 => 'id',
        );
     
        $totalData = $this->Alokasi_persediaan_pusat_model->get(null,$id_alokasi);
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
		$posts_all_search =  $this->Alokasi_persediaan_pusat_model->get(null, $id_alokasi, null, null, null, null, $filtercond, $search);
        $totalFiltered = count($posts_all_search);
		$posts =  $this->Alokasi_persediaan_pusat_model->get(null, $id_alokasi, $start, $length, $order, $dir, $filtercond, $search);

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData['tahun_anggaran'] = $post->tahun_anggaran;
                $nestedData['no_kontrak'] = $post->no_kontrak;
                $nestedData['periode'] = date('d-m-Y', strtotime($post->periode_mulai)). " s/d ".date('d-m-Y', strtotime($post->periode_selesai));
                $nestedData['nama_barang'] = $post->nama_barang;
                $nestedData['merk'] = $post->merk;
                $nestedData['nama_provinsi'] = $post->nama_provinsi;
                $nestedData['nama_kabupaten'] = $post->nama_kabupaten;

                $nestedData['dinas'] = $post->dinas;
                $nestedData['nama_penyedia'] = $post->nama_penyedia_pusat;
                $nestedData['status_alokasi'] = $post->status_alokasi;

                $nestedData['nilai_barang'] = number_format($post->nilai_barang, 0);
                $nestedData['jumlah_barang'] = number_format($post->jumlah_barang, 0);
                if($post->jumlah_barang >0){
                    $nestedData['harga_satuan'] = number_format(($post->nilai_barang/$post->jumlah_barang), 0);
                }else{
                    $nestedData['harga_satuan'] = 0;
                }

				$tools = '<div class="dropdown">
				<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				-- Pilih menu --
				</button>
				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					<a class="dropdown-item" href="#" data-href="#" data-toggle="modal" data-record-title="'.$post->id.'" data-target="#upload-modal" data-record-id="'.$post->id.'">Upload Dokumen</a>
					<a class="dropdown-item" href="#" onclick="LoadData('.$post->id.')">Lihat Data</a>';
				if($bolehedit)
					$tools .= '<a class="dropdown-item" href="'.base_url('Alokasi_persediaan_pusat/edit?id='.$post->id).'">Ubah Data</a>';
				if($bolehhapus)
					$tools .= '<a class="dropdown-item"  href="#" data-toggle="modal" data-record-title="'.$post->id.'" data-target="#confirm-delete" data-href="'.base_url('Alokasi_persediaan_pusat/destroy/').$post->id.'">Hapus Data</a>';
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
		$alokasi_pusat = $this->Alokasi_pusat_model->get($id);;
		$kontrak_pusat = $this->Kontrak_pusat_model->get($alokasi_pusat->id_kontrak_pusat);;
		$param['kontrak_pusat'] = $kontrak_pusat;

		$param['Alokasi_persediaan'] = array();
		$param['total_unit'] = $this->Kontrak_pusat_model->total_unit($kontrak_pusat->id);
		$param['total_nilai'] = $this->Kontrak_pusat_model->total_nilai($kontrak_pusat->id);

		$this->load->library('parser');
		$data = array(
	        'title' => 'Data Alokasi Persediaan Kontrak Pusat',
	        'content-path' => 'PENGADAAN PUSAT / DATA ALOKASI PERSEDIAAN',
	        'content' => $this->load->view('alokasi-persediaan-pusat/index', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

    public function get_json()
    {
		$id = $this->input->get('id');

		$data = $this->Alokasi_persediaan_pusat_model->get($id);
		$hasil = json_encode($data);
		header('HTTP/1.1: 200');
		header('Status: 200');
		header('Content-Length: '.strlen($hasil));
		exit($hasil);
    }
    
    public function create()
	{
		$id_alokasi = $this->input->get('id_alokasi');
		$param['alokasi_pusat'] = $this->Alokasi_pusat_model->get($id_alokasi);
		$param['kontrak_pusat'] = $this->Kontrak_pusat_model->get($param['alokasi_pusat']->id_kontrak_pusat);
		$param['id_alokasi'] = $id_alokasi;

		$this->load->library('parser');
		$this->load->model('ProvinsiModel');

		$param['provinsi'] = $this->ProvinsiModel->GetAll();

		$data = array(
	        'title' => 'Input Alokasi Persediaan Kontrak Pusat',
	        'content-path' => 'PENGADAAN PUSAT / DATA ALOKASI PERSEDIAAN / TAMBAH DATA',
	        'content' => $this->load->view('alokasi-persediaan-pusat/add', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function store()
	{	
		//cek unit limit
		$id_alokasi = $this->input->post('id_alokasi');
		$alokasi_pusat = $this->Alokasi_pusat_model->get($id_alokasi);
		$kontrak_pusat = $this->Kontrak_pusat_model->get($alokasi_pusat->id_kontrak_pusat);
		$jumlah_unit = (int)str_replace(',', '', $this->input->post('jumlah_barang'));
		$jumlah_nilai = (float)str_replace(',', '', $this->input->post('nilai_barang'));
		$total_nilai = $this->Alokasi_persediaan_pusat_model->total_nilai($id_alokasi);
		$total_unit = $this->Alokasi_persediaan_pusat_model->total_unit($id_alokasi);
		// var_dump($total_nilai);exit();
        if(($jumlah_unit+$total_unit > $alokasi_pusat->jumlah_barang) || ($jumlah_nilai+$total_nilai > $alokasi_pusat->nilai_barang)){
            $this->session->set_flashdata('error','Jumlah atau nilai unit tidak dapat melebihi jumlah atau nilai unit kontrak.');
            redirect('Alokasi_persediaan_pusat/create?id_alokasi='.$id_alokasi);
        }

		$id_provinsi = $this->input->post('id_provinsi');
		$id_kabupaten = $this->input->post('id_kabupaten');
		$jumlah_barang = (float)str_replace(',', '', $this->input->post('jumlah_barang'));
		$nilai_barang = (float)str_replace(',', '', $this->input->post('nilai_barang'));
		$dinas = $this->input->post('dinas');

		$status_alokasi = $this->input->post('status_alokasi');
		
		if($id_alokasi == '' or $id_provinsi == '' or $id_kabupaten == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('Alokasi_persediaan_pusat/create?id_alokasi='.$id_alokasi);
		}
		else{
			$data = array(
				'id_alokasi' => $id_alokasi,
				'id_provinsi' => $id_provinsi,
				'id_kabupaten' => $id_kabupaten,
				'jumlah_barang' => $jumlah_barang,
				'nilai_barang' => $nilai_barang,
				'dinas' => $dinas,
				'status_alokasi' => $status_alokasi,
				'nama_file' => '',
				'created_by' => $this->session->userdata('logged_in')->id_pengguna,
				'created_at' => NOW,
			);
			$this->Alokasi_persediaan_pusat_model->store($data);
			$this->session->set_flashdata('info','Data inserted successfully.');
			redirect('Alokasi_persediaan_pusat/index?id_alokasi='.$id_alokasi);
		}
		
	}

	public function edit()
	{
		$id = $this->input->get('id');

		$alokasi_persediaan_pusat = $this->Alokasi_persediaan_pusat_model->get($id);
		$param['alokasi_persediaan_pusat'] = $alokasi_persediaan_pusat;
		$param['kontrak_pusat'] = $this->Kontrak_pusat_model->get($alokasi_persediaan_pusat->id_kontrak_pusat);

		$this->load->library('parser');
		$this->load->model('ProvinsiModel');

		$param['provinsi'] = $this->ProvinsiModel->GetAll();

		$data = array(
	        'title' => 'Data Alokasi_persediaan Kontrak Pusat',
	        'content-path' => 'PENGADAAN PUSAT / DATA Alokasi_persediaan / UBAH DATA',
	        'content' => $this->load->view('alokasi-persediaan-pusat/edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function update()
	{	

		$id = $this->input->post('id');
		$alokasi_persediaan_pusat = $this->Alokasi_persediaan_pusat_model->get($id);

		//cek unit limit
		$id_alokasi = $alokasi_persediaan_pusat->id_alokasi;
		$alokasi_pusat = $this->Alokasi_pusat_model->get($id_alokasi);
		$kontrak_pusat = $this->Kontrak_pusat_model->get($alokasi_pusat->id_kontrak_pusat);
		$jumlah_unit = (float)str_replace(',', '', $this->input->post('jumlah_barang'));
		$jumlah_nilai = (float)str_replace(',', '', $this->input->post('nilai_barang'));
		$total_unit = $this->Alokasi_persediaan_pusat_model->total_unit($id_alokasi);
		$total_nilai = $this->Alokasi_persediaan_pusat_model->total_nilai($id_alokasi);
		if(($jumlah_unit+$total_unit-$alokasi_persediaan_pusat->jumlah_barang > $alokasi_pusat->jumlah_barang) || $jumlah_nilai+$total_nilai-$alokasi_persediaan_pusat->nilai_barang > $alokasi_pusat->nilai_barang){
			$this->session->set_flashdata('error','Jumlah unit tidak dapat melebihi jumlah unit kontrak.');
			redirect('Alokasi_persediaan_pusat/edit?id='.$id);
		}

		$id_provinsi = $this->input->post('id_provinsi');
		$id_kabupaten = $this->input->post('id_kabupaten');
		$jumlah_barang = (float)str_replace(',', '', $this->input->post('jumlah_barang'));
		$nilai_barang = (float)str_replace(',', '', $this->input->post('nilai_barang'));
		$dinas = $this->input->post('dinas');

		$status_alokasi = $this->input->post('status_alokasi');
		
		if($id_alokasi == '' or $id_provinsi == '' or $id_kabupaten == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('Alokasi_persediaan_pusat/edit?id='.$id);
		}
		else{
			$data = array(
				'id_alokasi' => $id_alokasi,
				'id_provinsi' => $id_provinsi,
				'id_kabupaten' => $id_kabupaten,
				'jumlah_barang' => $jumlah_barang,
				'nilai_barang' => $nilai_barang,
				'dinas' => $dinas,
				'status_alokasi' => $status_alokasi,
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->Alokasi_persediaan_pusat_model->update($id, $data);
			$this->session->set_flashdata('info','Data updated successfully.');
			redirect('Alokasi_persediaan_pusat/index?id_alokasi='.$id_alokasi);
		}
		
	}

	public function destroy($id)
	{	
		$alokasi_persediaan_pusat = $this->Alokasi_persediaan_pusat_model->get($id);
		$id_alokasi_pusat = $alokasi_persediaan_pusat->id_alokasi;

		if($alokasi_persediaan_pusat->nama_file != '' and $alokasi_persediaan_pusat->nama_file != 'null' and !is_null($alokasi_persediaan_pusat->nama_file) and $alokasi_persediaan_pusat->nama_file != '[]')
			$images = json_decode($alokasi_persediaan_pusat->nama_file);
		else
			$images = [];

		foreach($images as $image){
			@unlink($this->config->item('doc_root').'/upload/alokasi_persediaan_pusat/'.$image);	
		}

		$this->Alokasi_persediaan_pusat_model->destroy($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('Alokasi_persediaan_pusat/index?id_alokasi='.$id_alokasi_pusat);
	}

    public function export()
    {
        $columns = array( 
            0 =>'tahun_anggaran', 
            1 =>'no_kontrak',
            2 => 'periode_mulai',
            3 => 'hd.nama_barang',
            4 => 'hd.merk',
            5 => 'nama_provinsi',
            6 => 'nama_kabupaten',
            7 => 'jumlah_barang',
            8 => 'nilai_barang',
            9 => 'harga_satuan',
            10 => 'dinas',
            13 => 'nama_penyedia_pusat',
            15 => 'id',
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
            $data = $this->Alokasi_persediaan_pusat_model->ExportAllForAjax($order, $dir, $filtercond);
        }
        else {
            $search = $this->input->post('search')['value']; 
            $data =  $this->Alokasi_persediaan_pusat_model->ExportSearchAjax($search, $order, $dir, $filtercond);
        }

        $visible_columns = $this->input->post('visible_columns');
        $visible_header_columns = array();
        foreach($visible_columns as $value) {
            switch($value['title']) {
				case 'Tahun Anggaran':
				case 'Alokasi_persediaan': 
					$visible_header_columns[$value['title']] = 'integer';
					break;
                case 'Nilai (Rp)': 
                case 'Harga Satuan (Rp)': 
					$visible_header_columns[$value['title']] = '#,##0';
					break;
				default :
					$visible_header_columns[$value['title']] = 'string';
			}
        }
        $this->xlsxwriter->writeSheetHeader('Alokasi_persediaan Pusat', $visible_header_columns, array('font-style'=>'bold'));
        
        foreach($data as $row) {
            $newRow = array();
            foreach($visible_columns as $key => $value) {
                $defaultValue = '';
                if(isset($row[$value['id']])) {
                    $defaultValue = $row[$value['id']];
                }

                $jumlahBarang = 0;
                $nilaiBarang = 0;
                $hargaSatuan = 0;

                if($jumlahBarang > 0) {
                    $hargaSatuan = round($nilaiBarang / $jumlahBarang);
                }

                switch($value['id']) {
                    case 'tahun_anggaran': 
                        $newRow[$key] = $row['tahun_anggaran'];
                        break;
                    case 'periode':
                        $newRow[$key] = $row['periode_mulai'].' s/d '.$row['periode_selesai'];
                        break;
                    case 'Alokasi_persediaan': 
                        $newRow[$key] = $jumlahBarang;
                        break;
                    case 'nilai_barang': 
                        $newRow[$key] = $nilaiBarang;
                        break;
                    case 'harga_satuan': 
                        $newRow[$key] = $hargaSatuan;
                        break;
                    case 'nama_penyedia': 
                        $newRow[$key] = $row['nama_penyedia_pusat'];
                        break;
                    default: 
                        $newRow[$key] = $defaultValue;
                }
            }
            $this->xlsxwriter->writeSheetRow('Alokasi_persediaan Pusat', $newRow);
        }
        
        $uniq_id = substr(md5(uniqid(rand(), true)), 0, 5);
        $file = "upload/BASTB App Data Alokasi_persediaan Kontrak Pusat - $uniq_id.xlsx";
        $this->xlsxwriter->writeToFile($file);

        header('Content-Type: application/json');
        echo json_encode(array('filename' => base_url().'Alokasi_persediaan_pusat/download?filename='.$file));
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

		$alokasi_persediaan = $this->Alokasi_persediaan_pusat_model->get($id);

		$kodefile_upload = strtotime(NOW);

		if($alokasi_persediaan->nama_file != '' and $alokasi_persediaan->nama_file != 'null' and !is_null($alokasi_persediaan->nama_file) and $alokasi_persediaan->nama_file != '[]')
			$nama_file = json_decode($alokasi_persediaan->nama_file);
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
		        $targetFile =  $target_file = $this->config->item('doc_root').'/upload/alokasi_persediaan_pusat/'.$kodefile_upload.basename($_FILES['file']['name'][$key]);
	        	move_uploaded_file($tempFile, $target_file);

		    }

		    $nama_file = json_encode($nama_file);
        	$data = array(
				'nama_file' => $nama_file,
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->Alokasi_persediaan_pusat_model->update($id, $data);

			$this->session->set_flashdata('info','File uploaded successfully.');
			exit('success');
        }
    }
    
    public function remove_file()
    {
		$id = $this->input->get('id');
		$urutanfile = $this->input->get('urutanfile');

		$alokasi_persediaan = $this->Alokasi_persediaan_pusat_model->get($id);

		if($alokasi_persediaan->nama_file != '' and $alokasi_persediaan->nama_file != 'null' and !is_null($alokasi_persediaan->nama_file) and $alokasi_persediaan->nama_file != '[]')
			$images = json_decode($alokasi_persediaan->nama_file);
		else
			$images = [];

		$nama_file = $images[$urutanfile];
		unlink($this->config->item('doc_root').'/upload/alokasi_persediaan_pusat/'.$nama_file);	

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
		$this->Alokasi_persediaan_pusat_model->update($id, $data);

		$this->session->set_flashdata('info','File berhasil dihapus.');
		exit('success');
	}

	public function rekap()
	{
		$cols = array(
			array("column" => "tahun_anggaran", "caption" => "Tahun Anggaran", "dbcolumn" => "tahun_anggaran"),
			array("column" => "merk", "caption" => "Merk", "dbcolumn" => "merk"),
			array("column" => "nama_barang", "caption" => "Nama Barang", "dbcolumn" => "nama_barang"),
			array("column" => "nama_penyedia_pusat", "caption" => "Nama Penyedia Pusat", "dbcolumn" => "nama_penyedia_pusat"),
			array("column" => "no_kontrak", "caption" => "No Kontrak", "dbcolumn" => "no_kontrak"),
			array("column" => "periode_mulai", "caption" => "Periode Mulai", "dbcolumn" => "periode_mulai"),
			array("column" => "periode_selesai", "caption" => "Periode Selesai", "dbcolumn" => "periode_selesai"),
			array("column" => "alokasi", "caption" => "Alokasi", "dbcolumn" => "alokasi"),
			array("column" => "jumlah_barang", "caption" => "Pagu", "dbcolumn" => "tb_kontrak_pusat.jumlah_barang"),
		);
        $this->load->library('parser');
		$param['cols'] = $cols;
        $param['alokasi_persediaan_pusat'] = $this->Alokasi_persediaan_pusat_model->get_rekap();

        $param['alokasi'] = $this->Alokasi_persediaan_pusat_model->total_unit_rekap();
        $param['pagu'] = $this->Alokasi_persediaan_pusat_model->total_pagu_rekap();

        $data = array(
            'title' => 'Rekap Alokasi Persediaan Kontrak Pusat',
            'content-path' => 'PENGADAAN PUSAT / REKAP ALOKASI PERSEDIAAN',
            'content' => $this->load->view('alokasi-persediaan-pusat/rekap', $param, TRUE),
        );
        $this->parser->parse('default_template', $data);
	}
    
    public function rekap_json()
    {   
		$cols = array(
			array("column" => "tahun_anggaran", "caption" => "Tahun Anggaran", "dbcolumn" => "tahun_anggaran"),
			array("column" => "merk", "caption" => "Merk", "dbcolumn" => "merk"),
			array("column" => "nama_barang", "caption" => "Nama Barang", "dbcolumn" => "nama_barang"),
			array("column" => "nama_penyedia_pusat", "caption" => "Nama Penyedia Pusat", "dbcolumn" => "nama_penyedia_pusat"),
			array("column" => "no_kontrak", "caption" => "No Kontrak", "dbcolumn" => "no_kontrak"),
			array("column" => "periode_mulai", "caption" => "Periode Mulai", "dbcolumn" => "periode_mulai"),
			array("column" => "periode_selesai", "caption" => "Periode Selesai", "dbcolumn" => "periode_selesai"),
			array("column" => "alokasi", "caption" => "Alokasi", "dbcolumn" => "alokasi"),
			array("column" => "jumlah_barang", "caption" => "Pagu", "dbcolumn" => "tb_kontrak_pusat.jumlah_barang"),
		);

        $start = empty($this->input->post('start'))? 0:$this->input->post('start');
        $length = empty($this->input->post('length'))? null:$this->input->post('length');
        $order = empty($this->input->post('order')[0]['column'])? null:$this->input->post('order')[0]['column'];
		if(!empty($order)) $order = $cols[$order]['column'];
        $dir = empty($this->input->post('order')[0]['dir'])? null:$this->input->post('order')[0]['dir'];
        
        $id_alokasi = $this->input->post('id_alokasi');
		$columns = array();
		foreach($cols as $key=>$val){
			array_push($columns,$val['column']);
		}

		$dbcolumns = array();
		foreach($cols as $key=>$val){
			array_push($dbcolumns,$val['dbcolumn']);
		}
     
        $totalData = $this->Alokasi_persediaan_pusat_model->get_rekap();
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
		$posts_all_search =  $this->Alokasi_persediaan_pusat_model->get_rekap(null, null, null, null, null, $filtercond, $search);
        $totalFiltered = count($posts_all_search);
		$posts =  $this->Alokasi_persediaan_pusat_model->get_rekap(null, $start, $length, $order, $dir, $filtercond, $search);

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
				foreach($cols as $val){
					$nestedData[$val['column']] = $post->{$val['column']};
				}
                $nestedData['periode_mulai'] = date('d-m-Y', strtotime($post->periode_mulai)). " s/d ".date('d-m-Y', strtotime($post->periode_selesai));
                $nestedData['alokasi'] = number_format($post->alokasi, 0);
                $nestedData['jumlah_barang'] = number_format($post->jumlah_barang, 0);
                $nestedData['sisa'] = number_format($post->jumlah_barang - $post->alokasi, 0);
                
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
	