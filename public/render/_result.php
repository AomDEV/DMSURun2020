<?php
require(__DIR__."/../../public/modules/config.inc.php");
if(!class_exists('database')){die("Class not exist!");}
if(!isset($_SESSION["uid"])){die("access denied!");}
$db = new database($config["user"], $config["pass"], $config["host"], $config["db"]);
$getRun = $db->getRow("select status from run where uid=?",array($_SESSION["uid"]));
?>
<h3 align="left"><span uk-icon="hashtag"></span> ผลการวิ่ง</h3>
<?php if($getRun["status"] == 1){ ?>
<h1><span uk-icon="check"></span> <b>ผ่าน</b></h1>
<?php } else if($getRun["status"] == 2){ ?>
<h1><span uk-icon="close"></span> <b>ไม่ผ่าน</b></h1>
<?php } else { ?>
<h1><span uk-icon="info"></span> <b>ยังไม่ได้วิ่ง</b></h1>
<?php } ?>
<hr /><div align="left"><label>คุณสามารถพิมพ์เกียรติบัตรได้ทันทีที่เมนู <b>"เกียรติบัตร"</b></label></div>