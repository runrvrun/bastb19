<?php
	class KabupatenModel extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function GetAll()
		{
			// $this->db->select('tb_kabupaten.id, tb_kabupaten.kode_kabupaten, tb_kabupaten.nama_kabupaten, tb_provinsi.nama_provinsi');
			// $this->db->from('tb_kabupaten');
			// $this->db->join('tb_provinsi', 'tb_provinsi.id = tb_kabupaten.id_provinsi');
			// $this->db->order_by('tb_kabupaten.kode_kabupaten');
			// $res = $this->db->get();

			$qry = '
					SELECT 
						tb_kabupaten.id, tb_kabupaten.kode_kabupaten, tb_kabupaten.nama_kabupaten, tb_provinsi.nama_provinsi 
					FROM 
					tb_kabupaten INNER JOIN tb_provinsi ON tb_provinsi.id = tb_kabupaten.id_provinsi
			';

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					where id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}

			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					where id = ".$this->session->userdata('logged_in')->id_kabupaten;
			}
			
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetSemua()
		{
			// $res = $this->db->get('tb_provinsi');
			$qry = '
					SELECT 
						* FROM 
					tb_kabupaten
					
			';

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					where id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}

			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					where id = ".$this->session->userdata('logged_in')->id_kabupaten;
			}

			$qry .= " order by nama_kabupaten";
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
						tb_kabupaten.id, tb_kabupaten.kode_kabupaten, tb_kabupaten.nama_kabupaten, tb_provinsi.nama_provinsi 
					FROM 
					tb_kabupaten INNER JOIN tb_provinsi ON tb_provinsi.id = tb_kabupaten.id_provinsi
			';

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					where id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}
			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					where tb_kabupaten.id = ".$this->session->userdata('logged_in')->id_kabupaten;
			}
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetSearchAjaxCount($search, $filter = '')
		{
			$qry = '
					SELECT 
						tb_kabupaten.id, tb_kabupaten.kode_kabupaten, tb_kabupaten.nama_kabupaten, tb_provinsi.nama_provinsi 
					FROM 
					tb_kabupaten INNER JOIN tb_provinsi ON tb_provinsi.id = tb_kabupaten.id_provinsi
					where
						(tb_kabupaten.kode_kabupaten LIKE "%'.$search.'%"
						or tb_kabupaten.nama_kabupaten LIKE "%'.$search.'%"
						or tb_provinsi.nama_provinsi LIKE "%'.$search.'%")
			';

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}
			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					and tb_kabupaten.id = ".$this->session->userdata('logged_in')->id_kabupaten;
			}

			$qry .= $filter;
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetFilterAjaxCount($filter = '')
		{
			$qry = '
					SELECT 
						tb_kabupaten.id, tb_kabupaten.kode_kabupaten, tb_kabupaten.nama_kabupaten, tb_provinsi.nama_provinsi 
					FROM 
					tb_kabupaten INNER JOIN tb_provinsi ON tb_provinsi.id = tb_kabupaten.id_provinsi
					where 1 = 1
			';

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}
			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					and tb_kabupaten.id = ".$this->session->userdata('logged_in')->id_kabupaten;
			}

			$qry .= $filter;
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetAllForAjax($start, $length, $col, $dir, $filter = '')
		{

			$qry = '
					SELECT 
						tb_kabupaten.id, tb_kabupaten.kode_kabupaten, tb_kabupaten.nama_kabupaten, tb_provinsi.nama_provinsi 
					FROM 
					tb_kabupaten INNER JOIN tb_provinsi ON tb_provinsi.id = tb_kabupaten.id_provinsi
					WHERE 1 = 1';
			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					AND id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}
			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					and tb_kabupaten.id = ".$this->session->userdata('logged_in')->id_kabupaten;
			}

			$qry .= $filter;

			$qry .= ' ORDER BY '.$col.' '.$dir;
			if($length > 0)
				$qry .=	' LIMIT ' . $start . ',' . $length;

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetSearchAjax($start, $length, $search, $col, $dir, $filter = '')
		{

			$qry = '
					SELECT 
						tb_kabupaten.id, tb_kabupaten.kode_kabupaten, tb_kabupaten.nama_kabupaten, tb_provinsi.nama_provinsi 
					FROM 
					tb_kabupaten INNER JOIN tb_provinsi ON tb_provinsi.id = tb_kabupaten.id_provinsi
					where
						(tb_kabupaten.kode_kabupaten LIKE "%'.$search.'%"
						or tb_kabupaten.nama_kabupaten LIKE "%'.$search.'%"
						or tb_provinsi.nama_provinsi LIKE "%'.$search.'%")';

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}
			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					and tb_kabupaten.id = ".$this->session->userdata('logged_in')->id_kabupaten;
			}

			$qry .= $filter;

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
			$res = $this->db->get_where('tb_kabupaten', array('id' => $id));

			if($res->num_rows() > 0)
				return $res->row();
			else
				return array();
		}

		function Insert($data)
		{
			$this->db->insert('tb_kabupaten',$data);
		}

		function Update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_kabupaten', $data);
		}

		function Delete($id)
		{
			$this->db->delete('tb_kabupaten', array('id' => $id));
		}

		function GetByProvinsi($id_provinsi)
		{
			$qry =	"	select distinct id, nama_kabupaten from tb_kabupaten
					where id_provinsi = '$id_provinsi'";

			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					and id = ".$this->session->userdata('logged_in')->id_kabupaten;
			}

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetByListProvinsi($list_id_provinsi)
		{
			$qry =	"	select distinct id, nama_kabupaten from tb_kabupaten
					where 1 = 1";

			for($i=0; $i<count($list_id_provinsi); $i++){
				if($i == 0)
					$qry .= " and ( id_provinsi = ".$list_id_provinsi[$i];
				else
					$qry .= " or id_provinsi = ".$list_id_provinsi[$i];
			}

			$qry .= ")";
			
			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					and id = ".$this->session->userdata('logged_in')->id_kabupaten;
			}
			
			$qry .= " order by nama_kabupaten";

			// die($qry);

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}
	}
?>