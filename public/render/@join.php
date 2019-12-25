<?php
require(__DIR__ ."/../../public/modules/config.inc.php");
if(!class_exists('database')){die("Class not exist!");}
$db = new database($config["user"], $config["pass"], $config["host"], $config["db"]);
if($_SESSION["admin"] != true){die("Not have permission!");}
?>
<h3 align="left"><span uk-icon="check"></span> ลงชื่อเข้างาน</h3>

<form action method="get">

	<?php
	if(isset($_GET["id"]) and is_numeric($_GET["id"])){
		$sqlRunner = "select * from run where runid=?";
		$sqlAccount = "select * from accounts where uid=?";
		$checkRunner = $db->getNumber($sqlRunner,array(intval($_GET["id"])));
		if($checkRunner <= 0){
			echo '<div class="uk-alert-danger" uk-alert><span uk-icon="warning"></span> ยังไม่ได้ลงทะเบียนการวิ่ง</div>';
		} else{
			$getRunner = $db->getRow($sqlRunner,array(intval($_GET["id"])));
			$getAccount = $db->getRow($sqlAccount,array($getRunner["uid"]));
			$getName = $getAccount["first_name"].' '.$getAccount["last_name"];
			$getIDCard = $getAccount["idcard"];
			$getBirthday = date("d F Y",$getAccount["birthday"]);
			$runLabel = ($getRunner["isVIP"]==true)?"VIP (".substr($runType[$getRunner["runType"]], 0, 6).")":$runType[$getRunner["runType"]];
			if($getRunner["uid"]<=0){
				$getName = $getRunner["follow_name"];
				$getIDCard = "(ลงทะเบียนแบบกลุ่ม)";
				$getBirthday = "-";
			}
			echo '<div align="left" class="uk-alert-primary" uk-alert>';
			echo '<div><label>ชื่อ-สกุล: <b>'.$getName.'</b></label></div>';
			echo '<div><label>เลขบัตรประจำตัว: <b>'.$getIDCard.'</b></label></div>';
			echo '<div><label>วันเกิด: <b>'.$getBirthday.'</b></label></div>';
			echo '<div><label>การวิ่ง: <b>'.$runLabel.'</b></label></div>';
			echo '<div align="left">';
			echo '<a class="uk-button uk-button-primary uk-button-small" onclick="return confirm(\'แน่ใจหรือไม่?\');" href="./?admin=join&confirm='.$_GET["id"].'" style="margin-top:5px;">ลงทะเบียน</a> ';
			echo '<a class="uk-button uk-button-danger uk-button-small" href="./?admin=join" style="margin-top:5px;">ยกเลิก</a>';
			echo '</div></div>';
		}
	}
	if(isset($_GET["confirm"]) and is_numeric($_GET["confirm"])){
		if($db->getNumber("select uid from run where uid=?",array(intval($_GET["confirm"]))) > 0){
			$db->updateRow("UPDATE run SET status='1' WHERE runid=?;",array(intval($_GET["confirm"])));
			echo '<div class="uk-alert-success" uk-alert><span uk-icon="check"></span> ลงทะเบียนสำเร็จ</div><script>setInterval(function(){window.location="./?admin=join";},2000);</script>';
		} else{
			echo '<div class="uk-alert-danger" uk-alert><span uk-icon="warning"></span> ไม่พบผู้วิ่งนี้</div>';
		}
	}
	?>

	<input type="hidden" name="admin" value="join" />
	<div align="left">
		<label>
			<b>เลขประจำตัวผู้วิ่ง <font color="red"><b>*</b></font></b> 
			<input type="text" maxlength="4" class="uk-input uk-form-large" name="id" autocomplete="off" required="" placeholder="Runner ID (#0000)" value="<?php echo str_pad(intval(isset($_GET['id'])?$_GET['id']:0), 4, '0', STR_PAD_LEFT); ?>" /> 
			<div align="right" style="margin-top:10px;">
				<button type="submit" class="uk-button uk-button-primary"><span uk-icon="search"></span> ค้นหา</button>
			</div>
		</label>
	</div>
</form>