<?php
	session_start();
	if(!isset($_SESSION['id']))
		echo "<script>location.href=\"./login.php\"</script>";
	if(isset($_GET['article_id']))
	{
		if((int)$_GET['article_id'] >= 0)
		{
			$query = "select * from article where id='".$_GET['article_id']."'";
			$database = mysql_connect("localhost","root","123456");
			mysql_select_db("hw3",$database);
			$result = mysql_query($query);
			$row = mysql_fetch_array($result);
			if(!get_magic_quotes_gpc())
			{
				$title = stripslashes($row['title']);
			    $content = stripslashes($row['content']);
			}
			else
			{
				$title = $row['title'];
			    $content = $row['content'];
			}
			mysql_close($database);
		}
	}
	if(isset($_POST['title']))
	{
		if(!get_magic_quotes_gpc())
		{
			$title = addslashes($_POST['title']);
		    $content = addslashes($_POST['content']);
		}
		else
		{
			$title = $_POST['title'];
		    $content = $_POST['content'];
		}
		date_default_timezone_set("Asia/Shanghai");
		$date = date("Y-m-d H:i:s",time());
		if($_POST['article_id'] != "")
		{
			$update = "update article set title='".$_POST['title']."', content='".$_POST['content']."',last_update='".$date."' where id='".$_POST['article_id']."'";
			$database = mysql_connect("localhost","root","123456");
			mysql_select_db("hw3",$database);
			mysql_query($update);
			mysql_close($database);
			echo "<script>alert('修改成功');window.location.href=\"./article.php?article_id=".$_POST['article_id']."\";</script>";
		}
		else
		{
			$insert = "insert into article (author_id,title,content,created_time,last_update) values('".$_SESSION['id']."','".$title."','".$content."','".$date."','".$date."')";
			$database = mysql_connect("localhost","root","123456");
			mysql_select_db("hw3",$database);
			mysql_query($insert);
			$query = "select LAST_INSERT_ID()";
			$result = mysql_query($query);
			$rows = mysql_fetch_row($result);
			mysql_close($database);
			echo "<script>alert('发布成功');window.location.href=\"./article.php?article_id=".$rows[0]."\";</script>";
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Article</title>
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
				  <li class="navli" role="presentation"><a href="./login.php?clear=1" style="color: #00e3e3;">Logout</a></li>
				</ul>
			</div>
		</div>
		<div class="row" style="margin-top: 1%">
			<div class="col-md-12"><h4>Welcome back,<?php echo $_SESSION['Name'] ?></h4></div>
		</div>
		<div class="row">
			<div class="col-md-offset-5"><h1><?php if(!isset($_GET['article_id'])) echo "发表新文章"; else echo "修改文章";?></h1></div>
			<div class="col-md-offset-4 col-md-4">
				<form method="post" action="./article.php">
				  <div class="form-group">
				    <label for="exampleInputPassword1">标题</label>
				    <input type="input" class="form-control" name="title" id="exampleInputPassword1" placeholder="Title" <?php if(isset($_GET['article_id'])) echo "value=\"".$title."\"";?>>
				  </div>
				  <div class="form-group">
				    <label for="exampleInputEmail1">内容</label>
				    <textarea class="form-control" name="content" placeholder="Content" rows="10"><?php if(isset($_GET['article_id'])) echo $content;?></textarea>
				  </div>
				  <div class="form-group">
				    <input type="hidden" name="article_id" <?php if(isset($_GET['article_id'])) echo "value=\"".$_GET['article_id']."\""?>>
				  </div>
				  <button type="sumbit" class="btn btn-primary">保存主题</button>
				  <button type="button" class="btn btn-success"><a  href="./index.php" style="color:white;text-decoration: none;">Back to Home</a></button>
				</form>
			</div>
		</div>
	</div>
</body>
</html>