<center>
<h2><?= $title ?></h2>
<h2>BERDASARKAN <?= $getDasar ?> SPBU</h2>
<h2>BULAN : <?= $getBulan .' '. $getTahun ?></h2>
</center>
<br>
<div style="float:right"><?= $this->Satuan->printSatuan($getSatuan) ?></div>
<table id="tabel">
	<thead>
		<tr>
			<th><b>No</b></th>
			<th><b>UP3AD</b></th>
			<th><b>Jumlah SPBU</b></th>
			<?php
				foreach ( $arrJenisPbbkb as $th ) {
					echo "<th><b>". $th['item_pbbkb'] ."</th>";
				}
			?>
			<th><b>Jumlah</b></th>
		</tr>
	</thead>
	<tbody>
		<?php
			$arrJumlahPerJenis = array();
			$total_keseluruhan = 0;
			$no = 1;
			$jmlspbu = 0;
			foreach( $arrLokasi as $lokasi ) {
				$countSpbu = empty($getJmlSPBU[$lokasi['id_lokasi']]) ? 0 : $getJmlSPBU[$lokasi['id_lokasi']];
				echo '<tr>
					<td align="right">'. $no .'</td>
					<td>'. $lokasi['lokasi'] .'</td>
					<td align="center">'. $countSpbu .'</td>';
				
				$jmlperjenis = 0;
				foreach ( $arrJenisPbbkb as $td ) {
					$jml = empty($getEntrian[$lokasi['id_lokasi']][$td['id_item_pbbkb']]) ? '0' : $getEntrian[$lokasi['id_lokasi']][$td['id_item_pbbkb']];
					echo '<td align="right" width="8%" class="str">'. $this->Satuan->printValue($jml, $getSatuan) .'</td>';
					$jmlperjenis += $jml;
					$arrJumlahPerJenis[$td['id_item_pbbkb']] += $jml;
				}

				echo '<td align="right" width="8%" class="str">'. $this->Satuan->printValue($jmlperjenis, $getSatuan) .'</td>';
				echo '</tr>';
				$no++;
				$total_keseluruhan += $jmlperjenis;
				$jmlspbu += $countSpbu;
			}

		?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="2"><b>Jumlah Bulan ini</b></td>
			<td align="center"><b><?= number_format($jmlspbu) ?></b></td>
			<?php 
				foreach ( $arrJenisPbbkb as $tdfooter ) {
					echo '<td align="right" class="str"><b>'. $this->Satuan->printValue($arrJumlahPerJenis[$tdfooter['id_item_pbbkb']], $getSatuan) .'</b></td>';
				}
			?>
			<td align="right" class="str"><b><?= $this->Satuan->printValue($total_keseluruhan, $getSatuan) ?></b></td>
		</tr>
		<tr>
			<td colspan="3"><b>Jumlah s.d Bulan Lalu</b></td>
			<?php 
				$total_bulan_lalu = 0;
				foreach ( $arrJenisPbbkb as $tdfooter ) {
					echo '<td align="right" class="str"><b>'. $this->Satuan->printValue($getEntrianLalu[$tdfooter['id_item_pbbkb']], $getSatuan) .'</b></td>';
					$total_bulan_lalu += $getEntrianLalu[$tdfooter['id_item_pbbkb']];
				}
			?>
			<td align="right" class="str"><b><?= $this->Satuan->printValue($total_bulan_lalu, $getSatuan) ?></b></td>
		</tr>
		<tr>
			<td colspan="3"><b>Jumlah s.d Bulan Ini</b></td>
			<?php 
				$total_sd_bulan_ini = 0;
				foreach ( $arrJenisPbbkb as $tdfooter ) {
					echo '<td align="right" class="str"><b>'. $this->Satuan->printValue($getEntrianLalu[$tdfooter['id_item_pbbkb']]+$arrJumlahPerJenis[$tdfooter['id_item_pbbkb']], $getSatuan) .'</b></td>';
					$total_sd_bulan_ini += $getEntrianLalu[$tdfooter['id_item_pbbkb']]+$arrJumlahPerJenis[$tdfooter['id_item_pbbkb']];
				}
			?>
			<td align="right" class="str"><b><?= $this->Satuan->printValue($total_sd_bulan_ini, $getSatuan) ?></b></td>
		</tr>
	</tfoot>
</table>
<div style="float:right;"><i>dicetak : <?= element('username', $this->Opsisite->getDataUser()) ?> ; <?= date('d-m-Y H:i:s') ?></i></div>