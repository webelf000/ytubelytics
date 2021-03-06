<!DOCTYPE html>
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title><?php echo $this->config->item('product_name')." | ".$this->lang->line("slogan");?></title>
	<meta name="description" content="complete visitor and seo analytics">
	<meta name="author" content="<?php echo $this->config->item('institute_address1');?>">

	<!-- Mobile Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Favicon -->
	<link rel="shortcut icon" href="<?php echo base_url();?>assets/images/favicon.png"> 

	<!-- Web Fonts -->
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700,300&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Raleway:700,400,300' rel='stylesheet' type='text/css'>

	<!-- Bootstrap core CSS -->
	<link href="<?php echo base_url();?>assets/site/bootstrap/css/bootstrap.css" rel="stylesheet">

	<!-- Font Awesome CSS -->
	<link href="<?php echo base_url();?>assets/site/fonts/font-awesome/css/font-awesome.css" rel="stylesheet">

	<!-- Plugins -->
	<link href="<?php echo base_url();?>assets/site/css/animations.css" rel="stylesheet">

	<!-- Worthy core CSS file -->
	<link href="<?php echo base_url();?>assets/site/css/style.css" rel="stylesheet">

	<!-- Custom css --> 
	<link href="<?php echo base_url();?>assets/site/css/custom.css" rel="stylesheet">

	<!-- for RTL support -->
	<?php 
