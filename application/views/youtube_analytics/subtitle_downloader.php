<br/>
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 well">
		<div class='text-center'><h4 class="text-info"><strong><?php echo $this->lang->line("Subtitle Downloader"); ?></strong></h4><div class="alert alert-info">Please note that all the videos of youtube hasn't subtitle/caption. Those videos which have the option enabled , we can grab the subtitles of those videos only.</div></div>
		<hr>
		<br/>
		<form enctype="multipart/form-data" method="post" class="form-inline" id="new_search_form" style="margin-top:60px margin-left:10px">
			<div class="row">				
				<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
					<input id="video_id" type="text" value="<?php echo $called_video_id?>"" style="width:100%;padding:5px;" placeholder="<?php echo $this->lang->line('Youtube Video ID'); ?>">					
				</div> 	
				<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
					<?php echo form_dropdown("lang",$lang_list,"en","class='form-control' id='lang'");?>				
				</div> 		
				<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">   
					<button type="button"  id="new_search_button" class="btn btn-info"><i class="fa fa-search"></i> <?php echo $this->lang->line("download"); ?></button>
				</div>				
			</div>
			<div class="row">				
				<div class="form-group col-xs-12">
				<a class="label label-warning" data-toggle="modal" href='#find_video_id'>How to find youtube video ID ?</a>
				</div>
			</div>
			<br/>
			<br/>

		</form>
	</div>
</div>


<script>	

$j("document").ready(function(){		
		if($('#video_id').val()!="")
		$("#new_search_button").click();
});
	
		var base_url="<?php echo base_url(); ?>";
		
		$(document.body).on('click','#new_search_button',function(){
			$("#tag_download_div").html('');
			var video_id=$("#video_id").val();
			var lang=$("#lang").val();
			
			if(video_id==''){
				alert("<?php echo $this->lang->line("please enter youtube video ID"); ?>");
				return false;
			}			
			
			$("#success_msg").html('<img class="center-block" src="'+base_url+'assets/pre-loader/custom.gif" alt="Searching..."><br/>');
			$.ajax({
				url:base_url+'youtube_marketer/subtitle_downloader_action',
				type:'POST',
				data:{video_id:video_id,lang:lang},
				success:function(response){					
					
					if(response=="0")
					{
						$("#success_msg").html("<h4><div class='alert alert-warning text-center'>Sorry, No subtitle found.</div></h4>");

					}

					else if (response == 'limit_cross')
					{
						var usage_log_link = "<?php echo site_url('payment/usage_history'); ?>";
						var limit_cross_error = "<h4><div class='alert alert-danger text-center'>sorry, your monthly limit is exceeded for this module. <a href='"+usage_log_link+"'>click here to see usage log</a></div></h4>";
						$("#success_msg").html(limit_cross_error);
					}

					else
					{
						$("#tag_download_div").html('<a href="<?php echo base_url()."download/youtube/subtitle_{$this->user_id}_{$this->download_id}.srt" ?>" target="_blank" class="btn btn-lg btn-warning"><i class="fa fa-cloud-download"></i> <b><?php echo $this->lang->line("Download SRT"); ?></b></a>');							
						$("#success_msg").html(response);
					}
					
				}
				
			});
			
			
		});
		
	
</script>


				

	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 wow fadeInRight text-center">		  
			<div class="loginmodal-container">
	
				<div id="tag_download_div" style="display:inline">
	
				</div>
				                 
			</div>
		</div>						
	</div>
				
	<br/><br/>			
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1" id="success_msg"></div>     
	</div> 
	

	<div class="modal fade" id="find_video_id">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">How to get youtube video ID ?</h4>
				</div>
				<div class="modal-body">
					<ol>
						<li>Visit any youtube video ( example : <a target="_BLANK" href="https://www.youtube.com/watch?v=NXqmvFy9cXE">https://www.youtube.com/watch?v=lpXacmotd4A</a> )<br/></li>
						<li>Look at the url , you will find the ID at the end of the url. (example: Video ID of the example ur is <b>lpXacmotd4A</b>)</li>
					</ol>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>