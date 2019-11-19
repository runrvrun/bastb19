<?php
	class Baphp_model extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function get($id = null, $id_alokasi = null,  $start = null, $length = null, $col = null, $dir = null, $filter = null, $search = null)
		{
			if(empty($col)) $col = 'tb_baphp_reguler.updated_at';
			if(empty($dir)) $dir = 'DESC';
			$qry =	"	
			SELECT 
				tb_baphp_reguler.id as id, tb_baphp_reguler.id_alokasi_pusat, tb_alokasi_pusat.id_kontrak_pusat,
				tb_baphp_reguler.tahun_anggaran, tb_baphp_reguler.titik_serah, tb_baphp_reguler.nama_wilayah,
				tb_baphp_reguler.no_baphp, tb_baphp_reguler.tanggal,
				peny.nama_penyedia_pusat as pihak_penyerah, nama_penyedia_pusat,
				tb_baphp_reguler.nama_penyerah,
				tb_baphp_reguler.jabatan_penyerah,
				tb_baphp_reguler.notelp_penyerah,
				tb_baphp_reguler.alamat_penyerah,
				tb_baphp_reguler.id_provinsi_penyerah, prov_peny.nama_provinsi as provinsi_penyerah,
				tb_baphp_reguler.id_kabupaten_penyerah, kab_peny.nama_kabupaten as kabupaten_penyerah,
				tb_baphp_reguler.pihak_penerima, 
				tb_baphp_reguler.nama_penerima,
				tb_baphp_reguler.jabatan_penerima,
				tb_baphp_reguler.notelp_penerima,
				tb_baphp_reguler.alamat_penerima,
				tb_baphp_reguler.id_provinsi_penerima, prov_pene.nama_provinsi as provinsi_penerima,
				tb_baphp_reguler.id_kabupaten_penerima, kab_pene.nama_kabupaten as kabupaten_penerima,
				tb_baphp_reguler.no_kontrak,
				tb_baphp_reguler.nama_barang, tb_baphp_reguler.merk, tb_baphp_reguler.jumlah_barang, tb_baphp_reguler.nilai_barang,
				IFNULL(tb_baphp_reguler.nilai_barang/NULLIF(tb_baphp_reguler.jumlah_barang,0),0) harga_satuan,
				tb_baphp_reguler.nama_file, tb_baphp_reguler.nama_filefoto,
				tb_baphp_reguler.nama_mengetahui,
				tb_baphp_reguler.jabatan_mengetahui, no_batitip, tanggal_batitip, no_bart, tanggal_bart,
				tb_alokasi_pusat.regcad
			FROM tb_baphp_reguler 
			INNER JOIN tb_alokasi_pusat ON tb_alokasi_pusat.id=tb_baphp_reguler.id_alokasi_pusat
			LEFT JOIN tb_penyedia_pusat peny ON tb_baphp_reguler.id_penyerah = peny.id
			LEFT JOIN tb_provinsi prov_peny ON tb_baphp_reguler.id_provinsi_penyerah = prov_peny.id
			LEFT JOIN tb_provinsi prov_pene ON tb_baphp_reguler.id_provinsi_penerima = prov_pene.id
			LEFT JOIN tb_kabupaten kab_peny ON tb_baphp_reguler.id_kabupaten_penyerah = kab_peny.id
			LEFT JOIN tb_kabupaten kab_pene ON tb_baphp_reguler.id_kabupaten_penerima = kab_pene.id
			WHERE `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
				if (isset($id_alokasi)) $qry .= " and tb_baphp_reguler.id_alokasi_pusat = $id_alokasi";
				if (isset($id)) $qry .= " and tb_baphp_reguler.id = $id";
				if (isset($this->session->userdata('logged_in')->id_provinsi)) $qry .= " and prov_pene.id = ".$this->session->userdata('logged_in')->id_provinsi;
				$qry .= $filter."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " 
				and peny.id = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "");
				if (isset($id)) $qry .= " and tb_baphp_reguler.id = $id";
				$qry .= $filter;
				$qry .= "
				ORDER BY ".$col." ".$dir;
			if($length > 0)
				$qry .=	' LIMIT ' . $start . ',' . $length;
					// var_dump($qry);exit();
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

		function getFile($param = null)
		{
			$qry =	"	
			SELECT 				
				tb_baphp_reguler.nama_file, tb_baphp_reguler.nama_filefoto
			FROM tb_baphp_reguler 
			INNER JOIN tb_alokasi_pusat ON tb_alokasi_pusat.id=tb_baphp_reguler.id_alokasi_pusat
			INNER JOIN tb_kontrak_pusat ON tb_alokasi_pusat.id_kontrak_pusat=tb_kontrak_pusat.id
			WHERE 1=1 ";
			if (isset($param['id_kontrak_pusat'])) $qry .= " and tb_kontrak_pusat.id =". $param['id_kontrak_pusat'];
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
			if($data['regcad'] == 'REGULER'){
				unset($data['regcad']);				
				$this->db->insert('tb_baphp_reguler',$data);
			}else{
				unset($data['regcad']);				
				$this->db->insert('tb_baphp_persediaan',$data);
			}
		}

		function update($id, $data)
		{
			$this->db->where('id', $id);
			if(($data['regcad'] == 'CADANGAN') || ($data['regcad'] == 'PERSEDIAAN')){
				unset($data['regcad']);				
				$this->db->update('tb_baphp_persediaan',$data);
			}else{
				unset($data['regcad']);				
				$this->db->update('tb_baphp_reguler',$data);
			}
		}

		function destroy($id)
		{
			// if($regcad == 'REGULER'){
				$this->db->delete('tb_baphp_reguler', array('id' => $id));
			// }else{
				// $this->db->delete('tb_baphp_persediaan', array('id' => $id));
			// }
		}

		function total_unit($id_alokasi = null, $id_kontrak_pusat = null, $addwhere = null)
		{
			$qry =	"	
				SELECT SUM(dt.jumlah_barang) AS total
				FROM tb_baphp_reguler dt 
				INNER JOIN tb_alokasi_pusat al ON al.id = dt.id_alokasi_pusat
				INNER JOIN tb_kontrak_pusat hd ON hd.id = al.id_kontrak_pusat
				WHERE hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan; 
			if (isset($id_alokasi)) $qry .= " and dt.id_alokasi_pusat = $id_alokasi";
			if (isset($id_kontrak_pusat)) $qry .= " and al.id_kontrak_pusat = $id_kontrak_pusat";
			if (isset($addwhere)) $qry .= " and $addwhere";
			$qry .= (isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and al.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and al.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "");
			if (isset($id_alokasi)) $qry .= " GROUP BY dt.id_alokasi_pusat";
			if (isset($id_kontrak_pusat)) $qry .= " GROUP BY al.id_kontrak_pusat";
			// echo $qry;exit();
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return 0;
		}

		function total_nilai($id_alokasi = null, $id_kontrak_pusat = null)
		{
			$qry =	"	
				SELECT SUM(dt.nilai_barang) AS total
				FROM tb_baphp_reguler dt 
				INNER JOIN tb_alokasi_pusat al ON al.id = dt.id_alokasi_pusat
				INNER JOIN tb_kontrak_pusat hd ON hd.id = al.id_kontrak_pusat
				WHERE hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
			if (isset($id_alokasi)) $qry .= " and dt.id_alokasi_pusat = $id_alokasi";
			if (isset($id_kontrak_pusat)) $qry .= " and al.id_kontrak_pusat = $id_kontrak_pusat";
			$qry .= (isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and hd.id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
					".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and al.id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi : "")."
					".(isset($this->session->userdata('logged_in')->id_kabupaten) ? " and al.id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten : "");
			if (isset($id_alokasi)) $qry .= " GROUP BY dt.id_alokasi_pusat";
			if (isset($id_kontrak_pusat)) $qry .= " GROUP BY al.id_kontrak_pusat";
	
			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return 0;
		}
	}
?>