<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanSummaryPusat extends CI_Controller {

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

		$this->load->library('Pdf');
	}

	public function index()
	{
		$this->load->library('parser');
		$param['tahun_pengadaan'] = $this->TahunPengadaanModel->GetAll();
		$param['provinsi'] = $this->ProvinsiModel->GetAll();
		$param['penyedia'] = $this->PenyediaPusatModel->GetAll();

		$data = array(
	        'title' => 'SUMMARY',
	        'content-path' => 'PENGADAAN PUSAT / LAPORAN / SUMMARY',
	        'content' => $this->load->view('laporan-summary-pusat', $param, TRUE),
		);
		$this->parser->parse('default_template', $data);
	}

	public function GetReportData()
	{
		$tahun_anggaran = $this->input->post('tahun_anggaran');
		$list_id_provinsi = $this->input->post('list_id_provinsi');
		$list_id_kabupaten = $this->input->post('list_id_kabupaten');
		$list_id_penyedia = $this->input->post('list_id_penyedia');

		$count_kontrak = $this->LaporanPusatModel->GetTotalKontrak($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$count_barang = $this->LaporanPusatModel->GetTotalBarang($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$count_bapsthp_reg = $this->LaporanPusatModel->GetTotalBAPSTHPREG($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$count_bapsthp_cad = $this->LaporanPusatModel->GetTotalBAPSTHPCAD($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$count_bapsthp = $count_bapsthp_reg + $count_bapsthp_cad;
		$count_bastb = $this->LaporanPusatModel->GetTotalBASTB($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);

		$sum_unit_kontrak = $this->LaporanPusatModel->GetTotalUnitKontrak($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$sum_unit_alokasi = $this->LaporanPusatModel->GetTotalUnitAlokasi($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$sum_unit_bapsthp_reg = $this->LaporanPusatModel->GetTotalUnitBAPSTHPREG($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$sum_unit_bapsthp_cad = $this->LaporanPusatModel->GetTotalUnitBAPSTHPCAD($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$sum_unit_bapsthp = $sum_unit_bapsthp_reg + $sum_unit_bapsthp_cad;
		$sum_unit_bastb = $this->LaporanPusatModel->GetTotalUnitBASTB($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);

		$sum_nilai_kontrak = $this->LaporanPusatModel->GetTotalNilaiKontrak($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$sum_nilai_alokasi = $this->LaporanPusatModel->GetTotalNilaiAlokasi($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$sum_nilai_bapsthp_reg = $this->LaporanPusatModel->GetTotalNilaiBAPSTHPREG($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$sum_nilai_bapsthp_cad = $this->LaporanPusatModel->GetTotalNilaiBAPSTHPCAD($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$sum_nilai_bapsthp = $sum_nilai_bapsthp_reg + $sum_nilai_bapsthp_cad;
		$sum_nilai_bastb = $this->LaporanPusatModel->GetTotalNilaiBASTB($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);

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

	public function ExportPDF()
	{
		$tahun_anggaran = $this->input->get('tahun_anggaran');
		$list_id_provinsi = $this->input->get('list_id_provinsi');
		$list_id_kabupaten = $this->input->get('list_id_kabupaten');
		$list_id_penyedia = $this->input->get('list_id_penyedia');

		$count_kontrak = $this->LaporanPusatModel->GetTotalKontrak($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$count_barang = $this->LaporanPusatModel->GetTotalBarang($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$count_bapsthp_reg = $this->LaporanPusatModel->GetTotalBAPSTHPREG($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$count_bapsthp_cad = $this->LaporanPusatModel->GetTotalBAPSTHPCAD($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$count_bapsthp = $count_bapsthp_reg + $count_bapsthp_cad;
		$count_bastb = $this->LaporanPusatModel->GetTotalBASTB($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);

		$sum_unit_kontrak = $this->LaporanPusatModel->GetTotalUnitKontrak($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$sum_unit_alokasi = $this->LaporanPusatModel->GetTotalUnitAlokasi($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$sum_unit_bapsthp_reg = $this->LaporanPusatModel->GetTotalUnitBAPSTHPREG($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$sum_unit_bapsthp_cad = $this->LaporanPusatModel->GetTotalUnitBAPSTHPCAD($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$sum_unit_bapsthp = $sum_unit_bapsthp_reg + $sum_unit_bapsthp_cad;
		$sum_unit_bastb = $this->LaporanPusatModel->GetTotalUnitBASTB($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);

		$sum_nilai_kontrak = $this->LaporanPusatModel->GetTotalNilaiKontrak($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$sum_nilai_alokasi = $this->LaporanPusatModel->GetTotalNilaiAlokasi($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$sum_nilai_bapsthp_reg = $this->LaporanPusatModel->GetTotalNilaiBAPSTHPREG($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$sum_nilai_bapsthp_cad = $this->LaporanPusatModel->GetTotalNilaiBAPSTHPCAD($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);
		$sum_nilai_bapsthp = $sum_nilai_bapsthp_reg + $sum_nilai_bapsthp_cad;
		$sum_nilai_bastb = $this->LaporanPusatModel->GetTotalNilaiBASTB($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia);

		$prov = '';
		$kab = '';
		$peny = '';

		if($list_id_provinsi == ""){
			$prov = "ALL";
		}
		else{
			for($i=0; $i<count($list_id_provinsi); $i++){
				$temp = $this->ProvinsiModel->Get($list_id_provinsi[$i]);
				if($i==0)
					$prov .= $temp->nama_provinsi;
				else
					$prov .= ', '.$temp->nama_provinsi;
			}
			
		}

		if($list_id_kabupaten == ""){
			$kab = "ALL";
		}
		else{
			for($i=0; $i<count($list_id_kabupaten); $i++){
				$temp = $this->KabupatenModel->Get($list_id_kabupaten[$i]);
				if($i==0)
					$kab .= $temp->nama_kabupaten;
				else
					$kab .= ', '.$temp->nama_kabupaten;
			}
			
		}

		if($list_id_penyedia == ""){
			$peny = "ALL";
		}
		else{
			for($i=0; $i<count($list_id_penyedia); $i++){
				$temp = $this->PenyediaPusatModel->Get($list_id_penyedia[$i]);
				if($i==0)
					$peny .= $temp->nama_penyedia_pusat;
				else
					$peny .= ', '.$temp->nama_penyedia_pusat;
			}
			
		}

		$pdf = new FPDF('L','cm','A4');
		$pdf->AddFont('calibri','','calibri.php');
        $pdf->AddFont('calibri','B','calibrib.php');
        // membuat halaman baru
        $pdf->AddPage();
        
        $pdf->SetFont('calibri', 'B', 18);
        $pdf->SetXY(1, 0);
        $pdf->Cell(0, 2,'LAPORAN SUMMARY PUSAT', 0, 1, 'C');

        $pdf->SetFont('calibri', 'B', 12);
        $pdf->Cell(4, 0.5,'PROVINSI', 0, 0, 'L');
        $pdf->Cell(1, 0.5,':', 0, 0, 'C');
        $pdf->Cell(1, 0.5,$prov, 0, 1, 'L');

        $pdf->Cell(4, 0.5,'KABUPATEN', 0, 0, 'L');
        $pdf->Cell(1, 0.5,':', 0, 0, 'C');
        $pdf->Cell(1, 0.5,$kab, 0, 1, 'L');

        $pdf->Cell(4, 0.5,'PENYEDIA', 0, 0, 'L');
        $pdf->Cell(1, 0.5,':', 0, 0, 'C');
        $pdf->Cell(1, 0.5,$peny, 0, 1, 'L');

        $pdf->Cell(4, 0.5,'TAHUN ANGGARAN', 0, 0, 'L');
        $pdf->Cell(1, 0.5,':', 0, 0, 'C');
        $pdf->Cell(1, 0.5,$tahun_anggaran, 0, 1, 'L');

        $pdf->Ln();

        $pdf->SetFont('calibri', 'B', 12);
        $pdf->Cell(7,1, 'KONTRAK', 1, 0, 'C');
        $pdf->Cell(7,1, 'JENIS BARANG', 1, 0, 'C');
        $pdf->Cell(7,1, 'DOKUMEN BAP-STHP', 1, 0, 'C');
        $pdf->Cell(7,1, 'DOKUMEN BASTB', 1, 1, 'C');

        $pdf->SetFont('calibri', 'B', 16);
        $pdf->Cell(7,1.5, number_format($count_kontrak, 0), 1, 0, 'C');
        $pdf->Cell(7,1.5, number_format($count_barang, 0), 1, 0, 'C');
        $pdf->Cell(7,1.5, number_format($count_bapsthp, 0), 1, 0, 'C');
        $pdf->Cell(7,1.5, number_format($count_bastb, 0), 1, 1, 'C');

        $pdf->SetFont('calibri', 'B', 14);
        $pdf->Cell(0, 1.5,'UNIT', 0, 1, 'L');

        $pdf->SetFont('calibri', 'B', 12);
        $pdf->Cell(7,1, 'KONTRAK', 1, 0, 'C');
        $pdf->Cell(7,1, 'ALOKASI', 1, 0, 'C');
        $pdf->Cell(7,1, 'BAP-STHP', 1, 0, 'C');
        $pdf->Cell(7,1, 'BASTB', 1, 1, 'C');

        $pdf->SetFont('calibri', 'B', 16);
        $pdf->Cell(7,3, number_format($sum_unit_kontrak, 0), 1, 0, 'C');
        $pdf->Cell(7,3, number_format($sum_unit_alokasi, 0), 1, 0, 'C');
        $pdf->Cell(7,3, number_format($sum_unit_bapsthp, 0), 1, 0, 'C');
        $pdf->Cell(7,3, number_format($sum_unit_bastb, 0), 1, 1, 'C');

        $pdf->SetFont('calibri', 'B', 14);
        $pdf->Cell(0, 1.5,'NILAI (RP)', 0, 1, 'L');

        $pdf->SetFont('calibri', 'B', 12);
        $pdf->Cell(7,1, 'KONTRAK', 1, 0, 'C');
        $pdf->Cell(7,1, 'ALOKASI', 1, 0, 'C');
        $pdf->Cell(7,1, 'BAP-STHP', 1, 0, 'C');
        $pdf->Cell(7,1, 'BASTB', 1, 1, 'C');

        $pdf->SetFont('calibri', 'B', 16);
        $pdf->Cell(7,3, number_format($sum_nilai_kontrak, 0), 1, 0, 'C');
        $pdf->Cell(7,3, number_format($sum_nilai_alokasi, 0), 1, 0, 'C');
        $pdf->Cell(7,3, number_format($sum_nilai_bapsthp, 0), 1, 0, 'C');
        $pdf->Cell(7,3, number_format($sum_nilai_bastb, 0), 1, 1, 'C');

        $pdf->SetFont('calibri', 'B', 12);
        $pdf->SetXY(8,11.5);
        $pdf->Cell(7,1, '( '.number_format(($sum_unit_kontrak == 0 ? 0 : ($sum_unit_alokasi / $sum_unit_kontrak * 100)), 0).' % )', 0, 0, 'C');
        $pdf->Cell(7,1, '( '.number_format(($sum_unit_alokasi == 0 ? 0 : ($sum_unit_bapsthp / $sum_unit_alokasi * 100)), 0).' % )', 0, 0, 'C');
        $pdf->Cell(7,1, '( '.number_format(($sum_unit_alokasi == 0 ? 0 : ($sum_unit_bastb / $sum_unit_alokasi * 100)), 0).' % )', 0, 0, 'C');

        $pdf->SetXY(8,17);
        $pdf->Cell(7,1, '( '.number_format(($sum_unit_kontrak == 0 ? 0 : ($sum_unit_alokasi / $sum_unit_kontrak * 100)), 0).' % )', 0, 0, 'C');
        $pdf->Cell(7,1, '( '.number_format(($sum_unit_alokasi == 0 ? 0 : ($sum_unit_bapsthp / $sum_unit_alokasi * 100)), 0).' % )', 0, 0, 'C');
        $pdf->Cell(7,1, '( '.number_format(($sum_unit_alokasi == 0 ? 0 : ($sum_unit_bastb / $sum_unit_alokasi * 100)), 0).' % )', 0, 0, 'C');

		$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/upload/report/LaporanSummaryPusat.pdf', 'F');

		$data = 'success';
		$hasil = json_encode($data);
		header('HTTP/1.1: 200');
		header('Status: 200');
		header('Content-Length: '.strlen($hasil));
		exit($hasil);
	}
	
}
