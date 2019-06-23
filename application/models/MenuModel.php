<?php
	class MenuModel extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function GetAll()
		{
			$res = $this->db->get('tb_menu');

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function Get($id)
		{
			$res = $this->db->get_where('tb_provinsi', array('id' => $id));

			if($res->num_rows() > 0)
				return $res->row();
			else
				return array();
		}


	}
?>