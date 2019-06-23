<?php
	class KontrakPusatModel extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function GetAll()
		{
			$qry =	"	
					SELECT hd.id, hd.tahun_anggaran, peny.nama_penyedia_pusat, hd.no_kontrak, hd.periode_mulai, hd.periode_selesai, hd.nama_barang, hd.merk, hd.jumlah_barang, hd.nilai_barang, hd.nama_file, 
						t.jumlah_alokasi, t.nilai_alokasi
						FROM tb_kontrak_pusat hd INNER JOIN tb_penyedia_pusat peny ON hd.`id_penyedia_pusat` = peny.id
						LEFT JOIN (
							SELECT id_header, SUM(total_jumlah_temp) AS jumlah_alokasi, SUM(total_nilai_temp) AS nilai_alokasi FROM (
								SELECT hd.id AS id_header, dt.status_alokasi, (CASE 	
									WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
										SUM(dt.jumlah_barang_rev_1)
									WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
										SUM(dt.jumlah_barang_rev_2)
									ELSE
										SUM(dt.jumlah_barang_detail)
									END) AS total_jumlah_temp,
									(CASE 	
									WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
										SUM(dt.nilai_barang_rev_1)
									WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
										SUM(dt.nilai_barang_rev_2)
									ELSE
										SUM(dt.nilai_barang_detail)
									END) AS total_nilai_temp
							FROM tb_kontrak_detail_pusat dt 
							LEFT JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
							WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')
							GROUP BY id_header, dt.status_alokasi) AS temp
							GROUP BY temp.id_header
						) AS t ON hd.id = t.id_header
						WHERE hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
						GROUP BY hd.id, hd.tahun_anggaran, peny.nama_penyedia_pusat, hd.no_kontrak, hd.periode_mulai, hd.periode_selesai, hd.nama_barang, 
						hd.merk, hd.jumlah_barang, hd.nilai_barang, hd.nama_file, t.jumlah_alokasi, t.nilai_alokasi";
		
			$res = $this->db->query($qry);

			return array();
			// if($res->num_rows() > 0)
			// 	return $res->result();
			// else
			// 	return array();
		}

		function GetAllAjaxCount()
		{
			$qry =	"	
				SELECT 
					hd.id, hd.tahun_anggaran, peny.nama_penyedia_pusat, hd.no_kontrak, hd.periode_mulai, hd.periode_selesai, hd.nama_barang, hd.merk, hd.jumlah_barang, hd.nilai_barang, hd.nama_file, 
				t.jumlah_alokasi, t.nilai_alokasi
				FROM tb_kontrak_pusat hd INNER JOIN tb_penyedia_pusat peny ON hd.`id_penyedia_pusat` = peny.id
				LEFT JOIN (
					SELECT id_header, SUM(total_jumlah_temp) AS jumlah_alokasi, SUM(total_nilai_temp) AS nilai_alokasi FROM (
						SELECT hd.id AS id_header, dt.status_alokasi, (CASE 	
							WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
								SUM(dt.jumlah_barang_rev_1)
							WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
								SUM(dt.jumlah_barang_rev_2)
							WHEN dt.status_alokasi = 'DATA KONTRAK AWAL' THEN
								SUM(dt.jumlah_barang_detail)
							ELSE
								0
							END) AS total_jumlah_temp,
							(CASE 	
							WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
								SUM(dt.nilai_barang_rev_1)
							WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
								SUM(dt.nilai_barang_rev_2)
							WHEN dt.status_alokasi = 'DATA KONTRAK AWAL' THEN
								SUM(dt.nilai_barang_detail)
							ELSE
								0
							END) AS total_nilai_temp
					FROM tb_kontrak_detail_pusat dt 
					LEFT JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
					WHERE 1 = 1
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
					GROUP BY id_header, dt.status_alokasi) AS temp
					GROUP BY temp.id_header
				) AS t ON hd.id = t.id_header
				WHERE hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and peny.id = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
				".((isset($this->session->userdata('logged_in')->id_provinsi) or isset($this->session->userdata('logged_in')->id_kabupaten)) ? " AND t.id_header IS NOT NULL " : "")."
				GROUP BY hd.id, hd.tahun_anggaran, peny.nama_penyedia_pusat, hd.no_kontrak, hd.periode_mulai, hd.periode_selesai, hd.nama_barang, 
				hd.merk, hd.jumlah_barang, hd.nilai_barang, hd.nama_file, t.jumlah_alokasi, t.nilai_alokasi ";
			
			// die($qry);
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetSearchAjaxCount($search, $filter = '')
		{
			$qry =	"
				SELECT hd.id, hd.tahun_anggaran, peny.nama_penyedia_pusat, hd.no_kontrak, hd.periode_mulai, hd.periode_selesai, hd.nama_barang, hd.merk, hd.jumlah_barang, hd.nilai_barang, hd.nama_file, 
				t.jumlah_alokasi, t.nilai_alokasi
				FROM tb_kontrak_pusat hd INNER JOIN tb_penyedia_pusat peny ON hd.`id_penyedia_pusat` = peny.id
				LEFT JOIN (
					SELECT id_header, SUM(total_jumlah_temp) AS jumlah_alokasi, SUM(total_nilai_temp) AS nilai_alokasi FROM (
						SELECT hd.id AS id_header, dt.status_alokasi, (CASE 	
							WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
								SUM(dt.jumlah_barang_rev_1)
							WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
								SUM(dt.jumlah_barang_rev_2)
							WHEN dt.status_alokasi = 'DATA KONTRAK AWAL' THEN
								SUM(dt.jumlah_barang_detail)
							ELSE
								0
							END) AS total_jumlah_temp,
							(CASE 	
							WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
								SUM(dt.nilai_barang_rev_1)
							WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
								SUM(dt.nilai_barang_rev_2)
							WHEN dt.status_alokasi = 'DATA KONTRAK AWAL' THEN
								SUM(dt.nilai_barang_detail)
							ELSE
								0
							END) AS total_nilai_temp
					FROM tb_kontrak_detail_pusat dt 
					LEFT JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
					WHERE 1 = 1
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
					GROUP BY id_header, dt.status_alokasi) AS temp
					GROUP BY temp.id_header
				) AS t ON hd.id = t.id_header
				WHERE hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				and
				(
					hd.`no_kontrak` LIKE '%".$search."%' 
					or peny.nama_penyedia_pusat LIKE '%".$search."%' 
					or hd.`merk` LIKE '%".$search."%' 
					or hd.nama_barang LIKE '%".$search."%' 
				)
				".$filter."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and peny.id = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
				".((isset($this->session->userdata('logged_in')->id_provinsi) or isset($this->session->userdata('logged_in')->id_kabupaten)) ? " AND t.id_header IS NOT NULL " : "")."
				GROUP BY hd.id, hd.tahun_anggaran, peny.nama_penyedia_pusat, hd.no_kontrak, hd.periode_mulai, hd.periode_selesai, hd.nama_barang, 
				hd.merk, hd.jumlah_barang, hd.nilai_barang, hd.nama_file, t.jumlah_alokasi, t.nilai_alokasi
			";
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetFilterAjaxCount($filter = '')
		{
			$qry =	"	
				SELECT 
					hd.id, hd.tahun_anggaran, peny.nama_penyedia_pusat, hd.no_kontrak, hd.periode_mulai, hd.periode_selesai, hd.nama_barang, hd.merk, hd.jumlah_barang, hd.nilai_barang, hd.nama_file, 
				t.jumlah_alokasi, t.nilai_alokasi
				FROM tb_kontrak_pusat hd INNER JOIN tb_penyedia_pusat peny ON hd.`id_penyedia_pusat` = peny.id
				LEFT JOIN (
					SELECT id_header, SUM(total_jumlah_temp) AS jumlah_alokasi, SUM(total_nilai_temp) AS nilai_alokasi FROM (
						SELECT hd.id AS id_header, dt.status_alokasi, (CASE 	
							WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
								SUM(dt.jumlah_barang_rev_1)
							WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
								SUM(dt.jumlah_barang_rev_2)
							WHEN dt.status_alokasi = 'DATA KONTRAK AWAL' THEN
								SUM(dt.jumlah_barang_detail)
							ELSE
								0
							END) AS total_jumlah_temp,
							(CASE 	
							WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
								SUM(dt.nilai_barang_rev_1)
							WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
								SUM(dt.nilai_barang_rev_2)
							WHEN dt.status_alokasi = 'DATA KONTRAK AWAL' THEN
								SUM(dt.nilai_barang_detail)
							ELSE
								0
							END) AS total_nilai_temp
					FROM tb_kontrak_detail_pusat dt 
					LEFT JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
					WHERE 1 = 1
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
					GROUP BY id_header, dt.status_alokasi) AS temp
					GROUP BY temp.id_header
				) AS t ON hd.id = t.id_header
				WHERE hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and peny.id = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
				".((isset($this->session->userdata('logged_in')->id_provinsi) or isset($this->session->userdata('logged_in')->id_kabupaten)) ? " AND t.id_header IS NOT NULL " : "")."
				".$filter."
				GROUP BY hd.id, hd.tahun_anggaran, peny.nama_penyedia_pusat, hd.no_kontrak, hd.periode_mulai, hd.periode_selesai, hd.nama_barang, 
				hd.merk, hd.jumlah_barang, hd.nilai_barang, hd.nama_file, t.jumlah_alokasi, t.nilai_alokasi ";
			
			// die($qry);
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetAllForAjax($start, $length, $col, $dir, $filter = '')
		{

			$qry = "
				SELECT 
					hd.id, hd.tahun_anggaran, peny.nama_penyedia_pusat, hd.no_kontrak, hd.periode_mulai, hd.periode_selesai, hd.nama_barang, hd.merk, hd.jumlah_barang, hd.nilai_barang, hd.nama_file, 
				t.jumlah_alokasi, t.nilai_alokasi
				FROM tb_kontrak_pusat hd INNER JOIN tb_penyedia_pusat peny ON hd.`id_penyedia_pusat` = peny.id
				LEFT JOIN (
					SELECT id_header, SUM(total_jumlah_temp) AS jumlah_alokasi, SUM(total_nilai_temp) AS nilai_alokasi FROM (
						SELECT hd.id AS id_header, dt.status_alokasi, (CASE 	
							WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
								SUM(dt.jumlah_barang_rev_1)
							WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
								SUM(dt.jumlah_barang_rev_2)
							WHEN dt.status_alokasi = 'DATA KONTRAK AWAL' THEN
								SUM(dt.jumlah_barang_detail)
							ELSE
								0
							END) AS total_jumlah_temp,
							(CASE 	
							WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
								SUM(dt.nilai_barang_rev_1)
							WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
								SUM(dt.nilai_barang_rev_2)
							WHEN dt.status_alokasi = 'DATA KONTRAK AWAL' THEN
								SUM(dt.nilai_barang_detail)
							ELSE
								0
							END) AS total_nilai_temp
					FROM tb_kontrak_detail_pusat dt 
					LEFT JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
					WHERE 1 = 1
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
					GROUP BY id_header, dt.status_alokasi) AS temp
					GROUP BY temp.id_header
				) AS t ON hd.id = t.id_header
				WHERE hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and peny.id = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
				".((isset($this->session->userdata('logged_in')->id_provinsi) or isset($this->session->userdata('logged_in')->id_kabupaten)) ? " AND t.id_header IS NOT NULL " : "")."
				".$filter."
				GROUP BY hd.id, hd.tahun_anggaran, peny.nama_penyedia_pusat, hd.no_kontrak, hd.periode_mulai, hd.periode_selesai, hd.nama_barang, 
				hd.merk, hd.jumlah_barang, hd.nilai_barang, hd.nama_file, t.jumlah_alokasi, t.nilai_alokasi
					
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
				SELECT hd.id, hd.tahun_anggaran, peny.nama_penyedia_pusat, hd.no_kontrak, hd.periode_mulai, hd.periode_selesai, hd.nama_barang, hd.merk, hd.jumlah_barang, hd.nilai_barang, hd.nama_file, 
				t.jumlah_alokasi, t.nilai_alokasi
				FROM tb_kontrak_pusat hd INNER JOIN tb_penyedia_pusat peny ON hd.`id_penyedia_pusat` = peny.id
				LEFT JOIN (
					SELECT id_header, SUM(total_jumlah_temp) AS jumlah_alokasi, SUM(total_nilai_temp) AS nilai_alokasi FROM (
						SELECT hd.id AS id_header, dt.status_alokasi, (CASE 	
							WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
								SUM(dt.jumlah_barang_rev_1)
							WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
								SUM(dt.jumlah_barang_rev_2)
							WHEN dt.status_alokasi = 'DATA KONTRAK AWAL' THEN
								SUM(dt.jumlah_barang_detail)
							ELSE
								0
							END) AS total_jumlah_temp,
							(CASE 	
							WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
								SUM(dt.nilai_barang_rev_1)
							WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
								SUM(dt.nilai_barang_rev_2)
							WHEN dt.status_alokasi = 'DATA KONTRAK AWAL' THEN
								SUM(dt.nilai_barang_detail)
							ELSE
								0
							END) AS total_nilai_temp
					FROM tb_kontrak_detail_pusat dt 
					LEFT JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
					WHERE 1 = 1
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
					GROUP BY id_header, dt.status_alokasi) AS temp
					GROUP BY temp.id_header
				) AS t ON hd.id = t.id_header
				WHERE hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				and
				(
					hd.`no_kontrak` LIKE '%".$search."%' 
					or peny.nama_penyedia_pusat LIKE '%".$search."%' 
					or hd.`merk` LIKE '%".$search."%' 
					or hd.nama_barang LIKE '%".$search."%' 
				)
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and peny.id = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
				".((isset($this->session->userdata('logged_in')->id_provinsi) or isset($this->session->userdata('logged_in')->id_kabupaten)) ? " AND t.id_header IS NOT NULL " : "")."
				".$filter."
				GROUP BY hd.id, hd.tahun_anggaran, peny.nama_penyedia_pusat, hd.no_kontrak, hd.periode_mulai, hd.periode_selesai, hd.nama_barang, 
				hd.merk, hd.jumlah_barang, hd.nilai_barang, hd.nama_file, t.jumlah_alokasi, t.nilai_alokasi

				ORDER BY ".$col." ".$dir;
			if($length > 0)
				$qry .=	' LIMIT ' . $start . ',' . $length;
			
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function Get($id)
		{
			// $res = $this->db->get_where('tb_kontrak_pusat', array('id' => $id));
			// $res = $this->db->get('tb_kontrak_pusat');

			// $this->db->select('tb_kontrak_pusat.id, tb_kontrak_pusat.tahun_anggaran, tb_penyedia_pusat.id, tb_penyedia_pusat.nama_penyedia_pusat, tb_kontrak_pusat.no_kontrak, tb_kontrak_pusat.periode_mulai, tb_kontrak_pusat.periode_selesai, tb_jenis_barang_pusat.id, tb_jenis_barang_pusat.nama_barang, tb_jenis_barang_pusat.merk, tb_jenis_barang_pusat.jenis_barang, tb_kontrak_pusat.jumlah_barang, tb_kontrak_pusat.nilai_barang, tb_kontrak_pusat.nama_file');
			// $this->db->from('tb_kontrak_pusat');
			// $this->db->join('tb_penyedia_pusat', 'tb_penyedia_pusat.id = tb_kontrak_pusat.id_penyedia_pusat');
			// $this->db->join('tb_jenis_barang_pusat', 'tb_jenis_barang_pusat.id = tb_kontrak_pusat.id_jenis_barang_pusat');
			// $this->db->where('tb_kontrak_pusat.id', array('id' => $id));
			// $res = $this->db->get();

			$qry =	"	select 
							tb_kontrak_pusat.id, 
							tb_kontrak_pusat.tahun_anggaran, 
							tb_penyedia_pusat.id as id_penyedia_pusat, 	
							tb_penyedia_pusat.nama_penyedia_pusat, 
							tb_kontrak_pusat.no_kontrak, 
							tb_kontrak_pusat.periode_mulai, 
							tb_kontrak_pusat.periode_selesai, 
							tb_kontrak_pusat.nama_barang, 
							tb_kontrak_pusat.merk, 
							tb_kontrak_pusat.jumlah_barang, 
							tb_kontrak_pusat.nilai_barang, 
							tb_kontrak_pusat.nama_file
					FROM tb_kontrak_pusat
					join tb_penyedia_pusat on tb_penyedia_pusat.id = tb_kontrak_pusat.id_penyedia_pusat
					where tb_kontrak_pusat.id = '$id'";
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row();
			else
				return array();
		}

		function GetByBarang($nama_barang, $merk)
		{
			$qry =	"	
						SELECT no_kontrak from tb_kontrak_pusat
						WHERE nama_barang = '$nama_barang' and merk = '$merk'
						order by created_at desc
					";
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		
		}

		function Insert($data)
		{
			$this->db->insert('tb_kontrak_pusat',$data);
		}

		function Update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_kontrak_pusat', $data);
		}

		function Delete($id)
		{
			$this->db->delete('tb_kontrak_detail_pusat', array('id_kontrak_pusat' => $id));
			$this->db->delete('tb_kontrak_pusat', array('id' => $id));
		}


		function GetTotalUnit()
		{
			$qry =	"	
				SELECT SUM(jumlah_barang) AS total 
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

		function GetTotalNilai()
		{
			$qry =	"	
				SELECT SUM(nilai_barang) AS total 
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

		// DETAIL

		function HitungJumlahKontrakDetail($id)
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
					FROM tb_kontrak_detail_pusat dt 
					LEFT JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
					WHERE hd.id = $id
					and dt.status_alokasi NOT IN('MENUNGGU KONFIRMASI')
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
					GROUP BY dt.status_alokasi) 
				AS t_temp";
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}

		function HitungJumlahKontrakDetailSelainDetailIni($id, $id_detail)
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
					FROM tb_kontrak_detail_pusat dt 
					LEFT JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
					WHERE hd.id = $id
					and dt.status_alokasi NOT IN('MENUNGGU KONFIRMASI')
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
					and dt.id != $id_detail
					GROUP BY dt.status_alokasi) 
				AS t_temp";
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}

		function GetTotalUnitDetail($id)
		{
			$qry =	"	
				SELECT SUM(total_nilai_temp) AS total FROM (
					SELECT dt.status_alokasi, (CASE 	
						WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
							SUM(dt.jumlah_barang_rev_1)
						WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
							SUM(dt.jumlah_barang_rev_2)
						ELSE
							SUM(dt.jumlah_barang_detail)
						END) AS total_nilai_temp
					FROM tb_kontrak_detail_pusat dt 
					LEFT JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
					WHERE hd.id = $id
					and dt.status_alokasi NOT IN('MENUNGGU KONFIRMASI')
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
					GROUP BY dt.status_alokasi) 
				AS t_temp";
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}

		function GetTotalNilaiDetail($id)
		{
			$qry =	"	
				SELECT SUM(total_nilai_temp) AS total FROM (
					SELECT dt.status_alokasi, (CASE 	
						WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
							SUM(dt.nilai_barang_rev_1)
						WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
							SUM(dt.nilai_barang_rev_2)
						ELSE
							SUM(dt.nilai_barang_detail)
						END) AS total_nilai_temp
					FROM tb_kontrak_detail_pusat dt 
					LEFT JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
					WHERE hd.id = $id
					and dt.status_alokasi NOT IN('MENUNGGU KONFIRMASI')
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
					GROUP BY dt.status_alokasi) 
				AS t_temp";
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}

		function GetAllDetail($id_kontrak_pusat)
		{
			$qry =	"	
				SELECT 
					dt.id, dt.`id_kontrak_pusat`, 
					dt.`id_provinsi`, p.`nama_provinsi`,
					dt.`id_kabupaten`, k.`nama_kabupaten`,
					dt.`nilai_barang_detail`, dt.`jumlah_barang_detail`, 
					dt.`regcad`, dt.`dinas`,
					dt.`no_adendum_1`, dt.`jumlah_barang_rev_1`, dt.`nilai_barang_rev_1`,
					dt.`no_adendum_2`, dt.`jumlah_barang_rev_2`, dt.`nilai_barang_rev_2`,
					dt.`nama_file_adendum_1`, dt.`nama_file_adendum_2`,
					dt.`status_alokasi`, dt.nama_file, 
					hd.no_kontrak
				FROM 
				tb_kontrak_detail_pusat dt 
				LEFT JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
				INNER JOIN tb_provinsi p ON p.id = dt.`id_provinsi`
				INNER JOIN tb_kabupaten k ON k.id = dt.`id_kabupaten`
				WHERE dt.id_kontrak_pusat = $id_kontrak_pusat
				".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				ORDER BY nama_provinsi, nama_kabupaten ";
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetAllAjaxCountDetail($id_kontrak_pusat){
			$qry =	"	
				SELECT 
					dt.id, dt.`id_kontrak_pusat`, 
					dt.`id_provinsi`, p.`nama_provinsi`,
					dt.`id_kabupaten`, k.`nama_kabupaten`,
					dt.`nilai_barang_detail`, dt.`jumlah_barang_detail`, 
					dt.`regcad`, dt.`dinas`,
					dt.`no_adendum_1`, dt.`jumlah_barang_rev_1`, dt.`nilai_barang_rev_1`,
					dt.`no_adendum_2`, dt.`jumlah_barang_rev_2`, dt.`nilai_barang_rev_2`,
					dt.`nama_file_adendum_1`, dt.`nama_file_adendum_2`,
					dt.`status_alokasi`, dt.nama_file, 
					hd.no_kontrak
				FROM 
				tb_kontrak_detail_pusat dt 
				LEFT JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
				INNER JOIN tb_provinsi p ON p.id = dt.`id_provinsi`
				INNER JOIN tb_kabupaten k ON k.id = dt.`id_kabupaten`
				WHERE dt.id_kontrak_pusat = $id_kontrak_pusat
				".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				ORDER BY nama_provinsi, nama_kabupaten ";

			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetSearchAjaxCountDetail($id_kontrak_pusat, $search, $filter = '')
		{
			$qry =	"
				SELECT 
					dt.id, dt.`id_kontrak_pusat`, 
					dt.`id_provinsi`, p.`nama_provinsi`,
					dt.`id_kabupaten`, k.`nama_kabupaten`,
					dt.`nilai_barang_detail`, dt.`jumlah_barang_detail`, 
					dt.`regcad`, dt.`dinas`,
					dt.`no_adendum_1`, dt.`jumlah_barang_rev_1`, dt.`nilai_barang_rev_1`,
					dt.`no_adendum_2`, dt.`jumlah_barang_rev_2`, dt.`nilai_barang_rev_2`,
					dt.`nama_file_adendum_1`, dt.`nama_file_adendum_2`,
					dt.`status_alokasi`, dt.nama_file, 
					hd.no_kontrak
				FROM 
				tb_kontrak_detail_pusat dt 
				LEFT JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
				INNER JOIN tb_provinsi p ON p.id = dt.`id_provinsi`
				INNER JOIN tb_kabupaten k ON k.id = dt.`id_kabupaten`
				WHERE dt.id_kontrak_pusat = $id_kontrak_pusat
				".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				".$filter."
				AND(
					p.nama_provinsi LIKE '%".$search."%'
					or k.nama_kabupaten LIKE '%".$search."%'
					or regcad LIKE '%".$search."%'
					or dinas LIKE '%".$search."%'
					or no_adendum_1 LIKE '%".$search."%'
					or no_adendum_2 LIKE '%".$search."%'
					or status_alokasi LIKE '%".$search."%'
				)";
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetFilterAjaxCountDetail($id_kontrak_pusat, $filter = ''){
			$qry =	"	
				SELECT 
					dt.id, dt.`id_kontrak_pusat`, 
					dt.`id_provinsi`, p.`nama_provinsi`,
					dt.`id_kabupaten`, k.`nama_kabupaten`,
					dt.`nilai_barang_detail`, dt.`jumlah_barang_detail`, 
					dt.`regcad`, dt.`dinas`,
					dt.`no_adendum_1`, dt.`jumlah_barang_rev_1`, dt.`nilai_barang_rev_1`,
					dt.`no_adendum_2`, dt.`jumlah_barang_rev_2`, dt.`nilai_barang_rev_2`,
					dt.`nama_file_adendum_1`, dt.`nama_file_adendum_2`,
					dt.`status_alokasi`, dt.nama_file, 
					hd.no_kontrak
				FROM 
				tb_kontrak_detail_pusat dt 
				LEFT JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
				INNER JOIN tb_provinsi p ON p.id = dt.`id_provinsi`
				INNER JOIN tb_kabupaten k ON k.id = dt.`id_kabupaten`
				WHERE dt.id_kontrak_pusat = $id_kontrak_pusat
				".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				".$filter."
				ORDER BY nama_provinsi, nama_kabupaten ";
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetAllForAjaxDetail($id_kontrak_pusat, $start, $length, $col, $dir, $filter = '')
		{

			$qry =	"	
				SELECT 
					dt.id, dt.`id_kontrak_pusat`, 
					dt.`id_provinsi`, p.`nama_provinsi`,
					dt.`id_kabupaten`, k.`nama_kabupaten`,
					dt.`nilai_barang_detail`, dt.`jumlah_barang_detail`, 
					dt.`regcad`, dt.`dinas`,
					dt.`no_adendum_1`, dt.`jumlah_barang_rev_1`, dt.`nilai_barang_rev_1`,
					dt.`no_adendum_2`, dt.`jumlah_barang_rev_2`, dt.`nilai_barang_rev_2`,
					dt.`nama_file_adendum_1`, dt.`nama_file_adendum_2`,
					dt.`status_alokasi`, dt.nama_file, 
					hd.no_kontrak
				FROM 
				tb_kontrak_detail_pusat dt 
				LEFT JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
				INNER JOIN tb_provinsi p ON p.id = dt.`id_provinsi`
				INNER JOIN tb_kabupaten k ON k.id = dt.`id_kabupaten`
				WHERE dt.id_kontrak_pusat = $id_kontrak_pusat
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

		function GetSearchAjaxDetail($id_kontrak_pusat, $start, $length, $search, $col, $dir, $filter = '')
		{

			$qry =	"	
				SELECT 
					dt.id, dt.`id_kontrak_pusat`, 
					dt.`id_provinsi`, p.`nama_provinsi`,
					dt.`id_kabupaten`, k.`nama_kabupaten`,
					dt.`nilai_barang_detail`, dt.`jumlah_barang_detail`, 
					dt.`regcad`, dt.`dinas`,
					dt.`no_adendum_1`, dt.`jumlah_barang_rev_1`, dt.`nilai_barang_rev_1`,
					dt.`no_adendum_2`, dt.`jumlah_barang_rev_2`, dt.`nilai_barang_rev_2`,
					dt.`nama_file_adendum_1`, dt.`nama_file_adendum_2`,
					dt.`status_alokasi`, dt.nama_file, 
					hd.no_kontrak
				FROM 
				tb_kontrak_detail_pusat dt 
				LEFT JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
				INNER JOIN tb_provinsi p ON p.id = dt.`id_provinsi`
				INNER JOIN tb_kabupaten k ON k.id = dt.`id_kabupaten`
				WHERE dt.id_kontrak_pusat = $id_kontrak_pusat
				".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				".$filter."
				AND(
					p.nama_provinsi LIKE '%".$search."%'
					or k.nama_kabupaten LIKE '%".$search."%'
					or regcad LIKE '%".$search."%'
					or dinas LIKE '%".$search."%'
					or no_adendum_1 LIKE '%".$search."%'
					or no_adendum_2 LIKE '%".$search."%'
					or status_alokasi LIKE '%".$search."%'
				)
				ORDER BY ".$col." ".$dir;
			if($length > 0)
				$qry .=	' LIMIT ' . $start . ',' . $length;
			
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetDetailData($id)
		{
			$qry =	"	
				SELECT 
					dt.id, dt.`id_kontrak_pusat`, 
					dt.`id_provinsi`, p.`nama_provinsi`,
					dt.`id_kabupaten`, k.`nama_kabupaten`,
					dt.`nilai_barang_detail`, dt.`jumlah_barang_detail`, 
					dt.`regcad`, dt.`dinas`,
					dt.`no_adendum_1`, dt.`jumlah_barang_rev_1`, dt.`nilai_barang_rev_1`,
					dt.`no_adendum_2`, dt.`jumlah_barang_rev_2`, dt.`nilai_barang_rev_2`,
					dt.`nama_file_adendum_1`, dt.`nama_file_adendum_2`,
					dt.`status_alokasi`, dt.nama_file,
					hd.no_kontrak
				FROM 
				tb_kontrak_detail_pusat dt 
				LEFT JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
				INNER JOIN tb_provinsi p ON p.id = dt.`id_provinsi`
				INNER JOIN tb_kabupaten k ON k.id = dt.`id_kabupaten`
				WHERE dt.id = $id
				ORDER BY nama_provinsi, nama_kabupaten ";
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row();
			else
				return array();
		}

		function InsertDetail($data)
		{
			$this->db->insert('tb_kontrak_detail_pusat',$data);
		}

		function UpdateDetail($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_kontrak_detail_pusat', $data);
		}

		function DeleteDetail($id)
		{
			$this->db->delete('tb_kontrak_detail_pusat', array('id' => $id));
		}

	}
?>