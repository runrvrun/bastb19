<?php
	class KodimKoremModel extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function GetAll()
		{
			// $res = $this->db->get('tb_kodim_korem');
			$qry = '
					SELECT 
						* FROM 
					tb_kodim_korem
					order by nama_kodim_korem asc
			';
			
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetAllAjaxCount()
		{
			$qry = '
					SELECT 
						* FROM 
					tb_kodim_korem
			';
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetSearchAjaxCount($search)
		{
			$qry = '
					SELECT 
						* FROM 
					tb_kodim_korem
					where
						brigade LIKE "%'.$search.'%"
						or nama_kodim_korem LIKE "%'.$search.'%"
			';
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetAllForAjax($start, $length, $col, $dir)
		{

			$qry = '
					SELECT 
						* FROM 
					tb_kodim_korem
					ORDER BY '.$col.' '.$dir;
			if($length > 0)
				$qry .=	' LIMIT ' . $start . ',' . $length;
			
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetSearchAjax($start, $length, $search, $col, $dir)
		{

			$qry = '
					SELECT 
						* FROM 
					tb_kodim_korem
					where
						brigade LIKE "%'.$search.'%"
						or nama_kodim_korem LIKE "%'.$search.'%"
					ORDER BY '.$col.' '.$dir;
			if($length > 0)
				$qry .=	' LIMIT ' . $start . ',' . $length;
			
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function Get($id)
		{
			$res = $this->db->get_where('tb_kodim_korem', array('id' => $id));

			if($res->num_rows() > 0)
				return $res->row();
			else
				return array();
		}

		function Insert($data)
		{
			$this->db->insert('tb_kodim_korem',$data);
		}

		function Update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_kodim_korem', $data);
		}

		function Delete($id)
		{
			$this->db->delete('tb_kodim_korem', array('id' => $id));
		}

	}
?>