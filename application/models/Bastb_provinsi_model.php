<?php
	class Bastb_provinsi_model extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function get($id = null, $id_alokasi = null,  $start = null, $length = null, $col = null, $dir = null, $filter = null, $search = null)
		{
			if(empty($col)) $col = 'tb_bastb_provinsi.updated_at';
			if(empty($dir)) $dir = 'DESC';
			$qry =	"	
			SELECT 
				tb_bastb_provinsi.id as id, tb_bastb_provinsi.id_alokasi_provinsi, tb_alokasi_provinsi.id_kontrak_provinsi,
				tb_bastb_provinsi.tahun_anggaran, tb_bastb_provinsi.kelompok_penerima, tb_bastb_provinsi.kelompok_penerima,
				tb_bastb_provinsi.no_bastb, tb_bastb_provinsi.tanggal,
				tb_bastb_provinsi.pihak_penyerah,
				tb_bastb_provinsi.nama_penyerah,
				tb_bastb_provinsi.jabatan_penyerah,
				tb_bastb_provinsi.notelp_penyerah,
				tb_bastb_provinsi.alamat_penyerah,
				tb_bastb_provinsi.id_provinsi_penyerah, prov_peny.nama_provinsi as provinsi_penyerah,
				tb_bastb_provinsi.id_kabupaten_penyerah, kab_peny.nama_kabupaten as kabupaten_penyerah,
				tb_bastb_provinsi.pihak_penerima, 
				tb_bastb_provinsi.nama_penerima,
				tb_bastb_provinsi.jabatan_penerima,nik_penerima,
				tb_bastb_provinsi.notelp_penerima,
				tb_bastb_provinsi.alamat_penerima,
				tb_bastb_provinsi.id_provinsi_penerima, prov_pene.nama_provinsi as provinsi_penerima,
				tb_bastb_provinsi.id_kabupaten_penerima, kab_pene.nama_kabupaten as kabupaten_penerima,
				tb_bastb_provinsi.id_kecamatan_penerima, tb_bastb_provinsi.id_kelurahan_penerima,
				nama_kecamatan, nama_kelurahan,
				tb_bastb_provinsi.no_kontrak,
				tb_bastb_provinsi.nama_barang, tb_bastb_provinsi.merk, tb_bastb_provinsi.jumlah_barang, tb_bastb_provinsi.nilai_barang,
				CASE tb_bastb_provinsi.jumlah_barang WHEN 0 THEN 0 ELSE tb_bastb_provinsi.nilai_barang/tb_bastb_provinsi.jumlah_barang END harga_satuan,
				tb_bastb_provinsi.nama_file, tb_bastb_provinsi.nama_filefoto,
				tb_bastb_provinsi.nama_mengetahui,
				tb_bastb_provinsi.jabatan_mengetahui, nama_penyedia_provinsi
			FROM tb_bastb_provinsi 
			INNER JOIN tb_alokasi_provinsi ON tb_alokasi_provinsi.id=tb_bastb_provinsi.id_alokasi_provinsi
			INNER JOIN tb_kontrak_provinsi ON tb_kontrak_provinsi.id=tb_alokasi_provinsi.id_kontrak_provinsi
			INNER JOIN tb_penyedia_provinsi ON tb_penyedia_provinsi.id=tb_kontrak_provinsi.id_penyedia_provinsi
			LEFT JOIN tb_provinsi prov_peny ON tb_bastb_provinsi.id_provinsi_penyerah = prov_peny.id
			LEFT JOIN tb_provinsi prov_pene ON tb_bastb_provinsi.id_provinsi_penerima = prov_pene.id
			LEFT JOIN tb_kabupaten kab_peny ON tb_bastb_provinsi.id_kabupaten_penyerah = kab_peny.id
			LEFT JOIN tb_kabupaten kab_pene ON tb_bastb_provinsi.id_kabupaten_penerima = kab_pene.id
			LEFT JOIN tb_kecamatan ON tb_bastb_provinsi.id_kecamatan_penerima = tb_kecamatan.id
			LEFT JOIN tb_kelurahan ON tb_bastb_provinsi.id_kelurahan_penerima = tb_kelurahan.id
			WHERE tb_kontrak_provinsi.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
				if (isset($id_alokasi)) $qry .= " and tb_bastb_provinsi.id_alokasi_provinsi = $id_alokasi";
				if (isset($id)) $qry .= " and tb_bastb_provinsi.id = $id";
				$qry .= $filter;
				//cek privilege penyedia/provinsi/kabupaten
				if(!isset($id)){
					if(isset($this->session->userdata('logged_in')->id_penyedia_provinsi)) $qry .= " and tb_penyedia_provinsi.id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi;
					if(isset($this->session->userdata('logged_in')->id_provinsi)) $qry .= " and prov_peny.id = ".$this->session->userdata('logged_in')->id_provinsi;
					if(isset($this->session->userdata('logged_in')->id_kabupaten)) $qry .= " and kab_peny.id = ".$this->session->userdata('logged_in')->id_kabupaten;
				}
				if (isset($id)) $qry .= " and tb_bastb_provinsi.id = $id";
				$qry .= $filter;
				$qry .= "
				ORDER BY ".$col." ".$dir;
			if($length > 0)
				$qry .=	' LIMIT ' . $start . ',' . $length;
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

		function store($data)
		{
			$this->db->insert('tb_bastb_provinsi',$data);
		}

		function update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_bastb_provinsi',$data);
		}

		function destroy($id)
		{
			$this->db->delete('tb_bastb_provinsi', array('id' => $id));
		}

		function total_unit($id_alokasi = null, $id_kontrak_provinsi = null)
		{
			$qry =	"	
				SELECT SUM(dt.jumlah_barang) AS total
				FROM tb_bastb_provinsi dt 
				INNER JOIN tb_alokasi_provinsi al ON al.id = dt.id_alokasi_provinsi
				INNER JOIN tb_kontrak_provinsi hd ON hd.id = al.id_kontrak_provinsi
				WHERE hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan; 
			if (isset($id_alokasi)) $qry .= " and dt.id_alokasi_provinsi = $id_alokasi";
			if (isset($id_kontrak_provinsi)) $qry .= " and al.id_kontrak_provinsi = $id_kontrak_provinsi";
			$qry .= (isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and hd.id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				";
			if (isset($id_alokasi_provinsi)) $qry .= " GROUP BY dt.id_alokasi_provinsi";
			// echo $qry;exit();
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return 0;
		}

		function total_nilai($id_alokasi = null, $id_kontrak_provinsi = null)
		{
			$qry =	"	
				SELECT SUM(dt.nilai_barang) AS total
				FROM tb_bastb_provinsi dt 
				INNER JOIN tb_alokasi_provinsi al ON al.id = dt.id_alokasi_provinsi
				INNER JOIN tb_kontrak_provinsi hd ON hd.id = al.id_kontrak_provinsi
				WHERE hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
			if (isset($id_alokasi)) $qry .= " and dt.id_alokasi_provinsi = $id_alokasi";
			if (isset($id_kontrak_provinsi)) $qry .= " and al.id_kontrak_provinsi = $id_kontrak_provinsi";
			$qry .= (isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and hd.id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and dt.id_provinsi_penyerah = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and dt.id_kabupaten_penyerah = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				";
			if (isset($id_alokasi_provinsi)) $qry .= " GROUP BY dt.id_alokasi_provinsi";
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
			tb_bastb_provinsi.nama_file, tb_bastb_provinsi.nama_filefoto
			FROM tb_bastb_provinsi 
			INNER JOIN tb_alokasi_provinsi ON tb_alokasi_provinsi.id=tb_bastb_provinsi.id_alokasi_provinsi
			INNER JOIN tb_kontrak_provinsi ON tb_alokasi_provinsi.id_kontrak_provinsi=tb_kontrak_provinsi.id
			WHERE 1=1 ";
			if (isset($param['id_kontrak_provinsi'])) $qry .= " and tb_kontrak_provinsi.id =". $param['id_kontrak_provinsi'];
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