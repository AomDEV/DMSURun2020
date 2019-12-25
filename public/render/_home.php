<?php
require(__DIR__ . "/../../public/modules/config.inc.php");
if(!class_exists('database')){die("Class not exist!");}
$db = new database($config["user"], $config["pass"], $config["host"], $config["db"]);
$uid = $_SESSION["uid"];
$getData = $db->getRow("SELECT * FROM accounts WHERE uid=?",array($uid));
?>

<h3 align="left"><span uk-icon="user"></span> <b><?php echo $getData["first_name"]." ".$getData["last_name"]; ?></b></h3>
<div align="left"><label><span uk-icon="receiver"></span> หมายเลขโทรศัพท์</label> <b><?php echo substr($getData["phone"],0,6); ?>XXXX</b></div>
<div align="left"><label><span uk-icon="nut"></span> เลขบัตรประจำตัว</label> <b><?php echo substr($getData["idcard"],0,9); ?>XXXX</b></div>
<div align="left"><label><span uk-icon="world"></span> เพศ</label> <b><?php echo array("ชาย","หญิง")[$getData["gender"]]; ?></b></div>
<div align="left"><label><span uk-icon="calendar"></span> วันเกิด</label> <b><?php echo date("d F ",$getData["birthday"]).((date("Y",$getData["birthday"]))+543); ?></b></div>
<?php if(($db->getNumber("select * from run where follower=?",array($uid))) > 0){ ?>
<div align="left"><label><span uk-icon="users"></span> <a href="./public/render/groupList.php" target="_new">ดูข้อมูลการวิ่งแบบกลุ่ม</a></div>
<?php } ?>

<?php
$checkRun = $db->getNumber("select uid from payment_report where uid=?",array($uid));
if($checkRun > 0){
	$getStatus = $db->getRow("select status from payment_report where uid=?",array($uid));
	if($getStatus["status"]==0){
		echo '<div class="uk-alert-primary" uk-alert><span uk-icon="info"></span> การชำระเงินของคุณยังไม่ถูกยืนยัน!</div>';
	} else if($getStatus["status"]==1){
		$getRun = $db->getRow("select runid,runType,subType,isVIP from run where uid=?",array($uid));
		$subLabel = ($getRun["isVIP"]==true)?$runType[$getRun["runType"]]:(($getRun["runType"]==2)?$ageType[$getRun["subType"]]:"");
		$runLabel = ($getRun["isVIP"]==true)?"VIP":$runType[$getRun["runType"]];
		echo '<div class="uk-alert-success" uk-alert><span uk-icon="check"></span> คุณได้ลงทะเบียนในการวิ่งแล้ว!</div>';
		echo '<label>หมายเลขประจำตัวผู้วิ่ง</label><h1 style="margin-top:0px;">#<b>'.str_pad($getRun["runid"], 4, '0', STR_PAD_LEFT).'</b></h1>';
		echo '<div><label style="margin-bottom:0px;"><b>'.$runLabel.'</b></label></div>';
		echo '<label style="margin-bottom:0px;">'.$subLabel.'</label>';
	} else{
		echo '<div class="uk-alert-danger" uk-alert><span uk-icon="close"></span> การชำระเงินของคุณมีปัญหา!</div>';
	}
}
?>