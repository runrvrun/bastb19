<?php
	class Bart_norangka_model extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function get($id = null, $param = null)
		{
			if(!isset($param['order'])) $param['order'] = 'tb_bart_norangka.created_at';
			if(!isset($param['dir'])) $param['dir'] = 'DESC';
			$qry =	"	
				SELECT tb_bart_norangka.id, id_bart, tb_bart_norangka.id_kontrak_pusat, norangka, nomesin, tb_bart.jumlah_unit, 
				tb_kontrak_pusat.nama_barang, tb_kontrak_pusat.merk, tb_kontrak_pusat.jumlah_barang, tb_kontrak_pusat.nilai_barang				FROM 
				tb_bart_norangka
				INNER JOIN tb_bart ON tb_bart.id=tb_bart_norangka.id_bart
				INNER JOIN tb_kontrak_pusat ON tb_kontrak_pusat.id=tb_bart.id_kontrak_pusat
				INNER JOIN tb_penyedia_pusat ON tb_penyedia_pusat.id=tb_kontrak_pusat.id_penyedia_pusat
				WHERE 1=1
				";
				if (isset($param['id_bart'])) $qry .= " and id_bart = ".$param['id_bart'];
				if (isset($param['id_kontrak_pusat'])) $qry .= " and tb_bart_norangka.id_kontrak_pusat = ".$param['id_kontrak_pusat'];
				if (isset($param['id_baphp'])) $qry .= " and tb_baphp_reguler.id = ".$param['id_baphp'];
				if (isset($id)) $qry .= " and tb_bart_norangka.id = $id ";
				if (isset($param['filter'])) $qry .= $param['filter'];
				if (isset($param['order'])) $qry .= " ORDER BY ".$param['order']." ".$param['dir'];
				if(isset($param['length']) && $param['length'] > 0) $qry .=	' LIMIT ' . $param['start'] . ',' . $param['length'];

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
			$this->db->insert('tb_bart_norangka',$data);
		}

		function update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_bart_norangka', $data);
		}

		function destroy($id)
		{
			$this->db->delete('tb_bart_norangka', array('id' => $id));
		}	

		function get_nomesin(){
			$qry =	"SELECT nomesin FROM tb_bart_norangka";
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function get_norangka(){
			$qry =	"SELECT norangka FROM tb_bart_norangka";
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function get_used($id_bart){
			$qry =	"SELECT count(1) FROM tb_bart_norangka INNER JOIN tb_baphp_norangka ON tb_bart_norangka.id=tb_baphp_norangka.id_norangka
			 WHERE tb_bart_norangka.id_bart = $id_bart";
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}
	}
?>