<?php
	class JenisBarangPusatModel extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function GetAll()
		{
			// $this->db->select('tb_jenis_barang_pusat.id, tb_jenis_barang_pusat.jenis_barang, tb_jenis_barang_pusat.nama_barang, tb_jenis_barang_pusat.merk, tb_penyedia_pusat.nama_penyedia_pusat, tb_jenis_barang_pusat.kode_barang, tb_jenis_barang_pusat.akun');
			// $this->db->from('tb_jenis_barang_pusat');
			// $this->db->join('tb_penyedia_pusat', 'tb_penyedia_pusat.id = tb_jenis_barang_pusat.id_penyedia_pusat');
			// $this->db->order_by('tb_jenis_barang_pusat.jenis_barang');
			// $res = $this->db->get();
			$qry = '
				SELECT 
				tb_jenis_barang_pusat.id, tb_jenis_barang_pusat.jenis_barang, tb_jenis_barang_pusat.nama_barang,
				tb_jenis_barang_pusat.merk, tb_penyedia_pusat.nama_penyedia_pusat, tb_jenis_barang_pusat.kode_barang, 
				tb_jenis_barang_pusat.akun, tb_jenis_barang_pusat.nama_file
				FROM 
				tb_jenis_barang_pusat INNER JOIN tb_penyedia_pusat 
				ON tb_penyedia_pusat.id = tb_jenis_barang_pusat.id_penyedia_pusat
			';

			if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
				$qry .= "
					where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat;
			}
			
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
				tb_jenis_barang_pusat.id, tb_jenis_barang_pusat.jenis_barang, tb_jenis_barang_pusat.nama_barang,tb_jenis_barang_pusat.merk, tb_penyedia_pusat.nama_penyedia_pusat, tb_jenis_barang_pusat.kode_barang, tb_jenis_barang_pusat.akun FROM 
				tb_jenis_barang_pusat INNER JOIN tb_penyedia_pusat 
				ON tb_penyedia_pusat.id = tb_jenis_barang_pusat.id_penyedia_pusat
			';
			
			if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
				$qry .= "
					where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat;
			}
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetSearchAjaxCount($search, $filter = '')
		{
			$qry = '
				SELECT 
				tb_jenis_barang_pusat.id, tb_jenis_barang_pusat.jenis_barang, tb_jenis_barang_pusat.nama_barang,tb_jenis_barang_pusat.merk, tb_penyedia_pusat.nama_penyedia_pusat, tb_jenis_barang_pusat.kode_barang, tb_jenis_barang_pusat.akun FROM 
				tb_jenis_barang_pusat INNER JOIN tb_penyedia_pusat 
				ON tb_penyedia_pusat.id = tb_jenis_barang_pusat.id_penyedia_pusat
				where(
						tb_jenis_barang_pusat.jenis_barang LIKE "%'.$search.'%"
						or tb_jenis_barang_pusat.nama_barang LIKE "%'.$search.'%"
						or tb_jenis_barang_pusat.merk LIKE "%'.$search.'%"
						or tb_penyedia_pusat.nama_penyedia_pusat LIKE "%'.$search.'%"
						or tb_jenis_barang_pusat.kode_barang LIKE "%'.$search.'%"
						or tb_jenis_barang_pusat.akun LIKE "%'.$search.'%"
					)
				'.$filter.'
			';
			
			if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
				$qry .= "
					and id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat;
			}
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetFilterAjaxCount($filter = '')
		{
			$qry = '
				SELECT 
				tb_jenis_barang_pusat.id, tb_jenis_barang_pusat.jenis_barang, tb_jenis_barang_pusat.nama_barang,tb_jenis_barang_pusat.merk, tb_penyedia_pusat.nama_penyedia_pusat, tb_jenis_barang_pusat.kode_barang, tb_jenis_barang_pusat.akun FROM 
				tb_jenis_barang_pusat INNER JOIN tb_penyedia_pusat 
				ON tb_penyedia_pusat.id = tb_jenis_barang_pusat.id_penyedia_pusat
				where 1 = 1
			'.$filter;
			
			if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
				$qry .= "
					and id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat;
			}
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetAllForAjax($start, $length, $col, $dir, $filter = '')
		{

			$qry = '
					SELECT 
					tb_jenis_barang_pusat.id, tb_jenis_barang_pusat.jenis_barang, tb_jenis_barang_pusat.nama_barang,tb_jenis_barang_pusat.merk, tb_penyedia_pusat.nama_penyedia_pusat, tb_jenis_barang_pusat.kode_barang, tb_jenis_barang_pusat.akun FROM 
					tb_jenis_barang_pusat INNER JOIN tb_penyedia_pusat 
					ON tb_penyedia_pusat.id = tb_jenis_barang_pusat.id_penyedia_pusat
					where 1 = 1';
					
			if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
				$qry .= "
					and id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat;
			}
				$qry .= $filter.'
					ORDER BY '.$col.' '.$dir;
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
					tb_jenis_barang_pusat.id, tb_jenis_barang_pusat.jenis_barang, tb_jenis_barang_pusat.nama_barang,tb_jenis_barang_pusat.merk, tb_penyedia_pusat.nama_penyedia_pusat, tb_jenis_barang_pusat.kode_barang, tb_jenis_barang_pusat.akun FROM 
					tb_jenis_barang_pusat INNER JOIN tb_penyedia_pusat 
					ON tb_penyedia_pusat.id = tb_jenis_barang_pusat.id_penyedia_pusat
					where
						(tb_jenis_barang_pusat.jenis_barang LIKE "%'.$search.'%"
						or tb_jenis_barang_pusat.nama_barang LIKE "%'.$search.'%"
						or tb_jenis_barang_pusat.merk LIKE "%'.$search.'%"
						or tb_penyedia_pusat.nama_penyedia_pusat LIKE "%'.$search.'%"
						or tb_jenis_barang_pusat.kode_barang LIKE "%'.$search.'%"
						or tb_jenis_barang_pusat.akun LIKE "%'.$search.'%")';
						
			if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
				$qry .= "
					and id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat;
			}
				$qry = $filter.'
					ORDER BY '.$col.' '.$dir;
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
			$res = $this->db->get_where('tb_jenis_barang_pusat', array('id' => $id));

			if($res->num_rows() > 0)
				return $res->row();
			else
				return array();
		}

		function Insert($data)
		{
			$this->db->insert('tb_jenis_barang_pusat',$data);
		}

		function Update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_jenis_barang_pusat', $data);
		}

		function Delete($id)
		{
			$this->db->delete('tb_jenis_barang_pusat', array('id' => $id));
		}

		function GetByPenyedia($id_penyedia_pusat)
		{
			// $res = $this->db->get_where('tb_jenis_barang_pusat', array('id_penyedia_pusat' => $id_penyedia_pusat));

			if($id_penyedia_pusat == 'all'){
				$qry =	"	select distinct nama_barang from tb_jenis_barang_pusat
						order by nama_barang";
			}
			else{
				$qry =	"	select distinct nama_barang from tb_jenis_barang_pusat
						where id_penyedia_pusat = '$id_penyedia_pusat' order by nama_barang";
			}
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetMerk($id_jenis_barang_pusat)
		{
			$res = $this->db->get_where('tb_jenis_barang_pusat', array('id' => $id_jenis_barang_pusat));

			if($res->num_rows() > 0)
				return $res->row();
			else
				return array();
		}

		function GetMerkByNamaBarang($id_penyedia_pusat, $namabarang)
		{
			// $res = $this->db->get_where('tb_jenis_barang_pusat', array('id' => $id_jenis_barang_pusat));

			// $qry =	"	select distinct merk from tb_jenis_barang_pusat
			// 		where nama_barang = '$namabarang' and id_penyedia_pusat = $id_penyedia_pusat";
			
			$nama_barang = str_replace("'","\'",$namabarang);
			$nama_barang = str_replace('"','\"',$namabarang);

			if($id_penyedia_pusat == 'all'){
				$qry =	"	select distinct merk from tb_jenis_barang_pusat
					where nama_barang = '$namabarang' order by merk";
			}
			else{
				$qry =	"	select distinct merk from tb_jenis_barang_pusat
					where nama_barang = '$namabarang' and id_penyedia_pusat = $id_penyedia_pusat order by merk";
			}

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetDistinctNamaBarang()
		{
			$qry = 'select distinct nama_barang from tb_jenis_barang_pusat
			';

			if(isset($this->session->userdata('logged_in')->id_penyedia_pusat)){
				$qry .= " where id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat;
			}
			$qry .= " order by nama_barang";
			
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

	}
?>