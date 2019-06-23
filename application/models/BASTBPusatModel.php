<?php
	class BASTBPusatModel extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function GetAllAjaxCount()
		{
			$qry =	"	
				SELECT 
					bap.id as id,
					bap.tahun_anggaran, bap.kelompok_penerima,
					bap.no_bastb, bap.tanggal,
					bap.pihak_penyerah, 
					bap.nama_penyerah,
					bap.jabatan_penyerah,
					bap.notelp_penyerah,
					bap.alamat_penyerah,
					bap.id_provinsi_penyerah, prov_peny.nama_provinsi as provinsi_penyerah,
					bap.id_kabupaten_penyerah, kab_peny.nama_kabupaten as kabupaten_penyerah,
					bap.pihak_penerima, 
					bap.nama_penerima,
					bap.jabatan_penerima,
					bap.notelp_penerima,
					bap.alamat_penerima,
					bap.id_provinsi_penerima, prov_pene.nama_provinsi as provinsi_penerima,
					bap.id_kabupaten_penerima, kab_pene.nama_kabupaten as kabupaten_penerima,
					bap.id_kecamatan_penerima, kec_pene.nama_kecamatan as kecamatan_penerima,
					bap.id_kelurahan_penerima, kel_pene.nama_kelurahan as kelurahan_penerima,
					bap.no_kontrak,
					bap.nama_barang, bap.merk, bap.jumlah_barang, bap.nilai_barang,
					bap.nama_file,
					bap.nama_mengetahui,
					bap.jabatan_mengetahui
				from tb_bastb_pusat bap
				LEFT JOIN tb_provinsi prov_peny ON bap.id_provinsi_penyerah = prov_peny.id
				LEFT JOIN tb_provinsi prov_pene ON bap.id_provinsi_penerima = prov_pene.id
				LEFT JOIN tb_kabupaten kab_peny ON bap.id_kabupaten_penyerah = kab_peny.id
				LEFT JOIN tb_kabupaten kab_pene ON bap.id_kabupaten_penerima = kab_pene.id
				LEFT JOIN tb_kecamatan kec_pene ON bap.id_kecamatan_penerima = kec_pene.id
				LEFT JOIN tb_kelurahan kel_pene ON bap.id_kelurahan_penerima = kel_pene.id
				WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and merk in ( SELECT merk from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat." )" : "")." 
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and merk in ( SELECT merk from tb_jenis_barang_provinsi where id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi. " )" : "")."
					";
				
			// die($qry);
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetSearchAjaxCount($search, $filter = '')
		{
			$qry =	"
				SELECT 
					bap.id as id,
					bap.tahun_anggaran, bap.kelompok_penerima,
					bap.no_bastb, bap.tanggal,
					bap.pihak_penyerah, 
					bap.nama_penyerah,
					bap.jabatan_penyerah,
					bap.notelp_penyerah,
					bap.alamat_penyerah,
					bap.id_provinsi_penyerah, prov_peny.nama_provinsi as provinsi_penyerah,
					bap.id_kabupaten_penyerah, kab_peny.nama_kabupaten as kabupaten_penyerah,
					bap.pihak_penerima, 
					bap.nama_penerima,
					bap.jabatan_penerima,
					bap.notelp_penerima,
					bap.alamat_penerima,
					bap.id_provinsi_penerima, prov_pene.nama_provinsi as provinsi_penerima,
					bap.id_kabupaten_penerima, kab_pene.nama_kabupaten as kabupaten_penerima,
					bap.id_kecamatan_penerima, kec_pene.nama_kecamatan as kecamatan_penerima,
					bap.id_kelurahan_penerima, kel_pene.nama_kelurahan as kelurahan_penerima,
					bap.no_kontrak,
					bap.nama_barang, bap.merk, bap.jumlah_barang, bap.nilai_barang,
					bap.nama_file,
					bap.nama_mengetahui,
					bap.jabatan_mengetahui
				from tb_bastb_pusat bap
				LEFT JOIN tb_provinsi prov_peny ON bap.id_provinsi_penyerah = prov_peny.id
				LEFT JOIN tb_provinsi prov_pene ON bap.id_provinsi_penerima = prov_pene.id
				LEFT JOIN tb_kabupaten kab_peny ON bap.id_kabupaten_penyerah = kab_peny.id
				LEFT JOIN tb_kabupaten kab_pene ON bap.id_kabupaten_penerima = kab_pene.id
				LEFT JOIN tb_kecamatan kec_pene ON bap.id_kecamatan_penerima = kec_pene.id
				LEFT JOIN tb_kelurahan kel_pene ON bap.id_kelurahan_penerima = kel_pene.id
				WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and merk in ( SELECT merk from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat." )" : "")." 
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and merk in ( SELECT merk from tb_jenis_barang_provinsi where id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi. " )" : "")."
				AND (
					bap.kelompok_penerima LIKE '%".$search."%' or
					bap.no_bastb LIKE '%".$search."%' or
					bap.pihak_penyerah LIKE '%".$search."%' or
					bap.nama_penyerah LIKE '%".$search."%' or
					bap.jabatan_penyerah LIKE '%".$search."%' or
					bap.notelp_penyerah LIKE '%".$search."%' or
					bap.alamat_penyerah LIKE '%".$search."%' or
					prov_peny.nama_provinsi LIKE '%".$search."%' or
					kab_peny.nama_kabupaten LIKE '%".$search."%' or
					bap.pihak_penerima LIKE '%".$search."%' or
					bap.nama_penerima LIKE '%".$search."%' or
					bap.notelp_penerima LIKE '%".$search."%' or
					bap.alamat_penerima LIKE '%".$search."%' or
					prov_pene.nama_provinsi LIKE '%".$search."%' or
					kab_pene.nama_kabupaten LIKE '%".$search."%' or
					kec_pene.nama_kecamatan LIKE '%".$search."%' or
					kel_pene.nama_kelurahan LIKE '%".$search."%' or
					bap.no_kontrak LIKE '%".$search."%' or
					bap.nama_barang LIKE '%".$search."%' or
					bap.merk LIKE '%".$search."%' or
					bap.nama_mengetahui LIKE '%".$search."%' or
					bap.jabatan_mengetahui LIKE '%".$search."%'
				)
				".$filter."
			";
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetFilterAjaxCount($filter = '')
		{
			$qry =	"	
				SELECT 
					bap.id as id,
					bap.tahun_anggaran, bap.kelompok_penerima,
					bap.no_bastb, bap.tanggal,
					bap.pihak_penyerah, 
					bap.nama_penyerah,
					bap.jabatan_penyerah,
					bap.notelp_penyerah,
					bap.alamat_penyerah,
					bap.id_provinsi_penyerah, prov_peny.nama_provinsi as provinsi_penyerah,
					bap.id_kabupaten_penyerah, kab_peny.nama_kabupaten as kabupaten_penyerah,
					bap.pihak_penerima, 
					bap.nama_penerima,
					bap.jabatan_penerima,
					bap.notelp_penerima,
					bap.alamat_penerima,
					bap.id_provinsi_penerima, prov_pene.nama_provinsi as provinsi_penerima,
					bap.id_kabupaten_penerima, kab_pene.nama_kabupaten as kabupaten_penerima,
					bap.id_kecamatan_penerima, kec_pene.nama_kecamatan as kecamatan_penerima,
					bap.id_kelurahan_penerima, kel_pene.nama_kelurahan as kelurahan_penerima,
					bap.no_kontrak,
					bap.nama_barang, bap.merk, bap.jumlah_barang, bap.nilai_barang,
					bap.nama_file,
					bap.nama_mengetahui,
					bap.jabatan_mengetahui
				from tb_bastb_pusat bap
				LEFT JOIN tb_provinsi prov_peny ON bap.id_provinsi_penyerah = prov_peny.id
				LEFT JOIN tb_provinsi prov_pene ON bap.id_provinsi_penerima = prov_pene.id
				LEFT JOIN tb_kabupaten kab_peny ON bap.id_kabupaten_penyerah = kab_peny.id
				LEFT JOIN tb_kabupaten kab_pene ON bap.id_kabupaten_penerima = kab_pene.id
				LEFT JOIN tb_kecamatan kec_pene ON bap.id_kecamatan_penerima = kec_pene.id
				LEFT JOIN tb_kelurahan kel_pene ON bap.id_kelurahan_penerima = kel_pene.id
				WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".$filter."
				".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and merk in ( SELECT merk from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat." )" : "")." 
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and merk in ( SELECT merk from tb_jenis_barang_provinsi where id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi. " )" : "")."
					";
				
			// die($qry);
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetAllForAjax($start, $length, $col, $dir, $filter = '')
		{

			$qry = "
				SELECT 
					bap.id as id,
					bap.tahun_anggaran, bap.kelompok_penerima,
					bap.no_bastb, bap.tanggal,
					bap.pihak_penyerah, 
					bap.nama_penyerah,
					bap.jabatan_penyerah,
					bap.notelp_penyerah,
					bap.alamat_penyerah,
					bap.id_provinsi_penyerah, prov_peny.nama_provinsi as provinsi_penyerah,
					bap.id_kabupaten_penyerah, kab_peny.nama_kabupaten as kabupaten_penyerah,
					bap.pihak_penerima, 
					bap.nama_penerima,
					bap.jabatan_penerima,
					bap.notelp_penerima,
					bap.alamat_penerima,
					bap.id_provinsi_penerima, prov_pene.nama_provinsi as provinsi_penerima,
					bap.id_kabupaten_penerima, kab_pene.nama_kabupaten as kabupaten_penerima,
					bap.id_kecamatan_penerima, kec_pene.nama_kecamatan as kecamatan_penerima,
					bap.id_kelurahan_penerima, kel_pene.nama_kelurahan as kelurahan_penerima,
					bap.no_kontrak,
					bap.nama_barang, bap.merk, bap.jumlah_barang, bap.nilai_barang,
					bap.nama_file,
					bap.nama_mengetahui,
					bap.jabatan_mengetahui
				from tb_bastb_pusat bap
				LEFT JOIN tb_provinsi prov_peny ON bap.id_provinsi_penyerah = prov_peny.id
				LEFT JOIN tb_provinsi prov_pene ON bap.id_provinsi_penerima = prov_pene.id
				LEFT JOIN tb_kabupaten kab_peny ON bap.id_kabupaten_penyerah = kab_peny.id
				LEFT JOIN tb_kabupaten kab_pene ON bap.id_kabupaten_penerima = kab_pene.id
				LEFT JOIN tb_kecamatan kec_pene ON bap.id_kecamatan_penerima = kec_pene.id
				LEFT JOIN tb_kelurahan kel_pene ON bap.id_kelurahan_penerima = kel_pene.id
				WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and merk in ( SELECT merk from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat." )" : "")." 
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and merk in ( SELECT merk from tb_jenis_barang_provinsi where id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi. " )" : "")."
				".$filter."
				ORDER BY ".$col." ".$dir;
			if($length > 0)
				$qry .=	' LIMIT ' . $start . ',' . $length;
			
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetSearchAjax($start, $length, $search, $col, $dir, $filter = '')
		{

			$qry = "
				SELECT 
					bap.id as id,
					bap.tahun_anggaran, bap.kelompok_penerima,
					bap.no_bastb, bap.tanggal,
					bap.pihak_penyerah, 
					bap.nama_penyerah,
					bap.jabatan_penyerah,
					bap.notelp_penyerah,
					bap.alamat_penyerah,
					bap.id_provinsi_penyerah, prov_peny.nama_provinsi as provinsi_penyerah,
					bap.id_kabupaten_penyerah, kab_peny.nama_kabupaten as kabupaten_penyerah,
					bap.pihak_penerima, 
					bap.nama_penerima,
					bap.jabatan_penerima,
					bap.notelp_penerima,
					bap.alamat_penerima,
					bap.id_provinsi_penerima, prov_pene.nama_provinsi as provinsi_penerima,
					bap.id_kabupaten_penerima, kab_pene.nama_kabupaten as kabupaten_penerima,
					bap.id_kecamatan_penerima, kec_pene.nama_kecamatan as kecamatan_penerima,
					bap.id_kelurahan_penerima, kel_pene.nama_kelurahan as kelurahan_penerima,
					bap.no_kontrak,
					bap.nama_barang, bap.merk, bap.jumlah_barang, bap.nilai_barang,
					bap.nama_file,
					bap.nama_mengetahui,
					bap.jabatan_mengetahui
				from tb_bastb_pusat bap
				LEFT JOIN tb_provinsi prov_peny ON bap.id_provinsi_penyerah = prov_peny.id
				LEFT JOIN tb_provinsi prov_pene ON bap.id_provinsi_penerima = prov_pene.id
				LEFT JOIN tb_kabupaten kab_peny ON bap.id_kabupaten_penyerah = kab_peny.id
				LEFT JOIN tb_kabupaten kab_pene ON bap.id_kabupaten_penerima = kab_pene.id
				LEFT JOIN tb_kecamatan kec_pene ON bap.id_kecamatan_penerima = kec_pene.id
				LEFT JOIN tb_kelurahan kel_pene ON bap.id_kelurahan_penerima = kel_pene.id
				WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and merk in ( SELECT merk from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat." )" : "")." 
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and merk in ( SELECT merk from tb_jenis_barang_provinsi where id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi. " )" : "")."
				AND (
					bap.kelompok_penerima LIKE '%".$search."%' or
					bap.no_bastb LIKE '%".$search."%' or
					bap.pihak_penyerah LIKE '%".$search."%' or
					bap.nama_penyerah LIKE '%".$search."%' or
					bap.jabatan_penyerah LIKE '%".$search."%' or
					bap.notelp_penyerah LIKE '%".$search."%' or
					bap.alamat_penyerah LIKE '%".$search."%' or
					prov_peny.nama_provinsi LIKE '%".$search."%' or
					kab_peny.nama_kabupaten LIKE '%".$search."%' or
					bap.pihak_penerima LIKE '%".$search."%' or
					bap.nama_penerima LIKE '%".$search."%' or
					bap.notelp_penerima LIKE '%".$search."%' or
					bap.alamat_penerima LIKE '%".$search."%' or
					prov_pene.nama_provinsi LIKE '%".$search."%' or
					kab_pene.nama_kabupaten LIKE '%".$search."%' or
					kec_pene.nama_kecamatan LIKE '%".$search."%' or
					kel_pene.nama_kelurahan LIKE '%".$search."%' or
					bap.no_kontrak LIKE '%".$search."%' or
					bap.nama_barang LIKE '%".$search."%' or
					bap.merk LIKE '%".$search."%' or
					bap.nama_mengetahui LIKE '%".$search."%' or
					bap.jabatan_mengetahui LIKE '%".$search."%'
				)
				".$filter."
				ORDER BY ".$col." ".$dir;
			if($length > 0)
				$qry .=	' LIMIT ' . $start . ',' . $length;
			
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function ExportAllForAjax($col, $dir, $filter = '')
		{

			$qry = "
				SELECT 
					bap.id as id,
					bap.tahun_anggaran, bap.kelompok_penerima,
					bap.no_bastb, bap.tanggal,
					bap.pihak_penyerah, 
					bap.nama_penyerah,
					bap.jabatan_penyerah,
					bap.notelp_penyerah,
					bap.alamat_penyerah,
					bap.id_provinsi_penyerah, prov_peny.nama_provinsi as provinsi_penyerah,
					bap.id_kabupaten_penyerah, kab_peny.nama_kabupaten as kabupaten_penyerah,
					bap.pihak_penerima, 
					bap.nama_penerima,
					bap.jabatan_penerima,
					bap.notelp_penerima,
					bap.alamat_penerima,
					bap.id_provinsi_penerima, prov_pene.nama_provinsi as provinsi_penerima,
					bap.id_kabupaten_penerima, kab_pene.nama_kabupaten as kabupaten_penerima,
					bap.id_kecamatan_penerima, kec_pene.nama_kecamatan as kecamatan_penerima,
					bap.id_kelurahan_penerima, kel_pene.nama_kelurahan as kelurahan_penerima,
					bap.no_kontrak,
					bap.nama_barang, bap.merk, bap.jumlah_barang, bap.nilai_barang,
					bap.nama_file,
					bap.nama_mengetahui,
					bap.jabatan_mengetahui
				from tb_bastb_pusat bap
				LEFT JOIN tb_provinsi prov_peny ON bap.id_provinsi_penyerah = prov_peny.id
				LEFT JOIN tb_provinsi prov_pene ON bap.id_provinsi_penerima = prov_pene.id
				LEFT JOIN tb_kabupaten kab_peny ON bap.id_kabupaten_penyerah = kab_peny.id
				LEFT JOIN tb_kabupaten kab_pene ON bap.id_kabupaten_penerima = kab_pene.id
				LEFT JOIN tb_kecamatan kec_pene ON bap.id_kecamatan_penerima = kec_pene.id
				LEFT JOIN tb_kelurahan kel_pene ON bap.id_kelurahan_penerima = kel_pene.id
				WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and merk in ( SELECT merk from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat." )" : "")." 
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and merk in ( SELECT merk from tb_jenis_barang_provinsi where id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi. " )" : "")."
				".$filter."
				ORDER BY ".$col." ".$dir;

			$data = array();
			$res = $this->db->query($qry);
			if($res->num_rows() > 0) {
				$data = $res->result_array();
			}		
			return $data;
		}

		function ExportSearchAjax($search, $col, $dir, $filter = '')
		{

			$qry = "
				SELECT 
					bap.id as id,
					bap.tahun_anggaran, bap.kelompok_penerima,
					bap.no_bastb, bap.tanggal,
					bap.pihak_penyerah, 
					bap.nama_penyerah,
					bap.jabatan_penyerah,
					bap.notelp_penyerah,
					bap.alamat_penyerah,
					bap.id_provinsi_penyerah, prov_peny.nama_provinsi as provinsi_penyerah,
					bap.id_kabupaten_penyerah, kab_peny.nama_kabupaten as kabupaten_penyerah,
					bap.pihak_penerima, 
					bap.nama_penerima,
					bap.jabatan_penerima,
					bap.notelp_penerima,
					bap.alamat_penerima,
					bap.id_provinsi_penerima, prov_pene.nama_provinsi as provinsi_penerima,
					bap.id_kabupaten_penerima, kab_pene.nama_kabupaten as kabupaten_penerima,
					bap.id_kecamatan_penerima, kec_pene.nama_kecamatan as kecamatan_penerima,
					bap.id_kelurahan_penerima, kel_pene.nama_kelurahan as kelurahan_penerima,
					bap.no_kontrak,
					bap.nama_barang, bap.merk, bap.jumlah_barang, bap.nilai_barang,
					bap.nama_file,
					bap.nama_mengetahui,
					bap.jabatan_mengetahui
				from tb_bastb_pusat bap
				LEFT JOIN tb_provinsi prov_peny ON bap.id_provinsi_penyerah = prov_peny.id
				LEFT JOIN tb_provinsi prov_pene ON bap.id_provinsi_penerima = prov_pene.id
				LEFT JOIN tb_kabupaten kab_peny ON bap.id_kabupaten_penyerah = kab_peny.id
				LEFT JOIN tb_kabupaten kab_pene ON bap.id_kabupaten_penerima = kab_pene.id
				LEFT JOIN tb_kecamatan kec_pene ON bap.id_kecamatan_penerima = kec_pene.id
				LEFT JOIN tb_kelurahan kel_pene ON bap.id_kelurahan_penerima = kel_pene.id
				WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and merk in ( SELECT merk from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat." )" : "")." 
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and merk in ( SELECT merk from tb_jenis_barang_provinsi where id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi. " )" : "")."
				AND (
					bap.kelompok_penerima LIKE '%".$search."%' or
					bap.no_bastb LIKE '%".$search."%' or
					bap.pihak_penyerah LIKE '%".$search."%' or
					bap.nama_penyerah LIKE '%".$search."%' or
					bap.jabatan_penyerah LIKE '%".$search."%' or
					bap.notelp_penyerah LIKE '%".$search."%' or
					bap.alamat_penyerah LIKE '%".$search."%' or
					prov_peny.nama_provinsi LIKE '%".$search."%' or
					kab_peny.nama_kabupaten LIKE '%".$search."%' or
					bap.pihak_penerima LIKE '%".$search."%' or
					bap.nama_penerima LIKE '%".$search."%' or
					bap.notelp_penerima LIKE '%".$search."%' or
					bap.alamat_penerima LIKE '%".$search."%' or
					prov_pene.nama_provinsi LIKE '%".$search."%' or
					kab_pene.nama_kabupaten LIKE '%".$search."%' or
					kec_pene.nama_kecamatan LIKE '%".$search."%' or
					kel_pene.nama_kelurahan LIKE '%".$search."%' or
					bap.no_kontrak LIKE '%".$search."%' or
					bap.nama_barang LIKE '%".$search."%' or
					bap.merk LIKE '%".$search."%' or
					bap.nama_mengetahui LIKE '%".$search."%' or
					bap.jabatan_mengetahui LIKE '%".$search."%'
				)
				".$filter."
				ORDER BY ".$col." ".$dir;

			$data = array();
			$res = $this->db->query($qry);
			if($res->num_rows() > 0) {
				$data = $res->result_array();
			}		
			return $data;
		}

		function Get($id)
		{

			$qry =	"	
				SELECT 
					bap.id as id,
					bap.tahun_anggaran, bap.kelompok_penerima,
					bap.no_bastb, bap.tanggal,
					bap.pihak_penyerah, 
					bap.nama_penyerah,
					bap.jabatan_penyerah,
					bap.notelp_penyerah,
					bap.alamat_penyerah,
					bap.id_provinsi_penyerah, prov_peny.nama_provinsi as provinsi_penyerah,
					bap.id_kabupaten_penyerah, kab_peny.nama_kabupaten as kabupaten_penyerah,
					bap.pihak_penerima, 
					bap.nama_penerima,
					bap.jabatan_penerima,
					bap.notelp_penerima,
					bap.alamat_penerima,
					bap.id_provinsi_penerima, prov_pene.nama_provinsi as provinsi_penerima,
					bap.id_kabupaten_penerima, kab_pene.nama_kabupaten as kabupaten_penerima,
					bap.id_kecamatan_penerima, kec_pene.nama_kecamatan as kecamatan_penerima,
					bap.id_kelurahan_penerima, kel_pene.nama_kelurahan as kelurahan_penerima,
					bap.no_kontrak,
					bap.nama_barang, bap.merk, bap.jumlah_barang, bap.nilai_barang,
					bap.nama_file,
					bap.nama_mengetahui,
					bap.jabatan_mengetahui
				from tb_bastb_pusat bap
				LEFT JOIN tb_provinsi prov_peny ON bap.id_provinsi_penyerah = prov_peny.id
				LEFT JOIN tb_provinsi prov_pene ON bap.id_provinsi_penerima = prov_pene.id
				LEFT JOIN tb_kabupaten kab_peny ON bap.id_kabupaten_penyerah = kab_peny.id
				LEFT JOIN tb_kabupaten kab_pene ON bap.id_kabupaten_penerima = kab_pene.id
				LEFT JOIN tb_kecamatan kec_pene ON bap.id_kecamatan_penerima = kec_pene.id
				LEFT JOIN tb_kelurahan kel_pene ON bap.id_kelurahan_penerima = kel_pene.id
				WHERE bap.id = '$id'";
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row();
			else
				return array();
		}

		function Insert($data)
		{
			$this->db->insert('tb_bastb_pusat',$data);
		}

		function Update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_bastb_pusat', $data);
		}

		function Delete($id)
		{
			$this->db->delete('tb_bastb_pusat', array('id' => $id));
		}


		function GetTotalUnit()
		{
			$qry =	"	
				SELECT SUM(jumlah_barang) AS total 
				FROM tb_bastb_pusat 
				where `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and merk in ( SELECT merk from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat." )" : "")." 
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and merk in ( SELECT merk from tb_jenis_barang_provinsi where id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi. " )" : "")."
				";
				
			// if(isset($this->session->userdata('logged_in')->id_provinsi)){
			// 	$qry .= "
			// 		and id in(
			// 			select id_kontrak_pusat from tb_kontrak_detail_pusat
			// 			where id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi."
			// 		)
			// 	";
			// }

			// if(isset($this->session->userdata('logged_in')->id_kabupaten)){
			// 	$qry .= "
			// 		and id in(
			// 			select id_kontrak_pusat from tb_kontrak_detail_pusat
			// 			where id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten."
			// 		)
			// 	";
			// }

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}

		function GetTotalNilai()
		{
			$qry =	"	
				SELECT SUM(nilai_barang) AS total 
				FROM tb_bastb_pusat 
				where `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and merk in ( SELECT merk from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat." )" : "")." 
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and merk in ( SELECT merk from tb_jenis_barang_provinsi where id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi. " )" : "")."
				";

			// if(isset($this->session->userdata('logged_in')->id_provinsi)){
			// 	$qry .= "
			// 		and id in(
			// 			select id_kontrak_pusat from tb_kontrak_detail_pusat
			// 			where id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi."
			// 		)
			// 	";
			// }

			// if(isset($this->session->userdata('logged_in')->id_kabupaten)){
			// 	$qry .= "
			// 		and id in(
			// 			select id_kontrak_pusat from tb_kontrak_detail_pusat
			// 			where id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten."
			// 		)
			// 	";
			// }

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}

		function GetTotalKontrak()
		{
			$qry =	"	
				SELECT COUNT(*) AS total 
				FROM tb_kontrak_pusat 
				where `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "");
			
			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and id in(
						select id_kontrak_pusat from tb_kontrak_detail_pusat
						where id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi."
					)
				";
			}

			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					and id in(
						select id_kontrak_pusat from tb_kontrak_detail_pusat
						where id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten."
					)
				";
			}

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}

		function GetTotalMerk()
		{
			$qry =	"	
				SELECT COUNT(DISTINCT merk) AS total 
				FROM tb_kontrak_pusat 
				where `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "");

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and id in(
						select id_kontrak_pusat from tb_kontrak_detail_pusat
						where id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi."
					)
				";
			}

			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					and id in(
						select id_kontrak_pusat from tb_kontrak_detail_pusat
						where id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten."
					)
				";
			}

			$res = $this->db->query($qry);		

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}

	}
?>