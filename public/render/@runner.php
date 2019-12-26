<?php
require(__DIR__ ."/../../public/modules/config.inc.php");
if(!class_exists('database')){die("Class not exist!");}
$db = new database($config["user"], $config["pass"], $config["host"], $config["db"]);
if($_SESSION["admin"] != true){die("Not have permission!");}
?>
<div align="left" style="margin-top:-20px;margin-left:-20px;margin-right:-20px;">
	<label><span uk-icon="cog"></span></label> 
	<a style="margin-top:2px;" class="uk-button uk-button-secondary uk-button-small <?php if(isset($_GET['filter']) and $_GET['filter']=='funrun'){echo 'uk-disabled';} ?>" href="./?admin=runner&filter=funrun">FUN-RUN</a> 
	<a style="margin-top:2px;" class="uk-button uk-button-secondary uk-button-small <?php if(isset($_GET['filter']) and $_GET['filter']=='mini'){echo 'uk-disabled';} ?>" href="./?admin=runner&filter=mini">MINI</a> 
	<a style="margin-top:2px;" class="uk-button uk-button-secondary uk-button-small <?php if(isset($_GET['filter']) and $_GET['filter']=='vip'){echo 'uk-disabled';} ?>" href="./?admin=runner&filter=vip">VIP</a> 
	<a style="margin-top:2px;" class="uk-button uk-button-secondary uk-button-small <?php if(isset($_GET['filter']) and $_GET['filter']=='notpaid'){echo 'uk-disabled';} ?>" href="./?admin=runner&filter=notpaid">ยังไม่ชำระเงิน</a> 
	<a style="margin-top:2px;" class="uk-button uk-button-secondary uk-button-small <?php if(isset($_GET['filter']) and $_GET['filter']=='paid'){echo 'uk-disabled';} ?>" href="./?admin=runner&filter=paid">ชำระแล้ว</a> 
	<a style="margin-top:2px" class="uk-button uk-button-link uk-button-small search-click"><span uk-icon="search"></span></a>
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
} );
</script>