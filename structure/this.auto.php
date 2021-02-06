<?php

$navigateur = $_SERVER["HTTP_USER_AGENT"];
$bannav = Array('HTTrack','httrack','WebCopier','HTTPClient','websitecopier','webcopier');

foreach ($bannav as $banni) {
	$comparaison = strstr($navigateur, $banni);
	if($comparaison!==false) {
		echo '<center>ok brother</center>';
		exit;
	}
}


class this {
	private static $instance;
	public static $pdo;
	public static $htmlpurifier;
	public static $_url = array();
	public static $_vehicles = array();
	public static $_vehColors = array();
	private static $_perPage = 18;

	public function __construct() {
		connect::init();
		self::_getUrl();
		self::arrays();
	}

	private static function _getUrl() {
		$url = isset($_GET['page']) ? $_GET['page'] : null;
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        self::$_url = explode('/', $url);
	}

	public static function init() {
		$url = isset($_GET['page']) ? $_GET['page'] : null;
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        self::$_url = explode('/', $url);

		if (is_null(self::$instance))
		{
			self::$instance = new self();
		}
		return self::$instance;
	}

	public static function getContent() {
		require_once 'structure/HTMLPurifier.auto.php';
		include_once 'views/general/header.cy.php';
		if(self::$_url[0] === 'action') { include 'actions/' . self::$_url[1] . '.a.php'; return; }
		if(isset(self::$_url[0]) && !strlen(self::$_url[0]))
			include_once 'views/index.cy.php';
		else if(file_exists('views/' . self::$_url[0] . '.cy.php')) 
			include 'views/' . self::$_url[0] . '.cy.php';
		else
			include_once 'views/index.cy.php'; 
		include_once 'views/general/footer.cy.php';
	}

	public static function alert($type,$message) {
		return '<div class="alert alert-'.$type.'">'.$message.'</div>';
	}

	public static function format($number) {
		return number_format($number,0,'.','.');
	}

