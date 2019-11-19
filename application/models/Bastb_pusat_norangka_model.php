<?php
	class Bastb_pusat_norangka_model extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function get($id = null, $id_bastb_pusat = null, $start = null, $length = null, $col = null, $dir = null, $filter = null, $search = null)
		{
			if(empty($col)) $col = 'tb_bastb_pusat_norangka.created_at';
			if(empty($dir)) $dir = 'DESC';
			$qry =	"	
				SELECT tb_bastb_pusat_norangka.id, id_bastb_pusat, id_norangka, tb_bastb_pusat.id_alokasi_pusat, norangka, nomesin, 
				tb_kontrak_pusat.nama_barang, tb_kontrak_pusat.merk, tb_kontrak_pusat.jumlah_barang, tb_kontrak_pusat.nilai_barang
				FROM 
				tb_bastb_pusat_norangka
				INNER JOIN tb_norangka ON tb_norangka.id=tb_bastb_pusat_norangka.id_norangka
				INNER JOIN tb_bastb_pusat ON tb_bastb_pusat.id=tb_bastb_pusat_norangka.id_bastb_pusat
				INNER JOIN tb_alokasi_pusat ON tb_alokasi_pusat.id=tb_bastb_pusat.id_alokasi_pusat
				INNER JOIN tb_kontrak_pusat ON tb_kontrak_pusat.id=tb_alokasi_pusat.id_kontrak_pusat
				INNER JOIN tb_penyedia_pusat ON tb_penyedia_pusat.id=tb_kontrak_pusat.id_penyedia_pusat
				WHERE 1=1
				";
				if (isset($id_bastb_pusat)) $qry .= " and id_bastb_pusat = $id_bastb_pusat ";
				if (isset($id)) $qry .= " and tb_bastb_pusat_norangka.id = $id ";
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
			$this->db->insert('tb_bastb_pusat_norangka',$data);
		}

		function update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_bastb_pusat_norangka', $data);
		}

		function destroy($id)
		{
			$this->db->delete('tb_bastb_pusat_norangka', array('id' => $id));
		}	
	}
?>