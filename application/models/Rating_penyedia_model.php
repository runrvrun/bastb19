<?php
	class Rating_penyedia_model extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function get($id = null, $id_penyedia_pusat = null, $start = null, $length = null, $col = null, $dir = null, $filter = null, $search = null)
		{
			if(empty($col)) $col = 'nama_penyedia_pusat';
			if(empty($dir)) $dir = 'ASC';
			$qry =	"	
				SELECT tb_rating_penyedia.id, tb_penyedia_pusat.id id_penyedia_pusat, nama_penyedia_pusat, overall, kecepatan_pelayanan, kualitas_pelayanan, penjelasan_produk, 
				bantuan_informasi_teknis, penanganan_komplain, tb_penyedia_pusat.nama_file
				FROM
				tb_penyedia_pusat 
				LEFT JOIN tb_rating_penyedia ON tb_rating_penyedia.id_penyedia_pusat=tb_penyedia_pusat.id
				WHERE 1=1
				";
				if (isset($id)) $qry .= " and tb_rating_penyedia.id = $id ";
				$qry .= $filter;
				$qry .= " 
				ORDER BY ".$col." ".$dir;
			if($length > 0)
				$qry .=	' LIMIT ' . $start . ',' . $length;

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				if(isset($id) || isset($id_penyedia_pusat)){
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
			$this->db->insert('tb_rating_penyedia',$data);
		}

		function update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_rating_penyedia', $data);
		}

		function destroy($id)
		{
			$this->db->delete('tb_rating_penyedia', array('id' => $id));
		}	
	}
?>