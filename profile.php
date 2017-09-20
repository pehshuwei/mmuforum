<!DOCTYPE HTML> 
<?php 
include("dataconnection.php");

$user_id = $_REQUEST["user_id"];
$label = '';

//
if (isset($_SESSION['authenticated'])==false)
{
	$_SESSION['user_id'] = '0';
}

//check whether user_id exists
if($user_id)
{
	$sql_searchprofile = "select * from user where user_id = '$user_id'";
	$search_profile = mysqli_query($conn,$sql_searchprofile);
	$row=mysqli_fetch_assoc($search_profile);

	if($row['user_status'] == 'VISITOR')
	{	$label = 'label-default';}
	else if($row['user_status'] == 'PENDING')
	{	$label = 'label-warning';}
	else if($row['user_status'] == 'STUDENT')
	{	$label = 'label-success';}
	else if($row['user_status'] == 'BLOCKED')
	{	$label = 'label-primary';}
	else if($row['user_status'] == 'ADMIN')
	{	$label = 'label-info';}		
}
else
{
	header('location: home.php');
}



?>
<html>
<head>
	<title>
		PROFILE | MMU FORUM
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

			<!-- search and navigate ==================== -->
			<div class=" nav navbar-nav navbar-right col-md-1 col-sm-7 col-xs-7 no-padding" >
				<ul class="nav nav-pills">
					<li><a href="home.php" >HOME</a></li>
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
								<img class="img-circle profile-pic" alt="" src="img/cat.jpg">	
								<br/>				            
							</div>

							<!-- profile details =================== -->
							<div class="col-md-7">								
								<ul class="list-group">
									<li class="list-group-item no-border">
										<b>NAME &emsp;&emsp; |</b> &emsp; <?php echo $row['user_name']; ?>
									</li>
									<li class="list-group-item no-border">
										<b>EMAIL &emsp;&emsp; |</b> &emsp; <?php echo $row['user_email']; ?>
									</li>
									<li class="list-group-item no-border">
										<b>FACULTY &emsp;&emsp; |</b> &emsp; <?php echo $row['faculty']; ?>
									</li>
									<li class="list-group-item no-border">
										<b>ABOUT &emsp;&emsp; |</b> &emsp; <?php echo $row['user_about']; ?>
									</li>
									<li class="list-group-item no-border">
										<b>LINK &emsp;&emsp; |</b> &emsp; <a href="<?php echo $row['user_link']; ?>"><?php echo $row['user_link']; ?></a>
									</li>
									<li class="list-group-item no-border">
										<b>STATUS &emsp;&emsp; |</b> &emsp; <span class="label <?php echo $label; ?>"><?php echo $row['user_status'];?></span>
									</li>
									<li class="list-group-item no-border">
										<b>POSTS &emsp;&emsp; |</b> &emsp; 6
									</li>
								</ul>
							</div>

							<!-- edit button ===================== -->
							<?php
							if ($_SESSION['user_id'] == $user_id)
							{
								echo '<div class="col-md-1"><a href="profileEdit.php?user_id='.$row['user_id'].'" class="btn btn-default">EDIT</a></div>';
							}
							?>
							

						</div>

						<!--haven't done php-->

						<div class="row">
							<br/><br/>
							<div class="col-md-8 col-md-offset-4">
								<!-- user's posts =================== -->
								<div class="list-group">
									<a href="#" class="list-group-item">
										<span class="badge">16  COMMENTS</span>
										<span class="badge">57 VIEWS</span>
										<h4 class="list-group-item-heading"><span class="font-blue">RECRUITING FYP GROUPMATES</span></h4>
										<br/>
										<p class="list-group-item-text"><span class="font-red">cat</span>
											| date | time | <span class="label label-info">FYP</span></p>
										</a>
										<a href="#" class="list-group-item">
											<span class="badge">12  COMMENTS</span>
											<span class="badge">78 VIEWS</span>
											<h4 class="list-group-item-heading"><span class="font-blue">HOW MANY DIPLOMA COURSES DO MMU CYBERJAYA OFFER?</span></h4>
											<br/>
											<p class="list-group-item-text"><span class="font-red">cat</span>
												| date | time | <span class="label label-info">CDP</span></p>
											</a>
											<a href="#" class="list-group-item">
												<span class="badge">27 COMMENTS</span>
												<span class="badge">92 VIEWS</span>
												<h4 class="list-group-item-heading"><span class="font-blue">WHO SHOULD I FIND IF I HAVE PROBLEM WITH MY CLASS SCHEDULE</span></h4>
												<br/>
												<p class="list-group-item-text"><span class="font-red">cat</span>
													| date | time | <span class="label label-info">CDP</span></p>
												</a>
												<a href="#" class="list-group-item">
													<span class="badge">45  COMMENTS</span>
													<span class="badge">124 VIEWS</span>
													<h4 class="list-group-item-heading"><span class="font-blue">WHY I CAN'T ACCESS TO THE SHOP</span></h4>
													<br/>
													<p class="list-group-item-text"><span class="font-red">cat</span>
														| date | time | <span class="label label-info">FORUM</span></p>
													</a>
													<a href="#" class="list-group-item">
														<span class="badge">21 COMMENTS</span>
														<span class="badge">49 VIEWS</span>
														<h4 class="list-group-item-heading"><span class="font-blue">WHERE SHOLD I SEND MY PARCEL TO WHEN I BUY THINGS ONLINE</span></h4>
														<br/>
														<p class="list-group-item-text"><span class="font-red">cat</span>
															| date | time | <span class="label label-info">GENERAL</span></p>
														</a>
														<a href="#" class="list-group-item">
															<span class="badge">7  COMMENTS</span>
															<span class="badge">39 VIEWS</span>
															<h4 class="list-group-item-heading"><span class="font-blue">WHEN IS THE PRESENTATION DATE FOR FYP PART 1</span></h4>
															<br/>
															<p class="list-group-item-text"><span class="font-red">cat</span>
																| date | time | <span class="label label-info">FYP</span></p>
															</a>
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