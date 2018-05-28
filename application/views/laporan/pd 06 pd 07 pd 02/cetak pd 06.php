<table style="font-size: 12px; border-collapse: collapse; font-weight: bold;" width="100%">
<tr>
<td colspan="19" align="center"><?= $header ?></td>
</tr>
</table>
<table style="font-size: 10px; border-collapse: collapse;" width="100%">
<tr>
<td colspan="17"></td>
<td align="right" colspan="2">MODEL PD 06</td>
</tr>
</table>
<table style="overflow:scroll; font-size: 10px; border-collapse: collapse;" border=1 cellpadding="2" width="100%">
<thead style="font-weight: bold;">
<tr>
<th rowspan="3">NO.</th>
<th rowspan="3">JENIS PUNGUTAN</th>
<th colspan="3">PENETAPAN S/D BULAN LALU</th>
<th colspan="3">PENETAPAN BULAN INI</th>
<th colspan="3">PENGURANGAN</th>
<th colspan="5">PENERIMAAN</th>
<th colspan="3">SISA KETETAPAN S/D BULAN</th>
</tr>
<tr>
<th rowspan="2">OBY</th>
<th>POKOK</th>
<th>SANKSI</th>
<th rowspan="2">OBY</th>
<th>POKOK</th>
<th>SANKSI</th>
<th rowspan="2">OBY</th>
<th>POKOK</th>
<th>SANKSI</th>
<th rowspan="2">OBY</th>
<th>ANGSURAN</th>
<th>POKOK</th>
<th>SANKSI</th>
<th>JUMLAH</th>
<th rowspan="2">OBY</th>
<th>POKOK</th>
<th>SANKSI</th>
</tr>
<tr>
<th>Rp</th>
<th>Rp</th>
<th>Rp</th>
<th>Rp</th>
<th>Rp</th>
<th>Rp</th>
<th>Rp</th>
<th>Rp</th>
<th>Rp</th>
<th>Rp</th>
<th>Rp</th>
<th>Rp</th>
</tr>
<tr>
<?php 
for($i_th = 1;$i_th <= 19; $i_th++)
{
	echo '<th>'. $i_th .'</th>';
}
?>
</tr>
<tr>
<th>A</th>
<th><small>BBNKB I</small></th>
<?php 
for($i_th = 1;$i_th <= 17; $i_th++)
{
	echo '<th></th>';
}
?>
</tr>
</thead>
<tbody>
	<?php 
		$no = 1;
		$arr = array();
		$tot_oby_bi = 0;
		$tot_pokok_bi = 0;
		$tot_sanksi_bi = 0;
		foreach ( $this->Fungsi->arrKodeJenisPajak() as $jenis )
		{
			$oby_bi = empty($arrData[$jenis['id_jenis']]->tot_obyek) ? '0' : $arrData[$jenis['id_jenis']]->tot_obyek;
			$pokok_bi = empty($arrData[$jenis['id_jenis']]->tot_pokok) ? '0' : $arrData[$jenis['id_jenis']]->tot_pokok;
			$sanksi_bi = empty($arrData[$jenis['id_jenis']]->tot_sanksi) ? '0' : $arrData[$jenis['id_jenis']]->tot_sanksi;
			echo '<tr>
			<td align="center">'. $no .'</td>
			<td align="center">'. $jenis['kode_jenis'] .'</td>
			<td align="right">-</td>
			<td align="right">-</td>
			<td align="right">-</td>
			<td align="right">'. $this->Fungsi->formatangkalaporan($oby_bi) .'</td>
			<td align="right">'. $this->Fungsi->formatangkalaporan($pokok_bi) .'</td>
			<td align="right">'. $this->Fungsi->formatangkalaporan($sanksi_bi) .'</td>
			<td align="right">-</td>
			<td align="right">-</td>
			<td align="right">-</td>
			<td align="right">'. $this->Fungsi->formatangkalaporan($oby_bi) .'</td>
			<td align="right">-</td>
			<td align="right">'. $this->Fungsi->formatangkalaporan($pokok_bi) .'</td>
			<td align="right">'. $this->Fungsi->formatangkalaporan($sanksi_bi) .'</td>
			<td align="right">'. $this->Fungsi->formatangkalaporan($sanksi_bi + $pokok_bi) .'</td>
			<td align="right">-</td>
			<td align="right">-</td>
			<td align="right">-</td>
			</tr>';
			$tot_oby_bi += $oby_bi;
			$tot_pokok_bi += $pokok_bi;
			$tot_sanksi_bi += $sanksi_bi;
			$no++;
		}

		
	?>
