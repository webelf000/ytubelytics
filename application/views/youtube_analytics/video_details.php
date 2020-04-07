<div class="container-fluid">
	<div class="col-xs-12" style="padding-top:20px;">
		<div class="well text-center"><h2 class="red">Video Title : <?php echo $title; ?></h2></div>
	</div>
	<div class="col-xs-12">
		<div class="col-xs-6">			
			<!-- Date range -->
			<div class="form-group">
				<label>Date Range:</label>
				<div class="input-group">
					<div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</div>
					<input type="text" class="form-control pull-right reservation" id="date_range" />
				</div><!-- /.input group -->
			</div><!-- /.form group -->
		</div>
		<div class="col-xs-2">
			<label for=""></label>
			<div><input style="margin-top:5px;" type="button" class="form-control btn btn-success" id="search" value="Search"></div>
		</div>
	</div>
	<!-- table id -->
	<input type="hidden" id="table_id" value="<?php echo $table_id; ?>">
	<!-- end of table id -->
	<div class="col-xs-12" style="padding-top:20px;">
		<!-- AREA CHART -->
		<div class="box box-primary">
			<div class="box-header with-border">
			<h3 class="box-title" style="color: #3C8DBC; word-spacing: 4px;"> Views (Viewed by day statistics) From <?php echo $from_date; ?> To <?php echo $to_date; ?></h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<div class="box-body">
				<div class="well text-center">The number of times that videos was viewed. In a playlist report, the metric indicates the number of times that videos was viewed in the context of a playlist</div>
				<input type="hidden" id="views" value='<?php echo $views; ?>' />
				<div class="chart">
					<div class="chart" id="views_line_chart" style="height: 300px;"></div>
				</div>
			</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div>



	<!-- <div class="col-xs-12" style="padding-top:20px;">
		<div class="box box-primary">
			<div class="box-header with-border">
			<h3 class="box-title" style="color: #3C8DBC; word-spacing: 4px;"> Unique Views From <?php echo $from_date; ?> To <?php echo $to_date; ?></h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<div class="box-body">
				<div class="well text-center">The number of unique viewers that watched a video. This calculation is based on the number of unique cookies and, therefore, will overcount users who are using multiple devices or browsers</div>
				<input type="hidden" id="unique_views" value='<?php echo $unique_views; ?>' />
				<div class="chart">
					<div class="chart" id="unique_views_line_chart" style="height: 300px;"></div>
				</div>
			</div>
		</div>
	</div> -->

	<div class="col-xs-12" style="padding-top:20px;">
		<!-- AREA CHART -->
		<div class="box box-primary">
			<div class="box-header with-border">
			<h3 class="box-title" style="color: #3C8DBC; word-spacing: 4px;"> Watch (Statistics total minutes and average time on the video output view) From <?php echo $from_date; ?> To <?php echo $to_date; ?></h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-6">
						<h4 class="text-center red">ESTIMATED MINUTES WATCHED</h4>
						<div class="well text-center">The number of minutes that users watched videos</div>
						<input type="hidden" id="minute_watch" value='<?php echo $minute_watch; ?>' />
						<div class="chart">
							<div class="chart" id="minute_watch_line_chart" style="height: 300px;"></div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-6">
						<h4 class="text-center red">AVERAGE VIEW DURATION</h4>
						<div class="well text-center">The average length, in seconds, of video playbacks</div>
						<input type="hidden" id="second_watch" value='<?php echo $second_watch; ?>' />
						<div class="chart">
							<div class="chart" id="second_watch_line_chart" style="height: 300px;"></div>
						</div>
					</div>
				</div>
				
			</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div>


	<div class="col-xs-12" style="padding-top:20px;">
		<!-- AREA CHART -->
		<div class="box box-primary">
			<div class="box-header with-border">
			<h3 class="box-title" style="color: #3C8DBC; word-spacing: 4px;"> The statistics views data on channels by gender and age group From <?php echo $from_date; ?> To <?php echo $to_date; ?></h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-6">
						<div class="row">
							<h4 class="text-center red">GENDER</h4>
							<div class="col-xs-12"><div class="well text-center">The statistics gender on video</div></div>
							<div class="col-md-8 col-xs-12">
								<input type="hidden" id="gender_percentage" value='<?php echo $gender_percentage; ?>' />
								<div class="chart-responsive">
									<canvas id="gender_percentage_pieChart" height="250"></canvas>
								</div><!-- ./chart-responsive -->
							</div><!-- /.col -->
							<div class="col-md-4 col-xs-12" style="padding-top:5px;height:250px;overflow:auto;">
								<ul class="chart-legend clearfix" id="">
									<?php echo $gender_percentage_list; ?>
								</ul>
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div>
					<div class="col-xs-12 col-sm-12 col-md-6">
						<div class="row">
							<h4 class="text-center red">AGE GROUP</h4>
							<div class="col-xs-12"><div class="well text-center">The statistics age group on video</div></div>
							<div class="col-md-8 col-xs-12">
								<input type="hidden" id="age_group" value='<?php echo $age_group; ?>' />
								<div class="chart-responsive">
									<canvas id="age_group_pieChart" height="250"></canvas>
								</div><!-- ./chart-responsive -->
							</div><!-- /.col -->
							<div class="col-md-4 col-xs-12" style="padding-top:5px;height:250px;overflow:auto;">
								<ul class="chart-legend clearfix" id="">
									<?php echo $age_group_list; ?>
								</ul>
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div>
				</div>
				
			</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div>

	<div class="col-xs-12" style="padding-top:20px;">
		<!-- AREA CHART -->
		<div class="box box-primary">
			<div class="box-header with-border">
			<h3 class="box-title" style="color: #3C8DBC; word-spacing: 4px;"> Subscriber Vs Unsubscriber From <?php echo $from_date; ?> To <?php echo $to_date; ?></h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<div class="box-body">
				<div class="well text-center">The number of times that users subscribed or unsubscribed to a channel</div>
				<input type="hidden" id="subscriber_vs_unsubscriber" value='<?php echo $subscriber_vs_unsubscriber; ?>' />
				<div class="chart">
					<div class="chart" id="subscriber_vs_unsubscriber_line_chart" style="height: 300px;"></div>
				</div>
			</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div>


	<div class="col-xs-12" style="padding-top:20px;">
		<!-- AREA CHART -->
		<div class="box box-primary">
			<div class="box-header with-border">
			<h3 class="box-title" style="color: #3C8DBC; word-spacing: 4px;"> Likes Vs Dislikes From <?php echo $from_date; ?> To <?php echo $to_date; ?></h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<div class="box-body">
				<div class="well text-center">The number of times that users indicated that they liked disliked videos by giving it a positive rating or disliked a video by giving it a negative rating</div>
				<input type="hidden" id="likes_vs_dislikes" value='<?php echo $likes_vs_dislikes; ?>' />
				<div class="chart">
					<div class="chart" id="likes_vs_dislikes_line_chart" style="height: 300px;"></div>
				</div>
			</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div>



	<div class="col-xs-12" style="padding-top:20px;">
		<!-- AREA CHART -->
		<div class="box box-primary">
			<div class="box-header with-border">
			<h3 class="box-title" style="color: #3C8DBC; word-spacing: 4px;"> Comments And Shares From <?php echo $from_date; ?> To <?php echo $to_date; ?></h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-6">
						<h4 class="text-center red">COMMMENT</h4>
						<div class="well text-center">The number of times that users commented on videos</div>
						<input type="hidden" id="comments" value='<?php echo $comments; ?>' />
						<div class="chart">
							<div class="chart" id="comments_line_chart" style="height: 300px;"></div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-6">
						<h4 class="text-center red">SHARES</h4>
						<div class="well text-center">The number of times that users shared on video</div>
						<input type="hidden" id="shares" value='<?php echo $shares; ?>' />
						<div class="chart">
							<div class="chart" id="shares_line_chart" style="height: 300px;"></div>
						</div>
					</div>
				</div>
				
			</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div>

	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<div class="col-xs-12" style="padding-top:20px;">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title" style="color: #3C8DBC; word-spacing: 4px;"> Country Wise Views From <?php echo $from_date; ?> To <?php echo $to_date; ?></h3>
				<div class="box-tools pull-right">
					<button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
					<button data-widget="remove" class="btn btn-box-tool"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<div class="box-body chart-responsive text-center well">
				<input type="hidden" id="country_map" value='<?php echo $country_map; ?>' />
				<div class="chart-responsive" id="regions_div">
					
				</div>
			</div>
		</div> <!-- end box -->			
	</div>

	<div class="col-xs-12" style="padding-top:20px;">
		<!-- AREA CHART -->
		<div class="box box-primary">
			<div class="box-header with-border">
			<h3 class="box-title" style="color: #3C8DBC; word-spacing: 4px;"> Top 10 Countries From <?php echo $from_date; ?> To <?php echo $to_date; ?></h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-6">
						<h4 class="text-center red">Top 10 countries with view percentage(%)</h4>
						<input type="hidden" id="top_ten_country_chart_data" value='<?php echo $top_ten_country_chart_data; ?>' />
						<div class="chart-responsive">
							<canvas id="top_ten_country_chart" height="350"></canvas>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-6">
						<h4 class="text-center red">Top 10 countries with the most views</h4>
						<div class="table-responsive" style='height: 350px; overflow: auto;'>
							<?php echo $top_ten_country_table; ?>
						</div>
					</div>
				</div>
				
			</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div>


	<div class="col-xs-12" style="padding-top:20px;">
		<!-- AREA CHART -->
		<div class="box box-primary">
			<div class="box-header with-border">
			<h3 class="box-title" style="color: #3C8DBC; word-spacing: 4px;"> Annotation Impressions From <?php echo $from_date; ?> To <?php echo $to_date; ?></h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<div class="box-body">
				<div class="well text-center">The total number of annotation impressions</div>
				<input type="hidden" id="annotation_impressions" value='<?php echo $annotation_impressions; ?>' />
				<div class="chart">
					<div class="chart" id="annotation_impressions_line_chart" style="height: 300px;"></div>
				</div>
			</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div>


	<div class="col-xs-12" style="padding-top:20px;">
		<!-- AREA CHART -->
		<div class="box box-primary">
			<div class="box-header with-border">
			<h3 class="box-title" style="color: #3C8DBC; word-spacing: 4px;"> Annotation Clicks and Closes Impressions From <?php echo $from_date; ?> To <?php echo $to_date; ?></h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<div class="box-body">
				<div class="well text-center">The number of annotations that appeared and could be clicked or closed</div>
				<input type="hidden" id="annotation_close_click_impressions" value='<?php echo $annotation_close_click_impressions; ?>' />
				<div class="chart">
					<div class="chart" id="annotation_close_click_impressions_line_chart" style="height: 300px;"></div>
				</div>
			</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div>


	<div class="col-xs-12" style="padding-top:20px;">
		<!-- AREA CHART -->
		<div class="box box-primary">
			<div class="box-header with-border">
			<h3 class="box-title" style="color: #3C8DBC; word-spacing: 4px;"> Annotation Clicks and Closes From <?php echo $from_date; ?> To <?php echo $to_date; ?></h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<div class="box-body">
				<div class="well text-center">The number of clicked and closed annotations</div>
				<input type="hidden" id="annotation_clicks_closes" value='<?php echo $annotation_clicks_closes; ?>' />
				<div class="chart">
					<div class="chart" id="annotation_clicks_closes_line_chart" style="height: 300px;"></div>
				</div>
			</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div>


	<div class="col-xs-12" style="padding-top:20px;">
		<!-- AREA CHART -->
		<div class="box box-primary">
			<div class="box-header with-border">
			<h3 class="box-title" style="color: #3C8DBC; word-spacing: 4px;"> Device Type Report From <?php echo $from_date; ?> To <?php echo $to_date; ?></h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-xs-12">						
						<div class="well text-center">This statistics aggregates viewing statistics based on the manner in which viewers reached your playlist content</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-6">
						<h4 class="text-center red">Device Type Report With Percentage(%)</h4>
						<input type="hidden" id="device_type_chart_data" value='<?php echo $device_type_chart_data; ?>' />
						<div class="chart-responsive">
							<canvas id="device_type_chart" height="250"></canvas>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-6">
						<div class="table-responsive" style='height: 350px; overflow: auto;'>
							<?php echo $device_type_table; ?>
						</div>
					</div>
				</div>
				
			</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div>


	<div class="col-xs-12" style="padding-top:20px;">
		<!-- AREA CHART -->
		<div class="box box-primary">
			<div class="box-header with-border">
			<h3 class="box-title" style="color: #3C8DBC; word-spacing: 4px;"> Operaing System Report From <?php echo $from_date; ?> To <?php echo $to_date; ?></h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-xs-12">						
						<div class="well text-center">The statistics aggregates viewing statistics based on viewers' operating systems</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-6">
						<h4 class="text-center red">Operating System Report With Percentage(%)</h4>
						<input type="hidden" id="operating_system_chart_data" value='<?php echo $operating_system_chart_data; ?>' />
						<div class="chart-responsive">
							<canvas id="operating_system_chart" height="250"></canvas>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-6">
						<div class="table-responsive" style='height: 350px; overflow: auto;'>
							<?php echo $operating_system_table; ?>
						</div>
					</div>
				</div>
				
			</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div>


	<div class="col-xs-12" style="padding-top:20px;">
		<!-- AREA CHART -->
		<div class="box box-primary">
			<div class="box-header with-border">
			<h3 class="box-title" style="color: #3C8DBC; word-spacing: 4px;"> Audience retention From <?php echo $from_date; ?> To <?php echo $to_date; ?></h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<div class="box-body">
				<div class="well text-center">This report measures a video's ability to retain its audience. The metrics provide measurement that show how well the video retains its audience.</div>
				<input type="hidden" id="retention" value='<?php echo $retention; ?>' />
				<div class="chart">
					<div class="chart" id="retention_bar_chart" style="height: 300px;"></div>
				</div>
			</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div>
	


