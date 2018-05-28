<center><h2>LAPORAN <?= $header ?></h2></center>
<br>
<table style="overflow:scroll; font-size: 10px; border-collapse: collapse;" border=1 cellpadding="2">
<thead>
<tr>
<th rowspan="2" width="30px">No.</th>
<th rowspan="2" width="200px">BULAN</th>
<th colspan="<?= count($this->Fungsi->arrKodeJenisPajak()) ?>"><?= $th ?></th>
<th rowspan="2" width="70px">JUMLAH</th>
</tr>
<tr>
<?php
	foreach( $this->Fungsi->arrKodeJenisPajak() as $kode ){
		echo '<th width="70px">'. $kode['kode_jenis'] .'</th>';
	}
?>
</tr>
</thead>
<tbody>
<?php
$arrTotalperColumn = array();
$no = 1;
foreach( $this->Bulan->getAllData()->result() as $bulan )
{
	$TotalPerRowBulan = 0;
	echo '<tr>
	<td align="right" width="30px">'. $no .'.</td>
	<td width="200px">'. $bulan->bulan .'</td>';
	foreach( $this->Fungsi->arrKodeJenisPajak() as $kode ){
		$total = empty($arrTrx[$bulan->id_bulan][$kode['id_jenis']]) ? 0 : $arrTrx[$bulan->id_bulan][$kode['id_jenis']] ;
		echo '<td width="70px" align="right" class="str">'. $this->Fungsi->formatangkalaporan($total) .'</td>';
		$arrTotalperColumn[$kode['id_jenis']][] = $total;
		$TotalPerRowBulan += $total;
	}
	echo '<td align="right" width="70px" class="str">'. $this->Fungsi->formatangkalaporan($TotalPerRowBulan) .'</td>
	</tr>';
	if( $no > 1 ) {
		$no ++;
		$subtotal2 = 0;
		echo '<tr style="color:red;">
		<td align="right" width="30px"><b>'. $no .'.</b></td>
		<td width="200px"><b>JUMLAH</b></td>';
		foreach( $this->Fungsi->arrKodeJenisPajak() as $kode ){
			
			$subtotal = 0;
			foreach ( $arrTotalperColumn[$kode['id_jenis']] as $r )
			{
				$subtotal += $r;
			}
			//Only PHP 5.6 dan seterusnya
			//$subtotal = empty(array_sum($arrTotalperColumn[$kode['id_jenis']])) ? 0 : array_sum($arrTotalperColumn[$kode['id_jenis']]) ;
			echo '<td width="70px" align="right" class="str"><b>'. $this->Fungsi->formatangkalaporan($subtotal) .'</b></td>';
			$subtotal2 += $subtotal;
		}
		echo '<td align="right" width="70px" class="str"><b>'. $this->Fungsi->formatangkalaporan($subtotal2) .'</b></td>
		</tr>';
	}
	$no ++;
}
?>
</tbody>
</table>
<div><i>dicetak : <?= element('username', $this->Opsisite->getDataUser()) ?> ; <?= date('d-m-Y H:i:s') ?></i></div>