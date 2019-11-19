<?php
	class LaporanProvinsiModel extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function GetTotalKontrak($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia)
		{
			$qry =	"	
				SELECT COUNT(DISTINCT(id)) AS total 
				FROM tb_kontrak_provinsi 
				where tahun_anggaran = ".$tahun_anggaran." 
				and id in (
					select id_kontrak_provinsi from tb_alokasi_provinsi
						where 1=1 and status_alokasi NOT IN ('MENUNGGU KONFIRMASI')";
						$qry .= " AND `tb_kontrak_provinsi.tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
			
			if($list_id_provinsi != ''){

				for($i=0; $i<count($list_id_provinsi); $i++){
					if($i == 0)
						$qry .= "  and ( id_provinsi = ".$list_id_provinsi[$i];
					else
						$qry .= " or id_provinsi = ".$list_id_provinsi[$i];
				}

				$qry .= ")";
			}
			
			if($list_id_kabupaten != ''){

				for($i=0; $i<count($list_id_kabupaten); $i++){
					if($i == 0)
						$qry .= " and ( id_kabupaten = ".$list_id_kabupaten[$i];
					else
						$qry .= " or id_kabupaten = ".$list_id_kabupaten[$i];
				}

				$qry .= ")";

			}

			$qry .= ")";

			if($list_id_penyedia != ''){

				for($i=0; $i<count($list_id_penyedia); $i++){
					if($i == 0)
						$qry .= " and ( id_penyedia_provinsi = ".$list_id_penyedia[$i];
					else
						$qry .= " or id_penyedia_provinsi = ".$list_id_penyedia[$i];
				}

				$qry .= ")";
			}

			

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}

		function GetTotalBarang($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia)
		{
			$qry =	"	
				SELECT COUNT(DISTINCT(nama_barang)) AS total 
				FROM tb_kontrak_provinsi 
				where tahun_anggaran = ".$tahun_anggaran." 
				and id in (
					select id_kontrak_provinsi from tb_alokasi_provinsi
						where 1=1 and status_alokasi NOT IN ('MENUNGGU KONFIRMASI')";
						$qry .= " AND `tb_kontrak_provinsi.tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
			
			if($list_id_provinsi != ''){

				for($i=0; $i<count($list_id_provinsi); $i++){
					if($i == 0)
						$qry .= "  and ( id_provinsi = ".$list_id_provinsi[$i];
					else
						$qry .= " or id_provinsi = ".$list_id_provinsi[$i];
				}

				$qry .= ")";
			}
			
			if($list_id_kabupaten != ''){

				for($i=0; $i<count($list_id_kabupaten); $i++){
					if($i == 0)
						$qry .= " and ( id_kabupaten = ".$list_id_kabupaten[$i];
					else
						$qry .= " or id_kabupaten = ".$list_id_kabupaten[$i];
				}

				$qry .= ")";

			}

			$qry .= ")";

			if($list_id_penyedia != ''){

				for($i=0; $i<count($list_id_penyedia); $i++){
					if($i == 0)
						$qry .= " and ( id_penyedia_provinsi = ".$list_id_penyedia[$i];
					else
						$qry .= " or id_penyedia_provinsi = ".$list_id_penyedia[$i];
				}

				$qry .= ")";
			}

			

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}

		function GetTotalBAPHP($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia)
		{
			$qry =	"	
				SELECT COUNT(DISTINCT(id)) AS total 
				FROM tb_baphp_provinsi
				where tahun_anggaran = ".$tahun_anggaran." 
				and nama_barang in (
					select distinct nama_barang from tb_jenis_barang_provinsi
						where 1=1";
						$qry .= " AND tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;
			
			if($list_id_penyedia != ''){

				for($i=0; $i<count($list_id_penyedia); $i++){
					if($i == 0)
						$qry .= " and ( id_penyedia_provinsi = ".$list_id_penyedia[$i];
					else
						$qry .= " or id_penyedia_provinsi = ".$list_id_penyedia[$i];
				}

				$qry .= ")";
			}

			$qry .= ")";

			if($list_id_provinsi != ''){

				for($i=0; $i<count($list_id_provinsi); $i++){
					if($i == 0)
						$qry .= "  and ( id_provinsi_penerima = ".$list_id_provinsi[$i];
					else
						$qry .= " or id_provinsi_penerima = ".$list_id_provinsi[$i];
				}

				$qry .= ")";
			}
			
			if($list_id_kabupaten != ''){

				for($i=0; $i<count($list_id_kabupaten); $i++){
					if($i == 0)
						$qry .= " and ( id_kabupaten_penerima = ".$list_id_kabupaten[$i];
					else
						$qry .= " or id_kabupaten_penerima = ".$list_id_kabupaten[$i];
				}

				$qry .= ")";

			}

			// die($qry);

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}

		function GetTotalBASTB($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia)
		{
			$qry =	"	
				SELECT COUNT(DISTINCT(id)) AS total 
				FROM tb_bastb_provinsi 
				where tahun_anggaran = ".$tahun_anggaran." 
				and nama_barang in (
					select distinct nama_barang from tb_jenis_barang_provinsi
						where 1=1";
						$qry .= " AND tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;
			
			if($list_id_penyedia != ''){

				for($i=0; $i<count($list_id_penyedia); $i++){
					if($i == 0)
						$qry .= " and ( id_penyedia_provinsi = ".$list_id_penyedia[$i];
					else
						$qry .= " or id_penyedia_provinsi = ".$list_id_penyedia[$i];
				}

				$qry .= ")";
			}

			$qry .= ")";

			if($list_id_provinsi != ''){

				for($i=0; $i<count($list_id_provinsi); $i++){
					if($i == 0)
						$qry .= "  and ( id_provinsi_penyerah = ".$list_id_provinsi[$i];
					else
						$qry .= " or id_provinsi_penyerah = ".$list_id_provinsi[$i];
				}

				$qry .= ")";
			}
			
			if($list_id_kabupaten != ''){

				for($i=0; $i<count($list_id_kabupaten); $i++){
					if($i == 0)
						$qry .= " and ( id_kabupaten_penyerah = ".$list_id_kabupaten[$i];
					else
						$qry .= " or id_kabupaten_penyerah = ".$list_id_kabupaten[$i];
				}

				$qry .= ")";

			}

			

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}
		
		function GetTotalUnitKontrak($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia)
		{
			$qry =	"	
				SELECT SUM(t.jumlah_alokasi) as total
					FROM tb_kontrak_provinsi hd INNER JOIN tb_penyedia_provinsi peny ON hd.`id_penyedia_provinsi` = peny.id
					LEFT JOIN (
						SELECT id_header, SUM(total_jumlah_temp) AS jumlah_alokasi, SUM(total_nilai_temp) AS nilai_alokasi FROM (
							SELECT hd.id AS id_header, dt.status_alokasi, (CASE 	
								WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
									SUM(dt.jumlah_barang_rev_1)
								WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
									SUM(dt.jumlah_barang_rev_2)
								ELSE
									SUM(dt.jumlah_barang)
								END) AS total_jumlah_temp,
								(CASE 	
								WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
									SUM(dt.nilai_barang_rev_1)
								WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
									SUM(dt.nilai_barang_rev_2)
								ELSE
									SUM(dt.nilai_barang)
								END) AS total_nilai_temp
						FROM tb_alokasi_provinsi dt 
						LEFT JOIN tb_kontrak_provinsi hd ON hd.id = dt.id_kontrak_provinsi
						WHERE 1=1 ";
						$qry .= " AND `tb_kontrak_provinsi.tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
			
			if($list_id_provinsi != ''){

				for($i=0; $i<count($list_id_provinsi); $i++){
					if($i == 0)
						$qry .= "  and ( hd.id_provinsi = ".$list_id_provinsi[$i];
					else
						$qry .= " or hd.id_provinsi = ".$list_id_provinsi[$i];
				}

				$qry .= ")";
			}
			
			if($list_id_kabupaten != ''){

				for($i=0; $i<count($list_id_kabupaten); $i++){
					if($i == 0)
						$qry .= " and ( id_kabupaten = ".$list_id_kabupaten[$i];
					else
						$qry .= " or id_kabupaten = ".$list_id_kabupaten[$i];
				}

				$qry .= ")";

			}

			$qry .= "
			GROUP BY id_header, dt.status_alokasi) AS temp
				GROUP BY temp.id_header
			) AS t ON hd.id = t.id_header
			WHERE 1=1";
			$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan; 

			if($list_id_penyedia != ''){

				for($i=0; $i<count($list_id_penyedia); $i++){
					if($i == 0)
						$qry .= " and ( id_penyedia_provinsi = ".$list_id_penyedia[$i];
					else
						$qry .= " or id_penyedia_provinsi = ".$list_id_penyedia[$i];
				}

				$qry .= ")";
			}

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}

		function GetTotalUnitAlokasi($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia)
		{
			$qry =	"	
				SELECT SUM(total_temp) AS total FROM (
					SELECT dt.status_alokasi, (CASE 	
						WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
							SUM(dt.jumlah_barang_rev_1)
						WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
							SUM(dt.jumlah_barang_rev_2)
						ELSE
							SUM(dt.jumlah_barang)
						END) AS total_temp
				FROM tb_alokasi_provinsi dt 
				LEFT JOIN tb_kontrak_provinsi hd ON hd.id = dt.id_kontrak_provinsi
				WHERE 
					dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan; 

			if($list_id_provinsi != ''){

				for($i=0; $i<count($list_id_provinsi); $i++){
					if($i == 0)
						$qry .= "  and ( dt.id_provinsi = ".$list_id_provinsi[$i];
					else
						$qry .= " or dt.id_provinsi = ".$list_id_provinsi[$i];
				}

				$qry .= ")";
			}
			
			if($list_id_kabupaten != ''){

				for($i=0; $i<count($list_id_kabupaten); $i++){
					if($i == 0)
						$qry .= " and ( dt.id_kabupaten = ".$list_id_kabupaten[$i];
					else
						$qry .= " or dt.id_kabupaten = ".$list_id_kabupaten[$i];
				}

				$qry .= ")";

			}

			if($list_id_penyedia != ''){

				for($i=0; $i<count($list_id_penyedia); $i++){
					if($i == 0)
						$qry .= " and ( hd.id_penyedia_provinsi = ".$list_id_penyedia[$i];
					else
						$qry .= " or hd.id_penyedia_provinsi = ".$list_id_penyedia[$i];
				}

				$qry .= ")";
			}

					
			$qry .= " GROUP BY dt.status_alokasi) AS t_temp ";
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}

		function GetTotalUnitBAPHP($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia)
		{
			$qry =	"	
				SELECT SUM(jumlah_barang) AS total 
				FROM tb_baphp_provinsi
				where tahun_anggaran = ".$tahun_anggaran." 
				and nama_barang in (
					select distinct nama_barang from tb_jenis_barang_provinsi
					where 1=1";
					$qry .= " AND tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;
			
			if($list_id_penyedia != ''){

				for($i=0; $i<count($list_id_penyedia); $i++){
					if($i == 0)
						$qry .= " and ( id_penyedia_provinsi = ".$list_id_penyedia[$i];
					else
						$qry .= " or id_penyedia_provinsi = ".$list_id_penyedia[$i];
				}

				$qry .= ")";
			}

			$qry .= ")";

			if($list_id_provinsi != ''){

				for($i=0; $i<count($list_id_provinsi); $i++){
					if($i == 0)
						$qry .= "  and ( id_provinsi_penerima = ".$list_id_provinsi[$i];
					else
						$qry .= " or id_provinsi_penerima = ".$list_id_provinsi[$i];
				}

				$qry .= ")";
			}
			
			if($list_id_kabupaten != ''){

				for($i=0; $i<count($list_id_kabupaten); $i++){
					if($i == 0)
						$qry .= " and ( id_kabupaten_penerima = ".$list_id_kabupaten[$i];
					else
						$qry .= " or id_kabupaten_penerima = ".$list_id_kabupaten[$i];
				}

				$qry .= ")";

			}

			// die($qry);

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}

		function GetTotalUnitBASTB($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia)
		{
			$qry =	"	
				SELECT SUM(jumlah_barang) AS total 
				FROM tb_bastb_provinsi 
				where tahun_anggaran = ".$tahun_anggaran." 
				and nama_barang in (
					select distinct nama_barang from tb_jenis_barang_provinsi
						where 1=1";
						$qry .= " AND tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;
			
			if($list_id_penyedia != ''){

				for($i=0; $i<count($list_id_penyedia); $i++){
					if($i == 0)
						$qry .= " and ( id_penyedia_provinsi = ".$list_id_penyedia[$i];
					else
						$qry .= " or id_penyedia_provinsi = ".$list_id_penyedia[$i];
				}

				$qry .= ")";
			}

			$qry .= ")";

			if($list_id_provinsi != ''){

				for($i=0; $i<count($list_id_provinsi); $i++){
					if($i == 0)
						$qry .= "  and ( id_provinsi_penyerah = ".$list_id_provinsi[$i];
					else
						$qry .= " or id_provinsi_penyerah = ".$list_id_provinsi[$i];
				}

				$qry .= ")";
			}
			
			if($list_id_kabupaten != ''){

				for($i=0; $i<count($list_id_kabupaten); $i++){
					if($i == 0)
						$qry .= " and ( id_kabupaten_penyerah = ".$list_id_kabupaten[$i];
					else
						$qry .= " or id_kabupaten_penyerah = ".$list_id_kabupaten[$i];
				}

				$qry .= ")";

			}

			

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}

		function GetTotalNilaiKontrak($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia)
		{
			$qry =	"	
				SELECT SUM(t.nilai_alokasi) as total
					FROM tb_kontrak_provinsi hd INNER JOIN tb_penyedia_provinsi peny ON hd.`id_penyedia_provinsi` = peny.id
					LEFT JOIN (
						SELECT id_header, SUM(total_jumlah_temp) AS jumlah_alokasi, SUM(total_nilai_temp) AS nilai_alokasi FROM (
							SELECT hd.id AS id_header, dt.status_alokasi, (CASE 	
								WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
									SUM(dt.jumlah_barang_rev_1)
								WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
									SUM(dt.jumlah_barang_rev_2)
								ELSE
									SUM(dt.jumlah_barang)
								END) AS total_jumlah_temp,
								(CASE 	
								WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
									SUM(dt.nilai_barang_rev_1)
								WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
									SUM(dt.nilai_barang_rev_2)
								ELSE
									SUM(dt.nilai_barang)
								END) AS total_nilai_temp
						FROM tb_alokasi_provinsi dt 
						LEFT JOIN tb_kontrak_provinsi hd ON hd.id = dt.id_kontrak_provinsi
						WHERE 1=1 ";
						$qry .= " AND tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;
			
			if($list_id_provinsi != ''){

				for($i=0; $i<count($list_id_provinsi); $i++){
					if($i == 0)
						$qry .= "  and ( hd.id_provinsi = ".$list_id_provinsi[$i];
					else
						$qry .= " or hd.id_provinsi = ".$list_id_provinsi[$i];
				}

				$qry .= ")";
			}
			
			if($list_id_kabupaten != ''){

				for($i=0; $i<count($list_id_kabupaten); $i++){
					if($i == 0)
						$qry .= " and ( id_kabupaten = ".$list_id_kabupaten[$i];
					else
						$qry .= " or id_kabupaten = ".$list_id_kabupaten[$i];
				}

				$qry .= ")";

			}

			$qry .= "
			GROUP BY id_header, dt.status_alokasi) AS temp
				GROUP BY temp.id_header
			) AS t ON hd.id = t.id_header
			WHERE 1=1";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan; 

			if($list_id_penyedia != ''){

				for($i=0; $i<count($list_id_penyedia); $i++){
					if($i == 0)
						$qry .= " and ( id_penyedia_provinsi = ".$list_id_penyedia[$i];
					else
						$qry .= " or id_penyedia_provinsi = ".$list_id_penyedia[$i];
				}

				$qry .= ")";
			}

			

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}

		function GetTotalNilaiAlokasi($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia)
		{
			$qry =	"	
				SELECT SUM(total_temp) AS total FROM (
					SELECT dt.status_alokasi, (CASE 	
						WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
							SUM(dt.nilai_barang_rev_1)
						WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
							SUM(dt.nilai_barang_rev_2)
						ELSE
							SUM(dt.nilai_barang)
						END) AS total_temp
				FROM tb_alokasi_provinsi dt 
				LEFT JOIN tb_kontrak_provinsi hd ON hd.id = dt.id_kontrak_provinsi
				WHERE 
					dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan; 

			if($list_id_provinsi != ''){

				for($i=0; $i<count($list_id_provinsi); $i++){
					if($i == 0)
						$qry .= "  and ( hd.id_provinsi = ".$list_id_provinsi[$i];
					else
						$qry .= " or hd.id_provinsi = ".$list_id_provinsi[$i];
				}

				$qry .= ")";
			}
			
			if($list_id_kabupaten != ''){

				for($i=0; $i<count($list_id_kabupaten); $i++){
					if($i == 0)
						$qry .= " and ( dt.id_kabupaten = ".$list_id_kabupaten[$i];
					else
						$qry .= " or dt.id_kabupaten = ".$list_id_kabupaten[$i];
				}

				$qry .= ")";

			}

			if($list_id_penyedia != ''){

				for($i=0; $i<count($list_id_penyedia); $i++){
					if($i == 0)
						$qry .= " and ( hd.id_penyedia_provinsi = ".$list_id_penyedia[$i];
					else
						$qry .= " or hd.id_penyedia_provinsi = ".$list_id_penyedia[$i];
				}

				$qry .= ")";
			}

					
			$qry .= " GROUP BY dt.status_alokasi) AS t_temp ";
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}

		function GetTotalNilaiBAPHP($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia)
		{
			$qry =	"	
				SELECT SUM(nilai_barang) AS total 
				FROM tb_baphp_provinsi
				where tahun_anggaran = ".$tahun_anggaran." 
				and nama_barang in (
					select distinct nama_barang from tb_jenis_barang_provinsi
						where 1=1";
						$qry .= " AND tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;
			
			if($list_id_penyedia != ''){

				for($i=0; $i<count($list_id_penyedia); $i++){
					if($i == 0)
						$qry .= " and ( id_penyedia_provinsi = ".$list_id_penyedia[$i];
					else
						$qry .= " or id_penyedia_provinsi = ".$list_id_penyedia[$i];
				}

				$qry .= ")";
			}

			$qry .= ")";

			if($list_id_provinsi != ''){

				for($i=0; $i<count($list_id_provinsi); $i++){
					if($i == 0)
						$qry .= "  and ( id_provinsi_penerima = ".$list_id_provinsi[$i];
					else
						$qry .= " or id_provinsi_penerima = ".$list_id_provinsi[$i];
				}

				$qry .= ")";
			}
			
			if($list_id_kabupaten != ''){

				for($i=0; $i<count($list_id_kabupaten); $i++){
					if($i == 0)
						$qry .= " and ( id_kabupaten_penerima = ".$list_id_kabupaten[$i];
					else
						$qry .= " or id_kabupaten_penerima = ".$list_id_kabupaten[$i];
				}

				$qry .= ")";

			}

			

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}

		function GetTotalNilaiBASTB($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia)
		{
			$qry =	"	
				SELECT SUM(nilai_barang) AS total 
				FROM tb_bastb_provinsi 
				where tahun_anggaran = ".$tahun_anggaran." 
				and nama_barang in (
					select distinct nama_barang from tb_jenis_barang_provinsi
						where 1=1";
						$qry .= " AND tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;
			
			if($list_id_penyedia != ''){

				for($i=0; $i<count($list_id_penyedia); $i++){
					if($i == 0)
						$qry .= " and ( id_penyedia_provinsi = ".$list_id_penyedia[$i];
					else
						$qry .= " or id_penyedia_provinsi = ".$list_id_penyedia[$i];
				}

				$qry .= ")";
			}

			$qry .= ")";

			if($list_id_provinsi != ''){

				for($i=0; $i<count($list_id_provinsi); $i++){
					if($i == 0)
						$qry .= "  and ( id_provinsi_penyerah = ".$list_id_provinsi[$i];
					else
						$qry .= " or id_provinsi_penyerah = ".$list_id_provinsi[$i];
				}

				$qry .= ")";
			}
			
			if($list_id_kabupaten != ''){

				for($i=0; $i<count($list_id_kabupaten); $i++){
					if($i == 0)
						$qry .= " and ( id_kabupaten_penyerah = ".$list_id_kabupaten[$i];
					else
						$qry .= " or id_kabupaten_penyerah = ".$list_id_kabupaten[$i];
				}

				$qry .= ")";

			}

			

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}


		// **************************************************************************************************************************************
		// LAPORAN PROVINSI *********************************************************************************************************************
		// /*************************************************************************************************************************************

		function GetAllAjaxCountProvUnit($list_nama_barang = '')
		{
			$qry = "
				SELECT dt.`id_provinsi`, pro.`nama_provinsi`, 
				SUM(CASE 	
				WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
					dt.jumlah_barang_rev_1
				WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
					dt.jumlah_barang_rev_2
				ELSE
					dt.jumlah_barang
				END) AS total_unit,
				SUM(CASE 	
				WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
					dt.nilai_barang_rev_1
				WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
					dt.nilai_barang_rev_2
				ELSE
					dt.nilai_barang
				END) AS total_nilai,
				alo.total_unit AS total_unit_alokasi,
				alo.total_nilai AS total_nilai_alokasi,
				bap_reg.total_barang AS total_unit_bapreg,
				bap_reg.total_nilai AS total_nilai_bapreg,
				bas.total_barang AS total_unit_bastb,
				bas.total_nilai AS total_nilai_bastb
				FROM
				tb_alokasi_provinsi dt
				LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
				LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
				LEFT JOIN (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, 
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang
					END) AS total_unit,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang
					END) AS total_nilai
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan; 

				if($list_nama_barang != ''){

					for($i=0; $i<count($list_nama_barang); $i++){
						if($i == 0)
							$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
						else
							$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
					}

					$qry .= ")";

				}
				$qry .= "
					GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`
				) AS alo ON alo.id_provinsi = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_provinsi_penerima, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM tb_baphp_provinsi";
				if($list_nama_barang != ''){

					for($i=0; $i<count($list_nama_barang); $i++){
						if($i == 0)
							$qry .= " where ( nama_barang = '".$list_nama_barang[$i]."'";
						else
							$qry .= " or nama_barang = '".$list_nama_barang[$i]."'";
					}

					$qry .= ")";

				}
				$qry .= " GROUP BY id_provinsi_penerima
				) AS bap_reg ON bap_reg.id_provinsi_penerima = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_provinsi_penyerah, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_bastb_provinsi`";
				if($list_nama_barang != ''){

					for($i=0; $i<count($list_nama_barang); $i++){
						if($i == 0)
							$qry .= " where ( nama_barang = '".$list_nama_barang[$i]."'";
						else
							$qry .= " or nama_barang = '".$list_nama_barang[$i]."'";
					}

					$qry .= ")";

				}
				$qry .= "
					GROUP BY id_provinsi_penyerah
				) AS bas ON bas.id_provinsi_penyerah = dt.`id_provinsi`
				WHERE 1=1";
				$qry .= " AND tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

			if($list_nama_barang != ''){

				for($i=0; $i<count($list_nama_barang); $i++){
					if($i == 0)
						$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
					else
						$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
				}

				$qry .= ")";

			}

			$qry .= " GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`";
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetSearchAjaxCountProvUnit($search, $filter = '', $list_nama_barang = '')
		{
			$qry = "
				SELECT dt.`id_provinsi`, pro.`nama_provinsi`, 
				SUM(CASE 	
				WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
					dt.jumlah_barang_rev_1
				WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
					dt.jumlah_barang_rev_2
				ELSE
					dt.jumlah_barang
				END) AS total_unit,
				SUM(CASE 	
				WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
					dt.nilai_barang_rev_1
				WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
					dt.nilai_barang_rev_2
				ELSE
					dt.nilai_barang
				END) AS total_nilai,
				alo.total_unit AS total_unit_alokasi,
				alo.total_nilai AS total_nilai_alokasi,
				bap_reg.total_barang AS total_unit_bapreg,
				bap_reg.total_nilai AS total_nilai_bapreg,
				bas.total_barang AS total_unit_bastb,
				bas.total_nilai AS total_nilai_bastb
				FROM
				tb_alokasi_provinsi dt
				LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
				LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
				LEFT JOIN (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, 
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang
					END) AS total_unit,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang
					END) AS total_nilai
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan; 

				if($list_nama_barang != ''){

					for($i=0; $i<count($list_nama_barang); $i++){
						if($i == 0)
							$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
						else
							$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
					}

					$qry .= ")";

				}
				$qry .= "
					GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`
				) AS alo ON alo.id_provinsi = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_provinsi_penerima, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM tb_baphp_provinsi";
				if($list_nama_barang != ''){

					for($i=0; $i<count($list_nama_barang); $i++){
						if($i == 0)
							$qry .= " where ( nama_barang = '".$list_nama_barang[$i]."'";
						else
							$qry .= " or nama_barang = '".$list_nama_barang[$i]."'";
					}

					$qry .= ")";

				}
				$qry .= " GROUP BY id_provinsi_penerima
				) AS bap_reg ON bap_reg.id_provinsi_penerima = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_provinsi_penyerah, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_bastb_provinsi`";
				if($list_nama_barang != ''){

					for($i=0; $i<count($list_nama_barang); $i++){
						if($i == 0)
							$qry .= " where ( nama_barang = '".$list_nama_barang[$i]."'";
						else
							$qry .= " or nama_barang = '".$list_nama_barang[$i]."'";
					}

					$qry .= ")";

				}
				$qry .= "
					GROUP BY id_provinsi_penyerah
				) AS bas ON bas.id_provinsi_penyerah = dt.`id_provinsi`
				WHERE 1=1";

			if($list_nama_barang != ''){

				for($i=0; $i<count($list_nama_barang); $i++){
					if($i == 0)
						$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
					else
						$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
				}

				$qry .= ")";

			}

			$qry .= "
				and (
					pro.nama_provinsi LIKE '%$search%'
				)
			";

			$qry .= $filter;

			$qry .= ' GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`';
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetFilterAjaxCountProvUnit($filter = '', $list_nama_barang = '')
		{
			$qry = "
				SELECT dt.`id_provinsi`, pro.`nama_provinsi`, 
				SUM(CASE 	
				WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
					dt.jumlah_barang_rev_1
				WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
					dt.jumlah_barang_rev_2
				ELSE
					dt.jumlah_barang
				END) AS total_unit,
				SUM(CASE 	
				WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
					dt.nilai_barang_rev_1
				WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
					dt.nilai_barang_rev_2
				ELSE
					dt.nilai_barang
				END) AS total_nilai,
				alo.total_unit AS total_unit_alokasi,
				alo.total_nilai AS total_nilai_alokasi,
				SUM(bap_reg.total_barang) AS total_unit_bapreg,
				SUM(bap_reg.total_nilai) AS total_nilai_bapreg,
				SUM(bas.total_barang) AS total_unit_bastb,
				SUM(bas.total_nilai) AS total_nilai_bastb
				FROM
				tb_alokasi_provinsi dt
				LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
				LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
				LEFT JOIN (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, 
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang
					END) AS total_unit,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang
					END) AS total_nilai
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan; 

				if($list_nama_barang != ''){

					for($i=0; $i<count($list_nama_barang); $i++){
						if($i == 0)
							$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
						else
							$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
					}

					$qry .= ")";

				}
				$qry .= "
					GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`
				) AS alo ON alo.id_provinsi = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_provinsi_penerima, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM tb_baphp_provinsi";
				if($list_nama_barang != ''){

					for($i=0; $i<count($list_nama_barang); $i++){
						if($i == 0)
							$qry .= " where ( nama_barang = '".$list_nama_barang[$i]."'";
						else
							$qry .= " or nama_barang = '".$list_nama_barang[$i]."'";
					}

					$qry .= ")";

				}
				$qry .= " GROUP BY id_provinsi_penerima
				) AS bap_reg ON bap_reg.id_provinsi_penerima = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_provinsi_penyerah, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_bastb_provinsi`";
				if($list_nama_barang != ''){

					for($i=0; $i<count($list_nama_barang); $i++){
						if($i == 0)
							$qry .= " where ( nama_barang = '".$list_nama_barang[$i]."'";
						else
							$qry .= " or nama_barang = '".$list_nama_barang[$i]."'";
					}

					$qry .= ")";

				}
				$qry .= "
					GROUP BY id_provinsi_penyerah
				) AS bas ON bas.id_provinsi_penyerah = dt.`id_provinsi`
				WHERE 1=1";

			if($list_nama_barang != ''){

				for($i=0; $i<count($list_nama_barang); $i++){
					if($i == 0)
						$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
					else
						$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
				}

				$qry .= ")";

			}

			$qry .= $filter;
			
			$qry .= ' GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`';

			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetAllForAjaxProvUnit($start, $length, $col, $dir, $filter = '', $list_nama_barang = '')
		{

			$qry = "
				SELECT dt.`id_provinsi`, pro.`nama_provinsi`, 
				SUM(CASE 	
				WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
					dt.jumlah_barang_rev_1
				WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
					dt.jumlah_barang_rev_2
				WHEN dt.status_alokasi = 'DATA ADDENDUM 3' THEN
					dt.jumlah_barang_rev_3
				ELSE
					dt.jumlah_barang
				END) AS total_unit,
				SUM(CASE 	
				WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
					dt.nilai_barang_rev_1
				WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
					dt.nilai_barang_rev_2			
				WHEN dt.status_alokasi = 'DATA ADDENDUM 3' THEN
					dt.nilai_barang_rev_3
				ELSE
					dt.nilai_barang
				END) AS total_nilai,
				alo.total_unit AS total_unit_alokasi,
				alo.total_nilai AS total_nilai_alokasi,
				bap_reg.total_barang AS total_unit_bapreg,
				bap_reg.total_nilai AS total_nilai_bapreg,
				bas.total_barang AS total_unit_bastb,
				bas.total_nilai AS total_nilai_bastb
				FROM
				tb_alokasi_provinsi dt
				LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
				LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
				LEFT JOIN (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, id_kontrak_provinsi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang
					END) AS total_unit,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang
					END) AS total_nilai
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
				$qry .= "
					GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`
				) AS alo ON alo.id_kontrak_provinsi = hd.id
				LEFT JOIN (
					SELECT id_provinsi_penerima, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM tb_baphp_provinsi";
				$qry .= " GROUP BY id_provinsi_penerima
				) AS bap_reg ON bap_reg.id_provinsi_penerima = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_provinsi_penyerah, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_bastb_provinsi`";
				$qry .= "
					GROUP BY id_provinsi_penyerah
				) AS bas ON bas.id_provinsi_penyerah = dt.`id_provinsi`
				WHERE 1=1";

			$qry .= $filter;

			$qry .= ' GROUP BY dt.`id_provinsi`, pro.`nama_provinsi` ORDER BY '.$col.' '.$dir;
			if($length > 0)
				$qry .=	' LIMIT ' . $start . ',' . $length;

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetSearchAjaxProvUnit($start, $length, $search, $col, $dir, $filter = '', $list_nama_barang = '')
		{

			$qry = "
				SELECT dt.`id_provinsi`, pro.`nama_provinsi`, 
				SUM(CASE 	
				WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
					dt.jumlah_barang_rev_1
				WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
					dt.jumlah_barang_rev_2
				ELSE
					dt.jumlah_barang
				END) AS total_unit,
				SUM(CASE 	
				WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
					dt.nilai_barang_rev_1
				WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
					dt.nilai_barang_rev_2
				ELSE
					dt.nilai_barang
				END) AS total_nilai,
				alo.total_unit AS total_unit_alokasi,
				alo.total_nilai AS total_nilai_alokasi,
				SUM(bap_reg.total_barang) AS total_unit_bapreg,
				SUM(bap_reg.total_nilai) AS total_nilai_bapreg,
				SUM(bas.total_barang) AS total_unit_bastb,
				SUM(bas.total_nilai) AS total_nilai_bastb
				FROM
				tb_alokasi_provinsi dt
				LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
				LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
				LEFT JOIN (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, 
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang
					END) AS total_unit,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang
					END) AS total_nilai
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan; 

				if($list_nama_barang != ''){

					for($i=0; $i<count($list_nama_barang); $i++){
						if($i == 0)
							$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
						else
							$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
					}

					$qry .= ")";

				}
				$qry .= "
					GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`
				) AS alo ON alo.id_provinsi = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_provinsi_penerima, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM tb_baphp_provinsi";
				if($list_nama_barang != ''){

					for($i=0; $i<count($list_nama_barang); $i++){
						if($i == 0)
							$qry .= " where ( nama_barang = '".$list_nama_barang[$i]."'";
						else
							$qry .= " or nama_barang = '".$list_nama_barang[$i]."'";
					}

					$qry .= ")";

				}
				$qry .= " GROUP BY id_provinsi_penerima
				) AS bap_reg ON bap_reg.id_provinsi_penerima = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_provinsi_penyerah, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_bastb_provinsi`";
				if($list_nama_barang != ''){

					for($i=0; $i<count($list_nama_barang); $i++){
						if($i == 0)
							$qry .= " where ( nama_barang = '".$list_nama_barang[$i]."'";
						else
							$qry .= " or nama_barang = '".$list_nama_barang[$i]."'";
					}

					$qry .= ")";

				}
				$qry .= "
					GROUP BY id_provinsi_penyerah
				) AS bas ON bas.id_provinsi_penyerah = dt.`id_provinsi`
				WHERE 1=1";

			if($list_nama_barang != ''){

				for($i=0; $i<count($list_nama_barang); $i++){
					if($i == 0)
						$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
					else
						$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
				}

				$qry .= ")";

			}
			
			$qry .= " and (
						pro.nama_provinsi LIKE '%$search%'
				)
			";

			$qry .= $filter;

			$qry .= ' GROUP BY dt.`id_provinsi`, pro.`nama_provinsi` ORDER BY '.$col.' '.$dir;
			if($length > 0)
				$qry .=	' LIMIT ' . $start . ',' . $length;
			
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function getProvinsi($param = null){
			if(empty($param['col'])) $param['col'] = 'nama_provinsi';
			if(empty($param['dir'])) $param['dir'] = 'ASC';
			if(empty($param['filter'])) $param['filter'] = '';
			if(empty($param['length'])) $param['length'] = 0;

			$qry = "SELECT nama_provinsi, alo.jumlah_barang alokasi_provinsi, bap.jumlah_barang baphp_provinsi, bas.jumlah_barang bastb_provinsi
			, alo.nilai_barang alokasi_provinsi_nilai, bap.nilai_barang baphp_provinsi_nilai, bas.nilai_barang bastb_provinsi_nilai
			FROM tb_provinsi pro
			LEFT JOIN (
			SELECT alo.id_provinsi, SUM(CASE 	
					WHEN alo.status_alokasi = 'DATA KONTRAK AWAL' THEN
						alo.jumlah_barang
					WHEN alo.status_alokasi = 'DATA ADDENDUM 1' THEN
						alo.jumlah_barang_rev_1
					WHEN alo.status_alokasi = 'DATA ADDENDUM 2' THEN
						alo.jumlah_barang_rev_2
					WHEN alo.status_alokasi = 'DATA ADDENDUM 3' THEN
						alo.jumlah_barang_rev_3
					END) AS jumlah_barang, SUM(CASE 	
					WHEN alo.status_alokasi = 'DATA KONTRAK AWAL' THEN
						alo.nilai_barang
					WHEN alo.status_alokasi = 'DATA ADDENDUM 1' THEN
						alo.nilai_barang_rev_1
					WHEN alo.status_alokasi = 'DATA ADDENDUM 2' THEN
						alo.nilai_barang_rev_2
					WHEN alo.status_alokasi = 'DATA ADDENDUM 3' THEN
						alo.nilai_barang_rev_3
					END) AS nilai_barang
					FROM tb_alokasi_provinsi alo 
					INNER JOIN tb_kontrak_provinsi kon ON alo.id_kontrak_provinsi=kon.id AND tahun_anggaran=".$this->session->userdata('logged_in')->tahun_pengadaan."
					GROUP BY id_provinsi
			)alo ON alo.id_provinsi = pro.id
			LEFT JOIN (
			SELECT id_provinsi_penerima, SUM(jumlah_barang) jumlah_barang, SUM(nilai_barang) nilai_barang
			FROM tb_baphp_provinsi
			WHERE tahun_anggaran=".$this->session->userdata('logged_in')->tahun_pengadaan."
			GROUP BY id_provinsi_penerima
			)bap ON bap.id_provinsi_penerima=pro.id
			LEFT JOIN (
			SELECT id_provinsi_penyerah, SUM(jumlah_barang) jumlah_barang, SUM(nilai_barang) nilai_barang
			FROM tb_bastb_provinsi
			WHERE tahun_anggaran=".$this->session->userdata('logged_in')->tahun_pengadaan."
			GROUP BY id_provinsi_penyerah
			)bas ON bas.id_provinsi_penyerah=pro.id
			WHERE (alo.jumlah_barang > 0)";
			if(isset($param['search'])) $qry .= " AND nama_provinsi LIKE '%".$param['search']."%'";
			$qry .= $param['filter']."
			ORDER BY ".$param['col']." ".$param['dir'];
			if($param['length'] > 0)
				$qry .=	' LIMIT ' . $param['start'] . ',' . $param['length'];
			
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		// **************************************************************************************************************************************
		// LAPORAN KABUPATEN *********************************************************************************************************************
		// /*************************************************************************************************************************************

		function GetAllAjaxCountKabupaten($list_nama_barang = '', $list_id_provinsi = '')
		{
			$qry = "
				SELECT uni.id_provinsi, uni.nama_provinsi, uni.nama_kabupaten, 
				SUM(total_unit) AS total_unit, SUM(total_nilai) AS total_nilai,
				SUM(total_unit_alokasi) AS total_unit_alokasi, SUM(total_nilai_alokasi) AS total_nilai_alokasi,
				SUM(total_unit_baphp) AS total_unit_baphp, SUM(total_nilai_baphp) AS total_nilai_baphp,
				SUM(total_unit_bastb) AS total_unit_bastb, SUM(total_nilai_bastb) AS total_nilai_bastb
				FROM (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang
					END) AS total_unit,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang
					END) AS total_nilai,
					0 AS total_unit_alokasi,
					0 AS total_nilai_alokasi,
					0 AS total_unit_baphp,
					0 AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					WHERE 1=1";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;
					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_provinsi = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or dt.id_provinsi = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}
			$qry .= " GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`, dt.`id_kabupaten`, kab.`nama_kabupaten`

					UNION

					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					0 AS total_unit, 0 AS total_nilai,  
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang
					END) AS total_unit_alokasi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang
					END) AS total_nilai_alokasi,
					0 AS total_unit_baphp,
					0 AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan; 
					
					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_provinsi = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or dt.id_provinsi = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

			$qry .= " GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`, dt.`id_kabupaten`, kab.`nama_kabupaten`
					
					UNION
					
					SELECT bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					SUM(bap.jumlah_barang) AS total_unit_baphp, SUM(bap.`nilai_barang`) AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_baphp_provinsi bap
					LEFT JOIN tb_kontrak_provinsi kon ON bap.`no_kontrak` = kon.`no_kontrak`
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					WHERE 1=1";
					$qry .= " AND kon.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( bap.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or bap.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( bap.id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or bap.id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

			$qry .= " GROUP BY bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten
					
					UNION
					
					SELECT bas.id_provinsi_penyerah, pro.`nama_provinsi`, bas.id_kabupaten_penyerah, kab.nama_kabupaten,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					0 AS total_unit_baphp, 0 AS total_nilai_baphp,
					SUM(bas.jumlah_barang) AS total_unit_bastb, SUM(bas.`nilai_barang`) AS total_nilai_bastb
					FROM `tb_bastb_provinsi` bas
					LEFT JOIN tb_kontrak_provinsi kon ON bas.`no_kontrak` = kon.`no_kontrak`
					LEFT JOIN tb_provinsi pro ON bas.`id_provinsi_penyerah` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bas.`id_kabupaten_penyerah` = kab.`id`
					WHERE 1=1";
					$qry .= " AND kon.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( bas.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or bas.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( bas.id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or bas.id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}
			$qry .= " GROUP BY bas.id_provinsi_penyerah, pro.`nama_provinsi`, bas.id_kabupaten_penyerah, kab.nama_kabupaten
				) AS uni
				WHERE 1=1 ";

				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and uni.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and uni.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
				}

			$qry .= " GROUP BY uni.id_provinsi, uni.nama_provinsi, uni.nama_kabupaten";
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetSearchAjaxCountKabupaten($search, $filter = '', $list_nama_barang = '', $list_id_provinsi = '')
		{
			$qry = "
			SELECT uni.id_provinsi, uni.nama_provinsi, uni.nama_kabupaten, 
			SUM(total_unit) AS total_unit, SUM(total_nilai) AS total_nilai,
			SUM(total_unit_alokasi) AS total_unit_alokasi, SUM(total_nilai_alokasi) AS total_nilai_alokasi,
			SUM(total_unit_baphp) AS total_unit_baphp, SUM(total_nilai_baphp) AS total_nilai_baphp,
			SUM(total_unit_bastb) AS total_unit_bastb, SUM(total_nilai_bastb) AS total_nilai_bastb
			FROM (
				SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
				SUM(CASE 	
				WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
					dt.jumlah_barang_rev_1
				WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
					dt.jumlah_barang_rev_2
				ELSE
					dt.jumlah_barang
				END) AS total_unit,
				SUM(CASE 	
				WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
					dt.nilai_barang_rev_1
				WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
					dt.nilai_barang_rev_2
				ELSE
					dt.nilai_barang
				END) AS total_nilai,
				0 AS total_unit_alokasi,
				0 AS total_nilai_alokasi,
				0 AS total_unit_baphp,
				0 AS total_nilai_baphp,
				0 AS total_unit_bastb, 0 AS total_nilai_bastb
				FROM
				tb_alokasi_provinsi dt
				LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
				LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
				LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
				WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
				$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;
				if($list_nama_barang != ''){

					for($i=0; $i<count($list_nama_barang); $i++){
						if($i == 0)
							$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
						else
							$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
					}

					$qry .= ")";

				}

				if($list_id_provinsi != ''){

					for($i=0; $i<count($list_id_provinsi); $i++){
						if($i == 0)
							$qry .= " and ( dt.id_provinsi = '".$list_id_provinsi[$i]."'";
						else
							$qry .= " or dt.id_provinsi = '".$list_id_provinsi[$i]."'";
					}

					$qry .= ")";

				}
		$qry .= " GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`, dt.`id_kabupaten`, kab.`nama_kabupaten`

				UNION

				SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
				0 AS total_unit, 0 AS total_nilai,  
				SUM(CASE 	
				WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
					dt.jumlah_barang_rev_1
				WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
					dt.jumlah_barang_rev_2
				ELSE
					dt.jumlah_barang
				END) AS total_unit_alokasi,
				SUM(CASE 	
				WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
					dt.nilai_barang_rev_1
				WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
					dt.nilai_barang_rev_2
				ELSE
					dt.nilai_barang
				END) AS total_nilai_alokasi,
				0 AS total_unit_baphp,
				0 AS total_nilai_baphp,
				0 AS total_unit_bastb, 0 AS total_nilai_bastb
				FROM
				tb_alokasi_provinsi dt
				LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
				LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
				LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
				WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
				$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan; 
				
				if($list_nama_barang != ''){

					for($i=0; $i<count($list_nama_barang); $i++){
						if($i == 0)
							$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
						else
							$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
					}

					$qry .= ")";

				}

				if($list_id_provinsi != ''){

					for($i=0; $i<count($list_id_provinsi); $i++){
						if($i == 0)
							$qry .= " and ( dt.id_provinsi = '".$list_id_provinsi[$i]."'";
						else
							$qry .= " or dt.id_provinsi = '".$list_id_provinsi[$i]."'";
					}

					$qry .= ")";

				}

		$qry .= " GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`, dt.`id_kabupaten`, kab.`nama_kabupaten`
				
				UNION
				
				SELECT bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten,
				0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
				SUM(bap.jumlah_barang) AS total_unit_baphp, SUM(bap.`nilai_barang`) AS total_nilai_baphp,
				0 AS total_unit_bastb, 0 AS total_nilai_bastb
				FROM tb_baphp_provinsi bap
				LEFT JOIN tb_kontrak_provinsi kon ON bap.`no_kontrak` = kon.`no_kontrak`
				LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
				LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
				WHERE 1=1";
				$qry .= " AND kon.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

				if($list_nama_barang != ''){

					for($i=0; $i<count($list_nama_barang); $i++){
						if($i == 0)
							$qry .= " and ( bap.nama_barang = '".$list_nama_barang[$i]."'";
						else
							$qry .= " or bap.nama_barang = '".$list_nama_barang[$i]."'";
					}

					$qry .= ")";

				}

				if($list_id_provinsi != ''){

					for($i=0; $i<count($list_id_provinsi); $i++){
						if($i == 0)
							$qry .= " and ( bap.id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
						else
							$qry .= " or bap.id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
					}

					$qry .= ")";

				}

		$qry .= " GROUP BY bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten
				
				UNION
				
				SELECT bas.id_provinsi_penyerah, pro.`nama_provinsi`, bas.id_kabupaten_penyerah, kab.nama_kabupaten,
				0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
				0 AS total_unit_baphp, 0 AS total_nilai_baphp,
				SUM(bas.jumlah_barang) AS total_unit_bastb, SUM(bas.`nilai_barang`) AS total_nilai_bastb
				FROM `tb_bastb_provinsi` bas
				LEFT JOIN tb_kontrak_provinsi kon ON kon.`no_kontrak` = bas.`no_kontrak`
				LEFT JOIN tb_provinsi pro ON bas.`id_provinsi_penyerah` = pro.`id`
				LEFT JOIN tb_kabupaten kab ON bas.`id_kabupaten_penyerah` = kab.`id`
				WHERE 1=1";
				$qry .= " AND kon.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

				if($list_nama_barang != ''){

					for($i=0; $i<count($list_nama_barang); $i++){
						if($i == 0)
							$qry .= " and ( bas.nama_barang = '".$list_nama_barang[$i]."'";
						else
							$qry .= " or bas.nama_barang = '".$list_nama_barang[$i]."'";
					}

					$qry .= ")";

				}

				if($list_id_provinsi != ''){

					for($i=0; $i<count($list_id_provinsi); $i++){
						if($i == 0)
							$qry .= " and ( bas.id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
						else
							$qry .= " or bas.id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
					}

					$qry .= ")";

				}
		$qry .= " GROUP BY bas.id_provinsi_penyerah, pro.`nama_provinsi`, bas.id_kabupaten_penyerah, kab.nama_kabupaten
			) AS uni
			WHERE 1=1 ";

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and uni.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}
			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					and uni.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
			}

			$qry .= " and (
					uni.nama_provinsi LIKE '%$search%'
					or uni.nama_kabupaten LIKE '%$search%'
				)";

			$qry .= $filter;
 
			$qry .= " GROUP BY uni.id_provinsi, uni.nama_provinsi, uni.nama_kabupaten";
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetFilterAjaxCountKabupaten($filter = '', $list_nama_barang = '', $list_id_provinsi = '')
		{
			$qry = "
				SELECT uni.id_provinsi, uni.nama_provinsi, uni.nama_kabupaten, 
				SUM(total_unit) AS total_unit, SUM(total_nilai) AS total_nilai,
				SUM(total_unit_alokasi) AS total_unit_alokasi, SUM(total_nilai_alokasi) AS total_nilai_alokasi,
				SUM(total_unit_baphp) AS total_unit_baphp, SUM(total_nilai_baphp) AS total_nilai_baphp,
				SUM(total_unit_bastb) AS total_unit_bastb, SUM(total_nilai_bastb) AS total_nilai_bastb
				FROM (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang
					END) AS total_unit,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang
					END) AS total_nilai,
					0 AS total_unit_alokasi,
					0 AS total_nilai_alokasi,
					0 AS total_unit_baphp,
					0 AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					WHERE 1=1";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;
					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_provinsi = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or dt.id_provinsi = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}
			$qry .= " GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`, dt.`id_kabupaten`, kab.`nama_kabupaten`

					UNION

					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					0 AS total_unit, 0 AS total_nilai,  
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang
					END) AS total_unit_alokasi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang
					END) AS total_nilai_alokasi,
					0 AS total_unit_baphp,
					0 AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan; 
					
					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_provinsi = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or dt.id_provinsi = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

			$qry .= " GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`, dt.`id_kabupaten`, kab.`nama_kabupaten`
					
					UNION
					
					SELECT bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					SUM(jumlah_barang) AS total_unit_baphp, SUM(`nilai_barang`) AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_baphp_provinsi bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					WHERE 1=1";
					$qry .= " AND tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( bap.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or bap.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( bap.id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or bap.id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

			$qry .= " GROUP BY bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten
					
					UNION
					
					SELECT bas.id_provinsi_penyerah, pro.`nama_provinsi`, bas.id_kabupaten_penyerah, kab.nama_kabupaten,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					0 AS total_unit_baphp, 0 AS total_nilai_baphp,
					SUM(jumlah_barang) AS total_unit_bastb, SUM(`nilai_barang`) AS total_nilai_bastb
					FROM `tb_bastb_provinsi` bas
					LEFT JOIN tb_provinsi pro ON bas.`id_provinsi_penyerah` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bas.`id_kabupaten_penyerah` = kab.`id`
					WHERE 1=1";
					$qry .= " AND tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( bas.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or bas.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( bas.id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or bas.id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}
			$qry .= " GROUP BY bas.id_provinsi_penyerah, pro.`nama_provinsi`, bas.id_kabupaten_penyerah, kab.nama_kabupaten
				) AS uni
				WHERE 1=1 ";

				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and uni.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and uni.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				// if(isset($this->session->userdata('logged_in')->id_penyedia_provinsi)){
				// 	$qry .= "
				// 		and uni.id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi;
				// }

			$qry .= " GROUP BY uni.id_provinsi, uni.nama_provinsi, uni.nama_kabupaten";

			$qry .= $filter;

			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetAllForAjaxKabupaten($start, $length, $col, $dir, $filter = '', $list_nama_barang = '', $list_id_provinsi = '')
		{

			$qry = "
				SELECT uni.id_provinsi, uni.nama_provinsi, uni.nama_kabupaten, 
				SUM(total_unit) AS total_unit, SUM(total_nilai) AS total_nilai,
				SUM(total_unit_alokasi) AS total_unit_alokasi, SUM(total_nilai_alokasi) AS total_nilai_alokasi,
				SUM(total_unit_baphp) AS total_unit_baphp, SUM(total_nilai_baphp) AS total_nilai_baphp,
				SUM(total_unit_bastb) AS total_unit_bastb, SUM(total_nilai_bastb) AS total_nilai_bastb
				FROM (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang
					END) AS total_unit,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang
					END) AS total_nilai,
					0 AS total_unit_alokasi,
					0 AS total_nilai_alokasi,
					0 AS total_unit_baphp,
					0 AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;
					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_provinsi = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or dt.id_provinsi = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}
			$qry .= " GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`, dt.`id_kabupaten`, kab.`nama_kabupaten`

					UNION

					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					0 AS total_unit, 0 AS total_nilai,  
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang
					END) AS total_unit_alokasi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang
					END) AS total_nilai_alokasi,
					0 AS total_unit_baphp,
					0 AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan; 
					
					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_provinsi = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or dt.id_provinsi = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

			$qry .= " GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`, dt.`id_kabupaten`, kab.`nama_kabupaten`
					
					UNION
					
					SELECT bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					SUM(bap.jumlah_barang) AS total_unit_baphp, SUM(bap.`nilai_barang`) AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_baphp_provinsi bap
					LEFT JOIN tb_kontrak_provinsi kon ON bap.`no_kontrak` = kon.`no_kontrak`
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					WHERE 1=1";
					$qry .= " AND kon.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( bap.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or bap.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( bap.id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or bap.id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

			$qry .= " GROUP BY bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten
					
					UNION
					
					SELECT bas.id_provinsi_penyerah, pro.`nama_provinsi`, bas.id_kabupaten_penyerah, kab.nama_kabupaten,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					0 AS total_unit_baphp, 0 AS total_nilai_baphp,
					SUM(bas.jumlah_barang) AS total_unit_bastb, SUM(bas.`nilai_barang`) AS total_nilai_bastb
					FROM `tb_bastb_provinsi` bas
					LEFT JOIN tb_kontrak_provinsi kon ON kon.`no_kontrak` = bas.`no_kontrak`
					LEFT JOIN tb_provinsi pro ON bas.`id_provinsi_penyerah` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bas.`id_kabupaten_penyerah` = kab.`id`
					WHERE 1=1";
					$qry .= " AND kon.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( bas.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or bas.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( bas.id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or bas.id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}
			$qry .= " GROUP BY bas.id_provinsi_penyerah, pro.`nama_provinsi`, bas.id_kabupaten_penyerah, kab.nama_kabupaten
				) AS uni
				WHERE 1=1 ";

				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and uni.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and uni.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				// if(isset($this->session->userdata('logged_in')->id_penyedia_provinsi)){
				// 	$qry .= "
				// 		and uni.id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi;
				// }

			$qry .= " GROUP BY uni.id_provinsi, uni.nama_provinsi, uni.nama_kabupaten";


			$qry .= $filter;

			$qry .= ' ORDER BY '.$col.' '.$dir;
			if($length > 0)
				$qry .=	' LIMIT ' . $start . ',' . $length;

			// die($qry);
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetSearchAjaxKabupaten($start, $length, $search, $col, $dir, $filter = '', $list_nama_barang = '', $list_id_provinsi = '')
		{
			$qry = "
			SELECT uni.id_provinsi, uni.nama_provinsi, uni.nama_kabupaten, 
			SUM(total_unit) AS total_unit, SUM(total_nilai) AS total_nilai,
			SUM(total_unit_alokasi) AS total_unit_alokasi, SUM(total_nilai_alokasi) AS total_nilai_alokasi,
			SUM(total_unit_baphp) AS total_unit_baphp, SUM(total_nilai_baphp) AS total_nilai_baphp,
			SUM(total_unit_bastb) AS total_unit_bastb, SUM(total_nilai_bastb) AS total_nilai_bastb
			FROM (
				SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
				SUM(CASE 	
				WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
					dt.jumlah_barang_rev_1
				WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
					dt.jumlah_barang_rev_2
				ELSE
					dt.jumlah_barang
				END) AS total_unit,
				SUM(CASE 	
				WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
					dt.nilai_barang_rev_1
				WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
					dt.nilai_barang_rev_2
				ELSE
					dt.nilai_barang
				END) AS total_nilai,
				0 AS total_unit_alokasi,
				0 AS total_nilai_alokasi,
				0 AS total_unit_baphp,
				0 AS total_nilai_baphp,
				0 AS total_unit_bastb, 0 AS total_nilai_bastb
				FROM
				tb_alokasi_provinsi dt
				LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
				LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
				LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
				WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
				$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;
				if($list_nama_barang != ''){

					for($i=0; $i<count($list_nama_barang); $i++){
						if($i == 0)
							$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
						else
							$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
					}

					$qry .= ")";

				}

				if($list_id_provinsi != ''){

					for($i=0; $i<count($list_id_provinsi); $i++){
						if($i == 0)
							$qry .= " and ( dt.id_provinsi = '".$list_id_provinsi[$i]."'";
						else
							$qry .= " or dt.id_provinsi = '".$list_id_provinsi[$i]."'";
					}

					$qry .= ")";

				}
		$qry .= " GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`, dt.`id_kabupaten`, kab.`nama_kabupaten`

				UNION

				SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
				0 AS total_unit, 0 AS total_nilai,  
				SUM(CASE 	
				WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
					dt.jumlah_barang_rev_1
				WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
					dt.jumlah_barang_rev_2
				ELSE
					dt.jumlah_barang
				END) AS total_unit_alokasi,
				SUM(CASE 	
				WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
					dt.nilai_barang_rev_1
				WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
					dt.nilai_barang_rev_2
				ELSE
					dt.nilai_barang
				END) AS total_nilai_alokasi,
				0 AS total_unit_baphp,
				0 AS total_nilai_baphp,
				0 AS total_unit_bastb, 0 AS total_nilai_bastb
				FROM
				tb_alokasi_provinsi dt
				LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
				LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
				LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
				WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
				$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan; 
				
				if($list_nama_barang != ''){

					for($i=0; $i<count($list_nama_barang); $i++){
						if($i == 0)
							$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
						else
							$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
					}

					$qry .= ")";

				}

				if($list_id_provinsi != ''){

					for($i=0; $i<count($list_id_provinsi); $i++){
						if($i == 0)
							$qry .= " and ( dt.id_provinsi = '".$list_id_provinsi[$i]."'";
						else
							$qry .= " or dt.id_provinsi = '".$list_id_provinsi[$i]."'";
					}

					$qry .= ")";

				}

		$qry .= " GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`, dt.`id_kabupaten`, kab.`nama_kabupaten`
				
				UNION
				
				SELECT bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten,
				0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
				SUM(bap.jumlah_barang) AS total_unit_baphp, SUM(bap.`nilai_barang`) AS total_nilai_baphp,
				0 AS total_unit_bastb, 0 AS total_nilai_bastb
				FROM tb_baphp_provinsi bap
				LEFT JOIN tb_kontrak_provinsi kon ON bap.`no_kontrak` = kon.`no_kontrak`
				LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
				LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
				WHERE 1=1";
				$qry .= " AND kon.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

				if($list_nama_barang != ''){

					for($i=0; $i<count($list_nama_barang); $i++){
						if($i == 0)
							$qry .= " and ( bap.nama_barang = '".$list_nama_barang[$i]."'";
						else
							$qry .= " or bap.nama_barang = '".$list_nama_barang[$i]."'";
					}

					$qry .= ")";

				}

				if($list_id_provinsi != ''){

					for($i=0; $i<count($list_id_provinsi); $i++){
						if($i == 0)
							$qry .= " and ( bap.id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
						else
							$qry .= " or bap.id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
					}

					$qry .= ")";

				}

		$qry .= " GROUP BY bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten
				
				UNION
				
				SELECT bas.id_provinsi_penyerah, pro.`nama_provinsi`, bas.id_kabupaten_penyerah, kab.nama_kabupaten,
				0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
				0 AS total_unit_baphp, 0 AS total_nilai_baphp,
				SUM(bas.jumlah_barang) AS total_unit_bastb, SUM(bas.`nilai_barang`) AS total_nilai_bastb
				FROM `tb_bastb_provinsi` bas
				LEFT JOIN tb_kontrak_provinsi kon ON kon.`no_kontrak` = bas.`no_kontrak`
				LEFT JOIN tb_provinsi pro ON bas.`id_provinsi_penyerah` = pro.`id`
				LEFT JOIN tb_kabupaten kab ON bas.`id_kabupaten_penyerah` = kab.`id`
				WHERE 1=1";
				$qry .= " AND kon.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

				if($list_nama_barang != ''){

					for($i=0; $i<count($list_nama_barang); $i++){
						if($i == 0)
							$qry .= " and ( bas.nama_barang = '".$list_nama_barang[$i]."'";
						else
							$qry .= " or bas.nama_barang = '".$list_nama_barang[$i]."'";
					}

					$qry .= ")";

				}

				if($list_id_provinsi != ''){

					for($i=0; $i<count($list_id_provinsi); $i++){
						if($i == 0)
							$qry .= " and ( bas.id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
						else
							$qry .= " or bas.id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
					}

					$qry .= ")";

				}
		$qry .= " GROUP BY bas.id_provinsi_penyerah, pro.`nama_provinsi`, bas.id_kabupaten_penyerah, kab.nama_kabupaten
			) AS uni
			WHERE 1=1 ";

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and uni.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}
			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					and uni.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
			}

			$qry .= " and (
					uni.nama_provinsi LIKE '%$search%'
					or uni.nama_kabupaten LIKE '%$search%'
				)";

			$qry .= $filter;
 
			$qry .= " GROUP BY uni.id_provinsi, uni.nama_provinsi, uni.nama_kabupaten";

			$qry .= ' ORDER BY '.$col.' '.$dir;
			if($length > 0)
				$qry .=	' LIMIT ' . $start . ',' . $length;
			
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}
		
		function getKabupaten($param=null){			
			if(empty($param['col'])) $param['col'] = 'nama_kabupaten';
			if(empty($param['dir'])) $param['dir'] = 'ASC';
			if(empty($param['filter'])) $param['filter'] = '';
			if(empty($param['length'])) $param['length'] = 0;

			$qry = "SELECT nama_provinsi, nama_kabupaten, alo.jumlah_barang alokasi_provinsi, bap.jumlah_barang baphp_provinsi, bas.jumlah_barang bastb_provinsi
			, alo.nilai_barang alokasi_provinsi_nilai, bap.nilai_barang baphp_provinsi_nilai, bas.nilai_barang bastb_provinsi_nilai
			FROM tb_kabupaten kab
			INNER JOIN tb_provinsi pro ON kab.id_provinsi=pro.id
			LEFT JOIN (
			SELECT id_kabupaten, SUM(CASE 	
					WHEN alo.status_alokasi = 'DATA KONTRAK AWAL' THEN
						alo.jumlah_barang
					WHEN alo.status_alokasi = 'DATA ADDENDUM 1' THEN
						alo.jumlah_barang_rev_1
					WHEN alo.status_alokasi = 'DATA ADDENDUM 2' THEN
						alo.jumlah_barang_rev_2
					WHEN alo.status_alokasi = 'DATA ADDENDUM 3' THEN
						alo.jumlah_barang_rev_3
					END) AS jumlah_barang, SUM(CASE 	
					WHEN alo.status_alokasi = 'DATA KONTRAK AWAL' THEN
						alo.nilai_barang
					WHEN alo.status_alokasi = 'DATA ADDENDUM 1' THEN
						alo.nilai_barang_rev_1
					WHEN alo.status_alokasi = 'DATA ADDENDUM 2' THEN
						alo.nilai_barang_rev_2
					WHEN alo.status_alokasi = 'DATA ADDENDUM 3' THEN
						alo.nilai_barang_rev_3
					END) AS nilai_barang
					FROM tb_alokasi_provinsi alo 
					INNER JOIN tb_kontrak_provinsi kon ON alo.id_kontrak_provinsi=kon.id AND tahun_anggaran=".$this->session->userdata('logged_in')->tahun_pengadaan."
					GROUP BY id_kabupaten
			)alo ON alo.id_kabupaten = kab.id
			LEFT JOIN (
			SELECT id_kabupaten_penerima, SUM(jumlah_barang) jumlah_barang, SUM(nilai_barang) nilai_barang
			FROM tb_baphp_provinsi
			WHERE tahun_anggaran=".$this->session->userdata('logged_in')->tahun_pengadaan."
			GROUP BY id_kabupaten_penerima
			)bap ON bap.id_kabupaten_penerima=kab.id
			LEFT JOIN (
			SELECT id_kabupaten_penyerah, SUM(jumlah_barang) jumlah_barang, SUM(nilai_barang) nilai_barang
			FROM tb_bastb_provinsi
			WHERE tahun_anggaran=".$this->session->userdata('logged_in')->tahun_pengadaan."
			GROUP BY id_kabupaten_penyerah
			)bas ON bas.id_kabupaten_penyerah=kab.id
			WHERE (alo.jumlah_barang > 0)
			".$param['filter'];
			if(isset($param['search'])) $qry .= " AND (nama_provinsi LIKE '%".$param['search']."%' OR nama_kabupaten LIKE '%".$param['search']."%')";
			if(isset($this->session->userdata('logged_in')->id_provinsi)) $qry .= " and id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			$qry .= " ORDER BY ".$param['col']." ".$param['dir'];
			if($param['length'] > 0){
				$qry .=	' LIMIT ' . $param['start'] . ',' . $param['length'];
			}
			// var_dump($this->session->userdata('logged_in')->id_provinsi);exit();
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else 
				return array();
		}

		// **************************************************************************************************************************************
		// LAPORAN KONTRAK *********************************************************************************************************************
		// /*************************************************************************************************************************************

		function GetAllAjaxCountKontrak($list_nama_barang = '', $list_id_provinsi = '', $list_id_kabupaten = '')
		{
			$qry = "
				SELECT uni.no_kontrak, uni.nama_barang, uni.nama_penyedia_provinsi, 
				SUM(total_unit) AS total_unit, SUM(total_nilai) AS total_nilai,
				SUM(total_unit_alokasi) AS total_unit_alokasi, SUM(total_nilai_alokasi) AS total_nilai_alokasi,
				SUM(total_unit_baphp) AS total_unit_baphp, SUM(total_nilai_baphp) AS total_nilai_baphp,
				SUM(total_unit_bastb) AS total_unit_bastb, SUM(total_nilai_bastb) AS total_nilai_bastb
				FROM (
					SELECT
					hd.nama_barang, hd.no_kontrak, peny.nama_penyedia_provinsi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					WHEN dt.status_alokasi = 'DATA ADDENDUM 3' THEN
						dt.jumlah_barang_rev_3
					ELSE
						dt.jumlah_barang
					END) AS total_unit,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					WHEN dt.status_alokasi = 'DATA ADDENDUM 3' THEN
						dt.nilai_barang_rev_3
					ELSE
						dt.nilai_barang
					END) AS total_nilai,
					0 AS total_unit_alokasi,
					0 AS total_nilai_alokasi,
					0 AS total_unit_baphp,
					0 AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_provinsi peny ON hd.id_penyedia_provinsi = peny.id
					WHERE 1=1";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan; 
					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( hd.id_provinsi = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or hd.id_provinsi = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}

					if(isset($this->session->userdata('logged_in')->id_provinsi)){
						$qry .= "
							and hd.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
					}
					if(isset($this->session->userdata('logged_in')->id_kabupaten)){
						$qry .= "
							and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
					}

			$qry .= " GROUP BY
					hd.nama_barang, hd.no_kontrak, peny.nama_penyedia_provinsi

					UNION

					SELECT
					hd.nama_barang, hd.no_kontrak, peny.nama_penyedia_provinsi,
					0 AS total_unit, 0 AS total_nilai,  
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					WHEN dt.status_alokasi = 'DATA ADDENDUM 3' THEN
						dt.jumlah_barang_rev_3
					ELSE
						dt.jumlah_barang
					END) AS total_unit_alokasi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang
					END) AS total_nilai_alokasi,
					0 AS total_unit_baphp,
					0 AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_provinsi peny ON hd.id_penyedia_provinsi = peny.id
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan; 
					
					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( hd.id_provinsi = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or hd.id_provinsi = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}

					if(isset($this->session->userdata('logged_in')->id_provinsi)){
						$qry .= "
							and hd.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
					}
					if(isset($this->session->userdata('logged_in')->id_kabupaten)){
						$qry .= "
							and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
					}

			$qry .= " GROUP BY 
					hd.nama_barang, hd.no_kontrak, peny.nama_penyedia_provinsi
					
					UNION
					
					SELECT
					bap.nama_barang, bap.no_kontrak, peny.nama_penyedia_provinsi,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					SUM(bap.jumlah_barang) AS total_unit_baphp, SUM(bap.`nilai_barang`) AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_baphp_provinsi bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					LEFT JOIN tb_kontrak_provinsi kon ON bap.no_kontrak = kon.no_kontrak 
					LEFT JOIN tb_penyedia_provinsi peny ON kon.id_penyedia_provinsi = peny.id
					WHERE 1=1";
					$qry .= " AND kon.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( bap.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or bap.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( bap.id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or bap.id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( bap.id_kabupaten_penerima = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or bap.id_kabupaten_penerima = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}

					if(isset($this->session->userdata('logged_in')->id_provinsi)){
						$qry .= "
							and bap.id_provinsi_penerima = ".$this->session->userdata('logged_in')->id_provinsi;
					}
					if(isset($this->session->userdata('logged_in')->id_kabupaten)){
						$qry .= "
							and bap.id_kabupaten_penerima = ".$this->session->userdata('logged_in')->id_kabupaten;
					}

			$qry .= " GROUP BY 
					bap.nama_barang, bap.no_kontrak, peny.nama_penyedia_provinsi
					
					UNION
					
					SELECT 
					bas.nama_barang, bas.no_kontrak, peny.nama_penyedia_provinsi, 
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					0 AS total_unit_baphp, 0 AS total_nilai_baphp,
					SUM(bas.jumlah_barang) AS total_unit_bastb, SUM(bas.`nilai_barang`) AS total_nilai_bastb
					FROM `tb_bastb_provinsi` bas
					LEFT JOIN tb_provinsi pro ON bas.`id_provinsi_penyerah` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bas.`id_kabupaten_penyerah` = kab.`id`
					LEFT JOIN tb_kontrak_provinsi kon ON bas.no_kontrak = kon.no_kontrak 
					LEFT JOIN tb_penyedia_provinsi peny ON kon.id_penyedia_provinsi = peny.id
					WHERE 1=1";
					$qry .= " AND kon.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( bas.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or bas.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( bas.id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or bas.id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( bas.id_kabupaten_penyerah = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or bas.id_kabupaten_penyerah = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}

					if(isset($this->session->userdata('logged_in')->id_provinsi)){
						$qry .= "
							and bas.id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi;
					}
					if(isset($this->session->userdata('logged_in')->id_kabupaten)){
						$qry .= "
							and bas.id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten;
					}
			$qry .= " GROUP BY 
					bas.nama_barang, bas.no_kontrak, peny.nama_penyedia_provinsi
				) AS uni
				WHERE 1=1 ";

				if(isset($this->session->userdata('logged_in')->id_penyedia_provinsi)){
					$qry .= "
						and uni.nama_penyedia_provinsi = ( SELECT nama_penyedia_provinsi from tb_penyedia_provinsi where id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi.")";
				}

			$qry .= ' GROUP BY uni.nama_barang, uni.no_kontrak, uni.nama_penyedia_provinsi';
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetSearchAjaxCountKontrak($search, $filter = '', $list_nama_barang = '', $list_id_provinsi = '', $list_id_kabupaten = '')
		{
			$qry = "
				SELECT uni.no_kontrak, uni.nama_barang, uni.nama_penyedia_provinsi, 
				SUM(total_unit) AS total_unit, SUM(total_nilai) AS total_nilai,
				SUM(total_unit_alokasi) AS total_unit_alokasi, SUM(total_nilai_alokasi) AS total_nilai_alokasi,
				SUM(total_unit_baphp) AS total_unit_baphp, SUM(total_nilai_baphp) AS total_nilai_baphp,
				SUM(total_unit_bastb) AS total_unit_bastb, SUM(total_nilai_bastb) AS total_nilai_bastb
				FROM (
					SELECT
					hd.nama_barang, hd.no_kontrak, peny.nama_penyedia_provinsi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					WHEN dt.status_alokasi = 'DATA ADDENDUM 3' THEN
						dt.jumlah_barang_rev_3
					ELSE
						dt.jumlah_barang
					END) AS total_unit,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					WHEN dt.status_alokasi = 'DATA ADDENDUM 3' THEN
						dt.nilai_barang_rev_3
					ELSE
						dt.nilai_barang
					END) AS total_nilai,
					0 AS total_unit_alokasi,
					0 AS total_nilai_alokasi,
					0 AS total_unit_baphp,
					0 AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_provinsi peny ON hd.id_penyedia_provinsi = peny.id
					WHERE 1=1";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;
					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( hd.id_provinsi = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or hd.id_provinsi = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}

					if(isset($this->session->userdata('logged_in')->id_provinsi)){
						$qry .= "
							and hd.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
					}
					if(isset($this->session->userdata('logged_in')->id_kabupaten)){
						$qry .= "
							and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
					}

			$qry .= " GROUP BY
					hd.nama_barang, hd.no_kontrak, peny.nama_penyedia_provinsi

					UNION

					SELECT
					hd.nama_barang, hd.no_kontrak, peny.nama_penyedia_provinsi,
					0 AS total_unit, 0 AS total_nilai,  
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					WHEN dt.status_alokasi = 'DATA ADDENDUM 3' THEN
						dt.jumlah_barang_rev_3
					ELSE
						dt.jumlah_barang
					END) AS total_unit_alokasi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					WHEN dt.status_alokasi = 'DATA ADDENDUM 3' THEN
						dt.nilai_barang_rev_3
					ELSE
						dt.nilai_barang
					END) AS total_nilai_alokasi,
					0 AS total_unit_baphp,
					0 AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_provinsi peny ON hd.id_penyedia_provinsi = peny.id
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan; 
					
					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( hd.id_provinsi = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or hd.id_provinsi = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}

					if(isset($this->session->userdata('logged_in')->id_provinsi)){
						$qry .= "
							and hd.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
					}
					if(isset($this->session->userdata('logged_in')->id_kabupaten)){
						$qry .= "
							and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
					}

			$qry .= " GROUP BY 
					hd.nama_barang, hd.no_kontrak, peny.nama_penyedia_provinsi
					
					UNION
					
					SELECT
					bap.nama_barang, bap.no_kontrak, peny.nama_penyedia_provinsi,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					SUM(bap.jumlah_barang) AS total_unit_baphp, SUM(bap.`nilai_barang`) AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_baphp_provinsi bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					LEFT JOIN tb_kontrak_provinsi kon ON bap.no_kontrak = kon.no_kontrak 
					LEFT JOIN tb_penyedia_provinsi peny ON kon.id_penyedia_provinsi = peny.id
					WHERE 1=1";
					$qry .= " AND kon.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( bap.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or bap.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( bap.id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or bap.id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( bap.id_kabupaten_penerima = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or bap.id_kabupaten_penerima = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}

					if(isset($this->session->userdata('logged_in')->id_provinsi)){
						$qry .= "
							and bap.id_provinsi_penerima = ".$this->session->userdata('logged_in')->id_provinsi;
					}
					if(isset($this->session->userdata('logged_in')->id_kabupaten)){
						$qry .= "
							and bap.id_kabupaten_penerima = ".$this->session->userdata('logged_in')->id_kabupaten;
					}


			$qry .= " GROUP BY 
					bap.nama_barang, bap.no_kontrak, peny.nama_penyedia_provinsi
					
					UNION
					
					SELECT 
					bas.nama_barang, bas.no_kontrak, peny.nama_penyedia_provinsi, 
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					0 AS total_unit_baphp, 0 AS total_nilai_baphp,
					SUM(bas.jumlah_barang) AS total_unit_bastb, SUM(bas.`nilai_barang`) AS total_nilai_bastb
					FROM `tb_bastb_provinsi` bas
					LEFT JOIN tb_provinsi pro ON bas.`id_provinsi_penyerah` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bas.`id_kabupaten_penyerah` = kab.`id`
					LEFT JOIN tb_kontrak_provinsi kon ON bas.no_kontrak = kon.no_kontrak 
					LEFT JOIN tb_penyedia_provinsi peny ON kon.id_penyedia_provinsi = peny.id
					WHERE 1=1";
					$qry .= " AND kon.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( bas.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or bas.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( bas.id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or bas.id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( bas.id_kabupaten_penyerah = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or bas.id_kabupaten_penyerah = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}

					if(isset($this->session->userdata('logged_in')->id_provinsi)){
						$qry .= "
							and bas.id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi;
					}
					if(isset($this->session->userdata('logged_in')->id_kabupaten)){
						$qry .= "
							and bas.id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten;
					}

			$qry .= " GROUP BY 
					bas.nama_barang, bas.no_kontrak, peny.nama_penyedia_provinsi
				) AS uni
				WHERE 1=1 ";

				if(isset($this->session->userdata('logged_in')->id_penyedia_provinsi)){
					$qry .= "
						and uni.nama_penyedia_provinsi = ( SELECT nama_penyedia_provinsi from tb_penyedia_provinsi where id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi.")";
				}

			$qry .= " and (
						uni.nama_barang LIKE '%$search%'
						or uni.no_kontrak LIKE '%$search%'
						or uni.nama_penyedia_provinsi LIKE '%$search%'
				)
			";

			$qry .= $filter;

			$qry .= ' GROUP BY uni.nama_barang, uni.no_kontrak, uni.nama_penyedia_provinsi';
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetFilterAjaxCountKontrak($filter = '', $list_nama_barang = '', $list_id_provinsi = '', $list_id_kabupaten = '')
		{
			$qry = "
				SELECT uni.no_kontrak, uni.nama_barang, uni.nama_penyedia_provinsi, 
				SUM(total_unit) AS total_unit, SUM(total_nilai) AS total_nilai,
				SUM(total_unit_alokasi) AS total_unit_alokasi, SUM(total_nilai_alokasi) AS total_nilai_alokasi,
				SUM(total_unit_baphp) AS total_unit_baphp, SUM(total_nilai_baphp) AS total_nilai_baphp,
				SUM(total_unit_bastb) AS total_unit_bastb, SUM(total_nilai_bastb) AS total_nilai_bastb
				FROM (
					SELECT
					hd.nama_barang, hd.no_kontrak, peny.nama_penyedia_provinsi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					WHEN dt.status_alokasi = 'DATA ADDENDUM 3' THEN
						dt.jumlah_barang_rev_3
					ELSE
						dt.jumlah_barang
					END) AS total_unit,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					WHEN dt.status_alokasi = 'DATA ADDENDUM 3' THEN
						dt.nilai_barang_rev_3
					ELSE
						dt.nilai_barang
					END) AS total_nilai,
					0 AS total_unit_alokasi,
					0 AS total_nilai_alokasi,
					0 AS total_unit_baphp,
					0 AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_provinsi peny ON hd.id_penyedia_provinsi = peny.id
					WHERE 1=1";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;
					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( hd.id_provinsi = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or hd.id_provinsi = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}

					if(isset($this->session->userdata('logged_in')->id_provinsi)){
						$qry .= "
							and hd.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
					}
					if(isset($this->session->userdata('logged_in')->id_kabupaten)){
						$qry .= "
							and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
					}

			$qry .= " GROUP BY
					hd.nama_barang, hd.no_kontrak, peny.nama_penyedia_provinsi

					UNION

					SELECT
					hd.nama_barang, hd.no_kontrak, peny.nama_penyedia_provinsi,
					0 AS total_unit, 0 AS total_nilai,  
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang
					END) AS total_unit_alokasi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang
					END) AS total_nilai_alokasi,
					0 AS total_unit_baphp,
					0 AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_provinsi peny ON hd.id_penyedia_provinsi = peny.id
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan; 
					
					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( hd.id_provinsi = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or hd.id_provinsi = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}

					if(isset($this->session->userdata('logged_in')->id_provinsi)){
						$qry .= "
							and hd.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
					}
					if(isset($this->session->userdata('logged_in')->id_kabupaten)){
						$qry .= "
							and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
					}

			$qry .= " GROUP BY 
					hd.nama_barang, hd.no_kontrak, peny.nama_penyedia_provinsi
					
					UNION
					
					SELECT
					bap.nama_barang, bap.no_kontrak, peny.nama_penyedia_provinsi,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					SUM(bap.jumlah_barang) AS total_unit_baphp, SUM(bap.`nilai_barang`) AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_baphp_provinsi bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					LEFT JOIN tb_kontrak_provinsi kon ON bap.no_kontrak = kon.no_kontrak 
					LEFT JOIN tb_penyedia_provinsi peny ON kon.id_penyedia_provinsi = peny.id
					WHERE 1=1";
					$qry .= " AND kon.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( bap.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or bap.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( bap.id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or bap.id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( bap.id_kabupaten_penerima = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or bap.id_kabupaten_penerima = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}

					if(isset($this->session->userdata('logged_in')->id_provinsi)){
						$qry .= "
							and bap.id_provinsi_penerima = ".$this->session->userdata('logged_in')->id_provinsi;
					}
					if(isset($this->session->userdata('logged_in')->id_kabupaten)){
						$qry .= "
							and bap.id_kabupaten_penerima = ".$this->session->userdata('logged_in')->id_kabupaten;
					}

			$qry .= " GROUP BY 
					bap.nama_barang, bap.no_kontrak, peny.nama_penyedia_provinsi
					
					UNION
					
					SELECT 
					bas.nama_barang, bas.no_kontrak, peny.nama_penyedia_provinsi, 
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					0 AS total_unit_baphp, 0 AS total_nilai_baphp,
					SUM(bas.jumlah_barang) AS total_unit_bastb, SUM(bas.`nilai_barang`) AS total_nilai_bastb
					FROM `tb_bastb_provinsi` bas
					LEFT JOIN tb_provinsi pro ON bas.`id_provinsi_penyerah` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bas.`id_kabupaten_penyerah` = kab.`id`
					LEFT JOIN tb_kontrak_provinsi kon ON bas.no_kontrak = kon.no_kontrak 
					LEFT JOIN tb_penyedia_provinsi peny ON kon.id_penyedia_provinsi = peny.id
					WHERE 1=1";
					$qry .= " AND kon.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( bas.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or bas.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( bas.id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or bas.id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( bas.id_kabupaten_penyerah = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or bas.id_kabupaten_penyerah = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}

					if(isset($this->session->userdata('logged_in')->id_provinsi)){
						$qry .= "
							and bas.id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi;
					}
					if(isset($this->session->userdata('logged_in')->id_kabupaten)){
						$qry .= "
							and bas.id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten;
					}
			$qry .= " GROUP BY 
					bas.nama_barang, bas.no_kontrak, peny.nama_penyedia_provinsi
				) AS uni
				WHERE 1=1 ";

				if(isset($this->session->userdata('logged_in')->id_penyedia_provinsi)){
					$qry .= "
						and uni.nama_penyedia_provinsi = ( SELECT nama_penyedia_provinsi from tb_penyedia_provinsi where id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi.")";
				}


			$qry .= $filter;

			$qry .= " GROUP BY 
				uni.nama_barang, uni.no_kontrak, uni.nama_penyedia_provinsi";

			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetAllForAjaxKontrak($start, $length, $col, $dir, $filter = '', $list_nama_barang = '', $list_id_provinsi = '', $list_id_kabupaten = '')
		{

			$qry = "
				SELECT nama_provinsi, uni.no_kontrak, uni.nama_barang, uni.merk, uni.nama_penyedia_provinsi, 
				SUM(total_unit) AS total_unit, SUM(total_nilai) AS total_nilai,
				SUM(total_unit_alokasi) AS total_unit_alokasi, SUM(total_nilai_alokasi) AS total_nilai_alokasi,
				SUM(total_unit_baphp) AS total_unit_baphp, SUM(total_nilai_baphp) AS total_nilai_baphp,
				SUM(total_unit_bastb) AS total_unit_bastb, SUM(total_nilai_bastb) AS total_nilai_bastb
				FROM (
					SELECT
					nama_provinsi, hd.nama_barang, hd.no_kontrak, peny.nama_penyedia_provinsi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					WHEN dt.status_alokasi = 'DATA ADDENDUM 3' THEN
						dt.jumlah_barang_rev_3
					ELSE
						dt.jumlah_barang
					END) AS total_unit,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					WHEN dt.status_alokasi = 'DATA ADDENDUM 3' THEN
						dt.nilai_barang_rev_3
					ELSE
						dt.nilai_barang
					END) AS total_nilai,
					0 AS total_unit_alokasi,
					0 AS total_nilai_alokasi,
					0 AS total_unit_baphp,
					0 AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_provinsi peny ON hd.id_penyedia_provinsi = peny.id
					WHERE 1=1 and status_alokasi NOT IN ('MENUNGGU KONFIRMASI') ";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;
					
					if(isset($this->session->userdata('logged_in')->id_provinsi)){
						$qry .= "
							and hd.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
					}
					if(isset($this->session->userdata('logged_in')->id_kabupaten)){
						$qry .= "
							and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
					}

			$qry .= " GROUP BY
			nama_provinsi, hd.nama_barang, hd.no_kontrak, peny.nama_penyedia_provinsi

					UNION

					SELECT
					nama_provinsi, hd.nama_barang, hd.no_kontrak, peny.nama_penyedia_provinsi,
					0 AS total_unit, 0 AS total_nilai,  
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					WHEN dt.status_alokasi = 'DATA ADDENDUM 3' THEN
						dt.jumlah_barang_rev_3
					ELSE
						dt.jumlah_barang
					END) AS total_unit_alokasi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					WHEN dt.status_alokasi = 'DATA ADDENDUM 3' THEN
						dt.nilai_barang_rev_3
					ELSE
						dt.nilai_barang
					END) AS total_nilai_alokasi,
					0 AS total_unit_baphp,
					0 AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_provinsi peny ON hd.id_penyedia_provinsi = peny.id
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan; 
					
					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( hd.id_provinsi = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or hd.id_provinsi = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}

					if(isset($this->session->userdata('logged_in')->id_provinsi)){
						$qry .= "
							and hd.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
					}
					if(isset($this->session->userdata('logged_in')->id_kabupaten)){
						$qry .= "
							and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
					}

			$qry .= " GROUP BY 
			nama_provinsi, hd.nama_barang, hd.no_kontrak, peny.nama_penyedia_provinsi
					
					UNION
					
					SELECT
					nama_provinsi, bap.nama_barang, bap.no_kontrak, peny.nama_penyedia_provinsi,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					SUM(bap.jumlah_barang) AS total_unit_baphp, SUM(bap.`nilai_barang`) AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_baphp_provinsi bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					LEFT JOIN tb_kontrak_provinsi kon ON bap.no_kontrak = kon.no_kontrak 
					LEFT JOIN tb_penyedia_provinsi peny ON kon.id_penyedia_provinsi = peny.id
					WHERE 1=1";
					$qry .= " AND kon.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( bap.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or bap.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( bap.id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or bap.id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( bap.id_kabupaten_penerima = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or bap.id_kabupaten_penerima = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}

					if(isset($this->session->userdata('logged_in')->id_provinsi)){
						$qry .= "
							and bap.id_provinsi_penerima = ".$this->session->userdata('logged_in')->id_provinsi;
					}
					if(isset($this->session->userdata('logged_in')->id_kabupaten)){
						$qry .= "
							and bap.id_kabupaten_penerima = ".$this->session->userdata('logged_in')->id_kabupaten;
					}

			$qry .= " GROUP BY 
			nama_provinsi, bap.nama_barang, bap.no_kontrak, peny.nama_penyedia_provinsi
					
					UNION
					
					SELECT 
					nama_provinsi, bas.nama_barang, bas.no_kontrak, peny.nama_penyedia_provinsi, 
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					0 AS total_unit_baphp, 0 AS total_nilai_baphp,
					SUM(bas.jumlah_barang) AS total_unit_bastb, SUM(bas.`nilai_barang`) AS total_nilai_bastb
					FROM `tb_bastb_provinsi` bas
					LEFT JOIN tb_provinsi pro ON bas.`id_provinsi_penyerah` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bas.`id_kabupaten_penyerah` = kab.`id`
					LEFT JOIN tb_kontrak_provinsi kon ON bas.no_kontrak = kon.no_kontrak 
					LEFT JOIN tb_penyedia_provinsi peny ON kon.id_penyedia_provinsi = peny.id
					WHERE 1=1";
					$qry .= " AND kon.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( bas.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or bas.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( bas.id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or bas.id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( bas.id_kabupaten_penyerah = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or bas.id_kabupaten_penyerah = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}

					if(isset($this->session->userdata('logged_in')->id_provinsi)){
						$qry .= "
							and bas.id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi;
					}
					if(isset($this->session->userdata('logged_in')->id_kabupaten)){
						$qry .= "
							and bas.id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten;
					}
			$qry .= " GROUP BY 
			nama_provinsi, bas.nama_barang, bas.no_kontrak, peny.nama_penyedia_provinsi
				) AS uni
				WHERE 1=1 ";

				if(isset($this->session->userdata('logged_in')->id_penyedia_provinsi)){
					$qry .= "
						and uni.nama_penyedia_provinsi = ( SELECT nama_penyedia_provinsi from tb_penyedia_provinsi where id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi.")";
				}

			$qry .= $filter;

			$qry .= " GROUP BY 
			nama_provinsi, uni.nama_barang, uni.no_kontrak, uni.nama_penyedia_provinsi";

			$qry .= ' ORDER BY '.$col.' '.$dir;
			if($length > 0)
				$qry .=	' LIMIT ' . $start . ',' . $length;

			// die($qry);
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetSearchAjaxKontrak($start, $length, $search, $col, $dir, $filter = '', $list_nama_barang = '', $list_id_provinsi = '', $list_id_kabupaten = '')
		{

			$qry = "
				SELECT uni.no_kontrak, uni.nama_barang, uni.merk, uni.nama_penyedia_provinsi, 
				SUM(total_unit) AS total_unit, SUM(total_nilai) AS total_nilai,
				SUM(total_unit_alokasi) AS total_unit_alokasi, SUM(total_nilai_alokasi) AS total_nilai_alokasi,
				SUM(total_unit_baphp) AS total_unit_baphp, SUM(total_nilai_baphp) AS total_nilai_baphp,
				SUM(total_unit_bastb) AS total_unit_bastb, SUM(total_nilai_bastb) AS total_nilai_bastb
				FROM (
					SELECT
					hd.nama_barang, hd.no_kontrak, peny.nama_penyedia_provinsi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					WHEN dt.status_alokasi = 'DATA ADDENDUM 3' THEN
						dt.jumlah_barang_rev_3
					ELSE
						dt.jumlah_barang
					END) AS total_unit,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					WHEN dt.status_alokasi = 'DATA ADDENDUM 3' THEN
						dt.nilai_barang_rev_3
					ELSE
						dt.nilai_barang
					END) AS total_nilai,
					0 AS total_unit_alokasi,
					0 AS total_nilai_alokasi,
					0 AS total_unit_baphp,
					0 AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_provinsi peny ON hd.id_penyedia_provinsi = peny.id
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;
					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_provinsi = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or dt.id_provinsi = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}

					if(isset($this->session->userdata('logged_in')->id_provinsi)){
						$qry .= "
							and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
					}
					if(isset($this->session->userdata('logged_in')->id_kabupaten)){
						$qry .= "
							and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
					}

			$qry .= " GROUP BY
					hd.nama_barang, hd.no_kontrak, peny.nama_penyedia_provinsi

					UNION

					SELECT
					hd.nama_barang, hd.no_kontrak, peny.nama_penyedia_provinsi,
					0 AS total_unit, 0 AS total_nilai,  
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang
					END) AS total_unit_alokasi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang
					END) AS total_nilai_alokasi,
					0 AS total_unit_baphp,
					0 AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_provinsi peny ON hd.id_penyedia_provinsi = peny.id
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan; 
					
					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_provinsi = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or dt.id_provinsi = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}

					if(isset($this->session->userdata('logged_in')->id_provinsi)){
						$qry .= "
							and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
					}
					if(isset($this->session->userdata('logged_in')->id_kabupaten)){
						$qry .= "
							and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
					}

			$qry .= " GROUP BY 
					hd.nama_barang, hd.no_kontrak, peny.nama_penyedia_provinsi
					
					UNION
					
					SELECT
					bap.nama_barang, bap.no_kontrak, peny.nama_penyedia_provinsi,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					SUM(bap.jumlah_barang) AS total_unit_baphp, SUM(bap.`nilai_barang`) AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_baphp_provinsi bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					LEFT JOIN tb_kontrak_provinsi kon ON bap.no_kontrak = kon.no_kontrak 
					LEFT JOIN tb_penyedia_provinsi peny ON kon.id_penyedia_provinsi = peny.id
					WHERE 1=1";
					$qry .= " AND kon.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( bap.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or bap.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( bap.id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or bap.id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( bap.id_kabupaten_penerima = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or bap.id_kabupaten_penerima = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}

					if(isset($this->session->userdata('logged_in')->id_provinsi)){
						$qry .= "
							and bap.id_provinsi_penerima = ".$this->session->userdata('logged_in')->id_provinsi;
					}
					if(isset($this->session->userdata('logged_in')->id_kabupaten)){
						$qry .= "
							and bap.id_kabupaten_penerima = ".$this->session->userdata('logged_in')->id_kabupaten;
					}

			$qry .= " GROUP BY 
					bap.nama_barang, bap.no_kontrak, peny.nama_penyedia_provinsi
					
					UNION
					
					SELECT 
					bas.nama_barang, bas.no_kontrak, peny.nama_penyedia_provinsi, 
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					0 AS total_unit_baphp, 0 AS total_nilai_baphp,
					SUM(bas.jumlah_barang) AS total_unit_bastb, SUM(bas.`nilai_barang`) AS total_nilai_bastb
					FROM `tb_bastb_provinsi` bas
					LEFT JOIN tb_provinsi pro ON bas.`id_provinsi_penyerah` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bas.`id_kabupaten_penyerah` = kab.`id`
					LEFT JOIN tb_kontrak_provinsi kon ON bas.no_kontrak = kon.no_kontrak 
					LEFT JOIN tb_penyedia_provinsi peny ON kon.id_penyedia_provinsi = peny.id
					WHERE 1=1";
					$qry .= " AND kon.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( bas.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or bas.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( bas.id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or bas.id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( bas.id_kabupaten_penyerah = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or bas.id_kabupaten_penyerah = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}

					if(isset($this->session->userdata('logged_in')->id_provinsi)){
						$qry .= "
							and bas.id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi;
					}
					if(isset($this->session->userdata('logged_in')->id_kabupaten)){
						$qry .= "
							and bas.id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten;
					}

			$qry .= " GROUP BY 
					bas.nama_barang, bas.no_kontrak, peny.nama_penyedia_provinsi
				) AS uni
				WHERE 1=1 ";
				$qry .= " AND tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

				if(isset($this->session->userdata('logged_in')->id_penyedia_provinsi)){
					$qry .= "
						and uni.nama_penyedia_provinsi = ( SELECT nama_penyedia_provinsi from tb_penyedia_provinsi where id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi.")";
				}

			$qry .= " and (
						uni.nama_barang LIKE '%$search%'
						or nama_provinsi LIKE '%$search%'
						or uni.no_kontrak LIKE '%$search%'
						or uni.nama_penyedia_provinsi LIKE '%$search%'
				)
			";

			$qry .= $filter;

			$qry .= ' GROUP BY uni.nama_barang, uni.no_kontrak, uni.nama_penyedia_provinsi ORDER BY '.$col.' '.$dir;
			if($length > 0)
				$qry .=	' LIMIT ' . $start . ',' . $length;
			
			
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}
 
		function getKontrak($param = null){
			if(empty($param['col'])) $param['col'] = 'no_kontrak';
			if(empty($param['dir'])) $param['dir'] = 'ASC';
			if(empty($param['search'])) $param['search'] = '';
			if(empty($param['filter'])) $param['filter'] = '';
			if(empty($param['length'])) $param['length'] = 0;

			$qry = "SELECT id_kontrak_provinsi, nama_provinsi, id_provinsi, no_kontrak, nama_barang, merk, nama_penyedia_provinsi, 
			SUM(jumlah_barang) jumlah_barang, SUM(nilai_barang) nilai_barang,
			SUM(baphp_jumlah) baphp_jumlah, SUM(baphp_nilai)baphp_nilai, SUM(bastb_jumlah) bastb_jumlah, 
			SUM(bastb_nilai) bastb_nilai
			FROM(
			SELECT kon.id id_kontrak_provinsi, pro.nama_provinsi, pro.id as id_provinsi, kon.no_kontrak, kon.nama_barang, kon.merk, nye.nama_penyedia_provinsi, 
			CASE 	
					WHEN alo.status_alokasi = 'DATA ADDENDUM 1' THEN
						alo.jumlah_barang_rev_1
					WHEN alo.status_alokasi = 'DATA ADDENDUM 2' THEN
						alo.jumlah_barang_rev_2
					WHEN alo.status_alokasi = 'DATA ADDENDUM 3' THEN
						alo.jumlah_barang_rev_3
					ELSE
						alo.jumlah_barang
					END AS jumlah_barang,
			CASE 	
					WHEN alo.status_alokasi = 'DATA ADDENDUM 1' THEN
						alo.nilai_barang_rev_1
					WHEN alo.status_alokasi = 'DATA ADDENDUM 2' THEN
						alo.nilai_barang_rev_2
					WHEN alo.status_alokasi = 'DATA ADDENDUM 3' THEN
						alo.nilai_barang_rev_3
					ELSE
						alo.nilai_barang
					END AS nilai_barang,
			bap.jumlah_barang baphp_jumlah, bap.nilai_barang baphp_nilai,
			bas.jumlah_barang bastb_jumlah, bas.nilai_barang bastb_nilai
			FROM
			tb_kontrak_provinsi kon
			LEFT JOIN tb_alokasi_provinsi alo ON alo.id_kontrak_provinsi=kon.id
			LEFT JOIN tb_provinsi pro ON kon.id_provinsi = pro.id
			LEFT JOIN tb_penyedia_provinsi nye ON kon.id_penyedia_provinsi=nye.id
			LEFT JOIN (SELECT id_alokasi_provinsi, SUM(jumlah_barang) AS jumlah_barang, 
						SUM(nilai_barang) AS nilai_barang  
						FROM tb_baphp_provinsi bap
						GROUP BY id_alokasi_provinsi) bap ON bap.id_alokasi_provinsi=alo.id
			LEFT JOIN (SELECT id_alokasi_provinsi, SUM(jumlah_barang) AS jumlah_barang, 
						SUM(nilai_barang) AS nilai_barang  
						FROM tb_bastb_provinsi GROUP BY id_alokasi_provinsi) bas ON bas.id_alokasi_provinsi=alo.id
			WHERE alo.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI') "; 
			if(isset($this->session->userdata('logged_in')->id_provinsi)) $qry .= " AND kon.id_provinsi=".$this->session->userdata('logged_in')->id_provinsi; 
			$qry .= " AND kon.tahun_anggaran=".$this->session->userdata('logged_in')->tahun_pengadaan."
			)a WHERE 1=1";

			if(!empty($param['search'])) $qry .= " ".$param['search'];
			if(!empty($param['filter'])) $qry .= " ".$param['filter'];

			$qry .= " GROUP BY id_kontrak_provinsi
			ORDER BY ".$param['col']." ".$param['dir'];

			
			if($param['length'] > 0)
				$qry .=	' LIMIT ' . $param['start'] . ',' . $param['length'];			
			
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		// **************************************************************************************************************************************
		// LAPORAN DETAIL *********************************************************************************************************************
		// /*************************************************************************************************************************************

		function GetAllAjaxCountDetail($list_nama_barang = '', $list_id_provinsi = '', $list_id_kabupaten = '')
		{
			
			$qry = "
				SELECT uni.id_provinsi, uni.nama_provinsi, uni.nama_kabupaten,
				uni.nama_barang, uni.merk, uni.no_kontrak, uni.nama_penyedia_provinsi, 
				SUM(total_unit) AS total_unit, SUM(total_nilai) AS total_nilai,
				SUM(total_unit_alokasi) AS total_unit_alokasi, SUM(total_nilai_alokasi) AS total_nilai_alokasi,
				SUM(total_unit_baphp) AS total_unit_baphp, SUM(total_nilai_baphp) AS total_nilai_baphp,
				SUM(total_unit_bastb) AS total_unit_bastb, SUM(total_nilai_bastb) AS total_nilai_bastb
				FROM (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_provinsi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang
					END) AS total_unit,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang
					END) AS total_nilai,
					0 AS total_unit_alokasi,
					0 AS total_nilai_alokasi,
					0 AS total_unit_baphp,
					0 AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_provinsi peny ON hd.id_penyedia_provinsi = peny.id
					WHERE 1=1";
					$qry .= " AND tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;
					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_provinsi = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or dt.id_provinsi = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}
			$qry .= " GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_provinsi

					UNION

					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_provinsi,
					0 AS total_unit, 0 AS total_nilai,  
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang
					END) AS total_unit_alokasi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang
					END) AS total_nilai_alokasi,
					0 AS total_unit_baphp,
					0 AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_provinsi peny ON hd.id_penyedia_provinsi = peny.id
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan; 
					
					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_provinsi = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or dt.id_provinsi = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}

			$qry .= " GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_provinsi
					
					UNION
					
					SELECT bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten,
					bap.nama_barang, bap.merk, bap.no_kontrak, peny.nama_penyedia_provinsi,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					SUM(jumlah_barang) AS total_unit_baphp, SUM(`nilai_barang`) AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_baphp_provinsi bap
					LEFT JOIN tb_kontrak_provinsi kon ON bap.no_kontrak = kon.no_kontrak 
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					LEFT JOIN tb_jenis_barang_provinsi jb ON bap.nama_barang  = jb.nama_barang and bap.merk = jb.merk
					INNER JOIN tb_penyedia_provinsi peny ON jb.id_penyedia_provinsi = peny.id and peny.id_provinsi = bap.id_provinsi_penerima
					WHERE 1=1";
					$qry .= " AND kon.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( bap.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or bap.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( bap.id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or bap.id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( bap.id_kabupaten_penerima = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or bap.id_kabupaten_penerima = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}

			$qry .= " GROUP BY bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten,
					bap.nama_barang, bap.merk, bap.no_kontrak, peny.nama_penyedia_provinsi
					
					UNION
					
					SELECT bas.id_provinsi_penyerah, pro.`nama_provinsi`, bas.id_kabupaten_penyerah, kab.nama_kabupaten,
					bas.nama_barang, bas.merk, bas.no_kontrak, peny.nama_penyedia_provinsi, 
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					0 AS total_unit_baphp, 0 AS total_nilai_baphp,
					SUM(jumlah_barang) AS total_unit_bastb, SUM(`nilai_barang`) AS total_nilai_bastb
					FROM `tb_bastb_provinsi` bas
					LEFT JOIN tb_provinsi pro ON bas.`id_provinsi_penyerah` = pro.`id`
					LEFT JOIN tb_kontrak_provinsi kon ON bas.no_kontrak = kon.no_kontrak 
					LEFT JOIN tb_kabupaten kab ON bas.`id_kabupaten_penyerah` = kab.`id`
					LEFT JOIN tb_jenis_barang_provinsi jb ON bas.nama_barang  = jb.nama_barang and bas.merk = jb.merk
					INNER JOIN tb_penyedia_provinsi peny ON jb.id_penyedia_provinsi = peny.id and peny.id_provinsi = bas.id_provinsi_penyerah
					WHERE 1=1";
					$qry .= " AND kon.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( bas.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or bas.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( bas.id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or bas.id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( bas.id_kabupaten_penyerah = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or bas.id_kabupaten_penyerah = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}

			$qry .= " GROUP BY bas.id_provinsi_penyerah, pro.`nama_provinsi`, bas.id_kabupaten_penyerah, kab.nama_kabupaten,
					bas.nama_barang, bas.merk, bas.no_kontrak, peny.nama_penyedia_provinsi
				) AS uni
				WHERE 1=1 ";
				$qry .= " AND tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and uni.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and uni.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				if(isset($this->session->userdata('logged_in')->id_penyedia_provinsi)){
					$qry .= "
						and uni.nama_penyedia_provinsi = ( SELECT nama_penyedia_provinsi from tb_penyedia_provinsi where id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi.")";
				}

			$qry .= " GROUP BY uni.id_provinsi, uni.nama_provinsi, uni.nama_kabupaten,
				uni.nama_barang, uni.merk, uni.no_kontrak, uni.nama_penyedia_provinsi";

			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetSearchAjaxCountDetail($search, $filter = '', $list_nama_barang = '', $list_id_provinsi = '', $list_id_kabupaten = '')
		{
			
			$qry = "
				SELECT uni.id_provinsi, uni.nama_provinsi, uni.nama_kabupaten,
				uni.nama_barang, uni.merk, uni.no_kontrak, uni.nama_penyedia_provinsi, 
				SUM(total_unit) AS total_unit, SUM(total_nilai) AS total_nilai,
				SUM(total_unit_alokasi) AS total_unit_alokasi, SUM(total_nilai_alokasi) AS total_nilai_alokasi,
				SUM(total_unit_baphp) AS total_unit_baphp, SUM(total_nilai_baphp) AS total_nilai_baphp,
				SUM(total_unit_bastb) AS total_unit_bastb, SUM(total_nilai_bastb) AS total_nilai_bastb
				FROM (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_provinsi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang
					END) AS total_unit,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang
					END) AS total_nilai,
					0 AS total_unit_alokasi,
					0 AS total_nilai_alokasi,
					0 AS total_unit_baphp,
					0 AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_provinsi peny ON hd.id_penyedia_provinsi = peny.id
					WHERE 1=1";
					$qry .= " AND tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;
					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_provinsi = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or dt.id_provinsi = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}
			$qry .= " GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_provinsi

					UNION

					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_provinsi,
					0 AS total_unit, 0 AS total_nilai,  
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang
					END) AS total_unit_alokasi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang
					END) AS total_nilai_alokasi,
					0 AS total_unit_baphp,
					0 AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_provinsi peny ON hd.id_penyedia_provinsi = peny.id
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan; 
					
					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_provinsi = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or dt.id_provinsi = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}

			$qry .= " GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_provinsi
					
					UNION
					
					SELECT bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten,
					bap.nama_barang, bap.merk, bap.no_kontrak, peny.nama_penyedia_provinsi,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					SUM(jumlah_barang) AS total_unit_baphp, SUM(`nilai_barang`) AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_baphp_provinsi bap
					LEFT JOIN tb_kontrak_provinsi kon ON bap.no_kontrak = kon.no_kontrak 
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					LEFT JOIN tb_jenis_barang_provinsi jb ON bap.nama_barang  = jb.nama_barang and bap.merk = jb.merk
					INNER JOIN tb_penyedia_provinsi peny ON jb.id_penyedia_provinsi = peny.id and peny.id_provinsi = bap.id_provinsi_penerima
					WHERE 1=1";
					$qry .= " AND kon.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( bap.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or bap.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( bap.id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or bap.id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( bap.id_kabupaten_penerima = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or bap.id_kabupaten_penerima = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}

			$qry .= " GROUP BY bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten,
					bap.nama_barang, bap.merk, bap.no_kontrak, peny.nama_penyedia_provinsi
					
					UNION
					
					SELECT bas.id_provinsi_penyerah, pro.`nama_provinsi`, bas.id_kabupaten_penyerah, kab.nama_kabupaten,
					bas.nama_barang, bas.merk, bas.no_kontrak, peny.nama_penyedia_provinsi, 
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					0 AS total_unit_baphp, 0 AS total_nilai_baphp,
					SUM(jumlah_barang) AS total_unit_bastb, SUM(`nilai_barang`) AS total_nilai_bastb
					FROM `tb_bastb_provinsi` bas
					LEFT JOIN tb_kontrak_provinsi kon ON bas.no_kontrak = kon.no_kontrak 
					LEFT JOIN tb_provinsi pro ON bas.`id_provinsi_penyerah` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bas.`id_kabupaten_penyerah` = kab.`id`
					LEFT JOIN tb_jenis_barang_provinsi jb ON bas.nama_barang  = jb.nama_barang and bas.merk = jb.merk
					INNER JOIN tb_penyedia_provinsi peny ON jb.id_penyedia_provinsi = peny.id and peny.id_provinsi = bas.id_provinsi_penyerah
					WHERE 1=1";
					$qry .= " AND kon.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( bas.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or bas.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( bas.id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or bas.id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( bas.id_kabupaten_penyerah = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or bas.id_kabupaten_penyerah = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}
			$qry .= " GROUP BY bas.id_provinsi_penyerah, pro.`nama_provinsi`, bas.id_kabupaten_penyerah, kab.nama_kabupaten,
					bas.nama_barang, bas.merk, bas.no_kontrak, peny.nama_penyedia_provinsi
				) AS uni
				WHERE 1=1 ";
				$qry .= " AND tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and uni.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and uni.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				if(isset($this->session->userdata('logged_in')->id_penyedia_provinsi)){
					$qry .= "
						and uni.nama_penyedia_provinsi = ( SELECT nama_penyedia_provinsi from tb_penyedia_provinsi where id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi.")";
				}

			$qry .= " and (
					uni.nama_provinsi LIKE '%$search%'
					or uni.nama_kabupaten LIKE '%$search%'
					or uni.nama_barang LIKE '%$search%'
					or uni.merk LIKE '%$search%'
					or uni.no_kontrak LIKE '%$search%'
					or uni.nama_penyedia_provinsi LIKE '%$search%'
				)";

			$qry .= $filter;

			$qry .= " GROUP BY uni.id_provinsi, uni.nama_provinsi, uni.nama_kabupaten,
				uni.nama_barang, uni.merk, uni.no_kontrak, uni.nama_penyedia_provinsi";

			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetFilterAjaxCountDetail($filter = '', $list_nama_barang = '', $list_id_provinsi = '', $list_id_kabupaten = '')
		{
			

			$qry = "
				SELECT uni.id_provinsi, uni.nama_provinsi, uni.nama_kabupaten,
				uni.nama_barang, uni.merk, uni.no_kontrak, uni.nama_penyedia_provinsi, 
				SUM(total_unit) AS total_unit, SUM(total_nilai) AS total_nilai,
				SUM(total_unit_alokasi) AS total_unit_alokasi, SUM(total_nilai_alokasi) AS total_nilai_alokasi,
				SUM(total_unit_baphp) AS total_unit_baphp, SUM(total_nilai_baphp) AS total_nilai_baphp,
				SUM(total_unit_bastb) AS total_unit_bastb, SUM(total_nilai_bastb) AS total_nilai_bastb
				FROM (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_provinsi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang
					END) AS total_unit,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang
					END) AS total_nilai,
					0 AS total_unit_alokasi,
					0 AS total_nilai_alokasi,
					0 AS total_unit_baphp,
					0 AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_provinsi peny ON hd.id_penyedia_provinsi = peny.id
					WHERE 1=1";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;
					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_provinsi = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or dt.id_provinsi = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}
			$qry .= " GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_provinsi

					UNION

					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_provinsi,
					0 AS total_unit, 0 AS total_nilai,  
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang
					END) AS total_unit_alokasi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang
					END) AS total_nilai_alokasi,
					0 AS total_unit_baphp,
					0 AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_provinsi peny ON hd.id_penyedia_provinsi = peny.id
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan; 
					
					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_provinsi = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or dt.id_provinsi = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}
			$qry .= " GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_provinsi
					
					UNION
					
					SELECT bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten,
					bap.nama_barang, bap.merk, bap.no_kontrak, peny.nama_penyedia_provinsi,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					SUM(jumlah_barang) AS total_unit_baphp, SUM(`nilai_barang`) AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_baphp_provinsi bap
					LEFT JOIN tb_kontrak_provinsi kon ON bap.no_kontrak = kon.no_kontrak 
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					LEFT JOIN tb_jenis_barang_provinsi jb ON bap.nama_barang  = jb.nama_barang and bap.merk = jb.merk
					INNER JOIN tb_penyedia_provinsi peny ON jb.id_penyedia_provinsi = peny.id and peny.id_provinsi = bap.id_provinsi_penerima
					WHERE 1=1";
					$qry .= " AND kon.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( bap.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or bap.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( bap.id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or bap.id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( bap.id_kabupaten_penerima = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or bap.id_kabupaten_penerima = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}
			$qry .= " GROUP BY bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten,
					bap.nama_barang, bap.merk, bap.no_kontrak, peny.nama_penyedia_provinsi
					
					UNION
					
					SELECT bas.id_provinsi_penyerah, pro.`nama_provinsi`, bas.id_kabupaten_penyerah, kab.nama_kabupaten,
					bas.nama_barang, bas.merk, bas.no_kontrak, peny.nama_penyedia_provinsi, 
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					0 AS total_unit_baphp, 0 AS total_nilai_baphp,
					SUM(jumlah_barang) AS total_unit_bastb, SUM(`nilai_barang`) AS total_nilai_bastb
					FROM `tb_bastb_provinsi` bas
					LEFT JOIN tb_provinsi pro ON bas.`id_provinsi_penyerah` = pro.`id`
					LEFT JOIN tb_kontrak_provinsi kon ON bas.no_kontrak = kon.no_kontrak 
					LEFT JOIN tb_kabupaten kab ON bas.`id_kabupaten_penyerah` = kab.`id`
					LEFT JOIN tb_jenis_barang_provinsi jb ON bas.nama_barang  = jb.nama_barang and bas.merk = jb.merk
					INNER JOIN tb_penyedia_provinsi peny ON jb.id_penyedia_provinsi = peny.id and peny.id_provinsi = bas.id_provinsi_penyerah
					WHERE 1=1";
					$qry .= " AND kon.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( bas.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or bas.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( bas.id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or bas.id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( bas.id_kabupaten_penyerah = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or bas.id_kabupaten_penyerah = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}
			$qry .= " GROUP BY bas.id_provinsi_penyerah, pro.`nama_provinsi`, bas.id_kabupaten_penyerah, kab.nama_kabupaten,
					bas.nama_barang, bas.merk, bas.no_kontrak, peny.nama_penyedia_provinsi
				) AS uni
				WHERE 1=1 ";
				$qry .= " AND tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and uni.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and uni.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				if(isset($this->session->userdata('logged_in')->id_penyedia_provinsi)){
					$qry .= "
						and uni.nama_penyedia_provinsi = ( SELECT nama_penyedia_provinsi from tb_penyedia_provinsi where id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi.")";
				}

			$qry .= $filter;

			$qry .= " GROUP BY uni.id_provinsi, uni.nama_provinsi, uni.nama_kabupaten,
				uni.nama_barang, uni.merk, uni.no_kontrak, uni.nama_penyedia_provinsi";

			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetAllForAjaxDetail($start, $length, $col, $dir, $filter = '', $list_nama_barang = '', $list_id_provinsi = '', $list_id_kabupaten = '')
		{

			$qry = "
				SELECT uni.id_provinsi, uni.nama_provinsi, uni.nama_kabupaten,
				uni.nama_barang, uni.merk, uni.no_kontrak, uni.nama_penyedia_provinsi, 
				SUM(total_unit) AS total_unit, SUM(total_nilai) AS total_nilai,
				SUM(total_unit_alokasi) AS total_unit_alokasi, SUM(total_nilai_alokasi) AS total_nilai_alokasi,
				SUM(total_unit_baphp) AS total_unit_baphp, SUM(total_nilai_baphp) AS total_nilai_baphp,
				SUM(total_unit_bastb) AS total_unit_bastb, SUM(total_nilai_bastb) AS total_nilai_bastb
				FROM (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_provinsi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang
					END) AS total_unit,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang
					END) AS total_nilai,
					0 AS total_unit_alokasi,
					0 AS total_nilai_alokasi,
					0 AS total_unit_baphp,
					0 AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_provinsi peny ON hd.id_penyedia_provinsi = peny.id
					WHERE 1=1";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;
					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_provinsi = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or dt.id_provinsi = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}
			$qry .= " GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_provinsi

					UNION

					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_provinsi,
					0 AS total_unit, 0 AS total_nilai,  
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang
					END) AS total_unit_alokasi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang
					END) AS total_nilai_alokasi,
					0 AS total_unit_baphp,
					0 AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_provinsi peny ON hd.id_penyedia_provinsi = peny.id
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan; 
					
					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_provinsi = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or dt.id_provinsi = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}

			$qry .= " GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_provinsi
					
					UNION
					
					SELECT bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten,
					bap.nama_barang, bap.merk, bap.no_kontrak, peny.nama_penyedia_provinsi,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					SUM(jumlah_barang) AS total_unit_baphp, SUM(`nilai_barang`) AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_baphp_provinsi bap
					LEFT JOIN tb_kontrak_provinsi kon ON bap.no_kontrak = kon.no_kontrak 
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					LEFT JOIN tb_jenis_barang_provinsi jb ON bap.nama_barang  = jb.nama_barang and bap.merk = jb.merk
					INNER JOIN tb_penyedia_provinsi peny ON jb.id_penyedia_provinsi = peny.id and peny.id_provinsi = bap.id_provinsi_penerima
					WHERE 1=1";
					$qry .= " AND kon.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( bap.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or bap.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( bap.id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or bap.id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( bap.id_kabupaten_penerima = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or bap.id_kabupaten_penerima = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}

			$qry .= " GROUP BY bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten,
					bap.nama_barang, bap.merk, bap.no_kontrak, peny.nama_penyedia_provinsi
					
					UNION
					
					SELECT bas.id_provinsi_penyerah, pro.`nama_provinsi`, bas.id_kabupaten_penyerah, kab.nama_kabupaten,
					bas.nama_barang, bas.merk, bas.no_kontrak, peny.nama_penyedia_provinsi, 
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					0 AS total_unit_baphp, 0 AS total_nilai_baphp,
					SUM(jumlah_barang) AS total_unit_bastb, SUM(`nilai_barang`) AS total_nilai_bastb
					FROM `tb_bastb_provinsi` bas
					LEFT JOIN tb_kontrak_provinsi kon ON bas.no_kontrak = kon.no_kontrak 
					LEFT JOIN tb_provinsi pro ON bas.`id_provinsi_penyerah` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bas.`id_kabupaten_penyerah` = kab.`id`
					LEFT JOIN tb_jenis_barang_provinsi jb ON bas.nama_barang  = jb.nama_barang and bas.merk = jb.merk
					INNER JOIN tb_penyedia_provinsi peny ON jb.id_penyedia_provinsi = peny.id and peny.id_provinsi = bas.id_provinsi_penyerah
					WHERE 1=1";
					$qry .= " AND kon.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( bas.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or bas.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( bas.id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or bas.id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( bas.id_kabupaten_penyerah = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or bas.id_kabupaten_penyerah = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}
			$qry .= " GROUP BY bas.id_provinsi_penyerah, pro.`nama_provinsi`, bas.id_kabupaten_penyerah, kab.nama_kabupaten,
					bas.nama_barang, bas.merk, bas.no_kontrak, peny.nama_penyedia_provinsi
				) AS uni
				WHERE 1=1 ";
				$qry .= " AND tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and uni.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and uni.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				if(isset($this->session->userdata('logged_in')->id_penyedia_provinsi)){
					$qry .= "
						and uni.nama_penyedia_provinsi = ( SELECT nama_penyedia_provinsi from tb_penyedia_provinsi where id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi.")";
				}

			$qry .= $filter;

			$qry .= " GROUP BY uni.id_provinsi, uni.nama_provinsi, uni.nama_kabupaten,
				uni.nama_barang, uni.merk, uni.no_kontrak, uni.nama_penyedia_provinsi";

			$qry .= ' ORDER BY '.$col.' '.$dir;
			if($length > 0)
				$qry .=	' LIMIT ' . $start . ',' . $length;

			// die($qry);
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetSearchAjaxDetail($start, $length, $search, $col, $dir, $filter = '', $list_nama_barang = '', $list_id_provinsi = '', $list_id_kabupaten = '')
		{

			$qry = "
				SELECT uni.id_provinsi, uni.nama_provinsi, uni.nama_kabupaten,
				uni.nama_barang, uni.merk, uni.no_kontrak, uni.nama_penyedia_provinsi, 
				SUM(total_unit) AS total_unit, SUM(total_nilai) AS total_nilai,
				SUM(total_unit_alokasi) AS total_unit_alokasi, SUM(total_nilai_alokasi) AS total_nilai_alokasi,
				SUM(total_unit_baphp) AS total_unit_baphp, SUM(total_nilai_baphp) AS total_nilai_baphp,
				SUM(total_unit_bastb) AS total_unit_bastb, SUM(total_nilai_bastb) AS total_nilai_bastb
				FROM (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_provinsi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang
					END) AS total_unit,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang
					END) AS total_nilai,
					0 AS total_unit_alokasi,
					0 AS total_nilai_alokasi,
					0 AS total_unit_baphp,
					0 AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_provinsi peny ON hd.id_penyedia_provinsi = peny.id
					WHERE 1=1";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;
					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_provinsi = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or dt.id_provinsi = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}
			$qry .= " GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_provinsi

					UNION

					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_provinsi,
					0 AS total_unit, 0 AS total_nilai,  
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang
					END) AS total_unit_alokasi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang
					END) AS total_nilai_alokasi,
					0 AS total_unit_baphp,
					0 AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_alokasi_provinsi dt
					LEFT JOIN tb_kontrak_provinsi hd ON dt.`id_kontrak_provinsi` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_provinsi peny ON hd.id_penyedia_provinsi = peny.id
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					$qry .= " AND hd.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan; 
					
					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_provinsi = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or dt.id_provinsi = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or dt.id_kabupaten = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}

			$qry .= " GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_provinsi
					
					UNION
					
					SELECT bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten,
					bap.nama_barang, bap.merk, bap.no_kontrak, peny.nama_penyedia_provinsi,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					SUM(jumlah_barang) AS total_unit_baphp, SUM(`nilai_barang`) AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_baphp_provinsi bap
					LEFT JOIN tb_kontrak_provinsi kon ON bap.no_kontrak = kon.no_kontrak 
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					LEFT JOIN tb_jenis_barang_provinsi jb ON bap.nama_barang  = jb.nama_barang and bap.merk = jb.merk
					INNER JOIN tb_penyedia_provinsi peny ON jb.id_penyedia_provinsi = peny.id and peny.id_provinsi = bap.id_provinsi_penerima
					WHERE 1=1";
					$qry .= " AND kon.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( bap.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or bap.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( bap.id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or bap.id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( bap.id_kabupaten_penerima = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or bap.id_kabupaten_penerima = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}

			$qry .= " GROUP BY bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten,
					bap.nama_barang, bap.merk, bap.no_kontrak, peny.nama_penyedia_provinsi
					
					UNION
					
					SELECT bas.id_provinsi_penyerah, pro.`nama_provinsi`, bas.id_kabupaten_penyerah, kab.nama_kabupaten,
					bas.nama_barang, bas.merk, bas.no_kontrak, peny.nama_penyedia_provinsi, 
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					0 AS total_unit_baphp, 0 AS total_nilai_baphp,
					SUM(jumlah_barang) AS total_unit_bastb, SUM(`nilai_barang`) AS total_nilai_bastb
					FROM `tb_bastb_provinsi` bas
					LEFT JOIN tb_kontrak_provinsi kon ON bas.no_kontrak = kon.no_kontrak 
					LEFT JOIN tb_provinsi pro ON bas.`id_provinsi_penyerah` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bas.`id_kabupaten_penyerah` = kab.`id`
					LEFT JOIN tb_jenis_barang_provinsi jb ON bas.nama_barang  = jb.nama_barang and bas.merk = jb.merk
					INNER JOIN tb_penyedia_provinsi peny ON jb.id_penyedia_provinsi = peny.id and peny.id_provinsi = bas.id_provinsi_penyerah
					WHERE 1=1";
					$qry .= " AND kon.tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

					if($list_nama_barang != ''){

						for($i=0; $i<count($list_nama_barang); $i++){
							if($i == 0)
								$qry .= " and ( bas.nama_barang = '".$list_nama_barang[$i]."'";
							else
								$qry .= " or bas.nama_barang = '".$list_nama_barang[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_provinsi != ''){

						for($i=0; $i<count($list_id_provinsi); $i++){
							if($i == 0)
								$qry .= " and ( bas.id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
							else
								$qry .= " or bas.id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
						}

						$qry .= ")";

					}

					if($list_id_kabupaten != ''){

						for($i=0; $i<count($list_id_kabupaten); $i++){
							if($i == 0)
								$qry .= " and ( bas.id_kabupaten_penyerah = '".$list_id_kabupaten[$i]."'";
							else
								$qry .= " or bas.id_kabupaten_penyerah = '".$list_id_kabupaten[$i]."'";
						}

						$qry .= ")";

					}

			$qry .= " GROUP BY bas.id_provinsi_penyerah, pro.`nama_provinsi`, bas.id_kabupaten_penyerah, kab.nama_kabupaten,
					bas.nama_barang, bas.merk, bas.no_kontrak, peny.nama_penyedia_provinsi
				) AS uni
				WHERE 1=1 ";
				$qry .= " AND tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;

				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and uni.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and uni.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				if(isset($this->session->userdata('logged_in')->id_penyedia_provinsi)){
					$qry .= "
						and uni.nama_penyedia_provinsi = ( SELECT nama_penyedia_provinsi from tb_penyedia_provinsi where id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi.")";
				}

			$qry .= " and (
					uni.nama_provinsi LIKE '%$search%'
					or uni.nama_kabupaten LIKE '%$search%'
					or uni.nama_barang LIKE '%$search%'
					or uni.merk LIKE '%$search%'
					or uni.no_kontrak LIKE '%$search%'
					or uni.nama_penyedia_provinsi LIKE '%$search%'
				)";

			$qry .= $filter;

			$qry .= " GROUP BY uni.id_provinsi, uni.nama_provinsi, uni.nama_kabupaten,
				uni.nama_barang, uni.merk, uni.no_kontrak, uni.nama_penyedia_provinsi";

			$qry .= ' ORDER BY '.$col.' '.$dir;
			if($length > 0)
				$qry .=	' LIMIT ' . $start . ',' . $length;
			
			// die($qry);
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

	}
?>