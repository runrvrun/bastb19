<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanKabupatenPusat extends CI_Controller {

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
	        'title' => 'KABUPATEN/KOTA',
	        'content-path' => 'PENGADAAN PUSAT / LAPORAN / KABUPATEN/KOTA',
	        'content' => $this->load->view('laporan-kabupaten-pusat', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function AjaxGetDataUnit()
	{
		$columns = array( 
            0 => 'nama_provinsi', 
            1 => 'nama_kabupaten',
            2 => 'kontrak',
            3 => 'alokasi',
            4 => 'persen_alokasi',
            5 => 'bapsthp',
            6 => 'persen_bapsthp',
            7 => 'bastb',
            8 => 'persen_bastb'
        );

		$list_nama_barang = $this->input->post('barang');
		$list_id_provinsi = $this->input->post('provinsi');
		// foreach($list_nama_barang as $barang){
		// 	echo $barang;
		// }
		// exit(1);

        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

		$totalData = $this->LaporanPusatModel->GetAllAjaxCountKabupaten($list_nama_barang, $list_id_provinsi);
            
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
            $posts = $this->LaporanPusatModel->GetAllForAjaxKabupaten($_POST['start'], $_POST['length'], $order, $dir, $filtercond, $list_nama_barang, $list_id_provinsi);
            $totalFiltered = $this->LaporanPusatModel->GetFilterAjaxCountKabupaten($filtercond, $list_nama_barang, $list_id_provinsi);
        }
        else {
            $search = $this->input->post('search')['value']; 

            $posts =  $this->LaporanPusatModel->GetSearchAjaxKabupaten($_POST['start'], $_POST['length'], $search, $order, $dir, $filtercond, $list_nama_barang, $list_id_provinsi);

            $totalFiltered = $this->LaporanPusatModel->GetSearchAjaxCountKabupaten($search, $filtercond, $list_nama_barang, $list_id_provinsi);
        }

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData['provinsi'] = $post->nama_provinsi;
                $nestedData['kabupaten'] = $post->nama_kabupaten;
                $nestedData['kontrak'] = number_format($post->total_unit, 0);
                $nestedData['alokasi'] = number_format($post->total_unit_alokasi, 0);
                $nestedData['persen_alokasi'] = number_format(($post->total_unit == 0 ? 0 : ($post->total_unit_alokasi / $post->total_unit * 100)), 0)."%";
                // $nestedData['bapsthp'] = number_format($post->total_unit_bapreg + $post->total_unit_bapcad, 0);
                $nestedData['bapsthp'] = number_format($post->total_unit_bapsthp, 0);
                $nestedData['persen_bapsthp'] = number_format(($post->total_unit_alokasi == 0 ? 0 : ($post->total_unit_bapsthp / $post->total_unit_alokasi * 100)), 0)."%";
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
            0 => 'nama_provinsi', 
            1 => 'nama_kabupaten',
            2 => 'kontrak',
            3 => 'alokasi',
            4 => 'persen_alokasi',
            5 => 'bapsthp',
            6 => 'persen_bapsthp',
            7 => 'bastb',
            8 => 'persen_bastb'
        );

		$list_nama_barang = $this->input->post('barang');
		$list_id_provinsi = $this->input->post('provinsi');
		// foreach($list_nama_barang as $barang){
		// 	echo $barang;
		// }
		// exit(1);

        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

		$totalData = $this->LaporanPusatModel->GetAllAjaxCountKabupaten($list_nama_barang, $list_id_provinsi);
            
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
            $posts = $this->LaporanPusatModel->GetAllForAjaxKabupaten($_POST['start'], $_POST['length'], $order, $dir, $filtercond, $list_nama_barang, $list_id_provinsi);
            $totalFiltered = $this->LaporanPusatModel->GetFilterAjaxCountKabupaten($filtercond, $list_nama_barang, $list_id_provinsi);
        }
        else {
            $search = $this->input->post('search')['value']; 

            $posts =  $this->LaporanPusatModel->GetSearchAjaxKabupaten($_POST['start'], $_POST['length'], $search, $order, $dir, $filtercond, $list_nama_barang, $list_id_provinsi);

            $totalFiltered = $this->LaporanPusatModel->GetSearchAjaxCountKabupaten($search, $filtercond, $list_nama_barang, $list_id_provinsi);
        }

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData['provinsi_nilai'] = $post->nama_provinsi;
                $nestedData['kabupaten_nilai'] = $post->nama_kabupaten;
                $nestedData['kontrak_nilai'] = number_format($post->total_nilai, 0);
                $nestedData['alokasi_nilai'] = number_format($post->total_nilai_alokasi, 0);
                $nestedData['persen_alokasi_nilai'] = number_format(($post->total_nilai == 0 ? 0 : ($post->total_nilai_alokasi / $post->total_nilai * 100)), 0)."%";
                // $nestedData['bapsthp_nilai'] = number_format($post->total_nilai_bapreg + $post->total_nilai_bapcad, 0);
                $nestedData['bapsthp_nilai'] = number_format($post->total_nilai_bapsthp, 0);
                $nestedData['persen_bapsthp_nilai'] = number_format(($post->total_nilai_alokasi == 0 ? 0 : ($post->total_nilai_bapsthp / $post->total_nilai_alokasi * 100)), 0)."%";
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
