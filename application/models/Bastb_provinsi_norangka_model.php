<?php
	class Bastb_provinsi_norangka_model extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function get($id = null, $id_bastb_provinsi = null, $start = null, $length = null, $col = null, $dir = null, $filter = null, $search = null)
		{
			if(empty($col)) $col = 'tb_bastb_provinsi_norangka.created_at';
			if(empty($dir)) $dir = 'DESC';
			$qry =	"	
				SELECT tb_bastb_provinsi_norangka.id, id_bastb_provinsi, norangka, nomesin, tb_bastb_provinsi.id_alokasi_provinsi, norangka, nomesin, 
				tb_kontrak_provinsi.nama_barang, tb_kontrak_provinsi.merk, tb_kontrak_provinsi.jumlah_barang, tb_kontrak_provinsi.nilai_barang
				FROM 
				tb_bastb_provinsi_norangka
				INNER JOIN tb_bastb_provinsi ON tb_bastb_provinsi.id=tb_bastb_provinsi_norangka.id_bastb_provinsi
				INNER JOIN tb_alokasi_provinsi ON tb_alokasi_provinsi.id=tb_bastb_provinsi.id_alokasi_provinsi
				INNER JOIN tb_kontrak_provinsi ON tb_kontrak_provinsi.id=tb_alokasi_provinsi.id_kontrak_provinsi
				INNER JOIN tb_penyedia_provinsi ON tb_penyedia_provinsi.id=tb_kontrak_provinsi.id_penyedia_provinsi
				WHERE 1=1
				";
				if (isset($id_bastb_provinsi)) $qry .= " and id_bastb_provinsi = $id_bastb_provinsi ";
				if (isset($id)) $qry .= " and tb_bastb_provinsi_norangka.id = $id ";
				$qry .= $filter;
				$qry .= " 
				ORDER BY ".$col." ".$dir;
			if($length > 0)
				$qry .=	' LIMIT ' . $start . ',' . $length;

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				if(isset($id)){
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
			$this->db->insert('tb_bastb_provinsi_norangka',$data);
		}

		function update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_bastb_provinsi_norangka', $data);
		}

		function destroy($id)
		{
			$this->db->delete('tb_bastb_provinsi_norangka', array('id' => $id));
		}	
		
		function get_nomesin(){
			$qry =	"SELECT nomesin FROM tb_bastb_provinsi_norangka";
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function get_norangka(){
			$qry =	"SELECT norangka FROM tb_bastb_provinsi_norangka";
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

	}
?>