<center><?= $header ?></center>
<table width="100%" cellpadding="5" id="tabel" border=1>
	<thead>
		<tr>
			<th rowspan="3">No.</th>
			<th rowspan="3">PUSAT / SAMSAT</th>
			<th colspan="24">BULAN</th>
		</tr>
		<tr>
			<?php
				foreach( $get_semester as $th )
				{
					echo '<th colspan="3">'. $this->Fungsi->getBulan($th->id_bulan) .'</th>';
				}
			?>
		</tr>
		<tr>
			<?php
				foreach( $get_semester as $th2 )
				{
					echo '<th width="6%">APP</th><th width="6%">PAD OL</th><th width="6%">SELISIH</th>';
				}
			?>
		</tr>
	</thead>
	<tbody>
		<?php
			$no = 1;
			$totaloby = 0;
			$arr_total_app_per_bulan = array();
			$arr_total_pad_per_bulan = array();
			foreach( $this->Lokasi->arrLokasiIndukDanSamtu() as $lokasi )
			{
				
				echo '<tr>
				<td>'. $no .'</td>
				<td>'. $lokasi['lokasi'] .'</td>';
				foreach( $get_semester as $td )
				{
					$val_app = empty($trx_app[$lokasi['id_lokasi']][$td->id_bulan]) ? 0 : $trx_app[$lokasi['id_lokasi']][$td->id_bulan];
					$val_pad = empty($trx_padol[$lokasi['id_lokasi']][$td->id_bulan]) ? 0 : $trx_padol[$lokasi['id_lokasi']][$td->id_bulan];
					$selisih = $val_app - $val_pad;
					echo '<td width="6%" align="right">'. number_format($val_app,0) .'</td>
					<td width="6%" align="right">'. number_format($val_pad,0) .'</td>
					<td width="6%" align="right">'. number_format($selisih,0) .'</td>';
					$arr_total_app_per_bulan[$td->id_bulan] += $val_app;
					$arr_total_pad_per_bulan[$td->id_bulan] += $val_pad;
				}
				echo '</tr>';
				$no++;
			}
		?>
	</tbody>
	<tfoot>
		<tr>
		<td colspan="2" align="center"><b>JUMLAH</b></td>
		<?php
			foreach( $get_semester as $td2 )
			{
				echo '<td width="6%" align="right"><b>'. number_format($arr_total_app_per_bulan[$td2->id_bulan],0) .'</b></td>
					<td width="6%" align="right"><b>'. number_format($arr_total_pad_per_bulan[$td2->id_bulan],0) .'</b></td>
					<td width="6%" align="right"><b>'. number_format(($arr_total_app_per_bulan[$td2->id_bulan] - $arr_total_pad_per_bulan[$td2->id_bulan]),0) .'</b></td>';
			}
		?>
		</tr>
	</tfoot>
</table>
<div><i>dicetak : <?= element('username', $this->Opsisite->getDataUser()) ?> ; <?= date('d-m-Y H:i:s') ?></i></div>