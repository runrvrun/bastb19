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
		$count_baphp = 0;
		$count_bastb = 0;

		$sum_unit_kontrak = $this->LaporanPusatModel->GetTotalUnitKontrak($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$sum_unit_alokasi = $this->LaporanPusatModel->GetTotalUnitAlokasi($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$sum_unit_baphp = 0;
		$sum_unit_bastb = 0;

		$sum_nilai_kontrak = $this->LaporanPusatModel->GetTotalNilaiKontrak($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$sum_nilai_alokasi = $this->LaporanPusatModel->GetTotalNilaiAlokasi($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$sum_nilai_baphp = 0;
		$sum_nilai_bastb = 0;

		$data = array(
			"count_kontrak" => number_format($count_kontrak, 0),
			"count_barang" => number_format($count_barang, 0),
			"count_baphp" => number_format($count_baphp, 0),
			"count_bastb" => number_format($count_bastb, 0),

			"sum_unit_kontrak" => number_format($sum_unit_kontrak, 0),
			"sum_unit_alokasi" => number_format($sum_unit_alokasi, 0),
			"sum_unit_baphp" => number_format($sum_unit_baphp, 0),
			"sum_unit_bastb" => number_format($sum_unit_bastb, 0),

			"sum_nilai_kontrak" => number_format($sum_nilai_kontrak, 0),
			"sum_nilai_alokasi" => number_format($sum_nilai_alokasi, 0),
			"sum_nilai_baphp" => number_format($sum_nilai_baphp, 0),
			"sum_nilai_bastb" => number_format($sum_nilai_bastb, 0),

			"persen_unit_alokasi" => number_format(($sum_unit_kontrak == 0 ? 0 : ($sum_unit_alokasi / $sum_unit_kontrak * 100)), 0),
			"persen_unit_baphp" => number_format(($sum_unit_alokasi == 0 ? 0 : ($sum_unit_baphp / $sum_unit_alokasi * 100)), 0),
			"persen_unit_bastb" => number_format(($sum_unit_alokasi == 0 ? 0 : ($sum_unit_bastb / $sum_unit_alokasi * 100)), 0),

			"persen_nilai_alokasi" => number_format(($sum_nilai_kontrak == 0 ? 0 : ($sum_nilai_alokasi / $sum_nilai_kontrak * 100)), 0),
			"persen_nilai_baphp" => number_format(($sum_nilai_alokasi == 0 ? 0 : ($sum_nilai_baphp / $sum_nilai_alokasi * 100)), 0),
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
            4 => 'baphp',
            5 => 'persen_baphp',
            6 => 'bastb',
            7 => 'persen_bastb',
            8 => 'alokasicad',
            9 => 'persen_alokasicad',
            10 => 'baphpcad',
            11 => 'persen_baphpcad',
            12 => 'bastbcad',
            13 => 'persen_bastbcad'
        );

        $list_nama_barang = $this->input->post('barang');
        
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

		$totalData = $this->LaporanPusatModel->getProvinsi();            
        $totalData = count($totalData); 

        //search data percolumn
        $filtercond = '';
        for($i=0;$i<count($columns);$i++){
        	if(!empty($this->input->post('columns')[$i]['search']['value'])){
        		$search = $this->input->post('columns')[$i]['search']['value'];
        		$filtercond  .= " and ".$columns[$i]." LIKE '%".$search."%'"; 
        	}	        	
        }
        $search = $this->input->post('search')['value']; 
        if(!empty($search)){
            $search = " and nama_provinsi LIKE '%".$search."%'"; 
        }
        $posts =  $this->LaporanPusatModel->getProvinsi(array('start'=>$_POST['start'], 'length'=>$_POST['length'], 'search'=>$search, 'col'=>$order, 'dir'=>$dir, 'filter'=>$filtercond));
        $totalFiltered =  $this->LaporanPusatModel->getProvinsi(array('search'=>$search, 'col'=>$order, 'dir'=>$dir, 'filter'=>$filtercond));
        $totalFiltered = count($totalFiltered);

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData['provinsi'] = $post->nama_provinsi;
                $nestedData['alokasi'] = number_format($post->alokasi_reguler, 0);
                $nestedData['baphp'] = number_format($post->baphp_reguler, 0);
                $nestedData['persen_baphp'] = $post->alokasi_reguler == 0 ? 0 : (($post->baphp_reguler) / $post->alokasi_reguler * 100);
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
				$nestedData['alokasi_persediaan'] = number_format($post->alokasi_persediaan, 0);
                $nestedData['baphp_persediaan'] = number_format($post->baphp_persediaan, 0);
                $nestedData['persen_baphp_persediaan'] = $post->alokasi_persediaan == 0 ? 0 : ($post->baphp_persediaan / $post->alokasi_persediaan * 100);
				if($nestedData['persen_baphp_persediaan'] >= 100){
                    $nestedData['persen_baphp_persediaan'] = '<a class="btn btn-success" style="min-width:60px">'.number_format($nestedData['persen_baphp_persediaan'],0).'%</a>';
                }else{
                    $nestedData['persen_baphp_persediaan'] = '<a class="btn btn-danger" style="min-width:60px">'.number_format($nestedData['persen_baphp_persediaan'],0).'%</a>';
                }    
				$nestedData['bastb_persediaan'] = number_format($post->bastb_persediaan, 0);
                $nestedData['persen_bastb_persediaan'] = $post->alokasi_persediaan == 0 ? 0 : ($post->bastb_persediaan / $post->alokasi_persediaan * 100);
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
            0 => 'nama_provinsi', 
            1 => 'kontrak',
            2 => 'alokasi',
            3 => 'persen_alokasi',
            4 => 'baphp',
            5 => 'persen_baphp',
            6 => 'bastb',
            7 => 'persen_bastb',
            8 => 'alokasicad',
            9 => 'persen_alokasicad',
            10 => 'baphpcad',
            11 => 'persen_baphpcad',
            12 => 'bastbcad',
            13 => 'persen_bastbcad'
        );

		$list_nama_barang = $this->input->post('barang');

        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

		$totalData = $this->LaporanPusatModel->getProvinsi();            
        $totalFiltered = count($totalData); 

        //search data percolumn
        $filtercond = '';
        for($i=0;$i<count($columns);$i++){
        	if(!empty($this->input->post('columns')[$i]['search']['value'])){
        		$search = $this->input->post('columns')[$i]['search']['value'];
        		$filtercond  .= " and ".$columns[$i]." LIKE '%".$search."%'"; 
        	}	        	
        }

        $search = $this->input->post('search')['value']; 
        if(!empty($search)){
            $search = " and nama_provinsi LIKE '%".$search."%'"; 
        }
        $posts =  $this->LaporanPusatModel->getProvinsi(array('start'=>$_POST['start'], 'length'=>$_POST['length'], 'search'=>$search, 'col'=>$order, 'dir'=>$dir, 'filter'=>$filtercond));
        $totalFiltered = count($posts);

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData['provinsi_nilai'] = $post->nama_provinsi;
                $nestedData['alokasi_nilai'] = number_format($post->alokasi_reguler_nilai, 0);
                $nestedData['baphp_nilai'] = number_format($post->baphp_reguler_nilai, 0);
                $nestedData['persen_baphp_nilai'] = $post->alokasi_reguler_nilai == 0 ? 0 : (($post->baphp_reguler_nilai) / $post->alokasi_reguler_nilai * 100);
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
				$nestedData['alokasi_persediaan_nilai'] = number_format($post->alokasi_persediaan_nilai, 0);
                $nestedData['baphp_persediaan_nilai'] = number_format($post->baphp_persediaan_nilai, 0);
                $nestedData['persen_baphp_persediaan_nilai'] = $post->alokasi_persediaan_nilai == 0 ? 0 : ($post->baphp_persediaan_nilai / $post->alokasi_persediaan_nilai * 100);
				if($nestedData['persen_baphp_persediaan_nilai'] >= 100){
                    $nestedData['persen_baphp_persediaan_nilai'] = '<a class="btn btn-success" style="min-width:60px">'.number_format($nestedData['persen_baphp_persediaan_nilai'],0).'%</a>';
                }else{
                    $nestedData['persen_baphp_persediaan_nilai'] = '<a class="btn btn-danger" style="min-width:60px">'.number_format($nestedData['persen_baphp_persediaan_nilai'],0).'%</a>';
                }    
				$nestedData['bastb_persediaan_nilai'] = number_format($post->bastb_persediaan_nilai, 0);
                $nestedData['persen_bastb_persediaan_nilai'] = $post->alokasi_persediaan_nilai == 0 ? 0 : ($post->bastb_persediaan_nilai / $post->alokasi_persediaan_nilai * 100);
                if($nestedData['persen_bastb_persediaan_nilai'] >= 100){
                    $nestedData['persen_bastb_persediaan_nilai'] = '<a class="btn btn-success" style="min-width:60px">'.number_format($nestedData['persen_bastb_persediaan_nilai'],0).'%</a>';
                }else{
                    $nestedData['persen_bastb_persediaan_nilai'] = '<a class="btn btn-danger" style="min-width:60px">'.number_format($nestedData['persen_bastb_persediaan_nilai'],0).'%</a>';
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
