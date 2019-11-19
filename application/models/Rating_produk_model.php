<?php
	class Rating_produk_model extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function get($id = null, $id_jenis_barang_pusat = null, $start = null, $length = null, $col = null, $dir = null, $filter = null, $search = null)
		{
			if(empty($col)) $col = 'nama_barang';
			if(empty($dir)) $dir = 'ASC';
			$qry =	"	
				SELECT tb_rating_produk.id, tb_jenis_barang_pusat.id id_jenis_barang_pusat, nama_barang, merk,
				overall, mutu, daya_tahan, kesesuaian, ketersediaan_suku_cadang, perawatan, cara_pengoperasian, tb_jenis_barang_pusat.nama_file
				FROM
				tb_jenis_barang_pusat 
				LEFT JOIN tb_rating_produk ON tb_rating_produk.id_jenis_barang_pusat=tb_jenis_barang_pusat.id
				WHERE 1=1
				";
				if (isset($id)) $qry .= " and tb_rating_produk.id = $id ";
				if (isset($id_jenis_barang_pusat)) $qry .= " and tb_jenis_barang_pusat.id = $id_jenis_barang_pusat ";
				$qry .= $filter;
				$qry .= " 
				ORDER BY ".$col." ".$dir;
			if($length > 0)
				$qry .=	' LIMIT ' . $start . ',' . $length;

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				if(isset($id) || isset($id_jenis_barang_pusat)){
					return $res->row();
				}else{
					return $res->result();
				}
			else
				return array();

			return array();
		}

		function store($data)
		{
			$this->db->insert('tb_rating_produk',$data);
		}

		function update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_rating_produk', $data);
		}

		function destroy($id)
		{
			$this->db->delete('tb_rating_produk', array('id' => $id));
		}	
	}
?>