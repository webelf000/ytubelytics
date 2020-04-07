<section class="content-header">
	<section class="content">
	<?php 
	if($this->session->userdata("success_message") == 1){
		echo "<div class='alert alert-success text-center'><h4 style='margin:0;'><i class='fa fa-check-circle'></i> ".$this->lang->line("your data has been successfully stored into the database.")."</h4></div>";
		$this->session->unset_userdata("success_message");
	}
	if($this->session->userdata("error_message") == 1){
		echo "<div class='alert alert-danger text-center'><h4 style='margin:0;'><i class='fa fa-remove'></i> ".$this->lang->line("your data has been failed to stored into the database.")."</h4></div>";
		$this->session->unset_userdata("error_message");
	}
	if($this->session->userdata("limit_exceeded") == 2){
		echo "<div class='alert alert-danger text-center'><h4 style='margin:0;'><i class='fa fa-remove'></i> ".$this->lang->line("sorry, your bulk limit is exceeded for this this module.")."<a href='".site_url('payment/usage_history')."'>".$this->lang->line("click here to see usage log")."</a></h4></div>";
		$this->session->unset_userdata("limit_exceeded");
	}
	if($this->session->userdata("limit_exceeded") == 3){
		echo "<div class='alert alert-danger text-center'><h4 style='margin:0;'><i class='fa fa-remove'></i> ".$this->lang->line("sorry, your monthly limit is exceeded for this this module.")."<a href='".site_url('payment/usage_history')."'>".$this->lang->line("click here to see usage log")."</a></h4></div>";
		$this->session->unset_userdata("limit_exceeded");
	}
	?>
		<div class="box box-info custom_box">
			<div class="box-header">
				<h3 class="box-title"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line("add")." - ".$this->lang->line("keyword"); ?></h3>
			</div><!-- /.box-header -->
			<!-- form start -->
			<form class="form-horizontal" action="<?php echo site_url().'video_position_tracking/keyword_tracking_settings_action';?>" method="POST">
				<div class="box-body">

					<!-- <div class="form-group">
						<label class="col-sm-3 control-label" >Name 
						</label>
						<div class="col-sm-9 col-md-6 col-lg-6">
							<input name="name" id="name" value="<?php echo set_value('name');?>"  class="form-control" type="text" required />		          
							<span class="red"><?php echo form_error('name'); ?></span>
						</div>
					</div> -->

					<div class="form-group">
						<label class="col-sm-3 control-label" ><?php echo $this->lang->line("keyword"); ?> * 
						</label>
						<div class="col-sm-9 col-md-6 col-lg-6">
							<input name="keyword" id="keyword" value="<?php echo set_value('keyword');?>"  class="form-control" type="text" required />		          
							<span class="red"><?php echo form_error('keyword'); ?></span>
						</div>
					</div>


					<div class="form-group">
						<label class="col-sm-3 control-label" ><?php echo $this->lang->line("name"); ?>
						</label>
						<div class="col-sm-9 col-md-6 col-lg-6">
							<input name="name" id="name" value="<?php echo set_value('name');?>"  class="form-control" type="text" />		          
							<span class="red"><?php echo form_error('name'); ?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" ><?php echo $this->lang->line("video ID"); ?> *
						</label>
						<div class="col-sm-9 col-md-6 col-lg-6">
							<input name="video_id" id="video_id" value="<?php echo set_value('video_id');?>"  class="form-control" type="text" required />		          
							<span class="red"><?php echo form_error('video_id'); ?></span>
						</div>
					</div>

				</div> <!-- /.box-body --> 
				<div class="box-footer">
					<div class="form-group">
						<div class="col-sm-12 text-center">	
							<input name="submit" id="submit" type="submit" class="btn btn-warning btn-lg" value="<?php echo $this->lang->line("save");?>"/>
							<input type="button" class="btn btn-default btn-lg" value="<?php echo $this->lang->line("cancel");?>" onclick='goBack("video_position_tracking/keyword_list")'/>  
						</div>
					</div>
				</div><!-- /.box-footer -->         
			</div><!-- /.box-info -->       
		</form>     
	</div>
</section>
</section>