<?php
	class Kontrak_pusat_model extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}

		function get($id = null, $start = null, $length = null, $col = null, $dir = null, $filter = null, $search = null)
		{
			if(empty($col)) $col = 'hd.created_at';
			if(empty($dir)) $dir = 'DESC';
			$qry =	"
				SELECT hd.id, hd.tahun_anggaran, peny.nama_penyedia_pusat, hd.no_kontrak, hd.periode_mulai, hd.periode_selesai, 
				hd.nama_barang, hd.merk, hd.jumlah_barang, hd.nilai_barang, hd.nama_file, 
				t.jumlah_alokasi, t.nilai_alokasi, hd.id_penyedia_pusat, jumlah_termin, uang_muka, termin_1, termin_2, 
				termin_3, termin_4, termin_5
				FROM tb_kontrak_pusat hd INNER JOIN tb_penyedia_pusat peny ON hd.`id_penyedia_pusat` = peny.id
				LEFT JOIN (
					SELECT id_header, SUM(total_jumlah_temp) AS jumlah_alokasi, SUM(total_nilai_temp) AS nilai_alokasi FROM (
						SELECT hd.id AS id_header, dt.status_alokasi, (CASE 	
							WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
								SUM(dt.jumlah_barang_rev_1)
							WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
								SUM(dt.jumlah_barang_rev_2)
								WHEN dt.status_alokasi = 'DATA ADDENDUM 3' THEN
									SUM(dt.jumlah_barang_rev_3)
							WHEN dt.status_alokasi = 'DATA KONTRAK AWAL' THEN
								SUM(dt.jumlah_barang)
							ELSE
								0
							END) AS total_jumlah_temp,
							(CASE 	
							WHEN dt.status_alokasi = 'DATA ADDENDUM 1' THEN
								SUM(dt.nilai_barang_rev_1)
							WHEN dt.status_alokasi = 'DATA ADDENDUM 2' THEN
								SUM(dt.nilai_barang_rev_2)
								WHEN dt.status_alokasi = 'DATA ADDENDUM 3' THEN
									SUM(dt.nilai_barang_rev_3)
							WHEN dt.status_alokasi = 'DATA KONTRAK AWAL' THEN
								SUM(dt.nilai_barang)
							ELSE
								0
							END) AS total_nilai_temp
					FROM tb_alokasi_pusat dt 
					LEFT JOIN tb_kontrak_pusat hd ON hd.id = dt.id_kontrak_pusat
					WHERE 1=1";					
					$qry .= " GROUP BY id_header, dt.status_alokasi) AS temp
					GROUP BY temp.id_header
				) AS t ON hd.id = t.id_header
				WHERE hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;
				if (isset($id)) $qry .= " and hd.id = $id";
				$qry .= " and
				(
					hd.`no_kontrak` LIKE '%".$search."%' 
					or peny.nama_penyedia_pusat LIKE '%".$search."%' 
					or hd.`merk` LIKE '%".$search."%' 
					or hd.nama_barang LIKE '%".$search."%' 
				)
				".$filter."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and peny.id = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "")."
				".((isset($this->session->userdata('logged_in')->id_provinsi) or isset($this->session->userdata('logged_in')->id_kabupaten)) ? " AND t.id_header IS NOT NULL " : "")."
				GROUP BY hd.id, hd.tahun_anggaran, peny.nama_penyedia_pusat, hd.no_kontrak, hd.periode_mulai, hd.periode_selesai, hd.nama_barang, 
				hd.merk, hd.jumlah_barang, hd.nilai_barang, hd.nama_file, t.jumlah_alokasi, t.nilai_alokasi
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

		}

		function store($data)
		{
			$this->db->insert('tb_kontrak_pusat',$data);
		}

		function update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_kontrak_pusat', $data);
		}

		function destroy($id)
		{
			$this->db->delete('tb_alokasi_pusat', array('id_kontrak_pusat' => $id));
			$this->db->delete('tb_kontrak_pusat', array('id' => $id));
		}


		function total_unit()
		{
			$qry =	"	
				SELECT SUM(jumlah_barang) AS total 
				FROM tb_kontrak_pusat 
				where `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "");
			
			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and id in(
						select id_kontrak_pusat from tb_alokasi_pusat
						where id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi."
					)
				";
			}

			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					and id in(
						select id_kontrak_pusat from tb_alokasi_pusat
						where id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten."
						and regcad = 'REGULER'
					)
				";
			}

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return 0;
		}

		function total_nilai()
		{
			$qry =	"	
				SELECT SUM(nilai_barang) AS total 
				FROM tb_kontrak_pusat 
				where `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "");
			
			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and id in(
						select id_kontrak_pusat from tb_alokasi_pusat
						where id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi."
					)
				";
			}

			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					and id in(
						select id_kontrak_pusat from tb_alokasi_pusat
						where id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten."
					)
				";
			}

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return 0;
		}

		function total_kontrak()
		{
			$qry =	"	
				SELECT COUNT(*) AS total 
				FROM tb_kontrak_pusat 
				where `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "");
			
			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and id in(
						select id_kontrak_pusat from tb_alokasi_pusat
						where id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi."
					)
				";
			}

			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					and id in(
						select id_kontrak_pusat from tb_alokasi_pusat
						where id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten."
					)
				";
			}

			$res = $this->db->query($qry);

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return 0;
		}

		function total_merk()
		{
			$qry =	"	
				SELECT COUNT(DISTINCT merk) AS total 
				FROM tb_kontrak_pusat 
				where `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_penyedia_pusat) ? " and id_penyedia_pusat = ".$this->session->userdata('logged_in')->id_penyedia_pusat : "");

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= "
					and id in(
						select id_kontrak_pusat from tb_alokasi_pusat
						where id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi."
					)
				";
			}

			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					and id in(
						select id_kontrak_pusat from tb_alokasi_pusat
						where id_kabupaten = ".$this->session->userdata('logged_in')->id_kabupaten."
					)
				";
			}

			$res = $this->db->query($qry);		

			if($res->num_rows() > 0)
				return $res->row()->total;
			else
				return 0;
		}
	}
?>