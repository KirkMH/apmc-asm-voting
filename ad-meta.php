<?php
require_once 'core/init.php';

if (!isset($_SESSION['user_no'])) {
	// not logged in
	Redirect::to("ad-log.php");
	//$_SESSION['name'] = "Tester";
}

$url = basename($_SERVER['PHP_SELF']);
?>