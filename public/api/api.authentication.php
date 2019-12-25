<?php
session_start();
require("../modules/db.pdo.php");
require("../modules/config.inc.php");
$array = array("error"=>true,"message"=>"Not found request");
//ID Card check
function isValidNationalId(string $nationalId)
{
  if (strlen($nationalId) === 13) {
    $digits = str_split($nationalId);
    $lastDigit = array_pop($digits);
    $sum = array_sum(array_map(function($d, $k) {
      return ($k+2) * $d;
    }, array_reverse($digits), array_keys($digits)));
    return $lastDigit === strval((11 - $sum % 11) % 10);
  }
  return false;
}

if(isset($_POST["method"])){

	$db = new database($config["user"], $config["pass"], $config["host"], $config["db"]);
	switch(intval($_POST["method"])){
		case 0: //login
			if(isset($_POST["telphone"]) and isset($_POST["idcard"])){
				if(!is_numeric($_POST["telphone"]) or !is_numeric($_POST["idcard"])){
					$array["message"] = "รูปแบบตัวเลขไม่ถูกต้อง";
				} else if(strlen($_POST["telphone"]) != 10 || strlen($_POST["idcard"]) != 13){
					$array["message"] = "ข้อมูลไม่ถูกต้อง";
				} else{
					$sqlRow = "SELECT uid,admin FROM accounts WHERE phone=? AND idcard=?";
					$sqlParam = array($_POST["telphone"],$_POST["idcard"]);
					$checkRow = $db->getNumber($sqlRow,$sqlParam);
					if($checkRow <= 0){
						$array["message"] = "เข้าสู่ระบบไม่สำเร็จ";
					} else{
						$getUID = $db->getRow($sqlRow,$sqlParam); //Get UID from Database
						$_SESSION["uid"] = $getUID["uid"]; //Set UID to session
						$_SESSION["admin"] = boolval($getUID["admin"]);

						$array["error"] = false;
						$array["message"] = "เข้าสู่ระบบสำเร็จ";
					}
				}
			}
		break;
		case 1: //register
			if(isset($_POST["telphone"]) and isset($_POST["idcard"]) and isset($_POST["firstn"]) and isset($_POST["lastn"]) and isset($_POST["gender"]) and isset($_POST["birthday"]) or isset($_POST["email"])){
				if(!is_numeric($_POST["telphone"]) or !is_numeric($_POST["idcard"])){
					$array["message"] = "รูปแบบตัวเลขไม่ถูกต้อง";
				} else if((strlen($_POST["email"])>=1 and !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))){
					$array["message"] = "รูปแบบอีเมลไม่ถูกต้อง";
				} else if (!is_numeric($_POST["gender"]) or $_POST["gender"]>1 or $_POST["gender"]<0) {
					$array["message"] = "เพศไม่ถูกต้อง";
				} else if(isValidNationalId($_POST["idcard"])!==true){
					$array["message"] = "หมายเลขบัตรประชาชนไม่ถูกต้อง";
				} else if(!preg_match('/^[0-9]{10}+$/', $_POST["telphone"])){
					$array["message"] = "เบอร์โทรศัพท์ไม่ถูกต้อง";
				} else{
					$sqlRow = "SELECT uid FROM accounts WHERE phone=? OR idcard=? OR email=?";
					$sqlParam = array($_POST["telphone"],$_POST["idcard"],$_POST["email"]);
					$checkRow = $db->getNumber($sqlRow,$sqlParam);
					if($checkRow > 0){
						$array["message"] = "ข้อมูลถูกลงทะเบียนไปแล้ว";
					} else{
						$sqlInsert = "INSERT INTO accounts(uid,idcard,phone,first_name,last_name,gender,birthday,email) VALUES (NULL,?,?,?,?,?,?,?);";
						$sqlParam = array(($_POST["idcard"]),($_POST["telphone"]),$_POST["firstn"],$_POST["lastn"],intval($_POST["gender"]),strtotime($_POST["birthday"]),$_POST["email"]);
						$db->insertRow($sqlInsert,$sqlParam);
						$array["error"] = false;
						$array["message"] = "สมัครสมาชิกสำเร็จ";
					}
				}
			}
		break;
	}

}
echo json_encode($array);
?>