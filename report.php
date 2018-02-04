<!DOCTYPE HTML> 
<!-- admin ========== -->
<?php
include("dataconnection.php");

$report_num = "";

//check whether user has logged in
if(isset($_SESSION['authenticated'])==false){
	header('Location: home.php');   
}
else
{
	//get user id
	$admin_id = $_SESSION['user_id'];

	//CHECK whether user is an admin
	if($admin_id!=1 && $admin_id!=2 && $admin_id!=3) 
	{
		header('Location: home.php');
	}
	else
	{
		//get report
		$sql_report = "select report.report_id, report.report_reason, report.report_timestamp, report.topic_id, topic.topic_title, report.user_id, user.user_name from report inner join topic on report.topic_id=topic.topic_id inner join user on report.user_id=user.user_id";
		$report = mysqli_query($conn,$sql_report);
		$report_num = mysqli_num_rows($report);
		if(!$report_num)
		{
			$report_num = "0";
		}

		//delete topic
		if(isset($_POST['topicDeleteBtn']))
		{
			$report_topicid = $_POST['report_topicid'];
			$sql_deletetopic = "delete from topic where topic_id = '$report_topicid'";
			mysqli_query($conn,$sql_deletetopic);
			mysqli_close($conn);
			header('location: report.php');
		}

		//delete report
		if(isset($_POST['reportDeleteBtn']))
		{
			$report_id = $_POST['report_id'];
			$sql_deletereport = "delete from report where report_id = '$report_id'";
			mysqli_query($conn,$sql_deletereport);
			mysqli_close($conn);
			header('location: report.php');
		}
	}
}

?>

<html>
<head>
	<title>
		Report | MMU FORUM
	</title>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style/FYP_bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="style/custom.css"/>

</head>
<body>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
	<script src="bootstrap_js.js"></script>

	<!-- upheader ==================== -->
	<nav class="navbar navbar-default no-margin padding-5px no-border-radius">
		<div class="container-fluid">
			<!-- for smaller screan ==================== -->
			<div class="navbar-header">
				<!-- logo ==================== -->
				<a href="home.php">
					<img src="img/logo.png" height="40px" name="Home" alt="Home"/>
				</a>
				<span>Alpha</span>

				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				</button>
			</div>	

			<!-- for medium screen ==================== -->
			<div class="nav navbar-nav navbar-right <?php if (isset($_SESSION['authenticated'])){echo 'col-md-3';}else{echo 'col-md-2';}?> col-sm-4 navbar-collapse collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="home.php">HOME</a></li>
					<li><a href="profile.php?user_id=<?php echo $_SESSION['user_id'];?>">PROFILE</a></li>
					<li><a href="logout.php">LOGOUT</a></li>
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
					<li><a href="division.php?division_id=SHOP">SHOP</a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">ADMIN <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="idVerification.php">ID VERIFICATION</a></li>
							<li><a href="shopApproval.php">SHOP APPROVAL</a></li>
							<li class="disabled"><a href="#">REPORT</a></li>
							<li><a href="blockedUser.php">BLOCKED USER</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</nav>

	<!-- breadcrumb ==================== -->
	<div class="row no-margin">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="home.php">Home</a></li>
				<li class="active">Admin</li>
				<li class="active">Report</li>
			</ul>
		</div>
	</div>

	<div class="container">
		<!-- page header ==================== -->
		<div class="row">
			<div class="col-md-12">
				<div class="page-header">
					<h1><?php if($report_num>1){echo $report_num.' Reports';}else{echo $report_num.' Report';}?></h1>
				</div>
			</div>
		</div>

		<!-- report list -->
		<div class="row">
			<div class="col-md-12">
				<?php

				while ($row_report=mysqli_fetch_assoc($report)) 
				{
					echo '<div class="panel panel-default">
					<div class="panel-body text">
						<div class="col-md-9 col-sm-12 col-xs-12">
							<h2>'.nl2br($row_report['topic_title']).'</h2>
						</div>
					</div>
					<div class="panel-body text">
						<div class="col-md-9">
							<p class="text-info">Report reason</p>
							<blockquote><p>'.nl2br($row_report['report_reason']).'</p></blockquote>
							<p>Reported by <a href="profile.php?user_id='.$row_report['user_id'].'">'.$row_report['user_name'].'</a> | '.$row_report['report_timestamp'].'</p>
						</div>
						<div class="col-md-2 col-sm-12 col-xs-12 pull-right">
							<form><div class="form-group">
								<a href="topic.php?topic_id='.$row_report['topic_id'].'" class="btn btn-info btn-block">View Topic</a>
							</div></form>
							<form method="post" action="" onsubmit="return topicDeleteConfirmation()";>
								<div class="form-group">
									<input type="submit" class="btn btn-primary btn-block" name="topicDeleteBtn" value="Delete Topic"/>
									<input type="hidden" name="report_topicid" value="'.$row_report['topic_id'].'" />
								</div>
							</form>
							<form method="post" action="" onsubmit="return reportDeleteConfirmation()";>
								<div class="form-group">
									<input type="submit" class="btn btn-default btn-block" name="reportDeleteBtn" value="Delete Report"/>
									<input type="hidden" name="report_id" value="'.$row_report['report_id'].'" />
								</div>
							</form>
						</div>
					</div>
				</div>';
			}
			?>
		</div>				
	</div>
</div>
</div>

<script data-align="right" data-overlay="false" id="keyreply-script" src="//keyreply.com/chat/widget.js" data-color="#E4392B" data-apps="JTdCJTIyZmFjZWJvb2slMjI6JTIyMTAwMDAwMzU0Njc5MjA0JTIyLCUyMmVtYWlsJTIyOiUyMnNodXdlaS5wZWhAZ21haWwuY29tJTIyJTdE"></script>
</body>
</html>

<script type="text/javascript">
	function topicDeleteConfirmation()
	{
		if(confirm("Are you sure you want to delete this topic? Action cannot be undone."))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function reportDeleteConfirmation()
	{
		if(confirm("Are you sure you want to delete this report? Action cannot be undone."))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
</script>