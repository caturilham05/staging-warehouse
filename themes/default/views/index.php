<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
<script src="<?= $assets ?>js/highcharts.js"></script>
<?php
if($data) {
	?>
	<script type="text/javascript">

		$(document).ready(function () {

			Highcharts.theme = {
			   colors: ["#f45b5b", "#8085e9", "#8d4654", "#7798BF", "#aaeeee", "#ff0066", "#eeaaee",
			      "#55BF3B", "#DF5353", "#7798BF", "#aaeeee"],
			   chart: {
			      backgroundColor: '#f2f2f2',
			      style: {
			         fontFamily: "\"Ruda\",sans-serif"
			      }
			   },
			   title: {
			      style: {
			         color: 'black',
			         fontSize: '16px',
			         fontWeight: 'bold'
			      }
			   },
			   subtitle: {
			      style: {
			         color: 'black'
			      }
			   },
			   tooltip: {
			      borderWidth: 0
			   },
			   legend: {
			      itemStyle: {
			         fontWeight: 'bold',
			         fontSize: '13px'
			      }
			   },
			   xAxis: {
			      labels: {
			         style: {
			            color: '#6e6e70'
			         }
			      }
			   },
			   yAxis: {
			      labels: {
			         style: {
			            color: '#6e6e70'
			         }
			      }
			   },
			   plotOptions: {
			      series: {
			         shadow: true
			      },
			      candlestick: {
			         lineColor: '#404048'
			      },
			      map: {
			         shadow: false
			      }
			   },

			   navigator: {
			      xAxis: {
			         gridLineColor: '#D0D0D8'
			      }
			   },
			   rangeSelector: {
			      buttonTheme: {
			         fill: 'white',
			         stroke: '#C0C0C8',
			         'stroke-width': 1,
			         states: {
			            select: {
			               fill: '#D0D0D8'
			            }
			         }
			      }
			   },
			   scrollbar: {
			      trackBorderColor: '#C0C0C8'
			   },

			   // General
			   background2: '#E0E0E8'

			};

			Highcharts.setOptions(Highcharts.theme);

			$('#chart').highcharts({
				chart: { },
				credits: { enabled: false },
				exporting: { enabled: false },
				title: { text: '<?= lang('Number of Inbound & Outbond'); ?>' },
				xAxis: { categories: [<?php foreach($data as $row) { echo "'".date('M Y', strtotime($row['month']))."', "; } ?>] },
				yAxis: { min: 0, title: "" },
				tooltip: {
					shared: true,
					followPointer: true,
					headerFormat: '<div class="well well-sm" style="margin-bottom:0;"><span style="font-size:12px">{point.key}</span><table class="table table-striped" style="margin-bottom:0;">',
					pointFormat: '<tr><td style="color:{series.color};padding:4px">{series.name}: </td>' +
					'<td style="color:{series.color};padding:4px;text-align:right;"> <b>{point.y}</b></td></tr>',
					footerFormat: '</table></div>',
					useHTML: true, borderWidth: 0, shadow: false,
					style: {fontSize: '14px', padding: '0', color: '#000000'}
				},
				plotOptions: {
					column: {
						pointPadding: 0.2,
						borderWidth: 0
					}
				},
				series: [{
					//type: 'column',
					name: '<?= lang("Inbound"); ?>',
					data: [<?php foreach($data as $row) { echo (isset($row['check_ins']) ? $row['check_ins'] : 0).", "; } ?>]
				},
				{
					//type: 'column',
					name: '<?= lang("Outbond"); ?>',
					data: [<?php foreach($data as $row) { echo (isset($row['check_outs']) ? $row['check_outs'] : 0).", "; } ?>]
				}
				]
			});
});
</script>
<?php
}
?>
<div class="">
	<h3><?= lang('welcome')." ".$Settings->site_name; ?> </h3>
	<p><?= lang('dashboard_heading'); ?></p>
</div>
<div class="row">
	<div class="col-lg-12">
		<div class="content-panel">
			<div class="table-responsive">
				<table class="table table-bordered dash">
					<tbody>
						<tr>
							<td class="text-center btn-theme03"><a href="<?= site_url('items'); ?>"><i class="fa fa-barcode"></i> <span><?= lang('items'); ?></span></a></td>
							<td class="text-center btn-theme03"><a href="<?= site_url('check_in'); ?>"><i class="fa fa-arrow-circle-down"></i> <span><?= lang('Inbound'); ?></span></a></td>
							<td class="text-center btn-theme03"><a href="<?= site_url('check_out'); ?>"><i class="fa fa-arrow-circle-up"></i> <span><?= lang('Outbond'); ?></span></a></td>
							<td class="text-center btn-theme03"><a href="<?= site_url('sales'); ?>"><i class="fa fa-shopping-cart"></i> <span><?= lang('Sales'); ?></span></a></td>
							<?php if($Admin) { ?>
							<td class="text-center btn-theme03"><a href="<?= site_url('users'); ?>"><i class="fa fa-users"></i> <span><?= lang('users'); ?></span></a></td>
							<!--<td class="text-center btn-theme03"><a href="<?= site_url('settings'); ?>"><i class="fa fa-cogs"></i> <span><?= lang('settings'); ?></span></a></td>
							<td class="text-center btn-theme03"><a href="<?= site_url('settings/backups'); ?>"><i class="fa fa-download"></i> <span><?= lang('backups'); ?></span></a></td>
							<td class="text-center btn-theme03"><a href="<?= site_url('settings/updates'); ?>"><i class="fa fa-upload"></i> <span><?= lang('updates'); ?></span></a></td>-->
							<?php } ?>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<div class="box box-primary">
						<div class="box-body">
						<div id="chart" style="height:400px;"></div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
