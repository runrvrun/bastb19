<?php
	class Bapb_provinsi_model extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function get($id = null, $id_kontrak_provinsi = null,  $start = null, $length = null, $col = null, $dir = null, $filter = null, $search = null)
		{
			if(empty($col)) $col = 'tb_bapb_provinsi.created_at';
			if(empty($dir)) $dir = 'DESC';
			$qry =	"	
			SELECT 
				tb_bapb_provinsi.id as id, id_kontrak_provinsi,
				tb_kontrak_provinsi.nama_barang, tb_kontrak_provinsi.merk, tb_kontrak_provinsi.jumlah_barang, tb_kontrak_provinsi.nilai_barang,
				nomor, tanggal, jumlah_unit, lokasi_pemeriksaan, tb_bapb_provinsi.nama_file, nama_penyedia_provinsi
			FROM tb_bapb_provinsi
			INNER JOIN tb_kontrak_provinsi ON tb_bapb_provinsi.id_kontrak_provinsi = tb_kontrak_provinsi.id
			INNER JOIN tb_penyedia_provinsi ON tb_penyedia_provinsi.id=tb_kontrak_provinsi.id_penyedia_provinsi
			WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
				if (isset($id_kontrak_provinsi)) $qry .= " and tb_bapb_provinsi.id_kontrak_provinsi = $id_kontrak_provinsi";
				if (isset($id)) $qry .= " and tb_bapb_provinsi.id = $id";
				$qry .= $filter;
				$qry .= " ORDER BY ".$col." ".$dir;
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
			$this->db->insert('tb_bapb_provinsi',$data);
		}

		function update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_bapb_provinsi',$data);
		}

		function destroy($id)
		{
			$this->db->delete('tb_bapb_provinsi', array('id' => $id));
		}

		function total_unit($id_kontrak_provinsi)
		{
			$qry =	"	
				SELECT SUM(tb_bapb_provinsi.jumlah_unit) AS total
				FROM tb_bapb_provinsi
				INNER JOIN tb_kontrak_provinsi ON tb_bapb_provinsi.id_kontrak_provinsi = tb_kontrak_provinsi.id
				WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan; 
			if (isset($id_kontrak_provinsi)) $qry .= " and id_kontrak_provinsi = $id_kontrak_provinsi";
		// echo $qry;exit();
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return 0;
		}

		function min_tanggal($id_kontrak_provinsi)
		{
			$qry =	"	
				SELECT IFNULL(MIN(tb_bapb_provinsi.tanggal),now()) AS tanggal
				FROM tb_bapb_provinsi
				WHERE 1=1"; 
			if (isset($id_kontrak_provinsi)) $qry .= " and id_kontrak_provinsi = $id_kontrak_provinsi";

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row();
			else
				return 0;
		}
	}
?>