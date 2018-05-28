<center>
<h2>REKAPITULASI KEGIATAN DOOR TO DOOR PEGAWAI</h2>
<h2><?= $getNama ?></h2>
<h2>TRIWULAN <?= $getTriwulan ?> - TAHUN <?= $getTahun ?></h2>
</center>
<br>
<table id="tabel">
	<thead>
		<tr>
			<th rowspan="2">No</th>
			<th rowspan="2">Nama Pegawai</th>
			<th rowspan="2">NIP</th>
			<th rowspan="2">Lokasi D2D</th>
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
			foreach( $arrPegawai as $pegawai ) {
				echo '<tr>
					<td align="right">'. $no .'</td>
					<td>'. $pegawai['nama_pegawai'] .'</td>
					<td align="center">'. $pegawai['nip'] .'</td>
					<td align="center">'. $pegawai['nama_lokasi'] .'</td>
					<td align="right" class="str">'. number_format($pegawai['total'], 0) .'</td>';
				
				$jmlperpegawai = 0;
				foreach ( $this->Bulan->getAllByIdTriwulan($postTriwulan) as $td ) {
					$jml = empty($arrTrx[$pegawai['id_mutasi']][$td['id_bulan']]['jumlah']) ? 0 : $arrTrx[$pegawai['id_mutasi']][$td['id_bulan']]['jumlah'];
					echo '<td align="right">'. $jml .'</td>';
					$jmlperpegawai += $jml;
					$arrJumlahPerBulan[$td['id_bulan']] += $jml;
				}
				echo '<td align="right" class="str">'. number_format($jmlperpegawai, 0) .'</td>';
				echo '<td align="right" class="str">'. number_format( ($jmlperpegawai / $pegawai['total']) * 100, 2 ) .'</td>';
				echo '</tr>';
				$no++;
				$total_target += $pegawai['total'];
				$total_keseluruhan += $jmlperpegawai;
			}
		?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="4">Total Keseluruhan</td>
			<td align="right" class="str"><?= number_format($total_target, 0) ?></td>
			<td align="right" class="str"><?= number_format($arrJumlahPerBulan[1], 0) ?></td>
			<td align="right" class="str"><?= number_format($arrJumlahPerBulan[2], 0) ?></td>
			<td align="right" class="str"><?= number_format($arrJumlahPerBulan[3], 0) ?></td>
			<td align="right" class="str"><?= number_format($total_keseluruhan, 0) ?></td>
			<td align="right" class="str"><?= number_format( ($total_keseluruhan / $total_target) * 100, 2 ) ?></td>
		</tr>
	</tfoot>
</table>
<div style="float:right;"><i>dicetak : <?= element('username', $this->Opsisite->getDataUser()) ?> ; <?= date('d-m-Y H:i:s') ?></i></div>