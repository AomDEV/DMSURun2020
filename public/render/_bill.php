<h3 align="left"><span uk-icon="credit-card"></span> ส่งหลักฐานการชำระเงิน</h3>
<h2><i class="icon-bank scb"></i> <b>608-282862-4</b></h2>
<div><label>ธนาคาร</label> <b>ไทยพาณิชย์</b></div>
<div><label>สาขา</label> <b>มหาสารคาม 0608</b></div>
<div><label>ชื่อบัญชี</label> <b>นายธัญสุต บริหารธนวุฒิ และ นายคึกฤทธิ์ เรกะลาภ</b></div>

<?php
require(__DIR__ ."/../../public/modules/config.inc.php");
if(!class_exists('database')){die("Class not exist!");}
if(!isset($_SESSION["uid"])){die("access denied!");}
$db = new database($config["user"], $config["pass"], $config["host"], $config["db"]);
$sqlBilling = "select status from payment_report where uid=?";
$getBilling = $db->getRow($sqlBilling,array($_SESSION["uid"]));
$getUser = $db->getRow("select first_name,last_name from accounts where uid=?",array($_SESSION["uid"]));
if(isset($getBilling["status"]) and (/*$getBilling["status"]==1 or*/ $getBilling["status"]==0)){
	if($getBilling["status"]==1){
		//echo '<div class="uk-alert-success" uk-alert><span uk-icon="check"></span> หลักฐานการชำระเงินของคุณอนุมัติแล้ว</div>';
		//For single payment report
	} else {
		echo '<div class="uk-alert-primary" uk-alert><span uk-icon="info"></span> กำลังตรวจสอบข้อมูล</div>';
	}
} else{
	$getRun = $db->getRows("select runType,isVIP,follow_name,follower from run where uid=? or follower=?",array($_SESSION["uid"],$_SESSION["uid"]));
	if(isset($getRun) and count($getRun)>0){

	$report = "> สรุปยอด <\\n";
	$priceTotal = 0;
	foreach($getRun as $row){
		$followName = $row["follow_name"];
		if(isset($getUser["first_name"]) and strlen($followName)<= 5){
			$followName = $getUser["first_name"]." ".$getUser["last_name"];
		}
		$runLabel = ($row["isVIP"]==true)?"VIP (".substr($runType[$row["runType"]], 0, 1).")":$runType[$row["runType"]];
		$price = ($row["isVIP"]==true)?$runPrice[3]:$runPrice[$row["runType"]];
		$report .= $followName." ".$runLabel." - ".number_format($price,2)." บาท\\n";
		$priceTotal += intval($price);
	}
	if($db->getNumber("select amount from payment_report where uid=? and status=1",array($_SESSION["uid"]))>=1){
		$getTotalPrice = $db->getRows("select amount from payment_report where uid=? and status=1",array($_SESSION["uid"]));
		$paidTotal = 0;
		foreach($getTotalPrice as $row){
			$paidTotal += intval($row["amount"]);
		}
		$priceTotal -= $paidTotal;
	}
	if($priceTotal<=0){
		echo '<div class="uk-alert-success" uk-alert><span uk-icon="check"></span> หลักฐานการชำระเงินของคุณอนุมัติครบแล้ว</div>';
	} else{

?>
<hr />
<form action="./?a=bill" method="post" class="form-bill">
	<div id="box" style="display:none"></div>
	<div align="left">
		<label><b>วันที่โอนเงินค่าสมัคร</b> 
		<div align="left" class="uk-text-center uk-grid-small" uk-grid>
			<div class="uk-width-1-2">
				<input type="date" class="uk-input" name="date" required="" value="<?php echo date('Y-m-d'); ?>" />
			</div>
			<div class="uk-width-1-2">
				<input type="time" class="uk-input" style="margin-left:0px;" name="time" required="" value="<?php echo date("H:i"); ?>" />
			</div>
			</label>
		</div>
	</label>
	</div>
	<div align="left">
		<div align="left" class="uk-text-center uk-grid-small" uk-grid>
			<div class="uk-width-1-2" align="left">
				<label><b>หลักฐานการโอนเงิน</b> 
				<div uk-form-custom="target: true">
					<input type="file" name="file" class="upload-file">
	            	<input class="uk-input uk-form-width-medium" type="text" placeholder="เลือกไฟล์..." disabled>
				</label></div>
			</div>
			<div class="uk-width-1-2" align="left">
				<label><b>จำนวนเงิน</b> 
					<input type="text" class="uk-input" style="margin-left:0px;" name="amount" autocomplete="off" required="" placeholder="100.00" />
				</label>
			</div>
		</div>
	</div>
	<div align="right"><label>ยอดเงิน</label> <h3 style="margin-top:-10px;margin-bottom:5px;"><a href="javascript:alert('<?php echo $report; ?>');" ><span uk-icon="search"></span></a> <b><?php echo number_format($priceTotal,2);?></b> บาท</h3></div>
	<div align="right">
		<button type="submit" class="uk-button uk-button-primary save-bill"><span uk-icon="check"></span> ส่งหลักฐาน</button>
	</div>
</form>
<?php
		}
	} else{
		echo '<div class="uk-alert-danger" uk-alert><span uk-icon="warning"></span> คุณยังไม่ได้ลงทะเบียนวิ่ง!</div>';
	}
}
?>

<script src="public/assets/js/bill.js"></script>