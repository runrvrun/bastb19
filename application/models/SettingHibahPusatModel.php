<?php
	class SettingHibahPusatModel extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function GetSettingUser($userlogin)
		{
			$qry = "
				SELECT * FROM tb_setting_hibah_pusat WHERE user_login = '$userlogin' 
					";
			
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row();
			else
				return array();
		}

		function InsertDefaultData()
		{
			$qry = "
				INSERT INTO tb_setting_hibah_pusat(user_login, nama_penyerah, nip_penyerah, pangkat_penyerah, jabatan_penyerah, alamat_dinas_penyerah, created_by, created_at)
				VALUES(
					'".$this->session->userdata('logged_in')->id_pengguna."',
					'Dr. Sarwo Edhy, S.P, M.M.',
					'196203221983031001',
					'Pembina Utama Muda (Gol. IV/c)',
					'Direktur Jenderal Prasarana dan Sarana Pertanian',
					'Jalan Harsono RM Nomor 3 Gedung D Lantai 8 Ragunan, Jakarta Selatan',
					'".$this->session->userdata('logged_in')->id_pengguna."',
					'".NOW."'

				)
					";
			
			$this->db->query($qry);

		}

		function SaveData($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_setting_hibah_pusat', $data);

		}

	}
?>