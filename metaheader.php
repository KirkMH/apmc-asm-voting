<?php
require_once 'core/init.php';
var_dump($_SESSION);
if (!isset($_SESSION['user_no'])) {
	// not logged in
	Redirect::to("index.php");
}
?>