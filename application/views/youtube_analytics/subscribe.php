<br/>
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 well">
		<div class='text-center'><h4 class="text-info"><strong><?php echo $this->lang->line("Youtube Channel Subscription Plugin"); ?></strong></h4></div>
		<hr>
		<br/>
		<form enctype="multipart/form-data" method="post" class="form-horizontal" id="new_search_form" style="margin-top:60px margin-left:10px">			
				<div class="form-group">
					<label class="col-xs-4 control-label" >Youtube Channel ID</label>
					<div class="col-xs-6">
						<input type="text" id="channel_id" class="form-control">
						<a class="label label-warning" data-toggle="modal" href='#find_video_id'>How to find youtube channel ID ?</a>
					</div>
				</div>	
				<div class="form-group">
					<label class="col-xs-4 control-label" >Button Layout</label>
					<div class="col-xs-6">
						<?php echo form_dropdown('layout',array("default"=>"Default","full"=>"Full"), 'full',"class='form-control' id='layout'"); ?>
					</div>
				</div>				
				<div class="form-group">
					<label class="col-xs-4 control-label" >Show Subscriber Count ?</label>
					<div class="col-xs-6">
						<?php echo form_dropdown('count',array("default"=>"Yes","hidden"=>"No"), 'default',"class='form-control' id='count'"); ?>
					</div>
				</div>
				<div class="form-group">   
					<div class="col-xs-4"></div>
					<div class="col-xs-6">
						<button type="button"  id="new_search_button" class="btn btn-info"><i class="fa fa-code"></i> <?php echo $this->lang->line("Get Subscription Button Embed Code"); ?></button>
					</div>
				</div>

			<br/>

		</form>
	</div>
</div>


<script>	

	var base_url="<?php echo base_url(); ?>";

	function subscription_action()
	{
		var channel_id=$("#channel_id").val();
		var layout=$("#layout").val();
		var count=$("#count").val();
		
		if(channel_id==''){
			alert("please enter youtube channel ID");
			return false;
		}			
		
		$("#preview").html('<img class="center-block" src="'+base_url+'assets/pre-loader/custom.gif" alt="Searching..."><br/>');
		$("#success_msg").html('<img class="center-block" src="'+base_url+'assets/pre-loader/custom.gif" alt="Searching..."><br/>');
		$.ajax({
			url:base_url+'youtube_marketer/subscribe_plugin_action',
			type:'POST',
			dataType:'JSON',
			data:{channel_id:channel_id,layout:layout,count:count},
			success:function(response){					
				$("#preview_con").show();
				$("#success_msg").html(response.printdata);
				$("#preview").html(response.code);
			}
			
		});			

	}

	$j("document").ready(function(){
		// $("#theme,#layout,#count").on('change',function(){
		// 	subscription_action();
		// });

		// $("#channel_id").on('blur',function(){
		// 	subscription_action();
		// });

		$("#new_search_button").on('click',function(){
			subscription_action();
		});
	});
		
</script>

			

	<br/>

			
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6  col-md-offset-1 col-lg-6 col-lg-offset-1 loading" id="success_msg"></div>     
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 loading" id="preview_con" style="display:none">
			<div class="col-xs-12 well" style="margin-bottom:10px;"> 
          	<h3 class="text-center">Preview</h3><br/><hr>
          	<div><center id="preview"></center></div>
		</div>     
	</div> 

	<div class="modal fade" id="find_video_id">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">How to get youtube channel ID ?</h4>
				</div>
				<div class="modal-body">
					<ol>
						<li>Visit any youtube channel ( example : <a target="_BLANK" href="https://www.youtube.com/channel/UCbuRhRXR8KcHjQfdvqPE7XQ">https://www.youtube.com/channel/UCbuRhRXR8KcHjQfdvqPE7XQ</a> )<br/><img class="img-thumbnail img-responsive" src="<?php echo base_url("assets/images/channel_id.png");?>"/><br/><br/></li>
						<li>Look at the url , you will find the ID at the end of the url. (example: Channel ID of the example ur is <b>https://www.youtube.com/channel/UCbuRhRXR8KcHjQfdvqPE7XQ</b>)</li>
					</ol>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>