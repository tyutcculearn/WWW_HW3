<?php 
	session_start();
	extract($_POST);
	if(isset($Name) && $Name && isset($Email) && $Email && isset($Password) && $Password && isset($Cpassword) && $Cpassword)
	{
		$query = "select * from user where Email='".$Email."'";
		$database = mysql_connect("localhost","root","123456");
		mysql_select_db("hw3",$database);
		$result = mysql_query($query);
		if($row = mysql_fetch_array($result))
			$error = "Warning! 此帐号已被注册";
		else
		{
			if($Password != $Cpassword)
				$error = "Warning! 密码和确认密码不同";
			else
			{
				$insert = "insert into user (Name,Email,Password) values('".$Name."','".$Email."','".$Password."')";
				mysql_query($insert);
				$query = "select * from user where Email = '".$Email."'";
				$result = mysql_query($query);
				$row = mysql_fetch_array($result);
				$_SESSION['id'] = $row['id'];
				$_SESSION['Name'] = $row['Name'];
				$_SESSION['Email'] = $row['Email'];
				mysql_close($database);
				echo "<script>alert('注册成功');location.href=\"./index.php\";</script>";
			}
		}
		mysql_close($database);
	}
	else if(!isset($Name) && !isset($Email) && !isset($Password) && !isset($Cpassword))
	{
		$error = "";
	}
	else
	{
		$error = "Warning! 请确保填写所有栏位";
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
	<!-- 新 Bootstrap 核心 CSS 文件 -->
	<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">

	<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
	<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>

	<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
	<script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container">
		<div class="row" style="background-color: #272727;">
			<div class="col-md-2 col-md-offset-0">
				<h4 style="color:#00e3e3;">Geek Forum</h4>
			</div>
			<div class="col-md-2 col-md-offset-8">
				<ul class="nav nav-pills">
				  <li class="navli" role="presentation"><a href="./index.php" style="color: #00e3e3;">Home</a></li>
				  <li class="navli" role="presentation"><a href="./login.php" style="color: #00e3e3;">Login</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="container" style="margin-top: 1%;">
		<div class="row">
			<div class="col-md-offset-0 col-md-12">
				<H1><p class="text-center">Create new account</p></H1>
			</div>
			<div class="col-md-offset-4 col-md-4">
				<form method="post" action="./register.php">
				  <div class="form-group">
				    <label for="exampleInputPassword1">Name</label>
				    <input type="input" class="form-control" name="Name" id="exampleInputPassword1" placeholder="Name">
				  </div>
				  <div class="form-group">
				    <label for="exampleInputEmail1">Email</label>
				    <input type="input" class="form-control" name="Email" id="exampleInputEmail1" placeholder="Email">
				  </div>
				  <div class="form-group">
				    <label for="exampleInputPassword1">Password</label>
				    <input type="password" class="form-control" name="Password" id="exampleInputPassword1" placeholder="Password">
				  </div>
				  <div class="form-group">
				    <label for="exampleInputPassword1">Confirm Password</label>
				    <input type="password" class="form-control" name="Cpassword" id="exampleInputPassword1" placeholder="Password">
				  </div>
				  <?php 
				  	if(isset($error) && $error != "")
				  	{
				  		echo "<div class=\"form-group\" style=\"background-color: #FFD9EC;height: 40px;\">
				    			<h4 style=\"color:#AE0000;padding: 13px;\">".$error."</h4></div>";
				  	}
				  ?>
				  <button type="reset" class="btn btn-default">Cancel</button>
				  <button type="submit" class="btn btn-success">Create</button>
				</form>
			</div>
		</div>
	</div>
</body>	
</html>