<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanKontrakPusat extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('LaporanPusatModel');
		$this->load->model('TahunPengadaanModel');
		$this->load->model('ProvinsiModel');
		$this->load->model('KabupatenModel');
		$this->load->model('PenyediaPusatModel');
		$this->load->model('JenisBarangPusatModel');
	}

	public function index()
	{
		$this->load->library('parser');
		$param['tahun_pengadaan'] = $this->TahunPengadaanModel->GetAll();
		$param['barang'] = $this->JenisBarangPusatModel->GetDistinctNamaBarang();
		$param['provinsi'] = $this->ProvinsiModel->GetAll();

		$data = array(
	        'title' => 'KONTRAK',
	        'content-path' => 'PENGADAAN PUSAT / LAPORAN / KONTRAK',
	        'content' => $this->load->view('laporan-kontrak-pusat', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function AjaxGetDataUnit()
	{
		$columns = array( 
            0 => 'no_kontrak', 
            1 => 'nama_barang',
            2 => 'nama_penyedia_pusat',
            3 => 'kontrak',
            4 => 'alokasi',
            5 => 'persen_alokasi',
            6 => 'bapsthp',
            7 => 'persen_bapsthp',
            8 => 'bastb',
            9 => 'persen_bastb'
        );

		$list_nama_barang = $this->input->post('barang');
		$list_id_provinsi = $this->input->post('provinsi');
        $list_id_kabupaten = $this->input->post('kabupaten');
		// foreach($list_nama_barang as $barang){
		// 	echo $barang;
		// }
		// exit(1);

        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

		$totalData = $this->LaporanPusatModel->GetAllAjaxCountKontrak($list_nama_barang, $list_id_provinsi, $list_id_kabupaten);
            
        $totalFiltered = $totalData; 

        //search data percolumn
        $filtercond = '';
        for($i=0;$i<count($columns);$i++){
        	if(!empty($this->input->post('columns')[$i]['search']['value'])){
        		$search = $this->input->post('columns')[$i]['search']['value'];
        		$filtercond  .= " and ".$columns[$i]." LIKE '%".$search."%'"; 
        	}	        	
        }

		if(empty($this->input->post('search')['value']))
        {            
            $posts = $this->LaporanPusatModel->GetAllForAjaxKontrak($_POST['start'], $_POST['length'], $order, $dir, $filtercond, $list_nama_barang, $list_id_provinsi, $list_id_kabupaten);
            $totalFiltered = $this->LaporanPusatModel->GetFilterAjaxCountKontrak($filtercond, $list_nama_barang, $list_id_provinsi, $list_id_kabupaten);
        }
        else {
            $search = $this->input->post('search')['value']; 

            $posts =  $this->LaporanPusatModel->GetSearchAjaxKontrak($_POST['start'], $_POST['length'], $search, $order, $dir, $filtercond, $list_nama_barang, $list_id_provinsi, $list_id_kabupaten);

            $totalFiltered = $this->LaporanPusatModel->GetSearchAjaxCountKontrak($search, $filtercond, $list_nama_barang, $list_id_provinsi, $list_id_kabupaten);
        }

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData['no_kontrak'] = $post->no_kontrak;
                $nestedData['nama_barang'] = $post->nama_barang;
                $nestedData['penyedia'] = $post->nama_penyedia_pusat;
                $nestedData['kontrak'] = number_format($post->total_unit, 0);
                $nestedData['alokasi'] = number_format($post->total_unit_alokasi, 0);
                $nestedData['persen_alokasi'] = number_format(($post->total_unit == 0 ? 0 : ($post->total_unit_alokasi / $post->total_unit * 100)), 0)."%";
                $nestedData['bapsthp'] = number_format($post->total_unit_bapreg + $post->total_unit_bapcad, 0);
                $nestedData['persen_bapsthp'] = number_format(($post->total_unit_alokasi == 0 ? 0 : (($post->total_unit_bapreg + $post->total_unit_bapcad) / $post->total_unit_alokasi * 100)), 0)."%";
                $nestedData['bastb'] = number_format($post->total_unit_bastb, 0);
                $nestedData['persen_bastb'] = number_format(($post->total_unit_alokasi == 0 ? 0 : ($post->total_unit_bastb / $post->total_unit_alokasi * 100)), 0)."%";
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

	public function AjaxGetDataNilai()
	{
		$columns = array( 
            0 => 'no_kontrak', 
            1 => 'nama_barang',
            2 => 'nama_penyedia_pusat',
            3 => 'kontrak',
            4 => 'alokasi',
            5 => 'persen_alokasi',
            6 => 'bapsthp',
            7 => 'persen_bapsthp',
            8 => 'bastb',
            9 => 'persen_bastb'
        );

		$list_nama_barang = $this->input->post('barang');
		$list_id_provinsi = $this->input->post('provinsi');
        $list_id_kabupaten = $this->input->post('kabupaten');
		// foreach($list_nama_barang as $barang){
		// 	echo $barang;
		// }
		// exit(1);

        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

		$totalData = $this->LaporanPusatModel->GetAllAjaxCountKontrak($list_nama_barang, $list_id_provinsi, $list_id_kabupaten);
            
        $totalFiltered = $totalData; 

        //search data percolumn
        $filtercond = '';
        for($i=0;$i<count($columns);$i++){
        	if(!empty($this->input->post('columns')[$i]['search']['value'])){
        		$search = $this->input->post('columns')[$i]['search']['value'];
        		$filtercond  .= " and ".$columns[$i]." LIKE '%".$search."%'"; 
        	}	        	
        }

		if(empty($this->input->post('search')['value']))
        {            
            $posts = $this->LaporanPusatModel->GetAllForAjaxKontrak($_POST['start'], $_POST['length'], $order, $dir, $filtercond, $list_nama_barang, $list_id_provinsi, $list_id_kabupaten);
            $totalFiltered = $this->LaporanPusatModel->GetFilterAjaxCountKontrak($filtercond, $list_nama_barang, $list_id_provinsi, $list_id_kabupaten);
        }
        else {
            $search = $this->input->post('search')['value']; 

            $posts =  $this->LaporanPusatModel->GetSearchAjaxKontrak($_POST['start'], $_POST['length'], $search, $order, $dir, $filtercond, $list_nama_barang, $list_id_provinsi, $list_id_kabupaten);

            $totalFiltered = $this->LaporanPusatModel->GetSearchAjaxCountKontrak($search, $filtercond, $list_nama_barang, $list_id_provinsi, $list_id_kabupaten);
        }

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData['no_kontrak_nilai'] = $post->no_kontrak;
                $nestedData['nama_barang_nilai'] = $post->nama_barang;
                $nestedData['penyedia_nilai'] = $post->nama_penyedia_pusat;
                $nestedData['kontrak_nilai'] = number_format($post->total_nilai, 0);
                $nestedData['alokasi_nilai'] = number_format($post->total_nilai_alokasi, 0);
                $nestedData['persen_alokasi_nilai'] = number_format(($post->total_nilai == 0 ? 0 : ($post->total_nilai_alokasi / $post->total_nilai * 100)), 0)."%";
                $nestedData['bapsthp_nilai'] = number_format($post->total_nilai_bapreg + $post->total_nilai_bapcad, 0);
                $nestedData['persen_bapsthp_nilai'] = number_format(($post->total_nilai_alokasi == 0 ? 0 : (($post->total_nilai_bapreg + $post->total_nilai_bapcad) / $post->total_nilai_alokasi * 100)), 0)."%";
                $nestedData['bastb_nilai'] = number_format($post->total_nilai_bastb, 0);
                $nestedData['persen_bastb_nilai'] = number_format(($post->total_nilai_alokasi == 0 ? 0 : ($post->total_nilai_bastb / $post->total_nilai_alokasi * 100)), 0)."%";
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
