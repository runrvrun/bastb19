<?php
	class JenisBarangProvinsiModel extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function GetAll()
		{
			// $this->db->select('tb_jenis_barang_provinsi.id, tb_jenis_barang_provinsi.jenis_barang, tb_jenis_barang_provinsi.nama_barang, tb_jenis_barang_provinsi.merk, tb_penyedia_provinsi.nama_penyedia_provinsi, tb_provinsi.nama_provinsi, tb_jenis_barang_provinsi.kode_barang, tb_jenis_barang_provinsi.akun');
			// $this->db->from('tb_jenis_barang_provinsi');
			// $this->db->join('tb_penyedia_provinsi', 'tb_penyedia_provinsi.id = tb_jenis_barang_provinsi.id_penyedia_provinsi');
			// $this->db->join('tb_provinsi', 'tb_provinsi.id = tb_penyedia_provinsi.id_provinsi');
			// $this->db->order_by('tb_jenis_barang_provinsi.jenis_barang');
			// $res = $this->db->get();
			$qry = '
				SELECT 
				tb_jenis_barang_provinsi.id, tb_jenis_barang_provinsi.jenis_barang, tb_jenis_barang_provinsi.nama_barang, tb_jenis_barang_provinsi.merk, tb_penyedia_provinsi.nama_penyedia_provinsi, tb_provinsi.nama_provinsi, tb_jenis_barang_provinsi.kode_barang, tb_jenis_barang_provinsi.akun FROM 
				tb_jenis_barang_provinsi 
				INNER JOIN tb_penyedia_provinsi 
				ON tb_penyedia_provinsi.id = tb_jenis_barang_provinsi.id_penyedia_provinsi
				INNER JOIN tb_provinsi ON tb_provinsi.id = tb_jenis_barang_provinsi.id_provinsi
					
			';

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					where tb_penyedia_provinsi.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}

			if(isset($this->session->userdata('logged_in')->id_penyedia_provinsi)){
				$qry .= "
					where tb_penyedia_provinsi.id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi;
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
				tb_jenis_barang_provinsi.id, tb_jenis_barang_provinsi.jenis_barang, tb_jenis_barang_provinsi.nama_barang, tb_jenis_barang_provinsi.merk, tb_penyedia_provinsi.nama_penyedia_provinsi, tb_provinsi.nama_provinsi, tb_jenis_barang_provinsi.kode_barang, tb_jenis_barang_provinsi.akun FROM 
				tb_jenis_barang_provinsi 
				INNER JOIN tb_penyedia_provinsi 
				ON tb_penyedia_provinsi.id = tb_jenis_barang_provinsi.id_penyedia_provinsi
				INNER JOIN tb_provinsi ON tb_provinsi.id = tb_jenis_barang_provinsi.id_provinsi
					
			';

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					where tb_penyedia_provinsi.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}

			if(isset($this->session->userdata('logged_in')->id_penyedia_provinsi)){
				$qry .= "
					where tb_penyedia_provinsi.id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi;
			}
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetSearchAjaxCount($search, $filter = '')
		{
			$qry = '
				SELECT 
				tb_jenis_barang_provinsi.id, tb_jenis_barang_provinsi.jenis_barang, tb_jenis_barang_provinsi.nama_barang, tb_jenis_barang_provinsi.merk, tb_penyedia_provinsi.nama_penyedia_provinsi, tb_provinsi.nama_provinsi, tb_jenis_barang_provinsi.kode_barang, tb_jenis_barang_provinsi.akun FROM 
				tb_jenis_barang_provinsi INNER JOIN tb_penyedia_provinsi 
				ON tb_penyedia_provinsi.id = tb_jenis_barang_provinsi.id_penyedia_provinsi
				INNER JOIN tb_provinsi ON tb_provinsi.id = tb_jenis_barang_provinsi.id_provinsi
				where(
						tb_jenis_barang_provinsi.jenis_barang LIKE "%'.$search.'%"
						or tb_jenis_barang_provinsi.nama_barang LIKE "%'.$search.'%"
						or tb_jenis_barang_provinsi.merk LIKE "%'.$search.'%"
						or tb_penyedia_provinsi.nama_penyedia_provinsi LIKE "%'.$search.'%"
						or tb_jenis_barang_provinsi.kode_barang LIKE "%'.$search.'%"
						or tb_jenis_barang_provinsi.akun LIKE "%'.$search.'%"
					) '.$filter.'
			';

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and tb_penyedia_provinsi.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}

			if(isset($this->session->userdata('logged_in')->id_penyedia_provinsi)){
				$qry .= "
					and tb_penyedia_provinsi.id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi;
			}
			
			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetFilterAjaxCount($filter = '')
		{
			$qry = '
				SELECT 
				tb_jenis_barang_provinsi.id, tb_jenis_barang_provinsi.jenis_barang, tb_jenis_barang_provinsi.nama_barang,tb_jenis_barang_provinsi.merk, tb_penyedia_provinsi.nama_penyedia_provinsi, tb_jenis_barang_provinsi.kode_barang, tb_jenis_barang_provinsi.akun FROM 
				tb_jenis_barang_provinsi 
				INNER JOIN tb_penyedia_provinsi 
				ON tb_penyedia_provinsi.id = tb_jenis_barang_provinsi.id_penyedia_provinsi
				INNER JOIN tb_provinsi ON tb_provinsi.id = tb_jenis_barang_provinsi.id_provinsi
				where 1 = 1
			'.$filter;
			
			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and tb_penyedia_provinsi.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}

			if(isset($this->session->userdata('logged_in')->id_penyedia_provinsi)){
				$qry .= "
					and tb_penyedia_provinsi.id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi;
			}

			$res = $this->db->query($qry);

			return $res->num_rows();
		}

		function GetAllForAjax($start, $length, $col, $dir, $filter = '')
		{

			$qry = '
					SELECT 
					tb_jenis_barang_provinsi.id, tb_jenis_barang_provinsi.jenis_barang, tb_jenis_barang_provinsi.nama_barang, tb_jenis_barang_provinsi.merk, tb_penyedia_provinsi.nama_penyedia_provinsi, tb_provinsi.nama_provinsi, tb_jenis_barang_provinsi.kode_barang, tb_jenis_barang_provinsi.akun FROM 
					tb_jenis_barang_provinsi INNER JOIN tb_penyedia_provinsi 
					ON tb_penyedia_provinsi.id = tb_jenis_barang_provinsi.id_penyedia_provinsi
					INNER JOIN tb_provinsi ON tb_provinsi.id = tb_jenis_barang_provinsi.id_provinsi
					WHERE 1 = 1
					'.$filter;

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and tb_penyedia_provinsi.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}

			if(isset($this->session->userdata('logged_in')->id_penyedia_provinsi)){
				$qry .= "
					and tb_penyedia_provinsi.id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi;
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

		function GetSearchAjax($start, $length, $search, $col, $dir, $filter = '')
		{

			$qry = '
					SELECT 
					tb_jenis_barang_provinsi.id, tb_jenis_barang_provinsi.jenis_barang, tb_jenis_barang_provinsi.nama_barang, tb_jenis_barang_provinsi.merk, tb_penyedia_provinsi.nama_penyedia_provinsi, tb_provinsi.nama_provinsi, tb_jenis_barang_provinsi.kode_barang, tb_jenis_barang_provinsi.akun FROM 
					tb_jenis_barang_provinsi INNER JOIN tb_penyedia_provinsi
					ON tb_penyedia_provinsi.id = tb_jenis_barang_provinsi.id_penyedia_provinsi
					INNER JOIN tb_provinsi ON tb_provinsi.id = tb_jenis_barang_provinsi.id_provinsi
					where(
						tb_jenis_barang_provinsi.jenis_barang LIKE "%'.$search.'%"
						or tb_jenis_barang_provinsi.nama_barang LIKE "%'.$search.'%"
						or tb_jenis_barang_provinsi.merk LIKE "%'.$search.'%"
						or tb_penyedia_provinsi.nama_penyedia_provinsi LIKE "%'.$search.'%"
						or tb_jenis_barang_provinsi.kode_barang LIKE "%'.$search.'%"
						or tb_jenis_barang_provinsi.akun LIKE "%'.$search.'%"
					)
					'.$filter;

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and tb_penyedia_provinsi.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}

			if(isset($this->session->userdata('logged_in')->id_penyedia_provinsi)){
				$qry .= "
					and tb_penyedia_provinsi.id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi;
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
			$res = $this->db->get_where('tb_jenis_barang_provinsi', array('id' => $id));

			if($res->num_rows() > 0)
				return $res->row();
			else
				return array();
		}

		function Insert($data)
		{
			$this->db->insert('tb_jenis_barang_provinsi',$data);
		}

		function Update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_jenis_barang_provinsi', $data);
		}

		function Delete($id)
		{
			$this->db->delete('tb_jenis_barang_provinsi', array('id' => $id));
		}

		function GetByPenyedia($id_penyedia_provinsi)
		{

			if($id_penyedia_provinsi == 'all'){
				$qry =	"	select distinct nama_barang from tb_jenis_barang_provinsi
						where 1 = 1";
			}
			else{
				$qry =	"	select distinct nama_barang from tb_jenis_barang_provinsi
						where id_penyedia_provinsi = '$id_penyedia_provinsi' ";
			}

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}
			if(isset($this->session->userdata('logged_in')->id_penyedia_provinsi)){
				$qry .= "
					and id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi;
			}
			$qry .= " order by nama_barang";

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetByProvinsi($id_provinsi)
		{

			
			$qry =	"	select distinct nama_barang from tb_jenis_barang_provinsi
					where id_penyedia_provinsi IN (SELECT id from tb_penyedia_provinsi WHERE id_provinsi = '$id_provinsi') order by nama_barang";
			
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetMerkByNamaBarang($id_penyedia_provinsi, $namabarang)
		{
			// echo $namabarang;exit();			
			$namabarang = str_replace("'","\'",$namabarang);
			$namabarang = str_replace('"','\"',$namabarang);
			if($id_penyedia_provinsi == 'all'){
				$qry =	"select distinct merk from tb_jenis_barang_provinsi
					where nama_barang = '$namabarang'";
			}
			else{
				$qry =	"	select distinct merk from tb_jenis_barang_provinsi
					where nama_barang = '$namabarang' and id_penyedia_provinsi = $id_penyedia_provinsi";
			}

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}
			if(isset($this->session->userdata('logged_in')->id_penyedia_provinsi)){
				$qry .= "
					and id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi;
			}

			$qry .= " order by merk";
			// echo $qry;exit();
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetMerkByProvinsi($id_provinsi, $namabarang)
		{
			$namabarang = str_replace("'","\'",$namabarang);
			$namabarang = str_replace('"','\"',$namabarang);
			
			$qry =	"select distinct merk from tb_jenis_barang_provinsi
					where nama_barang = '$namabarang' and id_provinsi = $id_provinsi";

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}
			if(isset($this->session->userdata('logged_in')->id_penyedia_provinsi)){
				$qry .= "
					and id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi;
			}

			$qry .= " order by merk";

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetDistinctNamaBarang()
		{
			$qry = '
				select distinct nama_barang from tb_jenis_barang_provinsi
					
			';

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					where id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}
			if(isset($this->session->userdata('logged_in')->id_penyedia_provinsi)){
				$qry .= "
					where id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi;
			}

			$qry .= ' order by nama_barang';
			
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function GetByListProvinsi($list_id_provinsi)
		{
			$qry =	"	select distinct nama_barang from tb_jenis_barang_provinsi
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
					and id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi;
			}
			
			$qry .= " order by nama_barang";

			// die($qry);

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

	}
?>