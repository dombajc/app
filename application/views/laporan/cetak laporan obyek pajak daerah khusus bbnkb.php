<center>
<h2><?= $title ?></h2>
<h2>BEA BALIK NAMA KENDARAAN BERMOTOR</h2>
<h2>TRIWULAN <?= $getTriwulan ?> - TAHUN <?= $getTahun ?></h2>
</center>
<br>
<table id="tabel">
	<thead>
		<tr>
			<th rowspan="3"><b>No</b></th>
			<th rowspan="3"><b>UP3AD</b></th>
			<th colspan="6"><b>Jumlah Obyek</b></th>
			<th rowspan="2" colspan="2"><b>Jumlah</b></th>
		</tr>
		<tr>
			<?php
				foreach ( $this->Bulan->getAllByIdTriwulan($postTriwulan) as $th ) {
					echo "<th colspan=\"2\"><b>". $th['bulan'] ."</th>";
				}
			?>
		</tr>
		<tr>
			<?php
				foreach ( $this->Bulan->getAllByIdTriwulan($postTriwulan) as $th ) {
					foreach ( $this->Pd->getArrayBBNKB() as $thbbnkb ) {
						echo "<th><b>". $thbbnkb['nama_rekening'] ."</th>";
					}
				}
				foreach ( $this->Pd->getArrayBBNKB() as $thbbnkb ) {
					echo "<th><b>". $thbbnkb['nama_rekening'] ."</th>";
				}
			?>
		</tr>
	</thead>
	<tbody>
		<?php
			$arrJumlahPerBulan = array();
			$total_keseluruhan = array();
			
			$no = 1;
			foreach( $arrLokasi as $lokasi ) {
				$jmlperup3ad = array();
				echo '<tr>
					<td align="right">'. $no .'</td>
					<td>'. $lokasi['lokasi'] .'</td>';
				
				foreach ( $this->Bulan->getAllByIdTriwulan($postTriwulan) as $td ) {
					foreach ( $this->Pd->getArrayBBNKB() as $tdbbnkb ) {
						$jml = empty($arrTransaksi[$tdbbnkb['id_rek_pd']][$lokasi['id_lokasi']][$td['id_bulan']]['realisasi']) ? 0 : $arrTransaksi[$tdbbnkb['id_rek_pd']][$lokasi['id_lokasi']][$td['id_bulan']]['realisasi'];
						echo '<td align="right" class="str">'. number_format($jml, 0) .'</td>';
						$jmlperup3ad[$tdbbnkb['id_rek_pd']] += $jml;
						$arrJumlahPerBulan[$tdbbnkb['id_rek_pd']][$td['id_bulan']] += $jml;
					}
				}
				foreach ( $this->Pd->getArrayBBNKB() as $tdbbnkb2 ) {
					$jml_per_bbnkb = empty($jmlperup3ad[$tdbbnkb2['id_rek_pd']]) ? 0 : $jmlperup3ad[$tdbbnkb2['id_rek_pd']];
					echo '<td align="right" class="str">'. number_format($jml_per_bbnkb, 0) .'</td>';
					$total_keseluruhan[$tdbbnkb2['id_rek_pd']] += $jml_per_bbnkb;
				}
				
				echo '</tr>';
				$no++;
				
			}

		?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="2"><b>JUMLAH</b></td>
			<?php 
				foreach ( $this->Bulan->getAllByIdTriwulan($postTriwulan) as $tdfooter ) {
					foreach ( $this->Pd->getArrayBBNKB() as $tdbbnkbfooter ) {
						echo '<td align="right" class="str"><b>'. number_format($arrJumlahPerBulan[$tdbbnkbfooter['id_rek_pd']][$tdfooter['id_bulan']], 0) .'</b></td>';
					}
				}

				$total = 0;
				foreach ( $this->Pd->getArrayBBNKB() as $tdbbnkbfooter2 ) {
					echo '<td align="right" class="str"><b>'. number_format($total_keseluruhan[$tdbbnkbfooter2['id_rek_pd']], 0) .'</b></td>';
					$total += $total_keseluruhan[$tdbbnkbfooter2['id_rek_pd']];
				}
				$persentase = $getPd->total_target <= 0 ? 0 : ($total/$getPd->total_target)*100;
			?>
		</tr>
		<tr>
			<td colspan="8"><b>JUMLAH BBNKB I + BBNKB II</b></td>
			<td align="right" colspan="2" class="str"><b><?= number_format($total, 0) ?></b></td>
		</tr>
		<tr>
			<td colspan="8"><b>TARGET TRIWULAN <?= $getTriwulan ?></b></td>
			<td align="right" colspan="2" class="str"><b><?= number_format($getPd->total_target, 0) ?></b></td>
		</tr>
		<tr>
			<td colspan="8"><b>PROSENTASE CAPAIAN (%)</b></td>
			<td align="right" colspan="2" class="str"><b><?= number_format($persentase, 0) ?></b></td>
		</tr>
	</tfoot>
</table>
<div style="float:right;"><i>dicetak : <?= element('username', $this->Opsisite->getDataUser()) ?> ; <?= date('d-m-Y H:i:s') ?></i></div>