<?php
error_reporting(E_ERROR | E_PARSE); 
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}?>
<!DOCTYPE html>
<html>
<head>
	<title>Manage Users</title>
	<style>
		#class {
			font-family: "Times New Roman", Times, serif;
			background: rgba(255,255,255,.1);
			border: none;
			border-radius: 4px;
			font-size: 14px;
			margin: 0;
			outline: 0;
			padding: 10px;
			width: 100%;
			box-sizing: border-box; 
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box; 
			background-color: #e8eeef;
			color:#8a97a0;
			-webkit-box-shadow: 0 1px 0 rgba(0,0,0,0.03) inset;
			box-shadow: 0 1px 0 rgba(0,0,0,0.03) inset;
			margin-bottom: 30px;
			border: 1px solid #e8eeef;
		}
	</style>
<link rel="stylesheet" type="text/css" href="css/manageusers.css">
<script>
  $(window).on("load resize ", function() {
    var scrollWidth = $('.tbl-content').width() - $('.tbl-content table').width();
    $('.tbl-header').css({'padding-right':scrollWidth});
}).resize();
</script>
<script src="https://code.jquery.com/jquery-3.3.1.js"
        integrity="sha1256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous">
</script>
<script src="js/jquery-2.2.3.min.js"></script>
<script src="js/manage_users.js"></script>
<script>
  $(document).ready(function(){
  	  $.ajax({
	url: "manage_users_up.php"
        }).done(function(data) {
        $('#manage_users').html(data);
      });
    setInterval(function(){
      $.ajax({
        url: "manage_users_up.php"
        }).done(function(data) {
        $('#manage_users').html(data);
      });
    },5000);
  });
</script>
</head>
<body>
<?php include'header.php';?>
<main>
	<h1 class="slideInDown animated">THÊM THÔNG TIN HỌC SINH </h1>
	<div class="form-style-5 slideInDown animated">
		<div class="alert">
		<label id="alert"></label>
		</div>
		<form>
			<fieldset>
			<legend><span class="number">1</span> ID Vân tay:</legend>
				<label>Nhập ID vân tay có giá trị từ 1 đến 500:</label>
				<input type="number" name="fingerid" id="fingerid" placeholder="User Fingerprint ID...">
				<button type="button" name="fingerid_add" class="fingerid_add">Thêm ID</button>
			</fieldset>
			<fieldset>
				<legend><span class="number">2</span> Thông tin học sinh</legend>
				<input type="text" name="name" id="name" placeholder="Tên học sinh...">
				<select name="class" id="class">
					<option value="" disabled selected>Chọn Lớp...</option>
					<option value="11A1">11A1</option>
					<option value="11A2">11A2</option>
					<option value="11A3">11A3</option>
					<!-- Thêm các tùy chọn khác tùy ý -->
				</select>
				<input type="number" name="number" id="number" maxlength="10" placeholder="Số điện thoại...">
			</fieldset>
			<fieldset>
			<legend><span class="number">3</span> Thêm thông tin</legend>
			<label>
				Thời gian đăng ký:
				<input type="time" name="timein" id="timein">
				<input type="radio" name="gender" class="gender" value="Female">Nữ
	          	<input type="radio" name="gender" class="gender" value="Male" checked="checked">Nam
	      	</label >
			</fieldset>
			<button type="button" name="user_add" class="user_add">Thêm thông tin</button>
			<button type="button" name="user_upd" class="user_upd">Sửa thông tin</button>
			<button type="button" name="user_rmo" class="user_rmo">Xóa thông tin</button>
		</form>
	</div>

	<div class="section">
	<!--User table-->
		<div class="tbl-header slideInRight animated">
		    <table cellpadding="0" cellspacing="0" border="0">
		      <thead>
		        <tr>
	        	  <th>ID Vân tay</th>
		          <th>Tên</th>
		          <th>Giới tính</th>
		          <th>Lớp</th>
		          <th>Ngày</th>
		          <th>Thời gian</th>
		        </tr>
		      </thead>
		    </table>
		</div>
		<div class="tbl-content slideInRight animated">
		    <table cellpadding="0" cellspacing="0" border="0">
		      <div id="manage_users"></div>
		</div>
	</div>

</main>
</body>
</html>