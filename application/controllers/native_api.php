<?php
require_once("home.php");

class native_api extends Home
{
    public $user_id;
    
    public function __construct()
    {
        parent::__construct();   
        $this->user_id=$this->session->userdata("user_id");    
        $this->load->library('Web_common_report');
        $this->upload_path = realpath( APPPATH . '../upload');
    }


    public function api_member_validity($user_id='')
    {
        if($user_id!='') {
            $where['where'] = array('id'=>$user_id);
            $user_expire_date = $this->basic->get_data('users',$where,$select=array('expired_date'));
            $expire_date = strtotime($user_expire_date[0]['expired_date']);
            $current_date = strtotime(date("Y-m-d"));
            $package_data=$this->basic->get_data("users",$where=array("where"=>array("users.id"=>$user_id)),$select="package.price as price, users.user_type",$join=array('package'=>"users.package_id=package.id,left"));

            if(is_array($package_data) && array_key_exists(0, $package_data) && $package_data[0]['user_type'] == 'Admin' )
                return true;

            $price = '';
            if(is_array($package_data) && array_key_exists(0, $package_data))
            $price=$package_data[0]["price"];
            if($price=="Trial") $price=1;

            
            if ($expire_date < $current_date && ($price>0 && $price!=""))
            return false;
            else return true;
            

        }
    }


    public function index()
    {
       $this->get_api();
    }

    public function _api_key_generator()
    {
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login_page', 'location');

        if($this->session->userdata('user_type') != 'Admin' && !in_array(15,$this->module_access))
        redirect('home/login_page', 'location');

        $this->member_validity();
        $val=$this->session->userdata("user_id")."-".substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') , 0 , 7 ).time()
        .substr(str_shuffle('abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789') , 0 , 7 );
        return $val;
    }

    public function get_api()
    {
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login_page', 'location');

        if($this->session->userdata('user_type') != 'Admin' && !in_array(15,$this->module_access))
        redirect('home/login_page', 'location');

        $this->member_validity();

        $data['body'] = "api/native_api";
        $data['page_title'] = 'API';
        $api_data=$this->basic->get_data("native_api",array("where"=>array("user_id"=>$this->session->userdata("user_id"))));
        $data["api_key"]="";
        if(count($api_data)>0) $data["api_key"]=$api_data[0]["api_key"];
        $this->_viewcontroller($data);
    }

    public function get_api_action()
    { 
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login', 'location');

        if($this->session->userdata('user_type') != 'Admin' && !in_array(15,$this->module_access))
        redirect('home/login_page', 'location');

        $api_key=$this->_api_key_generator(); 
        if($this->basic->is_exist("native_api",array("api_key"=>$api_key)))
        $this->get_api_action();

        $user_id=$this->session->userdata("user_id");        
        if($this->basic->is_exist("native_api",array("user_id"=>$user_id)))
        $this->basic->update_data("native_api",array("user_id"=>$user_id),array("api_key"=>$api_key));
        else $this->basic->insert_data("native_api",array("api_key"=>$api_key,"user_id"=>$user_id));
            
        redirect('native_api/get_api', 'location');
    }


    public function video_upload_to_youtube($api_key="")
    {

        if ($api_key=="") exit();
        $user_id="";      
    
        if($api_key!="")
        {
            $explde_api_key=explode('-',$api_key);
            if(array_key_exists(0, $explde_api_key))
            $user_id=$explde_api_key[0];
        }        
        $this->user_id=$user_id;

        if(!$this->basic->is_exist("native_api",array("api_key"=>$api_key,"user_id"=>$user_id)))
        {
            echo "API Key does not match with any user.";
            exit();
        }   

        if(!$this->basic->is_exist("users",array("id"=>$user_id,"status"=>"1","deleted"=>"0","user_type"=>"Admin")))
        {
            echo "API Key does not match with any admin user.";
            exit();
        }  

        $this->load->library('Google_youtube_login');

        $where['where'] = array('upload_status'=>'0');
        $upload_video_list = $this->basic->get_data('youtube_video_upload',$where);


        
        foreach($upload_video_list as $value)
        {
            $channel_id = $value['channel_id'];
            $title = $value['title'];
            $description = $value['description'];
            $file_name = $value['link'];
            $tags = $value['tags'];
            $category = $value['category'];
            $privacy_type = $value['privacy_type'];

            $time_zone = $value['time_zone'];
            date_default_timezone_set($time_zone);
            $upload_time = strtotime($value['upload_time']);

            $date_time = date("Y-m-d H:i:s",strtotime("+5 minutes"));
            $current_time = strtotime($date_time);

            $where['where'] = array('youtube_channel_list.channel_id'=>$channel_id);
            $select = array('youtube_channel_list.id as table_id','access_token','refresh_token','channel_id','title');
            $join = array('youtube_channel_info'=>'youtube_channel_list.channel_info_id=youtube_channel_info.id,left');
            $channel_info = $this->basic->get_data('youtube_channel_list',$where,$select,$join);
            
            $this->session->set_userdata('cronjob_upload_access_token',$channel_info[0]['access_token']);
            $this->session->set_userdata('cronjob_upload_refresh_token',$channel_info[0]['refresh_token']);
            
            //upload video to youtube channel
            $uploaded_video_status = 'A service error occurred';
            if($upload_time <= $current_time)
                $uploaded_video_status = $this->google_youtube_login->cronjob_upload_video_to_youtube($title,$description,$file_name,$tags,$category,$privacy_type);

            $error_found = stripos($uploaded_video_status, "A service error occurred");
            if($error_found===false)
            {
                $data = array('upload_status'=>'1','video_id'=>$uploaded_video_status);
                $where = array('id'=>$value['id']);
                $this->basic->update_data('youtube_video_upload',$where,$data);

                $file_location = $this->upload_path."/video/".$value['link'];
                unlink($file_location);;
            }
        }





    }








    public function get_keyword_position_data($api_key="")
    {
        $user_id="";
        if($api_key!="")
        {
            $explde_api_key=explode('-',$api_key);
            $user_id="";
            if(array_key_exists(0, $explde_api_key))
            $user_id=$explde_api_key[0];
        }

        if($api_key=="")
        {        
            echo "API Key is required.";    
            exit();
        }

        if(!$this->basic->is_exist("native_api",array("api_key"=>$api_key,"user_id"=>$user_id)))
        {
           echo "API Key does not match with any user.";
           exit();
        }

        if(!$this->basic->is_exist("users",array("id"=>$user_id,"status"=>"1","deleted"=>"0","user_type"=>"Admin")))
        {
            echo "API Key does not match with any authentic user.";
            exit();
        }

        $this->load->library('web_common_report');
        
        
        /****** Video Tracking Code *******/
        $this->load->library('google');
        $keywords = $this->basic->get_data("video_position_set");
        
         foreach($keywords as $value){

            $keyword = $value['keyword'];
            $youtube_id=$value['youtube_video_id'];

        
            $keyword_position_youtube_data=$this->google->get_video_position($keyword,$youtube_id);
            
            $data = array(
                "keyword_id" => $value['id'],
                "youtube_position" => $keyword_position_youtube_data["position"],
                "date" => date("Y-m-d")
                );
            $this->basic->insert_data("video_position_report",$data);

        }
                
    }




    

    


    
}
