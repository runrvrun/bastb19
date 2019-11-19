<?php
	class Sop_model extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function get($id = null, $start = null, $length = null, $col = null, $dir = null, $filter = null, $search = null)
		{
			if(empty($col)) $col = 'dt.created_at';
			if(empty($dir)) $dir = 'DESC';
			$qry =	"	
				SELECT dt.id, dt.`tanggal`, dt.`judul`, dt.`deskripsi`, dt.`nama_file`					
				FROM 
				tb_sop dt 
				WHERE 1=1
				";
				if (isset($id)) $qry .= " and dt.id = $id ";
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
			$this->db->insert('tb_sop',$data);
		}

		function update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_sop', $data);
		}

		function destroy($id)
		{
			$this->db->delete('tb_sop', array('id' => $id));
		}	
	}
?>