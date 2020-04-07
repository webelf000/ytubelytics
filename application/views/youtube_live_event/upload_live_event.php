
<section class="content-header">
	<section class="content">
		<div class="box box-info custom_box">

			<div class="box-header">
				<h3 class="box-title"><i class="fa fa-plus-square"></i> Create a new event to your youtube channel</h3>
			</div><!-- /.box-header -->

			<div class="box-body">
				<form class="form-horizontal" action="" enctype="multipart/form-data" method="POST" id="application_form">

				<div class="form-group">
					<label class="col-sm-3 control-label" >Channel <span class="red">*</span></label>
					<div class="col-sm-6">
						<?php
							$channel_list[''] = 'Please Select';
							echo form_dropdown('channel_id',$channel_list,set_value('channel_id'),'class="form-control" id="channel_id"'); 
						?>
						<span class="red"><?php echo form_error('channel_id'); ?></span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label" >Event Title <span class="red">*</span></label>
					<div class="col-sm-6">
						<input placeholder="Video Title"  name="title" id="title" value="<?php echo set_value('title');?>"  class="form-control" type="text"  />		          
						<span class="red"><?php echo form_error('title'); ?></span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label" >Event Description <span class="red">*</span></label>
					<div class="col-sm-6">
						<textarea name="description" id="description" class="form-control"></textarea>
						<span class="red"><?php echo form_error('description'); ?></span>
					</div>
				</div>

							


				<div class="form-group">
					<label class="col-sm-3 control-label" >Privacy Type <span class="red">*</span></label>
					<div class="col-sm-6">
						<?php
							$privacy_type[''] = 'Please Select';
							$privacy_type['public'] = 'Public';
							$privacy_type['private'] = 'Private';
							$privacy_type['unlisted'] = 'Unlisted';
							echo form_dropdown('privacy_type',$privacy_type,set_value('privacy_type'),'class="form-control" id="privacy_type"'); 
						?>
						<span class="red"><?php echo form_error('privacy_type'); ?></span>
					</div>
				</div>
				

				<div class="form-group">
					<label class="col-sm-3 control-label" >Time Zone <span class="red">*</span></label>
					<div class="col-sm-6">
						<?php
							$time_zone_list[''] = 'Please Select';
							echo form_dropdown('time_zone',$time_zone_list,set_value('time_zone'),'class="form-control" id="time_zone"'); 
						?>
						<span class="red"><?php echo form_error('time_zone'); ?></span>
					</div>
				</div>



				<div class="form-group">
					<label class="col-sm-3 control-label" for="start_time">Event start time <span class="red">*</span></label>
					<div class="col-sm-6">
						<input placeholder="Time Schedule"  name="start_time" id="start_time" value="<?php echo set_value('start_time');?>"  class="form-control datepicker" type="text"  />
						<span class="red"><?php echo form_error('start_time'); ?></span>          
					</div>
				</div>


				<div class="form-group">
					<label class="col-sm-3 control-label" for="end_time">Event end time <span class="red">*</span></label>
					<div class="col-sm-6">
						<input placeholder="Time Schedule"  name="end_time" id="end_time" value="<?php echo set_value('end_time');?>"  class="form-control datepicker" type="text"  />
						<span class="red"><?php echo form_error('end_time'); ?></span>          
					</div>
				</div>


				<div class="form-group well" style="background: #fcfcfc;display:none;">
					<div class="col-xs-12 table-responsive">
								          
					</div>
				</div>

				<div id="loading" style="display: none;">
						<img class="center-block" src="<?php echo base_url("assets/pre-loader/Fading squares.gif");?>">
				</div> 

				<div id='youtube_upload_error'></div>

			</div>

			<div class="box-footer">
				<div class="form-group">
					<div class="col-sm-12 text-center">
						<input name="submit" id="submit" type="button" class="save btn btn-warning btn-lg" value="<?php echo $this->lang->line("save");?>"/>  
						<input type="button" class="cancel btn btn-default btn-lg" value="<?php echo $this->lang->line("cancel");?>" onclick='goBack("youtube_live_event/live_event_list")'/>
					</div>
				</div>

				</form>
			</div>

		</div>
	</section>
</section>

<script type="text/javascript">

	$j(function() {	
		$j('.datepicker').datetimepicker({
			theme:'dark',
			format:'Y-m-d H:i:s',
			formatDate:'Y-m-d H:i:s'
		})	

	

    });


    $(document.body).on('click','#submit',function(){

		  	var title=$("#title").val();
	    	var description=$("#description").val();
	    	var tags=$("#tags").val();
	    	var start_time=$("#start_time").val();
	    	var end_time=$("#end_time").val();
	    	var privacy_type=$("#privacy_type").val();
	    	var channel_id=$("#channel_id").val();
	    	var time_zone=$("#time_zone").val();
	   	    	

	    	if(title=="" || description=="" || start_time=="" || end_time=="" || privacy_type=="" || channel_id=="" || time_zone=="")
	    	{
	    		alert("All * fields are required");
	    		return;
	    	}


		  $("#loading").show();
		  $(".save").attr("disabled","disabled");
		  $("#cancel").attr("disabled","disabled");


		    var base_url="<?php echo site_url(); ?>";
			var queryString = new FormData($("#application_form")[0]);
			$.ajax({
				type:'POST' ,
				url: base_url+"youtube_live_event/create_new_event_action",
				data: queryString,
				dataType:'JSON',
				async: false,
				cache: false,
				contentType: false,
				processData: false,
				success: function (response) {
					$("#loading").hide();
					$(".save").removeAttr("disabled");
		   			$("#cancel").removeAttr("disabled");

		   			if(response.status=="0")
		   			{
		   				$("#youtube_upload_error").addClass("alert alert-danger text-center");
		   				$("#youtube_upload_error").html(response.error);
		   			}
		   			else if(response.status=="1")
		   			{
		   				var link="<?php echo site_url('youtube_live_event/live_event_list'); ?>"; 
						window.location.assign(link); 
		   			}
				}
			}); 


		});

   

</script>

<style type="text/css" media="screen">
	tr td, tr th{padding:2px;}
</style>