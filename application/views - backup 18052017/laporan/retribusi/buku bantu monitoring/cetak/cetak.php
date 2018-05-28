<table style="font-size:11pt;border-collapse:collapse; width:100%; font-weight:bold;" cellpadding="3">
<tr><td colspan="9" align="center">BUKU BANTU MONITORING TUNGGAKAN</td></tr>
<tr><td colspan="9" align="center">RETRIBUSI KEKAYAAN DAERAH (RPKD)</td></tr>
<tr><td colspan="9" align="center"><?= $get_lokasi ?></td></tr>
<tr><td colspan="9" align="center">BAGIAN BULAN : <?= $get_bulan_tahun ?></td></tr>
</table>
<br>
<table style="font-size:9pt;border-collapse:collapse; width:100%;" cellpadding="5" border="1">
	<thead>
		<tr>
			<th rowspan="2">NO.</th>
			<th rowspan="2" width="20%">PERUNTUKAN</th>
			<th colspan="2">KETETAPAN</th>
			<th colspan="2">PEMBAYARAN</th>
			<th colspan="2">TUNGGAKAN</th>
			<th rowspan="2">KETERANGAN</th>
		</tr>
		<tr>
			<th width="5%">OBY</th>
			<th width="8%">RP</th>
			<th width="5%">OBY</th>
			<th width="8%">RP</th>
			<th width="5%">OBY</th>
			<th width="8%">RP</th>
		</tr>
	</thead>
	<tbody>
		<?= $this->Rekeningpad->laporan_buku_bantu_monitoring_tunggakan($tahun, $bulan, $lokasi) ?>
	</tbody>
	<tfoot>
		<?= $this->Rekeningpad->footer_laporan_buku_bantu_monitoring_tunggakan($tahun, $bulan, $lokasi) ?>
	</tfoot>
</table>
<small>dicetak : <?= element('username', $this->Opsisite->getDataUser()) ?> ; <?= date('d-m-Y H:i:s') ?></small>
<br>
<table style="width:100%;">
<tr>
<td colspan="5" style="width:50%; text-align:center; font-size:9pt;">
</td>
<td colspan="4" style="width:50%; text-align:center; font-size:9pt;">
<?= $tgl_cetak ?><br>
<?=	$this->Paraf->getParafLaporan('rd2d', $lokasi, 1 ) ?>
</td>
</tr>
</table>
