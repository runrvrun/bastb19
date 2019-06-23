<?php
	class PenyediaProvinsiModel extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function GetAll()
		{
			$qry =	"	
				select tb_penyedia_provinsi.id, tb_penyedia_provinsi.kode_penyedia_provinsi, tb_penyedia_provinsi.nama_penyedia_provinsi, tb_provinsi.nama_provinsi from 
	 			tb_penyedia_provinsi
	 			INNER JOIN tb_provinsi ON tb_provinsi.id = tb_penyedia_provinsi.id_provinsi
	 			where 1 = 1
		 		".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and tb_penyedia_provinsi.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "").(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and tb_penyedia_provinsi.id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "");
			
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
			// return array();
		}

		function GetAllAjaxCount()
		{
			$qry =	"	
				select tb_penyedia_provinsi.id, tb_penyedia_provinsi.kode_penyedia_provinsi, tb_penyedia_provinsi.nama_penyedia_provinsi, tb_provinsi.nama_provinsi from 
	 			tb_penyedia_provinsi
	 			INNER JOIN tb_provinsi ON tb_provinsi.id = tb_penyedia_provinsi.id_provinsi
	 			where 1 = 1
		 		".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and tb_penyedia_provinsi.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "").(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and tb_penyedia_provinsi.id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "");
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetSearchAjaxCount($search)
		{
			$qry =	'
				select tb_penyedia_provinsi.id, tb_penyedia_provinsi.kode_penyedia_provinsi, tb_penyedia_provinsi.nama_penyedia_provinsi, tb_provinsi.nama_provinsi from 
	 			tb_penyedia_provinsi
	 			INNER JOIN tb_provinsi ON tb_provinsi.id = tb_penyedia_provinsi.id_provinsi
	 			where 1 = 1 
		 		'.(isset($this->session->userdata('logged_in')->id_provinsi) ? " and tb_penyedia_provinsi.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "").(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and tb_penyedia_provinsi.id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "").' and (
						kode_penyedia_provinsi LIKE "%'.$search.'%"
						or nama_provinsi LIKE "%'.$search.'%"
						or nama_penyedia_provinsi LIKE "%'.$search.'%" )';
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetAllForAjax($start, $length, $col, $dir)
		{

			$qry =	"	
				select tb_penyedia_provinsi.id, tb_penyedia_provinsi.kode_penyedia_provinsi, tb_penyedia_provinsi.nama_penyedia_provinsi, tb_provinsi.nama_provinsi from 
	 			tb_penyedia_provinsi
	 			INNER JOIN tb_provinsi ON tb_provinsi.id = tb_penyedia_provinsi.id_provinsi
	 			where 1 = 1
		 		".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and tb_penyedia_provinsi.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "").(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and tb_penyedia_provinsi.id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "");

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

			$qry =	'
				select tb_penyedia_provinsi.id, tb_penyedia_provinsi.kode_penyedia_provinsi, tb_penyedia_provinsi.nama_penyedia_provinsi, tb_provinsi.nama_provinsi from 
	 			tb_penyedia_provinsi
	 			INNER JOIN tb_provinsi ON tb_provinsi.id = tb_penyedia_provinsi.id_provinsi 
	 			where 1 = 1
		 		'.(isset($this->session->userdata('logged_in')->id_provinsi) ? " and tb_penyedia_provinsi.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "").(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and tb_penyedia_provinsi.id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "").' and (
						kode_penyedia_provinsi LIKE "%'.$search.'%"
						or nama_provinsi LIKE "%'.$search.'%"
						or nama_penyedia_provinsi LIKE "%'.$search.'%" )';

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
			$res = $this->db->get_where('tb_penyedia_provinsi', array('id' => $id));

			if($res->num_rows() > 0)
				return $res->row();
			else
				return array();
		}

		function Insert($data)
		{
			$this->db->insert('tb_penyedia_provinsi',$data);

			$this->db->set('kode_penyedia_provinsi', str_pad($this->db->insert_id(), 3, '0', STR_PAD_LEFT));
			$this->db->where('id', $this->db->insert_id());
			$this->db->update('tb_penyedia_provinsi');
		}

		function Update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_penyedia_provinsi', $data);
		}

		function Delete($id)
		{
			$this->db->delete('tb_penyedia_provinsi', array('id' => $id));
		}

		function GetByProvinsi($id_provinsi)
		{
			$qry =	"	select distinct id, nama_penyedia_provinsi from tb_penyedia_provinsi
					where id_provinsi = '$id_provinsi'";

			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					and id_provinsi = ( SELECT id_provinsi from tb_kabupaten where id = ".$this->session->userdata('logged_in')->id_kabupaten." )";
			}

			$qry .= " ORDER by nama_penyedia_provinsi";

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetByListProvinsi($list_id_provinsi)
		{
			$qry =	"	select distinct id, nama_penyedia_provinsi from tb_penyedia_provinsi
					where 1 = 1";

			for($i=0; $i<count($list_id_provinsi); $i++){
				if($i == 0)
					$qry .= " and ( id_provinsi = ".$list_id_provinsi[$i];
				else
					$qry .= " or id_provinsi = ".$list_id_provinsi[$i];
			}

			$qry .= ")";
			
			if(isset($this->session->userdata('logged_in')->id_penyedia_provinsi)){
				$qry .= "
					and id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi;
			}
			
			$qry .= " order by nama_penyedia_provinsi";

			// die($qry);

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

	}
?>