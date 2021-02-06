<?php
	echo '<center>This tentative was registered with successful!<br><br>Your IP adress was sended : ';
	$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	echo '<br>';
	echo $hostname;
	echo '</center>';
	exit;
?>