<table style="font-size:10pt;border-collapse:collapse; width:100%; font-weight:bold;" cellpadding="3">
<tr><td rowspan="4" colspan="2" width="20%" valign="top" align="right">LAPORAN :</td><td colspan="8">TARGET, PENETAPAN, PENERIMAAN DAN TUNGGKAN</td></tr>
<tr><td colspan="8">RETRIBUSI DAERAH YANG TIDAK DIKELOLA LANGSUNG UP3AD</td></tr>
<tr><td colspan="2" width="15%">BAGIAN BULAN</td><td colspan="5">: <?= $get_bulan_tahun ?></td><td align="center" colspan="2">Model : RD.02</td></tr>
<tr><td colspan="2" width="15%">UP3AD / KABUPATEN</td><td colspan="7">: <?= str_replace('UP3AD', '', $get_lokasi) ?></td></tr>
</table>
<br>
<table style="font-size:8pt;border-collapse:collapse;" cellpadding="5" border="1">
	<thead>
		<tr>
			<th>No. REKENING</th>
			<th width="20%">JENIS PUNGUTAN</th>
			<th width="8%">TARGET</th>
			<th width="8%">S/D BLN LALU</th>
			<th width="8%">BULAN INI</th>
			<th width="8%">S/D BULAN INI</th>
			<th width="5%">%</th>
		</tr>
	</thead>
	<tbody>
		<?= $this->Rekeningskpdlain->laporan_rd02($tahun, $bulan, $lokasi, $target) ?>
	</tbody>
</table>
<small>dicetak : <?= element('username', $this->Opsisite->getDataUser()) ?> ; <?= date('d-m-Y H:i:s') ?></small>
<br>
<table style="width:100%;">
<tr>
<td colspan="6" style="width:50%; text-align:center; font-size:9pt;">
</td>
<td colspan="4" style="width:50%; text-align:center; font-size:9pt;">
<?= $tgl_cetak ?><br>
<?=	$this->Paraf->getParafLaporan('rd2d', $lokasi, 1 ) ?>
</td>
</table>
