<div class="content-box login-box">

<h1><b>เข้าสู่ระบบ</b></h1>

<form class="login">
	<div id="box" style="display:none;margin-top: -5px;margin-bottom: -5px;"></div>
	<div align="left">
		<label><b>เบอร์โทรศัพท์</b> 
			<input type="text" maxlength="10" class="uk-input" name="telphone" pattern="[0-9]*" autocomplete="off" required="" placeholder="Phone Number (09XXXXXXXX)" />
		</label>
	</div>
	<div align="left">
		<label><b>เลขบัตรประชาชน</b> 
			<input type="text" maxlength="13" class="uk-input" name="idcard" pattern="[0-9]*" autocomplete="off" required="" placeholder="ID Card (XXXXXXXXXXXXX)" />
		</label>
	</div>

	<hr /><div>
		<button class="uk-button uk-button-primary" type="submit"><span uk-icon="sign-in"></span> เข้าสู่ระบบ</button> หรือ <a href="./?page=register">สมัครสมาชิก</a>
	</div>
</form>
<script src="public/assets/js/auth.js"></script>

</div>