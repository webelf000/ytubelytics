<style>
	.margin_top{
		margin-top:20px;
	}
	
	.padding{
		padding:15px;
	} 
	
	.count_text{
		margin: 0px;
		padding: 0px;
		color: orange;
	}
	.cover_image{
		height: 200px;
		width: 200px;
	}
</style>

<div class="container-fluid margin_top">
	<h3 class="orange text-center"><div class="well">Imported Youtube Channels</div></h3>
	<div class="row">
	<?php 
		if(!empty($channel_list_info))
		{
			foreach($channel_list_info as $value)
			{

	?>
		<div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
			<div class="well" style="border-top: 2px solid orange;">				
				<div class="row">
					<div class="col-xs-12 text-center clearfix">
						<span class="pull-right"><i channel_table_id="<?php echo $value['id']; ?>" class="delete_channel fa fa-2x fa-remove red" title="Do you want to delete this channel?"></i></span>
						<h4 style="color:purple;"><?php echo substr($value['title'], 0, 40).'..'; ?></h4>
					</div>
					<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
						<div class="text-center">
							<img class="cover_image img-circle" src="<?php echo $value['cover_image']; ?>" alt="">
						</div>
					</div>
				</div>
				<div class="row padding">
					<div class="col-md-4 col-lg-4 col-sm-12 col-xs-12 text-center">
						<h2 class="count_text"><?php echo custom_number_format($value['view_count']); ?></h2>
						<p>VIEWS</p>
					</div>
					<div class="col-md-4 col-lg-4 col-sm-12 col-xs-12 text-center" style="border-left:2px solid red;border-right:2px solid red;">
						<h2 class="count_text"><?php echo custom_number_format($value['video_count']); ?></h2>
						<p>VIDEOS</p>
					</div>
					<div class="col-md-4 col-lg-4 col-sm-12 col-xs-12 text-center">
						<h2 class="count_text"><?php echo custom_number_format($value['subscriber_count']); ?></h2>
						<p>SUBSCRIBERS</p>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-6 text-center">
						<div class="btn btn-success"><a style='text-decoration: none;color: white;' target="_blank" href="<?php echo base_url("youtube_analytics/get_individual_channel_info")."/".$value['id']; ?>">Channel Analytics</a></div>
					</div>
					<div class="col-xs-6">
						<div class="btn btn-success"><a style='text-decoration: none;color: white;' target="_blank" href="<?php echo base_url("youtube_analytics/get_channel_video_list")."/".$value['id']; ?>">Channel's videos</a></div>
					</div>
				</div>
			</div>
		</div>
	<?php 
			}
		}
		else
			echo "<h4><div class='alert alert-warning text-center'> No Data to show <a href=".site_url("youtube_analytics/index").">Click here to login with google</a></div></h4>";
	?>		
	</div>
</div>


<script>
	$j("document").ready(function(){
		var base_url = "<?php echo base_url(); ?>";

		$(".delete_channel").click(function(){
            var result = confirm("Do you want to delete this channel from your database ?");
            if(result)
            {
                var channel_table_id = $(this).attr('channel_table_id');

                $.ajax
                ({
                   type:'POST',
                   async:false,
                   url:base_url+'youtube_analytics/channel_delete_result',
                   data:{channel_table_id:channel_table_id},
                   success:function(response)
                    {
                        if(response == 'success')
	                        location.reload();
                    	if(response == 'error')
                    		alert('Something went wrong, please try again.');
                    }
                       
                });
            }
        });
	});
</script>