<center><h2>LAPORAN <?= $header ?></h2></center>
<br>
<table style="overflow:scroll; font-size: 10px; border-collapse: collapse;" border=1 cellpadding="2">
<thead>
<tr>
<th rowspan="3" width="30px">No.</th>
<th rowspan="3" width="200px">BULAN</th>
<th colspan="<?= (($gettahunakhir-$gettahunawal)+1)*2 ?>"><?= $th ?></th>
</tr>
<tr>
<?php
	$th = $gettahunawal;
	while( $th <= $gettahunakhir ){
		echo '<th width="70px" colspan="2">'. $th .'</th>';
		$th++;
	}
?>
</tr>
<tr>
<?php
	$th2 = $gettahunawal;
	while( $th2 <= $gettahunakhir ){
		echo '<th width="80px">Jumlah</th>
		<th width="20px">%</th>';
		$th2++;
	}
?>
</tr>
</thead>
<tbody>
<?php
$arrTotalperColumn = array();
$no = 1;
foreach( $this->Bulan->getAllData()->result() as $bulan )
{
	$td = $gettahunawal;
	$TotalPerRowBulan = 0;
	echo '<tr>
	<td align="right" width="30px">'. $no .'.</td>
	<td width="200px">'. $bulan->bulan .'</td>';
	while( $td <= $gettahunakhir ){
		$total = empty($arrTrx[$td][$bulan->id_bulan]) ? 0 : $arrTrx[$td][$bulan->id_bulan] ;
		$hasil_tren_bulan_lalu = $bulan->id_bulan == 1 ? 0 : $total - $arrTrx[$td][$bulan->id_bulan-1];
		$persentase_tren = $arrTrx[$td][$bulan->id_bulan-1] == 0 ? 0 : ($hasil_tren_bulan_lalu / $arrTrx[$td][$bulan->id_bulan-1]) * 100;
		echo '<td width="80px" align="right">'. $total .'</td>';
		echo '<td width="20px" align="center">'. number_format($persentase_tren, 2) .'</td>';
		$arrTotalperColumn[$td][] = $total;
		$td++;
	}
	echo '</tr>';
	$no ++;
}
?>
</tbody>
<tfoot>
<tr>
<td colspan="2"><b>TOTAL</b></td>
<?php
$td = $gettahunawal;
while( $td <= $gettahunakhir ){
	
	$subtotal = 0;
	foreach ( $arrTotalperColumn[$td] as $r )
	{
		$subtotal += $r;
	}
	
	echo '<td width="80px" align="right"><b>'. $subtotal .'</b></td>';
	echo '<td width="80px" align="center"><b>'. 0 .'</b></td>';
	$td++;
}
?>
</tr>
</tfoot>
</table>