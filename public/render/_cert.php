<?php
require(__DIR__ . "/../../public/modules/config.inc.php");
if(!class_exists('database')){die("Class not exist!");}
if(!isset($_SESSION["uid"])){die("access denied!");}
$allowCertPage = false;
$db = new database($config["user"], $config["pass"], $config["host"], $config["db"]);
$uid = $_SESSION["uid"];
?>
<h3 align="left"><span uk-icon="print"></span> เกียรติบัตร</h3>
<?php
if($_SESSION["admin"] != true && $allowCertPage==false){
?>
<span uk-icon="close"></span> ยังไม่พร้อมใช้งานในขณะนี้
<?php
} else{
    $sqlRun = "select runid from run where uid=?";
    if($db->getNumber($sqlRun,array($uid)) <= 0){
        echo '<span uk-icon="close"></span> คุณยังไม่ได้ลงสมัครวิ่ง';
    } else{
        $getData = $db->getRow("SELECT * FROM accounts WHERE uid=?",array($uid));
        $getRun = $db->getRow($sqlRun,array($uid));
?>
<script>var myRunID = <?php echo $getRun["runid"]; ?>;</script>
<div style="margin-bottom:10px;">
<select class="uk-select" onchange="getCert(this)">
<option selected value="<?php echo $getRun["runid"]; ?>"><?php echo $getData["first_name"]." ".$getData["last_name"]; ?></option>
<?php
foreach($db->getRows("select runid,follow_name from run where follower=?",array($uid)) as $row){
	echo "<option value='".$row["runid"]."'>".$row["follow_name"]."</option>";
}
?>
</select>
</div>

<div style="margin-bottom:10px;">
<img class="cert-img" />
</div>
<div align="right" class="btn-download">
<a class="print-cert" href="#"><span uk-icon="print"></span> ดาวน์โหลด</a>
</div>
<script>
function toDataURL(url, callback) {
  var xhr = new XMLHttpRequest();
  xhr.onload = function() {
    var reader = new FileReader();
    reader.onloadend = function() {
      callback(reader.result);
    }
    reader.readAsDataURL(xhr.response);
  };
  xhr.open('GET', url);
  xhr.responseType = 'blob';
  xhr.send();
}

function loadCert(dataUrl){
    $("div.btn-download").show(100);
    $("img.cert-img").attr("src",dataUrl);
    $("a.print-cert").attr("href",dataUrl);
    $("a.print-cert").attr("download","certificate.png");
    $("a.print-cert").attr("class","uk-button uk-button-default print-cert");
    $("a.print-cert").removeAttr("disabled");
}

$("div.btn-download").hide(100);
toDataURL('./public/api/api.cert.php?id='+myRunID, function(dataUrl) {
    loadCert(dataUrl);
});

function getCert(element){
    $("div.btn-download").hide(100);
    toDataURL('./public/api/api.cert.php?id='+element.value, function(dataUrl) {
        loadCert(dataUrl);
    });
}
</script>
<?php
    }
}
?>