</tbody>
<tfoot>
<tr style="font-weight: bold;">
<td colspan="5">JUMLAH BULAN INI</td>
<td align="right"><?= $this->Fungsi->formatangkalaporan($tot_oby_bi) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan($tot_pokok_bi) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan($tot_sanksi_bi) ?></td>
<td align="right">-</td>
<td align="right">-</td>
<td align="right">-</td>
<td align="right"><?= $this->Fungsi->formatangkalaporan($tot_oby_bi) ?></td>
<td align="right">-</td>
<td align="right"><?= $this->Fungsi->formatangkalaporan($tot_pokok_bi) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan($tot_sanksi_bi) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan($tot_pokok_bi + $tot_sanksi_bi) ?></td>
<td align="right">-</td>
<td align="right">-</td>
<td align="right">-</td>
</tr>
<tr style="font-weight: bold;">
<td colspan="5">JUMLAH S/D BULAN LALU</td>
<td align="right"><?= $this->Fungsi->formatangkalaporan($arrDataLalu->oby) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan($arrDataLalu->pokok) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan($arrDataLalu->sanksi) ?></td>
<td align="right">-</td>
<td align="right">-</td>
<td align="right">-</td>
<td align="right"><?= $this->Fungsi->formatangkalaporan($arrDataLalu->oby) ?></td>
<td align="right">-</td>
<td align="right"><?= $this->Fungsi->formatangkalaporan($arrDataLalu->pokok) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan($arrDataLalu->sanksi) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan($arrDataLalu->pokok + $arrDataLalu->sanksi) ?></td>
<td align="right">-</td>
<td align="right">-</td>
<td align="right">-</td>
</tr>
<tr style="font-weight: bold;">
<td colspan="5">JUMLAH S/D BULAN INI</td>
<td align="right"><?= $this->Fungsi->formatangkalaporan($arrDataLalu->oby + $tot_oby_bi) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan($arrDataLalu->pokok + $tot_pokok_bi) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan($arrDataLalu->sanksi + $tot_sanksi_bi) ?></td>
<td align="right">-</td>
<td align="right">-</td>
<td align="right">-</td>
<td align="right"><?= $this->Fungsi->formatangkalaporan($arrDataLalu->oby + $tot_oby_bi) ?></td>
<td align="right">-</td>
<td align="right"><?= $this->Fungsi->formatangkalaporan($arrDataLalu->pokok + $tot_pokok_bi) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan($arrDataLalu->sanksi + $tot_sanksi_bi) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan($arrDataLalu->pokok + $arrDataLalu->sanksi + $tot_pokok_bi + $tot_sanksi_bi) ?></td>
<td align="right">-</td>
<td align="right">-</td>
<td align="right">-</td>
</tr>
</tfoot>
</table>
<br>
<table style="font-size: 10px; border-collapse: collapse;" width="100%">
<tr>
<td colspan="13" valign="top">dicetak : <?= element('username', $this->Opsisite->getDataUser()) ?> pada <?= date('d-m-Y H:i:s') ?></td>
<td align="center" colspan="6"><?= $dtlokasi->kota ?>, <?= date('d') ?> <?= $this->Fungsi->getBulan(date('n')) ?> <?= date('Y') ?></td>
</tr>
<tr>
<td align="center" colspan="6"><?= $paraf_1['detil_jabatan'] ?></td>
<td colspan="7" width="40%"></td>
<td align="center" colspan="6"><?= $paraf_2['detil_jabatan'] ?></td>
</tr>
<tr>
<td align="center" colspan="6"></td>
</tr>
<tr><td><br></td><td><br></td><td><br></td></tr>
<tr><td><br></td><td><br></td><td><br></td></tr>
<tr><td><br></td><td><br></td><td><br></td></tr>
<tr>
<td align="center" colspan="6"><?= $paraf_1['detil_nama'] ?></td>
<td colspan="7" width="40%"></td>
<td align="center" colspan="6"><?= $paraf_2['detil_nama'] ?></td>
</tr>
</table>
