<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alokasi_provinsi extends CI_Controller {
    public $colsc = array(
		array("column" => "tahun_anggaran", "caption" => "Tahun Anggaran", "dbcolumn" => "tb_kontrak_provinsi.tahun_anggaran"),
	  	array("column" => "no_kontrak", "caption" => "No. Kontrak", "dbcolumn" => "hd.no_kontrak"),
		array("column" => "periode", "caption" => "Periode", "dbcolumn" => "periode"),
		array("column" => "nama_barang", "caption" => "Nama Barang", "dbcolumn" => "nama_barang"),
		array("column" => "merk", "caption" => "Merk", "dbcolumn" => "merk"),
		array("column" => "nama_provinsi", "caption" => "Provinsi", "dbcolumn" => "p.nama_provinsi"),
		array("column" => "nama_kabupaten", "caption" => "Kabupaten", "dbcolumn" => "k.nama_kabupaten"),
		array("column" => "jumlah_barang_rev", "caption" => "Alokasi", "dbcolumn" => "jumlah_barang_rev"),
		array("column" => "nilai_barang_rev", "caption" => "Nilai(Rp)", "dbcolumn" => "nilai_barang_rev"),
		array("column" => "harga_satuan_rev", "caption" => "Harga Satuan(Rp)", "dbcolumn" => "harga_satuan_rev"),
		array("column" => "dinas", "caption" => "ID", "dbcolumn" => "dinas"),
		array("column" => "regcad", "caption" => "REG/CAD", "dbcolumn" => "regcad"),
		array("column" => "no_adendum", "caption" => "No. Addendum", "dbcolumn" => "no_adendum"),
		array("column" => "nama_penyedia_provinsi", "caption" => "Penyedia", "dbcolumn" => "nama_penyedia_provinsi"),
    );
    
    function __construct()
    {
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('Alokasi_provinsi_model');
        $this->load->model('Kontrak_provinsi_model');
        $this->load->model('Sp2d_model');
        $this->load->helper('url');
		$this->load->library('xlsxwriter');
	}

    public function index($id = null)
    {
        $this->load->library('parser');
        $param['alokasi_provinsi'] = $this->Alokasi_provinsi_model->get();
        $id_kontrak_provinsi = $this->input->get('id_kontrak_provinsi');

        if(!empty($id_kontrak_provinsi)){
            $param['kontrak_provinsi'] = $this->Kontrak_provinsi_model->get($_GET['id_kontrak_provinsi']);
        }

        $param['total_unit'] = $this->Alokasi_provinsi_model->total_unit(null, $id_kontrak_provinsi);
        $param['total_nilai'] = $this->Alokasi_provinsi_model->total_nilai(null, $id_kontrak_provinsi);
        $param['total_kontrak'] = $this->Alokasi_provinsi_model->total_kontrak($id_kontrak_provinsi);
        $param['total_merk'] = $this->Alokasi_provinsi_model->total_merk($id_kontrak_provinsi);

        $param['total_unit_kontrak'] = $this->Kontrak_provinsi_model->total_unit();
        $param['total_nilai_kontrak'] = $this->Kontrak_provinsi_model->total_nilai();

        $data = array(
            'title' => 'Data Alokasi Kontrak Provinsi',
            'content-path' => 'PENGADAAN PROVINSI / ALOKASI',
            'content' => $this->load->view('alokasi-provinsi/index', $param, TRUE),
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
        
        $id_kontrak_provinsi = $this->input->post('id_kontrak_provinsi');
		$columns = array( 
            0 =>'nama_provinsi', 
            1 =>'nama_kabupaten',
            2 => 'jumlah_barang',
            3 => 'nilai_barang',
            4 => 'harga_satuan',
            5 => 'regcad',
            6 => 'dinas',
            7 => 'no_adendum_1',
            8 => 'jumlah_barang_rev_1',
            9 => 'nilai_barang_rev_1',
            10 => 'harga_satuan_rev_1',
            11 => 'no_adendum_2',
            12 => 'jumlah_barang_rev_2',
            13 => 'nilai_barang_rev_2',
            14 => 'harga_satuan_rev_2',
            15 => 'no_adendum_3',
            16 => 'jumlah_barang_rev_3',
            17 => 'nilai_barang_rev_3',
            18 => 'harga_satuan_rev_3',
            19 => 'status_alokasi',
        );
     
        $totalData = $this->Alokasi_provinsi_model->get(null,$id_kontrak_provinsi);
        $totalData = count($totalData);
            
        $totalFiltered = $totalData; 

        //search data percolumn
        $filtercond = '';
        for($i=0;$i<count($columns);$i++){
            if($i<7 or $i>9 or $i!=12){
                if(!empty($this->input->post('columns')[$i]['search']['value'])){
                    $search = $this->input->post('columns')[$i]['search']['value'];
                    $filtercond  .= " and ".$columns[$i]." LIKE '%".$search."%'"; 
                }
            }
            else{
                if($i==7){
                    $search = $this->input->post('columns')[$i]['search']['value'];
                    $filtercond .= " and (
                            (CASE   
                            WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
                                dt.jumlah_barang_rev_1
                            WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
                                dt.jumlah_barang_rev_2
                                WHEN dt.status_alokasi = 'DATA ADDENDUM 3' THEN
                                    dt.jumlah_barang_rev_3
                            WHEN dt.status_alokasi = 'DATA KONTRAK AWAL' THEN
                                dt.jumlah_barang
                            END) LIKE '%".$search."%'
                        )";
                }
                if($i==8){
                    $search = $this->input->post('columns')[$i]['search']['value'];
                    $filtercond .= " and (
                            (CASE   
                            WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
                                dt.nilai_barang_rev_1
                            WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
                                dt.nilai_barang_rev_2
                            WHEN dt.status_alokasi = 'DATA ADDENDUM 3' THEN
                                dt.nilai_barang_rev_3
                            WHEN dt.status_alokasi = 'DATA KONTRAK AWAL' THEN
                                dt.nilai_barang
                            END) LIKE '%".$search."%'
                        )";
                }
                if($i==12){
                    $search = $this->input->post('columns')[$i]['search']['value'];
                    $filtercond .= " and (
                            (CASE   
                            WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
                                dt.no_adendum_1
                            WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
                                dt.no_adendum_2
                            WHEN dt.status_alokasi = 'DATA ADDENDUM 3' THEN
                                dt.no_adendum_3
                            WHEN dt.status_alokasi = 'DATA KONTRAK AWAL' THEN
                                hd.no_kontrak
                            END) LIKE '%".$search."%'
                        )";
                }
            }      	
        }
        // var_dump($filtercond);exit();
		$posts_all_search =  $this->Alokasi_provinsi_model->get(null, $id_kontrak_provinsi, null, null, null, null, $filtercond);
        $totalFiltered = count($posts_all_search);
		$posts =  $this->Alokasi_provinsi_model->get(null, $id_kontrak_provinsi, $start, $length, $order, $dir, $filtercond);

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData['id'] = $post->id;
                $nestedData['tahun_anggaran'] = $post->tahun_anggaran;
                $nestedData['no_kontrak'] = $post->no_kontrak;
                $nestedData['periode'] = date('d-m-Y', strtotime($post->periode_mulai)). " s/d ".date('d-m-Y', strtotime($post->periode_selesai));
                $nestedData['nama_barang'] = $post->nama_barang;
                $nestedData['merk'] = $post->merk;
                $nestedData['nama_provinsi'] = $post->nama_provinsi;
                $nestedData['nama_kabupaten'] = $post->nama_kabupaten;

                $nestedData['dinas'] = $post->dinas;
                $nestedData['regcad'] = $post->regcad;

                $nestedData['nama_penyedia'] = $post->nama_penyedia_provinsi;
                $nestedData['status_alokasi'] = $post->status_alokasi;

                $nestedData['no_adendum'] = $post->no_adendum;
                $nestedData['nilai_barang'] = number_format($post->nilai_barang, 0);
                $nestedData['jumlah_barang'] = number_format($post->jumlah_barang, 0); 
                $nestedData['harga_satuan'] = number_format(($post->harga_satuan), 0);
                if($post->no_adendum_1){
                    $nestedData['no_adendum_1'] = $post->no_adendum_1;
                    $nestedData['jumlah_barang_rev_1'] = number_format($post->jumlah_barang_rev_1, 0);
                    $nestedData['nilai_barang_rev_1'] = number_format($post->nilai_barang_rev_1, 0);
                    if($post->jumlah_barang_rev_1 >0){
                        $nestedData['harga_satuan_rev_1'] = number_format(($post->nilai_barang_rev_1/$post->jumlah_barang_rev_1), 0);
                    }else{
                        $nestedData['harga_satuan_rev_1'] = '';
                    }
                }else{
                    $nestedData['no_adendum_1'] = '';
                    $nestedData['jumlah_barang_rev_1'] = '';
                    $nestedData['nilai_barang_rev_1'] = '';
                    $nestedData['harga_satuan_rev_1'] = '';
                }
                if($post->no_adendum_2){
                    $nestedData['no_adendum_2'] = $post->no_adendum_2;
                    $nestedData['jumlah_barang_rev_2'] = number_format($post->jumlah_barang_rev_2, 0);
                    $nestedData['nilai_barang_rev_2'] = number_format($post->nilai_barang_rev_2, 0);
                    if($post->jumlah_barang_rev_2 >0){
                        $nestedData['harga_satuan_rev_2'] = number_format(($post->nilai_barang_rev_2/$post->jumlah_barang_rev_2), 0);
                    }else{
                        $nestedData['harga_satuan_rev_2'] = '';
                    }
                }else{
                    $nestedData['no_adendum_2'] = '';
                    $nestedData['jumlah_barang_rev_2'] = '';
                    $nestedData['nilai_barang_rev_2'] = '';
                    $nestedData['harga_satuan_rev_2'] = '';
                }
                if($post->no_adendum_3){
                    $nestedData['no_adendum_3'] = $post->no_adendum_3;
                    $nestedData['jumlah_barang_rev_3'] = number_format($post->jumlah_barang_rev_3, 0);
                    $nestedData['nilai_barang_rev_3'] = number_format($post->nilai_barang_rev_3, 0);
                    if($post->jumlah_barang_rev_3 >0){
                        $nestedData['harga_satuan_rev_3'] = number_format(($post->nilai_barang_rev_3/$post->jumlah_barang_rev_3), 0);
                    }else{
                        $nestedData['harga_satuan_rev_3'] = '';
                    }
                }else{
                    $nestedData['no_adendum_3'] = '';
                    $nestedData['jumlah_barang_rev_3'] = '';
                    $nestedData['nilai_barang_rev_3'] = '';
                    $nestedData['harga_satuan_rev_3'] = '';
                }
                $nestedData['status_rilis'] = $post->status_rilis;
                $nestedData['ketfoto'] = (($post->nama_file != '' and $post->nama_file != '[]' and $post->nama_file != 'null' and !is_null($post->nama_file)) ?  '<a class="btn btn-success"><i class="glyphicon glyphicon-ok"></i></a>' : '<a class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i></a>');

                $tools = '<div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                -- Pilih menu --
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                $tools .= '<a class="dropdown-item" href="#" data-href="#" data-toggle="modal" data-record-title="'.$post->id.'" data-target="#upload-modal" data-record-id="'.$post->id.'">Upload Dokumen</a>
                    <a class="dropdown-item" href="#" onclick="LoadData('.$post->id.')">Lihat Data</a>';
                if($bolehedit)
                    $tools .= '<a class="dropdown-item" href="'.base_url('Alokasi_provinsi/edit?id='.$post->id).'">Ubah Data</a>';
                if($bolehhapus)
                    $tools .= '<a class="dropdown-item"  href="#" data-toggle="modal" data-record-title="'.$post->id.'" data-target="#confirm-delete" data-href="'.base_url('Alokasi_provinsi/destroy/').$post->id.'">Hapus Data</a>';
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

    public function index_confirmed($id = null) 
    {
		$param['cols'] = $this->colsc;
        $param['search_norangka'] = $this->input->post('search_norangka');
        
        $this->load->library('parser');
        $param['alokasi_provinsi'] = $this->Alokasi_provinsi_model->get_confirmed();

        $param['total_unit'] = $this->Alokasi_provinsi_model->total_unit();
        $param['total_nilai'] = $this->Alokasi_provinsi_model->total_nilai();
        $param['total_kontrak'] = $this->Alokasi_provinsi_model->total_kontrak();
        $param['total_merk'] = $this->Alokasi_provinsi_model->total_merk();

        $param['total_unit_kontrak'] = $this->Kontrak_provinsi_model->total_unit();
        $param['total_nilai_kontrak'] = $this->Kontrak_provinsi_model->total_nilai();

        $data = array(
            'title' => 'Data Alokasi Provinsi',
            'content-path' => 'PENGADAAN PROVINSI / ALOKASI',
            'content' => $this->load->view('alokasi-provinsi/index_confirmed', $param, TRUE),
        );
        $this->parser->parse('default_template', $data);
    }
    
    public function index_confirmed_json()
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
        
        $search_norangka = $this->input->post('search_norangka');
        $start = empty($this->input->post('start'))? 0:$this->input->post('start');
        $length = empty($this->input->post('length'))? null:$this->input->post('length');
        $order = empty($this->input->post('order')[0]['column'])? null:$this->input->post('order')[0]['column'];
        $dir = empty($this->input->post('order')[0]['dir'])? null:$this->input->post('order')[0]['dir'];
        
        $id_kontrak_provinsi = $this->input->post('id_kontrak_provinsi');
		$columns = array();
		foreach($this->colsc as $key=>$val){
			array_push($columns,$val['column']);
		}

		$dbcolumns = array();
		foreach($this->colsc as $key=>$val){
			array_push($dbcolumns,$val['dbcolumn']);
		}
     
        $totalData = $this->Alokasi_provinsi_model->get_confirmed(null,null);
        $totalData = count($totalData);
            
        $totalFiltered = $totalData; 

        //filter confirmed only
        $filtercond = " and status_alokasi NOT IN ('MENUNGGU KONFIRMASI') 
        AND CASE
        WHEN (status_alokasi = 'DATA ADDENDUM 1') THEN jumlah_barang_rev_1 > 0
        WHEN (status_alokasi = 'DATA ADDENDUM 2') THEN jumlah_barang_rev_2 > 0
        WHEN (status_alokasi = 'DATA ADDENDUM 3') THEN jumlah_barang_rev_3 > 0
        ELSE jumlah_barang > 0
        END
        ";
        //search data percolumn
        $filtercond = '';
        for($i=0;$i<count($columns);$i++){
            if(!empty($this->input->post('columns')[$i]['search']['value'])){
                if($dbcolumns[$i] == 'no_adendum'){
                    // check adendum1,2,3
                    $search = $this->input->post('columns')[$i]['search']['value'];
                    $filtercond  .= " AND CASE
                    WHEN (alo.status_alokasi = 'DATA ADDENDUM 1') THEN no_adendum_1 LIKE '%".$search."%'
                    WHEN (alo.status_alokasi = 'DATA ADDENDUM 2') THEN no_adendum_2 LIKE '%".$search."%'
                    WHEN (alo.status_alokasi = 'DATA ADDENDUM 3') THEN no_adendum_3 LIKE '%".$search."%'
                    END"; 
                }else{
                    $search = $this->input->post('columns')[$i]['search']['value'];
                    $filtercond  .= " and ".$dbcolumns[$i]." LIKE '%".$search."%'"; 
                }
            }
        }

        if(!empty($search_norangka)){
            $filtercond .= " and alo.id IN 
            (SELECT tb_alokasi_provinsi.id FROM tb_alokasi_provinsi 
            INNER JOIN tb_baphp_provinsi ON tb_baphp_provinsi.id_alokasi_provinsi = tb_alokasi_provinsi.id
            INNER JOIN tb_baphp_provinsi_norangka ON tb_baphp_norangka.id_baphp = tb_baphp_provinsi.id
            WHERE
            tb_baphp_provinsi_norangka.norangka LIKE '%".$search_norangka."%' OR tb_baphp_provinsi_norangka.nomesin LIKE '%".$search_norangka."%')";
        }
        
		$posts_all_search =  $this->Alokasi_provinsi_model->get_confirmed(null, $id_kontrak_provinsi, null, null, null, null, $filtercond);
        $totalFiltered = count($posts_all_search);
		$posts =  $this->Alokasi_provinsi_model->get_confirmed(null, $id_kontrak_provinsi, $start, $length, $order, $dir, $filtercond);
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();
				foreach($this->colsc as $val){
					$nestedData[$val['column']] = $post->{$val['column']};
                }
				$nestedData['nilai_barang_rev'] = number_format($post->nilai_barang_rev,0);                
                $nestedData['harga_satuan_rev'] = number_format($post->harga_satuan_rev,0);             
                if($post->jumlah_baphp == $post->jumlah_barang_rev){
                    $nestedData['status_baphp'] = '<a class="btn btn-success">CUKUP</a>';
                }elseif($post->jumlah_baphp > $post->jumlah_barang_rev){
                    $nestedData['status_baphp'] = '<a class="btn btn-primary" title="BAPHP: '.$post->jumlah_baphp.'/'.$post->jumlah_barang_rev.'">LEBIH</a>';
                }else{
                    $nestedData['status_baphp'] = '<a class="btn btn-danger" title="BAPHP: '.$post->jumlah_baphp.'/'.$post->jumlah_barang_rev.'">KURANG</a>';
                }
                if($post->jumlah_bastb == $post->jumlah_barang_rev){
                    $nestedData['status_bastb'] = '<a class="btn btn-success">CUKUP</a>';
                }elseif($post->jumlah_bastb > $post->jumlah_barang_rev){
                    $nestedData['status_bastb'] = '<a class="btn btn-primary" title="BASTB: '.$post->jumlah_baphp.'/'.$post->jumlah_barang_rev.'">LEBIH</a>';
                }else{
                    $nestedData['status_bastb'] = '<a class="btn btn-danger" title="BASTB: '.$post->jumlah_baphp.'/'.$post->jumlah_barang_rev.'">KURANG</a>';
                }
                // $nestedData['status_baphp'] = ($post->jumlah_baphp > 0) ?  '<a class="btn btn-success">CUKUP</a>' : '<a class="btn btn-danger" title="BAPHP: '.$post->jumlah_baphp.'/'.$post->jumlah_barang_rev.'">KURANG</a>';
                // $nestedData['status_bastb'] = ($post->jumlah_bastb >= $post->jumlah_barang_rev) ?  '<a class="btn btn-success">CUKUP</a>' : '<a class="btn btn-danger" title="BASTB: '.$post->jumlah_bastb.'/'.$post->jumlah_barang_rev.'">KURANG</a>';
                if($nestedData['dinas'] == 'PROVINSI'){
                    $nestedData['status_bastb'] = '<a class="btn btn-warning"> - </a>';
                }
                
                $tools = '<div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                -- Pilih menu --
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                if(!in_array($this->session->userdata('logged_in')->role_pengguna,array('ADMIN KABUPATEN'))){// hide menu from admin provinsi and kabupaten
                    $tools .= '<a class="dropdown-item" href="'.base_url('Baphp_provinsi/index?id_alokasi_provinsi='.$post->id).'">BAPHP</a>';
                }
                if(!in_array($this->session->userdata('logged_in')->role_pengguna,array('ADMIN PENYEDIA PROVINSI','ADMIN PENYEDIA PROVINSI'))){// hide menu from admin penyedia
                    $tools .= '<a class="dropdown-item" href="'.base_url('Bastb_provinsi/index?id_alokasi_provinsi='.$post->id).'">BASTB</a>';
                }
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
		$kontrak_provinsi = $this->Kontrak_provinsi_model->Get($id);;
		$param['kontrak_provinsi'] = $kontrak_provinsi;

		$param['alokasi'] = array();
		$param['total_unit'] = $this->Kontrak_provinsi_model->GetTotalUnitAlokasi($kontrak_provinsi->id);
		$param['total_nilai'] = $this->Kontrak_provinsi_model->GetTotalNilaiAlokasi($kontrak_provinsi->id);

		$this->load->library('parser');
		$data = array(
	        'title' => 'Data Alokasi Kontrak Provinsi',
	        'content-path' => 'PENGADAAN PROVINSI / DATA ALOKASI KONTRAK',
	        'content' => $this->load->view('alokasi-provinsi/index', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

    public function get_json()
    {
		$id_kontrak_detail = $this->input->get('id_alokasi');

		$data = $this->Alokasi_provinsi_model->get($id_kontrak_detail);
		$hasil = json_encode($data);
		header('HTTP/1.1: 200');
		header('Status: 200');
		header('Content-Length: '.strlen($hasil));
		exit($hasil);
    }
    
    public function create()
	{
		$id_kontrak_provinsi = $this->input->get('id_kontrak_provinsi');
        $param['kontrak_provinsi'] = $this->Kontrak_provinsi_model->get($id_kontrak_provinsi);

		$this->load->library('parser');
		$this->load->model('ProvinsiModel');

		$param['provinsi'] = $this->ProvinsiModel->GetAll();

		$data = array(
	        'title' => 'Input Alokasi Kontrak Provinsi',
	        'content-path' => 'PENGADAAN PROVINSI / DATA ALOKASI / TAMBAH DATA',
	        'content' => $this->load->view('alokasi-provinsi/add', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function store()
	{	
        $id_kontrak_provinsi = $this->input->post('id_kontrak_provinsi');
        //cek unit limit
        if($this->input->post('status_alokasi') !== 'MENUNGGU KONFIRMASI')
        {
            switch($this->input->post('status_alokasi')){
                case 'DATA ADDENDUM 1':
                    $jumlah_unit = (float)str_replace(',', '', $this->input->post('jumlah_barang_rev_1'));
                    $jumlah_nilai = (float)str_replace(',', '', $this->input->post('nilai_barang_rev_1'));
                    break;
                case 'DATA ADDENDUM 2':
                    $jumlah_unit = (float)str_replace(',', '', $this->input->post('jumlah_barang_rev_2'));
                    $jumlah_nilai = (float)str_replace(',', '', $this->input->post('nilai_barang_rev_2'));
                    break;
                case 'DATA ADDENDUM 3':
                    $jumlah_unit = (float)str_replace(',', '', $this->input->post('jumlah_barang_rev_3'));
                    $jumlah_nilai = (float)str_replace(',', '', $this->input->post('nilai_barang_rev_3'));
                    break;
                default:
                    $jumlah_unit = (float)str_replace(',', '', $this->input->post('jumlah_barang'));
                    $jumlah_nilai = (float)str_replace(',', '', $this->input->post('nilai_barang'));
            }
            $total_unit = $this->Alokasi_provinsi_model->total_unit(null,$id_kontrak_provinsi);
            $total_nilai = $this->Alokasi_provinsi_model->total_nilai(null,$id_kontrak_provinsi);
            $kontrak_provinsi = $this->Kontrak_provinsi_model->get($id_kontrak_provinsi);
            if(($jumlah_unit+$total_unit > $kontrak_provinsi->jumlah_barang+0 || $jumlah_nilai+$total_nilai > $kontrak_provinsi->nilai_barang+0)){
                $this->session->set_flashdata('error','Jumlah atau nilai unit tidak dapat melebihi jumlah atau nilai unit kontrak.');
                redirect('Alokasi_provinsi/create?id_kontrak_provinsi='.$id_kontrak_provinsi);
            }
        }

		$id_provinsi = $this->input->post('id_provinsi');
		$id_kabupaten = $this->input->post('id_kabupaten');
		$jumlah_barang = (float)str_replace(',', '', $this->input->post('jumlah_barang'));
		$nilai_barang = (float)str_replace(',', '', $this->input->post('nilai_barang'));
		$regcad = $this->input->post('regcad');
		$dinas = $this->input->post('dinas');

		$no_adendum_1 = $this->input->post('no_adendum_1');
		$jumlah_barang_rev_1 = (float)str_replace(',', '', $this->input->post('jumlah_barang_rev_1'));
		$nilai_barang_rev_1 = (float)str_replace(',', '', $this->input->post('nilai_barang_rev_1'));

		$no_adendum_2 = $this->input->post('no_adendum_2');
		$jumlah_barang_rev_2 = (float)str_replace(',', '', $this->input->post('jumlah_barang_rev_2'));
		$nilai_barang_rev_2 = (float)str_replace(',', '', $this->input->post('nilai_barang_rev_2'));

		$no_adendum_3 = $this->input->post('no_adendum_3');
		$jumlah_barang_rev_3 = (float)str_replace(',', '', $this->input->post('jumlah_barang_rev_3'));
		$nilai_barang_rev_3 = (float)str_replace(',', '', $this->input->post('nilai_barang_rev_3'));

		$status_alokasi = $this->input->post('status_alokasi');

		if($id_kontrak_provinsi == '' or $id_provinsi == '' or $id_kabupaten == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('Alokasi_provinsi/create?id_kontrak_provinsi='.$id_kontrak_provinsi);
		}
		else{
			$target_file_1 = '';
			$target_file_2 = '';
			$target_file_3 = '';
			$nama_file_adendum_1 = '';
			$nama_file_adendum_2 = '';
			$nama_file_adendum_3 = '';

			if(file_exists($_FILES['nama_file_adendum_1']['tmp_name']) and is_uploaded_file($_FILES['nama_file_adendum_1']['tmp_name'])) {

		    	$target_file_1 = $this->config->item('doc_root').'/upload/kontrak_provinsi/alokasi/'.basename($_FILES["nama_file_adendum_1"]["name"]);

		    	$nama_file_adendum_1 = basename($_FILES["nama_file_adendum_1"]["name"]);
				if (file_exists($target_file_1)) {
				    $this->session->set_flashdata('error','Sorry. File already exists.');
		     		redirect('Alokasi_provinsi/create?id_kontrak_provinsi='.$id_kontrak_provinsi);
				}
				else{
					if(!move_uploaded_file($_FILES["nama_file_adendum_1"]["tmp_name"], $target_file_1)){
						$this->session->set_flashdata('error','Image failed to upload.');
	     				redirect('Alokasi_provinsi/create?id_kontrak_provinsi='.$id_kontrak_provinsi);
					}
				}
			}

			if(file_exists($_FILES['nama_file_adendum_2']['tmp_name']) and is_uploaded_file($_FILES['nama_file_adendum_2']['tmp_name'])) {

		    	$target_file_2 = $this->config->item('doc_root').'/upload/kontrak_provinsi/alokasi/'.basename($_FILES["nama_file_adendum_2"]["name"]);

		    	$nama_file_adendum_2 = basename($_FILES["nama_file_adendum_2"]["name"]);
				if (file_exists($target_file_2)) {
				    $this->session->set_flashdata('error','Sorry. File already exists.');
		     		redirect('Alokasi_provinsi/create?id_kontrak_provinsi='.$id_kontrak_provinsi);
				}
				else{
					if(!move_uploaded_file($_FILES["nama_file_adendum_2"]["tmp_name"], $target_file_2)){
						unlink($taget_file_1);
						$this->session->set_flashdata('error','Image failed to upload.');
	     				redirect('Alokasi_provinsi/create?id_kontrak_provinsi='.$id_kontrak_provinsi);
					}
				}
			}

			if(file_exists($_FILES['nama_file_adendum_3']['tmp_name']) and is_uploaded_file($_FILES['nama_file_adendum_3']['tmp_name'])) {

		    	$target_file_3 = $this->config->item('doc_root').'/upload/kontrak_provinsi/alokasi/'.basename($_FILES["nama_file_adendum_3"]["name"]);

		    	$nama_file_adendum_3 = basename($_FILES["nama_file_adendum_3"]["name"]);
				if (file_exists($target_file_3)) {
				    $this->session->set_flashdata('error','Sorry. File already exists.');
		     		redirect('Alokasi_provinsi/create?id_kontrak_provinsi='.$id_kontrak_provinsi);
				}
				else{
					if(!move_uploaded_file($_FILES["nama_file_adendum_3"]["tmp_name"], $target_file_2)){
						unlink($taget_file_1);
						$this->session->set_flashdata('error','Image failed to upload.');
	     				redirect('Alokasi_provinsi/create?id_kontrak_provinsi='.$id_kontrak_provinsi);
					}
				}
			}

			$data = array(
				'id_kontrak_provinsi' => $id_kontrak_provinsi,
				'id_provinsi' => $id_provinsi,
				'id_kabupaten' => $id_kabupaten,
				'jumlah_barang' => $jumlah_barang,
				'nilai_barang' => $nilai_barang,
				'regcad' => $regcad,
				'dinas' => $dinas,
				'no_adendum_1' => $no_adendum_1,
				'jumlah_barang_rev_1' => $jumlah_barang_rev_1,
				'nilai_barang_rev_1' => $nilai_barang_rev_1,
				'no_adendum_2' => $no_adendum_2,
				'jumlah_barang_rev_2' => $jumlah_barang_rev_2,
				'nilai_barang_rev_2' => $nilai_barang_rev_2,
				'no_adendum_3' => $no_adendum_3,
				'jumlah_barang_rev_3' => $jumlah_barang_rev_3,
				'nilai_barang_rev_3' => $nilai_barang_rev_3,
				'nama_file_adendum_1' => $nama_file_adendum_1,
				'nama_file_adendum_2' => $nama_file_adendum_2,
				'nama_file_adendum_3' => $nama_file_adendum_3,
				'status_alokasi' => $status_alokasi,
				'nama_file' => '',
				'created_by' => $this->session->userdata('logged_in')->id_pengguna,
				'created_at' => NOW,
			);
			$this->Alokasi_provinsi_model->store($data);
			$this->session->set_flashdata('info','Data inserted successfully.');
			redirect('Alokasi_provinsi/index?id_kontrak_provinsi='.$id_kontrak_provinsi);
		}
		
	}

	public function edit()
	{
		$id = $this->input->get('id');

		$alokasi = $this->Alokasi_provinsi_model->get($id);
		$param['alokasi_provinsi'] = $alokasi;
		$param['kontrak_provinsi'] = $this->Kontrak_provinsi_model->Get($alokasi->id_kontrak_provinsi);

		$this->load->library('parser');
		$this->load->model('ProvinsiModel');
		$param['provinsi'] = $this->ProvinsiModel->GetAll();

		$data = array(
	        'title' => 'Data Alokasi Kontrak Provinsi',
	        'content-path' => 'PENGADAAN PROVINSI / DATA ALOKASI / UBAH DATA',
	        'content' => $this->load->view('alokasi-provinsi/edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function update()
	{	
        $id = $this->input->post('id');
        $alokasi = $this->Alokasi_provinsi_model->get($id);
        $id_kontrak_provinsi = $this->input->post('id_kontrak_provinsi');
        
        //cek unit limit
        if($this->input->post('status_alokasi') !== 'MENUNGGU KONFIRMASI')
        {
            switch($this->input->post('status_alokasi')){
                case 'DATA ADDENDUM 1':
                    $jumlah_unit = (float)str_replace(',', '', $this->input->post('jumlah_barang_rev_1'));
                    $jumlah_nilai = (float)str_replace(',', '', $this->input->post('nilai_barang_rev_1'));
                    break;
                case 'DATA ADDENDUM 2':
                    $jumlah_unit = (float)str_replace(',', '', $this->input->post('jumlah_barang_rev_2'));
                    $jumlah_nilai = (float)str_replace(',', '', $this->input->post('nilai_barang_rev_2'));
                    break;
                case 'DATA ADDENDUM 3':
                    $jumlah_unit = (float)str_replace(',', '', $this->input->post('jumlah_barang_rev_3'));
                    $jumlah_nilai = (float)str_replace(',', '', $this->input->post('nilai_barang_rev_3'));
                    break;
                default:
                    $jumlah_unit = (float)str_replace(',', '', $this->input->post('jumlah_barang'));
                    $jumlah_nilai = (float)str_replace(',', '', $this->input->post('nilai_barang'));
            }
        }
        $total_unit = $this->Alokasi_provinsi_model->total_unit(null,$alokasi->id_kontrak_provinsi);
        $total_nilai = $this->Alokasi_provinsi_model->total_nilai(null,$alokasi->id_kontrak_provinsi);
        $kontrak_provinsi = $this->Kontrak_provinsi_model->get($alokasi->id_kontrak_provinsi);
        // var_dump($total_nilai);var_dump($jumlah_nilai);var_dump($alokasi->nilai_barang_rev);var_dump($kontrak_provinsi->nilai_barang);exit();
        if(($total_unit-$alokasi->jumlah_barang_rev+$jumlah_unit > $kontrak_provinsi->jumlah_barang+0) || $total_nilai-$alokasi->nilai_barang_rev+$jumlah_nilai > $kontrak_provinsi->nilai_barang+0){
            $this->session->set_flashdata('error','Jumlah atau nilai unit tidak dapat melebihi jumlah atau nilai unit kontrak.');
            redirect('Alokasi_provinsi/edit?id='.$id);
        }

        $id_kontrak_provinsi = $alokasi->id_kontrak_provinsi;

		$kontrak_provinsi = $this->Kontrak_provinsi_model->Get($id_kontrak_provinsi);
		$jumlah_barang_kontrak_provinsi = $kontrak_provinsi->jumlah_barang;
		$jumlah_alokasi = $this->Alokasi_provinsi_model->HitungJumlahAlokasiLainnya($id_kontrak_provinsi, $id);

		$id_provinsi = $this->input->post('id_provinsi');
		$id_kabupaten = $this->input->post('id_kabupaten');
		$jumlah_barang = (float)str_replace(',', '', $this->input->post('jumlah_barang'));
		$nilai_barang = (float)str_replace(',', '', $this->input->post('nilai_barang'));
		$regcad = $this->input->post('regcad');
		$dinas = $this->input->post('dinas');

		$no_adendum_1 = $this->input->post('no_adendum_1');
		$jumlah_barang_rev_1 = (float)str_replace(',', '', $this->input->post('jumlah_barang_rev_1'));
		$nilai_barang_rev_1 = (float)str_replace(',', '', $this->input->post('nilai_barang_rev_1'));

		$no_adendum_2 = $this->input->post('no_adendum_2');
		$jumlah_barang_rev_2 = (float)str_replace(',', '', $this->input->post('jumlah_barang_rev_2'));
		$nilai_barang_rev_2 = (float)str_replace(',', '', $this->input->post('nilai_barang_rev_2'));

		$no_adendum_3 = $this->input->post('no_adendum_3');
		$jumlah_barang_rev_3 = (float)str_replace(',', '', $this->input->post('jumlah_barang_rev_3'));
		$nilai_barang_rev_3 = (float)str_replace(',', '', $this->input->post('nilai_barang_rev_3'));

		$status_alokasi = $this->input->post('status_alokasi');
        
		if(empty($id_kontrak_provinsi) || empty($id_provinsi) || empty($id_kabupaten)){
            $this->session->set_flashdata('error','All fields must be filled.');
            redirect('Alokasi_provinsi/edit?id='.$id);
		}
		else{
			$data = array(
				'id_provinsi' => $id_provinsi,
				'id_kabupaten' => $id_kabupaten,
				'jumlah_barang' => $jumlah_barang,
				'nilai_barang' => $nilai_barang,
				'regcad' => $regcad,
				'dinas' => $dinas,
				'no_adendum_1' => $no_adendum_1,
				'jumlah_barang_rev_1' => $jumlah_barang_rev_1,
				'nilai_barang_rev_1' => $nilai_barang_rev_1,
				'no_adendum_2' => $no_adendum_2,
				'jumlah_barang_rev_2' => $jumlah_barang_rev_2,
				'nilai_barang_rev_2' => $nilai_barang_rev_2,
				'no_adendum_3' => $no_adendum_3,
				'jumlah_barang_rev_3' => $jumlah_barang_rev_3,
				'nilai_barang_rev_3' => $nilai_barang_rev_3,
				'status_alokasi' => $status_alokasi,
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->Alokasi_provinsi_model->update($id, $data);
			$this->session->set_flashdata('info','Data updated successfully.');
			redirect('Alokasi_provinsi/index?id_kontrak_provinsi='.$id_kontrak_provinsi);
		}
		
	}

	public function destroy($id)
	{	
		$alokasi = $this->Alokasi_provinsi_model->get($id);
		$id_kontrak_provinsi = $alokasi->id_kontrak_provinsi;

		if($alokasi->nama_file != '' and $alokasi->nama_file != 'null' and !is_null($alokasi->nama_file) and $alokasi->nama_file != '[]')
			$images = json_decode($alokasi->nama_file);
		else
			$images = [];

		foreach($images as $image){
			unlink($this->config->item('doc_root').'/upload/alokasi_provinsi/'.$image);	
		}

		$this->Alokasi_provinsi_model->destroy($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('Alokasi_provinsi/index?id_kontrak_provinsi='.$id_kontrak_provinsi);
		
	}

    public function export_confirmed()
    {
		$columns = array();
		foreach($this->colsc as $key=>$val){
			array_push($columns,$val['column']);
		}

        // $order = $columns[$this->input->post('order')[0]['column']];
		// $dir = $this->input->post('order')[0]['dir'];
		$order='id';
		$dir='ASC';
		$id_alokasi=$this->input->post('id_alokasi');
        $filtercond = '';
        for($i=0;$i<count($columns);$i++){
            if(!empty($this->input->post('columns')[$i]['search']['value'])){
                $search = $this->input->post('columns')[$i]['search']['value'];
                $filtercond  .= " and ".$columns[$i]." LIKE '%".$search."%'"; 
            }
        }

        $data = array();
        if(empty($this->input->post('search')['value'])){            
            $data = $this->Alokasi_provinsi_model->get_confirmed(null,null,null,null,$order, $dir, $filtercond);
        }
        else {
            $search = $this->input->post('search')['value']; 
            $data =  $this->Alokasi_provinsi_model->get_confirmed(null,$null,null,null, $order, $dir, $filtercond,$search);
        }

        // $visible_columns = $this->input->post('visible_columns');
        $visible_columns = $this->colsc; 
        $visible_header_columns = array();
        foreach($visible_columns as $value) {
            switch($value['caption']) {
				case 'Tahun Anggaran':
				case 'Nilai (Rp)': 
                case 'Harga Satuan (Rp)': 
					$visible_header_columns[$value['caption']] = '#,##0';
					break;
				default :
					$visible_header_columns[$value['caption']] = 'string';
			}
        }
        $visible_header_columns['Status BAPHP'] = 'string';		
        if(!in_array($this->session->userdata('logged_in')->role_pengguna,array('ADMIN PENYEDIA PROVINSI','ADMIN PENYEDIA PROVINSI'))){// hide menu from admin penyedia        
            $visible_header_columns['Status BASTB'] = 'string';		
        }
        $this->xlsxwriter->writeSheetHeader('Rekap Alokasi Provinsi', $visible_header_columns, array('font-style'=>'bold'));
        
        foreach($data as $row) {
            $newRow = array();
            foreach($visible_columns as $key => $value) {
                $defaultValue = '';
                if(isset($row->{$value['column']})) {
                    $defaultValue = $row->{$value['column']};
                }

                switch($value['column']) {
                    case 'harga_satuan_rev':
                        $newRow[$key] = (int) $defaultValue;
                        break;
                    default: 
                        $newRow[$key] = $defaultValue;
                }
            }
            if($row->jumlah_baphp == $row->jumlah_barang_rev){
                $newRow[$key+1] = 'CUKUP';
            }elseif($row->jumlah_baphp > $row->jumlah_barang_rev){
                $newRow[$key+1] = 'LEBIH';
            }else{
                $newRow[$key+1] = 'KURANG';
            }
            if(!in_array($this->session->userdata('logged_in')->role_pengguna,array('ADMIN PENYEDIA PROVINSI','ADMIN PENYEDIA PROVINSI'))){// hide menu from admin penyedia        
                if($row->jumlah_bastb == $row->jumlah_barang_rev){
                    $newRow[$key+2] = 'CUKUP';
                }elseif($row->jumlah_bastb > $row->jumlah_barang_rev){
                    $newRow[$key+2] = 'LEBIH';
                }else{
                    $newRow[$key+2] = 'KURANG';
                }            
            }
            $this->xlsxwriter->writeSheetRow('Rekap Alokasi Provinsi', $newRow);
        }
        
        $uniq_id = substr(md5(uniqid(rand(), true)), 0, 5);
        $file = "tmp_export/Rekap Alokasi Provinsi - $uniq_id.xlsx";
        $this->xlsxwriter->writeToFile($file);
        header('Content-Type: application/json');
        echo json_encode(array('filename' => base_url().'Alokasi_provinsi/download?filename='.$file));
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
		$id_alokasi = $this->input->post('id_alokasi');

		$alokasi = $this->Alokasi_provinsi_model->get($id_alokasi);

		$kodefile_upload = strtotime(NOW);

		if($alokasi->nama_file != '' and $alokasi->nama_file != 'null' and !is_null($alokasi->nama_file) and $alokasi->nama_file != '[]')
			$nama_file = json_decode($alokasi->nama_file);
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
		        $targetFile =  $target_file = $this->config->item('doc_root').'/upload/alokasi_provinsi/'.$kodefile_upload.basename($_FILES['file']['name'][$key]);
	        	move_uploaded_file($tempFile, $target_file);

		    }

		    $nama_file = json_encode($nama_file);
        	$data = array(
				'nama_file' => $nama_file,
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->Alokasi_provinsi_model->update($id_alokasi, $data);

			$this->session->set_flashdata('info','File uploaded successfully.');
			exit('success');
        }
    }
    
    public function remove_file()
    {
		$id_alokasi = $this->input->get('id_alokasi');
		$urutanfile = $this->input->get('urutanfile');

		$alokasi = $this->Alokasi_provinsi_model->get($id_alokasi);

		if($alokasi->nama_file != '' and $alokasi->nama_file != 'null' and !is_null($alokasi->nama_file) and $alokasi->nama_file != '[]')
			$images = json_decode($alokasi->nama_file);
		else
			$images = [];

		$nama_file = $images[$urutanfile];
		unlink($this->config->item('doc_root').'/upload/alokasi_provinsi/'.$nama_file);	

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
		$this->Alokasi_provinsi_model->update($id_alokasi, $data);

		$this->session->set_flashdata('info','File berhasil dihapus.');
		exit('success');
    }

    public function index_hibah_json($id_kontrak_provinsi = null)
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
            11 => 'regcad',
            12 => 'no_adendum_1',
            13 => 'nama_penyedia_provinsi',
            14 => 'id',
            15 => 'id',
        );
        
        $start = empty($this->input->post('start'))? 0:$this->input->post('start');
        $length = empty($this->input->post('length'))? null:$this->input->post('length');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $totalData = $this->Alokasi_provinsi_model->get();//TODO: create getcount
            
        $totalFiltered = $totalData; 
        //search data percolumn
        $filtercond = '';
        for($i=0;$i<count($columns);$i++){
            if($i<7 or $i>9 or $i!=12){
                if(!empty($this->input->post('columns')[$i]['search']['value'])){
                    $search = $this->input->post('columns')[$i]['search']['value'];
                    $filtercond  .= " and ".$columns[$i]." LIKE '%".$search."%'"; 
                }
            }
            else{
                if($i==7){
                    $search = $this->input->post('columns')[$i]['search']['value'];
                    $filtercond .= " and (
                            (CASE   
                            WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
                                dt.jumlah_barang_rev_1
                            WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
                                dt.jumlah_barang_rev_2
                            WHEN dt.status_alokasi = 'DATA KONTRAK AWAL' THEN
                                dt.jumlah_barang_detail
                            END) LIKE '%".$search."%'
                        )";
                }
                if($i==8){
                    $search = $this->input->post('columns')[$i]['search']['value'];
                    $filtercond .= " and (
                            (CASE   
                            WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
                                dt.nilai_barang_rev_1
                            WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
                                dt.nilai_barang_rev_2
                            WHEN dt.status_alokasi = 'DATA KONTRAK AWAL' THEN
                                dt.nilai_barang_detail
                            END) LIKE '%".$search."%'
                        )";
                }
                if($i==12){
                    $search = $this->input->post('columns')[$i]['search']['value'];
                    $filtercond .= " and (
                            (CASE   
                            WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
                                dt.no_adendum_1
                            WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
                                dt.no_adendum_2
                            WHEN dt.status_alokasi = 'DATA KONTRAK AWAL' THEN
                                hd.no_kontrak
                            END) LIKE '%".$search."%'
                        )";
                }
            }            
        }

        $posts_all_search =  $this->Alokasi_provinsi_model->get(null, $id_kontrak_provinsi, null, null, null, null, $filtercond);
        $totalFiltered = count($posts_all_search);
		$posts =  $this->Alokasi_provinsi_model->get(null, $id_kontrak_provinsi, $start, $length, $order, $dir, $filtercond);

        if(!empty($posts))
        {   
            foreach ($posts as $post)
            {
                $nestedData['id'] = $post->id;
                $nestedData['tahun_anggaran'] = $post->tahun_anggaran;
                $nestedData['no_kontrak'] = $post->no_kontrak;
                $nestedData['periode'] = date('d-m-Y', strtotime($post->periode_mulai)). " s/d ".date('d-m-Y', strtotime($post->periode_selesai));
                $nestedData['nama_barang'] = $post->nama_barang;
                $nestedData['merk'] = $post->merk;
                $nestedData['nama_provinsi'] = $post->nama_provinsi;
                $nestedData['nama_kabupaten'] = $post->nama_kabupaten;
                $nestedData['dinas'] = $post->dinas;
                $nestedData['regcad'] = $post->regcad;
                $nestedData['nama_penyedia'] = $post->nama_penyedia_provinsi;
                $nestedData['jumlah_barang'] = $post->jumlah_barang_rev;
                $nestedData['nilai_barang'] = number_format($post->nilai_barang_rev,0);
                $nestedData['harga_satuan'] = number_format($post->harga_satuan_rev,0);
                $nestedData['no_adendum'] = $post->no_adendum;

                // if($post->status_alokasi == 'DATA KONTRAK AWAL'){
                //     $nestedData['alokasi'] = number_format($post->jumlah_barang_detail, 0);
                //     $nestedData['nilai_barang'] = number_format($post->nilai_barang_detail, 0);
                //     if($post->jumlah_barang_detail == 0)
                //         $nestedData['harga_satuan'] = 0;
                //     else
                //        $nestedData['harga_satuan'] = number_format(($post->nilai_barang_detail/$post->jumlah_barang_detail), 0);
                //     $nestedData['no_adendum'] = '';
                // }
                // else if($post->status_alokasi == 'DATA ADENDUM 1'){
                //     $nestedData['alokasi'] = number_format($post->jumlah_barang_rev_1, 0);
                //     $nestedData['nilai_barang'] = number_format($post->nilai_barang_rev_1, 0);
                //     if($post->jumlah_barang_rev_1 == 0)
                //         $nestedData['harga_satuan'] = 0;
                //     else
                //         $nestedData['harga_satuan'] = number_format(($post->nilai_barang_rev_1/$post->jumlah_barang_rev_1), 0);
                    
                //     $nestedData['no_adendum'] = $post->no_adendum_1;
                // }
                // else if($post->status_alokasi == 'DATA ADENDUM 2'){
                //     $nestedData['alokasi'] = number_format($post->jumlah_barang_rev_2, 0);
                //     $nestedData['nilai_barang'] = number_format($post->nilai_barang_rev_2, 0);
                //     if($post->jumlah_barang_rev_2 == 0)
                //         $nestedData['harga_satuan'] = 0;
                //     else
                //         $nestedData['harga_satuan'] = number_format(($post->nilai_barang_rev_2/$post->jumlah_barang_rev_2), 0);
                    
                    
                //     $nestedData['no_adendum'] = $post->no_adendum_2;
                // }
                if($post->id_hibah_provinsi)
                    $nestedData['status_rilis'] = '<a class="btn btn-success">SUDAH</a>';
                else
                    $nestedData['status_rilis'] = '<a class="btn btn-danger">BELUM</a>';
                $nestedData['tools'] = "<input type='checkbox' id='chk_".$post->id."' disabled />";
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
	