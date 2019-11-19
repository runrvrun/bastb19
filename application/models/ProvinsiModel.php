<?php
	class ProvinsiModel extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function GetAll($nofilter=0)
		{
			// $res = $this->db->get('tb_provinsi');
			$qry = '
					SELECT 
						* FROM 
					tb_provinsi
					WHERE 1=1
			';
			if(!$nofilter){
				if(isset($this->session->userdata('logged_in')->id_provinsi)){
					$qry .= "
					and id = ".$this->session->userdata('logged_in')->id_provinsi;
				}
				// if($this->session->userdata('logged_in')->role_pengguna == 'ADMIN PROVINSI'){
				// 	$qry .= "
				// 		and id = ".$this->session->userdata('logged_in')->id_provinsi;
				// }
				if($this->session->userdata('logged_in')->role_pengguna == 'ADMIN KABUPATEN'){
					$qry .= "
					and id = ( SELECT id_provinsi from tb_kabupaten where id = ".$this->session->userdata('logged_in')->id_kabupaten." )";
				}
				if($this->session->userdata('logged_in')->role_pengguna == 'ADMIN PENYEDIA PROVINSI'){
					$qry .= "
					and id = ( select id_provinsi from tb_penyedia_provinsi where id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi.")";
				}
			}
					
			$qry .= " order by nama_provinsi";

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
					tb_provinsi
			';
			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					where id = ".$this->session->userdata('logged_in')->id_provinsi;
			}
			if(isset($this->session->userdata('logged_in')->id_penyedia_provinsi)){
				$qry .= "
					where id = ( select id_provinsi from tb_penyedia_provinsi where id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi.")";
			}
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetSearchAjaxCount($search)
		{
			$qry = '
					SELECT 
						* FROM 
					tb_provinsi
					where
						kode_provinsi LIKE "%'.$search.'%"
						or nama_provinsi LIKE "%'.$search.'%"
			';
			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and id = ".$this->session->userdata('logged_in')->id_provinsi;
			}
			if(isset($this->session->userdata('logged_in')->id_penyedia_provinsi)){
				$qry .= "
					and id = ( select id_provinsi from tb_penyedia_provinsi where id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi.")";
			}
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetAllForAjax($start, $length, $col, $dir)
		{

			$qry = '
					SELECT 
						* FROM 
					tb_provinsi';
			
			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					where id = ".$this->session->userdata('logged_in')->id_provinsi;
			}
			if(isset($this->session->userdata('logged_in')->id_penyedia_provinsi)){
				$qry .= "
					where id = ( select id_provinsi from tb_penyedia_provinsi where id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi.")";
			}

			$qry .= ' ORDER BY '.$col.' '.$dir;
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
					tb_provinsi
					where
						kode_provinsi LIKE "%'.$search.'%"
						or nama_provinsi LIKE "%'.$search.'%"';

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and id = ".$this->session->userdata('logged_in')->id_provinsi;
			}
			if(isset($this->session->userdata('logged_in')->id_penyedia_provinsi)){
				$qry .= "
					and id = ( select id_provinsi from tb_penyedia_provinsi where id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi.")";
			}

			$qry .= ' ORDER BY '.$col.' '.$dir;
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
			$res = $this->db->get_where('tb_provinsi', array('id' => $id));

			if($res->num_rows() > 0)
				return $res->row();
			else
				return array();
		}

		function Insert($data)
		{
			$this->db->insert('tb_provinsi',$data);
		}

		function Update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_provinsi', $data);
		}

		function Delete($id)
		{
			$this->db->delete('tb_provinsi', array('id' => $id));
		}

	}
?>