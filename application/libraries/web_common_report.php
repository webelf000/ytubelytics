<?php  
	require_once('phpwhois-4.2.2/whois.main.php'); // including 
	require_once('simple_html_dom.php');
	require_once( 'IXR_Library.php' );
	include("phpQuery-onefile.php");


	class Web_common_report
	{

		public  $googlehost='toolbarqueries.google.com';
		public 	$googleua='Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.6) Gecko/20060728 Firefox/1.5';
		public $same_site_in_ip=array();

		public $country_list = array (
								  'AF' => 'AFGHANISTAN',
								  'AX' => 'ÅLAND ISLANDS',
								  'AL' => 'ALBANIA',
								  'BLANK' => 'ZANZIBAR',
								  'DZ' => 'ALGERIA (El Djazaïr)',
								  'AS' => 'AMERICAN SAMOA',
								  'AD' => 'ANDORRA',
								  'AO' => 'ANGOLA',
								  'AI' => 'ANGUILLA',
								  'AQ' => 'ANTARCTICA',
								  'AG' => 'ANTIGUA AND BARBUDA',
								  'AR' => 'ARGENTINA',
								  'AM' => 'ARMENIA',
								  'AW' => 'ARUBA',
								  'blank' => 'YUGOSLAVIA (Internet code still used)',
								  'AU' => 'AUSTRALIA',
								  'AT' => 'AUSTRIA',
								  'AZ' => 'AZERBAIJAN',
								  'BS' => 'BAHAMAS',
								  'BH' => 'BAHRAIN',
								  'BD' => 'BANGLADESH',
								  'BB' => 'BARBADOS',
								  'BY' => 'BELARUS',
								  'BE' => 'BELGIUM',
								  'BZ' => 'BELIZE',
								  'BJ' => 'BENIN',
								  'BM' => 'BERMUDA',
								  'BT' => 'BHUTAN',
								  'BO' => 'BOLIVIA',
								  'BQ' => 'BONAIRE, ST. EUSTATIUS, AND SABA',
								  'BA' => 'BOSNIA AND HERZEGOVINA',
								  'BW' => 'BOTSWANA',
								  'BV' => 'BOUVET ISLAND',
								  'BR' => 'BRAZIL',
								  'IO' => 'BRITISH INDIAN OCEAN TERRITORY',
								  'BN' => 'BRUNEI DARUSSALAM',
								  'BG' => 'BULGARIA',
								  'BF' => 'BURKINA FASO',
								  'BI' => 'BURUNDI',
								  'KH' => 'CAMBODIA',
								  'CM' => 'CAMEROON',
								  'CA' => 'CANADA',
								  'CV' => 'CAPE VERDE',
								  'KY' => 'CAYMAN ISLANDS',
								  'CF' => 'CENTRAL AFRICAN REPUBLIC',
								  'CD' => 'CONGO, THE DEMOCRATIC REPUBLIC OF THE (formerly Zaire)',
								  'CL' => 'CHILE',
								  'CN' => 'CHINA',
								  'CX' => 'CHRISTMAS ISLAND',
								  'CC' => 'COCOS (KEELING) ISLANDS',
								  'CO' => 'COLOMBIA',
								  'KM' => 'COMOROS',
								  'CG' => 'CONGO, REPUBLIC OF',
								  'CK' => 'COOK ISLANDS',
								  'CR' => 'COSTA RICA',
								  'CI' => 'CÔTE D\'IVOIRE (Ivory Coast)',
								  'HR' => 'CROATIA (Hrvatska)',
								  'CU' => 'CUBA',
								  'CW' => 'CURAÇAO',
								  'CY' => 'CYPRUS',
								  'CZ' => 'ZECH REPUBLIC',
								  'DK' => 'DENMARK',
								  'DJ' => 'DJIBOUTI',
								  'DM' => 'DOMINICA',
								  'DO'=>'Dominican Republic',
								  'DC' => 'DOMINICAN REPUBLIC',
								  'EC' => 'ECUADOR',
								  'EG' => 'EGYPT',
								  'SV' => 'EL SALVADOR',
								  'GQ' => 'EQUATORIAL GUINEA',
								  'ER' => 'ERITREA',
								  'EE' => 'ESTONIA',
								  'ET' => 'ETHIOPIA',
								  'FO' => 'FAEROE ISLANDS',
								  'FK' => 'FALKLAND ISLANDS (MALVINAS)',
								  'FJ' => 'FIJI',
								  'FI' => 'FINLAND',
								  'FR' => 'FRANCE',
								  'GF' => 'FRENCH GUIANA',
								  'PF' => 'FRENCH POLYNESIA',
								  'TF' => 'FRENCH SOUTHERN TERRITORIES',
								  'GA' => 'GABON',
								  'GM' => 'GAMBIA, THE',
								  'GE' => 'GEORGIA',
								  'DE' => 'GERMANY (Deutschland)',
								  'GH' => 'GHANA',
								  'GI' => 'GIBRALTAR',
								  'GB' => 'UNITED KINGDOM',
								  'GR' => 'GREECE',
								  'GL' => 'GREENLAND',
								  'GD' => 'GRENADA',
								  'GP' => 'GUADELOUPE',
								  'GU' => 'GUAM',
								  'GT' => 'GUATEMALA',
								  'GG' => 'GUERNSEY',
								  'GN' => 'GUINEA',
								  'GW' => 'GUINEA-BISSAU',
								  'GY' => 'GUYANA',
								  'HT' => 'HAITI',
								  'HM' => 'HEARD ISLAND AND MCDONALD ISLANDS',
								  'HN' => 'HONDURAS',
								  'HK' => 'HONG KONG (Special Administrative Region of China)',
								  'HU' => 'HUNGARY',
								  'IS' => 'ICELAND',
								  'IN' => 'INDIA',
								  'ID' => 'INDONESIA',
								  'IR' => 'IRAN (Islamic Republic of Iran)',
								  'IQ' => 'IRAQ',
								  'IE' => 'IRELAND',
								  'IM' => 'ISLE OF MAN',
								  'IL' => 'ISRAEL',
								  'IT' => 'ITALY',
								  'JM' => 'JAMAICA',
								  'JP' => 'JAPAN',
								  'JE' => 'JERSEY',
								  'JO' => 'JORDAN (Hashemite Kingdom of Jordan)',
								  'KZ' => 'KAZAKHSTAN',
								  'KE' => 'KENYA',
								  'KI' => 'KIRIBATI',
								  'KP' => 'KOREA (Democratic Peoples Republic of [North] Korea)',
								  'KR' => 'KOREA (Republic of [South] Korea)',
								  'KW' => 'KUWAIT',
								  'KG' => 'KYRGYZSTAN',
								  'LA' => 'LAO PEOPLE\'S DEMOCRATIC REPUBLIC',
								  'LV' => 'LATVIA',
								  'LB' => 'LEBANON',
								  'LS' => 'LESOTHO',
								  'LR' => 'LIBERIA',
								  'LY' => 'LIBYA (Libyan Arab Jamahirya)',
								  'LI' => 'LIECHTENSTEIN (Fürstentum Liechtenstein)',
								  'LT' => 'LITHUANIA',
								  'LU' => 'LUXEMBOURG',
								  'MO' => 'MACAO (Special Administrative Region of China)',
								  'MK' => 'MACEDONIA (Former Yugoslav Republic of Macedonia)',
								  'MG' => 'MADAGASCAR',
								  'MW' => 'MALAWI',
								  'MY' => 'MALAYSIA',
								  'MV' => 'MALDIVES',
								  'ML' => 'MALI',
								  'MT' => 'MALTA',
								  'MH' => 'MARSHALL ISLANDS',
								  'MQ' => 'MARTINIQUE',
								  'MR' => 'MAURITANIA',
								  'MU' => 'MAURITIUS',
								  'YT' => 'MAYOTTE',
								  'MX' => 'MEXICO',
								  'FM' => 'MICRONESIA (Federated States of Micronesia)',
								  'MD' => 'MOLDOVA',
								  'MC' => 'MONACO',
								  'MN' => 'MONGOLIA',
								  'ME' => 'MONTENEGRO',
								  'MS' => 'MONTSERRAT',
								  'MA' => 'MOROCCO',
								  'MZ' => 'MOZAMBIQUE (Moçambique)',
								  'MM' => 'MYANMAR (formerly Burma)',
								  'NA' => 'NAMIBIA',
								  'NR' => 'NAURU',
								  'NP' => 'NEPAL',
								  'NL' => 'NETHERLANDS',
								  'AN' => 'NETHERLANDS ANTILLES (obsolete)',
								  'NC' => 'NEW CALEDONIA',
								  'NZ' => 'NEW ZEALAND',
								  'NI' => 'NICARAGUA',
								  'NE' => 'NIGER',
								  'NG' => 'NIGERIA',
								  'NU' => 'NIUE',
								  'NF' => 'NORFOLK ISLAND',
								  'MP' => 'NORTHERN MARIANA ISLANDS',
								  'ND' => 'NORWAY',
								  'NO' => 'NORWAY',
								  'OM' => 'OMAN',
								  'PK' => 'PAKISTAN',
								  'PW' => 'PALAU',
								  'PS' => 'PALESTINIAN TERRITORIES',
								  'PA' => 'PANAMA',
								  'PG' => 'PAPUA NEW GUINEA',
								  'PY' => 'PARAGUAY',
								  'PE' => 'PERU',
								  'PH' => 'PHILIPPINES',
								  'PN' => 'PITCAIRN',
								  'PL' => 'POLAND',
								  'PT' => 'PORTUGAL',
								  'PR' => 'PUERTO RICO',
								  'QA' => 'QATAR',
								  'RE' => 'RÉUNION',
								  'RO' => 'ROMANIA',
								  'RU' => 'RUSSIAN FEDERATION',
								  'RW' => 'RWANDA',
								  'BL' => 'SAINT BARTHÉLEMY',
								  'SH' => 'SAINT HELENA',
								  'KN' => 'SAINT KITTS AND NEVIS',
								  'LC' => 'SAINT LUCIA',
								  'MF' => 'SAINT MARTIN (French portion)',
								  'PM' => 'SAINT PIERRE AND MIQUELON',
								  'VC' => 'SAINT VINCENT AND THE GRENADINES',
								  'WS' => 'SAMOA (formerly Western Samoa)',
								  'SM' => 'SAN MARINO (Republic of)',
								  'ST' => 'SAO TOME AND PRINCIPE',
								  'SA' => 'SAUDI ARABIA (Kingdom of Saudi Arabia)',
								  'SN' => 'SENEGAL',
								  'RS' => 'SERBIA (Republic of Serbia)',
								  'SC' => 'SEYCHELLES',
								  'SL' => 'SIERRA LEONE',
								  'SG' => 'SINGAPORE',
								  'SX' => 'SINT MAARTEN',
								  'SK' => 'SLOVAKIA (Slovak Republic)',
								  'SI' => 'SLOVENIA',
								  'SB' => 'SOLOMON ISLANDS',
								  'SO' => 'SOMALIA',
								  'ZA' => 'ZAMBIA (formerly Northern Rhodesia)',
								  'GS' => 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS',
								  'SS' => 'SOUTH SUDAN',
								  'ES' => 'SPAIN (España)',
								  'LK' => 'SRI LANKA (formerly Ceylon)',
								  'SD' => 'SUDAN',
								  'SR' => 'SURINAME',
								  'SJ' => 'SVALBARD AND JAN MAYE',
								  'SZ' => 'SWAZILAND',
								  'SE' => 'SWEDEN',
								  'CH' => 'SWITZERLAND (Confederation of Helvetia)',
								  'SY' => 'SYRIAN ARAB REPUBLIC',
								  'TW' => 'TAIWAN ("Chinese Taipei" for IOC)',
								  'TJ' => 'TAJIKISTAN',
								  'TZ' => 'TANZANIA',
								  'TH' => 'THAILAND',
								  'TL' => 'TIMOR-LESTE (formerly East Timor)',
								  'TG' => 'TOGO',
								  'TK' => 'TOKELAU',
								  'TO' => 'TONGA',
								  'TT' => 'TRINIDAD AND TOBAGO',
								  'TN' => 'TUNISIA',
								  'TR' => 'TURKEY',
								  'TM' => 'TURKMENISTAN',
								  'TC' => 'TURKS AND CAICOS ISLANDS',
								  'TV' => 'TUVALU',
								  'UG' => 'UGANDA',
								  'UA' => 'UKRAINE',
								  'AE' => 'UNITED ARAB EMIRATES',
								  'US' => 'UNITED STATES',
								  'UM' => 'UNITED STATES MINOR OUTLYING ISLANDS',
								  'UY' => 'URUGUAY',
								  'UZ' => 'UZBEKISTAN',
								  'VU' => 'VANUATU',
								  'VA' => 'VATICAN CITY (Holy See)',
								  'VN' => 'VIET NAM',
								  'VG' => 'VIRGIN ISLANDS, BRITISH',
								  'VI' => 'VIRGIN ISLANDS, U.S.',
								  'WF' => 'WALLIS AND FUTUNA',
								  'EH' => 'WESTERN SAHARA (formerly Spanish Sahara)',
								  'YE' => 'YEMEN (Yemen Arab Republic)',
								  'ZW' => 'ZIMBABWE',
								);

		public $ping_link = array(
			'http://ping.blogs.yandex.ru/RPC2',
			'http://blogsearch.google.com/ping/RPC2',
			'http://blogsearch.google.ae/ping/RPC2',
			'http://blogsearch.google.at/ping/RPC2',
			'http://blogsearch.google.be/ping/RPC2',
			'http://blogsearch.google.bg/ping/RPC2',
			'http://blogsearch.google.ch/ping/RPC2',
			'http://blogsearch.google.cl/ping/RPC2',
			'http://blogsearch.google.co.id/ping/RPC2',
			'http://blogsearch.google.co.il/ping/RPC2',
			'http://blogsearch.google.co.in/ping/RPC2',
			'http://blogsearch.google.co.jp/ping/RPC2',
			'http://blogsearch.google.co.ma/ping/RPC2',
			'http://blogsearch.google.co.nz/ping/RPC2',
			'http://blogsearch.google.co.th/ping/RPC2',
			'http://blogsearch.google.co.uk/ping/RPC2',
			'http://blogsearch.google.co.ve/ping/RPC2',
			'http://blogsearch.google.co.za/ping/RPC2',
			'http://blogsearch.google.com.ar/ping/RPC2',
			'http://blogsearch.google.com.au/ping/RPC2',
			'http://blogsearch.google.com.br/ping/RPC2',
			'http://blogsearch.google.com.co/ping/RPC2',
			'http://blogsearch.google.com.mx/ping/RPC2',
			'http://blogsearch.google.com.my/ping/RPC2',
			'http://blogsearch.google.com.pe/ping/RPC2',
			'http://blogsearch.google.com.sa/ping/RPC2',
			'http://blogsearch.google.com.sg/ping/RPC2',
			'http://blogsearch.google.com.tr/ping/RPC2',
			'http://blogsearch.google.com.tw/ping/RPC2',
			'http://blogsearch.google.com.ua/ping/RPC2',
			'http://blogsearch.google.com.uy/ping/RPC2',
			'http://blogsearch.google.com.vn/ping/RPC2',
			'http://blogsearch.google.de/ping/RPC2',
			'http://blogsearch.google.es/ping/RPC2',
			'http://blogsearch.google.fi/ping/RPC2',
			'http://blogsearch.google.fr/ping/RPC2',
			'http://blogsearch.google.gr/ping/RPC2',
			'http://blogsearch.google.hr/ping/RPC2',
			'http://blogsearch.google.ie/ping/RPC2',
			'http://blogsearch.google.it/ping/RPC2',
			'http://blogsearch.google.jp/ping/RPC2',
			'http://blogsearch.google.lt/ping/RPC2',
			'http://blogsearch.google.nl/ping/RPC2',
			'http://blogsearch.google.pl/ping/RPC2',
			'http://blogsearch.google.pt/ping/RPC2',
			'http://blogsearch.google.ro/ping/RPC2',
			'http://blogsearch.google.ru/ping/RPC2',
			'http://blogsearch.google.se/ping/RPC2',
			'http://blogsearch.google.sk/ping/RPC2',
			'http://blogsearch.google.us/ping/RPC2',
			'http://blogsearch.google.ca/ping/RPC2',
			'http://blogsearch.google.co.cr/ping/RPC2',
			'http://blogsearch.google.co.hu/ping/RPC2',
			'http://blogsearch.google.com.do/ping/RPC2',
			'http://blogpingr.de/ping/rpc2',
			'http://ping.pubsub.com/ping',
			'http://pingomatic.com',
			'http://blogsearch.google.lk/ping/RPC2',
			'http://blogsearch.google.ws/ping/RPC2',
			'http://blogsearch.google.vu/ping/RPC2',
			'http://blogsearch.google.vg/ping/RPC2',
			'http://blogsearch.google.tt/ping/RPC2',
			'http://blogsearch.google.to/ping/RPC2',
			'http://blogsearch.google.tm/ping/RPC2',
			'http://blogsearch.google.tl/ping/RPC2',
			'http://blogsearch.google.tk/ping/RPC2',
			'http://blogsearch.google.st/ping/RPC2',
			'http://blogsearch.google.sn/ping/RPC2',
			'http://blogsearch.google.sm/ping/RPC2',
			'http://blogsearch.google.si/ping/RPC2',
			'http://blogsearch.google.sh/ping/RPC2',
			'http://blogsearch.google.sc/ping/RPC2',
			'http://blogsearch.google.rw/ping/RPC2',
			'http://blogsearch.google.pn/ping/RPC2',
			'http://blogsearch.google.nu/ping/RPC2',
			'http://blogsearch.google.nr/ping/RPC2',
			'http://blogsearch.google.no/ping/RPC2',
			'http://blogsearch.google.mw/ping/RPC2',
			'http://blogsearch.google.mv/ping/RPC2',
			'http://blogsearch.google.mu/ping/RPC2',
			'http://blogsearch.google.ms/ping/RPC2',
			'http://blogsearch.google.mn/ping/RPC2',
			'http://blogsearch.google.md/ping/RPC2',
			'http://blogsearch.google.lu/ping/RPC2',
			'http://blogsearch.google.li/ping/RPC2',
			'http://blogsearch.google.la/ping/RPC2',
			'http://blogsearch.google.kz/ping/RPC2',
			'http://blogsearch.google.kg/ping/RPC2',
			'http://blogsearch.google.jo/ping/RPC2',
			'http://blogsearch.google.je/ping/RPC2',
			'http://blogsearch.google.is/ping/RPC2',
			'http://blogsearch.google.im/ping/RPC2',
			'http://blogsearch.google.hu/ping/RPC2',
			'http://blogsearch.google.ht/ping/RPC2',
			'http://rpc.weblogs.com/RPC2',
			'http://services.newsgator.com/ngws/xmlrpcping.aspx',
			'http://www.blogpeople.net/servlet/weblogUpdates',
			'http://blogpeople.net/ping',
			'http://pubsub.com/ping'
			);
			
			
			public $back_link_url=Array(
			
   		 			"http://similarsites.com/site/[url]",
				    "http://alexa.com/siteinfo/[url]",
				    "http://builtwith.com/[url]",
				    "http://siteadvisor.cn/sites/[url]/summary/",
				    "http://whois.domaintools.com/[url]",
					"http://whoisx.co.uk/[url]",
				    "http://aboutdomain.org/info/[url]/",
				    "http://aboutus.org/[url]",
				    "http://validator.w3.org/check?uri=[url]",
				    "http://sitepricechecker.com/[url]",
				    "http://script3.prothemes.biz/[url]",
				    "http://websitevaluebot.com/[url]",
				    "http://listenarabic.com/search?q=[url]&sa=Search",
				    "http://keywordspy.com/research/search.aspx?q=[url]&tab=domain-overview",
				    "http://aboutdomain.org/backlinks/[url]/",
				    "http://who.is/whois/[url]/",
				    "http://protect-x.com/info/[url]",
				    "https://siteanalytics.compete.com/[url]/",
				    "http://sitedossier.com/site/[url]",
				    "http://wholinkstome.com/url/[url]",
				    "http://serpanalytics.com/#competitor/[url]/summary/1",
				    "http://hosts-file.net/default.asp?s=[url]",
				    "http://robtex.com/dns/[url].html",
				    "https://quantcast.com/[url]",
				    "http://toolbar.netcraft.com/site_report?url=[url]",
				    "http://aboutthedomain.com/[url]",
				    "http://websiteshadow.com/[url]",
				    "http://surcentro.com/en/info/[url]",
				    "http://onlinewebcheck.com/check.php?url=[url]",
				    "http://socialwebwatch.com/stats.php?url=[url]",
				    "http://statscrop.com/www/[url]",
				    "http://statmyweb.com/site/[url]",
				    "http://tools.quicksprout.com/analyze/[url]",
				    "http://whois.net/whois/[url]",
				    "http://iwebchk.com/reports/view/[url]",
				    "http://siteadvisor.com/sites/[url]",
				    "http://google.com/safebrowsing/diagnostic?site=[url]",
				    "https://safeweb.norton.com/report/show?url=[url]",
				    "https://mywot.com/en/scorecard/[url]",
				    "http://sitecheck.sucuri.net/results/[url]",
				    "http://sitejabber.com/search/[url]",
				    "http://avgthreatlabs.com/website-safety-reports/domain/[url]",
				    "http://siteprice.org/AnalyzeSite.aspx?url=[url]",
				    "http://similarweb.com/website/[url]",
				    "http://dnscheck.pingdom.com/?domain=[url]",
					"http://www.myip.net/[url]",
					"http://hqindex.org/[url]",
					"http://hqindex.org/[url]",
					"http://statsie.com/[url]",
					"http://toolbar.netcraft.com/site_report?url=[url]#last_reboot",
					"http://estibot.com/appraise.php?a=appraise&data=[url]",
					"http://onthesamehost.com/?q=[url]",
					
				);
				
		public $user_id; 
		public $proxy_ip;
		public $proxy_auth_pass;
		public $session_id;



	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->database();
		$this->CI->load->helper('my_helper');
		$this->CI->load->library('session');
		$this->user_id=$this->CI->session->userdata("user_id");
		$this->session_id=$this->CI->session->userdata("session_id");
		
		
		$q="select * from config_proxy where deleted='0' and (user_id='{$this->user_id}' or  admin_permission='everyone') ORDER BY rand() LIMIT 1";
		
		//echo $q="select * from config_proxy where deleted='0' ORDER BY rand() LIMIT 1";
		
		$query=$this->CI->db->query($q);
		$results=$query->result_array();
		
		if(count($results)==0) {
			$this->proxy_ip="";
			$this->proxy_auth_pass="";
		}
		else{
			foreach($results as $info){	
				$this->proxy_ip=$info['proxy'].":".$info['port'];
				if($info['username']=='' || $info['username']=='NULL'){
					$this->proxy_auth_pass="";
				}
				else{
					$this->proxy_auth_pass=$info['username'].":".$info['password'];
				}
				
			}
		}
	}




	function get_content($url)
	{

		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3");   
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
		curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");  
		curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt"); 	
		$content = curl_exec($ch);
		$content=json_decode($content,TRUE);

		return $content;

	}


	function StrToNum($Str, $Check, $Magic) 
	{
	$Int32Unit = 4294967296;  // 2^32
	$length = strlen($Str);
	for ($i = 0; $i < $length; $i++) {
		$Check *= $Magic;   
	//If the float is beyond the boundaries of integer (usually +/- 2.15e+9 = 2^31), 
	//  the result of converting to integer is undefined
	//  refer to http://www.php.net/manual/en/language.types.integer.php
		if ($Check >= $Int32Unit) {
			$Check = ($Check - $Int32Unit * (int) ($Check / $Int32Unit));
	//if the check less than -2^31
			$Check = ($Check < -2147483648) ? ($Check + $Int32Unit) : $Check;
		}
		$Check += ord($Str{$i}); 
	}
	return $Check;
	}

	//genearate a hash for a url
	function HashURL($String) 
	{
		$Check1 = $this->StrToNum($String, 0x1505, 0x21);
		$Check2 = $this->StrToNum($String, 0, 0x1003F);

		$Check1 >>= 2;    
		$Check1 = (($Check1 >> 4) & 0x3FFFFC0 ) | ($Check1 & 0x3F);
		$Check1 = (($Check1 >> 4) & 0x3FFC00 ) | ($Check1 & 0x3FF);
		$Check1 = (($Check1 >> 4) & 0x3C000 ) | ($Check1 & 0x3FFF);   

		$T1 = (((($Check1 & 0x3C0) << 4) | ($Check1 & 0x3C)) <<2 ) | ($Check2 & 0xF0F );
		$T2 = (((($Check1 & 0xFFFFC000) << 4) | ($Check1 & 0x3C00)) << 0xA) | ($Check2 & 0xF0F0000 );

		return ($T1 | $T2);
	}



	//genearate a checksum for the hash string
	function CheckHash($Hashnum) 
	{
		$CheckByte = 0;
		$Flag = 0;

		$HashStr = sprintf('%u', $Hashnum) ;
		$length = strlen($HashStr);

		for ($i = $length - 1;  $i >= 0;  $i --) {
			$Re = $HashStr{$i};
			if (1 === ($Flag % 2)) {              
				$Re += $Re;     
				$Re = (int)($Re / 10) + ($Re % 10);
			}
			$CheckByte += $Re;
			$Flag ++;   
		}

		$CheckByte %= 10;
		if (0 !== $CheckByte) {
			$CheckByte = 10 - $CheckByte;
			if (1 === ($Flag % 2) ) {
				if (1 === ($CheckByte % 2)) {
					$CheckByte += 9;
				}
				$CheckByte >>= 1;
			}
		}

		return '7'.$CheckByte.$HashStr;
	}



	function getch($url) 
	{ 
		return $this->CheckHash($this->HashURL($url)); 
	}


	/**Get Meta Tags ****/
	function extract_meta_tags($domain_name)
	{
		$tags = get_meta_tags($domain_name);
		return $tags;
	}


	/***** get content from searchengine ******/

	public function getContentFromSearchEngine($url, $proxy='')
	{

		$ch = curl_init(); // initialize curl handle
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
		curl_setopt($ch, CURLOPT_AUTOREFERER, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7);
		curl_setopt($ch, CURLOPT_REFERER, 'http://'.$url);
		curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); // times out after 50s
		curl_setopt($ch, CURLOPT_POST, 0); // set POST method

		/***** Proxy set for google . if lot of request gone, google will stop reponding. That's why it's should use some proxy *****/
		/**** Using proxy of public and private proxy both ****/
		if($this->proxy_ip!='')
			curl_setopt($ch, CURLOPT_PROXY, $this->proxy_ip);
		
		if($this->proxy_auth_pass!='')	
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy_auth_pass);
			

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies1.txt");
		curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies1.txt"); 
		$content = curl_exec($ch); // run the whole process	


		/****If it returns http code without 200 means caught by google, or redirect to captcha page*****/

		$get_info = curl_getinfo($ch);
		$httpcode=$get_info['http_code'];

		if($httpcode!='200'){
			return 0;
		}

		curl_close($ch);

		/** Remove html tag line <br> <b> in the email **/
		$content=str_replace("<b>", "", $content);
		$content=str_replace("</b>", "", $content);
		$content=str_replace("</br>", "", $content);
		$content=str_replace("<br>", "", $content);
		$content=str_replace("<br/>", "", $content);

		/*** These are specially for the bing search engine ***/
		$content=str_replace("<strong>", "", $content);
		$content=str_replace("</strong>", "", $content);
		$content=str_replace(",", "", $content);

		return $content;

	}

	

    public function get_email($content)
    {
        preg_match_all('/([\w+\.]*\w+@[\w+\.]*\w+[\w+\-\w+]*\.\w+)/is', $content, $results);
        return $results[1];
    }    


	 public function email_validate($email)
        {
            $email=trim($email);
            $is_valid=0;
            $is_exists=0;
            
            /***Validation check***/
            $pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';

            if (preg_match($pattern, $email) === 1) {
                $is_valid=1;
            }
            
             if($is_valid){
	            /*** MX record check ***/
	            @list($name, $domain)=explode('@', $email);
				
	            if (!checkdnsrr($domain, 'MX')) {
	                $is_exists=0;
	            } else {
	                $is_exists=1;
	            } 	
			}
                        
            $result['is_valid']=$is_valid;
            $result['is_exists']=$is_exists;
            return $result;
        }








	function get_general_content($url,$proxy=""){
			
			
			$ch = curl_init(); // initialize curl handle
           /* curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);*/
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
            curl_setopt($ch, CURLOPT_AUTOREFERER, false);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7);
            curl_setopt($ch, CURLOPT_REFERER, 'http://'.$url);
            curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
            curl_setopt($ch, CURLOPT_FAILONERROR, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            curl_setopt($ch, CURLOPT_TIMEOUT, 60); // times out after 60s
            curl_setopt($ch, CURLOPT_POST, 0); // set POST method

           /**** Using proxy of public and private proxy both ****/
		if($this->proxy_ip!='')
			curl_setopt($ch, CURLOPT_PROXY, $this->proxy_ip);
		
		if($this->proxy_auth_pass!='')	
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy_auth_pass);
		 
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");
            curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt");
            
            $content = curl_exec($ch); // run the whole process
			
            curl_close($ch);
			
			return $content;
			
	}




	function get_general_content_lower_time($url,$proxy=""){
			
			
			$ch = curl_init(); // initialize curl handle
           /* curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);*/
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
            curl_setopt($ch, CURLOPT_AUTOREFERER, false);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7);
            curl_setopt($ch, CURLOPT_REFERER, 'http://'.$url);
            curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
            curl_setopt($ch, CURLOPT_FAILONERROR, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            curl_setopt($ch, CURLOPT_TIMEOUT, 15); // times out after 50s
            curl_setopt($ch, CURLOPT_POST, 0); // set POST method

           /**** Using proxy of public and private proxy both ****/
		// if($this->proxy_ip!='')
		// 	curl_setopt($ch, CURLOPT_PROXY, $this->proxy_ip);
		
		// if($this->proxy_auth_pass!='')	
		// 	curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy_auth_pass);
		 
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");
            curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt");
            
            $content = curl_exec($ch); // run the whole process
			
            curl_close($ch);
			
			return $content;
			
	}
	

	
	function clean_domain_name($domain){

 		$domain=trim($domain);
		$domain=strtolower($domain);
		
		$domain=str_replace("www.","",$domain);
		$domain=str_replace("http://","",$domain);
		$domain=str_replace("https://","",$domain);
		$domain=str_replace("/","",$domain);
		
		return $domain; 
	}


		


	public function youtube_get_content($video_id){
	
			$url= "https://www.youtube.com/watch?v={$video_id}";
			
			$ch = curl_init(); 
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (FM Scene 4.6.1)");
            curl_setopt($ch, CURLOPT_AUTOREFERER, false);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7);
            curl_setopt($ch, CURLOPT_REFERER, 'http://'.$url);
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_FAILONERROR, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($ch, CURLOPT_TIMEOUT, 120); 

           /**** Using proxy of public and private proxy both ****/
		if($this->proxy_ip!='')
			curl_setopt($ch, CURLOPT_PROXY, $this->proxy_ip);
		
		if($this->proxy_auth_pass!='')	
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy_auth_pass);
		 
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");
            curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt");
            
            $content = curl_exec($ch); // run the whole process
			
            curl_close($ch);
			
			return $content;
	}	
	


	public function youtube_auto_keyword_suggestion($keyword="")
	{
		$keyword=urlencode($keyword);
		$url="http://suggestqueries.google.com/complete/search?client=firefox&ds=yt&q={$keyword}";
		$content=$this->get_general_content($url);
		$result=json_decode($content,true);
		return $result;
	}





}


?>