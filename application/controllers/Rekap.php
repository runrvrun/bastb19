<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap extends CI_Controller {
    function __construct()
    {
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('Kontrak_pusat_model');
		$this->load->model('Alokasi_pusat_model');
		$this->load->model('Baphp_model');
		$this->load->model('Baphp_persediaan_model');
		$this->load->model('Bastb_pusat_model');
		$this->load->model('HibahPusatModel');
		$this->load->model('Ongkir_model');
		$this->load->model('Laporan_pemanfaatan_model');
        $this->load->helper('url');
		$this->load->library('xlsxwriter');
	}
    public function index(){
		$this->alokasi();
	}
    public function alokasi()
    {
		$param['cols'] = array(
			array("column" => "tahun_anggaran", "caption" => "Tahun Anggaran", "dbcolumn" => "tb_kontrak_pusat.tahun_anggaran"),
			array("column" => "no_kontrak", "caption" => "No. Kontrak", "dbcolumn" => "tb_kontrak_pusat.no_kontrak"),
			array("column" => "periode", "caption" => "Periode", "dbcolumn" => "periode"),
			array("column" => "nama_barang", "caption" => "Nama Barang", "dbcolumn" => "nama_barang"),
			array("column" => "merk", "caption" => "Merk", "dbcolumn" => "merk"),
			array("column" => "nama_provinsi", "caption" => "Provinsi", "dbcolumn" => "tb_provinsi.nama_provinsi"),
			array("column" => "nama_kabupaten", "caption" => "Kabupaten", "dbcolumn" => "tb_kabupaten.nama_kabupaten"),
			array("column" => "jumlah_barang_rev", "caption" => "Jumlah Barang", "dbcolumn" => "jumlah_barang_rev"),
			array("column" => "nilai_barang_rev", "caption" => "Nilai(Rp)", "dbcolumn" => "nilai_barang_rev"),
			array("column" => "harga_satuan_rev", "caption" => "Harga Satuan(Rp)", "dbcolumn" => "harga_satuan_rev"),
			array("column" => "dinas", "caption" => "ID", "dbcolumn" => "dinas"),
			array("column" => "regcad", "caption" => "REG/CAD", "dbcolumn" => "regcad"),
			array("column" => "no_adendum", "caption" => "No. Addendum", "dbcolumn" => "no_adendum"),
			array("column" => "nama_penyedia_pusat", "caption" => "Penyedia", "dbcolumn" => "tb_penyedia_pusat.nama_penyedia_pusat"),
			array("column" => "sp2d", "caption" => "SP2D", "dbcolumn" => "sp2d.keterangan"),
		);
        $this->load->library('parser');
		$id_kontrak_pusat = $this->input->get('id_kontrak_pusat');
		$param['kontrak_pusat'] = $this->Kontrak_pusat_model->get($id_kontrak_pusat);
		$param['total_unit'] = $this->Alokasi_pusat_model->total_unit(null, $id_kontrak_pusat);
        $param['total_nilai'] = $this->Alokasi_pusat_model->total_nilai(null, $id_kontrak_pusat);

        $data = array(
			'title' => 'Rekap Kontrak Pusat',
            'content-path' => strtoupper('Rekap Kontrak Pusat'),
            'content' => $this->load->view('rekap/alokasi', $param, TRUE),
        );
        $this->parser->parse('default_template', $data);
	}

    public function alokasi_json()
    { 
		$cols = array(
			array("column" => "tahun_anggaran", "caption" => "Tahun Anggaran", "dbcolumn" => "tb_kontrak_pusat.tahun_anggaran"),
			array("column" => "no_kontrak", "caption" => "No. Kontrak", "dbcolumn" => "tb_kontrak_pusat.no_kontrak"),
			array("column" => "periode", "caption" => "Periode", "dbcolumn" => "periode"),
			array("column" => "nama_barang", "caption" => "Nama Barang", "dbcolumn" => "nama_barang"),
			array("column" => "merk", "caption" => "Merk", "dbcolumn" => "merk"),
			array("column" => "nama_provinsi", "caption" => "Provinsi", "dbcolumn" => "tb_provinsi.nama_provinsi"),
			array("column" => "nama_kabupaten", "caption" => "Kabupaten", "dbcolumn" => "tb_kabupaten.nama_kabupaten"),
			array("column" => "jumlah_barang_rev", "caption" => "Jumlah Barang", "dbcolumn" => "jumlah_barang_rev"),
			array("column" => "nilai_barang_rev", "caption" => "Nilai(Rp)", "dbcolumn" => "nilai_barang_rev"),
			array("column" => "harga_satuan_rev", "caption" => "Harga Satuan(Rp)", "dbcolumn" => "harga_satuan_rev"),
			array("column" => "dinas", "caption" => "ID", "dbcolumn" => "dinas"),
			array("column" => "regcad", "caption" => "REG/CAD", "dbcolumn" => "regcad"),
			array("column" => "no_adendum", "caption" => "No. Addendum", "dbcolumn" => "no_adendum"),
			array("column" => "nama_penyedia_pusat", "caption" => "Penyedia", "dbcolumn" => "tb_penyedia_pusat.nama_penyedia_pusat"),
			array("column" => "sp2d", "caption" => "SP2D", "dbcolumn" => "sp2d.keterangan"),
		);

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
        
		$id_kontrak_pusat = $this->input->post('id_kontrak_pusat');
        $start = empty($this->input->post('start'))? 0:$this->input->post('start');
        $length = empty($this->input->post('length'))? null:$this->input->post('length');
        $order = empty($this->input->post('order')[0]['column'])? null:$this->input->post('order')[0]['column'];
        $dir = empty($this->input->post('order')[0]['dir'])? null:$this->input->post('order')[0]['dir'];
        
		$columns = array();
		foreach($cols as $key=>$val){
			array_push($columns,$val['column']);
		}

		$dbcolumns = array();
		foreach($cols as $key=>$val){
			array_push($dbcolumns,$val['dbcolumn']);
		}
     
        $totalData = $this->Alokasi_pusat_model->get(null,$id_kontrak_pusat);
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
		$posts_all_search =  $this->Alokasi_pusat_model->get(null, $id_kontrak_pusat,null, null, null, null, $filtercond, $search);
        $totalFiltered = count($posts_all_search);
		$posts =  $this->Alokasi_pusat_model->get(null, $id_kontrak_pusat, $start, $length, $order, $dir, $filtercond, $search);

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();
				foreach($cols as $val){
					$nestedData[$val['column']] = $post->{$val['column']};
				}
				$nestedData['jumlah_barang_rev'] = number_format($nestedData['jumlah_barang_rev'],0);
				$nestedData['nilai_barang_rev'] = number_format($nestedData['nilai_barang_rev'],0);
				$nestedData['harga_satuan_rev'] = number_format($nestedData['harga_satuan_rev'],0);

                $tools =  "<a class='btn btn-xs btn-success btn-sm'><i class='glyphicon glyphicon-zoom-in' onclick='LoadData(".$post->id.")'></i></a>";
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
	
	public function baphp_reguler()
    {
		$param['cols'] = array(
			array("column" => "tahun_anggaran", "caption" => "Tahun Anggaran", "dbcolumn" => "tahun_anggaran"),
			array("column" => "titik_serah", "caption" => "Titik Serah", "dbcolumn" => "titik_serah"),
			array("column" => "nama_wilayah", "caption" => "Nama Wilayah", "dbcolumn" => "nama_wilayah"),
			array("column" => "no_baphp", "caption" => "Nomor BAPHP", "dbcolumn" => "no_baphp"),
			array("column" => "tanggal", "caption" => "Tanggal BAPHP", "dbcolumn" => "tanggal"),
			array("column" => "pihak_penyerah", "caption" => "Pihak Yang Menyerahkan", "dbcolumn" => "peny.nama_penyedia_pusat"),
			array("column" => "nama_penyerah", "caption" => "Nama Yang Menyerahkan", "dbcolumn" => "nama_penyerah"),
			array("column" => "jabatan_penyerah", "caption" => "Jabatan Yang Menyerahkan", "dbcolumn" => "jabatan_penyerah"),
			array("column" => "notelp_penyerah", "caption" => "No Telepon Yang Menyerahkan", "dbcolumn" => "notelp_penyerah"),
			array("column" => "alamat_penyerah", "caption" => "Alamat Yang Menyerahkan", "dbcolumn" => "alamat_penyerah"),
			array("column" => "provinsi_penyerah", "caption" => "Provinsi Yang Menyerahkan", "dbcolumn" => "prov_peny.nama_provinsi"),
			array("column" => "kabupaten_penyerah", "caption" => "Kabupaten/Kota Yang Menyerahkan", "dbcolumn" => "kab_peny.nama_kabupaten"),
			array("column" => "pihak_penerima", "caption" => "Pihak Yang Menerima", "dbcolumn" => "pihak_penerima"),
			array("column" => "nama_penerima", "caption" => "Nama Yang Menerima", "dbcolumn" => "nama_penerima"),
			array("column" => "jabatan_penerima", "caption" => "Jabatan Yang Menerima", "dbcolumn" => "jabatan_penerima"),
			array("column" => "notelp_penerima", "caption" => "No Telepon Yang Menerima", "dbcolumn" => "notelp_penerima"),
			array("column" => "alamat_penerima", "caption" => "Alamat Yang Menerima", "dbcolumn" => "alamat_penerima"),
			array("column" => "provinsi_penerima", "caption" => "Provinsi Yang Menerima", "dbcolumn" => "prov_pene.nama_provinsi"),
			array("column" => "kabupaten_penerima", "caption" => "Kabupaten/Kota Yang Menerima", "dbcolumn" => "kab_pene.nama_kabupaten"),
			array("column" => "nama_barang", "caption" => "Nama Barang", "dbcolumn" => "nama_barang"),
			array("column" => "merk", "caption" => "Merk Barang", "dbcolumn" => "merk"),
			array("column" => "jumlah_barang", "caption" => "Jumlah Barang", "dbcolumn" => "jumlah_barang"),
			array("column" => "nilai_barang", "caption" => "Nilai Barang (Rp)", "dbcolumn" => "nilai_barang"),
			array("column" => "harga_satuan", "caption" => "Harga Satuan (Rp)", "dbcolumn" => "harga_satuan"),
			array("column" => "no_kontrak", "caption" => "Nomor Kontrak", "dbcolumn" => "no_kontrak"),
			array("column" => "nama_mengetahui", "caption" => "Nama Mengetahui", "dbcolumn" => "nama_mengetahui"),
			array("column" => "jabatan_mengetahui", "caption" => "Jabatan Mengetahui", "dbcolumn" => "jabatan_mengetahui"),
			array("column" => "no_bart", "caption" => "Nomor BART", "dbcolumn" => "no_bart"),
			array("column" => "tanggal_bart", "caption" => "Tanggal BART", "dbcolumn" => "tanggal_bart"),
		);
		
		$this->load->library('parser');
		$id_kontrak_pusat = $this->input->get('id_kontrak_pusat');
		$param['kontrak_pusat'] = $this->Kontrak_pusat_model->get($id_kontrak_pusat);
		$param['total_unit'] = $this->Alokasi_pusat_model->total_unit(null, $id_kontrak_pusat);
        $param['total_nilai'] = $this->Alokasi_pusat_model->total_nilai(null, $id_kontrak_pusat);


		$data = array(
			'title' => 'Rekap Kontrak Pusat',
            'content-path' => strtoupper('Rekap Kontrak Pusat'),
            'content' => $this->load->view('rekap/baphp_reguler', $param, TRUE),
        );
        $this->parser->parse('default_template', $data);
	}

	public function baphp_reguler_json()
    {
		$cols = array(
			array("column" => "tahun_anggaran", "caption" => "Tahun Anggaran", "dbcolumn" => "tahun_anggaran"),
			array("column" => "titik_serah", "caption" => "Titik Serah", "dbcolumn" => "titik_serah"),
			array("column" => "nama_wilayah", "caption" => "Nama Wilayah", "dbcolumn" => "nama_wilayah"),
			array("column" => "no_baphp", "caption" => "Nomor BAPHP", "dbcolumn" => "no_baphp"),
			array("column" => "tanggal", "caption" => "Tanggal BAPHP", "dbcolumn" => "tanggal"),
			array("column" => "pihak_penyerah", "caption" => "Pihak Yang Menyerahkan", "dbcolumn" => "peny.nama_penyedia_pusat"),
			array("column" => "nama_penyerah", "caption" => "Nama Yang Menyerahkan", "dbcolumn" => "nama_penyerah"),
			array("column" => "jabatan_penyerah", "caption" => "Jabatan Yang Menyerahkan", "dbcolumn" => "jabatan_penyerah"),
			array("column" => "notelp_penyerah", "caption" => "No Telepon Yang Menyerahkan", "dbcolumn" => "notelp_penyerah"),
			array("column" => "alamat_penyerah", "caption" => "Alamat Yang Menyerahkan", "dbcolumn" => "alamat_penyerah"),
			array("column" => "provinsi_penyerah", "caption" => "Provinsi Yang Menyerahkan", "dbcolumn" => "prov_peny.nama_provinsi"),
			array("column" => "kabupaten_penyerah", "caption" => "Kabupaten/Kota Yang Menyerahkan", "dbcolumn" => "kab_peny.nama_kabupaten"),
			array("column" => "pihak_penerima", "caption" => "Pihak Yang Menerima", "dbcolumn" => "pihak_penerima"),
			array("column" => "nama_penerima", "caption" => "Nama Yang Menerima", "dbcolumn" => "nama_penerima"),
			array("column" => "jabatan_penerima", "caption" => "Jabatan Yang Menerima", "dbcolumn" => "jabatan_penerima"),
			array("column" => "notelp_penerima", "caption" => "No Telepon Yang Menerima", "dbcolumn" => "notelp_penerima"),
			array("column" => "alamat_penerima", "caption" => "Alamat Yang Menerima", "dbcolumn" => "alamat_penerima"),
			array("column" => "provinsi_penerima", "caption" => "Provinsi Yang Menerima", "dbcolumn" => "prov_pene.nama_provinsi"),
			array("column" => "kabupaten_penerima", "caption" => "Kabupaten/Kota Yang Menerima", "dbcolumn" => "kab_pene.nama_kabupaten"),
			array("column" => "nama_barang", "caption" => "Nama Barang", "dbcolumn" => "nama_barang"),
			array("column" => "merk", "caption" => "Merk Barang", "dbcolumn" => "merk"),
			array("column" => "jumlah_barang", "caption" => "Jumlah Barang", "dbcolumn" => "jumlah_barang"),
			array("column" => "nilai_barang", "caption" => "Nilai Barang (Rp)", "dbcolumn" => "nilai_barang"),
			array("column" => "harga_satuan", "caption" => "Harga Satuan (Rp)", "dbcolumn" => "harga_satuan"),
			array("column" => "no_kontrak", "caption" => "Nomor Kontrak", "dbcolumn" => "no_kontrak"),
			array("column" => "nama_mengetahui", "caption" => "Nama Mengetahui", "dbcolumn" => "nama_mengetahui"),
			array("column" => "jabatan_mengetahui", "caption" => "Jabatan Mengetahui", "dbcolumn" => "jabatan_mengetahui"),
			array("column" => "no_bart", "caption" => "Nomor BART", "dbcolumn" => "no_bart"),
			array("column" => "tanggal_bart", "caption" => "Tanggal BART", "dbcolumn" => "tanggal_bart"),
		);

        $start = empty($this->input->post('start'))? 0:$this->input->post('start');
        $length = empty($this->input->post('length'))? null:$this->input->post('length');
        $order = empty($this->input->post('order')[0]['column'])? null:$this->input->post('order')[0]['column'];
        $dir = empty($this->input->post('order')[0]['dir'])? null:$this->input->post('order')[0]['dir'];
        
		$id_alokasi = $this->input->post('id_alokasi');

		$columns = array();
		foreach($cols as $key=>$val){
			array_push($columns,$val['column']);
		}

		$dbcolumns = array();
		foreach($cols as $key=>$val){
			array_push($dbcolumns,$val['dbcolumn']);
		}
     
        $totalData = $this->Baphp_model->get(null,$id_alokasi);
        $totalData = count($totalData);
            
        $totalFiltered = $totalData; 

        //search data percolumn
        $filtercond = '';
        for($i=0;$i<count($columns);$i++){
            if(!empty($this->input->post('columns')[$i]['search']['value'])){
                $search = $this->input->post('columns')[$i]['search']['value'];
                $filtercond  .= " and ".$dbcolumns[$i]." LIKE '%".$search."%'"; 
            }
        }

		$search = $this->input->post('search')['value']; 
		$posts_all_search =  $this->Baphp_model->get(null, $id_alokasi, null, null, null, null, $filtercond, $search);
        $totalFiltered = count($posts_all_search);
		$posts =  $this->Baphp_model->get(null, $id_alokasi, $start, $length, $order, $dir, $filtercond, $search);

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();
				foreach($cols as $val){
					$nestedData[$val['column']] = $post->{$val['column']};
				}

				$tools = '<div class="dropdown">
				<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				-- Pilih menu --
				</button>
				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					<a class="dropdown-item" href="#" onclick="LoadData('.$post->id.')">Lihat Data</a>';
				$tools .= '</div>
				</div>';
				
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

	public function baphp_persediaan()
    {
		$param['cols'] = array(
			array("column" => "tahun_anggaran", "caption" => "Tahun Anggaran", "dbcolumn" => "tahun_anggaran"),
			array("column" => "titik_serah", "caption" => "Titik Serah", "dbcolumn" => "titik_serah"),
			array("column" => "nama_wilayah", "caption" => "Nama Wilayah", "dbcolumn" => "nama_wilayah"),
			array("column" => "no_baphp", "caption" => "Nama Wilayah", "dbcolumn" => "no_baphp"),
			array("column" => "tanggal", "caption" => "Nama Wilayah", "dbcolumn" => "tanggal"),
			array("column" => "nama_barang", "caption" => "Nama Barang", "dbcolumn" => "nama_barang"),
			array("column" => "merk", "caption" => "Merk Barang", "dbcolumn" => "merk"),
			array("column" => "jumlah_barang", "caption" => "Jumlah Barang", "dbcolumn" => "jumlah_barang"),
			array("column" => "nilai_barang", "caption" => "Nilai Barang (Rp)", "dbcolumn" => "nilai_barang"),
			array("column" => "pihak_penyerah", "caption" => "Pihak Yang Menyerahkan", "dbcolumn" => "peny.nama_penyedia_pusat"),
			array("column" => "provinsi_penerima", "caption" => "Provinsi Yang Menerima", "dbcolumn" => "prov_pene.nama_provinsi"),
			array("column" => "kabupaten_penerima", "caption" => "Kabupaten/Kota Yang Menerima", "dbcolumn" => "kab_pene.nama_kabupaten"),
			array("column" => "no_kontrak", "caption" => "Nomor Kontrak", "dbcolumn" => "no_kontrak"),
			array("column" => "nama_penyerah", "caption" => "Nama Yang Menyerahkan", "dbcolumn" => "nama_penyerah"),
			array("column" => "jabatan_penyerah", "caption" => "Jabatan Yang Menyerahkan", "dbcolumn" => "jabatan_penyerah"),
			array("column" => "notelp_penyerah", "caption" => "No Telepon Yang Menyerahkan", "dbcolumn" => "notelp_penyerah"),
			array("column" => "alamat_penyerah", "caption" => "Alamat Yang Menyerahkan", "dbcolumn" => "alamat_penyerah"),
			array("column" => "provinsi_penyerah", "caption" => "Provinsi Yang Menyerahkan", "dbcolumn" => "prov_peny.nama_provinsi"),
			array("column" => "kabupaten_penyerah", "caption" => "Kabupaten/Kota Yang Menyerahkan", "dbcolumn" => "kab_peny.nama_kabupaten"),
			array("column" => "no_batitip", "caption" => "Nomor BA Penitipan Barang", "dbcolumn" => "no_batitip"),
			array("column" => "tanggal_batitip", "caption" => "Tanggal BA Penitipan Barang", "dbcolumn" => "tanggal_batitip"),
			array("column" => "no_bart", "caption" => "Nomor BART", "dbcolumn" => "no_bart"),
			array("column" => "tanggal_bart", "caption" => "Tanggal BART", "dbcolumn" => "tanggal_bart")
		);

		$this->load->library('parser');
		$id_kontrak_pusat = $this->input->get('id_kontrak_pusat');
		$param['kontrak_pusat'] = $this->Kontrak_pusat_model->get($id_kontrak_pusat);
		$param['total_unit'] = $this->Alokasi_pusat_model->total_unit(null, $id_kontrak_pusat);
        $param['total_nilai'] = $this->Alokasi_pusat_model->total_nilai(null, $id_kontrak_pusat);
	
		$data = array(
			'title' => 'Rekap Kontrak Pusat',
            'content-path' => strtoupper('Rekap Kontrak Pusat'),
            'content' => $this->load->view('rekap/baphp_persediaan', $param, TRUE),
        );
        $this->parser->parse('default_template', $data);
	}
	
	public function baphp_persediaan_json()
    {
		$cols = array(
			array("column" => "tahun_anggaran", "caption" => "Tahun Anggaran", "dbcolumn" => "tahun_anggaran"),
			array("column" => "titik_serah", "caption" => "Titik Serah", "dbcolumn" => "titik_serah"),
			array("column" => "nama_wilayah", "caption" => "Nama Wilayah", "dbcolumn" => "nama_wilayah"),
			array("column" => "no_baphp", "caption" => "Nama Wilayah", "dbcolumn" => "no_baphp"),
			array("column" => "tanggal", "caption" => "Nama Wilayah", "dbcolumn" => "tanggal"),
			array("column" => "nama_barang", "caption" => "Nama Barang", "dbcolumn" => "nama_barang"),
			array("column" => "merk", "caption" => "Merk Barang", "dbcolumn" => "merk"),
			array("column" => "jumlah_barang", "caption" => "Jumlah Barang", "dbcolumn" => "jumlah_barang"),
			array("column" => "nilai_barang", "caption" => "Nilai Barang (Rp)", "dbcolumn" => "nilai_barang"),
			array("column" => "pihak_penyerah", "caption" => "Pihak Yang Menyerahkan", "dbcolumn" => "peny.nama_penyedia_pusat"),
			array("column" => "provinsi_penerima", "caption" => "Provinsi Yang Menerima", "dbcolumn" => "prov_pene.nama_provinsi"),
			array("column" => "kabupaten_penerima", "caption" => "Kabupaten/Kota Yang Menerima", "dbcolumn" => "kab_pene.nama_kabupaten"),
			array("column" => "no_kontrak", "caption" => "Nomor Kontrak", "dbcolumn" => "no_kontrak"),
			array("column" => "nama_penyerah", "caption" => "Nama Yang Menyerahkan", "dbcolumn" => "nama_penyerah"),
			array("column" => "jabatan_penyerah", "caption" => "Jabatan Yang Menyerahkan", "dbcolumn" => "jabatan_penyerah"),
			array("column" => "notelp_penyerah", "caption" => "No Telepon Yang Menyerahkan", "dbcolumn" => "notelp_penyerah"),
			array("column" => "alamat_penyerah", "caption" => "Alamat Yang Menyerahkan", "dbcolumn" => "alamat_penyerah"),
			array("column" => "provinsi_penyerah", "caption" => "Provinsi Yang Menyerahkan", "dbcolumn" => "prov_peny.nama_provinsi"),
			array("column" => "kabupaten_penyerah", "caption" => "Kabupaten/Kota Yang Menyerahkan", "dbcolumn" => "kab_peny.nama_kabupaten"),
			array("column" => "no_batitip", "caption" => "Nomor BA Penitipan Barang", "dbcolumn" => "no_batitip"),
			array("column" => "tanggal_batitip", "caption" => "Tanggal BA Penitipan Barang", "dbcolumn" => "tanggal_batitip"),
			array("column" => "no_bart", "caption" => "Nomor BART", "dbcolumn" => "no_bart"),
			array("column" => "tanggal_bart", "caption" => "Tanggal BART", "dbcolumn" => "tanggal_bart")
		);

        $start = empty($this->input->post('start'))? 0:$this->input->post('start');
        $length = empty($this->input->post('length'))? null:$this->input->post('length');
        $order = empty($this->input->post('order')[0]['column'])? null:$this->input->post('order')[0]['column'];
        $dir = empty($this->input->post('order')[0]['dir'])? null:$this->input->post('order')[0]['dir'];
        
		$id_alokasi_persediaan = $this->input->post('id_alokasi_persediaan');

		$columns = array();
		foreach($cols as $key=>$val){
			array_push($columns,$val['column']);
		}

		$dbcolumns = array();
		foreach($cols as $key=>$val){
			array_push($dbcolumns,$val['dbcolumn']);
		}
     
        $totalData = $this->Baphp_persediaan_model->get(null,$id_alokasi_persediaan);
        $totalData = count($totalData);
            
        $totalFiltered = $totalData; 

        //search data percolumn
        $filtercond = '';
        for($i=0;$i<count($columns);$i++){
            if(!empty($this->input->post('columns')[$i]['search']['value'])){
                $search = $this->input->post('columns')[$i]['search']['value'];
                $filtercond  .= " and ".$dbcolumns[$i]." LIKE '%".$search."%'"; 
            }
        }

		$search = $this->input->post('search')['value']; 
		$posts_all_search =  $this->Baphp_persediaan_model->get(null, $id_alokasi_persediaan, null, null, null, null, $filtercond, $search);
        $totalFiltered = count($posts_all_search);
		$posts =  $this->Baphp_persediaan_model->get(null, $id_alokasi_persediaan, $start, $length, $order, $dir, $filtercond, $search);

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();
				foreach($cols as $val){
					$nestedData[$val['column']] = $post->{$val['column']};
				}

				$tools = '<div class="dropdown">
				<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				-- Pilih menu --
				</button>
				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					<a class="dropdown-item" href="#" onclick="LoadData('.$post->id.')">Lihat Data</a>';
				$tools .= '</div>
				</div>';
				
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
	
	public function bastb()
    {
		$param['cols'] = array(
			array("column" => "tahun_anggaran", "caption" => "Tahun Anggaran", "dbcolumn" => "tahun_anggaran"),
			array("column" => "kelompok_penerima", "caption" => "Kelompok Penerima", "dbcolumn" => "kelompok_penerima"),
			array("column" => "no_bastb", "caption" => "Nomor BASTB", "dbcolumn" => "no_bastb"),
			array("column" => "tanggal", "caption" => "Tanggal BASTB", "dbcolumn" => "tanggal"),
			array("column" => "pihak_penyerah", "caption" => "Pihak Yang Menyerahkan", "dbcolumn" => "pihak_penyerah"),
			array("column" => "nama_penyerah", "caption" => "Nama Yang Menyerahkan", "dbcolumn" => "nama_penyerah"),
			array("column" => "jabatan_penyerah", "caption" => "Jabatan Yang Menyerahkan", "dbcolumn" => "jabatan_penyerah"),
			array("column" => "notelp_penyerah", "caption" => "No Telepon Yang Menyerahkan", "dbcolumn" => "notelp_penyerah"),
			array("column" => "alamat_penyerah", "caption" => "Alamat Yang Menyerahkan", "dbcolumn" => "alamat_penyerah"),
			array("column" => "provinsi_penyerah", "caption" => "Provinsi Yang Menyerahkan", "dbcolumn" => "prov_peny.nama_provinsi"),
			array("column" => "kabupaten_penyerah", "caption" => "Kabupaten/Kota Yang Menyerahkan", "dbcolumn" => "kab_peny.nama_kabupaten"),
			array("column" => "pihak_penerima", "caption" => "Pihak Yang Menerima", "dbcolumn" => "pihak_penerima"),
			array("column" => "provinsi_penerima", "caption" => "Provinsi Yang Menerima", "dbcolumn" => "prov_pene.nama_provinsi"),
			array("column" => "kabupaten_penerima", "caption" => "Kabupaten/Kota Yang Menerima", "dbcolumn" => "kab_pene.nama_kabupaten"),
			array("column" => "nama_kecamatan", "caption" => "Kecamatan Yang Menerima", "dbcolumn" => "nama_kecamatan"),
			array("column" => "nama_kelurahan", "caption" => "Kelurahan Yang Menerima", "dbcolumn" => "nama_kelurahan"),
			array("column" => "nama_barang", "caption" => "Nama Barang", "dbcolumn" => "nama_barang"),
			array("column" => "merk", "caption" => "Merk Barang", "dbcolumn" => "merk"),
			array("column" => "jumlah_barang", "caption" => "Jumlah Barang", "dbcolumn" => "jumlah_barang"),
			array("column" => "nilai_barang", "caption" => "Nilai Barang (Rp)", "dbcolumn" => "nilai_barang"),
			array("column" => "harga_satuan", "caption" => "Harga Satuan (Rp)", "dbcolumn" => "harga_satuan"),
			array("column" => "no_kontrak", "caption" => "Nomor Kontrak", "dbcolumn" => "no_kontrak"),
			array("column" => "nama_mengetahui", "caption" => "Nama Mengetahui", "dbcolumn" => "nama_mengetahui"),
			array("column" => "jabatan_mengetahui", "caption" => "Jabatan Mengetahui", "dbcolumn" => "jabatan_mengetahui"),
		);

		$this->load->library('parser');
		$id_kontrak_pusat = $this->input->get('id_kontrak_pusat');
		$param['kontrak_pusat'] = $this->Kontrak_pusat_model->get($id_kontrak_pusat);
		$param['total_unit'] = $this->Alokasi_pusat_model->total_unit(null, $id_kontrak_pusat);
        $param['total_nilai'] = $this->Alokasi_pusat_model->total_nilai(null, $id_kontrak_pusat);
				
		$data = array(
			'title' => 'Rekap Kontrak Pusat',
            'content-path' => strtoupper('Rekap Kontrak Pusat'),
            'content' => $this->load->view('rekap/bastb', $param, TRUE),
        );
        $this->parser->parse('default_template', $data);
	}
	
	public function bastb_json()
    {
		$cols = array(
			array("column" => "tahun_anggaran", "caption" => "Tahun Anggaran", "dbcolumn" => "tahun_anggaran"),
			array("column" => "kelompok_penerima", "caption" => "Kelompok Penerima", "dbcolumn" => "kelompok_penerima"),
			array("column" => "no_bastb", "caption" => "Nomor BASTB", "dbcolumn" => "no_bastb"),
			array("column" => "tanggal", "caption" => "Tanggal BASTB", "dbcolumn" => "tanggal"),
			array("column" => "pihak_penyerah", "caption" => "Pihak Yang Menyerahkan", "dbcolumn" => "pihak_penyerah"),
			array("column" => "nama_penyerah", "caption" => "Nama Yang Menyerahkan", "dbcolumn" => "nama_penyerah"),
			array("column" => "jabatan_penyerah", "caption" => "Jabatan Yang Menyerahkan", "dbcolumn" => "jabatan_penyerah"),
			array("column" => "notelp_penyerah", "caption" => "No Telepon Yang Menyerahkan", "dbcolumn" => "notelp_penyerah"),
			array("column" => "alamat_penyerah", "caption" => "Alamat Yang Menyerahkan", "dbcolumn" => "alamat_penyerah"),
			array("column" => "provinsi_penyerah", "caption" => "Provinsi Yang Menyerahkan", "dbcolumn" => "prov_peny.nama_provinsi"),
			array("column" => "kabupaten_penyerah", "caption" => "Kabupaten/Kota Yang Menyerahkan", "dbcolumn" => "kab_peny.nama_kabupaten"),
			array("column" => "pihak_penerima", "caption" => "Pihak Yang Menerima", "dbcolumn" => "pihak_penerima"),
			array("column" => "provinsi_penerima", "caption" => "Provinsi Yang Menerima", "dbcolumn" => "prov_pene.nama_provinsi"),
			array("column" => "kabupaten_penerima", "caption" => "Kabupaten/Kota Yang Menerima", "dbcolumn" => "kab_pene.nama_kabupaten"),
			array("column" => "nama_kecamatan", "caption" => "Kecamatan Yang Menerima", "dbcolumn" => "nama_kecamatan"),
			array("column" => "nama_kelurahan", "caption" => "Kelurahan Yang Menerima", "dbcolumn" => "nama_kelurahan"),
			array("column" => "nama_barang", "caption" => "Nama Barang", "dbcolumn" => "nama_barang"),
			array("column" => "merk", "caption" => "Merk Barang", "dbcolumn" => "merk"),
			array("column" => "jumlah_barang", "caption" => "Jumlah Barang", "dbcolumn" => "jumlah_barang"),
			array("column" => "nilai_barang", "caption" => "Nilai Barang (Rp)", "dbcolumn" => "nilai_barang"),
			array("column" => "harga_satuan", "caption" => "Harga Satuan (Rp)", "dbcolumn" => "harga_satuan"),
			array("column" => "no_kontrak", "caption" => "Nomor Kontrak", "dbcolumn" => "no_kontrak"),
			array("column" => "nama_mengetahui", "caption" => "Nama Mengetahui", "dbcolumn" => "nama_mengetahui"),
			array("column" => "jabatan_mengetahui", "caption" => "Jabatan Mengetahui", "dbcolumn" => "jabatan_mengetahui"),
		);

		$start = empty($this->input->post('start'))? 0:$this->input->post('start');
        $length = empty($this->input->post('length'))? null:$this->input->post('length');
        $order = empty($this->input->post('order')[0]['column'])? null:$this->input->post('order')[0]['column'];
        $dir = empty($this->input->post('order')[0]['dir'])? null:$this->input->post('order')[0]['dir'];
        
		$id_alokasi = $this->input->post('id_alokasi');

		$columns = array();
		foreach($cols as $key=>$val){
			array_push($columns,$val['column']);
		}

		$dbcolumns = array();
		foreach($cols as $key=>$val){
			array_push($dbcolumns,$val['dbcolumn']);
		}
     
        $totalData = $this->Bastb_pusat_model->get(null,$id_alokasi);
        $totalData = count($totalData);
            
        $totalFiltered = $totalData; 

        //search data percolumn
        $filtercond = '';
        for($i=0;$i<count($columns);$i++){
            if(!empty($this->input->post('columns')[$i]['search']['value'])){
                $search = $this->input->post('columns')[$i]['search']['value'];
                $filtercond  .= " and ".$dbcolumns[$i]." LIKE '%".$search."%'"; 
            }
        }

		$search = $this->input->post('search')['value']; 
		$posts_all_search =  $this->Bastb_pusat_model->get(null, $id_alokasi, null, null, null, null, $filtercond, $search);
        $totalFiltered = count($posts_all_search);
		$posts =  $this->Bastb_pusat_model->get(null, $id_alokasi, $start, $length, $order, $dir, $filtercond, $search);

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();
				foreach($cols as $val){
					$nestedData[$val['column']] = $post->{$val['column']};
				}

				$tools = '<div class="dropdown">
				<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				-- Pilih menu --
				</button>
				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
				<a class="dropdown-item" href="#" onclick="LoadData('.$post->id.')">Lihat Data</a>';
				$tools .= '</div>
				</div>';
				
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
	
	public function hibah()
    {
		$param['cols'] = array(
			array("column" => "tahun_anggaran", "caption" => "Jabatan Mengetahui", "dbcolumn" => "tahun_anggaran"),
			array("column" => "tanggal_naskah_hibah", "caption" => "Jabatan Mengetahui", "dbcolumn" => "tanggal_naskah_hibah"),
			array("column" => "no_naskah_hibah", "caption" => "Jabatan Mengetahui", "dbcolumn" => "no_naskah_hibah"),
			array("column" => "tanggal_bast_bmn", "caption" => "Jabatan Mengetahui", "dbcolumn" => "tanggal_bast_bmn"),
			array("column" => "no_bast_bmn", "caption" => "Jabatan Mengetahui", "dbcolumn" => "no_bast_bmn"),
			array("column" => "tanggal_surat_pernyataan", "caption" => "Jabatan Mengetahui", "dbcolumn" => "tanggal_surat_pernyataan"),
			array("column" => "no_surat_pernyataan", "caption" => "Jabatan Mengetahui", "dbcolumn" => "no_surat_pernyataan"),
			array("column" => "nama_provinsi", "caption" => "Jabatan Mengetahui", "dbcolumn" => "nama_provinsi"),
			array("column" => "nama_kabupaten", "caption" => "Jabatan Mengetahui", "dbcolumn" => "nama_kabupaten"),
			array("column" => "total_unit", "caption" => "Jabatan Mengetahui", "dbcolumn" => "total_unit"),
			array("column" => "total_nilai", "caption" => "Jabatan Mengetahui", "dbcolumn" => "total_nilai"),
			array("column" => "nama_penyerah", "caption" => "Jabatan Mengetahui", "dbcolumn" => "nama_penyerah"),
			array("column" => "nip_penyerah", "caption" => "Jabatan Mengetahui", "dbcolumn" => "nip_penyerah"),
			array("column" => "pangkat_penyerah", "caption" => "Jabatan Mengetahui", "dbcolumn" => "pangkat_penyerah"),
			array("column" => "jabatan_penyerah", "caption" => "Jabatan Mengetahui", "dbcolumn" => "jabatan_penyerah"),
			array("column" => "alamat_dinas_penyerah", "caption" => "Jabatan Mengetahui", "dbcolumn" => "alamat_dinas_penyerah"),
			array("column" => "titik_serah", "caption" => "Jabatan Mengetahui", "dbcolumn" => "titik_serah"),
			array("column" => "nama_wilayah", "caption" => "Jabatan Mengetahui", "dbcolumn" => "nama_wilayah"),
			array("column" => "instansi_penerima", "caption" => "Jabatan Mengetahui", "dbcolumn" => "instansi_penerima"),
			array("column" => "nama_penerima", "caption" => "Jabatan Mengetahui", "dbcolumn" => "nama_penerima"),
			array("column" => "nip_penerima", "caption" => "Jabatan Mengetahui", "dbcolumn" => "nip_penerima"),
			array("column" => "pangkat_penerima", "caption" => "Jabatan Mengetahui", "dbcolumn" => "pangkat_penerima"),
			array("column" => "jabatan_penerima", "caption" => "Jabatan Mengetahui", "dbcolumn" => "jabatan_penerima"),
			array("column" => "alamat_dinas_penerima", "caption" => "Jabatan Mengetahui", "dbcolumn" => "alamat_dinas_penerima"),
			array("column" => "ketfoto", "caption" => "Jabatan Mengetahui", "dbcolumn" => "ketfoto"),
		);

		$this->load->library('parser');
		$id_kontrak_pusat = $this->input->get('id_kontrak_pusat');
		$param['kontrak_pusat'] = $this->Kontrak_pusat_model->get($id_kontrak_pusat);
		$param['total_unit'] = $this->Alokasi_pusat_model->total_unit(null, $id_kontrak_pusat);
        $param['total_nilai'] = $this->Alokasi_pusat_model->total_nilai(null, $id_kontrak_pusat);
		
		$data = array(
			'title' => 'Rekap Kontrak Pusat',
            'content-path' => strtoupper('Rekap Kontrak Pusat'),
            'content' => $this->load->view('rekap/hibah', $param, TRUE),
        );
        $this->parser->parse('default_template', $data);
	}
	
	public function hibah_json()
    {
		$cols = array(
			array("column" => "tahun_anggaran", "caption" => "Jabatan Mengetahui", "dbcolumn" => "tahun_anggaran"),
			array("column" => "tanggal_naskah_hibah", "caption" => "Jabatan Mengetahui", "dbcolumn" => "tanggal_naskah_hibah"),
			array("column" => "no_naskah_hibah", "caption" => "Jabatan Mengetahui", "dbcolumn" => "no_naskah_hibah"),
			array("column" => "tanggal_bast_bmn", "caption" => "Jabatan Mengetahui", "dbcolumn" => "tanggal_bast_bmn"),
			array("column" => "no_bast_bmn", "caption" => "Jabatan Mengetahui", "dbcolumn" => "no_bast_bmn"),
			array("column" => "tanggal_surat_pernyataan", "caption" => "Jabatan Mengetahui", "dbcolumn" => "tanggal_surat_pernyataan"),
			array("column" => "no_surat_pernyataan", "caption" => "Jabatan Mengetahui", "dbcolumn" => "no_surat_pernyataan"),
			array("column" => "nama_provinsi", "caption" => "Jabatan Mengetahui", "dbcolumn" => "nama_provinsi"),
			array("column" => "nama_kabupaten", "caption" => "Jabatan Mengetahui", "dbcolumn" => "nama_kabupaten"),
			array("column" => "total_unit", "caption" => "Jabatan Mengetahui", "dbcolumn" => "total_unit"),
			array("column" => "total_nilai", "caption" => "Jabatan Mengetahui", "dbcolumn" => "total_nilai"),
			array("column" => "nama_penyerah", "caption" => "Jabatan Mengetahui", "dbcolumn" => "nama_penyerah"),
			array("column" => "nip_penyerah", "caption" => "Jabatan Mengetahui", "dbcolumn" => "nip_penyerah"),
			array("column" => "pangkat_penyerah", "caption" => "Jabatan Mengetahui", "dbcolumn" => "pangkat_penyerah"),
			array("column" => "jabatan_penyerah", "caption" => "Jabatan Mengetahui", "dbcolumn" => "jabatan_penyerah"),
			array("column" => "alamat_dinas_penyerah", "caption" => "Jabatan Mengetahui", "dbcolumn" => "alamat_dinas_penyerah"),
			array("column" => "titik_serah", "caption" => "Jabatan Mengetahui", "dbcolumn" => "titik_serah"),
			array("column" => "nama_wilayah", "caption" => "Jabatan Mengetahui", "dbcolumn" => "nama_wilayah"),
			array("column" => "instansi_penerima", "caption" => "Jabatan Mengetahui", "dbcolumn" => "instansi_penerima"),
			array("column" => "nama_penerima", "caption" => "Jabatan Mengetahui", "dbcolumn" => "nama_penerima"),
			array("column" => "nip_penerima", "caption" => "Jabatan Mengetahui", "dbcolumn" => "nip_penerima"),
			array("column" => "pangkat_penerima", "caption" => "Jabatan Mengetahui", "dbcolumn" => "pangkat_penerima"),
			array("column" => "jabatan_penerima", "caption" => "Jabatan Mengetahui", "dbcolumn" => "jabatan_penerima"),
			array("column" => "alamat_dinas_penerima", "caption" => "Jabatan Mengetahui", "dbcolumn" => "alamat_dinas_penerima"),
			array("column" => "ketfoto", "caption" => "Jabatan Mengetahui", "dbcolumn" => "ketfoto"),
		);

		$start = empty($this->input->post('start'))? 0:$this->input->post('start');
        $length = empty($this->input->post('length'))? null:$this->input->post('length');
        $order = empty($this->input->post('order')[0]['column'])? null:$this->input->post('order')[0]['column'];
        $dir = empty($this->input->post('order')[0]['dir'])? null:$this->input->post('order')[0]['dir'];
        
		$id_alokasi = $this->input->post('id_alokasi');

		$columns = array();
		foreach($cols as $key=>$val){
			array_push($columns,$val['column']);
		}

		$dbcolumns = array();
		foreach($cols as $key=>$val){
			array_push($dbcolumns,$val['dbcolumn']);
		}
     
        $totalData = $this->HibahPusatModel->get(null,$id_alokasi);
        $totalData = count($totalData);
            
        $totalFiltered = $totalData; 

        //search data percolumn
        $filtercond = '';
        for($i=0;$i<count($columns);$i++){
            if(!empty($this->input->post('columns')[$i]['search']['value'])){
                $search = $this->input->post('columns')[$i]['search']['value'];
                $filtercond  .= " and ".$dbcolumns[$i]." LIKE '%".$search."%'"; 
            }
        }

		$search = $this->input->post('search')['value']; 
		$posts_all_search =  $this->HibahPusatModel->get(null, $id_alokasi, null, null, null, null, $filtercond, $search);
        $totalFiltered = count($posts_all_search);
		$posts =  $this->HibahPusatModel->get(null, $id_alokasi, $start, $length, $order, $dir, $filtercond, $search);

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();
				foreach($cols as $val){
					$nestedData[$val['column']] = $post->{$val['column']};
				}

				$tools = '<div class="dropdown">
				<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				-- Pilih menu --
				</button>
				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
				<a class="dropdown-item" href="#" onclick="LoadData('.$post->id.')">Lihat Data</a>';
				$tools .= '</div>
				</div>';
				
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
	
	public function data_ongkir()
    {
		$param['cols'] = array(
			array("column" => "nama_penyedia_pusat", "caption" => "Nama Penyedia", "dbcolumn" => "nama_penyedia_pusat"),
			array("column" => "no_baphp", "caption" => "Nomor BAPHP", "dbcolumn" => "tb_baphp_reguler.no_baphp"),
			array("column" => "tanggal_baphp", "caption" => "Tanggal BAPHP", "dbcolumn" => "tb_baphp_reguler.tanggal"),
			array("column" => "nama_barang", "caption" => "Nama Barang", "dbcolumn" => "tb_kontrak_pusat.nama_barang"),
			array("column" => "merk", "caption" => "Merk Barang", "dbcolumn" => "tb_kontrak_pusat.merk"),
			array("column" => "jumlah_barang", "caption" => "Total Unit", "dbcolumn" => "tb_kontrak_pusat.jumlah_barang"),
			array("column" => "ongkir", "caption" => "Nilai (Rp)", "dbcolumn" => "ongkir"),
		);

		$this->load->library('parser');
		$id_kontrak_pusat = $this->input->get('id_kontrak_pusat');
		$param['kontrak_pusat'] = $this->Kontrak_pusat_model->get($id_kontrak_pusat);
		$param['total_unit'] = $this->Alokasi_pusat_model->total_unit(null, $id_kontrak_pusat);
        $param['total_nilai'] = $this->Alokasi_pusat_model->total_nilai(null, $id_kontrak_pusat);
		
		$data = array(
			'title' => 'Rekap Kontrak Pusat',
            'content-path' => strtoupper('Rekap Kontrak Pusat'),
            'content' => $this->load->view('rekap/ongkir', $param, TRUE),
        );
        $this->parser->parse('default_template', $data);
	}
	
	public function data_ongkir_json()
    {
		$cols = array(
			array("column" => "nama_penyedia_pusat", "caption" => "Nama Penyedia", "dbcolumn" => "nama_penyedia_pusat"),
			array("column" => "no_baphp", "caption" => "Nomor BAPHP", "dbcolumn" => "tb_baphp_reguler.no_baphp"),
			array("column" => "tanggal_baphp", "caption" => "Tanggal BAPHP", "dbcolumn" => "tb_baphp_reguler.tanggal"),
			array("column" => "nama_barang", "caption" => "Nama Barang", "dbcolumn" => "tb_kontrak_pusat.nama_barang"),
			array("column" => "merk", "caption" => "Merk Barang", "dbcolumn" => "tb_kontrak_pusat.merk"),
			array("column" => "jumlah_barang", "caption" => "Total Unit", "dbcolumn" => "tb_kontrak_pusat.jumlah_barang"),
			array("column" => "ongkir", "caption" => "Nilai (Rp)", "dbcolumn" => "ongkir"),
		);   

		$id_baphp = $this->input->post('id_baphp');
        $start = empty($this->input->post('start'))? 0:$this->input->post('start');
        $length = empty($this->input->post('length'))? null:$this->input->post('length');
        $order = empty($this->input->post('order')[0]['column'])? null:$this->input->post('order')[0]['column'];
        $dir = empty($this->input->post('order')[0]['dir'])? null:$this->input->post('order')[0]['dir'];
        
		$columns = array();
		foreach($cols as $key=>$val){
			array_push($columns,$val['column']);
		}

		$dbcolumns = array();
		foreach($cols as $key=>$val){
			array_push($dbcolumns,$val['dbcolumn']);
		}
     
        $totalData = $this->Ongkir_model->get(null,$id_baphp);
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
		$posts_all_search =  $this->Ongkir_model->get(null, $id_baphp,null, null, null, null, $filtercond, $search);
        $totalFiltered = count($posts_all_search);
		$posts =  $this->Ongkir_model->get(null, $id_baphp, $start, $length, $order, $dir, $filtercond, $search);

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();
				foreach($cols as $val){
					$nestedData[$val['column']] = $post->{$val['column']};
				}

                $tools =  "<a class='btn btn-xs btn-success btn-sm'><i class='glyphicon glyphicon-zoom-in' onclick='LoadData(".$post->id.")'></i></a>";
                if($bolehedit)
                    $tools .= "<a class='btn btn-xs btn-primary btn-sm' href='".base_url('Ongkir/edit?id=').$post->id."'><i class='glyphicon glyphicon-pencil'></i></a>
                <a class='btn btn-xs btn-info btn-sm' data-href='#' data-toggle='modal' data-record-title='".$post->id."' data-target='#upload-modal' data-record-id='".$post->id."'><i class='glyphicon glyphicon-open-file'></i></a>";
                if($bolehhapus)
                    $tools .= "<a class='btn btn-xs btn-danger btn-sm' data-href='".base_url('Ongkir/destroy?id=').$post->id."' data-toggle='modal' data-record-title='".$post->id."' data-target='#confirm-delete'><i class='glyphicon glyphicon-trash'></i></a>";
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
	
	public function pemanfaatan()
    {
		$param['cols'] = array(
			array("column" => "periode_mulai", "caption" => "Periode Tanggal Mulai Digunakan", "dbcolumn" => "periode_mulai"),
			array("column" => "total_area", "caption" => "Total Area Yang Dikerjakan (Ha)", "dbcolumn" => "total_area"),
			array("column" => "kondisi", "caption" => "Kondisi Alsintan Saat Ini", "dbcolumn" => "kondisi"),
			array("column" => "perawatan", "caption" => "Perawatan Yang Telah Dilakukan", "dbcolumn" => "perawatan"),
			array("column" => "keterangan", "caption" => "Keterangan", "dbcolumn" => "keterangan"),
			array("column" => "tanggal_laporan", "caption" => "Tanggal Kirim Laporan", "dbcolumn" => "tanggal_laporan"),
		);

		$this->load->library('parser');
		$id_kontrak_pusat = $this->input->get('id_kontrak_pusat');
		$param['kontrak_pusat'] = $this->Kontrak_pusat_model->get($id_kontrak_pusat);
		$param['total_unit'] = $this->Alokasi_pusat_model->total_unit(null, $id_kontrak_pusat);
        $param['total_nilai'] = $this->Alokasi_pusat_model->total_nilai(null, $id_kontrak_pusat);
		
		$data = array(
			'title' => 'Rekap Kontrak Pusat',
            'content-path' => strtoupper('Rekap Kontrak Pusat'),
            'content' => $this->load->view('rekap/pemanfaatan', $param, TRUE),
        );
        $this->parser->parse('default_template', $data);
	}
	
	public function pemanfaatan_json()
    {
		$cols = array(
			array("column" => "periode_mulai", "caption" => "Periode Tanggal Mulai Digunakan", "dbcolumn" => "periode_mulai"),
			array("column" => "total_area", "caption" => "Total Area Yang Dikerjakan (Ha)", "dbcolumn" => "total_area"),
			array("column" => "kondisi", "caption" => "Kondisi Alsintan Saat Ini", "dbcolumn" => "kondisi"),
			array("column" => "perawatan", "caption" => "Perawatan Yang Telah Dilakukan", "dbcolumn" => "perawatan"),
			array("column" => "keterangan", "caption" => "Keterangan", "dbcolumn" => "keterangan"),
			array("column" => "tanggal_laporan", "caption" => "Tanggal Kirim Laporan", "dbcolumn" => "tanggal_laporan"),
		);

		$start = empty($this->input->post('start'))? 0:$this->input->post('start');
        $length = empty($this->input->post('length'))? null:$this->input->post('length');
        $order = empty($this->input->post('order')[0]['column'])? null:$this->input->post('order')[0]['column'];
        $dir = empty($this->input->post('order')[0]['dir'])? null:$this->input->post('order')[0]['dir'];
        
		$columns = array();
		foreach($cols as $key=>$val){
			array_push($columns,$val['column']);
		}

		$dbcolumns = array();
		foreach($cols as $key=>$val){
			array_push($dbcolumns,$val['dbcolumn']);
		}
     
        $totalData = $this->Laporan_pemanfaatan_model->get(null,$id_bastb_pusat);
        $totalData = count($totalData);
            
        $totalFiltered = $totalData; 

        //search data percolumn
        $filtercond = '';
        for($i=0;$i<count($columns);$i++){
            if(!empty($this->input->post('columns')[$i]['search']['value'])){
                $search = $this->input->post('columns')[$i]['search']['value'];
                $filtercond  .= " and ".$dbcolumns[$i]." LIKE '%".$search."%'"; 
            }
        }

		$search = $this->input->post('search')['value']; 
		$posts_all_search =  $this->Laporan_pemanfaatan_model->get(null, $id_bastb_pusat, null, null, null, null, $filtercond, $search);
        $totalFiltered = count($posts_all_search);
		$posts =  $this->Laporan_pemanfaatan_model->get(null, $id_bastb_pusat, $start, $length, $order, $dir, $filtercond, $search);

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData = array();
				foreach($cols as $val){
					$nestedData[$val['column']] = $post->{$val['column']};
				}
				$nestedData['periode_mulai'] = date('d-m-Y', strtotime($nestedData['periode_mulai']));
				$nestedData['tanggal_laporan'] = date('d-m-Y', strtotime($nestedData['tanggal_laporan']));

				$tools = '<div class="dropdown">
				<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				-- Pilih menu --
				</button>
				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					<a class="dropdown-item" href="#" data-href="#" data-toggle="modal" data-record-title="'.$post->id.'" data-target="#upload-modal" data-record-id="'.$post->id.'">Upload Dokumen</a>
					<a class="dropdown-item" href="#" onclick="LoadData('.$post->id.')">Lihat Data</a>';
				if($bolehedit)
					$tools .= '<a class="dropdown-item" href="'.base_url('Laporan_pemanfaatan/edit?id='.$post->id).'">Ubah Data</a>';
				if($bolehhapus)
					$tools .= '<a class="dropdown-item" href="'.base_url('Laporan_pemanfaatan/destroy/').$post->id.'">Hapus Data</a>';
				$tools .= '</div>
				</div>';
				
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
}
	