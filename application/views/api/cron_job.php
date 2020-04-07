<?php $this->load->view('admin/theme/message'); ?>
<section class="content-header">
   <section class="content">
	     	<?php 
			$text="Generate Your ".$this->config->item("product_short_name")." API Key";
			$get_key_text="Get Your ".$this->config->item("product_short_name")." API Key";
			if(isset($api_key) && $api_key!="") 
			{
				$text="Re-generate Your ".$this->config->item("product_short_name")." API Key";
				$get_key_text="Your ".$this->config->item("product_short_name")." API Key";
	   		} 
	   		?>		    

		<?php  if($api_key=="")  {  echo "<a class='btn btn-lg btn-warning' href='".site_url("native_api/index")."'><i class='fa fa-key'></i> Get your API Key</a><br/><br/>";$api_key="YOUR_API_KEY";}?>
			<!-- <div>
				<h4 style="margin:0">
					<div class="alert alert-info" style="margin-bottom:0;">
						<i class="fa fa-clock-o"></i> Membership Expiry Alert Cron Job Command 
					</div>
				</h4>
				<div class="well" style="background:#F9F2F4;margin-top:0;border-radius:0;;">
					<?php echo "curl ".site_url("native_api/send_notification")."/".$api_key; ?>
				</div>
			</div>		 -->

			<div>
				<h4 style="margin:0">
					<div class="alert alert-info" style="margin-bottom:0;">
						<i class="fa fa-clock-o"></i>Scheduled Upload Cron Job Command 
					</div>
				</h4>
				<div class="well" style="background:#F9F2F4;margin-top:0;border-radius:0;;">
					<?php echo "curl ".site_url("native_api/video_upload_to_youtube")."/".$api_key; ?>
				</div>
			</div>	


			<div>
				<h4 style="margin:0">
					<div style="margin-bottom:0;" class="alert alert-info">
						<i class="fa fa-clock-o"></i> Keyword/Video Rank Tracking Cron Job Command 
					</div>
				</h4>
				<div style="background:#F9F2F4;margin-top:0;border-radius:0;;" class="well">
					<?php echo "curl ".site_url("native_api/get_keyword_position_data")."/".$api_key; ?>
				</div>
			</div>	
			
			

			
		

   </section>
</section>



