<?php
	class AlokasiProvinsiModel extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function GetAll()
		{
			$qry =	"	
				SELECT 
					hd.`tahun_anggaran`,
					hd.`no_kontrak`,
					hd.`periode_mulai`,
					hd.`periode_selesai`,
					hd.`nama_barang`,
					hd.`merk`,
					barang.`jenis_barang`,
					dt.id, dt.`id_kontrak_provinsi`, 
					dt.`id_provinsi`, p.`nama_provinsi`,
					dt.`id_kabupaten`, k.`nama_kabupaten`,
					dt.`nilai_barang_detail`, dt.`jumlah_barang_detail`, 
					dt.`regcad`, dt.`dinas`,
					dt.`no_adendum_1`, dt.`jumlah_barang_rev_1`, dt.`nilai_barang_rev_1`,
					dt.`no_adendum_2`, dt.`jumlah_barang_rev_2`, dt.`nilai_barang_rev_2`,
					dt.`nama_file_adendum_1`, dt.`nama_file_adendum_2`,
					dt.`status_alokasi`, dt.nama_file, 
					pen.`nama_penyedia_provinsi`,
					dt.id_hibah_provinsi
					
				FROM 
				tb_kontrak_detail_provinsi dt 
				LEFT JOIN tb_kontrak_provinsi hd ON hd.id = dt.id_kontrak_provinsi
				LEFT JOIN `tb_jenis_barang_provinsi` barang ON barang.`nama_barang` = hd.`nama_barang` AND barang.`merk` = hd.`merk` and barang.id_provinsi = hd.id_provinsi and barang.id_penyedia_provinsi = hd.id_penyedia_provinsi
				LEFT JOIN `tb_penyedia_provinsi` pen ON pen.`id` = hd.`id_penyedia_provinsi`
				INNER JOIN tb_provinsi p ON p.id = dt.`id_provinsi`
				INNER JOIN tb_kabupaten k ON k.id = dt.`id_kabupaten`
				WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')
				and hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and pen.id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "")."
				ORDER BY tahun_anggaran, hd.no_kontrak ";
		
			$res = $this->db->query($qry);

			// if($res->num_rows() > 0)
			// 	return $res->result();
			// else
			// 	return array();

			return array();
		}

		function GetAllAjaxCount()
		{
			$qry =	"	
				SELECT 
					hd.`tahun_anggaran`,
					hd.`no_kontrak`,
					hd.`periode_mulai`,
					hd.`periode_selesai`,
					hd.`nama_barang`,
					hd.`merk`,
					barang.`jenis_barang`,
					dt.id, dt.`id_kontrak_provinsi`, 
					dt.`id_provinsi`, p.`nama_provinsi`,
					dt.`id_kabupaten`, k.`nama_kabupaten`,
					dt.`nilai_barang_detail`, dt.`jumlah_barang_detail`, 
					dt.`regcad`, dt.`dinas`,
					dt.`no_adendum_1`, dt.`jumlah_barang_rev_1`, dt.`nilai_barang_rev_1`,
					dt.`no_adendum_2`, dt.`jumlah_barang_rev_2`, dt.`nilai_barang_rev_2`,
					dt.`nama_file_adendum_1`, dt.`nama_file_adendum_2`,
					dt.`status_alokasi`, dt.nama_file, 
					pen.`nama_penyedia_provinsi`,
					dt.id_hibah_provinsi
					
				FROM 
				tb_kontrak_detail_provinsi dt 
				LEFT JOIN tb_kontrak_provinsi hd ON hd.id = dt.id_kontrak_provinsi
				LEFT JOIN `tb_jenis_barang_provinsi` barang ON barang.`nama_barang` = hd.`nama_barang` AND barang.`merk` = hd.`merk` and barang.id_provinsi = hd.id_provinsi and barang.id_penyedia_provinsi = hd.id_penyedia_provinsi
				LEFT JOIN `tb_penyedia_provinsi` pen ON pen.`id` = hd.`id_penyedia_provinsi`
				INNER JOIN tb_provinsi p ON p.id = dt.`id_provinsi`
				INNER JOIN tb_kabupaten k ON k.id = dt.`id_kabupaten`
				WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')
				and hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and pen.id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				ORDER BY tahun_anggaran, hd.no_kontrak ";
			
			// die($qry);
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetSearchAjaxCount($search, $filter = '')
		{
			$qry =	"	
				SELECT 
					hd.`tahun_anggaran`,
					hd.`no_kontrak`,
					hd.`periode_mulai`,
					hd.`periode_selesai`,
					hd.`nama_barang`,
					hd.`merk`,
					barang.`jenis_barang`,
					dt.id, dt.`id_kontrak_provinsi`, 
					dt.`id_provinsi`, p.`nama_provinsi`,
					dt.`id_kabupaten`, k.`nama_kabupaten`,
					dt.`nilai_barang_detail`, dt.`jumlah_barang_detail`, 
					dt.`regcad`, dt.`dinas`,
					dt.`no_adendum_1`, dt.`jumlah_barang_rev_1`, dt.`nilai_barang_rev_1`,
					dt.`no_adendum_2`, dt.`jumlah_barang_rev_2`, dt.`nilai_barang_rev_2`,
					dt.`nama_file_adendum_1`, dt.`nama_file_adendum_2`,
					dt.`status_alokasi`, dt.nama_file, dt.nama_file, 
					pen.`nama_penyedia_provinsi`,
					dt.id_hibah_provinsi
					
				FROM 
				tb_kontrak_detail_provinsi dt 
				LEFT JOIN tb_kontrak_provinsi hd ON hd.id = dt.id_kontrak_provinsi
				LEFT JOIN `tb_jenis_barang_provinsi` barang ON barang.`nama_barang` = hd.`nama_barang` AND barang.`merk` = hd.`merk` and barang.id_provinsi = hd.id_provinsi and barang.id_penyedia_provinsi = hd.id_penyedia_provinsi
				LEFT JOIN `tb_penyedia_provinsi` pen ON pen.`id` = hd.`id_penyedia_provinsi`
				INNER JOIN tb_provinsi p ON p.id = dt.`id_provinsi`
				INNER JOIN tb_kabupaten k ON k.id = dt.`id_kabupaten`
				WHERE 
					dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')
					and (
						hd.`no_kontrak` LIKE '%".$search."%' 
						or hd.`nama_barang` LIKE '%".$search."%' 
						or hd.`merk` LIKE '%".$search."%' 
						or barang.`jenis_barang` LIKE '%".$search."%' 
						or p.`nama_provinsi` LIKE '%".$search."%' 
						or k.`nama_kabupaten` LIKE '%".$search."%' 
						or dt.`no_adendum_1` LIKE '%".$search."%' 
						or dt.`no_adendum_2` LIKE '%".$search."%' 
						or dt.`status_alokasi` LIKE '%".$search."%' 
						or pen.`nama_penyedia_provinsi` LIKE '%".$search."%' 
					)
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and pen.id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				".$filter."
				and hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetFilterAjaxCount($filter = '')
		{
			$qry =	"	
				SELECT 
					hd.`tahun_anggaran`,
					hd.`no_kontrak`,
					hd.`periode_mulai`,
					hd.`periode_selesai`,
					hd.`nama_barang`,
					hd.`merk`,
					barang.`jenis_barang`,
					dt.id, dt.`id_kontrak_provinsi`, 
					dt.`id_provinsi`, p.`nama_provinsi`,
					dt.`id_kabupaten`, k.`nama_kabupaten`,
					dt.`nilai_barang_detail`, dt.`jumlah_barang_detail`, 
					dt.`regcad`, dt.`dinas`,
					dt.`no_adendum_1`, dt.`jumlah_barang_rev_1`, dt.`nilai_barang_rev_1`,
					dt.`no_adendum_2`, dt.`jumlah_barang_rev_2`, dt.`nilai_barang_rev_2`,
					dt.`nama_file_adendum_1`, dt.`nama_file_adendum_2`,
					dt.`status_alokasi`, dt.nama_file, 
					pen.`nama_penyedia_provinsi`,
					dt.id_hibah_provinsi
					
				FROM 
				tb_kontrak_detail_provinsi dt 
				LEFT JOIN tb_kontrak_provinsi hd ON hd.id = dt.id_kontrak_provinsi
				LEFT JOIN `tb_jenis_barang_provinsi` barang ON barang.`nama_barang` = hd.`nama_barang` AND barang.`merk` = hd.`merk` and barang.id_provinsi = hd.id_provinsi and barang.id_penyedia_provinsi = hd.id_penyedia_provinsi
				LEFT JOIN `tb_penyedia_provinsi` pen ON pen.`id` = hd.`id_penyedia_provinsi`
				INNER JOIN tb_provinsi p ON p.id = dt.`id_provinsi`
				INNER JOIN tb_kabupaten k ON k.id = dt.`id_kabupaten`
				WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')
				and hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and pen.id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				".$filter."
				ORDER BY tahun_anggaran, hd.no_kontrak ";
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetAllForAjax($start, $length, $col, $dir, $filter = '')
		{

			$qry = "
				SELECT 
					hd.`tahun_anggaran`,
					hd.`no_kontrak`,
					hd.`periode_mulai`,
					hd.`periode_selesai`,
					hd.`nama_barang`,
					hd.`merk`,
					barang.`jenis_barang`,
					dt.id, dt.`id_kontrak_provinsi`, 
					dt.`id_provinsi`, p.`nama_provinsi`,
					dt.`id_kabupaten`, k.`nama_kabupaten`,
					dt.`nilai_barang_detail`, dt.`jumlah_barang_detail`, 
					dt.`regcad`, dt.`dinas`,
					dt.`no_adendum_1`, dt.`jumlah_barang_rev_1`, dt.`nilai_barang_rev_1`,
					dt.`no_adendum_2`, dt.`jumlah_barang_rev_2`, dt.`nilai_barang_rev_2`,
					dt.`nama_file_adendum_1`, dt.`nama_file_adendum_2`,
					dt.`status_alokasi`, dt.nama_file, 
					pen.`nama_penyedia_provinsi`,
					dt.id_hibah_provinsi
					
				FROM 
					tb_kontrak_detail_provinsi dt 
					LEFT JOIN tb_kontrak_provinsi hd ON hd.id = dt.id_kontrak_provinsi
					LEFT JOIN `tb_jenis_barang_provinsi` barang ON barang.`nama_barang` = hd.`nama_barang` AND barang.`merk` = hd.`merk` and barang.id_provinsi = hd.id_provinsi and barang.id_penyedia_provinsi = hd.id_penyedia_provinsi
					LEFT JOIN `tb_penyedia_provinsi` pen ON pen.`id` = hd.`id_penyedia_provinsi`
					INNER JOIN tb_provinsi p ON p.id = dt.`id_provinsi`
					INNER JOIN tb_kabupaten k ON k.id = dt.`id_kabupaten`
				WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')
				and hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and pen.id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				".$filter."
				ORDER BY ".$col." ".$dir;
			if($length > 0)
				$qry .=	' LIMIT ' . $start . ',' . $length;
			
			$res = $this->db->query($qry);
			// die($qry);
			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetSearchAjax($start, $length, $search, $col, $dir, $filter = '')
		{

			$qry = "
				SELECT 
					hd.`tahun_anggaran`,
					hd.`no_kontrak`,
					hd.`periode_mulai`,
					hd.`periode_selesai`,
					hd.`nama_barang`,
					hd.`merk`,
					barang.`jenis_barang`,
					dt.id, dt.`id_kontrak_provinsi`, 
					dt.`id_provinsi`, p.`nama_provinsi`,
					dt.`id_kabupaten`, k.`nama_kabupaten`,
					dt.`nilai_barang_detail`, dt.`jumlah_barang_detail`, 
					dt.`regcad`, dt.`dinas`,
					dt.`no_adendum_1`, dt.`jumlah_barang_rev_1`, dt.`nilai_barang_rev_1`,
					dt.`no_adendum_2`, dt.`jumlah_barang_rev_2`, dt.`nilai_barang_rev_2`,
					dt.`nama_file_adendum_1`, dt.`nama_file_adendum_2`,
					dt.`status_alokasi`, dt.nama_file, 
					pen.`nama_penyedia_provinsi`,
					dt.id_hibah_provinsi
					
				FROM 
					tb_kontrak_detail_provinsi dt 
					LEFT JOIN tb_kontrak_provinsi hd ON hd.id = dt.id_kontrak_provinsi
					LEFT JOIN `tb_jenis_barang_provinsi` barang ON barang.`nama_barang` = hd.`nama_barang` AND barang.`merk` = hd.`merk` and barang.id_provinsi = hd.id_provinsi and barang.id_penyedia_provinsi = hd.id_penyedia_provinsi
					LEFT JOIN `tb_penyedia_provinsi` pen ON pen.`id` = hd.`id_penyedia_provinsi`
					INNER JOIN tb_provinsi p ON p.id = dt.`id_provinsi`
					INNER JOIN tb_kabupaten k ON k.id = dt.`id_kabupaten`
				WHERE 
					dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')
					and (
						hd.`no_kontrak` LIKE '%".$search."%' 
						or hd.`nama_barang` LIKE '%".$search."%' 
						or hd.`merk` LIKE '%".$search."%' 
						or barang.`jenis_barang` LIKE '%".$search."%' 
						or p.`nama_provinsi` LIKE '%".$search."%' 
						or k.`nama_kabupaten` LIKE '%".$search."%' 
						or dt.`no_adendum_1` LIKE '%".$search."%' 
						or dt.`no_adendum_2` LIKE '%".$search."%' 
						or dt.`status_alokasi` LIKE '%".$search."%' 
						or pen.`nama_penyedia_provinsi` LIKE '%".$search."%' 
					)
				and hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and pen.id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
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
					hd.`tahun_anggaran`,
					hd.`no_kontrak`,
					hd.`periode_mulai`,
					hd.`periode_selesai`,
					hd.`nama_barang`,
					hd.`merk`,
					barang.`jenis_barang`,
					dt.id, dt.`id_kontrak_provinsi`, 
					dt.`id_provinsi`, p.`nama_provinsi`,
					dt.`id_kabupaten`, k.`nama_kabupaten`,
					dt.`nilai_barang_detail`, dt.`jumlah_barang_detail`, 
					dt.`regcad`, dt.`dinas`,
					dt.`no_adendum_1`, dt.`jumlah_barang_rev_1`, dt.`nilai_barang_rev_1`,
					dt.`no_adendum_2`, dt.`jumlah_barang_rev_2`, dt.`nilai_barang_rev_2`,
					dt.`nama_file_adendum_1`, dt.`nama_file_adendum_2`,
					dt.`status_alokasi`, dt.nama_file, 
					pen.`nama_penyedia_provinsi`,
					dt.id_hibah_provinsi
					
				FROM 
					tb_kontrak_detail_provinsi dt 
					LEFT JOIN tb_kontrak_provinsi hd ON hd.id = dt.id_kontrak_provinsi
					LEFT JOIN `tb_jenis_barang_provinsi` barang ON barang.`nama_barang` = hd.`nama_barang` AND barang.`merk` = hd.`merk` and barang.id_provinsi = hd.id_provinsi and barang.id_penyedia_provinsi = hd.id_penyedia_provinsi
					LEFT JOIN `tb_penyedia_provinsi` pen ON pen.`id` = hd.`id_penyedia_provinsi`
					INNER JOIN tb_provinsi p ON p.id = dt.`id_provinsi`
					INNER JOIN tb_kabupaten k ON k.id = dt.`id_kabupaten`
				WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')
				and hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and pen.id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
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
					hd.`tahun_anggaran`,
					hd.`no_kontrak`,
					hd.`periode_mulai`,
					hd.`periode_selesai`,
					hd.`nama_barang`,
					hd.`merk`,
					barang.`jenis_barang`,
					dt.id, dt.`id_kontrak_provinsi`, 
					dt.`id_provinsi`, p.`nama_provinsi`,
					dt.`id_kabupaten`, k.`nama_kabupaten`,
					dt.`nilai_barang_detail`, dt.`jumlah_barang_detail`, 
					dt.`regcad`, dt.`dinas`,
					dt.`no_adendum_1`, dt.`jumlah_barang_rev_1`, dt.`nilai_barang_rev_1`,
					dt.`no_adendum_2`, dt.`jumlah_barang_rev_2`, dt.`nilai_barang_rev_2`,
					dt.`nama_file_adendum_1`, dt.`nama_file_adendum_2`,
					dt.`status_alokasi`, dt.nama_file, 
					pen.`nama_penyedia_provinsi`,
					dt.id_hibah_provinsi
					
				FROM 
					tb_kontrak_detail_provinsi dt 
					LEFT JOIN tb_kontrak_provinsi hd ON hd.id = dt.id_kontrak_provinsi
					LEFT JOIN `tb_jenis_barang_provinsi` barang ON barang.`nama_barang` = hd.`nama_barang` AND barang.`merk` = hd.`merk` and barang.id_provinsi = hd.id_provinsi and barang.id_penyedia_provinsi = hd.id_penyedia_provinsi
					LEFT JOIN `tb_penyedia_provinsi` pen ON pen.`id` = hd.`id_penyedia_provinsi`
					INNER JOIN tb_provinsi p ON p.id = dt.`id_provinsi`
					INNER JOIN tb_kabupaten k ON k.id = dt.`id_kabupaten`
				WHERE 
					dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')
					and (
						hd.`no_kontrak` LIKE '%".$search."%' 
						or hd.`nama_barang` LIKE '%".$search."%' 
						or hd.`merk` LIKE '%".$search."%' 
						or barang.`jenis_barang` LIKE '%".$search."%' 
						or p.`nama_provinsi` LIKE '%".$search."%' 
						or k.`nama_kabupaten` LIKE '%".$search."%' 
						or dt.`no_adendum_1` LIKE '%".$search."%' 
						or dt.`no_adendum_2` LIKE '%".$search."%' 
						or dt.`status_alokasi` LIKE '%".$search."%' 
						or pen.`nama_penyedia_provinsi` LIKE '%".$search."%' 
					)
				and hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and pen.id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				".$filter."
				ORDER BY ".$col." ".$dir;
			
			$data = array();
			$res = $this->db->query($qry);
			if($res->num_rows() > 0) {
				$data = $res->result_array();
			}		
			return $data;
		}

		function GetData($id_kontrak_detail)
		{
			$qry =	"	
				SELECT 
					hd.`tahun_anggaran`,
					hd.`no_kontrak`,
					hd.`periode_mulai`,
					hd.`periode_selesai`,
					hd.`nama_barang`,
					hd.`merk`,
					barang.`jenis_barang`,
					dt.id, dt.`id_kontrak_provinsi`, 
					dt.`id_provinsi`, p.`nama_provinsi`,
					dt.`id_kabupaten`, k.`nama_kabupaten`,
					dt.`nilai_barang_detail`, dt.`jumlah_barang_detail`, 
					dt.`regcad`, dt.`dinas`,
					dt.`no_adendum_1`, dt.`jumlah_barang_rev_1`, dt.`nilai_barang_rev_1`,
					dt.`no_adendum_2`, dt.`jumlah_barang_rev_2`, dt.`nilai_barang_rev_2`,
					dt.`nama_file_adendum_1`, dt.`nama_file_adendum_2`,
					dt.`status_alokasi`, dt.nama_file, 
					pen.`nama_penyedia_provinsi`,
					dt.id_hibah_provinsi,
					barang.akun, barang.kode_barang, barang.jenis_barang
					
				FROM 
					tb_kontrak_detail_provinsi dt 
					LEFT JOIN tb_kontrak_provinsi hd ON hd.id = dt.id_kontrak_provinsi
					LEFT JOIN `tb_jenis_barang_provinsi` barang ON barang.`nama_barang` = hd.`nama_barang` AND barang.`merk` = hd.`merk` and barang.id_provinsi = hd.id_provinsi and barang.id_penyedia_provinsi = hd.id_penyedia_provinsi
					LEFT JOIN `tb_penyedia_provinsi` pen ON pen.`id` = hd.`id_penyedia_provinsi`
					INNER JOIN tb_provinsi p ON p.id = dt.`id_provinsi`
					INNER JOIN tb_kabupaten k ON k.id = dt.`id_kabupaten`
				WHERE 
					dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')
					and dt.id = $id_kontrak_detail
					and hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				ORDER BY tahun_anggaran, hd.no_kontrak ";
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row();
			else
				return array();
		}

		function GetByHibah($id_hibah_provinsi)
		{
			$qry =	"	
				SELECT 
					hd.`tahun_anggaran`,
					hd.`no_kontrak`,
					hd.`periode_mulai`,
					hd.`periode_selesai`,
					hd.`nama_barang`,
					hd.`merk`,
					barang.`jenis_barang`,
					dt.id, dt.`id_kontrak_provinsi`, 
					dt.`id_provinsi`, p.`nama_provinsi`,
					dt.`id_kabupaten`, k.`nama_kabupaten`,
					dt.`nilai_barang_detail`, dt.`jumlah_barang_detail`, 
					dt.`regcad`, dt.`dinas`,
					dt.`no_adendum_1`, dt.`jumlah_barang_rev_1`, dt.`nilai_barang_rev_1`,
					dt.`no_adendum_2`, dt.`jumlah_barang_rev_2`, dt.`nilai_barang_rev_2`,
					dt.`nama_file_adendum_1`, dt.`nama_file_adendum_2`,
					dt.`status_alokasi`, dt.nama_file, 
					pen.`nama_penyedia_provinsi`,
					dt.id_hibah_provinsi,
					barang.akun, barang.kode_barang, barang.jenis_barang
				FROM 
					tb_kontrak_detail_provinsi dt 
					LEFT JOIN tb_kontrak_provinsi hd ON hd.id = dt.id_kontrak_provinsi
					LEFT JOIN `tb_jenis_barang_provinsi` barang ON barang.`nama_barang` = hd.`nama_barang` AND barang.`merk` = hd.`merk` and barang.id_provinsi = hd.id_provinsi and barang.id_penyedia_provinsi = hd.id_penyedia_provinsi
					LEFT JOIN `tb_penyedia_provinsi` pen ON pen.`id` = hd.`id_penyedia_provinsi`
					INNER JOIN tb_provinsi p ON p.id = dt.`id_provinsi`
					INNER JOIN tb_kabupaten k ON k.id = dt.`id_kabupaten`
				WHERE 
					dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')
					and dt.id_hibah_provinsi = $id_hibah_provinsi
					and hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				ORDER BY tahun_anggaran, hd.no_kontrak ";
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetByHibahGrouping($id_hibah_provinsi){
			$qry =	"	
				SELECT hd.`nama_barang`, barang.`akun`,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang_detail
					END) AS total_unit,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang_detail
					END) AS total_nilai
					FROM
					tb_kontrak_detail_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN `tb_jenis_barang_provinsi` barang ON barang.`nama_barang` = hd.`nama_barang` AND barang.`merk` = hd.`merk` and barang.id_provinsi = hd.id_provinsi and barang.id_penyedia_provinsi = hd.id_penyedia_provinsi
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')
					AND dt.id_hibah_provinsi = ".$id_hibah_provinsi." AND hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				GROUP BY  hd.`nama_barang`, barang.`akun`
				ORDER BY hd.`nama_barang`, barang.`akun` ";
			
			// die($qry);
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetTotalUnit()
		{
			$qry =	"	
				SELECT SUM(total_temp) AS total FROM (
					SELECT dt.status_alokasi, (CASE 	
						WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
							SUM(dt.jumlah_barang_rev_1)
						WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
							SUM(dt.jumlah_barang_rev_2)
						ELSE
							SUM(dt.jumlah_barang_detail)
						END) AS total_temp
				FROM tb_kontrak_detail_provinsi dt 
				LEFT JOIN tb_kontrak_provinsi hd ON hd.id = dt.id_kontrak_provinsi
				WHERE 
					dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')
					and hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
					".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and hd.id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				GROUP BY dt.status_alokasi) AS t_temp ";
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}

		function GetTotalNilai()
		{
			$qry =	"	
				SELECT SUM(total_temp) AS total FROM (
					SELECT dt.status_alokasi, (CASE 	
						WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
							SUM(dt.nilai_barang_rev_1)
						WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
							SUM(dt.nilai_barang_rev_2)
						ELSE
							SUM(dt.nilai_barang_detail)
						END) AS total_temp
				FROM tb_kontrak_detail_provinsi dt 
				LEFT JOIN tb_kontrak_provinsi hd ON hd.id = dt.id_kontrak_provinsi
				WHERE 
					dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')
					and hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
					".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and hd.id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				GROUP BY dt.status_alokasi) AS t_temp ";
	
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}

		function GetUnit($id_kontrak_detail)
		{
			$qry =	"	
				SELECT SUM(total_temp) AS total FROM (
					SELECT dt.status_alokasi, (CASE 	
						WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
							SUM(dt.jumlah_barang_rev_1)
						WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
							SUM(dt.jumlah_barang_rev_2)
						ELSE
							SUM(dt.jumlah_barang_detail)
						END) AS total_temp
				FROM tb_kontrak_detail_provinsi dt 
				LEFT JOIN tb_kontrak_provinsi hd ON hd.id = dt.id_kontrak_provinsi
				WHERE 
					dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')
					and dt.id = $id_kontrak_detail
					and hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
					".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and hd.id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				GROUP BY dt.status_alokasi) AS t_temp ";
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}

		function GetNilai($id_kontrak_detail)
		{
			$qry =	"	
				SELECT SUM(total_temp) AS total FROM (
					SELECT dt.status_alokasi, (CASE 	
						WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
							SUM(dt.nilai_barang_rev_1)
						WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
							SUM(dt.nilai_barang_rev_2)
						ELSE
							SUM(dt.nilai_barang_detail)
						END) AS total_temp
				FROM tb_kontrak_detail_provinsi dt 
				LEFT JOIN tb_kontrak_provinsi hd ON hd.id = dt.id_kontrak_provinsi
				WHERE 
					dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')
					and dt.id = $id_kontrak_detail
					and hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
					".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and hd.id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				GROUP BY dt.status_alokasi) AS t_temp ";
	
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}

		function GetTotalKontrak()
		{
			$qry =	"	
				SELECT COUNT(DISTINCT dt.id_kontrak_provinsi) AS total 
				FROM tb_kontrak_detail_provinsi dt 
				LEFT JOIN tb_kontrak_provinsi hd ON hd.id = dt.id_kontrak_provinsi
				WHERE 
					dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI') 
					".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and hd.id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
					and hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
		
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
				FROM tb_kontrak_detail_provinsi dt 
				LEFT JOIN tb_kontrak_provinsi hd ON hd.id = dt.id_kontrak_provinsi
				WHERE 
					dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')
					".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and hd.id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
					and hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}

		function UpdateHibahId($id_kontrak_detail, $id_hibah)
		{
			$qry =	"	
				UPDATE tb_kontrak_detail_provinsi SET id_hibah_provinsi = $id_hibah
				WHERE id = $id_kontrak_detail";
	
			$this->db->query($qry);
		}

	}
?>