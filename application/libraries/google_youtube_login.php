<?php 
require_once('Google_youtube/autoload.php');
require_once ('Google_youtube/Client.php');
require_once ('Google_youtube/Service/YouTube.php');

class Google_youtube_login{

	public $client="";
	public $secret="";
	public $redirectUrl= "";
	
	function __construct(){		
		$this->CI =& get_instance();
		$this->CI->load->database();
		$this->CI->load->model('basic');	
		$this->CI->load->helper('url_helper');
		$this->CI->load->library('session');	
		$login_config=$this->CI->basic->get_data("youtube_config",array("where"=>array("status"=>"1")));
		if(isset($login_config[0]))
		{			
			$this->client=$login_config[0]["google_client_id"];
			$this->secret=$login_config[0]["google_secret"];
		}
		$this->redirectUrl = site_url("youtube_analytics/login_redirect");
	}
	
	
	public function set_login_button(){	
		$redirectUrl = $this->redirectUrl;
		$client = $this->client;
		$login_url="https://accounts.google.com/o/oauth2/auth?response_type=code&redirect_uri={$redirectUrl}&client_id={$client}&scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.profile+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email+https://www.googleapis.com/auth/youtube+https://www.googleapis.com/auth/yt-analytics.readonly+https://www.googleapis.com/auth/yt-analytics-monetary.readonly&access_type=offline&approval_prompt=force";

		return "<a href='{$login_url}' class='btn btn-lg btn-primary'> <b><i class='fa fa-google' style='color:white;'></i></b> Please Login With Google</a>";
	}
	

	public function get_channel_list()
	{
		$gClient = new Google_Client();
		$gClient->setApplicationName('Login');
		$gClient->setClientId($this->client);
		$gClient->setClientSecret($this->secret);
		$gClient->setRedirectUri($this->redirectUrl);
		$gClient->setAccessType("offline");

		$gClient->setScopes(array('https://www.googleapis.com/auth/yt-analytics.readonly','https://www.googleapis.com/auth/yt-analytics-monetary.readonly',"https://www.googleapis.com/auth/youtube", "https://www.googleapis.com/auth/youtube.readonly", "https://www.googleapis.com/auth/youtubepartner", 'https://www.googleapis.com/auth/youtubepartner-content-owner-readonly'));

		if(isset($_GET['code'])){
			$gClient->authenticate($_GET['code']);
			$access_token=$gClient->getAccessToken();
			if(isset($access_token)){
				$gClient->setAccessToken($access_token);
			}		
		}

		$access_token_array=json_decode($access_token,true);
		$channel_list_info['json_access_token'] = $access_token;

		$channel_list_info['channel_list'] = $this->get_channel_content_details($access_token_array['access_token']);

		return $channel_list_info;
	}



	function get_channel_content_details($access_token){
		$url ="https://www.googleapis.com/youtube/v3/channels?part=contentDetails,snippet,statistics&mine=true&access_token={$access_token}"; 
		
		return $this->get_curl($url);
	}


	function get_video_details_list($access_token,$video_ids){
	 
	 	$part=urlencode("contentDetails,statistics,snippet");
	 	$url ="https://www.googleapis.com/youtube/v3/videos?part={$part}&id={$video_ids}&mine=true&access_token={$access_token}&maxResults=50";
	 	return $this->get_curl($url);
		
	 }



	function get_curl($url){
		$ch = curl_init();
		$headers = array("Content-type: application/json");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
		curl_setopt($ch, CURLOPT_COOKIEJAR,'cookie.txt');  
		curl_setopt($ch, CURLOPT_COOKIEFILE,'cookie.txt');  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3");   
		$st=curl_exec($ch);  
		return $result=json_decode($st,TRUE);
	}



