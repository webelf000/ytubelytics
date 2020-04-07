<?php 
if(isset($google_api[0]['google_safety_api'])) $google_api_key=$google_api[0]['google_safety_api'];
else $google_api_key="AIzaSyBG0sIVBWReW1Q0WGkWO28uGaKWhQp7Q4c";

if($google_api_key=="")
$google_api_key="AIzaSyBG0sIVBWReW1Q0WGkWO28uGaKWhQp7Q4c";

?>
<section class="content-header">
	<section class="content">
		<div class="box box-info custom_box">
			<div class="box-header">
				<h3 class="box-title"><i class="fa fa-youtube-play"></i> <?php echo $this->lang->line('youtube video'); ?></h3>
			</div><!-- /.box-header -->
			<!-- form start -->
			<form class="form-horizontal" method="POST">
				<div class="box-body">
					<?php 
						$btn_lang=$this->lang->line('search video');
						$head_lang=$this->lang->line('keyword');					
					?>
					<div class="form-group">
						<label class="col-sm-3 control-label" ><?php echo $head_lang;?> 
						</label>
						<div class="col-sm-9 col-md-6 col-lg-6">
							<input name="keyword" id="keyword" value="<?php echo set_value('keyword');?>" placeholder="<?php echo $this->lang->line('type any keyword');?>..."  class="form-control" type="text" required />		          
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" ><?php echo $this->lang->line('limit');?> 
						</label>
						<div class="col-sm-9 col-md-6 col-lg-6">
							<?php include("application/views/video_search_engine/limit.php");?>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" ><?php echo $this->lang->line('order');?> 
						</label>
						<div class="col-sm-9 col-md-6 col-lg-6">
							<?php include("application/views/video_search_engine/order.php");?>
						</div>
					</div>

					<div>
					<div class="form-group">
						<label class="col-sm-3 control-label" ><?php echo $this->lang->line('date');?> 
						</label>
						<div class="col-sm-4 col-md-3 col-lg-3">
						<input id="publish_before" type="text" class="form-control datepicker" placeholder="<?php echo $this->lang->line("published before"); ?>" style="width:100%"/>
						</div>
						<div class="col-sm-4 col-md-3 col-lg-3">
						<input id="publish_after" type="text" class="form-control datepicker" placeholder="<?php echo $this->lang->line("published after"); ?>" style="width:100%"/>
						</div>
					</div>


					<div class="form-group">
						<label class="col-sm-3 control-label" ><?php echo $this->lang->line('location');?> 
						</label>
						<div class="col-sm-4 col-md-3 col-lg-3">
							<input id="location" type="text" class="form-control" placeholder="<?php echo $this->lang->line("Location"); ?>" style="width:100%"/>
							<input id="location_lat" type="hidden" class="form-control" />
							<input id="location_long" type="hidden" class="form-control" />
						</div>
						<div class="col-sm-4 col-md-3 col-lg-3">
							<?php include("application/views/video_search_engine/radius.php");?>
						</div>
					</div>

			
					<div class="form-group">
						<label class="col-sm-3 control-label" ><?php echo $this->lang->line('channel ID');?> 
						</label>
						<div class="col-sm-9 col-md-6 col-lg-6">
							<input id="channel_id" value="<?php echo $channel_id;?>" type="text" class="form-control" placeholder="<?php echo $this->lang->line("channel ID"); ?>" style="width:100%"/>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" >
						</label>
						<div class="col-sm-3 col-md-2 col-lg-2">
							<?php include("application/views/video_search_engine/event_type.php");?>
						</div>
						<div class="col-sm-3 col-md-2 col-lg-2">
							<?php include("application/views/video_search_engine/video_type.php");?>
						</div>
						<div class="col-sm-3 col-md-2 col-lg-2">
							<?php include("application/views/video_search_engine/duration.php");?>
						</div>						
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" >
						</label>
						<div class="col-sm-3 col-md-2 col-lg-2">
							<?php include("application/views/video_search_engine/dimension.php");?>
						</div>	
						<div class="col-sm-3 col-md-2 col-lg-2">
							<?php include("application/views/video_search_engine/defination.php");?>
						</div>	
						<div class="col-sm-3 col-md-2 col-lg-2">
							<?php include("application/views/video_search_engine/license.php");?>
						</div>											
					</div>
				</div>

				</div> <!-- /.box-body --> 
				<div class="box-footer">
					<div class="form-group">
						<div class="col-sm-12 text-center">
							<input name="submit" id="search" type="button" class="btn btn-primary btn-lg" value="<?php echo $btn_lang;?>"/>  
							<input type="button" class="btn btn-default btn-lg" value="<?php echo $this->lang->line("cancel");?>" onclick='goBack("video_search_engine/youtube")'/>  
						</div>
					</div>

					<div class="col-xs-12 text-center" id="domain_success_msg"></div> 					
					<div class="col-xs-12 text-center" id="progress_msg">
						<b><span id="domain_progress_msg_text"></span></b><br/>
					</div>

					<div class='space'></div>
					<div id="response"></div>


				</div><!-- /.box-footer -->         
			</form>	
		</div><!-- /.box-info -->       
	</section>
