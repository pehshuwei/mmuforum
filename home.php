<!DOCTYPE HTML> 
<?php 
	include("dataconnection.php");
?>
<html> 
<head>
	<title>
		HOME | MMU FORUM
	</title>

	<style type="text/css">
		body{
			position: absolute;
			background-image: url("img/test.jpg");
			background-repeat: no-repeat;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			background-size: cover;
			width: 100%;
			height: 100%;
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

	<!-- upheader ==================== -->
	<nav class="navbar navbar-default no-margin padding-5px">
		<div class="container-fluid">
			<!-- logo ==================== -->
			<div class="navbar-header col-md-8 col-sm-5 col-xs-5">
				<a class="navbar-brand"> 
					<a href="home.html">
						<img src="img/mmulogo.png" height="40px" name="Home" alt="Home"/>
					</a>
					<span class="font-size-20px">F<small>ORUM</small></span>
				</a>
			</div>				

			<!-- search and navigate ====================-->
			<div class=" nav navbar-nav navbar-right col-md-1 col-sm-7 col-xs-7" >
				<ul class="nav nav-pills">
					<li>
					<?php
						if (isset($_SESSION['authenticated']))
						{
							echo '<a href="profileEdit.php">PROFILE</a>'; 
						}
						else
						{
							echo '<a href="login.php">LOGIN</a>';
						}
					?>
					
					</li>
				</ul>
			</div>
		</div>
	</nav>

	<div class="container" style="margin-top:100px;">
		<div class="row">
			<div class="col-md-12">

				<div class="row">
					<div class="col-md-6">
						<a href="" class="homedivision">Faculty of Engineering</a>
					</div>
					<div class="col-md-6">
						<a href="" class="homedivision">Faculty of  Applied Communication</a>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<a href="" class="homedivision">Centre of Diploma Programme</a>
					</div>
					<div class="col-md-6">
						<a href="" class="homedivision">Faculty of Creative Multimedia</a>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<a href="" class="homedivision">Faculty of Management</a>
					</div>
					<div class="col-md-6">
						<a href="" class="homedivision">Faculty of Computing and Informatics</a>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<a href="" class="homedivision">Shop</a>
					</div>
					<div class="col-md-6">
						<a href="" class="homedivision">Accomodation</a>
					</div>
				</div>
			</div>
		</div>
	</div>


	<script data-align="right" data-overlay="false" id="keyreply-script" src="//keyreply.com/chat/widget.js" data-color="#E4392B" data-apps="JTdCJTIyZmFjZWJvb2slMjI6JTIyMTAwMDAwMzU0Njc5MjA0JTIyLCUyMmVtYWlsJTIyOiUyMnNodXdlaS5wZWhAZ21haWwuY29tJTIyJTdE"></script>
</body>
</htmL>