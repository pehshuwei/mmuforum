<!DOCTYPE HTML> 
<?php
include("dataconnection.php");

$topic_status = "";
$item_num = "";

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
		//get item
		$sql_item = "select * from topic where division_id='SHOP' and topic_status is NULL";
		$item = mysqli_query($conn,$sql_item);
		$item_num = mysqli_num_rows($item);
		if(!$item_num)
		{
			$item_num = "0";
		}

		
		//Approve item
		if(isset($_POST['itemApproveBtn']))
		{
			$item_id = $_POST['item_id'];
			$topic_status = "Approved";
			$sql_updateitemstatus = "update topic set topic_status='$topic_status' where topic_id='$item_id'";
			mysqli_query($conn,$sql_updateitemstatus);
			mysqli_close($conn);
			header('location: shopApproval.php');
		}

		//Reject item
		if(isset($_POST['itemRejectBtn']))
		{
			$item_id = $_POST['item_id'];
			$sql_deleteitem = "delete from topic where topic_id = '$item_id'";
			mysqli_query($conn,$sql_deleteitem);
			mysqli_close($conn);
			header('location: shopApproval.php');
		}
	}
}

?>

<html>
<head>
	<title>
		Shop Approval | MMU FORUM
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
					<span class="home-nav">F<small>ORUM</small></span>
					</a>
					<span>Alpha</span>
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
							<li><a href="idVerification.php">ID VERIFICATION</a></li>
							<li class="disabled"><a href="#">SHOP APPROVAL</a></li>
							<li><a href="report.php">REPORT</a></li>
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
				<li class="active">Shop Approval</li>
			</ul>
		</div>
	</div>

	<div class="container">
		<!-- page header ==================== -->
		<div class="row">
			<div class="col-md-12">
				<div class="page-header">
					<h1><?php if($item_num>1){echo $item_num.' items';}else{echo $item_num.' item';}?> needed to be approved</h1>
				</div>
			</div>
		</div>

		<!-- item list -->
		<div class="row">
			<div class="col-md-12">
				<?php

				while ($row_item=mysqli_fetch_assoc($item)) 
				{
					//get owner
					$owner_id = $row_item['user_id'];
					$sql_owner = "select user_name from user where user_id='$owner_id'";
					$owner = mysqli_query($conn,$sql_owner);
					$row_owner = mysqli_fetch_assoc($owner);

					echo '<div class="panel panel-default">
					<div class="panel-body text">
						<div class="col-md-9 col-sm-12 col-xs-12">
							<h2>'.nl2br($row_item['topic_title']).'</h2>
						</div>
					</div>
					<div class="panel-body text">
						<div class="col-md-9">
							<img src="data:image;base64,'.$row_item['topic_img'].'" height="300px"/>
						</div>
					</div>
					<div class="panel-body text">
						<div class="col-md-9">
							<blockquote><p>'.nl2br($row_item['topic_desc']).'</p></blockquote>
							<p class="text-primary">RM '.$row_item['topic_itemprice'].'</p>
							<p>By <a href="profile.php?user_id=<?php echo $owner_id?>">'.$row_owner['user_name'].'</a> | '.$row_item['topic_timestamp'].'</p>
						</div>
						<div class="col-md-2 col-sm-12 col-xs-12 pull-right">
							<form method="post" action="" onsubmit="return itemApproveConfirmation()";>
								<div class="form-group">
									<input type="submit" class="btn btn-primary btn-block" name="itemApproveBtn" value="APPROVE"/>
									<input type="hidden" name="item_id" value="'.$row_item['topic_id'].'" />
								</div>
							</form>
							<form method="post" action="" onsubmit="return itemRejectConfirmation()";>
								<div class="form-group">
									<input type="submit" class="btn btn-primary btn-block" name="itemRejectBtn" value="REJECT"/>
									<input type="hidden" name="item_id" value="'.$row_item['topic_id'].'" />
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
	function itemApproveConfirmation()
	{
		if(confirm("Are you sure you want to approve this item? Action cannot be undone."))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function itemRejectConfirmation()
	{
		if(confirm("Are you sure you want to reject this item? Action cannot be undone."))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
</script>