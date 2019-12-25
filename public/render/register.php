<div class="content-box login-box">

<h1><b>ลงทะเบียน</b></h1>

<form class="register">
	<div id="box" style="display:none;margin-top: -5px;margin-bottom: -5px;"></div>
	<div align="left">
		<label>
			<b>เบอร์โทรศัพท์ <font color="red"><b>*</b></font></b> 
			<input type="text" maxlength="10" class="uk-input" name="telphone" pattern="\d+" autocomplete="off" required="" placeholder="Phone Numbe" /> 
		</label>
	</div>
	<div align="left">
		<label>
			<b>เลขบัตรประชาชน <font color="red"><b>*</b></font></b> 
			<input type="text" maxlength="13" class="uk-input" name="idcard" pattern="\d+" autocomplete="off" required="" placeholder="ID Card" />
		</label>
	</div>
	<div align="left">
		<label>
			<b>ชื่อ-สกุล <font color="red"><b>*</b></font></b> <font color="grey">(ไม่ต้องใส่คำนำหน้า)</font> 
			<div class="uk-text-center uk-grid-small" uk-grid>
				<div class="uk-width-1-2">
					<input type="text" class="uk-input" style="margin-right:0px;" name="firstn" autocomplete="off" required="" placeholder="First Name" />
				</div>
				<div class="uk-width-1-2">
					<input type="text" class="uk-input" style="margin-left:0px;" name="lastn" autocomplete="off" required="" placeholder="Last Name" />
				</div>
			</div>
		</label>
	</div>
	<div align="left">
		<label>
			<b>เพศ <font color="red"><b>*</b></font></b> 
			<div class="uk-grid-small uk-child-width-auto uk-grid">
				<label><input class="uk-radio" type="radio" name="gender" value="0" checked="" /> ชาย</label>
				<label><input class="uk-radio" type="radio" name="gender" value="1" /> หญิง</label>
			</div>
		</label>
	</div>
	<div align="left">
		<label>
			<b>วัน/เดือน/ปี เกิด <font color="red"><b>*</b></font></b> 
			<input type="date" class="uk-input" name="birthday" autocomplete="off" required="" placeholder="Birthday (dd/mm/YYYY)" />
		</label>
	</div>
	<div align="left">
		<label>
			<b>E-Mail <font color="red"><b></b></font></b> 
			<input type="email" maxlength="120" class="uk-input" name="email" autocomplete="off" placeholder="Email (xxx@xxx.xxx)" />
		</label>
	</div>

	<div align="left" style="margin-top:5px;">
		<font color="grey">โปรด<b>ตรวจสอบ</b>ข้อมูลให้ถูกต้อง <b>ไม่สามารถแก้ไขได้</b></font>
	</div>

	<hr /><div>
		<button class="uk-button uk-button-primary" type="submit"><span uk-icon="check"></span> สมัครสมาชิก</button> หรือ <a href="./?page=login">เข้าสู่ระบบ</a>
	</div>
</form>
<script src="public/assets/js/auth.js"></script>

</div>