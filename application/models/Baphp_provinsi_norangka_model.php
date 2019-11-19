<?php
	class Baphp_provinsi_norangka_model extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function get($id = null, $param = null)
		{
			if(empty($param['col'])) $param['col'] = 'tb_baphp_provinsi_norangka.created_at';
			if(empty($param['dir'])) $param['dir'] = 'DESC';
			$qry =	"	
				SELECT tb_baphp_provinsi_norangka.id, id_baphp_provinsi, 
				tb_baphp_provinsi.id_alokasi_provinsi, norangka, nomesin, 
				tb_kontrak_provinsi.nama_barang, tb_kontrak_provinsi.merk, tb_kontrak_provinsi.jumlah_barang, tb_kontrak_provinsi.nilai_barang
				FROM 
				tb_baphp_provinsi_norangka
				INNER JOIN tb_baphp_provinsi ON tb_baphp_provinsi.id=tb_baphp_provinsi_norangka.id_baphp_provinsi
				INNER JOIN tb_alokasi_provinsi ON tb_alokasi_provinsi.id=tb_baphp_provinsi.id_alokasi_provinsi
				INNER JOIN tb_kontrak_provinsi ON tb_kontrak_provinsi.id=tb_alokasi_provinsi.id_kontrak_provinsi
				INNER JOIN tb_penyedia_provinsi ON tb_penyedia_provinsi.id=tb_kontrak_provinsi.id_penyedia_provinsi
				WHERE 1=1
				";
				if (isset($param['id_baphp_provinsi'])) $qry .= " and id_baphp_provinsi = ".$param['id_baphp_provinsi'];
				if (isset($id)) $qry .= " and tb_baphp_provinsi_norangka.id = ".$id;
				if (isset($param['filter'])) $qry .= $param['filter'];				
				$qry .= " 
				ORDER BY ".$param['col']." ".$param['dir'];
			if(isset($param['length']) && ($param['length'] > 0))
				$qry .=	' LIMIT ' . $param['start'] . ',' . $param['length'];

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
			$this->db->insert('tb_baphp_provinsi_norangka',$data);
		}

		function update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_baphp_provinsi_norangka', $data);
		}

		function destroy($id)
		{
			$this->db->delete('tb_baphp_provinsi_norangka', array('id' => $id));
		}	

		function get_nomesin(){
			$qry =	"SELECT nomesin FROM tb_baphp_provinsi_norangka";
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		function get_norangka(){
			$qry =	"SELECT norangka FROM tb_baphp_provinsi_norangka";
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();
		}

		
		function get_unselbastb($id = null, $param = null)
		{
			if(!isset($param['order'])) $param['order'] = 'tb_baphp_provinsi_norangka.norangka';
			if(!isset($param['dir'])) $param['dir'] = 'DESC';
			$qry =	"	
				SELECT DISTINCT tb_baphp_provinsi_norangka.id, tb_baphp_provinsi_norangka.id_kontrak_provinsi, norangka, nomesin
				FROM 
				tb_baphp_provinsi_norangka				
				INNER JOIN tb_kontrak_provinsi ON tb_kontrak_provinsi.id=tb_baphp_provinsi_norangka.id_kontrak_provinsi 
				INNER JOIN tb_baphp_provinsi ON tb_baphp_provinsi_norangka.id_baphp_provinsi=tb_baphp_provinsi.id";			
			$qry .=	" WHERE 
				norangka NOT IN (SELECT norangka FROM tb_bastb_provinsi_norangka)
				";
				if (!empty($param['id_alokasi_provinsi'])) $qry .= " and tb_baphp_provinsi.id_alokasi_provinsi = ".$param['id_alokasi_provinsi'];
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

	}
?>