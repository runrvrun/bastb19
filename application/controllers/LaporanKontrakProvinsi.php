<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanKontrakProvinsi extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('LaporanProvinsiModel');
		$this->load->model('TahunPengadaanModel');
		$this->load->model('ProvinsiModel');
		$this->load->model('KabupatenModel');
		$this->load->model('PenyediaProvinsiModel');
		$this->load->model('JenisBarangProvinsiModel');
	}

	public function index()
	{
		$this->load->library('parser');
		$param['tahun_pengadaan'] = $this->TahunPengadaanModel->GetAll();
		$param['barang'] = $this->JenisBarangProvinsiModel->GetDistinctNamaBarang();
		$param['provinsi'] = $this->ProvinsiModel->GetAll();

		$data = array(
	        'title' => 'KONTRAK',
	        'content-path' => 'PENGADAAN TP PROVINSI / LAPORAN / KONTRAK',
	        'content' => $this->load->view('laporan-kontrak-provinsi', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function AjaxGetDataUnit()
	{
		$columns = array( 
            0 => 'nama_provinsi', 
            1 => 'no_kontrak', 
            2 => 'nama_barang',
            3 => 'merk',
            4 => 'nama_penyedia_provinsi',
            5 => 'kontrak',
            6 => 'alokasi',
            7 => 'persen_alokasi',
            8 => 'baphp',
            9 => 'persen_baphp',
            10 => 'bastb',
            11 => 'persen_bastb'
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

		$totalData = $this->LaporanProvinsiModel->getKontrak();            
        $totalData = count($totalData); 
        $filtercond = '';
        if(!empty($list_id_provinsi)){
            $id_provinsis = implode(',',$list_id_provinsi);
            // var_dump($id_provinsis);exit();
            $filtercond  .= " AND id_provinsi IN ($id_provinsis)";
        }
        $search = $this->input->post('search')['value'];
        $search = " AND (nama_provinsi LIKE '%".$search."%'
                        OR no_kontrak LIKE '%".$search."%'
                        OR nama_barang LIKE '%".$search."%'
                        OR merk LIKE '%".$search."%'
                        OR nama_penyedia_provinsi LIKE '%".$search."%'
                        )"; 
        
            $posts =  $this->LaporanProvinsiModel->getKontrak(array('start'=>$_POST['start'], 'length'=>$_POST['length'], 'search'=>$search, 'col'=>$order, 'dir'=>$dir, 'filter'=>$filtercond));
            $totalFiltered = $this->LaporanProvinsiModel->getKontrak(array('search'=>$search, 'col'=>$order, 'dir'=>$dir, 'filter'=>$filtercond));        
            $totalFiltered = count($totalFiltered);

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData['nama_provinsi'] = $post->nama_provinsi;
                $nestedData['no_kontrak'] = $post->no_kontrak;
                $nestedData['nama_barang'] = $post->nama_barang;
                $nestedData['merk'] = $post->merk;
                $nestedData['penyedia'] = $post->nama_penyedia_provinsi;
                $nestedData['alokasi'] = number_format($post->jumlah_barang, 0);
                $nestedData['baphp'] = number_format($post->baphp_jumlah, 0);
                $nestedData['persen_baphp'] = $post->jumlah_barang == 0 ? 0 : (($post->baphp_jumlah) / $post->jumlah_barang * 100);
				if($nestedData['persen_baphp'] >= 100){
                    $nestedData['persen_baphp'] = '<a class="btn btn-success" style="min-width:60px">'.number_format($nestedData['persen_baphp'],0).'%</a>';
                }else{
                    $nestedData['persen_baphp'] = '<a class="btn btn-danger" style="min-width:60px">'.number_format($nestedData['persen_baphp'],0).'%</a>';
                }
                $nestedData['bastb'] = number_format($post->bastb_jumlah, 0);
                $nestedData['persen_bastb'] = $post->jumlah_barang == 0 ? 0 : (($post->bastb_jumlah) / $post->jumlah_barang * 100);
				if($nestedData['persen_bastb'] >= 100){
                    $nestedData['persen_bastb'] = '<a class="btn btn-success" style="min-width:60px">'.number_format($nestedData['persen_bastb'],0).'%</a>';
                }else{
                    $nestedData['persen_bastb'] = '<a class="btn btn-danger" style="min-width:60px">'.number_format($nestedData['persen_bastb'],0).'%</a>';
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
            1 => 'no_kontrak', 
            2 => 'nama_barang',
            3 => 'merk',
            4 => 'nama_penyedia_provinsi',
            5 => 'kontrak',
            6 => 'alokasi',
            7 => 'persen_alokasi',
            8 => 'baphp',
            9 => 'persen_baphp',
            10 => 'bastb',
            11 => 'persen_bastb'
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

		$totalData = $this->LaporanProvinsiModel->GetAllAjaxCountKontrak($list_nama_barang, $list_id_provinsi, $list_id_kabupaten);
            
        $totalFiltered = $totalData; 

        
        $filtercond = '';
        if(!empty($list_id_provinsi)){
            $id_provinsis = implode(',',$list_id_provinsi);
            // var_dump($id_provinsis);exit();
            $filtercond  .= " AND id_provinsi IN ($id_provinsis)";
        }
        $search = $this->input->post('search')['value'];
        $search = " AND (nama_provinsi LIKE '%".$search."%'
                        OR no_kontrak LIKE '%".$search."%'
                        OR nama_barang LIKE '%".$search."%'
                        OR merk LIKE '%".$search."%'
                        OR nama_penyedia_provinsi LIKE '%".$search."%'
                        )"; 
                        
            $posts =  $this->LaporanProvinsiModel->getKontrak(array('start'=>$_POST['start'], 'length'=>$_POST['length'], 'search'=>$search, 'col'=>$order, 'dir'=>$dir, 'filter'=>$filtercond));
            $totalFiltered = $this->LaporanProvinsiModel->getKontrak(array('search'=>$search, 'col'=>$order, 'dir'=>$dir, 'filter'=>$filtercond));        
            $totalFiltered = count($totalFiltered);

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData['nama_provinsi_nilai'] = $post->nama_provinsi;
                $nestedData['no_kontrak_nilai'] = $post->no_kontrak;
                $nestedData['nama_barang_nilai'] = $post->nama_barang;
                $nestedData['merk_nilai'] = $post->merk;
                $nestedData['penyedia_nilai'] = $post->nama_penyedia_provinsi;
                $nestedData['alokasi_nilai'] = number_format($post->nilai_barang, 0);
                $nestedData['baphp_nilai'] = number_format($post->baphp_nilai, 0);
                $nestedData['persen_baphp_nilai'] = $post->nilai_barang == 0 ? 0 : (($post->baphp_nilai) / $post->nilai_barang * 100);
				if($nestedData['persen_baphp_nilai'] >= 100){
                    $nestedData['persen_baphp_nilai'] = '<a class="btn btn-success" style="min-width:60px">'.number_format($nestedData['persen_baphp_nilai'],0).'%</a>';
                }else{
                    $nestedData['persen_baphp_nilai'] = '<a class="btn btn-danger" style="min-width:60px">'.number_format($nestedData['persen_baphp_nilai'],0).'%</a>';
                }
                $nestedData['bastb_nilai'] = number_format($post->bastb_nilai, 0);
                $nestedData['persen_bastb_nilai'] = $post->nilai_barang == 0 ? 0 : (($post->bastb_nilai) / $post->nilai_barang * 100);
				if($nestedData['persen_bastb_nilai'] >= 100){
                    $nestedData['persen_bastb_nilai'] = '<a class="btn btn-success" style="min-width:60px">'.number_format($nestedData['persen_bastb_nilai'],0).'%</a>';
                }else{
                    $nestedData['persen_bastb_nilai'] = '<a class="btn btn-danger" style="min-width:60px">'.number_format($nestedData['persen_bastb_nilai'],0).'%</a>';
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
