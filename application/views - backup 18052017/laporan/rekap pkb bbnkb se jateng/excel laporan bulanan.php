<center><h2>LAPORAN <?= $header ?></h2></center>
<br>
<table style="overflow:scroll; font-size: 10px; border-collapse: collapse;" border=1 cellpadding="2">
<thead>
<tr>
<th rowspan="2" width="30px">No.</th>
<th rowspan="2" width="200px">Lokasi</th>
<th colspan="<?= count($this->Fungsi->arrKodeJenisPajak()) ?>"><?= $th ?></th>
<th rowspan="2" width="70px">JUMLAH</th>
</tr>
<tr>
<?php
	foreach( $this->Fungsi->arrKodeJenisPajak() as $kode ){
		echo '<th width="70px">'. $kode['kode_jenis'] .'</th>';
	}
?>
</tr>
</thead>
<tbody>
<?php
$arrTotalperColumn = array();
$no = 1;
foreach( $this->Lokasi->arrLokasiIndukDanSamtu() as $lokasi )
{
	$TotalPerRowLokasi = 0;
	echo '<tr>
	<td align="right" width="30px">'. $no .'.</td>
	<td width="200px">'. $lokasi['lokasi'] .'</td>';
	foreach( $this->Fungsi->arrKodeJenisPajak() as $kode ){
		$total = empty($arrTrx[$lokasi['id_lokasi']][$kode['id_jenis']]) ? 0 : $arrTrx[$lokasi['id_lokasi']][$kode['id_jenis']] ;
		echo '<td width="70px" align="right">'. $this->Fungsi->formatangkalaporan($total) .'</td>';
		$arrTotalperColumn[$kode['id_jenis']][] = $total;
		$TotalPerRowLokasi += $total;
	}
	echo '<td align="right" width="70px">'. $TotalPerRowLokasi .'</td>
	</tr>';
	$no++;
}
?>
</tbody>
<tfoot>
<tr>
<td colspan="2">JUMLAH BULAN INI</td>
<?php
$arrTotal = array();

foreach( $this->Fungsi->arrKodeJenisPajak() as $kode )
{
	$TotalPerColumn = 0;
	foreach ( $arrTotalperColumn[$kode['id_jenis']] as $r )
	{
		$TotalPerColumn += $r;
	}
	//Only PHP 5.6 dan seterusnya
	//$TotalPerColumn = empty(array_sum($arrTotalperColumn[$kode['id_jenis']])) ? 0 : array_sum($arrTotalperColumn[$kode['id_jenis']]);
	//$TotalPerColumn = 0;
	echo '<td width="70px" align="right">'. $TotalPerColumn .'</td>';
	$arrTotal[$kode['id_jenis']] = $TotalPerColumn;
}
?>
<td align="right" width="70px"><?= array_sum($arrTotal) ?></td>
</tr>
<tr>
<td colspan="2">JUMLAH S.D. BULAN LALU</td>
<?php
foreach( $this->Fungsi->arrKodeJenisPajak() as $kode )
{
	$TotalPerColumnLalu = empty($arrTrxLalu[$kode['id_jenis']]) ? 0 : $arrTrxLalu[$kode['id_jenis']];
	echo '<td width="70px" align="right">'. $TotalPerColumnLalu .'</td>';
	$arrTotal[$kode['id_jenis']] += $TotalPerColumnLalu;
}
?>
<td align="right" width="70px"><?= array_sum($arrTrxLalu) ?></td>
</tr>
<tr>
<td colspan="2">JUMLAH S.D. BULAN INI</td>
<?php
foreach( $this->Fungsi->arrKodeJenisPajak() as $kode )
{
	$TotalFinalColumn = empty($arrTotal[$kode['id_jenis']]) ? 0 : $arrTotal[$kode['id_jenis']];
	echo '<td width="70px" align="right">'. $TotalFinalColumn .'</td>';
}
?>
<td align="right" width="70px"><?= array_sum($arrTotal) ?></td>
</tr>
</tfoot>
</table>