<table style="font-size: 14px; border-collapse: collapse; font-weight: bold;" width="100%">
<tr>
<td rowspan="4" valign="top">LAPORAN : </td>
<td colspan="13">PENETAPAN, PENGURANGAN, TAGIHAN, PENERIMAAN DAN TUNGGAKAN PKB</td>
<td align="center" colspan="7"></td>
</tr>
<tr>
<td colspan="13">( BERDASARKAN POTENSI OBYEK YANG TERDAFTAR / TER-REGISTRASI DI SAMSAT )</td>
<td align="center" colspan="7"></td>
</tr>
<tr>
<td colspan="13"><?= $header_periode ?></td>
<td align="center" colspan="7"></td>
</tr>
<tr>
<td colspan="13"><?= $header_lokasi ?></td>
<td align="center" colspan="7"><?= $header_lokasi2 ?></td>
</tr>
</table>
<table style="font-size: 10px; border-collapse: collapse;" width="100%">
<tr>
<td colspan="19">PENERIMAAN YANG DITERIMA CASH KASIR</td>
<td align="right" colspan="2">MODEL PD 02</td>
</tr>
</table>
<table style="overflow:scroll; font-size: 10px; border-collapse: collapse;" border=1 cellpadding="2" width="100%">
<thead style="font-weight: bold;">
<tr>
<th rowspan="4">NO.</th>
<th rowspan="4">JENIS PUNGUTAN</th>
<th colspan="3" rowspan="2">PENETAPAN S/D BULAN LALU</th>
<th colspan="6">PENETAPAN BULAN INI</th>
<th colspan="3" rowspan="2">PENGURANGAN</th>
<th colspan="4">PENERIMAAN</th>
<th colspan="3" rowspan="2">SISA PENETAPAN S/D BULAN INI</th>
</tr>
<tr>
<th colspan="3">TAHUN LALU</th>
<th colspan="3">TAHUN JALAN</th>
<th colspan="4">LOKAL + MEMPROSES</th>
</tr>
<tr>
<th rowspan="2">OBYEK</th>
<th>POKOK</th>
<th>SANKSI</th>
<th rowspan="2">OBYEK</th>
<th>POKOK</th>
<th>SANKSI</th>
<th rowspan="2">OBYEK</th>
<th>POKOK</th>
<th>SANKSI</th>
<th rowspan="2">OBYEK</th>
<th>POKOK</th>
<th>SANKSI</th>
<th rowspan="2">OBYEK</th>
<th>POKOK</th>
<th>SANKSI</th>
<th>JUMLAH</th>
<th rowspan="2">OBYEK</th>
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
<th>TOTAL</th>
<th>Rp</th>
<th>Rp</th>
</tr>
<tr>
<?php 
for($i_th = 1;$i_th <= 21; $i_th++)
{
	echo '<th>'. $i_th .'</th>';
}
?>
</tr>
<tr>
<th>A</th>
<th><small>PENETAPAN PKB THN JALAN</small></th>
<?php 
for($i_th = 1;$i_th <= 19; $i_th++)
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
		foreach ( $this->Fungsi->arrKodeJenisPajak() as $jenis )
		{
			$oby_tl = empty($arrData[$jenis['id_jenis']][0]->tot_obyek) ? '0' : $arrData[$jenis['id_jenis']][0]->tot_obyek;
			$pokok_tl = empty($arrData[$jenis['id_jenis']][0]->tot_pokok) ? '0' : $arrData[$jenis['id_jenis']][0]->tot_pokok;
			$sanksi_tl = empty($arrData[$jenis['id_jenis']][0]->tot_sanksi) ? '0' : $arrData[$jenis['id_jenis']][0]->tot_sanksi;
			$oby_tj = empty($arrData[$jenis['id_jenis']][1]->tot_obyek) ? '0' : $arrData[$jenis['id_jenis']][1]->tot_obyek;
			$pokok_tj = empty($arrData[$jenis['id_jenis']][1]->tot_pokok) ? '0' : $arrData[$jenis['id_jenis']][1]->tot_pokok;
			$sanksi_tj = empty($arrData[$jenis['id_jenis']][1]->tot_sanksi) ? '0' : $arrData[$jenis['id_jenis']][1]->tot_sanksi;
			echo '<tr>
			<td align="center">'. $no .'</td>
			<td align="center">'. $jenis['kode_jenis'] .'</td>
			<td align="right">-</td>
			<td align="right">-</td>
			<td align="right">-</td>
			<td align="right">'. $this->Fungsi->formatangkalaporan($oby_tl) .'</td>
			<td align="right">'. $this->Fungsi->formatangkalaporan($pokok_tl) .'</td>
			<td align="right">'. $this->Fungsi->formatangkalaporan($sanksi_tl) .'</td>
			<td align="right">'. $this->Fungsi->formatangkalaporan($oby_tj) .'</td>
			<td align="right">'. $this->Fungsi->formatangkalaporan($pokok_tj) .'</td>
			<td align="right">'. $this->Fungsi->formatangkalaporan($sanksi_tj) .'</td>
			<td align="right">-</td>
			<td align="right">-</td>
			<td align="right">-</td>
			<td align="right">'. $this->Fungsi->formatangkalaporan($oby_tj) .'</td>
			<td align="right">'. $this->Fungsi->formatangkalaporan($pokok_tl+$pokok_tj) .'</td>
			<td align="right">'. $this->Fungsi->formatangkalaporan($sanksi_tl+$sanksi_tj) .'</td>
			<td align="right">'. $this->Fungsi->formatangkalaporan(($pokok_tl+$pokok_tj)+($sanksi_tl+$sanksi_tj)) .'</td>
			<td align="right">-</td>
			<td align="right">-</td>
			<td align="right">-</td>
			</tr>';
			$arr['oby_tl'][] = $oby_tl;
			$arr['pokok_tl'][] = $pokok_tl;
			$arr['sanksi_tl'][] = $sanksi_tl;
			$arr['oby_tj'][] = $oby_tj;
			$arr['pokok_tj'][] = $pokok_tj;
			$arr['sanksi_tj'][] = $sanksi_tj;
			$no++;
		}

		$tot_oby_tl_bs = empty($arrDataLalu[0]->tot_obyek) ? 0 : $arrDataLalu[0]->tot_obyek;
		$tot_pokok_tl_bs = empty($arrDataLalu[0]->tot_pokok) ? 0 : $arrDataLalu[0]->tot_pokok;
		$tot_sanksi_tl_bs = empty($arrDataLalu[0]->tot_sanksi) ? 0 : $arrDataLalu[0]->tot_sanksi;
		$tot_oby_tj_bs = empty($arrDataLalu[1]->tot_obyek) ? 0 : $arrDataLalu[1]->tot_obyek;
		$tot_pokok_tj_bs = empty($arrDataLalu[1]->tot_pokok) ? 0 : $arrDataLalu[1]->tot_pokok;
		$tot_sanksi_tj_bs = empty($arrDataLalu[1]->tot_sanksi) ? 0 : $arrDataLalu[1]->tot_sanksi;
	?>
