<?php
header('Content-Type: image/jpg');

if(isset($_SESSION['type_map']) && isset($_SESSION['id_map'])) {

	$im = imagecreatefromjpeg(''.this::$_PAGE_URL.'resources/images/map_full.jpg');

	if($_SESSION['type_map'] === 'vehicle') {
		$ds = connect::$g_con->prepare('SELECT * FROM `cars` WHERE `ID` = ? LIMIT 1');
		
	}
	else if($_SESSION['type_map'] === 'house') {
		$ds = connect::$g_con->prepare('SELECT * FROM `houses` WHERE `ID` = ? LIMIT 1');
		
	}
	else if($_SESSION['type_map'] === 'business') {
		$ds = connect::$g_con->prepare('SELECT * FROM `bizz` WHERE `ID` = ? LIMIT 1');
		
	}

	$ds->execute(array($_SESSION['id_map']));
	$data = $ds->fetch(PDO::FETCH_OBJ);

	switch($_SESSION['type_map']) {
		case 'vehicle':
			$x = $data->Locationx/3.9;
			$y = $data->Locationy/3.9;
			$x = $x + 768;
			$y = -($y - 768);
			$icon = imagecreatefromgif(''.this::$_PAGE_URL.'resources/images/vehicle.gif');
			imagecopyresized($im,$icon,$x,$y,0,0,50,50,16,16);
			break;
		case 'house':
			$x = $data->Entrancex/3.9;
			$y = $data->Entrancey/3.9;
			$x = $x + 768;
			$y = -($y - 768);
			$icon = imagecreatefromgif(''.this::$_PAGE_URL.'resources/images/house_owned.gif');
			imagecopyresized($im,$icon,$x,$y,0,0,50,50,16,16);
			break;
		case 'business':
			$x = $data->EntranceX/3.9;
			$y = $data->EntranceY/3.9;
			$x = $x + 768;
			$y = -($y - 768);
			$icon = imagecreatefromgif(''.this::$_PAGE_URL.'resources/images/business.gif');
			imagecopyresized($im,$icon,$x,$y,0,0,50,50,16,16);
			break;
	}

	$x = $x + 768;
	$y = -($y - 768);

	$red = imagecolorallocate($im, 255, 0, 0);

	imagefilledrectangle($im, $x, $y, $x+15, $y + 15, $red);

	ob_start();
	$im = imagejpeg($im);

	// $outputBuffer = ob_get_clean();
	// $base64 = base64_encode($outputBuffer);

	// echo json_encode(array('image'=>$base64));
	return; 
}
$im = imagecreatefrompng(''.this::$_PAGE_URL.'resources/images/map_legend.png');


$vehicle = imagecreatefromgif(''.this::$_PAGE_URL.'resources/images/vehicle.gif');
$business = imagecreatefromgif(''.this::$_PAGE_URL.'resources/images/business.gif');
$business_owned = imagecreatefromgif(''.this::$_PAGE_URL.'resources/images/business_owned.gif');
$house = imagecreatefromgif(''.this::$_PAGE_URL.'resources/images/house.gif');
$house_owned = imagecreatefromgif(''.this::$_PAGE_URL.'resources/images/house_owned.gif');

$q = connect::$g_con->prepare('SELECT * FROM `houses` WHERE `ID` != 0');
$q->execute();
while($data = $q->fetch(PDO::FETCH_OBJ)) {

	$x = ($data->Entrancex/3.9 + 768);
	$y = -(($data->Entrancey/3.9) - 768);

	if($data->Owner == "AdmBot")
		imagecopyresized($im,$house,$x,$y,0,0,35,40,16,16);
	else 
		imagecopyresized($im,$house_owned,$x,$y,0,0,35,40,16,16);
}

$q = connect::$g_con->prepare('SELECT * FROM `bizz` WHERE `ID` != 0');
$q->execute();
while($data = $q->fetch(PDO::FETCH_OBJ)) {

	$x = ($data->EntranceX/3.9 + 768);
	$y = -(($data->EntranceY/3.9) - 768);

	if($data->Owner != "AdmBot")
		imagecopyresized($im,$business_owned,$x,$y,0,0,35,40,16,16);
	else 
		imagecopyresized($im,$business,$x,$y,0,0,35,40,16,16);
}

$black = imagecolorallocate($im, 0, 0, 0);
$font = ''.this::$_PAGE_URL.'resources/images/GOTHIC.TTF';

/*$q = connect::$g_con->prepare('SELECT * FROM `turfs` WHERE `ID` != 0');
$q->execute();
while($data = $q->fetch(PDO::FETCH_OBJ)) {

	$x1 = ($data->MinX/3.9 + 768);
	$y1 = -(($data->MinY/3.9) - 768);

	$x2 = ($data->MaxX/3.9 + 768);
	$y2 = -(($data->MaxY/3.9) - 768);
	
	// $color = sscanf(Arrays::$_factions[$data->Owned]['color'], "#%02x%02x%02x");
	$color = imagecolorallocatealpha($im, $color[0], $color[1], $color[2],95);

	imagefilledrectangle($im, $x1, $y1, $x2, $y2, $color);
	
	$xCenter = ($x1 + $x2) / 2;
	$yCenter = ($y1 + $y2) / 2;
	
	
	imagettftext($im, 15, 0, $xCenter, $yCenter, $black, $font, $data->ID);
	
	imagerectangle($im, $x1, $y1, $x2, $y2, $black);
	
}*/

imagepng($im);
?>