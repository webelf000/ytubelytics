<?php 
	if($this->session->userdata('youtube_upload_error') != '')
	{
		echo "<div class='alert alert-danger text-center'>".$this->session->userdata('youtube_upload_error')."</div>";
		$this->session->unset_userdata('youtube_upload_error');
	}
?>
<?php $this->load->view("include/upload_js"); ?>
<section class="content-header">
	<section class="content">
		<div class="box box-info custom_box">

			<div class="box-header">
				<h3 class="box-title"><i class="fa fa-cloud-upload"></i> Upload a video to your youtube channel</h3>
			</div><!-- /.box-header -->

			<div class="box-body">
				<form class="form-horizontal" action="<?php echo site_url().'youtube_live_event/youtube_video_upload_action';?>" enctype="multipart/form-data" method="POST">

				<div class="form-group">
					<label class="col-sm-3 control-label" >Channel <span class="red">*</span></label>
					<div class="col-sm-6">
						<!-- <input  placeholder="Channel ID"  name="channel_id" id="channel_id" value="<?php echo set_value('channel_id');?>"  class="form-control" type="text"  />		           -->
						<?php
							$channel_list[''] = 'Please Select';
							echo form_dropdown('channel_id',$channel_list,set_value('channel_id'),' class="form-control" id="channel_id"'); 
						?>
						<span class="red"><?php echo form_error('channel_id'); ?></span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label" >Video Title</label>
					<div class="col-sm-6">
						<input placeholder="Video Title"  name="title" id="title" value="<?php echo set_value('title');?>"  class="form-control" type="text"  />		          
						<span class="red"><?php echo form_error('title'); ?></span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label" >Video Description</label>
					<div class="col-sm-6">
						<!-- <input placeholder="Video Description"  name="title" id="title" value="<?php echo set_value('title');?>"  class="form-control" type="text"  />   -->
						<textarea name="description" id="description"  class="form-control"></textarea>
						<span class="red"><?php echo form_error('description'); ?></span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label" >Video Tags</label>
					<div class="col-sm-6">
						<input placeholder="Example : needed,HD,High"  name="tags" id="tags" value="<?php echo set_value('tags');?>"  class="form-control" type="text"  />		          
						<span class="red"><?php echo form_error('tags'); ?></span>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label" >Video Category <span class="red">*</span></label>
					<div class="col-sm-6">
						<?php
							$video_category[''] = 'Please Select';
							echo form_dropdown('category',$video_category,set_value('category'),' class="form-control" id="category"'); 
						?>
						<span class="red"><?php echo form_error('category'); ?></span>
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
							echo form_dropdown('privacy_type',$privacy_type,set_value('privacy_type'),' class="form-control" id="privacy_type"'); 
						?>
						<span class="red"><?php echo form_error('privacy_type'); ?></span>
					</div>
				</div>
				

				<div class="form-group">
					<label class="col-sm-3 control-label" >Time Zone <span class="red">*</span></label>
					<div class="col-sm-6">
						<?php
							$time_zone_list[''] = 'Please Select';
							echo form_dropdown('time_zone',$time_zone_list,set_value('time_zone'),' class="form-control" id="time_zone"'); 
						?>
						<span class="red"><?php echo form_error('time_zone'); ?></span>
					</div>
				</div>


				<div class="form-group" id="schedule_time_div">
					<label class="col-sm-3 control-label" for="">Schedule Time <span class="red">*</span></label>
					<div class="col-sm-6">
						<input placeholder="Time Schedule"  name="schedule_time" id="schedule_time" value="<?php echo set_value('schedule_time');?>"  class="form-control datepicker" type="text"  />
						<span class="red"><?php echo form_error('schedule_time'); ?></span>          
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label" >Video File  <span class="red">*</span></label>
					<div class="col-sm-6">
						<input class="form-control" name="video_url" id="video_url" placeholder="" type="hidden"> 
					 	<div id="video_url_upload"><?php echo $this->lang->line('Upload');?></div>						
			            <div class="well"><b>Format :  mov/mpeg4/mp4/avi/wmv/mpegps/flv/3gpp/webm <br/>Max size: 25MB</b></div>
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

    $j(document).ready(function(){
    	var base_url = "<?php echo base_url(); ?>";



		$("#video_url_upload").uploadFile({
	        url:base_url+"youtube_live_event/upload_video",
	        fileName:"myfile",
	        maxFileSize:25*1024*1024,
	        showPreview:false,
	        returnType: "json",
	        dragDrop: true,
	        showDelete: true,
	        multiple:false,
	        maxFileCount:1, 
	        acceptFiles:".mov,.mpeg4,.mp4,.avi,.wmv,.mpegps,.flv,.3gpp,.webm",
	        deleteCallback: function (data, pd) {
	            var delete_url="<?php echo site_url('youtube_live_event/delete_uploaded_file');?>";
                $.post(delete_url, {op: "delete",name: data},
                    function (resp,textStatus, jqXHR) { 
                    	$("#video_url").val('');  
	                	load_preview(0,0,1);	                        
                    });
	           
	         },
	         onSuccess:function(files,data,xhr,pd)
	           {           
	               $("#video_url").val(data);
	           }
	    });
    });
</script>