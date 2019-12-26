<?php
require(__DIR__ ."/../../public/modules/config.inc.php");
if(!class_exists('database')){die("Class not exist!");}
$db = new database($config["user"], $config["pass"], $config["host"], $config["db"]);
if($_SESSION["admin"] != true){die("Not have permission!");}
?>
<div style="margin-left:-30px;margin-right:-30px;" class="uk-overflow-auto">
<table class="uk-table uk-table-hover uk-table-divider uk-table-justify">
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
		if($db->getNumber("select uid from run",array()) > 0){
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