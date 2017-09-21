<!DOCTYPE HTML> 
<?php 
include("dataconnection.php");

$division_id = $_REQUEST['division_id'];
$error_title = "";
$error_desc = "";

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
		//topicCreate
		if(isset($_POST["topicCreateBtn"]))
		{
			$topic_title = $_POST['create_title'];
			$topic_desc = $_POST['create_desc'];
			$user_id = $_SESSION['user_id'];

			//topicCreate validation
			if(strlen($topic_title)<10)
			{
				$error_title = "Please insert at least 10 characters.";
				
			}
			else if(strlen($topic_desc)<10)
			{
				$error_title = "";
				$error_desc = "Please insert at least 10 characters.";
				
			}
			else
			{
				$sql_inserttopic = "insert into topic(topic_title, topic_desc, topic_timestamp, user_id, division_id) 
				values('$topic_title', '$topic_desc', now(), '$user_id', '$division_id')";
				if (mysqli_query($conn,$sql_inserttopic)) {
					$topic_id = mysqli_insert_id($conn);
				}
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
				<li class="active">Create Topic</li>
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
						<form class="form-horizontal" method="post" action="">

							<div class="col-md-12">
								<!-- topic title ==================== -->
								<div class="form-group <?php if($error_title){echo 'has-error';}?>">
									<input type="text" class="form-control" name="create_title" placeholder="TITLE" maxlength="100" value="<?php echo isset($topic_title)?$topic_title:"";?>" required/>
									<span class="help-block"><?php if($error_title){echo $error_title;}?></span>
								</div>
								<!-- topic description -->
								<div class="form-group <?php if($error_desc){echo 'has-error';}?>">
									<textarea placeholder="DESCRIPTION" class="form-control" name="create_desc" maxlength="1000" rows="10" required><?php echo isset($topic_desc)?$topic_desc:"";?></textarea>
									<span class="help-block"><?php if($error_desc){echo $error_desc;}?></span>
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

</script>	
