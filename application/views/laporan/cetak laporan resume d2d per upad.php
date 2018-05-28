<center>
<h2><?= $title ?></h2>
<h2>TRIWULAN <?= $getTriwulan ?> - TAHUN <?= $getTahun ?></h2>
</center>
<br>
<table id="tabel">
	<thead>
		<tr>
			<th rowspan="2">No</th>
			<th rowspan="2">LOKASI UPAD</th>
			<th rowspan="2">Target Minimal</th>
			<th colspan="3">Jumlah Obyek</th>
			<th rowspan="2">Jumlah</th>
			<th rowspan="2">%</th>
		</tr>
		<tr>
			<?php
				foreach ( $this->Bulan->getAllByIdTriwulan($postTriwulan) as $th ) {
					echo "<th>Bulan ". $th['bulan'] ."</th>";
				}
			?>
		</tr>
	</thead>
	<tbody>
		<?php
			$arrJumlahPerBulan = array();
			$total_target = 0;
			$total_keseluruhan = 0;
			$no = 1;
			foreach( $arrLokasi as $lokasi ) {
				$val_target = empty($arrTarget[$lokasi['id_lokasi']]) ? 0 : $arrTarget[$lokasi['id_lokasi']];

				echo '<tr>
					<td align="right">'. $no .'</td>
					<td>'. $lokasi['lokasi'] .'</td>
					<td align="right" class="str">'. number_format( $val_target, 0) .'</td>';
				
				$jmlperpegawai = 0;
				foreach ( $this->Bulan->getAllByIdTriwulan($postTriwulan) as $td ) {
					$jml = empty($arrTransaksi[$lokasi['id_lokasi']][$td['id_bulan']]['jumlah']) ? 0 : $arrTransaksi[$lokasi['id_lokasi']][$td['id_bulan']]['jumlah'];
					echo '<td align="right" class="str">'. $jml .'</td>';
					$jmlperpegawai += $jml;
					$arrJumlahPerBulan[$td['id_bulan']] += $jml;
				}
				$persen = $jmlperpegawai > 0 && $val_target > 0 ? ($jmlperpegawai / $val_target) * 100 : 0;
				echo '<td align="right" class="str">'. number_format($jmlperpegawai, 0) .'</td>';
				echo '<td align="right" class="str">'. number_format( $persen, 2 ) .'</td>';
				echo '</tr>';
				$no++;
				$total_target += $val_target;
				$total_keseluruhan += $jmlperpegawai;
			}
		?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="2">Total Keseluruhan</td>
			<td align="right" class="str"><?= number_format($total_target, 0) ?></td>
			<?php 
				foreach ( $this->Bulan->getAllByIdTriwulan($postTriwulan) as $tdfooter ) {
					echo '<td align="right" class="str">'. number_format($arrJumlahPerBulan[$tdfooter['id_bulan']], 0) .'</td>';
				}
			?>
			
			<td align="right" class="str"><?= number_format($total_keseluruhan, 0) ?></td>
			<td align="right" class="str"><?= number_format( ($total_keseluruhan / $total_target) * 100, 2 ) ?></td>
		</tr>
	</tfoot>
</table>
<div style="float:right;"><i>dicetak : <?= element('username', $this->Opsisite->getDataUser()) ?> ; <?= date('d-m-Y H:i:s') ?></i></div>