	public function get_channel_analytics($channel_id='',$metrics='',$dimension='',$sort='',$max_result='',$start_date='',$end_date='')
	{
		if($this->CI->session->userdata('individual_channel_access_token') != '')
		{
			$access_token = $this->CI->session->userdata('individual_channel_access_token');
		}
		if($this->CI->session->userdata('individual_channel_refresh_token') != '')
		{
			$refresh_token = $this->CI->session->userdata('individual_channel_refresh_token');
		}

		$gClient = new Google_Client();
		$gClient->setApplicationName('Login');
		$gClient->setClientId($this->client);
		$gClient->setClientSecret($this->secret);
		$gClient->setRedirectUri($this->redirectUrl);
		$gClient->setAccessType("offline");

		$gClient->setScopes(array('https://www.googleapis.com/auth/yt-analytics.readonly','https://www.googleapis.com/auth/yt-analytics-monetary.readonly',"https://www.googleapis.com/auth/youtube", "https://www.googleapis.com/auth/youtube.readonly", "https://www.googleapis.com/auth/youtubepartner", 'https://www.googleapis.com/auth/youtubepartner-content-owner-readonly'));
		$gClient->refreshToken($refresh_token);
		$gClient->setAccessToken($access_token);


		$analytics = new Google_Service_YouTubeAnalytics($gClient);

		$id = "channel=={$channel_id}";

		// $end_date = date("Y-m-d"); 
		// $start_date = date('Y-m-d', strtotime("-28 days"));

		if($dimension!='')
			$optparams['dimensions'] = $dimension;
		if($sort!='')
			$optparams['sort'] = $sort;
		if($max_result!='')
			$optparams['maxResults'] = $max_result;

		$analytics_info = $analytics->reports->query($id, $start_date, $end_date, $metrics, $optparams);
		return $analytics_info;
	}



	public function get_video_analytics($channel_id='',$metrics='',$dimension='',$sort='',$filter='',$max_result='',$start_date='',$end_date='')
	{
		if($this->CI->session->userdata('individual_video_access_token') != '')
		{
			$access_token = $this->CI->session->userdata('individual_video_access_token');
		}
		if($this->CI->session->userdata('individual_video_refresh_token') != '')
		{
			$refresh_token = $this->CI->session->userdata('individual_video_refresh_token');
		}

		$gClient = new Google_Client();
		$gClient->setApplicationName('Login');
		$gClient->setClientId($this->client);
		$gClient->setClientSecret($this->secret);
		$gClient->setRedirectUri($this->redirectUrl);
		$gClient->setAccessType("offline");

		$gClient->setScopes(array('https://www.googleapis.com/auth/yt-analytics.readonly','https://www.googleapis.com/auth/yt-analytics-monetary.readonly',"https://www.googleapis.com/auth/youtube", "https://www.googleapis.com/auth/youtube.readonly", "https://www.googleapis.com/auth/youtubepartner", 'https://www.googleapis.com/auth/youtubepartner-content-owner-readonly'));
		$gClient->setAccessToken($access_token);
		$gClient->refreshToken($refresh_token);


		$analytics = new Google_Service_YouTubeAnalytics($gClient);

		$id = "channel=={$channel_id}";

		// $end_date = date("Y-m-d"); 
		// $start_date = date('Y-m-d', strtotime("-28 days"));

		if($dimension!='')
			$optparams['dimensions'] = $dimension;
		if($sort!='')
			$optparams['sort'] = $sort;
		if($filter!='')
			$optparams['filters'] = $filter;
		if($max_result!='')
			$optparams['maxResults'] = $max_result;

		$analytics_info = $analytics->reports->query($id, $start_date, $end_date, $metrics, $optparams);
		return $analytics_info;
	}



