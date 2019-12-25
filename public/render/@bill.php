<?php
require(__DIR__ ."/../../public/modules/config.inc.php");
if(!class_exists('database')){die("Class not exist!");}
$db = new database($config["user"], $config["pass"], $config["host"], $config["db"]);
if($_SESSION["admin"] !== true){die("Not have permission!");}
if(isset($_GET["accept"]) and is_numeric($_GET["accept"])){
	@$db->updateRow("UPDATE payment_report SET status='1' WHERE rid=?;",array(intval($_GET["accept"])));
	echo '<script>window.location="./?admin=bill";</script>';
}
if(isset($_GET["eject"]) and is_numeric($_GET["eject"])){
	@$db->updateRow("UPDATE payment_report SET status='2' WHERE rid=?;",array(intval($_GET["eject"])));
	echo '<script>window.location="./?admin=bill";</script>';
}
?>
<div style="margin:-35px;" class="uk-overflow-auto">
<table class="uk-table uk-table-hover uk-table-divider uk-table-justify">
	<thead>
		<tr>
			<th>#</th>
			<th>หมายเลขผู้วิ่ง</th>
			<th>วันเวลาที่โอน</th>
			<th>จำนวนเงิน</th>
			<th>รูปหลักฐาน</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
		<?php
		if($db->getNumber("select uid from payment_report",array()) > 0){
			foreach($db->getRows("select * from payment_report") as $row){
				$getRun = $db->getRow("select runType,isVIP from run where uid=?",array($_SESSION["uid"]));
				$price = ($getRun["isVIP"]==true)?$runPrice[3]:$runPrice[$getRun["runType"]];
				$icon = array("","check","close");
				echo '<tr> <td>'.$row["rid"].'</td> <td>'.str_pad($row["uid"], 4, '0', STR_PAD_LEFT).'</td> <td>'.date("d/m/Y H:i",$row["date"]).'</td> <td>'.number_format($row["amount"],2).'</td> <td><a href="./storage/user_'.$row["uid"].'/'.$row["file_name"].'" target="_new"><span uk-icon="search"></span> ดูรูป</a></td> <td>';
				if($row["status"]==0){
					echo '<a href="./?admin=bill&accept='.$row["rid"].'" onclick="return confirm(\'อนุมัติ แน่ใจหรือไม่?\')"><span uk-icon="check"></span></a><a href="./?admin=bill&eject='.$row["rid"].'" onclick="return confirm(\'ไม่อนุมัติ แน่ใจหรือไม่?\')"><span uk-icon="close"></span></a>';
				} else{
					echo "<span uk-icon='".$icon[$row["status"]]."'></span>";
				}
				echo '</td></tr>';
			}
		} else{
			echo '<tr> <td colspan="6">ไม่พบข้อมูล</td> </tr>';
		}
		?>
	</tbody>
</table>
</div>