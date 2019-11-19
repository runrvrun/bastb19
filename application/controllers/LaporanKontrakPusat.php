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
            2 => 'merk',
            3 => 'nama_penyedia_pusat',
            4 => 'kontrak',
            5 => 'alokasi_reguler',
            6 => 'persen_alokasi_reguler',
            7 => 'baphp_reguler',
            8 => 'persen_baphp_reguler',
            9 => 'bastb_reguler',
            10 => 'persen_bastb_reguler',
            11 => 'alokasi_persediaan',
            12 => 'persen_alokasi_persediaan',
            13 => 'baphp_persediaan',
            14 => 'persen_baphp_persediaan',
            15 => 'bastb_persediaan',
            16 => 'persen_bastb_persediaan'
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
                $nestedData['merk'] = $post->merk;
                $nestedData['penyedia'] = $post->nama_penyedia_pusat;
                $nestedData['kontrak'] = number_format($post->total_unit, 0);
                $nestedData['alokasi_reguler'] = number_format($post->total_unit_alokasi, 0);
                $nestedData['baphp_reguler'] = number_format($post->total_unit_bapreg, 0);
                $nestedData['persen_baphp_reguler'] = $post->total_unit_alokasi == 0 ? 0 : (($post->total_unit_bapreg) / $post->total_unit_alokasi * 100);
                if($nestedData['persen_baphp_reguler'] >= 100){
                    $nestedData['persen_baphp_reguler'] = '<a class="btn btn-success" style="min-width:60px">'.number_format($nestedData['persen_baphp_reguler'],0).'%</a>';
                }else{
                    $nestedData['persen_baphp_reguler'] = '<a class="btn btn-danger" style="min-width:60px">'.number_format($nestedData['persen_baphp_reguler'],0).'%</a>';
                }
                $nestedData['bastb_reguler'] = number_format($post->total_unit_basreg, 0);
                $nestedData['persen_bastb_reguler'] = $post->total_unit_alokasi == 0 ? 0 : ($post->total_unit_basreg / $post->total_unit_alokasi * 100);
                if($nestedData['persen_bastb_reguler'] >= 100){
                    $nestedData['persen_bastb_reguler'] = '<a class="btn btn-success" style="min-width:60px">'.number_format($nestedData['persen_bastb_reguler'],0).'%</a>';
                }else{
                    $nestedData['persen_bastb_reguler'] = '<a class="btn btn-danger" style="min-width:60px">'.number_format($nestedData['persen_bastb_reguler'],0).'%</a>';
                }
                $nestedData['alokasi_persediaan'] = number_format($post->total_unit_alokasicad, 0);
                $nestedData['baphp_persediaan'] = number_format($post->total_unit_bapcad, 0);
                $nestedData['persen_baphp_persediaan'] = $post->total_unit_alokasicad == 0 ? 0 : (($post->total_unit_bapcad) / $post->total_unit_alokasicad * 100);
                if($nestedData['persen_baphp_persediaan'] >= 100){
                    $nestedData['persen_baphp_persediaan'] = '<a class="btn btn-success" style="min-width:60px">'.number_format($nestedData['persen_baphp_persediaan'],0).'%</a>';
                }else{
                    $nestedData['persen_baphp_persediaan'] = '<a class="btn btn-danger" style="min-width:60px">'.number_format($nestedData['persen_baphp_persediaan'],0).'%</a>';
                }
                $nestedData['bastb_persediaan'] = number_format($post->total_unit_bascad, 0);
                $nestedData['persen_bastb_persediaan'] = $post->total_unit_alokasicad == 0 ? 0 : ($post->total_unit_bascad / $post->total_unit_alokasicad * 100);
                if($nestedData['persen_bastb_persediaan'] >= 100){
                    $nestedData['persen_bastb_persediaan'] = '<a class="btn btn-success" style="min-width:60px">'.number_format($nestedData['persen_bastb_persediaan'],0).'%</a>';
                }else{
                    $nestedData['persen_bastb_persediaan'] = '<a class="btn btn-danger" style="min-width:60px">'.number_format($nestedData['persen_bastb_persediaan'],0).'%</a>';
                }
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
            2 => 'merk',
            3 => 'nama_penyedia_pusat',
            4 => 'kontrak',
            5 => 'alokasi_reguler',
            6 => 'persen_alokasi_reguler',
            7 => 'baphp_reguler',
            8 => 'persen_baphp_reguler',
            9 => 'bastb_reguler',
            10 => 'persen_bastb_reguler',
            11 => 'alokasi_persediaan',
            12 => 'persen_alokasi_persediaan',
            13 => 'baphp_persediaan',
            14 => 'persen_baphp_persediaan',
            15 => 'bastb_persediaan',
            16 => 'persen_bastb_persediaan'
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
                $nestedData['merk_nilai'] = $post->merk;
                $nestedData['penyedia_nilai'] = $post->nama_penyedia_pusat;
                $nestedData['kontrak_nilai'] = number_format($post->total_nilai, 0);
                $nestedData['alokasi_nilai_reguler'] = number_format($post->total_nilai_alokasi, 0);
                $nestedData['baphp_nilai_reguler'] = number_format($post->total_nilai_bapreg, 0);
                $nestedData['persen_baphp_nilai_reguler'] = $post->total_nilai_alokasi == 0 ? 0 : ($post->total_nilai_bapreg) / $post->total_nilai_alokasi * 100;
                if($nestedData['persen_baphp_nilai_reguler'] >= 100){
                    $nestedData['persen_baphp_nilai_reguler'] = '<a class="btn btn-success" style="min-width:60px">'.number_format($nestedData['persen_baphp_nilai_reguler'],0).'%</a>';
                }else{
                    $nestedData['persen_baphp_nilai_reguler'] = '<a class="btn btn-danger" style="min-width:60px">'.number_format($nestedData['persen_baphp_nilai_reguler'],0).'%</a>';
                }
                $nestedData['bastb_nilai_reguler'] = number_format($post->total_nilai_basreg, 0);
                $nestedData['persen_bastb_nilai_reguler'] = $post->total_nilai_alokasi == 0 ? 0 : ($post->total_nilai_basreg / $post->total_nilai_alokasi * 100);
                if($nestedData['persen_bastb_nilai_reguler'] >= 100){
                    $nestedData['persen_bastb_nilai_reguler'] = '<a class="btn btn-success" style="min-width:60px">'.number_format($nestedData['persen_bastb_nilai_reguler'],0).'%</a>';
                }else{
                    $nestedData['persen_bastb_nilai_reguler'] = '<a class="btn btn-danger" style="min-width:60px">'.number_format($nestedData['persen_bastb_nilai_reguler'],0).'%</a>';
                }
                $nestedData['alokasi_nilai_persediaan'] = number_format($post->total_nilai_alokasicad, 0);
                $nestedData['baphp_nilai_persediaan'] = number_format($post->total_nilai_bapcad, 0);
                $nestedData['persen_baphp_nilai_persediaan'] = $post->total_nilai_alokasicad == 0 ? 0 : ($post->total_nilai_bapcad) / $post->total_nilai_alokasicad * 100;
                if($nestedData['persen_baphp_nilai_persediaan'] >= 100){
                    $nestedData['persen_baphp_nilai_persediaan'] = '<a class="btn btn-success" style="min-width:60px">'.number_format($nestedData['persen_baphp_nilai_persediaan'],0).'%</a>';
                }else{
                    $nestedData['persen_baphp_nilai_persediaan'] = '<a class="btn btn-danger" style="min-width:60px">'.number_format($nestedData['persen_baphp_nilai_persediaan'],0).'%</a>';
                }
                $nestedData['bastb_nilai_persediaan'] = number_format($post->total_nilai_bascad, 0);
                $nestedData['persen_bastb_nilai_persediaan'] = $post->total_nilai_alokasicad == 0 ? 0 : ($post->total_nilai_bascad / $post->total_nilai_alokasicad * 100);
                if($nestedData['persen_bastb_nilai_persediaan'] >= 100){
                    $nestedData['persen_bastb_nilai_persediaan'] = '<a class="btn btn-success" style="min-width:60px">'.number_format($nestedData['persen_bastb_nilai_persediaan'],0).'%</a>';
                }else{
                    $nestedData['persen_bastb_nilai_persediaan'] = '<a class="btn btn-danger" style="min-width:60px">'.number_format($nestedData['persen_bastb_nilai_persediaan'],0).'%</a>';
                }                
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
