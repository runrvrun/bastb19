<?php
	class Bart_model extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function get($id = null, $id_kontrak_pusat = null, $start = null, $length = null, $col = null, $dir = null, $filter = null, $search = null)
		{
			if(empty($col)) $col = 'tb_bart.created_at';
			if(empty($dir)) $dir = 'DESC';
			$qry =	"	
				SELECT tb_bart.id, id_kontrak_pusat, `tanggal`, `nomor`, `lokasi_pemeriksaan`, `jumlah_unit`, tb_bart.nama_file,				
				tb_kontrak_pusat.nama_barang, tb_kontrak_pusat.merk, tb_kontrak_pusat.jumlah_barang, tb_kontrak_pusat.nilai_barang,
				nama_penyedia_pusat
				FROM 
				tb_bart
				INNER JOIN tb_kontrak_pusat ON tb_bart.id_kontrak_pusat = tb_kontrak_pusat.id
				INNER JOIN tb_penyedia_pusat ON tb_penyedia_pusat.id=tb_kontrak_pusat.id_penyedia_pusat
			WHERE 1=1
				";
				if (isset($id_kontrak_pusat)) $qry .= " and id_kontrak_pusat = $id_kontrak_pusat ";
				if (isset($id)) $qry .= " and tb_bart.id = $id ";
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
			$this->db->insert('tb_bart',$data);
		}

		function update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_bart', $data);
		}

		function destroy($id)
		{
			$this->db->delete('tb_bart', array('id' => $id));
		}	

		function total_unit($id_kontrak_pusat)
		{
			$qry =	"	
			SELECT sum(`jumlah_unit`) as total
			FROM 
			tb_bart
			WHERE 1=1
			";
			if (isset($id_kontrak_pusat)) $qry .= " and id_kontrak_pusat = $id_kontrak_pusat ";
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return 0;
		}
	}
?>