<center>
<h2><?= $title ?></h2>
<h2><?= $getPd->nama_rekening ?></h2>
<h2>TRIWULAN <?= $getTriwulan ?> - TAHUN <?= $getTahun ?></h2>
</center>
<br>
<table id="tabel">
	<thead>
		<tr>
			<th rowspan="2"><b>No</b></th>
			<th rowspan="2"><b>UP3AD</b></th>
			<th colspan="3"><b>Jumlah Obyek</b></th>
			<th rowspan="2"><b>Jumlah</b></th>
		</tr>
		<tr>
			<?php
				foreach ( $this->Bulan->getAllByIdTriwulan($postTriwulan) as $th ) {
					echo "<th><b>". $th['bulan'] ."</th>";
				}
			?>
		</tr>
	</thead>
	<tbody>
		<?php
			$arrJumlahPerBulan = array();
			$total_keseluruhan = 0;
			$no = 1;
			foreach( $arrLokasi as $lokasi ) {

				echo '<tr>
					<td align="right">'. $no .'</td>
					<td>'. $lokasi['lokasi'] .'</td>';
				
				$jmlperup3ad = 0;
				foreach ( $this->Bulan->getAllByIdTriwulan($postTriwulan) as $td ) {
					$jml = empty($arrTransaksi[$lokasi['id_lokasi']][$td['id_bulan']]['realisasi']) ? 0 : $arrTransaksi[$lokasi['id_lokasi']][$td['id_bulan']]['realisasi'];
					echo '<td align="right" class="str">'. number_format($jml, 0) .'</td>';
					$jmlperup3ad += $jml;
					$arrJumlahPerBulan[$td['id_bulan']] += $jml;
				}

				echo '<td align="right" class="str">'. number_format($jmlperup3ad, 0) .'</td>';
				echo '</tr>';
				$no++;
				$total_keseluruhan += $jmlperup3ad;
			}

			$persentase = $getPd->total_target <= 0 ? 0 : ($total_keseluruhan/$getPd->total_target)*100;
		?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="2"><b>JUMLAH</b></td>
			<?php 
				foreach ( $this->Bulan->getAllByIdTriwulan($postTriwulan) as $tdfooter ) {
					echo '<td align="right" class="str"><b>'. number_format($arrJumlahPerBulan[$tdfooter['id_bulan']], 0) .'</b></td>';
				}
			?>
			<td align="right" class="str"><b><?= number_format($total_keseluruhan, 0) ?></b></td>
		</tr>
		<tr>
			<td colspan="5"><b>TARGET TRIWULAN <?= $getTriwulan ?></b></td>
			<td align="right" class="str"><b><?= number_format($getPd->total_target, 0) ?></b></td>
		</tr>
		<tr>
			<td colspan="5"><b>PROSENTASE CAPAIAN (%)</b></td>
			<td align="right" class="str"><b><?= number_format($persentase, 0) ?></b></td>
		</tr>
	</tfoot>
</table>
<div style="float:right;"><i>dicetak : <?= element('username', $this->Opsisite->getDataUser()) ?> ; <?= date('d-m-Y H:i:s') ?></i></div>