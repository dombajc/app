<center><?= $header ?></center>
<table width="100%" cellpadding="5" id="tabel" border=1>
	<thead>
		<tr>
			<th rowspan="2">No.</th>
			<th rowspan="2">UP3AD</th>
			<th rowspan="2" width="5%">Kinerja Pendataan</th>
			<th colspan="12">Obyek Pendataan Bulanan</th>
		</tr>
		<tr>
			<?php
				foreach( $this->Bulan->getAllData()->result() as $thbulan )
				{
					echo '<th width="6%">'. $thbulan->bulan .'</th>';
				}
			?>
		</tr>
	</thead>
	<tbody>
		<?php
			$no = 1;
			$totaloby = 0;
			$arrJml = array();
			foreach( $this->Lokasi->showAllUpad()->result() as $lokasi )
			{
				$jmlOby = empty($arr_per_lokasi[$lokasi->id_lokasi]) ? 0 : $arr_per_lokasi[$lokasi->id_lokasi];
				
				echo '<tr>
				<td>'. $no .'</td>
				<td>'. $lokasi->lokasi .'</td>
				<td align="center">'. $jmlOby .'</td>';

				foreach( $this->Bulan->getAllData()->result() as $bulan )
				{
					$jmlTr = empty($arrTr[$lokasi->id_lokasi][$bulan->id_bulan]) ? 0 : $arrTr[$lokasi->id_lokasi][$bulan->id_bulan];
					echo '<td align="right">'. $jmlTr .'</td>';
					$arrJml[$bulan->id_bulan] += $jmlTr;
				}
				
				echo '</tr>';
				$no++;
				$totaloby += $jmlOby;
			}
		?>
	</tbody>
	<tfoot>
		<tr>
		<td colspan="2" align="center"><b>JUMLAH</b></td>
		<td align="center"><?= number_format($totaloby) ?></td>
		<?php
			foreach( $this->Bulan->getAllData()->result() as $footbulan )
			{
				echo '<td align="right">'. $arrJml[$footbulan->id_bulan] .'</td>';
			}
		?>
		
		</tr>
	</tfoot>
</table>
<div style="float:right;"><i>dicetak : <?= element('username', $this->Opsisite->getDataUser()) ?> ; <?= date('d-m-Y H:i:s') ?></i></div>