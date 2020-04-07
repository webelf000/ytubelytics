<div class="section-1">



	<div class="wrap">



		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">



			<div class="section-title"><span>The ultimate software to help your marketing and video marketing effectiveness on the internet today</span></div>



			<div class="section-desc">

			  <p><span>

			    

			    Video Marketers RX is the professionals choice for managing their clients websites, gaining new clients and offering services never found in one complete software platform before.</span></p>

			  <p><span> Prospecting, Follow-up, Invoicing, Timers, Complete Analytics, Video Players, Video Posting, S.E.O Features, Website Health Reports, Email, SMS and much, much more.

			    </span></p>

			  <p><span>We're sure that you will love our software and remain a happy member of our great family for a very long time! </span></p>

			</div>



		</div>



		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">



			<div class="login-form">



                <h3 style="color:#fff;text-align:center;">Login</h3>



                <?php 

                  if($this->session->flashdata('login_msg')!='') 

                  {

                      echo "<div class='alert alert-danger text-center'>"; 

                          echo $this->session->flashdata('login_msg');

                      echo "</div>"; 

                  }   

                  if($this->session->flashdata('reset_success')!='') 

                  {

                      echo "<div class='alert alert-success text-center'>"; 

                          echo $this->session->flashdata('reset_success');

                      echo "</div>"; 

                  } 

                  if($this->session->userdata('reg_success') != ''){

                    echo '<div class="alert alert-success text-center">'.$this->session->userdata("reg_success").'</div>';

                    $this->session->unset_userdata('reg_success');

                  }  



                  if($this->session->userdata('jzvoo_success') != ''){

                    echo '<div class="alert alert-success text-center">'.$this->lang->line("your account has been created successfully and pending for approval.").'</div>';

                    $this->session->unset_userdata('jzvoo_success');

                  }    

                ?>







                <div class="tab-content">



                    <div id="loginFrom" class="tab-pane fade in active">



                        <div class="col-md-12">



                            <form class="form-horizontal formLogin" role="form" action="<?php echo site_url('home/login');?>" method="post">



                                <div class="form-group">



                                    <div class="input-group">



                                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>



                                        <input type="text" class="form-control" name="username" placeholder="Email">



                                    </div>

                                    <span style="color:#fff"><?php echo form_error('username'); ?></span>



                                </div>



                                <div class="form-group">



                                    <div class="input-group">



                                        <span class="input-group-addon"><i class="fa fa-unlock"></i></span>



                                        <input type="password" class="form-control" name="password" placeholder="Password">



                                    </div>

                                    <span style="color:#fff"><?php echo form_error('password'); ?></span>



                                </div>



                                <div class="row">



                                    <div class="col-md-8 pl0"><div class="msg error"></div></div>

                                    <div class="col-md-4">



                                        <div class="form-group pull-right">                



                                            <button type="submit" class="btn btn-login btn-lg"><i class="fa fa-sign-in"></i> login</button>



                                        </div>

                                      



                                    </div>



                                </div>



                                <div class="row">                                    

                                    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 text-left"><a style="color:#fff;font-size:14px;font-family:arial;" href="<?php echo site_url('home/forgot_password');?>">Forgot password?</a></div>

                                    <div class="col-md-12  col-sm-12 col-md-5 col-lg-5 text-right"><a style="color:#fff;font-size:14px;font-family:arial;" href="<?php echo site_url('home/sign_up');?>">New user?</a></div>

                                </div>



                            </form>



                        </div>



                    </div>



                    <div class="clearfix"></div>





                </div>



            </div>



		</div>



	</div>



	<div class="clearfix"></div>



</div>