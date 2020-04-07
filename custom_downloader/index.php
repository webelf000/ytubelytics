<?php 
$video_id="";
if(isset($_GET['video_id'])) $video_id=$_GET['video_id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Youtube Downloader</title>
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="css/custom.css" rel="stylesheet">
</head>
<?php if($video_id!="") $str='onload="autosubmit()"'; else $str=''; ?>
<body <?php echo $str; ?>>
	<form class="form-download" method="get" id="download" action="getvideo.php">
		<h3 class="form-download-heading">Youtube Downloader</h3><hr>
		<input type="text" style="width:100%" name="videoid" id="videoid" size="40" value="<?php echo $video_id;?>" placeholder="YouTube Link or VideoID" autofocus/><br>
		<input class="btn btn-primary" type="submit" name="type" id="type" value="Download" />	

	<!-- @TODO: Prepend the base URI -->
<?php
include_once('common.php');

?>
</form>
</body>
</html>
<script type="text/javascript">
	function autosubmit()
	{
		document.getElementById("type").click();
	}
</script>
