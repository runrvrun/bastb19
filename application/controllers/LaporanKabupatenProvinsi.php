<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanKabupatenProvinsi extends CI_Controller {

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
	        'title' => 'KABUPATEN/KOTA',
	        'content-path' => 'PENGADAAN TP PROVINSI / LAPORAN / KABUPATEN/KOTA',
	        'content' => $this->load->view('laporan-kabupaten-provinsi', $param, TRUE),
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

		$totalData = $this->LaporanProvinsiModel->getKabupaten();
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
		$posts =  $this->LaporanProvinsiModel->getKabupaten(array('start'=>$_POST['start'], 'length'=>$_POST['length'], 'search'=>$search, 'col'=>$order, 'dir'=>$dir, 'filter'=>$filtercond));
		$totalFiltered = $this->LaporanProvinsiModel->getKabupaten(array('search'=>$search, 'filter'=>$filtercond));
		$totalFiltered = count($totalFiltered);

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData['provinsi'] = $post->nama_provinsi;
                $nestedData['kabupaten'] = $post->nama_kabupaten;
                $nestedData['alokasi'] = number_format($post->alokasi_provinsi, 0);
                $nestedData['baphp'] = number_format($post->baphp_provinsi, 0);
				$nestedData['persen_baphp'] = $post->alokasi_provinsi == 0 ? 0 : (($post->baphp_provinsi) / $post->alokasi_provinsi * 100);
				if($nestedData['persen_baphp'] >= 100){
                    $nestedData['persen_baphp'] = '<a class="btn btn-success" style="min-width:60px">'.number_format($nestedData['persen_baphp'],0).'%</a>';
                }else{
                    $nestedData['persen_baphp'] = '<a class="btn btn-danger" style="min-width:60px">'.number_format($nestedData['persen_baphp'],0).'%</a>';
                }                
				$nestedData['bastb'] = number_format($post->bastb_provinsi, 0);
				$nestedData['persen_bastb'] = $post->alokasi_provinsi == 0 ? 0 : (($post->bastb_provinsi) / $post->alokasi_provinsi * 100);
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
            1 => 'nama_kabupaten',
            2 => 'kontrak',
            3 => 'alokasi',
            4 => 'persen_alokasi',
            5 => 'baphp',
            6 => 'persen_baphp',
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

		$totalData = $this->LaporanProvinsiModel->getKabupaten();
        $totalData = count($totalData);

        //search data percolumn
        $filtercond = '';
        if(!empty($list_id_provinsi)){
            $id_provinsis = implode(',',$list_id_provinsi);
            // var_dump($id_provinsis);exit();
            $filtercond  .= " AND pro.id IN ($id_provinsis)";
        }
        for($i=0;$i<count($columns);$i++){
        	if(!empty($this->input->post('columns')[$i]['search']['value'])){
        		$search = $this->input->post('columns')[$i]['search']['value'];
        		$filtercond  .= " and ".$columns[$i]." LIKE '%".$search."%'"; 
        	}	        	
        }

		$search = $this->input->post('search')['value']; 
		$posts =  $this->LaporanProvinsiModel->getKabupaten(array('start'=>$_POST['start'], 'length'=>$_POST['length'], 'search'=>$search, 'col'=>$order, 'dir'=>$dir, 'filter'=>$filtercond));
		$totalFiltered = $this->LaporanProvinsiModel->getKabupaten(array('search'=>$search, 'filter'=>$filtercond));
		$totalFiltered = count($totalFiltered);

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData['provinsi_nilai'] = $post->nama_provinsi;
                $nestedData['kabupaten_nilai'] = $post->nama_kabupaten;
                $nestedData['alokasi_nilai'] = number_format($post->alokasi_provinsi_nilai, 0);
                $nestedData['baphp_nilai'] = number_format($post->baphp_provinsi_nilai, 0);
				$nestedData['persen_baphp_nilai'] = $post->alokasi_provinsi_nilai == 0 ? 0 : (($post->baphp_provinsi_nilai) / $post->alokasi_provinsi_nilai * 100);
				if($nestedData['persen_baphp_nilai'] >= 100){
                    $nestedData['persen_baphp_nilai'] = '<a class="btn btn-success" style="min-width:60px">'.number_format($nestedData['persen_baphp_nilai'],0).'%</a>';
                }else{
                    $nestedData['persen_baphp_nilai'] = '<a class="btn btn-danger" style="min-width:60px">'.number_format($nestedData['persen_baphp_nilai'],0).'%</a>';
                }                
				$nestedData['bastb_nilai'] = number_format($post->bastb_provinsi_nilai, 0);
				$nestedData['persen_bastb_nilai'] = $post->alokasi_provinsi_nilai == 0 ? 0 : (($post->bastb_provinsi_nilai) / $post->alokasi_provinsi_nilai * 100);
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
