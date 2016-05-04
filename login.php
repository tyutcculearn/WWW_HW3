<?php
	session_start();
	extract($_POST);
	if(isset($_GET['clear']) && $_GET['clear'])
		session_destroy();
	if(isset($Email) && isset($Password) && $Email && $Password)
	{
		$query = "select * from user where Email='".$Email."'";
		$database = mysql_connect("localhost","root","123456");
		mysql_select_db("hw3",$database);
		$result = mysql_query($query);
		if(!($row = mysql_fetch_array($result)))
		{
			$error = "此帐号不存在！";
			mysql_close($database);
		}
		else
		{
			$query = "select * from user where Email='".$Email."' and Password='".$Password."'";
			$result = mysql_query($query);
			$row = mysql_fetch_array($result);
			if(!$row)
			{
				$error = "密码错误！";
				mysql_close($database);
			}
			mysql_close($database);
			$_SESSION['id'] = $row['id'];
			$_SESSION['Name'] = $row['Name'];
			$_SESSION['Email'] = $row['Email'];
			echo "<script>location.href=\"./index.php\"</script>";
		}	
	}
	else
	{
		if((isset($Email) && !$Email) || (isset($Password) && !$Password))
			$error = "信箱以及密码栏位请勿空白";
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
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
	<div class="container" style="margin-top: 1%; height: 250px;background-color: #F0F0F0;">
		<div class="row">
			<div class="col-md-offset-1">
				<H1>Welcome to Geek Forum</H1>
				<p>Login and have fun!</p>
				<p>If you don't have any account,please register one right now!</p>
			</div>
			<div class="col-md-offset-1">
				<form class="form-inline" method="post" action="./login.php">
				  <div class="form-group">
				    <label class="sr-only" for="exampleInputEmail3">Email address</label>
				    <input type="input" class="form-control" name="Email" id="exampleInputEmail3" placeholder="Email">
				  </div>
				  <div class="form-group">
				    <label class="sr-only" for="exampleInputPassword3">Password</label>
				    <input type="password" class="form-control" name = "Password" id="exampleInputPassword3" placeholder="Password">
				  </div>
				  <button type="submit" class="btn btn-primary">Login</button>
				  <button type="button" class="btn btn-success"><a href="./register.php" style="color:white;text-decoration: none;">Register</a></button>
				</form>
			</div>
			<?php
				if(isset($error) && $error != "")
					echo"<div class='col-md-offset-1'><h4 style='color:red;'>".$error."</h4></div>" ; 
			?>
		</div>
	</div>
</body>
</html>