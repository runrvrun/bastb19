<?php
	class Bastb_persediaan_model extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function get($id = null, $id_alokasi_persediaan = null,  $start = null, $length = null, $col = null, $dir = null, $filter = null, $search = null)
		{
			if(empty($col)) $col = 'tb_bastb_persediaan.updated_at';
			if(empty($dir)) $dir = 'DESC';
			$qry =	"	
			SELECT 
				tb_bastb_persediaan.id as id, tb_bastb_persediaan.id_alokasi_persediaan_pusat, 
				tb_alokasi_persediaan_pusat.id_alokasi, tb_alokasi_pusat.id_kontrak_pusat,
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
				tb_bastb_persediaan.jabatan_mengetahui, nama_penyedia_pusat, 'cad' as source
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
				if (isset($id_alokasi_persediaan)) $qry .= " and tb_bastb_persediaan.id_alokasi_persediaan_pusat = $id_alokasi_persediaan";
				if (isset($id)) $qry .= " and tb_bastb_persediaan.id = $id";
				$qry .= $filter."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " 
				and peny.id = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "");
				$qry .= $filter;
				$qry .= "
				ORDER BY ".$col." ".$dir;
			if($length > 0)
				$qry .=	' LIMIT ' . $start . ',' . $length;
					//echo $qry;exit();
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

		function store($data)
		{
			$this->db->insert('tb_bastb_persediaan',$data);
		}

		function update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_bastb_persediaan',$data);
		}

		function destroy($id)
		{
			$this->db->delete('tb_bastb_persediaan_norangka', array('id_bastb_persediaan' => $id));
			$this->db->delete('tb_bastb_persediaan', array('id' => $id));
		}

		function total_unit($id_alokasi_persediaan = null, $id_kontrak_pusat = null)
		{
			$qry =	"	
				SELECT SUM(dt.jumlah_barang) AS total
				FROM tb_bastb_persediaan dt 
				INNER JOIN tb_alokasi_persediaan_pusat alp ON alp.id = dt.id_alokasi_persediaan_pusat
				INNER JOIN tb_alokasi_pusat al ON al.id = alp.id_alokasi
				INNER JOIN tb_kontrak_pusat hd ON hd.id = al.id_kontrak_pusat
				WHERE hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan; 
			if (isset($id_alokasi_persediaan)) $qry .= " and dt.id_alokasi_persediaan_pusat = $id_alokasi_persediaan";
			if (isset($id_kontrak_pusat)) $qry .= " and al.id_kontrak_pusat = $id_kontrak_pusat";
			$qry .= (isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				GROUP BY dt.id_alokasi_persediaan_pusat";
		// echo $qry;exit();
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return 0;
		}

		function total_nilai($id_alokasi_persediaan = null, $id_kontrak_pusat = null)
		{
			$qry =	"	
				SELECT SUM(dt.nilai_barang) AS total
				FROM tb_bastb_persediaan dt 
				INNER JOIN tb_alokasi_persediaan_pusat alp ON alp.id = dt.id_alokasi_persediaan_pusat
				INNER JOIN tb_alokasi_pusat al ON al.id = alp.id_alokasi
				INNER JOIN tb_kontrak_pusat hd ON hd.id = al.id_kontrak_pusat
				WHERE hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
			if (isset($id_alokasi_persediaan)) $qry .= " and dt.id_alokasi_persediaan_pusat = $id_alokasi_persediaan";
			if (isset($id_kontrak_pusat)) $qry .= " and al.id_kontrak_pusat = $id_kontrak_pusat";
			$qry .= (isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				GROUP BY dt.id_alokasi_persediaan_pusat";
	
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
			tb_bastb_persediaan.nama_file, tb_bastb_persediaan.nama_filefoto
			FROM tb_bastb_persediaan 
			INNER JOIN tb_alokasi_persediaan_pusat ON tb_bastb_persediaan.id_alokasi_persediaan_pusat=tb_alokasi_persediaan_pusat.id
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