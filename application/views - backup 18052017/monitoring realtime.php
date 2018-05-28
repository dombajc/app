<style>
	body {
		background : url(<?= base_url('img/blur-background09.jpg') ?>);
		
	}
	
	#title-display {
		margin-top : -0.5em;
	}
	
	.content {
		background : none;
	}
	
	.tertinggi > h4 {
		background : #78DD76;
		padding : 5px 3px;
		color : #fff;
		margin-top : 0;
	}
	
	.tertinggi {
		border : 2px solid #78DD76;
	}
	
	.terendah > h4 {
		background : #FF6861;
		padding : 5px 3px;
		color : #fff;
		margin-top : 0;
	}
	
	.terendah {
		border : 2px solid #FF6861;
	}
	
	.info > h4 {
		background : #408CCA;
		padding : 5px 3px;
		color : #fff;
		margin-top : 0;
	}
	
	.info {
		border : 2px solid #408CCA;
	}
	
	.bigger {
		font-size: 4.4em;
	}
	
	.box-centent {
		padding : 12px 15px;
	}
	
	.box-content > table {
		margin : 0;
	}
	
	.text-sumber { font-size: 12px; color : #fff; }
</style>
<h1 id="title-display">RESUME PELAPORAN DAN PEMBUKUAN<small class="pull-right text-sumber">Sumber : Aplikasi Pembukuan dan Pelaporan ( pad-dppad.jatengprov.go.id/app )</small></h1>
<div class="row ">
    <div class="col-sm-3">
    	<div id="tile1" class="tile box tertinggi">
        <h4>CAPAIAN TERTINGGI D2D</h4>
			<div class="box-centent">
				<div class="carousel slide" data-ride="carousel">
					<div class="carousel-inner">
					<?php
					$up = 1;
					
					foreach( $arrTriwulan as $r_up ) {
						$class = $up == 1 ? ' active' : '';
						echo '<div class="item'. $class .'">
						   Triwulan '. $r_up['triwulan'] .'
						   <h4 id="label_up_'. $r_up['id_triwulan'] .'"></h4>
						   <h1 id="persen_up_'. $r_up['id_triwulan'] .'" class="pull-right"></h1>
						   <span class="fa fa-thumbs-up bigger text-success" aria-hidden="true"></span>
						</div>';
						$up++;
					}
					?>
					</div>
				</div>
			</div>
        
        </div>
    </div>
	<div class="col-sm-3">
		<div id="tile2" class="tile box terendah">
    	 <h4>CAPAIAN TERENDAH D2D</h4>
		 <div class="box-centent">
			<div class="carousel slide" data-ride="carousel">
			  <!-- Wrapper for slides -->
			  <div class="carousel-inner">
				<?php
				$down = 1;
				
				foreach( $arrTriwulan as $r_down ) {
					$class = $down == 1 ? ' active' : '';
					echo '<div class="item'. $class .'">
					   Triwulan '. $r_down['triwulan'] .'
					   <h4 id="label_down_'. $r_down['id_triwulan'] .'"></h4>
					   <h1 id="persen_down_'. $r_down['id_triwulan'] .'" class="pull-right"></h1>
					   <span class="fa fa-thumbs-down bigger text-danger" aria-hidden="true"></span>
					</div>';
					$down++;
				}
				?>
			  </div>
			</div>
		 </div>
		</div>
	</div>
	<div class="col-sm-3">
		<div id="tile3" class="tile box info">
			<h4>Rata-rata Kinerja Pegawai</h4>
			<div class="box-content">
				<table class="table">
					<thead>
						<tr>
							<th class="text-center">PEGAWAI</th>
							<?php
								foreach ( $arrTriwulan as $th ) {
									echo '<th class="text-center">'. $th['triwulan'] .'</th>';
								}
							?>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Non ASN</td>
							<?php
								foreach ( $arrTriwulan as $tr ) {
									echo '<td align="center"><label id="label_nonasn_'. $tr['id_triwulan'] .'"></label></td>';
								}
							?>
						</tr>
						<tr>
							<td>ASN</td>
							<?php
								foreach ( $arrTriwulan as $tr2 ) {
									echo '<td align="center"><label id="label_asn_'. $tr2['id_triwulan'] .'"></label></td>';
								}
							?>
						</tr>
						<tr>
							<td>Keseluruhan</td>
							<?php
								foreach ( $arrTriwulan as $tr3 ) {
									echo '<td align="center"><label id="label_semua_'. $tr3['id_triwulan'] .'"></label></td>';
								}
							?>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	
</div>

<div class="row">
	<div class="col-sm-3">
		<div id="tile4" class="tile">
			<div id="div-grafik-pkb"></div>
		</div>
	</div>
	<div class="col-sm-3">
		<div id="tile5" class="tile chart2">
			<div id="div-grafik-bbnkb"></div>
		</div>
	</div>
	<div class="col-sm-3">
		<div id="tile6" class="tile chart3">
			<div id="div-grafik-pbbkb"></div>
		</div>
	</div>
	<div class="col-sm-3">
		<div id="tile7" class="tile chart4">
			<div id="div-grafik-pap"></div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?= base_url('plugins/highchart/highcharts.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/highchart/highcharts-3d.js') ?>"></script>

<script>
	$(function(){
			
		$.ajax({
			url: '<?= base_url("display/api_json") ?>',
			dataType: "JSON",
			success: function(html) {
				setTimeout(function() {
					$.unblockUI({
						onUnblock: function() {
							
							$.each(html.d2dtertinggi, function (index, value) {
								$('#label_up_'+ index).text(value.lokasi);
								$('#persen_up_'+ index).text(value.persen);
							});
							
							$.each(html.d2dterendah, function (index, value) {
								$('#label_down_'+ index).text(value.lokasi);
								$('#persen_down_'+ index).text(value.persen);
							});
							
							$.each(html.rata2, function (index, value) {
								$('#label_nonasn_'+ index).text(value.nonasn);
								$('#label_asn_'+ index).text(value.asn);
								
								var avg = (parseInt(value.nonasn) + parseInt(value.asn)) / 2;
								$('#label_semua_'+ index).text(avg);
							});
							
							load_grafik( 'div-grafik-pkb', html.getpkb, 'PKB' );
							load_grafik( 'div-grafik-bbnkb', html.getbbnkb, 'BBNKB' );
							load_grafik( 'div-grafik-pbbkb', html.getpbbkb, 'PBBKB' );
							load_grafik( 'div-grafik-pap', html.getpap, 'PAP' );
							
						}
					});
				}, 1000);
			},
			beforeSend: function() {
				loadoverlay();
			},
			error: function(xhr, ajaxOptions, thrownError) {
				setTimeout(function() {
					$.unblockUI({
						onUnblock: function() {
							alert(xhr.responseText);
						}
					});
				}, 1000);
			}
		});
		
	});
	
	Highcharts.theme = {
	   colors: ["#FF6861", "#78DD76", "#90ee7e", "#7798BF", "#aaeeee", "#ff0066", "#eeaaee",
		  "#55BF3B", "#DF5353", "#7798BF", "#aaeeee"],
	   chart: {
		  backgroundColor: null,
		  style: {
			 fontFamily: "Dosis, sans-serif"
		  }
	   },
	   title: {
		  style: {
			 fontSize: '16px',
			 fontWeight: 'bold',
			 textTransform: 'uppercase'
		  }
	   },
	   tooltip: {
		  borderWidth: 0,
		  backgroundColor: 'rgba(219,219,216,0.8)',
		  shadow: false
	   },
	   legend: {
		  itemStyle: {
			 fontWeight: 'bold',
			 fontSize: '13px'
		  }
	   },
	   xAxis: {
		  gridLineWidth: 1,
		  labels: {
			 style: {
				fontSize: '12px'
			 }
		  }
	   },
	   yAxis: {
		  minorTickInterval: 'auto',
		  title: {
			 style: {
				textTransform: 'uppercase'
			 }
		  },
		  labels: {
			 style: {
				fontSize: '12px'
			 }
		  }
	   },
	   plotOptions: {
		  candlestick: {
			 lineColor: '#404048'
		  }
	   },


	   // General
	   background2: '#F0F0EA'
	   
	};
	
	// Apply the theme
	Highcharts.setOptions(Highcharts.theme);
	
	function load_grafik( getDIV, json, title )
	{
		
		var options = {
			chart: {
				renderTo: getDIV,
				type: 'column',
				options3d: {
					enabled: true,
					alpha: 15,
					beta: 15,
					depth: 50,
					viewDistance: 25
				}
			},
			title: {
				text: 'Target dan Realisasi Jumlah Obyek '+ title,
				x: -20 //center
			},
			subtitle: {
				text: '',
				x: -20
			},
			xAxis: {
				categories: []
			},
			yAxis: {
				title: {
					text: 'Obyek'
				},
				showFirstLabel: false,
			},
			tooltip: {
				formatter: function () {

					return '<b>' + this.series.name + '</b><br/>Triwulan ' +
							this.x + ': ' + this.y;
				}
			},
			plotOptions: {
				column: {
					dataLabels: {
						enabled: true,
						formatter: function() {
							return this.y;
						}
						
					}
				}
			},
			series: []
		}
		
		options.xAxis.categories = json[0]['data'];
		options.series[0] = json[1];
		options.series[1] = json[2];
		chart = new Highcharts.Chart(options);
	}
</script>