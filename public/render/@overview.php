<?php
require("public/modules/config.inc.php");
$db = new database($config["user"], $config["pass"], $config["host"], $config["db"]);
if($_SESSION["admin"] !== true){die("Not have permission!");}
$allAccount = $db->getNumber("select uid from accounts",array());
$allRunner = $db->getNumber("select uid from run",array());
$allPaid = $db->getNumber("select uid from payment_report where status=1",array());
$percentPaid = @($allPaid/$allAccount)*100;
$allFunrun = $db->getNumber("select uid from run where runType=1 and isVIP=0",array());
$percentFunrun = @($allFunrun/$allRunner)*100;
$allMini = $db->getNumber("select uid from run where runType=2 and isVIP=0",array());
$percentMini = @($allMini/$allRunner)*100;
$allVIP = $db->getNumber("select uid from run where isVIP=1",array());
$percentVIP = @($allVIP/$allRunner)*100;
?>

<div align="center">
	<label>ยอดผู้เข้าร่วมทั้งหมด</label><h1 style="margin-top:0px;"><b><?php echo number_format($allRunner,0); ?></b> คน</h1>
</div>

<Div align="left">
	<label><b>ชำระเงินแล้ว</b> (<?php echo number_format($allPaid,0); ?>/<?php echo number_format($allAccount,0); ?>) <?php echo round($percentPaid,2)."%"; ?></label>
	<progress class="uk-progress" value="<?php echo $percentPaid; ?>" max="100" style="margin-top:0px;"></progress>
</Div>
<Div align="left">
	<label><b>FUN RUN</b> (<?php echo number_format($allFunrun,0); ?> คน) <?php echo round($percentFunrun,2)."%"; ?></label>
	<progress class="uk-progress" value="<?php echo $percentFunrun; ?>" max="100" style="margin-top:0px;"></progress>
</Div>
<Div align="left">
	<label><b>Mini-Marathon</b> (<?php echo number_format($allMini,0); ?> คน) <?php echo round($percentMini,2)."%"; ?></label>
	<progress class="uk-progress" value="<?php echo $percentMini; ?>" max="100" style="margin-top:0px;"></progress>
</Div>
<Div align="left">
	<label><b>VIP</b> (<?php echo number_format($allVIP,0); ?> คน) <?php echo round($percentVIP,2)."%"; ?></label>
	<progress class="uk-progress" value="<?php echo $percentVIP; ?>" max="100" style="margin-top:0px;"></progress>
</Div>