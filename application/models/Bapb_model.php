<?php
	class Bapb_model extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function get($id = null, $id_kontrak_pusat = null,  $start = null, $length = null, $col = null, $dir = null, $filter = null, $search = null)
		{
			if(empty($col)) $col = 'tb_bapb.created_at';
			if(empty($dir)) $dir = 'DESC';
			$qry =	"	
			SELECT 
				tb_bapb.id as id, id_kontrak_pusat,
				tb_kontrak_pusat.nama_barang, tb_kontrak_pusat.merk, tb_kontrak_pusat.jumlah_barang, tb_kontrak_pusat.nilai_barang,
				nomor, tanggal, jumlah_unit, lokasi_pemeriksaan, tb_bapb.nama_file, nama_penyedia_pusat
			FROM tb_bapb
			INNER JOIN tb_kontrak_pusat ON tb_bapb.id_kontrak_pusat = tb_kontrak_pusat.id
			INNER JOIN tb_penyedia_pusat ON tb_penyedia_pusat.id=tb_kontrak_pusat.id_penyedia_pusat
			WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
				if (isset($id_kontrak_pusat)) $qry .= " and tb_bapb.id_kontrak_pusat = $id_kontrak_pusat";
				if (isset($id)) $qry .= " and tb_bapb.id = $id";
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
			$this->db->insert('tb_bapb',$data);
		}

		function update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_bapb bap',$data);
		}

		function destroy($id)
		{
			$this->db->delete('tb_bapb', array('id' => $id));
		}

		function total_unit($id_kontrak_pusat)
		{
			$qry =	"	
				SELECT SUM(tb_bapb.jumlah_unit) AS total
				FROM tb_bapb
				INNER JOIN tb_kontrak_pusat ON tb_bapb.id_kontrak_pusat = tb_kontrak_pusat.id
				WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan; 
			if (isset($id_kontrak_pusat)) $qry .= " and id_kontrak_pusat = $id_kontrak_pusat";
		// echo $qry;exit();
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return 0;
		}

		function min_tanggal($id_kontrak_pusat)
		{
			$qry =	"	
				SELECT IFNULL(MIN(tb_bapb.tanggal),now()) AS tanggal
				FROM tb_bapb
				WHERE 1=1"; 
			if (isset($id_kontrak_pusat)) $qry .= " and id_kontrak_pusat = $id_kontrak_pusat";

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row();
			else
				return 0;
		}
	}
?>