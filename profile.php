<!DOCTYPE HTML> 
<?php 
include("dataconnection.php");

$profile_id = $_REQUEST["user_id"];
$label = '';
$nav_col = '';
$topic_itemprice = '';

if($profile_id)
{
	$sql_searchprofile = "select * from user where user_id = '$profile_id'";
	$search_profile = mysqli_query($conn,$sql_searchprofile);
	
	//check whether user_id existed
	if ($row_profile = mysqli_fetch_assoc($search_profile)) 
	{
		//set user_id for users those are not logged in
		if (isset($_SESSION['authenticated']))
		{
			//to check user status
			$user_id = $_SESSION['user_id'];
			$sql_checkstatus = "select user_status from user where user_id='$user_id'";
			$check_status = mysqli_query($conn,$sql_checkstatus);
			$row_user=mysqli_fetch_assoc($check_status);

			if($user_id!=$profile_id)
				{	$nav_col = 'col-md-3';}
			else
				{	$nav_col = 'col-md-2';}
		}
		else
		{
			$_SESSION['user_id'] = '0';
			$nav_col = 'col-md-1';
		}

		if($row_profile['user_status'] == 'VISITOR')
			{	$label = 'label-default';}
		else if($row_profile['user_status'] == 'PENDING')
			{	$label = 'label-warning';}
		else if($row_profile['user_status'] == 'MMU-ians')
			{	$label = 'label-success';}
		else if($row_profile['user_status'] == 'BLOCKED')
			{	$label = 'label-primary';}
		else if($row_profile['user_status'] == 'ADMIN')
			{	$label = 'label-info';}	

		//get topic
		$sql_topic = "select * from topic where user_id='$profile_id'";
		$topic = mysqli_query($conn,$sql_topic);
		$topic_num = mysqli_num_rows($topic);

		//block user
		if(isset($_POST['blockUserBtn'])) 
		{
			$sql_updatestatus = "update user set user_status='BLOCKED' where user_id='$profile_id'";
			mysqli_query($conn,$sql_updatestatus);
			mysqli_close($conn);
			header('location: profile.php?user_id='.$profile_id);
		}
	}
	else
	{
		header('location: home.php');
	}
}
else
{
	header('location: home.php');
}

?>
<html>
<head>
	<title>
		Profile | MMU FORUM
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
					<span class="home-nav">F<small>ORUM</small></span>
					</a>
					<span>Alpha</span>
				</a>
			</div>		

			<!-- navigate ==================== -->
			<div class="nav navbar-nav navbar-right <?php if($nav_col){echo $nav_col;}?>  col-sm-7 col-xs-7 no-padding" >
				<ul class="nav nav-pills">
					<li><a href="home.php">HOME</a></li>
					<?php
					if (isset($_SESSION['authenticated']))
					{
						if ($user_id!=$profile_id)
						{
							echo '<li><a href="profile.php?user_id='.$user_id.'">PROFILE</a></li>';
						}
						echo '<li><a href="logout.php">LOGOUT</a></li>';
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
					if (isset($_SESSION['verified'])) {
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
			<li class="active">PROFILE</li>
		</ul>
	</div>
</div>

<!--body ==================== -->
<div class="container">
	<!-- page header ==================== -->
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1>PROFILE</h1>
			</div>
		</div>
	</div>

	<!-- body =================== -->
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-body">

					<div class="row"><br/>
						<!-- profile pic =================== -->
						<div class="col-md-4">
							<img class="img-circle profile-pic" src="<?php if($row_profile['user_dp']){echo 'data:image;base64,'.$row_profile['user_dp'];}else{echo 'img/default.png';}?>"/>	            
						</div>

						<!-- profile details =================== -->
						<div class="col-md-7">
							<table class="table">
								<tr>
									<th>NAME</th>
									<td>|</td>
									<td><?php echo $row_profile['user_name']; ?></td>
								</tr>
								<tr>
									<th>EMAIL</th>
									<td>|</td>
									<td><?php echo $row_profile['user_email']; ?></td>
								</tr>
								<tr>
									<th>FACULTY</th>
									<td>|</td>
									<td><?php echo $row_profile['faculty']; ?></td>
								</tr>
								<tr>
									<th>ABOUT</th>
									<td>|</td>
									<td class="text"><?php echo nl2br($row_profile['user_about']); ?></td>
								</tr>
								<tr>
									<th>LINK</th>
									<td>|</td>
									<td><a href="<?php echo $row_profile['user_link']; ?>"><?php echo $row_profile['user_link']; ?></a></td>
								</tr>
								<tr>
									<th>STATUS</th>
									<td>|</td>
									<td><span class="label <?php echo $label; ?>"><?php echo $row_profile['user_status'];?></span></td>
								</tr>
								<tr>
									<th>POSTS</th>
									<td>|</td>
									<td><?php echo $topic_num;?></td>
								</tr>
							</table>
						</div>

						<!-- edit button ===================== -->
						<?php
						if ($user_id == $profile_id)
						{
							echo '<div class="col-md-1"><a href="profileEdit.php?user_id='.$profile_id.'" class="btn btn-default">EDIT</a></div>';
						}
						else
						{
							if($row_user['user_status'] == 'ADMIN')
							{
								if($row_profile['user_status'] != 'ADMIN')
								{
									echo '<div class="col-md-1">
										<form method="post" action="" onsubmit="return blockUserConfirmation()";>
											<div class="form-group">
												<input type="submit" class="btn btn-primary btn-block" name="blockUserBtn" value="BLOCK"/>
											</div>
										</form>
									</div>';
								}
							}
						}
						?>
					</div>
				</div>
				<!-- topics list ==================== -->
				<div class="panel-body">					
					<div class="row">						
						<div class="col-md-10 col-md-offset-1">
							<div class="list-group">
								<?php
								while ($row_topic=mysqli_fetch_assoc($topic)) 
								{
									//get item price
									if($row_topic['division_id']=='SHOP')
									{
										$topic_itemprice = 'RM '.$row_topic['topic_itemprice'];
									}
									else
									{
										$topic_itemprice = "";
									}

									//get comment 
									$topic_id = $row_topic['topic_id'];
									$sql_comment = "select * from comment where topic_id='$topic_id'";
									$comment = mysqli_query($conn,$sql_comment);
									$comment_num = mysqli_num_rows($comment);
									if($comment_num>1)
									{
										$comment_num = $comment_num.' Comments';
									}
									else
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
											<p><b>'.$row_profile['user_name'].'</b> | '.$row_topic['topic_timestamp'].' | <span class="label label-info">'.$topic_category.'</span> | <span class="label label-warning">'.$row_topic['division_id'].'</span><span class="badge pull-right">'.$comment_num.'</span></p>
										</a>		
										';
									}
									?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script data-align="right" data-overlay="false" id="keyreply-script" src="//keyreply.com/chat/widget.js" data-color="#E4392B" data-apps="JTdCJTIyZmFjZWJvb2slMjI6JTIyMTAwMDAwMzU0Njc5MjA0JTIyLCUyMmVtYWlsJTIyOiUyMnNodXdlaS5wZWhAZ21haWwuY29tJTIyJTdE"></script>
</body>
</html>

<script type="text/javascript">
	function blockUserConfirmation()
	{
		if(confirm("Are you sure you want to block this user? Action cannot be undone."))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
</script>