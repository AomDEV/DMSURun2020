<div class="content-box">
	<h1><b><span uk-icon="icon: bolt; ratio: 1.5"></span> ลงทะเบียนการวิ่ง</b></h1>

<?php
require("public/modules/config.inc.php");
$db = new database($config["user"], $config["pass"], $config["host"], $config["db"]);
$allowGroupRegister = false;
if($db->getNumber("SELECT uid FROM run WHERE uid=?",array($_SESSION["uid"])) >= 1){
	$allowGroupRegister = true;
	?>
	<div class="uk-alert-danger" uk-alert><p><span uk-icon="warning"></span> ท่านได้ลงทะเบียนวิ่งแล้วเรียบร้อย!</p></div>
	<script>window.location="./?page=groupRun";</script>
	<?php
}
if((isset($_GET["step"]) and $_GET["step"]==1) or !isset($_GET["step"])){
?>
<div id="step1">
<form action="./?page=regisRun&step=2" method="post">
	<h3 align="left"><span uk-icon="settings"></span> เลือกประเภทวิ่ง</h3>

	<div class="uk-child-width-1-3@m uk-grid-small uk-grid-match" uk-grid>
		<div>
			<div class="uk-card uk-card-default uk-card-body">
				<h3 class="uk-card-title">FUN RUN</h3>
				<p style="margin-top: -15px;margin-bottom: 5px;">ระยะทาง 3.5 กิโลเมตร</p>
				<div align="center">
					<button id="run_1" type="button" data-type="1" data-price="400" class="uk-button uk-button-default" style="padding:0 20px;">
						<span uk-icon="check" style="display:inline"></span> เลือก (400 บาท)
					</button>
				</div>
			</div>
		</div>
		<div>
			<div class="uk-card uk-card-primary uk-card-body">
				<h3 class="uk-card-title">Mini-Marathon</h3>
				<p style="margin-top: -15px;margin-bottom: 5px;">ระยะทาง 10.5 กิโลเมตร</p>
				<div align="center">
					<button  id="run_2" type="button" data-type="2" data-price="500" class="uk-button uk-button-default" style="padding:0 20px;">
						<span uk-icon="check" style="display:none"></span> เลือก (500 บาท)
					</button>
				</div>
			</div>
		</div>
		<div>
			<div class="uk-card uk-card-secondary uk-card-body">
				<h3 class="uk-card-title">VIP</h3>
				<p style="margin-top: -15px;margin-bottom: 5px;">** วิ่งแบบใดแบบหนึ่ง **</p>
				<div align="center">
					<button  id="run_3" type="button" data-type="3" data-price="1500" class="uk-button uk-button-default" style="padding:0 20px;">
						<span uk-icon="check" style="display:none"></span> เลือก (1,500 บาท)
					</button>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" name="runType" value="1" />

	<div align="right" style="margin-top:15px;">
		ยอดที่ต้องชำระ
		<h2><b><span id="price">400</span></b> ฿</h2>
		<div style="margin-top:-10px;">
			<?php if($allowGroupRegister){ ?>
			<a href="./?page=groupRun">ลงทะเบียนแบบกลุ่ม</a> &nbsp; 
			<?php } ?>
			<a class="uk-button uk-button-default" href="./"><span uk-icon="home"></span> หน้าแรก</a>
			<button type="submit" class="uk-button uk-button-secondary"><span uk-icon="check"></span> ขั้นตอนต่อไป</button>
		</div>
	</div>
</form>
</div>

<?php
} else if(isset($_GET["step"]) and $_GET["step"]==2){
	if(!(isset($_POST["runType"]) and is_numeric($_POST["runType"]) and $_POST["runType"]<=3 and $_POST["runType"]>=0)){echo "<script>window.location='./?page=regisRun&step=1';</script>";}
	$label = array("","","เลือกรุ่นอายุ","เลือกประเภทการวิ่ง");
?>
<div id="step2">

<form action="./?page=regisRun&step=3" method="post">
	<input type="hidden" name="runType" value="<?php echo $_POST['runType']; ?>" />
	<div id="reward">
		<div style="margin-bottom:-15px;">
			<h2 align="left"><span uk-icon="location"></span> <b><?php echo $runType[intval($_POST["runType"])]; ?></b></h2>
		</div>
		<?php
		$imgShirtRunType = array(
			"public/assets/img/shirt_funrun.png",
			"public/assets/img/shirt_mini.png",
			"public/assets/img/shirt_vip.png"
		);
		$imgBadgeRunType = array(
			"public/assets/img/badge_funrun.png",
			"public/assets/img/badge_mini.png",
			"public/assets/img/badge_vip.png"
		);
		?>
		<div class="uk-text-center" uk-grid>
			<div class="uk-width-3-4">
				<h3 align="left"><span uk-icon="question"></span> เสื้อที่ระรึก</h3><img src="<?=$imgShirtRunType[intval($_POST['runType'])-1]?>" />
			</div>
			<div class="uk-width-1-4">
				<h3 align="left"><span uk-icon="question"></span> เหรียญรางวัล</h3><img src="<?=$imgBadgeRunType[intval($_POST['runType'])-1]?>" />
			</div>
		</div>
	</div>
	<?php if($_POST["runType"]!=1){ ?>
	<h3 align="left"><span uk-icon="location"></span> <?php echo $label[$_POST["runType"]]; ?></h3>
	<?php
	if($_POST["runType"]==2){
	?>
	<div align="left" style="margin-left: 20px;">
		<?php
		for($i=0;$i<count($ageType);$i++){
			$check = "";
			if($i==0){$check = "checked";}
			echo '<div><label><input type="radio" class="uk-radio" name="subType" value="'.$i.'" '.$check.' /> '.$ageType[$i].'</label></div>';
		}
		?>
	</div>
	<?php
	} else if($_POST["runType"]==3){
	?>
	<div align="left" style="margin-left: 20px;">
		<div><label><input type="radio" class="uk-radio" name="subType" value="1" checked /> FUN RUN (3.5km)</label></div>
		<div><label><input type="radio" class="uk-radio" name="subType" value="2" /> Mini-Marathon (10.5km)</label></div>
	</div>
	<?php
	}
	?>
	<?php } else{ echo "<input type='hidden' name='subType' value='0' />"; } ?>
	<h3 align="left"><span uk-icon="code"></span> ขนาดเสื้อ</h3>
	<div align="left" style="margin-left: 20px;">
		<select class="uk-select" name="size">
			<?php
			for($i=0;$i<count($sizes);$i++){
				$check = "";
				if($i==2){$check = "selected";}
				echo '<option value="'.$i.'" '.$check.'>'.$sizes[$i].'</option>';
			}
			?>
		</select>
	</div>

	<hr /><div align="right">
		<a class="uk-button uk-button-default" href="./?page=regisRun&step=1"><span uk-icon="home"></span> ย้อนกลับ</a>
		<button type="submit" class="uk-button uk-button-secondary"><span uk-icon="check"></span> ขั้นตอนต่อไป</button>
	</div>
</form>

</div>
<?php
} else if(isset($_GET["step"]) and $_GET["step"]==3){
	if(!(isset($_POST["runType"]) and is_numeric($_POST["runType"]) and $_POST["runType"]<=3 and $_POST["runType"]>=0)){echo "<script>window.location='./?page=regisRun&step=1';</script>";}
	if(!(isset($_POST["subType"]) and is_numeric($_POST["subType"]) and $_POST["subType"]>=0 and $_POST["subType"]<=5)){echo "<script>window.location='./?page=regisRun&step=1';</script>";}
	if(!(isset($_POST["size"]) and is_numeric($_POST["size"]) and $_POST["size"]>=0 and $_POST["size"]<=11)){echo "<script>window.location='./?page=regisRun&step=1';</script>";}
?>
<h3 align="left"><span uk-icon="history"></span> ตรวจสอบข้อมูล</h3>
<form action="./?page=regisRun&step=4" method="post">
	<input type="hidden" name="runType" value="<?php echo $_POST['runType']; ?>" />
	<input type="hidden" name="subType" value="<?php echo $_POST['subType']; ?>" />
	<input type="hidden" name="size" value="<?php echo $_POST['size']; ?>" />
	<input type="hidden" name="token" value="<?php echo md5(time()-(60*60)); ?>" />
	<div align="left">
		<div>ประเภทการวิ่ง : <b><?php echo $runType[$_POST["runType"]] ?></b></div>
		<?php if($_POST["runType"] == 2){ ?>
			<div>รุ่นอายุ : <b><?php echo $ageType[intval($_POST["subType"])]; ?></b></div>
		<?php } ?>
		<?php if($_POST["runType"] == 3){ ?>
			<div>ประเภท : <b><?php echo $runType[intval($_POST["subType"])]; ?></b></div>
		<?php } ?>
		<div>ขนาดเสื้อ : <b><?php echo $sizes[intval($_POST["size"])]; ?></b></div>
		<div>ยอดชำระ : <b><?php echo number_format($runPrice[$_POST["runType"]],2); ?> บาท</b></div>
	</div>
	<hr /><div align="right">
		<a class="uk-button uk-button-default" href="./?page=regisRun&step=1"><span uk-icon="home"></span> ย้อนกลับ</a>
		<button type="submit" class="uk-button uk-button-secondary"><span uk-icon="check"></span> ยืนยันการลงทะเบียน</button>
	</div>
</form>
<?php
} else if(isset($_GET["step"]) and $_GET["step"]==4){

	if(isset($_POST["runType"]) and isset($_POST["subType"]) and isset($_POST["size"]) and is_numeric($_POST["runType"]) and is_numeric($_POST["subType"]) and is_numeric($_POST["size"])){
		$runType = $_POST["runType"];
		$subType = $_POST["subType"];
		$isVIP = false;
		$size = $_POST["size"];
		if($_POST["runType"]==3){ //VIP
			$runType = $subType;
			$subType = 0;
			$isVIP = true;
		}
		$sqlInsert = "INSERT INTO run(runid,isVIP,runType,subType,size,uid,registerTime,status,phone,follow_name) VALUES (NULL,?,?,?,?,?,?,?,?,?);";
		if($db->getNumber("SELECT uid FROM run WHERE uid=?",array($_SESSION["uid"])) <= 0){
			$db->insertRow($sqlInsert,array($isVIP,$runType,$subType,$size,$_SESSION["uid"],time(),0,"0","none"));
			?><div class="uk-alert-success" uk-alert><p><span uk-icon="check"></span> ลงทะเบียนสำเร็จ!</p></div><?php
		}
	} else{
		?><div class="uk-alert-danger" uk-alert><p><span uk-icon="warning"></span> ข้อมูลไม่ถูกต้อง</p></div><?php
	}

	?>
	<a class="uk-button uk-button-default" href="./"><span uk-icon="home"></span> กลับหน้าหลัก</a> 
	<a class="uk-button uk-button-primary" href="./?a=bill"><span uk-icon="credit-card"></span> ส่งหลักฐานชำระเงิน</a>
	<?php

} else{
	die("Not found!");
}
?>

<script src="public/assets/js/regisRun.js"></script>
</div>