	public static function isAjax() {
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
			return true;
		}
		return false;
	}

	public static function date($data,$reverse = false) {
		return (!$reverse ? date('H:i:s d-m-Y',$data) : date('d-m-Y H:i:s',$data));
	}

	public static function getDate($timestamp,$time = true){
		if(!$timestamp) return 1;
		$difference = time() - $timestamp;
		if($difference == 0)
			return 'just now';
		$periods = array("second", "minute", "hour", "day", "week",
		"month", "year", "decade");
		$lengths = array("60","60","24","7","4.35","12","10");
		if ($difference > 0) {
			$ending = "ago";
		} else {
			$difference = -$difference;
			$ending = "to go";
		}
		if(!$difference) return 'just now';
		for($j = 0; $difference >= $lengths[$j]; $j++)
		$difference /= $lengths[$j];
		$difference = round($difference);
		if($difference != 1) $periods[$j].= "s";
		if($time) $text = "$difference $periods[$j]";
		else $text = "$difference $periods[$j] $ending";
		return $text;
	}

	public static function getSpec($table, $data, $name, $value) {
		$q = connect::$g_con->prepare('SELECT `'.$data.'` FROM `'.$table.'` WHERE `'.$name.'` = ?');
		$q->execute(array($value));
		$r_data = $q->fetch();
		return $r_data[$data];
	}

	public static function getName($user, $name = true) {
		if($name == true) {
			$wd = connect::$g_con->prepare('SELECT `name` FROM `users` WHERE `name` = ?');
			$wd->execute(array($user));
			if($wd->rowCount()) {
				$r_data = $wd->fetch();
				$text = $r_data['name'];
			} else $text = $user;
		} else {
			$wd = connect::$g_con->prepare('SELECT `name` FROM `users` WHERE `id` = ?');
			$wd->execute(array($user));
			if($wd->rowCount()) {
				$r_data = $wd->fetch();
				$text = $r_data['name'];
			} else $text = $user;
		}
		return $text;
	}
	
	public static function getID($user) {
		$wd = connect::$g_con->prepare('SELECT `id` FROM `users` WHERE `name` = ?');
		$wd->execute(array($user));
		if($wd->rowCount()) {
			$r_data = $wd->fetch();
			$text = $r_data['id'];
		} else $text = $user;
		return $text;
	}

	public static function getUser() {
		return (isset($_SESSION['user']) ? $_SESSION['user'] : false);
	}
	
	public static function getNameFromID($id) {
		$wc = connect::$g_con->prepare('SELECT `name` FROM `users` WHERE `id` = ?');
		$wc->execute(array($id));
		$r_data = $wc->fetch();
		return $r_data['name'];
	}

	public static function timeFuture($time_ago)
	{
		$cur_time   = time();
		$time_elapsed   = $time_ago - $cur_time;
		$days       = round($time_elapsed / 86400 );

		if($days > -1){
			return "in $days days";
		 }else {
			return "$days days ago";
		}
	}
	public static function timeAgo($time_ago, $icon = true)
	{
		$time_ago = strtotime($time_ago);
		$cur_time   = time();
		$time_elapsed   = $cur_time - $time_ago;
		$seconds    = $time_elapsed ;
		$minutes    = round($time_elapsed / 60 );
		$hours      = round($time_elapsed / 3600);
		$days       = round($time_elapsed / 86400 );
		$weeks      = round($time_elapsed / 604800);
		$months     = round($time_elapsed / 2600640 );
		$years      = round($time_elapsed / 31207680 );

		if($seconds <= 60){
			return "chiar acum";
		}
		else if($minutes <=60){
			if($minutes==1){
				return "acum 1 minut";
			}
			else{
				return "acum $minutes minute";
			}
		}
		else if($hours <=24){
			if($hours==1){
				return "acum o ora";
			}else{
				return "acum $hours ore";
			}
		}
		else if($days <= 7){
			if($days==1){
				return "ieri";
			}else{
				return "acum $days zile";
			}
		}
		else if($weeks <= 4.3){
			if($weeks==1){
				return "acum o saptamana";
			}else{
				return "acum $weeks saptamani";
			}
		}
		else if($months <=12){
			if($months==1){
				return "acum o luna";
			}else{
				return "acum $months luni";
			}
		}
		else{
			if($years==1){
				return "acum 1 an";
			}else{
				return "acum $years ani";
			}
		}
	}


	public static function getData($table,$data,$id) {
			$q = connect::$g_con->prepare('SELECT `'.$data.'` FROM `'.$table.'` WHERE `id` = ?');
			$q->execute(array($id));
			$r_data = $q->fetch();
			return $r_data[$data];
	}
	
	public static function getData2($table,$cescoti,$cebagi,$data) {
		$q = connect::$g_con->prepare('SELECT `'.$cescoti.'` FROM `'.$table.'` WHERE `'.$cebagi.'` = ?');
		$q->execute(array($data));
		$r_data = $q->fetch();
		return $r_data[$cescoti];
	}

	public static function limit() {

		if(!isset(self::$_url[2]))

			self::$_url[2] = 1;

		return "LIMIT ".((self::$_url[2] * self::$_perPage) - self::$_perPage).",".self::$_perPage;

	}



	public static function create($rows) {

		if(!isset(self::$_url[2]))

			self::$_url[2] = 1;

		$adjacents = "2";

		$prev = self::$_url[2] - 1;

		$next = self::$_url[2] + 1;

		$lastpage = ceil($rows/self::$_perPage);

		$lpm1 = $lastpage - 1;



		$pagination = "<ul class='pagination justify-content-center'>";

		if($lastpage > 1)

		{

			if($prev != 0)

				$pagination.= "<li class='previous_page btn btn-dark flat'><a href='".self::$_PAGE_URL.self::$_url[0]."/page/1'>« First</a></li>";  

			else 

				$pagination.= "<li class='btn btn-dark flat previous_page disabled'><a>« First</a></li>";  

			if ($lastpage < 7 + ($adjacents * 2))

			{   

				for ($counter = 1; $counter <= $lastpage; $counter++)

				{

					if ($counter == self::$_url[2])

						$pagination.= "<li class='active btn btn-dark flat'><a href='#'>$counter</a></li>";

					else

						$pagination.= "<li class='btn btn-dark flat'><a href='".self::$_PAGE_URL.self::$_url[0]."/page/$counter'>$counter</a></li>";                   

				}

			}

			elseif($lastpage > 5 + ($adjacents * 2))

			{

				if(self::$_url[2] < 1 + ($adjacents * 2))       

				{

					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)

					{

						if ($counter == self::$_url[2])

							$pagination.= "<li class='active btn btn-dark flat'><a href='#'>$counter</a></li>";

						else

							$pagination.= "<li class='btn btn-dark flat'><a href='".self::$_PAGE_URL.self::$_url[0]."/page/$counter'>$counter</a></li>";                   

					}

					$pagination.= "<li class='dots btn btn-dark flat'><a href='#'>...</a></li>";

					$pagination.= "<li class='btn btn-dark flat'><a href='".self::$_PAGE_URL.self::$_url[0]."/page/$lpm1'>$lpm1</a></li>";

					$pagination.= "<li class='btn btn-dark flat'><a href='".self::$_PAGE_URL.self::$_url[0]."/page/$lastpage'>$lastpage</a></li>";       

				}

				elseif($lastpage - ($adjacents * 2) > self::$_url[2] && self::$_url[2] > ($adjacents * 2))

				{

					$pagination.= "<li class='btn btn-dark flat'><a href='".self::$_PAGE_URL.self::$_url[0]."/page/1'>1</a></li>";

					$pagination.= "<li class='btn btn-dark flat'><a href='".self::$_PAGE_URL.self::$_url[0]."/page/2'>2</a></li>";

					$pagination.= "<li class='dotsbtn btn-dark flat'><a href='#'>...</a></li>";

					for ($counter = self::$_url[2] - $adjacents; $counter <= self::$_url[2] + $adjacents; $counter++)

					{

						if ($counter == self::$_url[2])

							$pagination.= "<li class='activebtn btn-dark flat'><a href='#'>$counter</a></li>";

						else

							$pagination.= "<li class='btn btn-dark flat'><a href='".self::$_PAGE_URL.self::$_url[0]."/page/$counter'>$counter</a></li>";                   

					}

					$pagination.= "<li class='dotsbtn btn-dark flat'><a href='#'>...</a></li>";

					$pagination.= "<li class='btn btn-dark flat'><a href='".self::$_PAGE_URL.self::$_url[0]."/page/$lpm1'>$lpm1</a></li>";

					$pagination.= "<li class='btn btn-dark flat'><a href='".self::$_PAGE_URL.self::$_url[0]."/page/$lastpage'>$lastpage</a></li>";      

				}

				else

				{

					$pagination.= "<li class='btn btn-dark flat'><a href='".self::$_PAGE_URL.self::$_url[0]."/page/1'>1</a></li>";

					$pagination.= "<li class='btn btn-dark flat'><a href='".self::$_PAGE_URL.self::$_url[0]."/page/2'>2</a></li>";

					$pagination.= "<li class='dotsbtn btn-dark flat'><a href='#'>...</a></li>";

					for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)

					{

						if ($counter == self::$_url[2])

							$pagination.= "<li class='activebtn btn-dark flat'><a href='#'>$counter</a></li>";

						else

							$pagination.= "<li class='btn btn-dark flat'><a href='".self::$_PAGE_URL.self::$_url[0]."/page/$counter'>$counter</a></li>";                   

					}

				}

			}

			if($lastpage == (isset(self::$_url[2]) ? self::$_url[2] : 1))

				$pagination.= "<li class='next_page disabled btn btn-dark flat'><a>Last »</a></li>";  

			else 

				$pagination.= "<li class='next_page btn btn-dark flat'><a href='".self::$_PAGE_URL.self::$_url[0]."/page/$lastpage'>Last »</a></li>";  

		}

		$pagination .= "</ul>";

		return $pagination;

	}

	public static $_PAGE_URL = 'https://localhost/advanced-rp/';

	public static function getPage() {
		return isset(self::$_url[2]) ? self::$_url[2] : 1;
	}

	public static function isActive($active) {
		if(is_array($active)) {
			foreach($active as $ac) {
				if($ac === self::$_url[0]) return ' class="active"';
			}
			return;
		} else return self::$_url[0] === $active ? ' class="active"' : false;
	}

	public static function makeNotification($userid,$username,$notif,$vid,$vname,$link) {
		$notify = connect::$g_con->prepare('INSERT INTO `panel_notifications` (`UserID`,`UserName`,`Notification`,`FromID`,`FromName`,`Link`) VALUES (?, ?, ?, ?, ?, ?)');
		$notify->execute(array($userid,$username,$notif,$vid,$vname,$link));
		return 1;
	}

	public static function Protejez($data)
	{
	$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
	$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
	$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
	$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

	$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

	$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

	$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

	do
	{
	    $old_data = $data;
	    $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
	}
	while ($old_data !== $data);

	return $data;
	}
	
	public static function Curat($text = null) {
		if (strpos($text, 'script') !== false) return 1;
		if (strpos($text, 'meta') !== false) return 1;
		return preg_replace_callback($regex, function ($matches) {
			return '<a target="_blank" href="'.$matches[0].'">'.$matches[0].'</a>';
		}, $text);
	}

	private static function arrays() {
		self::$_vehicles = array(
			400 => "Landstalker", 401 => "Bravura", 402 => "Buffalo", 403 => "Linerunner", 404 => "Perrenial", 405 => "Sentinel", 406 => "Dumper", 407 => "Firetruck",
			408 => "Trashmaster", 409 => "Stretch", 410 => "Manana", 411 => "Infernus", 412 => "Voodoo", 413 => "Pony", 414 => "Mule", 415 => "Cheetah",
			416 => "Ambulance", 417 => "Leviathan", 418 => "Moonbeam", 419 => "Esperanto", 420 => "Taxi", 421 => "Washington", 422 => "Bobcat", 423 => "Whoopee",
			424 => "BFInjection", 425 => "Hunter", 426 => "Premier", 427 => "Enforcer", 428 => "Securicar", 429 => "Banshee", 430 => "Predator", 431 => "Bus",
			432 => "Rhino", 433 => "Barracks", 434 => "Hotknife", 435 => "Trailer", 436 => "Previon", 437 => "Coach", 438 => "Cabbie", 439 => "Stallion",
			440 => "Rumpo", 441 => "RCBandit", 442 => "Romero", 443 => "Packer", 444 => "Monster", 445 => "Admiral", 446 => "Squalo", 447 => "Seasparrow",
			448 => "Pizzaboy", 449 => "Tram", 450 => "Trailer", 451 => "Turismo", 452 => "Speeder", 453 => "Reefer", 454 => "Tropic", 455 => "Flatbed", 456 => "Yankee",
			457 => "Caddy", 458 => "Solair", 459 => "Berkley\'sRCVan", 460 => "Skimmer", 461 => "PCJ-600", 462 => "Faggio", 463 => "Freeway", 464 => "RCBaron",
			465 => "RCRaider", 466 => "Glendale", 467 => "Oceanic", 468 => "Sanchez", 469 => "Sparrow", 470 => "Patriot", 471 => "Quad", 472 => "Coastguard",
			473 => "Dinghy", 474 => "Hermes", 475 => "Sabre", 476 => "Rustler", 477 => "ZR-350", 478 => "Walton", 479 => "Regina", 480 => "Comet", 481 => "BMX",
			482 => "Burrito", 483 => "Camper", 484 => "Marquis", 485 => "Baggage", 486 => "Dozer", 487 => "Maverick", 488 => "NewsChopper", 489 => "Rancher",
			490 => "FBIRancher", 491 => "Virgo", 492 => "Greenwood", 493 => "Jetmax", 494 => "Hotring", 495 => "Sandking", 496 => "BlistaCompact",
			497 => "PoliceMaverick", 498 => "Boxville", 499 => "Benson", 500 => "Mesa", 501 => "RCGoblin", 502 => "HotringRacerA", 503 => "HotringRacerB",
			504 => "BloodringBanger", 505 => "Rancher", 506 => "SuperGT", 507 => "Elegant", 508 => "Journey", 509 => "Bike", 510 => "MountainBike",	511 => "Beagle",
			512 => "Cropduster", 513 => "Stunt", 514 => "Tanker", 515 => "Roadtrain", 516 => "Nebula", 517 => "Majestic", 518 => "Buccaneer", 519 => "Shamal",
			520 => "Hydra", 521 => "FCR-900", 522 => "NRG-500", 523 => "HPV1000", 524 => "CementTruck", 525 => "TowTruck", 526 => "Fortune", 527 => "Cadrona",
			528 => "FBITruck",529 => "Willard", 530 => "Forklift", 531 => "Tractor", 532 => "Combine", 533 => "Feltzer", 534 => "Remington", 535 => "Slamvan",
			536 => "Blade", 537 => "Freight",538 => "Streak", 539 => "Vortex", 540 => "Vincent", 541 => "Bullet", 542 => "Clover", 543 => "Sadler", 544 => "Firetruck",
			545 => "Hustler", 546 => "Intruder", 547 => "Primo", 548 => "Cargobob", 549 => "Tampa", 550 => "Sunrise", 551 => "Merit", 552 => "Utility", 553 => "Nevada",
			554 => "Yosemite", 555 => "Windsor", 556 => "Monster", 557 => "Monster", 558 => "Uranus", 559 => "Jester", 560 => "Sultan", 561 => "Stratium",
			562 => "Elegy", 563 => "Raindance", 564 => "RCTiger", 565 => "Flash", 566 => "Tahoma", 567 => "Savanna", 568 => "Bandito", 569 => "FreightFlat",
			570 => "StreakCarriage", 571 => "Kart", 572 => "Mower", 573 => "Dune", 574 => "Sweeper", 575 => "Broadway", 576 => "Tornado", 577 => "AT-400",
			578 => "DFT-30", 579 => "Huntley", 580 => "Stafford", 581 => "BF-400", 582 => "NewsVan", 583 => "Tug", 584 => "Trailer", 585 => "Emperor", 586 => "Wayfarer",
			587 => "Euros", 588 => "Hotdog", 589 => "Club", 590 => "FreightBox", 591 => "Trailer", 592 => "Andromada", 593 => "Dodo", 594 => "RCCam", 595 => "Launch",
			596 => "PoliceCar", 597 => "PoliceCar", 598 => "PoliceCar", 599 => "PoliceRanger", 600 => "Picador", 601 => "S.W.A.T", 602 => "Alpha", 603 => "Phoenix",
			604 => "Glendale", 605 => "Sadler", 606 => "Luggage", 607 => "Luggage", 608 => "Stairs", 609 => "Boxville", 610 => "Tiller", 611 => "UtilityTrailer"
		);

		self::$_vehColors = array(
			'#000000', '#F5F5F5', '#2A77A1', '#840410', '#263739', '#86446E', '#D78E10', '#4C75B7', '#BDBEC6', '#5E7072',
			'#46597A', '#656A79', '#5D7E8D', '#58595A', '#D6DAD6', '#9CA1A3', '#335F3F', '#730E1A', '#7B0A2A', '#9F9D94',
			'#3B4E78', '#732E3E', '#691E3B', '#96918C', '#515459', '#3F3E45', '#A5A9A7', '#635C5A', '#3D4A68', '#979592',
			'#421F21', '#5F272B', '#8494AB', '#767B7C', '#646464', '#5A5752', '#252527', '#2D3A35', '#93A396', '#6D7A88',
			'#221918', '#6F675F', '#7C1C2A', '#5F0A15', '#193826', '#5D1B20', '#9D9872', '#7A7560', '#989586', '#ADB0B0',
			'#848988', '#304F45', '#4D6268', '#162248', '#272F4B', '#7D6256', '#9EA4AB', '#9C8D71', '#6D1822', '#4E6881',
			'#9C9C98', '#917347', '#661C26', '#949D9F', '#A4A7A5', '#8E8C46', '#341A1E', '#6A7A8C', '#AAAD8E', '#AB988F',
			'#851F2E', '#6F8297', '#585853', '#9AA790', '#601A23', '#20202C', '#A4A096', '#AA9D84', '#78222B', '#0E316D',
			'#722A3F', '#7B715E', '#741D28', '#1E2E32', '#4D322F', '#7C1B44', '#2E5B20', '#395A83', '#6D2837', '#A7A28F',
			'#AFB1B1', '#364155', '#6D6C6E', '#0F6A89', '#204B6B', '#2B3E57', '#9B9F9D', '#6C8495', '#4D8495', '#AE9B7F',
			'#406C8F', '#1F253B', '#AB9276', '#134573', '#96816C', '#64686A', '#105082', '#A19983', '#385694', '#525661',
			'#7F6956', '#8C929A', '#596E87', '#473532', '#44624F', '#730A27', '#223457', '#640D1B', '#A3ADC6', '#695853',
			'#9B8B80', '#620B1C', '#5B5D5E', '#624428', '#731827', '#1B376D', '#EC6AAE', '#000000',
			'#177517', '#210606', '#125478', '#452A0D', '#571E1E', '#010701', '#25225A', '#2C89AA', '#8A4DBD', '#35963A',
			'#B7B7B7', '#464C8D', '#84888C', '#817867', '#817A26', '#6A506F', '#583E6F', '#8CB972', '#824F78', '#6D276A',
			'#1E1D13', '#1E1306', '#1F2518', '#2C4531', '#1E4C99', '#2E5F43', '#1E9948', '#1E9999', '#999976', '#7C8499',
			'#992E1E', '#2C1E08', '#142407', '#993E4D', '#1E4C99', '#198181', '#1A292A', '#16616F', '#1B6687', '#6C3F99',
			'#481A0E', '#7A7399', '#746D99', '#53387E', '#222407', '#3E190C', '#46210E', '#991E1E', '#8D4C8D', '#805B80',
			'#7B3E7E', '#3C1737', '#733517', '#781818', '#83341A', '#8E2F1C', '#7E3E53', '#7C6D7C', '#020C02', '#072407',
			'#163012', '#16301B', '#642B4F', '#368452', '#999590', '#818D96', '#99991E', '#7F994C', '#839292', '#788222',
			'#2B3C99', '#3A3A0B', '#8A794E', '#0E1F49', '#15371C', '#15273A', '#375775', '#060820', '#071326', '#20394B',
			'#2C5089', '#15426C', '#103250', '#241663', '#692015', '#8C8D94', '#516013', '#090F02', '#8C573A', '#52888E',
			'#995C52', '#99581E', '#993A63', '#998F4E', '#99311E', '#0D1842', '#521E1E', '#42420D', '#4C991E', '#082A1D',
			'#96821D', '#197F19', '#3B141F', '#745217', '#893F8D', '#7E1A6C', '#0B370B', '#27450D', '#071F24', '#784573',
			'#8A653A', '#732617', '#319490', '#56941D', '#59163D', '#1B8A2F', '#38160B', '#041804', '#355D8E', '#2E3F5B',
			'#561A28', '#4E0E27', '#706C67', '#3B3E42', '#2E2D33', '#7B7E7D', '#4A4442', '#28344E'
		);
	}

}