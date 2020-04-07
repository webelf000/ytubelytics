<?php

require_once("home.php"); // loading home controller

class playlist_search_engine extends Home
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

        if($this->session->userdata('user_type') != 'Admin' && !in_array(63,$this->module_access))
        redirect('home/login_page', 'location'); 
    }


    public function index()
    {
        $this->youtube();      
    }
    

  
    public function youtube($channel_id="")
    {
        $data['body'] = 'playlist_search_engine/youtube';
        $data['page_title'] = $this->lang->line('Playlist Search Engine');
        $data['channel_id'] = $channel_id;
        // $data["google_api"]=$this->basic->get_data("config",array("where"=>array("user_id"=>$this->session->userdata("user_id"))));
        $this->_viewcontroller($data);
    }

    public function youtube_action()
    {
        $status=$this->_check_usage($module_id=63,$request=1);
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
        $publish_after=$this->input->post("publish_after",true);
        $publish_before=$this->input->post("publish_before",true);
        $order=$this->input->post("order",true);
        $channel_id=$this->input->post("channel_id",true);

        if($keyword=="" && $channel_id=="")
        {
            echo "please enter keyword or channel ID";
            exit();
        }
		
	
        $youtube_data=$this->google->get_youtube_playlist($keyword,$limit,$channel_id,$location="",$radius="",$order,$publish_after,$publish_before);
        $playlist_ids=array_column($youtube_data, 'playlist_id');			
				
        $total_playlist=count($playlist_ids);
        $playlist_ids=implode(",",$playlist_ids);

        $final_data=$this->google->get_playlist_by_id($playlist_ids);
              

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
    
    
        $output.= "<div class='well text-center'><h2><i class='fa fa-th-list'></i> ".$this->lang->line("Playlists")." (".$total_playlist.")</h2></div>";

        $formatted_data=array();
        foreach ($final_data as $value) 
        {
           if(isset($value["items"]))
           {             
               foreach ($value["items"] as $row) 
               {
                  if(isset($row["id"]))
                  {
                     $formatted_data[$row["id"]]=array();
                     $formatted_data[$row["id"]]["playlist_id"]=$row["id"];
                     $formatted_data[$row["id"]]["status"]               =  isset($row["status"]["privacyStatus"])              ? $row["status"]["privacyStatus"] : "";
                     $formatted_data[$row["id"]]["videoCount"]           =  isset($row["contentDetails"]["itemCount"])          ? $row["contentDetails"]["itemCount"]: "0";

                  }
               }
            }
        } 
        foreach ($youtube_data as $row) 
        {    
           $published_at=isset($row["published_at"]) ? $row["published_at"] : "";
           $published_at=date("Y-m-d",strtotime($published_at));
           $channel_id=isset($row["channel_id"]) ? $row["channel_id"] : "";
           $playlist_id=isset($row["playlist_id"]) ? $row["playlist_id"] : "";
           $title=isset($row["title"]) ? $row["title"] : "";
           $description=isset($row["description"]) ? $row["description"] : "";
           $thumb=isset($row["thumbnail"]) ? $row["thumbnail"] : "";
           $channel_link="<a href='https://www.youtube.com/channel/{$channel_id}' target='_BLANK'>Visit Channel</a>";         
           
           $video_count= isset($formatted_data[$playlist_id]["videoCount"]) ? $formatted_data[$playlist_id]["videoCount"] : "0";
           $status= isset($formatted_data[$playlist_id]["status"]) ? $formatted_data[$playlist_id]["status"]: "0";

           $real_url="https://www.youtube.com/playlist?list={$playlist_id}";
           $url="http://www.youtube.com/embed/videoseries?list={$playlist_id}&amp;hl=en_US&showinfo=1";

           $get_video_sub_btn=base_url("video_search_engine/youtube_playlist_video/{$playlist_id}");
           $get_video_btn="<a style='margin-top:7px;padding:3px;font-size:11px' class='btn btn-primary' href='{$get_video_sub_btn}' target='_BLANK'>Videos</a>";
           $get_video_sub_mon_btn=base_url("video_search_engine/youtube_playlist_video/{$playlist_id}/only_monetized");
          
           $output.=' 
            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3" style="margin-bottom:10px;padding:5px">
                <div class="col-xs-12" style="min-height:350px; border:1px solid #ccc; padding:10px;background:#fcfcfc;">';
                    if(strlen($title)>50) $dot='...'; else $dot="";
                    $output.="<h5 class='text-center' style='cursor:pointer;' title='".$title."'><b>".substr($title, 0, 50).$dot."</b></h5>";
                    $output.= "<div class='col-xs-12'><span class='pull-left'><i title='Status' style='color:#8ABA45' class='fa  fa-user'></i> ".$status."</span>";
                    $output.= "<span class='pull-right'><i title='Videos' style='color:#3C8DBC' class='fa  fa-camera-retro'></i>".$video_count.'</span>';
                    $output.= "</div><center><a class='youtube' title='{$description}' target='_BLANK' href='{$url}'><img class='img-thumbnail' style='width:100% !important;height:150px !important;' src='".$thumb."'></a></center>";
                    $output.= " <center>{$channel_link}<br/>";
                    $output.= "<span style='margin-top:5px'></span><input type='text' style='width:100%' value='{$real_url}'/><center>";
                    if($this->session->userdata('user_type') == 'Admin' || in_array(26,$this->module_access))
                    {
                        $output.="&nbsp;{$get_video_btn}";
                    }                
                    $output.= "<br/><span style='font-size:11px;'>published: {$published_at}</span>";
                    $output.= 
                '</div>
            </div>';               
          
        }
        if($total_playlist==0) $output.= "<div class='alert alert-warning text-center'>".$this->lang->line("no data found")."</div>";
        else
        {
           // insert into usage_log table
           $this->_insert_usage_log($module_id=63,$request=1);
        }
        $output.="</div>";

        $page_encoding =  mb_detect_encoding($output);
        if(isset($page_encoding)){
          $output = @iconv( $page_encoding, "utf-8//IGNORE", $output );
        } 
        echo $output;

    } 




}