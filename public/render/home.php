<?php
ini_set('display_errors', 1);
?>
<div class="content-box">
<div class="header">
<img src="public/assets/img/dmsu_run2020.png" width="60%" />
</div>

<h3 style="margin-bottom: 0px;margin-top:10px"><marquee onmouseover="this.stop();" onmouseout="this.start();"> 
	<span uk-icon="triangle-right"></span> 
    ขอเชิญชวนทุกท่านร่วมกิจกรรมวิ่งการกุศล <b>DMSU RUN 2020</b> รายได้เพื่อปรับปรุงสนามกีฬาเพื่อกิจกรรมนันทนาการของนักเรียน
     <span uk-icon="triangle-left"></span> 
</h3></marquee></h3>
<div class="uk-text-center uk-grid-small" uk-grid>
    <div class="uk-width-1-3@m">
        <?php include 'nav.php'; ?>
    </div>
    <div class="uk-width-expand@m">
        <div class="uk-card uk-card-default uk-card-body">
        	<?php
        	$allowedPage = array("bill","result","cert");
            $adminAllowedPage = array("join","runner","bill","overview","users");
        	if(isset($_GET["a"]) and in_array($_GET["a"], $allowedPage)){
        		include __DIR__ .'/../../public/render/_'.$_GET["a"].'.php';
            } else if(isset($_GET["admin"]) and in_array($_GET["admin"], $adminAllowedPage) and $_SESSION["admin"]==true){
                include __DIR__ .'/../../public/render/@'.$_GET["admin"].'.php';
        	} else{
        		include __DIR__ .'/../../public/render/_home.php';
        	}
        	?>
        </div>
    </div>
</div>

</div>