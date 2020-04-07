<?php

require_once("home.php"); // loading home controller

class video_search_engine extends Home
{

    public $user_id;    

    /**
    * load constructor
    * @access public
    * @return void
    */
    
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login_page', 'location');   
        
        $this->load->helper('form');
        $this->load->library('upload');
        $this->load->library('google');
        $this->load->library('Web_common_report');
        $this->upload_path = realpath(APPPATH . '../upload');
        $this->user_id=$this->session->userdata('user_id');
        set_time_limit(0);

        $this->important_feature();
        $this->member_validity();

        if($this->session->userdata('user_type') != 'Admin' && !in_array(26,$this->module_access))
        redirect('home/login_page', 'location'); 
    }


    public function index(){
        $this->youtube();      
    }
    

  
    public function youtube($channel_id="",$monetized="")
    {
        $data['body'] = 'video_search_engine/youtube';
        $data['page_title'] = $this->lang->line('youtube video');
        $data["google_api"]=$this->basic->get_data("config",array("where"=>array("user_id"=>$this->session->userdata("user_id"))));
        $data["channel_id"]=$channel_id;
        $data["monetized"]=$monetized;
        $this->_viewcontroller($data);
    }

    public function youtube_action()
    {
        $status=$this->_check_usage($module_id=26,$request=1);
        if($status=="2") 
        {
            echo "<div class='alert alert-danger text-center'>".$this->lang->line("sorry, your bulk limit is exceeded for this module.")."<a href='".site_url('payment/usage_history')."'>".$this->lang->line("click here to see usage log")."</a></div>";
            exit();
        }
        else if($status=="3") 
        {            
            echo "<div class='alert alert-danger text-center'>".$this->lang->line("sorry, your monthly limit is exceeded for this module.")."<a href='".site_url('payment/usage_history')."'>".$this->lang->line("click here to see usage log")."</a></div>";            
            exit();
        }

        $keyword=$this->input->post("keyword",true);
        $limit=$this->input->post("limit",true);
        $event_type=$this->input->post("event_type",true);
        $radius=$this->input->post("radius",true);
        $latitude=$this->input->post("latitude",true);
        $longitude=$this->input->post("longitude",true);
        $channel_id=$this->input->post("channel_id",true);
        $publish_after=$this->input->post("publish_after",true);
        $publish_before=$this->input->post("publish_before",true);
        $order=$this->input->post("order",true);
        $duration=$this->input->post("duration",true);
        $video_type=$this->input->post("video_type",true);

        $dimension=$this->input->post("dimension",true);
        $defination=$this->input->post("defination",true);
        $license=$this->input->post("license",true);

        if($keyword=="" && $channel_id=="")
        {
            echo $this->lang->line("please enter keyword");
            exit();
        }

        $location="";
        if($latitude!="" && $longitude!="") 
        {          
          $location=$latitude.",".$longitude;
          if($radius=="") $radius="1000km";
        }
		

        $youtube_data=$this->google->get_youtube_video($keyword,$limit,$channel_id,$location,$radius,$order,$publish_after,$publish_before,$duration,$video_type,$event_type,$dimension,$defination,$license);
        $video_ids=array_column($youtube_data, 'video_id');
	
		
        $total_video=count($video_ids);
        $video_ids=implode(",",$video_ids);

        $final_data=$this->google->get_video_by_id($video_ids);
      
        

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
    
    
    
        $output.= "<div class='well text-center'><h2><i class='fa fa-camera'></i> ".$this->lang->line("videos")." (".$total_video.")</h2></div>";
        foreach ($final_data as $value) 
        {
           if(isset($value["items"]))
           {
             foreach ($value["items"] as $row) 
             {
                 $title=isset($row["snippet"]["title"]) ? $row["snippet"]["title"] : "";
                 $description=isset($row["snippet"]["description"]) ? $row["snippet"]["description"] : "";
                 $published_at=isset($row["snippet"]["publishedAt"]) ? $row["snippet"]["publishedAt"] : "";
                 $published_at=date("Y-m-d",strtotime($published_at));
                 $tags=isset($row["snippet"]["tags"]) ? implode(',', $row["snippet"]["tags"]) : "";
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
                $download_video=base_url("youtube_marketer/custom_downloader/{$id}");
                $tag_blink=base_url("youtube_marketer/tag_scraper/{$id}");
                $download_sub_link=base_url("youtube_marketer/subtitle_downloader/{$id}");
                $download_video="<a style='margin-top:7px;padding:3px;font-size:12px' class='btn btn-warning' href='{$download_video}' target='_BLANK'><i class='fa fa-download'></i> Download</a>";
                $download_tag_blink="<a style='margin-top:7px;padding:3px;font-size:12px' class='btn btn-primary' href='{$tag_blink}' target='_BLANK'><i class='fa fa-tags'></i>Tags/Keywords</a>";
                 $download_sub="<a style='margin-top:7px;padding:3px;font-size:11px' class='btn btn-info' href='{$download_sub_link}' target='_BLANK'>Subtitle</a>";
                 $output.=' 
                  <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" style="margin-bottom:10px;">
                      <div class="col-xs-12" style="min-height:415px; border:1px solid #ccc; padding:10px;background:#fcfcfc;">';
                          if(strlen($title)>70) $dot='...'; else $dot="";
                          $output.="<h5 class='text-center' style='cursor:pointer;' title='".$title."'><b>".substr($title, 0, 70).$dot."</b></h5>";
                          $output.= "<div class='text-center'><i title='Likes' style='color:#8ABA45' class='fa  fa-thumbs-up'></i> ".$likes;
                          $output.= "&nbsp;&nbsp;&nbsp;<i title='Dislikes' style='color:#DD4B39' class='fa  fa-thumbs-down'></i> ".$dislikes;
                          $output.= "&nbsp;&nbsp;&nbsp;<i title='Commnets' style='color:#3C8DBC' class='fa  fa-comment'></i>".$comments;
                          $output.= "&nbsp;&nbsp;&nbsp;<i title='Views' style='color:#F39C12' class='fa  fa-play-circle'></i>".$views;
                          $output.= "</div><center><a class='youtube' title='{$description}' href='{$url}'><img class='img-thumbnail' style='width:100% !important' src='".$thumb."'></a></center><br/>";
                          $output.= "<center><input type='text' value='{$real_url}'/>&nbsp;&nbsp;&nbsp;<i title='Duration' style='color:#DD4B39' class='fa fa-clock-o'></i> ".$duration."</center><center>";
                          // if($this->session->userdata('user_type') == 'Admin' || in_array(45,$this->module_access))
                          // $output.="{$download_sub}";
                          if($this->session->userdata('user_type') == 'Admin' || in_array(37,$this->module_access))
                          $output.="&nbsp;{$download_video}"; 
                          if($this->session->userdata('user_type') == 'Admin' || in_array(34,$this->module_access))
                          $output.="&nbsp;{$download_tag_blink}";  
                          $output.= "<br/><span style='font-size:11px;'>published: {$published_at}</span>";                    
                      $output.= 
                      '</center></div>
                  </div>';  
             }
             if($total_video==0) $output.= "<div class='alert alert-warning text-center'>".$this->lang->line("no data found")."</div>";
             else
             {
                // insert into usage_log table
                $this->_insert_usage_log($module_id=26,$request=1);
             }
           }
        }
        $output.="</div>";


        $page_encoding =  mb_detect_encoding($output);
        if(isset($page_encoding)){
          $output = @iconv( $page_encoding, "utf-8//IGNORE", $output );
        } 
        echo $output;

    }




    public function youtube_playlist_video($playlist_id="",$monetized="")
    {
     
      $final_data=$this->google->playlist_item($playlist_id);
      $total_video=isset($final_data["items"]) ? count($final_data["items"]) : 0;

      $output='<div class="space"></div><div class="row">';        
        
        $playlist_url=$url="http://www.youtube.com/embed/videoseries?list={$playlist_id}&amp;hl=en_US&showinfo=1";    
        $playlist_link="<a class='youtube' href='".$playlist_url."'>Playlist Videos</a>";
        $playlist_count= isset($final_data["pageInfo"]["totalResults"]) ? $final_data["pageInfo"]["totalResults"] : 0 ;
        $output.= "<div class='well text-center'><h3><i class='fa fa-camera'></i> ".$playlist_link." (showing ".$total_video." / ".$playlist_count.")</h3></div>";

         if(isset($final_data["items"]))
         {
           foreach ($final_data["items"] as $row) 
           {
               $title=isset($row["snippet"]["title"]) ? $row["snippet"]["title"] : "";
               $description=isset($row["snippet"]["description"]) ? $row["snippet"]["description"] : "";
               $published_at=isset($row["snippet"]["publishedAt"]) ? $row["snippet"]["publishedAt"] : "";
               $published_at=date("Y-m-d",strtotime($published_at));
               $thumb=isset($row["snippet"]["thumbnails"]["medium"]["url"]) ?  $row["snippet"]["thumbnails"]["medium"]["url"] : "";
               $id=isset($row["snippet"]["resourceId"]["videoId"]) ?  $row["snippet"]["resourceId"]["videoId"] : "";

               if($monetized=="only_monetized")
               {
                  $monetize_check = $this->web_common_report->youtube_video_monetize_check($id);
                  if($monetize_check=="0") continue;
               }

               $real_url="https://www.youtube.com/watch?v={$id}";         
               $url="https://www.youtube.com/embed/{$id}?rel=0&wmode=transparent&autoplay=1";
               $output.=' 
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" style="margin-bottom:10px;">
                    <div class="col-xs-12" style="min-height:370px; border:1px solid #ccc; padding:10px;background:#fcfcfc;">';
                        if(strlen($title)>70) $dot='...'; else $dot="";
                        $output.="<h5 class='text-center' style='cursor:pointer;' title='".$title."'><b>".substr($title, 0, 70).$dot."</b></h5>";
                        $output.= "<center><a class='youtube' title='{$description}' href='{$url}'><img class='img-thumbnail' style='width:100% !important' src='".$thumb."'></a></center><br/>";
                        $output.= "<center><input type='text' value='{$real_url}'/><br/>";
                        $output.= "<span style='font-size:11px;'>published: {$published_at}</span></center>";                    
                    $output.= 
                    '</center></div>
                </div>';  
           }
           if($total_video==0) $output.= "<div class='alert alert-warning text-center'>".$this->lang->line("no data found")."</div>";
         }
        
        $output.="</div>";


        $page_encoding =  mb_detect_encoding($output);
        if(isset($page_encoding)){
          $output = @iconv( $page_encoding, "utf-8//IGNORE", $output );
        } 

        $data["output"]  = $output;
        $data['body'] = 'video_search_engine/playlist_videos';
        $data['page_title'] = $this->lang->line('Playlist Videos');
        $this->_viewcontroller($data);

    }
 




}