<?php
	class PenyediaPusatModel extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function GetAll()
		{
			$res = $this->db->get('tb_penyedia_pusat');
			$qry =	"	select * from 
						tb_penyedia_pusat 
					".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " where id = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "");
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetAllAjaxCount()
		{
			$qry =	"	
				select * from 
	 			tb_penyedia_pusat 
		 		".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " where id = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "");
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetSearchAjaxCount($search)
		{
			$qry =	'
				select * from 
	 			tb_penyedia_pusat 
		 		'.(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " where id = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "").' where
						kode_penyedia_pusat LIKE "%'.$search.'%"
						or nama_penyedia_pusat LIKE "%'.$search.'%"
			';
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetAllForAjax($start, $length, $col, $dir)
		{

			$qry =	'
				select * from 
	 			tb_penyedia_pusat 
		 		'.(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " where id = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "").' 
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

			$qry =	'
				select * from 
	 			tb_penyedia_pusat 
		 		'.(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " where id = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "").' where
						kode_penyedia_pusat LIKE "%'.$search.'%"
						or nama_penyedia_pusat LIKE "%'.$search.'%"
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
			$res = $this->db->get_where('tb_penyedia_pusat', array('id' => $id));

			if($res->num_rows() > 0)
				return $res->row();
			else
				return array();
		}

		function Insert($data)
		{
			$this->db->insert('tb_penyedia_pusat',$data);

			$this->db->set('kode_penyedia_pusat', str_pad($this->db->insert_id(), 3, '0', STR_PAD_LEFT));
			$this->db->where('id', $this->db->insert_id());
			$this->db->update('tb_penyedia_pusat');
		}

		function Update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_penyedia_pusat', $data);
		}

		function Delete($id)
		{
			$this->db->delete('tb_penyedia_pusat', array('id' => $id));
		}

	}
?>