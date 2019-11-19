<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap_kontrak_provinsi extends CI_Controller {
    function __construct()
    {
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('Kontrak_provinsi_model');
		$this->load->model('Alokasi_provinsi_model');
		$this->load->model('Baphp_provinsi_model');
		$this->load->model('Bastb_provinsi_model');
		$this->load->model('HibahProvinsiModel');
		$this->load->model('Ongkir_model');
		$this->load->model('Laporan_pemanfaatan_provinsi_model');
		$this->load->model('Rekap_kontrak_provinsi_model');
        $this->load->helper('url');
		$this->load->library('xlsxwriter');
	}
    public function index(){
		$this->rekap();
	}
	
	public function rekap()
    {
		$param['cols'] = array(
			array("column" => "no_kontrak", "caption" => "Nomor Kontrak", "dbcolumn" => "no_kontrak"),			
			array("column" => "periode", "caption" => "Periode", "dbcolumn" => "periode_mulai"),			
			array("column" => "nama_barang", "caption" => "Nama Barang", "dbcolumn" => "nama_barang"),			
			array("column" => "merk", "caption" => "Merk", "dbcolumn" => "merk"),			
			array("column" => "nama_penyedia_provinsi", "caption" => "Penyedia", "dbcolumn" => "nama_penyedia_provinsi"),			
			array("column" => "jumlah_barang", "caption" => "Unit", "dbcolumn" => "jumlah_barang"),			
			array("column" => "nilai_barang", "caption" => "Nilai(Rp)", "dbcolumn" => "nilai_barang"),			
			array("column" => "nama_provinsi", "caption" => "Provinsi", "dbcolumn" => "nama_provinsi"),			
			array("column" => "nama_kabupaten", "caption" => "Kabupaten/Kota", "dbcolumn" => "nama_kabupaten"),			
			array("column" => "no_bapb", "caption" => "No. BAPB", "dbcolumn" => "no_bapb"),	
			array("column" => "tanggal_bapb", "caption" => "Tanggal BAPB", "dbcolumn" => "bapb.tanggal"),	
			array("column" => "no_baphp_reguler", "caption" => "No. Baphp Reguler", "dbcolumn" => "baphp_reguler.no_baphp"),	
			array("column" => "tanggal_baphp_reguler", "caption" => "Tanggal Baphp Reguler", "dbcolumn" => "baphp_reguler.tanggal"),	
			array("column" => "no_batitip", "caption" => "No. BA Penitipan Barang", "dbcolumn" => "no_batitip"),	
			array("column" => "tanggal_batitip", "caption" => "Tanggal BA Penitipan Barang", "dbcolumn" => "tanggal_batitip"),	
			array("column" => "no_bart", "caption" => "No. BART", "dbcolumn" => "no_bart"),	
			array("column" => "tanggal_bart", "caption" => "Tanggal BART", "dbcolumn" => "tanggal_bart"),	
			array("column" => "jumlah_barang_rev", "caption" => "Jumlah Unit", "dbcolumn" => "jumlah_barang_rev"),	
			array("column" => "nilai_barang_rev", "caption" => "Nilai(Rp)", "dbcolumn" => "nilai_barang_rev"),	
			array("column" => "no_rangka", "caption" => "No. Rangka", "dbcolumn" => "no_rangka"),	
			array("column" => "no_mesin", "caption" => "No. Mesin", "dbcolumn" => "no_mesin"),	
			array("column" => "no_sp2d", "caption" => "No. SP2D", "dbcolumn" => "no_sp2d"),	
			array("column" => "tanggal_sp2d", "caption" => "Tanggal SP2D", "dbcolumn" => "sp2d.tanggal"),	
			array("column" => "no_spm", "caption" => "No. SPM", "dbcolumn" => "no_spm"),	
			array("column" => "tanggal_spm", "caption" => "Tanggal SPM", "dbcolumn" => "tanggal_spm"),	
			array("column" => "no_bastb", "caption" => "No. Bastb", "dbcolumn" => "no_bastb"),	
			array("column" => "tanggal_bastb", "caption" => "Tanggal Bastb", "dbcolumn" => "bastb.tanggal"),	
			array("column" => "kelompok_penerima", "caption" => "Kelompok Penerima", "dbcolumn" => "kelompok_penerima"),	
			array("column" => "pihak_penerima", "caption" => "Pihak Yang Menerima", "dbcolumn" => "pihak_penerima"),	
			array("column" => "nama_penerima", "caption" => "Nama Yang Menerima", "dbcolumn" => "nama_penerima"),	
			array("column" => "nik_penerima", "caption" => "NIK", "dbcolumn" => "nik_penerima"),	
			array("column" => "kecamatan_penerima", "caption" => "Kecamatan", "dbcolumn" => "kecamatan_penerima"),	
			array("column" => "kelurahan_penerima", "caption" => "Kelurahan", "dbcolumn" => "kelurahan_penerima"),	
			array("column" => "notelp_penerima", "caption" => "No. Telp", "dbcolumn" => "notelp_penerima"),
		);
        $this->load->library('parser');
		$id_kontrak_provinsi = $this->input->get('id_kontrak_provinsi');
		$param['kontrak_provinsi'] = $this->Kontrak_provinsi_model->get($id_kontrak_provinsi);
		$param['total_unit'] = $this->Alokasi_provinsi_model->total_unit(null, $id_kontrak_provinsi);
        $param['total_nilai'] = $this->Alokasi_provinsi_model->total_nilai(null, $id_kontrak_provinsi);

        $data = array(
			'title' => 'Rekap Kontrak Provinsi',
            'content-path' => strtoupper('Rekap Kontrak Provinsi'),
            'content' => $this->load->view('rekap-kontrak-provinsi/rekap', $param, TRUE),
        );
        $this->parser->parse('default_template', $data);
	}

    public function rekap_json()
    { 
		$cols = array(
			array("column" => "no_kontrak", "caption" => "Nomor Kontrak", "dbcolumn" => "no_kontrak"),			
			array("column" => "periode", "caption" => "Periode", "dbcolumn" => "periode_mulai"),			
			array("column" => "nama_barang", "caption" => "Nama Barang", "dbcolumn" => "nama_barang"),			
			array("column" => "merk", "caption" => "Merk", "dbcolumn" => "merk"),			
			array("column" => "nama_penyedia_provinsi", "caption" => "Penyedia", "dbcolumn" => "nama_penyedia_provinsi"),			
			array("column" => "jumlah_barang", "caption" => "Unit", "dbcolumn" => "jumlah_barang"),			
			array("column" => "nilai_barang", "caption" => "Nilai(Rp)", "dbcolumn" => "nilai_barang"),			
			array("column" => "nama_provinsi", "caption" => "Provinsi", "dbcolumn" => "nama_provinsi"),			
			array("column" => "nama_kabupaten", "caption" => "Kabupaten/Kota", "dbcolumn" => "nama_kabupaten"),			
			array("column" => "no_bapb", "caption" => "No. BAPB", "dbcolumn" => "no_bapb"),	
			array("column" => "tanggal_bapb", "caption" => "Tanggal BAPB", "dbcolumn" => "bapb.tanggal"),	
			array("column" => "no_baphp_reguler", "caption" => "No. Baphp Reguler", "dbcolumn" => "baphp_reguler.no_baphp"),	
			array("column" => "tanggal_baphp_reguler", "caption" => "Tanggal Baphp Reguler", "dbcolumn" => "baphp_reguler.tanggal"),	
			array("column" => "no_batitip", "caption" => "No. BA Penitipan Barang", "dbcolumn" => "no_batitip"),	
			array("column" => "tanggal_batitip", "caption" => "Tanggal BA Penitipan Barang", "dbcolumn" => "tanggal_batitip"),	
			array("column" => "no_bart", "caption" => "No. BART", "dbcolumn" => "no_bart"),	
			array("column" => "tanggal_bart", "caption" => "Tanggal BART", "dbcolumn" => "tanggal_bart"),	
			array("column" => "jumlah_barang_rev", "caption" => "Jumlah Unit", "dbcolumn" => "jumlah_barang_rev"),	
			array("column" => "nilai_barang_rev", "caption" => "Nilai(Rp)", "dbcolumn" => "nilai_barang_rev"),	
			array("column" => "no_rangka", "caption" => "No. Rangka", "dbcolumn" => "no_rangka"),	
			array("column" => "no_mesin", "caption" => "No. Mesin", "dbcolumn" => "no_mesin"),	
			array("column" => "no_sp2d", "caption" => "No. SP2D", "dbcolumn" => "no_sp2d"),	
			array("column" => "tanggal_sp2d", "caption" => "Tanggal SP2D", "dbcolumn" => "sp2d.tanggal"),	
			array("column" => "no_spm", "caption" => "No. SPM", "dbcolumn" => "no_spm"),	
			array("column" => "tanggal_spm", "caption" => "Tanggal SPM", "dbcolumn" => "tanggal_spm"),	
			array("column" => "no_bastb", "caption" => "No. Bastb", "dbcolumn" => "no_bastb"),	
			array("column" => "tanggal_bastb", "caption" => "Tanggal Bastb", "dbcolumn" => "bastb.tanggal"),	
			array("column" => "kelompok_penerima", "caption" => "Kelompok Penerima", "dbcolumn" => "kelompok_penerima"),	
			array("column" => "pihak_penerima", "caption" => "Pihak Yang Menerima", "dbcolumn" => "pihak_penerima"),	
			array("column" => "nama_penerima", "caption" => "Nama Yang Menerima", "dbcolumn" => "nama_penerima"),	
			array("column" => "nik_penerima", "caption" => "NIK", "dbcolumn" => "nik_penerima"),	
			array("column" => "kecamatan_penerima", "caption" => "Kecamatan", "dbcolumn" => "kecamatan_penerima"),	
			array("column" => "kelurahan_penerima", "caption" => "Kelurahan", "dbcolumn" => "kelurahan_penerima"),	
			array("column" => "notelp_penerima", "caption" => "No. Telp", "dbcolumn" => "notelp_penerima"),
		);

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
        
		$id_kontrak_provinsi = $this->input->post('id_kontrak_provinsi');
        $start = empty($this->input->post('start'))? 0:$this->input->post('start');
        $length = empty($this->input->post('length'))? null:$this->input->post('length');
        $order = empty($this->input->post('order')[0]['column'])? null:$this->input->post('order')[0]['column'];
        $dir = empty($this->input->post('order')[0]['dir'])? null:$this->input->post('order')[0]['dir'];
        
		$columns = array();
		foreach($cols as $key=>$val){
			array_push($columns,$val['column']);
		}

		$dbcolumns = array();
		foreach($cols as $key=>$val){
			array_push($dbcolumns,$val['dbcolumn']);
		}
     
        $totalData = $this->Rekap_kontrak_provinsi_model->get(null,array('id_kontrak_provinsi'=>$id_kontrak_provinsi));
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
		$posts_all_search =  $this->Rekap_kontrak_provinsi_model->get(null,array('id_kontrak_provinsi'=>$id_kontrak_provinsi, 'filter'=>$filtercond, 'search'=>$search));
        $totalFiltered = count($posts_all_search);
		$posts =  $this->Rekap_kontrak_provinsi_model->get(null,array('id_kontrak_provinsi'=>$id_kontrak_provinsi, 'start'=>$start, 'length'=>$length, 'order'=>$order, 'dir'=>$dir, 'filter'=>$filtercond, 'search'=>$search));

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();
				foreach($cols as $val){
					$nestedData[$val['column']] = $post->{$val['column']};
				}
				$nestedData['jumlah_barang_rev'] = number_format($nestedData['jumlah_barang_rev'],0);
				$nestedData['nilai_barang'] = number_format($nestedData['nilai_barang'],0); 
				$nestedData['nilai_barang_rev'] = number_format($nestedData['nilai_barang_rev'],0);

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
	
	public function pemanfaatan()
    {
		$param['cols'] = array(
			array("column" => "nama_provinsi", "caption" => "Provinsi Yang Menerima", "dbcolumn" => "nama_provinsi"),
			array("column" => "nama_kabupaten", "caption" => "Kabupaten Yang Menerima", "dbcolumn" => "nama_kabupaten"),
			array("column" => "pihak_penerima", "caption" => "Pihak Yang Menerima", "dbcolumn" => "periode_mulai"),
			array("column" => "no_bastb", "caption" => "No BASTB", "dbcolumn" => "no_bastb"),
			array("column" => "periode_mulai", "caption" => "Periode Tanggal Mulai Digunakan", "dbcolumn" => "periode_mulai"),
			array("column" => "total_area", "caption" => "Total Area Yang Dikerjakan (Ha)", "dbcolumn" => "total_area"),
			array("column" => "kondisi", "caption" => "Kondisi Alsintan Saat Ini", "dbcolumn" => "kondisi"),
			array("column" => "perawatan", "caption" => "Perawatan Yang Telah Dilakukan", "dbcolumn" => "perawatan"),
			array("column" => "keterangan", "caption" => "Keterangan", "dbcolumn" => "keterangan"),
			array("column" => "tanggal_laporan", "caption" => "Tanggal Kirim Laporan", "dbcolumn" => "tanggal_laporan"),
		);

		$this->load->library('parser');
		$id_kontrak_provinsi = $this->input->get('id_kontrak_provinsi');
		$param['kontrak_provinsi'] = $this->Kontrak_provinsi_model->get($id_kontrak_provinsi);
		$param['total_unit'] = $this->Alokasi_provinsi_model->total_unit(null, $id_kontrak_provinsi);
        $param['total_nilai'] = $this->Alokasi_provinsi_model->total_nilai(null, $id_kontrak_provinsi);
		
		$data = array(
			'title' => 'Rekap Kontrak Provinsi',
            'content-path' => strtoupper('Rekap Kontrak Provinsi'),
            'content' => $this->load->view('rekap-kontrak-provinsi/pemanfaatan', $param, TRUE),
        );
        $this->parser->parse('default_template', $data);
	}
	
	public function pemanfaatan_json()
    {
		$cols = array(
			array("column" => "nama_provinsi", "caption" => "Provinsi Yang Menerima", "dbcolumn" => "nama_provinsi"),
			array("column" => "nama_kabupaten", "caption" => "Kabupaten Yang Menerima", "dbcolumn" => "nama_kabupaten"),
			array("column" => "pihak_penerima", "caption" => "Pihak Yang Menerima", "dbcolumn" => "periode_mulai"),
			array("column" => "no_bastb", "caption" => "No BASTB", "dbcolumn" => "no_bastb"),
			array("column" => "periode_mulai", "caption" => "Periode Tanggal Mulai Digunakan", "dbcolumn" => "periode_mulai"),
			array("column" => "total_area", "caption" => "Total Area Yang Dikerjakan (Ha)", "dbcolumn" => "total_area"),
			array("column" => "kondisi", "caption" => "Kondisi Alsintan Saat Ini", "dbcolumn" => "kondisi"),
			array("column" => "perawatan", "caption" => "Perawatan Yang Telah Dilakukan", "dbcolumn" => "perawatan"),
			array("column" => "keterangan", "caption" => "Keterangan", "dbcolumn" => "keterangan"),
			array("column" => "tanggal_laporan", "caption" => "Tanggal Kirim Laporan", "dbcolumn" => "tanggal_laporan"),
		);

		$id_kontrak_provinsi = empty($this->input->post('start'))? 0:$this->input->post('id_kontrak_provinsi');
		$start = empty($this->input->post('start'))? 0:$this->input->post('start');
        $length = empty($this->input->post('length'))? null:$this->input->post('length');
        $order = empty($this->input->post('order')[0]['column'])? null:$this->input->post('order')[0]['column'];
        $dir = empty($this->input->post('order')[0]['dir'])? null:$this->input->post('order')[0]['dir'];
        
		$columns = array();
		foreach($cols as $key=>$val){
			array_push($columns,$val['column']);
		}

		$dbcolumns = array();
		foreach($cols as $key=>$val){
			array_push($dbcolumns,$val['dbcolumn']);
		}
     
        $totalData = $this->Laporan_pemanfaatan_provinsi_model->get(null,array('id_kontrak_provinsi'=>$id_kontrak_provinsi));
        $totalData = count($totalData);
            
        $totalFiltered = $totalData; 

        //search data percolumn
        $filtercond = '';
        for($i=0;$i<count($columns);$i++){
            if(!empty($this->input->post('columns')[$i]['search']['value'])){
                $search = $this->input->post('columns')[$i]['search']['value'];
                $filtercond  .= " and ".$dbcolumns[$i]." LIKE '%".$search."%'"; 
            }
        }

		$search = $this->input->post('search')['value']; 
		$posts_all_search =  $this->Laporan_pemanfaatan_provinsi_model->get(null, array('id_kontrak_provinsi'=>$id_kontrak_provinsi,'filter'=>$filtercond, 'search'=>$search));
        $totalFiltered = count($posts_all_search);
		$posts =  $this->Laporan_pemanfaatan_provinsi_model->get(null, array('id_kontrak_provinsi'=>$id_kontrak_provinsi, 'start'=>$start, 'length'=>$length, 'order'=>$order, 'dir'=>$dir, 'filter'=>$filtercond, 'search'=>$search));

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();
				foreach($cols as $val){
					$nestedData[$val['column']] = $post->{$val['column']};
				}
				$nestedData['periode_mulai'] = date('d-m-Y', strtotime($nestedData['periode_mulai']));
				$nestedData['tanggal_laporan'] = date('d-m-Y', strtotime($nestedData['tanggal_laporan']));

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

	public function downloaddoc(){
		$id_kontrak_provinsi = $this->input->get('id_kontrak_provinsi');
		// create the zip
		$zip = new ZipArchive;
		$zipname = 'BASTB_Kontrak_'.$id_kontrak_provinsi.'.zip';
		$fullzippath = $this->config->item('doc_root').'/upload/tmp_kontrak_download/'.$zipname;
		$zip->open($this->config->item('doc_root').'/upload/tmp_kontrak_download/'.$zipname, ZipArchive::CREATE);
		// add kontrak
		$dir = 'kontrak_provinsi';// need to be same with upload folder name
		$zip->addEmptyDir($dir);
		$data = $this->Kontrak_provinsi_model->get($id_kontrak_provinsi);
		if(!empty($data->nama_file)){
			$files = json_decode($data->nama_file);
			foreach ((array)$files as $file) {
				$zip->addFile($this->config->item('doc_root').'/upload/'.$dir.'/'.$file,$dir.'/'.$file);
			}
		}
		// add alokasi
		$dir = 'alokasi_provinsi';// need to be same with upload folder name
		$zip->addEmptyDir($dir);
		$datas = $this->Alokasi_provinsi_model->get(null,$id_kontrak_provinsi);
		foreach((array)$datas as $data){			
			$files = json_decode($data->nama_file);
			foreach ((array)$files as $file) {
				$zip->addFile($this->config->item('doc_root').'/upload/'.$dir.'/'.$file,$dir.'/'.$file);
			}
		}
		// add baphp
		$dir = 'baphp_provinsi';// need to be same with upload folder name
		$zip->addEmptyDir($dir);
		$datas = $this->Baphp_provinsi_model->getFile(array('id_kontrak_provinsi'=>$id_kontrak_provinsi));
		foreach((array)$datas as $data){			
			$files = json_decode($data->nama_file);
			foreach ((array)$files as $file) {
				$zip->addFile($this->config->item('doc_root').'/upload/'.$dir.'/'.$file,$dir.'/'.$file);
			}
			$files = json_decode($data->nama_filefoto);
			foreach ((array)$files as $file) {
				$zip->addFile($this->config->item('doc_root').'/upload/'.$dir.'/'.$file,$dir.'/'.$file);
			}
		}
		// add bastb_provinsi
		$dir = 'bastb_provinsi';// need to be same with upload folder name
		$zip->addEmptyDir($dir);
		$datas = $this->Bastb_provinsi_model->getFile(array('id_kontrak_provinsi'=>$id_kontrak_provinsi));
		foreach((array)$datas as $data){			
			$files = json_decode($data->nama_file);
			foreach ((array)$files as $file) {
				$zip->addFile($this->config->item('doc_root').'/upload/'.$dir.'/'.$file,$dir.'/'.$file);
			}
			$files = json_decode($data->nama_filefoto);
			foreach ((array)$files as $file) {
				$zip->addFile($this->config->item('doc_root').'/upload/'.$dir.'/'.$file,$dir.'/'.$file);
			}
		}
		//
		$zip->close();
		// var_dump($zip);exit();
		header('Content-Type: application/zip');
		header('Content-disposition: attachment; filename='.$zipname);
		header('Content-Length: ' . filesize($fullzippath));
		readfile($fullzippath);
		unlink($fullzippath);
	}
}
	