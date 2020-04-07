<!DOCTYPE html>

<html>

<head>

	<title><?php echo $page_title;?></title>

	<meta charset="utf-8">
	<link rel="shortcut icon" href="<?php echo base_url();?>assets/images/favicon.png"> 
	<meta name="description" content="<?php echo $meta_description;?>">

	<meta name="keywords" content="<?php echo $meta_keyword;?>">

	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">

	<link href="<?php echo site_url();?>assets/login/plugins/jquery.ui/smoothness/jquery-ui-1.10.1.custom.css" rel="stylesheet" >

	<link href="<?php echo site_url();?>assets/login/plugins/bootstrap/bootstrap.min.css" rel="stylesheet" type="text/css">

	<link rel="stylesheet" href="<?php echo site_url();?>assets/login/plugins/daterangepicker/daterangepicker-bs3.css">

    <link rel="stylesheet" href="<?php echo site_url();?>assets/login/plugins/simplepicker/jquery.simple-dtpicker.css">

    <link rel="stylesheet" href="<?php echo site_url();?>assets/login/plugins/icheck/icheck.css">

	<link href="<?php echo site_url();?>assets/login/plugins/elfinder/css/elfinder.min.css" rel="stylesheet" >

    <link href="<?php echo site_url();?>assets/login/plugins/elfinder/css/theme.css" rel="stylesheet" >

    <link href="<?php echo site_url();?>assets/login/plugins/elfinder/css/dialog.css" rel="stylesheet" >

	<link href="<?php echo site_url();?>assets/login/css/fonts.css" rel="stylesheet" type="text/css">

	<link href="<?php echo site_url();?>assets/login/css/style.css" rel="stylesheet" type="text/css">

	<script src="<?php echo site_url();?>assets/login/plugins/jquery/jquery.min.js"></script>

</head>

<body>

	<?php $this->load->view("login/header"); ?>
	<?php $this->load->view($body); ?>
	<?php $this->load->view("login/footer"); ?>

	<!--Modal Login-->

	<div id="myModal" class="modal fade" role="dialog">

	    <div class="modal-dialog">

	        <div class="modal-content">

	        	<form class="formUpdateAccount">

		            <div class="modal-header bg-owner">

		                <button type="button" class="close" data-dismiss="modal">&times;</button>

		                <h4 class="modal-title">add-new-account</h4>

		            </div>

		            <div class="modal-body">

		                <div class="box-body text-center">


		                		<ul class="account-errors has-message error text-left" style="display: block;">

		                			<li><i class="fa fa-exclamation-circle"></i>desc-account-limit</li>

		                		</ul>

		                	<div class="form-horizontal" style="max-width: 400px; margin: auto;">

		                        <div class="form-group">

		                            <label class="col-sm-3 control-label mt0">username</label>

		                            <div class="col-sm-9">

		                                <input type="text" name="username" class="form-control">

		                            </div>

		                        </div>

		                        <div class="form-group">

		                            <label class="col-sm-3 control-label mt0">password</label>

		                            <div class="col-sm-9">

		                                <input type="password" name="password" class="form-control">

		                            </div>

		                        </div>

		                        <div class="form-group">

		                            <label class="col-sm-3 control-label"></label>

		                            <div class="col-sm-9">

		                                <div class="msg-add-new-account"></div>

		                            </div>

		                        </div>

		                    </div>

		                    <div class="form-group text-red">you-can-be-assured</div>

		                </div>

		            </div>

		            <div class="modal-footer">

		                <button type="submit" class="btn btn-success">submit</button>

		            </div>

	            </form>

	        </div>

	    </div>

	</div>



	<!--javascript-->

	<script src="<?php echo site_url();?>assets/login/plugins/bootstrap/bootstrap.min.js"></script>

	<script src="<?php echo site_url();?>assets/login/plugins/highcharts/highcharts.js"></script>

	<script src="<?php echo site_url();?>assets/login/plugins/jquery.ui/jquery.ui.min.js"></script>

	<script src="<?php echo site_url();?>assets/login/plugins/icheck/icheck.min.js"></script>

	<script src="<?php echo site_url();?>assets/login/plugins/elfinder/js/elfinder.full.js"></script>

    <script src="<?php echo site_url();?>assets/login/plugins/elfinder/js/jquery.dialogelfinder.js"></script>

    <script src="<?php echo site_url();?>assets/login/plugins/daterangepicker/moment.min.js"></script>

    <script src="<?php echo site_url();?>assets/login/plugins/daterangepicker/daterangepicker.js"></script>

    <script src="<?php echo site_url();?>assets/login/plugins/simplepicker/jquery.simple-dtpicker.js"></script>

	<script src="<?php echo site_url();?>assets/login/js/instagram.js"></script>

	<script src="<?php echo site_url();?>assets/login/js/main.js"></script>

</body>

</html>