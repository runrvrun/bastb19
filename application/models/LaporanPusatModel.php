<?php
	class LaporanPusatModel extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function GetTotalKontrak($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia)
		{
			$tahun_anggaran = $this->session->userdata('logged_in')->tahun_pengadaan;
			$qry =	"	
				SELECT COUNT(DISTINCT(id)) AS total 
				FROM tb_kontrak_pusat 
				where `tahun_anggaran` = ".$tahun_anggaran." 
				and id in (
					select id_kontrak_pusat from tb_alokasi_pusat
						where 1=1 and status_alokasi NOT IN ('MENUNGGU KONFIRMASI')";
			
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
						$qry .= " and ( id_penyedia_pusat = ".$list_id_penyedia[$i];
					else
						$qry .= " or id_penyedia_pusat = ".$list_id_penyedia[$i];
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
			$tahun_anggaran = $this->session->userdata('logged_in')->tahun_pengadaan;
			$qry =	"	
				SELECT COUNT(DISTINCT(nama_barang)) AS total 
				FROM tb_kontrak_pusat 
				where `tahun_anggaran` = ".$tahun_anggaran." 
				and id in (
					select id_kontrak_pusat from tb_alokasi_pusat
						where 1=1 and status_alokasi NOT IN ('MENUNGGU KONFIRMASI')";
			
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
						$qry .= " and ( id_penyedia_pusat = ".$list_id_penyedia[$i];
					else
						$qry .= " or id_penyedia_pusat = ".$list_id_penyedia[$i];
				}

				$qry .= ")";
			}

			

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}

		function GetTotalBAPHPREG($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia)
		{
			$tahun_anggaran = $this->session->userdata('logged_in')->tahun_pengadaan;
			$qry =	"	
				SELECT COUNT(DISTINCT(id)) AS total 
				FROM tb_baphp_reguler
				where `tahun_anggaran` = ".$tahun_anggaran." 
				and merk in (
					select merk from tb_jenis_barang_pusat
						where 1=1";
			
			if($list_id_penyedia != ''){

				for($i=0; $i<count($list_id_penyedia); $i++){
					if($i == 0)
						$qry .= " and ( id_penyedia_pusat = ".$list_id_penyedia[$i];
					else
						$qry .= " or id_penyedia_pusat = ".$list_id_penyedia[$i];
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
		function GetTotalBAPHPCAD($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia)
		{
			$tahun_anggaran = $this->session->userdata('logged_in')->tahun_pengadaan;
			$qry =	"	
				SELECT COUNT(DISTINCT(id)) AS total 
				FROM tb_baphp_persediaan
				where `tahun_anggaran` = ".$tahun_anggaran." 
				and merk in (
					select merk from tb_jenis_barang_pusat
						where 1=1";
			
			if($list_id_penyedia != ''){

				for($i=0; $i<count($list_id_penyedia); $i++){
					if($i == 0)
						$qry .= " and ( id_penyedia_pusat = ".$list_id_penyedia[$i];
					else
						$qry .= " or id_penyedia_pusat = ".$list_id_penyedia[$i];
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

		function GetTotalBASTB($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia)
		{
			$tahun_anggaran = $this->session->userdata('logged_in')->tahun_pengadaan;
			$qry =	"	
				SELECT COUNT(DISTINCT(id)) AS total 
				FROM tb_bastb_pusat 
				where `tahun_anggaran` = ".$tahun_anggaran." 
				and merk in (
					select merk from tb_jenis_barang_pusat
						where 1=1";
			
			if($list_id_penyedia != ''){

				for($i=0; $i<count($list_id_penyedia); $i++){
					if($i == 0)
						$qry .= " and ( id_penyedia_pusat = ".$list_id_penyedia[$i];
					else
						$qry .= " or id_penyedia_pusat = ".$list_id_penyedia[$i];
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
			$tahun_anggaran = $this->session->userdata('logged_in')->tahun_pengadaan;
			$qry =	"	
				SELECT SUM(t.jumlah_alokasi) as total
					FROM tb_kontrak_pusat hd INNER JOIN tb_penyedia_pusat peny ON hd.`id_penyedia_pusat` = peny.id
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
						FROM tb_alokasi_pusat dt 
						LEFT JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
						WHERE 1=1 ";
			$qry .= " AND `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
			
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

			$qry .= "
			GROUP BY id_header, dt.status_alokasi) AS temp
				GROUP BY temp.id_header
			) AS t ON hd.id = t.id_header
			WHERE hd.`tahun_anggaran` = ".$tahun_anggaran;

			if($list_id_penyedia != ''){

				for($i=0; $i<count($list_id_penyedia); $i++){
					if($i == 0)
						$qry .= " and ( id_penyedia_pusat = ".$list_id_penyedia[$i];
					else
						$qry .= " or id_penyedia_pusat = ".$list_id_penyedia[$i];
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
			$tahun_anggaran = $this->session->userdata('logged_in')->tahun_pengadaan;
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
				FROM tb_alokasi_pusat dt 
				LEFT JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
				WHERE 
					dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')
					and hd.`tahun_anggaran` = ".$tahun_anggaran;

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
						$qry .= " and ( hd.id_penyedia_pusat = ".$list_id_penyedia[$i];
					else
						$qry .= " or hd.id_penyedia_pusat = ".$list_id_penyedia[$i];
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

		function GetTotalUnitBAPHPREG($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia)
		{
			$tahun_anggaran = $this->session->userdata('logged_in')->tahun_pengadaan;
			$qry =	"	
				SELECT SUM(jumlah_barang) AS total 
				FROM tb_baphp_reguler
				where `tahun_anggaran` = ".$tahun_anggaran." 
				and merk in (
					select merk from tb_jenis_barang_pusat
						where 1=1";
			
			if($list_id_penyedia != ''){

				for($i=0; $i<count($list_id_penyedia); $i++){
					if($i == 0)
						$qry .= " and ( id_penyedia_pusat = ".$list_id_penyedia[$i];
					else
						$qry .= " or id_penyedia_pusat = ".$list_id_penyedia[$i];
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
		function GetTotalUnitBAPHPCAD($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia)
		{
			$tahun_anggaran = $this->session->userdata('logged_in')->tahun_pengadaan;
			$qry =	"	
				SELECT SUM(jumlah_barang) AS total 
				FROM tb_baphp_persediaan
				where `tahun_anggaran` = ".$tahun_anggaran." 
				and merk in (
					select merk from tb_jenis_barang_pusat
						where 1=1";
			
			if($list_id_penyedia != ''){

				for($i=0; $i<count($list_id_penyedia); $i++){
					if($i == 0)
						$qry .= " and ( id_penyedia_pusat = ".$list_id_penyedia[$i];
					else
						$qry .= " or id_penyedia_pusat = ".$list_id_penyedia[$i];
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

		function GetTotalUnitBASTB($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia)
		{
			$tahun_anggaran = $this->session->userdata('logged_in')->tahun_pengadaan;
			$qry =	"	
				SELECT SUM(jumlah_barang) AS total 
				FROM tb_bastb_pusat 
				where `tahun_anggaran` = ".$tahun_anggaran." 
				and merk in (
					select merk from tb_jenis_barang_pusat
						where 1=1";
			
			if($list_id_penyedia != ''){

				for($i=0; $i<count($list_id_penyedia); $i++){
					if($i == 0)
						$qry .= " and ( id_penyedia_pusat = ".$list_id_penyedia[$i];
					else
						$qry .= " or id_penyedia_pusat = ".$list_id_penyedia[$i];
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
			$tahun_anggaran = $this->session->userdata('logged_in')->tahun_pengadaan;
			$qry =	"	
				SELECT SUM(t.nilai_alokasi) as total
					FROM tb_kontrak_pusat hd INNER JOIN tb_penyedia_pusat peny ON hd.`id_penyedia_pusat` = peny.id
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
						FROM tb_alokasi_pusat dt 
						LEFT JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
						WHERE 1=1 ";
						$qry .= " AND `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
			
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

			$qry .= "
			GROUP BY id_header, dt.status_alokasi) AS temp
				GROUP BY temp.id_header
			) AS t ON hd.id = t.id_header
			WHERE hd.`tahun_anggaran` = ".$tahun_anggaran;

			if($list_id_penyedia != ''){

				for($i=0; $i<count($list_id_penyedia); $i++){
					if($i == 0)
						$qry .= " and ( id_penyedia_pusat = ".$list_id_penyedia[$i];
					else
						$qry .= " or id_penyedia_pusat = ".$list_id_penyedia[$i];
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
			$tahun_anggaran = $this->session->userdata('logged_in')->tahun_pengadaan;
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
				FROM tb_alokasi_pusat dt 
				LEFT JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
				WHERE 
					dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')
					and hd.`tahun_anggaran` = ".$tahun_anggaran;

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
						$qry .= " and ( hd.id_penyedia_pusat = ".$list_id_penyedia[$i];
					else
						$qry .= " or hd.id_penyedia_pusat = ".$list_id_penyedia[$i];
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

		function GetTotalNilaiBAPHPREG($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia)
		{
			$tahun_anggaran = $this->session->userdata('logged_in')->tahun_pengadaan;
			$qry =	"	
				SELECT SUM(nilai_barang) AS total 
				FROM tb_baphp_reguler
				where `tahun_anggaran` = ".$tahun_anggaran." 
				and merk in (
					select merk from tb_jenis_barang_pusat
						where 1=1";
			
			if($list_id_penyedia != ''){

				for($i=0; $i<count($list_id_penyedia); $i++){
					if($i == 0)
						$qry .= " and ( id_penyedia_pusat = ".$list_id_penyedia[$i];
					else
						$qry .= " or id_penyedia_pusat = ".$list_id_penyedia[$i];
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
		function GetTotalNilaiBAPHPCAD($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia)
		{
			$tahun_anggaran = $this->session->userdata('logged_in')->tahun_pengadaan;
			$qry =	"	
				SELECT SUM(nilai_barang) AS total 
				FROM tb_baphp_persediaan
				where `tahun_anggaran` = ".$tahun_anggaran." 
				and merk in (
					select merk from tb_jenis_barang_pusat
						where 1=1";
			
			if($list_id_penyedia != ''){

				for($i=0; $i<count($list_id_penyedia); $i++){
					if($i == 0)
						$qry .= " and ( id_penyedia_pusat = ".$list_id_penyedia[$i];
					else
						$qry .= " or id_penyedia_pusat = ".$list_id_penyedia[$i];
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
			$tahun_anggaran = $this->session->userdata('logged_in')->tahun_pengadaan;
			$qry =	"	
				SELECT SUM(nilai_barang) AS total 
				FROM tb_bastb_pusat 
				where `tahun_anggaran` = ".$tahun_anggaran." 
				and merk in (
					select merk from tb_jenis_barang_pusat
						where 1=1";
			
			if($list_id_penyedia != ''){

				for($i=0; $i<count($list_id_penyedia); $i++){
					if($i == 0)
						$qry .= " and ( id_penyedia_pusat = ".$list_id_penyedia[$i];
					else
						$qry .= " or id_penyedia_pusat = ".$list_id_penyedia[$i];
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
				SUM(alo.total_unit) AS total_unit_alokasi,
				SUM(alo.total_nilai) AS total_nilai_alokasi,
				SUM(bap_reg.total_barang) AS total_unit_bapreg,
				SUM(bap_reg.total_nilai) AS total_nilai_bapreg,
				SUM(bap_cad.total_barang) AS total_unit_bapcad,
				SUM(bap_cad.total_nilai) AS total_nilai_bapcad,
				SUM(bas.total_barang) AS total_unit_bastb,
				SUM(bas.total_nilai) AS total_nilai_bastb
				FROM
				tb_alokasi_pusat dt
				LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
				LEFT JOIN tb_alokasi_persediaan_pusat alp ON alp.id_alokasi = dt.id
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
					tb_alokasi_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					$qry .= " AND `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
					// if (isset($this->session->userdata('logged_in')->id_provinsi)) $qry .= " and pro.id = ".$this->session->userdata('logged_in')->id_provinsi;

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
					FROM tb_baphp_reguler";
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
					SELECT id_provinsi_penerima, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_baphp_persediaan`";
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
					GROUP BY id_provinsi_penerima
				) AS bap_cad ON bap_cad.id_provinsi_penerima = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_provinsi_penyerah, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_bastb_pusat`";
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
				$qry .= " AND `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;

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
				SUM(alo.total_unit) AS total_unit_alokasi,
				SUM(alo.total_nilai) AS total_nilai_alokasi,
				SUM(bap_reg.total_barang) AS total_unit_bapreg,
				SUM(bap_reg.total_nilai) AS total_nilai_bapreg,
				SUM(bap_cad.total_barang) AS total_unit_bapcad,
				SUM(bap_cad.total_nilai) AS total_nilai_bapcad,
				SUM(bas.total_barang) AS total_unit_bastb,
				SUM(bas.total_nilai) AS total_nilai_bastb
				FROM
				tb_alokasi_pusat dt
				LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
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
					tb_alokasi_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					$qry .= " AND `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;

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
					FROM tb_baphp_reguler";
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
					SELECT id_provinsi_penerima, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_baphp_persediaan`";
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
					GROUP BY id_provinsi_penerima
				) AS bap_cad ON bap_cad.id_provinsi_penerima = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_provinsi_penyerah, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_bastb_pusat`";
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
				$qry .= " AND `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;

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
				SUM(alo.total_unit) AS total_unit_alokasi,
				SUM(alo.total_nilai) AS total_nilai_alokasi,
				SUM(bap_reg.total_barang) AS total_unit_bapreg,
				SUM(bap_reg.total_nilai) AS total_nilai_bapreg,
				SUM(bap_cad.total_barang) AS total_unit_bapcad,
				SUM(bap_cad.total_nilai) AS total_nilai_bapcad,
				SUM(bas.total_barang) AS total_unit_bastb,
				SUM(bas.total_nilai) AS total_nilai_bastb
				FROM
				tb_alokasi_pusat dt
				LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
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
					tb_alokasi_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					$qry .= " AND `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;

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
					FROM tb_baphp_reguler";
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
					SELECT id_provinsi_penerima, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_baphp_persediaan`";
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
					GROUP BY id_provinsi_penerima
				) AS bap_cad ON bap_cad.id_provinsi_penerima = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_provinsi_penyerah, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_bastb_pusat`";
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
				$qry .= " AND `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;

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
			if($col == 'nama_provinsi'){
				$col = 'IFNULL(alocad.`nama_provinsi`,alo.`nama_provinsi`)';
			}
			$qry = "
				SELECT IFNULL(alocad.`id_provinsi`,alo.`id_provinsi`) as id_provinsi, IFNULL(alocad.`nama_provinsi`,alo.`nama_provinsi`) as nama_provinsi, 
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
				alocad.total_barang AS total_unit_alokasicad,
				alocad.total_nilai AS total_nilai_alokasicad,
				bap_reg.total_barang AS total_unit_bapreg,
				bap_reg.total_nilai AS total_nilai_bapreg,
				bap_cad.total_barang AS total_unit_bapcad,
				bap_cad.total_nilai AS total_nilai_bapcad,
				bas.total_barang AS total_unit_bastb,
				bas.total_nilai AS total_nilai_bastb,
				bascad.total_barang AS total_unit_bastbcad,
				bascad.total_nilai AS total_nilai_bastbcad
				FROM
				tb_alokasi_pusat dt
				INNER JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
				LEFT JOIN (
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
					END) AS total_nilai
					FROM
					tb_alokasi_pusat dt
					INNER JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					INNER JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					$qry .= " AND `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
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
				) AS alo ON alo.id_provinsi = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_alokasi, pro.id as id_provinsi, pro.nama_provinsi, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM tb_alokasi_persediaan_pusat dt
					INNER JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`";
				$qry .= " GROUP BY id_alokasi
				) AS alocad ON alocad.id_alokasi = dt.id				
				LEFT JOIN (
					SELECT id_provinsi_penerima, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM tb_baphp_reguler";
					$qry .= " WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
				$qry .= " GROUP BY id_provinsi_penerima
				) AS bap_reg ON bap_reg.id_provinsi_penerima = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_provinsi_penerima, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_baphp_persediaan`";
					$qry .= " WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
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
				) AS bap_cad ON bap_cad.id_provinsi_penerima = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_provinsi_penyerah, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_bastb_pusat`";
					$qry .= " WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;					
				$qry .= "
				) AS bas ON bas.id_provinsi_penyerah = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_provinsi_penyerah, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_bastb_persediaan`";
					$qry .= " WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;					
				$qry .= "
				) AS bascad ON bascad.id_provinsi_penyerah = dt.`id_provinsi`				
				WHERE 1=1"; 
				$qry .= " AND `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
			
			$qry .= $filter;

			$qry .= ' GROUP BY IFNULL(alocad.`id_provinsi`,alo.`id_provinsi`) ORDER BY '.$col.' '.$dir;
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
				alocad.total_barang AS total_unit_alokasicad,
				alocad.total_nilai AS total_nilai_alokasicad,
				bap_reg.total_barang AS total_unit_bapreg,
				bap_reg.total_nilai AS total_nilai_bapreg,
				bap_cad.total_barang AS total_unit_bapcad,
				bap_cad.total_nilai AS total_nilai_bapcad,
				bas.total_barang AS total_unit_bastb,
				bas.total_nilai AS total_nilai_bastb,
				bascad.total_barang AS total_unit_bastbcad,
				bascad.total_nilai AS total_nilai_bastbcad
				FROM
				tb_alokasi_pusat dt
				LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
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
					tb_alokasi_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";

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
					SELECT id_alokasi, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM tb_alokasi_persediaan_pusat";
				$qry .= " GROUP BY id_alokasi
				) AS alocad ON alocad.id_alokasi = dt.id								
				LEFT JOIN (
					SELECT id_provinsi_penerima, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM tb_baphp_reguler";
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
					SELECT id_provinsi_penerima, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_baphp_persediaan`";
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
					GROUP BY id_provinsi_penerima
				) AS bap_cad ON bap_cad.id_provinsi_penerima = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_provinsi_penyerah, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_bastb_pusat`";				
				$qry .= "
					GROUP BY id_provinsi_penyerah
				) AS bas ON bas.id_provinsi_penyerah = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_provinsi_penyerah, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_bastb_persediaan`";				
				$qry .= "
					GROUP BY id_provinsi_penyerah
				) AS bascad ON bascad.id_provinsi_penyerah = dt.`id_provinsi`
				WHERE 1=1";
				$qry .= " AND `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;

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

			$qry = "SELECT nama_provinsi, alo.jumlah_barang alokasi_reguler, bap.jumlah_barang baphp_reguler, bas.jumlah_barang bastb_reguler
			, alop.jumlah_barang alokasi_persediaan, bapp.jumlah_barang baphp_persediaan, basp.jumlah_barang bastb_persediaan
			, alo.nilai_barang alokasi_reguler_nilai, bap.nilai_barang baphp_reguler_nilai, bas.nilai_barang bastb_reguler_nilai
			, alop.nilai_barang alokasi_persediaan_nilai, bapp.nilai_barang baphp_persediaan_nilai, basp.nilai_barang bastb_persediaan_nilai			
			FROM tb_provinsi pro
			LEFT JOIN (
			SELECT id_provinsi, SUM(CASE 	
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
					FROM tb_alokasi_pusat alo 
					INNER JOIN tb_kontrak_pusat kon ON alo.id_kontrak_pusat=kon.id AND tahun_anggaran=".$this->session->userdata('logged_in')->tahun_pengadaan."
					GROUP BY id_provinsi
			)alo ON alo.id_provinsi = pro.id
			LEFT JOIN (
			SELECT id_provinsi_penerima, SUM(jumlah_barang) jumlah_barang, SUM(nilai_barang) nilai_barang
			FROM tb_baphp_reguler
			WHERE tahun_anggaran=".$this->session->userdata('logged_in')->tahun_pengadaan."
			GROUP BY id_provinsi_penerima
			)bap ON bap.id_provinsi_penerima=pro.id
			LEFT JOIN (
			SELECT id_provinsi_penyerah, SUM(jumlah_barang) jumlah_barang, SUM(nilai_barang) nilai_barang
			FROM tb_bastb_pusat
			WHERE tahun_anggaran=".$this->session->userdata('logged_in')->tahun_pengadaan."
			GROUP BY id_provinsi_penyerah
			)bas ON bas.id_provinsi_penyerah=pro.id
			LEFT JOIN (
			SELECT alop.id_provinsi, SUM(alop.jumlah_barang) jumlah_barang, SUM(alop.nilai_barang) nilai_barang
					FROM tb_alokasi_persediaan_pusat alop
					INNER JOIN tb_alokasi_pusat alo ON alop.id_alokasi=alo.id
					INNER JOIN tb_kontrak_pusat kon ON alo.id_kontrak_pusat=kon.id AND tahun_anggaran=".$this->session->userdata('logged_in')->tahun_pengadaan."
					GROUP BY id_provinsi
			)alop ON alop.id_provinsi = pro.id
			LEFT JOIN (
			SELECT id_provinsi_penerima, SUM(jumlah_barang) jumlah_barang, SUM(nilai_barang) nilai_barang
			FROM tb_baphp_persediaan
			WHERE tahun_anggaran=".$this->session->userdata('logged_in')->tahun_pengadaan."
			GROUP BY id_provinsi_penerima
			)bapp ON bapp.id_provinsi_penerima=pro.id
			LEFT JOIN (
			SELECT id_provinsi_penyerah, SUM(jumlah_barang) jumlah_barang, SUM(nilai_barang) nilai_barang
			FROM tb_bastb_persediaan
			WHERE tahun_anggaran=".$this->session->userdata('logged_in')->tahun_pengadaan."
			GROUP BY id_provinsi_penyerah
			)basp ON basp.id_provinsi_penyerah=pro.id
			WHERE (alo.jumlah_barang > 0 OR alop.jumlah_barang > 0)";
			if(isset($param['search'])) $qry .= " ".$param['search'];
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

		function GetAllAjaxCountKabupaten($list_nama_barang = '')
		{
			$qry = "
				SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.`id_kabupaten`, kab.`nama_kabupaten`, 
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
				SUM(alo.total_unit) AS total_unit_alokasi,
				SUM(alo.total_nilai) AS total_nilai_alokasi,
				SUM(bap_reg.total_barang) AS total_unit_bapreg,
				SUM(bap_reg.total_nilai) AS total_nilai_bapreg,
				SUM(bap_cad.total_barang) AS total_unit_bapcad,
				SUM(bap_cad.total_nilai) AS total_nilai_bapcad,
				SUM(bas.total_barang) AS total_unit_bastb,
				SUM(bas.total_nilai) AS total_nilai_bastb
				FROM
				tb_alokasi_pusat dt
				LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
				LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
				LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
				LEFT JOIN (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.`id_kabupaten`, kab.`nama_kabupaten`,
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
					tb_alokasi_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
				WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					$qry .= " AND `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
					// if (isset($this->session->userdata('logged_in')->id_provinsi)) $qry .= " and pro.id = ".$this->session->userdata('logged_in')->id_provinsi;

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
					GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`, dt.`id_kabupaten`, kab.`nama_kabupaten`
				) AS alo ON alo.id_provinsi = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_provinsi_penerima, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM tb_baphp_reguler";
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
					SELECT id_provinsi_penerima, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_baphp_persediaan`";
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
					GROUP BY id_provinsi_penerima
				) AS bap_cad ON bap_cad.id_provinsi_penerima = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_provinsi_penyerah, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_bastb_pusat`";
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
				$qry .= " AND `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;

			if($list_nama_barang != ''){

				for($i=0; $i<count($list_nama_barang); $i++){
					if($i == 0)
						$qry .= " and ( hd.nama_barang = '".$list_nama_barang[$i]."'";
					else
						$qry .= " or hd.nama_barang = '".$list_nama_barang[$i]."'";
				}

				$qry .= ")";

			}

			$qry .= " GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`, dt.`id_kabupaten`, kab.`nama_kabupaten`";
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetSearchAjaxCountKabupaten($search, $filter = '', $list_nama_barang = '')
		{
			$qry = "
				SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.`id_kabupaten`, kab.`nama_kabupaten`,
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
				SUM(alo.total_unit) AS total_unit_alokasi,
				SUM(alo.total_nilai) AS total_nilai_alokasi,
				SUM(bap_reg.total_barang) AS total_unit_bapreg,
				SUM(bap_reg.total_nilai) AS total_nilai_bapreg,
				SUM(bap_cad.total_barang) AS total_unit_bapcad,
				SUM(bap_cad.total_nilai) AS total_nilai_bapcad,
				SUM(bas.total_barang) AS total_unit_bastb,
				SUM(bas.total_nilai) AS total_nilai_bastb
				FROM
				tb_alokasi_pusat dt
				LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
				LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
				LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
				LEFT JOIN (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.`id_kabupaten`, kab.`nama_kabupaten`,
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
					tb_alokasi_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					$qry .= " AND `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;

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
					GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`, dt.`id_kabupaten`, kab.`nama_kabupaten`
				) AS alo ON alo.id_provinsi = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_provinsi_penerima, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM tb_baphp_reguler";
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
					SELECT id_provinsi_penerima, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_baphp_persediaan`";
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
					GROUP BY id_provinsi_penerima
				) AS bap_cad ON bap_cad.id_provinsi_penerima = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_provinsi_penyerah, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_bastb_pusat`";
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
				$qry .= " AND `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;

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
			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and pro.id = ".$this->session->userdata('logged_in')->id_provinsi;
			}

			$qry .= ' GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`, dt.`id_kabupaten`, kab.`nama_kabupaten`';
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetFilterAjaxCountKabupaten($filter = '', $list_nama_barang = '')
		{
			$qry = "
				SELECT dt.`id_provinsi`, pro.`nama_provinsi`,dt.`id_kabupaten`, kab.`nama_kabupaten`, 
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
				SUM(alo.total_unit) AS total_unit_alokasi,
				SUM(alo.total_nilai) AS total_nilai_alokasi,
				SUM(bap_reg.total_barang) AS total_unit_bapreg,
				SUM(bap_reg.total_nilai) AS total_nilai_bapreg,
				SUM(bap_cad.total_barang) AS total_unit_bapcad,
				SUM(bap_cad.total_nilai) AS total_nilai_bapcad,
				SUM(bas.total_barang) AS total_unit_bastb,
				SUM(bas.total_nilai) AS total_nilai_bastb
				FROM
				tb_alokasi_pusat dt
				LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
				LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
				LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
				LEFT JOIN (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`,dt.`id_kabupaten`, kab.`nama_kabupaten`, 
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
					tb_alokasi_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					$qry .= " AND `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;

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
					GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`, dt.`id_kabupaten`, kab.`nama_kabupaten`
				) AS alo ON alo.id_provinsi = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_provinsi_penerima, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM tb_baphp_reguler";
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
					SELECT id_provinsi_penerima, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_baphp_persediaan`";
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
					GROUP BY id_provinsi_penerima
				) AS bap_cad ON bap_cad.id_provinsi_penerima = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_provinsi_penyerah, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_bastb_pusat`";
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
				$qry .= " AND `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;

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
			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and pro.id = ".$this->session->userdata('logged_in')->id_provinsi;
			}
			
			$qry .= ' GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`, dt.`id_kabupaten`, kab.`nama_kabupaten`';

			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetAllForAjaxKabupaten($start, $length, $col, $dir, $filter = '', $list_nama_barang = '')
		{

			$qry = "
				SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.`id_kabupaten`, kab.`nama_kabupaten`,
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
				alocad.total_barang AS total_unit_alokasicad, 
				alocad.total_nilai AS total_nilai_alokasicad,
				bap_reg.total_barang AS total_unit_baphp,
				bap_reg.total_nilai AS total_nilai_baphp,
				bap_cad.total_barang AS total_unit_baphpcad,
				bap_cad.total_nilai AS total_nilai_baphpcad,
				bas.total_barang AS total_unit_bastb,
				bas.total_nilai AS total_nilai_bastb,
				bascad.total_barang AS total_unit_bastbcad,
				bascad.total_nilai AS total_nilai_bastbcad
				FROM
				tb_alokasi_pusat dt
				INNER JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
				INNER JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
				LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
				LEFT JOIN (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.`id_kabupaten`, kab.`nama_kabupaten`,
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
					END) AS total_nilai
					FROM
					tb_alokasi_pusat dt
					INNER JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					INNER JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					$qry .= " AND `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
					
			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and pro.id = ".$this->session->userdata('logged_in')->id_provinsi;
			}
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
					GROUP BY dt.`id_provinsi`
				) AS alo ON alo.id_provinsi = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_alokasi, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM tb_alokasi_persediaan_pusat";
				$qry .= " GROUP BY id_alokasi
				) AS alocad ON alocad.id_alokasi = dt.id				
				LEFT JOIN (
					SELECT id_provinsi_penerima, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM tb_baphp_reguler";
					$qry .= " WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
				$qry .= " GROUP BY id_provinsi_penerima
				) AS bap_reg ON bap_reg.id_provinsi_penerima = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_provinsi_penerima, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_baphp_persediaan`";
					$qry .= " WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
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
					GROUP BY id_provinsi_penerima
				) AS bap_cad ON bap_cad.id_provinsi_penerima = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_provinsi_penyerah, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_bastb_pusat`";
					$qry .= " WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;					
				$qry .= "
					GROUP BY id_provinsi_penyerah
				) AS bas ON bas.id_provinsi_penyerah = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_provinsi_penyerah, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_bastb_persediaan`";
					$qry .= " WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;					
				$qry .= "
					GROUP BY id_provinsi_penyerah
				) AS bascad ON bascad.id_provinsi_penyerah = dt.`id_provinsi`				
				WHERE 1=1"; 
				$qry .= " AND `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;

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
		
			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and pro.id = ".$this->session->userdata('logged_in')->id_provinsi;
			}
			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and pro.id = ".$this->session->userdata('logged_in')->id_provinsi;
			}
			$qry .= ' GROUP BY dt.`id_provinsi` ORDER BY '.$col.' '.$dir;
			if($length > 0)
				$qry .=	' LIMIT ' . $start . ',' . $length;

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetSearchAjaxKabupaten($start, $length, $search, $col, $dir, $filter = '', $list_nama_barang = '')
		{

			$qry = "
				SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.`id_kabupaten`, kab.`nama_kabupaten`,
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
				alocad.total_barang AS total_unit_alokasicad,
				alocad.total_nilai AS total_nilai_alokasicad,
				bap_reg.total_barang AS total_unit_bapreg,
				bap_reg.total_nilai AS total_nilai_bapreg,
				bap_cad.total_barang AS total_unit_bapcad,
				bap_cad.total_nilai AS total_nilai_bapcad,
				bas.total_barang AS total_unit_bastb,
				bas.total_nilai AS total_nilai_bastb,
				bascad.total_barang AS total_unit_bastbcad,
				bascad.total_nilai AS total_nilai_bastbcad
				FROM
				tb_alokasi_pusat dt
				LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
				LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
				LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
				LEFT JOIN (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.`id_kabupaten`, kab.`nama_kabupaten`,
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
					tb_alokasi_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";

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
					GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`, dt.`id_kabupaten`, kab.`nama_kabupaten`
				) AS alo ON alo.id_provinsi = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_alokasi, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM tb_alokasi_persediaan_pusat";
				$qry .= " GROUP BY id_alokasi
				) AS alocad ON alocad.id_alokasi = dt.id								
				LEFT JOIN (
					SELECT id_provinsi_penerima, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM tb_baphp_reguler";
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
					SELECT id_provinsi_penerima, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_baphp_persediaan`";
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
					GROUP BY id_provinsi_penerima
				) AS bap_cad ON bap_cad.id_provinsi_penerima = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_provinsi_penyerah, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_bastb_pusat`";				
				$qry .= "
					GROUP BY id_provinsi_penyerah
				) AS bas ON bas.id_provinsi_penyerah = dt.`id_provinsi`
				LEFT JOIN (
					SELECT id_provinsi_penyerah, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_bastb_persediaan`";				
				$qry .= "
					GROUP BY id_provinsi_penyerah
				) AS bascad ON bascad.id_provinsi_penyerah = dt.`id_provinsi`
				WHERE 1=1";
				$qry .= " AND `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;

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
			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and pro.id = ".$this->session->userdata('logged_in')->id_provinsi;
			}

			$qry .= ' GROUP BY dt.`id_provinsi`, pro.`nama_provinsi`, dt.`id_kabupaten`, kab.`nama_kabupaten` ORDER BY '.$col.' '.$dir;
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

			$qry = "SELECT nama_provinsi, nama_kabupaten, alo.jumlah_barang alokasi_reguler, bap.jumlah_barang baphp_reguler, bas.jumlah_barang bastb_reguler
			, alop.jumlah_barang alokasi_persediaan, bapp.jumlah_barang baphp_persediaan, basp.jumlah_barang bastb_persediaan
			, alo.nilai_barang alokasi_reguler_nilai, bap.nilai_barang baphp_reguler_nilai, bas.nilai_barang bastb_reguler_nilai
			, alop.nilai_barang alokasi_persediaan_nilai, bapp.nilai_barang baphp_persediaan_nilai, basp.nilai_barang bastb_persediaan_nilai
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
					FROM tb_alokasi_pusat alo 
					INNER JOIN tb_kontrak_pusat kon ON alo.id_kontrak_pusat=kon.id AND tahun_anggaran=".$this->session->userdata('logged_in')->tahun_pengadaan."
					GROUP BY id_kabupaten
			)alo ON alo.id_kabupaten = kab.id
			LEFT JOIN (
			SELECT id_kabupaten_penerima, SUM(jumlah_barang) jumlah_barang, SUM(nilai_barang) nilai_barang
			FROM tb_baphp_reguler
			WHERE tahun_anggaran=".$this->session->userdata('logged_in')->tahun_pengadaan."
			GROUP BY id_kabupaten_penerima
			)bap ON bap.id_kabupaten_penerima=kab.id
			LEFT JOIN (
			SELECT id_kabupaten_penyerah, SUM(jumlah_barang) jumlah_barang, SUM(nilai_barang) nilai_barang
			FROM tb_bastb_pusat
			WHERE tahun_anggaran=".$this->session->userdata('logged_in')->tahun_pengadaan."
			GROUP BY id_kabupaten_penyerah
			)bas ON bas.id_kabupaten_penyerah=kab.id
			LEFT JOIN (
			SELECT alop.id_kabupaten, SUM(alop.jumlah_barang) jumlah_barang, SUM(alop.nilai_barang) nilai_barang
					FROM tb_alokasi_persediaan_pusat alop
					INNER JOIN tb_alokasi_pusat alo ON alop.id_alokasi=alo.id
					INNER JOIN tb_kontrak_pusat kon ON alo.id_kontrak_pusat=kon.id AND tahun_anggaran=".$this->session->userdata('logged_in')->tahun_pengadaan."
					GROUP BY id_kabupaten
			)alop ON alop.id_kabupaten = kab.id
			LEFT JOIN (
			SELECT id_kabupaten_penerima, SUM(jumlah_barang) jumlah_barang, SUM(nilai_barang) nilai_barang
			FROM tb_baphp_persediaan
			WHERE tahun_anggaran=".$this->session->userdata('logged_in')->tahun_pengadaan."
			GROUP BY id_kabupaten_penerima
			)bapp ON bapp.id_kabupaten_penerima=kab.id
			LEFT JOIN (
			SELECT id_kabupaten_penyerah, SUM(jumlah_barang) jumlah_barang, SUM(nilai_barang) nilai_barang
			FROM tb_bastb_persediaan
			WHERE tahun_anggaran=".$this->session->userdata('logged_in')->tahun_pengadaan."
			GROUP BY id_kabupaten_penyerah
			)basp ON basp.id_kabupaten_penyerah=kab.id
			WHERE (alo.jumlah_barang > 0 OR alop.jumlah_barang > 0)
			".$param['filter'];
			if(isset($param['search'])) $qry .= " ".$param['search'];
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
				SELECT dt.id
				FROM
				tb_alokasi_pusat dt
				LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
				LEFT JOIN tb_penyedia_pusat peny ON hd.`id_penyedia_pusat` = peny.`id`
				LEFT JOIN (
					SELECT hd.`no_kontrak`, hd.`nama_barang`, peny.nama_penyedia_pusat,
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
					tb_alokasi_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_penyedia_pusat peny ON hd.`id_penyedia_pusat` = peny.`id`";
					$qry .= " WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
					$qry .= " AND dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";

				if(isset($this->session->userdata('logged_in')->id_provinsi)) $qry .= " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;				
				if(isset($this->session->userdata('logged_in')->id_kabupaten)) $qry .= " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;	
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)) $qry .= " and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat;				

				$qry .= "
					GROUP BY hd.`no_kontrak`, hd.`nama_barang`, peny.nama_penyedia_pusat
				) AS alo ON alo.no_kontrak = hd.`no_kontrak` and alo.nama_barang = hd.nama_barang and alo.nama_penyedia_pusat = peny.nama_penyedia_pusat

				LEFT JOIN (
					SELECT no_kontrak, nama_barang, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM tb_baphp_reguler where 1=1";

				if(isset($this->session->userdata('logged_in')->id_provinsi)) $qry .= " and id_provinsi_penerima = ".$this->session->userdata('logged_in')->id_provinsi;				
				if(isset($this->session->userdata('logged_in')->id_kabupaten)) $qry .= " and id_kabupaten_penerima = ".$this->session->userdata('logged_in')->id_kabupaten;				
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)) $qry .= " and nama_barang in ( SELECT nama_barang from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat." )";
				
				$qry .= " GROUP BY no_kontrak, nama_barang
				) AS bap_reg ON bap_reg.no_kontrak = hd.`no_kontrak` and bap_reg.nama_barang = hd.`nama_barang`

				LEFT JOIN (
					SELECT no_kontrak, nama_barang, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_baphp_persediaan` where 1=1";
				if($list_nama_barang != ''){

					for($i=0; $i<count($list_nama_barang); $i++){
						if($i == 0)
							$qry .= " and ( nama_barang = '".$list_nama_barang[$i]."'";
						else
							$qry .= " or nama_barang = '".$list_nama_barang[$i]."'";
					}

					$qry .= ")";

				}
				if($list_id_provinsi != ''){

					for($i=0; $i<count($list_id_provinsi); $i++){
						if($i == 0)
							$qry .= " and ( id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
						else
							$qry .= " or id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
					}

					$qry .= ")";

				}
				if($list_id_kabupaten != ''){

					for($i=0; $i<count($list_id_kabupaten); $i++){
						if($i == 0)
							$qry .= " and ( id_kabupaten_penerima = '".$list_id_kabupaten[$i]."'";
						else
							$qry .= " or id_kabupaten_penerima = '".$list_id_kabupaten[$i]."'";
					}

					$qry .= ")";

				}
				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and id_provinsi_penerima = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and id_kabupaten_penerima = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
					$qry .= "
						and nama_barang in ( SELECT nama_barang from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat." )";
				}
				
				$qry .= "
					GROUP BY no_kontrak, nama_barang
				) AS bap_cad ON bap_cad.no_kontrak = hd.`no_kontrak` and bap_cad.nama_barang = hd.nama_barang
				LEFT JOIN (
					SELECT no_kontrak, nama_barang, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_bastb_pusat` where 1=1";
				if($list_nama_barang != ''){

					for($i=0; $i<count($list_nama_barang); $i++){
						if($i == 0)
							$qry .= " and ( nama_barang = '".$list_nama_barang[$i]."'";
						else
							$qry .= " or nama_barang = '".$list_nama_barang[$i]."'";
					}

					$qry .= ")";

				}
				if($list_id_provinsi != ''){

					for($i=0; $i<count($list_id_provinsi); $i++){
						if($i == 0)
							$qry .= " and ( id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
						else
							$qry .= " or id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
					}

					$qry .= ")";

				}
				if($list_id_kabupaten != ''){

					for($i=0; $i<count($list_id_kabupaten); $i++){
						if($i == 0)
							$qry .= " and ( id_kabupaten_penyerah = '".$list_id_kabupaten[$i]."'";
						else
							$qry .= " or id_kabupaten_penyerah = '".$list_id_kabupaten[$i]."'";
					}

					$qry .= ")";

				}
				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
					$qry .= "
						and nama_barang in ( SELECT nama_barang from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat." )";
				}
				
				$qry .= "
					GROUP BY no_kontrak, nama_barang
				) AS bas ON bas.no_kontrak = hd.`no_kontrak` and bas.nama_barang = hd.nama_barang
				WHERE 1=1";
				$qry .= " AND `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;

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
			if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
				$qry .= "
					and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat;
			}

			$qry .= ' GROUP BY hd.`no_kontrak`, hd.`nama_barang`, peny.nama_penyedia_pusat';
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetSearchAjaxCountKontrak($search, $filter = '', $list_nama_barang = '', $list_id_provinsi = '', $list_id_kabupaten = '')
		{
			$qry = "
				SELECT hd.`no_kontrak`, hd.`nama_barang`, hd.merk, peny.nama_penyedia_pusat,
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
				SUM(alo.total_unit) AS total_unit_alokasi,
				SUM(alo.total_nilai) AS total_nilai_alokasi,
				SUM(bap_reg.total_barang) AS total_unit_bapreg,
				SUM(bap_reg.total_nilai) AS total_nilai_bapreg,
				SUM(bap_cad.total_barang) AS total_unit_bapcad,
				SUM(bap_cad.total_nilai) AS total_nilai_bapcad,
				SUM(bas.total_barang) AS total_unit_bastb,
				SUM(bas.total_nilai) AS total_nilai_bastb
				FROM
				tb_alokasi_pusat dt
				LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
				LEFT JOIN tb_penyedia_pusat peny ON hd.`id_penyedia_pusat` = peny.`id`
				LEFT JOIN (
					SELECT hd.`no_kontrak`, hd.`nama_barang`, peny.nama_penyedia_pusat,
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
					tb_alokasi_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_penyedia_pusat peny ON hd.`id_penyedia_pusat` = peny.`id`";
					$qry .= " WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
					$qry .= " AND dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";

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
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
					$qry .= "
						and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat;
				}
				$qry .= "
					GROUP BY hd.`no_kontrak`, hd.`nama_barang`, peny.nama_penyedia_pusat
				) AS alo ON alo.no_kontrak = hd.`no_kontrak` and alo.nama_barang = hd.nama_barang and alo.nama_penyedia_pusat = peny.nama_penyedia_pusat

				LEFT JOIN (
					SELECT no_kontrak, nama_barang, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM tb_baphp_reguler where 1=1";
				if($list_nama_barang != ''){

					for($i=0; $i<count($list_nama_barang); $i++){
						if($i == 0)
							$qry .= " and ( nama_barang = '".$list_nama_barang[$i]."'";
						else
							$qry .= " or nama_barang = '".$list_nama_barang[$i]."'";
					}

					$qry .= ")";

				}
				if($list_id_provinsi != ''){

					for($i=0; $i<count($list_id_provinsi); $i++){
						if($i == 0)
							$qry .= " and ( id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
						else
							$qry .= " or id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
					}

					$qry .= ")";

				}
				if($list_id_kabupaten != ''){

					for($i=0; $i<count($list_id_kabupaten); $i++){
						if($i == 0)
							$qry .= " and ( id_kabupaten_penerima = '".$list_id_kabupaten[$i]."'";
						else
							$qry .= " or id_kabupaten_penerima = '".$list_id_kabupaten[$i]."'";
					}

					$qry .= ")";

				}

				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and id_provinsi_penerima = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and id_kabupaten_penerima = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
					$qry .= "
						and nama_barang in ( SELECT nama_barang from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat." )";
				}
				
				$qry .= " GROUP BY no_kontrak, nama_barang
				) AS bap_reg ON bap_reg.no_kontrak = hd.`no_kontrak` and bap_reg.nama_barang = hd.`nama_barang`

				LEFT JOIN (
					SELECT no_kontrak, nama_barang, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_baphp_persediaan` where 1=1";
				if($list_nama_barang != ''){

					for($i=0; $i<count($list_nama_barang); $i++){
						if($i == 0)
							$qry .= " and ( nama_barang = '".$list_nama_barang[$i]."'";
						else
							$qry .= " or nama_barang = '".$list_nama_barang[$i]."'";
					}

					$qry .= ")";

				}
				if($list_id_provinsi != ''){

					for($i=0; $i<count($list_id_provinsi); $i++){
						if($i == 0)
							$qry .= " and ( id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
						else
							$qry .= " or id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
					}

					$qry .= ")";

				}
				if($list_id_kabupaten != ''){

					for($i=0; $i<count($list_id_kabupaten); $i++){
						if($i == 0)
							$qry .= " and ( id_kabupaten_penerima = '".$list_id_kabupaten[$i]."'";
						else
							$qry .= " or id_kabupaten_penerima = '".$list_id_kabupaten[$i]."'";
					}

					$qry .= ")";

				}
				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and id_provinsi_penerima = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and id_kabupaten_penerima = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
					$qry .= "
						and nama_barang in ( SELECT nama_barang from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat." )";
				}
				$qry .= "
					GROUP BY no_kontrak, nama_barang
				) AS bap_cad ON bap_cad.no_kontrak = hd.`no_kontrak` and bap_cad.nama_barang = hd.nama_barang
				LEFT JOIN (
					SELECT no_kontrak, nama_barang, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_bastb_pusat` where 1=1";
				if($list_nama_barang != ''){

					for($i=0; $i<count($list_nama_barang); $i++){
						if($i == 0)
							$qry .= " and ( nama_barang = '".$list_nama_barang[$i]."'";
						else
							$qry .= " or nama_barang = '".$list_nama_barang[$i]."'";
					}

					$qry .= ")";

				}
				if($list_id_provinsi != ''){

					for($i=0; $i<count($list_id_provinsi); $i++){
						if($i == 0)
							$qry .= " and ( id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
						else
							$qry .= " or id_provinsi_penyerah = '".$list_id_provinsi[$i]."'";
					}

					$qry .= ")";

				}
				if($list_id_kabupaten != ''){

					for($i=0; $i<count($list_id_kabupaten); $i++){
						if($i == 0)
							$qry .= " and ( id_kabupaten_penyerah = '".$list_id_kabupaten[$i]."'";
						else
							$qry .= " or id_kabupaten_penyerah = '".$list_id_kabupaten[$i]."'";
					}

					$qry .= ")";

				}
				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
					$qry .= "
						and nama_barang in ( SELECT nama_barang from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat." )";
				}
				$qry .= "
					GROUP BY no_kontrak, nama_barang
				) AS bas ON bas.no_kontrak = hd.`no_kontrak` and bas.nama_barang = hd.nama_barang
				WHERE 1=1";
				$qry .= " AND `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;

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
			if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
				$qry .= "
					and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat;
			}

			$qry .= "
				and (
					hd.no_kontrak LIKE '%$search%'
					or hd.nama_barang LIKE '%$search%'
					or peny.nama_penyedia_pusat LIKE '%$search%'
					or merk LIKE '%$search%'
				)
			";

			$qry .= $filter;

			$qry .= ' GROUP BY hd.`no_kontrak`, hd.`nama_barang`, peny.nama_penyedia_pusat';
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetFilterAjaxCountKontrak($filter = '', $list_nama_barang = '', $list_id_provinsi = '', $list_id_kabupaten = '')
		{
			$qry = "SELECT hd.`no_kontrak`, hd.`nama_barang`, peny.nama_penyedia_pusat,
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
				SUM(alo.total_unit) AS total_unit_alokasi,
				SUM(alo.total_nilai) AS total_nilai_alokasi,
				SUM(bap_reg.total_barang) AS total_unit_bapreg,
				SUM(bap_reg.total_nilai) AS total_nilai_bapreg,
				SUM(bap_cad.total_barang) AS total_unit_bapcad,
				SUM(bap_cad.total_nilai) AS total_nilai_bapcad,
				SUM(bas.total_barang) AS total_unit_bastb,
				SUM(bas.total_nilai) AS total_nilai_bastb
				FROM
				tb_alokasi_pusat dt
				LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
				LEFT JOIN tb_penyedia_pusat peny ON hd.`id_penyedia_pusat` = peny.`id`
				LEFT JOIN (
					SELECT hd.`no_kontrak`, hd.`nama_barang`, peny.nama_penyedia_pusat,
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
					tb_alokasi_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_penyedia_pusat peny ON hd.`id_penyedia_pusat` = peny.`id`";
					$qry .= " WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
					$qry .= " AND dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";			
				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
					$qry .= "
						and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat;
				}


				$qry .= "
					GROUP BY hd.`no_kontrak`, hd.`nama_barang`, peny.nama_penyedia_pusat
				) AS alo ON alo.no_kontrak = hd.`no_kontrak` and alo.nama_barang = hd.nama_barang and alo.nama_penyedia_pusat = peny.nama_penyedia_pusat
				LEFT JOIN (
					SELECT no_kontrak, nama_barang, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM tb_baphp_reguler where 1=1";
				if($list_nama_barang != ''){

					for($i=0; $i<count($list_nama_barang); $i++){
						if($i == 0)
							$qry .= " and ( nama_barang = '".$list_nama_barang[$i]."'";
						else
							$qry .= " or nama_barang = '".$list_nama_barang[$i]."'";
					}

					$qry .= ")";

				}
				if($list_id_provinsi != ''){

					for($i=0; $i<count($list_id_provinsi); $i++){
						if($i == 0)
							$qry .= " and ( id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
						else
							$qry .= " or id_provinsi_penerima = '".$list_id_provinsi[$i]."'";
					}

					$qry .= ")";

				}
				if($list_id_kabupaten != ''){

					for($i=0; $i<count($list_id_kabupaten); $i++){
						if($i == 0)
							$qry .= " and ( id_kabupaten_penerima = '".$list_id_kabupaten[$i]."'";
						else
							$qry .= " or id_kabupaten_penerima = '".$list_id_kabupaten[$i]."'";
					}

					$qry .= ")";

				}
				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and id_provinsi_penerima = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and id_kabupaten_penerima = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
					$qry .= "
						and nama_barang in ( SELECT nama_barang from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat." )";
				}
				$qry .= " GROUP BY no_kontrak, nama_barang
				) AS bap_reg ON bap_reg.no_kontrak = hd.`no_kontrak` and bap_reg.nama_barang = hd.`nama_barang`
				LEFT JOIN (
					SELECT no_kontrak, nama_barang, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_baphp_persediaan` where 1=1";
				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and id_provinsi_penerima = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and id_kabupaten_penerima = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
					$qry .= "
						and nama_barang in ( SELECT nama_barang from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat." )";
				}
				$qry .= "
					GROUP BY no_kontrak, nama_barang
				) AS bap_cad ON bap_cad.no_kontrak = hd.`no_kontrak` and bap_cad.nama_barang = hd.nama_barang
				LEFT JOIN (
					SELECT no_kontrak, nama_barang, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_bastb_pusat` where 1=1";
				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
					$qry .= "
						and nama_barang in ( SELECT nama_barang from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat." )";
				}
				$qry .= "
					GROUP BY no_kontrak, nama_barang
				) AS bas ON bas.no_kontrak = hd.`no_kontrak` and bas.nama_barang = hd.nama_barang
				WHERE 1=1";
				$qry .= " AND `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;

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
			if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
				$qry .= "
					and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat;
			}

			$qry .= $filter;

			$qry .= ' GROUP BY hd.`no_kontrak`, hd.`nama_barang`, peny.nama_penyedia_pusat';

			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetAllForAjaxKontrak($start, $length, $col, $dir, $filter = '', $list_nama_barang = '', $list_id_provinsi = '', $list_id_kabupaten = '')
		{

			$qry = "
				SELECT hd.`no_kontrak`, hd.`nama_barang`, hd.merk, peny.nama_penyedia_pusat,
				hd.jumlah_barang AS total_unit,
				hd.nilai_barang AS total_nilai,
				alo.total_unit AS total_unit_alokasi,
				alocad.total_barang AS total_unit_alokasicad,
				SUM(alo.total_nilai) AS total_nilai_alokasi,
				SUM(alocad.total_nilai) AS total_nilai_alokasicad,
				SUM(bap_reg.total_barang) AS total_unit_bapreg,
				SUM(bap_reg.total_nilai) AS total_nilai_bapreg,
				SUM(bap_cad.total_barang) AS total_unit_bapcad,
				SUM(bap_cad.total_nilai) AS total_nilai_bapcad,
				SUM(bas.total_barang) AS total_unit_basreg,
				SUM(bas.total_nilai) AS total_nilai_basreg,
				SUM(bas_cad.total_barang) AS total_unit_bascad,
				SUM(bas_cad.total_nilai) AS total_nilai_bascad
				FROM
				tb_kontrak_pusat hd
				LEFT JOIN tb_penyedia_pusat peny ON hd.`id_penyedia_pusat` = peny.`id`
				LEFT JOIN (
					SELECT dt.id, hd.`no_kontrak`, hd.`nama_barang`, peny.nama_penyedia_pusat,
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
					END) AS total_nilai
					FROM
					tb_alokasi_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_penyedia_pusat peny ON hd.`id_penyedia_pusat` = peny.`id`";
					$qry .= " WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
					$qry .= " AND dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";			
				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
					$qry .= "
						and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat;
				}

				$qry .= "
					GROUP BY hd.`no_kontrak`, hd.`nama_barang`, peny.nama_penyedia_pusat
				) AS alo ON alo.no_kontrak = hd.`no_kontrak` and alo.nama_barang = hd.nama_barang and alo.nama_penyedia_pusat = peny.nama_penyedia_pusat
				LEFT JOIN (
					SELECT id_alokasi, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM tb_alokasi_persediaan_pusat where 1=1";
				$qry .= " GROUP BY id_alokasi
				) AS alocad ON alocad.id_alokasi = alo.id				
				LEFT JOIN (
					SELECT no_kontrak, nama_barang, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM tb_baphp_reguler where 1=1";
				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and id_provinsi_penerima = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and id_kabupaten_penerima = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
					$qry .= "
						and nama_barang in ( SELECT nama_barang from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat." )";
				}
				$qry .= " GROUP BY no_kontrak, nama_barang
				) AS bap_reg ON bap_reg.no_kontrak = hd.`no_kontrak` 
				LEFT JOIN (
					SELECT no_kontrak, nama_barang, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_baphp_persediaan` where 1=1";				
				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and id_provinsi_penerima = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and id_kabupaten_penerima = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusati)){
					$qry .= "
						and nama_barang in ( SELECT nama_barang from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusati." )";
				}
				$qry .= "
					GROUP BY no_kontrak, nama_barang
				) AS bap_cad ON bap_cad.no_kontrak = hd.`no_kontrak` and bap_cad.nama_barang = hd.nama_barang
				LEFT JOIN (
					SELECT no_kontrak, nama_barang, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_bastb_pusat` where 1=1";
				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusati)){
					$qry .= "
						and nama_barang in ( SELECT nama_barang from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusati." )";
				}
				$qry .= "
					GROUP BY no_kontrak, nama_barang
				) AS bas ON bas.no_kontrak = hd.`no_kontrak` and bas.nama_barang = hd.nama_barang
				LEFT JOIN (
					SELECT no_kontrak, nama_barang, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_bastb_persediaan` where 1=1";
				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusati)){
					$qry .= "
						and nama_barang in ( SELECT nama_barang from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusati." )";
				}
				$qry .= "
					GROUP BY no_kontrak, nama_barang
				) AS bas_cad ON bas_cad.no_kontrak = hd.`no_kontrak` and bas_cad.nama_barang = hd.nama_barang
				
				WHERE 1=1 ";
				$qry .= " AND `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;

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
			if(isset($this->session->userdata('logged_in')->id_penyedia_pusati)){
				$qry .= "
					and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusati;
			}

			$qry .= $filter;

			$qry .= ' GROUP BY hd.`no_kontrak`, hd.`nama_barang`, peny.nama_penyedia_pusat ORDER BY '.$col.' '.$dir;
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
				SELECT hd.`no_kontrak`, hd.`nama_barang`, hd.merk, peny.nama_penyedia_pusat,
				hd.jumlah_barang AS total_unit,
				hd.nilai_barang AS total_nilai,
				alo.total_unit AS total_unit_alokasi,
				alocad.total_barang AS total_unit_alokasicad,
				SUM(alo.total_nilai) AS total_nilai_alokasi,
				SUM(alocad.total_nilai) AS total_nilai_alokasicad,
				SUM(bap_reg.total_barang) AS total_unit_bapreg,
				SUM(bap_reg.total_nilai) AS total_nilai_bapreg,
				SUM(bap_cad.total_barang) AS total_unit_bapcad,
				SUM(bap_cad.total_nilai) AS total_nilai_bapcad,
				SUM(bas.total_barang) AS total_unit_basreg,
				SUM(bas.total_nilai) AS total_nilai_basreg,
				SUM(bas_cad.total_barang) AS total_unit_bascad,
				SUM(bas_cad.total_nilai) AS total_nilai_bascad
				FROM
				tb_kontrak_pusat hd
				LEFT JOIN tb_penyedia_pusat peny ON hd.`id_penyedia_pusat` = peny.`id`
				LEFT JOIN (
					SELECT dt.id, dt.id_kontrak_pusat, hd.`no_kontrak`, hd.`nama_barang`,
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
					END) AS total_nilai
					FROM
					tb_alokasi_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`";
					$qry .= " WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
					$qry .= " AND dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";			
				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
					$qry .= "
						and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat;
				}

				$qry .= "
					GROUP BY hd.`no_kontrak`
				) AS alo ON alo.id_kontrak_pusat = hd.id
				LEFT JOIN (
					SELECT id_alokasi, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM tb_alokasi_persediaan_pusat where 1=1";
				$qry .= " GROUP BY id_alokasi
				) AS alocad ON alocad.id_alokasi = alo.id				
				LEFT JOIN (
					SELECT no_kontrak, nama_barang, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM tb_baphp_reguler where 1=1";
				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and id_provinsi_penerima = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and id_kabupaten_penerima = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
					$qry .= "
						and nama_barang in ( SELECT nama_barang from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat." )";
				}
				$qry .= " GROUP BY no_kontrak, nama_barang
				) AS bap_reg ON bap_reg.no_kontrak = hd.`no_kontrak`
				LEFT JOIN (
					SELECT no_kontrak, nama_barang, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_baphp_persediaan` where 1=1";				
				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and id_provinsi_penerima = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and id_kabupaten_penerima = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusati)){
					$qry .= "
						and nama_barang in ( SELECT nama_barang from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusati." )";
				}
				$qry .= "
					GROUP BY no_kontrak, nama_barang
				) AS bap_cad ON bap_cad.no_kontrak = hd.`no_kontrak` and bap_cad.nama_barang = hd.nama_barang
				LEFT JOIN (
					SELECT no_kontrak, nama_barang, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_bastb_pusat` where 1=1";
				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusati)){
					$qry .= "
						and nama_barang in ( SELECT nama_barang from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusati." )";
				}
				$qry .= "
					GROUP BY no_kontrak, nama_barang
				) AS bas ON bas.no_kontrak = hd.`no_kontrak` and bas.nama_barang = hd.nama_barang
				LEFT JOIN (
					SELECT no_kontrak, nama_barang, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_bastb_persediaan` where 1=1";
				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusati)){
					$qry .= "
						and nama_barang in ( SELECT nama_barang from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusati." )";
				}
				$qry .= "
					GROUP BY no_kontrak, nama_barang
				) AS bas_cad ON bas_cad.no_kontrak = hd.`no_kontrak` and bas_cad.nama_barang = hd.nama_barang
				
				WHERE 1=1 ";
				$qry .= " AND `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;

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
			if(isset($this->session->userdata('logged_in')->id_penyedia_pusati)){
				$qry .= "
					and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusati;
			}

			$qry .= " and (
				hd.nama_barang LIKE '%$search%'
				or hd.no_kontrak LIKE '%$search%'
				or peny.nama_penyedia_pusat LIKE '%$search%'
				or merk LIKE '%$search%'
				)
			";

			$qry .= $filter;

			$qry .= ' GROUP BY hd.`no_kontrak`, hd.`nama_barang`, peny.nama_penyedia_pusat ORDER BY '.$col.' '.$dir;
			if($length > 0)
				$qry .=	' LIMIT ' . $start . ',' . $length;
			
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
				uni.nama_barang, uni.merk, uni.no_kontrak, uni.nama_penyedia_pusat, 
				SUM(total_unit) AS total_unit, SUM(total_nilai) AS total_nilai,
				SUM(total_unit_alokasi) AS total_unit_alokasi, SUM(total_nilai_alokasi) AS total_nilai_alokasi,
				SUM(total_unit_baphp) AS total_unit_baphp, SUM(total_nilai_baphp) AS total_nilai_baphp,
				SUM(total_unit_bastb) AS total_unit_bastb, SUM(total_nilai_bastb) AS total_nilai_bastb
				FROM (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_pusat,
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
					tb_alokasi_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_pusat peny ON hd.id_penyedia_pusat = peny.id
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
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_pusat

					UNION

					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_pusat,
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
					tb_alokasi_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_pusat peny ON hd.id_penyedia_pusat = peny.id
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					
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
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_pusat
					
					UNION
					
					SELECT bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten,
					bap.nama_barang, bap.merk, bap.no_kontrak, peny.nama_penyedia_pusat,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					SUM(bap.jumlah_barang) AS total_unit_baphp, SUM(bap.nilai_barang) AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_baphp_reguler bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					LEFT JOIN tb_kontrak_pusat kon ON bap.no_kontrak = kon.no_kontrak
					LEFT JOIN tb_penyedia_pusat peny ON kon.id_penyedia_pusat = peny.id
					WHERE 1=1";

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
					bap.nama_barang, bap.merk, bap.no_kontrak, peny.nama_penyedia_pusat
					
					UNION
					
					SELECT bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten,
					bap.nama_barang, bap.merk, bap.no_kontrak, peny.nama_penyedia_pusat,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					SUM(bap.jumlah_barang) AS total_unit_baphp, SUM(bap.nilai_barang) AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_baphp_persediaan bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					LEFT JOIN tb_kontrak_pusat kon ON bap.no_kontrak = kon.no_kontrak
					LEFT JOIN tb_penyedia_pusat peny ON kon.id_penyedia_pusat = peny.id
					WHERE 1=1";

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
					bap.nama_barang, bap.merk, bap.no_kontrak, peny.nama_penyedia_pusat
					
					UNION
					
					SELECT bas.id_provinsi_penyerah, pro.`nama_provinsi`, bas.id_kabupaten_penyerah, kab.nama_kabupaten,
					bas.nama_barang, bas.merk, bas.no_kontrak, peny.nama_penyedia_pusat, 
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					0 AS total_unit_baphp, 0 AS total_nilai_baphp,
					SUM(bas.jumlah_barang) AS total_unit_bastb, SUM(bas.nilai_barang) AS total_nilai_bastb
					FROM `tb_bastb_pusat` bas
					LEFT JOIN tb_provinsi pro ON bas.`id_provinsi_penyerah` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bas.`id_kabupaten_penyerah` = kab.`id`
					LEFT JOIN tb_kontrak_pusat kon ON bas.no_kontrak = kon.no_kontrak
					LEFT JOIN tb_penyedia_pusat peny ON kon.id_penyedia_pusat = peny.id
					WHERE 1=1";

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
					bas.nama_barang, bas.merk, bas.no_kontrak, peny.nama_penyedia_pusat
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
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
					$qry .= "
						and uni.nama_penyedia_pusat = ( SELECT nama_penyedia_pusat from tb_penyedia_pusat where id = ".$this->session->userdata('logged_in')->id_penyedia_pusat.")";
				}

			$qry .= " GROUP BY uni.id_provinsi, uni.nama_provinsi, uni.nama_kabupaten,
				uni.nama_barang, uni.merk, uni.no_kontrak, uni.nama_penyedia_pusat";

			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetSearchAjaxCountDetail($search, $filter = '', $list_nama_barang = '', $list_id_provinsi = '', $list_id_kabupaten = '')
		{
			
			$qry = "
				SELECT uni.id_provinsi, uni.nama_provinsi, uni.nama_kabupaten,
				uni.nama_barang, uni.merk, uni.no_kontrak, uni.nama_penyedia_pusat, 
				SUM(total_unit) AS total_unit, SUM(total_nilai) AS total_nilai,
				SUM(total_unit_alokasi) AS total_unit_alokasi, SUM(total_nilai_alokasi) AS total_nilai_alokasi,
				SUM(total_unit_baphp) AS total_unit_baphp, SUM(total_nilai_baphp) AS total_nilai_baphp,
				SUM(total_unit_bastb) AS total_unit_bastb, SUM(total_nilai_bastb) AS total_nilai_bastb
				FROM (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_pusat,
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
					tb_alokasi_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_pusat peny ON hd.id_penyedia_pusat = peny.id
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
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_pusat

					UNION

					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_pusat,
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
					tb_alokasi_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_pusat peny ON hd.id_penyedia_pusat = peny.id
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					
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
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_pusat
					
					UNION
					
					SELECT bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten,
					bap.nama_barang, bap.merk, bap.no_kontrak, peny.nama_penyedia_pusat,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					SUM(bap.jumlah_barang) AS total_unit_baphp, SUM(bap.nilai_barang) AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_baphp_reguler bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					LEFT JOIN tb_kontrak_pusat kon ON bap.no_kontrak = kon.no_kontrak
					LEFT JOIN tb_penyedia_pusat peny ON kon.id_penyedia_pusat = peny.id
					WHERE 1=1";

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
					bap.nama_barang, bap.merk, bap.no_kontrak, peny.nama_penyedia_pusat
					
					UNION
					
					SELECT bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten,
					bap.nama_barang, bap.merk, bap.no_kontrak, peny.nama_penyedia_pusat,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					SUM(bap.jumlah_barang) AS total_unit_baphp, SUM(bap.nilai_barang) AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_baphp_persediaan bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					LEFT JOIN tb_kontrak_pusat kon ON bap.no_kontrak = kon.no_kontrak
					LEFT JOIN tb_penyedia_pusat peny ON kon.id_penyedia_pusat = peny.id
					WHERE 1=1";

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
					bap.nama_barang, bap.merk, bap.no_kontrak, peny.nama_penyedia_pusat
					
					UNION
					
					SELECT bas.id_provinsi_penyerah, pro.`nama_provinsi`, bas.id_kabupaten_penyerah, kab.nama_kabupaten,
					bas.nama_barang, bas.merk, bas.no_kontrak, peny.nama_penyedia_pusat, 
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					0 AS total_unit_baphp, 0 AS total_nilai_baphp,
					SUM(bas.jumlah_barang) AS total_unit_bastb, SUM(bas.nilai_barang) AS total_nilai_bastb
					FROM `tb_bastb_pusat` bas
					LEFT JOIN tb_provinsi pro ON bas.`id_provinsi_penyerah` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bas.`id_kabupaten_penyerah` = kab.`id`
					LEFT JOIN tb_kontrak_pusat kon ON bas.no_kontrak = kon.no_kontrak
					LEFT JOIN tb_penyedia_pusat peny ON kon.id_penyedia_pusat = peny.id
					WHERE 1=1";

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
					bas.nama_barang, bas.merk, bas.no_kontrak, peny.nama_penyedia_pusat
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
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
					$qry .= "
						and uni.nama_penyedia_pusat = ( SELECT nama_penyedia_pusat from tb_penyedia_pusat where id = ".$this->session->userdata('logged_in')->id_penyedia_pusat.")";
				}

			$qry .= " and (
					uni.nama_provinsi LIKE '%$search%'
					or uni.nama_kabupaten LIKE '%$search%'
					or uni.nama_barang LIKE '%$search%'
					or uni.merk LIKE '%$search%'
					or uni.no_kontrak LIKE '%$search%'
					or uni.nama_penyedia_pusat LIKE '%$search%'
				)";

			$qry .= $filter;

			$qry .= " GROUP BY uni.id_provinsi, uni.nama_provinsi, uni.nama_kabupaten,
				uni.nama_barang, uni.merk, uni.no_kontrak, uni.nama_penyedia_pusat";

			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetFilterAjaxCountDetail($filter = '', $list_nama_barang = '', $list_id_provinsi = '', $list_id_kabupaten = '')
		{
			

			$qry = "
				SELECT uni.id_provinsi, uni.nama_provinsi, uni.nama_kabupaten,
				uni.nama_barang, uni.merk, uni.no_kontrak, uni.nama_penyedia_pusat, 
				SUM(total_unit) AS total_unit, SUM(total_nilai) AS total_nilai,
				SUM(total_unit_alokasi) AS total_unit_alokasi, SUM(total_nilai_alokasi) AS total_nilai_alokasi,
				SUM(total_unit_baphp) AS total_unit_baphp, SUM(total_nilai_baphp) AS total_nilai_baphp,
				SUM(total_unit_bastb) AS total_unit_bastb, SUM(total_nilai_bastb) AS total_nilai_bastb
				FROM (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_pusat,
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
					tb_alokasi_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_pusat peny ON hd.id_penyedia_pusat = peny.id
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
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_pusat

					UNION

					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_pusat,
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
					tb_alokasi_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_pusat peny ON hd.id_penyedia_pusat = peny.id
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					
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
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_pusat
					
					UNION
					
					SELECT bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten,
					bap.nama_barang, bap.merk, bap.no_kontrak, peny.nama_penyedia_pusat,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					SUM(bap.jumlah_barang) AS total_unit_baphp, SUM(bap.nilai_barang) AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_baphp_reguler bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					LEFT JOIN tb_kontrak_pusat kon ON bap.no_kontrak = kon.no_kontrak
					LEFT JOIN tb_penyedia_pusat peny ON kon.id_penyedia_pusat = peny.id
					WHERE 1=1";

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
					bap.nama_barang, bap.merk, bap.no_kontrak, peny.nama_penyedia_pusat
					
					UNION
					
					SELECT bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten,
					bap.nama_barang, bap.merk, bap.no_kontrak, peny.nama_penyedia_pusat,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					SUM(bap.jumlah_barang) AS total_unit_baphp, SUM(bap.nilai_barang) AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_baphp_persediaan bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					LEFT JOIN tb_kontrak_pusat kon ON bap.no_kontrak = kon.no_kontrak
					LEFT JOIN tb_penyedia_pusat peny ON kon.id_penyedia_pusat = peny.id
					WHERE 1=1";

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
					bap.nama_barang, bap.merk, bap.no_kontrak, peny.nama_penyedia_pusat
					
					UNION
					
					SELECT bas.id_provinsi_penyerah, pro.`nama_provinsi`, bas.id_kabupaten_penyerah, kab.nama_kabupaten,
					bas.nama_barang, bas.merk, bas.no_kontrak, peny.nama_penyedia_pusat, 
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					0 AS total_unit_baphp, 0 AS total_nilai_baphp,
					SUM(bas.jumlah_barang) AS total_unit_bastb, SUM(bas.nilai_barang) AS total_nilai_bastb
					FROM `tb_bastb_pusat` bas
					LEFT JOIN tb_provinsi pro ON bas.`id_provinsi_penyerah` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bas.`id_kabupaten_penyerah` = kab.`id`
					LEFT JOIN tb_kontrak_pusat kon ON bas.no_kontrak = kon.no_kontrak
					LEFT JOIN tb_penyedia_pusat peny ON kon.id_penyedia_pusat = peny.id
					WHERE 1=1";

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
					bas.nama_barang, bas.merk, bas.no_kontrak, peny.nama_penyedia_pusat
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
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
					$qry .= "
						and uni.nama_penyedia_pusat = ( SELECT nama_penyedia_pusat from tb_penyedia_pusat where id = ".$this->session->userdata('logged_in')->id_penyedia_pusat.")";
				}

			$qry .= $filter;

			$qry .= " GROUP BY uni.id_provinsi, uni.nama_provinsi, uni.nama_kabupaten,
				uni.nama_barang, uni.merk, uni.no_kontrak, uni.nama_penyedia_pusat";

			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetAllForAjaxDetail($start, $length, $col, $dir, $filter = '', $list_nama_barang = '', $list_id_provinsi = '', $list_id_kabupaten = '')
		{

			$qry = "
				SELECT uni.id_provinsi, uni.nama_provinsi, uni.nama_kabupaten,
				uni.nama_barang, uni.merk, uni.no_kontrak, uni.nama_penyedia_pusat, 
				SUM(total_unit) AS total_unit, SUM(total_nilai) AS total_nilai,
				SUM(total_unit_alokasi) AS total_unit_alokasi, SUM(total_nilai_alokasi) AS total_nilai_alokasi,
				SUM(total_unit_baphp) AS total_unit_baphp, SUM(total_nilai_baphp) AS total_nilai_baphp,
				SUM(total_unit_bastb) AS total_unit_bastb, SUM(total_nilai_bastb) AS total_nilai_bastb
				FROM (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_pusat,
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
					tb_alokasi_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_pusat peny ON hd.id_penyedia_pusat = peny.id
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
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_pusat

					UNION

					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_pusat,
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
					tb_alokasi_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_pusat peny ON hd.id_penyedia_pusat = peny.id
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					
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
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_pusat
					
					UNION
					
					SELECT bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten,
					bap.nama_barang, bap.merk, bap.no_kontrak, peny.nama_penyedia_pusat,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					SUM(bap.jumlah_barang) AS total_unit_baphp, SUM(bap.nilai_barang) AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_baphp_reguler bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					LEFT JOIN tb_kontrak_pusat kon ON bap.no_kontrak = kon.no_kontrak
					LEFT JOIN tb_penyedia_pusat peny ON kon.id_penyedia_pusat = peny.id
					WHERE 1=1";

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
					bap.nama_barang, bap.merk, bap.no_kontrak, peny.nama_penyedia_pusat
					
					UNION
					
					SELECT bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten,
					bap.nama_barang, bap.merk, bap.no_kontrak, peny.nama_penyedia_pusat,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					SUM(bap.jumlah_barang) AS total_unit_baphp, SUM(bap.nilai_barang) AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_baphp_persediaan bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					LEFT JOIN tb_kontrak_pusat kon ON bap.no_kontrak = kon.no_kontrak
					LEFT JOIN tb_penyedia_pusat peny ON kon.id_penyedia_pusat = peny.id
					WHERE 1=1";

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
					bap.nama_barang, bap.merk, bap.no_kontrak, peny.nama_penyedia_pusat
					
					UNION
					
					SELECT bas.id_provinsi_penyerah, pro.`nama_provinsi`, bas.id_kabupaten_penyerah, kab.nama_kabupaten,
					bas.nama_barang, bas.merk, bas.no_kontrak, peny.nama_penyedia_pusat, 
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					0 AS total_unit_baphp, 0 AS total_nilai_baphp,
					SUM(bas.jumlah_barang) AS total_unit_bastb, SUM(bas.nilai_barang) AS total_nilai_bastb
					FROM `tb_bastb_pusat` bas
					LEFT JOIN tb_provinsi pro ON bas.`id_provinsi_penyerah` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bas.`id_kabupaten_penyerah` = kab.`id`
					LEFT JOIN tb_kontrak_pusat kon ON bas.no_kontrak = kon.no_kontrak
					LEFT JOIN tb_penyedia_pusat peny ON kon.id_penyedia_pusat = peny.id
					WHERE 1=1";

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
					bas.nama_barang, bas.merk, bas.no_kontrak, peny.nama_penyedia_pusat
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
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
					$qry .= "
						and uni.nama_penyedia_pusat = ( SELECT nama_penyedia_pusat from tb_penyedia_pusat where id = ".$this->session->userdata('logged_in')->id_penyedia_pusat.")";
				}

			$qry .= $filter;

			$qry .= " GROUP BY uni.id_provinsi, uni.nama_provinsi, uni.nama_kabupaten,
				uni.nama_barang, uni.merk, uni.no_kontrak, uni.nama_penyedia_pusat";

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
				uni.nama_barang, uni.merk, uni.no_kontrak, uni.nama_penyedia_pusat, 
				SUM(total_unit) AS total_unit, SUM(total_nilai) AS total_nilai,
				SUM(total_unit_alokasi) AS total_unit_alokasi, SUM(total_nilai_alokasi) AS total_nilai_alokasi,
				SUM(total_unit_baphp) AS total_unit_baphp, SUM(total_nilai_baphp) AS total_nilai_baphp,
				SUM(total_unit_bastb) AS total_unit_bastb, SUM(total_nilai_bastb) AS total_nilai_bastb
				FROM (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_pusat,
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
					tb_alokasi_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_pusat peny ON hd.id_penyedia_pusat = peny.id
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
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_pusat

					UNION

					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_pusat,
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
					tb_alokasi_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_pusat peny ON hd.id_penyedia_pusat = peny.id
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
					
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
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_pusat
					
					UNION
					
					SELECT bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten,
					bap.nama_barang, bap.merk, bap.no_kontrak, peny.nama_penyedia_pusat,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					SUM(bap.jumlah_barang) AS total_unit_baphp, SUM(bap.nilai_barang) AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_baphp_reguler bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					LEFT JOIN tb_kontrak_pusat kon ON bap.no_kontrak = kon.no_kontrak
					LEFT JOIN tb_penyedia_pusat peny ON kon.id_penyedia_pusat = peny.id
					WHERE 1=1";

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
					bap.nama_barang, bap.merk, bap.no_kontrak, peny.nama_penyedia_pusat
					
					UNION
					
					SELECT bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten,
					bap.nama_barang, bap.merk, bap.no_kontrak, peny.nama_penyedia_pusat,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					SUM(bap.jumlah_barang) AS total_unit_baphp, SUM(bap.nilai_barang) AS total_nilai_baphp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_baphp_persediaan bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					LEFT JOIN tb_kontrak_pusat kon ON bap.no_kontrak = kon.no_kontrak
					LEFT JOIN tb_penyedia_pusat peny ON kon.id_penyedia_pusat = peny.id
					WHERE 1=1";

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
					bap.nama_barang, bap.merk, bap.no_kontrak, peny.nama_penyedia_pusat
					
					UNION
					
					SELECT bas.id_provinsi_penyerah, pro.`nama_provinsi`, bas.id_kabupaten_penyerah, kab.nama_kabupaten,
					bas.nama_barang, bas.merk, bas.no_kontrak, peny.nama_penyedia_pusat, 
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					0 AS total_unit_baphp, 0 AS total_nilai_baphp,
					SUM(bas.jumlah_barang) AS total_unit_bastb, SUM(bas.nilai_barang) AS total_nilai_bastb
					FROM `tb_bastb_pusat` bas
					LEFT JOIN tb_provinsi pro ON bas.`id_provinsi_penyerah` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bas.`id_kabupaten_penyerah` = kab.`id`
					LEFT JOIN tb_kontrak_pusat kon ON bas.no_kontrak = kon.no_kontrak
					LEFT JOIN tb_penyedia_pusat peny ON kon.id_penyedia_pusat = peny.id
					WHERE 1=1";

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
					bas.nama_barang, bas.merk, bas.no_kontrak, peny.nama_penyedia_pusat
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
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
					$qry .= "
						and uni.nama_penyedia_pusat = ( SELECT nama_penyedia_pusat from tb_penyedia_pusat where id = ".$this->session->userdata('logged_in')->id_penyedia_pusat.")";
				}

			$qry .= " and (
					uni.nama_provinsi LIKE '%$search%'
					or uni.nama_kabupaten LIKE '%$search%'
					or uni.nama_barang LIKE '%$search%'
					or uni.merk LIKE '%$search%'
					or uni.no_kontrak LIKE '%$search%'
					or uni.nama_penyedia_pusat LIKE '%$search%'
				)";

			$qry .= $filter;

			$qry .= " GROUP BY uni.id_provinsi, uni.nama_provinsi, uni.nama_kabupaten,
				uni.nama_barang, uni.merk, uni.no_kontrak, uni.nama_penyedia_pusat";

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