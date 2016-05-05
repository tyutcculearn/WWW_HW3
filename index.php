<?php
	session_start();
	if(!isset($_SESSION['id']))
		echo "<script>location.href=\"./login.php\"</script>";

?>
<!DOCTYPE html>
<html>
<head>
	<title>Index</title>
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
		<?php 
			if(!isset($_GET['article_id']))
			{
				echo "<div class=\"row\" style=\"margin-top: 1%\">
						<div class=\"col-md-offset-11\">
							<button type=\"button\" class=\"btn btn-primary\">
								<a href=\"./article.php\" style=\"color:white;text-decoration: none;\">创建新主题
								</a>
							</button>
					  	</div>
					  </div>";
				$query = "select user.Name,article.*,count(respond.id) as num from article inner join user on article.author_id = user.id left join respond on respond.article_id = article.id where user.id = '".$_SESSION['id']."'group by article.id order by article.last_update desc limit 5";
				$database = mysql_connect("localhost","root","123456");
				mysql_select_db("hw3",$database);
				$result = mysql_query($query);
				echo "<div class=\"row\">
						 <div class=\" col-md-12\"><h3 style=\"color:#009393;\">您近期更新/被留言的文章</h3></div>
						 <div class=\"col-md-12\">
						 	<table class=\"table table-striped\">
						 		<tr>
						 			<td>发表日期</td>
						 			<td>Author</td>
						 			<td>Title</td>
						 			<td>回复</td>
						 			<td>最后更新时间/回复</td>
						 		</tr>";
						while($row = mysql_fetch_array($result))
						{
						echo "<tr>
								<td>".$row['created_time']."</td>
								<td>".$row['Name']."</td>
								<td><a href=\"./index.php?article_id=".$row['id']."\">".$row['title']."</a></td>
								<td>".$row['num']."</td>
								<td>".$row['last_update']."</td>
							  </tr>";
						}
				echo        "</table>
						 </div>
					  </div>";
			    mysql_close($database);

				$query = "select user.Name,article.*,count(respond.id) as num from article inner join user on article.author_id = user.id left join respond on respond.article_id = article.id group by article.id order by article.last_update desc";
				$database = mysql_connect("localhost","root","123456");
				mysql_select_db("hw3",$database);
				$result = mysql_query($query);
				echo "<div class=\"row\">
						 <div class=\" col-md-12\"><h3 style=\"color:#009393;\">所有文章列表</h3></div>
						 <div class=\"col-md-12\">
						 	<table class=\"table table-striped\">
						 		<tr>
						 			<td>发表日期</td>
						 			<td>Author</td>
						 			<td>Title</td>
						 			<td>回复</td>
						 			<td>最后更新时间/回复</td>
						 		</tr>";
						while($row = mysql_fetch_array($result))
						{
						echo "<tr>
								<td>".$row['created_time']."</td>
								<td>".$row['Name']."</td>
								<td><a href=\"./index.php?article_id=".$row['id']."\">".$row['title']."</a></td>
								<td>".$row['num']."</td>
								<td>".$row['last_update']."</td>
							  </tr>";
						}
				echo        "</table>
						 </div>
					  </div>";
				mysql_close($database);
			}
			else
			{
				$article_id = (int)$_GET['article_id'];
				if($_GET['article_id'] < 0)
					echo "<script>window.location.href=\"./index.php\"</script>";
				else
				{
					if(!isset($_GET['respond']) && !isset($_GET['delete']))
					{
						$query = "select article.*,user.Name from article,user where user.id = article.author_id and article.id = '".$_GET['article_id']."'";
						$database = mysql_connect("localhost","root","123456");
						mysql_select_db("hw3",$database);
						$result = mysql_query($query);
						mysql_close($database);
						$row = mysql_fetch_array($result);
						echo "<div class=\"row\">
								<div class=\"col-md-12\"><h1 style=\"color:#009393;\">".$row['title'].
								"<h1></div>
								<div class=\"col-md-4\"><p>".$row['Name']."Updated On ".$row['last_update'].
								"</p></div>";
						if($_SESSION['id'] == $row['author_id'])
						{	
							echo"<div class=\"col-md-offset-10\">
								  <button type=\"button\" class=\"btn btn-success\"><a href=\"./article.php?article_id=".$row['id']."\" style=\"color:white;text-decoration: none;\">Edit</a></button>
								  <button type=\"button\" class=\"btn btn-danger\"><a href=\"./index.php?article_id=".$row['id']."&delete=1\" style=\"color:white;text-decoration: none;\">Delete</a></button>
								</div>";
						}
						echo "</div>";
						echo "<div class=\"row\">
								<div class=\"col-md-12\"><p>".$row['content']."</p></div>
							  </div>";
						$query = "select respond.*,user.Name from respond left join user on respond.user_id = user.id where article_id = '".$_GET['article_id']."' order by respond.timestamp desc";
						$database = mysql_connect("localhost","root","123456");
						mysql_select_db("hw3",$database);
						$result = mysql_query($query);
						mysql_close($database);
						echo "<div class=\"row\">
								<div class=\"col-md-12\"><h4>Respond</h4></div>
								<div class=\"col-md-12\">
									<form class=\"form-inline\" method=\"get\" action=\"./index.php\">
									  <div class=\"form-group\">
									    <label for=\"exampleInputName2\">".$_SESSION['Name']."</label>
									    <input type=\"text\" class=\"form-control\" name=\"message\" id=\"exampleInputName2\" placeholder=\"Give a comment to this article.\">
									    <input type=\"hidden\" name=\"article_id\" value=\"".$row['id']."\">
									    <input type=\"hidden\" name=\"respond\" value=\"1\">
									  </div>
									  <button type=\"submit\" class=\"btn btn-primary\">Submit</button>
									</form>
								</div>";
						echo "</div>
							  <div class=\"row\" style=\"margin-top:1%;\"><div class=\"col-md-12\"><table class=\"table table-striped\">";
						while ($row = mysql_fetch_array($result)) {
						echo "<tr>
								<td>".$row['Name']."</td>
								<td>".$row['message']."</td>
								<td>".$row['timestamp']."</td></tr>";
						}
						echo "</table></div></div>";

					}
					else if(isset($_GET['respond']))
					{
						date_default_timezone_set("Asia/Shanghai");
						$date = date("Y-m-d H:i:s",time());
						$insert = "insert into respond (article_id,user_id,message,timestamp) values('".$_GET['article_id']."','".$_SESSION['id']."','".$_GET['message']."','".$date."')";
						$database = mysql_connect("localhost","root","123456");
						mysql_select_db("hw3",$database);
						mysql_query($insert);
						$update = "update article set last_update='".$date."' where id='".$_GET['article_id']."'";
						mysql_query($update);
						mysql_close($database);
						echo "<script>alert('留言成功');window.location.href=\"index.php?article_id=".$_GET['article_id']."\"</script>";
					}
					else if(isset($_GET['delete']))
					{
						$query = "select * from article where id = '".$_GET['article_id']."'";
						$database = mysql_connect("localhost","root","123456");
						mysql_select_db("hw3",$database);
						$result = mysql_query($query);
						$row = mysql_fetch_array($result);
						if($row['author_id'] != $_SESSION['id'])
						{
							mysql_close($database);
							echo "<script>window.location.href=\"./index.php\"</script>";
						}
						$dele = "delete from respond where article_id = '".$_GET['article_id']."'";
						mysql_query($dele);
						$dele = "delete from article where id = '".$_GET['article_id']."'";
						mysql_query($dele);
						mysql_close($database);
						echo "<script>alert('删除成功');window.location.href=\"./index.php\"</script>";
					}
				}
			}

		?>	
	</div>
</body>
</html>