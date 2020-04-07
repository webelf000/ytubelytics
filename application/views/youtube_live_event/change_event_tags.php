<?php 
	if($this->session->userdata('youtube_upload_error') != '')
	{
		echo "<div class='alert alert-danger text-center'>".$this->session->userdata('youtube_upload_error')."</div>";
		$this->session->unset_userdata('youtube_upload_error');
	}
?>

<section class="content-header">
	<section class="content">
		<div class="box box-info custom_box">

			<div class="box-header">
				<h3 class="box-title"><i class="fa fa-pencil-square-o"></i> Change keywords for this event</h3>
			</div><!-- /.box-header -->

			<div class="box-body">
				<form class="form-horizontal" action="<?php echo site_url().'youtube_live_event/change_event_tags_action';?>" enctype="multipart/form-data" method="POST">
				<input type="hidden" name="table_id" value="<?php echo $info['id']; ?>" />

				<div class="form-group">
					<label class="col-sm-3 control-label" >Channel <span class="red">*</span></label>
					<div class="col-sm-6">
						<?php
							$channel_list[''] = 'Please Select';
							echo form_dropdown('channel_id',$channel_list,$info['channel_id'],'disabled required class="form-control" id="channel_id"'); 
						?>
						<span class="red"><?php echo form_error('channel_id'); ?></span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label" >Event Title</label>
					<div class="col-sm-6">
						<input disabled placeholder="Video Title"  name="title" id="title" value="<?php echo $info['title'];?>"  class="form-control" type="text"  />		          
						<span class="red"><?php echo form_error('title'); ?></span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label" >Event Description</label>
					<div class="col-sm-6">
						<textarea disabled name="description" id="description" cols="71" rows="10"><?php echo $info['description'];?></textarea>
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
							echo form_dropdown('privacy_type',$privacy_type,$info['privacy_type'],'disabled required class="form-control" id="privacy_type"'); 
						?>
						<span class="red"><?php echo form_error('privacy_type'); ?></span>
					</div>
				</div>
				

				<div class="form-group">
					<label class="col-sm-3 control-label" >Time Zone <span class="red">*</span></label>
					<div class="col-sm-6">
						<?php
							$time_zone_list[''] = 'Please Select';
							echo form_dropdown('time_zone',$time_zone_list,$info['time_zone'],'disabled required class="form-control" id="time_zone"'); 
						?>
						<span class="red"><?php echo form_error('time_zone'); ?></span>
					</div>
				</div>



				<div class="form-group">
					<label class="col-sm-3 control-label" for="start_time">Event start time <span class="red">*</span></label>
					<div class="col-sm-6">
						<input disabled placeholder="Time Schedule"  name="start_time" id="start_time" value="<?php echo $info['start_time'];?>"  class="form-control datepicker" type="text"  />
						<span class="red"><?php echo form_error('start_time'); ?></span>          
					</div>
				</div>


				<div class="form-group">
					<label class="col-sm-3 control-label" for="end_time">Event end time <span class="red">*</span></label>
					<div class="col-sm-6">
						<input disabled placeholder="Time Schedule"  name="end_time" id="end_time" value="<?php echo $info['end_time'];?>"  class="form-control datepicker" type="text"  />
						<span class="red"><?php echo form_error('end_time'); ?></span>          
					</div>
				</div>


				<div class="form-group">
					<label class="col-sm-3 control-label" >Keyword for rank tracking <span class="red">*</span></label>
					<div class="col-sm-6">
						<?php 
						if(set_value('tags'))
							$tags = set_value('tags');
						else
							$tags = $info['tags'];

						?>
						<input placeholder="Example : needed,HD,High"  name="tags" id="tags" value="<?php echo $tags;?>"  class="form-control" type="text"  />		          
						<span class="red"><?php echo form_error('tags'); ?></span>
					</div>
				</div>



			</div>

			<div class="box-footer">
				<div class="form-group">
					<div class="col-sm-12 text-center">
						<input name="submit" id="submit" type="submit" class="btn btn-warning btn-lg" value="<?php echo $this->lang->line("save");?>"/>  
						<input type="button" class="btn btn-default btn-lg" value="<?php echo $this->lang->line("cancel");?>" onclick='goBack("youtube_live_event/live_event_list")'/>
					</div>
				</div>

				</form>
			</div>

		</div>
	</section>
</section>