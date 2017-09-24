<!DOCTYPE HTML> 
<?php
include("dataconnection.php");

$division_id = $_REQUEST['division_id'];
$error_login = "";
$topic_itemprice = "";

if($division_id)
{
	$sql_division = "select * from division where division_id = '$division_id'";
	$division = mysqli_query($conn,$sql_division);
	$row_div = mysqli_fetch_assoc($division);

	//check whether topic exists
	if (!$row_div) 
	{
		header("Location: home.php");
	}
	else
	{
		//check user status when in SHOP div
		if($division_id=="SHOP" && $_SESSION['verified']==false)
		{
			header("Location: home.php");
		}
		else
		{
			//check whether user has logged in
			if(isset($_SESSION['authenticated']))
			{
				//to check user status
				$user_id= $_SESSION['user_id'];
				$sql_checkstatus = "select user_status from user where user_id='$user_id'";
				$check_status = mysqli_query($conn,$sql_checkstatus);
				$row_user=mysqli_fetch_assoc($check_status);
			}
			else
			{
				$error_login = true;
			}

			//get topic
			$sql_topic = "select topic.topic_id, topic.topic_title, topic.topic_timestamp, topic.topic_itemprice, topic.topic_status, user.user_name from topic inner join user on topic.user_id=user.user_id where topic.division_id='$division_id'";
			$topic = mysqli_query($conn,$sql_topic);

			//get category
			$sql_category = "select * from category where division_id='$division_id'";
			$category = mysqli_query($conn,$sql_category);
		}
	}	
}
else
{
	header("Location: home.php");
}

?>
<html>
<head>
	<title>
		<?php echo $row_div['division_name'];?> | MMU FORUM
	</title>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style/FYP_bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="style/custom.css"/>

</head>
<body>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>		
	<script src="js/bootstrap_js.js"></script>

	<!-- upheader ==================== -->
	<nav class="navbar navbar-default no-margin padding-5px">
		<div class="container-fluid">
			<!-- logo ==================== -->
			<div class="navbar-header col-md-8 col-sm-5 col-xs-5">
				<a class="navbar-brand"> 
					<a href="home.php">
						<img src="img/mmulogo.png" height="40px" name="Home" alt="Home"/>
					</a>
					<span class="font-size-20px">F<small>ORUM</small></span>
				</a>
			</div>				

			<!-- navigate ====================-->
			<div class="nav navbar-nav navbar-right <?php if (isset($_SESSION['authenticated'])){echo 'col-md-3';}else{echo 'col-md-2';}?> col-sm-4 col-xs-4" >
				<ul class="nav nav-pills">
					<li><a href="home.php">HOME</a></li>
					<?php
					if (isset($_SESSION['authenticated']))
					{
						echo '<li><a href="profile.php?user_id='.$_SESSION['user_id'].'">PROFILE</a></li>';
						echo '<li><a href="logout.php">LOGOUT</a></li>';
					}
					else
					{
						echo '<li><a href="login.php">LOGIN</a></li>';
					}
					?>
				</ul>
			</div>
		</div>
	</nav>

	<!-- downheader ==================== -->
	<nav class="navbar navbar-inverse no-border-radius">
		<div class="container-fluid">
			<!-- for smaller screan ==================== -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>

			<!-- for medium screen ==================== -->
			<div class="navbar-collapse collapse" id="bs-example-navbar-collapse-2">
				<ul class="nav navbar-nav">
					<li><a href="division.php?division_id=FOM">FOM</a></li>
					<li><a href="division.php?division_id=FOE">FOE</a></li>
					<li><a href="division.php?division_id=FCM">FCM</a></li>
					<li><a href="division.php?division_id=FCI">FCI</a></li>
					<li><a href="division.php?division_id=FAC">FAC</a></li>
					<li><a href="division.php?division_id=CDP">CDP</a></li>
					<li><a href="division.php?division_id=ACC">ACCOMMODATION</a></li>
					<li><a href="division.php?division_id=FOOD">FOOD</a></li>
					<li><a href="division.php?division_id=GEN">GENERAL</a></li>
					<?php
					if(isset($_SESSION['authenticated']))
					{
						if (isset($_SESSION['verified'])) 
						{
							echo '<li><a href="division.php?division_id=SHOP">SHOP</a></li>';
						}
						if($row_user['user_status'] == 'ADMIN')
						{							
							echo '<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">ADMIN <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="idVerification.php">ID VERIFICATION</a></li>
								<li><a href="shopApproval.php">SHOP APPROVAL</a></li>
								<li><a href="report.php">REPORT</a></li>
								<li><a href="blockedUser.php">BLOCKED USER</a></li>
							</ul>
						</li>';
					}
				}
				?>
			</ul>
		</div>
	</div>
