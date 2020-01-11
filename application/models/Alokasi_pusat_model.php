<?php
	class Alokasi_pusat_model extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function get($id = null, $id_kontrak_pusat = null,  $start = null, $length = null, $col = null, $dir = null, $filter = null, $search = null)
		{
			if(empty($col)) $col = 'dt.updated_at';
			if(empty($dir)) $dir = 'DESC';
			$qry =	"	
				SELECT 
					hd.`tahun_anggaran`,
					hd.`no_kontrak`,
					hd.`periode_mulai`,
					hd.`periode_selesai`,
					CONCAT(DATE_FORMAT(hd.`periode_mulai`,'%d-%m-%Y'),' s/d ',DATE_FORMAT(hd.`periode_selesai`,'%d-%m-%Y')) periode,
					hd.`nama_barang`,
					hd.`merk`,
					dt.id, dt.`id_kontrak_pusat`, 
					dt.`id_provinsi`, p.`nama_provinsi`,
					dt.`id_kabupaten`, k.`nama_kabupaten`,
					dt.`nilai_barang`, dt.`jumlah_barang`, 
					IFNULL(dt.nilai_barang/NULLIF(dt.jumlah_barang,0),0) harga_satuan,
					dt.`regcad`, dt.`dinas`,
					dt.`no_adendum_1`, dt.`jumlah_barang_rev_1`, dt.`nilai_barang_rev_1`, 
					IFNULL(nilai_barang_rev_1/NULLIF(jumlah_barang_rev_1,0),0) harga_satuan_rev_1,
					dt.`no_adendum_2`, dt.`jumlah_barang_rev_2`, dt.`nilai_barang_rev_2`,
					IFNULL(nilai_barang_rev_2/NULLIF(jumlah_barang_rev_2,0),0) harga_satuan_rev_2,
					dt.`no_adendum_3`, dt.`jumlah_barang_rev_3`, dt.`nilai_barang_rev_3`,
					IFNULL(nilai_barang_rev_3/NULLIF(jumlah_barang_rev_3,0),0) harga_satuan_rev_3,
					dt.`nama_file_adendum_1`, dt.`nama_file_adendum_2`, dt.`nama_file_adendum_3`,
					dt.`status_alokasi`, dt.nama_file, id_hibah_pusat,
					pen.`nama_penyedia_pusat`,s.`keterangan` as sp2d,
					CASE
						WHEN dt.`status_alokasi` = 'DATA ADDENDUM 1' THEN dt.`no_adendum_1`
						WHEN dt.`status_alokasi` = 'DATA ADDENDUM 2' THEN dt.`no_adendum_2`
						WHEN dt.`status_alokasi` = 'DATA ADDENDUM 3' THEN dt.`no_adendum_3`
						ELSE ''
					END	AS no_adendum,
					CASE
						WHEN dt.`status_alokasi` = 'DATA ADDENDUM 1' THEN dt.`jumlah_barang_rev_1`
						WHEN dt.`status_alokasi` = 'DATA ADDENDUM 2' THEN dt.`jumlah_barang_rev_2`
						WHEN dt.`status_alokasi` = 'DATA ADDENDUM 3' THEN dt.`jumlah_barang_rev_3`
						ELSE dt.`jumlah_barang`
					END	AS jumlah_barang_rev,
					CASE
						WHEN dt.`status_alokasi` = 'DATA ADDENDUM 1' THEN dt.`nilai_barang_rev_1`
						WHEN dt.`status_alokasi` = 'DATA ADDENDUM 2' THEN dt.`nilai_barang_rev_2`
						WHEN dt.`status_alokasi` = 'DATA ADDENDUM 3' THEN dt.`nilai_barang_rev_3`
						ELSE dt.`nilai_barang`
					END	AS nilai_barang_rev,
					CASE
						WHEN dt.`status_alokasi` = 'DATA ADDENDUM 1' THEN IFNULL(nilai_barang_rev_1/NULLIF(jumlah_barang_rev_1,0),0)
						WHEN dt.`status_alokasi` = 'DATA ADDENDUM 2' THEN IFNULL(nilai_barang_rev_2/NULLIF(jumlah_barang_rev_2,0),0)
						WHEN dt.`status_alokasi` = 'DATA ADDENDUM 3' THEN IFNULL(nilai_barang_rev_3/NULLIF(jumlah_barang_rev_3,0),0)
						ELSE IFNULL(dt.nilai_barang/NULLIF(dt.jumlah_barang,0),0)
					END	AS harga_satuan_rev,
					CASE
						WHEN dt.`id_hibah_pusat`IS NOT NULL THEN 'SUDAH'
						ELSE 'BELUM'
					END	AS status_rilis
				FROM 
				tb_alokasi_pusat dt 
				INNER JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
				LEFT JOIN `tb_penyedia_pusat` pen ON pen.`id` = hd.`id_penyedia_pusat`
				LEFT JOIN (SELECT id_kontrak_pusat, keterangan FROM `tb_sp2d` s GROUP BY id_kontrak_pusat, keterangan LIMIT 1) s ON s.`id_kontrak_pusat` = dt.`id_kontrak_pusat`
				INNER JOIN tb_provinsi p ON p.id = dt.`id_provinsi`
				INNER JOIN tb_kabupaten k ON k.id = dt.`id_kabupaten`
				WHERE hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
				if (isset($id)) $qry .= " and dt.id = $id";
				$qry .= " and
				(
					hd.`no_kontrak` LIKE '%".$search."%' 
					or pen.nama_penyedia_pusat LIKE '%".$search."%' 
					or hd.`merk` LIKE '%".$search."%' 
					or hd.nama_barang LIKE '%".$search."%' 
				)";
				//cek privilege penyedia/provinsi/kabupaten
				if(!isset($id)){
					if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)) $qry .= " and pen.id = ".$this->session->userdata('logged_in')->id_penyedia_pusat;
					if(isset($this->session->userdata('logged_in')->id_provinsi)) $qry .= " and p.id = ".$this->session->userdata('logged_in')->id_provinsi;
					if(isset($this->session->userdata('logged_in')->id_kabupaten)) $qry .= " and k.id = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				//cek param id
				if(isset($id)) $qry .= " and dt.id = $id";
				if(isset($id_kontrak_pusat)) $qry .= " and dt.id_kontrak_pusat = $id_kontrak_pusat";
				if(isset($filter)) {
					$filter = str_replace('jumlah_barang','dt.jumlah_barang',$filter);
					$qry .= $filter;
				}
				$qry .= " GROUP BY 
				hd.`tahun_anggaran`, hd.`no_kontrak`, hd.`periode_mulai`, hd.`periode_selesai`, hd.`nama_barang`, 
				hd.`merk`, dt.id, dt.`id_kontrak_pusat`, dt.`id_provinsi`, p.`nama_provinsi`, dt.`id_kabupaten`, 
				k.`nama_kabupaten`, dt.`nilai_barang`, dt.`jumlah_barang`, dt.`regcad`, dt.`dinas`, dt.`no_adendum_1`, 
				dt.`jumlah_barang_rev_1`, dt.`nilai_barang_rev_1`, dt.`no_adendum_2`, dt.`jumlah_barang_rev_2`, dt.`nilai_barang_rev_2`,
				dt.`no_adendum_3`, dt.`jumlah_barang_rev_3`, dt.`nilai_barang_rev_3`, 
				dt.`nama_file_adendum_1`, dt.`nama_file_adendum_2`, dt.`nama_file_adendum_3`, dt.`status_alokasi`, 
				dt.nama_file, pen.`nama_penyedia_pusat`,s.`keterangan`, 
				dt.id_hibah_pusat 
				ORDER BY ".$col." ".$dir;// , tahun_anggaran, hd.no_kontrak
			if($length > 0)
				$qry .=	' LIMIT ' . $start . ',' . $length;
			$res = $this->db->query($qry);
			if($res->num_rows() > 0)
				if(isset($id)){
					return $res->row();
				}else{
					return $res->result();
				}
			else
				return array();

			return array();
		}

		function get_confirmed($id = null, $id_kontrak_pusat = null,  $start = null, $length = null, $col = null, $dir = null, $filter = null, $search = null)
		{
			if(empty($col)) $col = 'updated_at';
			if(empty($dir)) $dir = 'DESC';
			$qry =	"SELECT * FROM (
				SELECT alo.id, alo.id_kontrak_pusat, id_hibah_pusat, alo.updated_at,
					hd.`tahun_anggaran`,
					hd.`no_kontrak`,
					CONCAT(DATE_FORMAT(hd.`periode_mulai`,'%d-%m-%Y'),' s/d ',DATE_FORMAT(hd.`periode_selesai`,'%d-%m-%Y')) periode,
					hd.periode_mulai, hd.periode_selesai,
					hd.`nama_barang`,
					hd.`merk`,
					p.`nama_provinsi`,
					k.`nama_kabupaten`,
					alo.`jumlah_barang`, alo.`nilai_barang`,
					IFNULL(alo.nilai_barang/NULLIF(alo.jumlah_barang,0),0) harga_satuan,
					alo.`dinas`, 'REGULER' as `regcad`, 
					CASE
						WHEN alo.`status_alokasi` = 'DATA ADDENDUM 1' THEN alo.`no_adendum_1`
						WHEN alo.`status_alokasi` = 'DATA ADDENDUM 2' THEN alo.`no_adendum_2`
						WHEN alo.`status_alokasi` = 'DATA ADDENDUM 3' THEN alo.`no_adendum_3`
						ELSE ''
					END	AS no_adendum,
					CASE
						WHEN alo.`status_alokasi` = 'DATA ADDENDUM 1' THEN alo.`jumlah_barang_rev_1`
						WHEN alo.`status_alokasi` = 'DATA ADDENDUM 2' THEN alo.`jumlah_barang_rev_2`
						WHEN alo.`status_alokasi` = 'DATA ADDENDUM 3' THEN alo.`jumlah_barang_rev_3`
						ELSE alo.`jumlah_barang`
					END	AS jumlah_barang_rev,
					CASE
						WHEN alo.`status_alokasi` = 'DATA ADDENDUM 1' THEN alo.`nilai_barang_rev_1`
						WHEN alo.`status_alokasi` = 'DATA ADDENDUM 2' THEN alo.`nilai_barang_rev_2`
						WHEN alo.`status_alokasi` = 'DATA ADDENDUM 3' THEN alo.`nilai_barang_rev_3`
						ELSE alo.`nilai_barang`
					END	AS nilai_barang_rev,
					CASE
						WHEN alo.`status_alokasi` = 'DATA ADDENDUM 1' THEN IFNULL(nilai_barang_rev_1/NULLIF(jumlah_barang_rev_1,0),0)
						WHEN alo.`status_alokasi` = 'DATA ADDENDUM 2' THEN IFNULL(nilai_barang_rev_2/NULLIF(jumlah_barang_rev_2,0),0)
						WHEN alo.`status_alokasi` = 'DATA ADDENDUM 3' THEN IFNULL(nilai_barang_rev_3/NULLIF(jumlah_barang_rev_3,0),0)
						ELSE IFNULL(alo.nilai_barang/NULLIF(alo.jumlah_barang,0),0)
					END	AS harga_satuan_rev,
					pen.`nama_penyedia_pusat`, jumlah_termin, alo.termin, tb_sp2d.keterangan as sp2d, 'alokasi' as source,
					IFNULL((SELECT jumlah_barang FROM tb_baphp_reguler WHERE id_alokasi_pusat = alo.id LIMIT 1),0) as jumlah_baphp, 
					IFNULL((SELECT SUM(jumlah_barang) FROM tb_bastb_pusat WHERE id_alokasi_pusat = alo.id GROUP BY id_alokasi_pusat),0) as jumlah_bastb, 
					hd.created_at as kontrak_created, alo.created_at as alokasi_created, alo.updated_at as alokasi_updated	
				FROM 
				tb_alokasi_pusat alo
				INNER JOIN tb_kontrak_pusat hd ON hd.id = alo.id_kontrak_pusat
				LEFT JOIN `tb_penyedia_pusat` pen ON pen.`id` = hd.`id_penyedia_pusat`
				LEFT JOIN `tb_sp2d` ON tb_sp2d.id_kontrak_pusat = alo.`id_kontrak_pusat` AND alo.termin=tb_sp2d.keterangan
				INNER JOIN tb_provinsi p ON p.id = alo.`id_provinsi`
				INNER JOIN tb_kabupaten k ON k.id = alo.`id_kabupaten`
				WHERE 
				CASE
						WHEN alo.`status_alokasi` = 'DATA ADDENDUM 1' THEN alo.`jumlah_barang_rev_1` > 0
						WHEN alo.`status_alokasi` = 'DATA ADDENDUM 2' THEN alo.`jumlah_barang_rev_2` > 0
						WHEN alo.`status_alokasi` = 'DATA ADDENDUM 3' THEN alo.`jumlah_barang_rev_3` > 0
						ELSE alo.`jumlah_barang` > 0
					END and
				hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
				$qry .= " and alo.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
				if (isset($id)) $qry .= " and alo.id = $id";
				$qry .= " and
				(
					hd.`no_kontrak` LIKE '%".$search."%' 
					or pen.nama_penyedia_pusat LIKE '%".$search."%' 
					or hd.`merk` LIKE '%".$search."%' 
					or hd.nama_barang LIKE '%".$search."%' 
				)";
				//cek privilege penyedia/provinsi/kabupaten
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)) $qry .= " and pen.id = ".$this->session->userdata('logged_in')->id_penyedia_pusat;
				if(isset($this->session->userdata('logged_in')->id_provinsi)) $qry .= " and p.id = ".$this->session->userdata('logged_in')->id_provinsi;
				if(isset($this->session->userdata('logged_in')->id_kabupaten)) $qry .= " and k.id = ".$this->session->userdata('logged_in')->id_kabupaten;				
				if(isset($filter)) {
					$filter1 = str_replace('jumlah_barang','alo.jumlah_barang',$filter); // $filter1 so $filter not replaced for persediaan query below					
					$filter1 = str_replace('dinas','alo.dinas',$filter1); // ambiguos column because of union
					$filter1 = str_replace('replace_search_norangka',
					'and alo.id = 
					(SELECT tb_alokasi_pusat.id FROM tb_alokasi_pusat 
					INNER JOIN tb_baphp_reguler ON tb_baphp_reguler.id_alokasi_pusat = tb_alokasi_pusat.id 
					INNER JOIN tb_baphp_norangka ON tb_baphp_norangka.id_baphp = tb_baphp_reguler.id 
					INNER JOIN tb_norangka ON tb_norangka.id = tb_baphp_norangka.id_norangka 
					WHERE ',$filter1);
					$qry .= $filter1;
				}
				$qry .= " GROUP BY 
				hd.`tahun_anggaran`, hd.`no_kontrak`, hd.`periode_mulai`, hd.`periode_selesai`, hd.`nama_barang`, 
				hd.`merk`, alo.id, alo.`id_kontrak_pusat`, p.`nama_provinsi`,  
				k.`nama_kabupaten`, alo.`nilai_barang`, alo.`jumlah_barang`, alo.`dinas`, 
				alo.`no_adendum_1`, alo.`no_adendum_2`, alo.`no_adendum_3`, 
				alo.`status_alokasi`, pen.`nama_penyedia_pusat`,`keterangan`";
				$qry .=	" UNION 	
				SELECT  dt.id, alo.id_kontrak_pusat, dt.id_hibah_pusat, dt.updated_at,
					hd.`tahun_anggaran`,
					hd.`no_kontrak`,
					CONCAT(DATE_FORMAT(hd.`periode_mulai`,'%d-%m-%Y'),' s/d ',DATE_FORMAT(hd.`periode_selesai`,'%d-%m-%Y')) periode,
					hd.periode_mulai, hd.periode_selesai,
					hd.`nama_barang`,
					hd.`merk`,
					p.`nama_provinsi`,
					k.`nama_kabupaten`,
					dt.`jumlah_barang`, dt.`nilai_barang`,
					IFNULL(dt.nilai_barang/NULLIF(dt.jumlah_barang,0),0) harga_satuan,
					dt.`dinas`, 'PERSEDIAAN' as `regcad`, 
					null AS no_adendum,
					dt.jumlah_barang AS jumlah_barang_rev,
					dt.nilai_barang AS nilai_barang_rev,
					IFNULL(dt.nilai_barang/NULLIF(dt.jumlah_barang,0),0) AS harga_satuan_rev,
					pen.`nama_penyedia_pusat`, hd.jumlah_termin, dt.termin, tb_sp2d.`keterangan` as sp2d, 'alokasi_persediaan' as source,
					IFNULL((SELECT jumlah_barang FROM tb_baphp_persediaan WHERE id_alokasi_persediaan_pusat = dt.id LIMIT 1),0) as jumlah_baphp, 
					IFNULL((SELECT SUM(jumlah_barang) FROM tb_bastb_persediaan WHERE id_alokasi_persediaan_pusat = dt.id GROUP BY id_alokasi_persediaan_pusat),0) as jumlah_bastb, 
					hd.created_at as kontrak_created, alo.created_at as alokasi_created, alo.updated_at as alokasi_updated
				FROM 
				tb_alokasi_persediaan_pusat dt 
				INNER JOIN tb_alokasi_pusat alo ON alo.id = dt.id_alokasi
				INNER JOIN tb_kontrak_pusat hd ON hd.id = alo.id_kontrak_pusat
				LEFT JOIN `tb_penyedia_pusat` pen ON pen.`id` = hd.`id_penyedia_pusat`
				LEFT JOIN `tb_sp2d` ON tb_sp2d.id_kontrak_pusat = alo.`id_kontrak_pusat` AND alo.termin=tb_sp2d.keterangan
				INNER JOIN tb_provinsi p ON p.id = dt.`id_provinsi`
				INNER JOIN tb_kabupaten k ON k.id = dt.`id_kabupaten`
				WHERE 
				dt.`jumlah_barang` > 0 and
				hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
				//  and hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
				$qry .= " and alo.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
				if (isset($id)) $qry .= " and dt.id = $id";
				$qry .= " and
				(
					hd.`no_kontrak` LIKE '%".$search."%' 
					or pen.nama_penyedia_pusat LIKE '%".$search."%' 
					or hd.`merk` LIKE '%".$search."%' 
					or hd.nama_barang LIKE '%".$search."%' 
				)";
				//cek privilege penyedia/provinsi/kabupaten
				if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)) $qry .= " and pen.id = ".$this->session->userdata('logged_in')->id_penyedia_pusat;
				if(isset($this->session->userdata('logged_in')->id_provinsi)) $qry .= " and p.id = ".$this->session->userdata('logged_in')->id_provinsi;
				if(isset($this->session->userdata('logged_in')->id_kabupaten)) $qry .= " and k.id = ".$this->session->userdata('logged_in')->id_kabupaten;
				if(isset($filter)) {
					$filter = str_replace('jumlah_barang','dt.jumlah_barang',$filter);
					$filter = str_replace('dinas','dt.dinas',$filter); // ambiguos column because of union
					$filter = str_replace('alo.no_adendum_1','dt.id',$filter); // alokasi_persediaan have no no_adendum, return empty
					$filter = str_replace('alo.no_adendum_2','dt.id',$filter); // alokasi_persediaan have no no_adendum, return empty
					$filter = str_replace('alo.no_adendum_3','dt.id',$filter); // alokasi_persediaan have no no_adendum, return empty
					$filter = str_replace('replace_search_norangka',
					'and dt.id = 
					(SELECT tb_alokasi_persediaan_pusat.id FROM tb_alokasi_persediaan_pusat 
INNER JOIN tb_baphp_persediaan ON tb_baphp_persediaan.id_alokasi_persediaan_pusat = tb_alokasi_persediaan_pusat.id 
INNER JOIN tb_baphp_persediaan_norangka ON tb_baphp_persediaan_norangka.id_baphp_persediaan = tb_baphp_persediaan.id 
INNER JOIN tb_norangka ON tb_norangka.id = tb_baphp_persediaan_norangka.id_norangka WHERE ',$filter);
					$qry .= $filter;
				}
				$qry .= " GROUP BY 
				hd.`tahun_anggaran`, hd.`no_kontrak`, hd.`periode_mulai`, hd.`periode_selesai`, hd.`nama_barang`, 
				hd.`merk`, dt.id, alo.`id_kontrak_pusat`, p.`nama_provinsi`,  
				k.`nama_kabupaten`, dt.`nilai_barang`, dt.`jumlah_barang`, dt.`dinas`, 
				dt.`status_alokasi`, pen.`nama_penyedia_pusat`,`keterangan`
				) alokasi_confirmed 
				ORDER BY $col $dir, updated_at DESC, kontrak_created DESC, no_kontrak ASC, alokasi_created DESC, source DESC";
				// $qry .= " ORDER BY tahun_anggaran, hd.no_kontrak, ".$col." ".$dir;
			if($length > 0) $qry .=	' LIMIT ' . $start . ',' . $length;

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				if(isset($id)){
					return $res->row();
				}else{
					return $res->result();
				}
			else
				return array();

			return array();
		}

		function store($data)
		{
			$this->db->insert('tb_alokasi_pusat',$data);
		}

		function update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_alokasi_pusat', $data);
		}

		function destroy($id)
		{
			$this->db->delete('tb_alokasi_pusat', array('id' => $id));
		}

		function total_unit($id = null, $id_kontrak_pusat = null, $addwhere = null)
		{
			$qry =	"	
				SELECT SUM(total_temp) AS total FROM (
					SELECT dt.status_alokasi, (CASE 	
						WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
							SUM(dt.jumlah_barang_rev_1)
						WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
							SUM(dt.jumlah_barang_rev_2)
						WHEN dt.status_alokasi = 'DATA ADDENDUM 3' THEN
							SUM(dt.jumlah_barang_rev_3)
						ELSE
							SUM(dt.jumlah_barang)
						END) AS total_temp
				FROM tb_alokasi_pusat dt 
				LEFT JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
				WHERE
					dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')
					and hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
					if (isset($id)) $qry .= " and dt.id = $id";
					if (isset($id_kontrak_pusat)) $qry .= " and dt.id_kontrak_pusat = $id_kontrak_pusat";
					if (isset($addwhere)) $qry .= " and $addwhere";
					$qry .= (isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				GROUP BY dt.status_alokasi) AS t_temp ";
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}

		function total_nilai($id = null, $id_kontrak_pusat = null)
		{
			$qry =	"	
				SELECT SUM(total_temp) AS total FROM (
					SELECT dt.status_alokasi, (CASE 	
						WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
							SUM(dt.nilai_barang_rev_1)
						WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
							SUM(dt.nilai_barang_rev_2)
						WHEN dt.status_alokasi = 'DATA ADDENDUM 3' THEN
							SUM(dt.nilai_barang_rev_3)
						ELSE
							SUM(dt.nilai_barang)
						END) AS total_temp
				FROM tb_alokasi_pusat dt 
				LEFT JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
				WHERE 
					dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')
					and hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
					if (isset($id)) $qry .= " and dt.id = $id";
					if (isset($id_kontrak_pusat)) $qry .= " and dt.id_kontrak_pusat = $id_kontrak_pusat";
				$qry .= (isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				GROUP BY dt.status_alokasi) AS t_temp ";

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return 0;
		}

		function total_unit_confirmed($id = null, $id_kontrak_pusat = null, $addwhere = null)
		{
			$qry =	"	
				SELECT SUM(total_temp) AS total FROM (
					SELECT dt.status_alokasi, (CASE 	
						WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
							SUM(dt.jumlah_barang_rev_1)
						WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
							SUM(dt.jumlah_barang_rev_2)
						WHEN dt.status_alokasi = 'DATA ADDENDUM 3' THEN
							SUM(dt.jumlah_barang_rev_3)
						ELSE
							SUM(dt.jumlah_barang)
						END) AS total_temp
				FROM tb_alokasi_pusat dt 
				INNER JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
				WHERE
					dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')
					and hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
					if (isset($id)) $qry .= " and dt.id = $id";
					if (isset($id_kontrak_pusat)) $qry .= " and dt.id_kontrak_pusat = $id_kontrak_pusat";
					if (isset($addwhere)) $qry .= " and $addwhere";
					$qry .= (isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				GROUP BY dt.status_alokasi";
			if($this->session->userdata('logged_in')->role_pengguna!='ADMIN PUSAT' && $this->session->userdata('logged_in')->role_pengguna!='ADMIN PENYEDIA PUSAT'){
				$qry .= " UNION 
				SELECT dt.status_alokasi, SUM(dt.jumlah_barang) AS total_temp
				FROM tb_alokasi_persediaan_pusat dt
				INNER JOIN tb_alokasi_pusat alo ON dt.id_alokasi=alo.id
				INNER JOIN tb_kontrak_pusat hd ON hd.id = alo.id_kontrak_pusat
				WHERE hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
				if (isset($id)) $qry .= " and dt.id = $id";
					if (isset($id_kontrak_pusat)) $qry .= " and dt.id_kontrak_pusat = $id_kontrak_pusat";
					if (isset($addwhere)) $qry .= " and $addwhere";
					$qry .= (isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
					GROUP BY dt.status_alokasi";
				} 
				$qry .= " ) AS t_temp 
				";
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}

		function total_nilai_confirmed($id = null, $id_kontrak_pusat = null)
		{
			$qry =	"	
				SELECT SUM(total_temp) AS total FROM (
					SELECT dt.status_alokasi, (CASE 	
						WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
							SUM(dt.nilai_barang_rev_1)
						WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
							SUM(dt.nilai_barang_rev_2)
						WHEN dt.status_alokasi = 'DATA ADDENDUM 3' THEN
							SUM(dt.nilai_barang_rev_3)
						ELSE
							SUM(dt.nilai_barang)
						END) AS total_temp
				FROM tb_alokasi_pusat dt 
				INNER JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
				WHERE 
					dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')
					and hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
					if (isset($id)) $qry .= " and dt.id = $id";
					if (isset($id_kontrak_pusat)) $qry .= " and dt.id_kontrak_pusat = $id_kontrak_pusat";
				$qry .= (isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				GROUP BY dt.status_alokasi";
			if($this->session->userdata('logged_in')->role_pengguna!='ADMIN PUSAT' && $this->session->userdata('logged_in')->role_pengguna!='ADMIN PENYEDIA PUSAT'){
				$qry .= " UNION
				SELECT dt.status_alokasi,SUM(dt.nilai_barang)AS total_temp
				FROM tb_alokasi_persediaan_pusat dt 
				INNER JOIN tb_alokasi_pusat alo ON dt.id_alokasi=alo.id
				INNER JOIN tb_kontrak_pusat hd ON hd.id = alo.id_kontrak_pusat
				WHERE 
					dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')
					and hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
					if (isset($id)) $qry .= " and dt.id = $id";
					if (isset($id_kontrak_pusat)) $qry .= " and dt.id_kontrak_pusat = $id_kontrak_pusat";
				$qry .= (isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				GROUP BY dt.status_alokasi";
			}
				$qry .= " ) AS t_temp ";

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return 0;
		}

		function total_kontrak($id_kontrak_pusat = null)
		{
			$qry =	"	
				SELECT COUNT(DISTINCT dt.id_kontrak_pusat) AS total 
				FROM tb_alokasi_pusat dt 
				LEFT JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
				WHERE 
					dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
				if (isset($id_kontrak_pusat)) $qry .= " and dt.id_kontrak_pusat = $id_kontrak_pusat";
				$qry .=	(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
					and hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return 0;
		}

		function total_merk($id_kontrak_pusat = null)
		{
			$qry =	"	
				SELECT COUNT(DISTINCT merk) AS total 
				FROM tb_alokasi_pusat dt 
				LEFT JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
				WHERE 
					dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')";
				if (isset($id_kontrak_pusat)) $qry .= " and dt.id_kontrak_pusat = $id_kontrak_pusat";
				$qry .= (isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
					and hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return 0;
		}
		
		function HitungJumlahAlokasi($id)
		{
			$qry =	"	
				SELECT SUM(nilai_sebelum_pajak) AS total FROM (
					SELECT dt.status_alokasi, (CASE 	
						WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
							SUM(dt.jumlah_barang_rev_1)
						WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
							SUM(dt.jumlah_barang_rev_2)
							WHEN dt.status_alokasi = 'DATA ADDENDUM 3' THEN
								SUM(dt.jumlah_barang_rev_3)
						ELSE
							SUM(dt.jumlah_barang)
						
					FROM tb_alokasi_pusat dt 
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

		function HitungJumlahAlokasiLainnya($id, $id_alokasi)
		{
			$qry =	"	
				SELECT SUM(total) AS total FROM (
					SELECT dt.status_alokasi, (CASE 	
						WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
							SUM(dt.jumlah_barang_rev_1)
						WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
							SUM(dt.jumlah_barang_rev_2)
						WHEN dt.status_alokasi = 'DATA ADDENDUM 3' THEN
							SUM(dt.jumlah_barang_rev_3)
						ELSE
							SUM(dt.jumlah_barang)
						END) total
					FROM tb_alokasi_pusat dt 
					LEFT JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
					WHERE hd.id = $id
					and dt.status_alokasi NOT IN('MENUNGGU KONFIRMASI')
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
					and dt.id != $id_alokasi
					GROUP BY dt.status_alokasi) 
				AS t_temp";
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}

		function UpdateHibahId($id_kontrak_detail, $id_hibah)
		{
			$qry =	"	
				UPDATE tb_alokasi_pusat SET id_hibah_pusat = $id_hibah
				WHERE id = $id_kontrak_detail";
	
			$this->db->query($qry);
		}

		function GetByHibah($id_hibah_pusat)
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
					dt.id, dt.`id_kontrak_pusat`, 
					dt.`id_provinsi`, p.`nama_provinsi`,
					dt.`id_kabupaten`, k.`nama_kabupaten`,
					dt.`nilai_barang`, dt.`jumlah_barang`, 
					dt.`regcad`, dt.`dinas`,
					dt.`no_adendum_1`, dt.`jumlah_barang_rev_1`, dt.`nilai_barang_rev_1`,
					dt.`no_adendum_2`, dt.`jumlah_barang_rev_2`, dt.`nilai_barang_rev_2`,dt.`no_adendum_3`, dt.`jumlah_barang_rev_3`, dt.`nilai_barang_rev_3`,
					dt.`nama_file_adendum_1`, dt.`nama_file_adendum_2`, dt.`nama_file_adendum_3`,
					dt.`status_alokasi`, dt.nama_file, 
					pen.`nama_penyedia_pusat`,s.`keterangan` as sp2d,
					dt.id_hibah_pusat,
					barang.akun, barang.kode_barang, barang.jenis_barang,
					CASE
						WHEN dt.`status_alokasi` = 'DATA ADDENDUM 1' THEN dt.`no_adendum_1`
						WHEN dt.`status_alokasi` = 'DATA ADDENDUM 2' THEN dt.`no_adendum_2`
						WHEN dt.`status_alokasi` = 'DATA ADDENDUM 3' THEN dt.`no_adendum_3`
						ELSE ''
					END	AS no_adendum
				FROM 
					tb_alokasi_pusat dt 
					LEFT JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
					LEFT JOIN `tb_jenis_barang_pusat` barang ON barang.`nama_barang` = hd.`nama_barang` AND barang.`merk` = hd.`merk`
					LEFT JOIN `tb_penyedia_pusat` pen ON pen.`id` = hd.`id_penyedia_pusat`
					LEFT JOIN `tb_sp2d` s ON s.`id_kontrak_pusat` = dt.`id_kontrak_pusat`
					INNER JOIN tb_provinsi p ON p.id = dt.`id_provinsi`
					INNER JOIN tb_kabupaten k ON k.id = dt.`id_kabupaten`
				WHERE 
					dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')
					and dt.id_hibah_pusat = $id_hibah_pusat
					and hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				ORDER BY tahun_anggaran, hd.nama_barang, hd.merk ";
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetByHibahGrouping($id_hibah_pusat){
			$qry =	"	
				SELECT hd.`nama_barang`, barang.`akun`, barang.kode_barang,
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
					LEFT JOIN `tb_jenis_barang_pusat` barang ON barang.`nama_barang` = hd.`nama_barang` AND barang.`merk` = hd.`merk`
					WHERE dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')
					AND dt.id_hibah_pusat = ".$id_hibah_pusat." AND hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				GROUP BY  hd.`nama_barang`, barang.`akun`
				ORDER BY hd.`nama_barang`, barang.`akun` ";
			
			// die($qry);
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		/*
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
					dt.id, dt.`id_kontrak_pusat`, 
					dt.`id_provinsi`, p.`nama_provinsi`,
					dt.`id_kabupaten`, k.`nama_kabupaten`,
					dt.`nilai_barang`, dt.`jumlah_barang`, 
					dt.`regcad`, dt.`dinas`,
					dt.`no_adendum_1`, dt.`jumlah_barang_rev_1`, dt.`nilai_barang_rev_1`,
					dt.`no_adendum_2`, dt.`jumlah_barang_rev_2`, dt.`nilai_barang_rev_2`,dt.`no_adendum_3`, dt.`jumlah_barang_rev_3`, dt.`nilai_barang_rev_3`,
					dt.`nama_file_adendum_1`, dt.`nama_file_adendum_2`, dt.`nama_file_adendum_3`,
					dt.`status_alokasi`, dt.nama_file, 
					pen.`nama_penyedia_pusat`,s.`keterangan` as sp2d,
					barang.akun, barang.kode_barang, barang.jenis_barang
				FROM 
					tb_alokasi_pusat dt 
					LEFT JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
					LEFT JOIN `tb_jenis_barang_pusat` barang ON barang.`nama_barang` = hd.`nama_barang` AND barang.`merk` = hd.`merk`
					LEFT JOIN `tb_penyedia_pusat` pen ON pen.`id` = hd.`id_penyedia_pusat`
					LEFT JOIN `tb_sp2d` s ON s.`id_kontrak_pusat` = dt.`id_kontrak_pusat`
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

		function GetUnit($id_kontrak_detail)
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
				FROM tb_alokasi_pusat dt 
				LEFT JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
				WHERE 
					dt.`status_alokasi` NOT IN ('MENUNGGU KONFIRMASI')
					and dt.id = $id_kontrak_detail
					and hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
					".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
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
					and dt.id = $id_kontrak_detail
					and hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
					".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				GROUP BY dt.status_alokasi) AS t_temp ";
	
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}

		*/

	}
?>