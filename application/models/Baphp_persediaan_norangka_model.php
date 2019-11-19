<?php
	class Baphp_persediaan_norangka_model extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function get($id = null, $id_baphp_persediaan = null, $start = null, $length = null, $col = null, $dir = null, $filter = null, $search = null)
		{
			if(empty($col)) $col = 'tb_baphp_persediaan_norangka.created_at';
			if(empty($dir)) $dir = 'DESC';
			$qry =	"	
				SELECT tb_baphp_persediaan_norangka.id, id_baphp_persediaan, id_norangka, 
				tb_baphp_persediaan.id_alokasi_persediaan_pusat, norangka, nomesin, 
				tb_kontrak_pusat.nama_barang, tb_kontrak_pusat.merk, tb_kontrak_pusat.jumlah_barang, tb_kontrak_pusat.nilai_barang
				FROM 
				tb_baphp_persediaan_norangka
				INNER JOIN tb_norangka ON tb_norangka.id=tb_baphp_persediaan_norangka.id_norangka
				INNER JOIN tb_baphp_persediaan ON tb_baphp_persediaan.id=tb_baphp_persediaan_norangka.id_baphp_persediaan
				INNER JOIN tb_alokasi_persediaan_pusat ON tb_alokasi_persediaan_pusat.id=tb_baphp_persediaan.id_alokasi_persediaan_pusat
				INNER JOIN tb_alokasi_pusat ON tb_alokasi_pusat.id=tb_alokasi_persediaan_pusat.id_alokasi
				INNER JOIN tb_kontrak_pusat ON tb_kontrak_pusat.id=tb_alokasi_pusat.id_kontrak_pusat
				INNER JOIN tb_penyedia_pusat ON tb_penyedia_pusat.id=tb_kontrak_pusat.id_penyedia_pusat
				WHERE 1=1
				";
				if (isset($id_baphp_persediaan)) $qry .= " and id_baphp_persediaan = $id_baphp_persediaan ";
				if (isset($id)) $qry .= " and tb_baphp_persediaan_norangka.id = $id ";
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
			$this->db->insert('tb_baphp_persediaan_norangka',$data);
		}

		function update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_baphp_persediaan_norangka', $data);
		}

		function destroy($id)
		{
			$this->db->delete('tb_baphp_persediaan_norangka', array('id' => $id));
		}	
	}
?>