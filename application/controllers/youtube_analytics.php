<?php 
require_once("home.php"); // loading home controller

class Youtube_analytics extends Home
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
        if($this->session->userdata('user_type') != 'Admin' && !in_array(33,$this->module_access))
        redirect('home/login_page', 'location'); 

        $this->load->library('Google_youtube_login');
        $this->upload_path = realpath( APPPATH . '../upload');
    }


    public function index()
    {
        $data['body'] = "youtube_analytics/login_page";
        $data['page_title'] = $this->lang->line("Youtube Analytic");
        $data['login_button'] = $this->google_youtube_login->set_login_button();
        $status=$this->_check_usage($module_id=33,$request=1);
        if($status=="2") 
        {
            $data['limit_cross'] = "Sorry, your account import limit has been exceeded.";
        }
        else if($status=="3") 
        {
            $data['limit_cross'] = "Sorry, your account import limit has been exceeded.";
        }

        $this->_viewcontroller($data);
    }



    public function login_redirect()
    {
        $channel_list_info = $this->google_youtube_login->get_channel_list();

        $access_token = $channel_list_info['json_access_token'];
        $refresh_token_array = json_decode($access_token,true);
        $refresh_token = $refresh_token_array['refresh_token'];

        $data_1 = array(
            'access_token' => $access_token,
            'user_id' => $this->user_id,
            'refresh_token' => $refresh_token,
            'last_update' => date("Y-m-d")
            );
        $this->basic->insert_data('youtube_channel_info',$data_1);
        $channel_info_id = $this->db->insert_id();


        if(!empty($channel_list_info['channel_list']['items']))
        {
            foreach($channel_list_info['channel_list']['items'] as $value)
            {
                $data = array(
                    'user_id' => $this->user_id,
                    'channel_info_id' => $channel_info_id
                    );
                if(isset($value['id']))
                    $data['channel_id'] = $value['id'];

                if(isset($value['snippet']['title']))
                    $data['title'] = $value['snippet']['title'];

                if(isset($value['snippet']['description']))
                    $data['description'] = $value['snippet']['description'];

                if(isset($value['snippet']['thumbnails']['default']))
                    $data['profile_image'] = $value['snippet']['thumbnails']['default']['url'];

                if(isset($value['snippet']['thumbnails']['high']))
                    $data['cover_image'] = $value['snippet']['thumbnails']['high']['url'];

                if(isset($value['statistics']['viewCount']))
                    $data['view_count'] = $value['statistics']['viewCount'];

                if(isset($value['statistics']['videoCount']))
                    $data['video_count'] = $value['statistics']['videoCount'];

                if(isset($value['statistics']['subscriberCount']))
                    $data['subscriber_count'] = $value['statistics']['subscriberCount'];

                if(isset($data['channel_id']))
                {
                    $where['where'] = array(
                        'user_id' => $this->user_id,
                        'channel_id' => $data['channel_id']
                        );
                    $existing_data = $this->basic->get_data('youtube_channel_list',$where);
                    if(!empty($existing_data))
                    {
                        $where_update = array(
                            'user_id' => $this->user_id,
                            'channel_id' => $data['channel_id']
                            );
                        $this->basic->update_data('youtube_channel_list',$where_update,$data);
                    }
                    else
                    {
                        $this->basic->insert_data('youtube_channel_list',$data);
                        // insert into usage_log table
                        $this->_insert_usage_log($module_id=33,$request=1);
                    }

                    

                    $channel_content_details = $this->google_youtube_login->get_channel_content_details($refresh_token_array['access_token']);
                    $playlist_id=$channel_content_details['items'][0]['contentDetails']['relatedPlaylists']['uploads'];                    
                    /***** Get all palylist Item ***/
                    $next_page='';
                    do{
                        $playlist_info=$this->playlist_item($refresh_token_array['access_token'],$playlist_id,$next_page);
                        if(isset($playlist_info['nextPageToken']))
                            $next_page=$playlist_info['nextPageToken'];
                        else
                            $next_page = '';
                        $video_id_str='';
                        foreach($playlist_info['items'] as $info){
                            $video_id=$info['snippet']['resourceId']['videoId'];
                            $video_id_str.=$video_id.",";
                            $video_information[$video_id]['publishedAt']=$info['snippet']['publishedAt'];
                            $video_information[$video_id]['title']=$info['snippet']['title'];
                            $video_information[$video_id]['thumbnails']=$info['snippet']['thumbnails']['medium']['url'];
                        }

                        $video_info= $this->google_youtube_login->get_video_details_list($refresh_token_array['access_token'],$video_id_str);


                        foreach($video_info['items'] as $v_info){

                         $single_video_id= $v_info['id'];
                         $video_information[$single_video_id]['description']=$v_info['snippet']['description'];
                         $video_information[$single_video_id]['tags']=isset($v_info['snippet']['tags'])? $v_info['snippet']['tags']:'';
                         $video_information[$single_video_id]['categoryId']=$v_info['snippet']['categoryId'];
                         $video_information[$single_video_id]['liveBroadcastContent']=$v_info['snippet']['liveBroadcastContent'];


                         $video_information[$single_video_id]['duration']=$v_info['contentDetails']['duration'];
                         $video_information[$single_video_id]['dimension']=$v_info['contentDetails']['dimension'];
                         $video_information[$single_video_id]['definition']=$v_info['contentDetails']['definition'];
                         $video_information[$single_video_id]['caption']=$v_info['contentDetails']['caption'];
                         $video_information[$single_video_id]['licensedContent']=$v_info['contentDetails']['licensedContent'];
                         $video_information[$single_video_id]['projection']=$v_info['contentDetails']['projection'];


                         $video_information[$single_video_id]['viewCount']=$v_info['statistics']['viewCount'];
                         $video_information[$single_video_id]['likeCount']=$v_info['statistics']['likeCount'];
                         $video_information[$single_video_id]['dislikeCount']=$v_info['statistics']['dislikeCount'];
                         $video_information[$single_video_id]['favoriteCount']=$v_info['statistics']['favoriteCount'];
                         $video_information[$single_video_id]['commentCount']=$v_info['statistics']['commentCount'];
                     }

                    }while($next_page!='');

                    if(isset($video_information))
                    {                        
                        $channel_id = $value['id'];                    
                        $delete_where = array(
                            'user_id' => $this->user_id,
                            'channel_id' => $channel_id
                            );
                        $this->basic->delete_data('youtube_video_list',$delete_where);
                    
                        foreach ($video_information as $key => $value) {
                            $video_data = array(
                                'user_id' => $this->user_id,
                                'channel_id' => $channel_id,
                                'video_id' => $key,
                                'title' => $value['title'],
                                'image_link' => $value['thumbnails'],
                                'publish_time' => $value['publishedAt'],
                                'description' => $value['description'],
                                'tags' => json_encode($value['tags']),
                                'categoryId' => $value['categoryId'],
                                'liveBroadcastContent' => $value['liveBroadcastContent'],
                                'duration' => $value['duration'],
                                'dimension' => $value['dimension'],
                                'definition' => $value['definition'],
                                'caption' => $value['caption'],
                                'licensedContent' => $value['licensedContent'],
                                'projection' => $value['projection'],
                                'viewCount' => $value['viewCount'],
                                'likeCount' => $value['likeCount'],
                                'dislikeCount' => $value['dislikeCount'],
                                'favoriteCount' => $value['favoriteCount'],
                                'commentCount' => $value['commentCount']
                                );

                            $this->basic->insert_data('youtube_video_list',$video_data);
                        }
                    }

                }
            }
        }

        redirect('youtube_analytics/get_channel_list','Location');

        
    }




    public function get_channel_list()
    {
        $where['where'] = array('user_id'=>$this->user_id);
        $data['channel_list_info'] = $this->basic->get_data('youtube_channel_list',$where,$select='',$join='',$limit='',$start=NULL,$order_by='title asc');
        $data['page_title'] = 'Channel List';
        $data['body'] = 'youtube_analytics/channel_list';
        $this->_viewcontroller($data);
    }


    public function get_individual_channel_info($id=0,$start_date='',$end_date='')
    {
        if($id==0) exit();
        $where['where'] = array('youtube_channel_list.id'=>$id);
        $select = array('youtube_channel_list.id as table_id','access_token','refresh_token','channel_id','title');
        $join = array('youtube_channel_info'=>'youtube_channel_list.channel_info_id=youtube_channel_info.id,left');
        $channel_info = $this->basic->get_data('youtube_channel_list',$where,$select,$join);
        
        $this->session->set_userdata('individual_channel_access_token',$channel_info[0]['access_token']);
        $this->session->set_userdata('individual_channel_refresh_token',$channel_info[0]['refresh_token']);
        $channel_id = $channel_info[0]['channel_id'];


        if($start_date == '' && $end_date == '')
        {            
            $end_date = date("Y-m-d"); 
            $start_date = date('Y-m-d', strtotime("-28 days"));
        }
        else
        {
            $end_date = str_replace('-', '/', $end_date);
            $start_date = str_replace('-', '/', $start_date);
            $end_date = date("Y-m-d",strtotime($end_date));
            $start_date = date("Y-m-d",strtotime($start_date));
        }


        $dDiff = strtotime($end_date) - strtotime($start_date);
        $no_of_days = floor($dDiff/(60*60*24));



        // ***************************** views ********************
        $metrics = 'views';
        $dimension = 'day';
        $sort = 'day';
        $views_info = $this->google_youtube_login->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
        $views_info = (array)$views_info;

        $views = array();
        if(!empty($views_info['rows']))
        {
            foreach($views_info['rows'] as $value)
            {
                $views_raw[$value[0]] = $value[1];
            }


            for($i=0;$i<=$no_of_days;$i++)
            {
                $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

                if(isset($views_raw[$day_count])){
                    $views[$i]['date'] = $day_count;
                    $views[$i]['views'] = $views_raw[$day_count];
                }
                else
                {
                    $views[$i]['date'] = $day_count;
                    $views[$i]['views'] = 0;
                }
            }
        }
        $data['views'] = json_encode($views);
        // ************************* end of views ***********************



        // ***************************** unique_views ********************
        // $metrics = 'uniques';
        // $dimension = 'day';
        // $sort = 'day';
        // $unique_views_info = $this->google_youtube_login->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
        // $unique_views_info = (array)$unique_views_info;

        // $unique_views = array();
        // if(!empty($unique_views_info['rows']))
        // {
        //     foreach($unique_views_info['rows'] as $value)
        //     {
        //         $unique_views_raw[$value[0]] = $value[1];
        //     }

        //     for($i=0;$i<=$no_of_days;$i++)
        //     {
        //         $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

        //         if(isset($unique_views_raw[$day_count])){
        //             $unique_views[$i]['date'] = $day_count;
        //             $unique_views[$i]['unique_views'] = $unique_views_raw[$day_count];
        //         }
        //         else
        //         {
        //             $unique_views[$i]['date'] = $day_count;
        //             $unique_views[$i]['unique_views'] = 0;
        //         }
        //     }
        // }
        // $data['unique_views'] = json_encode($unique_views);
        // ************************* end of unique_views ***********************



        // ***************************** minute_watch ********************
        $metrics = 'estimatedMinutesWatched';
        $dimension = 'day';
        $sort = 'day';
        $minute_watch_info = $this->google_youtube_login->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
        $minute_watch_info = (array)$minute_watch_info;

        $minute_watch = array();
        if(!empty($minute_watch_info['rows']))
        {
            foreach($minute_watch_info['rows'] as $value)
            {
                $minute_watch_raw[$value[0]] = $value[1];
            }

            for($i=0;$i<=$no_of_days;$i++)
            {
                $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

                if(isset($minute_watch_raw[$day_count])){
                    $minute_watch[$i]['date'] = $day_count;
                    $minute_watch[$i]['minute_watch'] = $minute_watch_raw[$day_count];
                }
                else
                {
                    $minute_watch[$i]['date'] = $day_count;
                    $minute_watch[$i]['minute_watch'] = 0;
                }
            }
        }
        $data['minute_watch'] = json_encode($minute_watch);
        // ************************* end of minute_watch ***********************




        // ***************************** minute_watch ********************
        $metrics = 'averageViewDuration';
        $dimension = 'day';
        $sort = 'day';
        $second_watch_info = $this->google_youtube_login->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
        $second_watch_info = (array)$second_watch_info;

        $second_watch = array();
        if(!empty($second_watch_info['rows']))
        {
            foreach($second_watch_info['rows'] as $value)
            {
                $second_watch_raw[$value[0]] = $value[1];
            }

            for($i=0;$i<=$no_of_days;$i++)
            {
                $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

                if(isset($second_watch_raw[$day_count])){
                    $second_watch[$i]['date'] = $day_count;
                    $second_watch[$i]['second_watch'] = $second_watch_raw[$day_count];
                }
                else
                {
                    $second_watch[$i]['date'] = $day_count;
                    $second_watch[$i]['second_watch'] = 0;
                }
            }
        }
        $data['second_watch'] = json_encode($second_watch);
        // ************************* end of minute_watch ***********************



        // ***************************** subscriber_vs_unsubscriber ********************
        $metrics = 'subscribersGained';
        $dimension = 'day';
        $sort = 'day';
        $subscriber_info = $this->google_youtube_login->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
        $subscriber_info = (array)$subscriber_info;


        $metrics = 'subscribersLost';
        $unsubscriber_info = $this->google_youtube_login->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
        $unsubscriber_info = (array)$unsubscriber_info;


        $subscriber_vs_unsubscriber = array();
        if(!empty($subscriber_info['rows']) && !empty($unsubscriber_info['rows']))
        {
            for($i=0;$i<count($subscriber_info['rows']);$i++)
            {
                $subscriber_vs_unsubscriber_raw[$i]['date'] = $subscriber_info['rows'][$i][0];
                $subscriber_vs_unsubscriber_raw[$i]['subscriber'] = $subscriber_info['rows'][$i][1];
                $subscriber_vs_unsubscriber_raw[$i]['unsubscriber'] = $unsubscriber_info['rows'][$i][1];
            }

            foreach($subscriber_vs_unsubscriber_raw as $value)
            {
                $subscriber_vs_unsubscriber_raw_2[$value['date']]['subscriber'] = $value['subscriber'];
                $subscriber_vs_unsubscriber_raw_2[$value['date']]['unsubscriber'] = $value['unsubscriber'];
            }


            for($i=0;$i<=$no_of_days;$i++)
            {
                $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

                if(isset($subscriber_vs_unsubscriber_raw_2[$day_count])){
                    $subscriber_vs_unsubscriber[$i]['date'] = $day_count;
                    $subscriber_vs_unsubscriber[$i]['subscriber'] = $subscriber_vs_unsubscriber_raw_2[$day_count]['subscriber'];
                    $subscriber_vs_unsubscriber[$i]['unsubscriber'] = $subscriber_vs_unsubscriber_raw_2[$day_count]['unsubscriber'];
                }
                else
                {
                    $subscriber_vs_unsubscriber[$i]['date'] = $day_count;
                    $subscriber_vs_unsubscriber[$i]['subscriber'] = 0;
                    $subscriber_vs_unsubscriber[$i]['unsubscriber'] = 0;
                }
            }
        }

        $data['subscriber_vs_unsubscriber'] = json_encode($subscriber_vs_unsubscriber);

        
        // ************************* end of subscriber_vs_unsubscriber ***********************


        // ***************************** likes_vs_dislikes ********************
        $metrics = 'likes';
        $dimension = 'day';
        $sort = 'day';
        $likes_info = $this->google_youtube_login->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
        $likes_info = (array)$likes_info;

        $metrics = 'dislikes';
        $dislikes_info = $this->google_youtube_login->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
        $dislikes_info = (array)$dislikes_info;


        $likes_vs_dislikes = array();
        if(!empty($likes_info['rows']) && !empty($dislikes_info['rows']))
        {
            for($i=0;$i<count($likes_info['rows']);$i++)
            {
                $likes_vs_dislikes_raw[$i]['date'] = $likes_info['rows'][$i][0];
                $likes_vs_dislikes_raw[$i]['likes'] = $likes_info['rows'][$i][1];
                $likes_vs_dislikes_raw[$i]['dislikes'] = $dislikes_info['rows'][$i][1];
            }

            foreach($likes_vs_dislikes_raw as $value)
            {
                $likes_vs_dislikes_raw_2[$value['date']]['likes'] = $value['likes'];
                $likes_vs_dislikes_raw_2[$value['date']]['dislikes'] = $value['dislikes'];
            }


            for($i=0;$i<=$no_of_days;$i++)
            {
                $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

                if(isset($likes_vs_dislikes_raw_2[$day_count])){
                    $likes_vs_dislikes[$i]['date'] = $day_count;
                    $likes_vs_dislikes[$i]['likes'] = $likes_vs_dislikes_raw_2[$day_count]['likes'];
                    $likes_vs_dislikes[$i]['dislikes'] = $likes_vs_dislikes_raw_2[$day_count]['dislikes'];
                }
                else
                {
                    $likes_vs_dislikes[$i]['date'] = $day_count;
                    $likes_vs_dislikes[$i]['likes'] = 0;
                    $likes_vs_dislikes[$i]['dislikes'] = 0;
                }
            }
        }

        $data['likes_vs_dislikes'] = json_encode($likes_vs_dislikes);

        
        // ************************* end of likes_vs_dislikes ***********************



        // ***************************** video_added_vs_removed ********************
        $metrics = 'videosAddedToPlaylists';
        $dimension = 'day';
        $sort = 'day';
        $video_added_info = $this->google_youtube_login->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
        $video_added_info = (array)$video_added_info;


        $metrics = 'videosRemovedFromPlaylists';
        $video_removed_info = $this->google_youtube_login->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
        $video_removed_info = (array)$video_removed_info;

        $video_added_vs_removed = array();
        if(!empty($video_added_info['rows']) && !empty($video_removed_info['rows']))
        {
            for($i=0;$i<count($video_added_info['rows']);$i++)
            {
                $video_added_vs_removed_raw[$i]['date'] = $video_added_info['rows'][$i][0];
                $video_added_vs_removed_raw[$i]['added'] = $video_added_info['rows'][$i][1];
                $video_added_vs_removed_raw[$i]['removed'] = $video_removed_info['rows'][$i][1];
            }

            foreach($video_added_vs_removed_raw as $value)
            {
                $video_added_vs_removed_raw_2[$value['date']]['added'] = $value['added'];
                $video_added_vs_removed_raw_2[$value['date']]['removed'] = $value['removed'];
            }


            for($i=0;$i<=$no_of_days;$i++)
            {
                $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

                if(isset($video_added_vs_removed_raw_2[$day_count])){
                    $video_added_vs_removed[$i]['date'] = $day_count;
                    $video_added_vs_removed[$i]['added'] = $video_added_vs_removed_raw_2[$day_count]['added'];
                    $video_added_vs_removed[$i]['removed'] = $video_added_vs_removed_raw_2[$day_count]['removed'];
                }
                else
                {
                    $video_added_vs_removed[$i]['date'] = $day_count;
                    $video_added_vs_removed[$i]['added'] = 0;
                    $video_added_vs_removed[$i]['removed'] = 0;
                }
            }

        }

        $data['video_added_vs_removed'] = json_encode($video_added_vs_removed);

        
        // ************************* end of video_added_vs_removed ***********************


        // ***************************** comments ********************
        $metrics = 'comments';
        $dimension = 'day';
        $sort = 'day';
        $comments_info = $this->google_youtube_login->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
        $comments_info = (array)$comments_info;

        $comments = array();
        if(!empty($comments_info['rows']))
        {
            foreach($comments_info['rows'] as $value)
            {
                $comments_raw[$value[0]] = $value[1];
            }

            for($i=0;$i<=$no_of_days;$i++)
            {
                $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

                if(isset($comments_raw[$day_count])){
                    $comments[$i]['date'] = $day_count;
                    $comments[$i]['comments'] = $comments_raw[$day_count];
                }
                else
                {
                    $comments[$i]['date'] = $day_count;
                    $comments[$i]['comments'] = 0;
                }
            }
        }
        $data['comments'] = json_encode($comments);
        // ************************* end of comments ***********************


        // ***************************** shares ********************
        $metrics = 'shares';
        $dimension = 'day';
        $sort = 'day';
        $shares_info = $this->google_youtube_login->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
        $shares_info = (array)$shares_info;

        $shares = array();
        if(!empty($shares_info['rows']))
        {
            foreach($shares_info['rows'] as $value)
            {
                $shares_raw[$value[0]] = $value[1];
            }

            for($i=0;$i<=$no_of_days;$i++)
            {
                $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

                if(isset($shares_raw[$day_count])){
                    $shares[$i]['date'] = $day_count;
                    $shares[$i]['shares'] = $shares_raw[$day_count];
                }
                else
                {
                    $shares[$i]['date'] = $day_count;
                    $shares[$i]['shares'] = 0;
                }
            }
        }
        $data['shares'] = json_encode($shares);
        // ************************* end of shares ***********************


        // ***************************** country map ********************
        $metrics = 'views';
        $dimension = 'country';
        $sort = '-views';
        $max_result = 1000;
        $country_map_info = $this->google_youtube_login->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result,$start_date,$end_date);
        $country_map_info = (array)$country_map_info;

        $country_map = array();
        $country_names_array = $this->get_country_names();
        if(!empty($country_map_info['rows']))
        {
            $i=0;
            $a = array('Country','Views');
            $country_map[$i] = $a;
            foreach($country_map_info['rows'] as $value)
            {
                $i++;
                $temp = array();
                $temp[] = isset($country_names_array[$value[0]]) ? $country_names_array[$value[0]]:$value[0];
                $temp[] = $value[1];
                $country_map[$i] = $temp;
            }
        }
        
        $data['country_map'] = htmlspecialchars(json_encode($country_map), ENT_QUOTES, 'UTF-8');
        // ************************* end of country map ***********************

 

        // ***************************** top 10 country ********************
        $metrics = 'views';
        $dimension = 'country';
        $sort = '-views';
        $max_result = 10;
        $top_ten_country_info = $this->google_youtube_login->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result,$start_date,$end_date);
        $top_ten_country_info = (array)$top_ten_country_info;
        $top_ten_country_chart_data = array();

        $top_ten_country_table = "<table class='table table-bordered table-hover table-striped'><tr><th>Sl</th><th>Country</th><th>Views</th></tr>";
        if(!empty($top_ten_country_info['rows']))
        {
            $i = 0;
            $total_views = 0;
            foreach($top_ten_country_info['rows'] as $value)
            {
                $i++;
                $country = isset($country_names_array[$value[0]]) ? $country_names_array[$value[0]]:$value[0];
                $top_ten_country_table .= "<tr><td>".$i."</td><td>".$country."</td><td>".$value[1]."</td></tr>";
                $total_views = $total_views+$value[1];
            }
            $top_ten_country_table .= "</table>";


            $color_array = array("#FF8B6B","#D75EF2","#78ED78","#D31319","#798C0E","#FC749F","#D3C421","#1DAF92","#5832BA","#FC5B20","#EDED28","#E27263","#E5C77B","#B7F93B","#A81538", "#087F24","#9040CE","#872904","#DD5D18","#FBFF0F");
            $i=0;
            $color_count=0;
            foreach($top_ten_country_info['rows'] as $value)
            {
                if($total_views>0)
                $top_ten_country_chart_data[$i]['value'] = number_format($value[1]*100/$total_views,2);
                else  $top_ten_country_chart_data[$i]['value'] = 0;
                $top_ten_country_chart_data[$i]['color'] = $color_array[$color_count];
                $top_ten_country_chart_data[$i]['highlight'] = $color_array[$color_count];
                $top_ten_country_chart_data[$i]['label'] = isset($country_names_array[$value[0]]) ? $country_names_array[$value[0]]:$value[0];
                $i++;
                $color_count++;
                if($color_count>=count($color_array)) $color_count=0;
            }
        }
        $data['top_ten_country_table'] = $top_ten_country_table;
        $data['top_ten_country_chart_data'] = json_encode($top_ten_country_chart_data);
        // ************************* end of top 10 country ***********************



        // ***************************** gender percentage ********************
        $metrics = 'viewerPercentage';
        $dimension = 'gender';
        $sort = '';
        $max_result = 10;
        $gender_percentage_info = $this->google_youtube_login->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result,$start_date,$end_date);
        $gender_percentage_info = (array)$gender_percentage_info;
        $gender_percentage = array();

        $gender_percentage_list = "";
        if(!empty($gender_percentage_info['rows']))
        {
            $color_array = array("#E27263","#E5C77B");
            $i=0;
            foreach($gender_percentage_info['rows'] as $value)
            {
                $gender_percentage[$i]['value'] = $value[1];
                $gender_percentage[$i]['color'] = $color_array[$i];
                $gender_percentage[$i]['highlight'] = $color_array[$i];
                $gender_percentage[$i]['label'] = $value[0];

                $gender_percentage_list .= '<li><i class="fa fa-circle-o" style="color: '.$color_array[$i].';"></i> '.$value[0].' : '.$value[1].' %</li>';
                $i++;
            }
        }
        $data['gender_percentage_list'] = $gender_percentage_list;
        $data['gender_percentage'] = json_encode($gender_percentage);
        // ************************* gender percentage ***********************



        // ***************************** age group ********************
        $metrics = 'viewerPercentage';
        $dimension = 'ageGroup';
        $sort = '';
        $max_result = 10;
        $age_group_info = $this->google_youtube_login->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result,$start_date,$end_date);
        $age_group_info = (array)$age_group_info;
        $age_group = array();

        $age_group_list = "";
        if(!empty($age_group_info['rows']))
        {
            $color_array = array("#FF8B6B","#D75EF2","#78ED78","#D31319","#798C0E","#FC749F","#D3C421","#1DAF92","#5832BA","#FC5B20","#EDED28","#E27263","#E5C77B","#B7F93B","#A81538", "#087F24","#9040CE","#872904","#DD5D18","#FBFF0F");
            $color_array = array_reverse($color_array);
            $i=0;
            $color_count=0;
            foreach($age_group_info['rows'] as $value)
            {
                $age_group[$i]['value'] = $value[1];
                $age_group[$i]['color'] = $color_array[$color_count];
                $age_group[$i]['highlight'] = $color_array[$color_count];
                $age_group[$i]['label'] = $value[0];

                $age_group_list .= '<li><i class="fa fa-circle-o" style="color: '.$color_array[$color_count].';"></i> '.$value[0].' : '.$value[1].' %</li>';
                $i++;
                $color_count++;
                if($color_count>=count($color_array)) $color_count=0;
            }
        }
        $data['age_group_list'] = $age_group_list;
        $data['age_group'] = json_encode($age_group);
        // ************************* age group ***********************



        // ***************************** views ********************
        $metrics = 'annotationImpressions';
        $dimension = 'day';
        $sort = 'day';
        $annotation_impression_info = $this->google_youtube_login->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
        $annotation_impression_info = (array)$annotation_impression_info;

        $annotation_impression = array();
        if(!empty($annotation_impression_info['rows']))
        {
            foreach($annotation_impression_info['rows'] as $value)
            {
                $annotation_impression_raw[$value[0]] = $value[1];
            }

            for($i=0;$i<=$no_of_days;$i++)
            {
                $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

                if(isset($annotation_impression_raw[$day_count])){
                    $annotation_impression[$i]['date'] = $day_count;
                    $annotation_impression[$i]['annotation_impressions'] = $annotation_impression_raw[$day_count];
                }
                else
                {
                    $annotation_impression[$i]['date'] = $day_count;
                    $annotation_impression[$i]['annotation_impressions'] = 0;
                }
            }
        }
        $data['annotation_impressions'] = json_encode($annotation_impression);
        // ************************* end of views ***********************



        // ***************************** annotation close and click impressions ********************
        $metrics = 'annotationClosableImpressions';
        $dimension = 'day';
        $sort = 'day';
        $close_impression_info = $this->google_youtube_login->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
        $close_impression_info = (array)$close_impression_info;


        $metrics = 'annotationClickableImpressions';
        $click_impression_info = $this->google_youtube_login->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
        $click_impression_info = (array)$click_impression_info;

        $annotation_close_click_impressions = array();
        if(!empty($close_impression_info['rows']) && !empty($click_impression_info['rows']))
        {
            for($i=0;$i<count($close_impression_info['rows']);$i++)
            {
                $annotation_close_click_impressions_raw[$i]['date'] = $close_impression_info['rows'][$i][0];
                $annotation_close_click_impressions_raw[$i]['click_impression'] = $click_impression_info['rows'][$i][1];
                $annotation_close_click_impressions_raw[$i]['close_impression'] = $close_impression_info['rows'][$i][1];
            }

            foreach($annotation_close_click_impressions_raw as $value)
            {
                $annotation_close_click_impressions_raw_2[$value['date']]['click_impression'] = $value['click_impression'];
                $annotation_close_click_impressions_raw_2[$value['date']]['close_impression'] = $value['close_impression'];
            }


            for($i=0;$i<=$no_of_days;$i++)
            {
                $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

                if(isset($annotation_close_click_impressions_raw_2[$day_count])){
                    $annotation_close_click_impressions[$i]['date'] = $day_count;
                    $annotation_close_click_impressions[$i]['click_impression'] = $annotation_close_click_impressions_raw_2[$day_count]['click_impression'];
                    $annotation_close_click_impressions[$i]['close_impression'] = $annotation_close_click_impressions_raw_2[$day_count]['close_impression'];
                }
                else
                {
                    $annotation_close_click_impressions[$i]['date'] = $day_count;
                    $annotation_close_click_impressions[$i]['click_impression'] = 0;
                    $annotation_close_click_impressions[$i]['close_impression'] = 0;
                }
            }
        }

        $data['annotation_close_click_impressions'] = json_encode($annotation_close_click_impressions);

        
        // ************************* end of annotation close and click impressions ***********************


        
        // ***************************** annotation close and click impressions ********************
        $metrics = 'annotationCloses';
        $dimension = 'day';
        $sort = 'day';
        $annotation_close_info = $this->google_youtube_login->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
        $annotation_close_info = (array)$annotation_close_info;


        $metrics = 'annotationClicks';
        $annotation_click_info = $this->google_youtube_login->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result='',$start_date,$end_date);
        $annotation_click_info = (array)$annotation_click_info;

        $annotation_clicks_closes = array();
        if(!empty($annotation_close_info['rows']) && !empty($annotation_click_info['rows']))
        {
            for($i=0;$i<count($annotation_close_info['rows']);$i++)
            {
                $annotation_clicks_closes_raw[$i]['date'] = $annotation_close_info['rows'][$i][0];
                $annotation_clicks_closes_raw[$i]['annotation_click'] = $annotation_click_info['rows'][$i][1];
                $annotation_clicks_closes_raw[$i]['annotation_close'] = $annotation_close_info['rows'][$i][1];
            }

            foreach($annotation_clicks_closes_raw as $value)
            {
                $annotation_clicks_closes_raw_2[$value['date']]['annotation_click'] = $value['annotation_click'];
                $annotation_clicks_closes_raw_2[$value['date']]['annotation_close'] = $value['annotation_close'];
            }


            for($i=0;$i<=$no_of_days;$i++)
            {
                $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

                if(isset($annotation_clicks_closes_raw_2[$day_count])){
                    $annotation_clicks_closes[$i]['date'] = $day_count;
                    $annotation_clicks_closes[$i]['annotation_click'] = $annotation_clicks_closes_raw_2[$day_count]['annotation_click'];
                    $annotation_clicks_closes[$i]['annotation_close'] = $annotation_clicks_closes_raw_2[$day_count]['annotation_close'];
                }
                else
                {
                    $annotation_clicks_closes[$i]['date'] = $day_count;
                    $annotation_clicks_closes[$i]['annotation_click'] = 0;
                    $annotation_clicks_closes[$i]['annotation_close'] = 0;
                }
            }
        }

        $data['annotation_clicks_closes'] = json_encode($annotation_clicks_closes);

        
        // ************************* end of annotation close and click impressions ***********************



        // ***************************** top 10 country ********************
        $metrics = 'views';
        $dimension = 'deviceType';
        $sort = '';
        $max_result = '';
        $device_type_info = $this->google_youtube_login->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result,$start_date,$end_date);
        $device_type_info = (array)$device_type_info;
        $device_type_chart_data = array();

        $device_type_table = "<table class='table table-bordered table-hover table-striped'><tr><th>Sl</th><th>Device Type</th><th>Total</th></tr>";
        if(!empty($device_type_info['rows']))
        {
            $i = 0;
            $total_views = 0;
            foreach($device_type_info['rows'] as $value)
            {
                $i++;
                $device = ucfirst($value[0]);
                $device_type_table .= "<tr><td>".$i."</td><td>".$device."</td><td>".$value[1]."</td></tr>";
                $total_views = $total_views+$value[1];
            }
            $device_type_table .= "</table>";


            $color_array = array("#5832BA","#FC5B20","#EDED28","#E27263","#E5C77B","#B7F93B","#A81538", "#087F24","#9040CE","#872904","#DD5D18","#FBFF0F");
            $i=0;
            $color_count=0;
            foreach($device_type_info['rows'] as $value)
            {
                $device_type_chart_data[$i]['value'] = number_format($value[1]*100/$total_views,2);
                $device_type_chart_data[$i]['color'] = $color_array[$color_count];
                $device_type_chart_data[$i]['highlight'] = $color_array[$color_count];
                $device_type_chart_data[$i]['label'] = ucfirst($value[0]);
                $i++;
                $color_count++;
                if($color_count>=count($color_array)) $color_count=0;
            }
        }
        $data['device_type_table'] = $device_type_table;
        $data['device_type_chart_data'] = json_encode($device_type_chart_data);
        // ************************* end of top 10 country ***********************




        // ***************************** top 10 country ********************
        $metrics = 'views';
        $dimension = 'operatingSystem';
        $sort = '';
        $max_result = '';
        $operating_system_info = $this->google_youtube_login->get_channel_analytics($channel_id,$metrics,$dimension,$sort,$max_result,$start_date,$end_date);
        $operating_system_info = (array)$operating_system_info;
        $operating_system_chart_data = array();

        $operating_system_table = "<table class='table table-bordered table-hover table-striped'><tr><th>Sl</th><th>Operating System</th><th>Total</th></tr>";
        if(!empty($operating_system_info['rows']))
        {
            $i = 0;
            $total_views = 0;
            foreach($operating_system_info['rows'] as $value)
            {
                $i++;
                $device = ucfirst($value[0]);
                $operating_system_table .= "<tr><td>".$i."</td><td>".$device."</td><td>".$value[1]."</td></tr>";
                $total_views = $total_views+$value[1];
            }
            $operating_system_table .= "</table>";


            $color_array = array("#5832BA","#FC5B20","#EDED28","#E27263","#E5C77B","#B7F93B","#A81538", "#087F24","#9040CE","#872904","#DD5D18","#FBFF0F");
            $color_array = array_reverse($color_array);
            $i=0;
            $color_count=0;
            foreach($operating_system_info['rows'] as $value)
            {
                $operating_system_chart_data[$i]['value'] = number_format($value[1]*100/$total_views,2);
                $operating_system_chart_data[$i]['color'] = $color_array[$color_count];
                $operating_system_chart_data[$i]['highlight'] = $color_array[$color_count];
                $operating_system_chart_data[$i]['label'] = ucfirst($value[0]);
                $i++;
                $color_count++;
                if($color_count>=count($color_array)) $color_count=0;
            }
        }
        $data['operating_system_table'] = $operating_system_table;
        $data['operating_system_chart_data'] = json_encode($operating_system_chart_data);
        // ************************* end of top 10 country ***********************


        // echo "<pre>"; print_r($country_map); exit();
        $this->session->unset_userdata('individual_channel_access_token');
        $this->session->unset_userdata('individual_channel_refresh_token');

        $data['title'] = $channel_info[0]['title'];
        $data['from_date'] = date("Y-M-d", strtotime($start_date));
        $data['to_date'] = date("Y-M-d", strtotime($end_date));
        $data['table_id'] = $channel_info[0]['table_id'];
        $data['body'] = 'youtube_analytics/channel_details';
        $data['page_title'] = 'Channel Analytics';
        // echo "<pre>";
        // print_r($data); exit();
        $this->_viewcontroller($data);


    }


    public function get_all_video_list()
    {
        $data['page_title'] = "Video List";
        $data['body'] = 'youtube_analytics/all_video_list_grid';
        $this->_viewcontroller($data);
    }


    public function get_all_video_list_data()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }
        $page = isset($_POST['page']) ? intval($_POST['page']) : 15;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 5;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'id';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'ASC';

        $video_title      = trim($this->input->post("video_title", true));
        $is_searched = $this->input->post('is_searched', true);


        if ($is_searched) {            
            $this->session->set_userdata('youtube_all_video_title', $video_title);
        }

        $search_video_title = $this->session->userdata('youtube_all_video_title');

        $where_simple=array();

        if ($search_video_title) {
            $where_simple['title like ']    = "%".$search_video_title."%";
        }
        
        $where_simple['user_id'] = $this->user_id;
        
        $where  = array('where'=>$where_simple);

        $order_by_str=$sort." ".$order;

        $offset = ($page-1)*$rows;
        $result = array();

        $table = "youtube_video_list";

        $info = $this->basic->get_data($table, $where, $select='', $join='', $limit=$rows, $start=$offset, $order_by=$order_by_str, $group_by='');

        $total_rows_array = $this->basic->count_row($table, $where, $count="id");      

        $total_result = $total_rows_array[0]['total_rows'];

        echo convert_to_grid_data($info, $total_result);
    }


    public function playlist_item($access_token,$playlist_id,$next_page='')
    {    

       $url ="https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId={$playlist_id}&mine=true&access_token={$access_token}&maxResults=10&pageToken={$next_page}";
       return $this->google_youtube_login->get_curl($url);

    }



    public function get_channel_video_list($channel_list_id=0)
    {
        if($channel_list_id==0) exit();

        $where['where'] = array(
            'user_id' => $this->user_id,
            'id' => $channel_list_id
            );
        $channel_id = $this->basic->get_data('youtube_channel_list',$where);
        $data['channel_id'] = $channel_id[0]['channel_id'];
        $data['channel_title'] = $channel_id[0]['title'];
        $data['page_title'] = "Video List";
        $data['body'] = 'youtube_analytics/video_list_grid';
        $this->_viewcontroller($data);
    }


    public function get_channel_video_list_data($channel_id=0)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }
        $page = isset($_POST['page']) ? intval($_POST['page']) : 15;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 5;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'id';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'ASC';

        $video_title      = trim($this->input->post("video_title", true));
        $is_searched = $this->input->post('is_searched', true);


        if ($is_searched) {            
            $this->session->set_userdata('youtube_video_list_video_title', $video_title);
        }

        $search_video_title = $this->session->userdata('youtube_video_list_video_title');

        $where_simple=array();

        if ($search_video_title) {
            $where_simple['title like ']    = "%".$search_video_title."%";
        }
        
        $where_simple['user_id'] = $this->user_id;
        $where_simple['channel_id'] = $channel_id;
        
        $where  = array('where'=>$where_simple);

        $order_by_str=$sort." ".$order;

        $offset = ($page-1)*$rows;
        $result = array();

        $table = "youtube_video_list";

        $info = $this->basic->get_data($table, $where, $select='', $join='', $limit=$rows, $start=$offset, $order_by=$order_by_str, $group_by='');

        $total_rows_array = $this->basic->count_row($table, $where, $count="id");      

        $total_result = $total_rows_array[0]['total_rows'];

        echo convert_to_grid_data($info, $total_result);
    }



    public function get_video_details($video_id=0,$start_date='',$end_date='')
    {
        $id = $video_id;
        if($id==0) exit();
        $where['where'] = array('youtube_video_list.id'=>$id);
        $select = array('youtube_video_list.id as id','access_token','refresh_token','youtube_video_list.channel_id as channel_id','youtube_video_list.title as title','video_id');
        $join = array(
            'youtube_channel_list'=>'youtube_video_list.channel_id=youtube_channel_list.channel_id,left',
            'youtube_channel_info'=>'youtube_channel_list.channel_info_id=youtube_channel_info.id,left'
            );
        $channel_info = $this->basic->get_data('youtube_video_list',$where,$select,$join);
        
        $this->session->set_userdata('individual_video_access_token',$channel_info[0]['access_token']);
        $this->session->set_userdata('individual_video_refresh_token',$channel_info[0]['refresh_token']);
        $channel_id = $channel_info[0]['channel_id'];
        $video_id = $channel_info[0]['video_id'];



        if($start_date == '' && $end_date == '')
        {            
            $end_date = date("Y-m-d"); 
            $start_date = date('Y-m-d', strtotime("-28 days"));
        }
        else
        {
            $end_date = str_replace('-', '/', $end_date);
            $start_date = str_replace('-', '/', $start_date);
            $end_date = date("Y-m-d",strtotime($end_date));
            $start_date = date("Y-m-d",strtotime($start_date));
        }


        $dDiff = strtotime($end_date) - strtotime($start_date);
        $no_of_days = floor($dDiff/(60*60*24));

        
        // ***************************** views ********************
        $metrics = 'views';
        $dimension = 'day';
        $sort = 'day';
        $filter = "video=={$video_id}";
        $max_result = '';
        $views_info = $this->google_youtube_login->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
        $views_info = (array)$views_info;

        $views = array();
        if(!empty($views_info['rows']))
        {
            foreach($views_info['rows'] as $value)
            {
                $views_raw[$value[0]] = $value[1];
            }


            for($i=0;$i<=$no_of_days;$i++)
            {
                $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

                if(isset($views_raw[$day_count])){
                    $views[$i]['date'] = $day_count;
                    $views[$i]['views'] = $views_raw[$day_count];
                }
                else
                {
                    $views[$i]['date'] = $day_count;
                    $views[$i]['views'] = 0;
                }
            }
            
            
        }
        $data['views'] = json_encode($views);
        // ************************* end of views ***********************



        // ***************************** unique_views ********************
        // $metrics = 'uniques';
        // $dimension = 'day';
        // $sort = 'day';
        // $filter = "video=={$video_id}";
        // $max_result = '';
        // $unique_views_info = $this->google_youtube_login->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
        // $unique_views_info = (array)$unique_views_info;

        // $unique_views = array();
        // if(!empty($unique_views_info['rows']))
        // {
        //     foreach($unique_views_info['rows'] as $value)
        //     {
        //         $unique_views_raw[$value[0]] = $value[1];
        //     }

        //     for($i=0;$i<=$no_of_days;$i++)
        //     {
        //         $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

        //         if(isset($unique_views_raw[$day_count])){
        //             $unique_views[$i]['date'] = $day_count;
        //             $unique_views[$i]['unique_views'] = $unique_views_raw[$day_count];
        //         }
        //         else
        //         {
        //             $unique_views[$i]['date'] = $day_count;
        //             $unique_views[$i]['unique_views'] = 0;
        //         }
        //     }
        // }
        // $data['unique_views'] = json_encode($unique_views);
        // ************************* end of unique_views ***********************



        // ***************************** minute_watch ********************
        $metrics = 'estimatedMinutesWatched';
        $dimension = 'day';
        $sort = 'day';
        $filter = "video=={$video_id}";
        $max_result = '';
        $minute_watch_info = $this->google_youtube_login->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
        $minute_watch_info = (array)$minute_watch_info;

        $minute_watch = array();
        if(!empty($minute_watch_info['rows']))
        {
            foreach($minute_watch_info['rows'] as $value)
            {
                $minute_watch_raw[$value[0]] = $value[1];
            }

            for($i=0;$i<=$no_of_days;$i++)
            {
                $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

                if(isset($minute_watch_raw[$day_count])){
                    $minute_watch[$i]['date'] = $day_count;
                    $minute_watch[$i]['minute_watch'] = $minute_watch_raw[$day_count];
                }
                else
                {
                    $minute_watch[$i]['date'] = $day_count;
                    $minute_watch[$i]['minute_watch'] = 0;
                }
            }
        }
        $data['minute_watch'] = json_encode($minute_watch);
        // ************************* end of minute_watch ***********************



        // ***************************** minute_watch ********************
        $metrics = 'averageViewDuration';
        $dimension = 'day';
        $sort = 'day';
        $filter = "video=={$video_id}";
        $max_result = '';
        $second_watch_info = $this->google_youtube_login->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
        $second_watch_info = (array)$second_watch_info;

        $second_watch = array();
        if(!empty($second_watch_info['rows']))
        {
            foreach($second_watch_info['rows'] as $value)
            {
                $second_watch_raw[$value[0]] = $value[1];
            }

            for($i=0;$i<=$no_of_days;$i++)
            {
                $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

                if(isset($second_watch_raw[$day_count])){
                    $second_watch[$i]['date'] = $day_count;
                    $second_watch[$i]['second_watch'] = $second_watch_raw[$day_count];
                }
                else
                {
                    $second_watch[$i]['date'] = $day_count;
                    $second_watch[$i]['second_watch'] = 0;
                }
            }

        }
        $data['second_watch'] = json_encode($second_watch);
        // ************************* end of minute_watch ***********************




        // ***************************** subscriber_vs_unsubscriber ********************
        $metrics = 'subscribersGained';
        $dimension = 'day';
        $sort = 'day';
        $filter = "video=={$video_id}";
        $max_result = '';
        $subscriber_info = $this->google_youtube_login->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
        $subscriber_info = (array)$subscriber_info;


        $metrics = 'subscribersLost';
        $unsubscriber_info = $this->google_youtube_login->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
        $unsubscriber_info = (array)$unsubscriber_info;

        $subscriber_vs_unsubscriber = array();
        if(!empty($subscriber_info['rows']) && !empty($unsubscriber_info['rows']))
        {            
            for($i=0;$i<count($subscriber_info['rows']);$i++)
            {
                $subscriber_vs_unsubscriber_raw[$i]['date'] = $subscriber_info['rows'][$i][0];
                $subscriber_vs_unsubscriber_raw[$i]['subscriber'] = $subscriber_info['rows'][$i][1];
                $subscriber_vs_unsubscriber_raw[$i]['unsubscriber'] = $unsubscriber_info['rows'][$i][1];
            }

            foreach($subscriber_vs_unsubscriber_raw as $value)
            {
                $subscriber_vs_unsubscriber_raw_2[$value['date']]['subscriber'] = $value['subscriber'];
                $subscriber_vs_unsubscriber_raw_2[$value['date']]['unsubscriber'] = $value['unsubscriber'];
            }


            for($i=0;$i<=$no_of_days;$i++)
            {
                $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

                if(isset($subscriber_vs_unsubscriber_raw_2[$day_count])){
                    $subscriber_vs_unsubscriber[$i]['date'] = $day_count;
                    $subscriber_vs_unsubscriber[$i]['subscriber'] = $subscriber_vs_unsubscriber_raw_2[$day_count]['subscriber'];
                    $subscriber_vs_unsubscriber[$i]['unsubscriber'] = $subscriber_vs_unsubscriber_raw_2[$day_count]['unsubscriber'];
                }
                else
                {
                    $subscriber_vs_unsubscriber[$i]['date'] = $day_count;
                    $subscriber_vs_unsubscriber[$i]['subscriber'] = 0;
                    $subscriber_vs_unsubscriber[$i]['unsubscriber'] = 0;
                }
            }



        }

        $data['subscriber_vs_unsubscriber'] = json_encode($subscriber_vs_unsubscriber);
        
        // ************************* end of subscriber_vs_unsubscriber ***********************




        // ***************************** likes_vs_dislikes ********************
        $metrics = 'likes';
        $dimension = 'day';
        $sort = 'day';
        $filter = "video=={$video_id}";
        $max_result = '';
        $likes_info = $this->google_youtube_login->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
        $likes_info = (array)$likes_info;

        $metrics = 'dislikes';
        $dislikes_info = $this->google_youtube_login->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
        $dislikes_info = (array)$dislikes_info;

        $likes_vs_dislikes = array();
        if(!empty($likes_info['rows']) && !empty($dislikes_info['rows']))
        {
            for($i=0;$i<count($likes_info['rows']);$i++)
            {
                $likes_vs_dislikes_raw[$i]['date'] = $likes_info['rows'][$i][0];
                $likes_vs_dislikes_raw[$i]['likes'] = $likes_info['rows'][$i][1];
                $likes_vs_dislikes_raw[$i]['dislikes'] = $dislikes_info['rows'][$i][1];
            }

            foreach($likes_vs_dislikes_raw as $value)
            {
                $likes_vs_dislikes_raw_2[$value['date']]['likes'] = $value['likes'];
                $likes_vs_dislikes_raw_2[$value['date']]['dislikes'] = $value['dislikes'];
            }


            for($i=0;$i<=$no_of_days;$i++)
            {
                $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

                if(isset($likes_vs_dislikes_raw_2[$day_count])){
                    $likes_vs_dislikes[$i]['date'] = $day_count;
                    $likes_vs_dislikes[$i]['likes'] = $likes_vs_dislikes_raw_2[$day_count]['likes'];
                    $likes_vs_dislikes[$i]['dislikes'] = $likes_vs_dislikes_raw_2[$day_count]['dislikes'];
                }
                else
                {
                    $likes_vs_dislikes[$i]['date'] = $day_count;
                    $likes_vs_dislikes[$i]['likes'] = 0;
                    $likes_vs_dislikes[$i]['dislikes'] = 0;
                }
            }
        }

        $data['likes_vs_dislikes'] = json_encode($likes_vs_dislikes);
        
        // ************************* end of likes_vs_dislikes ***********************




        // ***************************** comments ********************
        $metrics = 'comments';
        $dimension = 'day';
        $sort = 'day';
        $filter = "video=={$video_id}";
        $max_result = '';
        $comments_info = $this->google_youtube_login->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
        $comments_info = (array)$comments_info;

        $comments = array();
        if(!empty($comments_info['rows']))
        {
            foreach($comments_info['rows'] as $value)
            {
                $comments_raw[$value[0]] = $value[1];
            }

            for($i=0;$i<=$no_of_days;$i++)
            {
                $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

                if(isset($comments_raw[$day_count])){
                    $comments[$i]['date'] = $day_count;
                    $comments[$i]['comments'] = $comments_raw[$day_count];
                }
                else
                {
                    $comments[$i]['date'] = $day_count;
                    $comments[$i]['comments'] = 0;
                }
            }
        }
        $data['comments'] = json_encode($comments);
        // ************************* end of comments ***********************



        // ***************************** shares ********************
        $metrics = 'shares';
        $dimension = 'day';
        $sort = 'day';
        $filter = "video=={$video_id}";
        $max_result = '';
        $shares_info = $this->google_youtube_login->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
        $shares_info = (array)$shares_info;

        $shares = array();
        if(!empty($shares_info['rows']))
        {
            foreach($shares_info['rows'] as $value)
            {
                $shares_raw[$value[0]] = $value[1];
            }

            for($i=0;$i<=$no_of_days;$i++)
            {
                $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

                if(isset($shares_raw[$day_count])){
                    $shares[$i]['date'] = $day_count;
                    $shares[$i]['shares'] = $shares_raw[$day_count];
                }
                else
                {
                    $shares[$i]['date'] = $day_count;
                    $shares[$i]['shares'] = 0;
                }
            }
        }
        $data['shares'] = json_encode($shares);
        // ************************* end of shares ***********************




        // ***************************** country map ********************
        $metrics = 'views';
        $dimension = 'country';
        $sort = '-views';
        $filter = "video=={$video_id}";
        $max_result = 1000;
        $country_map_info = $this->google_youtube_login->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
        $country_map_info = (array)$country_map_info;

        $country_map = array();
        $country_names_array = $this->get_country_names();
        if(!empty($country_map_info['rows']))
        {
            $i=0;
            $a = array('Country','Views');
            $country_map[$i] = $a;
            foreach($country_map_info['rows'] as $value)
            {
                $i++;
                $temp = array();
                $temp[] = isset($country_names_array[$value[0]]) ? $country_names_array[$value[0]]:$value[0];
                $temp[] = $value[1];
                $country_map[$i] = $temp;
            }
        }
        
        $data['country_map'] = htmlspecialchars(json_encode($country_map), ENT_QUOTES, 'UTF-8');
        // ************************* end of country map ***********************



        // ***************************** top 10 country ********************
        $metrics = 'views';
        $dimension = 'country';
        $sort = '-views';
        $filter = "video=={$video_id}";
        $max_result = 10;
        $top_ten_country_info = $this->google_youtube_login->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
        $top_ten_country_info = (array)$top_ten_country_info;
        $top_ten_country_chart_data = array();

        $top_ten_country_table = "<table class='table table-bordered table-hover table-striped'><tr><th>Sl</th><th>Country</th><th>Views</th></tr>";
        if(!empty($top_ten_country_info['rows']))
        {
            $i = 0;
            $total_views = 0;
            foreach($top_ten_country_info['rows'] as $value)
            {
                $i++;
                $country = isset($country_names_array[$value[0]]) ? $country_names_array[$value[0]]:$value[0];
                $top_ten_country_table .= "<tr><td>".$i."</td><td>".$country."</td><td>".$value[1]."</td></tr>";
                $total_views = $total_views+$value[1];
            }
            $top_ten_country_table .= "</table>";


            $color_array = array("#FF8B6B","#D75EF2","#78ED78","#D31319","#798C0E","#FC749F","#D3C421","#1DAF92","#5832BA","#FC5B20","#EDED28","#E27263","#E5C77B","#B7F93B","#A81538", "#087F24","#9040CE","#872904","#DD5D18","#FBFF0F");
            $i=0;
            $color_count=0;
            foreach($top_ten_country_info['rows'] as $value)
            {
                if($total_views>0)
                $top_ten_country_chart_data[$i]['value'] = number_format($value[1]*100/$total_views,2);
                else  $top_ten_country_chart_data[$i]['value'] = 0;
                $top_ten_country_chart_data[$i]['color'] = $color_array[$color_count];
                $top_ten_country_chart_data[$i]['highlight'] = $color_array[$color_count];
                $top_ten_country_chart_data[$i]['label'] = isset($country_names_array[$value[0]]) ? $country_names_array[$value[0]]:$value[0];
                $i++;
                $color_count++;
                if($color_count>=count($color_array)) $color_count=0;
            }
        }
        $data['top_ten_country_table'] = $top_ten_country_table;
        $data['top_ten_country_chart_data'] = json_encode($top_ten_country_chart_data);
        // ************************* end of top 10 country ***********************



        // ***************************** gender percentage ********************
        $metrics = 'viewerPercentage';
        $dimension = 'gender';
        $sort = '';
        $filter = "video=={$video_id}";
        $max_result = 10;
        $gender_percentage_info = $this->google_youtube_login->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
        $gender_percentage_info = (array)$gender_percentage_info;
        $gender_percentage = array();

        $gender_percentage_list = "";
        if(!empty($gender_percentage_info['rows']))
        {
            $color_array = array("#E27263","#E5C77B");
            $i=0;
            foreach($gender_percentage_info['rows'] as $value)
            {
                $gender_percentage[$i]['value'] = $value[1];
                $gender_percentage[$i]['color'] = $color_array[$i];
                $gender_percentage[$i]['highlight'] = $color_array[$i];
                $gender_percentage[$i]['label'] = $value[0];

                $gender_percentage_list .= '<li><i class="fa fa-circle-o" style="color: '.$color_array[$i].';"></i> '.$value[0].' : '.$value[1].' %</li>';
                $i++;
            }
        }
        $data['gender_percentage_list'] = $gender_percentage_list;
        $data['gender_percentage'] = json_encode($gender_percentage);
        // ************************* gender percentage ***********************




        // ***************************** age group ********************
        $metrics = 'viewerPercentage';
        $dimension = 'ageGroup';
        $sort = '';
        $filter = "video=={$video_id}";
        $max_result = 10;
        $age_group_info = $this->google_youtube_login->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
        $age_group_info = (array)$age_group_info;
        $age_group = array();

        $age_group_list = "";
        if(!empty($age_group_info['rows']))
        {
            $color_array = array("#FF8B6B","#D75EF2","#78ED78","#D31319","#798C0E","#FC749F","#D3C421","#1DAF92","#5832BA","#FC5B20","#EDED28","#E27263","#E5C77B","#B7F93B","#A81538", "#087F24","#9040CE","#872904","#DD5D18","#FBFF0F");
            $color_array = array_reverse($color_array);
            $i=0;
            $color_count=0;
            foreach($age_group_info['rows'] as $value)
            {
                $age_group[$i]['value'] = $value[1];
                $age_group[$i]['color'] = $color_array[$color_count];
                $age_group[$i]['highlight'] = $color_array[$color_count];
                $age_group[$i]['label'] = $value[0];

                $age_group_list .= '<li><i class="fa fa-circle-o" style="color: '.$color_array[$color_count].';"></i> '.$value[0].' : '.$value[1].' %</li>';
                $i++;
                $color_count++;
                if($color_count>=count($color_array)) $color_count=0;
            }
        }
        $data['age_group_list'] = $age_group_list;
        $data['age_group'] = json_encode($age_group);
        // ************************* age group ***********************



        // ***************************** views ********************
        $metrics = 'annotationImpressions';
        $dimension = 'day';
        $sort = 'day';
        $filter = "video=={$video_id}";
        $max_result = '';
        $annotation_impression_info = $this->google_youtube_login->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
        $annotation_impression_info = (array)$annotation_impression_info;

        $annotation_impression = array();
        if(!empty($annotation_impression_info['rows']))
        {
            foreach($annotation_impression_info['rows'] as $value)
            {
                $annotation_impression_raw[$value[0]] = $value[1];
            }

            for($i=0;$i<=$no_of_days;$i++)
            {
                $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

                if(isset($annotation_impression_raw[$day_count])){
                    $annotation_impression[$i]['date'] = $day_count;
                    $annotation_impression[$i]['annotation_impressions'] = $annotation_impression_raw[$day_count];
                }
                else
                {
                    $annotation_impression[$i]['date'] = $day_count;
                    $annotation_impression[$i]['annotation_impressions'] = 0;
                }
            }
        }
        $data['annotation_impressions'] = json_encode($annotation_impression);
        // ************************* end of views ***********************




        // ***************************** annotation close and click impressions ********************
        $metrics = 'annotationClosableImpressions';
        $dimension = 'day';
        $sort = 'day';
        $filter = "video=={$video_id}";
        $max_result = '';
        $close_impression_info = $this->google_youtube_login->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
        $close_impression_info = (array)$close_impression_info;


        $metrics = 'annotationClickableImpressions';
        $click_impression_info = $this->google_youtube_login->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
        $click_impression_info = (array)$click_impression_info;

        $annotation_close_click_impressions = array();
        if(!empty($close_impression_info['rows']) && !empty($click_impression_info['rows']))
        {
            for($i=0;$i<count($close_impression_info['rows']);$i++)
            {
                $annotation_close_click_impressions_raw[$i]['date'] = $close_impression_info['rows'][$i][0];
                $annotation_close_click_impressions_raw[$i]['click_impression'] = $click_impression_info['rows'][$i][1];
                $annotation_close_click_impressions_raw[$i]['close_impression'] = $close_impression_info['rows'][$i][1];
            }

            foreach($annotation_close_click_impressions_raw as $value)
            {
                $annotation_close_click_impressions_raw_2[$value['date']]['click_impression'] = $value['click_impression'];
                $annotation_close_click_impressions_raw_2[$value['date']]['close_impression'] = $value['close_impression'];
            }


            for($i=0;$i<=$no_of_days;$i++)
            {
                $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

                if(isset($annotation_close_click_impressions_raw_2[$day_count])){
                    $annotation_close_click_impressions[$i]['date'] = $day_count;
                    $annotation_close_click_impressions[$i]['click_impression'] = $annotation_close_click_impressions_raw_2[$day_count]['click_impression'];
                    $annotation_close_click_impressions[$i]['close_impression'] = $annotation_close_click_impressions_raw_2[$day_count]['close_impression'];
                }
                else
                {
                    $annotation_close_click_impressions[$i]['date'] = $day_count;
                    $annotation_close_click_impressions[$i]['click_impression'] = 0;
                    $annotation_close_click_impressions[$i]['close_impression'] = 0;
                }
            }
        }

        $data['annotation_close_click_impressions'] = json_encode($annotation_close_click_impressions);

        
        // ************************* end of annotation close and click impressions ***********************




        // ***************************** annotation close and click impressions ********************
        $metrics = 'annotationCloses';
        $dimension = 'day';
        $sort = 'day';
        $filter = "video=={$video_id}";
        $max_result = '';
        $annotation_close_info = $this->google_youtube_login->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
        $annotation_close_info = (array)$annotation_close_info;


        $metrics = 'annotationClicks';
        $annotation_click_info = $this->google_youtube_login->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
        $annotation_click_info = (array)$annotation_click_info;

        $annotation_clicks_closes = array();
        if(!empty($annotation_close_info['rows']) && !empty($annotation_click_info['rows']))
        {
            for($i=0;$i<count($annotation_close_info['rows']);$i++)
            {
                $annotation_clicks_closes_raw[$i]['date'] = $annotation_close_info['rows'][$i][0];
                $annotation_clicks_closes_raw[$i]['annotation_click'] = $annotation_click_info['rows'][$i][1];
                $annotation_clicks_closes_raw[$i]['annotation_close'] = $annotation_close_info['rows'][$i][1];
            }

            foreach($annotation_clicks_closes_raw as $value)
            {
                $annotation_clicks_closes_raw_2[$value['date']]['annotation_click'] = $value['annotation_click'];
                $annotation_clicks_closes_raw_2[$value['date']]['annotation_close'] = $value['annotation_close'];
            }


            for($i=0;$i<=$no_of_days;$i++)
            {
                $day_count = date('Y-m-d', strtotime($start_date. " + $i days"));

                if(isset($annotation_clicks_closes_raw_2[$day_count])){
                    $annotation_clicks_closes[$i]['date'] = $day_count;
                    $annotation_clicks_closes[$i]['annotation_click'] = $annotation_clicks_closes_raw_2[$day_count]['annotation_click'];
                    $annotation_clicks_closes[$i]['annotation_close'] = $annotation_clicks_closes_raw_2[$day_count]['annotation_close'];
                }
                else
                {
                    $annotation_clicks_closes[$i]['date'] = $day_count;
                    $annotation_clicks_closes[$i]['annotation_click'] = 0;
                    $annotation_clicks_closes[$i]['annotation_close'] = 0;
                }
            }
        }

        $data['annotation_clicks_closes'] = json_encode($annotation_clicks_closes);

        
        // ************************* end of annotation close and click impressions ***********************




        // ***************************** top 10 country ********************
        $metrics = 'views';
        $dimension = 'deviceType';
        $sort = '';
        $filter = "video=={$video_id}";
        $max_result = '';
        $device_type_info = $this->google_youtube_login->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
        $device_type_info = (array)$device_type_info;
        $device_type_chart_data = array();

        $device_type_table = "<table class='table table-bordered table-hover table-striped'><tr><th>Sl</th><th>Device Type</th><th>Total</th></tr>";
        if(!empty($device_type_info['rows']))
        {
            $i = 0;
            $total_views = 0;
            foreach($device_type_info['rows'] as $value)
            {
                $i++;
                $device = ucfirst($value[0]);
                $device_type_table .= "<tr><td>".$i."</td><td>".$device."</td><td>".$value[1]."</td></tr>";
                $total_views = $total_views+$value[1];
            }
            $device_type_table .= "</table>";


            $color_array = array("#5832BA","#FC5B20","#EDED28","#E27263","#E5C77B","#B7F93B","#A81538", "#087F24","#9040CE","#872904","#DD5D18","#FBFF0F");
            $i=0;
            $color_count=0;
            foreach($device_type_info['rows'] as $value)
            {
                $device_type_chart_data[$i]['value'] = number_format($value[1]*100/$total_views,2);
                $device_type_chart_data[$i]['color'] = $color_array[$color_count];
                $device_type_chart_data[$i]['highlight'] = $color_array[$color_count];
                $device_type_chart_data[$i]['label'] = ucfirst($value[0]);
                $i++;
                $color_count++;
                if($color_count>=count($color_array)) $color_count=0;
            }
        }
        $data['device_type_table'] = $device_type_table;
        $data['device_type_chart_data'] = json_encode($device_type_chart_data);
        // ************************* end of top 10 country ***********************




        // ***************************** top 10 country ********************
        $metrics = 'views';
        $dimension = 'operatingSystem';
        $sort = '';
        $filter = "video=={$video_id}";
        $max_result = '';
        $operating_system_info = $this->google_youtube_login->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
        $operating_system_info = (array)$operating_system_info;
        $operating_system_chart_data = array();

        $operating_system_table = "<table class='table table-bordered table-hover table-striped'><tr><th>Sl</th><th>Operating System</th><th>Total</th></tr>";
        if(!empty($operating_system_info['rows']))
        {
            $i = 0;
            $total_views = 0;
            foreach($operating_system_info['rows'] as $value)
            {
                $i++;
                $device = ucfirst($value[0]);
                $operating_system_table .= "<tr><td>".$i."</td><td>".$device."</td><td>".$value[1]."</td></tr>";
                $total_views = $total_views+$value[1];
            }
            $operating_system_table .= "</table>";


            $color_array = array("#5832BA","#FC5B20","#EDED28","#E27263","#E5C77B","#B7F93B","#A81538", "#087F24","#9040CE","#872904","#DD5D18","#FBFF0F");
            $color_array = array_reverse($color_array);
            $color_count=0;
            $i=0;
            foreach($operating_system_info['rows'] as $value)
            {
                $operating_system_chart_data[$i]['value'] = number_format($value[1]*100/$total_views,2);
                $operating_system_chart_data[$i]['color'] = $color_array[$color_count];
                $operating_system_chart_data[$i]['highlight'] = $color_array[$color_count];
                $operating_system_chart_data[$i]['label'] = ucfirst($value[0]);
                $i++;
                $color_count++;
                if($color_count>=count($color_array)) $color_count=0;
            }
        }
        $data['operating_system_table'] = $operating_system_table;
        $data['operating_system_chart_data'] = json_encode($operating_system_chart_data);
        // ************************* end of top 10 country ***********************


        // ***************************** shares ********************
        $metrics = 'audienceWatchRatio';
        $dimension = 'elapsedVideoTimeRatio';
        $sort = '';
        $filter = "video=={$video_id}";
        $max_result = '';
        $retention_info = $this->google_youtube_login->get_video_analytics($channel_id,$metrics,$dimension,$sort,$filter,$max_result,$start_date,$end_date);
        $retention_info = (array)$retention_info;

        $retention = array();
        if(!empty($retention_info['rows']))
        {
            $i = 0;
            $j = 0;
            foreach($retention_info['rows'] as $value)
            {
                $j=$i+1;
                $retention[$i]['video_length'] = $j." % ";
                $retention[$i]['audience'] = $value[1];
                $i++;
            }

        }
        // echo "<pre>";
        // print_r($retention); exit();
        $data['retention'] = json_encode($retention);
        // ************************* end of shares ***********************


        $this->session->unset_userdata('individual_video_access_token');
        $this->session->unset_userdata('individual_video_refresh_token');

        $data['title'] = $channel_info[0]['title'];
        $data['from_date'] = date("Y-M-d", strtotime($start_date));
        $data['to_date'] = date("Y-M-d", strtotime($end_date));
        $data['table_id'] = $channel_info[0]['id'];
        $data['body'] = 'youtube_analytics/video_details';
        $data['page_title'] = 'Video Analytics';
        $this->_viewcontroller($data);
 

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



    function country_names_array(){
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


    public function channel_delete_result()
    {
        $channel_table_id = $this->input->post('channel_table_id',true);

        $this->db->trans_start();

        $where['where'] = array('id'=>$channel_table_id);
        $youtube_channel_id = $this->basic->get_data('youtube_channel_list',$where);

        $channel_info_id = $youtube_channel_id[0]['channel_info_id'];
        $user_id = $youtube_channel_id[0]['user_id'];
        $channel_id = $youtube_channel_id[0]['channel_id'];

        $this->basic->delete_data('youtube_channel_list',array('id'=>$channel_table_id));
        $this->basic->delete_data('youtube_channel_info',array('id'=>$channel_info_id,'user_id'=>$user_id));        
        $this->basic->delete_data('youtube_video_list',array('user_id'=>$user_id,'channel_id'=>$channel_id));

        //delete from usage_log table
        $this->_delete_usage_log($module_id=33,$request=1); 

        $this->db->trans_complete();
        if($this->db->trans_status() === false) {
            echo "error";
        }
        else
        {
            echo "success";
        }
    }



   


}