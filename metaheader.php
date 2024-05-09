<?php
require_once 'core/init.php';

if (!isset($_SESSION['user_no'])) {
	// not logged in
	Redirect::to("index.php");
}
?>