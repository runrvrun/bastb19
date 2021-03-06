<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bapsthp_pusat extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('Bapsthp_pusat_model');
		$this->load->model('PenyediaPusatModel');
		$this->load->model('JenisBarangPusatModel');
		$this->load->model('ProvinsiModel');
		$this->load->model('Alokasi_pusat_model');
		$this->load->helper('url');
		$this->load->library('xlsxwriter');
	}

	public function index()
	{
		$regcad = $this->input->get('regcad');
		if ($regcad == 'REG') $regcadtext = 'Reguler';
		if ($regcad == 'CAD') $regcadtext = 'Cadangan';
		$this->load->library('parser');
		$param['confirmed'] = 0;
		$param['regcad'] = $regcad;
		$param['bapsthp_pusat'] = array();
		$param['total_unit'] = $this->Bapsthp_pusat_model->total_unit();
		$param['total_nilai'] = $this->Bapsthp_pusat_model->total_nilai();
		$param['total_unit_alokasi'] = $this->Alokasi_pusat_model->total_unit();
		$param['total_nilai_alokasi'] = $this->Alokasi_pusat_model->total_nilai();
		$data = array(
	        'title' => 'BAP-STHP '. $regcadtext,
	        'content-path' => 'PENGADAAN PUSAT / BAP-STHP',
	        'content' => $this->load->view('bapsthp-pusat/index', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function index_json()
	{
		$bolehtambah = 0;
		$bolehedit = 0;
		$bolehhapus = 0;
		$regcad = $this->input->post('regcad');
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

		$columns = array( 
            0 =>'tahun_anggaran', 
            1 =>'titik_serah',
            2 =>'nama_wilayah',
            3 => 'no_bapsthp',
            4 => 'tanggal',
            5 => 'nama_penyedia_pusat',
            6 => 'nama_penyerah',
            7 => 'jabatan_penyerah',
            8 => 'notelp_penyerah',
            9 => 'alamat_penyerah',
            10 => 'prov_peny.nama_provinsi',
            11 => 'kab_peny.nama_kabupaten',
            12 => 'pihak_penerima',
            13 => 'nama_penerima',
            14 => 'jabatan_penerima',
            15 => 'notelp_penerima',
            16 => 'alamat_penerima',
            17 => 'prov_pene.nama_provinsi',
            18 => 'kab_pene.nama_kabupaten',
            19 => 'no_kontrak',
            20 => 'nama_barang',
            21 => 'merk',
            22 => 'jumlah_barang',
            23 => 'nilai_barang',
            24 => 'nama_mengetahui',
            25 => 'jabatan_mengetahui',
            26 => 'id',
            27 => 'id',
        );

        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

		$totalData = $this->Bapsthp_pusat_model->get();
        $totalData = count($totalData);
            
        $totalFiltered = $totalData; 
        $filtercond = '';

        $posts = $this->Bapsthp_pusat_model->get(null, $regcad, $_POST['start'], $_POST['length'], $order, $dir, $filtercond, null);

		if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $nestedData['tahun_anggaran'] = $post->tahun_anggaran;
                $nestedData['titik_serah'] = $post->titik_serah;
                $nestedData['nama_wilayah'] = $post->nama_wilayah;
                $nestedData['no_bapsthp'] = $post->no_bapsthp;
                $nestedData['tanggal'] = date('d-m-Y', strtotime($post->tanggal));

                $nestedData['pihak_penyerah'] = $post->pihak_penyerah;
                $nestedData['nama_penyerah'] = $post->nama_penyerah;
                $nestedData['jabatan_penyerah'] = $post->jabatan_penyerah;
                $nestedData['notelp_penyerah'] = $post->notelp_penyerah;
                $nestedData['alamat_penyerah'] = $post->alamat_penyerah;
                $nestedData['provinsi_penyerah'] = $post->provinsi_penyerah;
                $nestedData['kabupaten_penyerah'] = $post->kabupaten_penyerah;

                $nestedData['pihak_penerima'] = $post->pihak_penerima;
                $nestedData['nama_penerima'] = $post->nama_penerima;
                $nestedData['jabatan_penerima'] = $post->jabatan_penerima;
                $nestedData['notelp_penerima'] = $post->notelp_penerima;
                $nestedData['alamat_penerima'] = $post->alamat_penerima;
                $nestedData['provinsi_penerima'] = $post->provinsi_penerima;
                $nestedData['kabupaten_penerima'] = $post->kabupaten_penerima;

                $nestedData['no_kontrak'] = $post->no_kontrak;
                $nestedData['nama_barang'] = $post->nama_barang;
                $nestedData['merk'] = $post->merk;
                $nestedData['jumlah_barang'] = number_format($post->jumlah_barang, 0);
                $nestedData['nilai_barang'] = number_format($post->nilai_barang, 0);

                $nestedData['nama_mengetahui'] = $post->nama_mengetahui;
                $nestedData['jabatan_mengetahui'] = $post->jabatan_mengetahui;

                $nestedData['ketfoto'] = (($post->nama_file != '' and $post->nama_file != '[]' and $post->nama_file != 'null' and !is_null($post->nama_file)) ?  'TERSEDIA' : 'TDK TERSEDIA');

                $tools = "";

                $tools .= "<a class='btn btn-xs btn-success btn-sm'><i class='glyphicon glyphicon-zoom-in' onclick='LoadData(".$post->id.")'></i> View</a>";

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

	public function create()
	{
		$this->load->library('parser');
		$param['penyedia_pusat'] = $this->PenyediaPusatModel->GetAll();
		$param['jenis_barang_pusat'] = $this->JenisBarangPusatModel->GetAll();
		$param['provinsi'] = $this->ProvinsiModel->GetAll();
		$data = array(
	        'title' => 'INPUT LAPORAN BAP-STHP',
	        'content-path' => 'PEGADAAN PUSAT / BAP-STHP / TAMBAH DATA',
	        'content' => $this->load->view('bapsthp-pusat/add', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function store()
	{	

		$tahun_anggaran = $this->input->post('tahun_anggaran');
		$titik_serah = $this->input->post('titik_serah');

		if($titik_serah == 'PUSAT')
			$nama_wilayah = $this->input->post('txt_nama_wilayah');
		else
			$nama_wilayah = $this->input->post('nama_wilayah');

		$no_bapsthp = $this->input->post('no_bapsthp');
		$tanggal = $this->input->post('tanggal');

		$id_penyerah = $this->input->post('id_penyerah');
		$nama_penyerah = $this->input->post('nama_penyerah');
		$jabatan_penyerah = $this->input->post('jabatan_penyerah');
		$notelp_penyerah = $this->input->post('notelp_penyerah');
		$alamat_penyerah = $this->input->post('alamat_penyerah');
		$id_provinsi_penyerah = $this->input->post('id_provinsi_penyerah');
		$id_kabupaten_penyerah = $this->input->post('id_kabupaten_penyerah');

		$pihak_penerima = $this->input->post('pihak_penerima');
		$nama_penerima = $this->input->post('nama_penerima');
		$jabatan_penerima = $this->input->post('jabatan_penerima');
		$notelp_penerima = $this->input->post('notelp_penerima');
		$alamat_penerima = $this->input->post('alamat_penerima');
		$id_provinsi_penerima = $this->input->post('id_provinsi_penerima');
		$id_kabupaten_penerima = $this->input->post('id_kabupaten_penerima');

		$no_kontrak = $this->input->post('no_kontrak');
		$nama_barang = $this->input->post('nama_barang');
		$merk = $this->input->post('merk');
		$jumlah_barang = (float)str_replace(',', '', $this->input->post('jumlah_barang'));
		$nilai_barang = (float)str_replace(',', '', $this->input->post('nilai_barang'));

		$nama_mengetahui = $this->input->post('nama_mengetahui');
		$jabatan_mengetahui = $this->input->post('jabatan_mengetahui');

		if($tahun_anggaran == '' or $id_penyerah == '' or $no_kontrak == '' or $nama_barang == '' or $merk == '' or$jumlah_barang == '' or $nilai_barang == '' or $tanggal == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('Bapsthp_pusat/Add');
		}
		else{
			$tanggal = DateTime::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');

			$data = array(
				'tahun_anggaran' => $tahun_anggaran,
				'titik_serah' => $titik_serah,
				'nama_wilayah' => $nama_wilayah,
				'no_bapsthp' => $no_bapsthp,
				'tanggal' => $tanggal,
				'id_penyerah' => $id_penyerah,
				'nama_penyerah' => $nama_penyerah,
				'jabatan_penyerah' => $jabatan_penyerah,
				'notelp_penyerah' => $notelp_penyerah,
				'alamat_penyerah' => $alamat_penyerah,
				'id_provinsi_penyerah' => $id_provinsi_penyerah,
				'id_kabupaten_penyerah' => $id_kabupaten_penyerah,
				'pihak_penerima' => $pihak_penerima,
				'nama_penerima' => $nama_penerima,
				'jabatan_penerima' => $jabatan_penerima,
				'notelp_penerima' => $notelp_penerima,
				'alamat_penerima' => $alamat_penerima,
				'id_provinsi_penerima' => $id_provinsi_penerima,
				'id_kabupaten_penerima' => $id_kabupaten_penerima,
				'no_kontrak' => strtoupper($no_kontrak),
				'nama_barang' => $nama_barang,
				'merk' => $merk,
				'jumlah_barang' => $jumlah_barang,
				'nilai_barang' => $nilai_barang,
				'nama_mengetahui' => $nama_mengetahui,
				'jabatan_mengetahui' => $jabatan_mengetahui,
				'nama_file' => '',
				'created_by' => $this->session->userdata('logged_in')->id_pengguna,
				'created_at' => NOW,
			);
			$this->Bapsthp_pusat_model->Insert($data);
			$this->session->set_flashdata('info','Data inserted successfully.');
			redirect('Bapsthp_pusat');

				
		}
	 	
	}

	public function remove_image()
	{
	    
		$id = $this->input->get('id_bapsthp');
		$urutanfile = $this->input->get('urutanfile');

		$bapsthp_pusat = $this->Bapsthp_pusat_model->Get($id);

		if($bapsthp_pusat->nama_file != '' and $bapsthp_pusat->nama_file != 'null' and !is_null($bapsthp_pusat->nama_file) and $bapsthp_pusat->nama_file != '[]')
			$images = json_decode($bapsthp_pusat->nama_file);
		else
			$images = [];

		$nama_file = $images[$urutanfile];
		unlink($_SERVER['DOCUMENT_ROOT'].'/upload/bapsthp_pusat/'.$nama_file);	

		$new_nama_file = [];
		foreach($images as $image){
			if($image != $nama_file){
				array_push($new_nama_file, $image);
			}
		}

		$nama_file = json_encode($new_nama_file);

		if($nama_file == '[]' or $nama_file == NULL or $nama_file == 'null' or is_null($nama_file)){
			$nama_file = '';
		}

		// echo $nama_file;
		// exit(1);

		$data = array(
			'nama_file' => $nama_file,
			'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
			'updated_at' => NOW,
		);
		$this->Bapsthp_pusat_model->Update($id, $data);

		$this->session->set_flashdata('info','File berhasil dihapus.');
		exit('success');
	}

	public function edit()
	{
		$id = $this->input->get('id');

		$param['bapsthp_pusat'] = $this->Bapsthp_pusat_model->Get($id);

		$param['penyedia_pusat'] = $this->PenyediaPusatModel->GetAll();
		$param['jenis_barang_pusat'] = $this->JenisBarangPusatModel->GetAll();
		$param['provinsi'] = $this->ProvinsiModel->GetAll();

		$this->load->library('parser');
		$data = array(
	        'title' => 'UBAH LAPORAN BAP-STHP',
	        'content-path' => 'PEGADAAN PUSAT / BAP-STHP / UBAH DATA',
	        'content' => $this->load->view('bapsthp-pusat/edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);

	}

	public function update()
	{	
		$id = $this->input->post('id');

		$tahun_anggaran = $this->input->post('tahun_anggaran');
		$titik_serah = $this->input->post('titik_serah');

		if($titik_serah == 'PUSAT')
			$nama_wilayah = $this->input->post('txt_nama_wilayah');
		else
			$nama_wilayah = $this->input->post('nama_wilayah');

		$no_bapsthp = $this->input->post('no_bapsthp');
		$tanggal = $this->input->post('tanggal');

		$id_penyerah = $this->input->post('id_penyerah');
		$nama_penyerah = $this->input->post('nama_penyerah');
		$jabatan_penyerah = $this->input->post('jabatan_penyerah');
		$notelp_penyerah = $this->input->post('notelp_penyerah');
		$alamat_penyerah = $this->input->post('alamat_penyerah');
		$id_provinsi_penyerah = $this->input->post('id_provinsi_penyerah');
		$id_kabupaten_penyerah = $this->input->post('id_kabupaten_penyerah');

		$pihak_penerima = $this->input->post('pihak_penerima');
		$nama_penerima = $this->input->post('nama_penerima');
		$jabatan_penerima = $this->input->post('jabatan_penerima');
		$notelp_penerima = $this->input->post('notelp_penerima');
		$alamat_penerima = $this->input->post('alamat_penerima');
		$id_provinsi_penerima = $this->input->post('id_provinsi_penerima');
		$id_kabupaten_penerima = $this->input->post('id_kabupaten_penerima');

		$no_kontrak = $this->input->post('no_kontrak');
		$nama_barang = $this->input->post('nama_barang');
		$merk = $this->input->post('merk');
		$jumlah_barang = (float)str_replace(',', '', $this->input->post('jumlah_barang'));
		$nilai_barang = (float)str_replace(',', '', $this->input->post('nilai_barang'));

		$nama_mengetahui = $this->input->post('nama_mengetahui');
		$jabatan_mengetahui = $this->input->post('jabatan_mengetahui');

		if($tahun_anggaran == '' or $id_penyerah == '' or $no_kontrak == '' or $nama_barang == '' or $merk == '' or$jumlah_barang == '' or $nilai_barang == '' or $tanggal == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('Bapsthp_pusat/Add');
		}
		else{
			$tanggal = DateTime::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');

			$data = array(
				'tahun_anggaran' => $tahun_anggaran,
				'titik_serah' => $titik_serah,
				'nama_wilayah' => $nama_wilayah,
				'no_bapsthp' => $no_bapsthp,
				'tanggal' => $tanggal,
				'id_penyerah' => $id_penyerah,
				'nama_penyerah' => $nama_penyerah,
				'jabatan_penyerah' => $jabatan_penyerah,
				'notelp_penyerah' => $notelp_penyerah,
				'alamat_penyerah' => $alamat_penyerah,
				'id_provinsi_penyerah' => $id_provinsi_penyerah,
				'id_kabupaten_penyerah' => $id_kabupaten_penyerah,
				'pihak_penerima' => $pihak_penerima,
				'nama_penerima' => $nama_penerima,
				'jabatan_penerima' => $jabatan_penerima,
				'notelp_penerima' => $notelp_penerima,
				'alamat_penerima' => $alamat_penerima,
				'id_provinsi_penerima' => $id_provinsi_penerima,
				'id_kabupaten_penerima' => $id_kabupaten_penerima,
				'no_kontrak' => strtoupper($no_kontrak),
				'nama_barang' => $nama_barang,
				'merk' => $merk,
				'jumlah_barang' => $jumlah_barang,
				'nilai_barang' => $nilai_barang,
				'nama_mengetahui' => $nama_mengetahui,
				'jabatan_mengetahui' => $jabatan_mengetahui,
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->Bapsthp_pusat_model->Update($id, $data);
			$this->session->set_flashdata('info','Data updated successfully.');
			redirect('Bapsthp_pusat');

				
		}
		
	}

	public function destroy()
	{	
		$id = $this->input->get('id');

		$bapsthp_pusat = $this->Bapsthp_pusat_model->Get($id);

		if($bapsthp_pusat->nama_file != '' and $bapsthp_pusat->nama_file != 'null' and !is_null($bapsthp_pusat->nama_file) and $bapsthp_pusat->nama_file != '[]')
			$images = json_decode($bapsthp_pusat->nama_file);
		else
			$images = [];

		foreach($images as $image){
			unlink($_SERVER['DOCUMENT_ROOT'].'/upload/bapsthp_pusat/'.$image);	
		}
		
		$this->Bapsthp_pusat_model->Delete($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('Bapsthp_pusat');
		
	}

	public function get_json()
	{
		$id_bapsthp = $this->input->get('id_bapsthp');

		$data = $this->Bapsthp_pusat_model->Get($id_bapsthp);
		$hasil = json_encode($data);
		header('HTTP/1.1: 200');
		header('Status: 200');
		header('Content-Length: '.strlen($hasil));
		exit($hasil);
		
	}

	public function export() {
		$columns = array( 
			0 =>'tahun_anggaran', 
			1 =>'titik_serah',
			2 =>'nama_wilayah',
			3 => 'no_bapsthp',
			4 => 'tanggal',
			5 => 'nama_penyedia_pusat',
			6 => 'nama_penyerah',
			7 => 'jabatan_penyerah',
			8 => 'notelp_penyerah',
			9 => 'alamat_penyerah',
			10 => 'prov_peny.nama_provinsi',
			11 => 'kab_peny.nama_kabupaten',
			12 => 'pihak_penerima',
			13 => 'nama_penerima',
			14 => 'jabatan_penerima',
			15 => 'notelp_penerima',
			16 => 'alamat_penerima',
			17 => 'prov_pene.nama_provinsi',
			18 => 'kab_pene.nama_kabupaten',
			19 => 'no_kontrak',
			20 => 'nama_barang',
			21 => 'merk',
			22 => 'jumlah_barang',
			23 => 'nilai_barang',
			24 => 'nama_mengetahui',
			25 => 'jabatan_mengetahui',
			26 => 'id',
			27 => 'id',
		);

		$order = $columns[$this->input->post('order')[0]['column']];
		$dir = $this->input->post('order')[0]['dir'];
		$filtercond = '';
		for($i=0;$i<count($columns);$i++){
			if($i < 26){
				if(!empty($this->input->post('columns')[$i]['search']['value'])){
					$search = $this->input->post('columns')[$i]['search']['value'];
					$filtercond  .= " and ".$columns[$i]." LIKE '%".$search."%'"; 
				}
			}
			else{
				if($i==26){
					if($this->input->post('columns')[$i]['search']['value'] == 'TERSEDIA'){
						$filtercond .= " and (nama_file != '' and nama_file != 'null' and nama_file is not null)";
					}
					else if($this->input->post('columns')[$i]['search']['value'] == 'TDKTERSEDIA'){
						$filtercond .= " and (nama_file = '' or nama_file = 'null' or nama_file is null ) ";
					}
				}	
			}				
		}

		$data = array();
		if(empty($this->input->post('search')['value'])){            
			$data = $this->Bapsthp_pusat_model->ExportAllForAjax($order, $dir, $filtercond);
		}
		else {
			$search = $this->input->post('search')['value'];
			$data =  $this->Bapsthp_pusat_model->ExportSearchAjax($search, $order, $dir, $filtercond);
		}

		$visible_columns = $this->input->post('visible_columns');
		$visible_header_columns = array();
		foreach($visible_columns as $value) {
			switch($value['title']) {
				case 'Tahun Anggaran':
				case 'Jumlah': 
					$visible_header_columns[$value['title']] = 'integer';
					break;
				case 'Nilai (Rp)': 
					$visible_header_columns[$value['title']] = '#,##0';
					break;
				default :
					$visible_header_columns[$value['title']] = 'string';
			}
    }
		$this->xlsxwriter->writeSheetHeader('BAPSTHP Pusat', $visible_header_columns, array('font-style'=>'bold'));

		foreach($data as $row) {
			$newRow = array();
			foreach($visible_columns as $key => $value) {
				$defaultValue = '';
				if(isset($row[$value['id']])) {
					$defaultValue = $row[$value['id']];
				}
				switch($value['id']) {
					case 'tahun_anggaran': 
						$newRow[$key] = $row['tahun_anggaran'];
						break;
					case 'ketfoto': 
						$newRow[$key] = $row['nama_file'] === "" ? "TDK TERSEDIA" : "TERSEDIA";
						break;
					default: 
						$newRow[$key] = $defaultValue;
				}
			}
			$this->xlsxwriter->writeSheetRow('BAPSTHP Pusat', $newRow);
		}

		$uniq_id = substr(md5(uniqid(rand(), true)), 0, 5);
		$file = "upload/BASTB App Data BAPSTHP Pusat - $uniq_id.xlsx";
		$this->xlsxwriter->writeToFile($file);

		header('Content-Type: application/json');
		echo json_encode(array('filename' => base_url().'Bapsthp_pusat/doDownload?filename='.$file));
	}

	public function download() {
		if(isset($_GET['filename'])) {
			$file = $_GET['filename'];
			if(file_exists($file)) {
				header('Content-Description: File Transfer');
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment; filename='.basename($file));
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($file));
				readfile($file);
				unlink($file);
			}
		}
	}
}
	