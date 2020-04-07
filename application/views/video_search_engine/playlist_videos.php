<section class="content-header">
	<section class="content">
		<div class="box box-info custom_box">
			<div class="box-header">
				<h3 class="box-title"><i class="fa fa-camera"></i> <?php echo $this->lang->line('playlist videos'); ?></h3>
			</div><!-- /.box-header -->
			<!-- form start -->		
				<div class="box-footer">
					<div class="col-xs-12 text-center" id="domain_success_msg"></div> 					
					<div class="col-xs-12 text-center" id="progress_msg">
						<b><span id="domain_progress_msg_text"></span></b><br/>
					</div>

					<div class='space'></div>
					<div id="response"><?php echo $output;?></div>
				</div><!-- /.box-footer -->      
		</div><!-- /.box-info -->       
	</section>
</section>


	<script>
		$j("document").ready(function(){
			$colorbox(document).bind("cbox_complete", function(){
			if($("#cboxTitle").height() > 20){
			$("#cboxTitle").hide();
			$("#cboxLoadedContent").append(""+$("#cboxTitle").html()+"").css({color: $("#cboxTitle").css("color")});

			}
			});

	      var width=$(window).width();
	      var a;
	      var b;

	      if(width<400) a=90;
	      else a= 55;

	      b= 9*a/16;
	      var iframe_width=width*a/100;
	      var iframe_height=iframe_width*b/a;
            $colorbox(".youtube").colorbox({iframe:true, innerWidth:iframe_width, innerHeight:iframe_height});
      	});
      
    </script>
    