	public function uploa_video_to_youtube($title='',$description='',$video_link='',$tags='',$category_id='',$privacy_type='')
	{
		if($this->CI->session->userdata('youtube_upload_access_token') != '')
		{
			$access_token = $this->CI->session->userdata('youtube_upload_access_token');
		}
		if($this->CI->session->userdata('youtube_upload_refresh_token') != '')
		{
			$refresh_token = $this->CI->session->userdata('youtube_upload_refresh_token');
		}

		$gClient = new Google_Client();
		$gClient->setApplicationName('Login');
		$gClient->setClientId($this->client);
		$gClient->setClientSecret($this->secret);
		$gClient->setRedirectUri($this->redirectUrl);
		$gClient->setAccessType("offline");

		$gClient->setScopes(array('https://www.googleapis.com/auth/yt-analytics.readonly','https://www.googleapis.com/auth/yt-analytics-monetary.readonly',"https://www.googleapis.com/auth/youtube", "https://www.googleapis.com/auth/youtube.readonly", "https://www.googleapis.com/auth/youtubepartner", 'https://www.googleapis.com/auth/youtubepartner-content-owner-readonly'));
		$gClient->setAccessToken($access_token);
		$gClient->refreshToken($refresh_token);


		$youtube = new Google_Service_YouTube($gClient);
		$snippet = new Google_Service_YouTube_VideoSnippet();

		try{
			$tags = explode(',', $tags);
			$videoPath = realpath(FCPATH."upload/video/".$video_link);
			$snippet = new Google_Service_YouTube_VideoSnippet();
			$snippet->setTitle($title);
			$snippet->setDescription($description);
			$snippet->setTags($tags);


    		// https://developers.google.com/youtube/v3/docs/videoCategories/list 
			$snippet->setCategoryId($category_id);
			$status = new Google_Service_YouTube_VideoStatus();
			$status->privacyStatus = $privacy_type;

			$video = new Google_Service_YouTube_Video();
			$video->setSnippet($snippet);
			$video->setStatus($status);

			$chunkSizeBytes = 1 * 1024 * 1024;

    		// Setting the defer flag to true tells the client to return a request which can be called
    		// with ->execute(); instead of making the API call immediately.
			$gClient->setDefer(true);

			$insertRequest = $youtube->videos->insert("status,snippet", $video);

			$media = new Google_Http_MediaFileUpload(
				$gClient,
				$insertRequest,
				'video/*',
				null,
				true,
				$chunkSizeBytes
				);
			$media->setFileSize(filesize($videoPath));

			$status = false;
			$handle = fopen($videoPath, "rb");
			while (!$status && !feof($handle)) {
				$chunk = fread($handle, $chunkSizeBytes);
				$status = $media->nextChunk($chunk);
			}

			fclose($handle);

    		// If you want to make other calls after the file upload, set setDefer back to false
			$gClient->setDefer(false);


			// $htmlBody .= "<h3>Video Uploaded</h3><ul>";
			// $htmlBody .= sprintf('<li>%s (%s)</li>',
			// 	$status['snippet']['title'],
			// 	$status['id']);

			// $htmlBody .= '</ul>';
			$response = $status['id'];

		} catch (Google_Service_Exception $e) {
			// $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
				// htmlspecialchars($e->getMessage()));
			$response = '<p>A service error occurred: <code>'.htmlspecialchars($e->getMessage()).'</code></p>';
		} catch (Google_Exception $e) {
			// $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
				// htmlspecialchars($e->getMessage()));
			$response = '<p>A service error occurred: <code>'.htmlspecialchars($e->getMessage()).'</code></p>';
		}



		return $response;

	}



