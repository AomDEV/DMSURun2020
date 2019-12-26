<?php
require(__DIR__ ."/../../public/modules/config.inc.php");
if(!class_exists('database')){die("Class not exist!");}
$db = new database($config["user"], $config["pass"], $config["host"], $config["db"]);
?>

<div class="content-box">

<h2 align="left"><span uk-icon="user"></span> <b>ตรวจสอบรายชื่อ</b></h2>

<div style="margin-left:0px;margin-right:0px;" class="uk-overflow-auto">
<table class="runnerTable display">
	<thead>
		<tr class="info">
			<th><center>#</center></th>
			<th><center>ชื่อ-สกุล</center></th>
			<th><center>รายการวิ่ง</center></th>
			<th><center>ประเภท</center></th>
			<th><center>ไซส์เสื้อ</center></th>
			<th><center>สถานะ</center></th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$getRunner = $db->getRows("select * from run order by runid asc",array());
		foreach($getRunner as $row){
			$thisUID = ($row["uid"]!=0)?$row["follower"]:$row["uid"];
			//Check payment
			$getRun = $db->getRows("select runType,isVIP,follow_name,follower from run where uid=? or follower=?",array($thisUID,$thisUID));
			$priceTotal = 0;
			foreach($getRun as $row0){
				$price = ($row0["isVIP"]==true)?$runPrice[3]:$runPrice[$row0["runType"]];
				$priceTotal += intval($price);
			}
			if($db->getNumber("select amount from payment_report where uid=? and status=1",array($thisUID))>=1){
				$getTotalPrice = $db->getRows("select amount from payment_report where uid=? and status=1",array($thisUID));
				$paidTotal = 0;
				foreach($getTotalPrice as $row){
					$paidTotal += intval($row["amount"]);
				}
				$priceTotal -= $paidTotal;
			}
			$icon = "warning";
			if($priceTotal<=0){$icon="check";}
			//Check payment

			$runTypeLabel = ($row["isVIP"]==true)?$runSubType[$row["runType"]]:(($row["runType"]==2)?$ageType[$row["subType"]]:"ปกติ");
			$runLabel = ($row["isVIP"]==true)?"VIP":$runType[$row["runType"]];
			if(isset($row["uid"]) and $row["uid"]==0){
				$fullName = $row["follow_name"];
			} else{
				$getName = $db->getRow("select first_name,last_name from accounts where uid=?",array($row["uid"]));
				$fullName = $getName["first_name"]." ".$getName["last_name"];
			}
			echo '<tr>';
			echo '<td><center>#'.str_pad($row["runid"], 4, '0', STR_PAD_LEFT).'</center></td>';
			echo '<td>'.$fullName.'</td>';
			echo '<td>'.$runLabel.'</td>';
			echo '<td>'.$runTypeLabel.'</td>';
			echo '<td><center>'.$sizes[$row["size"]].'</center></td>';
			echo '<td><center><span uk-icon="'.$icon.'"></span></center></td>';
			echo '</tr>';
		}

		?>
	</tbody>
</table>
</div>

<link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

<script type="text/javascript"> 
$(function(){
    $('table.runnerTable').dataTable({
		"oLanguage": {
			"sLengthMenu": "แสดง _MENU_ ข้อมูล ต่อหน้า",
			"sZeroRecords": "ไม่พบข้อมูลที่ค้นหา",
			"sInfo": "แสดง _START_ ถึง _END_ ของ _TOTAL_ ข้อมูล",
			"sInfoEmpty": "แสดง 0 ถึง 0 ของ 0 เร็คคอร์ด",
			"sInfoFiltered": "(จากทั้งหมด _MAX_ ข้อมูล)",
			"sSearch": "ค้นหา :"
		}
    });
} );
</script>

</div>