<?php
	class AdminModel extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function GetAll($role)
		{

			$qry =	"	
				SELECT 
					adm.id,
					adm.role_pengguna,
					adm.id_pengguna,
					adm.nama,
					adm.no_telepon,
					adm.last_login,
					adm.created_at,
					adm.is_active,
					prov.nama_provinsi,
					kab.nama_kabupaten,
					penpus.nama_penyedia_pusat,
					penprov.nama_penyedia_provinsi
					
				FROM 
				tb_admin adm 
				LEFT JOIN tb_provinsi prov ON prov.id = adm.id_provinsi
				LEFT JOIN `tb_kabupaten` kab ON kab.`id` = adm.`id_kabupaten`
				LEFT JOIN `tb_penyedia_pusat` penpus ON penpus.`id` = adm.`id_penyedia_pusat`
				LEFT JOIN `tb_penyedia_provinsi` penprov ON penprov.`id` = adm.`id_penyedia_provinsi`
				WHERE adm.`role_pengguna` = '$role'
				".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and (prov.id = ".$this->session->userdata('logged_in')->id_provinsi." or kab.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi." or penprov.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi.")" : "")."
				".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and adm.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "")."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and adm.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and adm.id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "")."
				ORDER BY adm.created_at desc ";
			
			// die($qry);
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function Get($id)
		{
			$res = $this->db->get_where('tb_admin', array('id' => $id));

			if($res->num_rows() > 0)
				return $res->row();
			else
				return array();
		}

		function GetByIdPengguna($idpengguna)
		{
			// $res = $this->db->get_where('tb_admin', array('id_pengguna' => $idpengguna));
			$qry =	"	
				SELECT 
					adm.id,
					adm.role_pengguna,
					adm.id_pengguna,
					adm.nama,
					adm.no_telepon,
					adm.last_login,
					adm.created_at,
					adm.is_active,
					adm.id_penyedia_pusat,
					adm.id_penyedia_provinsi,
					adm.id_provinsi,
					adm.id_kabupaten,
					prov.nama_provinsi,
					kab.nama_kabupaten,
					penpus.nama_penyedia_pusat,
					penprov.nama_penyedia_provinsi,
					adm.file_avatar
				FROM 
				tb_admin adm 
				LEFT JOIN tb_provinsi prov ON prov.id = adm.id_provinsi
				LEFT JOIN `tb_kabupaten` kab ON kab.`id` = adm.`id_kabupaten`
				LEFT JOIN `tb_penyedia_pusat` penpus ON penpus.`id` = adm.`id_penyedia_pusat`
				LEFT JOIN `tb_penyedia_provinsi` penprov ON penprov.`id` = adm.`id_penyedia_provinsi`
				WHERE adm.`id_pengguna` = '$idpengguna'";
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row();
			else
				return $res->row();
		}

		function Insert($data)
		{
			$this->db->insert('tb_admin',$data);
		}

		function Update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_admin', $data);
		}

		function Delete($id)
		{
			$this->db->delete('tb_admin', array('id' => $id));
		}

	}
?>