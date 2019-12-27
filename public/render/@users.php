<?php
require(__DIR__ ."/../../public/modules/config.inc.php");
if(!class_exists('database')){die("Class not exist!");}
$db = new database($config["user"], $config["pass"], $config["host"], $config["db"]);
if($_SESSION["admin"] != true){die("Not have permission!");}
date_default_timezone_set("Asia/Bangkok");
?>

<?php
if(isset($_GET["edit"]) and is_numeric($_GET["edit"])){
	if($db->getNumber("select uid from accounts where uid=?",array(intval($_GET["edit"]))) > 0){
		$getData = $db->getRow("select * from accounts where uid=?",array(intval($_GET["edit"])));

		if(isset($_POST) and isset($_POST["firstn"]) and isset($_POST["lastn"]) and isset($_POST["idcard"]) and isset($_POST["telphone"]) and isset($_POST["gender"]) and isset($_POST["birthday"]) and is_numeric($_POST["idcard"]) and is_numeric($_POST["telphone"]) and is_numeric($_POST["gender"])){
			$sqlUpdate = "UPDATE accounts SET idcard=?,phone=?,first_name=?,last_name=?,gender=?,birthday=?,email=?,admin=? WHERE uid=?;";
			$idCard = $_POST["idcard"];
			$phone = $_POST["telphone"];
			$firstn = $_POST["firstn"];
			$lastn = $_POST["lastn"];
			$gender = intval($_POST["gender"]);
			$birthday = strtotime($_POST["birthday"]);
			$email = (isset($_POST["email"]))?$_POST["email"]:"";
			$admin = (isset($_POST["admin"]))?1:0;
			$db->updateRow($sqlUpdate,array($idCard,$phone,$firstn,$lastn,$gender,$birthday,$email,$admin,intval($_GET["edit"])));
			echo '<div class="uk-alert-success" uk-alert><span uk-icon="check"></span> บันทึกข้อมูลสำเร็จ!</div>';
			echo '<script>setInterval(function(){window.location="./?admin=users";},2000);</script>';
		} else{
?>
<link rel="stylesheet" href="public/assets/css/jquery-ui.css">
<script src="public/assets/js/jquery-ui.min.js"></script>

<form class="uk-form-stacked" action="./?admin=users&edit=<?php echo intval($_GET["edit"]); ?>" method="post">
	<div class="uk-margin" align="left">
		<label for="firstn">
			<b>ชื่อ-สกุล</b> <font color="grey">(ไม่ต้องใส่คำนำหน้า)</font> 
		</label>
		<div class="uk-text-center uk-grid-small" uk-grid>
			<div class="uk-width-1-2">
				<input type="text" id="firstn" class="uk-input" style="margin-right:0px;" name="firstn" autocomplete="off" required placeholder="First Name" value="<?php echo $getData["first_name"] ?>" />
			</div>
			<div class="uk-width-1-2">
				<input type="text" class="uk-input" style="margin-left:0px;" name="lastn" autocomplete="off" required placeholder="Last Name" value="<?php echo $getData["last_name"] ?>" />
			</div>
		</div>
	</div>
	<div class="uk-margin" align="left">
		<label class="uk-form-label" for="idcard-input"><b>เลขประจำตัว</b></label>
		<div class="uk-form-controls">
			<input class="uk-input" id="idcard-input" name="idcard" type="text" pattern="[0-9]*" placeholder="ID Card" autocomplete="off" required value="<?php echo $getData["idcard"] ?>" />
		</div>
	</div>
	<div class="uk-margin" align="left">
		<label class="uk-form-label" for="telphone-input"><b>เบอร์โทรศัพท์</b></label>
		<div class="uk-form-controls">
			<input class="uk-input" id="telphone-input" name="telphone" type="text" pattern="[0-9]*" placeholder="Phone Number" autocomplete="off" required value="<?php echo $getData["phone"] ?>" />
		</div>
	</div>
	<div class="uk-margin" align="left">
		<label>
			<b>เพศ <font color="red"><b>*</b></font></b> 
			<div class="uk-grid-small uk-child-width-auto uk-grid">
				<label><input class="uk-radio" type="radio" name="gender" value="0" <?php if($getData["gender"]==0){echo "checked";} ?> /> ชาย</label>
				<label><input class="uk-radio" type="radio" name="gender" value="1"  <?php if($getData["gender"]==1){echo "checked";} ?> /> หญิง</label>
			</div>
		</label>
	</div>
	<div class="uk-margin" align="left">
		<label>
			<b>วัน/เดือน/ปี เกิด <font color="red"><b>*</b></font></b> 
			<input type="text" class="uk-input" name="birthday" value="<?php echo date('d-m-Y',$getData["birthday"]); ?>" autocomplete="off" required="" placeholder="Birthday (dd/mm/YYYY)" />
		</label>
	</div>
	<div class="uk-margin" align="left">
		<label>
			<b>E-Mail <font color="red"><b></b></font></b> 
			<input type="email" maxlength="120" class="uk-input" name="email" autocomplete="off" placeholder="Email (xxx@xxx.xxx)" value="<?php echo $getData["email"] ?>" />
		</label>
	</div>
	<div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
		<label><input class="uk-checkbox" type="checkbox" name="admin" value="1" <?php if($getData["admin"]==1){echo "checked";} ?>> ให้สิทธิ์ผู้ดูแล</label>
	</div>
	<div align="right">
		<button type="submit" style="margin-top:2px;" class="uk-button uk-button-primary"><span uk-icon="pencil"></span> บันทึกข้อมูล</button> 
		<a href="./?admin=users" style="margin-top:2px;" class="uk-button uk-button-secondary"><span uk-icon="home"></span> ย้อนกลับ</a>
	</div>
</form>
<script>$(function(){$("input[name=birthday]").datepicker({ changeMonth: true,changeYear: true,yearRange: "-100:+0",dateFormat: "dd-mm-yy" });});</script>

<?php
		}
	} else{
		echo '<div style="margin-bottom:10px;" class="uk-alert-danger" uk-alert><span uk-icon="close"></span> ไม่พบข้อมูล</div>';
		echo '<div align="right"><a href="./?admin=users" class="uk-button uk-button-secondary"><span uk-icon="home"></span> ย้อนกลับ</a></div>';
	}
} else if(isset($_GET["remove"]) and is_numeric($_GET["remove"])){
	$db->updateRow("DELETE FROM accounts WHERE uid=?;",array(intval($_GET["remove"])));
	echo '<div class="uk-alert-success" uk-alert><span uk-icon="check"></span> ลบข้อมูลสำเร็จ!</div>';
	echo '<script>setInterval(function(){window.location="./?admin=users";},2000);</script>';
} else{
?>
<div align="left" style="margin-right:10px;">
	<a style="margin-top:5px;margin-bottom:5px;" class="uk-button uk-button-secondary uk-button-small search-click"><span uk-icon="search"></span></a>
	<a style="margin-top:5px;margin-bottom:5px;" class="uk-button uk-button-secondary uk-button-small edit-click"><span uk-icon="pencil"></span></a> 
	<a style="margin-top:5px;margin-bottom:5px;" class="uk-button uk-button-secondary uk-button-small remove-click"><span uk-icon="close"></span></a>
</div>
<div style="margin-left:-30px;margin-right:-30px;" class="uk-overflow-auto">
<table class="uk-table uk-table-hover uk-table-divider uk-table-small usersTable uk-table-justify">
	<thead>
		<tr>
			<th class="uk-table-shrink"><center>#</center></th>
			<th><center>ชื่อ</center></th>
			<th><center>เลขประจำตัว</center></th>
			<th><center>เบอร์โทรฯ</center></th>
			<th><center>อายุ</center></th>
			<th><center>เพศ</center></th>
		</tr>
	</thead>
	<tbody>
		<?php
		if($db->getNumber("select uid from accounts",array()) > 0){
			$gArray = array("ช","ญ");
		foreach($db->getRows("select * from accounts") as $row){
			$uid = $row["uid"];
			$phone = $row["phone"];
			$idcard = $row["idcard"];
			$firstName = mb_substr($row["first_name"],0,12,'UTF-8');
			$age = floor((time() - intval($row["birthday"])) / (31536000));
			$gender = $gArray[intval($row["gender"])];

			echo '<tr> <td>'.$uid.'</td> <td>'.$firstName.'</td> <td>'.$idcard.'</td> <td>'.$phone.'</td> <td>'.$age.' ปี</td> <td>'.$gender.'</td></tr>';
		}
		} else { echo '<tr> <td colspan="7">ไม่พบข้อมูล</td> </tr>'; }
		?>
	</tbody>
</table>
</div>
<?php
}
?>

<link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

<script type="text/javascript"> 
$(function(){
	var clicked = false;
	$("a.search-click").click(function(){
		if(!clicked){
			clicked=true;
    		$('table.usersTable').dataTable({});
    	} else{
    		clicked=false;
    		//$('table.runnerTable').dataTable().fnClearTable();
    		$('table.usersTable').dataTable().fnDestroy();
    	}
	});
	$("a.edit-click").click(function(){
		var uid = prompt("กรุณาระบุ #UID ที่ต้องการแก้ไข", "");
		if (uid == null || uid == "" || isNaN(uid)) {
			window.alert("#UID ไม่ถูกต้อง");
		} else {
			window.location="./?admin=users&edit="+uid;
		}
	});
	$("a.remove-click").click(function(){
		var uid = prompt("กรุณาระบุ #UID ที่ต้องการแก้ไข", "");
		if (uid == null || uid == "" || isNaN(uid)) {
			window.alert("#UID ไม่ถูกต้อง");
		} else {
			if(window.confirm("แน่ใจหรือไม่ ต้องการที่จะลบ #UID " + uid + "?")){
				window.location="./?admin=users&remove="+uid;
			}
		}
	});
} );
</script>