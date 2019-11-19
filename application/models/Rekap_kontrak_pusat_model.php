<?php
	class Rekap_kontrak_pusat_model extends CI_Model
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
				tb_kontrak_pusat.no_kontrak, concat(DATE_FORMAT(periode_mulai,'%d-%m-%Y'),' - ',DATE_FORMAT(periode_selesai,'%d-%m-%Y')) as periode, tb_kontrak_pusat.nama_barang, 
				tb_kontrak_pusat.merk, nama_penyedia_pusat, tb_kontrak_pusat.jumlah_barang, tb_kontrak_pusat.nilai_barang,
				IFNULL(p2.nama_provinsi, p1.nama_provinsi) nama_provinsi, IFNULL(k2.nama_kabupaten, k1.nama_kabupaten) nama_kabupaten, 
				tb_bapb.nomor as no_bapb, DATE_FORMAT(tb_bapb.tanggal,'%d-%m-%Y') as tanggal_bapb,
				IFNULL(tb_baphp_reguler.no_baphp,br.no_baphp) as no_baphp_reguler, IFNULL(DATE_FORMAT(tb_baphp_reguler.tanggal,'%d-%m-%Y'),DATE_FORMAT(br.tanggal,'%d-%m-%Y')) as tanggal_baphp_reguler,
				IFNULL(tb_baphp_reguler.no_batitip,br.no_batitip) as no_batitip, IFNULL(DATE_FORMAT(tb_baphp_reguler.tanggal_batitip,'%d-%m-%Y'),DATE_FORMAT(br.tanggal_batitip,'%d-%m-%Y')) as tanggal_batitip,
				tb_baphp_persediaan.no_baphp as no_baphp_persediaan, DATE_FORMAT(tb_baphp_persediaan.tanggal,'%d-%m-%Y') as tanggal_baphp_persediaan,
				IFNULL(tb_baphp_reguler.no_bart,tb_baphp_persediaan.no_bart) as no_bart, IFNULL(DATE_FORMAT(tb_baphp_reguler.tanggal_bart,'%d-%m-%Y'),DATE_FORMAT(tb_baphp_persediaan.tanggal_bart,'%d-%m-%Y')) as tanggal_bart,
				1 AS jumlah_barang_rev,
				IFNULL(tb_bastb_pusat.nilai_barang/tb_bastb_pusat.jumlah_barang,tb_bastb_persediaan.nilai_barang/tb_bastb_persediaan.jumlah_barang) AS nilai_barang_rev,
				tb_norangka.norangka as no_rangka, tb_norangka.nomesin as no_mesin,				
				tb_sp2d.nomor as no_sp2d, DATE_FORMAT(tb_sp2d.tanggal,'%d-%m-%Y') as tanggal_sp2d, FORMAT(nilai_sebelum_pajak,0) as nilai_sebelum_pajak, FORMAT(nilai_setelah_pajak,0) as nilai_setelah_pajak, tb_sp2d.keterangan as termin,	
				no_spm, IF(tanggal_spm>'1900-01-01',DATE_FORMAT(tanggal_spm,'%d-%m-%Y'),'') as tanggal_spm,		
				IFNULL(tb_bastb_pusat.no_bastb,tb_bastb_persediaan.no_bastb)  as no_bastb, IFNULL(DATE_FORMAT(tb_bastb_pusat.tanggal,'%d-%m-%Y'),DATE_FORMAT(tb_bastb_persediaan.tanggal,'%d-%m-%Y')) as tanggal_bastb, 
				IFNULL(tb_bastb_pusat.kelompok_penerima,tb_bastb_persediaan.kelompok_penerima) as kelompok_penerima, 
				IFNULL(tb_bastb_pusat.pihak_penerima,tb_bastb_persediaan.pihak_penerima) as pihak_penerima, 
				IFNULL(tb_bastb_pusat.nama_penerima,tb_bastb_persediaan.nama_penerima) as nama_penerima, 
				IFNULL(tb_bastb_pusat.nik_penerima,tb_bastb_persediaan.nik_penerima) as nik_penerima, 
				IFNULL(kc1.nama_kecamatan,kc2.nama_kecamatan) as kecamatan_penerima, 
				IFNULL(kl1.nama_kelurahan,kl2.nama_kelurahan) as kelurahan_penerima, 
				IFNULL(tb_bastb_pusat.notelp_penerima,tb_bastb_persediaan.notelp_penerima) as notelp_penerima									
				FROM
				tb_kontrak_pusat
				INNER JOIN tb_norangka ON tb_norangka.id_kontrak_pusat=tb_kontrak_pusat.id
				INNER JOIN tb_bapb ON tb_norangka.id_bapb=tb_bapb.id
				INNER JOIN tb_penyedia_pusat ON tb_kontrak_pusat.id_penyedia_pusat=tb_penyedia_pusat.id
				LEFT JOIN tb_bastb_pusat_norangka ON tb_bastb_pusat_norangka.id_norangka = tb_norangka.id
				LEFT JOIN tb_bastb_persediaan_norangka ON tb_bastb_persediaan_norangka.id_norangka = tb_norangka.id
				LEFT JOIN tb_bastb_pusat ON tb_bastb_pusat.id = tb_bastb_pusat_norangka.id_bastb_pusat
				LEFT JOIN tb_bastb_persediaan ON tb_bastb_persediaan.id = tb_bastb_persediaan_norangka.id_bastb_persediaan
				LEFT JOIN tb_provinsi p1 ON tb_bastb_pusat.id_provinsi_penyerah = p1.id
				LEFT JOIN tb_kabupaten k1 ON tb_bastb_pusat.id_kabupaten_penyerah = k1.id
				LEFT JOIN tb_provinsi p2 ON tb_bastb_persediaan.id_provinsi_penyerah = p2.id
				LEFT JOIN tb_kabupaten k2 ON tb_bastb_persediaan.id_kabupaten_penyerah = k2.id
				LEFT JOIN tb_kecamatan kc1 ON tb_bastb_pusat.id_provinsi_penerima = kc1.id
				LEFT JOIN tb_kelurahan kl1 ON tb_bastb_pusat.id_kabupaten_penerima = kl1.id
				LEFT JOIN tb_kecamatan kc2 ON tb_bastb_persediaan.id_kecamatan_penerima = kc2.id
				LEFT JOIN tb_kelurahan kl2 ON tb_bastb_persediaan.id_kelurahan_penerima = kl2.id
				LEFT JOIN tb_baphp_norangka ON tb_baphp_norangka.id_norangka = tb_norangka.id
				LEFT JOIN tb_baphp_reguler ON tb_baphp_reguler.id = tb_baphp_norangka.id_baphp
				LEFT JOIN tb_baphp_persediaan_norangka ON tb_baphp_persediaan_norangka.id_norangka = tb_norangka.id
				LEFT JOIN tb_baphp_persediaan ON tb_baphp_persediaan.id = tb_baphp_persediaan_norangka.id_baphp_persediaan
				LEFT JOIN tb_alokasi_persediaan_pusat alp ON tb_baphp_persediaan.id_alokasi_persediaan_pusat = alp.id
				LEFT JOIN tb_alokasi_pusat ap ON alp.id_alokasi = ap.id
				LEFT JOIN tb_baphp_reguler br ON br.id_alokasi_pusat = ap.id
				LEFT JOIN tb_sp2d ON tb_sp2d.id_kontrak_pusat = tb_kontrak_pusat.id
				WHERE 1=1 ";
				if (isset($param['id_kontrak_pusat'])) $qry .= " and tb_kontrak_pusat.id = ".$param['id_kontrak_pusat'];
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