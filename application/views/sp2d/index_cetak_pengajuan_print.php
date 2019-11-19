<style>
  table.dataTable thead:first-child > tr:first-child > th, table.dataTable tr td:first-child {
    text-align: left; 
  }
  table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
  }
  th, td {
    padding: 5px;
  }
</style>
<div id="headerKontrak" style="width:100%; text-align:center; border-style: solid; mergin-bottom: 40px">
  <h4><?php echo $kontrak_pusat->nama_penyedia_pusat; ?></h4>
  <h2><?php echo $kontrak_pusat->no_kontrak; ?></h2>
  <h4><?php echo date('d M Y', strtotime($kontrak_pusat->periode_mulai))." - ".date('d M Y', strtotime($kontrak_pusat->periode_selesai)); ?></h4>
  <table width="100%">
    <tr>
      <td><?php echo $kontrak_pusat->nama_barang ?></td>
      <td><?php echo $kontrak_pusat->merk; ?></td>
      <td><?php echo number_format($kontrak_pusat->jumlah_barang, 0); ?></b> UNIT</td>
      <td> Rp <?php echo number_format($kontrak_pusat->nilai_barang, 0); ?></td>
    </tr>
  </table> 
</div>
<div>
  <table width="100%">
    <tr>
      <td><?php echo $total_unit; ?></span></b> Unit</td>
      <td>Nilai (Rp): <?php echo $total_nilai; ?></span></b></td>
    </tr>
  </table> 
</div>

<div style="margin-top: 20px; ">
  <table width="100%">
  <thead>
    <tr>
      <?php foreach($cols as $key=>$val){ ?>
        <th><?php echo $val['caption'];?></th>
      <?php } ?>
    </tr>
    <?php foreach($sp2d as $keysp2d=>$valsp2d){ ?>
    <tr>
        <?php foreach($cols as $key=>$val){ ?>
        <td><?php echo $valsp2d->{$val['column']};?></td>
        <?php } ?>
    </tr>
    <?php } ?>
  </thead>
  <tbody>
  </tbody>
  </table>
</div>