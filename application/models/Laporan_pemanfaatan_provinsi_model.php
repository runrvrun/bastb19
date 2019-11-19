<?php
	class Laporan_pemanfaatan_provinsi_model extends CI_Model
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
			if(empty($param['col'])) $param['col'] = 'tb_laporan_pemanfaatan_provinsi.created_at';
			if(empty($param['dir'])) $param['dir'] = 'DESC';
			$qry =	"	
			SELECT 
				tb_laporan_pemanfaatan_provinsi.id as id, id_bastb_provinsi, no_bastb, tb_bastb_provinsi.pihak_penerima, nama_provinsi, nama_kabupaten,
				tb_laporan_pemanfaatan_provinsi.periode_mulai, tb_laporan_pemanfaatan_provinsi.periode_selesai, total_area, keterangan, kondisi, perawatan, tanggal_laporan, 
				pengguna, tb_laporan_pemanfaatan_provinsi.nama_file
			FROM tb_laporan_pemanfaatan_provinsi
			INNER JOIN tb_bastb_provinsi ON tb_laporan_pemanfaatan_provinsi.id_bastb_provinsi=tb_bastb_provinsi.id
			INNER JOIN tb_alokasi_provinsi ON tb_bastb_provinsi.id_alokasi_provinsi=tb_alokasi_provinsi.id
			INNER JOIN tb_kontrak_provinsi oN tb_alokasi_provinsi.id_kontrak_provinsi=tb_kontrak_provinsi.id
			INNER JOIN tb_provinsi ON tb_bastb_provinsi.id_provinsi_penerima=tb_provinsi.id
			INNER JOIN tb_kabupaten ON tb_bastb_provinsi.id_kabupaten_penerima=tb_kabupaten.id
			WHERE 1=1 ";
				if (isset($param['id_bastb_provinsi'])) $qry .= " and tb_laporan_pemanfaatan_provinsi.id_bastb_provinsi = ".$param['id_bastb_provinsi'];
				if (isset($id)) $qry .= " and tb_laporan_pemanfaatan_provinsi.id =". $id;
				if (isset($param['id_kontrak_provinsi'])) $qry .= " and tb_kontrak_provinsi.id =". $param['id_kontrak_provinsi'];
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
			$this->db->insert('tb_laporan_pemanfaatan_provinsi',$data);
		}

		function update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_laporan_pemanfaatan_provinsi bap',$data);
		}

		function destroy($id)
		{
			$this->db->delete('tb_laporan_pemanfaatan_provinsi', array('id' => $id));
		}
	}
?>