</section>


<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=<?php echo $google_api_key;?>"></script>

<script>
	var placeSearch, autocomplete,autocomplete2,autocomplete3,autocomplete4,autocomplete5;
	function initialize() 
	{
	  autocomplete = new google.maps.places.Autocomplete((document.getElementById('location')),{ types: ['geocode'] });
	  google.maps.event.addListener(autocomplete, 'place_changed', function() 
	  {
		fillInAddress("location_lat","location_long",1);
	  });
	}
	
	function fillInAddress(lat_div,lng_div,autocomplete_no) 
	{
		if(autocomplete_no==1)
		var place = autocomplete.getPlace();		
		else var place = autocomplete5.getPlace();

		console.log(place);
		console.log(place.geometry.location.lat());
		console.log(place.geometry.location.lng());
		var latitude = place.geometry.location.lat();
		var longitude = place.geometry.location.lng();

		$("#location_lat").val(latitude);
		$("#location_long").val(longitude);		
	}	
	
	window.onload=initialize;

	var pacContainerInitialized = false;					  
	$(document).on('keypress','#location',function(){
	        if (!pacContainerInitialized) {
	                $('.pac-container').css('z-index', '9999');
	                pacContainerInitialized = true;
	        }
	});	
	
</script>


<script type="text/javascript">

		$j("document").ready(function(){
			$( ".datepicker" ).datepicker();
			if($('#channel_id').val()!="")
			$("#search").click();
		});


		$(document.body).on('click','#search',function(){
			var keyword = $("#keyword").val();
			var limit = $("#limit").val();
			var location = $("#location").val();
			var event_type = $("#event_type").val();
			var radius = $("#radius").val();
			var latitude = $("#location_lat").val();
			var longitude = $("#location_long").val();
			var channel_id = $("#channel_id").val();
			var publish_after = $("#publish_after").val();
			var publish_before = $("#publish_before").val();
			var order = $("#order").val();
			var video_type = $("#video_type").val();
			var duration = $("#duration").val();

			var dimension = $("#dimension").val();
			var defination = $("#defination").val();
			var license = $("#license").val();
			var base_url="<?php echo base_url(); ?>";

			if(keyword == '' && channel_id=="")
			{
				alert("please enter keyword or enter channel ID");
				return false;
			}

			$("#response").html("");
			$("#search").attr("disabled","disabled");
			
			$("#domain_progress_msg_text").html('<?php echo $this->lang->line("please wait"); ?>');
								
			$("#domain_success_msg").html('<img class="center-block" src="'+base_url+'assets/pre-loader/custom_lg.gif" alt="Processing..."><br/>');
			$.ajax({
				url:base_url+'video_search_engine/youtube_action',
				type:'POST',
				data:{keyword:keyword,limit:limit,location:location,event_type:event_type,radius:radius,latitude:latitude,longitude:longitude,channel_id:channel_id,publish_before:publish_before,publish_after:publish_after,order:order,video_type:video_type,duration:duration,dimension:dimension,defination:defination,license:license},
				success:function(response){		

					$("#domain_progress_bar_con").hide();					
					$("#domain_progress_msg_text").html("");
					$("#domain_success_msg").html('');
					$("#response").html(response);
					$("#search").removeAttr("disabled");
				}

			});


		});
		
	</script>





