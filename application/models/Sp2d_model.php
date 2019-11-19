<?php
	class Sp2d_model extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function get($id = null, $id_kontrak_pusat = null, $start = null, $length = null, $col = null, $dir = null, $filter = null, $search = null)
		{
			if(empty($col)) $col = 'tb_sp2d.created_at';
			if(empty($dir)) $dir = 'DESC';
			$qry =	"	
				SELECT tb_sp2d.id, tb_sp2d.id_kontrak_pusat, tb_sp2d.`tanggal`, tb_sp2d.`nomor`, tb_sp2d.no_spm, tb_sp2d.tanggal_spm,
				`nilai_sebelum_pajak`, nilai_setelah_pajak, 
				keterangan, tb_sp2d.nama_file
				, sum(tb_baphp_reguler.jumlah_barang) total_jumlah_termin_alokasi			
				, sum(tb_baphp_reguler.nilai_barang) total_nilai_termin_alokasi
				, jumlah_termin, termin_1, termin_2, termin_3, termin_4, termin_5		
				FROM 
				tb_sp2d
				LEFT JOIN tb_alokasi_pusat ON tb_alokasi_pusat.id_kontrak_pusat = tb_sp2d.id_kontrak_pusat AND tb_alokasi_pusat.termin = tb_sp2d.keterangan
				LEFT JOIN tb_baphp_reguler ON tb_baphp_reguler.id_alokasi_pusat = tb_alokasi_pusat.id
				LEFT JOIN tb_kontrak_pusat ON tb_kontrak_pusat.id = tb_sp2d.id_kontrak_pusat
				WHERE 1=1 
				";
				if (isset($id_kontrak_pusat)) $qry .= " and tb_sp2d.id_kontrak_pusat = $id_kontrak_pusat ";
				if (isset($id)) $qry .= " and tb_sp2d.id = $id ";
				$qry .= $filter;
				$qry .= " 
				GROUP BY tb_sp2d.id, tb_sp2d.id_kontrak_pusat, tb_sp2d.`tanggal`, tb_sp2d.`nomor`, `nilai_sebelum_pajak`, nilai_setelah_pajak, 
				keterangan, tb_sp2d.nama_file
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

		function get_cetak_pengajuan($id = null, $param = null)
		{
			if(empty($param['col'])) $param['col'] = 'tb_sp2d.created_at';
			if(empty($param['dir'])) $param['dir'] = 'DESC';
			$qry =	"	
				SELECT
				no_baphp, tb_baphp_reguler.tanggal as tanggal_baphp,
				tb_sp2d.nomor, tb_sp2d.tanggal, tb_sp2d.nilai_sebelum_pajak, tb_sp2d.nilai_setelah_pajak, tb_sp2d.keterangan,
				tb_provinsi.nama_provinsi as provinsi_penerima, tb_kabupaten.nama_kabupaten as kabupaten_penerima, 
				tb_baphp_reguler.jumlah_barang as unit, tb_baphp_reguler.nilai_barang as nilai, 
				CASE 
				 WHEN tb_baphp_reguler.nama_file is not null THEN 'V'
				 ELSE 'X'
				END AS dokumen				
				FROM 
				tb_sp2d
				INNER JOIN tb_kontrak_pusat ON tb_sp2d.id_kontrak_pusat = tb_kontrak_pusat.id
				INNER JOIN tb_alokasi_pusat ON tb_alokasi_pusat.id_kontrak_pusat = tb_kontrak_pusat.id 
				INNER JOIN tb_baphp_reguler ON tb_baphp_reguler.id_alokasi_pusat = tb_alokasi_pusat.id 
				INNER JOIN tb_provinsi ON tb_provinsi.id = tb_baphp_reguler.id_provinsi_penerima
				INNER JOIN tb_kabupaten ON tb_kabupaten.id = tb_baphp_reguler.id_kabupaten_penerima
				WHERE 1=1
				";
				if (isset($param['id_kontrak_pusat'])) $qry .= " and tb_sp2d.id_kontrak_pusat = ".$param['id_kontrak_pusat'];
				if (isset($param['keterangan'])) $qry .= " and tb_sp2d.keterangan = '".$param['keterangan']."'";
				if (isset($id)) $qry .= " and tb_sp2d.id =". $id;
				if(isset($param['filter'])) $qry .= $param['filter'];
				$qry .= " 
				ORDER BY ".$param['col']." ".$param['dir'];
			if(isset($param['length']) && $param['length'] > 0)
				$qry .=	' LIMIT ' . $param['start'] . ',' . $param['length'];
				// var_dump($qry);exit();
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
			$this->db->insert('tb_sp2d',$data);
		}

		function update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_sp2d', $data);
		}

		function destroy($id)
		{
			$this->db->delete('tb_sp2d', array('id' => $id));
		}	

		function total_nilai($id_kontrak_pusat)
		{
			$qry =	"	
			SELECT sum(`nilai_sebelum_pajak`) as total
			FROM 
			tb_sp2d
			WHERE 1=1
			";
			if (isset($id_kontrak_pusat)) $qry .= " and id_kontrak_pusat = $id_kontrak_pusat ";
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return 0;
		}		

		public function termincek($termin = null, $id_kontrak_pusat = null){
			$qry =	"	
			SELECT keterangan FROM tb_sp2d WHERE 1=1 ";
			if (isset($termin)) $qry .= " and keterangan = '$termin' ";
			if (isset($id_kontrak_pusat)) $qry .= " and id_kontrak_pusat = $id_kontrak_pusat ";
		
			$res = $this->db->query($qry);

			return $res->result();
		}
	}
?>