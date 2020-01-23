<?php
session_start();
require(__DIR__ . "/../modules/db.pdo.php");
require(__DIR__ . "/../modules/config.inc.php");
error_reporting(E_ALL);
ini_set("display_errors", 1);
if(!isset($_SESSION["uid"])){die ("Access denied");} else{
    if(isset($_GET["id"]) and is_numeric($_GET["id"])){
        $runid = intval($_GET["id"]);
        $myUID = $_SESSION["uid"];
        $imagePath = __DIR__."/../../public/assets/img/cert_template.png";
        $fontPathBold = __DIR__."/../../public/assets/font/kanit_bold.ttf";
        $fontPathRegular = __DIR__."/../../public/assets/font/kanit_regular.ttf";
        $image = imagecreatefrompng($imagePath);
        $im = @imagecreatetruecolor(imagesx($image),imagesy($image));
        $background_color = imagecolorallocate($im, 255, 255, 255);
        imagefill($im, 0, 0, $background_color);
        imagecopy($im, $image, 0, 0, 0, 0, imagesx($image),imagesx($image));
        $textColor = imagecolorallocate($im, 0, 0, 0);

        $db = new database($config["user"], $config["pass"], $config["host"], $config["db"]);
        $runSQL = "select * from run where runid=?";
        if($db->getNumber($runSQL,array($runid))){
            $getRun = $db->getRow($runSQL,array($runid));
            if(!$_SESSION["admin"]){
                if($getRun["uid"]!=$myUID and $getRun["follower"]!=$myUID){die("access denied");}
            }
            if($getRun["follower"]==0){
                //Account
                $getAccount = $db->getRow("select * from accounts where uid=?",array($getRun["uid"]));
                $textName = $getAccount["first_name"] . " " . $getAccount["last_name"];
            } else{
                $textName = $getRun["follow_name"];
            }
            $runTypeLabel=0;
            $runLabel=0;
            if(isset($getRun["isVIP"])){
				$runTypeLabel = ($getRun["isVIP"]==true)?$runSubType[$getRun["runType"]]:(($getRun["runType"]==2)?$ageType[$getRun["subType"]]:"ปกติ");
                $runLabel = ($getRun["isVIP"]==true)?"VIP":$runType[$getRun["runType"]];
                if($getRun["isVIP"]==true){$runLabel.=" (".$runTypeLabel.")";}
			}
        } else{
            die("not found data");
        }
        $textType = $runLabel;
        $textSubType = $runTypeLabel;

        //Place Name
        $keyword = array("นาย","นาง","นางสาว","เด็กชาย","เด็กหญิง","คุณ");
        foreach($keyword as $w){ $textName=str_replace($w,"",$textName); }
        $textName = "คุณ".$textName;
        $fontSizeName = 60;
        $dimensionsName = imagettfbbox($fontSizeName, 0, $fontPathBold, $textName); 
        $xName = (imagesx($image) - ($dimensionsName[2] - $dimensionsName[0])) / 2; 
        $yName = imagesy($image)/2.7;
        imagettftext($im, $fontSizeName, 0, $xName, $yName, $textColor, $fontPathBold, $textName);

        //Place Type
        $fontSizeType = 40;
        $xType = (imagesx($image)/2.3); 
        $yType = (imagesy($image)/1.825)-5;
        imagettftext($im, $fontSizeType, 0, $xType, $yType, $textColor, $fontPathBold, $textType);

        //PlaceSubType
        if($getRun["isVIP"]!=true){
            $fontSizeSubType = 25;
            $xSubType = (imagesx($image)/2.3); 
            $ySubType = (imagesy($image)/1.75);
            imagettftext($im, $fontSizeSubType, 0, $xSubType, $ySubType, $textColor, $fontPathRegular, $textSubType);
        }

        header('Content-type: image/png');
        imagepng($im);
        imagedestroy($image);
        imagedestroy($im);
    } else{
        die("unknow");
    }
}
?>