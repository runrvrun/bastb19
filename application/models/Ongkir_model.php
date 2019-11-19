<?php
	class Ongkir_model extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function get($id = null, $id_baphp = null, $start = null, $length = null, $col = null, $dir = null, $filter = null, $search = null)
		{
			if(empty($col)) $col = 'tb_ongkir.created_at';
			if(empty($dir)) $dir = 'DESC';
			$qry =	"	
				SELECT tb_ongkir.id, id_baphp, id_kontrak_ongkir, ongkir, keterangan, nomor_surat_permohonan, tanggal_surat_permohonan, 
				nomor_surat_ke_penyedia, tanggal_surat_ke_penyedia, nomor_surat_ke_dinas, tanggal_surat_ke_dinas, tb_ongkir.nama_file,
				nama_penyedia_pusat, no_baphp, tb_baphp_reguler.tanggal as tanggal_baphp, 
				tb_kontrak_pusat.nama_barang, tb_kontrak_pusat.merk, tb_kontrak_pusat.jumlah_barang, tb_kontrak_pusat.nilai_barang
				FROM 
				tb_ongkir
				INNER JOIN tb_baphp_reguler ON tb_baphp_reguler.id=tb_ongkir.id_baphp
				INNER JOIN tb_alokasi_pusat ON tb_alokasi_pusat.id=tb_baphp_reguler.id_alokasi_pusat
				INNER JOIN tb_kontrak_pusat ON tb_kontrak_pusat.id=tb_alokasi_pusat.id_kontrak_pusat
				INNER JOIN tb_penyedia_pusat ON tb_penyedia_pusat.id=tb_kontrak_pusat.id_penyedia_pusat
				WHERE 1=1
				";
				if (isset($id_baphp)) $qry .= " and id_baphp = $id_baphp ";
				if (isset($id)) $qry .= " and tb_ongkir.id = $id ";
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
			$this->db->insert('tb_ongkir',$data);
		}

		function update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_ongkir', $data);
		}

		function destroy($id)
		{
			$this->db->delete('tb_ongkir', array('id' => $id));
		}	

		function total_nilai($id_baphp)
		{
			$qry =	"	
			SELECT sum(`ongkir`) as total
			FROM 
			tb_ongkir
			WHERE 1=1
			";
			if (isset($id_baphp)) $qry .= " and id_baphp = $id_baphp ";
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return 0;
		}
	}
?>