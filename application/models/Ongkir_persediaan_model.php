<?php
	class Ongkir_persediaan_model extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function get($id = null, $id_baphp_persediaan = null, $start = null, $length = null, $col = null, $dir = null, $filter = null, $search = null)
		{
			if(empty($col)) $col = 'tb_ongkir_persediaan.created_at';
			if(empty($dir)) $dir = 'DESC';
			$qry =	"	
				SELECT tb_ongkir_persediaan.id, id_baphp_persediaan, id_kontrak_ongkir, nomor, tb_ongkir_persediaan.ongkir, keterangan, nomor_surat_permohonan, tanggal_surat_permohonan, 
				nomor_surat_ke_penyedia, tanggal_surat_ke_penyedia, nomor_surat_ke_dinas, tanggal_surat_ke_dinas, tb_ongkir_persediaan.nama_file,
				nama_penyedia_pusat, no_baphp, tb_baphp_persediaan.tanggal as tanggal_baphp, 
				tb_kontrak_pusat.nama_barang, tb_kontrak_pusat.merk, tb_kontrak_pusat.jumlah_barang, tb_kontrak_pusat.nilai_barang
				FROM 
				tb_ongkir_persediaan
				INNER JOIN tb_kontrak_ongkir ON tb_kontrak_ongkir.id=tb_ongkir_persediaan.id_kontrak_ongkir
				INNER JOIN tb_baphp_persediaan ON tb_baphp_persediaan.id=tb_ongkir_persediaan.id_baphp_persediaan
				INNER JOIN tb_alokasi_persediaan_pusat ON tb_alokasi_persediaan_pusat.id=tb_baphp_persediaan.id_alokasi_persediaan_pusat
				INNER JOIN tb_alokasi_pusat ON tb_alokasi_pusat.id=tb_alokasi_persediaan_pusat.id_alokasi
				INNER JOIN tb_kontrak_pusat ON tb_kontrak_pusat.id=tb_alokasi_pusat.id_kontrak_pusat
				INNER JOIN tb_penyedia_pusat ON tb_penyedia_pusat.id=tb_kontrak_pusat.id_penyedia_pusat
				WHERE 1=1
				";
			// $qry =	"	
			// 	SELECT tb_ongkir_persediaan.id, tb_kontrak_ongkir.nomor, tb_kontrak_ongkir.tanggal, ekspedisi, tb_kontrak_ongkir.ongkir as kontrak_ongkir,
			// 	no_baphp, tb_baphp_persediaan.tanggal as tanggal_baphp,
			// 	nama_provinsi, nama_kabupaten,
			// 	nama_barang, merk,
			// 	tb_ongkir_persediaan.ongkir, nomor_surat_permohonan, tanggal_surat_permohonan,
			// 	nomor_surat_ke_penyedia, tanggal_surat_ke_penyedia, nomor_surat_ke_dinas, tanggal_surat_ke_dinas, keterangan
			// 	FROM tb_ongkir_persediaan
			// 	INNER JOIN tb_kontrak_ongkir ON tb_ongkir_persediaan.id_kontrak_ongkir=tb_kontrak_ongkir.id
			// 	INNER JOIN tb_baphp_persediaan ON tb_ongkir_persediaan.id_baphp_persediaan=tb_baphp_persediaan.id
			// 	INNER JOIN tb_provinsi ON tb_baphp_persediaan.id_provinsi_penerima=tb_provinsi.id
			// 	INNER JOIN tb_kabupaten ON tb_baphp_persediaan.id_kabupaten_penerima=tb_kabupaten.id
			// 	WHERE 1=1
			// 	";
				if (isset($id_baphp_persediaan)) $qry .= " and id_baphp_persediaan = $id_baphp_persediaan ";
				if (isset($id)) $qry .= " and tb_ongkir_persediaan.id = $id ";
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
			$this->db->insert('tb_ongkir_persediaan',$data);
		}

		function update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_ongkir_persediaan', $data);
		}

		function destroy($id)
		{
			$this->db->delete('tb_ongkir_persediaan', array('id' => $id));
		}	

		function total_nilai($id_baphp_persediaan = null, $id_kontrak_ongkir = null)
		{
			$qry =	"	
			SELECT sum(`ongkir`) as total
			FROM 
			tb_ongkir_persediaan
			WHERE 1=1
			";
			if (isset($id_baphp_persediaan)) $qry .= " and id_baphp_persediaan = $id_baphp_persediaan ";
			if (isset($id_kontrak_ongkir)) $qry .= " and id_kontrak_ongkir = $id_kontrak_ongkir ";
		
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return 0;
		}
	}
?>