<section class="content-header">
	<section class="content">
		<div class="box box-info custom_box">

			<div class="box-header">
				<h3 class="box-title"><i class="fa fa-cloud-upload"></i> Edit your video info.</h3>
			</div><!-- /.box-header -->

			<div class="box-body">
				<form class="form-horizontal" action="<?php echo site_url().'youtube_live_event/edit_uploaded_video_action';?>" enctype="multipart/form-data" method="POST">

				<div class="form-group">
					<label class="col-sm-3 control-label" >Channel <span class="red">*</span></label>
					<div class="col-sm-6">
						<input type="hidden" name='table_id' value="<?php echo $video_info[0]['id']; ?>">
						<?php
							if(set_value('channel_id'))
								$channel = set_value('channel_id');
							else
								$channel = $video_info[0]['channel_id'];
							$channel_list[''] = 'Please Select';
							echo form_dropdown('channel_id',$channel_list,$channel,' class="form-control" id="channel_id"'); 
						?>
						<span class="red"><?php echo form_error('channel_id'); ?></span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label" >Video Title</label>
					<div class="col-sm-6">
						<input placeholder="Video Title"  name="title" id="title" value="<?php if(set_value('title')) echo set_value('title'); else echo $video_info[0]['title'];?>"  class="form-control" type="text"  />		          
						<span class="red"><?php echo form_error('title'); ?></span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label" >Video Description</label>
					<div class="col-sm-6">
						<textarea name="description" id="description" cols="71" rows="10"><?php 
							if(set_value('description')) echo set_value('description'); 
							else echo $video_info[0]['description'];
							?>
						</textarea>
						<span class="red"><?php echo form_error('description'); ?></span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label" >Video Tags</label>
					<div class="col-sm-6">
						<input placeholder="Example : needed,HD,High"  name="tags" id="tags" value="<?php if(set_value('tags')) echo set_value('tags'); else echo $video_info[0]['tags'];?>"  class="form-control" type="text"  />		          
						<span class="red"><?php echo form_error('tags'); ?></span>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label" >Video Category <span class="red">*</span></label>
					<div class="col-sm-6">
						<?php
							if(set_value('category'))
								$category = set_value('category');
							else
								$category = $video_info[0]['category'];

							$video_category[''] = 'Please Select';
							echo form_dropdown('category',$video_category,$category,' class="form-control" id="category"'); 
						?>
						<span class="red"><?php echo form_error('category'); ?></span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label" >Privacy Type <span class="red">*</span></label>
					<div class="col-sm-6">
						<?php
							if(set_value('privacy_type'))
								$privacy = set_value('privacy_type');
							else
								$privacy = $video_info[0]['privacy_type'];

							$privacy_type[''] = 'Please Select';
							$privacy_type['public'] = 'Public';
							$privacy_type['private'] = 'Private';
							$privacy_type['unlisted'] = 'Unlisted';
							echo form_dropdown('privacy_type',$privacy_type,$privacy,' class="form-control" id="privacy_type"'); 
						?>
						<span class="red"><?php echo form_error('privacy_type'); ?></span>
					</div>
				</div>
				

				<div class="form-group">
					<label class="col-sm-3 control-label" >Time Zone <span class="red">*</span></label>
					<div class="col-sm-6">
						<?php
							if(set_value('time_zone'))
								$time_zone = set_value('time_zone');
							else
								$time_zone = $video_info[0]['time_zone'];

							$time_zone_list[''] = 'Please Select';
							echo form_dropdown('time_zone',$time_zone_list,$time_zone,' class="form-control" id="time_zone"'); 
						?>
						<span class="red"><?php echo form_error('time_zone'); ?></span>
					</div>
				</div>


				<div class="form-group" id="schedule_time_div">
					<label class="col-sm-3 control-label" for="">Schedule Time <span class="red">*</span></label>
					<div class="col-sm-6">
						<input placeholder="Time Schedule"  name="schedule_time" id="schedule_time" value="<?php if(set_value('schedule_time')) echo set_value('schedule_time'); else echo $video_info[0]['upload_time'];?>"  class="form-control datepicker" type="text"  />
						<span class="red"><?php echo form_error('schedule_time'); ?></span>          
					</div>
				</div>

			</div>

			<div class="box-footer">
				<div class="form-group">
					<div class="col-sm-12 text-center">
						<input name="submit" id="submit" type="submit" class="btn btn-warning btn-lg" value="<?php echo $this->lang->line("save");?>"/>  
						<input type="button" class="btn btn-default btn-lg" value="<?php echo $this->lang->line("cancel");?>" onclick='goBack("youtube_live_event/uploaded_video_list")'/>
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
</script>