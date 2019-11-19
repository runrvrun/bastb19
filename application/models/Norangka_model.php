<?php
	class Norangka_model extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function get($id = null, $param = null)
		{
			if(!isset($param['order'])) $param['order'] = 'tb_norangka.created_at';
			if(!isset($param['dir'])) $param['dir'] = 'DESC';
			$qry =	"	
				SELECT tb_norangka.id, id_bapb, tb_norangka.id_kontrak_pusat, norangka, nomesin, tb_bapb.jumlah_unit, 
				tb_kontrak_pusat.nama_barang, tb_kontrak_pusat.merk, tb_kontrak_pusat.jumlah_barang, tb_kontrak_pusat.nilai_barang,
				no_baphp
				FROM 
				tb_norangka
				INNER JOIN tb_bapb ON tb_bapb.id=tb_norangka.id_bapb
				INNER JOIN tb_kontrak_pusat ON tb_kontrak_pusat.id=tb_bapb.id_kontrak_pusat
				INNER JOIN tb_penyedia_pusat ON tb_penyedia_pusat.id=tb_kontrak_pusat.id_penyedia_pusat
				LEFT JOIN tb_baphp_norangka ON tb_norangka.id = tb_baphp_norangka.id_norangka
				LEFT JOIN tb_baphp_reguler ON tb_baphp_norangka.id_baphp = tb_baphp_reguler.id 
				LEFT JOIN tb_alokasi_pusat ON tb_alokasi_pusat.id = tb_baphp_reguler.id_alokasi_pusat 
				WHERE 1=1
				";
				if (isset($param['id_bapb'])) $qry .= " and id_bapb = ".$param['id_bapb'];
				if (isset($param['id_kontrak_pusat'])) $qry .= " and tb_norangka.id_kontrak_pusat = ".$param['id_kontrak_pusat'];
				if (isset($param['id_alokasi_pusat'])) $qry .= " and tb_alokasi_pusat.id = ".$param['id_alokasi_pusat'];
				if (isset($param['id_baphp'])) $qry .= " and tb_baphp_reguler.id = ".$param['id_baphp'];
				if (isset($id)) $qry .= " and tb_norangka.id = $id ";
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

		function get_unselbaphp($id = null, $param = null)
		{
			if(!isset($param['order'])) $param['order'] = 'tb_norangka.norangka';
			if(!isset($param['dir'])) $param['dir'] = 'DESC';
			$qry =	"	
				SELECT tb_norangka.id, tb_norangka.id_kontrak_pusat, norangka, nomesin, 0 as no_baphp
				FROM 
				tb_norangka
				INNER JOIN tb_bapb ON tb_bapb.id=tb_norangka.id_bapb
				INNER JOIN tb_kontrak_pusat ON tb_kontrak_pusat.id=tb_bapb.id_kontrak_pusat
				WHERE 
				tb_norangka.id NOT IN (SELECT id_norangka FROM tb_baphp_norangka)
				AND tb_norangka.id NOT IN (SELECT id_norangka FROM tb_baphp_persediaan_norangka) 
				";
				if (isset($param['id_kontrak_pusat'])) $qry .= " and tb_norangka.id_kontrak_pusat = ".$param['id_kontrak_pusat'];
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

		function get_unselbastb($id = null, $param = null)
		{
			if(!isset($param['order'])) $param['order'] = 'tb_norangka.norangka';
			if(!isset($param['dir'])) $param['dir'] = 'DESC';
			$qry =	"	
				SELECT DISTINCT tb_norangka.id, tb_norangka.id_kontrak_pusat, norangka, nomesin, 0 as no_baphp
				FROM 
				tb_norangka
				INNER JOIN tb_bapb ON tb_bapb.id=tb_norangka.id_bapb
				INNER JOIN tb_kontrak_pusat ON tb_kontrak_pusat.id=tb_bapb.id_kontrak_pusat ";
			if(!empty($param['id_alokasi_pusat'])){
				$qry .=	" INNER JOIN tb_baphp_reguler ON tb_baphp_reguler.id_alokasi_pusat = ".$param['id_alokasi_pusat'];	
				$qry .=	" INNER JOIN tb_baphp_norangka ON tb_baphp_norangka.id_baphp = tb_baphp_reguler.id	
				AND tb_baphp_norangka.id_norangka=tb_norangka.id";	
			}
			if(!empty($param['id_alokasi_persediaan_pusat'])){
				$qry .=	" INNER JOIN tb_baphp_persediaan ON tb_baphp_persediaan.id_alokasi_persediaan_pusat = ".$param['id_alokasi_persediaan_pusat'];	
				$qry .=	" INNER JOIN tb_baphp_persediaan_norangka ON tb_baphp_persediaan_norangka.id_baphp_persediaan = tb_baphp_persediaan.id 
				AND tb_baphp_persediaan_norangka.id_norangka=tb_norangka.id";	
			}
			$qry .=	" WHERE 
				tb_norangka.id NOT IN (SELECT id_norangka FROM tb_bastb_pusat_norangka)
				AND tb_norangka.id NOT IN (SELECT id_norangka FROM tb_bastb_persediaan_norangka)
				";
				if (!empty($param['id_kontrak_pusat'])) $qry .= " and tb_norangka.id_kontrak_pusat = ".$param['id_kontrak_pusat'];
				if (isset($param['filter'])) $qry .= $param['filter'];
				if (isset($param['order'])) $qry .= " ORDER BY ".$param['order']." ".$param['dir'];
				if (isset($param['length']) && $param['length'] > 0) $qry .=	' LIMIT ' . $param['start'] . ',' . $param['length'];

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
			$this->db->insert('tb_norangka',$data);
		}

		function update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_norangka', $data);
		}

		function destroy($id)
		{
			$this->db->delete('tb_norangka', array('id' => $id));
		}	

		function get_nomesin(){
			$qry =	"SELECT nomesin FROM tb_norangka";
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function get_norangka(){
			$qry =	"SELECT norangka FROM tb_norangka";
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function get_used($id_bapb){
			$qry =	"SELECT count(1) FROM tb_norangka INNER JOIN tb_baphp_norangka ON tb_norangka.id=tb_baphp_norangka.id_norangka
			 WHERE tb_norangka.id_bapb = $id_bapb";
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}
	}
?>