<?php
	class Baphp_provinsi_model extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function get($id = null, $id_alokasi_provinsi = null,  $start = null, $length = null, $col = null, $dir = null, $filter = null, $search = null)
		{
			if(empty($col)) $col = 'tb_baphp_provinsi.updated_at';
			if(empty($dir)) $dir = 'DESC';
			$qry =	"	
			SELECT 
				tb_baphp_provinsi.id as id, tb_baphp_provinsi.id_alokasi_provinsi,
				tb_baphp_provinsi.tahun_anggaran, tb_baphp_provinsi.titik_serah, tb_baphp_provinsi.nama_wilayah,
				tb_baphp_provinsi.no_baphp, tb_baphp_provinsi.tanggal,
				peny.nama_penyedia_provinsi as pihak_penyerah, nama_penyedia_provinsi,
				tb_baphp_provinsi.nama_penyerah,
				tb_baphp_provinsi.jabatan_penyerah,
				tb_baphp_provinsi.notelp_penyerah,
				tb_baphp_provinsi.alamat_penyerah,
				tb_baphp_provinsi.id_provinsi_penyerah, prov_peny.nama_provinsi as provinsi_penyerah,
				tb_baphp_provinsi.id_kabupaten_penyerah, kab_peny.nama_kabupaten as kabupaten_penyerah,
				tb_baphp_provinsi.pihak_penerima, 
				tb_baphp_provinsi.nama_penerima,
				tb_baphp_provinsi.jabatan_penerima,
				tb_baphp_provinsi.notelp_penerima,
				tb_baphp_provinsi.alamat_penerima,
				tb_baphp_provinsi.id_provinsi_penerima, prov_pene.nama_provinsi as provinsi_penerima,
				tb_baphp_provinsi.id_kabupaten_penerima, kab_pene.nama_kabupaten as kabupaten_penerima,
				tb_baphp_provinsi.no_kontrak, tb_alokasi_provinsi.id_kontrak_provinsi,
				tb_baphp_provinsi.nama_barang, tb_baphp_provinsi.merk, 
				tb_baphp_provinsi.jumlah_barang, tb_baphp_provinsi.nilai_barang,
				IFNULL(tb_baphp_provinsi.nilai_barang/NULLIF(tb_baphp_provinsi.jumlah_barang,0),0) harga_satuan,				
				tb_baphp_provinsi.nama_file, tb_baphp_provinsi.nama_filefoto,
				tb_baphp_provinsi.nama_mengetahui,
				tb_baphp_provinsi.jabatan_mengetahui, no_bart, tanggal_bart, no_sp2d, tanggal_sp2d, no_spm, tanggal_spm
			FROM tb_baphp_provinsi
			INNER JOIN tb_alokasi_provinsi ON tb_alokasi_provinsi.id=tb_baphp_provinsi.id_alokasi_provinsi
			LEFT JOIN tb_penyedia_provinsi peny ON tb_baphp_provinsi.id_penyerah = peny.id
			LEFT JOIN tb_provinsi prov_peny ON tb_baphp_provinsi.id_provinsi_penyerah = prov_peny.id
			LEFT JOIN tb_provinsi prov_pene ON tb_baphp_provinsi.id_provinsi_penerima = prov_pene.id
			LEFT JOIN tb_kabupaten kab_peny ON tb_baphp_provinsi.id_kabupaten_penyerah = kab_peny.id
			LEFT JOIN tb_kabupaten kab_pene ON tb_baphp_provinsi.id_kabupaten_penerima = kab_pene.id
			WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
				if (isset($id_alokasi_provinsi)) $qry .= " and tb_baphp_provinsi.id_alokasi_provinsi = $id_alokasi_provinsi";
				if (isset($id)) $qry .= " and tb_baphp_provinsi.id = $id";
				if (isset($this->session->userdata('logged_in')->id_provinsi)) $qry .= " and prov_pene.id =". $this->session->userdata('logged_in')->id_provinsi;
				$qry .= $filter."
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " 
				and peny.id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "");
				if (isset($id)) $qry .= " and tb_baphp_provinsi.id = $id";
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
			$this->db->insert('tb_baphp_provinsi',$data);
		}

		function update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_baphp_provinsi',$data);
		}

		function destroy($id)
		{
			$this->db->delete('tb_baphp_provinsi', array('id' => $id));
		}

		function total_unit($id_alokasi_provinsi)
		{
			$qry =	"	
				SELECT SUM(dt.jumlah_barang) AS total
				FROM tb_baphp_provinsi dt 
				INNER JOIN tb_alokasi_provinsi alp ON alp.id = dt.id_alokasi_provinsi
				INNER JOIN tb_kontrak_provinsi hd ON hd.id = alp.id_kontrak_provinsi
				WHERE hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan; 
			if (isset($id_alokasi_provinsi)) $qry .= " and dt.id_alokasi_provinsi = $id_alokasi_provinsi";
			$qry .= (isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and hd.id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and alp.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and alp.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
					";
			if (isset($id_alokasi_provinsi)) $qry .= " GROUP BY dt.id_alokasi_provinsi";
		// echo $qry;exit();
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return 0;
		}

		function total_nilai($id_alokasi_provinsi)
		{
			$qry =	"	
				SELECT SUM(dt.nilai_barang) AS total
				FROM tb_baphp_provinsi dt 
				INNER JOIN tb_alokasi_provinsi alp ON alp.id = dt.id_alokasi_provinsi
				INNER JOIN tb_kontrak_provinsi hd ON hd.id = alp.id_kontrak_provinsi
				WHERE hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
			if (isset($id_alokasi_provinsi)) $qry .= " and dt.id_alokasi_provinsi = $id_alokasi_provinsi";
			$qry .= (isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and hd.id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and alp.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and alp.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
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
				tb_baphp_provinsi.nama_file, tb_baphp_provinsi.nama_filefoto
			FROM tb_baphp_provinsi 
			INNER JOIN tb_alokasi_provinsi ON tb_alokasi_provinsi.id=tb_baphp_provinsi.id_alokasi_provinsi
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