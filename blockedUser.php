<!DOCTYPE HTML> 
<?php
include("dataconnection.php");

$blocked_num = "";

//check whether user has logged in
if(isset($_SESSION['authenticated'])==false){
	header('Location: home.php');   
}
else
{
	//get user id
	$admin_id = $_SESSION['user_id'];

	//CHECK whether user is an admin
	if($admin_id!=1 && $admin_id!=2 && admin_id!=3) 
	{
		header('Location: home.php');
	}
	else
	{
		//get blocked user
		$sql_blocked = "select user_id, user_name, user_email from user where user_status='BLOCKED'";
		$blocked = mysqli_query($conn,$sql_blocked);
		$blocked_num = mysqli_num_rows($blocked);
		if(!$blocked_num)
		{
			$blocked_num = "0";
		}

		//Unblock user
		if(isset($_POST['unblockBtn']))
		{
			$blocked_id = $_POST['blocked_id'];
			$sql_updatestatus = "update user set user_status='VISITOR' where user_id='$blocked_id'";
			mysqli_query($conn,$sql_updatestatus);
			mysqli_close($conn);
			header('location: blockedUser.php');
		}
	}
}
?>
<html>
<head>
	<title>
		Blocked User | MMU FORUM
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
			<div class=" nav navbar-nav navbar-right col-md-3 col-sm-7 col-xs-7" >
				<ul class="nav nav-pills">
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
							<li><a href="idVerification_Admin.php">ID VERIFICATION</a></li>
							<li><a href="shopApproval.php">SHOP APPROVAL</a></li>
							<li><a href="report.php">REPORT</a></li>
							<li class="disabled"><a href="#">BLOCKED USER</a></li>
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
				<li class="active">Blocked User</li>
			</ul>
		</div>
	</div>

	<div class="container">
		<!-- page header ==================== -->
		<div class="row">
			<div class="col-md-12">
				<div class="page-header">
					<h1><?php if($blocked_num>1){echo $blocked_num.' blocked users';}else{echo $blocked_num.' blocked user';}?></h1>
				</div>
			</div>
		</div>

		<!-- blocked user list ==================== -->
		<div class="row">
			<div class="col-md-12">
				<div class="well">
					<?php
					while ($row_blocked=mysqli_fetch_assoc($blocked)) 
					{
						echo '
						<div class="panel panel-dafault">
							<div class="panel-body">
								<div class="col-md-8 lead">
									'.$row_blocked['user_email'].'
								</div>
								<div class="col-md-2">
									<a href="profile.php?user_id='.$row_blocked['user_id'].'" class="btn btn-info btn-block">View Profile</a>
								</div>
								<div class="col-md-2">
									<form method="post" action="" onsubmit="return unblockConfirmation()";>
										<div class="form-group">
											<input type="submit" class="btn btn-primary btn-block" name="unblockBtn" value="Unblock"/>
											<input type="hidden" name="blocked_id" value="'.$row_blocked['user_id'].'" />
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
	function unblockConfirmation()
	{
		if(confirm("Are you sure you want to unblock this user? Action cannot be undone."))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
</script>

