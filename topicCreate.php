<!DOCTYPE HTML> 
<?php 
include("dataconnection.php");

//check whether user has logged in
if(isset($_SESSION['authenticated'])==false){
	header("Location: home.php");   
}
else
{
	if(isset($_POST['topicCreateBtn']))
	{
		$topic_title = $_POST['topic_title'];
		$topic_desc = $_POST['topic_desc'];
		$user_id = $_SESSION['user_id'];
		$division_id = $_SESSION['division_id'];
	}
}

?>
<html>
<head>
	<title>
		CREATE TOPIC | MMU FORUM
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

			<!-- search and navigate ====================-->
			<div class=" nav navbar-nav navbar-right col-md-2 col-sm-7 col-xs-7" >
				<ul class="nav nav-pills">
					<li><a href="profile.php?user_id=<?php echo $_SESSION['user_id'];?>">PROFILE</a></li>
					<li><a href="home.php" >HOME</a></li>
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
					<li><a href="#">ACCOMMODATION</a></li>
					<li><a href="#">FOM</a></li>
					<li><a href="#">FOE</a></li>
					<li><a href="#">FCM</a></li>
					<li><a href="#">FCI</a></li>
					<li><a href="#">FAC</a></li>
					<li><a href="division.html">CDP</a></li>
					<li><a href="shop.html">SHOP</a></li>
				</ul>
			</div>
		</div>
	</nav>

	<!-- breadcrumb ==================== -->
	<div class="row no-margin">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="home.html">HOME</a></li>
				<li><a href="#">FOM</a></li>
				<li class="active">CREATE TOPIC</li>
			</ul>
		</div>
	</div>

	<!-- Page Content -->
	<div class="container">

		<!-- page header ==================== -->
		<div class="row">
			<div class="col-md-12">
				<div class="page-header">
					<h1>CREATE TOPIC</h1>
				</div>
			</div>
		</div>


		<!--topic -->
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<form class="form-horizontal">

							<!-- upload photo ==================== -->
							<div class="col-md-3">
								<div class="form-group">
									<div id="id_image_preview"></div>
									<br/>
									<input type="file"  name="image" accept=".jpg, .png" id="id_image"/>
									<br/>
								</div>
							</div>

							<div class="col-md-9">
								<!-- item title ==================== -->
								<div class="form-group">
									<input type="text" class="form-control" name="create_title" placeholder="TITLE" value="<?php echo isset($topic_title)?$topic_title:"";?>" required/>
								</div>
								<!-- description -->
								<div class="form-group">
									<textarea placeholder="DESCRIPTION" class="form-control" name="create_desc" rows="10" required><?php echo isset($topic_desc)?$topic_desc:"";?></textarea>
								</div>

								<div class="form-group">
									<!-- category ==================== -->
									<!-- <div class="col-md-5 col-md-offset-3">
										<label class="control-label badge"">CATEGORY</label>
										<select  name="category">
											<option>gadget</option>
											<option>book</option>
											<option>accomodation</option>
											<option>food</option>
											<option>clothes</option>
											<option>car</option>
										</select>
									</div> -->
									<input type="submit" name="topicCreateBtn" value="POST" class="btn btn-primary pull-right"/>
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

<script>
	$(document).ready(function() 
	{
		$("#id_image").on('change', function() 
		{
			var countFiles = $(this)[0].files.length;
			var imgPath = $(this)[0].value;
			var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
			var image_holder = $("#id_image_preview");
			image_holder.empty();

			for (var i = 0; i < countFiles; i++) 
			{
				var reader = new FileReader();

				reader.onload = function(e) 
				{
					$("<img/>",
					{
						"src": e.target.result,
						"class": "thumb-image",
						"height": "200px"
					}).appendTo(image_holder);
				}

				image_holder.show();
				reader.readAsDataURL($(this)[0].files[i]);
			}

		});
	});



	$(function(){
		$('.normal').autosize();
		$('.animated').autosize({append: "\n"});
	});
</script>	
