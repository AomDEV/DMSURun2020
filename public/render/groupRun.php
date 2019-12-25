<?php
require(__DIR__ ."/../../public/modules/config.inc.php");
if(!class_exists('database')){die("Class not exist!");}
$db = new database($config["user"], $config["pass"], $config["host"], $config["db"]);
?>
<div class="content-box">
	<h1><b><span uk-icon="icon: users; ratio: 1.5"></span> ลงทะเบียนการวิ่งแบบกลุ่ม</b></h1>

	<?php
	if($db->getNumber("SELECT uid FROM run WHERE uid=?",array($_SESSION["uid"])) <= 0){
		echo("กรุณาลงทะเบียนวิ่งแบบเดี่ยวก่อน!");
		echo '<hr /><a class="uk-button uk-button-default" href="./?page=regisRun"><span uk-icon="home"></span> ย้อนกลับ</a>';
	} else{
	?>
	<form action="./?page=groupRun" method="post">
		<?php
		if(isset($_POST["count"]) and is_numeric($_POST["count"]) and $_POST["count"]<=10){
			for($i=1;$i<=intval($_POST["count"]);$i++){
		?>
		<h3 align="right" style="margin:0px;margin-bottom: 5px;"><b>#<?php echo $i; ?></b></h3>
		<div align="left">
		<label><b>ชื่อ-สกุล <font color="red"><b>*</b></font></b> <font color="grey">(ไม่ต้องใส่คำนำหน้า)</font> 
			<div class="uk-text-center uk-grid-small" uk-grid><div class="uk-width-1-2">
				<input type="text" class="uk-input" style="margin-right:0px;" name="firstn[<?php echo $i-1; ?>]" autocomplete="off" required placeholder="First Name" />
			</div><div class="uk-width-1-2">
				<input type="text" class="uk-input" style="margin-left:0px;" name="lastn[<?php echo $i-1; ?>]" autocomplete="off" required placeholder="Last Name" />
			</div></div></label>
		</div>
		<div align="left">
			<div class="uk-text-center uk-grid-small" uk-grid><div class="uk-width-1-2" align="left">
				<label><b>ไซส์เสื้อ <font color="red"><b>*</b></font></b>
				<select class="uk-select" name="size[<?php echo $i-1; ?>]">
					<?php
					for($i0=0;$i0<count($sizes);$i0++){
						$check = "";
						if($i0==2){$check = "selected";}
						echo '<option value="'.$i0.'" '.$check.'>'.$sizes[$i0].'</option>';
					}
					?>
				</select></label>
			</div><div class="uk-width-1-2" align="left">
				<label><b>ประเภทการวิ่ง <font color="red"><b>*</b></font></b>
				<select class="uk-select runtype" data-index="<?php echo $i-1; ?>" name="runType[<?php echo $i-1; ?>]">
					<?php
					for($i0=1;$i0<count($runType);$i0++){
						$check = "";
						if($i0==1){$check = "selected";}
						echo '<option value="'.$i0.'" '.$check.'>'.$runType[$i0].'</option>';
					}
					?>
				</select></label>
			</div></div></div>
			<div align="left"><div class="uk-text-center uk-grid-small" uk-grid><div class="uk-width-1-2" align="left">
				<label><b>เบอร์โทรศัพท์ <font color="red"><b>*</b></font></b>
				<input type="text" maxlength="10" name="phone[<?php echo $i-1;?>]" required autocomplete="off" class="uk-input" placeholder="Phone Number" /></label>
			</div><div class="uk-width-1-2 subtype_<?php echo $i-1; ?>" style="display:none;" align="left">
				<label><b><span id="label_<?php echo $i-1; ?>"></span> <font color="red"><b>*</b></font></b>
				<select class="uk-select subTypeSelect_<?php echo $i-1; ?>" name="subType[<?php echo $i-1; ?>]"><option value="0" selected="">0</option>
				</select></label>
			</div></div>
		</div>
		<hr />
		<?php
			}
		?>
		<input type="hidden" name="act" value="1" />
		<div align="right">
			<a class="uk-button uk-button-default" href="./?page=groupRun"><span uk-icon="home"></span> ย้อนกลับ</a>
			<button type="submit" class="uk-button uk-button-secondary"><span uk-icon="check"></span> ขั้นตอนต่อไป</button>
		</div>
		<?php
		} else if(isset($_POST["act"]) and isset($_POST["firstn"]) and isset($_POST["lastn"]) and isset($_POST["phone"]) and isset($_POST["runType"]) and isset($_POST["subType"]) and is_array($_POST["firstn"]) and is_array($_POST["lastn"]) and is_array($_POST["phone"]) and is_array($_POST["runType"]) and is_array($_POST["subType"]) and $_POST["act"]=="1"){
			echo '<h3 align="left"><span uk-icon="search"></span> ตรวจสอบข้อมูล</h3>';
			echo '<div align="left">';
			$totalPrice = 0;
			$error = false;
			for($i=0;$i<count($_POST["firstn"]);$i++){
				if(!is_numeric($_POST["phone"][$i]) or strlen($_POST["phone"][$i])!=10){echo "ข้อมูลไม่ถูกต้อง";$error = true;break;}
				$price = number_format($runPrice[$_POST["runType"][$i]],2);
				$totalPrice += intval($runPrice[$_POST["runType"][$i]]);
				echo "<b>#".($i+1)."</b> ";
				echo "<h3 style='margin:0px;'>".$_POST["firstn"][$i]." ".$_POST["lastn"][$i]."</h3>";
				echo "เบอร์โทรศัพท์ : <b>".$_POST["phone"][$i]."</b>";
				echo "<div>การวิ่ง : <b>".$runType[$_POST["runType"][$i]]."</b></div>";
				if($_POST["runType"][$i] == 2){
					echo '<div>รุ่นอายุ : <b>'.$ageType[intval($_POST["subType"][$i])].'</b></div>';
				} if($_POST["runType"][$i] == 3){ 
					echo '<div>ประเภท : <b>'.$runType[intval($_POST["subType"][$i])].'</b></div>';
				}
				echo '<div>ขนาดเสื้อ : <b>'.$sizes[intval($_POST["size"][$i])].'</b></div>';
				echo '<div>ยอดชำระ : <b>'.$price.' บาท</b></div>';
				echo "<hr />";
			}
			echo '</div>';
		?>
		<div align="right">
			ยอดทั้งหมด <h3><b><?php echo number_format($totalPrice,2); ?></b> บาท</h3>
		</div>
		<?php
		if(!$error){
		for($i=0;$i<count($_POST["firstn"]);$i++){
			echo "<input type='hidden' name='firstn[".$i."]' value='".$_POST["firstn"][$i]."' />";
			echo "<input type='hidden' name='lastn[".$i."]' value='".$_POST["lastn"][$i]."' />";
			echo "<input type='hidden' name='phone[".$i."]' value='".$_POST["phone"][$i]."' />";
			echo "<input type='hidden' name='size[".$i."]' value='".intval($_POST["size"][$i])."' />";
			echo "<input type='hidden' name='runType[".$i."]' value='".intval($_POST["runType"][$i])."' />";
			echo "<input type='hidden' name='subType[".$i."]' value='".intval($_POST["subType"][$i])."' />";
		}
		?>
		<input type="hidden" name="act" value="2" />
		<div align="right">
			<a class="uk-button uk-button-default" href="./?page=groupRun"><span uk-icon="home"></span> ย้อนกลับ</a>
			<button type="submit" class="uk-button uk-button-secondary"><span uk-icon="check"></span> ลงทะเบียน</button>
		</div>
		<?php
		} else{ echo '<a class="uk-button uk-button-default" href="./?page=groupRun"><span uk-icon="home"></span> ย้อนกลับ</a>'; }
		} else if(isset($_POST["act"]) and isset($_POST["firstn"]) and isset($_POST["lastn"]) and isset($_POST["phone"]) and isset($_POST["runType"]) and isset($_POST["subType"]) and is_array($_POST["firstn"]) and is_array($_POST["lastn"]) and is_array($_POST["phone"]) and is_array($_POST["runType"]) and is_array($_POST["subType"]) and $_POST["act"]=="2"){
			for($i=0;$i<count($_POST["firstn"]);$i++){
				if(isset($_POST["runType"][$i]) and isset($_POST["subType"][$i]) and isset($_POST["size"][$i]) and isset($_POST["phone"][$i]) and is_numeric($_POST["runType"][$i]) and is_numeric($_POST["subType"][$i]) and is_numeric($_POST["size"][$i]) and is_numeric($_POST["phone"][$i]) and strlen($_POST["phone"][$i])==10){
					$runType = $_POST["runType"][$i];
					$subType = $_POST["subType"][$i];
					$isVIP = false;
					$size = $_POST["size"][$i];
					$fullName = $_POST["firstn"][$i] . " " . $_POST["lastn"][$i];
					$phone = $_POST["phone"][$i];
					if($_POST["runType"][$i]==3){ //VIP
						$runType = $subType;
						$subType = 0;
						$isVIP = true;
					}
					$sqlInsert = "INSERT INTO run(runid,isVIP,runType,subType,size,uid,registerTime,status,follower,follow_name,phone) VALUES (NULL,?,?,?,?,?,?,?,?,?,?);";
					if($db->getNumber("SELECT uid FROM run WHERE uid='0' and follower=? and follow_name=?",array($_SESSION["uid"],$fullName)) <= 0){
						$db->insertRow($sqlInsert,array($isVIP,$runType,$subType,$size,0,time(),0,$_SESSION["uid"],$fullName,$phone));
					}
				} else{
					?><div class="uk-alert-danger" uk-alert><p><span uk-icon="warning"></span> ข้อมูลไม่ถูกต้อง</p></div><?php
					break;
				}
			}
			?>
			<div class="uk-alert-success" uk-alert><p><span uk-icon="check"></span> ลงทะเบียนสำเร็จ!</p></div><hr />
			<a class="uk-button uk-button-default" href="./"><span uk-icon="home"></span> กลับหน้าหลัก</a> 
			<a class="uk-button uk-button-primary" href="./?a=bill"><span uk-icon="credit-card"></span> ส่งหลักฐานชำระเงิน</a>
			<?php
		} else{
		?>
		<h3 align="left"><span uk-icon="code"></span> จำนวนคน <label><font color="grey" size="2">(จำกัดครั้งละ 10 คน)</font></label></h3>
		<div align="left" style="margin-left: 20px;">
			<select class="uk-select" name="count">
				<?php for($i=1;$i<=10;$i++){echo '<option value="'.$i.'">'.$i.'</option>';} ?>
			</select>
		</div>

		<hr /><div align="right">
			<a class="uk-button uk-button-default" href="./"><span uk-icon="home"></span> หน้าหลัก</a>
			<button type="submit" class="uk-button uk-button-secondary"><span uk-icon="check"></span> ขั้นตอนต่อไป</button>
		</div>
		<?php } ?>
	</form>
	<?php } ?>
</div>
<script>
<?php
function js_str($s)
{
    return '"' . addcslashes($s, "\0..\37\"\\") . '"';
}

function js_array($array)
{
    $temp = array_map('js_str', $array);
    return '[' . implode(',', $temp) . ']';
}

echo 'var subType1 = ', js_array($ageType), ';';
echo 'var startType1 = 0;';
echo 'var endType1 = '.(count($ageType)).';';
echo 'var subType2 = ', js_array($runType), ';';
echo 'var startType2 = 1;';
echo 'var endType2 = '.(count($runType)-1).';';
?>
</script>
<script src="public/assets/js/group.js"></script>