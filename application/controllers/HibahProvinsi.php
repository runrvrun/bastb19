<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HibahProvinsi extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('Home/Logout');
		}
		$this->load->model('HibahProvinsiModel');
		$this->load->model('PenyediaProvinsiModel');
		$this->load->model('JenisBarangProvinsiModel');
		$this->load->model('ProvinsiModel');
		$this->load->model('AlokasiProvinsiModel');
		$this->load->model('SettingHibahProvinsiModel');

		$this->load->library('Pdf');
	}

	public function index()
	{
		$this->load->library('parser');
		$param['hibah_provinsi'] = array();
		$param['total_unit'] = $this->HibahProvinsiModel->GetTotalUnit();
		$param['total_nilai'] = $this->HibahProvinsiModel->GetTotalNilai();
		$param['total_unit_alokasi'] = $this->AlokasiProvinsiModel->GetTotalUnit();
		$param['total_nilai_alokasi'] = $this->AlokasiProvinsiModel->GetTotalNilai();
		// $param['total_kontrak'] = $this->KontrakProvinsiModel->GetTotalKontrak();
		// $param['total_merk'] = $this->KontrakProvinsiModel->GetTotalMerk();
		$data = array(
	        'title' => 'Data Hibah TP Provinsi',
	        'content-path' => 'PENGADAAN TP PROVINSI / HIBAH',
	        'content' => $this->load->view('hibah-provinsi-index', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function AjaxGetAllData()
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
		// admin hibah bisa crud di menu hibah
		if($this->session->userdata('logged_in')->role_pengguna == 'ADMIN HIBAH'){
		   $bolehtambah = 1;
		   $bolehedit = 1;
		   $bolehhapus = 1;
		   $grafik = 1;
		 }

		$columns = array( 
            0 =>'tahun_anggaran', 
            1 =>'tanggal_naskah_hibah',
            2 => 'no_naskah_hibah',
            3 =>'tanggal_bast_bmn',
            4 => 'no_bast_bmn',
            5 =>'tanggal_surat_pernyataan',
            6 => 'no_surat_pernyataan',
            7 => 'nama_provinsi',
            8 => 'nama_kabupaten',
            9 => 'total_unit',
            10 => 'total_nilai',
            11 => 'nama_penyerah',
            12 => 'nip_penyerah',
            13 => 'pangkat_penyerah',
            14 => 'jabatan_penyerah',
            15 => 'alamat_dinas_penyerah',
            16 => 'titik_serah',
            17 => 'nama_wilayah',
            18 => 'instansi_penerima',
            19 => 'nama_penerima',
            20 => 'nip_penerima',
            21 => 'pangkat_penerima',
            22 => 'jabatan_penerima',
            23 => 'alamat_dinas_penerima',
            24 => 'id',
            25 => 'id',
        );

        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

		$totalData = $this->HibahProvinsiModel->GetAllAjaxCount();
            
        $totalFiltered = $totalData;

        $filtercond = '';
        for($i=0;$i<count($columns);$i++){
        	if($i < 24){
        		if(!empty($this->input->post('columns')[$i]['search']['value'])){
	        		$search = $this->input->post('columns')[$i]['search']['value'];
	        		$filtercond  .= " and ".$columns[$i]." LIKE '%".$search."%'"; 
	        	}
        	}
        	else{
        		if($i==24){
        			if($this->input->post('columns')[$i]['search']['value'] == 'TERSEDIA'){
        				$filtercond .= " and (nama_file != '' and nama_file != 'null' and nama_file is not null)";
        			}
        			else if($this->input->post('columns')[$i]['search']['value'] == 'TDKTERSEDIA'){
        				$filtercond .= " and (nama_file = '' or nama_file = 'null' or nama_file is null ) ";
        			}
        		}
        		
        	}
        		        	
        }

		if(empty($this->input->post('search')['value']))
        {            
            $posts = $this->HibahProvinsiModel->GetAllForAjax($_POST['start'], $_POST['length'], $order, $dir, $filtercond);
            $totalFiltered = $this->HibahProvinsiModel->GetFilterAjaxCount($filtercond);
        }
        else {
            $search = $this->input->post('search')['value']; 

            $posts =  $this->HibahProvinsiModel->GetSearchAjax($_POST['start'], $_POST['length'], $search, $order, $dir, $filtercond);

            $totalFiltered = $this->HibahProvinsiModel->GetSearchAjaxCount($search, $filtercond);
        }

        if(!empty($posts))
        {
            foreach ($posts as $post)
            {

                $nestedData['tahun_anggaran'] = $post->tahun_anggaran;

                $nestedData['no_naskah_hibah'] = $post->no_naskah_hibah;            
                if($post->tanggal_naskah_hibah)
                	$nestedData['tanggal_naskah_hibah'] = date('d-m-Y', strtotime($post->tanggal_naskah_hibah));
                else
                	$nestedData['tanggal_naskah_hibah'] = '';

                $nestedData['no_bast_bmn'] = $post->no_bast_bmn;
                if($post->tanggal_bast_bmn)
                	$nestedData['tanggal_bast_bmn'] = date('d-m-Y', strtotime($post->tanggal_bast_bmn));
                else
                	$nestedData['tanggal_bast_bmn'] = '';

                $nestedData['no_surat_pernyataan'] = $post->no_surat_pernyataan;
                if($post->tanggal_surat_pernyataan)
                	$nestedData['tanggal_surat_pernyataan'] = date('d-m-Y', strtotime($post->tanggal_surat_pernyataan));
                else
                	$nestedData['tanggal_surat_pernyataan'] = '';

                $nestedData['nama_provinsi'] = $post->nama_provinsi;
                $nestedData['nama_kabupaten'] = $post->nama_kabupaten;
                $nestedData['total_unit'] = number_format($post->total_unit, 0);
                $nestedData['total_nilai'] = number_format($post->total_nilai, 0);

                $nestedData['nama_penyerah'] = $post->nama_penyerah;
                $nestedData['nip_penyerah'] = $post->nip_penyerah;
                $nestedData['pangkat_penyerah'] = $post->pangkat_penyerah;
                $nestedData['jabatan_penyerah'] = $post->jabatan_penyerah;
                $nestedData['alamat_dinas_penyerah'] = $post->alamat_dinas_penyerah;

                $nestedData['titik_serah'] = $post->titik_serah;
                $nestedData['nama_wilayah'] = $post->nama_wilayah;
                
                $nestedData['instansi_penerima'] = $post->instansi_penerima;
                $nestedData['nama_penerima'] = $post->nama_penerima;
                $nestedData['nip_penerima'] = $post->nip_penerima;
                $nestedData['pangkat_penerima'] = $post->pangkat_penerima;
                $nestedData['jabatan_penerima'] = $post->jabatan_penerima;
                $nestedData['alamat_dinas_penerima'] = $post->alamat_dinas_penerima;


                $nestedData['ketfoto'] = (($post->nama_file != '' and $post->nama_file != '[]' and $post->nama_file != 'null' and !is_null($post->nama_file)) ?  'TERSEDIA' : 'TDK TERSEDIA');

                $tools = "";
                $tools .= "<a class='btn btn-xs btn-success btn-sm' href='".base_url('HibahProvinsi/LihatDokumen?id=').$post->id."'><i class='glyphicon glyphicon-zoom-in'></i></a>";

                if($bolehedit)
                	$tools .= "<a class='btn btn-xs btn-primary btn-sm' href='".base_url('HibahProvinsi/Edit?id=').$post->id."'><i class='glyphicon glyphicon-pencil'></i></a>";
                	$tools .="<a class='btn btn-xs btn-info btn-sm' data-href='#' data-toggle='modal' data-record-title='".$post->no_naskah_hibah."' data-target='#upload-modal' data-record-id='".$post->id."'><i class='glyphicon glyphicon-open-file'></i></a>";

                if($bolehhapus)
                	$tools .= "<a class='btn btn-xs btn-danger btn-sm' data-href='".base_url('HibahProvinsi/doDelete?id=').$post->id."' data-toggle='modal' data-record-title='".$post->no_naskah_hibah."' data-target='#confirm-delete'><i class='glyphicon glyphicon-trash'></i></a>";
                
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

	public function SettingUmum()
	{
		$this->load->library('parser');
		$setting = $this->SettingHibahProvinsiModel->GetSettingUser($this->session->userdata('logged_in')->id_pengguna);

		if(!$setting){
			$this->SettingHibahProvinsiModel->InsertDefaultData();
			$setting = $this->SettingHibahProvinsiModel->GetSettingUser($this->session->userdata('logged_in')->id_pengguna);
		}

		$param['setting'] = $setting;
		$data = array(
	        'title' => 'SETTING UMUM',
	        'content-path' => 'PEGADAAN TP PROVINSI / HIBAH / SETTING UMUM',
	        'content' => $this->load->view('setting-hibah-provinsi', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function UpdateSettingUmum()
	{	
		$id = $this->input->post('id');
		$nama_penyerah = $this->input->post('nama_penyerah');
		$nip_penyerah = $this->input->post('nip_penyerah');
		$pangkat_penyerah = $this->input->post('pangkat_penyerah');
		$jabatan_penyerah = $this->input->post('jabatan_penyerah');
		$alamat_dinas_penyerah = $this->input->post('alamat_dinas_penyerah');

		$instansi_penerima = $this->input->post('instansi_penerima');
		$nama_penerima = $this->input->post('nama_penerima');
		$nip_penerima = $this->input->post('nip_penerima');
		$pangkat_penerima = $this->input->post('pangkat_penerima');
		$jabatan_penerima = $this->input->post('jabatan_penerima');
		$alamat_dinas_penerima = $this->input->post('alamat_dinas_penerima');

		if($nama_penyerah == '' or $nip_penyerah == '' or $pangkat_penyerah == '' or $jabatan_penyerah == '' or$alamat_dinas_penyerah == '' or $instansi_penerima == '' or $nama_penerima == '' or $nip_penerima == '' or $pangkat_penerima == '' or $jabatan_penerima == '' or $alamat_dinas_penerima == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('HibahProvinsi/SettingUmum');
		}
		else{

			$data = array(
				'nama_penyerah' => $nama_penyerah,
				'nip_penyerah' => $nip_penyerah,
				'pangkat_penyerah' => $pangkat_penyerah,
				'jabatan_penyerah' => $jabatan_penyerah,
				'alamat_dinas_penyerah' => $alamat_dinas_penyerah,
				'instansi_penerima' => $instansi_penerima,
				'nama_penerima' => $nama_penerima,
				'nip_penerima' => $nip_penerima,
				'pangkat_penerima' => $pangkat_penerima,
				'jabatan_penerima' => $jabatan_penerima,
				'alamat_dinas_penerima' => $alamat_dinas_penerima,
				'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
				'updated_at' => NOW,
			);
			$this->SettingHibahProvinsiModel->SaveData($id, $data);
			$this->session->set_flashdata('info','Data updated successfully.');
			redirect('HibahProvinsi/SettingUmum');

				
		}
	 	
	}

	public function Add()
	{
		$this->load->library('parser');
		$param['alokasi_provinsi'] = $this->AlokasiProvinsiModel->GetAll();

		$data = array(
	        'title' => 'INPUT DOKUMEN HIBAH',
	        'content-path' => 'PEGADAAN TP PROVINSI / HIBAH / INPUT DOKUMEN HIBAH',
	        'content' => $this->load->view('hibah-provinsi-add', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function doAdd()
	{	

		$listIdAlokasi = $this->input->post('listIdAlokasi');
		$arrayid = explode(",", $listIdAlokasi);

		$data_alokasi = array();

		$total_unit = 0;
		$total_nilai = 0;

		foreach($arrayid as $id){
			$alokasi = $this->AlokasiProvinsiModel->GetData($id);
			$unit = $this->AlokasiProvinsiModel->GetUnit($id);
			$nilai = $this->AlokasiProvinsiModel->GetNilai($id);

			$total_unit += $unit;
			$total_nilai += $nilai;
			array_push($data_alokasi, $alokasi);
		}

		// print_r($data_alokasi);
		// exit(1);

		// $tahun_anggaran = $this->input->post('tahun_anggaran');
		$tahun_anggaran = $this->session->userdata('logged_in')->tahun_pengadaan;

		$no_naskah_hibah = $this->input->post('no_naskah_hibah');
		$no_bast_bmn = $this->input->post('no_bast_bmn');
		$no_surat_pernyataan = $this->input->post('no_surat_pernyataan');

		$id_provinsi = $data_alokasi[0]->id_provinsi;
		$id_kabupaten = $data_alokasi[0]->id_kabupaten;
		

		$nama_penyerah = $this->input->post('nama_penyerah');
		$nip_penyerah = $this->input->post('nip_penyerah');
		$pangkat_penyerah = $this->input->post('pangkat_penyerah');
		$jabatan_penyerah = $this->input->post('jabatan_penyerah');
		$alamat_dinas_penyerah = $this->input->post('alamat_dinas_penyerah');

		$titik_serah = $this->input->post('titik_serah');
		$nama_wilayah = $this->input->post('nama_wilayah');
		$instansi_penerima = $this->input->post('instansi_penerima');
		$nama_penerima = $this->input->post('nama_penerima');
		$nip_penerima= $this->input->post('nip_penerima');
		$pangkat_penerima = $this->input->post('pangkat_penerima');
		$jabatan_penerima = $this->input->post('jabatan_penerima');
		$alamat_dinas_penerima = $this->input->post('alamat_dinas_penerima');

		$tanggal_naskah_hibah = $this->input->post('tanggal_naskah_hibah');
		$tanggal_bast_bmn = $this->input->post('tanggal_bast_bmn');
		$tanggal_surat_pernyataan = $this->input->post('tanggal_surat_pernyataan');

		//insert data
		if($nama_penyerah == '' or $nip_penyerah == '' or $pangkat_penyerah == '' or $jabatan_penyerah == '' or $alamat_dinas_penyerah == '' or $titik_serah == '' or $nama_wilayah == '' or $instansi_penerima == '' or $nama_penerima == '' or $nip_penerima == ''  or $pangkat_penerima == ''  or $jabatan_penerima == ''  or $alamat_dinas_penerima == '' ){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('HibahProvinsi/Add');
		}
		else{

			// if( ($no_naskah_hibah != '' and $tanggal_naskah_hibah == '') or ($no_naskah_hibah == '' and $tanggal_naskah_hibah != '') ){
			// 	$this->session->set_flashdata('error','Nomor dan Tanggal Naskah Hibah harus Diisi.');
   //   			redirect('HibahProvinsi/Add');
			// }

			// if( ($no_bast_bmn != '' and $tanggal_bast_bmn == '') or ($no_bast_bmn == '' and $tanggal_bast_bmn != '') ){
			// 	$this->session->set_flashdata('error','Nomor dan Tanggal BAST BMN harus Diisi.');
   //   			redirect('HibahProvinsi/Add');
			// }

			// if( ($no_surat_pernyataan != '' and $tanggal_surat_pernyataan == '') or ($no_surat_pernyataan == '' and $tanggal_surat_pernyataan != '') ){
			// 	$this->session->set_flashdata('error','Nomor dan Tanggal Surat Pernyataan harus Diisi.');
   //   			redirect('HibahProvinsi/Add');
			// }

			// exit(1);


			//cek no naskah hibah & no bast bmn & no surat pernyataan belum digunakan pada data lainnya karena digunakan utk nama file pdf yang akan digenerate...
			$no_naskah_hibah_terpakai = $this->HibahProvinsiModel->CheckNoNaskahHibah($no_naskah_hibah);
			$no_bast_bmn_terpakai = $this->HibahProvinsiModel->CheckNoBASTBMN($no_bast_bmn);
			$no_surat_pernyataan_terpakai = $this->HibahProvinsiModel->CheckNoSuratPernyataan($no_surat_pernyataan);

			// if($no_naskah_hibah != '' and $no_naskah_hibah_terpakai){
			// 	$this->session->set_flashdata('error','No Naskah Hibah sudah digunakan. Silahkan periksa kembali data anda.');
   //   			redirect('HibahProvinsi/Add');
			// }
			// if($no_bast_bmn != '' and $no_bast_bmn_terpakai){
			// 	$this->session->set_flashdata('error','No BAST BMN sudah digunakan. Silahkan periksa kembali data anda.');
   //   			redirect('HibahProvinsi/Add');
			// }
			// if($no_surat_pernyataan != '' and $no_surat_pernyataan_terpakai){
			// 	$this->session->set_flashdata('error','No Surat Pernyataan sudah digunakan. Silahkan periksa kembali data anda.');
   //   			redirect('HibahProvinsi/Add');
			// }

			if($tanggal_naskah_hibah != '')
				$tanggal_naskah_hibah = DateTime::createFromFormat('d-m-Y', $tanggal_naskah_hibah)->format('Y-m-d');
			else
				$tanggal_naskah_hibah = null;

			if($tanggal_bast_bmn != '')
				$tanggal_bast_bmn = DateTime::createFromFormat('d-m-Y', $tanggal_bast_bmn)->format('Y-m-d');
			else
				$tanggal_bast_bmn = null;

			if($tanggal_surat_pernyataan != '')
				$tanggal_surat_pernyataan = DateTime::createFromFormat('d-m-Y', $tanggal_surat_pernyataan)->format('Y-m-d');
			else
				$tanggal_surat_pernyataan = null;

			$data = array(
				'tahun_anggaran' => $tahun_anggaran,
				'no_naskah_hibah' => $no_naskah_hibah,
				'no_bast_bmn' => $no_bast_bmn,
				'no_surat_pernyataan' => $no_surat_pernyataan,
				'tanggal_naskah_hibah' => $tanggal_naskah_hibah,
				'tanggal_bast_bmn' => $tanggal_bast_bmn,
				'tanggal_surat_pernyataan' => $tanggal_surat_pernyataan,
				'id_provinsi' => $id_provinsi,
				'id_kabupaten' => $id_kabupaten,
				'total_unit' => $total_unit,
				'total_nilai' => $total_nilai,
				'nama_penyerah' => $nama_penyerah,
				'nip_penyerah' => $nip_penyerah,
				'pangkat_penyerah' => $pangkat_penyerah,
				'jabatan_penyerah' => $jabatan_penyerah,
				'alamat_dinas_penyerah' => $alamat_dinas_penyerah,
				'titik_serah' => $titik_serah,
				'nama_wilayah' => $nama_wilayah,
				'instansi_penerima' => $instansi_penerima,
				'nama_penerima' => $nama_penerima,
				'nip_penerima' => $nip_penerima,
				'pangkat_penerima' => $pangkat_penerima,
				'jabatan_penerima' => $jabatan_penerima,
				'alamat_dinas_penerima' => $alamat_dinas_penerima,
				'nama_file' => '',
				'created_by' => $this->session->userdata('logged_in')->id_pengguna,
				'created_at' => NOW,
			);
			$this->HibahProvinsiModel->Insert($data);

			//update alokasi
			$inserted = $this->HibahProvinsiModel->GetLast();
			$idinserted = $inserted->id;
			foreach($arrayid as $id){
				$this->AlokasiProvinsiModel->UpdateHibahId($id, $idinserted);
			}
		}

		// ************************************************************************************************************************
		// generate pdf naskah hibah
		// ************************************************************************************************************************

		// if($no_naskah_hibah != ''){
			
			if($tanggal_naskah_hibah != ''){
				$hari = 'Senin';
				$bulan = 'Januari';
				$mo = date('n', strtotime($tanggal_naskah_hibah));

				$day = date('D', strtotime($tanggal_naskah_hibah));
				if($day == 'Mon')
					$hari = 'Senin';
				if($day == 'Tue')
					$hari = 'Selasa';
				if($day =='Wed')
					$hari = 'Rabu';
				if($day == 'Thu')
					$hari = 'Kamis';
				if($day == 'Fri')
					$hari = 'Jumat';
				if($day == 'Sat')
					$hari = 'Sabtu';
				if($day == 'Sun')
					$hari = 'Minggu';

				
				
				if($mo == 1)
					$bulan = 'Januari';
				if($mo == 2)
					$bulan = 'Februari';
				if($mo == 3)
					$bulan = 'Maret';
				if($mo == 4)
					$bulan = 'April';
				if($mo == 5)
					$bulan = 'Mei';
				if($mo == 6)
					$bulan = 'Juni';
				if($mo == 7)
					$bulan = 'Juli';
				if($mo == 8)
					$bulan = 'Agustus';
				if($mo == 9)
					$bulan = 'September';
				if($mo == 10)
					$bulan = 'Oktober';
				if($mo == 11)
					$bulan = 'Nopember';
				if($mo == 12)
					$bulan = 'Desember';

				$tanggal = ucwords($this->terbilang(date('j', strtotime($tanggal_naskah_hibah))));
				$tahun = ucwords($this->terbilang(date('Y', strtotime($tanggal_naskah_hibah))));
			}
			else{
				$hari = '...............';
				$tanggal = '......';
				$bulan = '......';
				$tahun = '.........';
			}

			$pdf = new FPDF('P','cm','A4');
			$pdf->AddFont('calibri','','calibri.php');
	        $pdf->AddFont('calibri','B','calibrib.php');
	        // membuat halaman baru
	        $pdf->AddPage();
	        // $pdf->Image('assets/img/Kop.jpg', -1, 1, -195);
	        
	        $pdf->SetFont('calibri', 'B', 11);
	        $pdf->SetXY(1, 5);
	        $pdf->Cell(0, 0.5,'NASKAH PERJANJIAN HIBAH BARANG MILIK NEGARA', 0, 1, 'C');
	        $pdf->Cell(0, 0.5, 'BERUPA PERALATAN DAN MESIN TAHUN ANGGARAN '.$tahun_anggaran, 0, 1, 'C');

	        $pdf->Cell(0, 1, 'ANTARA', 0, 1, 'C');
	        $pdf->Cell(0, 1, 'KEMENTERIAN PERTANIAN', 0, 1, 'C');
	        $pdf->Cell(0, 1, 'DENGAN', 0, 1, 'C');
	        $pdf->Cell(0, 1, 'PEMERINTAH '.(substr($nama_wilayah, 0, 4) == 'KOTA' ? '' : $titik_serah).' '.$nama_wilayah, 0, 1, 'C');
	       	$pdf->Cell(0, 1, 'NOMOR : '.$no_naskah_hibah, 0, 1, 'C');

	       	$pdf->SetFont('calibri', '', 10);
	       	$pdf->Cell(0, 1, 'Pada hari ini '.$hari.' tanggal '.$tanggal.' bulan '.$bulan.' tahun '.$tahun.', kami yang bertandatangan di bawah ini :', 0, 1, 'L');
			$pdf->Cell(0, 1, 'I. 	Nama  	  :   '.$nama_penyerah,0, 1, 'L');
			$pdf->Cell(0, 1, '		 NIP   	     :   '.$nip_penyerah,0, 1, 'L');
			$pdf->Cell(0, 1, '		 Jabatan 	:   '.$jabatan_penyerah,0, 1, 'L');

			$pdf->SetXY(1.5, 15);
			$pdf->MultiCell(0, 0.5, 'Yang bertindak untuk dan atas nama Menteri Pertanian berkedudukan di '.$alamat_dinas_penyerah.' selanjutnya disebut PIHAK KESATU.',0, 'L', false);

			$pdf->SetXY(1, 16.5);
			$pdf->Cell(0, 1, 'II. 	Nama  	 :   '.$nama_penerima,0, 1, 'L');
			$pdf->Cell(0, 1, '		  NIP   	     :   '.$nip_penerima,0, 1, 'L');
			$pdf->Cell(0, 1, '		  Jabatan 	:   '.$jabatan_penerima,0, 1, 'L');

			$pdf->SetXY(1.5, 19.5);
			$pdf->MultiCell(0, 0.5, 'Yang bertindak untuk dan atas nama '.($titik_serah == 'PROVINSI' ? 'Gubernur Provinsi' : ($titik_serah == 'KABUPATEN' ? 'Bupati Kabupaten' : 'Walikota')).' '.$nama_wilayah.' berkedudukan di '.$alamat_dinas_penerima.' selanjutnya disebut PIHAK KEDUA.',0, 'L', false);

			$pdf->SetXY(1, 21);
			$pdf->MultiCell(0, 0.5, 'Dalam rangka menindaklanjuti persetujuan hibah Barang Milik Negara dari Sekretaris Jenderal Kementerian Pertanian a.n Menteri Pertanian Nomor ................................................................ tanggal ................................................................ dan sesuai ketentuan Undang-undang Nomor 1 Tahun 2004, Peraturan Pemerintah Nomor 27 Tahun 2014, Peraturan Menteri Keuangan (PMK) Nomor : 111/PMK.06 /2016 tentang Tata Cara Pelaksanaan Pemindahtanganan Barang Milik Negara, PIHAK KESATU menerangkan dengan ini menghibahkan kepada PIHAK KEDUA, dan PIHAK KEDUA menerangkan dengan ini menerima hibah dari PIHAK KESATU, Barang Milik Negara Kementerian Pertanian c.q. Direktorat Jenderal Prasarana dan Sarana Pertanian berupa Peralatan dan Mesin dengan total nilai perolehan sebesar Rp '.number_format($total_nilai, 0).' ('.$this->terbilang($total_nilai).' rupiah) dan total nilai buku sebesar Rp '.number_format($total_nilai, 0).' ('.$this->terbilang($total_nilai).' rupiah) sebagaimana daftar terlampir.',0, 'L', false);

			$pdf->Cell(0, 1, 'Kedua  belah  pihak  menerangkan  bahwa  hibah  ini  dilakukan  dengan  ketentuan  sebagai berikut  :', 0, 1, 'L');

			//page 2
			$pdf->AddPage();
	        // $pdf->Image('assets/img/Kop.jpg', -1, 1, -195);
	        
	        $baris = 5;
	        $pdf->SetFont('calibri', '', 10);
	        $pdf->SetXY(1, $baris);
	        $pdf->Cell(0, 0.5,'Pasal 1', 0, 1, 'C');
	        $pdf->Cell(0, 0.5,'JUMLAH DAN TUJUAN HIBAH', 0, 1, 'C');
	        $baris = $baris + 1.5;
	        $pdf->SetXY(1, $baris);
	 		$pdf->MultiCell(0, 0.5, '(1) 	PIHAK KESATU menghibahkan Barang Milik Negara Kementerian Pertanian Cq. Direkorat Jenderal Prasarana dan Sarana Pertanian sebagaimana  daftar terlampir kepada PIHAK  KEDUA yang merupakan bagian tidak terpisahkan dari Naskah Perjanjian Hibah ini, dengan total nilai perolehan sebesar Rp '.number_format($total_nilai, 0).' ('.$this->terbilang($total_nilai).' rupiah) dan total nilai buku sebesar Rp '.number_format($total_nilai, 0).' ('.$this->terbilang($total_nilai).' rupiah) sebagaimana daftar terlampir.');
	 		$baris = $baris + 2.5;
	 		$pdf->SetXY(1, $baris);
	 		$pdf->MultiCell(0, 0.5, '(2)	Barang Milik Negara sebagaimana dimaksud pada ayat (1) digunakan untuk mendukung penyelenggaraan tugas dan fungsi Pemerintah Daerah '.(substr($nama_wilayah, 0, 4) == 'KOTA' ? '' : $titik_serah).' '.$nama_wilayah.' khususnya dibidang Pertanian, Perkebunan dan Hortikultura.');
	 		$baris = $baris + 2;
	 		$pdf->SetXY(1, $baris);
	        $pdf->Cell(0, 0.5,'Pasal 2', 0, 1, 'C');
	        $pdf->Cell(0, 0.5,'HAK DAN KEWAJIBAN PIHAK KESATU', 0, 1, 'C');
	        $baris = $baris + 1.5;
	 		$pdf->SetXY(1, $baris);
	 		$pdf->MultiCell(0, 0.5, '(1) Menyerahkan Obyek Hibah kepada PIHAK KEDUA;');
	 		$baris = $baris + 0.5;
	 		$pdf->SetXY(1, $baris);
	 		$pdf->MultiCell(0, 0.5, '(2) Mengeluarkan catatan Barang Milik Negara tersebut dari laporan Simak BMN Kementerian Pertanian Cq. Direkorat Jenderal Prasarana dan Sarana Pertanian;.');
	 		$baris = $baris + 1;
	 		$pdf->SetXY(1, $baris);
	 		$pdf->MultiCell(0, 0.5, '(3) Melakukan monitoring atas pelaksanaan Naskah Perjanjian Hibah ini untuk menjamin difungsikannya aset sesuai dengan permohonan hibah, baik secara berkala maupun sewaktu-waktu;');
	 		$baris = $baris + 1;
	 		$pdf->SetXY(1, $baris);
	 		$pdf->MultiCell(0, 0.5, '(4) Meminta keterangan, tanggapan atas penjelasan dari PIHAK KESATU terhadap hal-hal yang diperlukan terkait dengan pelaksanaan monitoring tersebut pada ayat (3).');
	 		$baris = $baris + 1.5;
	 		$pdf->SetXY(1, $baris);
	        $pdf->Cell(0, 0.5,'Pasal 3', 0, 1, 'C');
	        $pdf->Cell(0, 0.5,'KEWAJIBAN PIHAK KEDUA', 0, 1, 'C');
	        $baris = $baris + 1.5;
	 		$pdf->SetXY(1, $baris);
	 		$pdf->MultiCell(0, 0.5, '(1) Menerima Obyek Hibah dari PIHAK KESATU;');
	 		$baris = $baris + 0.5;
	 		$pdf->SetXY(1, $baris);
	 		$pdf->MultiCell(0, 0.5, '(2) Menatausahakan Barang Milik Negara tersebut pada neraca Pemerintah '.(substr($nama_wilayah, 0, 4) == 'KOTA' ? '' : $titik_serah).' '.$nama_wilayah.' sesuai ketentuan yang berlaku;');
	 		$baris = $baris + 1;
	 		$pdf->SetXY(1, $baris);
	 		$pdf->MultiCell(0, 0.5, '(3) Menggunakan dan memelihara Obyek Hibah dengan baik sesuai dengan tujuan hibah;');
	 		$baris = $baris + 0.5;
	 		$pdf->SetXY(1, $baris);
	 		$pdf->MultiCell(0, 0.5, '(4) Melakukan pengamanan Obyek Hibah yang meliputi pengamanan administrasi, fisik dan pengamanan hukum.');
	 		$baris = $baris + 2;
	 		$pdf->SetXY(1, $baris);;
	        $pdf->Cell(0, 0.5,'Pasal 4', 0, 1, 'C');
	        $pdf->Cell(0, 0.5,'SERAH TERIMA', 0, 1, 'C');
	        $baris = $baris + 1.5;
	 		$pdf->SetXY(1, $baris);
	 		$pdf->MultiCell(0, 0.5, 'Penyerahan Barang Milik Negara dituangkan dalam Berita Acara Serah Terima dari '.$jabatan_penyerah.' atas nama Menteri Pertanian kepada '.$jabatan_penerima.' atas nama '.($titik_serah == 'PROVINSI' ? 'Gubernur Provinsi' : ($titik_serah == 'KABUPATEN' ? 'Bupati Kabupaten' : 'Walikota')).' '.$nama_wilayah.' yang merupakan bagian yang tidak terpisahkan dari Naskah Perjanjian Hibah ini.');

	 		//page 3
			$pdf->AddPage();
	        // $pdf->Image('assets/img/Kop.jpg', -1, 1, -195);
	        
	        $baris = 5;
	        $pdf->SetFont('calibri', '', 10);
	        $pdf->SetXY(1, $baris);
	        $pdf->Cell(0, 0.5,'Pasal 5', 0, 1, 'C');
	        $pdf->Cell(0, 0.5,'LAIN-LAIN', 0, 1, 'C');
	        $baris = $baris + 1.5;
	        $pdf->SetXY(1, $baris);
	        $pdf->MultiCell(0, 0.5, '(1) Segala ketentuan dan persyaratan dalam Naskah Perjanjian Hibah ini berlaku serta mengikat bagi PARA PIHAK yang menandatangani;');
	        $baris = $baris + 1;
	 		$pdf->SetXY(1, $baris);
	 		$pdf->MultiCell(0, 0.5, '(2) Naskah Perjanjian Hibah ini dibuat dalam rangkap 4 (empat) masing-masing satu rangkap untuk PIHAK KESATU, PIHAK KEDUA, Sekretaris Jenderal Kementerian Pertanian dan Kepala Kantor Wilayah KPKNL Jakarta II. ');
	 		$baris = $baris + 2;
	 		$pdf->SetXY(1, $baris);
	 		$pdf->MultiCell(0, 0.5, 'Dalam Naskah Perjanjian Hibah ini dibuat dan ditandatangani oleh PARA PIHAK pada hari, tanggal, bulan dan tahun sebagaimana tersebut di atas.');
	 		$baris = $baris + 3;
	 		$pdf->SetXY(1, $baris);
	 		// $pdf->Cell(0, 0.5,'                               PIHAK KEDUA                                                                                                    PIHAK KESATU', 0, 1, 'L');
	 		$pdf->Cell(9, 0.5, 'PIHAK KEDUA', 0, 0, 'C');
	 		$pdf->Cell(10, 0.5, 'PIHAK KESATU', 0, 1, 'C');
	 		// $pdf->Cell(0, 0.5,'                             Yang Menerima                                                                                             Yang Menyerahkan', 0, 1, 'L');
	 		$pdf->Cell(9, 0.5, 'Yang Menerima', 0, 0, 'C');
	 		$pdf->Cell(10, 0.5, 'Yang Menyerahkan', 0, 1, 'C');
	 		// $pdf->Cell(0, 0.5,'           a.n '.($titik_serah == 'PROVINSI' ? 'Gubernur Provinsi' : ($titik_serah == 'KABUPATEN' ? 'Bupati Kabupaten' : 'Walikota')).' '.$nama_wilayah.'                                                                              a.n Menteri Pertanian', 0, 1, 'L');
	 		$pdf->Cell(9, 0.5, 'a.n '.($titik_serah == 'PROVINSI' ? 'Gubernur Provinsi' : ($titik_serah == 'KABUPATEN' ? 'Bupati Kabupaten' : 'Walikota')).' '.$nama_wilayah, 0, 0, 'C');
	 		$pdf->Cell(10, 0.5, 'a.n Gubernur Provinsi '.$data_alokasi[0]->nama_provinsi, 0 , 1, 'C');

	 		// $pdf->Cell(0, 0.5,'                          '.$jabatan_penerima.'                                                                              '.$jabatan_penyerah, 0, 1, 'L');
	 		$pdf->Cell(9, 0.5, $jabatan_penerima, 0, 0, 'C');
	 		$pdf->Cell(10, 0.5, $jabatan_penyerah, 0, 1, 'C');
	 		
	 		$pdf->Ln();
	 		$pdf->Ln();
	 		$pdf->Ln();
	 		$pdf->Ln();
	 		$pdf->Ln();
	 		$pdf->Ln();

	 		// $pdf->Cell(0, 0.5,'                           '.$nama_penerima.'                                                                          '.$nama_penyerah, 0, 1, 'L');
	 		$pdf->Cell(9, 0.5, $nama_penerima, 0, 0, 'C');
	 		$pdf->Cell(10, 0.5, $nama_penyerah, 0, 1, 'C');
	 		// $pdf->Cell(0, 0.5,'                     NIP. '.$nip_penerima.'                                                                    NIP. '.$nip_penyerah, 0, 1, 'L');
	 		$pdf->Cell(9, 0.5, 'NIP. '.$nip_penerima, 0 , 0, 'C');
	 		$pdf->Cell(10, 0.5, 'NIP. '.$nip_penyerah, 0 , 1, 'C');
	        $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/naskah_hibah/'.$this->clean($no_naskah_hibah).'_'.$idinserted.'.pdf', 'F');
	        // $pdf->Output();
	        // exit(1);

	        // ************************************************************************************************************************
	        // generate pdf lampiran naskah hibah
	        // ************************************************************************************************************************
	        $pdf = new PDF_MC_Table('L','cm','A4');
			$pdf->AddFont('calibri','','calibri.php');
	        $pdf->AddFont('calibri','B','calibrib.php');
	        // membuat halaman baru
	        $pdf->AddPage();
	        // $pdf->Image('assets/img/Kop.jpg', -1, 1, -195);
	        
	        $pdf->SetFont('calibri', 'B', 11);
	        $pdf->SetXY(1, 1);
	        $pdf->Cell(0, 1,'LAMPIRAN NASKAH HIBAH BMN', 0, 1, 'L');

	       	$pdf->Cell(0, 1, 'NOMOR : '.$no_naskah_hibah, 0, 1, 'L');
	       	if($tanggal_bast_bmn != '')
	       		$pdf->Cell(0, 1, 'TANGGAL : '.date('j', strtotime($tanggal_naskah_hibah)).' '.$bulan.' '.date('Y', strtotime($tanggal_naskah_hibah)), 0, 1, 'L');
	       	else
	       		$pdf->Cell(0, 1, 'TANGGAL : ...............................', 0, 1, 'L');
	       	
	       	$pdf->SetFont('calibri', '', 10);
	       	
	       	$pdf->SetXY(1, 5);

			//header
	    	$pdf->Cell(1, 1, 'No.', 1, 0, 'C');
	    	$pdf->Cell(3, 1, 'Nama Barang', 1, 0, 'C');
	    	$pdf->Cell(2.5, 1, 'Kode Barang', 1, 0, 'C');
	    	$pdf->Cell(2.75, 1, 'Merk', 1, 0, 'C');
	    	$pdf->Cell(2, 1, 'Type', 1, 0, 'C');
	    	$pdf->Cell(2, 1, 'Jumlah Unit', 1, 0, 'C');
	    	$pdf->Cell(3, 1, 'Harga Perolehan', 1, 0, 'C');
	    	$pdf->Cell(2.75, 1, 'Tahun Perolehan', 1, 0, 'C');;
	    	$pdf->Cell(1.5, 1, 'Kondisi', 1, 0, 'C');
	    	$pdf->Cell(3, 1, 'Provinsi', 1, 0, 'C');
	    	$pdf->Cell(2.25, 1, 'Kabupaten', 1, 0, 'C');
	    	$pdf->Cell(2.5, 1, 'Nama Dinas', 1, 1, 'C');
	    	
	    	//data
	    	
	    	$pdf->SetFont('calibri', '', 8.5);

	    	$totalunit = 0;
	    	$totalnilai = 0;
	    	$unit = 0;
	    	$nilai = 0;
	    	
	    	// print_r($data_alokasi);
	    	// exit(1);

	    	$pdf->SetWidths(array(1,3,2.5,2.75,2,2,3,2.75,1.5,3,2.25,2.5));
			    
	    	$no = 1;
	    	foreach($data_alokasi as $alokasi){

	    		$adjust = ($no - 1) * 2;

	    		// $pdf->Cell(1, 1, $no, 1, 0, 'C');
		    	// $pdf->Cell(3, 1, $alokasi->nama_barang, 1, 0, 'C');
		    	// $pdf->Cell(2, 1, $alokasi->kode_barang, 1, 0, 'C');
		    	// $pdf->Cell(3.5, 1, $alokasi->merk, 1, 0, 'C');
		    	// $pdf->Cell(1, 1, $alokasi->jenis_barang, 1, 0, 'C');

		    	if($alokasi->status_alokasi == 'DATA KONTRAK AWAL'){
		    		$unit = $alokasi->jumlah_barang_detail;
		    		$nilai = $alokasi->nilai_barang_detail;
		    	}
		    	else if($alokasi->status_alokasi == 'DATA ADENDUM 1'){
		    		$unit = $alokasi->jumlah_barang_rev_1;
		    		$nilai = $alokasi->nilai_barang_rev_1;
		    	}
		    	else if($alokasi->status_alokasi == 'DATA ADENDUM 2'){
		    		$unit = $alokasi->jumlah_barang_rev_2;
		    		$nilai = $alokasi->nilai_barang_rev_2;
		    	}
		    	// $pdf->Cell(2, 1, number_format($unit, 0), 1, 0, 'C');
		    	// $pdf->Cell(3, 1, number_format($nilai, 0), 1, 0, 'C');
		    	// $pdf->Cell(2.75, 1, $alokasi->tahun_anggaran, 1, 0, 'C');
		    	// $pdf->Cell(1.5, 1, 'BAIK', 1, 0, 'C');
		    	// $pdf->Cell(3.25, 1, $alokasi->nama_provinsi, 1, 0, 'C');
		    	// $pdf->Cell(2.25, 1, $alokasi->nama_kabupaten, 1, 0, 'C');
		    	// $pdf->Cell(3, 1, $instansi_penerima, 1, 1, 'C');
		    	$pdf->Row(
		    		array(
			    		$no,
			    		$alokasi->nama_barang,
			    		$alokasi->kode_barang,
			    		$alokasi->merk,
			    		// $alokasi->jenis_barang,
			    		'',
			    		number_format($unit, 0),
			    		number_format($nilai, 0),
			    		$alokasi->tahun_anggaran,
			    		'BAIK',
			    		$alokasi->nama_provinsi,
			    		$alokasi->nama_kabupaten,
			    		$instansi_penerima
		    		)
		    	);

		    	$totalunit += $unit;
		    	$totalnilai += $nilai;
		    	$no ++;
	    	}

	    	$pdf->SetFont('calibri', '', 10);

	    	//footer
	    	$pdf->Cell(11.25, 1, 'JUMLAH', 1, 0, 'R');
	    	$pdf->Cell(2, 1, number_format($totalunit, 0), 1, 0, 'C');
	    	$pdf->Cell(3, 1, number_format($totalnilai, 0), 1, 0, 'C');
	    	$pdf->Cell(12, 1, '', 1, 1, 'C');
	    	
	    	$currY = $pdf->GetY();

	    	//12
	    	if($currY > 12)
	    		$pdf->AddPage();
	    	else
	    		$pdf->Ln();

	 		$pdf->Cell(12, 0.5, 'PIHAK KEDUA', 0, 0, 'C');
			$pdf->Cell(13, 0.5, 'PIHAK KESATU', 0, 1, 'C');

			$pdf->Cell(12, 0.5, 'Yang Menerima', 0, 0, 'C');
			$pdf->Cell(13, 0.5, 'Yang Menyerahkan', 0, 1, 'C');

			$pdf->Cell(12, 0.5, 'a.n '.($titik_serah == 'PROVINSI' ? 'Gubernur Provinsi' : ($titik_serah == 'KABUPATEN' ? 'Bupati Kabupaten' : 'Walikota')).' '.$nama_wilayah, 0, 0, 'C');
			$pdf->Cell(13, 0.5, 'a.n Gubernur Provinsi '.$data_alokasi[0]->nama_provinsi, 0 , 1, 'C');

			$pdf->Cell(12, 0.5, $jabatan_penerima, 0, 0, 'C');
			$pdf->Cell(13, 0.5, $jabatan_penyerah, 0, 1, 'C');

	 		$pdf->Ln();
	 		$pdf->Ln();
	 		$pdf->Ln();
	 		$pdf->Ln();
	 		$pdf->Ln();

	 		$pdf->Cell(12, 0.5, $nama_penerima, 0, 0, 'C');
			$pdf->Cell(13, 0.5, $nama_penyerah, 0, 1, 'C');

			$pdf->Cell(12, 0.5, 'NIP. '.$nip_penerima, 0 , 0, 'C');
			$pdf->Cell(13, 0.5, 'NIP. '.$nip_penyerah, 0 , 1, 'C');

	        $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/lamp_naskah_hibah/lamp_'.$this->clean($no_naskah_hibah).'_'.$idinserted.'.pdf', 'F');
	        // $pdf->Output();
	        // exit(1);
		// }

        // ************************************************************************************************************************
        // generate pdf bast bmn
        // ************************************************************************************************************************

		// if($no_bast_bmn != ''){
			
			if($tanggal_bast_bmn != ''){
				$hari = 'Senin';
				$bulan = 'Januari';
				$mo = date('n', strtotime($tanggal_bast_bmn));

				$day = date('D', strtotime($tanggal_bast_bmn));
				if($day == 'Mon')
					$hari = 'Senin';
				if($day == 'Tue')
					$hari = 'Selasa';
				if($day =='Wed')
					$hari = 'Rabu';
				if($day == 'Thu')
					$hari = 'Kamis';
				if($day == 'Fri')
					$hari = 'Jumat';
				if($day == 'Sat')
					$hari = 'Sabtu';
				if($day == 'Sun')
					$hari = 'Minggu';

				
				
				if($mo == 1)
					$bulan = 'Januari';
				if($mo == 2)
					$bulan = 'Februari';
				if($mo == 3)
					$bulan = 'Maret';
				if($mo == 4)
					$bulan = 'April';
				if($mo == 5)
					$bulan = 'Mei';
				if($mo == 6)
					$bulan = 'Juni';
				if($mo == 7)
					$bulan = 'Juli';
				if($mo == 8)
					$bulan = 'Agustus';
				if($mo == 9)
					$bulan = 'September';
				if($mo == 10)
					$bulan = 'Oktober';
				if($mo == 11)
					$bulan = 'Nopember';
				if($mo == 12)
					$bulan = 'Desember';

				$tanggal = ucwords($this->terbilang(date('j', strtotime($tanggal_bast_bmn))));
				$tahun = ucwords($this->terbilang(date('Y', strtotime($tanggal_bast_bmn))));
			}
			else{
				$hari = '...............';
				$tanggal = '......';
				$bulan = '......';
				$tahun = '.........';
			}

			$pdf = new FPDF('P','cm','A4');
			$pdf->AddFont('calibri','','calibri.php');
	        $pdf->AddFont('calibri','B','calibrib.php');
	        // membuat halaman baru
	        $pdf->AddPage();
	        // $pdf->Image('assets/img/Kop.jpg', -1, 1, -195);
	        
	        $pdf->SetFont('calibri', 'B', 11);
	        $pdf->SetXY(1, 5);
	        $pdf->Cell(0, 0.5,'BERITA ACARA SERAH TERIMA HIBAH BARANG MILIK NEGARA', 0, 1, 'C');
	        $pdf->Cell(0, 0.5, 'DARI DIREKTORAT JENDERAL PRASARANA DAN SARANA PERTANIAN', 0, 1, 'C');
	        $pdf->Cell(0, 0.5, 'KEPADA '.$instansi_penerima, 0, 1, 'C');

	       	$pdf->Cell(0, 1, 'NOMOR : '.$no_bast_bmn, 0, 1, 'C');

	       	$pdf->SetFont('calibri', '', 10);
	       	$pdf->Cell(0, 1, 'Pada hari ini '.$hari.' tanggal '.$tanggal.' bulan '.$bulan.' tahun '.$tahun.', kami yang bertandatangan di bawah ini :', 0, 1, 'L');
			$pdf->Cell(0, 1, 'I. 	Nama  	  :   '.$nama_penyerah,0, 1, 'L');
			$pdf->Cell(0, 1, '		 NIP   	     :   '.$nip_penyerah,0, 1, 'L');
			$pdf->Cell(0, 1, '		 Jabatan 	:   '.$jabatan_penyerah,0, 1, 'L');

			$pdf->SetXY(1.5, 11.5);
			$pdf->MultiCell(0, 0.5, 'Yang bertindak untuk dan atas nama Menteri Pertanian berkedudukan di '.$alamat_dinas_penyerah.' selanjutnya disebut PIHAK KESATU.',0, 'L', false);

			$pdf->SetXY(1, 13);
			$pdf->Cell(0, 1, 'II. 	Nama  	 :   '.$nama_penerima,0, 1, 'L');
			$pdf->Cell(0, 1, '		  NIP   	     :   '.$nip_penerima,0, 1, 'L');
			$pdf->Cell(0, 1, '		  Jabatan 	:   '.$jabatan_penerima,0, 1, 'L');

			$pdf->SetXY(1.5, 16);
			$pdf->MultiCell(0, 0.5, 'Selaku Unit Akuntansi Kuasa Pengguna Barang (UAKPB) berkedudukan di '.$alamat_dinas_penerima.' selanjutnya disebut PIHAK KEDUA.',0, 'L', false);

			$pdf->SetXY(1, 17.5);
			$pdf->MultiCell(0, 0.5, 'Dalam rangka tertib administrasi pengelolaan, pencatatan dan pelaporan Barang Milik Negara, dengan ini kedua belah pihak sepakat untuk melaksanakan serah terima Barang Milik Negara kegiatan Direktorat Jenderal Prasarana dan Sarana Pertanian Kementerian Pertanian dengan ketentuan sebagai berikut :',0, 'L', false);

			$pdf->SetXY(1, 20);
	        $pdf->Cell(0, 0.5,'Pasal 1', 0, 1, 'C');
	 		$pdf->MultiCell(0, 0.5, 'PIHAK KESATU menyerahkan Barang Milik Negara kepada PIHAK KEDUA dan PIHAK KEDUA menerima Barang Milik Negara kegiatan Direktorat Jenderal Prasarana dan Sarana Pertanian pada Satker Direktorat Jenderal Prasarana dan Sarana Pertanian kantor Provinsi kode satker 18.08.199.633656 beserta Dokumen pendukungnya sebagaimana tertera dalam daftar lampiran berita acara',0, 'L', false);

			$pdf->SetXY(1, 23.5);
	        $pdf->Cell(0, 0.5,'Pasal 2', 0, 1, 'C');
	 		$pdf->MultiCell(0, 0.5, 'PIHAK KESATU selanjutnya akan menghapuskan dari buku inventaris Unit Akuntansi Kuasa Pengguna Barang Direktorat Jenderal Prasarana dan Sarana Pertanian dan PIHAK KEDUA akan membukukan dalam buku inventaris Pemerintah Daerah '.(substr($nama_wilayah, 0, 4) == 'KOTA' ? '' : $titik_serah).' '.$nama_wilayah.' pada '.$instansi_penerima.'.',0, 'L', false);

	 		//page 2
			$pdf->AddPage();
	        // $pdf->Image('assets/img/Kop.jpg', -1, 1, -195);
	        
	        $baris = 5.5;
	        $pdf->SetFont('calibri', '', 10);
	        $pdf->SetXY(1, $baris);
	        $pdf->Cell(0, 0.5,'Pasal 3', 0, 1, 'C');
	        $baris = $baris + 0.5;
	        $pdf->SetXY(1, $baris);
	        $pdf->MultiCell(0, 0.5, 'Dengan ditandatangani Berita Acara Serah Terima ini oleh kedua belah pihak, maka segala sesuatu yang berkaitan dengan barang tersebut, sepenuhnya menjadi tanggung jawab pihak KEDUA.');
	        $baris = $baris + 1.5;
	 		$pdf->SetXY(1, $baris);
	 		$pdf->MultiCell(0, 0.5, 'Demikian berita acara serah terima ini dibuat dan ditandatangani kedua belah pihak pada tanggal tersebut di atas.');
	 		$baris = $baris + 3;
	 		$pdf->SetXY(1, $baris);

	 		$pdf->Cell(9, 0.5, 'PIHAK KEDUA', 0, 0, 'C');
			$pdf->Cell(10, 0.5, 'PIHAK KESATU', 0, 1, 'C');

			$pdf->Cell(9, 0.5, 'Yang Menerima', 0, 0, 'C');
			$pdf->Cell(10, 0.5, 'Yang Menyerahkan', 0, 1, 'C');

			$pdf->Cell(9, 0.5, 'a.n '.($titik_serah == 'PROVINSI' ? 'Gubernur Provinsi' : ($titik_serah == 'KABUPATEN' ? 'Bupati Kabupaten' : 'Walikota')).' '.$nama_wilayah, 0, 0, 'C');
			$pdf->Cell(10, 0.5, 'a.n Gubernur Provinsi '.$data_alokasi[0]->nama_provinsi, 0 , 1, 'C');

			$pdf->Cell(9, 0.5, $jabatan_penerima, 0, 0, 'C');
			$pdf->Cell(10, 0.5, $jabatan_penyerah, 0, 1, 'C');

	 		$pdf->Ln();
	 		$pdf->Ln();
	 		$pdf->Ln();
	 		$pdf->Ln();
	 		$pdf->Ln();
	 		$pdf->Ln();

	 		$pdf->Cell(9, 0.5, $nama_penerima, 0, 0, 'C');
			$pdf->Cell(10, 0.5, $nama_penyerah, 0, 1, 'C');

			$pdf->Cell(9, 0.5, 'NIP. '.$nip_penerima, 0 , 0, 'C');
			$pdf->Cell(10, 0.5, 'NIP. '.$nip_penyerah, 0 , 1, 'C');

	        $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/bast_bmn/'.$this->clean($no_bast_bmn).'_'.$idinserted.'.pdf', 'F');
	        // $pdf->Output();
	        // exit(1);

	        // ************************************************************************************************************************
	        // generate pdf lampiran bast bmn
	        // ************************************************************************************************************************
	        $pdf = new PDF_MC_Table('L','cm','A4');
			$pdf->AddFont('calibri','','calibri.php');
	        $pdf->AddFont('calibri','B','calibrib.php');
	        // membuat halaman baru
	        $pdf->AddPage();
	        // $pdf->Image('assets/img/Kop.jpg', -1, 1, -195);
	        
	        $pdf->SetFont('calibri', 'B', 11);
	        $pdf->SetXY(1, 1);
	        $pdf->Cell(0, 1,'LAMPIRAN BERITA ACARA SERAH TERIMA BMN', 0, 1, 'L');

	       	$pdf->Cell(0, 1, 'NOMOR : '.$no_bast_bmn, 0, 1, 'L');
	       	if($tanggal_bast_bmn != '')
	       		$pdf->Cell(0, 1, 'TANGGAL : '.date('j', strtotime($tanggal_bast_bmn)).' '.$bulan.' '.date('Y', strtotime($tanggal_bast_bmn)), 0, 1, 'L');
	       	else
	       		$pdf->Cell(0, 1, 'TANGGAL : ...............................', 0, 1, 'L');
	       	
	       	$pdf->SetFont('calibri', '', 10);
	       	
	       	$pdf->SetXY(1, 5);

			//header
	    	$pdf->Cell(1, 1, 'No.', 1, 0, 'C');
	    	$pdf->Cell(3, 1, 'Nama Barang', 1, 0, 'C');
	    	$pdf->Cell(2.5, 1, 'Kode Barang', 1, 0, 'C');
	    	$pdf->Cell(2.75, 1, 'Merk', 1, 0, 'C');
	    	$pdf->Cell(2, 1, 'Type', 1, 0, 'C');
	    	$pdf->Cell(2, 1, 'Jumlah Unit', 1, 0, 'C');
	    	$pdf->Cell(3, 1, 'Harga Perolehan', 1, 0, 'C');
	    	$pdf->Cell(2.75, 1, 'Tahun Perolehan', 1, 0, 'C');;
	    	$pdf->Cell(1.5, 1, 'Kondisi', 1, 0, 'C');
	    	$pdf->Cell(3, 1, 'Provinsi', 1, 0, 'C');
	    	$pdf->Cell(2.25, 1, 'Kabupaten', 1, 0, 'C');
	    	$pdf->Cell(2.5, 1, 'Nama Dinas', 1, 1, 'C');
	    	
	    	//data
	    	
	    	$pdf->SetFont('calibri', '', 8.5);

	    	$totalunit = 0;
	    	$totalnilai = 0;
	    	$unit = 0;
	    	$nilai = 0;
	    		       	

	    	$pdf->SetWidths(array(1,3,2.5,2.75,2,2,3,2.75,1.5,3,2.25,2.5));
			    
	    	$no = 1;
	    	foreach($data_alokasi as $alokasi){

	    		$adjust = ($no - 1) * 2;

	    		// $pdf->Cell(1, 1, $no, 1, 0, 'C');
		    	// $pdf->Cell(3, 1, $alokasi->nama_barang, 1, 0, 'C');
		    	// $pdf->Cell(2, 1, $alokasi->kode_barang, 1, 0, 'C');
		    	// $pdf->Cell(3.5, 1, $alokasi->merk, 1, 0, 'C');
		    	// $pdf->Cell(1, 1, $alokasi->jenis_barang, 1, 0, 'C');

		    	if($alokasi->status_alokasi == 'DATA KONTRAK AWAL'){
		    		$unit = $alokasi->jumlah_barang_detail;
		    		$nilai = $alokasi->nilai_barang_detail;
		    	}
		    	else if($alokasi->status_alokasi == 'DATA ADENDUM 1'){
		    		$unit = $alokasi->jumlah_barang_rev_1;
		    		$nilai = $alokasi->nilai_barang_rev_1;
		    	}
		    	else if($alokasi->status_alokasi == 'DATA ADENDUM 2'){
		    		$unit = $alokasi->jumlah_barang_rev_2;
		    		$nilai = $alokasi->nilai_barang_rev_2;
		    	}
		    	// $pdf->Cell(2, 1, number_format($unit, 0), 1, 0, 'C');
		    	// $pdf->Cell(3, 1, number_format($nilai, 0), 1, 0, 'C');
		    	// $pdf->Cell(2.75, 1, $alokasi->tahun_anggaran, 1, 0, 'C');
		    	// $pdf->Cell(1.5, 1, 'BAIK', 1, 0, 'C');
		    	// $pdf->Cell(3.25, 1, $alokasi->nama_provinsi, 1, 0, 'C');
		    	// $pdf->Cell(2.25, 1, $alokasi->nama_kabupaten, 1, 0, 'C');
		    	// $pdf->Cell(3, 1, $instansi_penerima, 1, 1, 'C');
		    	$pdf->Row(
		    		array(
			    		$no,
			    		$alokasi->nama_barang,
			    		$alokasi->kode_barang,
			    		$alokasi->merk,
			    		// $alokasi->jenis_barang,
			    		'',
			    		number_format($unit, 0),
			    		number_format($nilai, 0),
			    		$alokasi->tahun_anggaran,
			    		'BAIK',
			    		$alokasi->nama_provinsi,
			    		$alokasi->nama_kabupaten,
			    		$instansi_penerima
		    		)
		    	);

		    	$totalunit += $unit;
		    	$totalnilai += $nilai;
		    	$no ++;
	    	}

	    	$pdf->SetFont('calibri', '', 10);
	    	//footer
	    	$pdf->Cell(11.25, 1, 'JUMLAH', 1, 0, 'R');
	    	$pdf->Cell(2, 1, number_format($totalunit, 0), 1, 0, 'C');
	    	$pdf->Cell(3, 1, number_format($totalnilai, 0), 1, 0, 'C');
	    	$pdf->Cell(12, 1, '', 1, 1, 'C');

	 		if($currY > 12)
	    		$pdf->AddPage();
	    	else
	    		$pdf->Ln();

	 		$pdf->Cell(12, 0.5, 'PIHAK KEDUA', 0, 0, 'C');
			$pdf->Cell(13, 0.5, 'PIHAK KESATU', 0, 1, 'C');

			$pdf->Cell(12, 0.5, 'Yang Menerima', 0, 0, 'C');
			$pdf->Cell(13, 0.5, 'Yang Menyerahkan', 0, 1, 'C');

			$pdf->Cell(12, 0.5, 'a.n '.($titik_serah == 'PROVINSI' ? 'Gubernur Provinsi' : ($titik_serah == 'KABUPATEN' ? 'Bupati Kabupaten' : 'Walikota')).' '.$nama_wilayah, 0, 0, 'C');
			$pdf->Cell(13, 0.5, 'a.n Gubernur Provinsi '.$data_alokasi[0]->nama_provinsi, 0 , 1, 'C');

			$pdf->Cell(12, 0.5, $jabatan_penerima, 0, 0, 'C');
			$pdf->Cell(13, 0.5, $jabatan_penyerah, 0, 1, 'C');

	 		$pdf->Ln();
	 		$pdf->Ln();
	 		$pdf->Ln();
	 		$pdf->Ln();
	 		$pdf->Ln();

	 		$pdf->Cell(12, 0.5, $nama_penerima, 0, 0, 'C');
			$pdf->Cell(13, 0.5, $nama_penyerah, 0, 1, 'C');

			$pdf->Cell(12, 0.5, 'NIP. '.$nip_penerima, 0 , 0, 'C');
			$pdf->Cell(13, 0.5, 'NIP. '.$nip_penyerah, 0 , 1, 'C');

	        $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/lamp_bast_bmn/lamp_'.$this->clean($no_bast_bmn).'_'.$idinserted.'.pdf', 'F');
			// $pdf->Output();
			// exit(1);
	    // }
        // ************************************************************************************************************************
        // generate pdf surat pernyataan
        // ************************************************************************************************************************
	    // if($no_surat_pernyataan != '' and $tanggal_surat_pernyataan != ''){
	    	

			$pdf = new FPDF('P','cm','A4');
			$pdf->AddFont('calibri','','calibri.php');
	        $pdf->AddFont('calibri','B','calibrib.php');
	        // membuat halaman baru
	        $pdf->AddPage();
	        // $pdf->Image('assets/img/Kop.jpg', -1, 1, -195);
	        
	        $pdf->SetFont('calibri', 'B', 11);
	        $pdf->SetXY(1, 5);
	        $pdf->Cell(0, 0.5,'SURAT PERNYATAAN', 0, 1, 'C');

	       	$pdf->Cell(0, 1, 'NOMOR : '.$no_surat_pernyataan, 0, 1, 'C');

	       	$pdf->SetFont('calibri', '', 10);

			$pdf->SetXY(1, 6.5);
			$pdf->MultiCell(0, 0.5, 'Yang bertandatangan di bawah ini :',0, 'L', false);

			$pdf->SetXY(1, 7.5);
			$pdf->Cell(0, 1, 'Nama  	                              :   '.$nama_penerima, 0, 1, 'L');
			$pdf->Cell(0, 1, 'NIP   	                                 :   '.$nip_penerima, 0, 1, 'L');
			$pdf->Cell(0, 1, 'Pangkat/Golongan           :   '.$pangkat_penerima, 0, 1, 'L');
			$pdf->Cell(0, 1, 'Jabatan 	                            :   '.$jabatan_penerima, 0, 1, 'L');

			$pdf->SetXY(1, 12);
			$pdf->MultiCell(0, 0.5, 'Berdasarkan Peraturan Menteri Keuangan (PMK) Nomor : 111/PMK.06 /2016 tentang Tata Cara Pelaksanaan Pemindahtanganan Barang Milik Negara dengan ini kami menyatakan bersedia menerima Hibah Barang Milik Negara (BMN) yang akan digunakan untuk penyelenggaraan tugas dan fungsi Pemerintah Daerah pada Kantor '.$instansi_penerima.' dengan jenis barang sebagai berikut :',0, 'L', false);

			$pdf->SetXY(1, 14.5);
	       	
			//header
	    	$pdf->Cell(2.5, 1, 'No.', 1, 0, 'C');
	    	$pdf->Cell(5, 1, 'Jenis Kegiatan', 1, 0, 'C');
	    	$pdf->Cell(3.5, 1, 'AKUN', 1, 0, 'C');
	    	$pdf->Cell(3.5, 1, 'Jumlah', 1, 0, 'C');
	    	$pdf->Cell(4, 1, 'Nilai(Rp)', 1, 1, 'C');
	    	
	    	//data
	    	$no = 1;
	    	$totalunit = 0;
	    	$totalnilai = 0;

	    	$data_alokasi_sp = $this->AlokasiProvinsiModel->GetByHibahGrouping($idinserted);

	    	foreach($data_alokasi_sp as $alokasi){
	    		$pdf->Cell(2.5, 1, $no, 1, 0, 'C');
		    	$pdf->Cell(5, 1, $alokasi->nama_barang, 1, 0, 'C');
		    	$pdf->Cell(3.5, 1, $alokasi->akun, 1, 0, 'C');

		    	$unit = $alokasi->total_unit;
		    	$nilai = $alokasi->total_nilai;

		    	$pdf->Cell(3.5, 1, number_format($unit, 0), 1, 0, 'C');
		    	$pdf->Cell(4, 1, number_format($nilai, 0), 1, 1, 'C');
		    	
		    	$totalunit = $totalunit + $unit;
		    	$totalnilai = $totalnilai + $nilai;
		    	$no ++;
	    	}

	    	//footer
	    	$pdf->Cell(11, 1, 'TOTAL', 1, 0, 'R');
	    	$pdf->Cell(3.5, 1, number_format($totalunit, 0), 1, 0, 'C');
	    	$pdf->Cell(4, 1, number_format($totalnilai, 0), 1, 1, 'C');

	 		$pdf->MultiCell(0, 1, 'Demikian pernyataan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.', 0, 'L', false);

	 		$currY = $pdf->GetY();
	 		if($currY > 22)
	    		$pdf->AddPage();
	    	else
	    		$pdf->Ln();

	 		// $pdf->Cell(10, 0.5, $nama_wilayah.', '.date('j', strtotime($tanggal_surat_pernyataan)).' '.$bulan.' '.date('Y', strtotime($tanggal_surat_pernyataan)), 0, 1, 'C');

	 		if($tanggal_surat_pernyataan != '')
	       		$pdf->Cell(10, 0.5, $nama_wilayah.', '.date('j', strtotime($tanggal_surat_pernyataan)).' '.$bulan.' '.date('Y', strtotime($tanggal_surat_pernyataan)), 0, 1, 'C');
	       	else
	       		$pdf->Cell(10, 0.5, $nama_wilayah.', ...............................', 0, 1, 'C');

	 		$pdf->Cell(10, 0.5,'a.n '.($titik_serah == 'PROVINSI' ? 'Gubernur Provinsi' : ($titik_serah == 'KABUPATEN' ? 'Bupati Kabupaten' : 'Walikota')).' '.$nama_wilayah, 0, 1, 'C');
	 		$pdf->Cell(10, 0.5, $jabatan_penerima, 0, 1, 'C');
	 		
	 		$pdf->Ln();
	    	$pdf->Ln();
	    	$pdf->Ln();
			$pdf->Ln();
	    	$pdf->Ln();

	 		$pdf->Cell(10, 0.5, $nama_penerima, 0, 1, 'C');
	 		$pdf->Cell(10, 0.5, 'NIP. '.$nip_penerima, 0, 1, 'C');

	        $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/surat_pernyataan/'.$this->clean($no_surat_pernyataan).'_'.$idinserted.'.pdf', 'F');
	 		// 	$pdf->Output();
			// exit(1);

	        // ************************************************************************************************************************
	        // generate pdf lampiran surat pernyataan
	        // ************************************************************************************************************************
	        $pdf = new PDF_MC_Table('L','cm','A4');
			$pdf->AddFont('calibri','','calibri.php');
	        $pdf->AddFont('calibri','B','calibrib.php');
	        // membuat halaman baru
	        $pdf->AddPage();
	        // $pdf->Image('assets/img/Kop.jpg', -1, 1, -195);
	        
	        $pdf->SetFont('calibri', 'B', 11);
	        $pdf->SetXY(1, 1);
	        $pdf->Cell(0, 1,'LAMPIRAN SURAT PERNYATAAN', 0, 1, 'L');

	       	$pdf->Cell(3, 1, 'NOMOR', 0, 0, 'L');
	       	$pdf->Cell(0.5, 1, ':', 0, 0, 'L');
	       	$pdf->Cell(4, 1, $no_surat_pernyataan, 0, 1, 'L');

	       	$pdf->Cell(3, 1, 'TANGGAL', 0, 0, 'L');
	       	$pdf->Cell(0.5, 1, ':', 0, 0, 'L');
	       	// $pdf->Cell(4, 1, date('j', strtotime($tanggal_surat_pernyataan)).' '.$bulan.' '.date('Y', strtotime($tanggal_surat_pernyataan)), 0, 1, 'L');
	       	if($tanggal_surat_pernyataan != '')
	       		$pdf->Cell(4, 1, date('j', strtotime($tanggal_surat_pernyataan)).' '.$bulan.' '.date('Y', strtotime($tanggal_surat_pernyataan)), 0, 1, 'L');
	       	else
	       		$pdf->Cell(4, 1, '...............................', 0, 1, 'L');

	       	$pdf->Cell(3, 1, $titik_serah, 0, 0, 'L');
	       	$pdf->Cell(0.5, 1, ':', 0, 0, 'L');
	       	$pdf->Cell(4, 1, $nama_wilayah, 0, 1, 'L');

	       	$pdf->Cell(3, 1, 'ESELON I', 0, 0, 'L');
	       	$pdf->Cell(0.5, 1, ':', 0, 0, 'L');
	       	$pdf->Cell(4, 1, 'DITJEN PRASARANA DAN SARANA PERTANIAN', 0, 1, 'L');

	       	$pdf->Cell(3, 1, 'KEMENTERIAN', 0, 0, 'L');
	       	$pdf->Cell(0.5, 1, ':', 0, 0, 'L');
	       	$pdf->Cell(4, 1, 'KEMENTERIAN PERTANIAN', 0, 1, 'L');

	       	$pdf->SetFont('calibri', '', 10);
	       	
	       	$pdf->SetXY(1, 7);

			//header
	    	$pdf->Cell(1, 2, 'No.', 1, 0, 'C');
	    	$pdf->Cell(3, 2, 'Nama Barang', 1, 0, 'C');
	    	$pdf->Cell(3, 2, 'Kode Barang', 1, 0, 'C');
	    	$pdf->Cell(5, 2, 'Merk', 1, 0, 'C');
	    	$pdf->Cell(1.25, 2, 'Model', 1, 0, 'C');
	    	$pdf->MultiCell(1.25, 1, 'Jumlah Unit', 1, 'C');
	    	$x = 15.5;
	    	$pdf->SetXY($x, 7);
	    	$pdf->MultiCell(2.5, 1, 'Harga Perolehan', 1, 'C');
	    	$x+=2.5;
	    	$pdf->SetXY($x, 7);
	    	$pdf->MultiCell(2, 1, 'Tahun Perolehan', 1, 'C');
	    	$x+=2;
	    	$pdf->SetXY($x, 7);
	    	$pdf->Cell(3, 1, 'Kondisi', 1, 0, 'C');
	    	$pdf->SetXY($x, 8);
	    	$pdf->Cell(1, 1, 'B', 1, 0, 'C');
	    	$pdf->Cell(1, 1, 'RR', 1, 0, 'C');
	    	$pdf->Cell(1, 1, 'RB', 1, 0, 'C');
	    	$x+=3;
	    	$pdf->SetXY($x, 7);
	    	$pdf->Cell(5, 2, 'Lokasi', 1, 1, 'C');
	    	
	    	//data
	    	$no = 1;
	    	$pdf->SetFont('calibri', '', 8.5);

	    	$totalunit = 0;
	    	$totalnilai = 0;
	    	$unit = 0;
	    	$nilai = 0;
	    	$pdf->SetWidths(array(1,3,3,5,1.25,1.25,2.5,2,1,1,1,5));

	    	foreach($data_alokasi as $alokasi){

	    		$adjust = ($no - 1) * 2;

	    		// $pdf->Cell(1, 1, $no, 1, 0, 'C');
		    	// $pdf->Cell(3, 1, $alokasi->nama_barang, 1, 0, 'C');
		    	// $pdf->Cell(3, 1, $alokasi->kode_barang, 1, 0, 'C');
		    	// $pdf->Cell(5, 1, $alokasi->merk, 1, 0, 'C');
		    	// $pdf->Cell(1.25, 1, '', 1, 0, 'C');

		    	if($alokasi->status_alokasi == 'DATA KONTRAK AWAL'){
		    		$unit = $alokasi->jumlah_barang_detail;
		    		$nilai = $alokasi->nilai_barang_detail;
		    	}
		    	else if($alokasi->status_alokasi == 'DATA ADENDUM 1'){
		    		$unit = $alokasi->jumlah_barang_rev_1;
		    		$nilai = $alokasi->nilai_barang_rev_1;
		    	}
		    	else if($alokasi->status_alokasi == 'DATA ADENDUM 2'){
		    		$unit = $alokasi->jumlah_barang_rev_2;
		    		$nilai = $alokasi->nilai_barang_rev_2;
		    	}
		    	// $pdf->Cell(1.25, 1, number_format($unit, 0), 1, 0, 'C');
		    	// $pdf->Cell(2.5, 1, number_format($nilai, 0), 1, 0, 'C');
		    	// $pdf->Cell(2, 1, $alokasi->tahun_anggaran, 1, 0, 'C');
		    	// $pdf->Cell(1, 1, 'B', 1, 0, 'C');
		    	// $pdf->Cell(1, 1, '-', 1, 0, 'C');
		    	// $pdf->Cell(1, 1, '-', 1, 0, 'C');
		    	// $pdf->Cell(5, 1, $titik_serah.' '.$nama_wilayah, 1, 1, 'C');
		    	$pdf->Row(
		    		array(
			    		$no,
			    		$alokasi->nama_barang,
			    		$alokasi->kode_barang,
			    		$alokasi->merk,
			    		// $alokasi->jenis_barang,
			    		'',
			    		number_format($unit, 0),
			    		number_format($nilai, 0),
			    		$alokasi->tahun_anggaran,
			    		'B',
			    		'-',
			    		'-',
			    		$titik_serah.' '.$nama_wilayah,
		    		)
		    	);
		    	
		    	$totalunit += $unit;
		    	$totalnilai += $nilai;
		    	$no ++;
	    	}

	    	$pdf->SetFont('calibri', '', 10);
	    	//footer
	    	$pdf->Cell(13.25, 1, 'JUMLAH', 1, 0, 'R');
	    	$pdf->Cell(1.25, 1, number_format($totalunit, 0), 1, 0, 'C');
	    	$pdf->Cell(2.5, 1, number_format($totalnilai, 0), 1, 0, 'C');
	    	$pdf->Cell(10, 1, '', 1, 1, 'C');

	    	$currY = $pdf->GetY();

	        if($currY > 13){
	        	$pdf->AddPage();
	        	$baris = 2;
	        }
	    	else{
	    		if($currY == 13)
	    			$baris = $currY + 0.5;
	    		else
	    			$baris = $currY + 1;
	    	}
	    		
			
	        $pdf->SetXY(16, $baris);
	 		if($tanggal_surat_pernyataan != '')
	       		$pdf->Cell(0, 0.5, $nama_wilayah.', '.date('j', strtotime($tanggal_surat_pernyataan)).' '.$bulan.' '.date('Y', strtotime($tanggal_surat_pernyataan)), 0, 1, 'C');
	       	else
	       		$pdf->Cell(10, 0.5, $nama_wilayah.', ...............................', 0, 1, 'C');

	 		$baris+=0.5;
	 		$pdf->SetXY(12, $baris);
	 		$pdf->Cell(0, 0.5,'a.n '.($titik_serah == 'PROVINSI' ? 'Gubernur Provinsi' : ($titik_serah == 'KABUPATEN' ? 'Bupati Kabupaten' : 'Walikota')).' '.$nama_wilayah, 0, 1, 'C');
	 		$baris+=0.5;
	 		$pdf->SetXY(12, $baris);
	 		$pdf->Cell(0, 0.5,$jabatan_penerima, 0, 1, 'C');
	 		$baris = $baris + 3;
	 		$pdf->SetXY(12, $baris);
	 		$pdf->Cell(0, 0.5, $nama_penerima, 0, 1, 'C');
	 		$baris+=0.5;
	 		$pdf->SetXY(12, $baris);
	 		$pdf->Cell(0, 0.5,'NIP. '.$nip_penerima, 0, 1, 'C');

	        $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/lamp_surat_pernyataan/lamp_'.$this->clean($no_surat_pernyataan).'_'.$idinserted.'.pdf', 'F');
			// $pdf->Output();
			// exit(1);
	    // }

        $this->session->set_flashdata('info','Data inserted successfully.');
		redirect('HibahProvinsi/LihatDokumen?id='.$idinserted);
		// exit(1);
	 	
	}

	public function LihatDokumen()
	{
		$id = $this->input->get('id');

		$hibah_provinsi = $this->HibahProvinsiModel->Get($id);
		$param['hibah_provinsi'] = $hibah_provinsi;
		$param['list_alokasi'] = $this->AlokasiProvinsiModel->GetByHibah($id);

		$param['no_naskah_hibah'] = $this->clean($hibah_provinsi->no_naskah_hibah);
		$param['no_bast_bmn'] = $this->clean($hibah_provinsi->no_bast_bmn);
		$param['no_surat_pernyataan'] = $this->clean($hibah_provinsi->no_surat_pernyataan);

		$this->load->library('parser');
		$data = array(
	        'title' => 'LIHAT DOKUMEN',
	        'content-path' => 'PEGADAAN TP PROVINSI / HIBAH / LIHAT DOKUMEN',
	        'content' => $this->load->view('hibah-provinsi-lihat-dokumen', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);

	}


	public function Test()
	{
	    

			$id = $this->input->post('id_hibah_provinsi');

			$hibah_provinsi = $this->HibahProvinsiModel->Get($id);

			$kodefile_upload = strtotime(NOW);

			if($hibah_provinsi->nama_file != '' and $hibah_provinsi->nama_file != 'null' and !is_null($hibah_provinsi->nama_file) and $hibah_provinsi->nama_file != '[]')
				$nama_file = json_decode($hibah_provinsi->nama_file);
			else
				$nama_file = [];

			foreach($_FILES['file']['tmp_name'] as $key => $value) {
		        array_push($nama_file, $kodefile_upload.basename($_FILES['file']['name'][$key]));
		    }

	        if(count($nama_file) > 10){
	        	$this->session->set_flashdata('error','Jumlah file tidak boleh lebih dari 10. Upload dibatalkan.');
				exit('success');
	        }
	        else{

			    foreach($_FILES['file']['tmp_name'] as $key => $value) {
			        $tempFile = $_FILES['file']['tmp_name'][$key];
			        $targetFile =  $target_file = $_SERVER['DOCUMENT_ROOT'].'/upload/hibah_provinsi/'.$kodefile_upload.basename($_FILES['file']['name'][$key]);
		        	move_uploaded_file($tempFile, $target_file);

			    }

			    $nama_file = json_encode($nama_file);
	        	$data = array(
					'nama_file' => $nama_file,
					'updated_by' => $this->session->userdata('logged_in')->id_pengguna,
					'updated_at' => NOW,
				);
				$this->HibahProvinsiModel->Update($id, $data);

				$this->session->set_flashdata('info','File uploaded successfully.');
				exit('success');
	        }

		    
	}

	public function RemoveImage()
	{
	    
		$id = $this->input->get('id_hibah_provinsi');
		$urutanfile = $this->input->get('urutanfile');

		$hibah_provinsi = $this->HibahProvinsiModel->Get($id);

		if($hibah_provinsi->nama_file != '' and $hibah_provinsi->nama_file != 'null' and !is_null($hibah_provinsi->nama_file) and $hibah_provinsi->nama_file != '[]')
			$images = json_decode($hibah_provinsi->nama_file);
		else
			$images = [];

		$nama_file = $images[$urutanfile];
		unlink($_SERVER['DOCUMENT_ROOT'].'/upload/hibah_provinsi/'.$nama_file);	

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
		$this->HibahProvinsiModel->Update($id, $data);

		$this->session->set_flashdata('info','File berhasil dihapus.');
		exit('success');
	}

	public function BuatDokumen()
	{
		$listIdAlokasi = $this->input->post('listIdAlokasi');
		$arrayid = explode(",", $listIdAlokasi);

		$data_alokasi = array();
		foreach($arrayid as $id){
			$alokasi = $this->AlokasiProvinsiModel->GetData($id);
			array_push($data_alokasi, $alokasi);
		}

		$unit_alokasi = $this->input->post('hdnTotalUnit');
		$nilai_alokasi = $this->input->post('hdnTotalNilai');

		$provinsi = $data_alokasi[0]->nama_provinsi;
		$kabupaten = $data_alokasi[0]->nama_kabupaten;
		foreach($data_alokasi as $alokasi){
			if($alokasi->nama_provinsi != $provinsi){
				$this->session->set_flashdata('error','Provinsi alokasi yang dipilih ada yang berbeda. Silahkan cek kembali pilihan anda.');
     			redirect('HibahProvinsi/Add');
			}
			if($alokasi->nama_kabupaten != $kabupaten){
				$this->session->set_flashdata('error','Kabupaten/Kota alokasi yang dipilih ada yang berbeda. Silahkan cek kembali pilihan anda.');
     			redirect('HibahProvinsi/Add');
			}
		}

		$setting = $this->SettingHibahProvinsiModel->GetSettingUser($this->session->userdata('logged_in')->id_pengguna);
		if(!$setting){
			$this->session->set_flashdata('error','Harap lengkapi data setting umum terlebih dahulu.');
 			redirect('HibahProvinsi/Add');
		}

		$param['setting'] = $setting;
		$param['listIdAlokasi'] = $listIdAlokasi;
		$param['selected_provinsi'] = $provinsi;
		$param['selected_kabupaten'] = $kabupaten;
		$param['unit_alokasi'] = $unit_alokasi;
		$param['nilai_alokasi'] = $nilai_alokasi;

		$this->load->library('parser');
		$data = array(
	        'title' => 'BUAT DOKUMEN',
	        'content-path' => 'PEGADAAN TP PROVINSI / HIBAH / BUAT DOKUMEN',
	        'content' => $this->load->view('hibah-provinsi-buat-dokumen', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);

	}

	public function Edit()
	{
		$id = $this->input->get('id');

		$param['hibah_provinsi'] = $this->HibahProvinsiModel->Get($id);

		$setting = $this->SettingHibahProvinsiModel->GetSettingUser($this->session->userdata('logged_in')->id_pengguna);
		if(!$setting){
			$this->session->set_flashdata('error','Harap lengkapi data setting umum terlebih dahulu.');
 			redirect('HibahProvinsi/Add');
		}

		$data_alokasi = $this->AlokasiProvinsiModel->GetByHibah($id);

		if(!$data_alokasi){
			$this->session->set_flashdata('error','Data alokasi hibah ini tidak ditemukan. Silahkan hapus dan buat ulang data hibah.');
 			redirect('HibahProvinsi');
		}

		$provinsi = $data_alokasi[0]->nama_provinsi;
		$kabupaten = $data_alokasi[0]->nama_kabupaten;

		$param['setting'] = $setting;
		$param['provinsi_alokasi'] = $provinsi;
		$param['kabupaten_alokasi'] = $kabupaten;

		// exit($provinsi);

		$this->load->library('parser');
		$data = array(
	        'title' => 'UBAH HIBAH',
	        'content-path' => 'PEGADAAN TP PROVINSI / HIBAH / UBAH DATA',
	        'content' => $this->load->view('hibah-provinsi-edit', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);

	}

	public function doEdit()
	{	
		$id = $this->input->post('id');

		$hibah_provinsi = $this->HibahProvinsiModel->Get($id);

		$tahun_anggaran = $this->session->userdata('logged_in')->tahun_pengadaan;

		$no_naskah_hibah = $this->input->post('no_naskah_hibah');
		$no_bast_bmn = $this->input->post('no_bast_bmn');
		$no_surat_pernyataan = $this->input->post('no_surat_pernyataan');

		$nama_penyerah = $this->input->post('nama_penyerah');
		$nip_penyerah = $this->input->post('nip_penyerah');
		$pangkat_penyerah = $this->input->post('pangkat_penyerah');
		$jabatan_penyerah = $this->input->post('jabatan_penyerah');
		$alamat_dinas_penyerah = $this->input->post('alamat_dinas_penyerah');

		$titik_serah = $this->input->post('titik_serah');
		$nama_wilayah = $this->input->post('nama_wilayah');
		$instansi_penerima = $this->input->post('instansi_penerima');
		$nama_penerima = $this->input->post('nama_penerima');
		$nip_penerima= $this->input->post('nip_penerima');
		$pangkat_penerima = $this->input->post('pangkat_penerima');
		$jabatan_penerima = $this->input->post('jabatan_penerima');
		$alamat_dinas_penerima = $this->input->post('alamat_dinas_penerima');

		$tanggal_naskah_hibah = $this->input->post('tanggal_naskah_hibah');
		$tanggal_bast_bmn = $this->input->post('tanggal_bast_bmn');
		$tanggal_surat_pernyataan = $this->input->post('tanggal_surat_pernyataan');

		if($nama_penyerah == '' or $nip_penyerah == '' or $pangkat_penyerah == '' or $jabatan_penyerah == '' or $alamat_dinas_penyerah == '' or $titik_serah == '' or $nama_wilayah == '' or $instansi_penerima == '' or $nama_penerima == '' or $nip_penerima == ''  or $pangkat_penerima == ''  or $jabatan_penerima == ''  or $alamat_dinas_penerima == ''){
			$this->session->set_flashdata('error','All fields must be filled.');
     		redirect('HibahProvinsi/Edit?id='.$id);
		}
		else{

			// if( ($no_naskah_hibah != '' and $tanggal_naskah_hibah == '') or ($no_naskah_hibah == '' and $tanggal_naskah_hibah != '') ){
			// 	$this->session->set_flashdata('error','Nomor dan Tanggal Naskah Hibah harus Diisi.');
   //   			redirect('HibahProvinsi/Edit?id='.$id);
			// }

			// if( ($no_bast_bmn != '' and $tanggal_bast_bmn == '') or ($no_bast_bmn == '' and $tanggal_bast_bmn != '') ){
			// 	$this->session->set_flashdata('error','Nomor dan Tanggal BAST BMN harus Diisi.');
   //   			redirect('HibahProvinsi/Edit?id='.$id);
			// }

			// if( ($no_surat_pernyataan != '' and $tanggal_surat_pernyataan == '') or ($no_surat_pernyataan == '' and $tanggal_surat_pernyataan != '') ){
			// 	$this->session->set_flashdata('error','Nomor dan Tanggal Surat Pernyataan harus Diisi.');
   //   			redirect('HibahProvinsi/Edit?id='.$id);
			// }

			//cek no naskah hibah & no bast bmn & no surat pernyataan belum digunakan pada data lainnya karena digunakan utk nama file pdf yang akan digenerate...

			$ganti_naskah_hibah = 0;
			$ganti_bast_bmn = 0;
			$ganti_surat_pernyataan = 0;

			if($hibah_provinsi->no_naskah_hibah != $no_naskah_hibah or strtotime($hibah_provinsi->tanggal_naskah_hibah) != strtotime($tanggal_naskah_hibah)){
				$ganti_naskah_hibah = 1;
			}
			if($hibah_provinsi->no_bast_bmn != $no_bast_bmn or strtotime($hibah_provinsi->tanggal_bast_bmn) != strtotime($tanggal_bast_bmn)){
				$ganti_bast_bmn = 1;
			}
			if($hibah_provinsi->no_surat_pernyataan != $no_surat_pernyataan or strtotime($hibah_provinsi->tanggal_surat_pernyataan) != strtotime($tanggal_surat_pernyataan)){
				$ganti_surat_pernyataan = 1;
			}
			
			// if($ganti_naskah_hibah == 1 and $hibah_provinsi->no_naskah_hibah != $no_naskah_hibah){
			// 	$no_naskah_hibah_terpakai = $this->HibahProvinsiModel->CheckNoNaskahHibah($no_naskah_hibah);
			// 	if($no_naskah_hibah_terpakai and $no_naskah_hibah != ''){
			// 		$this->session->set_flashdata('error','No Naskah Hibah sudah digunakan. Silahkan periksa kembali data anda.');
	  //    			redirect('HibahProvinsi/Edit?id='.$id);
			// 	}
			// 	//hapus file lama
			// 	if (file_exists($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/naskah_hibah/'.$this->clean($hibah_provinsi->no_naskah_hibah).'.pdf'))
			// 		unlink($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/naskah_hibah/'.$this->clean($hibah_provinsi->no_naskah_hibah).'.pdf');
			// 	if (file_exists($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/lamp_naskah_hibah/lamp_'.$this->clean($hibah_provinsi->no_naskah_hibah).'.pdf'))
			// 		unlink($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/lamp_naskah_hibah/lamp_'.$this->clean($hibah_provinsi->no_naskah_hibah).'.pdf');
			// }

			// if($ganti_bast_bmn == 1 and $hibah_provinsi->no_bast_bmn != $no_bast_bmn){
			// 	$no_bast_bmn_terpakai = $this->HibahProvinsiModel->CheckNoBASTBMN($no_bast_bmn);
			// 	if($no_bast_bmn_terpakai and $no_bast_bmn != ''){
			// 		$this->session->set_flashdata('error','No BAST BMN sudah digunakan. Silahkan periksa kembali data anda.');
	  //    			redirect('HibahProvinsi/Edit?id='.$id);
			// 	}
			// 	if (file_exists($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/bast_bmn/'.$this->clean($hibah_provinsi->no_bast_bmn).'.pdf'))	
			// 		unlink($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/bast_bmn/'.$this->clean($hibah_provinsi->no_bast_bmn).'.pdf');
			// 	if (file_exists($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/lamp_bast_bmn/lamp_'.$this->clean($hibah_provinsi->no_bast_bmn).'.pdf'))
			// 		unlink($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/lamp_bast_bmn/lamp_'.$this->clean($hibah_provinsi->no_bast_bmn).'.pdf');
			// }

			// if($ganti_surat_pernyataan == 1 and $hibah_provinsi->no_surat_pernyataan != $no_surat_pernyataan){
			// 	$no_surat_pernyataan_terpakai = $this->HibahProvinsiModel->CheckNoSuratPernyataan($no_surat_pernyataan);
			// 	if($no_surat_pernyataan_terpakai and $no_surat_pernyataan != ''){
			// 		$this->session->set_flashdata('error','No Surat Pernyataan sudah digunakan. Silahkan periksa kembali data anda.');
	  //    			redirect('HibahProvinsi/Edit?id='.$id);
			// 	}
			// 	if (file_exists($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/surat_pernyataan/'.$this->clean($hibah_provinsi->no_surat_pernyataan).'.pdf'))
			// 		unlink($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/surat_pernyataan/'.$this->clean($hibah_provinsi->no_surat_pernyataan).'.pdf');

			// 	if (file_exists($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/lamp_surat_pernyataan/lamp_'.$this->clean($hibah_provinsi->no_surat_pernyataan).'.pdf'))
			// 		unlink($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/lamp_surat_pernyataan/lamp_'.$this->clean($hibah_provinsi->no_surat_pernyataan).'.pdf');
			// }

			if($tanggal_naskah_hibah != '')
				$tanggal_naskah_hibah = DateTime::createFromFormat('d-m-Y', $tanggal_naskah_hibah)->format('Y-m-d');
			else
				$tanggal_naskah_hibah = null;

			if($tanggal_bast_bmn != '')
				$tanggal_bast_bmn = DateTime::createFromFormat('d-m-Y', $tanggal_bast_bmn)->format('Y-m-d');
			else
				$tanggal_bast_bmn = null;

			if($tanggal_surat_pernyataan != '')
				$tanggal_surat_pernyataan = DateTime::createFromFormat('d-m-Y', $tanggal_surat_pernyataan)->format('Y-m-d');
			else
				$tanggal_surat_pernyataan = null;

			$data = array(
				'no_naskah_hibah' => $no_naskah_hibah,
				'no_bast_bmn' => $no_bast_bmn,
				'no_surat_pernyataan' => $no_surat_pernyataan,
				'tanggal_naskah_hibah' => $tanggal_naskah_hibah,
				'tanggal_bast_bmn' => $tanggal_bast_bmn,
				'tanggal_surat_pernyataan' => $tanggal_surat_pernyataan,
				'nama_penyerah' => $nama_penyerah,
				'nip_penyerah' => $nip_penyerah,
				'pangkat_penyerah' => $pangkat_penyerah,
				'jabatan_penyerah' => $jabatan_penyerah,
				'alamat_dinas_penyerah' => $alamat_dinas_penyerah,
				'titik_serah' => $titik_serah,
				'nama_wilayah' => $nama_wilayah,
				'instansi_penerima' => $instansi_penerima,
				'nama_penerima' => $nama_penerima,
				'nip_penerima' => $nip_penerima,
				'pangkat_penerima' => $pangkat_penerima,
				'jabatan_penerima' => $jabatan_penerima,
				'alamat_dinas_penerima' => $alamat_dinas_penerima,
				'created_by' => $this->session->userdata('logged_in')->id_pengguna,
				'created_at' => NOW,
			);

			$this->HibahProvinsiModel->Update($id, $data);

			$data_alokasi = $this->AlokasiProvinsiModel->GetByHibah($id);

			$total_unit = $hibah_provinsi->total_unit;
			$total_nilai = $hibah_provinsi->total_nilai;

			//***********************************************************************************
			//																					*
			//re-generate pdf files 															*
			//																					*
			//***********************************************************************************

			// ************************************************************************************************************************
			// generate pdf naskah hibah
			// ************************************************************************************************************************

			// if($no_naskah_hibah != ''){
				
				// echo $tanggal_naskah_hibah;
				// exit(1);
				if($tanggal_naskah_hibah != ''){
					$hari = 'Senin';
					$bulan = 'Januari';
					$mo = date('n', strtotime($tanggal_naskah_hibah));

					$day = date('D', strtotime($tanggal_naskah_hibah));
					if($day == 'Mon')
						$hari = 'Senin';
					if($day == 'Tue')
						$hari = 'Selasa';
					if($day =='Wed')
						$hari = 'Rabu';
					if($day == 'Thu')
						$hari = 'Kamis';
					if($day == 'Fri')
						$hari = 'Jumat';
					if($day == 'Sat')
						$hari = 'Sabtu';
					if($day == 'Sun')
						$hari = 'Minggu';

					
					
					if($mo == 1)
						$bulan = 'Januari';
					if($mo == 2)
						$bulan = 'Februari';
					if($mo == 3)
						$bulan = 'Maret';
					if($mo == 4)
						$bulan = 'April';
					if($mo == 5)
						$bulan = 'Mei';
					if($mo == 6)
						$bulan = 'Juni';
					if($mo == 7)
						$bulan = 'Juli';
					if($mo == 8)
						$bulan = 'Agustus';
					if($mo == 9)
						$bulan = 'September';
					if($mo == 10)
						$bulan = 'Oktober';
					if($mo == 11)
						$bulan = 'Nopember';
					if($mo == 12)
						$bulan = 'Desember';

					$tanggal = ucwords($this->terbilang(date('j', strtotime($tanggal_naskah_hibah))));
					$tahun = ucwords($this->terbilang(date('Y', strtotime($tanggal_naskah_hibah))));
				}
				else{
					$hari = '...............';
					$tanggal = '......';
					$bulan = '......';
					$tahun = '.........';
				}

				$pdf = new FPDF('P','cm','A4');
				$pdf->AddFont('calibri','','calibri.php');
		        $pdf->AddFont('calibri','B','calibrib.php');
		        // membuat halaman baru
		        $pdf->AddPage();
		        // $pdf->Image('assets/img/Kop.jpg', -1, 1, -195);
		        
		        $pdf->SetFont('calibri', 'B', 11);
		        $pdf->SetXY(1, 5);
		        $pdf->Cell(0, 0.5,'NASKAH PERJANJIAN HIBAH BARANG MILIK NEGARA', 0, 1, 'C');
		        $pdf->Cell(0, 0.5, 'BERUPA PERALATAN DAN MESIN TAHUN ANGGARAN '.$tahun_anggaran, 0, 1, 'C');

		        $pdf->Cell(0, 1, 'ANTARA', 0, 1, 'C');
		        $pdf->Cell(0, 1, 'KEMENTERIAN PERTANIAN', 0, 1, 'C');
		        $pdf->Cell(0, 1, 'DENGAN', 0, 1, 'C');
		        $pdf->Cell(0, 1, 'PEMERINTAH '.(substr($nama_wilayah, 0, 4) == 'KOTA' ? '' : $titik_serah).' '.$nama_wilayah, 0, 1, 'C');
		       	$pdf->Cell(0, 1, 'NOMOR : '.$no_naskah_hibah, 0, 1, 'C');

		       	$pdf->SetFont('calibri', '', 10);
		       	$pdf->Cell(0, 1, 'Pada hari ini '.$hari.' tanggal '.$tanggal.' bulan '.$bulan.' tahun '.$tahun.', kami yang bertandatangan di bawah ini :', 0, 1, 'L');
				$pdf->Cell(0, 1, 'I. 	Nama  	  :   '.$nama_penyerah,0, 1, 'L');
				$pdf->Cell(0, 1, '		 NIP   	     :   '.$nip_penyerah,0, 1, 'L');
				$pdf->Cell(0, 1, '		 Jabatan 	:   '.$jabatan_penyerah,0, 1, 'L');

				$pdf->SetXY(1.5, 15);
				$pdf->MultiCell(0, 0.5, 'Yang bertindak untuk dan atas nama Menteri Pertanian berkedudukan di '.$alamat_dinas_penyerah.' selanjutnya disebut PIHAK KESATU.',0, 'L', false);

				$pdf->SetXY(1, 16.5);
				$pdf->Cell(0, 1, 'II. 	Nama  	 :   '.$nama_penerima,0, 1, 'L');
				$pdf->Cell(0, 1, '		  NIP   	     :   '.$nip_penerima,0, 1, 'L');
				$pdf->Cell(0, 1, '		  Jabatan 	:   '.$jabatan_penerima,0, 1, 'L');

				$pdf->SetXY(1.5, 19.5);
				$pdf->MultiCell(0, 0.5, 'Yang bertindak untuk dan atas nama '.($titik_serah == 'PROVINSI' ? 'Gubernur Provinsi' : ($titik_serah == 'KABUPATEN' ? 'Bupati Kabupaten' : 'Walikota')).' '.$nama_wilayah.' berkedudukan di '.$alamat_dinas_penerima.' selanjutnya disebut PIHAK KEDUA.',0, 'L', false);

				$pdf->SetXY(1, 21);
				$pdf->MultiCell(0, 0.5, 'Dalam rangka menindaklanjuti persetujuan hibah Barang Milik Negara dari Sekretaris Jenderal Kementerian Pertanian a.n Menteri Pertanian Nomor ................................................................ tanggal ................................................................ dan sesuai ketentuan Undang-undang Nomor 1 Tahun 2004, Peraturan Pemerintah Nomor 27 Tahun 2014, Peraturan Menteri Keuangan (PMK) Nomor : 111/PMK.06 /2016 tentang Tata Cara Pelaksanaan Pemindahtanganan Barang Milik Negara, PIHAK KESATU menerangkan dengan ini menghibahkan kepada PIHAK KEDUA, dan PIHAK KEDUA menerangkan dengan ini menerima hibah dari PIHAK KESATU, Barang Milik Negara Kementerian Pertanian c.q. Direktorat Jenderal Prasarana dan Sarana Pertanian berupa Peralatan dan Mesin dengan total nilai perolehan sebesar Rp '.number_format($total_nilai, 0).' ('.$this->terbilang($total_nilai).' rupiah) dan total nilai buku sebesar Rp '.number_format($total_nilai, 0).' ('.$this->terbilang($total_nilai).' rupiah) sebagaimana daftar terlampir.',0, 'L', false);

				$pdf->Cell(0, 1, 'Kedua  belah  pihak  menerangkan  bahwa  hibah  ini  dilakukan  dengan  ketentuan  sebagai berikut  :', 0, 1, 'L');

				//page 2
				$pdf->AddPage();
		        // $pdf->Image('assets/img/Kop.jpg', -1, 1, -195);
		        
		        $baris = 5;
		        $pdf->SetFont('calibri', '', 10);
		        $pdf->SetXY(1, $baris);
		        $pdf->Cell(0, 0.5,'Pasal 1', 0, 1, 'C');
		        $pdf->Cell(0, 0.5,'JUMLAH DAN TUJUAN HIBAH', 0, 1, 'C');
		        $baris = $baris + 1.5;
		        $pdf->SetXY(1, $baris);
		 		$pdf->MultiCell(0, 0.5, '(1) 	PIHAK KESATU menghibahkan Barang Milik Negara Kementerian Pertanian Cq. Direkorat Jenderal Prasarana dan Sarana Pertanian sebagaimana  daftar terlampir kepada PIHAK  KEDUA yang merupakan bagian tidak terpisahkan dari Naskah Perjanjian Hibah ini, dengan total nilai perolehan sebesar Rp '.number_format($total_nilai, 0).' ('.$this->terbilang($total_nilai).' rupiah) dan total nilai buku sebesar Rp '.number_format($total_nilai, 0).' ('.$this->terbilang($total_nilai).' rupiah) sebagaimana daftar terlampir.');
		 		$baris = $baris + 2.5;
		 		$pdf->SetXY(1, $baris);
		 		$pdf->MultiCell(0, 0.5, '(2)	Barang Milik Negara sebagaimana dimaksud pada ayat (1) digunakan untuk mendukung penyelenggaraan tugas dan fungsi Pemerintah Daerah '.(substr($nama_wilayah, 0, 4) == 'KOTA' ? '' : $titik_serah).' '.$nama_wilayah.' khususnya dibidang Pertanian, Perkebunan dan Hortikultura.');
		 		$baris = $baris + 2;
		 		$pdf->SetXY(1, $baris);
		        $pdf->Cell(0, 0.5,'Pasal 2', 0, 1, 'C');
		        $pdf->Cell(0, 0.5,'HAK DAN KEWAJIBAN PIHAK KESATU', 0, 1, 'C');
		        $baris = $baris + 1.5;
		 		$pdf->SetXY(1, $baris);
		 		$pdf->MultiCell(0, 0.5, '(1) Menyerahkan Obyek Hibah kepada PIHAK KEDUA;');
		 		$baris = $baris + 0.5;
		 		$pdf->SetXY(1, $baris);
		 		$pdf->MultiCell(0, 0.5, '(2) Mengeluarkan catatan Barang Milik Negara tersebut dari laporan Simak BMN Kementerian Pertanian Cq. Direkorat Jenderal Prasarana dan Sarana Pertanian;.');
		 		$baris = $baris + 1;
		 		$pdf->SetXY(1, $baris);
		 		$pdf->MultiCell(0, 0.5, '(3) Melakukan monitoring atas pelaksanaan Naskah Perjanjian Hibah ini untuk menjamin difungsikannya aset sesuai dengan permohonan hibah, baik secara berkala maupun sewaktu-waktu;');
		 		$baris = $baris + 1;
		 		$pdf->SetXY(1, $baris);
		 		$pdf->MultiCell(0, 0.5, '(4) Meminta keterangan, tanggapan atas penjelasan dari PIHAK KESATU terhadap hal-hal yang diperlukan terkait dengan pelaksanaan monitoring tersebut pada ayat (3).');
		 		$baris = $baris + 1.5;
		 		$pdf->SetXY(1, $baris);
		        $pdf->Cell(0, 0.5,'Pasal 3', 0, 1, 'C');
		        $pdf->Cell(0, 0.5,'KEWAJIBAN PIHAK KEDUA', 0, 1, 'C');
		        $baris = $baris + 1.5;
		 		$pdf->SetXY(1, $baris);
		 		$pdf->MultiCell(0, 0.5, '(1) Menerima Obyek Hibah dari PIHAK KESATU;');
		 		$baris = $baris + 0.5;
		 		$pdf->SetXY(1, $baris);
		 		$pdf->MultiCell(0, 0.5, '(2) Menatausahakan Barang Milik Negara tersebut pada neraca Pemerintah '.(substr($nama_wilayah, 0, 4) == 'KOTA' ? '' : $titik_serah).' '.$nama_wilayah.' sesuai ketentuan yang berlaku;');
		 		$baris = $baris + 1;
		 		$pdf->SetXY(1, $baris);
		 		$pdf->MultiCell(0, 0.5, '(3) Menggunakan dan memelihara Obyek Hibah dengan baik sesuai dengan tujuan hibah;');
		 		$baris = $baris + 0.5;
		 		$pdf->SetXY(1, $baris);
		 		$pdf->MultiCell(0, 0.5, '(4) Melakukan pengamanan Obyek Hibah yang meliputi pengamanan administrasi, fisik dan pengamanan hukum.');
		 		$baris = $baris + 2;
		 		$pdf->SetXY(1, $baris);;
		        $pdf->Cell(0, 0.5,'Pasal 4', 0, 1, 'C');
		        $pdf->Cell(0, 0.5,'SERAH TERIMA', 0, 1, 'C');
		        $baris = $baris + 1.5;
		 		$pdf->SetXY(1, $baris);
		 		$pdf->MultiCell(0, 0.5, 'Penyerahan Barang Milik Negara dituangkan dalam Berita Acara Serah Terima dari '.$jabatan_penyerah.' atas nama Menteri Pertanian kepada '.$jabatan_penerima.' atas nama '.($titik_serah == 'PROVINSI' ? 'Gubernur Provinsi' : ($titik_serah == 'KABUPATEN' ? 'Bupati Kabupaten' : 'Walikota')).' '.$nama_wilayah.' yang merupakan bagian yang tidak terpisahkan dari Naskah Perjanjian Hibah ini.');

		 		//page 3
				$pdf->AddPage();
		        // $pdf->Image('assets/img/Kop.jpg', -1, 1, -195);
		        
		        $baris = 5;
		        $pdf->SetFont('calibri', '', 10);
		        $pdf->SetXY(1, $baris);
		        $pdf->Cell(0, 0.5,'Pasal 5', 0, 1, 'C');
		        $pdf->Cell(0, 0.5,'LAIN-LAIN', 0, 1, 'C');
		        $baris = $baris + 1.5;
		        $pdf->SetXY(1, $baris);
		        $pdf->MultiCell(0, 0.5, '(1) Segala ketentuan dan persyaratan dalam Naskah Perjanjian Hibah ini berlaku serta mengikat bagi PARA PIHAK yang menandatangani;');
		        $baris = $baris + 1;
		 		$pdf->SetXY(1, $baris);
		 		$pdf->MultiCell(0, 0.5, '(2) Naskah Perjanjian Hibah ini dibuat dalam rangkap 4 (empat) masing-masing satu rangkap untuk PIHAK KESATU, PIHAK KEDUA, Sekretaris Jenderal Kementerian Pertanian dan Kepala Kantor Wilayah KPKNL Jakarta II. ');
		 		$baris = $baris + 2;
		 		$pdf->SetXY(1, $baris);
		 		$pdf->MultiCell(0, 0.5, 'Dalam Naskah Perjanjian Hibah ini dibuat dan ditandatangani oleh PARA PIHAK pada hari, tanggal, bulan dan tahun sebagaimana tersebut di atas.');
		 		$baris = $baris + 3;
		 		$pdf->SetXY(1, $baris);
		 		// $pdf->Cell(0, 0.5,'                               PIHAK KEDUA                                                                                                    PIHAK KESATU', 0, 1, 'L');
		 		$pdf->Cell(9, 0.5, 'PIHAK KEDUA', 0, 0, 'C');
		 		$pdf->Cell(10, 0.5, 'PIHAK KESATU', 0, 1, 'C');
		 		// $pdf->Cell(0, 0.5,'                             Yang Menerima                                                                                             Yang Menyerahkan', 0, 1, 'L');
		 		$pdf->Cell(9, 0.5, 'Yang Menerima', 0, 0, 'C');
		 		$pdf->Cell(10, 0.5, 'Yang Menyerahkan', 0, 1, 'C');
		 		// $pdf->Cell(0, 0.5,'           a.n '.($titik_serah == 'PROVINSI' ? 'Gubernur Provinsi' : ($titik_serah == 'KABUPATEN' ? 'Bupati Kabupaten' : 'Walikota')).' '.$nama_wilayah.'                                                                              a.n Menteri Pertanian', 0, 1, 'L');
		 		$pdf->Cell(9, 0.5, 'a.n '.($titik_serah == 'PROVINSI' ? 'Gubernur Provinsi' : ($titik_serah == 'KABUPATEN' ? 'Bupati Kabupaten' : 'Walikota')).' '.$nama_wilayah, 0, 0, 'C');
		 		$pdf->Cell(10, 0.5, 'a.n Gubernur Provinsi '.$data_alokasi[0]->nama_provinsi, 0 , 1, 'C');

		 		// $pdf->Cell(0, 0.5,'                          '.$jabatan_penerima.'                                                                              '.$jabatan_penyerah, 0, 1, 'L');
		 		$pdf->Cell(9, 0.5, $jabatan_penerima, 0, 0, 'C');
		 		$pdf->Cell(10, 0.5, $jabatan_penyerah, 0, 1, 'C');
		 		
		 		$pdf->Ln();
		 		$pdf->Ln();
		 		$pdf->Ln();
		 		$pdf->Ln();
		 		$pdf->Ln();
		 		$pdf->Ln();

		 		// $pdf->Cell(0, 0.5,'                           '.$nama_penerima.'                                                                          '.$nama_penyerah, 0, 1, 'L');
		 		$pdf->Cell(9, 0.5, $nama_penerima, 0, 0, 'C');
		 		$pdf->Cell(10, 0.5, $nama_penyerah, 0, 1, 'C');
		 		// $pdf->Cell(0, 0.5,'                     NIP. '.$nip_penerima.'                                                                    NIP. '.$nip_penyerah, 0, 1, 'L');
		 		$pdf->Cell(9, 0.5, 'NIP. '.$nip_penerima, 0 , 0, 'C');
		 		$pdf->Cell(10, 0.5, 'NIP. '.$nip_penyerah, 0 , 1, 'C');
		        $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/naskah_hibah/'.$this->clean($no_naskah_hibah).'_'.$id.'.pdf', 'F');
		        // $pdf->Output();
		        // exit(1);

		        // ************************************************************************************************************************
		        // generate pdf lampiran naskah hibah
		        // ************************************************************************************************************************
		        $pdf = new PDF_MC_Table('L','cm','A4');
				$pdf->AddFont('calibri','','calibri.php');
		        $pdf->AddFont('calibri','B','calibrib.php');
		        // membuat halaman baru
		        $pdf->AddPage();
		        // $pdf->Image('assets/img/Kop.jpg', -1, 1, -195);
		        
		        $pdf->SetFont('calibri', 'B', 11);
		        $pdf->SetXY(1, 1);
		        $pdf->Cell(0, 1,'LAMPIRAN NASKAH HIBAH BMN', 0, 1, 'L');

		       	$pdf->Cell(0, 1, 'NOMOR : '.$no_naskah_hibah, 0, 1, 'L');
		       	if($tanggal_naskah_hibah != '')
		       		$pdf->Cell(0, 1, 'TANGGAL : '.date('j', strtotime($tanggal_naskah_hibah)).' '.$bulan.' '.date('Y', strtotime($tanggal_naskah_hibah)), 0, 1, 'L');
		       	else
		       		$pdf->Cell(0, 1, 'TANGGAL : ...............................', 0, 1, 'L');
		       	
		       	$pdf->SetFont('calibri', '', 10);
		       	
		       	$pdf->SetXY(1, 5);

				//header
		    	$pdf->Cell(1, 1, 'No.', 1, 0, 'C');
		    	$pdf->Cell(3, 1, 'Nama Barang', 1, 0, 'C');
		    	$pdf->Cell(2.5, 1, 'Kode Barang', 1, 0, 'C');
		    	$pdf->Cell(2.75, 1, 'Merk', 1, 0, 'C');
		    	$pdf->Cell(2, 1, 'Type', 1, 0, 'C');
		    	$pdf->Cell(2, 1, 'Jumlah Unit', 1, 0, 'C');
		    	$pdf->Cell(3, 1, 'Harga Perolehan', 1, 0, 'C');
		    	$pdf->Cell(2.75, 1, 'Tahun Perolehan', 1, 0, 'C');;
		    	$pdf->Cell(1.5, 1, 'Kondisi', 1, 0, 'C');
		    	$pdf->Cell(3, 1, 'Provinsi', 1, 0, 'C');
		    	$pdf->Cell(2.25, 1, 'Kabupaten', 1, 0, 'C');
		    	$pdf->Cell(2.5, 1, 'Nama Dinas', 1, 1, 'C');
		    	
		    	//data
		    	
		    	$pdf->SetFont('calibri', '', 8.5);

		    	$totalunit = 0;
		    	$totalnilai = 0;
		    	$unit = 0;
		    	$nilai = 0;
		    		       	

		    	$pdf->SetWidths(array(1,3,2.5,2.75,2,2,3,2.75,1.5,3,2.25,2.5));
				    
		    	$no = 1;
		    	foreach($data_alokasi as $alokasi){

		    		$adjust = ($no - 1) * 2;

		    		// $pdf->Cell(1, 1, $no, 1, 0, 'C');
			    	// $pdf->Cell(3, 1, $alokasi->nama_barang, 1, 0, 'C');
			    	// $pdf->Cell(2, 1, $alokasi->kode_barang, 1, 0, 'C');
			    	// $pdf->Cell(3.5, 1, $alokasi->merk, 1, 0, 'C');
			    	// $pdf->Cell(1, 1, $alokasi->jenis_barang, 1, 0, 'C');

			    	if($alokasi->status_alokasi == 'DATA KONTRAK AWAL'){
			    		$unit = $alokasi->jumlah_barang_detail;
			    		$nilai = $alokasi->nilai_barang_detail;
			    	}
			    	else if($alokasi->status_alokasi == 'DATA ADENDUM 1'){
			    		$unit = $alokasi->jumlah_barang_rev_1;
			    		$nilai = $alokasi->nilai_barang_rev_1;
			    	}
			    	else if($alokasi->status_alokasi == 'DATA ADENDUM 2'){
			    		$unit = $alokasi->jumlah_barang_rev_2;
			    		$nilai = $alokasi->nilai_barang_rev_2;
			    	}
			    	// $pdf->Cell(2, 1, number_format($unit, 0), 1, 0, 'C');
			    	// $pdf->Cell(3, 1, number_format($nilai, 0), 1, 0, 'C');
			    	// $pdf->Cell(2.75, 1, $alokasi->tahun_anggaran, 1, 0, 'C');
			    	// $pdf->Cell(1.5, 1, 'BAIK', 1, 0, 'C');
			    	// $pdf->Cell(3.25, 1, $alokasi->nama_provinsi, 1, 0, 'C');
			    	// $pdf->Cell(2.25, 1, $alokasi->nama_kabupaten, 1, 0, 'C');
			    	// $pdf->Cell(3, 1, $instansi_penerima, 1, 1, 'C');
			    	$pdf->Row(
			    		array(
				    		$no,
				    		$alokasi->nama_barang,
				    		$alokasi->kode_barang,
				    		$alokasi->merk,
				    		// $alokasi->jenis_barang,
				    		'',
				    		number_format($unit, 0),
				    		number_format($nilai, 0),
				    		$alokasi->tahun_anggaran,
				    		'BAIK',
				    		$alokasi->nama_provinsi,
				    		$alokasi->nama_kabupaten,
				    		$instansi_penerima
			    		)
			    	);

			    	$totalunit += $unit;
			    	$totalnilai += $nilai;
			    	$no ++;
		    	}

		    	$pdf->SetFont('calibri', '', 10);

		    	//footer
		    	$pdf->Cell(11.25, 1, 'JUMLAH', 1, 0, 'R');
		    	$pdf->Cell(2, 1, number_format($totalunit, 0), 1, 0, 'C');
		    	$pdf->Cell(3, 1, number_format($totalnilai, 0), 1, 0, 'C');
		    	$pdf->Cell(12, 1, '', 1, 1, 'C');
		    	
		    	$currY = $pdf->GetY();

		    	//12
		    	if($currY > 12)
		    		$pdf->AddPage();
		    	else
		    		$pdf->Ln();

		 		$pdf->Cell(12, 0.5, 'PIHAK KEDUA', 0, 0, 'C');
				$pdf->Cell(13, 0.5, 'PIHAK KESATU', 0, 1, 'C');

				$pdf->Cell(12, 0.5, 'Yang Menerima', 0, 0, 'C');
				$pdf->Cell(13, 0.5, 'Yang Menyerahkan', 0, 1, 'C');

				$pdf->Cell(12, 0.5, 'a.n '.($titik_serah == 'PROVINSI' ? 'Gubernur Provinsi' : ($titik_serah == 'KABUPATEN' ? 'Bupati Kabupaten' : 'Walikota')).' '.$nama_wilayah, 0, 0, 'C');
				$pdf->Cell(13, 0.5, 'a.n Gubernur Provinsi '.$data_alokasi[0]->nama_provinsi, 0 , 1, 'C');

				$pdf->Cell(12, 0.5, $jabatan_penerima, 0, 0, 'C');
				$pdf->Cell(13, 0.5, $jabatan_penyerah, 0, 1, 'C');

		 		$pdf->Ln();
		 		$pdf->Ln();
		 		$pdf->Ln();
		 		$pdf->Ln();
		 		$pdf->Ln();

		 		$pdf->Cell(12, 0.5, $nama_penerima, 0, 0, 'C');
				$pdf->Cell(13, 0.5, $nama_penyerah, 0, 1, 'C');

				$pdf->Cell(12, 0.5, 'NIP. '.$nip_penerima, 0 , 0, 'C');
				$pdf->Cell(13, 0.5, 'NIP. '.$nip_penyerah, 0 , 1, 'C');

		        $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/lamp_naskah_hibah/lamp_'.$this->clean($no_naskah_hibah).'_'.$id.'.pdf', 'F');
		        // $pdf->Output();
		        // exit(1);
			// }

	        // ************************************************************************************************************************
	        // generate pdf bast bmn
	        // ************************************************************************************************************************

			// if($no_bast_bmn != ''){
				
				if($tanggal_bast_bmn != ''){
					$hari = 'Senin';
					$bulan = 'Januari';
					$mo = date('n', strtotime($tanggal_bast_bmn));

					$day = date('D', strtotime($tanggal_bast_bmn));
					if($day == 'Mon')
						$hari = 'Senin';
					if($day == 'Tue')
						$hari = 'Selasa';
					if($day =='Wed')
						$hari = 'Rabu';
					if($day == 'Thu')
						$hari = 'Kamis';
					if($day == 'Fri')
						$hari = 'Jumat';
					if($day == 'Sat')
						$hari = 'Sabtu';
					if($day == 'Sun')
						$hari = 'Minggu';

					
					
					if($mo == 1)
						$bulan = 'Januari';
					if($mo == 2)
						$bulan = 'Februari';
					if($mo == 3)
						$bulan = 'Maret';
					if($mo == 4)
						$bulan = 'April';
					if($mo == 5)
						$bulan = 'Mei';
					if($mo == 6)
						$bulan = 'Juni';
					if($mo == 7)
						$bulan = 'Juli';
					if($mo == 8)
						$bulan = 'Agustus';
					if($mo == 9)
						$bulan = 'September';
					if($mo == 10)
						$bulan = 'Oktober';
					if($mo == 11)
						$bulan = 'Nopember';
					if($mo == 12)
						$bulan = 'Desember';

					$tanggal = ucwords($this->terbilang(date('j', strtotime($tanggal_bast_bmn))));
					$tahun = ucwords($this->terbilang(date('Y', strtotime($tanggal_bast_bmn))));
				}
				else{
					$hari = '...............';
					$tanggal = '......';
					$bulan = '......';
					$tahun = '.........';
				}

				$pdf = new FPDF('P','cm','A4');
				$pdf->AddFont('calibri','','calibri.php');
		        $pdf->AddFont('calibri','B','calibrib.php');
		        // membuat halaman baru
		        $pdf->AddPage();
		        // $pdf->Image('assets/img/Kop.jpg', -1, 1, -195);
		        
		        $pdf->SetFont('calibri', 'B', 11);
		        $pdf->SetXY(1, 5);
		        $pdf->Cell(0, 0.5,'BERITA ACARA SERAH TERIMA HIBAH BARANG MILIK NEGARA', 0, 1, 'C');
		        $pdf->Cell(0, 0.5, 'DARI DIREKTORAT JENDERAL PRASARANA DAN SARANA PERTANIAN', 0, 1, 'C');
		        $pdf->Cell(0, 0.5, 'KEPADA '.$instansi_penerima, 0, 1, 'C');

		       	$pdf->Cell(0, 1, 'NOMOR : '.$no_bast_bmn, 0, 1, 'C');

		       	$pdf->SetFont('calibri', '', 10);
		       	$pdf->Cell(0, 1, 'Pada hari ini '.$hari.' tanggal '.$tanggal.' bulan '.$bulan.' tahun '.$tahun.', kami yang bertandatangan di bawah ini :', 0, 1, 'L');
				$pdf->Cell(0, 1, 'I. 	Nama  	  :   '.$nama_penyerah,0, 1, 'L');
				$pdf->Cell(0, 1, '		 NIP   	     :   '.$nip_penyerah,0, 1, 'L');
				$pdf->Cell(0, 1, '		 Jabatan 	:   '.$jabatan_penyerah,0, 1, 'L');

				$pdf->SetXY(1.5, 11.5);
				$pdf->MultiCell(0, 0.5, 'Yang bertindak untuk dan atas nama Menteri Pertanian berkedudukan di '.$alamat_dinas_penyerah.' selanjutnya disebut PIHAK KESATU.',0, 'L', false);

				$pdf->SetXY(1, 13);
				$pdf->Cell(0, 1, 'II. 	Nama  	 :   '.$nama_penerima,0, 1, 'L');
				$pdf->Cell(0, 1, '		  NIP   	     :   '.$nip_penerima,0, 1, 'L');
				$pdf->Cell(0, 1, '		  Jabatan 	:   '.$jabatan_penerima,0, 1, 'L');

				$pdf->SetXY(1.5, 16);
				$pdf->MultiCell(0, 0.5, 'Selaku Unit Akuntansi Kuasa Pengguna Barang (UAKPB) berkedudukan di '.$alamat_dinas_penerima.' selanjutnya disebut PIHAK KEDUA.',0, 'L', false);

				$pdf->SetXY(1, 17.5);
				$pdf->MultiCell(0, 0.5, 'Dalam rangka tertib administrasi pengelolaan, pencatatan dan pelaporan Barang Milik Negara, dengan ini kedua belah pihak sepakat untuk melaksanakan serah terima Barang Milik Negara kegiatan Direktorat Jenderal Prasarana dan Sarana Pertanian Kementerian Pertanian dengan ketentuan sebagai berikut :',0, 'L', false);

				$pdf->SetXY(1, 20);
		        $pdf->Cell(0, 0.5,'Pasal 1', 0, 1, 'C');
		 		$pdf->MultiCell(0, 0.5, 'PIHAK KESATU menyerahkan Barang Milik Negara kepada PIHAK KEDUA dan PIHAK KEDUA menerima Barang Milik Negara kegiatan Direktorat Jenderal Prasarana dan Sarana Pertanian pada Satker Direktorat Jenderal Prasarana dan Sarana Pertanian kantor Provinsi kode satker 18.08.199.633656 beserta Dokumen pendukungnya sebagaimana tertera dalam daftar lampiran berita acara',0, 'L', false);

				$pdf->SetXY(1, 23.5);
		        $pdf->Cell(0, 0.5,'Pasal 2', 0, 1, 'C');
		 		$pdf->MultiCell(0, 0.5, 'PIHAK KESATU selanjutnya akan menghapuskan dari buku inventaris Unit Akuntansi Kuasa Pengguna Barang Direktorat Jenderal Prasarana dan Sarana Pertanian dan PIHAK KEDUA akan membukukan dalam buku inventaris Pemerintah Daerah '.(substr($nama_wilayah, 0, 4) == 'KOTA' ? '' : $titik_serah).' '.$nama_wilayah.' pada '.$instansi_penerima.'.',0, 'L', false);

		 		//page 2
				$pdf->AddPage();
		        // $pdf->Image('assets/img/Kop.jpg', -1, 1, -195);
		        
		        $baris = 5.5;
		        $pdf->SetFont('calibri', '', 10);
		        $pdf->SetXY(1, $baris);
		        $pdf->Cell(0, 0.5,'Pasal 3', 0, 1, 'C');
		        $baris = $baris + 0.5;
		        $pdf->SetXY(1, $baris);
		        $pdf->MultiCell(0, 0.5, 'Dengan ditandatangani Berita Acara Serah Terima ini oleh kedua belah pihak, maka segala sesuatu yang berkaitan dengan barang tersebut, sepenuhnya menjadi tanggung jawab pihak KEDUA.');
		        $baris = $baris + 1.5;
		 		$pdf->SetXY(1, $baris);
		 		$pdf->MultiCell(0, 0.5, 'Demikian berita acara serah terima ini dibuat dan ditandatangani kedua belah pihak pada tanggal tersebut di atas.');
		 		$baris = $baris + 3;
		 		$pdf->SetXY(1, $baris);

		 		$pdf->Cell(9, 0.5, 'PIHAK KEDUA', 0, 0, 'C');
				$pdf->Cell(10, 0.5, 'PIHAK KESATU', 0, 1, 'C');

				$pdf->Cell(9, 0.5, 'Yang Menerima', 0, 0, 'C');
				$pdf->Cell(10, 0.5, 'Yang Menyerahkan', 0, 1, 'C');

				$pdf->Cell(9, 0.5, 'a.n '.($titik_serah == 'PROVINSI' ? 'Gubernur Provinsi' : ($titik_serah == 'KABUPATEN' ? 'Bupati Kabupaten' : 'Walikota')).' '.$nama_wilayah, 0, 0, 'C');
				$pdf->Cell(10, 0.5, 'a.n Gubernur Provinsi '.$data_alokasi[0]->nama_provinsi, 0 , 1, 'C');

				$pdf->Cell(9, 0.5, $jabatan_penerima, 0, 0, 'C');
				$pdf->Cell(10, 0.5, $jabatan_penyerah, 0, 1, 'C');

		 		$pdf->Ln();
		 		$pdf->Ln();
		 		$pdf->Ln();
		 		$pdf->Ln();
		 		$pdf->Ln();
		 		$pdf->Ln();

		 		$pdf->Cell(9, 0.5, $nama_penerima, 0, 0, 'C');
				$pdf->Cell(10, 0.5, $nama_penyerah, 0, 1, 'C');

				$pdf->Cell(9, 0.5, 'NIP. '.$nip_penerima, 0 , 0, 'C');
				$pdf->Cell(10, 0.5, 'NIP. '.$nip_penyerah, 0 , 1, 'C');

		        $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/bast_bmn/'.$this->clean($no_bast_bmn).'_'.$id.'.pdf', 'F');
		        // $pdf->Output();
		        // exit(1);

		        // ************************************************************************************************************************
		        // generate pdf lampiran bast bmn
		        // ************************************************************************************************************************
		        $pdf = new PDF_MC_Table('L','cm','A4');
				$pdf->AddFont('calibri','','calibri.php');
		        $pdf->AddFont('calibri','B','calibrib.php');
		        // membuat halaman baru
		        $pdf->AddPage();
		        // $pdf->Image('assets/img/Kop.jpg', -1, 1, -195);
		        
		        $pdf->SetFont('calibri', 'B', 11);
		        $pdf->SetXY(1, 1);
		        $pdf->Cell(0, 1,'LAMPIRAN BERITA ACARA SERAH TERIMA BMN', 0, 1, 'L');

		       	$pdf->Cell(0, 1, 'NOMOR : '.$no_bast_bmn, 0, 1, 'L');
		       	if($tanggal_bast_bmn != '')
		       		$pdf->Cell(0, 1, 'TANGGAL : '.date('j', strtotime($tanggal_bast_bmn)).' '.$bulan.' '.date('Y', strtotime($tanggal_bast_bmn)), 0, 1, 'L');
		       	else
		       		$pdf->Cell(0, 1, 'TANGGAL : ...............................', 0, 1, 'L');
		       	
		       	$pdf->SetFont('calibri', '', 10);
		       	
		       	$pdf->SetXY(1, 5);

				//header
		    	$pdf->Cell(1, 1, 'No.', 1, 0, 'C');
		    	$pdf->Cell(3, 1, 'Nama Barang', 1, 0, 'C');
		    	$pdf->Cell(2.5, 1, 'Kode Barang', 1, 0, 'C');
		    	$pdf->Cell(2.75, 1, 'Merk', 1, 0, 'C');
		    	$pdf->Cell(2, 1, 'Type', 1, 0, 'C');
		    	$pdf->Cell(2, 1, 'Jumlah Unit', 1, 0, 'C');
		    	$pdf->Cell(3, 1, 'Harga Perolehan', 1, 0, 'C');
		    	$pdf->Cell(2.75, 1, 'Tahun Perolehan', 1, 0, 'C');;
		    	$pdf->Cell(1.5, 1, 'Kondisi', 1, 0, 'C');
		    	$pdf->Cell(3, 1, 'Provinsi', 1, 0, 'C');
		    	$pdf->Cell(2.25, 1, 'Kabupaten', 1, 0, 'C');
		    	$pdf->Cell(2.5, 1, 'Nama Dinas', 1, 1, 'C');
		    	
		    	//data
		    	
		    	$pdf->SetFont('calibri', '', 8.5);

		    	$totalunit = 0;
		    	$totalnilai = 0;
		    	$unit = 0;
		    	$nilai = 0;
		    		       	

		    	$pdf->SetWidths(array(1,3,2.5,2.75,2,2,3,2.75,1.5,3,2.25,2.5));
				    
		    	$no = 1;
		    	foreach($data_alokasi as $alokasi){

		    		$adjust = ($no - 1) * 2;

		    		// $pdf->Cell(1, 1, $no, 1, 0, 'C');
			    	// $pdf->Cell(3, 1, $alokasi->nama_barang, 1, 0, 'C');
			    	// $pdf->Cell(2, 1, $alokasi->kode_barang, 1, 0, 'C');
			    	// $pdf->Cell(3.5, 1, $alokasi->merk, 1, 0, 'C');
			    	// $pdf->Cell(1, 1, $alokasi->jenis_barang, 1, 0, 'C');

			    	if($alokasi->status_alokasi == 'DATA KONTRAK AWAL'){
			    		$unit = $alokasi->jumlah_barang_detail;
			    		$nilai = $alokasi->nilai_barang_detail;
			    	}
			    	else if($alokasi->status_alokasi == 'DATA ADENDUM 1'){
			    		$unit = $alokasi->jumlah_barang_rev_1;
			    		$nilai = $alokasi->nilai_barang_rev_1;
			    	}
			    	else if($alokasi->status_alokasi == 'DATA ADENDUM 2'){
			    		$unit = $alokasi->jumlah_barang_rev_2;
			    		$nilai = $alokasi->nilai_barang_rev_2;
			    	}
			    	// $pdf->Cell(2, 1, number_format($unit, 0), 1, 0, 'C');
			    	// $pdf->Cell(3, 1, number_format($nilai, 0), 1, 0, 'C');
			    	// $pdf->Cell(2.75, 1, $alokasi->tahun_anggaran, 1, 0, 'C');
			    	// $pdf->Cell(1.5, 1, 'BAIK', 1, 0, 'C');
			    	// $pdf->Cell(3.25, 1, $alokasi->nama_provinsi, 1, 0, 'C');
			    	// $pdf->Cell(2.25, 1, $alokasi->nama_kabupaten, 1, 0, 'C');
			    	// $pdf->Cell(3, 1, $instansi_penerima, 1, 1, 'C');
			    	$pdf->Row(
			    		array(
				    		$no,
				    		$alokasi->nama_barang,
				    		$alokasi->kode_barang,
				    		$alokasi->merk,
				    		// $alokasi->jenis_barang,
				    		'',
				    		number_format($unit, 0),
				    		number_format($nilai, 0),
				    		$alokasi->tahun_anggaran,
				    		'BAIK',
				    		$alokasi->nama_provinsi,
				    		$alokasi->nama_kabupaten,
				    		$instansi_penerima
			    		)
			    	);

			    	$totalunit += $unit;
			    	$totalnilai += $nilai;
			    	$no ++;
		    	}

		    	$pdf->SetFont('calibri', '', 10);
		    	//footer
		    	$pdf->Cell(11.25, 1, 'JUMLAH', 1, 0, 'R');
		    	$pdf->Cell(2, 1, number_format($totalunit, 0), 1, 0, 'C');
		    	$pdf->Cell(3, 1, number_format($totalnilai, 0), 1, 0, 'C');
		    	$pdf->Cell(12, 1, '', 1, 1, 'C');

		 		if($currY > 12)
		    		$pdf->AddPage();
		    	else
		    		$pdf->Ln();

		 		$pdf->Cell(12, 0.5, 'PIHAK KEDUA', 0, 0, 'C');
				$pdf->Cell(13, 0.5, 'PIHAK KESATU', 0, 1, 'C');

				$pdf->Cell(12, 0.5, 'Yang Menerima', 0, 0, 'C');
				$pdf->Cell(13, 0.5, 'Yang Menyerahkan', 0, 1, 'C');

				$pdf->Cell(12, 0.5, 'a.n '.($titik_serah == 'PROVINSI' ? 'Gubernur Provinsi' : ($titik_serah == 'KABUPATEN' ? 'Bupati Kabupaten' : 'Walikota')).' '.$nama_wilayah, 0, 0, 'C');
				$pdf->Cell(13, 0.5, 'a.n Gubernur Provinsi '.$data_alokasi[0]->nama_provinsi, 0 , 1, 'C');

				$pdf->Cell(12, 0.5, $jabatan_penerima, 0, 0, 'C');
				$pdf->Cell(13, 0.5, $jabatan_penyerah, 0, 1, 'C');

		 		$pdf->Ln();
		 		$pdf->Ln();
		 		$pdf->Ln();
		 		$pdf->Ln();
		 		$pdf->Ln();

		 		$pdf->Cell(12, 0.5, $nama_penerima, 0, 0, 'C');
				$pdf->Cell(13, 0.5, $nama_penyerah, 0, 1, 'C');

				$pdf->Cell(12, 0.5, 'NIP. '.$nip_penerima, 0 , 0, 'C');
				$pdf->Cell(13, 0.5, 'NIP. '.$nip_penyerah, 0 , 1, 'C');

		        $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/lamp_bast_bmn/lamp_'.$this->clean($no_bast_bmn).'_'.$id.'.pdf', 'F');
				// $pdf->Output();
				// exit(1);
		    // }
	        // ************************************************************************************************************************
	        // generate pdf surat pernyataan
	        // ************************************************************************************************************************
		    // if($no_surat_pernyataan != '' and $tanggal_surat_pernyataan != ''){
		    	

				$pdf = new FPDF('P','cm','A4');
				$pdf->AddFont('calibri','','calibri.php');
		        $pdf->AddFont('calibri','B','calibrib.php');
		        // membuat halaman baru
		        $pdf->AddPage();
		        // $pdf->Image('assets/img/Kop.jpg', -1, 1, -195);
		        
		        $pdf->SetFont('calibri', 'B', 11);
		        $pdf->SetXY(1, 5);
		        $pdf->Cell(0, 0.5,'SURAT PERNYATAAN', 0, 1, 'C');

		       	$pdf->Cell(0, 1, 'NOMOR : '.$no_surat_pernyataan, 0, 1, 'C');

		       	$pdf->SetFont('calibri', '', 10);

				$pdf->SetXY(1, 6.5);
				$pdf->MultiCell(0, 0.5, 'Yang bertandatangan di bawah ini :',0, 'L', false);

				$pdf->SetXY(1, 7.5);
				$pdf->Cell(0, 1, 'Nama  	                              :   '.$nama_penerima, 0, 1, 'L');
				$pdf->Cell(0, 1, 'NIP   	                                 :   '.$nip_penerima, 0, 1, 'L');
				$pdf->Cell(0, 1, 'Pangkat/Golongan           :   '.$pangkat_penerima, 0, 1, 'L');
				$pdf->Cell(0, 1, 'Jabatan 	                            :   '.$jabatan_penerima, 0, 1, 'L');

				$pdf->SetXY(1, 12);
				$pdf->MultiCell(0, 0.5, 'Berdasarkan Peraturan Menteri Keuangan (PMK) Nomor : 111/PMK.06 /2016 tentang Tata Cara Pelaksanaan Pemindahtanganan Barang Milik Negara dengan ini kami menyatakan bersedia menerima Hibah Barang Milik Negara (BMN) yang akan digunakan untuk penyelenggaraan tugas dan fungsi Pemerintah Daerah pada Kantor '.$instansi_penerima.' dengan jenis barang sebagai berikut :',0, 'L', false);

				$pdf->SetXY(1, 14.5);
		       	
				//header
		    	$pdf->Cell(2.5, 1, 'No.', 1, 0, 'C');
		    	$pdf->Cell(5, 1, 'Jenis Kegiatan', 1, 0, 'C');
		    	$pdf->Cell(3.5, 1, 'AKUN', 1, 0, 'C');
		    	$pdf->Cell(3.5, 1, 'Jumlah', 1, 0, 'C');
		    	$pdf->Cell(4, 1, 'Nilai(Rp)', 1, 1, 'C');
		    	
		    	//data
		    	$no = 1;
		    	$totalunit = 0;
		    	$totalnilai = 0;

		    	$data_alokasi_sp = $this->AlokasiProvinsiModel->GetByHibahGrouping($id);

		    	foreach($data_alokasi_sp as $alokasi){
		    		$pdf->Cell(2.5, 1, $no, 1, 0, 'C');
			    	$pdf->Cell(5, 1, $alokasi->nama_barang, 1, 0, 'C');
			    	$pdf->Cell(3.5, 1, $alokasi->akun, 1, 0, 'C');

			    	$unit = $alokasi->total_unit;
			    	$nilai = $alokasi->total_nilai;

			    	$pdf->Cell(3.5, 1, number_format($unit, 0), 1, 0, 'C');
			    	$pdf->Cell(4, 1, number_format($nilai, 0), 1, 1, 'C');
			    	
			    	$totalunit = $totalunit + $unit;
			    	$totalnilai = $totalnilai + $nilai;
			    	$no ++;
		    	}

		    	//footer
		    	$pdf->Cell(11, 1, 'TOTAL', 1, 0, 'R');
		    	$pdf->Cell(3.5, 1, number_format($totalunit, 0), 1, 0, 'C');
		    	$pdf->Cell(4, 1, number_format($totalnilai, 0), 1, 1, 'C');

		 		$pdf->MultiCell(0, 1, 'Demikian pernyataan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.', 0, 'L', false);

		 		$currY = $pdf->GetY();
		 		if($currY > 22)
		    		$pdf->AddPage();
		    	else
		    		$pdf->Ln();

		 		// $pdf->Cell(10, 0.5, $nama_wilayah.', '.date('j', strtotime($tanggal_surat_pernyataan)).' '.$bulan.' '.date('Y', strtotime($tanggal_surat_pernyataan)), 0, 1, 'C');
		 		if($tanggal_surat_pernyataan != '')
		       		$pdf->Cell(10, 0.5, $nama_wilayah.', '.date('j', strtotime($tanggal_surat_pernyataan)).' '.$bulan.' '.date('Y', strtotime($tanggal_surat_pernyataan)), 0, 1, 'C');
		       	else
		       		$pdf->Cell(16, 0.5, $nama_wilayah.', ...............................', 0, 1, 'C');

		 		$pdf->Cell(10, 0.5,'a.n '.($titik_serah == 'PROVINSI' ? 'Gubernur Provinsi' : ($titik_serah == 'KABUPATEN' ? 'Bupati Kabupaten' : 'Walikota')).' '.$nama_wilayah, 0, 1, 'C');
		 		$pdf->Cell(10, 0.5, $jabatan_penerima, 0, 1, 'C');
		 		
		 		$pdf->Ln();
		    	$pdf->Ln();
		    	$pdf->Ln();
				$pdf->Ln();
		    	$pdf->Ln();

		 		$pdf->Cell(10, 0.5, $nama_penerima, 0, 1, 'C');
		 		$pdf->Cell(10, 0.5, 'NIP. '.$nip_penerima, 0, 1, 'C');

		        $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/surat_pernyataan/'.$this->clean($no_surat_pernyataan).'_'.$id.'.pdf', 'F');
		  //       $pdf->Output();
				// exit(1);

		        // ************************************************************************************************************************
		        // generate pdf lampiran surat pernyataan
		        // ************************************************************************************************************************
		        $pdf = new PDF_MC_Table('L','cm','A4');
				$pdf->AddFont('calibri','','calibri.php');
		        $pdf->AddFont('calibri','B','calibrib.php');
		        // membuat halaman baru
		        $pdf->AddPage();
		        // $pdf->Image('assets/img/Kop.jpg', -1, 1, -195);
		        
		        $pdf->SetFont('calibri', 'B', 11);
		        $pdf->SetXY(1, 1);
		        $pdf->Cell(0, 1,'LAMPIRAN SURAT PERNYATAAN', 0, 1, 'L');

		       	$pdf->Cell(3, 1, 'NOMOR', 0, 0, 'L');
		       	$pdf->Cell(0.5, 1, ':', 0, 0, 'L');
		       	$pdf->Cell(4, 1, $no_surat_pernyataan, 0, 1, 'L');

		       	$pdf->Cell(3, 1, 'TANGGAL', 0, 0, 'L');
		       	$pdf->Cell(0.5, 1, ':', 0, 0, 'L');
		       	// $pdf->Cell(4, 1, date('j', strtotime($tanggal_surat_pernyataan)).' '.$bulan.' '.date('Y', strtotime($tanggal_surat_pernyataan)), 0, 1, 'L');
		       	if($tanggal_surat_pernyataan != '')
		       		$pdf->Cell(4, 1, date('j', strtotime($tanggal_surat_pernyataan)).' '.$bulan.' '.date('Y', strtotime($tanggal_surat_pernyataan)), 0, 1, 'L');
		       	else
		       		$pdf->Cell(4, 1, '...............................', 0, 1, 'L');

		       	$pdf->Cell(3, 1, $titik_serah, 0, 0, 'L');
		       	$pdf->Cell(0.5, 1, ':', 0, 0, 'L');
		       	$pdf->Cell(4, 1, $nama_wilayah, 0, 1, 'L');

		       	$pdf->Cell(3, 1, 'ESELON I', 0, 0, 'L');
		       	$pdf->Cell(0.5, 1, ':', 0, 0, 'L');
		       	$pdf->Cell(4, 1, 'DITJEN PRASARANA DAN SARANA PERTANIAN', 0, 1, 'L');

		       	$pdf->Cell(3, 1, 'KEMENTERIAN', 0, 0, 'L');
		       	$pdf->Cell(0.5, 1, ':', 0, 0, 'L');
		       	$pdf->Cell(4, 1, 'KEMENTERIAN PERTANIAN', 0, 1, 'L');

		       	$pdf->SetFont('calibri', '', 10);
		       	
		       	$pdf->SetXY(1, 7);

				//header
		    	$pdf->Cell(1, 2, 'No.', 1, 0, 'C');
		    	$pdf->Cell(3, 2, 'Nama Barang', 1, 0, 'C');
		    	$pdf->Cell(3, 2, 'Kode Barang', 1, 0, 'C');
		    	$pdf->Cell(5, 2, 'Merk', 1, 0, 'C');
		    	$pdf->Cell(1.25, 2, 'Model', 1, 0, 'C');
		    	$pdf->MultiCell(1.25, 1, 'Jumlah Unit', 1, 'C');
		    	$x = 15.5;
		    	$pdf->SetXY($x, 7);
		    	$pdf->MultiCell(2.5, 1, 'Harga Perolehan', 1, 'C');
		    	$x+=2.5;
		    	$pdf->SetXY($x, 7);
		    	$pdf->MultiCell(2, 1, 'Tahun Perolehan', 1, 'C');
		    	$x+=2;
		    	$pdf->SetXY($x, 7);
		    	$pdf->Cell(3, 1, 'Kondisi', 1, 0, 'C');
		    	$pdf->SetXY($x, 8);
		    	$pdf->Cell(1, 1, 'B', 1, 0, 'C');
		    	$pdf->Cell(1, 1, 'RR', 1, 0, 'C');
		    	$pdf->Cell(1, 1, 'RB', 1, 0, 'C');
		    	$x+=3;
		    	$pdf->SetXY($x, 7);
		    	$pdf->Cell(5, 2, 'Lokasi', 1, 1, 'C');
		    	
		    	//data
		    	$no = 1;
		    	$pdf->SetFont('calibri', '', 8.5);

		    	$totalunit = 0;
		    	$totalnilai = 0;
		    	$unit = 0;
		    	$nilai = 0;
		    	$pdf->SetWidths(array(1,3,3,5,1.25,1.25,2.5,2,1,1,1,5));

		    	foreach($data_alokasi as $alokasi){

		    		$adjust = ($no - 1) * 2;

		    		// $pdf->Cell(1, 1, $no, 1, 0, 'C');
			    	// $pdf->Cell(3, 1, $alokasi->nama_barang, 1, 0, 'C');
			    	// $pdf->Cell(3, 1, $alokasi->kode_barang, 1, 0, 'C');
			    	// $pdf->Cell(5, 1, $alokasi->merk, 1, 0, 'C');
			    	// $pdf->Cell(1.25, 1, '', 1, 0, 'C');

			    	if($alokasi->status_alokasi == 'DATA KONTRAK AWAL'){
			    		$unit = $alokasi->jumlah_barang_detail;
			    		$nilai = $alokasi->nilai_barang_detail;
			    	}
			    	else if($alokasi->status_alokasi == 'DATA ADENDUM 1'){
			    		$unit = $alokasi->jumlah_barang_rev_1;
			    		$nilai = $alokasi->nilai_barang_rev_1;
			    	}
			    	else if($alokasi->status_alokasi == 'DATA ADENDUM 2'){
			    		$unit = $alokasi->jumlah_barang_rev_2;
			    		$nilai = $alokasi->nilai_barang_rev_2;
			    	}
			    	// $pdf->Cell(1.25, 1, number_format($unit, 0), 1, 0, 'C');
			    	// $pdf->Cell(2.5, 1, number_format($nilai, 0), 1, 0, 'C');
			    	// $pdf->Cell(2, 1, $alokasi->tahun_anggaran, 1, 0, 'C');
			    	// $pdf->Cell(1, 1, 'B', 1, 0, 'C');
			    	// $pdf->Cell(1, 1, '-', 1, 0, 'C');
			    	// $pdf->Cell(1, 1, '-', 1, 0, 'C');
			    	// $pdf->Cell(5, 1, $titik_serah.' '.$nama_wilayah, 1, 1, 'C');
			    	$pdf->Row(
			    		array(
				    		$no,
				    		$alokasi->nama_barang,
				    		$alokasi->kode_barang,
				    		$alokasi->merk,
				    		// $alokasi->jenis_barang,
				    		'',
				    		number_format($unit, 0),
				    		number_format($nilai, 0),
				    		$alokasi->tahun_anggaran,
				    		'B',
				    		'-',
				    		'-',
				    		$titik_serah.' '.$nama_wilayah,
			    		)
			    	);
			    	
			    	$totalunit += $unit;
			    	$totalnilai += $nilai;
			    	$no ++;
		    	}

		    	$pdf->SetFont('calibri', '', 10);
		    	//footer
		    	$pdf->Cell(13.25, 1, 'JUMLAH', 1, 0, 'R');
		    	$pdf->Cell(1.25, 1, number_format($totalunit, 0), 1, 0, 'C');
		    	$pdf->Cell(2.5, 1, number_format($totalnilai, 0), 1, 0, 'C');
		    	$pdf->Cell(10, 1, '', 1, 1, 'C');

		    	$currY = $pdf->GetY();

		        if($currY > 13){
		        	$pdf->AddPage();
		        	$baris = 2;
		        }
		    	else{
		    		if($currY == 13)
		    			$baris = $currY + 0.5;
		    		else
		    			$baris = $currY + 1;
		    	}
		    		
				
		        $pdf->SetXY(12, $baris);
		 		if($tanggal_surat_pernyataan != '')
		       		$pdf->Cell(0, 0.5, $nama_wilayah.', '.date('j', strtotime($tanggal_surat_pernyataan)).' '.$bulan.' '.date('Y', strtotime($tanggal_surat_pernyataan)), 0, 1, 'C');
		       	else
		       		$pdf->Cell(10, 0.5, $nama_wilayah.', ...............................', 0, 1, 'C');

		 		$baris+=0.5;
		 		$pdf->SetXY(12, $baris);
		 		$pdf->Cell(0, 0.5,'a.n '.($titik_serah == 'PROVINSI' ? 'Gubernur Provinsi' : ($titik_serah == 'KABUPATEN' ? 'Bupati Kabupaten' : 'Walikota')).' '.$nama_wilayah, 0, 1, 'C');
		 		$baris+=0.5;
		 		$pdf->SetXY(12, $baris);
		 		$pdf->Cell(0, 0.5,$jabatan_penerima, 0, 1, 'C');
		 		$baris = $baris + 3;
		 		$pdf->SetXY(12, $baris);
		 		$pdf->Cell(0, 0.5, $nama_penerima, 0, 1, 'C');
		 		$baris+=0.5;
		 		$pdf->SetXY(12, $baris);
		 		$pdf->Cell(0, 0.5,'NIP. '.$nip_penerima, 0, 1, 'C');

		        $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/lamp_surat_pernyataan/lamp_'.$this->clean($no_surat_pernyataan).'_'.$id.'.pdf', 'F');
				// $pdf->Output();
				// exit(1);
		    // }
		    

			$this->session->set_flashdata('info','Data updated successfully.');
			redirect('HibahProvinsi/LihatDokumen?id='.$id);
			// redirect('HibahProvinsi');

				
		}
		
	}

	public function doDelete()
	{	
		$id = $this->input->get('id');

		$hibah_provinsi = $this->HibahProvinsiModel->Get($id);

		if (file_exists($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/naskah_hibah/'.$this->clean($hibah_provinsi->no_naskah_hibah).'_'.$id.'pdf'))
			unlink($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/naskah_hibah/'.$this->clean($hibah_provinsi->no_naskah_hibah).'_'.$id.'pdf');

		if (file_exists($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/bast_bmn/'.$this->clean($hibah_provinsi->no_bast_bmn).'_'.$id.'pdf'))	
			unlink($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/bast_bmn/'.$this->clean($hibah_provinsi->no_bast_bmn).'_'.$id.'pdf');

		if (file_exists($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/surat_pernyataan/'.$this->clean($hibah_provinsi->no_surat_pernyataan).'_'.$id.'pdf'))
			unlink($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/surat_pernyataan/'.$this->clean($hibah_provinsi->no_surat_pernyataan).'_'.$id.'pdf');

		if (file_exists($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/lamp_naskah_hibah/lamp_'.$this->clean($hibah_provinsi->no_naskah_hibah).'_'.$id.'pdf'))
			unlink($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/lamp_naskah_hibah/lamp_'.$this->clean($hibah_provinsi->no_naskah_hibah).'_'.$id.'pdf');

		if (file_exists($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/lamp_bast_bmn/lamp_'.$this->clean($hibah_provinsi->no_bast_bmn).'_'.$id.'pdf'))
			unlink($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/lamp_bast_bmn/lamp_'.$this->clean($hibah_provinsi->no_bast_bmn).'_'.$id.'pdf');

		if (file_exists($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/lamp_surat_pernyataan/lamp_'.$this->clean($hibah_provinsi->no_surat_pernyataan).'_'.$id.'pdf'))
			unlink($_SERVER['DOCUMENT_ROOT'].'/pdf_hibah/hibah_provinsi/lamp_surat_pernyataan/lamp_'.$this->clean($hibah_provinsi->no_surat_pernyataan).'_'.$id.'pdf');	

		$this->HibahProvinsiModel->Delete($id);
		$this->session->set_flashdata('info','Data deleted successfully.');
		redirect('HibahProvinsi');
		
	}

	public function GetHibah()
	{
		$id_hibah_provinsi = $this->input->get('id_hibah_provinsi');

		$data = $this->HibahProvinsiModel->Get($id_hibah_provinsi);
		$hasil = json_encode($data);
		header('HTTP/1.1: 200');
		header('Status: 200');
		header('Content-Length: '.strlen($hasil));
		exit($hasil);
		
	}

	public function penyebut($nilai) {
		$nilai = abs($nilai);
		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		if ($nilai < 12) {
			$temp = " ". $huruf[$nilai];
		} else if ($nilai <20) {
			$temp = $this->penyebut($nilai - 10). " belas";
		} else if ($nilai < 100) {
			$temp = $this->penyebut($nilai/10)." puluh". $this->penyebut($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " seratus" . $this->penyebut($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = $this->penyebut($nilai/100) . " ratus" . $this->penyebut($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " seribu" . $this->penyebut($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp = $this->penyebut($nilai/1000) . " ribu" . $this->penyebut($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = $this->penyebut($nilai/1000000) . " juta" . $this->penyebut($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = $this->penyebut($nilai/1000000000) . " milyar" . $this->penyebut(fmod($nilai,1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = $this->penyebut($nilai/1000000000000) . " trilyun" . $this->penyebut(fmod($nilai,1000000000000));
		}     
		return $temp;
	}
 
	public function terbilang($nilai) {
		if($nilai<0) {
			$hasil = "minus ". trim($this->penyebut($nilai));
		} else {
			$hasil = trim($this->penyebut($nilai));
		}     		
		return $hasil;
	}

	public function clean($string) {
	   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

	   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}

	function combineAndSumUp ($myArray = []) {

	    $finalArray = Array ();

	    foreach ($myArray as $nkey => $nvalue) {

	        $has = false;
	        $fk = false;

	        foreach ($finalArray as $fkey => $fvalue) {
	            if ( ($fvalue->nama_barang == $nvalue->nama_barang) && ($fvalue->akun == $nvalue->akun) ) {
	                $has = true;
	                $fk = $fkey;
	                break;
	            }
	        }

	        if ( $has === false ) {
	            $finalArray[] = $nvalue;
	        } else {
	            $finalArray[$fk]->jumlah_barang_detail += $nvalue->jumlah_barang_detail;
	            $finalArray[$fk]->jumlah_barang_rev_1 += $nvalue->jumlah_barang_rev_1;
	            $finalArray[$fk]->jumlah_barang_rev_2 += $nvalue->jumlah_barang_rev_2;

	            $finalArray[$fk]->nilai_barang_detail += $nvalue->nilai_barang_detail;
	            $finalArray[$fk]->nilai_barang_rev_1 += $nvalue->nilai_barang_rev_1;
	            $finalArray[$fk]->nilai_barang_rev_2 += $nvalue->nilai_barang_rev_2;
	        }

	    }

	    return $finalArray;
	}
}
	