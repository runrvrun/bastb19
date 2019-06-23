<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AlokasiPusat extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('AlokasiPusatModel');
        $this->load->model('KontrakPusatModel');
        $this->load->helper('url');
		$this->load->library('xlsxwriter');
	}

	public function index()
	{
		$this->load->library('parser');
		$param['alokasi_pusat'] = $this->AlokasiPusatModel->GetAll();

		$param['total_unit'] = $this->AlokasiPusatModel->GetTotalUnit();
		$param['total_nilai'] = $this->AlokasiPusatModel->GetTotalNilai();
		$param['total_kontrak'] = $this->AlokasiPusatModel->GetTotalKontrak();
		$param['total_merk'] = $this->AlokasiPusatModel->GetTotalMerk();

		$param['total_unit_kontrak'] = $this->KontrakPusatModel->GetTotalUnit();
		$param['total_nilai_kontrak'] = $this->KontrakPusatModel->GetTotalNilai();

		$data = array(
	        'title' => 'Data Alokasi Pusat',
	        'content-path' => 'PENGADAAN PUSAT / ALOKASI',
	        'content' => $this->load->view('alokasi-pusat-index', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function AjaxGetAllData()
	{


		$columns = array( 
            0 =>'tahun_anggaran', 
            1 =>'no_kontrak',
            2 => 'periode_mulai',
            3 => 'hd.nama_barang',
            4 => 'hd.merk',
            5 => 'nama_provinsi',
            6 => 'nama_kabupaten',
            7 => 'jumlah_barang_detail',
            8 => 'nilai_barang_detail',
            9 => 'harga_satuan',
            10 => 'dinas',
            11 => 'regcad',
            12 => 'no_adendum_1',
            13 => 'nama_penyedia_pusat',
            14 => 'id',
        );

        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

		$totalData = $this->AlokasiPusatModel->GetAllAjaxCount();
            
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

		if(empty($this->input->post('search')['value']))
        {            
            $posts = $this->AlokasiPusatModel->GetAllForAjax($_POST['start'], $_POST['length'], $order, $dir, $filtercond);
            $totalFiltered = $this->AlokasiPusatModel->GetFilterAjaxCount($filtercond);
        }
        else {
            $search = $this->input->post('search')['value']; 

            $posts =  $this->AlokasiPusatModel->GetSearchAjax($_POST['start'], $_POST['length'], $search, $order, $dir, $filtercond);

            $totalFiltered = $this->AlokasiPusatModel->GetSearchAjaxCount($search, $filtercond);
        }

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
                $nestedData['regcad'] = $post->regcad;

                $nestedData['nama_penyedia'] = $post->nama_penyedia_pusat;

                if($post->status_alokasi == 'DATA KONTRAK AWAL'){
	                $nestedData['alokasi'] = number_format($post->jumlah_barang_detail, 0);
	                $nestedData['nilai_barang'] = number_format($post->nilai_barang_detail, 0);

                    if($post->jumlah_barang_detail == 0)
                        $nestedData['harga_satuan'] = 0;
                    else
	                   $nestedData['harga_satuan'] = number_format(($post->nilai_barang_detail/$post->jumlah_barang_detail), 0);

	                $nestedData['no_adendum'] = '';
                }
                else if($post->status_alokasi == 'DATA ADENDUM 1'){
	            	$nestedData['alokasi'] = number_format($post->jumlah_barang_rev_1, 0);
	                $nestedData['nilai_barang'] = number_format($post->nilai_barang_rev_1, 0);

                    if($post->jumlah_barang_rev_1 == 0)
                        $nestedData['harga_satuan'] = 0;
                    else
                        $nestedData['harga_satuan'] = number_format(($post->nilai_barang_rev_1/$post->jumlah_barang_rev_1), 0);
	                
	                $nestedData['no_adendum'] = $post->no_adendum_1;
                }
                else if($post->status_alokasi == 'DATA ADENDUM 2'){
                	$nestedData['alokasi'] = number_format($post->jumlah_barang_rev_2, 0);
	                $nestedData['nilai_barang'] = number_format($post->nilai_barang_rev_2, 0);

                    if($post->jumlah_barang_rev_2 == 0)
                        $nestedData['harga_satuan'] = 0;
                    else
                        $nestedData['harga_satuan'] = number_format(($post->nilai_barang_rev_2/$post->jumlah_barang_rev_2), 0);
                    
	                
	                $nestedData['no_adendum'] = $post->no_adendum_2;
                }

                $nestedData['tools'] = "<a class='btn btn-xs btn-success btn-sm'><i class='glyphicon glyphicon-zoom-in' onclick='LoadData(".$post->id.")'></i></a>";

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

	public function GetData()
	{
		$id_kontrak_detail = $this->input->get('id_alokasi');

		$data = $this->AlokasiPusatModel->GetData($id_kontrak_detail);
		$hasil = json_encode($data);
		header('HTTP/1.1: 200');
		header('Status: 200');
		header('Content-Length: '.strlen($hasil));
		exit($hasil);
	}

    public function AjaxGetAllDataForHibah()
    {


        $columns = array( 
            0 =>'tahun_anggaran', 
            1 =>'no_kontrak',
            2 => 'periode_mulai',
            3 => 'hd.nama_barang',
            4 => 'hd.merk',
            5 => 'nama_provinsi',
            6 => 'nama_kabupaten',
            7 => 'jumlah_barang_detail',
            8 => 'nilai_barang_detail',
            9 => 'harga_satuan',
            10 => 'dinas',
            11 => 'regcad',
            12 => 'no_adendum_1',
            13 => 'nama_penyedia_pusat',
            14 => 'id',
            15 => 'id',
        );

        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $totalData = $this->AlokasiPusatModel->GetAllAjaxCount();
            
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

        if(empty($this->input->post('search')['value']))
        {            
            $posts = $this->AlokasiPusatModel->GetAllForAjax($_POST['start'], $_POST['length'], $order, $dir, $filtercond);
            $totalFiltered = $this->AlokasiPusatModel->GetFilterAjaxCount($filtercond);
        }
        else {
            $search = $this->input->post('search')['value']; 

            $posts =  $this->AlokasiPusatModel->GetSearchAjax($_POST['start'], $_POST['length'], $search, $order, $dir, $filtercond);

            $totalFiltered = $this->AlokasiPusatModel->GetSearchAjaxCount($search, $filtercond);
        }

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

                $nestedData['nama_penyedia'] = $post->nama_penyedia_pusat;

                if($post->status_alokasi == 'DATA KONTRAK AWAL'){
                    $nestedData['alokasi'] = number_format($post->jumlah_barang_detail, 0);
                    $nestedData['nilai_barang'] = number_format($post->nilai_barang_detail, 0);

                    if($post->jumlah_barang_detail == 0)
                        $nestedData['harga_satuan'] = 0;
                    else
                       $nestedData['harga_satuan'] = number_format(($post->nilai_barang_detail/$post->jumlah_barang_detail), 0);

                    $nestedData['no_adendum'] = '';
                }
                else if($post->status_alokasi == 'DATA ADENDUM 1'){
                    $nestedData['alokasi'] = number_format($post->jumlah_barang_rev_1, 0);
                    $nestedData['nilai_barang'] = number_format($post->nilai_barang_rev_1, 0);

                    if($post->jumlah_barang_rev_1 == 0)
                        $nestedData['harga_satuan'] = 0;
                    else
                        $nestedData['harga_satuan'] = number_format(($post->nilai_barang_rev_1/$post->jumlah_barang_rev_1), 0);
                    
                    $nestedData['no_adendum'] = $post->no_adendum_1;
                }
                else if($post->status_alokasi == 'DATA ADENDUM 2'){
                    $nestedData['alokasi'] = number_format($post->jumlah_barang_rev_2, 0);
                    $nestedData['nilai_barang'] = number_format($post->nilai_barang_rev_2, 0);

                    if($post->jumlah_barang_rev_2 == 0)
                        $nestedData['harga_satuan'] = 0;
                    else
                        $nestedData['harga_satuan'] = number_format(($post->nilai_barang_rev_2/$post->jumlah_barang_rev_2), 0);
                    
                    
                    $nestedData['no_adendum'] = $post->no_adendum_2;
                }

                if($post->id_hibah_pusat)
                    $nestedData['status_rilis'] = 'SUDAH';
                else
                    $nestedData['status_rilis'] = 'BELUM';

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

    public function doExport() {
        $columns = array( 
            0 =>'tahun_anggaran', 
            1 =>'no_kontrak',
            2 => 'periode_mulai',
            3 => 'hd.nama_barang',
            4 => 'hd.merk',
            5 => 'nama_provinsi',
            6 => 'nama_kabupaten',
            7 => 'jumlah_barang_detail',
            8 => 'nilai_barang_detail',
            9 => 'harga_satuan',
            10 => 'dinas',
            11 => 'regcad',
            12 => 'no_adendum_1',
            13 => 'nama_penyedia_pusat',
            14 => 'id',
            15 => 'id'
        );

        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
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

        $data = array();
        if(empty($this->input->post('search')['value'])){            
            $data = $this->AlokasiPusatModel->ExportAllForAjax($order, $dir, $filtercond);
        }
        else {
            $search = $this->input->post('search')['value']; 
            $data =  $this->AlokasiPusatModel->ExportSearchAjax($search, $order, $dir, $filtercond);
        }

        $visible_columns = $this->input->post('visible_columns');
        $visible_header_columns = array();
        foreach($visible_columns as $value) {
            switch($value['title']) {
				case 'Tahun Anggaran':
				case 'Alokasi': 
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
        $this->xlsxwriter->writeSheetHeader('Alokasi Pusat', $visible_header_columns, array('font-style'=>'bold'));
        
        foreach($data as $row) {
            $newRow = array();
            foreach($visible_columns as $key => $value) {
                $defaultValue = '';
                if(isset($row[$value['id']])) {
                    $defaultValue = $row[$value['id']];
                }

                $jumlahBarang = 0;
                $nilaiBarang = 0;
                $noAdendum = '';
                $hargaSatuan = 0;

                if($row['status_alokasi'] == 'DATA KONTRAK AWAL'){
                    $jumlahBarang = $row['jumlah_barang_detail'];
                    $nilaiBarang = $row['nilai_barang_detail'];
                    $noAdendum = '';
                }
                else if($row['status_alokasi'] == 'DATA ADENDUM 1'){
                    $jumlahBarang = $row['jumlah_barang_rev_1'];
                    $nilaiBarang = $row['nilai_barang_rev_1'];
                    $noAdendum = $row['no_adendum_1'];
                }
                else if($row['status_alokasi'] == 'DATA ADENDUM 2'){
                    $jumlahBarang = $row['jumlah_barang_rev_2'];
                    $nilaiBarang = $row['nilai_barang_rev_2'];
                    $noAdendum = $row['no_adendum_2'];
                }

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
                    case 'alokasi': 
                        $newRow[$key] = $jumlahBarang;
                        break;
                    case 'nilai_barang': 
                        $newRow[$key] = $nilaiBarang;
                        break;
                    case 'harga_satuan': 
                        $newRow[$key] = $hargaSatuan;
                        break;
                    case 'no_adendum': 
                        $newRow[$key] = $noAdendum;
                        break;
                    case 'nama_penyedia': 
                        $newRow[$key] = $row['nama_penyedia_pusat'];
                        break;
                    default: 
                        $newRow[$key] = $defaultValue;
                }
            }
            $this->xlsxwriter->writeSheetRow('Alokasi Pusat', $newRow);
        }
        
        $uniq_id = substr(md5(uniqid(rand(), true)), 0, 5);
        $file = "upload/BASTB App Data Alokasi Pusat - $uniq_id.xlsx";
        $this->xlsxwriter->writeToFile($file);

        header('Content-Type: application/json');
        echo json_encode(array('filename' => base_url().'AlokasiPusat/doDownload?filename='.$file));
    }

    public function doDownload() {
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

}
	