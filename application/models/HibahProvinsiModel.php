<?php
	class HibahProvinsiModel extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function GetAllAjaxCount()
		{
			$qry =	"	
				SELECT 
					bap.id as id,
					bap.tahun_anggaran,
					bap.no_naskah_hibah, bap.tanggal_naskah_hibah,
					bap.no_bast_bmn, bap.tanggal_bast_bmn,
					bap.no_surat_pernyataan, bap.tanggal_surat_pernyataan,
					prov.nama_provinsi, kab.nama_kabupaten,
					bap.nama_penyerah,
					bap.nip_penyerah, 
					bap.pangkat_penyerah, 
					bap.jabatan_penyerah,
					bap.alamat_dinas_penyerah,
					bap.titik_serah, 
					bap.nama_wilayah,
					bap.instansi_penerima,
					bap.nama_penerima,
					bap.nip_penerima,
					bap.pangkat_penerima,
					bap.jabatan_penerima,
					bap.alamat_dinas_penerima,
					bap.nama_file
				from tb_hibah_provinsi bap
				LEFT JOIN tb_provinsi prov ON bap.id_provinsi = prov.id
				LEFT JOIN tb_kabupaten kab ON bap.id_kabupaten = kab.id
				WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and bap.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and bap.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and merk in ( SELECT merk from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat." )" : "")." 
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and merk in ( SELECT merk from tb_jenis_barang_provinsi where id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi. " )" : "")."
					";
				
			// die($qry);
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetSearchAjaxCount($search, $filter = '')
		{
			$qry =	"
				SELECT 
					bap.id as id,
					bap.tahun_anggaran,
					bap.no_naskah_hibah, bap.tanggal_naskah_hibah,
					bap.no_bast_bmn, bap.tanggal_bast_bmn,
					bap.no_surat_pernyataan, bap.tanggal_surat_pernyataan,
					prov.nama_provinsi, kab.nama_kabupaten,
					bap.nama_penyerah,
					bap.nip_penyerah, 
					bap.pangkat_penyerah, 
					bap.jabatan_penyerah,
					bap.alamat_dinas_penyerah,
					bap.titik_serah, 
					bap.nama_wilayah,
					bap.instansi_penerima,
					bap.nama_penerima,
					bap.nip_penerima,
					bap.pangkat_penerima,
					bap.jabatan_penerima,
					bap.alamat_dinas_penerima,
					bap.nama_file
				from tb_hibah_provinsi bap
				LEFT JOIN tb_provinsi prov ON bap.id_provinsi = prov.id
				LEFT JOIN tb_kabupaten kab ON bap.id_kabupaten = kab.id
				WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and bap.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and bap.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and merk in ( SELECT merk from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat." )" : "")." 
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and merk in ( SELECT merk from tb_jenis_barang_provinsi where id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi. " )" : "")."
				AND (
					bap.no_naskah_hibah LIKE '%".$search."%'
				)
				".$filter."
			";
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetFilterAjaxCount($filter = '')
		{
			$qry =	"	
				SELECT 
					bap.id as id,
					bap.tahun_anggaran,
					bap.no_naskah_hibah, bap.tanggal_naskah_hibah,
					bap.no_bast_bmn, bap.tanggal_bast_bmn,
					bap.no_surat_pernyataan, bap.tanggal_surat_pernyataan,
					prov.nama_provinsi, kab.nama_kabupaten,
					bap.nama_penyerah,
					bap.nip_penyerah, 
					bap.pangkat_penyerah, 
					bap.jabatan_penyerah,
					bap.alamat_dinas_penyerah,
					bap.titik_serah, 
					bap.nama_wilayah,
					bap.instansi_penerima,
					bap.nama_penerima,
					bap.nip_penerima,
					bap.pangkat_penerima,
					bap.jabatan_penerima,
					bap.alamat_dinas_penerima,
					bap.nama_file
				from tb_hibah_provinsi bap
				LEFT JOIN tb_provinsi prov ON bap.id_provinsi = prov.id
				LEFT JOIN tb_kabupaten kab ON bap.id_kabupaten = kab.id
				WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".$filter."
				".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and bap.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and bap.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and merk in ( SELECT merk from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat." )" : "")." 
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and merk in ( SELECT merk from tb_jenis_barang_provinsi where id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi. " )" : "")."
					";
				
			// die($qry);
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetAllForAjax($start, $length, $col, $dir, $filter = '')
		{

			$qry = "
				SELECT 
					bap.id as id,
					bap.tahun_anggaran,
					bap.no_naskah_hibah, bap.tanggal_naskah_hibah,
					bap.no_bast_bmn, bap.tanggal_bast_bmn,
					bap.no_surat_pernyataan, bap.tanggal_surat_pernyataan,
					bap.total_unit, bap.total_nilai,
					prov.nama_provinsi, kab.nama_kabupaten,
					bap.nama_penyerah,
					bap.nip_penyerah, 
					bap.pangkat_penyerah, 
					bap.jabatan_penyerah,
					bap.alamat_dinas_penyerah,
					bap.titik_serah, 
					bap.nama_wilayah,
					bap.instansi_penerima,
					bap.nama_penerima,
					bap.nip_penerima,
					bap.pangkat_penerima,
					bap.jabatan_penerima,
					bap.alamat_dinas_penerima,
					bap.nama_file
				from tb_hibah_provinsi bap
				LEFT JOIN tb_provinsi prov ON bap.id_provinsi = prov.id
				LEFT JOIN tb_kabupaten kab ON bap.id_kabupaten = kab.id
				WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and bap.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and bap.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and merk in ( SELECT merk from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat." )" : "")." 
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and merk in ( SELECT merk from tb_jenis_barang_provinsi where id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi. " )" : "")."
				".$filter."
				ORDER BY ".$col." ".$dir."
				LIMIT ".$start.",".$length;
			
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetSearchAjax($start, $length, $search, $col, $dir, $filter = '')
		{

			$qry = "
				SELECT 
					bap.id as id,
					bap.tahun_anggaran,
					bap.no_naskah_hibah, bap.tanggal_naskah_hibah,
					bap.no_bast_bmn, bap.tanggal_bast_bmn,
					bap.no_surat_pernyataan, bap.tanggal_surat_pernyataan,
					bap.total_unit, bap.total_nilai,
					prov.nama_provinsi, kab.nama_kabupaten,
					bap.nama_penyerah,
					bap.nip_penyerah, 
					bap.pangkat_penyerah, 
					bap.jabatan_penyerah,
					bap.alamat_dinas_penyerah,
					bap.titik_serah, 
					bap.nama_wilayah,
					bap.instansi_penerima,
					bap.nama_penerima,
					bap.nip_penerima,
					bap.pangkat_penerima,
					bap.jabatan_penerima,
					bap.alamat_dinas_penerima,
					bap.nama_file
				from tb_hibah_provinsi bap
				LEFT JOIN tb_provinsi prov ON bap.id_provinsi = prov.id
				LEFT JOIN tb_kabupaten kab ON bap.id_kabupaten = kab.id
				WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and bap.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and bap.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and merk in ( SELECT merk from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat." )" : "")." 
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and merk in ( SELECT merk from tb_jenis_barang_provinsi where id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi. " )" : "")."
				AND (
					bap.no_naskah_hibah LIKE '%".$search."%' 
					
				)
				".$filter."
				ORDER BY ".$col." ".$dir."
				LIMIT ".$start.", ".$length;
			
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function Get($id)
		{

			$qry =	"	
				SELECT 
					bap.id as id,
					bap.tahun_anggaran,
					bap.no_naskah_hibah, bap.tanggal_naskah_hibah,
					bap.no_bast_bmn, bap.tanggal_bast_bmn,
					bap.no_surat_pernyataan, bap.tanggal_surat_pernyataan,
					bap.total_unit, bap.total_nilai,
					prov.nama_provinsi, kab.nama_kabupaten,
					bap.nama_penyerah,
					bap.nip_penyerah, 
					bap.pangkat_penyerah, 
					bap.jabatan_penyerah,
					bap.alamat_dinas_penyerah,
					bap.titik_serah, 
					bap.nama_wilayah,
					bap.instansi_penerima,
					bap.nama_penerima,
					bap.nip_penerima,
					bap.pangkat_penerima,
					bap.jabatan_penerima,
					bap.alamat_dinas_penerima,
					bap.nama_file
				from tb_hibah_provinsi bap
				LEFT JOIN tb_provinsi prov ON bap.id_provinsi = prov.id
				LEFT JOIN tb_kabupaten kab ON bap.id_kabupaten = kab.id
				WHERE bap.id = '$id'";
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row();
			else
				return array();
		}

		function GetLast()
		{
			$qry =	"	
					SELECT 
						*
					FROM tb_hibah_provinsi bap
					ORDER BY id DESC
					LIMIT 0, 1 ";
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row();
			else
				return array();
		}

		function Insert($data)
		{
			$this->db->insert('tb_hibah_provinsi',$data);
		}

		function Update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_hibah_provinsi', $data);
		}

		function Delete($id)
		{
			$this->db->delete('tb_hibah_provinsi', array('id' => $id));
			$qry =	"	
					UPDATE tb_alokasi_provinsi set id_hibah_provinsi = null
					where id_hibah_provinsi = $id
					 ";
			$this->db->query($qry);
		}


		function GetTotalUnit()
		{
			$qry =	"	
				SELECT SUM(total_unit) AS total 
				FROM tb_hibah_provinsi 
				where `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and merk in ( SELECT merk from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat." )" : "")." 
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and merk in ( SELECT merk from tb_jenis_barang_provinsi where id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi. " )" : "")."
				";				

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return array();
		}

		function GetTotalNilai()
		{
			$qry =	"	
				SELECT SUM(total_nilai) AS total 
				FROM tb_hibah_provinsi 
				where `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and merk in ( SELECT merk from tb_jenis_barang_pusat where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat." )" : "")." 
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and merk in ( SELECT merk from tb_jenis_barang_provinsi where id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi. " )" : "")."
				";

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
				FROM tb_kontrak_provinsi 
				where `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "");
			
			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and id in(
						select id_kontrak_provinsi from tb_alokasi_provinsi
						where id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi."
					)
				";
			}

			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					and id in(
						select id_kontrak_provinsi from tb_alokasi_provinsi
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
				FROM tb_kontrak_provinsi 
				where `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "");

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and id in(
						select id_kontrak_provinsi from tb_alokasi_provinsi
						where id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi."
					)
				";
			}

			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					and id in(
						select id_kontrak_provinsi from tb_alokasi_provinsi
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

		function CheckNoNaskahHibah($no_naskah_hibah)
		{
			$this->db->where('no_naskah_hibah', $no_naskah_hibah);
			$res = $this->db->get('tb_hibah_provinsi');
			// die($this->db->last_query());
			if($res->num_rows() > 0)
				return true;
			else
				return false;
		}

		function CheckNoBASTBMN($no_bast_bmn)
		{
			$this->db->where('no_bast_bmn', $no_bast_bmn);
			$res = $this->db->get('tb_hibah_provinsi');
			// die($this->db->last_query());
			if($res->num_rows() > 0)
				return true;
			else
				return false;
		}

		function CheckNoSuratPernyataan($no_surat_pernyataan)
		{
			$this->db->where('no_surat_pernyataan', $no_surat_pernyataan);
			$res = $this->db->get('tb_hibah_provinsi');
			// die($this->db->last_query());
			if($res->num_rows() > 0)
				return true;
			else
				return false;
		}

	}
?>