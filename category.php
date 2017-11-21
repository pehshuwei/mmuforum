<!DOCTYPE HTML> 
<?php 
include("dataconnection.php");

$division_id = $_REQUEST['division_id'];
$error_category = "";

if($division_id)
{
	$sql_division = "select * from division where division_id = '$division_id'";
	$division = mysqli_query($conn,$sql_division);
	$row_div = mysqli_fetch_assoc($division);

	//check whether user has logged in
	if(isset($_SESSION['authenticated'])==false){
		header('Location: home.php');   
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
			//to check user status
			$user_id = $_SESSION['user_id'];
			$sql_checkstatus = "select user_status from user where user_id='$user_id'";
			$check_status = mysqli_query($conn,$sql_checkstatus);
			$row_user = mysqli_fetch_assoc($check_status);

			if(isset($_POST['categoryCreateBtn'])) 
			{
				$category = $_POST['create_category'];
				$category = strtoupper($category);

				if(strlen($category)<3)
				{
					$error_category = "Please insert at least 3 characters";
				}
				else
				{
					$sql_insertcategory = "insert into category(category, division_id) values('$category', '$division_id')";
					mysqli_query($conn,$sql_insertcategory);
					mysqli_close($conn);
					header('location: topicCreate.php?division_id='.$division_id);
				}
			}
		}
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
		Add category | MMU FORUM
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
			<li><a href="division.php?division_id=<?php echo $division_id;?>"><?php echo $row_div['division_name'];?></a></li>
			<li><a href="topicCreate.php?division_id=<?php echo $division_id;?>">Create Topic</a></li>
			<li class="active">Add category</li>
		</ul>
	</div>
</div>

<!-- Page Content -->
<div class="container">

	<!-- page header ==================== -->
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1>Adding category to <?php echo $row_div['division_name'];?></h1>
			</div>
		</div>
	</div>


	<!--topic -->
	<div class="row">
		<div class="col-md-12">
			<div class="well">
				<form class="form-horizontal" method="post" action="" onsubmit="return categoryCreateConfirmation()">
					<!-- topic title ==================== -->
					<div class="form-group <?php if($error_category){echo 'has-error';}?>">
						<div class="col-md-12">
							<input type="text" class="form-control" name="create_category" placeholder="Enter category" maxlength="25" value="<?php echo isset($category)?$category:"";?>" required/>
							<span class="help-block"><?php if($error_category){echo $error_category;}?></span>
						</div>
					</div>

					<div class="form-group">
						<!-- add category button ==================== -->
						<div class="col-md-4 pull-right">
							<input class="btn btn-primary btn-block" type="submit" name="categoryCreateBtn" value="Add category"/>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script data-align="right" data-overlay="false" id="keyreply-script" src="//keyreply.com/chat/widget.js" data-color="#E4392B" data-apps="JTdCJTIyZmFjZWJvb2slMjI6JTIyMTAwMDAwMzU0Njc5MjA0JTIyLCUyMmVtYWlsJTIyOiUyMnNodXdlaS5wZWhAZ21haWwuY29tJTIyJTdE"></script>
</body>
</html>

<script type="text/javascript">
	function categoryCreateConfirmation()
	{
		if(confirm("Be responsible with what you are adding. Action cannot be undone."))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
</script>