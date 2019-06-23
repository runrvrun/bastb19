<?php
	class LaporanPusatModel extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function GetTotalKontrak($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia)
		{
			$qry =	"	
				SELECT COUNT(DISTINCT(id)) AS total 
				FROM tb_kontrak_pusat 
				where `tahun_anggaran` = ".$tahun_anggaran." 
				and id in (
					select id_kontrak_pusat from tb_kontrak_detail_pusat
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
			$qry =	"	
				SELECT COUNT(DISTINCT(nama_barang)) AS total 
				FROM tb_kontrak_pusat 
				where `tahun_anggaran` = ".$tahun_anggaran." 
				and id in (
					select id_kontrak_pusat from tb_kontrak_detail_pusat
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

		function GetTotalBAPSTHPREG($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia)
		{
			$qry =	"	
				SELECT COUNT(DISTINCT(id)) AS total 
				FROM tb_bapsthp_reguler
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
		function GetTotalBAPSTHPCAD($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia)
		{
			$qry =	"	
				SELECT COUNT(DISTINCT(id)) AS total 
				FROM tb_bapsthp_cadangan
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
			$qry =	"	
				SELECT SUM(t.jumlah_alokasi) as total
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
						WHERE 1 = 1 ";
			
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

		function GetTotalUnitBAPSTHPREG($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia)
		{
			$qry =	"	
				SELECT SUM(jumlah_barang) AS total 
				FROM tb_bapsthp_reguler
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
		function GetTotalUnitBAPSTHPCAD($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia)
		{
			$qry =	"	
				SELECT SUM(jumlah_barang) AS total 
				FROM tb_bapsthp_cadangan
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
			$qry =	"	
				SELECT SUM(t.nilai_alokasi) as total
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
						WHERE 1 = 1 ";
			
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
				FROM tb_kontrak_detail_pusat dt 
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

		function GetTotalNilaiBAPSTHPREG($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia)
		{
			$qry =	"	
				SELECT SUM(nilai_barang) AS total 
				FROM tb_bapsthp_reguler
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
		function GetTotalNilaiBAPSTHPCAD($tahun_anggaran, $list_id_provinsi, $list_id_kabupaten, $list_id_penyedia)
		{
			$qry =	"	
				SELECT SUM(nilai_barang) AS total 
				FROM tb_bapsthp_cadangan
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
				END) AS total_nilai,
				alo.total_unit AS total_unit_alokasi,
				alo.total_nilai AS total_nilai_alokasi,
				bap_reg.total_barang AS total_unit_bapreg,
				bap_reg.total_nilai AS total_nilai_bapreg,
				bap_cad.total_barang AS total_unit_bapcad,
				bap_cad.total_nilai AS total_nilai_bapcad,
				bas.total_barang AS total_unit_bastb,
				bas.total_nilai AS total_nilai_bastb
				FROM
				tb_kontrak_detail_pusat dt
				LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
				LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
				LEFT JOIN (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, 
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
					tb_kontrak_detail_pusat dt
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
					SELECT id_provinsi_penerima, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM tb_bapsthp_reguler";
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
					FROM `tb_bapsthp_cadangan`";
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
				END) AS total_nilai,
				alo.total_unit AS total_unit_alokasi,
				alo.total_nilai AS total_nilai_alokasi,
				bap_reg.total_barang AS total_unit_bapreg,
				bap_reg.total_nilai AS total_nilai_bapreg,
				bap_cad.total_barang AS total_unit_bapcad,
				bap_cad.total_nilai AS total_nilai_bapcad,
				bas.total_barang AS total_unit_bastb,
				bas.total_nilai AS total_nilai_bastb
				FROM
				tb_kontrak_detail_pusat dt
				LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
				LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
				LEFT JOIN (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, 
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
					tb_kontrak_detail_pusat dt
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
					SELECT id_provinsi_penerima, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM tb_bapsthp_reguler";
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
					FROM `tb_bapsthp_cadangan`";
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
				END) AS total_nilai,
				alo.total_unit AS total_unit_alokasi,
				alo.total_nilai AS total_nilai_alokasi,
				bap_reg.total_barang AS total_unit_bapreg,
				bap_reg.total_nilai AS total_nilai_bapreg,
				bap_cad.total_barang AS total_unit_bapcad,
				bap_cad.total_nilai AS total_nilai_bapcad,
				bas.total_barang AS total_unit_bastb,
				bas.total_nilai AS total_nilai_bastb
				FROM
				tb_kontrak_detail_pusat dt
				LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
				LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
				LEFT JOIN (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, 
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
					tb_kontrak_detail_pusat dt
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
					SELECT id_provinsi_penerima, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM tb_bapsthp_reguler";
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
					FROM `tb_bapsthp_cadangan`";
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
				END) AS total_nilai,
				alo.total_unit AS total_unit_alokasi,
				alo.total_nilai AS total_nilai_alokasi,
				bap_reg.total_barang AS total_unit_bapreg,
				bap_reg.total_nilai AS total_nilai_bapreg,
				bap_cad.total_barang AS total_unit_bapcad,
				bap_cad.total_nilai AS total_nilai_bapcad,
				bas.total_barang AS total_unit_bastb,
				bas.total_nilai AS total_nilai_bastb
				FROM
				tb_kontrak_detail_pusat dt
				LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
				LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
				LEFT JOIN (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, 
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
					tb_kontrak_detail_pusat dt
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
					SELECT id_provinsi_penerima, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM tb_bapsthp_reguler";
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
					FROM `tb_bapsthp_cadangan`";
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
				END) AS total_nilai,
				alo.total_unit AS total_unit_alokasi,
				alo.total_nilai AS total_nilai_alokasi,
				bap_reg.total_barang AS total_unit_bapreg,
				bap_reg.total_nilai AS total_nilai_bapreg,
				bap_cad.total_barang AS total_unit_bapcad,
				bap_cad.total_nilai AS total_nilai_bapcad,
				bas.total_barang AS total_unit_bastb,
				bas.total_nilai AS total_nilai_bastb
				FROM
				tb_kontrak_detail_pusat dt
				LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
				LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
				LEFT JOIN (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, 
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
					tb_kontrak_detail_pusat dt
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
					SELECT id_provinsi_penerima, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM tb_bapsthp_reguler";
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
					FROM `tb_bapsthp_cadangan`";
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

		// **************************************************************************************************************************************
		// LAPORAN KABUPATEN *********************************************************************************************************************
		// /*************************************************************************************************************************************

		function GetAllAjaxCountKabupaten($list_nama_barang = '', $list_id_provinsi = '')
		{
			$qry = "
				SELECT uni.id_provinsi, uni.nama_provinsi, uni.nama_kabupaten, 
				SUM(total_unit) AS total_unit, SUM(total_nilai) AS total_nilai,
				SUM(total_unit_alokasi) AS total_unit_alokasi, SUM(total_nilai_alokasi) AS total_nilai_alokasi,
				SUM(total_unit_bapsthp) AS total_unit_bapsthp, SUM(total_nilai_bapsthp) AS total_nilai_bapsthp,
				SUM(total_unit_bastb) AS total_unit_bastb, SUM(total_nilai_bastb) AS total_nilai_bastb
				FROM (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
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
					END) AS total_nilai,
					0 AS total_unit_alokasi,
					0 AS total_nilai_alokasi,
					0 AS total_unit_bapsthp,
					0 AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_kontrak_detail_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					WHERE 1 = 1";
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
					WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang_detail
					END) AS total_unit_alokasi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang_detail
					END) AS total_nilai_alokasi,
					0 AS total_unit_bapsthp,
					0 AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_kontrak_detail_pusat dt
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
					SUM(jumlah_barang) AS total_unit_bapsthp, SUM(`nilai_barang`) AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_bapsthp_reguler bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					WHERE 1 = 1";

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
					
					SELECT bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					SUM(jumlah_barang) AS total_unit_bapsthp, SUM(`nilai_barang`) AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_bapsthp_cadangan bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					WHERE 1 = 1";

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
					0 AS total_unit_bapsthp, 0 AS total_nilai_bapsthp,
					SUM(jumlah_barang) AS total_unit_bastb, SUM(`nilai_barang`) AS total_nilai_bastb
					FROM `tb_bastb_pusat` bas
					LEFT JOIN tb_provinsi pro ON bas.`id_provinsi_penyerah` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bas.`id_kabupaten_penyerah` = kab.`id`
					WHERE 1 = 1";

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
				WHERE 1 = 1 ";

				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and uni.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and uni.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				// if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
				// 	$qry .= "
				// 		and uni.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat;
				// }

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
				SUM(total_unit_bapsthp) AS total_unit_bapsthp, SUM(total_nilai_bapsthp) AS total_nilai_bapsthp,
				SUM(total_unit_bastb) AS total_unit_bastb, SUM(total_nilai_bastb) AS total_nilai_bastb
				FROM (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
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
					END) AS total_nilai,
					0 AS total_unit_alokasi,
					0 AS total_nilai_alokasi,
					0 AS total_unit_bapsthp,
					0 AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_kontrak_detail_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					WHERE 1 = 1";
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
					WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang_detail
					END) AS total_unit_alokasi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang_detail
					END) AS total_nilai_alokasi,
					0 AS total_unit_bapsthp,
					0 AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_kontrak_detail_pusat dt
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
					SUM(jumlah_barang) AS total_unit_bapsthp, SUM(`nilai_barang`) AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_bapsthp_reguler bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					WHERE 1 = 1";

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
					
					SELECT bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					SUM(jumlah_barang) AS total_unit_bapsthp, SUM(`nilai_barang`) AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_bapsthp_cadangan bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					WHERE 1 = 1";

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
					0 AS total_unit_bapsthp, 0 AS total_nilai_bapsthp,
					SUM(jumlah_barang) AS total_unit_bastb, SUM(`nilai_barang`) AS total_nilai_bastb
					FROM `tb_bastb_pusat` bas
					LEFT JOIN tb_provinsi pro ON bas.`id_provinsi_penyerah` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bas.`id_kabupaten_penyerah` = kab.`id`
					WHERE 1 = 1";

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
				WHERE 1 = 1 ";

				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and uni.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and uni.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				// if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
				// 	$qry .= "
				// 		and uni.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat;
				// }

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
				SUM(total_unit_bapsthp) AS total_unit_bapsthp, SUM(total_nilai_bapsthp) AS total_nilai_bapsthp,
				SUM(total_unit_bastb) AS total_unit_bastb, SUM(total_nilai_bastb) AS total_nilai_bastb
				FROM (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
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
					END) AS total_nilai,
					0 AS total_unit_alokasi,
					0 AS total_nilai_alokasi,
					0 AS total_unit_bapsthp,
					0 AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_kontrak_detail_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					WHERE 1 = 1";
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
					WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang_detail
					END) AS total_unit_alokasi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang_detail
					END) AS total_nilai_alokasi,
					0 AS total_unit_bapsthp,
					0 AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_kontrak_detail_pusat dt
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
					SUM(jumlah_barang) AS total_unit_bapsthp, SUM(`nilai_barang`) AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_bapsthp_reguler bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					WHERE 1 = 1";

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
					
					SELECT bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					SUM(jumlah_barang) AS total_unit_bapsthp, SUM(`nilai_barang`) AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_bapsthp_cadangan bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					WHERE 1 = 1";

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
					0 AS total_unit_bapsthp, 0 AS total_nilai_bapsthp,
					SUM(jumlah_barang) AS total_unit_bastb, SUM(`nilai_barang`) AS total_nilai_bastb
					FROM `tb_bastb_pusat` bas
					LEFT JOIN tb_provinsi pro ON bas.`id_provinsi_penyerah` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bas.`id_kabupaten_penyerah` = kab.`id`
					WHERE 1 = 1";

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
				WHERE 1 = 1 ";

				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and uni.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and uni.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				// if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
				// 	$qry .= "
				// 		and uni.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat;
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
				SUM(total_unit_bapsthp) AS total_unit_bapsthp, SUM(total_nilai_bapsthp) AS total_nilai_bapsthp,
				SUM(total_unit_bastb) AS total_unit_bastb, SUM(total_nilai_bastb) AS total_nilai_bastb
				FROM (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
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
					END) AS total_nilai,
					0 AS total_unit_alokasi,
					0 AS total_nilai_alokasi,
					0 AS total_unit_bapsthp,
					0 AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_kontrak_detail_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					WHERE 1 = 1";
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
					WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang_detail
					END) AS total_unit_alokasi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang_detail
					END) AS total_nilai_alokasi,
					0 AS total_unit_bapsthp,
					0 AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_kontrak_detail_pusat dt
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
					SUM(jumlah_barang) AS total_unit_bapsthp, SUM(`nilai_barang`) AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_bapsthp_reguler bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					WHERE 1 = 1";

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
					
					SELECT bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					SUM(jumlah_barang) AS total_unit_bapsthp, SUM(`nilai_barang`) AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_bapsthp_cadangan bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					WHERE 1 = 1";

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
					0 AS total_unit_bapsthp, 0 AS total_nilai_bapsthp,
					SUM(jumlah_barang) AS total_unit_bastb, SUM(`nilai_barang`) AS total_nilai_bastb
					FROM `tb_bastb_pusat` bas
					LEFT JOIN tb_provinsi pro ON bas.`id_provinsi_penyerah` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bas.`id_kabupaten_penyerah` = kab.`id`
					WHERE 1 = 1";

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
				WHERE 1 = 1 ";

				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and uni.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and uni.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				// if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
				// 	$qry .= "
				// 		and uni.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat;
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
				SUM(total_unit_bapsthp) AS total_unit_bapsthp, SUM(total_nilai_bapsthp) AS total_nilai_bapsthp,
				SUM(total_unit_bastb) AS total_unit_bastb, SUM(total_nilai_bastb) AS total_nilai_bastb
				FROM (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
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
					END) AS total_nilai,
					0 AS total_unit_alokasi,
					0 AS total_nilai_alokasi,
					0 AS total_unit_bapsthp,
					0 AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_kontrak_detail_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					WHERE 1 = 1";
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
					WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang_detail
					END) AS total_unit_alokasi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang_detail
					END) AS total_nilai_alokasi,
					0 AS total_unit_bapsthp,
					0 AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_kontrak_detail_pusat dt
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
					SUM(jumlah_barang) AS total_unit_bapsthp, SUM(`nilai_barang`) AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_bapsthp_reguler bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					WHERE 1 = 1";

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
					
					SELECT bap.`id_provinsi_penerima`, pro.`nama_provinsi`, bap.id_kabupaten_penerima, kab.nama_kabupaten,
					0 AS total_unit, 0 AS total_nilai, 0 AS total_unit_alokasi, 0 AS total_nilai_alokasi,
					SUM(jumlah_barang) AS total_unit_bapsthp, SUM(`nilai_barang`) AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_bapsthp_cadangan bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					WHERE 1 = 1";

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
					0 AS total_unit_bapsthp, 0 AS total_nilai_bapsthp,
					SUM(jumlah_barang) AS total_unit_bastb, SUM(`nilai_barang`) AS total_nilai_bastb
					FROM `tb_bastb_pusat` bas
					LEFT JOIN tb_provinsi pro ON bas.`id_provinsi_penyerah` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bas.`id_kabupaten_penyerah` = kab.`id`
					WHERE 1 = 1";

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
				WHERE 1 = 1 ";

				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
						and uni.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				if(isset($this->session->userdata('logged_in')->id_kabupaten)){
					$qry .= "
						and uni.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				// if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
				// 	$qry .= "
				// 		and uni.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat;
				// }

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

		// **************************************************************************************************************************************
		// LAPORAN KONTRAK *********************************************************************************************************************
		// /*************************************************************************************************************************************

		function GetAllAjaxCountKontrak($list_nama_barang = '', $list_id_provinsi = '', $list_id_kabupaten = '')
		{
			$qry = "
				SELECT hd.`no_kontrak`, hd.`nama_barang`, peny.nama_penyedia_pusat,
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
				END) AS total_nilai,
				alo.total_unit AS total_unit_alokasi,
				alo.total_nilai AS total_nilai_alokasi,
				bap_reg.total_barang AS total_unit_bapreg,
				bap_reg.total_nilai AS total_nilai_bapreg,
				bap_cad.total_barang AS total_unit_bapcad,
				bap_cad.total_nilai AS total_nilai_bapcad,
				bas.total_barang AS total_unit_bastb,
				bas.total_nilai AS total_nilai_bastb
				FROM
				tb_kontrak_detail_pusat dt
				LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
				LEFT JOIN tb_penyedia_pusat peny ON hd.`id_penyedia_pusat` = peny.`id`
				LEFT JOIN (
					SELECT hd.`no_kontrak`, hd.`nama_barang`, peny.nama_penyedia_pusat,
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
					tb_kontrak_detail_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_penyedia_pusat peny ON hd.`id_penyedia_pusat` = peny.`id`
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
					FROM tb_bapsthp_reguler where 1 = 1";
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
					FROM `tb_bapsthp_cadangan` where 1 = 1";
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
					FROM `tb_bastb_pusat` where 1 = 1";
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
				SELECT hd.`no_kontrak`, hd.`nama_barang`, peny.nama_penyedia_pusat,
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
				END) AS total_nilai,
				alo.total_unit AS total_unit_alokasi,
				alo.total_nilai AS total_nilai_alokasi,
				bap_reg.total_barang AS total_unit_bapreg,
				bap_reg.total_nilai AS total_nilai_bapreg,
				bap_cad.total_barang AS total_unit_bapcad,
				bap_cad.total_nilai AS total_nilai_bapcad,
				bas.total_barang AS total_unit_bastb,
				bas.total_nilai AS total_nilai_bastb
				FROM
				tb_kontrak_detail_pusat dt
				LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
				LEFT JOIN tb_penyedia_pusat peny ON hd.`id_penyedia_pusat` = peny.`id`
				LEFT JOIN (
					SELECT hd.`no_kontrak`, hd.`nama_barang`, peny.nama_penyedia_pusat,
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
					tb_kontrak_detail_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_penyedia_pusat peny ON hd.`id_penyedia_pusat` = peny.`id`
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
					FROM tb_bapsthp_reguler where 1 = 1";
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
					FROM `tb_bapsthp_cadangan` where 1 = 1";
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
					FROM `tb_bastb_pusat` where 1 = 1";
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
				)
			";

			$qry .= $filter;

			$qry .= ' GROUP BY hd.`no_kontrak`, hd.`nama_barang`, peny.nama_penyedia_pusat';
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetFilterAjaxCountKontrak($filter = '', $list_nama_barang = '', $list_id_provinsi = '', $list_id_kabupaten = '')
		{
			$qry = "
				SELECT hd.`no_kontrak`, hd.`nama_barang`, peny.nama_penyedia_pusat,
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
				END) AS total_nilai,
				alo.total_unit AS total_unit_alokasi,
				alo.total_nilai AS total_nilai_alokasi,
				bap_reg.total_barang AS total_unit_bapreg,
				bap_reg.total_nilai AS total_nilai_bapreg,
				bap_cad.total_barang AS total_unit_bapcad,
				bap_cad.total_nilai AS total_nilai_bapcad,
				bas.total_barang AS total_unit_bastb,
				bas.total_nilai AS total_nilai_bastb
				FROM
				tb_kontrak_detail_pusat dt
				LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
				LEFT JOIN tb_penyedia_pusat peny ON hd.`id_penyedia_pusat` = peny.`id`
				LEFT JOIN (
					SELECT hd.`no_kontrak`, hd.`nama_barang`, peny.nama_penyedia_pusat,
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
					tb_kontrak_detail_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_penyedia_pusat peny ON hd.`id_penyedia_pusat` = peny.`id`
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
					FROM tb_bapsthp_reguler where 1 = 1";
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
					FROM `tb_bapsthp_cadangan` where 1 = 1";
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
					FROM `tb_bastb_pusat` where 1 = 1";
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
				SELECT hd.`no_kontrak`, hd.`nama_barang`, peny.nama_penyedia_pusat,
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
				END) AS total_nilai,
				alo.total_unit AS total_unit_alokasi,
				alo.total_nilai AS total_nilai_alokasi,
				bap_reg.total_barang AS total_unit_bapreg,
				bap_reg.total_nilai AS total_nilai_bapreg,
				bap_cad.total_barang AS total_unit_bapcad,
				bap_cad.total_nilai AS total_nilai_bapcad,
				bas.total_barang AS total_unit_bastb,
				bas.total_nilai AS total_nilai_bastb
				FROM
				tb_kontrak_detail_pusat dt
				LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
				LEFT JOIN tb_penyedia_pusat peny ON hd.`id_penyedia_pusat` = peny.`id`
				LEFT JOIN (
					SELECT hd.`no_kontrak`, hd.`nama_barang`, peny.nama_penyedia_pusat,
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
					tb_kontrak_detail_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_penyedia_pusat peny ON hd.`id_penyedia_pusat` = peny.`id`
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
					FROM tb_bapsthp_reguler where 1 = 1";
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
					FROM `tb_bapsthp_cadangan` where 1 = 1";
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
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusati)){
					$qry .= "
						and nama_barang in ( SELECT nama_barang from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusati." )";
				}
				$qry .= "
					GROUP BY no_kontrak, nama_barang
				) AS bap_cad ON bap_cad.no_kontrak = hd.`no_kontrak` and bap_cad.nama_barang = hd.nama_barang
				LEFT JOIN (
					SELECT no_kontrak, nama_barang, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_bastb_pusat` where 1 = 1";
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
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusati)){
					$qry .= "
						and nama_barang in ( SELECT nama_barang from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusati." )";
				}
				$qry .= "
					GROUP BY no_kontrak, nama_barang
				) AS bas ON bas.no_kontrak = hd.`no_kontrak` and bas.nama_barang = hd.nama_barang
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
				SELECT hd.`no_kontrak`, hd.`nama_barang`, peny.nama_penyedia_pusat,
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
				END) AS total_nilai,
				alo.total_unit AS total_unit_alokasi,
				alo.total_nilai AS total_nilai_alokasi,
				bap_reg.total_barang AS total_unit_bapreg,
				bap_reg.total_nilai AS total_nilai_bapreg,
				bap_cad.total_barang AS total_unit_bapcad,
				bap_cad.total_nilai AS total_nilai_bapcad,
				bas.total_barang AS total_unit_bastb,
				bas.total_nilai AS total_nilai_bastb
				FROM
				tb_kontrak_detail_pusat dt
				LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
				LEFT JOIN tb_penyedia_pusat peny ON hd.`id_penyedia_pusat` = peny.`id`
				LEFT JOIN (
					SELECT hd.`no_kontrak`, hd.`nama_barang`, peny.nama_penyedia_pusat,
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
					tb_kontrak_detail_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_penyedia_pusat peny ON hd.`id_penyedia_pusat` = peny.`id`
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

				$qry .= "
					GROUP BY hd.`no_kontrak`, hd.`nama_barang`, peny.nama_penyedia_pusat
				) AS alo ON alo.no_kontrak = hd.`no_kontrak` and alo.nama_barang = hd.nama_barang and alo.nama_penyedia_pusat = peny.nama_penyedia_pusat

				LEFT JOIN (
					SELECT no_kontrak, nama_barang, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM tb_bapsthp_reguler where 1 = 1";
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
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusati)){
					$qry .= "
						and nama_barang in ( SELECT nama_barang from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusati." )";
				}
				$qry .= " GROUP BY no_kontrak, nama_barang
				) AS bap_reg ON bap_reg.no_kontrak = hd.`no_kontrak` and bap_reg.nama_barang = hd.`nama_barang`

				LEFT JOIN (
					SELECT no_kontrak, nama_barang, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_bapsthp_cadangan` where 1 = 1";
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
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusati)){
					$qry .= "
						and nama_barang in ( SELECT nama_barang from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusati." )";
				}
				$qry .= "
					GROUP BY no_kontrak, nama_barang
				) AS bap_cad ON bap_cad.no_kontrak = hd.`no_kontrak` and bap_cad.nama_barang = hd.nama_barang
				LEFT JOIN (
					SELECT no_kontrak, nama_barang, SUM(jumlah_barang) AS total_barang, SUM(nilai_barang) AS total_nilai
					FROM `tb_bastb_pusat` where 1 = 1";
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
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusati)){
					$qry .= "
						and nama_barang in ( SELECT nama_barang from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusati." )";
				}
				$qry .= "
					GROUP BY no_kontrak, nama_barang
				) AS bas ON bas.no_kontrak = hd.`no_kontrak` and bas.nama_barang = hd.nama_barang
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
				SUM(total_unit_bapsthp) AS total_unit_bapsthp, SUM(total_nilai_bapsthp) AS total_nilai_bapsthp,
				SUM(total_unit_bastb) AS total_unit_bastb, SUM(total_nilai_bastb) AS total_nilai_bastb
				FROM (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_pusat,
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
					END) AS total_nilai,
					0 AS total_unit_alokasi,
					0 AS total_nilai_alokasi,
					0 AS total_unit_bapsthp,
					0 AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_kontrak_detail_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_pusat peny ON hd.id_penyedia_pusat = peny.id
					WHERE 1 = 1";
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
					WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang_detail
					END) AS total_unit_alokasi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang_detail
					END) AS total_nilai_alokasi,
					0 AS total_unit_bapsthp,
					0 AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_kontrak_detail_pusat dt
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
					SUM(bap.jumlah_barang) AS total_unit_bapsthp, SUM(bap.nilai_barang) AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_bapsthp_reguler bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					LEFT JOIN tb_kontrak_pusat kon ON bap.no_kontrak = kon.no_kontrak
					LEFT JOIN tb_penyedia_pusat peny ON kon.id_penyedia_pusat = peny.id
					WHERE 1 = 1";

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
					SUM(bap.jumlah_barang) AS total_unit_bapsthp, SUM(bap.nilai_barang) AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_bapsthp_cadangan bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					LEFT JOIN tb_kontrak_pusat kon ON bap.no_kontrak = kon.no_kontrak
					LEFT JOIN tb_penyedia_pusat peny ON kon.id_penyedia_pusat = peny.id
					WHERE 1 = 1";

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
					0 AS total_unit_bapsthp, 0 AS total_nilai_bapsthp,
					SUM(bas.jumlah_barang) AS total_unit_bastb, SUM(bas.nilai_barang) AS total_nilai_bastb
					FROM `tb_bastb_pusat` bas
					LEFT JOIN tb_provinsi pro ON bas.`id_provinsi_penyerah` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bas.`id_kabupaten_penyerah` = kab.`id`
					LEFT JOIN tb_kontrak_pusat kon ON bas.no_kontrak = kon.no_kontrak
					LEFT JOIN tb_penyedia_pusat peny ON kon.id_penyedia_pusat = peny.id
					WHERE 1 = 1";

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
				WHERE 1 = 1 ";

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
				SUM(total_unit_bapsthp) AS total_unit_bapsthp, SUM(total_nilai_bapsthp) AS total_nilai_bapsthp,
				SUM(total_unit_bastb) AS total_unit_bastb, SUM(total_nilai_bastb) AS total_nilai_bastb
				FROM (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_pusat,
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
					END) AS total_nilai,
					0 AS total_unit_alokasi,
					0 AS total_nilai_alokasi,
					0 AS total_unit_bapsthp,
					0 AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_kontrak_detail_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_pusat peny ON hd.id_penyedia_pusat = peny.id
					WHERE 1 = 1";
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
					WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang_detail
					END) AS total_unit_alokasi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang_detail
					END) AS total_nilai_alokasi,
					0 AS total_unit_bapsthp,
					0 AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_kontrak_detail_pusat dt
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
					SUM(bap.jumlah_barang) AS total_unit_bapsthp, SUM(bap.nilai_barang) AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_bapsthp_reguler bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					LEFT JOIN tb_kontrak_pusat kon ON bap.no_kontrak = kon.no_kontrak
					LEFT JOIN tb_penyedia_pusat peny ON kon.id_penyedia_pusat = peny.id
					WHERE 1 = 1";

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
					SUM(bap.jumlah_barang) AS total_unit_bapsthp, SUM(bap.nilai_barang) AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_bapsthp_cadangan bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					LEFT JOIN tb_kontrak_pusat kon ON bap.no_kontrak = kon.no_kontrak
					LEFT JOIN tb_penyedia_pusat peny ON kon.id_penyedia_pusat = peny.id
					WHERE 1 = 1";

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
					0 AS total_unit_bapsthp, 0 AS total_nilai_bapsthp,
					SUM(bas.jumlah_barang) AS total_unit_bastb, SUM(bas.nilai_barang) AS total_nilai_bastb
					FROM `tb_bastb_pusat` bas
					LEFT JOIN tb_provinsi pro ON bas.`id_provinsi_penyerah` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bas.`id_kabupaten_penyerah` = kab.`id`
					LEFT JOIN tb_kontrak_pusat kon ON bas.no_kontrak = kon.no_kontrak
					LEFT JOIN tb_penyedia_pusat peny ON kon.id_penyedia_pusat = peny.id
					WHERE 1 = 1";

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
				WHERE 1 = 1 ";

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
				SUM(total_unit_bapsthp) AS total_unit_bapsthp, SUM(total_nilai_bapsthp) AS total_nilai_bapsthp,
				SUM(total_unit_bastb) AS total_unit_bastb, SUM(total_nilai_bastb) AS total_nilai_bastb
				FROM (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_pusat,
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
					END) AS total_nilai,
					0 AS total_unit_alokasi,
					0 AS total_nilai_alokasi,
					0 AS total_unit_bapsthp,
					0 AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_kontrak_detail_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_pusat peny ON hd.id_penyedia_pusat = peny.id
					WHERE 1 = 1";
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
					WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang_detail
					END) AS total_unit_alokasi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang_detail
					END) AS total_nilai_alokasi,
					0 AS total_unit_bapsthp,
					0 AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_kontrak_detail_pusat dt
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
					SUM(bap.jumlah_barang) AS total_unit_bapsthp, SUM(bap.nilai_barang) AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_bapsthp_reguler bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					LEFT JOIN tb_kontrak_pusat kon ON bap.no_kontrak = kon.no_kontrak
					LEFT JOIN tb_penyedia_pusat peny ON kon.id_penyedia_pusat = peny.id
					WHERE 1 = 1";

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
					SUM(bap.jumlah_barang) AS total_unit_bapsthp, SUM(bap.nilai_barang) AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_bapsthp_cadangan bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					LEFT JOIN tb_kontrak_pusat kon ON bap.no_kontrak = kon.no_kontrak
					LEFT JOIN tb_penyedia_pusat peny ON kon.id_penyedia_pusat = peny.id
					WHERE 1 = 1";

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
					0 AS total_unit_bapsthp, 0 AS total_nilai_bapsthp,
					SUM(bas.jumlah_barang) AS total_unit_bastb, SUM(bas.nilai_barang) AS total_nilai_bastb
					FROM `tb_bastb_pusat` bas
					LEFT JOIN tb_provinsi pro ON bas.`id_provinsi_penyerah` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bas.`id_kabupaten_penyerah` = kab.`id`
					LEFT JOIN tb_kontrak_pusat kon ON bas.no_kontrak = kon.no_kontrak
					LEFT JOIN tb_penyedia_pusat peny ON kon.id_penyedia_pusat = peny.id
					WHERE 1 = 1";

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
				WHERE 1 = 1 ";

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
				SUM(total_unit_bapsthp) AS total_unit_bapsthp, SUM(total_nilai_bapsthp) AS total_nilai_bapsthp,
				SUM(total_unit_bastb) AS total_unit_bastb, SUM(total_nilai_bastb) AS total_nilai_bastb
				FROM (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_pusat,
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
					END) AS total_nilai,
					0 AS total_unit_alokasi,
					0 AS total_nilai_alokasi,
					0 AS total_unit_bapsthp,
					0 AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_kontrak_detail_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_pusat peny ON hd.id_penyedia_pusat = peny.id
					WHERE 1 = 1";
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
					WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang_detail
					END) AS total_unit_alokasi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang_detail
					END) AS total_nilai_alokasi,
					0 AS total_unit_bapsthp,
					0 AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_kontrak_detail_pusat dt
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
					SUM(bap.jumlah_barang) AS total_unit_bapsthp, SUM(bap.nilai_barang) AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_bapsthp_reguler bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					LEFT JOIN tb_kontrak_pusat kon ON bap.no_kontrak = kon.no_kontrak
					LEFT JOIN tb_penyedia_pusat peny ON kon.id_penyedia_pusat = peny.id
					WHERE 1 = 1";

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
					SUM(bap.jumlah_barang) AS total_unit_bapsthp, SUM(bap.nilai_barang) AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_bapsthp_cadangan bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					LEFT JOIN tb_kontrak_pusat kon ON bap.no_kontrak = kon.no_kontrak
					LEFT JOIN tb_penyedia_pusat peny ON kon.id_penyedia_pusat = peny.id
					WHERE 1 = 1";

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
					0 AS total_unit_bapsthp, 0 AS total_nilai_bapsthp,
					SUM(bas.jumlah_barang) AS total_unit_bastb, SUM(bas.nilai_barang) AS total_nilai_bastb
					FROM `tb_bastb_pusat` bas
					LEFT JOIN tb_provinsi pro ON bas.`id_provinsi_penyerah` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bas.`id_kabupaten_penyerah` = kab.`id`
					LEFT JOIN tb_kontrak_pusat kon ON bas.no_kontrak = kon.no_kontrak
					LEFT JOIN tb_penyedia_pusat peny ON kon.id_penyedia_pusat = peny.id
					WHERE 1 = 1";

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
				WHERE 1 = 1 ";

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
				SUM(total_unit_bapsthp) AS total_unit_bapsthp, SUM(total_nilai_bapsthp) AS total_nilai_bapsthp,
				SUM(total_unit_bastb) AS total_unit_bastb, SUM(total_nilai_bastb) AS total_nilai_bastb
				FROM (
					SELECT dt.`id_provinsi`, pro.`nama_provinsi`, dt.id_kabupaten, kab.nama_kabupaten,
					hd.nama_barang, hd.merk, hd.no_kontrak, peny.nama_penyedia_pusat,
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
					END) AS total_nilai,
					0 AS total_unit_alokasi,
					0 AS total_nilai_alokasi,
					0 AS total_unit_bapsthp,
					0 AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_kontrak_detail_pusat dt
					LEFT JOIN tb_kontrak_pusat hd ON dt.`id_kontrak_pusat` = hd.`id`
					LEFT JOIN tb_provinsi pro ON dt.`id_provinsi` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON dt.`id_kabupaten` = kab.`id`
					LEFT JOIN tb_penyedia_pusat peny ON hd.id_penyedia_pusat = peny.id
					WHERE 1 = 1";
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
					WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
						dt.jumlah_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
						dt.jumlah_barang_rev_2
					ELSE
						dt.jumlah_barang_detail
					END) AS total_unit_alokasi,
					SUM(CASE 	
					WHEN dt.status_alokasi = 'DATA ADENDUM 1' THEN
						dt.nilai_barang_rev_1
					WHEN dt.status_alokasi = 'DATA ADENDUM 2' THEN
						dt.nilai_barang_rev_2
					ELSE
						dt.nilai_barang_detail
					END) AS total_nilai_alokasi,
					0 AS total_unit_bapsthp,
					0 AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM
					tb_kontrak_detail_pusat dt
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
					SUM(bap.jumlah_barang) AS total_unit_bapsthp, SUM(bap.nilai_barang) AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_bapsthp_reguler bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					LEFT JOIN tb_kontrak_pusat kon ON bap.no_kontrak = kon.no_kontrak
					LEFT JOIN tb_penyedia_pusat peny ON kon.id_penyedia_pusat = peny.id
					WHERE 1 = 1";

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
					SUM(bap.jumlah_barang) AS total_unit_bapsthp, SUM(bap.nilai_barang) AS total_nilai_bapsthp,
					0 AS total_unit_bastb, 0 AS total_nilai_bastb
					FROM tb_bapsthp_cadangan bap
					LEFT JOIN tb_provinsi pro ON bap.`id_provinsi_penerima` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bap.`id_kabupaten_penerima` = kab.`id`
					LEFT JOIN tb_kontrak_pusat kon ON bap.no_kontrak = kon.no_kontrak
					LEFT JOIN tb_penyedia_pusat peny ON kon.id_penyedia_pusat = peny.id
					WHERE 1 = 1";

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
					0 AS total_unit_bapsthp, 0 AS total_nilai_bapsthp,
					SUM(bas.jumlah_barang) AS total_unit_bastb, SUM(bas.nilai_barang) AS total_nilai_bastb
					FROM `tb_bastb_pusat` bas
					LEFT JOIN tb_provinsi pro ON bas.`id_provinsi_penyerah` = pro.`id`
					LEFT JOIN tb_kabupaten kab ON bas.`id_kabupaten_penyerah` = kab.`id`
					LEFT JOIN tb_kontrak_pusat kon ON bas.no_kontrak = kon.no_kontrak
					LEFT JOIN tb_penyedia_pusat peny ON kon.id_penyedia_pusat = peny.id
					WHERE 1 = 1";

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
				WHERE 1 = 1 ";

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