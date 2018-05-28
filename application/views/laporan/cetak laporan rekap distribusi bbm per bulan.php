<center>
<h2><?= $header ?></h2>
<h2>DATA PENYEDIA BAHAN BAKAR MINYAK (BBM) YANG MELAKUKAN PENJUALAN BBM</h2>
<h2><?= $header2 ?></h2>
<h2>BULAN : <?= $getBulan .' '. $getTahun ?></h2>
</center>
<br>
<div style="float:right"><?= $this->Satuan->printSatuan($getSatuan) ?></div>
<table id="tabel">
	<thead>
		<tr>
			<th><b>No</b></th>
			<th><b>Tanggal DO</b></th>
			<th><b>Badan Usaha Penyedia BBM</b></th>
			<th><b>Kota</b></th>
			<?php
				foreach ( $arrJenisPbbkb as $th ) {
					echo "<th><b>". $th['item_pbbkb'] ."</th>";
				}
			?>
			<th><b>Perusahaan Penerima BBM</b></th>
		</tr>
	</thead>
	<tbody>
		<?php
			
			$arrJumlahPerJenis = array();
			$total_keseluruhan = 0;
			$no = 1;

			foreach( $getRow as $tdrow ) {
				echo '<tr>
					<td align="right">'. $no .'</td>
					<td align="center">'. $tdrow['tgl_input'] .'</td>
					<td>'. $tdrow['nama_penyalur'] .'</td>
					<td align="center">'. $tdrow['kota_asal'] .'</td>';
				
				$jmlperjenis = 0;
				foreach ( $arrJenisPbbkb as $td ) {
					$jml = empty($getTransaction[$tdrow['id_distribusi_bbm']][$td['id_item_pbbkb']]) ? '0' : $getTransaction[$tdrow['id_distribusi_bbm']][$td['id_item_pbbkb']];
					echo '<td align="right" width="8%" class="str">'. $this->Satuan->printValue($jml, $getSatuan) .'</td>';
					$jmlperjenis += $jml;
					$arrJumlahPerJenis[$td['id_item_pbbkb']] += $jml;
				}

				echo '<td>'. $tdrow['nama_spbu'] .'</td>';
				echo '</tr>';
				$no++;
				$total_keseluruhan += $jmlperjenis;
			}

		?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="4"><b>Jumlah Bulan ini</b></td>
			<?php 
				foreach ( $arrJenisPbbkb as $tdfooter ) {
					echo '<td align="right" class="str"><b>'. $this->Satuan->printValue($arrJumlahPerJenis[$tdfooter['id_item_pbbkb']], $getSatuan) .'</b></td>';
				}
			?>
			<td align="right" class="str"><b><?= $this->Satuan->printValue($total_keseluruhan, $getSatuan) ?></b></td>
		</tr>
		<tr>
			<td colspan="4"><b>Jumlah s.d Bulan Lalu</b></td>
			<?php 
				$total_bulan_lalu = 0;
				foreach ( $arrJenisPbbkb as $tdfooter ) {
					echo '<td align="right" class="str"><b>'. $this->Satuan->printValue($getTotalLalu[$tdfooter['id_item_pbbkb']], $getSatuan) .'</b></td>';
					$total_bulan_lalu += $getTotalLalu[$tdfooter['id_item_pbbkb']];
				}
			?>
			<td align="right" class="str"><b><?= $this->Satuan->printValue($total_bulan_lalu, $getSatuan) ?></b></td>
		</tr>
		<tr>
			<td colspan="4"><b>Jumlah s.d Bulan Ini</b></td>
			<?php 
				$total_sd_bulan_ini = 0;
				foreach ( $arrJenisPbbkb as $tdfooter ) {
					echo '<td align="right" class="str"><b>'. $this->Satuan->printValue($getTotalLalu[$tdfooter['id_item_pbbkb']]+$arrJumlahPerJenis[$tdfooter['id_item_pbbkb']], $getSatuan) .'</b></td>';
					$total_sd_bulan_ini += $getTotalLalu[$tdfooter['id_item_pbbkb']]+$arrJumlahPerJenis[$tdfooter['id_item_pbbkb']];
				}
			?>
			<td align="right" class="str"><b><?= $this->Satuan->printValue($total_sd_bulan_ini, $getSatuan) ?></b></td>
		</tr>
	</tfoot>
</table>
<br>
<br>
<?php if ( $getLokasi->id_lokasi != '' ) { ?>
<table style="width:100%;">
	<tr>
		<td width="30%" style="font-size:9pt;"></td>
		<td width="40%"></td>
		<td width="30%" style="font-size:8pt;" align="center">
		<?= $getLokasi->kota .', '. date('d-m-Y') ?>
		<br>
		</td>
	</tr>
	<tr>
		<td width="30%" style="font-size:9pt;"></td>
		<td width="30%"></td>
		<td width="40%" style="font-size:9pt;">
			<center>
				<?= $getParaf->paraf_plt ?>KEPALA <?= $getLokasi ?>
				<br>
				<?= $getParaf->jbtn_plt ?>
				<br>
				<br>
				<br>
				<br>
				<span style="text-decoration:underline;"><?= $getParaf->nama_pegawai ?></span>
				<br>
				<?= $getParaf->pangkat ?>
				<br>
				NIP. <?= $getParaf->nip ?>
			</center>
		</td>
	</tr>
</table>
<?php } ?>
<br>
<div><i>dicetak : <?= element('username', $this->Opsisite->getDataUser()) ?> ; <?= date('d-m-Y H:i:s') ?></i></div>