// if($this->config->item('language')=="arabic")  
	if($this->is_rtl) 
		{ ?>
			<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.2.0-rc2/css/bootstrap-rtl.min.css" rel="stylesheet" type="text/css" />
			<link href="<?php echo base_url();?>assets/css/rtl.css" rel="stylesheet" type="text/css" />       
			<?php
		}
		?>
		<script src="<?php echo base_url();?>plugins/xregexp/xregexp.js" type="text/javascript"></script>

	</head>

	<body class="no-trans">
		<!-- scrollToTop -->
		<!-- ================ -->
		<div class="scrollToTop"><i class="icon-up-open-big"></i></div>

		<!-- header start -->
		<!-- ================ --> 
		<header class="header fixed clearfix navbar navbar-fixed-top">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-2">

						<!-- header-left start -->
						<!-- ================ -->
						<div class="header-left clearfix">

							<!-- logo -->
							<div class="logo smooth-scroll">
								<a href="#banner"><img id="logo" style="max-height:50px !important" src="<?php echo base_url();?>assets/images/logo.png" alt="<?php echo $this->config->item('product_name');?>"></a>
							</div>


						</div>
						<!-- header-left end -->

					</div>

					<div class="col-xs-6 col-sm-9 col-md-8">

						<!-- header-right start -->
						<!-- ================ -->
						<div class="header-right clearfix">

							<!-- main-navigation start -->
							<!-- ================ -->
							<div class="main-navigation animated">

								<!-- navbar start -->
								<!-- ================ -->
								<nav class="navbar navbar-default" role="navigation">
									<div class="container-fluid">

										<!-- Toggle get grouped for better mobile display -->
										<div class="navbar-header">
											<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1" style="margin-top:25px !important">
												<span class="sr-only">Toggle navigation</span>
												<span class="icon-bar"></span>
												<span class="icon-bar"></span>
												<span class="icon-bar"></span>
											</button>
										</div>

										<!-- Collect the nav links, forms, and other content for toggling -->
										<div class="collapse navbar-collapse scrollspy smooth-scroll" id="navbar-collapse-1">
											<ul class="nav navbar-nav navbar-right">
												<li class="active"><a href="#banner"><?php echo $this->lang->line("home"); ?></a></li>
												<li><a href="#about"><?php echo $this->lang->line("about"); ?></a></li>
												<li><a href="#features"><?php echo $this->lang->line("features"); ?></a></li>
												<li><a href="#pricing"><?php echo $this->lang->line("pricing"); ?></a></li>
												<li><a href="#contact"><?php echo $this->lang->line("contact"); ?></a></li>
												<li><a href="<?php echo site_url('home/login'); ?>"><?php echo $this->lang->line("login"); ?></a></li>
												<?php if($this->session->userdata("logged_in")!=1) 
												{?>
													<li><a href="<?php echo site_url('home/sign_up'); ?>"><?php echo $this->lang->line("sign up"); ?></a></li>
													<?php 
												} 
												else
													{ ?>
														<li><a href="<?php echo site_url('home/logout'); ?>"><?php echo $this->lang->line("logout"); ?></a></li>
														<?php
													} ?>	
												</ul>
											</div>

										</div>
									</nav>
									<!-- navbar end -->

								</div>
								<!-- main-navigation end -->

							</div>
							<!-- header-right end -->

						</div>


						<div class="col-xs-6 col-sm-3 col-md-2">
							<div class="form-group">               
								<?php 
								$select_lan="english";
								if($this->session->userdata("selected_language")=="") $select_lan=$this->config->item("language");
								else $select_lan=$this->session->userdata("selected_language");
								echo form_dropdown('language',$language_info,$select_lan,'class="form-control  pull-right" id="language_change" style="width:125px;height:40px;margin-top:20px;"');  ?>              
								<span class="red"><?php echo form_error('language'); ?></span>           
							</div> 
						</div>
					</div>
				</div>
			</header>

			<!-- header end -->

			<!-- banner start -->
			<!-- ================ -->
			<div id="banner" class="banner">
				<div class="banner-image"></div>
				<div class="banner-caption">
					<div class="container">
						<div class="row">
							<div class="col-md-8 col-md-offset-2 object-non-visible" data-animation-effect="fadeIn">
								<h2 class="text-center"> <span> <?php echo $this->config->item('product_name');?></span></h2>
								<p class="lead text-center">
									<?php echo $this->lang->line("catch line"); ?></p>
									<p class="lead text-center">
										<a href="<?php echo site_url('home/sign_up'); ?>" class="btn btn-warning" style="padding:10px;font-size:18px;font-weight:bold;"> <?php echo $this->lang->line('sign up now');?></a>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- banner end -->

				<!-- section start -->
				<!-- ================ -->
				<div class="section clearfix object-non-visible" data-animation-effect="fadeIn">
					<div class="container">
						<div class="row">
							<div class="col-md-12">
								<h1 id="about" class="title text-center"> <?php echo $this->config->item('about');?> <span><?php echo $this->config->item('product_name');?></span></h1>
								<p class="lead text-center"> <?php echo $this->config->item('slogan');?></p>
								<div class="space"></div>
								<div class="row">
									<div class="col-md-6">
										<img src="<?php echo base_url();?>assets/site/images/section-image-1.png" alt="">
										<div class="space"></div>
									</div>
									<div class="col-md-6">
										<p> <?php echo $this->lang->line('description 1');?></p>
										<p> <?php echo $this->lang->line('description 2');?></p>
										<p> <?php echo $this->lang->line('description 3');?></p>
										<p> <?php echo $this->lang->line('description 4');?></p>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12 col-md-5 col-md-offset-1">
										<ul class="list-unstyled">
											<li><i class="fa fa-caret-right pr-10 text-colored"></i> <?php echo $this->lang->line("visitor analysis");?> </li>
											<li><i class="fa fa-caret-right pr-10 text-colored"></i> <?php echo $this->lang->line("social network analysis");?> </li>
											<li><i class="fa fa-caret-right pr-10 text-colored"></i> <?php echo $this->lang->line("rank & index analysis");?></li>
											<li><i class="fa fa-caret-right pr-10 text-colored"></i> <?php echo $this->lang->line("domain analysis");?> </li>
											<li><i class="fa fa-caret-right pr-10 text-colored"></i> <?php echo $this->lang->line("ip analysis");?> </li>
											<li><i class="fa fa-caret-right pr-10 text-colored"></i> <?php echo $this->lang->line("link analysis");?> </li>
											<li><i class="fa fa-caret-right pr-10 text-colored"></i> <?php echo $this->lang->line("keyword analysis");?> </li>
										</ul>
									</div>
									<div class="col-xs-12 col-md-5">
										<ul class="list-unstyled">
											<li><i class="fa fa-caret-right pr-10 text-colored"></i> <?php echo $this->lang->line("backlink & ping");?> </li>
											<li><i class="fa fa-caret-right pr-10 text-colored"></i> <?php echo $this->lang->line("malware scan");?> </li>
											<li><i class="fa fa-caret-right pr-10 text-colored"></i> <?php echo $this->lang->line("google adwords scraper");?> </li>
											<li><i class="fa fa-caret-right pr-10 text-colored"></i> <?php echo $this->lang->line("utilities");?> </li>
											<li><i class="fa fa-caret-right pr-10 text-colored"></i> <?php echo $this->lang->line("native api");?> + <?php echo $this->lang->line("native widget");?> </li>
											<li><i class="fa fa-caret-right pr-10 text-colored"></i> <?php echo $this->lang->line("google url shortener");?></li>
											<li><i class="fa fa-caret-right pr-10 text-colored"></i> <?php echo $this->lang->line("code minifier");?></li>
										</ul>
									</div>
								</div>
								<div class="space"></div>
							</div>
						</div>
					</div>
				</div>
				<!-- section end -->

				<div class="container">
					<div class="row">
						<div class="col-xs-12"><img src="<?php echo base_url();?>assets/site/images/responsive.png" alt=""></div>
					</div>
				</div>



				<!-- section start -->
				<!-- ================ -->
				<div class="default-bg space blue">
					<div class="container">
						<div class="row">
							<div class="col-md-8 col-md-offset-2">
								<h1 class="text-center"><?php echo $this->lang->line("analysis was never this easy!");?></h1>
							</div>
						</div>
					</div>
				</div>
				<!-- section end -->


				<!-- section start -->
				<!-- ================ -->
				<div class="section translucent-bg bg-image-2 pb-clear">
					<div class="container object-non-visible" data-animation-effect="fadeIn">
						<h1 id="features" class="title text-center"><?php echo $this->lang->line("detailed features");?></h1>
						<div class="space"></div>
						<div class="row">

							<div class="col-xs-12 col-md-4">
								<div class="media testimonial">
									<div class="media-left">								
										<i class="fa fa-line-chart fa-3x"></i>
									</div>
									<div class="media-body">
										<h3 class="media-heading"><?php echo $this->lang->line("visitor analysis");?></h3>
										<blockquote>
											<p>
												<ul>
													<li>Unique Visitor</li>
													<li>Page View</li>
													<li>Bounce Rate</li>
													<li>Average Stay Time</li>
													<li>Average Visit</li>
													<li>Traffic Analysis</li>
													<li>Top Refferer</li>
													<li>New &amp; Returning Visitor</li>
													<li>Content Overview</li>
													<li>Country &amp; Browser Report</li>
													<li>OS &amp; Device Report</li>
												</ul>
											</p>
										</blockquote>
									</div>
								</div>
							</div>

							<div class="col-xs-12 col-md-4">
								<div class="media testimonial">
									<div class="media-left">								
										<i class="fa fa-share-alt fa-3x"></i>
									</div>
									<div class="media-body">
										<h3 class="media-heading"><?php echo $this->lang->line("social network analysis");?></h3>
										<blockquote>
											<p>
												<ul>
													<li>Facebook Like, Share, Comment</li>
													<li>Google+ Comment</li>
													<li>LinkedIn Share</li>
													<li>Xing Share</li>
													<li>Reddit Score, Up, Down</li>
													<li>Pinterest Pin</li>
													<li>Buffer Share</li>
													<li>StumbleUpon List,View, Like, Commnet</li>
												</ul>
											</p>								

										</blockquote>
									</div>
								</div>
							</div>

							<div class="col-xs-12 col-md-4">
								<div class="media testimonial">
									<div class="media-left">								
										<i class="fa fa-trophy fa-3x"></i>
									</div>
									<div class="media-body">
										<h3 class="media-heading"><?php echo $this->lang->line("rank & index analysis");?></h3>
										<blockquote>
											<p>
												<ul>
													<li>Alexa Rank</li>
													<li>Alexa Data</li>
													<li>SimilarWeb Rank &amp; Data</li>
													<li>MOZ Check <br/>(normalize/ raw rank, page/ domain authory)</li>
													<!-- <li>Google Page Rank</li> -->
													<li>Google Index</li>
													<li>Yahoo Index</li>
													<li>Bing Index</li>
												</ul>
											</p>								
										</blockquote>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-xs-12 col-md-4">
								<div class="media testimonial">
									<div class="media-left">								
										<i class="fa fa-server fa-3x"></i>
									</div>
									<div class="media-body">
										<h3 class="media-heading"><?php echo $this->lang->line("domain analysis");?></h3>
										<blockquote>
											<p>
												<ul>
													<li>Whois Search Data</li>
													<li>DMOZ Directory Listing</li>
													<li>Expired (Auction) Domain List</li>
												</ul>
											</p>
										</blockquote>
									</div>
								</div>
							</div>


							<div class="col-xs-12 col-md-4">
								<div class="media testimonial">
									<div class="media-left">								
										<i class="fa fa-map-marker fa-3x"></i>
									</div>
									<div class="media-body">
										<h3 class="media-heading"><?php echo $this->lang->line("ip analysis");?></h3>
										<blockquote>
											<p>
												<ul>
													<li>What is my IP</li>
													<li>Domain IP Information</li>
													<li>Sites in Same IP</li>
												</ul>
											</p>
										</blockquote>
									</div>
								</div>
							</div>

							<div class="col-xs-12 col-md-4">
								<div class="media testimonial">
									<div class="media-left">								
										<i class="fa fa-tags fa-3x"></i>
									</div>
									<div class="media-body">
										<h3 class="media-heading"><?php echo $this->lang->line("keyword analysis");?></h3>
										<blockquote>
											<p>
												<ul>
													<li>Keyword Analyzer</li>
													<li>Keyword Position</li>
													<li>Keyword Position Tracking (Daily)</li>
													<li>Correlared Trending Keywords</li>
													<li>Keyword Auto Suggestion</li>
												</ul>
											</p>
										</blockquote>
									</div>
								</div>
							</div>

						</div>

						<div class="row">
							<div class="col-xs-12 col-md-4">
								<div class="media testimonial">
									<div class="media-left">								
										<i class="fa fa-link fa-3x"></i>
									</div>
									<div class="media-body">
										<h3 class="media-heading"><?php echo $this->lang->line("backlink & ping");?></h3>
										<blockquote>
											<p>
												<ul>
													<li>Google Backlink Search</li>
													<li>Backlink Generator</li>
													<li>Website/Blog Ping</li>
												</ul>
											</p>
										</blockquote>
									</div>
								</div>
							</div>

							<div class="col-xs-12 col-md-4">
								<div class="media testimonial">
									<div class="media-left">								
										<i class="fa fa-shield fa-3x"></i>
									</div>
									<div class="media-body">
										<h3 class="media-heading"><?php echo $this->lang->line("malware scan");?></h3>
										<blockquote>
											<p>
												<ul>
													<li>Google Safe Browser</li>
													<li>Norton</li>
													<li>McAfee</li>
													<li>AVG</li>
												</ul>
											</p>
										</blockquote>
									</div>
								</div>
							</div>

							<div class="col-xs-12 col-md-4">
								<div class="media testimonial">
									<div class="media-left">								
										<i class="fa fa-anchor fa-3x"></i>
									</div>
									<div class="media-body">
										<h3 class="media-heading"><?php echo $this->lang->line("link analysis");?></h3>
										<blockquote>
											<p>
												<ul>
													<li>Link Analyzer <br/>(internal, external, doFollow, noFollow)</li>
													<li>Page Status Check</li>
												</ul>
											</p>
										</blockquote>
									</div>
								</div>
							</div>						

						</div>

						<div class="row">
							<div class="col-xs-12 col-md-4">
								<div class="media testimonial">
									<div class="media-left">								
										<i class="fa fa-asterisk  fa-3x"></i>
									</div>
									<div class="media-body">
										<h3 class="media-heading"><?php echo $this->lang->line("utilities");?></h3>
										<blockquote>
											<p>
												<ul>
													<li>Email Encoder/ Decoder</li>
													<li>URL Encoder/ Decoder</li>
													<li>Meta Tag Generator</li>
													<li>Robot Code Generator</li>
													<li>Plagiarism Check</li>
													<li>Valid Email Check</li>
													<li>Duplicate Email Filter</li>
												</ul>
											</p>
										</blockquote>
									</div>
								</div>
							</div>
							<div class="col-xs-12 col-md-4">
								<div class="media testimonial">
									<div class="media-left">								
										<i class="fa fa-cut fa-3x"></i>
									</div>
									<div class="media-body">
										<h3 class="media-heading"><?php echo $this->lang->line("google url shortener");?> </h3>
										<blockquote>
											<p>
											<ul>
												<li>URL Shortener</li>
												<li>Shortened URL Analytics</li>
											</ul>
											</p>
										</blockquote>
									</div>
								</div>
								<div class="media testimonial">
									<div class="media-left">								
										<i class="fa fa-pie-chart fa-3x"></i>
									</div>
									<div class="media-body">
										<h3 class="media-heading"><?php echo $this->lang->line("multilingual");?></h3>
		
									</div>
								</div>
								<div class="media testimonial">
									<div class="media-left">								
										<i class="fa fa-paypal fa-3x"></i>
									</div>
									<div class="media-body">
										<h3 class="media-heading">PayPal + Stripe</h3>
									</div>
								</div>
								
							</div>	

							<div class="col-xs-12 col-md-4">
								<div class="media testimonial">
									<div class="media-left">								
										<i class="fa fa-object-group fa-3x"></i>
									</div>
									<div class="media-body">
										<h3 class="media-heading"> <?php echo $this->lang->line("code minifier");?></h3>
										<blockquote>
												<ul>
												<li>HTML code minifier</li>
												<li>CSS code minifier</li>
												<li>JS code minifier</li>
												</ul>
										</blockquote>
									</div>
								</div>
								<div class="media testimonial">
									<div class="media-left">								
										<i class="fa fa-google fa-3x"></i>
									</div>
									<div class="media-body">
										<h3 class="media-heading"><?php echo $this->lang->line("google adwords scraper");?></h3>
							
									</div>
								</div>
								<div class="media testimonial">
									<div class="media-left">								
										<i class="fa fa-plug fa-3x"></i>
									</div>
									<div class="media-body">
										<h3 class="media-heading"> <?php echo $this->lang->line("native api");?> + <?php echo $this->lang->line("native widget");?></h3>

									</div>
								</div>
								
							</div>	
						</div>
					</div>			
				</div>
				<!-- section end -->


				<!-- section start -->
				<!-- ================ -->
				<div class="container-fluid">
					<div class="space"></div>
					<div class="row default-bg space blue">
						<div class="col-xs-12">
							<?php
							if(isset($default_package[0])) 
								{ ?>
									<h1 class="text-center"  id="pricing"  style="background: #55ACEE;">Trial : <?php echo $default_package[0]["validity"] ?> days</h1>
								<?php 
								} ?>
								<p><center><a style="color:#000;font-weight: bold;font-size: 22px;padding:12px 25px;text-decoration:none" class="btn btn-lg btn-default" href="<?php echo site_url('home/sign_up'); ?>"><?php echo $this->lang->line("sign up"); ?></a></center></p>
								</div>
							</div>

							<div class="row" >
								<?php 
								$i=0;
								$classes=array(1=>"tiny",2=>"small",3=>"medium",4=>"pro");
								foreach($payment_package as $pack)
								{ 	
									$i++;	
									?>
									<div class="col-xs-12 col-sm-6 col-md-3" style="padding-left:5px;padding-right:5px;">
										<div class="<?php echo $classes[$i]; ?>">
											<div class="pricing-table-header-<?php echo $classes[$i]; ?> text-center">
												<h2><?php echo $pack["package_name"]?></h2>
												<h3>Only @<?php echo $currency; ?> <?php echo $pack["price"]?> / <?php echo $pack["validity"]?> days</h3>
											</div>
											<div class="pricing-table-features" style="text-align:left !important;padding-left:3px;">
												<?php 
												$module_ids=$pack["module_ids"];
												$monthly_limit=json_decode($pack["monthly_limit"],true);
												$module_names_array=$this->basic->execute_query('SELECT module_name,id FROM modules WHERE FIND_IN_SET(id,"'.$module_ids.'") > 0  ORDER BY module_name ASC');  

												foreach ($module_names_array as $row) 
												{
													$limit=0;
													$limit=$monthly_limit[$row["id"]];
													if($limit=="0") $limit2="<b>".$this->lang->line("unlimited")."</b>";
													else $limit2=$limit;
													if($row["id"]!="1" && $limit!="0") $limit2="<b>".$limit2."/".$this->lang->line("month")."</b>";
													echo "<i class='fa fa-check'></i> ".$this->lang->line($row["module_name"]);
													if($row["id"]!="13" && $row["id"]!="14" && $row["id"]!="16") echo " : <b>". $limit2."</b>"."<br>";
													else echo "<br>";
												}
												?>
											</div>
											<div class="pricing-table-signup-<?php echo $classes[$i]; ?>">
												<p><center><a href="<?php echo site_url('home/sign_up'); ?>"><?php echo $this->lang->line("sign up"); ?></a></center></p>
											</div>
										</div>
									</div>

									<?php
									if($i%4==0) break;
								}?>
							</div>
						</div>

						<!-- section end -->

						<!-- footer start -->
						<!-- ================ -->
						<footer id="footer">

							<!-- .footer start -->
							<!-- ================ -->
							<div class="footer section">
								<div class="container">
									<h1 class="title text-center" id="contact"><?php echo $this->lang->line("contact");?></h1>
									<div class="space"></div>
									<div class="row">
										<div class="col-sm-6">
											<div class="footer-content">
												<p class="large"><?php echo $this->lang->line("feel free to contact us");?></p>
												<ul class="list-icons">
													<li><i class="fa fa-hand-o-right pr-10"></i> <?php echo $this->config->item("product_name"); ?></li>
													<li><i class="fa fa-building pr-10"></i> <?php echo $this->config->item("institute_address1"); ?></li>
													<li><i class="fa fa-map-marker pr-10"></i> <?php echo $this->config->item("institute_address2"); ?></li>
													<li><i class="fa fa-phone pr-10"></i> <?php echo $this->config->item("institute_mobile"); ?></li>
													<li><i class="fa fa-envelope-o pr-10"></i> <?php echo $this->config->item("institute_email"); ?></li>
												</ul>
											</div>
										</div>
										<div class="col-sm-6">
											<div>
												<?php 
												if($this->session->userdata('mail_sent') == 1) {
													echo "<div class='alert alert-success text-center'>".$this->lang->line("we have received your email. we will contact you through email as soon as possible")."</div>";
													$this->session->unset_userdata('mail_sent');
												}
												?>
											</div>

											<div class="footer-content">
												<form role="form" method="post" id="footer-form" action="<?php echo site_url("home/email_contact"); ?>">
													<div class="form-group has-feedback">
														<label class="sr-only" for="email"><?php echo $this->lang->line("email");?>* </label>
														<input type="email" class="form-control" required id="email" <?php echo set_value("email"); ?> placeholder="<?php echo $this->lang->line("email");?>" name="email">
														<i class="fa fa-envelope form-control-feedback"></i>
														<span class="red"><?php echo form_error("email") ?></span>
													</div>
													<div class="form-group has-feedback">
														<label class="sr-only" for="subject"><?php echo $this->lang->line("message subject");?>* </label>
														<input type="text" class="form-control" required id="subject" <?php echo set_value("subject"); ?> placeholder="<?php echo $this->lang->line("message subject");?>" name="subject">
														<i class="fa fa-tag form-control-feedback"></i>
														<span class="red"><?php echo form_error("subject") ?></span>
													</div>

													<div class="form-group has-feedback">
														<label class="sr-only" for="message"><?php echo $this->lang->line("message");?>* </label>
														<textarea class="form-control" rows="3" required id="message" <?php echo set_value("message"); ?> placeholder="<?php echo $this->lang->line("message");?>" name="message"></textarea>
														<i class="fa fa-pencil form-control-feedback"></i>
														<span class="red"><?php echo form_error("message") ?></span>
													</div>

													<div class="form-group has-feedback">
														<label  class="sr-only" for="captcha"><?php echo $this->lang->line("captcha");?> *  </label>
														<input type="number" class="form-control" step="1" required id="captcha" <?php echo set_value("captcha"); ?> placeholder="<?php echo $contact_num1. "+". $contact_num2." = ?"; ?>" name="captcha">
														<i class="fa fa-android form-control-feedback"></i>
														<span class="red">
															<?php 
															if(form_error('captcha')) 
																echo form_error('captcha'); 
															else  
															{ 
																echo $this->session->userdata("contact_captcha_error"); 
																$this->session->unset_userdata("contact_captcha_error"); 
															} 
															?>
														</span>
													</div>

													<input type="submit" value="<?php echo $this->lang->line("send email");?>" class="btn btn-primary">
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- .footer end -->

							<!-- .subfooter start -->
							<!-- ================ -->
							<div class="subfooter">
								<div class="container">
									<div class="row">
										<div class="col-md-12">
											<p class="text-center"><?php echo $this->config->item("product_short_name")." ".$this->config->item("product_version"); ?>  | Copyright &copy; <a target="_blank" href="<?php echo site_url(); ?>"><?php echo $this->config->item("institute_address1"); ?></a></p>
										</div>
									</div>
								</div>
							</div>
							<!-- .subfooter end -->

						</footer>
						<!-- footer end -->