</nav>

<!-- breadcrumb ==================== -->
<div class="row no-margin">
	<div class="col-md-12">
		<ul class="breadcrumb">
			<li><a href="home.php">HOME</a></li>
			<li class="active"><?php echo $row_div['division_name'];?></li>
		</ul>
	</div>
</div>


<!--division title ==================== -->
<div class="row">
	<div class="col-md-12 div-name">
		<?php echo '- '.$row_div['division_name'].' -';?>
	</div>
</div>

<!--body ==================== -->
<div class="container">

	<!-- left nav ==================== -->
	<div class="col-md-3 col-sm-12 col-xs-12">
		<!--create topic button ==================== -->
		<div class="list-group">
			<a <?php if(isset($_SESSION['authenticated'])){echo 'href="topicCreate.php?division_id='.$division_id.'"';}?>  class="btn btn-primary btn-block <?php if($error_login){echo 'disabled';}?>">CREATE TOPIC</a>
			<?php if($error_login){echo '<span class="text-primary pull-right"><b><a href="login.php">LOGIN</a></b> to create topic.</span>';}?>
		</div>
		<!--filter ==================== -->
		<div class="list-group">
			<a href="#" class="list-group-item">All Post</a></li>
			<a href="#" class="list-group-item">Most Comment</a></li>
		</div>
		<!--category ==================== -->
		<div class="list-group">
			<span class="list-group-item active">CATEGORY</span>
			<?php
				while($row_cat=mysqli_fetch_assoc($category)) 
				{
					echo '<a href="#" class="list-group-item">'.$row_cat['category'].'</a></li>';
				}
			?>
		</div>
	</div>

	<!-- topics list ==================== -->
	<div class="col-md-9 col-sm-12 col-xs-12">
		<div class="list-group">
		<?php
			while ($row_topic=mysqli_fetch_assoc($topic)) 
			{
				if($division_id=='SHOP' && $row_topic['topic_status']=='') 
				{} 
				else
				{
					//get item price
					if($division_id=='SHOP')
					{
						$topic_itemprice = 'RM '.$row_topic['topic_itemprice'];
					}

					//get comment 
					$topic_id = $row_topic['topic_id'];
					$sql_comment = "select * from comment where topic_id='$topic_id'";
					$comment = mysqli_query($conn,$sql_comment);
					$comment_num = mysqli_num_rows($comment);
					if($comment_num>1)
					{
						$comment_num = $comment_num.' Comments';
					}else
					{
						$comment_num = $comment_num.' Comment';
					}

					//get topic category
					$sql_topiccategory = "select category.category_id, category.category from topic inner join category on topic.category_id=category.category_id where topic_id='$topic_id'";
					$topiccategory = mysqli_query($conn,$sql_topiccategory);
					$topic_category = mysqli_fetch_assoc($topiccategory);
					if($topic_category['category_id']<1)
					{
						$topic_category = 'GENERAL';
					}
					else
					{
						$topic_category = $topic_category['category'];
					}

					echo '
					<a href="topic.php?topic_id='.$row_topic['topic_id'].'" class="list-group-item">
					<p class="lead text-info text">'.$row_topic['topic_title'].'</p>
					<p><span>'.$topic_itemprice.'</span>
					<p><b>'.$row_topic['user_name'].'</b> | '.$row_topic['topic_timestamp'].' | <span class="label label-info">'.$topic_category.'</span><span class="badge pull-right">'.$comment_num.'</span></p>
					</a>		
					';
				}
				
			}
		?>
		</div>
	</div>
</div>


	<script data-align="right" data-overlay="false" id="keyreply-script" src="//keyreply.com/chat/widget.js" data-color="#E4392B" data-apps="JTdCJTIyZmFjZWJvb2slMjI6JTIyMTAwMDAwMzU0Njc5MjA0JTIyLCUyMmVtYWlsJTIyOiUyMnNodXdlaS5wZWhAZ21haWwuY29tJTIyJTdE"></script>
</body>
</html>