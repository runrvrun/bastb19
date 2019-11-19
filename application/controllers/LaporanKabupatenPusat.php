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
            5 => 'baphp',
            6 => 'persen_baphp',
            7 => 'bastb',
            8 => 'persen_bastb',
            9 => 'alokasicad',
            10 => 'persen_alokasicad',
            11 => 'baphpcad',
            12 => 'persen_baphpcad',
            13 => 'bastbcad',
            14 => 'persen_bastbcad'
        );

		$list_nama_barang = $this->input->post('barang');
		$list_id_provinsi = $this->input->post('provinsi');
		// foreach($list_nama_barang as $barang){
		// 	echo $barang;
		// }
		// exit(1);

        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

		$totalData = $this->LaporanPusatModel->getKabupaten();            
        $totalData = count($totalData); 

        //search data percolumn
        $filtercond = '';
        for($i=0;$i<count($columns);$i++){
        	if(!empty($this->input->post('columns')[$i]['search']['value'])){
        		$search = $this->input->post('columns')[$i]['search']['value'];
        		$filtercond  .= " and ".$columns[$i]." LIKE '%".$search."%'"; 
        	}	        	
        }
        if(!empty($list_id_provinsi)){
            $id_provinsis = implode(',',$list_id_provinsi);
            // var_dump($id_provinsis);exit();
            $filtercond  .= " AND pro.id IN ($id_provinsis)";
        }

        $search = $this->input->post('search')['value']; 
        if(!empty($search)){
            $search = " and (nama_provinsi LIKE '%".$search."%' OR nama_kabupaten LIKE '%".$search."%')"; 
        }
        $posts =  $this->LaporanPusatModel->getKabupaten(array('start'=>$_POST['start'], 'length'=>$_POST['length'], 'search'=>$search, 'col'=>$order, 'dir'=>$dir, 'filter'=>$filtercond));
        $totalFiltered = $this->LaporanPusatModel->getKabupaten(array('search'=>$search, 'filter'=>$filtercond));
        $totalFiltered = count($totalFiltered);
        
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData['provinsi'] = $post->nama_provinsi;
                $nestedData['kabupaten'] = $post->nama_kabupaten;
                $nestedData['alokasi'] = number_format($post->alokasi_reguler, 0);
                $nestedData['baphp'] = number_format($post->baphp_reguler, 0);
                $nestedData['persen_baphp'] = $post->alokasi_reguler == 0 ? 0 : ($post->baphp_reguler / $post->alokasi_reguler * 100);
                if($nestedData['persen_baphp'] >= 100){
                    $nestedData['persen_baphp'] = '<a class="btn btn-success" style="min-width:60px">'.number_format($nestedData['persen_baphp'],0).'%</a>';
                }else{
                    $nestedData['persen_baphp'] = '<a class="btn btn-danger" style="min-width:60px">'.number_format($nestedData['persen_baphp'],0).'%</a>';
                }    
				$nestedData['bastb'] = number_format($post->bastb_reguler, 0);
                $nestedData['persen_bastb'] = $post->alokasi_reguler == 0 ? 0 : ($post->bastb_reguler / $post->alokasi_reguler * 100);
                if($nestedData['persen_bastb'] >= 100){
                    $nestedData['persen_bastb'] = '<a class="btn btn-success" style="min-width:60px">'.number_format($nestedData['persen_bastb'],0).'%</a>';
                }else{
                    $nestedData['persen_bastb'] = '<a class="btn btn-danger" style="min-width:60px">'.number_format($nestedData['persen_bastb'],0).'%</a>';
                }
                $nestedData['alokasicad'] = number_format($post->alokasi_persediaan, 0);
                $nestedData['baphpcad'] = number_format($post->baphp_persediaan, 0);
                $nestedData['persen_baphpcad'] = $post->alokasi_persediaan == 0 ? 0 : ($post->baphp_persediaan / $post->alokasi_persediaan * 100);
                if($nestedData['persen_baphpcad'] >= 100){
                    $nestedData['persen_baphpcad'] = '<a class="btn btn-success" style="min-width:60px">'.number_format($nestedData['persen_baphpcad'],0).'%</a>';
                }else{
                    $nestedData['persen_baphpcad'] = '<a class="btn btn-danger" style="min-width:60px">'.number_format($nestedData['persen_baphpcad'],0).'%</a>';
                }    
				$nestedData['bastbcad'] = number_format($post->bastb_persediaan, 0);
                $nestedData['persen_bastbcad'] = $post->alokasi_persediaan == 0 ? 0 : ($post->bastb_persediaan / $post->alokasi_persediaan * 100);
                if($nestedData['persen_bastbcad'] >= 100){
                    $nestedData['persen_bastbcad'] = '<a class="btn btn-success" style="min-width:60px">'.number_format($nestedData['persen_bastbcad'],0).'%</a>';
                }else{
                    $nestedData['persen_bastbcad'] = '<a class="btn btn-danger" style="min-width:60px">'.number_format($nestedData['persen_bastbcad'],0).'%</a>';
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
            0 => 'nama_provinsi', 
            1 => 'nama_kabupaten',
            2 => 'kontrak',
            3 => 'alokasi',
            4 => 'persen_alokasi',
            5 => 'baphp',
            6 => 'persen_baphp',
            7 => 'bastb',
            8 => 'persen_bastb',
            9 => 'alokasicad',
            10 => 'persen_alokasicad',
            11 => 'baphpcad',
            12 => 'persen_baphpcad',
            13 => 'bastbcad',
            14 => 'persen_bastbcad'
        );

		$list_nama_barang = $this->input->post('barang');
		$list_id_provinsi = $this->input->post('provinsi');
		// foreach($list_nama_barang as $barang){
		// 	echo $barang;
		// }
		// exit(1);

        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

		$totalData = $this->LaporanPusatModel->getKabupaten();            
        $totalData = count($totalData); 

        //search data percolumn
        $filtercond = '';
        for($i=0;$i<count($columns);$i++){
        	if(!empty($this->input->post('columns')[$i]['search']['value'])){
        		$search = $this->input->post('columns')[$i]['search']['value'];
        		$filtercond  .= " and ".$columns[$i]." LIKE '%".$search."%'"; 
        	}	        	
        }
        if(!empty($list_id_provinsi)){
            $id_provinsis = implode(',',$list_id_provinsi);
            // var_dump($id_provinsis);exit();
            $filtercond  .= " AND pro.id IN ($id_provinsis)";
        }

        $search = $this->input->post('search')['value']; 
        if(!empty($search)){
            $search = " and (nama_provinsi LIKE '%".$search."%' OR nama_kabupaten LIKE '%".$search."%')"; 
        }
        $posts =  $this->LaporanPusatModel->getKabupaten(array('start'=>$_POST['start'], 'length'=>$_POST['length'], 'search'=>$search, 'col'=>$order, 'dir'=>$dir, 'filter'=>$filtercond));
        $totalFiltered = $this->LaporanPusatModel->getKabupaten(array('search'=>$search, 'filter'=>$filtercond));
        $totalFiltered = count($totalFiltered);
        
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData['provinsi_nilai'] = $post->nama_provinsi;
                $nestedData['kabupaten_nilai'] = $post->nama_kabupaten;
                $nestedData['alokasi_nilai'] = number_format($post->alokasi_reguler_nilai, 0);
                $nestedData['baphp_nilai'] = number_format($post->baphp_reguler_nilai, 0);
                $nestedData['persen_baphp_nilai'] = $post->alokasi_reguler_nilai == 0 ? 0 : ($post->baphp_reguler_nilai / $post->alokasi_reguler_nilai * 100);
                if($nestedData['persen_baphp_nilai'] >= 100){
                    $nestedData['persen_baphp_nilai'] = '<a class="btn btn-success" style="min-width:60px">'.number_format($nestedData['persen_baphp_nilai'],0).'%</a>';
                }else{
                    $nestedData['persen_baphp_nilai'] = '<a class="btn btn-danger" style="min-width:60px">'.number_format($nestedData['persen_baphp_nilai'],0).'%</a>';
                }    
				$nestedData['bastb_nilai'] = number_format($post->bastb_reguler_nilai, 0);
                $nestedData['persen_bastb_nilai'] = $post->alokasi_reguler_nilai == 0 ? 0 : ($post->bastb_reguler_nilai / $post->alokasi_reguler_nilai * 100);
                if($nestedData['persen_bastb_nilai'] >= 100){
                    $nestedData['persen_bastb_nilai'] = '<a class="btn btn-success" style="min-width:60px">'.number_format($nestedData['persen_bastb_nilai'],0).'%</a>';
                }else{
                    $nestedData['persen_bastb_nilai'] = '<a class="btn btn-danger" style="min-width:60px">'.number_format($nestedData['persen_bastb_nilai'],0).'%</a>';
                }
                $nestedData['alokasicad_nilai'] = number_format($post->alokasi_persediaan_nilai, 0);
                $nestedData['baphpcad_nilai'] = number_format($post->baphp_persediaan_nilai, 0);
                $nestedData['persen_baphpcad_nilai'] = $post->alokasi_persediaan_nilai == 0 ? 0 : ($post->baphp_persediaan_nilai / $post->alokasi_persediaan_nilai * 100);
                if($nestedData['persen_baphpcad_nilai'] >= 100){
                    $nestedData['persen_baphpcad_nilai'] = '<a class="btn btn-success" style="min-width:60px">'.number_format($nestedData['persen_baphpcad_nilai'],0).'%</a>';
                }else{
                    $nestedData['persen_baphpcad_nilai'] = '<a class="btn btn-danger" style="min-width:60px">'.number_format($nestedData['persen_baphpcad_nilai'],0).'%</a>';
                }    
				$nestedData['bastbcad_nilai'] = number_format($post->bastb_persediaan_nilai, 0);
                $nestedData['persen_bastbcad_nilai'] = $post->alokasi_persediaan_nilai == 0 ? 0 : ($post->bastb_persediaan / $post->alokasi_persediaan_nilai * 100);
                if($nestedData['persen_bastbcad_nilai'] >= 100){
                    $nestedData['persen_bastbcad_nilai'] = '<a class="btn btn-success" style="min-width:60px">'.number_format($nestedData['persen_bastbcad_nilai'],0).'%</a>';
                }else{
                    $nestedData['persen_bastbcad_nilai'] = '<a class="btn btn-danger" style="min-width:60px">'.number_format($nestedData['persen_bastbcad_nilai'],0).'%</a>';
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
