<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penggunaan_ongkir extends CI_Controller {
    public $cols = array(
		array("column" => "nomor", "caption" => "No. Kontrak", "dbcolumn" => "tb_kontrak_ongkir.nomor"),
		array("column" => "tanggal", "caption" => "Tanggal", "dbcolumn" => "tb_kontrak_ongkir.tanggal"),
		array("column" => "ekspedisi", "caption" => "Ekspedisi", "dbcolumn" => "ekspedisi"),
        array("column" => "kontrak_ongkir", "caption" => "Nilai (Rp)", "dbcolumn" => "tb_kontrak_ongkir.ongkir"),
        array("column" => "no_baphp", "caption" => "Nomor BAPHP Persediaan", "dbcolumn" => "tb_baphp_persediaan.no_baphp"),
        array("column" => "tanggal_baphp", "caption" => "Tanggal BAPHP Persediaan", "dbcolumn" => "tb_baphp_persediaan.tanggal"),
        array("column" => "nama_provinsi", "caption" => "Provinsi", "dbcolumn" => "nama_provinsi"),
        array("column" => "nama_kabupaten", "caption" => "Kabupaten", "dbcolumn" => "nama_kabupaten"),
        array("column" => "nama_barang", "caption" => "Nama Barang", "dbcolumn" => "tb_kontrak_pusat.nama_barang"),
        array("column" => "merk", "caption" => "Merk Barang", "dbcolumn" => "tb_kontrak_pusat.merk"),
        array("column" => "ongkir", "caption" => "Nilai (Rp)", "dbcolumn" => "tb_ongkir_persediaan.ongkir"),
        array("column" => "nomor_surat_permohonan", "caption" => "No Surat Permohonan", "dbcolumn" => "nomor_surat_permohonan"),
        array("column" => "tanggal_surat_permohonan", "caption" => "Tanggal Surat Permohonan", "dbcolumn" => "tanggal_surat_permohonan"),
        array("column" => "nomor_surat_ke_penyedia", "caption" => "No Surat Ke Penyedia", "dbcolumn" => "nomor_surat_ke_penyedia"),
        array("column" => "tanggal_surat_ke_penyedia", "caption" => "Tanggal Surat Ke Penyedia", "dbcolumn" => "tanggal_surat_ke_penyedia"),
        array("column" => "nomor_surat_ke_dinas", "caption" => "No Surat Ke Dinas", "dbcolumn" => "nomor_surat_ke_dinas"),
        array("column" => "tanggal_surat_ke_dinas", "caption" => "Tanggal Surat Ke Dinas", "dbcolumn" => "tanggal_surat_ke_dinas"),
        array("column" => "keterangan", "caption" => "Keterangan", "dbcolumn" => "keterangan"), 
	);    
	
    function __construct()
    {
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('Baphp_model');
		$this->load->model('Ongkir_model');
		$this->load->model('Penggunaan_ongkir_model');
		$this->load->model('Kontrak_ongkir_model');
        $this->load->helper('url');
		$this->load->library('xlsxwriter');
	}

    public function index($id = null)
    {
		$this->load->library('parser');
		$id_kontrak_ongkir = $this->input->get('id_kontrak_ongkir');
		$param['cols'] = $this->cols;
		$param['kontrak_ongkir'] = $this->Kontrak_ongkir_model->get($id_kontrak_ongkir);
		$param['ongkir'] = $this->Penggunaan_ongkir_model->get(null,$id_kontrak_ongkir);
        $param['total_nilai'] = $this->Penggunaan_ongkir_model->total_nilai($id_kontrak_ongkir);
        $param['id_kontrak_ongkir'] = $id_kontrak_ongkir;

        $data = array(
			'title' => 'Data Detail Kontrak Ongkir',
            'content-path' => strtoupper('Alokasi / BAPHP / Data Detail Kontrak Ongkir'),
            'content' => $this->load->view('penggunaan-ongkir/index', $param, TRUE),
        );
        $this->parser->parse('default_template', $data);
	}

    public function index_json()
    { 
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
        
		$id_kontrak_ongkir = $this->input->post('id_kontrak_ongkir');
        $start = empty($this->input->post('start'))? 0:$this->input->post('start');
        $length = empty($this->input->post('length'))? null:$this->input->post('length');
        $order = empty($this->input->post('order')[0]['column'])? null:$this->input->post('order')[0]['column'];
        $dir = empty($this->input->post('order')[0]['dir'])? null:$this->input->post('order')[0]['dir'];
        
		$columns = array();
		foreach($this->cols as $key=>$val){
			array_push($columns,$val['column']);
		}

		$dbcolumns = array();
		foreach($this->cols as $key=>$val){
			array_push($dbcolumns,$val['dbcolumn']);
		}
     
        $totalData = $this->Penggunaan_ongkir_model->get(null,$id_kontrak_ongkir);
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
		$posts_all_search =  $this->Penggunaan_ongkir_model->get(null, $id_kontrak_ongkir,null, null, null, null, $filtercond, $search);
        $totalFiltered = count($posts_all_search);
		$posts =  $this->Penggunaan_ongkir_model->get(null, $id_kontrak_ongkir, $start, $length, $order, $dir, $filtercond, $search);

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();
				foreach($this->cols as $val){
					$nestedData[$val['column']] = $post->{$val['column']};
				}
                $nestedData['kontrak_ongkir'] = number_format($post->kontrak_ongkir,0);
                $nestedData['ongkir'] = number_format($post->ongkir,0);
                $nestedData['tanggal'] = date("d-m-Y",strtotime($post->tanggal));
                $nestedData['tanggal_baphp'] = date("d-m-Y",strtotime($post->tanggal_baphp));
                $nestedData['tanggal_surat_permohonan'] = date("d-m-Y",strtotime($post->tanggal_surat_permohonan));
                $nestedData['tanggal_surat_ke_dinas'] = date("d-m-Y",strtotime($post->tanggal_surat_ke_dinas));
                $nestedData['tanggal_surat_ke_penyedia'] = date("d-m-Y",strtotime($post->tanggal_surat_ke_penyedia));

                $tools =  "";
                $nestedData['tools'] = $tools;
               
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

    public function get_json()
    {
        $id = $this->input->get('id');
		$data = $this->Ongkir_model->get($id);
		$hasil = json_encode($data);
		header('HTTP/1.1: 200');
		header('Status: 200');
		header('Content-Length: '.strlen($hasil));
		exit($hasil);
	}
}
	