<!-- JavaScript files placed at the end of the document so the pages load faster
	================================================== -->
	<!-- Jquery and Bootstap core js files -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/site/plugins/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/site/bootstrap/js/bootstrap.min.js"></script>

	<!-- Modernizr javascript -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/site/plugins/modernizr.js"></script>

	<!-- Isotope javascript -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/site/plugins/isotope/isotope.pkgd.min.js"></script>

	<!-- Backstretch javascript -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/site/plugins/jquery.backstretch.min.js"></script>

	<!-- Appear javascript -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/site/plugins/jquery.appear.js"></script>

	<!-- Initialization of Plugins -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/site/js/template.js"></script>

	<!-- Custom Scripts -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/site/js/custom.js"></script>

</body>
</html>

<style type="text/css" media="screen">
	.red{color:red;}
</style>

<script type="text/javascript">
	$(document).ready(function() {
		$("#language_change").change(function(){
			var language=$(this).val();
			$("#language_label").html("Loading Language...");
			$.ajax({
				url: '<?php echo site_url("home/language_changer");?>',
				type: 'POST',
				data: {language:language},
				success:function(response){
					$("#language_label").html("Language");
					location.reload(); 
				}
			})

		});
	});
</script>


<?php if($this->is_rtl){ ?>

	<script type="text/javascript">

		$('document').ready(function() {
			$('*').each(function() {  
				if(!isRTL($(this).text())){
					$(this).addClass('ltr');
				}
			});
		});


		function isInt(value) {

			var er = /^-?[0-9]+$/;

			return er.test(value);
		}


		function isRTL(str) {

			var isArabic = XRegExp('[\\p{Arabic}]');
			var partArabic = 0;
			var rtlIndex = 0;

			/**This for check if any of the text is numberic then don't make it RTL***/
			var is_int=0;

			var isRTL = false;

			for(i=0;i<str.length;i++){
				if(isArabic.test(str[i]))
					partArabic++;

				if(isInt(str[i])){
					is_int=1;
				}

			}

			/**if any character is arabic and also check if no integer there , then it is RTL**/
			if(partArabic > 0 && is_int==0) {
				isRTL = true;
			}
			return isRTL;
		}

	</script>

	<?php  } ?>




