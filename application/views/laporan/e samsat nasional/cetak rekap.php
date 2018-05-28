<center>
<h2><?= $header ?></h2>
<h3><?= $subheader ?></h3>
</center>
<br>
<br>
PENERIMAAN YANG TELAH DITERIMA / TERCATAT DALAM RKUD
<table style="overflow:scroll; font-size: 12px; border-collapse: collapse;" border=1 cellpadding="2" width="100%">
<thead style="font-weight: bold; text-align: center;">
<tr>
<td rowspan="3" width="5%">NO.</td>
<td rowspan="3" width="25%">JENIS PUNGUTAN</td>
<td colspan="3">PENETAPAN PKB</td>
<td rowspan="2" width="25%">JUMLAH</td>
</tr>
<tr>
<td rowspan="2" width="16%">OBYEK</td>
<td>POKOK</td>
<td>SANKSI</td>
</tr>
<tr>
<td width="18%">Rp</td>
<td width="18%">Rp</td>
<td width="18%">Rp</td>
</tr>
</thead>
<tbody>
<?php
$no = 1;
$pokok = 0;
$denda = 0;
$jumlah = 0;
foreach ( $jenis_pungutan as $jenis )
{
	$oby = empty($resultdata[$jenis->id_jenis]['oby']) ? 0 : $resultdata[$jenis->id_jenis]['oby'];
	$pokok = empty($resultdata[$jenis->id_jenis]['pokok']) ? 0 : $resultdata[$jenis->id_jenis]['pokok'];
	$denda = empty($resultdata[$jenis->id_jenis]['denda']) ? 0 : $resultdata[$jenis->id_jenis]['denda'];
	$jumlah = empty($resultdata[$jenis->id_jenis]['jumlah']) ? 0 : $resultdata[$jenis->id_jenis]['jumlah'];
	echo '<tr>
	<td align="right">'. $no .'. </td>
	<td align="center">'. $jenis->id_jenis .'</td>
	<td align="center">'. number_format($oby, 0) .'</td>
	<td align="right">'. number_format($pokok, 0) .'</td>
	<td align="right">'. number_format($denda, 0) .'</td>
	<td align="right">'. number_format($jumlah, 0) .'</td>
	</tr>';
	$no++;
	$totoby += $oby;
	$totpokok += $pokok;
	$totdenda += $denda;
	$totjumlah += $jumlah;
}
?>
</tbody>
<tfoot style="font-weight: bold;">
<tr>
<td colspan="2">JUMLAH BULAN / PERIODE INI</td>
<td align="center"><?= number_format($totoby, 0) ?></td>
<td align="right"><?= number_format($totpokok, 0) ?></td>
<td align="right"><?= number_format($totdenda, 0) ?></td>
<td align="right"><?= number_format($totjumlah, 0) ?></td>
</tr>
<tr>
<td colspan="2">JUMLAH S/D BULAN /PERIODE LALU</td>
<td align="center"><?= number_format($before_resultdata->oby, 0) ?></td>
<td align="right"><?= number_format($before_resultdata->pokok, 0) ?></td>
<td align="right"><?= number_format($before_resultdata->denda, 0) ?></td>
<td align="right"><?= number_format($before_resultdata->jumlah, 0) ?></td>
</tr>
<tr>
<td colspan="2">JUMLAH S/D BULAN /PERIODE INI</td>
<td align="center"><?= number_format($totoby + $before_resultdata->oby, 0) ?></td>
<td align="right"><?= number_format($totpokok + $before_resultdata->pokok, 0) ?></td>
<td align="right"><?= number_format($totdenda + $before_resultdata->denda, 0) ?></td>
<td align="right"><?= number_format($totjumlah + $before_resultdata->jumlah, 0) ?></td>
</tr>
</tfoot>
</table>