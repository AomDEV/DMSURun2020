<?php
require(__DIR__ ."/../../public/modules/config.inc.php");
if(!class_exists('database')){die("Class not exist!");}
$db = new database($config["user"], $config["pass"], $config["host"], $config["db"]);
if($_SESSION["admin"] != true){die("Not have permission!");}

if(isset($_GET["edit"]) and is_numeric($_GET["edit"])){
	if($db->getNumber("select uid from run where runid=?",array(intval($_GET["edit"]))) > 0){
		$getData = $db->getRow("select * from run where runid=?",array(intval($_GET["edit"])));
		if(isset($_POST) and isset($_POST["size"]) and is_numeric($_POST["size"]) and isset($_POST["subType"]) and is_numeric($_POST["subType"]) and isset($_POST["runType"]) and is_numeric($_POST["runType"]) or (isset($_POST["name"]) or isset($_POST["phone"]))){
			$sqlUpdate = "UPDATE run SET size=?,isVIP=?,runType=?,subType=? WHERE runid=?;";
			$size = $_POST["size"];
			$runType = intval($_POST["runType"]);
			$subType = intval($_POST["subType"]);
			$isVIP = false;
			if($runType==3){ //VIP
				$runType = $subType;
				$subType = 0;
				$isVIP = true;
			}
			$db->updateRow($sqlUpdate,array($size,$isVIP,$runType,$subType,intval($_GET["edit"])));
			if(isset($_POST["name"]) and isset($_POST["telphone"]) and is_numeric($_POST["telphone"])){
				$sqlUpdate = "UPDATE run SET follow_name=?,phone=? WHERE runid=?;";
				$db->updateRow($sqlUpdate,array($_POST["name"],$_POST["telphone"],intval($_GET["edit"])));
			}
			echo '<div class="uk-alert-success" uk-alert><span uk-icon="check"></span> บันทึกข้อมูลสำเร็จ!</div>';
			echo '<script>setInterval(function(){window.location="./?admin=runner";},2000);</script>';
		} else{
?>
<form class="uk-form-stacked" action="./?admin=runner&edit=<?php echo intval($_GET["edit"]); ?>" method="post">
<div class="uk-margin" align="left">
	<label><b>ID</b>
	<?php echo '#'.str_pad($getData["runid"], 4, '0', STR_PAD_LEFT); ?>
	</label>
</div>
<div class="uk-margin" align="left">
	<label><b>ไซส์เสื้อ <font color="red"><b>*</b></font></b>
	<select class="uk-select" name="size">
	<?php
	for($i0=0;$i0<count($sizes);$i0++){
		$check = "";
		if($i0==$getData["size"]){$check = "selected";}
		echo '<option value="'.$i0.'" '.$check.'>'.$sizes[$i0].'</option>';
	}
	?>
	</select></label>
</div>
<div class="uk-margin" align="left">
		<label for="firstn">
			<b>ประเภทวิ่ง</b>
		</label>
		<div class="uk-text-center uk-grid-small" uk-grid>
			<div class="uk-width-1-2">
				<select class="uk-select runtype" name="runType">
					<?php
					$idRun = $getData["runType"];
					if($getData["isVIP"]==true){$idRun = 3;}
					for($i0=1;$i0<count($runType);$i0++){
						$check = "";
						echo '<option value="'.$i0.'" '.$check.'>'.$runType[$i0].'</option>';
					}
					?>
				</select>
			</div>
			<div class="uk-width-1-2">
			<select class="uk-select subTypeSelect" name="subType" disabled><option value="0" selected="">0</option></select>
			</div>
		</div>
	</div>
<script>
var backend = true;
<?php
function js_str($s)
{
	return '"' . addcslashes($s, "\0..\37\"\\") . '"';
}
function jsArray($array)
{
	$temp = array_map('js_str', $array);
	return '[' . implode(',', $temp) . ']';
}
echo 'var subType1 = ', jsArray($ageType), ';';
echo 'var startType1 = 0;';
echo 'var endType1 = '.(count($ageType)).';';
echo 'var subType2 = ', jsArray($runType), ';';
echo 'var startType2 = 1;';
echo 'var endType2 = '.(count($runType)-1).';';
?>
</script>
<script src="public/assets/js/group.js"></script>
<script>$(document).ready(function(){ $("select[name=runType]").val('<?php echo $idRun; ?>').change();$(".runType").trigger("change"); });</script>
<script>$(document).ready(function(){ $("select[name=subType]").val('<?php if($getData["isVIP"]==true){echo $getData["runType"];}else{echo $getData["subType"];} ?>').change();$(".runType").trigger("change"); });</script>
<?php if($getData["follower"]!=0){ ?>
<div class="uk-margin" align="left">
	<label class="uk-form-label" for="name-input"><b>ชื่อ-สกุล</b></label>
	<div class="uk-form-controls">
		<input class="uk-input" id="name-input" name="name" type="text" placeholder="Full Name" autocomplete="off" required value="<?php echo $getData["follow_name"] ?>" />
	</div>
</div>
<div class="uk-margin" align="left">
	<label class="uk-form-label" for="name-input"><b>เบอร์โทรศัพท์</b></label>
	<div class="uk-form-controls">
	<input class="uk-input" id="telphone-input" name="telphone" type="text" pattern="[0-9]*" placeholder="Phone Number" autocomplete="off" required value="<?php echo $getData["phone"] ?>" />
	</div>
</div>
<?php } ?>
<div align="right">
	<button type="submit" style="margin-top:2px;" class="uk-button uk-button-primary"><span uk-icon="pencil"></span> บันทึกข้อมูล</button> 
	<a href="./?admin=runner" style="margin-top:2px;" class="uk-button uk-button-secondary"><span uk-icon="home"></span> ย้อนกลับ</a>
</div>
</form>
<?php
		}
	} else{
		echo '<div style="margin-bottom:10px;" class="uk-alert-danger" uk-alert><span uk-icon="close"></span> ไม่พบข้อมูล</div>';
		echo '<div align="right"><a href="./?admin=runner" class="uk-button uk-button-secondary"><span uk-icon="home"></span> ย้อนกลับ</a></div>';
	}
} else{
?>
<div align="left" style="margin-top:-20px;margin-left:-20px;margin-right:-20px;">
	<a style="margin-top:2px;" class="uk-button uk-button-secondary uk-button-small <?php if(isset($_GET['filter']) and $_GET['filter']=='funrun'){echo 'uk-disabled';} ?>" href="./?admin=runner&filter=funrun">FUN-RUN</a> 
	<a style="margin-top:2px;" class="uk-button uk-button-secondary uk-button-small <?php if(isset($_GET['filter']) and $_GET['filter']=='mini'){echo 'uk-disabled';} ?>" href="./?admin=runner&filter=mini">MINI</a> 
	<a style="margin-top:2px;" class="uk-button uk-button-secondary uk-button-small <?php if(isset($_GET['filter']) and $_GET['filter']=='vip'){echo 'uk-disabled';} ?>" href="./?admin=runner&filter=vip">VIP</a> 
	<a style="margin-top:2px;" class="uk-button uk-button-secondary uk-button-small <?php if(isset($_GET['filter']) and $_GET['filter']=='notpaid'){echo 'uk-disabled';} ?>" href="./?admin=runner&filter=notpaid">ยังไม่ชำระเงิน</a> 
	<a style="margin-top:2px;" class="uk-button uk-button-secondary uk-button-small <?php if(isset($_GET['filter']) and $_GET['filter']=='paid'){echo 'uk-disabled';} ?>" href="./?admin=runner&filter=paid">ชำระแล้ว</a> 
	<a style="margin-top:2px;" class="uk-button uk-button-link uk-button-small edit-click"><span uk-icon="pencil"></span></a> 
	<a style="margin-top:2px;" class="uk-button uk-button-link uk-button-small search-click"><span uk-icon="search"></span></a>
</div>
<div style="margin-left:-30px;margin-right:-30px;margin-top:10px;" class="uk-overflow-auto">
<table class="uk-table uk-table-hover uk-table-divider uk-table-justify uk-table-small runnerTable">
	<thead>
		<tr>
			<th class="uk-table-shrink">#</th>
			<th>ชื่อ</th>
			<th>เลขประจำตัว</th>
			<th>เบอร์โทรฯ</th>
			<th>ประเภทวิ่ง</th>
			<th>ไซส์เสื้อ</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
		<?php
		if($db->getNumber("select uid from run",array()) > 0){
		foreach($db->getRows("select * from run") as $row){
			$getAcc = $db->getRow("select * from accounts where uid=?",array($row["uid"]));

			//Filter
			if(isset($_GET["filter"]) and ($_GET["filter"]=="notpaid" or $_GET["filter"]=="paid")){
				if($_GET["filter"]=="paid"){
					if($db->getNumber("select status from payment_report where (status=1) and uid=?",array($getAcc["uid"])) <= 0){
						continue;
					}
					if($row["uid"]<=0){
						continue;
					}
				} else{
					if($db->getNumber("select status from payment_report where (status=0 or status=2) and uid=?",array($getAcc["uid"])) <= 0){
						if($db->getNumber("select status from payment_report where uid=?",array($getAcc["uid"])) > 0){
							if($row["follower"]==$getAcc["uid"]){continue;}
							continue;
						}
						if($row["uid"]<=0){
							continue;
						}
					}
				}
			} else 
			if(isset($_GET["filter"]) and $_GET["filter"]=="vip"){
				if(isset($row["isVIP"]) and $row["isVIP"]!=true){
					continue;
				}
			} else 
			if(isset($_GET["filter"]) and $_GET["filter"]=="mini"){
				if((isset($row["runType"]) and isset($row["isVIP"])) and $row["runType"]!=2){
					continue;
				}
			} else 
			if(isset($_GET["filter"]) and $_GET["filter"]=="funrun"){
				if((isset($row["runType"]) and isset($row["isVIP"])) and $row["runType"]!=1){
					continue;
				}
			}

			$rid = $row["runid"];
			$phone = $getAcc["phone"];
			$idcard = substr($getAcc["idcard"],0,8)."..";
			$firstName = mb_substr($getAcc["first_name"],0,7,'UTF-8');
			if($row["uid"]<=0){
				$getFollower = $db->getRow("select runid from run where uid=?",array($row["follower"]));
				$firstName = mb_substr(explode(" ",$row["follow_name"])[0],0,8,'UTF-8');
				$idcard = "<i>#".str_pad($getFollower["runid"], 4, '0', STR_PAD_LEFT)."</i>";
				if(strlen($row["phone"])==10){
					$phone = $row["phone"];
				} else{
					$phone = "<i>ไม่ระบุ</i>";
				}
			}

			if(isset($row["runType"])){
				$runLabel = ($row["isVIP"]==true)?"VIP ".substr($runType[$row["runType"]], 0, 1):$runType[$row["runType"]];
				if($row["status"]==1){$icon = "check";} else{$icon = "clock";}
				echo '<tr> <td>'.$rid.'</td> <td>'.$firstName.'</td> <td>'.$idcard.'</td> <td>'.$phone.'</td> <td>'.str_replace(" ","",mb_substr($runLabel,0,7,'UTF-8')).'</td> <td>'.$sizes[$row["size"]].'</td> <td><span uk-icon="'.$icon.'"></span></td></tr>';
			} else{
				echo '<tr> <td>'.$rid.'</td> <td>'.$firstName.'</td> <td>'.$idcard.'..</td> <td>'.$phone.'</td> <td>-</td> <td>-</td> <td><span uk-icon="close"></span></td></tr>';
			}
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
    		$('table.runnerTable').dataTable({});
    	} else{
    		clicked=false;
    		//$('table.runnerTable').dataTable().fnClearTable();
    		$('table.runnerTable').dataTable().fnDestroy();
    	}
	});
	$("a.edit-click").click(function(){
		var uid = prompt("กรุณาระบุ #RID ที่ต้องการแก้ไข", "");
		if (uid == null || uid == "" || isNaN(uid)) {
			window.alert("#RID ไม่ถูกต้อง");
		} else {
			window.location="./?admin=runner&edit="+uid;
		}
	});
} );
</script>