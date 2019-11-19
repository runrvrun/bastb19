<?php
	class Laporan_pemanfaatan_persediaan_model extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function get($id = null, $param = null)
		{
			if(!isset($param['filter'])) $param['filter']=null;
			if(!isset($param['start'])) $param['start']=null;
			if(!isset($param['length'])) $param['length']=null;
			if(empty($param['col'])) $param['col'] = 'tb_laporan_pemanfaatan_persediaan.created_at';
			if(empty($param['dir'])) $param['dir'] = 'DESC';
			$qry =	"	
			SELECT 
				tb_laporan_pemanfaatan_persediaan.id as id, id_bastb_persediaan, no_bastb, tb_bastb_persediaan.pihak_penerima, nama_provinsi, nama_kabupaten,
				tb_laporan_pemanfaatan_persediaan.periode_mulai, tb_laporan_pemanfaatan_persediaan.periode_selesai, total_area, keterangan, kondisi, perawatan, tanggal_laporan, 
				pengguna, tb_laporan_pemanfaatan_persediaan.nama_file
			FROM tb_laporan_pemanfaatan_persediaan
			INNER JOIN tb_bastb_persediaan ON tb_laporan_pemanfaatan_persediaan.id_bastb_persediaan=tb_bastb_persediaan.id
			INNER JOIN tb_alokasi_persediaan_pusat ON tb_bastb_persediaan.id_alokasi_persediaan_pusat=tb_alokasi_persediaan_pusat.id
			INNER JOIN tb_alokasi_pusat ON tb_alokasi_persediaan_pusat.id_alokasi=tb_alokasi_pusat.id
			INNER JOIN tb_kontrak_pusat oN tb_alokasi_pusat.id_kontrak_pusat=tb_kontrak_pusat.id
			INNER JOIN tb_provinsi ON tb_bastb_persediaan.id_provinsi_penerima=tb_provinsi.id
			INNER JOIN tb_kabupaten ON tb_bastb_persediaan.id_kabupaten_penerima=tb_kabupaten.id
			WHERE 1=1 ";
				if (isset($param['id_bastb_persediaan'])) $qry .= " and tb_laporan_pemanfaatan_persediaan.id_bastb_persediaan = ".$param['id_bastb_persediaan'];
				if (isset($id)) $qry .= " and tb_laporan_pemanfaatan_persediaan.id =". $id;
				if (isset($param['id_kontrak_pusat'])) $qry .= " and tb_kontrak_pusat.id =". $param['id_kontrak_pusat'];
				if(isset($param['filter'])) $qry .= $param['filter'];
				$qry .= " ORDER BY ".$param['col']." ".$param['dir'];
			if(isset($param['length']) && $param['length'] > 0)
				$qry .=	' LIMIT ' . $param['start'] . ',' . $param['length'];
					//echo $qry;exit();
					// if(!empty($filter)) {echo $qry;exit();}
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
			$this->db->insert('tb_laporan_pemanfaatan_persediaan',$data);
		}

		function update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_laporan_pemanfaatan_persediaan bap',$data);
		}

		function destroy($id)
		{
			$this->db->delete('tb_laporan_pemanfaatan_persediaan', array('id' => $id));
		}
	}
?>