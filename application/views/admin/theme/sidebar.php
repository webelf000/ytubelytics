<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->  

    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu">
      <li class="header"></li>     

      
      
      <?php if($this->session->userdata('user_type') == 'Member'): ?>
        <li > <a href="<?php echo site_url()."payment/usage_history"; ?>"> <i class="fa fa-list-ol"></i> <span><?php echo $this->lang->line("usage log"); ?></span></a></li> 
        <li > <a href="<?php echo site_url()."payment/member_payment_history"; ?>"> <i class="fa fa-history"></i> <span><?php echo $this->lang->line("paymant history"); ?></span></a></li> 
      <?php endif; ?>


     <?php if ($this->session->userdata('user_type') == 'Admin') : ?>

      <li class="treeview">
        <a href="#">
          <i class="fa fa-user-plus"></i> <span><?php echo $this->lang->line("Administration"); ?></span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>    
        <ul class="treeview-menu">
          <li class="treeview">
            <a href="#">
              <i class="fa fa-cogs"></i> <span><?php echo $this->lang->line("Settings"); ?></span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
        
            <ul class="treeview-menu">
              <li><a href="<?php echo site_url(); ?>admin_config/configuration"><i class="fa fa-cog"></i> <?php echo $this->lang->line("General Settings"); ?></a></li>  
              <li><a href="<?php echo site_url()."admin_config_email/index"; ?>"><i class="fa fa-envelope"></i> <?php echo $this->lang->line("Email Settings"); ?></a></li>    
              <li><a href="<?php echo site_url()."config/index"; ?>"><i class="fa fa-connectdevelop"></i> <?php echo $this->lang->line("connectivity settings"); ?></a></li> 
              <li><a href='<?php echo site_url()."admin_config_youtube/youtube_config"; ?>'><i class="fa fa-youtube"></i> <?php echo $this->lang->line("youtube settings"); ?></a></li>
            </ul>
          </li> <!-- end settings -->

          <?php 
           $license_type = $this->session->userdata('license_type');
              if($license_type == 'double')
                {  
          ?>
                <li><a href="<?php echo site_url()."admin/user_management"; ?>"><i class="fa fa-user"></i> <?php echo $this->lang->line("User Management"); ?></a></li>

                <li><a href="<?php echo site_url(); ?>admin/notify_members"><i class="fa fa-bell-o"></i> <?php echo $this->lang->line("Send Notification"); ?></a></li>

                <li class="treeview">
                  <a href="#">
                    <i class="fa fa-paypal"></i> <span><?php echo $this->lang->line("Payment"); ?></span>
                    <i class="fa fa-angle-left pull-right"></i>
                  </a>    
                  <ul class="treeview-menu">
                    <li> <a href="<?php echo site_url()."payment/payment_dashboard_admin"; ?>"> <i class="fa fa-dashboard"></i> <?php echo $this->lang->line("Dashboard"); ?></a></li>   
                    <li><a href="<?php echo site_url()."payment/payment_setting_admin"; ?>"><i class="fa fa-cog"></i> <?php echo $this->lang->line("Payment Settings"); ?></a></li>               
                    <li><a href="<?php echo site_url()."payment/package_settings"; ?>"><i class="fa fa-cube"></i> <?php echo $this->lang->line("Package Settings"); ?></a></li>      
                    <li><a href="<?php echo site_url()."payment/admin_payment_history"; ?>"><i class="fa fa-history"></i> <?php echo $this->lang->line("Payment History"); ?></a></li>     
                  </ul>
                </li> 
          <?php } ?>  

          


        </ul>
      </li>
      <?php endif; ?>


      <?php if ($this->session->userdata('user_type') == 'Member') : ?>
        <li><a href="<?php echo site_url()."config/index"; ?>"><i class="fa fa-connectdevelop"></i> <span><?php echo $this->lang->line("Connectivity Settings"); ?></span> </a></li>        
     <?php endif; ?>
     
 
     <?php if($this->session->userdata('user_type') == 'Admin' || in_array(33,$this->module_access)): ?>
          <li> <a href="<?php echo site_url()."youtube_analytics/index"; ?>"> <i class="fa fa-download"></i> <span><?php echo $this->lang->line("Import Channel & Video"); ?></span></a></li>  
          <li> <a href="<?php echo site_url()."youtube_analytics/get_channel_list"; ?>"> <i class="fa fa-bar-chart"></i> <span><?php echo $this->lang->line("Channel Analytics"); ?></span></a></li>
          <li> <a href="<?php echo site_url()."youtube_analytics/get_all_video_list"; ?>"> <i class="fa fa-pie-chart"></i> <span><?php echo $this->lang->line("Video Analytics"); ?></span></a></li>
      <?php endif; ?>
      <?php if($this->session->userdata('user_type') == 'Admin' || in_array(39,$this->module_access)): ?>
          <li> <a href="<?php echo site_url()."youtube_live_event/uploaded_video_list"; ?>"> <i class="fa fa-clock-o"></i> <span><?php echo $this->lang->line("Scheduled Video Uploader"); ?></span></a></li>
          <li> <a href="<?php echo site_url()."youtube_live_event/index"; ?>"> <i class="fa fa-tv"></i> <span><?php echo $this->lang->line("Live Event"); ?></span></a></li>                
      <?php endif; ?>
      <?php if($this->session->userdata('user_type') == 'Admin' || in_array(34,$this->module_access)): ?>
          <li> <a href="<?php echo site_url()."youtube_marketer/tag_scraper"; ?>"> <i class="fa fa-tags"></i> <span><?php echo $this->lang->line("Keyword Scraper"); ?></span></a></li>                
      <?php endif; ?>
      <?php if($this->session->userdata('user_type') == 'Admin' || in_array(35,$this->module_access)): ?>
          <li> <a href="<?php echo site_url()."youtube_marketer/auto_suggestion"; ?>"> <i class="fa fa-thumbs-up"></i> <span><?php echo $this->lang->line("Auto Keyword Suggestion"); ?></span></a></li>
      <?php endif; ?>
      <?php if($this->session->userdata('user_type') == 'Admin' || in_array(36,$this->module_access)): ?>
          <li> <a href="<?php echo site_url()."youtube_marketer/subscribe_plugin"; ?>"> <i class="fa fa-rss"></i> <span><?php echo $this->lang->line("Subscription Button Plugin"); ?></span></a></li>
      <?php endif; ?>
      <?php if($this->session->userdata('user_type') == 'Admin' || in_array(37,$this->module_access)): ?>
          <li> <a href="<?php echo site_url()."youtube_marketer/custom_downloader"; ?>"> <i class="fa fa-download"></i> <span><?php echo $this->lang->line("Custom Video Downloader"); ?></span></a></li>
      <?php endif; ?>

      <?php if($this->session->userdata("user_type")=="Admin" || in_array(26,$this->module_access)) : ?>
        <li> <a href="<?php echo site_url()."video_search_engine/youtube"; ?>"> <i class="fa fa-search"></i> <span><?php echo $this->lang->line("Video Search Engine"); ?></span></a></li>
      <?php endif; ?>

      <?php if($this->session->userdata("user_type")=="Admin" || in_array(62,$this->module_access)) : ?>
        <li> <a href="<?php echo site_url()."channel_search_engine/youtube"; ?>"> <i class="fa fa-desktop"></i> <span><?php echo $this->lang->line("Channel Search Engine"); ?></span></a></li>
      <?php endif; ?>

       <?php if($this->session->userdata("user_type")=="Admin" || in_array(63,$this->module_access)) : ?>
         <li> <a href="<?php echo site_url()."playlist_search_engine/youtube"; ?>"> <i class="fa fa-th"></i> <span><?php echo $this->lang->line("Playlist Search Engine"); ?></span></a></li>
       <?php endif; ?>



      <?php if($this->session->userdata('user_type') == 'Admin' || in_array(27,$this->module_access)): ?>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-trophy"></i> <span><?php echo $this->lang->line("Video Rank Tracking"); ?></span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">        
                <li> <a href="<?php echo site_url()."video_position_tracking/index"; ?>"> <i class="fa fa-cog"></i> <?php echo $this->lang->line("Settings"); ?></a></li>
                <li> <a href="<?php echo site_url()."video_position_tracking/keyword_position_report"; ?>"> <i class="fa fa-file-movie-o"></i> <?php echo $this->lang->line("Report"); ?></a></li>
             
          </ul>
        </li> 
      <?php endif; ?>
      

               
      <?php
        $user_payment_status = 1;
        if($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Admin') 
        {
            $where['where'] = array('id'=>$this->session->userdata("user_id"));
            $user_expire_date = $this->basic->get_data('users',$where,$select=array('expired_date'));
            $expire_date = strtotime($user_expire_date[0]['expired_date']);
            $current_date = strtotime(date("Y-m-d"));
            $package_data=$this->basic->get_data("users",$where=array("where"=>array("users.id"=>$this->session->userdata("user_id"))),$select="package.price as price",$join=array('package'=>"users.package_id=package.id,left"));
            if(is_array($package_data) && array_key_exists(0, $package_data))
            $price=$package_data[0]["price"];
            if($price=="Trial") $price=1;
            if ($expire_date < $current_date && ($price>0 && $price==""))
            $user_payment_status = 0;
        }
      ?>
      


	      <?php if( $this->session->userdata('user_type') == 'Admin'): ?>
	      <li class="treeview">
	        <a href="#">
	          <i class="fa fa-plug"></i> <span><?php echo $this->lang->line("Cron Job Settings"); ?></span>
	          <i class="fa fa-angle-left pull-right"></i>
	        </a>
	        <ul class="treeview-menu">       

	          <li > <a href="<?php echo site_url()."native_api/index"; ?>"> <i class="fa fa-circle-o"></i> <?php echo $this->lang->line("Generate API Key"); ?></a></li> 
	          <li><a href="<?php echo site_url('cron_job/index'); ?>"><i class="fa fa-circle-o"></i> <?php echo $this->lang->line("cron job commands"); ?></a></li>
	       
	   
	        </ul>
	      </li> 
      <li><a target="_BLANK"  href="<?php echo site_url('documentation'); ?>"><i class="fa fa-book"></i> <span><?php echo $this->lang->line("documentation"); ?></span></a></li> 
      <?php endif; ?>

 

    <li style="margin-bottom:200px">&nbsp;</li>
       
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>