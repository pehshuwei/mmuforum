<!DOCTYPE html>
<?php 
include("dataconnection.php");

$topic_id = $_REQUEST['topic_id'];
$error_report = "";

if($topic_id)
{
	//get topic
	$sql_topic = "select * from topic where topic_id = '$topic_id'";
	$topic = mysqli_query($conn,$sql_topic);

	//CHECK whether topic exists
	if ($row_topic = mysqli_fetch_assoc($topic)) 
	{
		//CHECK whether user loged in
		if (isset($_SESSION['authenticated']))
		{
			$user_id= $_SESSION['user_id'];
			//CHECK user status
			$sql_checkstatus = "select user_status from user where user_id='$user_id'";
			$check_status = mysqli_query($conn,$sql_checkstatus);
			$row_user=mysqli_fetch_assoc($check_status);

			//report
			if(isset($_POST['reportBtn'])) 
			{
				$report_reason = $_POST['create_report'];

				if(strlen($report_reason)<20) 
				{
					$error_report = "Please insert at least 20 characters.";
				}
				else
				{
					$sql_insertreport = "insert into report(report_reason, topic_id, user_id) values('$report_reason', '$topic_id', '$user_id')";
					mysqli_query($conn,$sql_insertreport);
					mysqli_close($conn);
					header('location: topic.php?topic_id='.$topic_id);
				}
			}
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
	<title>Report Reason | MMU FORUM</title>

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

			<!-- search and navigate ====================-->
			<div class=" nav navbar-nav navbar-right <?php if (isset($_SESSION['authenticated'])){echo 'col-md-3';}else{echo 'col-md-2';}?> col-sm-7 col-xs-7" >
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
				<li><a href="division.php?division_id=<?php echo $row_topic['division_id'];?>"><?php echo $row_topic['division_id'];?></a></li>
				<li><a href="topic.php?topic_id=<?php echo $topic_id;?>"><?php echo $row_topic['topic_title'];?></a></li>
				<li class="active">Report Reason</li>
			</ul>
		</div>
	</div>

	<!-- Report Reason -->
	<div class="container">
		<!-- page header ==================== -->
		<div class="row">
			<div class="col-md-12">
				<div class="page-header">
					<h2>Let us know the reason of reporting this topic.</h2>
				</div>
			</div>
		</div>

		<!--report reason -->
		<div class="row">
			<div class="col-md-12">
				<div class="well">
					<form class="form-horizontal" method="post" action="">
						<div class="form-group <?php if($error_report){ echo 'has-error';}?>">
							<div class="col-md-12">
								<textarea placeholder="Report Reason..." class="form-control" name="create_report" maxlength="500" rows="10" required><?php echo nl2br(isset($report_reason)?$report_reason:"");?></textarea>
								<span class="help-block"><?php if($error_report){echo $error_report;}?></span>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-4 pull-right">
								<input class="btn btn-warning btn-block" type="submit" name="reportBtn" value="Report This Topic" />
								<p><span class="pull-right">This report will be submit to admin.</span></p>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
