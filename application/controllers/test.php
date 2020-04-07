<?php 
require_once("home.php");

class test extends Home
{
  
    public function __construct()
    {
        parent::__construct();   
      
        $this->load->library('Web_common_report');
    }
	

	function youtube_subtitle()	{
		
		$text=$this->web_common_report->get_youtube_subtitle("lpXacmotd4A");
		
		echo "<pre>";
			print_r($text);
			
		
	}
}
	
	