</tbody>
<tfoot>
<tr style="font-weight: bold;">
<td colspan="2">JUMLAH BULAN INI</td>
<td align="right">-</td>
<td align="right">-</td>
<td align="right">-</td>
<td align="right"><?= $this->Fungsi->formatangkalaporan(array_sum($arr['oby_tl'])) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan(array_sum($arr['pokok_tl'])) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan(array_sum($arr['sanksi_tl'])) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan(array_sum($arr['oby_tj'])) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan(array_sum($arr['pokok_tj'])) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan(array_sum($arr['sanksi_tj'])) ?></td>
<td align="right">-</td>
<td align="right">-</td>
<td align="right">-</td>
<td align="right"><?= $this->Fungsi->formatangkalaporan(array_sum($arr['oby_tj'])) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan(array_sum($arr['pokok_tl']) + array_sum($arr['pokok_tj'])) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan(array_sum($arr['sanksi_tl']) + array_sum($arr['sanksi_tj'])) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan((array_sum($arr['pokok_tl']) + array_sum($arr['pokok_tj']))+(array_sum($arr['sanksi_tl']) + array_sum($arr['sanksi_tj']))) ?></td>
<td align="right">-</td>
<td align="right">-</td>
<td align="right">-</td>
</tr>
<tr style="font-weight: bold;">
<td colspan="2">JUMLAH S/D BULAN LALU</td>
<td align="right">-</td>
<td align="right">-</td>
<td align="right">-</td>
<td align="right"><?= $this->Fungsi->formatangkalaporan($tot_oby_tl_bs) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan($tot_pokok_tl_bs) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan($tot_sanksi_tl_bs) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan($tot_oby_tj_bs) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan($tot_pokok_tj_bs) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan($tot_sanksi_tj_bs) ?></td>
<td align="right">-</td>
<td align="right">-</td>
<td align="right">-</td>
<td align="right"><?= $this->Fungsi->formatangkalaporan($tot_oby_tj_bs) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan($tot_pokok_tl_bs + $tot_pokok_tj_bs) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan($tot_sanksi_tl_bs + $tot_sanksi_tj_bs) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan(($tot_pokok_tl_bs + $tot_pokok_tj_bs)+($tot_sanksi_tl_bs + $tot_sanksi_tj_bs)) ?></td>
<td align="right">-</td>
<td align="right">-</td>
<td align="right">-</td>
</tr>
<tr style="font-weight: bold;">
<td colspan="2">JUMLAH S/D BULAN INI</td>
<td align="right">-</td>
<td align="right">-</td>
<td align="right">-</td>
<td align="right"><?= $this->Fungsi->formatangkalaporan(array_sum($arr['oby_tl']) + $tot_oby_tl_bs) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan(array_sum($arr['pokok_tl']) + $tot_pokok_tl_bs) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan(array_sum($arr['sanksi_tl']) + $tot_sanksi_tl_bs) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan(array_sum($arr['oby_tj']) + $tot_oby_tj_bs) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan(array_sum($arr['pokok_tj']) + $tot_pokok_tj_bs) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan(array_sum($arr['sanksi_tj']) + $tot_sanksi_tj_bs) ?></td>
<td align="right">-</td>
<td align="right">-</td>
<td align="right">-</td>
<td align="right"><?= $this->Fungsi->formatangkalaporan(array_sum($arr['oby_tj']) + $tot_oby_tj_bs) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan((array_sum($arr['pokok_tl']) + array_sum($arr['pokok_tj'])) + ($tot_pokok_tl_bs + $tot_pokok_tj_bs)) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan((array_sum($arr['sanksi_tl']) + array_sum($arr['sanksi_tj'])) + ($tot_sanksi_tl_bs + $tot_sanksi_tj_bs)) ?></td>
<td align="right"><?= $this->Fungsi->formatangkalaporan(((array_sum($arr['pokok_tl']) + array_sum($arr['pokok_tj']))+(array_sum($arr['sanksi_tl']) + array_sum($arr['sanksi_tj']))) + (($tot_pokok_tl_bs + $tot_pokok_tj_bs)+($tot_sanksi_tl_bs + $tot_sanksi_tj_bs))) ?></td>
<td align="right">-</td>
<td align="right">-</td>
<td align="right">-</td>
</tr>
</tfoot>
</table>
<br>
<table style="font-size: 10px; border-collapse: collapse;" width="100%">
<tr>
<td rowspan="9" colspan="15" valign="top">dicetak : <?= element('username', $this->Opsisite->getDataUser()) ?> pada <?= date('d-m-Y H:i:s') ?></td>
<td align="center" colspan="6"><?= $dtlokasi->kota ?>, <?= date('d') ?> <?= $this->Fungsi->getBulan(date('n')) ?> <?= date('Y') ?></td>
</tr>
<tr>
<td align="center" colspan="6">Kepala Unit Pelayanan Pendapatan Dan</td>
</tr>
<tr>
<td align="center" colspan="6">Pemberdayaan Aset Daerah</td>
</tr>
<tr><td><br></td></tr>
<tr><td><br></td></tr>
<tr><td><br></td></tr>
<tr>
<td align="center" colspan="6"><?= $this->Paraf->getParafLaporan2('rd2d', $postinduk, 1 ) ?></td>
</tr>
</table>
