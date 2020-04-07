<?php $this->load->view('admin/theme/message'); ?>
<?php 
$name= isset($profile_info[0]["name"]) ? $profile_info[0]["name"] : ""; 
$mobile= isset($profile_info[0]["mobile"]) ? $profile_info[0]["mobile"] : ""; 
$address= isset($profile_info[0]["address"]) ? $profile_info[0]["address"] : ""; 
$logo= isset($profile_info[0]["brand_logo"]) ? $profile_info[0]["brand_logo"] : ""; 
$website= isset($profile_info[0]["brand_url"]) ? $profile_info[0]["brand_url"] : ""; 
$vat_no= isset($profile_info[0]["vat_no"]) ? $profile_info[0]["vat_no"] : ""; 
$paypal_email= isset($profile_info[0]["paypal_email"]) ? $profile_info[0]["paypal_email"] : ""; 
?>
<section class="content-header">
   <section class="content">
     	<div class="box box-info custom_box">
		    	<div class="box-header">
		         <h3 class="box-title"><i class="fa fa-pencil"></i> <?php echo $this->lang->line("edit profile");?></h3>
		        </div><!-- /.box-header -->
		       		<!-- form start -->
		    <form class="form-horizontal" enctype="multipart/form-data" action="<?php echo site_url().'member/edit_profile_action';?>" method="POST">
		        <div class="box-body">
		           	<div class="form-group">
		              	<label class="col-sm-3 control-label" for=""><?php echo $this->lang->line("company name");?> *
		              	</label>
		                	<div class="col-sm-9 col-md-6 col-lg-6">
		               			<input name="name" value="<?php echo $name;?>"  class="form-control" type="text">		               
		             			<span class="red"><?php echo form_error('name'); ?></span>
		             		</div>
		            </div>
		           <div class="form-group">
		             	<label class="col-sm-3 control-label" for=""><?php echo $this->lang->line("company phone/ mobile");?> *
		             	</label>
	             		<div class="col-sm-9 col-md-6 col-lg-6">
	               			<input name="mobile" value="<?php echo $mobile;?>"  class="form-control" type="text">		          
	             			<span class="red"><?php echo form_error('mobile'); ?></span>
	             		</div>
		           </div> 
		          

		        
		            <div class="form-group">
		             	<label class="col-sm-3 control-label" for=""><?php echo $this->lang->line("company address");?> *
		             	</label>
	             		<div class="col-sm-9 col-md-6 col-lg-6">
	               			<textarea name="address" class="form-control"><?php echo $address;?></textarea>	          
	             			<span class="red"><?php echo form_error('address'); ?></span>
	             		</div>
		           </div> 


		            <div class="form-group" style="display:none">
		             	<label class="col-sm-3 control-label" for=""><?php echo $this->lang->line("VAT No");?> 
		             	</label>
	             		<div class="col-sm-9 col-md-6 col-lg-6">
	               			<input name="vat_no" value="<?php echo $vat_no;?>"  class="form-control" type="text">		          
	             			<span class="red"><?php echo form_error('vat_no'); ?></span>
	             		</div>
		           </div>


		           <div class="form-group" style="display:none">
		             	<label class="col-sm-3 control-label" for=""><?php echo $this->lang->line("brand url");?> *
		             	</label>
	             		<div class="col-sm-9 col-md-6 col-lg-6">
	               			<input name="website" value="<?php echo $website;?>"  class="form-control" type="text">		          
	             			<span class="red"><?php echo form_error('website'); ?></span>
	             		</div>
		           </div>  
		          

		           <div class="form-group" style="display:none">
		             	<label class="col-sm-3 control-label" for=""><?php echo $this->lang->line("brand logo");?>
		             	</label>
	             		<div class="col-sm-9 col-md-6 col-lg-6" >
		           			<div class='text-center'><img class="img-responsive" src="<?php echo base_url().'member/'.$logo;?>" alt="<?php echo $this->lang->line("brand logo");?>"/></div>
	               			<?php echo $this->lang->line("Max Dimension");?> : 600 x 300, <?php echo $this->lang->line("Max Size");?> : 200KB,  <?php echo $this->lang->line("Allowed Format");?> : png
	               			<input name="logo" class="form-control" type="file">		          
	             			<span class="red"> <?php echo $this->session->userdata('logo_error'); $this->session->unset_userdata('logo_error'); ?></span>
	               			<!-- <br><big><i><?php echo $this->lang->line("brand logo and url will be used in health check pdf report");?></i></big> -->
	             		</div>
		           </div> 

		           <div class="form-group" style="display:none">
		             	<label class="col-sm-3 control-label" for=""><?php echo $this->lang->line("Currency");?> *
		             	</label>
	             		<div class="col-sm-9 col-md-6 col-lg-6">
	               			<?php echo form_dropdown("currency",$currency,"USD"," class='form-control'");?>          
	             			<span class="red"><?php echo form_error('currency'); ?></span>
	             		</div>
		           </div>  

		           <div class="form-group" style="display:none">
		             	<label class="col-sm-3 control-label" for=""><?php echo $this->lang->line("PayPal Email");?>
		             	</label>
	             		<div class="col-sm-9 col-md-6 col-lg-6">
	               			<input name="paypal_email" value="<?php echo $paypal_email;?>"  class="form-control" type="email">		          
	             			<span class="red"><?php echo form_error('paypal_email'); ?></span>
	             		</div>
		           </div> 
		  		           
		         		               
		           </div> <!-- /.box-body --> 

		           	<div class="box-footer">
		            	<div class="form-group">
		             		<div class="col-sm-12 text-center">
		               			<input name="submit" type="submit" class="btn btn-warning btn-lg" value="<?php echo $this->lang->line("Save");?>"/>  
		              			<input type="button" class="btn btn-default btn-lg" value="<?php echo $this->lang->line("Cancel");?>" onclick='goBack("admin_config",1)'/>  
		             		</div>
		           		</div>
		         	</div><!-- /.box-footer -->         
		        </div><!-- /.box-info -->       
		    </form>     
     	</div>
   </section>
</section>