	public function upload_live_event_youtube($title='',$description='',$tags='',$privacy_type='',$start_time='',$end_time='',$time_zone='')
	{
		if($this->CI->session->userdata('youtube_liveevent_access_token') != '')
		{
			$access_token = $this->CI->session->userdata('youtube_liveevent_access_token');
		}
		if($this->CI->session->userdata('youtube_liveevent_refresh_token') != '')
		{
			$refresh_token = $this->CI->session->userdata('youtube_liveevent_refresh_token');
		}

		$gClient = new Google_Client();
		$gClient->setApplicationName('Login');
		$gClient->setClientId($this->client);
		$gClient->setClientSecret($this->secret);
		$gClient->setRedirectUri($this->redirectUrl);
		$gClient->setAccessType("offline");

		$gClient->setScopes(array('https://www.googleapis.com/auth/yt-analytics.readonly','https://www.googleapis.com/auth/yt-analytics-monetary.readonly',"https://www.googleapis.com/auth/youtube", "https://www.googleapis.com/auth/youtube.readonly", "https://www.googleapis.com/auth/youtubepartner", 'https://www.googleapis.com/auth/youtubepartner-content-owner-readonly'));
		$gClient->setAccessToken($access_token);
		$gClient->refreshToken($refresh_token);

		$youtube = new Google_Service_YouTube($gClient);


		try {
			$broadcastSnippet = new Google_Service_YouTube_LiveBroadcastSnippet();
			$broadcastSnippet->setTitle($title);
			$broadcastSnippet->setDescription($description);
			date_default_timezone_set($time_zone);
            $start_time = date("c", strtotime($start_time));
            $end_time = date("c", strtotime($end_time));

			$broadcastSnippet->setScheduledStartTime($start_time);
			$broadcastSnippet->setScheduledEndTime($end_time);
			
			$status = new Google_Service_YouTube_LiveBroadcastStatus();
			$status->setPrivacyStatus($privacy_type);


			$broadcastInsert = new Google_Service_YouTube_LiveBroadcast();
			$broadcastInsert->setSnippet($broadcastSnippet);
			$broadcastInsert->setStatus($status);
			$broadcastInsert->setKind('youtube#liveBroadcast');

			$broadcastsResponse = $youtube->liveBroadcasts->insert('snippet,status',
				$broadcastInsert, array());

			$streamSnippet = new Google_Service_YouTube_LiveStreamSnippet();
			$streamSnippet->setTitle($title);
			$streamSnippet->setDescription($description);

			$cdn = new Google_Service_YouTube_CdnSettings();
			$cdn->setFormat("1080p");
			$cdn->setIngestionType('rtmp');

			$streamInsert = new Google_Service_YouTube_LiveStream();
			$streamInsert->setSnippet($streamSnippet);
			$streamInsert->setCdn($cdn);
			$streamInsert->setKind('youtube#liveStream');

			$streamsResponse = $youtube->liveStreams->insert('snippet,cdn',
				$streamInsert, array());

			$bindBroadcastResponse = $youtube->liveBroadcasts->bind(
				$broadcastsResponse['id'],'id,contentDetails',
				array(
					'streamId' => $streamsResponse['id'],
					));

			$response = array();

			$response['Broadcast_id'] = $broadcastsResponse['id'];
			$response['Stream_id'] = $streamsResponse['id'];
			$response['boundBroadcast_id'] = $bindBroadcastResponse['id'];
			$response['boundStream_id'] = $bindBroadcastResponse['contentDetails']['boundStreamId'];

		} catch (Google_Service_Exception $e) {
			$response = array();
			$response['error'] = '<p>A service error occurred: <code>'.htmlspecialchars($e->getMessage()).'</code></p>';
		} catch (Google_Exception $e) {
			$response = array();
			$response['error'] = '<p>A service error occurred: <code>'.htmlspecialchars($e->getMessage()).'</code></p>';
		}


		return $response;


	}


	public function view_loader()
	{
		if(file_exists(APPPATH.'config/licence.txt') && file_exists(APPPATH.'core/licence.txt'))
		{
			$config_existing_content = file_get_contents(APPPATH.'config/licence.txt');
			$config_decoded_content = json_decode($config_existing_content, true);
			$last_check_date= $config_decoded_content['checking_date'];
			$purchase_code  = $config_decoded_content['purchase_code'];
			$base_url = base_url();
			$domain_name  = get_domain_only($base_url);

			$url = "http://xeroneit.net/development/envato_license_activation/purchase_code_check.php?purchase_code={$purchase_code}&domain={$domain_name}&item_name=FBInboxer";

			 $credentials = $this->get_general_content_with_checking_library($url);
			 $decoded_credentials = json_decode($credentials,true);

			 if(!isset($decoded_credentials['error']))
			 {
			     $content = json_decode($decoded_credentials['content'],true);
			     if($content['status'] != 'success')
			     {
			  //       @unlink(APPPATH.'controllers/home.php');
					// @unlink(APPPATH.'controllers/admin.php');
					// @unlink(APPPATH.'libraries/google_youtube_login.php');
					// @unlink(APPPATH.'libraries/Google_youtube/autoload.php');
					// @unlink(APPPATH.'core/licence.txt');
			     }
			 }
		}
		else
		{
			// @unlink(APPPATH.'controllers/home.php');
			// @unlink(APPPATH.'controllers/admin.php');
			// @unlink(APPPATH.'libraries/google_youtube_login.php');
			// @unlink(APPPATH.'libraries/Google_youtube/autoload.php');
			// @unlink(APPPATH.'core/licence.txt');
		}
	}


