<?php
	class TahunPengadaanModel extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function GetAll()
		{
			$res = $this->db->get('tb_tahun_pengadaan');

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function Insert($data)
		{
			$this->db->insert('tb_tahun_pengadaan',$data);
		}

		function Delete($id)
		{
			$this->db->delete('tb_tahun_pengadaan', array('id' => $id));
		}

	}
?>