</div>

<script>
	$('.reservation').daterangepicker();

	$j("document").ready(function(){

		$("#search").click(function(){
			var table_id = $("#table_id").val();
			var date_range = $("#date_range").val();
			var date_range_array = date_range.split(" - ");
			var start_date = date_range_array[0].replace(/\//g,'-');
			var end_date = date_range_array[1].replace(/\//g,'-');
			var link="<?php echo site_url('youtube_analytics/get_video_details'); ?>"+"/"+table_id+"/"+start_date+"/"+end_date; 
			window.location.assign(link);
		});

		// LINE CHART
		var views = $('#views').val();
	    var line = new Morris.Bar({
	      element: 'views_line_chart',
	      resize: true,
	      data: JSON.parse(views),
	      xkey: 'date',
	      ykeys: ['views'],
	      labels: ['Views'],
	      barColors: ['#0CC67D'],
	      lineWidth: 1,
	      hideHover: 'auto'
	    });




	    // LINE CHART
		// var unique_views = $('#unique_views').val();
	 //    var line = new Morris.Bar({
	 //      element: 'unique_views_line_chart',
	 //      resize: true,
	 //      data: JSON.parse(unique_views),
	 //      xkey: 'date',
	 //      ykeys: ['unique_views'],
	 //      labels: ['Unique Views'],
	 //      barColors: ['#EAA993'],
	 //      lineWidth: 1,
	 //      hideHover: 'auto'
	 //    });



	    // LINE CHART
		var minute_watch = $('#minute_watch').val();
	    var line = new Morris.Line({
	      element: 'minute_watch_line_chart',
	      resize: true,
	      data: JSON.parse(minute_watch),
	      xkey: 'date',
	      ykeys: ['minute_watch'],
	      labels: ['Minutes'],
	      lineColors: ['#C9A200'],
	      lineWidth: 1,
	      hideHover: 'auto'
	    });



	    // LINE CHART
		var second_watch = $('#second_watch').val();
	    var line = new Morris.Line({
	      element: 'second_watch_line_chart',
	      resize: true,
	      data: JSON.parse(second_watch),
	      xkey: 'date',
	      ykeys: ['second_watch'],
	      labels: ['Seconds'],
	      lineColors: ['#B1092D'],
	      lineWidth: 1,
	      hideHover: 'auto'
	    });




	    // LINE CHART
		var subscriber_vs_unsubscriber = $('#subscriber_vs_unsubscriber').val();
	    var line = new Morris.Area({
	      element: 'subscriber_vs_unsubscriber_line_chart',
	      resize: true,
	      data: JSON.parse(subscriber_vs_unsubscriber),
	      xkey: 'date',
	      ykeys: ['subscriber','unsubscriber'],
	      labels: ['Subscribers Gaine','Subscribers Lost'],
	      lineColors: ['#00CA7A','#C94536'],
	      lineWidth: 1,
	      hideHover: 'auto'
	    });


	    // LINE CHART
		var likes_vs_dislikes = $('#likes_vs_dislikes').val();
	    var line = new Morris.Bar({
	      element: 'likes_vs_dislikes_line_chart',
	      resize: true,
	      data: JSON.parse(likes_vs_dislikes),
	      xkey: 'date',
	      ykeys: ['likes','dislikes'],
	      labels: ['Likes','Dislikes'],
	      barColors: ['#7A3E48','#EECD86'],
	      lineWidth: 1,
	      hideHover: 'auto'
	    });



	    // LINE CHART
		var comments = $('#comments').val();
	    var line = new Morris.Line({
	      element: 'comments_line_chart',
	      resize: true,
	      data: JSON.parse(comments),
	      xkey: 'date',
	      ykeys: ['comments'],
	      labels: ['Comments'],
	      lineColors: ['#D889B8'],
	      lineWidth: 1,
	      hideHover: 'auto'
	    });


	    // LINE CHART
		var shares = $('#shares').val();
	    var line = new Morris.Line({
	      element: 'shares_line_chart',
	      resize: true,
	      data: JSON.parse(shares),
	      xkey: 'date',
	      ykeys: ['shares'],
	      labels: ['Shares'],
	      lineColors: ['#66CCFF'],
	      lineWidth: 1,
	      hideHover: 'auto'
	    });


	    var country_map = $("#country_map").val();
	    
	    var country_graph_data = JSON.parse(country_map);
	    function drawMap() {
			var data = google.visualization.arrayToDataTable(country_graph_data);

			var options = {};
			options['dataMode'] = 'regions';

			var container = document.getElementById('regions_div');
			var geomap = new google.visualization.GeoMap(container);

			geomap.draw(data, options);
		};
		google.charts.load('current', {'packages':['geomap']});
		google.charts.setOnLoadCallback(drawMap);




		// Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $("#top_ten_country_chart").get(0).getContext("2d");
        var pieChart = new Chart(pieChartCanvas);
        var top_ten_country_chart_data = $("#top_ten_country_chart_data").val();
        var PieData = JSON.parse(top_ten_country_chart_data);
        var pieOptions = {
          //Boolean - Whether we should show a stroke on each segment
          segmentShowStroke: true,
          //String - The colour of each segment stroke
          segmentStrokeColor: "#fff",
          //Number - The width of each segment stroke
          segmentStrokeWidth: 2,
          //Number - The percentage of the chart that we cut out of the middle
          percentageInnerCutout: 30, // This is 0 for Pie charts
          //Number - Amount of animation steps
          animationSteps: 100,
          //String - Animation easing effect
          animationEasing: "easeOutBounce",
          //Boolean - Whether we animate the rotation of the Doughnut
          animateRotate: true,
          //Boolean - Whether we animate scaling the Doughnut from the centre
          animateScale: false,
          //Boolean - whether to make the chart responsive to window resizing
          responsive: true,
          // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
          maintainAspectRatio: false
        };
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        pieChart.Doughnut(PieData, pieOptions);
		/******************************/




		// Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $("#gender_percentage_pieChart").get(0).getContext("2d");
        var pieChart = new Chart(pieChartCanvas);
        var gender_percentage = $("#gender_percentage").val();
        var PieData = JSON.parse(gender_percentage);
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        pieChart.Doughnut(PieData, pieOptions);
		/******************************/



		// Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $("#age_group_pieChart").get(0).getContext("2d");
        var pieChart = new Chart(pieChartCanvas);
        var age_group = $("#age_group").val();
        var PieData = JSON.parse(age_group);
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        pieChart.Doughnut(PieData, pieOptions);
		/******************************/




		// LINE CHART
		var annotation_impressions = $('#annotation_impressions').val();
	    var line = new Morris.Line({
	      element: 'annotation_impressions_line_chart',
	      resize: true,
	      data: JSON.parse(annotation_impressions),
	      xkey: 'date',
	      ykeys: ['annotation_impressions'],
	      labels: ['Annotation Impressions'],
	      lineColors: ['#FF9966'],
	      lineWidth: 1,
	      hideHover: 'auto'
	    });


	    // LINE CHART
		var annotation_close_click_impressions = $('#annotation_close_click_impressions').val();
	    var line = new Morris.Line({
	      element: 'annotation_close_click_impressions_line_chart',
	      resize: true,
	      data: JSON.parse(annotation_close_click_impressions),
	      xkey: 'date',
	      ykeys: ['click_impression','close_impression'],
	      labels: ['Annotation Clickable Impressions','Annotation Closeable Impressions'],
	      lineColors: ['#3399FF','#FFCC99'],
	      lineWidth: 1,
	      hideHover: 'auto'
	    });



	    // LINE CHART
		var annotation_clicks_closes = $('#annotation_clicks_closes').val();
	    var line = new Morris.Bar({
	      element: 'annotation_clicks_closes_line_chart',
	      resize: true,
	      data: JSON.parse(annotation_clicks_closes),
	      xkey: 'date',
	      ykeys: ['annotation_click','annotation_close'],
	      labels: ['Annotation Clicks','Annotation Closes'],
	      barColors: ['#71B238','#C9CACE'],
	      lineWidth: 1,
	      hideHover: 'auto'
	    });


		// Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $("#device_type_chart").get(0).getContext("2d");
        var pieChart = new Chart(pieChartCanvas);
        var device_type_chart_data = $("#device_type_chart_data").val();
        var PieData = JSON.parse(device_type_chart_data);
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        pieChart.Doughnut(PieData, pieOptions);
		/******************************/



		// Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $("#operating_system_chart").get(0).getContext("2d");
        var pieChart = new Chart(pieChartCanvas);
        var operating_system_chart_data = $("#operating_system_chart_data").val();
        var PieData = JSON.parse(operating_system_chart_data);
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        pieChart.Doughnut(PieData, pieOptions);
		/******************************/




		// LINE CHART
		var retention = $('#retention').val();
	    var line = new Morris.Bar({
	      element: 'retention_bar_chart',
	      resize: true,
	      data: JSON.parse(retention),
	      xkey: 'video_length',
	      ykeys: ['audience'],
	      labels: ['Audience'],
	      barColors: ['#0CC67D'],
	      lineWidth: 1,
	      hideHover: 'auto'
	    });




	});

</script>