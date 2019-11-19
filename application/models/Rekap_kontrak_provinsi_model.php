<?php
	class Rekap_kontrak_provinsi_model extends CI_Model
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
			if(empty($param['col'])) $param['col'] = '1';
			if(empty($param['dir'])) $param['dir'] = 'ASC';
			$qry =	"	
			SELECT 
				tb_kontrak_provinsi.no_kontrak, concat(DATE_FORMAT(periode_mulai,'%d-%m-%Y'),' - ',DATE_FORMAT(periode_selesai,'%d-%m-%Y')) as periode, tb_kontrak_provinsi.nama_barang, 
				tb_kontrak_provinsi.merk, nama_penyedia_provinsi, tb_kontrak_provinsi.jumlah_barang, tb_kontrak_provinsi.nilai_barang,
				nama_provinsi, nama_kabupaten, 
				tb_bapb_provinsi.nomor as no_bapb, DATE_FORMAT(tb_bapb_provinsi.tanggal,'%d-%m-%Y') as tanggal_bapb,
				tb_baphp_provinsi.no_baphp as no_baphp_provinsi, DATE_FORMAT(tb_baphp_provinsi.tanggal,'%d-%m-%Y') as tanggal_baphp_provinsi,
				tb_baphp_provinsi.no_batitip as no_batitip, DATE_FORMAT(tb_baphp_provinsi.tanggal_batitip,'%d-%m-%Y') as tanggal_batitip,
				tb_baphp_provinsi.no_bart, DATE_FORMAT(tb_baphp_provinsi.tanggal_bart,'%d-%m-%Y') as tanggal_bart,
				1 AS jumlah_barang_rev,
				tb_bastb_provinsi.nilai_barang/tb_bastb_provinsi.jumlah_barang AS nilai_barang_rev,
				tb_bastb_provinsi_norangka.norangka as no_rangka, tb_bastb_provinsi_norangka.nomesin as no_mesin,				
				tb_baphp_provinsi.no_sp2d, DATE_FORMAT(tb_baphp_provinsi.tanggal_sp2d,'%d-%m-%Y') as tanggal_sp2d,	
				no_spm, IF(tanggal_spm>'1900-01-01',DATE_FORMAT(tanggal_spm,'%d-%m-%Y'),'') as tanggal_spm,		
				tb_bastb_provinsi.no_bastb  as no_bastb, DATE_FORMAT(tb_bastb_provinsi.tanggal,'%d-%m-%Y') as tanggal_bastb, 
				tb_bastb_provinsi.kelompok_penerima, 
				tb_bastb_provinsi.pihak_penerima, 
				tb_bastb_provinsi.nama_penerima, 
				tb_bastb_provinsi.nik_penerima, 
				nama_kecamatan  as kecamatan_penerima, 
				nama_kelurahan as kelurahan_penerima, 
				tb_bastb_provinsi.notelp_penerima									
				FROM
				tb_kontrak_provinsi
				INNER JOIN tb_penyedia_provinsi ON tb_kontrak_provinsi.id_penyedia_provinsi=tb_penyedia_provinsi.id
				LEFT JOIN tb_bapb_provinsi ON tb_kontrak_provinsi.id = tb_bapb_provinsi.id_kontrak_provinsi
				LEFT JOIN tb_alokasi_provinsi ON tb_kontrak_provinsi.id = tb_alokasi_provinsi.id_kontrak_provinsi
				LEFT JOIN tb_bastb_provinsi ON tb_bastb_provinsi.id_alokasi_provinsi = tb_alokasi_provinsi.id
				INNER JOIN tb_bastb_provinsi_norangka ON tb_bastb_provinsi_norangka.id_bastb_provinsi = tb_bastb_provinsi.id
				LEFT JOIN tb_baphp_provinsi ON tb_baphp_provinsi.id_alokasi_provinsi = tb_alokasi_provinsi.id
				LEFT JOIN tb_provinsi ON tb_bastb_provinsi.id_provinsi_penyerah = tb_provinsi.id
				LEFT JOIN tb_kabupaten ON tb_bastb_provinsi.id_kabupaten_penyerah = tb_kabupaten.id
				LEFT JOIN tb_kecamatan ON tb_bastb_provinsi.id_provinsi_penerima = tb_kecamatan.id
				LEFT JOIN tb_kelurahan ON tb_bastb_provinsi.id_kabupaten_penerima = tb_kelurahan.id
				WHERE 1=1 ";
				if (isset($param['id_kontrak_provinsi'])) $qry .= " and tb_kontrak_provinsi.id = ".$param['id_kontrak_provinsi'];
				if(isset($param['filter'])) $qry .= $param['filter'];
				$qry .= " ORDER BY ".$param['col']." ".$param['dir'];
			if(isset($param['length']) && $param['length'] > 0)
				$qry .=	' LIMIT ' . $param['start'] . ',' . $param['length'];
					//echo $qry;exit();
					// if(!empty($filter)) {echo $qry;exit();}
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->result();
			else
				return array();

			return array();
		}

		
	}
?>