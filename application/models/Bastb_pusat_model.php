<?php
	class Bastb_pusat_model extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function get($id = null, $id_alokasi = null,  $start = null, $length = null, $col = null, $dir = null, $filter = null, $search = null)
		{
			if(empty($col)) $col = 'tb_bastb_pusat.updated_at';
			if(empty($dir)) $dir = 'DESC';
			$qry =	"	
			SELECT 
				tb_bastb_pusat.id as id, tb_bastb_pusat.id_alokasi_pusat, tb_alokasi_pusat.id_kontrak_pusat,
				tb_bastb_pusat.tahun_anggaran, tb_bastb_pusat.kelompok_penerima, tb_bastb_pusat.kelompok_penerima,
				tb_bastb_pusat.no_bastb, tb_bastb_pusat.tanggal,
				tb_bastb_pusat.pihak_penyerah,
				tb_bastb_pusat.nama_penyerah,
				tb_bastb_pusat.jabatan_penyerah,
				tb_bastb_pusat.notelp_penyerah,
				tb_bastb_pusat.alamat_penyerah,
				tb_bastb_pusat.id_provinsi_penyerah, prov_peny.nama_provinsi as provinsi_penyerah,
				tb_bastb_pusat.id_kabupaten_penyerah, kab_peny.nama_kabupaten as kabupaten_penyerah,
				tb_bastb_pusat.pihak_penerima, 
				tb_bastb_pusat.nama_penerima,
				tb_bastb_pusat.jabatan_penerima,nik_penerima,
				tb_bastb_pusat.notelp_penerima,
				tb_bastb_pusat.alamat_penerima,
				tb_bastb_pusat.id_provinsi_penerima, prov_pene.nama_provinsi as provinsi_penerima,
				tb_bastb_pusat.id_kabupaten_penerima, kab_pene.nama_kabupaten as kabupaten_penerima,
				tb_bastb_pusat.id_kecamatan_penerima, tb_bastb_pusat.id_kelurahan_penerima,
				nama_kecamatan, nama_kelurahan,
				tb_bastb_pusat.no_kontrak,
				tb_bastb_pusat.nama_barang, tb_bastb_pusat.merk, tb_bastb_pusat.jumlah_barang, tb_bastb_pusat.nilai_barang,
				CASE tb_bastb_pusat.jumlah_barang WHEN 0 THEN 0 ELSE tb_bastb_pusat.nilai_barang/tb_bastb_pusat.jumlah_barang END harga_satuan,
				tb_bastb_pusat.nama_file, tb_bastb_pusat.nama_filefoto,
				tb_bastb_pusat.nama_mengetahui,
				tb_bastb_pusat.jabatan_mengetahui, nama_penyedia_pusat, 'reg' as source
			FROM tb_bastb_pusat 
			INNER JOIN tb_alokasi_pusat ON tb_alokasi_pusat.id=tb_bastb_pusat.id_alokasi_pusat
			INNER JOIN tb_kontrak_pusat ON tb_kontrak_pusat.id=tb_alokasi_pusat.id_kontrak_pusat
			INNER JOIN tb_penyedia_pusat ON tb_penyedia_pusat.id=tb_kontrak_pusat.id_penyedia_pusat
			LEFT JOIN tb_provinsi prov_peny ON tb_bastb_pusat.id_provinsi_penyerah = prov_peny.id
			LEFT JOIN tb_provinsi prov_pene ON tb_bastb_pusat.id_provinsi_penerima = prov_pene.id
			LEFT JOIN tb_kabupaten kab_peny ON tb_bastb_pusat.id_kabupaten_penyerah = kab_peny.id
			LEFT JOIN tb_kabupaten kab_pene ON tb_bastb_pusat.id_kabupaten_penerima = kab_pene.id
			LEFT JOIN tb_kecamatan ON tb_bastb_pusat.id_kecamatan_penerima = tb_kecamatan.id
			LEFT JOIN tb_kelurahan ON tb_bastb_pusat.id_kelurahan_penerima = tb_kelurahan.id
			WHERE tb_kontrak_pusat.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
				if (isset($id_alokasi)) $qry .= " and tb_bastb_pusat.id_alokasi_pusat = $id_alokasi";
				if (isset($id)) $qry .= " and tb_bastb_pusat.id = $id";
				$qry .= $filter;
				//cek privilege penyedia/provinsi/kabupaten
				if(!isset($id)){
					if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)) $qry .= " and tb_penyedia_pusat.id = ".$this->session->userdata('logged_in')->id_penyedia_pusat;
					if(isset($this->session->userdata('logged_in')->id_provinsi)) $qry .= " and prov_peny.id = ".$this->session->userdata('logged_in')->id_provinsi;
					if(isset($this->session->userdata('logged_in')->id_kabupaten)) $qry .= " and kab_peny.id = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				$qry .= $filter;
				$qry .= "
				ORDER BY ".$col." ".$dir;
			if($length > 0) $qry .=	' LIMIT ' . $start . ',' . $length;
			// echo $qry;exit();
			// if(!empty($filter)) {echo $qry;exit();}
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

		function get_regcad($id = null, $id_alokasi = null,  $start = null, $length = null, $col = null, $dir = null, $filter = null, $search = null)
		{
			if(empty($col)) $col = 'updated_at';
			if(empty($dir)) $dir = 'DESC';
			$qry =	"SELECT 
				tb_bastb_pusat.id as id, tb_bastb_pusat.updated_at,
				tb_bastb_pusat.tahun_anggaran, tb_bastb_pusat.kelompok_penerima, tb_bastb_pusat.kelompok_penerima,
				tb_bastb_pusat.no_bastb, tb_bastb_pusat.tanggal,
				tb_bastb_pusat.pihak_penyerah,
				tb_bastb_pusat.nama_penyerah,
				tb_bastb_pusat.jabatan_penyerah,
				tb_bastb_pusat.notelp_penyerah,
				tb_bastb_pusat.alamat_penyerah,
				tb_bastb_pusat.id_provinsi_penyerah, prov_peny.nama_provinsi as provinsi_penyerah,
				tb_bastb_pusat.id_kabupaten_penyerah, kab_peny.nama_kabupaten as kabupaten_penyerah,
				tb_bastb_pusat.pihak_penerima, 
				tb_bastb_pusat.nama_penerima,
				tb_bastb_pusat.jabatan_penerima,nik_penerima,
				tb_bastb_pusat.notelp_penerima,
				tb_bastb_pusat.alamat_penerima,
				tb_bastb_pusat.id_provinsi_penerima, prov_pene.nama_provinsi as provinsi_penerima,
				tb_bastb_pusat.id_kabupaten_penerima, kab_pene.nama_kabupaten as kabupaten_penerima,
				tb_bastb_pusat.id_kecamatan_penerima, tb_bastb_pusat.id_kelurahan_penerima,
				nama_kecamatan, nama_kelurahan,
				tb_bastb_pusat.no_kontrak,
				tb_bastb_pusat.nama_barang, tb_bastb_pusat.merk, tb_bastb_pusat.jumlah_barang, tb_bastb_pusat.nilai_barang,
				CASE tb_bastb_pusat.jumlah_barang WHEN 0 THEN 0 ELSE tb_bastb_pusat.nilai_barang/tb_bastb_pusat.jumlah_barang END harga_satuan,
				tb_bastb_pusat.nama_file, tb_bastb_pusat.nama_filefoto,
				tb_bastb_pusat.nama_mengetahui,
				tb_bastb_pusat.jabatan_mengetahui, nama_penyedia_pusat, tb_bastb_pusat.created_at,'reg' as source
			FROM tb_bastb_pusat 
			INNER JOIN tb_alokasi_pusat ON tb_alokasi_pusat.id=tb_bastb_pusat.id_alokasi_pusat
			INNER JOIN tb_kontrak_pusat ON tb_kontrak_pusat.id=tb_alokasi_pusat.id_kontrak_pusat
			INNER JOIN tb_penyedia_pusat ON tb_penyedia_pusat.id=tb_kontrak_pusat.id_penyedia_pusat
			LEFT JOIN tb_provinsi prov_peny ON tb_bastb_pusat.id_provinsi_penyerah = prov_peny.id
			LEFT JOIN tb_provinsi prov_pene ON tb_bastb_pusat.id_provinsi_penerima = prov_pene.id
			LEFT JOIN tb_kabupaten kab_peny ON tb_bastb_pusat.id_kabupaten_penyerah = kab_peny.id
			LEFT JOIN tb_kabupaten kab_pene ON tb_bastb_pusat.id_kabupaten_penerima = kab_pene.id
			LEFT JOIN tb_kecamatan ON tb_bastb_pusat.id_kecamatan_penerima = tb_kecamatan.id
			LEFT JOIN tb_kelurahan ON tb_bastb_pusat.id_kelurahan_penerima = tb_kelurahan.id
			WHERE tb_bastb_pusat.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
				if (isset($id_alokasi)) $qry .= " and tb_bastb_pusat.id_alokasi_pusat = $id_alokasi";
				if (isset($id)) $qry .= " and tb_bastb_pusat.id = $id";
				$qry .= $filter;
				//cek privilege penyedia/provinsi/kabupaten
				if(!isset($id)){
					if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)) $qry .= " and tb_penyedia_pusat.id = ".$this->session->userdata('logged_in')->id_penyedia_pusat;
					if(isset($this->session->userdata('logged_in')->id_provinsi)) $qry .= " and prov_peny.id = ".$this->session->userdata('logged_in')->id_provinsi;
					if(isset($this->session->userdata('logged_in')->id_kabupaten)) $qry .= " and kab_peny.id = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				if (isset($id)) $qry .= " and tb_bastb_pusat.id = $id";
				$qry .= $filter;
				$qry .= " UNION ";
				$qry .=	"SELECT 
				tb_bastb_persediaan.id as id, tb_bastb_persediaan.updated_at,
				tb_bastb_persediaan.tahun_anggaran, tb_bastb_persediaan.kelompok_penerima, tb_bastb_persediaan.kelompok_penerima,
				tb_bastb_persediaan.no_bastb, tb_bastb_persediaan.tanggal,
				tb_bastb_persediaan.pihak_penyerah,
				tb_bastb_persediaan.nama_penyerah,
				tb_bastb_persediaan.jabatan_penyerah,
				tb_bastb_persediaan.notelp_penyerah,
				tb_bastb_persediaan.alamat_penyerah,
				tb_bastb_persediaan.id_provinsi_penyerah, prov_peny.nama_provinsi as provinsi_penyerah,
				tb_bastb_persediaan.id_kabupaten_penyerah, kab_peny.nama_kabupaten as kabupaten_penyerah,
				tb_bastb_persediaan.pihak_penerima, 
				tb_bastb_persediaan.nama_penerima,
				tb_bastb_persediaan.jabatan_penerima,nik_penerima,
				tb_bastb_persediaan.notelp_penerima,
				tb_bastb_persediaan.alamat_penerima,
				tb_bastb_persediaan.id_provinsi_penerima, prov_pene.nama_provinsi as provinsi_penerima,
				tb_bastb_persediaan.id_kabupaten_penerima, kab_pene.nama_kabupaten as kabupaten_penerima,
				tb_bastb_persediaan.id_kecamatan_penerima, tb_bastb_persediaan.id_kelurahan_penerima,
				nama_kecamatan, nama_kelurahan,
				tb_bastb_persediaan.no_kontrak,
				tb_bastb_persediaan.nama_barang, tb_bastb_persediaan.merk, tb_bastb_persediaan.jumlah_barang, tb_bastb_persediaan.nilai_barang,
				CASE tb_bastb_persediaan.jumlah_barang WHEN 0 THEN 0 ELSE tb_bastb_persediaan.nilai_barang/tb_bastb_persediaan.jumlah_barang END harga_satuan,
				tb_bastb_persediaan.nama_file, tb_bastb_persediaan.nama_filefoto,
				tb_bastb_persediaan.nama_mengetahui,
				tb_bastb_persediaan.jabatan_mengetahui, nama_penyedia_pusat, tb_bastb_persediaan.created_at,'cad' as source
			FROM tb_bastb_persediaan
			INNER JOIN tb_alokasi_persediaan_pusat ON tb_alokasi_persediaan_pusat.id=tb_bastb_persediaan.id_alokasi_persediaan_pusat
			INNER JOIN tb_alokasi_pusat ON tb_alokasi_pusat.id=tb_alokasi_persediaan_pusat.id_alokasi
			INNER JOIN tb_kontrak_pusat ON tb_kontrak_pusat.id=tb_alokasi_pusat.id_kontrak_pusat
			INNER JOIN tb_penyedia_pusat ON tb_penyedia_pusat.id=tb_kontrak_pusat.id_penyedia_pusat
			LEFT JOIN tb_provinsi prov_peny ON tb_bastb_persediaan.id_provinsi_penyerah = prov_peny.id
			LEFT JOIN tb_provinsi prov_pene ON tb_bastb_persediaan.id_provinsi_penerima = prov_pene.id
			LEFT JOIN tb_kabupaten kab_peny ON tb_bastb_persediaan.id_kabupaten_penyerah = kab_peny.id
			LEFT JOIN tb_kabupaten kab_pene ON tb_bastb_persediaan.id_kabupaten_penerima = kab_pene.id
			LEFT JOIN tb_kecamatan ON tb_bastb_persediaan.id_kecamatan_penerima = tb_kecamatan.id
			LEFT JOIN tb_kelurahan ON tb_bastb_persediaan.id_kelurahan_penerima = tb_kelurahan.id
			WHERE tb_bastb_persediaan.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
				if (isset($id_alokasi)) $qry .= " and tb_bastb_persediaan.id_alokasi_pusat = $id_alokasi";
				if (isset($id)) $qry .= " and tb_bastb_persediaan.id = $id";
				$filtercad = str_replace('tb_bastb_pusat','tb_bastb_persediaan',$filter);
				$qry .= $filtercad;
				//cek privilege penyedia/provinsi/kabupaten
				if(!isset($id)){
					if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)) $qry .= " and tb_penyedia_pusat.id = ".$this->session->userdata('logged_in')->id_penyedia_pusat;
					if(isset($this->session->userdata('logged_in')->id_provinsi)) $qry .= " and prov_peny.id = ".$this->session->userdata('logged_in')->id_provinsi;
					if(isset($this->session->userdata('logged_in')->id_kabupaten)) $qry .= " and kab_peny.id = ".$this->session->userdata('logged_in')->id_kabupaten;
				}				
				$qry .= "
				ORDER BY ".$col." ".$dir;
			if($length > 0) $qry .=	' LIMIT ' . $start . ',' . $length;
			// if(!empty($filter)) echo $qry;exit();
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

		function get_regcad_count($id = null, $id_alokasi = null,  $start = null, $length = null, $col = null, $dir = null, $filter = null, $search = null)
		{
			$qry =	"SELECT sum(cou) as total FROM (
			SELECT count(1) as cou
			FROM tb_bastb_pusat 
			INNER JOIN tb_alokasi_pusat ON tb_alokasi_pusat.id=tb_bastb_pusat.id_alokasi_pusat
			INNER JOIN tb_kontrak_pusat ON tb_kontrak_pusat.id=tb_alokasi_pusat.id_kontrak_pusat
			INNER JOIN tb_penyedia_pusat ON tb_penyedia_pusat.id=tb_kontrak_pusat.id_penyedia_pusat
			LEFT JOIN tb_provinsi prov_peny ON tb_bastb_pusat.id_provinsi_penyerah = prov_peny.id
			LEFT JOIN tb_provinsi prov_pene ON tb_bastb_pusat.id_provinsi_penerima = prov_pene.id
			LEFT JOIN tb_kabupaten kab_peny ON tb_bastb_pusat.id_kabupaten_penyerah = kab_peny.id
			LEFT JOIN tb_kabupaten kab_pene ON tb_bastb_pusat.id_kabupaten_penerima = kab_pene.id
			LEFT JOIN tb_kecamatan ON tb_bastb_pusat.id_kecamatan_penerima = tb_kecamatan.id
			LEFT JOIN tb_kelurahan ON tb_bastb_pusat.id_kelurahan_penerima = tb_kelurahan.id
			WHERE tb_bastb_pusat.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
				$qry .= $filter;
				//cek privilege penyedia/provinsi/kabupaten
				if(!isset($id)){
					if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)) $qry .= " and tb_penyedia_pusat.id = ".$this->session->userdata('logged_in')->id_penyedia_pusat;
					if(isset($this->session->userdata('logged_in')->id_provinsi)) $qry .= " and prov_peny.id = ".$this->session->userdata('logged_in')->id_provinsi;
					if(isset($this->session->userdata('logged_in')->id_kabupaten)) $qry .= " and kab_peny.id = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				if (isset($id)) $qry .= " and tb_bastb_pusat.id = $id";
				$qry .= " UNION ";
				$qry .=	"SELECT count(1) as cou
			FROM tb_bastb_persediaan
			INNER JOIN tb_alokasi_persediaan_pusat ON tb_alokasi_persediaan_pusat.id=tb_bastb_persediaan.id_alokasi_persediaan_pusat
			INNER JOIN tb_alokasi_pusat ON tb_alokasi_pusat.id=tb_alokasi_persediaan_pusat.id_alokasi
			INNER JOIN tb_kontrak_pusat ON tb_kontrak_pusat.id=tb_alokasi_pusat.id_kontrak_pusat
			INNER JOIN tb_penyedia_pusat ON tb_penyedia_pusat.id=tb_kontrak_pusat.id_penyedia_pusat
			LEFT JOIN tb_provinsi prov_peny ON tb_bastb_persediaan.id_provinsi_penyerah = prov_peny.id
			LEFT JOIN tb_provinsi prov_pene ON tb_bastb_persediaan.id_provinsi_penerima = prov_pene.id
			LEFT JOIN tb_kabupaten kab_peny ON tb_bastb_persediaan.id_kabupaten_penyerah = kab_peny.id
			LEFT JOIN tb_kabupaten kab_pene ON tb_bastb_persediaan.id_kabupaten_penerima = kab_pene.id
			LEFT JOIN tb_kecamatan ON tb_bastb_persediaan.id_kecamatan_penerima = tb_kecamatan.id
			LEFT JOIN tb_kelurahan ON tb_bastb_persediaan.id_kelurahan_penerima = tb_kelurahan.id
			WHERE tb_bastb_persediaan.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
				$filtercad = str_replace('tb_bastb_pusat','tb_bastb_persediaan',$filter);
				$qry .= $filtercad;
				//cek privilege penyedia/provinsi/kabupaten
				if(!isset($id)){
					if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)) $qry .= " and tb_penyedia_pusat.id = ".$this->session->userdata('logged_in')->id_penyedia_pusat;
					if(isset($this->session->userdata('logged_in')->id_provinsi)) $qry .= " and prov_peny.id = ".$this->session->userdata('logged_in')->id_provinsi;
					if(isset($this->session->userdata('logged_in')->id_kabupaten)) $qry .= " and kab_peny.id = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				$qry .= ")a";
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
					return $res->row()->total;
			else
				return array();

			return array();
		}

		function store($data)
		{
			$this->db->insert('tb_bastb_pusat',$data);
		}

		function update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_bastb_pusat',$data);
		}

		function destroy($id)
		{
			$this->db->delete('tb_bastb_pusat_norangka', array('id_bastb_pusat' => $id));
			$this->db->delete('tb_bastb_pusat', array('id' => $id));
		}

		function total_unit($id_alokasi = null, $id_kontrak_pusat = null)
		{
			$qry =	"	
				SELECT SUM(dt.jumlah_barang) AS total
				FROM tb_bastb_pusat dt 
				INNER JOIN tb_alokasi_pusat al ON al.id = dt.id_alokasi_pusat
				INNER JOIN tb_kontrak_pusat hd ON hd.id = al.id_kontrak_pusat
				WHERE hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan; 
			if (isset($id_alokasi)) $qry .= " and dt.id_alokasi_pusat = $id_alokasi";
			if (isset($id_kontrak_pusat)) $qry .= " and al.id_kontrak_pusat = $id_kontrak_pusat";
			$qry .= (isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				GROUP BY dt.id_alokasi_pusat";
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return 0;
		}

		function total_unit_regcad()
		{
			$qry =	"	
				SELECT SUM(dt.jumlah_barang) AS total
				FROM tb_bastb_pusat dt 
				INNER JOIN tb_alokasi_pusat al ON al.id = dt.id_alokasi_pusat
				INNER JOIN tb_kontrak_pusat hd ON hd.id = al.id_kontrak_pusat
				WHERE hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan; 
			$qry .= (isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				";
			$res = $this->db->query($qry);
			$total = $res->row()->total;

			$qry =	"	
				SELECT SUM(dt.jumlah_barang) AS total
				FROM tb_bastb_persediaan dt 
				INNER JOIN tb_alokasi_persediaan_pusat alp ON alp.id = dt.id_alokasi_persediaan_pusat
				INNER JOIN tb_alokasi_pusat al ON al.id = alp.id_alokasi
				INNER JOIN tb_kontrak_pusat hd ON hd.id = al.id_kontrak_pusat
				WHERE hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan; 
			$qry .= (isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				";
			$res = $this->db->query($qry);
			$total += $res->row()->total;

			if($res->num_rows() > 0)
				return $total;
			else
				return 0;
		}

		function total_nilai($id_alokasi = null, $id_kontrak_pusat = null)
		{
			$qry =	"	
				SELECT SUM(dt.nilai_barang) AS total
				FROM tb_bastb_pusat dt 
				INNER JOIN tb_alokasi_pusat al ON al.id = dt.id_alokasi_pusat
				INNER JOIN tb_kontrak_pusat hd ON hd.id = al.id_kontrak_pusat
				WHERE hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
			if (isset($id_alokasi)) $qry .= " and dt.id_alokasi_pusat = $id_alokasi";
			if (isset($id_kontrak_pusat)) $qry .= " and al.id_kontrak_pusat = $id_kontrak_pusat";
			$qry .= (isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				GROUP BY dt.id_alokasi_pusat";
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return 0;
		}
		function total_nilai_regcad()
		{
			$qry =	"	
				SELECT SUM(dt.nilai_barang) AS total
				FROM tb_bastb_pusat dt 
				INNER JOIN tb_alokasi_pusat al ON al.id = dt.id_alokasi_pusat
				INNER JOIN tb_kontrak_pusat hd ON hd.id = al.id_kontrak_pusat
				WHERE hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
			$qry .= (isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				";
			$res = $this->db->query($qry);
			$total = $res->row()->total;

			$qry =	"	
				SELECT SUM(dt.nilai_barang) AS total
				FROM tb_bastb_persediaan dt 
				INNER JOIN tb_alokasi_persediaan_pusat alp ON alp.id = dt.id_alokasi_persediaan_pusat
				INNER JOIN tb_alokasi_pusat al ON al.id = alp.id_alokasi
				INNER JOIN tb_kontrak_pusat hd ON hd.id = al.id_kontrak_pusat
				WHERE hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
			$qry .= (isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				";
			$res = $this->db->query($qry);
			$total += $res->row()->total;

			if($res->num_rows() > 0)
				return $total;
			else
				return 0;
		}

		function total_all_unit($id = null, $id_kontrak_pusat = null, $addwhere = null)
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
				GROUP BY dt.status_alokasi
				UNION
				SELECT dt.status_alokasi,SUM(dt.jumlah_barang) total_temp
				FROM tb_alokasi_persediaan_pusat dt 
				INNER JOIN tb_alokasi_pusat alo ON dt.id_alokasi=alo.id
				LEFT JOIN tb_kontrak_pusat hd ON hd.id = alo.id_kontrak_pusat
				WHERE hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
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

		function total_all_nilai($id = null, $id_kontrak_pusat = null)
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
				GROUP BY dt.status_alokasi
				UNION
				SELECT dt.status_alokasi, SUM(dt.nilai_barang) AS total_temp
				FROM tb_alokasi_persediaan_pusat dt 
				INNER JOIN tb_alokasi_pusat alo ON dt.id_alokasi=alo.id
				LEFT JOIN tb_kontrak_pusat hd ON hd.id = alo.id_kontrak_pusat
				WHERE hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
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

		function getFile($param = null)
		{
			$qry =	"	
			SELECT 				
			tb_bastb_pusat.nama_file, tb_bastb_pusat.nama_filefoto
			FROM tb_bastb_pusat 
			INNER JOIN tb_alokasi_pusat ON tb_alokasi_pusat.id=tb_bastb_pusat.id_alokasi_pusat
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