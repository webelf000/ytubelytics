<?php 
require_once("home.php"); // loading home controller

class Youtube_live_event extends Home
{

    public $user_id;    
    
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login_page', 'location');
 
        $this->user_id=$this->session->userdata('user_id');
        set_time_limit(0);

        $this->important_feature();
        $this->member_validity();
        if($this->session->userdata('user_type') != 'Admin' && !in_array(39,$this->module_access))
        redirect('home/login_page', 'location'); 

        $this->load->library('Google_youtube_login');
        $this->upload_path = realpath( APPPATH . '../upload');
    }


    public function index()
    {
        $this->live_event_list();
    }


    public function live_event_list()
    {
        $data['page_title'] = "Live event list";
        $data['body'] = 'youtube_live_event/live_event_list';
        $this->_viewcontroller($data);
    }


    public function live_event_list_data()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }
        $page = isset($_POST['page']) ? intval($_POST['page']) : 15;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 5;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'id';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'DESC';

        $event_title      = trim($this->input->post("event_title", true));
        $is_searched = $this->input->post('is_searched', true);


        if ($is_searched) {            
            $this->session->set_userdata('youtube_live_event_title', $event_title);
        }

        $search_video_title = $this->session->userdata('youtube_live_event_title');

        $where_simple=array();

        if ($search_video_title) {
            $where_simple['title like ']    = "%".$search_video_title."%";
        }
        
        $where_simple['user_id'] = $this->user_id;
        
        $where  = array('where'=>$where_simple);

        $order_by_str=$sort." ".$order;

        $offset = ($page-1)*$rows;
        $result = array();

        $table = "youtube_live_event";

        $info = $this->basic->get_data($table, $where, $select='', $join='', $limit=$rows, $start=$offset, $order_by=$order_by_str, $group_by='');

        $total_rows_array = $this->basic->count_row($table, $where, $count="id");      

        $total_result = $total_rows_array[0]['total_rows'];

        echo convert_to_grid_data($info, $total_result);
    }


    public function create_new_event()
    {
        $user_id = $this->user_id;
        $where['where'] = array('user_id'=>$user_id);
        $channel_list = $this->basic->get_data('youtube_channel_list',$where);
        $user_channel_list = array();
        foreach($channel_list as $value)
        {
            $user_channel_list[$value['channel_id']] = $value['title'];
        }
        $data['channel_list'] = $user_channel_list;
        $data['time_zone_list'] = $this->_time_zone_list();
        // $data['video_category'] = $this->get_youtube_video_category();
        $data['page_title'] = "Create new event";
        $data['body'] = 'youtube_live_event/upload_live_event';
        $this->_viewcontroller($data);
    }


    public function create_new_event_action()
    {               
        $status=array('status'=>"0","error"=>"","count"=>0);
        if($_POST)
        {
            //************************************************//
            $usage_status=$this->_check_usage($module_id=39,$request=1);
            if($usage_status=="2") 
            {
                $error_msg = $this->lang->line("sorry, your bulk limit is exceeded for this module.")."<a href='".site_url('payment/usage_history')."'>".$this->lang->line("click here to see usage log")."</a>";
                $status["status"]="0";
                $status["error"]=$error_msg;
                echo json_encode($status);
                exit();
            }
            else if($usage_status=="3") 
            {
                $error_msg = $this->lang->line("sorry, your monthly limit is exceeded for this module.")."<a href='".site_url('payment/usage_history')."'>".$this->lang->line("click here to see usage log")."</a>";
                $status["status"]="0";
                $status["error"]=$error_msg;
                echo json_encode($status);
                exit();
            }
            //************************************************//

            $channel_id = $this->input->post('channel_id',true);
            $title = $this->input->post('title',true);
            $description = $this->input->post('description',true);
            $tags = '';
            $instance = 0;
            $privacy_type = $this->input->post('privacy_type',true);
            $time_zone = $this->input->post('time_zone',true);
            $start_time = $this->input->post('start_time',true);
            $end_time = $this->input->post('end_time',true);            
                   
            $where['where'] = array('youtube_channel_list.channel_id'=>$channel_id);
            $select = array('youtube_channel_list.id as table_id','access_token','refresh_token','channel_id','title');
            $join = array('youtube_channel_info'=>'youtube_channel_list.channel_info_id=youtube_channel_info.id,left');
            $channel_info = $this->basic->get_data('youtube_channel_list',$where,$select,$join);
            
            $this->session->set_userdata('youtube_liveevent_access_token',$channel_info[0]['access_token']);
            $this->session->set_userdata('youtube_liveevent_refresh_token',$channel_info[0]['refresh_token']);
            
            //upload video to youtube channel
            $uploaded_event_status = $this->google_youtube_login->upload_live_event_youtube($title,$description,$tags,$privacy_type,$start_time,$end_time,$time_zone);
            
            if(isset($uploaded_event_status['error']))
            {
                $status["status"]="0";
                $status["error"]=$uploaded_event_status['error'];
            }
            else
            {
                $Broadcast_id = $uploaded_event_status['Broadcast_id'];
                $Stream_id = $uploaded_event_status['Stream_id'];
                $boundBroadcast_id = $uploaded_event_status['boundBroadcast_id'];
                $boundStream_id = $uploaded_event_status['boundStream_id'];

                $tags_explode=explode(',',$tags);
                $tags_position_array=array();
                foreach ($tags_explode as $value) 
                {
                    $tags_position_array[]=0;
                }
                $tags_position=implode(',', $tags_position_array);
                
                $data = array(
                    'user_id' => $this->user_id,
                    'channel_id' => $channel_id,
                    'Broadcast_id' => $Broadcast_id,
                    'Stream_id' => $Stream_id,
                    'boundBroadcast_id' => $boundBroadcast_id,
                    'boundStream_id' => $boundStream_id,
                    'title' => $title,
                    'description' => $description,
                    'tags' => $tags,
                    'privacy_type' => $privacy_type,
                    'time_zone' => $time_zone,
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'check_status' => '0',
                    'tags_position' => $tags_position
                    );

                 if($this->basic->insert_data('youtube_live_event',$data))
                 { 
                    // insert into usage_log table
                    $this->_insert_usage_log($module_id=39,$request=1);           
                    $this->session->set_userdata('uploaded_event_status', "1 live event has been created successfuly.");
                    $status["status"]="1";
                    $status["count"]=1;
                 }
                 else
                 {
                     $status["status"]="0";
                     $status["error"]="your data has been failed to stored into the database.";
                 }
               
            } //end else

            echo json_encode($status);
        } // end if post


    }




    public function delete_uploaded_event($id=0)
    {
        if($id == 0) exit();
        $this->db->trans_start();
        $where_delete = array('id'=>$id);
        $this->basic->delete_data('youtube_live_event',$where_delete);
        $this->_delete_usage_log($module_id=39,$request=1);
        $this->db->trans_complete();
        if($this->db->trans_status() === false) {
            $this->session->set_userdata('delete_error','Sorry fail to delete your event from database.');
        }
        else
            $this->session->set_userdata('delete_success','Your event has been successfuly deleted from database.');

        redirect('youtube_live_event/live_event_list','Location');
    }




    public function uploaded_video_list()
    {
        $data['page_title'] = "Uploaded video List";
        $data['body'] = 'youtube_live_event/uploaded_video_list_grid';
        $this->_viewcontroller($data);
    }


    public function uploaded_video_list_data()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }
        $page = isset($_POST['page']) ? intval($_POST['page']) : 15;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 5;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'id';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'DESC';

        $video_title      = trim($this->input->post("video_title", true));
        $is_searched = $this->input->post('is_searched', true);


        if ($is_searched) {            
            $this->session->set_userdata('youtube_uploaded_video_title', $video_title);
        }

        $search_video_title = $this->session->userdata('youtube_uploaded_video_title');

        $where_simple=array();

        if ($search_video_title) {
            $where_simple['title like ']    = "%".$search_video_title."%";
        }
        
        $where_simple['user_id'] = $this->user_id;
        
        $where  = array('where'=>$where_simple);

        $order_by_str=$sort." ".$order;

        $offset = ($page-1)*$rows;
        $result = array();

        $table = "youtube_video_upload";

        $info = $this->basic->get_data($table, $where, $select='', $join='', $limit=$rows, $start=$offset, $order_by=$order_by_str, $group_by='');

        $total_rows_array = $this->basic->count_row($table, $where, $count="id");      

        $total_result = $total_rows_array[0]['total_rows'];

        echo convert_to_grid_data($info, $total_result);
    }


    public function youtube_video_upload()
    {
        $user_id = $this->user_id;
        $where['where'] = array('user_id'=>$user_id);
        $channel_list = $this->basic->get_data('youtube_channel_list',$where);
        $user_channel_list = array();
        foreach($channel_list as $value)
        {
            $user_channel_list[$value['channel_id']] = $value['title'];
        }
        $data['channel_list'] = $user_channel_list;
        $data['time_zone_list'] = $this->_time_zone_list();
        $data['video_category'] = $this->get_youtube_video_category();
        $data['page_title'] = "Video Upload";
        $data['body'] = 'youtube_live_event/video_upload';
        $this->_viewcontroller($data);
    }


    public function youtube_video_upload_action()
    {
        //************************************************//

        $status=$this->_check_usage($module_id=39,$request=1);
        if($status=="2") 
        {
            $error_msg = $this->lang->line("sorry, your bulk limit is exceeded for this module.")."<a href='".site_url('payment/usage_history')."'>".$this->lang->line("click here to see usage log")."</a>";
            $this->session->set_userdata('youtube_upload_error',$error_msg);
            return $this->youtube_video_upload();
        }
        else if($status=="3") 
        {
            $error_msg = $this->lang->line("sorry, your monthly limit is exceeded for this module.")."<a href='".site_url('payment/usage_history')."'>".$this->lang->line("click here to see usage log")."</a>";
            $return_val=array("status"=>"0","message"=>$error_msg);
            $this->session->set_userdata('youtube_upload_error',$error_msg);
            return $this->youtube_video_upload();
        }
        //************************************************//

        $this->form_validation->set_rules('channel_id','<b>Channel ID</b>', 'trim|required|xss_clean');
        $this->form_validation->set_rules('title','<b>Video Title</b>', 'trim|xss_clean');
        $this->form_validation->set_rules('description','<b>Video Description</b>', 'trim|xss_clean');
        $this->form_validation->set_rules('tags','<b>Video Tags</b>', 'trim|xss_clean');
        $this->form_validation->set_rules('category','<b>Video Category</b>', 'trim|xss_clean|required');
        $this->form_validation->set_rules('privacy_type','<b>Privacy Type</b>', 'trim|xss_clean|required');
        $this->form_validation->set_rules('time_zone','<b>Time Zone</b>', 'trim|xss_clean|required');
        $this->form_validation->set_rules('schedule_time','<b>Schedule Time</b>', 'trim|xss_clean|required');

        if($this->form_validation->run() == FALSE)
        $this->youtube_video_upload();
        else
        {

            $channel_id = $this->input->post('channel_id',true);
            $title = $this->input->post('title',true);
            $description = $this->input->post('description',true);
            $tags = $this->input->post('tags',true);
            $category = $this->input->post('category',true);
            $privacy_type = $this->input->post('privacy_type',true);
            $time_zone = $this->input->post('time_zone',true);
            $upload_time = 'later';
            $schedule_time = $this->input->post('schedule_time',true);
            $file_name = $this->input->post('video_url',true);


            if($file_name!='')
            {
    
                $data = array
                (
                    'user_id' => $this->user_id,
                    'channel_id' => $channel_id,
                    'title' => $title,
                    'description' => $description,
                    'tags' => $tags,
                    'category' => $category,
                    'privacy_type' => $privacy_type,
                    'time_zone' => $time_zone,
                    'link' => $file_name,
                    'upload_time' => $schedule_time,
                    'upload_status' => '0',
                );

                
                if($this->basic->insert_data('youtube_video_upload',$data))
                {                  
                    // insert into usage_log table
                    $this->_insert_usage_log($module_id=39,$request=1);
                    $this->session->set_userdata('video_uploaded',"Your request has been accepted");
                    redirect('youtube_live_event/uploaded_video_list','Location'); 
                }

            }
            else
            {
                $this->session->set_userdata('video_upload_error',"<b>Video File</b> is required.");
                return $this->youtube_video_upload();
            }

        }

    }

    public function upload_video()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') exit();

        $ret=array();
        $output_dir = FCPATH."upload/video";
        if (isset($_FILES["myfile"])) {
            $error =$_FILES["myfile"]["error"];
            $post_fileName =$_FILES["myfile"]["name"];
            $post_fileName_array=explode(".", $post_fileName);
            $ext=array_pop($post_fileName_array);
            $filename=implode('.', $post_fileName_array);
            $filename="video_".$this->user_id."_".time().substr(uniqid(mt_rand(), true), 0, 6).".".$ext;
            move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir.'/'.$filename);
            $ret[]= $filename;
            echo json_encode($filename);
        }
    }

    public function delete_uploaded_file() // deletes the uploaded video to upload another one
    {
        if(!$_POST) exit();

        $output_dir = FCPATH."upload/video/";
        if(isset($_POST["op"]) && $_POST["op"] == "delete" && isset($_POST['name']))
        {
             $fileName =$_POST['name'];
             $fileName=str_replace("..",".",$fileName); //required. if somebody is trying parent folder files 
             $filePath = $output_dir. $fileName;
             if (file_exists($filePath)) 
             {
                unlink($filePath);
             }
        }
    }




    public function delete_uploaded_video($id=0)
    {
        if($id == 0) exit();
        $this->db->trans_start();

        $where_delete = array('id'=>$id);
        $where_get['where'] = array('id'=>$id);
        $video_info = $this->basic->get_data('youtube_video_upload',$where_get);

        if (file_exists(realpath(APPPATH . '../upload/video/'.$video_info[0]['link'])))
            unlink(realpath(APPPATH . '../upload/video/'.$video_info[0]['link']));

        $this->basic->delete_data('youtube_video_upload',$where_delete);
        if($video_info[0]['upload_status'] == '0')
            $this->_delete_usage_log($module_id=39,$request=1);

        $this->db->trans_complete();
        if($this->db->trans_status() === false) {
            $this->session->set_userdata('delete_error','Sorry fail to delete your video from database.');
        }
        else
            $this->session->set_userdata('delete_success','Your video has been successfuly deleted from database.');

        redirect('youtube_live_event/uploaded_video_list','Location');
    }


    public function edit_uploaded_video($id=0)
    {
        if($id == 0) exit();
        $user_id = $this->user_id;
        $where['where'] = array('user_id'=>$user_id);
        $channel_list = $this->basic->get_data('youtube_channel_list',$where);
        $user_channel_list = array();
        foreach($channel_list as $value)
        {
            $user_channel_list[$value['channel_id']] = $value['title'];
        }
        $data['channel_list'] = $user_channel_list;
        $data['time_zone_list'] = $this->_time_zone_list();
        $data['video_category'] = $this->get_youtube_video_category();

        $where_video['where'] = array('id'=>$id);
        $video_info = $this->basic->get_data('youtube_video_upload',$where_video);
        $data['video_info'] = $video_info;
        $data['page_title'] = "Edit Video Info.";
        $data['body'] = 'youtube_live_event/edit_video';
        $this->_viewcontroller($data);
    }


    public function edit_uploaded_video_action()
    {
        $this->form_validation->set_rules('channel_id','<b>Channel ID</b>', 'trim|required|xss_clean');
        $this->form_validation->set_rules('title','<b>Video Title</b>', 'trim|xss_clean');
        $this->form_validation->set_rules('description','<b>Video Description</b>', 'trim|xss_clean');
        $this->form_validation->set_rules('tags','<b>Video Tags</b>', 'trim|xss_clean');
        $this->form_validation->set_rules('category','<b>Video Category</b>', 'trim|xss_clean|required');
        $this->form_validation->set_rules('privacy_type','<b>Privacy Type</b>', 'trim|xss_clean|required');
        $this->form_validation->set_rules('time_zone','<b>Time Zone</b>', 'trim|xss_clean|required');
        $this->form_validation->set_rules('schedule_time','<b>Schedule Time</b>', 'trim|xss_clean|required');
        $id = $this->input->post('table_id',true);

        if($this->form_validation->run() == FALSE)
        $this->edit_uploaded_video($id);
        else
        {

            $channel_id = $this->input->post('channel_id',true);
            $title = $this->input->post('title',true);
            $description = $this->input->post('description',true);
            $tags = $this->input->post('tags',true);
            $category = $this->input->post('category',true);
            $privacy_type = $this->input->post('privacy_type',true);
            $time_zone = $this->input->post('time_zone',true);
            $upload_time = 'later';
            $schedule_time = $this->input->post('schedule_time',true);

            if($upload_time == 'now')
            {
                date_default_timezone_set($time_zone);
                $schedule_time = date("Y-m-d H:i:s");
            }
            
            $data = array(
                'channel_id' => $channel_id,
                'title' => $title,
                'description' => $description,
                'tags' => $tags,
                'category' => $category,
                'privacy_type' => $privacy_type,
                'time_zone' => $time_zone,
                'upload_time' => $schedule_time
                );
            $where_update = array('id'=>$id,'user_id'=>$this->user_id);
            $this->basic->update_data('youtube_video_upload',$where_update,$data);

            $this->session->set_userdata('edit_video_info','Your video information has been updated successfuly.');
            redirect('youtube_live_event/uploaded_video_list','Location');
        }

    }


 

    public function get_youtube_video_category()
    {
        $data = array(
            "2" => "Cars & Vehicles",
            "23" => "Comedy",
            "27" => "Education",
            "24" => "Entertainment",
            "1" => "Film & Animation",
            "20" => "Gaming",
            "26" => "How-to & Style",
            "10" => "Music",
            "25" => "News & Politics",
            "29" => "Non-profits & Activism",
            "22" => "People & Blogs",
            "15" => "Pets & Animals",
            "28" => "Science & Technology",
            "17" => "Sport",
            "19" => "Travel & Events"
            );
        return $data;
    }



    public function country_names_array(){
        $data = array(
            'AF' => 'Afghanistan',
            'AX' => 'Aland Islands',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AS' => 'American Samoa',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AQ' => 'Antarctica',
            'AG' => 'Antigua and Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AW' => 'Aruba',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas the',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BA' => 'Bosnia and Herzegovina',
            'BW' => 'Botswana',
            'BV' => 'Bouvet Island (Bouvetoya)',
            'BR' => 'Brazil',
            'IO' => 'British Indian Ocean Territory',
            'VG' => 'British Virgin Islands',
            'BN' => 'Brunei Darussalam',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'CV' => 'Cape Verde',
            'KY' => 'Cayman Islands',
            'CF' => 'Central African Republic',
            'TD' => 'Chad',
            'CL' => 'Chile',
            'CN' => 'China',
            'CX' => 'Christmas Island',
            'CC' => 'Cocos (Keeling) Islands',
            'CO' => 'Colombia',
            'KM' => 'Comoros the',
            'CD' => 'Congo',
            'CG' => 'Congo the',
            'CK' => 'Cook Islands',
            'CR' => 'Costa Rica',
            'CI' => 'Cote d`Ivoire',
            'HR' => 'Croatia',
            'CU' => 'Cuba',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DK' => 'Denmark',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'ET' => 'Ethiopia',
            'FO' => 'Faroe Islands',
            'FK' => 'Falkland Islands (Malvinas)',
            'FJ' => 'Fiji the Fiji Islands',
            'FI' => 'Finland',
            'FR' => 'France',
            'GF' => 'French Guiana',
            'PF' => 'French Polynesia',
            'TF' => 'French Southern Territories',
            'GA' => 'Gabon',
            'GM' => 'Gambia the',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GR' => 'Greece',
            'GL' => 'Greenland',
            'GD' => 'Grenada',
            'GP' => 'Guadeloupe',
            'GU' => 'Guam',
            'GT' => 'Guatemala',
            'GG' => 'Guernsey',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HM' => 'Heard Island and McDonald Islands',
            'VA' => 'Holy See',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IM' => 'Isle of Man',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JE' => 'Jersey',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'KP' => 'Korea',
            'KR' => 'Korea',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyz Republic',
            'LA' => 'Lao',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libyan Arab Jamahiriya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MO' => 'Macao',
            'MK' => 'Macedonia',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'YT' => 'Mayotte',
            'MX' => 'Mexico',
            'FM' => 'Micronesia',
            'MD' => 'Moldova',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'ME' => 'Montenegro',
            'MS' => 'Montserrat',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'AN' => 'Netherlands Antilles',
            'NL' => 'Netherlands the',
            'NC' => 'New Caledonia',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NU' => 'Niue',
            'NF' => 'Norfolk Island',
            'MP' => 'Northern Mariana Islands',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestinian Territory',
            'PA' => 'Panama',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PN' => 'Pitcairn Islands',
            'PL' => 'Poland',
            'PT' => 'Portugal => Portuguese Republic',
            'PR' => 'Puerto Rico',
            'QA' => 'Qatar',
            'RE' => 'Reunion',
            'RO' => 'Romania',
            'RU' => 'Russian Federation',
            'RW' => 'Rwanda',
            'BL' => 'Saint Barthelemy',
            'SH' => 'Saint Helena',
            'KN' => 'Saint Kitts and Nevis',
            'LC' => 'Saint Lucia',
            'MF' => 'Saint Martin',
            'PM' => 'Saint Pierre and Miquelon',
            'VC' => 'Saint Vincent and the Grenadines',
            'WS' => 'Samoa',
            'SM' => 'San Marino',
            'ST' => 'Sao Tome and Principe',
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'RS' => 'Serbia',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia',
            'ZA' => 'South Africa',
            'GS' => 'South Georgia and the South Sandwich Islands',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SJ' => 'Svalbard & Jan Mayen Islands',
            'SZ' => 'Swaziland',
            'SE' => 'Sweden',
            'CH' => 'Switzerland => Swiss Confederation',
            'SY' => 'Syrian Arab Republic',
            'TW' => 'Taiwan',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania',
            'TH' => 'Thailand',
            'TL' => 'Timor-Leste',
            'TG' => 'Togo',
            'TK' => 'Tokelau',
            'TO' => 'Tonga',
            'TT' => 'Trinidad and Tobago',
            'TN' => 'Tunisia',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TC' => 'Turks and Caicos Islands',
            'TV' => 'Tuvalu',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom',
            'US' => 'United States',
            'UM' => 'United States Minor Outlying Islands',
            'VI' => 'United States Virgin Islands',
            'UY' => 'Uruguay, Eastern Republic of',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VE' => 'Venezuela',
            'VN' => 'Vietnam',
            'WF' => 'Wallis and Futuna',
            'EH' => 'Western Sahara',
            'YE' => 'Yemen',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe'
        );

        return $data;
    }


   


}