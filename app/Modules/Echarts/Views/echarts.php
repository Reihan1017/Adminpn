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
		<form method="get" action="" class="form-horizontal mb-5">
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
				<div class="col-12 col-md-12 col-lg-12 col-xl-7"  style="overflow-x:auto">
					<div id="bar-container" style="width: 650px;height:400px;margin:auto"></div>
				</div>
				<div class="col-12 col-md-12 col-lg-12 col-xl-5" style="overflow-x:auto">
					<div id="pie-container" style="width: 400px; height:450px;margin:auto;"></div>
				</div>
			</div>	
			<script type="text/javascript">
			
				var barChart = echarts.init(document.getElementById('bar-container'));

				var option = {
					grid: {
						containLabel: true
					},
					title: {
						text: 'Data Penjualan ' + <?=$tahun?>,
						subtext: 'PT. Intertech Corporation',
						left: 'center',
						padding: 0,
						textStyle: {
							fontWeight: 'normal'
						}
					},
					toolbox: {
						feature: {
							dataZoom: {
								yAxisIndex: 'none'
							},
							restore: {},
							saveAsImage: {}
						}
					},
					tooltip: {
						formatter: function(a) {  return a.name + '<hr style="margin:5px 0;padding:0;border: 0; height: 1px; background: #CCCCCC"/>' + a.marker + a.seriesName + ' <strong>Rp. ' + a.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + '</strong>' }
					},
					legend: {
						bottom: 30,
						data:['Penjualan', 'Pembelian']
					},
					 xAxis: {
						type: 'category',
						data: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
					},
					yAxis: {
						type: 'value',
						name: 'Dalam Rupiah (Rp.)',
						nameRotate: 90,
						nameLocation: 'center',
						nameGap: 90,
						axisLabel : {
							formatter: function (value, index) {
								return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
							}
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
							],
							type: 'bar'
						},
						{
							name: 'Pembelian',
							data: [
								<?php
								foreach ($penjualan as $val) {
									$total_beli[] = $val['total_beli'];
								}
								
								echo join(',', $total_beli);
								?>
							],
							type: 'bar'
						}
					
					]
				};

				barChart.setOption(option);
				
				/* PIE Chart */
				
				var pieChart = echarts.init(document.getElementById('pie-container'));
				var option = {
						title: {
							text: 'Barang Terjual ' + <?=$tahun?>,
							subtext: 'PT. Intertech Corporation',
							left: 'center',
							top: 0,
							textStyle: {
								fontWeight: 'normal'
							}
						},
						toolbox: {
							feature: {
								saveAsImage: {}
							}
						},
						tooltip: {
							trigger: 'item'
						},
						legend: {
							orient: 'horizontal',
							top: 'bottom',
							left: 'center'
						},
						series: [
							{
								name: 'Barang Terjual',
								type: 'pie',
								selectedMode: 'single',
								radius: '50%',
								center: ['50%', '45%'],
								label : {
									formatter: function (data) {
										return data.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ' (' + data.percent.toFixed(1) + '%)'
									},
									overflow: 'break',
									position: 'outside'
								},
								data: [
									<?php
										foreach ($item_terjual as $val) {
											$jumlah[] = '{value: ' . $val['jml'] . ', name: "' . $val['nama'] . '"}';
										}
										
										echo join(',', $jumlah);
									?>
								],
								emphasis: {
									itemStyle: {
										shadowBlur: 10,
										shadowOffsetX: 0,
										shadowColor: 'rgba(0, 0, 0, 0.5)'
									}
								}
							}
						]
					};
					
				pieChart.setOption(option);
			
		</script>
	<?php
	}
	?>
	</div>
</div>