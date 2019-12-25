<?php
session_start();
require(__DIR__ . "/public/modules/db.pdo.php");
date_default_timezone_set("Asia/Bangkok");
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>DMSU RUN 2020 - Developed by @AOM [Charity]</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="public/assets/css/kanit.css" rel="stylesheet">
	<link rel="stylesheet" href="public/assets/css/uikit.min.css" />
	<link rel="stylesheet" href="public/assets/css/styles.css">
	<script src="public/assets/js/uikit.min.js"></script>
	<script src="public/assets/js/uikit-icons.min.js"></script>
	<script src="public/assets/js/jquery.js"></script>
</head>

<body>

<div align="center">

<?php 
if(isset($_SESSION["uid"]) and is_numeric($_SESSION["uid"]) and $_SESSION["uid"] > 0){
	$loggedinAllowed = array("regisRun","groupRun","home","logout");
	if(isset($_GET["page"]) and in_array($_GET["page"], $loggedinAllowed)){
		include __DIR__ . '/public/render/'.$_GET["page"].'.php';
	} else{
		include __DIR__ . '/public/render/home.php';
	}
} else{
	$nonLoginAllowed = array("login","register");
	if(isset($_GET["page"]) and in_array($_GET["page"], $nonLoginAllowed)){
		include __DIR__ . '/public/render/'.$_GET["page"].'.php';
	} else{
		include __DIR__ . '/public/render/login.php';
	}
}
?>

<div class="bottom">
	<div style="margin-bottom: 10px;color:rgba(255,255,255,0.6);text-shadow: 1px 1px 2px rgba(0,0,0,0.75);">
	<font size="2"><span uk-icon="icon:instagram; ratio:0.5"></span> aom.s__ | <span uk-icon="icon:facebook; ratio:0.5"></span> Aom Siriwat</font>
	</div>
</div>

</div>

</body>

</html>