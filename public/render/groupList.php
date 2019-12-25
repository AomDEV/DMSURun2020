<?php
session_start();
require("../modules/db.pdo.php");
require("../modules/config.inc.php");
if(!isset($_SESSION["uid"])){die("access denied!");}
?>
<h3>รายชื่อการวิ่งแบบกลุ่ม</h3>
<table border="1">
	<tr>
		<th>เลขประจำตัว</th> <th>ชื่อ-สกุล</th> <th>ประเภทการวิ่ง</th> <th>ประเภท</th> <th>ขนาดเสื้อ</th>
	</tr>
<?php
$db = new database($config["user"], $config["pass"], $config["host"], $config["db"]);
foreach($db->getRows("select runid,isVIP,follow_name,runType,subType,size from run where follower=?",array($_SESSION["uid"])) as $row){
	$runLabel = ($row["isVIP"]==true)?"VIP (".substr($runType[$row["runType"]], 0, 6).")":$runType[$row["runType"]];
	$subType = "-";
	if($row["runType"] == 2){
		$subType = $ageType[intval($row["subType"])];
	} if($row["runType"] == 3){ 
		$subType = $runType[intval($row["subType"])];
	}
	$runID = '#'.str_pad($row["runid"], 4, '0', STR_PAD_LEFT);
	echo "<tr> <td><center>".$runID."</center></td> <td>".$row["follow_name"]."</td> <td>".$runLabel."</td> <td><center>".$subType."</center></td> <th>".$sizes[intval($row["size"])]."</th> </tr>";
}
?>
</table>