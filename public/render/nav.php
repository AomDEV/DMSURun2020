<div class="uk-card uk-card-default uk-card-body">
    <ul class="uk-nav-default uk-nav-parent-icon" uk-nav>
        <li <?php if((isset($_GET["page"]) and $_GET["page"]=="home") and (!isset($_GET["page"]) or !isset($_GET["a"]))){echo 'class="uk-active"';} ?>>
            <a href="./?page=home"><div align="left"><span class="uk-margin-small-right" uk-icon="icon: home"></span> หน้าหลัก</div></a>
        </li>
        <li>
            <a href="./?page=regisRun"><div align="left"><span class="uk-margin-small-right" uk-icon="icon: pencil"></span> ลงทะเบียนวิ่ง</div></a>
        </li>
        <li <?php if(isset($_GET["a"]) and $_GET["a"]=="bill"){echo 'class="uk-active"';} ?>>
            <a href="./?a=bill"><div align="left"><span class="uk-margin-small-right" uk-icon="icon: credit-card"></span> ส่งหลักฐานชำระเงิน</div></a>
        </li>
        <li <?php if(isset($_GET["a"]) and $_GET["a"]=="result"){echo 'class="uk-active"';} ?>>
            <a href="./?a=result"><div align="left"><span class="uk-margin-small-right" uk-icon="icon: hashtag"></span> ผลการวิ่ง</div></a>
        </li>
        <li <?php if(isset($_GET["a"]) and $_GET["a"]=="cert"){echo 'class="uk-active"';} ?>>
            <a href="./?a=cert"><div align="left"><span class="uk-margin-small-right" uk-icon="icon: print"></span> เกียรติบัตร</div></a>
        </li>
        <?php if(isset($_SESSION["admin"]) and $_SESSION["admin"]==true){ ?>
            <li class="uk-nav-divider"></li>
            <li><a href="./?admin=overview"><div align="left"><span class="uk-margin-small-right" uk-icon="icon: thumbnails"></span> ภาพรวม</div></a></li>
            <li><a href="./?admin=join"><div align="left"><span class="uk-margin-small-right" uk-icon="icon: check"></span> ลงชื่อเข้างาน</div></a></li>
            <li><a href="./?admin=runner"><div align="left"><span class="uk-margin-small-right" uk-icon="icon: users"></span> ดูข้อมูลผู้วิ่ง</div></a></li>
            <li><a href="./?admin=bill"><div align="left"><span class="uk-margin-small-right" uk-icon="icon: history"></span> อนุมัติหลักฐาน</div></a></li>
        <?php } ?>
        <li class="uk-nav-divider"></li>
        <li><a href="./?page=logout"><div align="left"><span class="uk-margin-small-right" uk-icon="icon: sign-out"></span> ออกจากระบบ</div></a></li>
    </ul>
</div>