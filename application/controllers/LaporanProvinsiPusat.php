<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanProvinsiPusat extends CI_Controller {

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

		$data = array(
	        'title' => 'PROVINSI',
	        'content-path' => 'PENGADAAN PUSAT / LAPORAN / PROVINSI',
	        'content' => $this->load->view('laporan-provinsi-pusat', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function GetReportData()
	{
		$tahun_anggaran = $this->input->get('tahun_anggaran');
		$list_id_provinsi = $this->input->get('list_id_provinsi');
		$list_id_kabupaten = $this->input->get('list_id_kabupaten');
		$list_id_penyedia = $this->input->get('list_id_penyedia');

		$count_kontrak = $this->LaporanPusatModel->GetTotalKontrak($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$count_barang = $this->LaporanPusatModel->GetTotalBarang($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$count_bapsthp = 0;
		$count_bastb = 0;

		$sum_unit_kontrak = $this->LaporanPusatModel->GetTotalUnitKontrak($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$sum_unit_alokasi = $this->LaporanPusatModel->GetTotalUnitAlokasi($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$sum_unit_bapsthp = 0;
		$sum_unit_bastb = 0;

		$sum_nilai_kontrak = $this->LaporanPusatModel->GetTotalNilaiKontrak($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$sum_nilai_alokasi = $this->LaporanPusatModel->GetTotalNilaiAlokasi($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$sum_nilai_bapsthp = 0;
		$sum_nilai_bastb = 0;

		$data = array(
			"count_kontrak" => number_format($count_kontrak, 0),
			"count_barang" => number_format($count_barang, 0),
			"count_bapsthp" => number_format($count_bapsthp, 0),
			"count_bastb" => number_format($count_bastb, 0),

			"sum_unit_kontrak" => number_format($sum_unit_kontrak, 0),
			"sum_unit_alokasi" => number_format($sum_unit_alokasi, 0),
			"sum_unit_bapsthp" => number_format($sum_unit_bapsthp, 0),
			"sum_unit_bastb" => number_format($sum_unit_bastb, 0),

			"sum_nilai_kontrak" => number_format($sum_nilai_kontrak, 0),
			"sum_nilai_alokasi" => number_format($sum_nilai_alokasi, 0),
			"sum_nilai_bapsthp" => number_format($sum_nilai_bapsthp, 0),
			"sum_nilai_bastb" => number_format($sum_nilai_bastb, 0),

			"persen_unit_alokasi" => number_format(($sum_unit_kontrak == 0 ? 0 : ($sum_unit_alokasi / $sum_unit_kontrak * 100)), 0),
			"persen_unit_bapsthp" => number_format(($sum_unit_alokasi == 0 ? 0 : ($sum_unit_bapsthp / $sum_unit_alokasi * 100)), 0),
			"persen_unit_bastb" => number_format(($sum_unit_alokasi == 0 ? 0 : ($sum_unit_bastb / $sum_unit_alokasi * 100)), 0),

			"persen_nilai_alokasi" => number_format(($sum_nilai_kontrak == 0 ? 0 : ($sum_nilai_alokasi / $sum_nilai_kontrak * 100)), 0),
			"persen_nilai_bapsthp" => number_format(($sum_nilai_alokasi == 0 ? 0 : ($sum_nilai_bapsthp / $sum_nilai_alokasi * 100)), 0),
			"persen_nilai_bastb" => number_format(($sum_nilai_alokasi == 0 ? 0 : ($sum_nilai_bastb / $sum_nilai_alokasi * 100)), 0),
		);

		$hasil = json_encode($data);
		header('HTTP/1.1: 200');
		header('Status: 200');
		header('Content-Length: '.strlen($hasil));
		exit($hasil);
	}

	public function AjaxGetDataUnit()
	{
		$columns = array( 
            0 => 'nama_provinsi', 
            1 => 'kontrak',
            2 => 'alokasi',
            3 => 'persen_alokasi',
            4 => 'bapsthp',
            5 => 'persen_bapsthp',
            6 => 'bastb',
            7 => 'persen_bastb'
        );

		$list_nama_barang = $this->input->post('barang');
		// foreach($list_nama_barang as $barang){
		// 	echo $barang;
		// }
		// exit(1);

        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

		$totalData = $this->LaporanPusatModel->GetAllAjaxCountProvUnit($list_nama_barang);
            
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
            $posts = $this->LaporanPusatModel->GetAllForAjaxProvUnit($_POST['start'], $_POST['length'], $order, $dir, $filtercond, $list_nama_barang);
            $totalFiltered = $this->LaporanPusatModel->GetFilterAjaxCountProvUnit($filtercond, $list_nama_barang);
        }
        else {
            $search = $this->input->post('search')['value']; 

            $posts =  $this->LaporanPusatModel->GetSearchAjaxProvUnit($_POST['start'], $_POST['length'], $search, $order, $dir, $filtercond, $list_nama_barang);

            $totalFiltered = $this->LaporanPusatModel->GetSearchAjaxCountProvUnit($search, $filtercond, $list_nama_barang);
        }

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData['provinsi'] = $post->nama_provinsi;
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
            0 => 'nama_provinsi', 
            1 => 'kontrak',
            2 => 'alokasi',
            3 => 'persen_alokasi',
            4 => 'bapsthp',
            5 => 'persen_bapsthp',
            6 => 'bastb',
            7 => 'persen_bastb'
        );

		$list_nama_barang = $this->input->post('barang');
		// foreach($list_nama_barang as $barang){
		// 	echo $barang;
		// }
		// exit(1);

        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

		$totalData = $this->LaporanPusatModel->GetAllAjaxCountProvUnit($list_nama_barang);
            
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
            $posts = $this->LaporanPusatModel->GetAllForAjaxProvUnit($_POST['start'], $_POST['length'], $order, $dir, $filtercond, $list_nama_barang);
            $totalFiltered = $this->LaporanPusatModel->GetFilterAjaxCountProvUnit($filtercond, $list_nama_barang);
        }
        else {
            $search = $this->input->post('search')['value']; 

            $posts =  $this->LaporanPusatModel->GetSearchAjaxProvUnit($_POST['start'], $_POST['length'], $search, $order, $dir, $filtercond, $list_nama_barang);

            $totalFiltered = $this->LaporanPusatModel->GetSearchAjaxCountProvUnit($search, $filtercond, $list_nama_barang);
        }

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData['provinsi_nilai'] = $post->nama_provinsi;
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
