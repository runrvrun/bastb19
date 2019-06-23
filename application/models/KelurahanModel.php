<?php
	class KelurahanModel extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function GetAll()
		{
			// $this->db->select('tb_kelurahan.id, tb_kelurahan.kode_kelurahan, tb_kelurahan.nama_kelurahan, tb_provinsi.nama_provinsi, tb_kabupaten.nama_kabupaten, tb_kecamatan.nama_kecamatan');
			// $this->db->from('tb_kelurahan');
			// $this->db->join('tb_kecamatan', 'tb_kecamatan.id = tb_kelurahan.id_kecamatan');
			// $this->db->join('tb_kabupaten', 'tb_kabupaten.id = tb_kelurahan.id_kabupaten');
			// $this->db->join('tb_provinsi', 'tb_provinsi.id = tb_kelurahan.id_provinsi');
			// $this->db->order_by('tb_kelurahan.kode_kelurahan');
			// $res = $this->db->get();


			$qry = '
					SELECT 
						kel.id, kel.kode_kelurahan, kel.nama_kelurahan, 
						kec.nama_kecamatan, kab.nama_kabupaten, prov.nama_provinsi FROM 
					tb_kelurahan kel
					LEFT JOIN tb_kecamatan kec ON kec.id = kel.id_kecamatan
					LEFT JOIN tb_kabupaten kab ON kab.id = kec.id_kabupaten
					LEFT JOIN tb_provinsi prov ON prov.id = kab.id_provinsi
			';

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					where kel.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}
			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					where kel.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
			}
			
			$res = $this->db->query($qry);

			// return array();

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetAllAjaxCount()
		{
			$qry = '
					SELECT 
						kel.id, kel.kode_kelurahan, kel.nama_kelurahan, 
						kec.nama_kecamatan, kab.nama_kabupaten, prov.nama_provinsi FROM 
					tb_kelurahan kel
					LEFT JOIN tb_kecamatan kec ON kec.id = kel.id_kecamatan
					LEFT JOIN tb_kabupaten kab ON kab.id = kec.id_kabupaten
					LEFT JOIN tb_provinsi prov ON prov.id = kab.id_provinsi
			';

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					where kel.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}
			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					where kel.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
			}
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetSearchAjaxCount($search, $filter = '')
		{
			$qry = '
					SELECT 
						kel.id, kel.kode_kelurahan, kel.nama_kelurahan, 
						kec.nama_kecamatan, kab.nama_kabupaten, prov.nama_provinsi FROM 
					tb_kelurahan kel
					LEFT JOIN tb_kecamatan kec ON kec.id = kel.id_kecamatan
					LEFT JOIN tb_kabupaten kab ON kab.id = kec.id_kabupaten
					LEFT JOIN tb_provinsi prov ON prov.id = kab.id_provinsi
					where
						(kel.nama_kelurahan LIKE "%'.$search.'%"
						or kel.kode_kelurahan LIKE "%'.$search.'%"
						or kec.nama_kecamatan LIKE "%'.$search.'%"
						or kab.nama_kabupaten LIKE "%'.$search.'%" 
						or prov.nama_provinsi LIKE "%'.$search.'%" )
			'.$filter;

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and kel.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}

			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					and kel.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
			}
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetFilterAjaxCount($filter = '')
		{
			$qry = '
					SELECT 
						kel.id, kel.kode_kelurahan, kel.nama_kelurahan, 
						kec.nama_kecamatan, kab.nama_kabupaten, prov.nama_provinsi FROM 
					tb_kelurahan kel
					LEFT JOIN tb_kecamatan kec ON kec.id = kel.id_kecamatan
					LEFT JOIN tb_kabupaten kab ON kab.id = kec.id_kabupaten
					LEFT JOIN tb_provinsi prov ON prov.id = kab.id_provinsi
					where 1 = 1
						
			'.$filter;

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and kel.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}
			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					and kel.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
			}
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetAllForAjax($start, $length, $col, $dir, $filter = '')
		{
			// $this->db->select('tb_kelurahan.id, tb_kelurahan.kode_kelurahan, tb_kelurahan.nama_kelurahan, tb_provinsi.nama_provinsi, tb_kabupaten.nama_kabupaten, tb_kecamatan.nama_kecamatan');
			// $this->db->from('tb_kelurahan');
			// $this->db->join('tb_kecamatan', 'tb_kecamatan.id = tb_kelurahan.id_kecamatan');
			// $this->db->join('tb_kabupaten', 'tb_kabupaten.id = tb_kelurahan.id_kabupaten');
			// $this->db->join('tb_provinsi', 'tb_provinsi.id = tb_kelurahan.id_provinsi');
			// $this->db->order_by('tb_kelurahan.kode_kelurahan');
			// $res = $this->db->get();


			$qry = '
					SELECT 
						kel.id, kel.kode_kelurahan, kel.nama_kelurahan, 
						kec.nama_kecamatan, kab.nama_kabupaten, prov.nama_provinsi FROM 
					tb_kelurahan kel
					LEFT JOIN tb_kecamatan kec ON kec.id = kel.id_kecamatan
					LEFT JOIN tb_kabupaten kab ON kab.id = kec.id_kabupaten
					LEFT JOIN tb_provinsi prov ON prov.id = kab.id_provinsi
					where 1 = 1 '.$filter;
			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and kel.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}
			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					and kel.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
			}

			$qry .= ' ORDER BY '.$col.' '.$dir;
			if($length > 0)
				$qry .=	' LIMIT ' . $start . ',' . $length;

			$res = $this->db->query($qry);

			// return array();

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetSearchAjax($start, $length, $search, $col, $dir, $filter = '')
		{
			// $this->db->select('tb_kelurahan.id, tb_kelurahan.kode_kelurahan, tb_kelurahan.nama_kelurahan, tb_provinsi.nama_provinsi, tb_kabupaten.nama_kabupaten, tb_kecamatan.nama_kecamatan');
			// $this->db->from('tb_kelurahan');
			// $this->db->join('tb_kecamatan', 'tb_kecamatan.id = tb_kelurahan.id_kecamatan');
			// $this->db->join('tb_kabupaten', 'tb_kabupaten.id = tb_kelurahan.id_kabupaten');
			// $this->db->join('tb_provinsi', 'tb_provinsi.id = tb_kelurahan.id_provinsi');
			// $this->db->order_by('tb_kelurahan.kode_kelurahan');
			// $res = $this->db->get();


			$qry = '
					SELECT 
						kel.id, kel.kode_kelurahan, kel.nama_kelurahan, 
						kec.nama_kecamatan, kab.nama_kabupaten, prov.nama_provinsi FROM 
					tb_kelurahan kel
					LEFT JOIN tb_kecamatan kec ON kec.id = kel.id_kecamatan
					LEFT JOIN tb_kabupaten kab ON kab.id = kec.id_kabupaten
					LEFT JOIN tb_provinsi prov ON prov.id = kab.id_provinsi
					where
						(kel.nama_kelurahan LIKE "%'.$search.'%"
						or kel.kode_kelurahan LIKE "%'.$search.'%"
						or kec.nama_kecamatan LIKE "%'.$search.'%"
						or kab.nama_kabupaten LIKE "%'.$search.'%" 
						or prov.nama_provinsi LIKE "%'.$search.'%")'.$filter;
			
			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and kel.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}
			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					and kel.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
			}

			$qry .= ' ORDER BY '.$col.' '.$dir;
			if($length > 0)
				$qry .=	' LIMIT ' . $start . ',' . $length;

			$res = $this->db->query($qry);

			// return array();

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function ExportAllForAjax($col, $dir, $filter = '')
		{
			// $this->db->select('tb_kelurahan.id, tb_kelurahan.kode_kelurahan, tb_kelurahan.nama_kelurahan, tb_provinsi.nama_provinsi, tb_kabupaten.nama_kabupaten, tb_kecamatan.nama_kecamatan');
			// $this->db->from('tb_kelurahan');
			// $this->db->join('tb_kecamatan', 'tb_kecamatan.id = tb_kelurahan.id_kecamatan');
			// $this->db->join('tb_kabupaten', 'tb_kabupaten.id = tb_kelurahan.id_kabupaten');
			// $this->db->join('tb_provinsi', 'tb_provinsi.id = tb_kelurahan.id_provinsi');
			// $this->db->order_by('tb_kelurahan.kode_kelurahan');
			// $res = $this->db->get();


			$qry = '
					SELECT 
						kel.id, kel.kode_kelurahan, kel.nama_kelurahan, 
						kec.nama_kecamatan, kab.nama_kabupaten, prov.nama_provinsi FROM 
					tb_kelurahan kel
					LEFT JOIN tb_kecamatan kec ON kec.id = kel.id_kecamatan
					LEFT JOIN tb_kabupaten kab ON kab.id = kec.id_kabupaten
					LEFT JOIN tb_provinsi prov ON prov.id = kab.id_provinsi
					where 1 = 1 '.$filter;
			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and kel.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}
			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					and kel.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
			}

			$qry .= ' ORDER BY '.$col.' '.$dir;
			
			$data = array();
			$res = $this->db->query($qry);
			if($res->num_rows() > 0) {
				$data = $res->result_array();
			}		
			return $data;
		}

		function ExportSearchAjax($search, $col, $dir, $filter = '')
		{
			// $this->db->select('tb_kelurahan.id, tb_kelurahan.kode_kelurahan, tb_kelurahan.nama_kelurahan, tb_provinsi.nama_provinsi, tb_kabupaten.nama_kabupaten, tb_kecamatan.nama_kecamatan');
			// $this->db->from('tb_kelurahan');
			// $this->db->join('tb_kecamatan', 'tb_kecamatan.id = tb_kelurahan.id_kecamatan');
			// $this->db->join('tb_kabupaten', 'tb_kabupaten.id = tb_kelurahan.id_kabupaten');
			// $this->db->join('tb_provinsi', 'tb_provinsi.id = tb_kelurahan.id_provinsi');
			// $this->db->order_by('tb_kelurahan.kode_kelurahan');
			// $res = $this->db->get();


			$qry = '
					SELECT 
						kel.id, kel.kode_kelurahan, kel.nama_kelurahan, 
						kec.nama_kecamatan, kab.nama_kabupaten, prov.nama_provinsi FROM 
					tb_kelurahan kel
					LEFT JOIN tb_kecamatan kec ON kec.id = kel.id_kecamatan
					LEFT JOIN tb_kabupaten kab ON kab.id = kec.id_kabupaten
					LEFT JOIN tb_provinsi prov ON prov.id = kab.id_provinsi
					where
						(kel.nama_kelurahan LIKE "%'.$search.'%"
						or kel.kode_kelurahan LIKE "%'.$search.'%"
						or kec.nama_kecamatan LIKE "%'.$search.'%"
						or kab.nama_kabupaten LIKE "%'.$search.'%" 
						or prov.nama_provinsi LIKE "%'.$search.'%")'.$filter;
			
			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and kel.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}
			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					and kel.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten;
			}

			$qry .= ' ORDER BY '.$col.' '.$dir;

			$data = array();
			$res = $this->db->query($qry);
			if($res->num_rows() > 0) {
				$data = $res->result_array();
			}		
			return $data;
		}

		function Get($id)
		{
			$res = $this->db->get_where('tb_kelurahan', array('id' => $id));

			if($res->num_rows() > 0)
				return $res->row();
			else
				return array();
		}

		function Insert($data)
		{
			$this->db->insert('tb_kelurahan',$data);
		}

		function Update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_kelurahan', $data);
		}

		function Delete($id)
		{
			$this->db->delete('tb_kelurahan', array('id' => $id));
		}

		function GetByKecamatan($id_provinsi, $id_kabupaten, $id_kecamatan)
		{
			$qry =	"	select distinct id, nama_kelurahan from tb_kelurahan
					where id_provinsi = '$id_provinsi' and id_kabupaten = '$id_kabupaten' and id_kecamatan = '$id_kecamatan' ";
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}
	}
?>