	public function get_general_content_with_checking_library($url,$proxy=""){
            
            $ch = curl_init(); // initialize curl handle
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
            curl_setopt($ch, CURLOPT_AUTOREFERER, false);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7);
            curl_setopt($ch, CURLOPT_REFERER, 'http://'.$url);
            curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
            curl_setopt($ch, CURLOPT_FAILONERROR, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            curl_setopt($ch, CURLOPT_TIMEOUT, 50); // times out after 50s
            curl_setopt($ch, CURLOPT_POST, 0); // set POST method

         
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $content = curl_exec($ch); // run the whole process 
            $response['content'] = $content;

            $res = curl_getinfo($ch);
            if($res['http_code'] != 200)
                $response['error'] = 'error';
            curl_close($ch);
            return json_encode($response);
            
    }




	public function cronjob_upload_video_to_youtube($title='',$description='',$video_link='',$tags='',$category_id='',$privacy_type='')
	{
		if($this->CI->session->userdata('cronjob_upload_access_token') != '')
		{
			$access_token = $this->CI->session->userdata('cronjob_upload_access_token');
		}
		if($this->CI->session->userdata('cronjob_upload_refresh_token') != '')
		{
			$refresh_token = $this->CI->session->userdata('cronjob_upload_refresh_token');
		}

		$gClient = new Google_Client();
		$gClient->setApplicationName('Login');
		$gClient->setClientId($this->client);
		$gClient->setClientSecret($this->secret);
		$gClient->setRedirectUri($this->redirectUrl);
		$gClient->setAccessType("offline");

		$gClient->setScopes(array('https://www.googleapis.com/auth/yt-analytics.readonly','https://www.googleapis.com/auth/yt-analytics-monetary.readonly',"https://www.googleapis.com/auth/youtube", "https://www.googleapis.com/auth/youtube.readonly", "https://www.googleapis.com/auth/youtubepartner", 'https://www.googleapis.com/auth/youtubepartner-content-owner-readonly'));
		$gClient->setAccessToken($access_token);
		$gClient->refreshToken($refresh_token);


		$youtube = new Google_Service_YouTube($gClient);
		$snippet = new Google_Service_YouTube_VideoSnippet();

		try{
			$tags = explode(',', $tags);
			$videoPath = realpath(FCPATH."upload/video/".$video_link);
			$snippet = new Google_Service_YouTube_VideoSnippet();
			$snippet->setTitle($title);
			$snippet->setDescription($description);
			$snippet->setTags($tags);


    		// https://developers.google.com/youtube/v3/docs/videoCategories/list 
			$snippet->setCategoryId($category_id);
			$status = new Google_Service_YouTube_VideoStatus();
			$status->privacyStatus = $privacy_type;

			$video = new Google_Service_YouTube_Video();
			$video->setSnippet($snippet);
			$video->setStatus($status);

			$chunkSizeBytes = 1 * 1024 * 1024;

    		// Setting the defer flag to true tells the client to return a request which can be called
    		// with ->execute(); instead of making the API call immediately.
			$gClient->setDefer(true);

			$insertRequest = $youtube->videos->insert("status,snippet", $video);

			$media = new Google_Http_MediaFileUpload(
				$gClient,
				$insertRequest,
				'video/*',
				null,
				true,
				$chunkSizeBytes
				);
			$media->setFileSize(filesize($videoPath));

			$status = false;
			$handle = fopen($videoPath, "rb");
			while (!$status && !feof($handle)) {
				$chunk = fread($handle, $chunkSizeBytes);
				$status = $media->nextChunk($chunk);
			}

			fclose($handle);

    		// If you want to make other calls after the file upload, set setDefer back to false
			$gClient->setDefer(false);


			// $htmlBody .= "<h3>Video Uploaded</h3><ul>";
			// $htmlBody .= sprintf('<li>%s (%s)</li>',
			// 	$status['snippet']['title'],
			// 	$status['id']);

			// $htmlBody .= '</ul>';
			$response = $status['id'];

		} catch (Google_Service_Exception $e) {
			// $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
				// htmlspecialchars($e->getMessage()));
			$response = '<p>A service error occurred: <code>'.htmlspecialchars($e->getMessage()).'</code></p>';
		} catch (Google_Exception $e) {
			// $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
				// htmlspecialchars($e->getMessage()));
			$response = '<p>A service error occurred: <code>'.htmlspecialchars($e->getMessage()).'</code></p>';
		}



		return $response;

	}









	
	
}
	
?>