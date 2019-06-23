<?php
	class LoginModel extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function CheckLogin($username, $password)
		{
			$this->db->where('id_pengguna', $username);
			$this->db->where('password', md5($password));
			$res = $this->db->get('tb_admin');
			// die($this->db->last_query());
			if($res->num_rows() > 0)
				return true;
			else
				return false;
		}

		function GetUserLogin($username, $password)
		{
			$this->db->where('id_pengguna', $username);
			$this->db->where('password', md5($password));
			$res = $this->db->get('tb_admin');
			// die($this->db->last_query());
			if($res->num_rows() > 0)
				return $res->row();
			else
				return array();
		}

		function UpdateLastLogin($data, $username)
		{
			$this->db->where('id_pengguna', $username);
			$this->db->update('tb_admin', $data);
		}



	}
?>