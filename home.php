<!DOCTYPE HTML> 
<?php 
include("dataconnection.php");

if (isset($_SESSION['authenticated']))
{
	$user_id= $_SESSION['user_id'];
	//check user status
	$sql_checkstatus = "select user_status from user where user_id='$user_id'";
	$check_status = mysqli_query($conn,$sql_checkstatus);
	$row=mysqli_fetch_assoc($check_status);
}
?>
<html> 
<head>
	<title>
		HOME | MMU FORUM
	</title>

	<style type="text/css">
	img.bg{
		/* Set rules to fill background */
		min-height: 100%;
		min-width: 1024px;
		
		/* Set up proportionate scaling */
		width: 100%;
		height: auto;
		
		/* Set up positioning */
		position: fixed;
		top: 0;
		left: 0;
	}

	@media screen and (max-width: 1024px) {
		/* Specific to this particular image */
		img.bg {
			left: 50%;
			margin-left: -512px;   /* 50% */
		}
	}
</style>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="style/FYP_bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="style/custom.css"/>
</head>
<body>	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="js/bootstrap_js.js"></script>

	<img src="img/mmu.jpg" class="bg">

	<!-- header ==================== -->
	<nav class="navbar navbar-default no-margin padding-5px no-border-radius">
		<div class="container-fluid">
			<!-- for smaller screan ==================== -->
			<div class="navbar-header">
				<!-- logo ==================== -->
				<a href="home.php">
					<img src="img/logo.png" height="40px" name="Home" alt="Home"/>
				</a>
				<span>Alpha</span>

				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>	

			<!-- for medium screen ==================== -->
			<div class=" nav navbar-nav navbar-right <?php if (isset($_SESSION['authenticated'])){if($row['user_status']=='ADMIN'){echo 'col-md-4';}else{echo 'col-md-2';}}else{echo 'col-md-1';}?> col-sm-5 navbar-collapse collapse" id="bs-example-navbar-collapse-2">
				<ul class="nav navbar-nav">
					<?php
					if (isset($_SESSION['authenticated']))
					{
						if($row['user_status'] == 'ADMIN')
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

	<div class="container" style="margin-top:30px;">
		<div class="row">

			<div class="col-md-6">
				<a href="division.php?division_id=FOM" class="homedivision">Faculty of Management</a>
			</div>
			<div class="col-md-6">
				<a href="division.php?division_id=FOE" class="homedivision">Faculty of Engineering</a>
			</div>
			<div class="col-md-6">
				<a href="division.php?division_id=FCM" class="homedivision">Faculty of Creative Multimedia</a>
			</div>
			<div class="col-md-6">
				<a href="division.php?division_id=FCI" class="homedivision">Faculty of Computing and Informatics</a>
			</div>
			<div class="col-md-6">
				<a href="division.php?division_id=FAC" class="homedivision">Faculty of Applied Communication</a>
			</div>
			<div class="col-md-6">
				<a href="division.php?division_id=CDP" class="homedivision">Centre for Diploma Programme</a>
			</div>
			<div class="col-md-6">
				<a href="division.php?division_id=ACC" class="homedivision">Accomodation</a>
			</div>
			<div class="col-md-6">
				<a href="division.php?division_id=FOOD" class="homedivision">Food</a>
			</div>
			<div class="<?php if (isset($_SESSION['verified'])){ echo 'col-md-6'; } else { echo 'col-md-offset-3 col-md-6';} ?>" >
				<a href="division.php?division_id=GEN" class="homedivision">General</a>
			</div>
			<?php
			if (isset($_SESSION['verified'])) {
				echo '<div class="col-md-6"><a href="division.php?division_id=SHOP" class="homedivision">Shop</a></div>';
			}
			?>
			
		</div>
	</div>


	<script data-align="right" data-overlay="false" id="keyreply-script" src="//keyreply.com/chat/widget.js" data-color="#E4392B" data-apps="JTdCJTIyZmFjZWJvb2slMjI6JTIyMTAwMDAwMzU0Njc5MjA0JTIyLCUyMmVtYWlsJTIyOiUyMnNodXdlaS5wZWhAZ21haWwuY29tJTIyJTdE"></script>
</body>
</htmL>