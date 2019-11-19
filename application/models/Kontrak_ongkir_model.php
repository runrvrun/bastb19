<?php
	class Kontrak_ongkir_model extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function get($id = null, $start = null, $length = null, $col = null, $dir = null, $filter = null, $search = null)
		{
			if(empty($col)) $col = 'created_at';
			if(empty($dir)) $dir = 'DESC';
			$qry =	"SELECT id, tahun_anggaran, `tanggal`, `nomor`, `ekspedisi`, `ongkir`, nama_file			
				FROM 
				tb_kontrak_ongkir
				WHERE tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;				
				if (isset($id)) $qry .= " and id = $id ";
				$qry .= $filter;
				$qry .= " 
				ORDER BY ".$col." ".$dir;
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

		function store($data)
		{
			$this->db->insert('tb_kontrak_ongkir',$data);
		}

		function update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_kontrak_ongkir', $data);
		}

		function destroy($id)
		{
			$this->db->delete('tb_kontrak_ongkir', array('id' => $id));
		}	

		function total_nilai()
		{
			$qry =	"	
			SELECT sum(`ongkir`) as total
			FROM 
			tb_kontrak_ongkir
			WHERE tahun_anggaran = ".$this->session->userdata('logged_in')->tahun_pengadaan;			
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return 0;
		}
	}
?>