<style type="text/css">
	table thead tr {
		background: #403e3d;
	  	/*font-size: 3.4em;*/
	  	color: #fff;
	  	font-weight: 700;
	  	padding: 20px 0;
	  	text-shadow: 0 1px 1px rgba(0, 0, 0, 0.4);
	}
	td.title {
	 	color: #FFF;
		background: #e95846;
		padding: 20px 0;
		/*font-size: 2em;*/
		text-transform: uppercase;
		text-shadow: 0 1px 1px rgba(0, 0, 0, 0.4);
	}

	.td_01 {
		background-color: #FFBB6C;
	}
	.td_02 {
		background-color: #C6E6D1;
	}
	.td_03 {
		background-color: #C5D9F1;
	}
	.td_04 {
		background-color: #B8A8CB;
	}
	.td-title {
		background: #FFF;
  		color: #403d3a;
	}
</style>
<table class="table">
<thead>
	<tr>
		<th>Keterangan</th>
		<?php
			foreach( $this->Triwulan->getAllData()->result() as $th )
			{
				echo '<th>Triwulan '. $th->triwulan .'</th>';
			}
		?>
	</tr>
</thead>
<tbody>
<tr>
	<td colspan="5" class="title">
	Capaian tertinggi Kinerja Door To Door
	</td>
</tr>
<tr>
	<td class="td-title">
	LOKASI<br>(%)
	</td>
	<?php
		foreach( $this->Triwulan->getAllData()->result() as $td )
		{
			$upadTertinggi = $this->Trxd2d->getPersentaseDashboardUpadTertinggi($this->Thanggaran->getIdThAktif(), $td->id_triwulan);
			echo '<td class="text-center td_'. $td->id_triwulan .'">'. $upadTertinggi->lokasi .'<br>( '. number_format((($upadTertinggi->total_input/$upadTertinggi->total_target)*100),2) .' % )</td>';
		}
	?>
</tr>
<tr>
	<td colspan="5" class="title">
	Capaian terendah Kinerja Door To Door
	</td>
</tr>
<tr>
	<td class="td-title">
	LOKASI<br>(%)
	</td>
	<?php
		foreach( $this->Triwulan->getAllData()->result() as $td2 )
		{
			$upadTerendah = $this->Trxd2d->getPersentaseDashboardUpadTerendah($this->Thanggaran->getIdThAktif(), $td2->id_triwulan);
			$persen = $upadTerendah->total_input > 0 && $upadTerendah->total_target > 0 ? (($upadTerendah->total_input/$upadTerendah->total_target)*100) : 0;
			echo '<td class="text-center td_'. $td2->id_triwulan .'">'. $upadTerendah->lokasi .'<br>( '. number_format( $persen ,2) .' % )</td>';
		}
	?>
</tr>
<tr>
	<td colspan="5" class="title">
	Capaian Rata - Rata Obyek D2D per bulan
	</td>
</tr>
<tr>
	<td class="td-title">
	Pegawai ASN
	</td>
	<?php
		foreach( $this->Triwulan->getAllData()->result() as $td3 )
		{
			$rata2asn = $this->Trxd2d->getRata($this->Thanggaran->getIdThAktif(), $td3->id_triwulan, '33');
			echo '<td class="text-right td_'. $td3->id_triwulan .'">'. number_format( $rata2asn ) .'</td>';
		}
	?>
</tr>
<tr>
	<td class="td-title">
	Pegawai Non ASN
	</td>
	<?php
		foreach( $this->Triwulan->getAllData()->result() as $td4 )
		{
			$rata2non = $this->Trxd2d->getRata($this->Thanggaran->getIdThAktif(), $td4->id_triwulan, '99');
			echo '<td class="text-right td_'. $td4->id_triwulan .'">'. number_format( $rata2non ) .'</td>';
		}
	?>
</tr>
<tr>
	<td class="td-title">
	Pegawai Keseluruhan
	</td>
	<?php
		foreach( $this->Triwulan->getAllData()->result() as $td5 )
		{
			$rata2 = $this->Trxd2d->getRata($this->Thanggaran->getIdThAktif(), $td5->id_triwulan);
			echo '<td class="text-right td_'. $td5->id_triwulan .'">'. number_format( $rata2 ) .'</td>';
		}
	?>
</tr>
</tbody>
</table>

<table class="table">
<thead>
	<tr>
		<th rowspan="2">
			PAJAK DAERAH
		</th>
		<?php
			foreach( $this->Triwulan->getAllData()->result() as $thpd )
			{
				echo '<th colspan="3">Triwulan '. $thpd->triwulan .'</th>';
			}
		?>
	</tr>
	<tr>
		<?php
			for( $col=1; $col<=$this->Triwulan->getAllData()->num_rows(); $col++ )
			{
				echo '<th>Target</th><th>Realisasi</th><th>%</th>';
			}
		?>
	</tr>
</thead>
<tbody>
<?php
	$arrTarget = $this->Targetpelypd->getArrTarget($this->Thanggaran->getIdThAktif());
	$arrRealisasi = $this->Trxpd->getArrPungutan($this->Thanggaran->getIdThAktif());
	foreach ( $this->Pd->getRekeningInputTarget()->result() as $pd ) {
		echo '<tr>
		<td class="td-title">'. $pd->nama_rekening .'</td>';

		foreach( $this->Triwulan->getAllData()->result() as $trpd )
		{
			$target = empty($arrTarget[$trpd->id_triwulan][$pd->id_rek_pd]['target']) ? 0 : $arrTarget[$trpd->id_triwulan][$pd->id_rek_pd]['target'];
			$realisasi = empty($arrRealisasi[$trpd->id_triwulan][$pd->id_rek_pd]['realisasi']) ? 0 : $arrRealisasi[$trpd->id_triwulan][$pd->id_rek_pd]['realisasi'];
			$persen = $target > 0 && $realisasi > 0 ? (($realisasi/$target)*100) : 0;
			echo '<td class="text-right td_'. $trpd->id_triwulan .'">'. number_format($target) .'</td><td class="text-right td_'. $trpd->id_triwulan .'">'. number_format($realisasi) .'</td><td class="text-center td_'. $trpd->id_triwulan .'">'. number_format( $persen, 2) .' %</td>';
		}

		echo '</tr>';
	}
?>
</tbody>
</table>