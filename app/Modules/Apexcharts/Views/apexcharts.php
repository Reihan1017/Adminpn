<div class="card">
	<div class="card-header">
		<h5 class="card-title"><?=$current_module['judul_module']?></h5>
	</div>
	<form method="get">
	<?php helper('html')?>
	</form>
	<div class="card-body">
		<?php
		if ($message['status'] == 'error') {
			show_message($message);
		}
		?>
		<form method="get" action="" class="form-horizontal">
			<div class="row mb-3">
				<label class="col-sm-2 col-md-2 col-lg-2 col-xl-1 col-form-label">Tahun</label>
				<div class="col-sm-5 form-inline">
					<?=options(['name' => 'tahun'], [2019 => 2019, 2020 => 2020, 2021 => 2021], $tahun )?>
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
			</div>
		</form>
		<?php
		if ($message['status'] == 'ok') {
			?>
			<div class="row mb-3">
				<div class="col-12 col-md-12 col-lg-12 col-xl-6" style="overflow-x:auto">
					<div id="chart-container" style="min-width:550px; margin:auto"></div>
				</div>
				<div class="col-12 col-md-12 col-lg-12 col-xl-6" style="overflow-x:auto">
					<div id="pie-container" style="min-width:400px; margin:auto"></div>
				</div>
			</div>	
			<script>
			function dynamicColors() {
				var r = Math.floor(Math.random() * 255);
				var g = Math.floor(Math.random() * 255);
				var b = Math.floor(Math.random() * 255);
				return "rgba(" + r + "," + g + "," + b + ", 0.9)";
			}
			
			var options = {
				title: {
					text: 'Data Penjualan ' + <?=$tahun?>,
					floating: false,
					offsetY: 0,
					align: 'center',
					style: {
						color: '#444',
						fontWeight:  'normal'
					}
				},
				series: [{
						name: 'Penjualan',
						data: [
							<?php
							foreach ($penjualan as $val) {
							$total[] = $val['total'];
							}

							echo join(',', $total);
							?>
						]
					}, {
						name: 'Pembelian',
						data: [
							<?php
							foreach ($penjualan as $val) {
							$total_beli[] = $val['total_beli'];
							}

							echo join(',', $total_beli);
							?>
						]
					}, {
						name: 'Gross Profit',
						data: [
						<?php
						foreach ($penjualan as $val) {
						$total_profit[] = $val['total'] - $val['total_beli'];
						}

						echo join(',', $total_profit);
						?>
						]
					}
				],
				chart: {
					type: 'bar',
					height: 350
				},
				theme: {
					mode: 'light', 
					palette: 'palette1'
				},
				plotOptions: {
					bar: {
						horizontal: false,
						columnWidth: '55%',
						endingShape: 'rounded'
					},
				},
				dataLabels: {
					enabled: false
				},
				stroke: {
					show: true,
					width: 2,
					colors: ['transparent']
				},
				xaxis: {
					categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
				},
				yaxis: {
					title: {
						text: 'Dalam Rupiah (Rp.)',
						style: {
							fontWeight: 400
						}
					},
					labels: {
						formatter: function (value) {
							return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
						}
					}
				},
				fill: {
					opacity: 1
				},
				tooltip: {
					y: {
						formatter: function (val) {
							return "Rp. " + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
						}
					}
				}
			};

			var chart = new ApexCharts(document.querySelector("#chart-container"), options);
			chart.render();
			
			/* Pie Chart */
			
			var options = {
				title: {
					text: 'Data Penjualan ' + <?=$tahun?>,
					floating: false,
					offsetY: 0,
					align: 'left',
					margin: 10,
					style: {
						color: '#444',
						fontWeight:  'normal'
					}
				},
				colors : [
					'#008FFB', '#00E396', '#FEB019', '#FF4560', '#775DD0', '#546E7A', '#F86624'
				],
				/*
				colors:[
					<?php
					foreach ($item_terjual as $val) {
						$func[] = 'dynamicColors()';
					}
							
					echo join(',', $func);
					?>
				],*/
				series: [
						<?php
							foreach ($item_terjual as $val) {
								$jumlah[] = $val['jml'];
							}
							
							echo join(',', $jumlah);
						?>
					],
					chart: {
					width: 500,
					type: 'pie',
				},
				plotOptions: {
					pie: {
						expandOnClick: true
					}
				},
				theme: {
					mode: 'light', 
					palette: 'palette1'
				},
				dataLabels: {
					style: {
						fontSize: '12px',
						fontWeight: 'normal'
					},
					dropShadow: {
						enabled: false,
					}
				},
				labels: [
					<?php
						foreach ($item_terjual as $val) {
							$nama[] = $val['nama'];
						}
						
						echo '"' . join('","', $nama) . '"';
					?>
				],
				legend: {
					position: 'right'
				},
				responsive: [{
					breakpoint: 640,
					options: {
						chart: {
							width: '100%'
						},
						legend: {
							position: 'bottom'
						}
					}
				}]
			};

			var chart = new ApexCharts(document.querySelector("#pie-container"), options);
			chart.render();

		</script>
	<?php
	}?>
	</div>
</div>