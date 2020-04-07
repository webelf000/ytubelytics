<?php 
$src= base_url("custom_downloader");
if($video_id!="") $src.="?video_id={$video_id}";
?>
<iframe src="<?php echo $src;?>" frameborder="0" height="1500px" width="100%"></iframe>