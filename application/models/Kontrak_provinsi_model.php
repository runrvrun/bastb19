<?php
	class Kontrak_provinsi_model extends CI_Model
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
				SELECT hd.id, nama_provinsi, hd.id_provinsi, hd.tahun_anggaran, peny.nama_penyedia_provinsi, hd.no_kontrak, hd.periode_mulai, hd.periode_selesai, 
				hd.nama_barang, hd.merk, hd.jumlah_barang, hd.nilai_barang, hd.nama_file, 
				t.jumlah_alokasi, t.nilai_alokasi, hd.id_penyedia_provinsi, jumlah_termin, uang_muka, termin_1, termin_2, 
				termin_3, termin_4, termin_5
				FROM tb_kontrak_provinsi hd 
				INNER JOIN tb_provinsi ON tb_provinsi.id=hd.id_provinsi
				INNER JOIN tb_penyedia_provinsi peny ON hd.`id_penyedia_provinsi` = peny.id
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
					FROM tb_alokasi_provinsi dt 
					LEFT JOIN tb_kontrak_provinsi hd ON hd.id = dt.id_kontrak_provinsi
					WHERE 1=1";
					$qry .= " GROUP BY id_header, dt.status_alokasi) AS temp
					GROUP BY temp.id_header
				) AS t ON hd.id = t.id_header
				WHERE  1=1 AND hd.`tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan;				
				if (isset($id)) $qry .= " and hd.id = $id";
				$qry .= " and
				(
					hd.`no_kontrak` LIKE '%".$search."%' 
					or peny.nama_penyedia_provinsi LIKE '%".$search."%' 
					or hd.`merk` LIKE '%".$search."%' 
					or hd.nama_barang LIKE '%".$search."%' 
				)
				".$filter."
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and peny.id = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "")."
				".(isset($this->session->userdata('logged_in')->id_provinsi) ? " and tb_provinsi.id = ".$this->session->userdata('logged_in')->id_provinsi : "")."
				GROUP BY hd.id, hd.tahun_anggaran, peny.nama_penyedia_provinsi, hd.no_kontrak, hd.periode_mulai, hd.periode_selesai, hd.nama_barang, 
				hd.merk, hd.jumlah_barang, hd.nilai_barang, hd.nama_file, t.jumlah_alokasi, t.nilai_alokasi
				ORDER BY ".$col." ".$dir;
				// ".((isset($this->session->userdata('logged_in')->id_provinsi) or isset($this->session->userdata('logged_in')->id_kabupaten)) ? " AND t.id_header IS NOT NULL " : "")."
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
			$this->db->insert('tb_kontrak_provinsi',$data);
		}

		function update($id, $data)
		{
			$this->db->where('id', $id);
			$this->db->update('tb_kontrak_provinsi', $data);
		}

		function destroy($id)
		{
			$this->db->delete('tb_alokasi_provinsi', array('id_kontrak_provinsi' => $id));
			$this->db->delete('tb_kontrak_provinsi', array('id' => $id));
		}


		function total_unit()
		{
			$qry =	"	
				SELECT SUM(jumlah_barang) AS total 
				FROM tb_kontrak_provinsi 
				where `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "");
			
			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= " and id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}
	
			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					and id in(
						select id_kontrak_provinsi from tb_alokasi_provinsi
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
				FROM tb_kontrak_provinsi 
				where `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "");
			
			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= " and id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}

			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					and id in(
						select id_kontrak_provinsi from tb_alokasi_provinsi
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
				FROM tb_kontrak_provinsi 
				where `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "");
			
			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= " and id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}

			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					and id in(
						select id_kontrak_provinsi from tb_alokasi_provinsi
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
				FROM tb_kontrak_provinsi 
				where `tahun_anggaran` = ".$this->session->userdata('logged_in')->tahun_pengadaan."
				".(isset($this->session->userdata('logged_in')->id_penyedia_provinsi) ? " and id_penyedia_provinsi = ".$this->session->userdata('logged_in')->id_penyedia_provinsi : "");

			if(isset($this->session->userdata('logged_in')->id_provinsi)){
				$qry .= " and id_provinsi = ".$this->session->userdata('logged_in')->id_provinsi;
			}

			if(isset($this->session->userdata('logged_in')->id_kabupaten)){
				$qry .= "
					and id in(
						select id_kontrak_provinsi from tb_alokasi_provinsi
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