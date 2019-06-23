<?php
	class AksesModel extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function GetAll()
		{
			// $res = $this->db->get('tb_provinsi');

			$qry =	"	
					SELECT 
						men.id,
						men.menu_name,
						men.menu_parent,
						men.menu_level,
						am.role_pengguna,
						am.can_access
				 	FROM tb_menu men
					LEFT JOIN tb_akses_menu am
					ON men.id = am.id_menu 
					";
		
			$res = $this->db->query($qry);


			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetAllMenu()
		{
			$res = $this->db->get('tb_akses_menu');

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetMenuForRole($role)
		{
			if($role == 'ADMIN PUSAT'){
				$qry =	"	
					SELECT * 
					from tb_akses_menu
					";
			}
			else if($role == 'ADMIN LO'){
				$qry =	"	
					SELECT * 
					from tb_akses_menu
					WHERE admin_lo_acc = 1
					";
			}
			else if($role == 'ADMIN PROVINSI'){
				$qry =	"	
					SELECT * 
					from tb_akses_menu
					WHERE admin_provinsi_acc = 1
					";
			}
			else if($role == 'ADMIN KABUPATEN'){
				$qry =	"	
					SELECT * 
					from tb_akses_menu
					WHERE admin_kabupaten_acc = 1
					";
			}
			else if($role == 'ADMIN PENYEDIA PUSAT'){
				$qry =	"	
					SELECT * 
					from tb_akses_menu
					WHERE admin_penyedia_pusat_acc = 1
					";
			}
			else if($role == 'ADMIN PENYEDIA PROVINSI'){
				$qry =	"	
					SELECT * 
					from tb_akses_menu
					WHERE admin_penyedia_provinsi_acc = 1
					";
			}
			else if($role == 'ADMIN HIBAH'){
				$qry =	"	
					SELECT * 
					from tb_akses_menu
					WHERE admin_hibah_acc = 1
					";
			}
			else if($role == 'ADMIN KHUSUS'){
				$qry =	"	
					SELECT * 
					from tb_akses_menu
					WHERE admin_khusus_acc = 1
					";
			}
			
			// die($qry);
		
			$res = $this->db->query($qry);


			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetCrudForRole($role)
		{
			if($role == 'ADMIN PUSAT'){
				$qry =	"	
					SELECT * 
					from tb_akses_crud
					";
			}
			else if($role == 'ADMIN LO'){
				$qry =	"	
					SELECT * 
					from tb_akses_crud
					WHERE admin_lo_acc = 1
					";
			}
			else if($role == 'ADMIN PROVINSI'){
				$qry =	"	
					SELECT * 
					from tb_akses_crud
					WHERE admin_provinsi_acc = 1
					";
			}
			else if($role == 'ADMIN KABUPATEN'){
				$qry =	"	
					SELECT * 
					from tb_akses_crud
					WHERE admin_kabupaten_acc = 1
					";
			}
			else if($role == 'ADMIN PENYEDIA PUSAT'){
				$qry =	"	
					SELECT * 
					from tb_akses_crud
					WHERE admin_penyedia_pusat_acc = 1
					";
			}
			else if($role == 'ADMIN PENYEDIA PROVINSI'){
				$qry =	"	
					SELECT * 
					from tb_akses_crud
					WHERE admin_penyedia_provinsi_acc = 1
					";
			}
			else if($role == 'ADMIN HIBAH'){
				$qry =	"	
					SELECT * 
					from tb_akses_crud
					WHERE admin_hibah_acc = 1
					";
			}
			else if($role == 'ADMIN KHUSUS'){
				$qry =	"	
					SELECT * 
					from tb_akses_crud
					WHERE admin_khusus_acc = 1
					";
			}
			
		
			$res = $this->db->query($qry);


			if($res->num_rows() > 0)
				return $res->result();
			else
				return 0;
		}

		function GetAllCrud()
		{
			$res = $this->db->get('tb_akses_crud');

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetByMenuRole($menu_id, $role)
		{
			// $res = $this->db->get('tb_provinsi');

			$qry =	"	
					SELECT can_access 
					from tb_akses_menu
					WHERE id_menu = $menu_id and role_pengguna = '$role'
					";
		
			$res = $this->db->query($qry);


			if($res->num_rows() > 0)
				return $res->row()->can_access;
			else
				return 0;
		}

		function Update($data, $menuid)
		{
			// $res = $this->db->get_where('tb_akses_menu', array('id_menu' => $data["id_menu"], 'role_pengguna' => $data["role_pengguna"]));

			// if($res->num_rows() > 0){
			// 	$this->db->where('id', $res->row()->id);
			// 	$this->db->update('tb_akses_menu', $data);
			// }
			// else{
			// 	$this->db->insert('tb_akses_menu', $data);
			// }

			$this->db->where('id', $menuid);
			$this->db->update('tb_akses_menu', $data);
		}

		function UpdateCrud($data, $crudid)
		{
			// $res = $this->db->get_where('tb_akses_menu', array('id_menu' => $data["id_menu"], 'role_pengguna' => $data["role_pengguna"]));

			// if($res->num_rows() > 0){
			// 	$this->db->where('id', $res->row()->id);
			// 	$this->db->update('tb_akses_menu', $data);
			// }
			// else{
			// 	$this->db->insert('tb_akses_menu', $data);
			// }

			$this->db->where('id', $crudid);
			$this->db->update('tb_akses_crud', $data);
		}

		function Delete($id)
		{
			$this->db->delete('tb_provinsi', array('id' => $id));
		}

	}
?>