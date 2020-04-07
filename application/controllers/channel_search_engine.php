<?php

require_once("home.php"); // loading home controller

class channel_search_engine extends Home
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

        if($this->session->userdata('user_type') != 'Admin' && !in_array(62,$this->module_access))
        redirect('home/login_page', 'location'); 
    }


    public function index()
    {
        $this->youtube();      
    }
    

  
    public function youtube()
    {
        $data['body'] = 'channel_search_engine/youtube';
        $data['page_title'] = $this->lang->line('Channel Search Engine');
        // $data["google_api"]=$this->basic->get_data("config",array("where"=>array("user_id"=>$this->session->userdata("user_id"))));
        $this->_viewcontroller($data);
    }

    public function youtube_action()
    {
        $status=$this->_check_usage($module_id=62,$request=1);
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

        if($keyword=="")
        {
            echo $this->lang->line("please enter keyword");
            exit();
        }      	
	
        $youtube_data=$this->google->get_youtube_channel($keyword,$limit,$location="",$radius="",$order,$publish_after,$publish_before);
        $channel_ids=array_column($youtube_data, 'channel_id');			
				
        $total_channel=count($channel_ids);
        $channel_ids=implode(",",$channel_ids);

        $final_data=$this->google->get_channel_by_id($channel_ids);
              

        $output='';
    
        $output.= "<div class='well text-center'><h2><i class='fa fa-tv'></i> ".$this->lang->line("channels")." (".$total_channel.")</h2></div>";

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
                     $formatted_data[$row["id"]]["channel_id"]=$row["id"];
                     $formatted_data[$row["id"]]["viewCount"]            =  isset($row["statistics"]["viewCount"])              ? $row["statistics"]["viewCount"] : "0";
                     $formatted_data[$row["id"]]["commentCount"]         =  isset($row["statistics"]["commentCount"])           ? $row["statistics"]["commentCount"]: "0";
                     $formatted_data[$row["id"]]["subscriberCount"]      =  isset($row["statistics"]["subscriberCount"])        ? $row["statistics"]["subscriberCount"]: "0";
                     $formatted_data[$row["id"]]["hiddenSubscriberCount"]=  isset($row["statistics"]["hiddenSubscriberCount"])  ? $row["statistics"]["hiddenSubscriberCount"]: "0";
                     $formatted_data[$row["id"]]["videoCount"]           =  isset($row["statistics"]["videoCount"])             ? $row["statistics"]["videoCount"]: "0";

                  }
               }
            }
        } 
        foreach ($youtube_data as $row) 
        {    
           $published_at=isset($row["published_at"]) ? $row["published_at"] : "";
           $published_at=date("Y-m-d",strtotime($published_at));
           $channel_id=isset($row["channel_id"]) ? $row["channel_id"] : "";
           $title=isset($row["title"]) ? $row["title"] : "";
           $description=isset($row["description"]) ? $row["description"] : "";
           $thumb=isset($row["thumbnail"]) ? $row["thumbnail"] : "";
           
           $views= isset($formatted_data[$channel_id]["viewCount"]) ? $formatted_data[$channel_id]["viewCount"] : "0";
           $subscriber= isset($formatted_data[$channel_id]["subscriberCount"]) ? $formatted_data[$channel_id]["subscriberCount"]: "0";
           $hidden_subscriber= isset($formatted_data[$channel_id]["hiddenSubscriberCount"]) ? $formatted_data[$channel_id]["hiddenSubscriberCount"]: "0";
           $video_count= isset($formatted_data[$channel_id]["videoCount"]) ? $formatted_data[$channel_id]["videoCount"] : "0";
           $comments= isset($formatted_data[$channel_id]["commentCount"]) ? $formatted_data[$channel_id]["commentCount"]: "0";

           $real_url="https://www.youtube.com/channel/{$channel_id}";
           
           $get_play_list_sub_btn=base_url("playlist_search_engine/youtube/{$channel_id}");
           $get_play_list_btn="<a style='margin-top:7px;padding:3px;font-size:11px' class='btn btn-info' href='{$get_play_list_sub_btn}' target='_BLANK'>Playlists</a>";
            
           $get_video_sub_btn=base_url("video_search_engine/youtube/{$channel_id}");
           $get_video_btn="<a style='margin-top:7px;padding:3px;font-size:11px' class='btn btn-primary' href='{$get_video_sub_btn}' target='_BLANK'>Videos</a>";
            
           $get_video_sub_mon_btn=base_url("video_search_engine/youtube/{$channel_id}/only_monetized");
           $get_video_mon_btn="<a style='margin-top:7px;padding:3px;font-size:11px' class='btn btn-warning' href='{$get_video_sub_mon_btn}' target='_BLANK'>Monetized Videos</a>";
 
           $output.=' 
            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3" style="margin-bottom:10px;padding:5px">
                <div class="col-xs-12" style="min-height:380px; border:1px solid #ccc; padding:10px;background:#fcfcfc;">';
                    if(strlen($title)>50) $dot='...'; else $dot="";
                    $output.="<h5 class='text-center' style='cursor:pointer;' title='".$title."'><b>".substr($title, 0, 50).$dot."</b></h5>";
                    $output.= "<div class='col-xs-12'><span class='pull-left'><i title='Subscriber' style='color:#8ABA45' class='fa  fa-user'></i> ".$subscriber."</span>";
                    $output.= "<span class='pull-right'><i title='Videos' style='color:#3C8DBC' class='fa  fa-camera-retro'></i>".$video_count.'</span>';
                    $output.= "</div><div class='col-xs-12'><span class='pull-left'><i title='Views' style='color:#F39C12' class='fa  fa-play-circle'></i>".$views.'</span>';
                    $output.= "<span class='pull-right'><i title='Comments' style='color:#F39C12' class='fa  fa-comment'></i>".$comments.'</span>';
                    $output.= "</div><center><a title='{$title}' target='_BLANK' href='{$real_url}'><img class='img-thumbnail' style='width:100% !important;height:150px !important;' src='".$thumb."'></a></center>";
                    $output.= " <center><span style='font-size:9px'><b>Channel ID:</b> <br/> {$channel_id}</span><br/>";
                    $output.= "<span style='margin-top:5px'></span><input type='text' style='width:100%' value='{$description}'/><center>";
                    if($this->session->userdata('user_type') == 'Admin' || in_array(63,$this->module_access))
                    $output.="&nbsp;{$get_play_list_btn}";
                    if($this->session->userdata('user_type') == 'Admin' || in_array(26,$this->module_access))
                    {
                        // $output.="&nbsp;{$get_video_mon_btn}";
                        $output.="&nbsp;{$get_video_btn}";
                    }
                    $output.= "<br/><span style='font-size:11px;'>published: {$published_at}</span>";
                    $output.=
                '</div>
            </div>';               
          
        }
        if($total_channel==0) $output.= "<div class='alert alert-warning text-center'>".$this->lang->line("no data found")."</div>";
        else
        {
           // insert into usage_log table
           $this->_insert_usage_log($module_id=62,$request=1);
        }
        $output.="</div>";

        $page_encoding =  mb_detect_encoding($output);
        if(isset($page_encoding)){
          $output = @iconv( $page_encoding, "utf-8//IGNORE", $output );
        } 
        echo $output;

    } 




}