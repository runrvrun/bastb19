<?php
	class KecamatanModel extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function GetAll()
		{
			// $this->db->select('tb_kecamatan.id, tb_kecamatan.kode_kecamatan, tb_kecamatan.nama_kecamatan, tb_provinsi.nama_provinsi, tb_kabupaten.nama_kabupaten');
			// $this->db->from('tb_kecamatan');
			// $this->db->join('tb_kabupaten', 'tb_kabupaten.id = tb_kecamatan.id_kabupaten');
			// $this->db->join('tb_provinsi', 'tb_provinsi.id = tb_kecamatan.id_provinsi');
			// $this->db->order_by('tb_kecamatan.kode_kecamatan');
			// $res = $this->db->get();
			$qry = '
					SELECT 
						kec.id, kec.kode_kecamatan,
						kec.nama_kecamatan, kab.nama_kabupaten, prov.nama_provinsi FROM 
					tb_kecamatan kec
					LEFT JOIN tb_kabupaten kab ON kab.id = kec.id_kabupaten
					LEFT JOIN tb_provinsi prov ON prov.id = kec.id_provinsi
			';

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					where kec.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}
			
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();

			// return array();
		}

		function GetAllAjaxCount()
		{
			$qry = '
					SELECT 
						kec.id, kec.kode_kecamatan,
						kec.nama_kecamatan, kab.nama_kabupaten, prov.nama_provinsi FROM 
					tb_kecamatan kec
					LEFT JOIN tb_kabupaten kab ON kab.id = kec.id_kabupaten
					LEFT JOIN tb_provinsi prov ON prov.id = kec.id_provinsi
			';

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					where kec.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetSearchAjaxCount($search, $filter = '')
		{
			$qry = '
					SELECT 
						kec.id, kec.kode_kecamatan,
						kec.nama_kecamatan, kab.nama_kabupaten, prov.nama_provinsi FROM 
					tb_kecamatan kec
					LEFT JOIN tb_kabupaten kab ON kab.id = kec.id_kabupaten
					LEFT JOIN tb_provinsi prov ON prov.id = kec.id_provinsi
					where
						(kec.nama_kecamatan LIKE "%'.$search.'%"
						or kec.kode_kecamatan LIKE "%'.$search.'%" 
						or kab.nama_kabupaten LIKE "%'.$search.'%" 
						or prov.nama_provinsi LIKE "%'.$search.'%") 
			';

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and kec.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}
			$qry .= $filter;
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetFilterAjaxCount($filter = '')
		{
			$qry = '
					SELECT 
						kec.id, kec.kode_kecamatan,
						kec.nama_kecamatan, kab.nama_kabupaten, prov.nama_provinsi FROM 
					tb_kecamatan kec
					LEFT JOIN tb_kabupaten kab ON kab.id = kec.id_kabupaten
					LEFT JOIN tb_provinsi prov ON prov.id = kec.id_provinsi
					where 1 = 1
			';

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and kec.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}
			$qry .= $filter;
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetAllForAjax($start, $length, $col, $dir, $filter = '')
		{

			$qry = '
					SELECT 
						kec.id, kec.kode_kecamatan,
						kec.nama_kecamatan, kab.nama_kabupaten, prov.nama_provinsi FROM 
					tb_kecamatan kec
					LEFT JOIN tb_kabupaten kab ON kab.id = kec.id_kabupaten
					LEFT JOIN tb_provinsi prov ON prov.id = kec.id_provinsi
					where 1 = 1';

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and kec.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
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
						kec.id, kec.kode_kecamatan,
						kec.nama_kecamatan, kab.nama_kabupaten, prov.nama_provinsi FROM 
					tb_kecamatan kec
					LEFT JOIN tb_kabupaten kab ON kab.id = kec.id_kabupaten
					LEFT JOIN tb_provinsi prov ON prov.id = kec.id_provinsi
					where
						(kec.nama_kecamatan LIKE "%'.$search.'%"
						or kec.kode_kecamatan LIKE "%'.$search.'%"
						or kab.nama_kabupaten LIKE "%'.$search.'%" 
						or prov.nama_provinsi LIKE "%'.$search.'%")';

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and kec.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
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
			$res = $this->db->get_where('tb_kecamatan', array('id' => $id));

			if($res->num_rows() > 0)
				return $res->row();
			else
				return array();
		}

		function Insert($data)
		{
			$this->db->insert('tb_kecamatan',$data);
		}

		function Update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_kecamatan', $data);
		}

		function Delete($id)
		{
			$this->db->delete('tb_kecamatan', array('id' => $id));
		}

		function GetByKabupaten($id_provinsi, $id_kabupaten)
		{
			$qry =	"	select distinct id, nama_kecamatan from tb_kecamatan
					where id_provinsi = '$id_provinsi' and id_kabupaten = '$id_kabupaten' ";
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}
	}
?>