<?php 
require_once("home.php"); // loading home controller

class Youtube_marketer extends Home
{

    public $user_id;    
    public $download_id; 
    
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login_page', 'location');
 
        $this->user_id=$this->session->userdata('user_id');
        $this->download_id=$this->session->userdata('download_id');
        set_time_limit(0);

        $this->important_feature();
        $this->member_validity();
       

        $this->upload_path = realpath( APPPATH . '../upload');
    }


    public function index()
    {
        $this->tag_scraper();   
    }

    public function tag_scraper($id="")
    {
        if($this->session->userdata('user_type') != 'Admin' && !in_array(34,$this->module_access))
        redirect('home/login_page', 'location'); 

        $data['body'] = 'youtube_analytics/tag_scraper';
        $data['page_title'] = $this->lang->line("Youtube Keyword & Backlink Scraper");
        $data['called_video_id'] = $id;
        $this->_viewcontroller($data);
    }


    public function tag_scraper_action()
    {
        if($this->session->userdata('user_type') != 'Admin' && !in_array(34,$this->module_access))
        exit();

        //************************************************//
        $status=$this->_check_usage($module_id=34,$request=1);
        if($status=="2") 
        {
            $error_msg = $this->lang->line("sorry, your bulk limit is exceeded for this module.")."<a href='".site_url('payment/usage_history')."'>".$this->lang->line("click here to see usage log")."</a>";
            $output = 'limit_cross';
            echo $output;
            exit();
        }
        else if($status=="3") 
        {
            $error_msg = $this->lang->line("sorry, your monthly limit is exceeded for this module.")."<a href='".site_url('payment/usage_history')."'>".$this->lang->line("click here to see usage log")."</a>";
            $output = 'limit_cross';
            echo $output;
            exit();
        }
        //************************************************//

        $video_id=$this->input->post("video_id");    
        $this->load->library("google");
        $backlink_details="";
  
        $final_data=$this->google->get_video_by_id($video_id);
        $output="";

        $output='
        
            <script>
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
            
        </script>

        <div class="space"></div><div class="row">';


        $tags=array();
        $title="";

        foreach ($final_data as $value) 
        {
           if(isset($value["items"]))
           {
             foreach ($value["items"] as $row) 
             {
                 $title=isset($row["snippet"]["title"]) ? $row["snippet"]["title"] : "";
                 $description=isset($row["snippet"]["description"]) ? $row["snippet"]["description"] : "";
                 $tags=isset($row["snippet"]["tags"]) ? $row["snippet"]["tags"] : array();
                 $duration=isset($row["contentDetails"]["duration"]) ? $row["contentDetails"]["duration"] : "";
                 $duration=str_replace(array("PT","H","M","S"),array("","h ","m ","s "), $duration);
                 $views=isset($row["statistics"]["viewCount"]) ?  $row["statistics"]["viewCount"] : 0;
                 $likes=isset($row["statistics"]["likeCount"]) ?  $row["statistics"]["likeCount"] : 0;
                 $dislikes=isset($row["statistics"]["dislikeCount"]) ?  $row["statistics"]["dislikeCount"] : 0;
                 $favourite=isset($row["statistics"]["favoriteCount"]) ?  $row["statistics"]["favoriteCount"] : 0;
                 $comments=isset($row["statistics"]["commentCount"]) ?  $row["statistics"]["commentCount"] : 0;
                 // $embed=isset($row["player"]["embedHtml"]) ?  $row["player"]["embedHtml"] : "";
                 $thumb=isset($row["snippet"]["thumbnails"]["medium"]["url"]) ?  $row["snippet"]["thumbnails"]["medium"]["url"] : "";
                 $id=isset($row["id"]) ?  $row["id"] : "";
                 $real_url="https://www.youtube.com/watch?v={$id}";
                 $url="https://www.youtube.com/embed/{$id}?rel=0&wmode=transparent&autoplay=1";

                 $tag_details="";
                 for($i=0;$i<count($tags);$i++) 
                 {
                     $tag_details.="<li>".$tags[$i]."</li>";
                 }
                 if($tag_details=="") $tag_details="<li class='text-center'><b>No tag/keyword found.</b></li>";

                 $output.='
                  <div class="col-xs-12 well" style="margin-bottom:10px;">
                  <h4 class="text-center">Title : '.$title.'</h4><br/>
                  <hr/>'.nl2br($description).'
                  </div>
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 well" style="margin-bottom:10px;">
                  <h3 class="text-center">Tags/Keywords (Total : '.count($tags).') </h3><hr>
                  <ul style="height:225px;overflow:auto">'.$tag_details.'</ul>
                  </div> 
                  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="margin-bottom:10px;">
                      <div class="col-xs-12" style="min-height:365px; border:1px solid #ccc; padding:10px;background:#fcfcfc;">';
                          $output.="<h5 class='text-center' style='cursor:pointer;' title='".$title."'><b>".substr($title, 0, 70)."...</b></h5>";
                          $output.= "<div class='text-center'><i title='Likes' style='color:#8ABA45' class='fa  fa-thumbs-up'></i> ".$likes;
                          $output.= "&nbsp;&nbsp;&nbsp;<i title='Dislikes' style='color:#DD4B39' class='fa  fa-thumbs-down'></i> ".$dislikes;
                          $output.= "&nbsp;&nbsp;&nbsp;<i title='Commnets' style='color:#3C8DBC' class='fa  fa-comment'></i>".$comments;
                          $output.= "&nbsp;&nbsp;&nbsp;<i title='Views' style='color:#F39C12' class='fa  fa-play-circle'></i>".$views;
                          $output.= "</div><center><a class='youtube' title='{$description}' href='{$url}'><img class='img-thumbnail' style='width:100% !important' src='".$thumb."'></a></center><br/>";
                          $output.= "<center><input type='text' value='{$real_url}'/>&nbsp;&nbsp;&nbsp;<i title='Duration' style='color:#DD4B39' class='fa fa-clock-o'></i> ".$duration."<center>";
                      $output.= 
                      '</div>
                  </div> '; 
             }
             
           }

           if($title=="") $output="0";

           else
           {
                // insert into usage_log table
                $this->_insert_usage_log($module_id=34,$request=1);
           }

           echo $output;
        }

        $tag_scraper_writer=fopen("download/youtube/tag_scraper_{$this->user_id}_{$this->download_id}.csv", "w");
        fprintf($tag_scraper_writer, chr(0xEF).chr(0xBB).chr(0xBF));           
        $write_validation[]="Tags/Keyword";
        fputcsv($tag_scraper_writer, $write_validation);
        for($i=0;$i<count($tags);$i++) 
        {
            $write_validation=array();
            $write_validation[]=$tags[$i];
            fputcsv($tag_scraper_writer, $write_validation);
        }              
        fclose($tag_scraper_writer);   

          
    }

    public function auto_suggestion()
    {
        if($this->session->userdata('user_type') != 'Admin' && !in_array(35,$this->module_access))
        redirect('home/login_page', 'location'); 

        $data['body'] = 'youtube_analytics/auto_suggestion';
        $data['page_title'] = $this->lang->line("Youtube Auto Keyword Suggestion");
        $this->_viewcontroller($data);
    }


    public function auto_suggestion_action()
    {
        if($this->session->userdata('user_type') != 'Admin' && !in_array(35,$this->module_access))
        exit();

        //************************************************//
        $status=$this->_check_usage($module_id=35,$request=1);
        if($status=="2") 
        {
            $error_msg = $this->lang->line("sorry, your bulk limit is exceeded for this module.")."<a href='".site_url('payment/usage_history')."'>".$this->lang->line("click here to see usage log")."</a>";
            $output = 'limit_cross';
            echo $output;
            exit();
        }
        else if($status=="3") 
        {
            $error_msg = $this->lang->line("sorry, your monthly limit is exceeded for this module.")."<a href='".site_url('payment/usage_history')."'>".$this->lang->line("click here to see usage log")."</a>";
            $output = 'limit_cross';
            echo $output;
            exit();
        }
        //************************************************//

        $keyword=$this->input->post("keyword"); 
        $this->load->library("web_common_report");

        $result=$this->web_common_report->youtube_auto_keyword_suggestion($keyword);
        $final_data=isset($result[1]) ? $result[1] : array();
        $suggestion_details="";
        $count_sug=count($final_data);
        foreach ($final_data as $key => $value) 
        {
            $suggestion_details.="<li>{$value}</li>";
        }
        if($suggestion_details=="") $suggestion_details="<li class='text-center'><b>No suggestion found.</b></li>";
        
        $output="";
      
         $output.='
          <div class="col-xs-12 well" style="margin-bottom:10px;"> 
            <h3 class="text-center">Keyword Suggestion (Total : '.$count_sug.')</h3><hr>
            <ul style="height:300px;overflow:auto">'.$suggestion_details.'</ul>
          </div>';             

        if($count_sug==0) $output="0";
        else
        {
             // insert into usage_log table
             $this->_insert_usage_log($module_id=35,$request=1);
        }
        echo $output;
       
        $writer=fopen("download/youtube/suggestion_{$this->user_id}_{$this->download_id}.csv", "w");
        fprintf($writer, chr(0xEF).chr(0xBB).chr(0xBF));      
        $write_validation[]="Keyword Suggestion";
        fputcsv($writer, $write_validation);
        for($i=0;$i<count($final_data);$i++) 
        {
            $write_validation=array();
            $write_validation[]=$final_data[$i];
            fputcsv($writer, $write_validation);
        }              
        fclose($writer);   
           
    }


    public function subscribe_plugin()
    {
        if($this->session->userdata('user_type') != 'Admin' && !in_array(36,$this->module_access))
        redirect('home/login_page', 'location'); 

        $data['body'] = 'youtube_analytics/subscribe';
        $data['page_title'] = $this->lang->line("Youtube Channel Subscription Plugin");
        $this->_viewcontroller($data);
    }


    public function subscribe_plugin_action()
    {
        if($this->session->userdata('user_type') != 'Admin' && !in_array(36,$this->module_access))
        exit(); 

        $channel_id=$this->input->post("channel_id"); 
        $layout=$this->input->post("layout"); 
        $theme=$this->input->post("theme"); 
        $count=$this->input->post("count"); 

                
        $output="";
        $data_theme='';       
        $value_raw='<script src="https://apis.google.com/js/platform.js"></script>
        <div class="g-ytsubscribe" data-channelid="'.$channel_id.'" data-layout="'.$layout.'" data-count="'.$count.'"></div>';
        $value=htmlspecialchars($value_raw);
        $output.='
          <div class="col-xs-12 well" style="margin-bottom:10px;"> 
          <h3 class="text-center">Subscription Button Embed Code</h3><br/><center><b>Copy the embed code and paste in your web page</b></center><hr>';
        $output.="<input style='width:100%;height:30px' type='text' value='{$value}'>";
        $output.='</div>';             

        $response=array("code"=>$value_raw,"printdata"=>$output);
        echo json_encode($response); 
           
    }


    public function custom_downloader($id="")
    {
        if($this->session->userdata('user_type') != 'Admin' && !in_array(37,$this->module_access))
        redirect('home/login_page', 'location'); 

        $data['body'] = 'youtube_analytics/custom_downloader';
        $data['page_title'] = $this->lang->line("Custom Video Downloader");
        $data["video_id"]=$id;
        $this->_viewcontroller($data);
    }
  

  


}