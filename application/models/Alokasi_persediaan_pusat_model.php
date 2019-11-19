<?php
	class Alokasi_persediaan_pusat_model extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function get($id = null, $id_alokasi = null,  $start = null, $length = null, $col = null, $dir = null, $filter = null, $search = null)
		{
			if(empty($col)) $col = 'dt.created_at';
			if(empty($dir)) $dir = 'DESC';
			$qry =	"	
				SELECT 
					dt.id, hd.`tahun_anggaran`, id_alokasi,
					hd.`no_kontrak`,
					hd.`periode_mulai`,
					hd.`periode_selesai`,
					hd.`nama_barang`,
					hd.`merk`,
					barang.`jenis_barang`,
					dt.id, al.`id_kontrak_pusat`, 
					dt.`id_provinsi`, p.`nama_provinsi`,
					dt.`id_kabupaten`, k.`nama_kabupaten`,
					dt.`nilai_barang`, dt.`jumlah_barang`, 
				IFNULL(dt.nilai_barang/NULLIF(dt.jumlah_barang,0),0) harga_satuan,				
					dt.`dinas`,
					dt.`status_alokasi`, dt.nama_file, 
					pen.`nama_penyedia_pusat`					
				FROM 
				tb_alokasi_persediaan_pusat dt 
				INNER JOIN tb_alokasi_pusat al ON al.id = dt.id_alokasi
				INNER JOIN tb_kontrak_pusat hd ON hd.id = al.id_kontrak_pusat
				INNER JOIN tb_provinsi p ON p.id = dt.`id_provinsi`
				INNER JOIN tb_kabupaten k ON k.id = dt.`id_kabupaten`
				LEFT JOIN `tb_jenis_barang_pusat` barang ON barang.`nama_barang` = hd.`nama_barang` AND barang.`merk` = hd.`merk`
				LEFT JOIN `tb_penyedia_pusat` pen ON pen.`id` = hd.`id_penyedia_pusat`
				WHERE hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
				if (isset($id_alokasi)) $qry .= " and dt.id_alokasi = $id_alokasi";
				if (isset($id)) $qry .= " and dt.id = $id";
				$qry .= " and
				(
					hd.`no_kontrak` LIKE '%".$search."%' 
					or pen.nama_penyedia_pusat LIKE '%".$search."%' 
					or hd.`merk` LIKE '%".$search."%' 
					or hd.nama_barang LIKE '%".$search."%' 
				)
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " 
				and pen.id = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "");
				if (isset($id)) $qry .= " and dt.id = $id";
				$qry .= $filter;
				$qry .= "
				ORDER BY ".$col." ".$dir;
			if($length > 0)
				$qry .=	' LIMIT ' . $start . ',' . $length;
					// echo $qry;exit();
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
			$this->db->insert('tb_alokasi_persediaan_pusat',$data);
		}

		function update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_alokasi_persediaan_pusat', $data);
		}

		function destroy($id)
		{
			$this->db->delete('tb_alokasi_persediaan_pusat', array('id' => $id));
		}

		function total_unit($id_alokasi = null)
		{
			$qry =	"	
				SELECT SUM(dt.jumlah_barang) AS total
				FROM tb_alokasi_persediaan_pusat dt 
				INNER JOIN tb_alokasi_pusat al ON al.id = dt.id_alokasi
				INNER JOIN tb_kontrak_pusat hd ON hd.id = al.id_kontrak_pusat
				WHERE hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
				if(isset($id_alokasi)) $qry .= " and id_alokasi =".$id_alokasi;
				$qry .= (isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "");
				if(isset($id_alokasi)) $qry .= " GROUP BY dt.id_alokasi";
				// echo $qry;exit();
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return 0;
		}

		function total_nilai($id_alokasi = null)
		{
			$qry =	"	
				SELECT SUM(dt.nilai_barang) AS total
				FROM tb_alokasi_persediaan_pusat dt 
				INNER JOIN tb_alokasi_pusat al ON al.id = dt.id_alokasi
				INNER JOIN tb_kontrak_pusat hd ON hd.id = al.id_kontrak_pusat
				WHERE hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
				if(isset($id_alokasi)) $qry .= " and id_alokasi =".$id_alokasi;
				$qry .= (isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "");
				if(isset($id_alokasi)) $qry .= " GROUP BY dt.id_alokasi";
	
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return 0;
		}

		function get_rekap($id = null,  $start = null, $length = null, $col = null, $dir = null, $filter = null, $search = null)
		{
			if(empty($col)) $col = 'created_at';
			if(empty($dir)) $dir = 'DESC';
		$qry =	"SELECT tahun_anggaran, merk, nama_barang, nama_penyedia_pusat, no_kontrak, periode_mulai, periode_selesai,
				IFNULL(SUM(alokasi),0) as alokasi, SUM(jumlah_barang) as jumlah_barang FROM (
				SELECT tb_alokasi_pusat.id, tahun_anggaran, merk, nama_barang, nama_penyedia_pusat, no_kontrak, periode_mulai, periode_selesai,
				IFNULL(SUM(tb_alokasi_persediaan_pusat.jumlah_barang),0) as alokasi, tb_alokasi_pusat.jumlah_barang, tb_kontrak_pusat.created_at
				FROM tb_alokasi_pusat
				LEFT JOIN tb_alokasi_persediaan_pusat ON tb_alokasi_persediaan_pusat.id_alokasi = tb_alokasi_pusat.id
				INNER JOIN tb_kontrak_pusat ON tb_alokasi_pusat.id_kontrak_pusat = tb_kontrak_pusat.id
				INNER JOIN tb_penyedia_pusat ON tb_kontrak_pusat.id_penyedia_pusat = tb_penyedia_pusat.id
				WHERE tb_alokasi_pusat.Dinas='PUSAT' AND tb_kontrak_pusat.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan." 				
					".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and tb_kontrak_pusat.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				";
				$qry .= $filter;
				$qry .= ' GROUP BY tb_alokasi_pusat.id)a GROUP BY id';
				$qry .= "
				ORDER BY ".$col." ".$dir;
			if($length > 0)
				$qry .=	' LIMIT ' . $start . ',' . $length;
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function total_unit_rekap()
		{
			$qry =	"	
				SELECT SUM(dt.jumlah_barang) AS total
				FROM tb_alokasi_persediaan_pusat dt 
				INNER JOIN tb_alokasi_pusat al ON al.id = dt.id_alokasi
				INNER JOIN tb_kontrak_pusat hd ON hd.id = al.id_kontrak_pusat
				WHERE hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
					".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				";
		// echo $qry;exit();
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return 0;
		}

		function total_pagu_rekap()
		{
			$qry =	"	
			SELECT SUM(total) AS total FROM (
				SELECT CASE
									WHEN tb_alokasi_pusat.`status_alokasi` = 'DATA ADDENDUM 1' THEN tb_alokasi_pusat.`jumlah_barang_rev_1`
									WHEN tb_alokasi_pusat.`status_alokasi` = 'DATA ADDENDUM 2' THEN tb_alokasi_pusat.`jumlah_barang_rev_2`
									WHEN tb_alokasi_pusat.`status_alokasi` = 'DATA ADDENDUM 3' THEN tb_alokasi_pusat.`jumlah_barang_rev_3`
									ELSE tb_alokasi_pusat.jumlah_barang
								END AS total
							FROM tb_alokasi_pusat
				INNER JOIN tb_kontrak_pusat ON tb_kontrak_pusat.id = tb_alokasi_pusat.id_kontrak_pusat
				WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				AND dinas = 'PUSAT'
					".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and tb_kontrak_pusat.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				)a";
		// echo $qry;exit();
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return 0;
		}

		function getFile($param = null)
		{
			$qry =	"	
			SELECT 				
			tb_alokasi_persediaan_pusat.nama_file
			FROM tb_alokasi_persediaan_pusat 
			INNER JOIN tb_alokasi_pusat ON tb_alokasi_pusat.id=tb_alokasi_persediaan_pusat.id_alokasi
			INNER JOIN tb_kontrak_pusat ON tb_alokasi_pusat.id_kontrak_pusat=tb_kontrak_pusat.id
			WHERE 1=1 ";
			if (isset($param['id_kontrak_pusat'])) $qry .= " and tb_kontrak_pusat.id =". $param['id_kontrak_pusat'];
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

	}
?>