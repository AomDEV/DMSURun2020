<?php
session_start();
require(__DIR__ . "/../modules/db.pdo.php");
require(__DIR__ . "/../modules/config.inc.php");
$array = array("error"=>true,"message"=>"Not found request");
error_reporting(E_ALL);
ini_set("display_errors", 1);
date_default_timezone_set("Asia/Bangkok");

if(!isset($_SESSION["uid"])){$array["message"] = "ไม่มีสิทธิ์";} else{
	if(isset($_POST["date"]) and isset($_POST["time"]) and isset($_FILES['file']) and isset($_POST["amount"])){
		$uid = $_SESSION["uid"];
		$convertToTime = strtotime($_POST["date"]." ".$_POST["time"]);
		$maxFileSize = 2 * 10e6;
		$image = $_FILES["file"];
		$imageData = getimagesize($image['tmp_name']);
		$mimeType = $imageData['mime'];
		$allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
		$db = new database($config["user"], $config["pass"], $config["host"], $config["db"]);
		if($image['error'] !== 0 and $image['error'] === 1){
			$array["message"] = "ขนาดไฟล์ใหญ่เกินระบบกำหนด";
		} else if (!file_exists($image['tmp_name'])){
			$array["message"] = "ไม่พบไฟล์บนเซิร์ฟเวอร์";
		} else if ($image['size'] > $maxFileSize){
			$array["message"] = "ขนาดไฟล์ใหญ่เกินกำหนด";
		} else if(!$imageData){
			$array["message"] = "รูปภาพไม่ถูกต้อง";
		} else if(!in_array($mimeType, $allowedMimeTypes)){
			$array["message"] = "รองรับไฟล์ JPEG, PNG and GIFs เท่านั้น";
		} else if($db->getNumber("select rid from payment_report where uid=? and (status=1 or status=0)",array($uid)) > 0){
			$array["message"] = "คุณได้ส่งหลักฐานไปแล้ว";
		} else if(!is_numeric($_POST["amount"]) or intval($_POST["amount"]) <= 0){
			$array["message"] = "ยอดเงินไม่ถูกต้อง";
		} else{
			$storageDir = __DIR__ . '/../../storage';
			if(!file_exists($storageDir) or !is_dir($storageDir)){
				@mkdir($storageDir, 0777, true);
			}
			$userDir = __DIR__ . '/../../storage/user_'.$uid;
			if (!file_exists($userDir) or !is_dir($userDir)) {
				@mkdir($userDir, 0777, true);
			}

			$temp = explode(".", $image['name']);
			$newFileName = round(time()) . '.' . end($temp);
			$imagepath=__DIR__ . "/../../storage/user_".$uid."/".$newFileName;
			$amt = intval($_POST["amount"]);
			move_uploaded_file($image['tmp_name'],$imagepath);

			$db->insertRow("INSERT INTO payment_report (rid,file_name,date,uid,status,amount) VALUES (NULL,?,?,?,?,?);",array($newFileName,$convertToTime,$uid,0,$amt));
			$array["error"] = false;
			$array["message"] = "อัพโหลดไฟล์สำเร็จ!";
		}
	}
}
echo json_encode($array);
?>