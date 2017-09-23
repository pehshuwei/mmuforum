<!DOCTYPE HTML> 
<?php
include("dataconnection.php");

$division_id = $_REQUEST['division_id'];
$error_login = "";

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
										<li><a href="#">SHOP APPROVAL</a></li>
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

			<!-- left ==================== -->
			<div class="col-md-3 col-sm-3 col-xs-3">
				<!--create topic button ==================== -->
				<div class="list-group">
					<a <?php if(isset($_SESSION['authenticated'])){echo 'href="topicCreate.php?division_id='.$division_id.'"';}?>  class="btn btn-primary btn-block <?php if($error_login){echo 'disabled';}?>">CREATE TOPIC</a>
					<?php if($error_login){echo '<span class="text-primary pull-right"><b><a href="login.php">LOGIN</a></b> to create topic.</span>';}?>
				</div>

				<!--filter ==================== -->
				<div class="list-group">
					<a href="#" class="list-group-item">All Post</a></li>
					<a href="#" class="list-group-item">Most Recent</a></li>
					<a href="#" class="list-group-item">Most Viewer</a></li>
					<a href="#" class="list-group-item">Most Comment</a></li>
					<a href="#" class="list-group-item">Trending</a></li>
				</div>

				<!--category ==================== -->
				<div class="list-group">
					<span class="list-group-item active">CATEGORY</span>
					<a href="#" class="list-group-item">FYP</a></li>
					<a href="#" class="list-group-item">Database</a></li>
					<a href="#" class="list-group-item">JAVA</a></li>
					<a href="#" class="list-group-item">Program Design</a></li>
					<a href="#" class="list-group-item">C++</a></li>
				</div>

			</div>

			<!-- topics-static ==================== -->
			<div class="col-md-9 col-sm-9 col-xs-9">

				<div class="list-group">
					<a href="#" class="list-group-item">
						<span class="badge">16  COMMENTS</span>
						<span class="badge">57 VIEWS</span>
						<h4 class="list-group-item-heading"><span class="font-blue">RECRUITING FYP GROUPMATES</span></h4>
						<br/>
						<p class="list-group-item-text"><span class="font-red">potato_97</span>
						| date | time | <span class="label label-info">FYP</span></p>
					</a>		
					<a href="#" class="list-group-item">
						<span class="badge">12  COMMENTS</span>
						<span class="badge">78 VIEWS</span>
						<h4 class="list-group-item-heading"><span class="font-blue">HOW MANY DIPLOMA COURSES DO MMU CYBERJAYA OFFER?</span></h4>
						<br/>
						<p class="list-group-item-text"><span class="font-red">sotong_98</span>
						| date | time | <span class="label label-info">CDP</span></p>
					</a>
					<a href="divisionTopic.html" class="list-group-item">
						<span class="badge">2  COMMENTS</span>
						<span class="badge">4 VIEWS</span>
						<h4 class="list-group-item-heading"><span class="font-blue">LA LA LAND</span></h4>
						<br/>
						<p class="list-group-item-text"><span class="font-red">Emran</span>
						| date | time | <span class="label label-info">CDP</span></p>
					</a>
					<a href="#" class="list-group-item">
						<span class="badge">27 COMMENTS</span>
						<span class="badge">92 VIEWS</span>
						<h4 class="list-group-item-heading"><span class="font-blue">WHO SHOULD I FIND IF I HAVE PROBLEM WITH MY CLASS SCHEDULE</span></h4>
						<br/>
						<p class="list-group-item-text"><span class="font-red">jagung123</span>
						| date | time | <span class="label label-info">CDP</span></p>
					</a>
					<a href="#" class="list-group-item">
						<span class="badge">45  COMMENTS</span>
						<span class="badge">124 VIEWS</span>
						<h4 class="list-group-item-heading"><span class="font-blue">WHY I CAN'T ACCESS TO THE SHOP</span></h4>
						<br/>
						<p class="list-group-item-text"><span class="font-red">ikanbakar0102</span>
						| date | time | <span class="label label-info">FORUM</span></p>
					</a>
					<a href="#" class="list-group-item">
						<span class="badge">21 COMMENTS</span>
						<span class="badge">49 VIEWS</span>
						<h4 class="list-group-item-heading"><span class="font-blue">WHERE SHOLD I SEND MY PARCEL TO WHEN I BUY THINGS ONLINE</span></h4>
						<br/>
						<p class="list-group-item-text"><span class="font-red">filetofish</span>
						| date | time | <span class="label label-info">GENERAL</span></p>
					</a>
					<a href="#" class="list-group-item">
						<span class="badge">7  COMMENTS</span>
						<span class="badge">39 VIEWS</span>
						<h4 class="list-group-item-heading"><span class="font-blue">WHEN IS THE PRESENTATION DATE FOR FYP PART 1</span></h4>
						<br/>
						<p class="list-group-item-text"><span class="font-red">yoshinoya</span>
						| date | time | <span class="label label-info">FYP</span></p>
					</a>
					<a href="#" class="list-group-item">
						<span class="badge">58 COMMENTS</span>
						<span class="badge">104 VIEWS</span>
						<h4 class="list-group-item-heading"><span class="font-blue">WHAT CAN I DO TO UNBAR IF I AM IN THE BARRING LIST</span></h4>
						<br/>
						<p class="list-group-item-text"><span class="font-red">sushizanmai_99</span>
						| date | time | <span class="label label-info">GENERAL</span></p>
					</a>
					<a href="#" class="list-group-item">
						<span class="badge">45  COMMENTS</span>
						<span class="badge">97 VIEWS</span>
						<h4 class="list-group-item-heading"><span class="font-blue">PLEASE HELP ME ON THIS QUESTION - C++</span></h4>
						<br/>
						<p class="list-group-item-text"><span class="font-red">google.com</span>
						| date | time | <span class="label label-info">OOP</span></p>
					</a>
					<a href="#" class="list-group-item">
						<span class="badge">24 COMMENTS</span>
						<span class="badge">47 VIEWS</span>
						<h4 class="list-group-item-heading"><span class="font-blue">STACK OVERFLOW IS LOVE</span></h4>
						<br/>
						<p class="list-group-item-text"><span class="font-red">f2f</span>
						| date | time | <span class="label label-info">PROGRAMMING</span></p>
					</a>
					<a href="#" class="list-group-item">
						<span class="badge">7 COMMENTS</span>
						<span class="badge">28 VIEWS</span>
						<h4 class="list-group-item-heading"><span class="font-blue">WHY IS MY INTERSHIP HAPPEN IN LONG SEMESTER?</span></h4>
						<br/>
						<p class="list-group-item-text"><span class="font-red">prosperityburger</span>
						| date | time | <span class="label label-info">CDP</span></p>
					</a>

					<div class="col-md-4 col-md-offset-4">
						<ul class="pagination pagination-sm">
							<li class="disabled"><a href="#">&laquo;</a></li>
							<li class="active"><a href="#">1</a></li>
							<li><a href="#">2</a></li>
							<li><a href="#">3</a></li>
							<li><a href="#">4</a></li>
							<li><a href="#">5</a></li>
							<li><a href="#">&raquo;</a></li>
						</ul>
					</div>			
					
				</div>
			</div>

		</div>

	<script data-align="right" data-overlay="false" id="keyreply-script" src="//keyreply.com/chat/widget.js" data-color="#E4392B" data-apps="JTdCJTIyZmFjZWJvb2slMjI6JTIyMTAwMDAwMzU0Njc5MjA0JTIyLCUyMmVtYWlsJTIyOiUyMnNodXdlaS5wZWhAZ21haWwuY29tJTIyJTdE"></script>
	</body>


</html>