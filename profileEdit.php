<!DOCTYPE HTML> 
<?php 
include("dataconnection.php");

$user_id = $_REQUEST["user_id"];
$label = '';

if ($_SESSION['user_id'] == $user_id)
{
	$sql_searchprofile = "select * from user where user_id = '$user_id'";
	$search_profile = mysqli_query($conn,$sql_searchprofile);
	$row=mysqli_fetch_assoc($search_profile);

	if($row['user_status'] == 'VISITOR')
	{
		$label = 'label-default';
	}
	else if($row['user_status'] == 'PENDING')
	{
		$label = 'label-warning';
	}
	else if($row['user_status'] == 'STUDENT')
	{
		$label = 'label-success';
	}
	else if($row['user_status'] == 'BLOCKED')
	{
		$label = 'label-primary';
	}
	else if($row['user_status'] == 'ADMIN')
	{
		$label = 'label-info';
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
		PROFILE EDIT | MMU FORUM
	</title>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style/FYP_bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="style/custom.css"/>

</head>

<body>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
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

			<!-- navigate ==================== -->
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
					<h1>PROFILE EDIT</h1>
				</div>
			</div>
		</div>

		<!-- profile edit =================== -->
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-body">

						<form class="form-horizontal">

							<div class="col-md-4">
								<label class="control-label">PROFILE PICTURE</label>
								<div id="profile_image_preview"></div>
								<br/>
								<input type="file"  name="image" accept=".jpg, .png" id="profile_image"/>
								<br/>
							</div>

							<div class="col-md-7">

								<div class="form-group">
									<label class="control-label">NAME</label>
									<input type="text" class="form-control" name="title" value="<?php echo $row['user_name'];?>"/>
								</div>

								<div class="form-group">
									<label class="control-label">EMAIL</label>
									<input type="email" class="form-control" name="title" disabled="" value="<?php echo $row['user_email'];?>"/>
								</div>

								<div class="form-group">
									<label class="control-label">FACULTY</label>
									<select class="form-control" name="category">
										<option>NONE</option>
										<option>FOM</option>
										<option>FOE</option>
										<option>FCM</option>
										<option>FCI</option>
										<option>FAC</option>
										<option>CDP</option>
									</select>
								</div>

								<div class="form-group">
									<label class="control-label">ABOUT</label>
									<textarea value="<?php echo $row['user_about'];?>" class="form-control" rows="3"></textarea>
								</div>

								<div class="form-group">
									<label class="control-label">LINK</label>
									<input type="text" class="form-control" name="title" value="<?php echo $row['user_link'];?>"/>
								</div>

								<div class="form-group">
									STATUS | <span class="label <?php echo $label; ?>"><?php echo $row['user_status'];?></span> <a href="ID VERIFICATION USER.HTML"> Change status</a>
								</div>
								<hr>
								<div class="form-group">
									<label class="control-label">CHANGE PASSWORD</label>
									<input type="password" class="form-control" name="title" placeholder="Enter your old password"/>
								</div>

								<div class="form-group">
									<label class="control-label">NEW PASSWORD</label>
									<input type="password" class="form-control" name="title" placeholder="Enter your new password"/>
								</div>

								<div class="form-group">
									<label class="control-label">CONFIRM NEW PASSWORD</label>
									<input type="password" class="form-control" name="title" placeholder="Renter your new password"/>
								</div>

								<div class="col-md-offset-10"><a href="" class="btn btn-primary no-border">SAVE CHANGES</a></div>



							</div>
						</form>
					</div>
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
		$("#profile_image").on('change', function() 
		{
			var countFiles = $(this)[0].files.length;
			var imgPath = $(this)[0].value;
			var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
			var image_holder = $("#profile_image_preview");
			image_holder.empty();

			for (var i = 0; i < countFiles; i++) 
			{
				var reader = new FileReader();

				reader.onload = function(e) 
				{
					$("<img />",
					{
						"src": e.target.result,
						"class": "img-circle profile-pic",
						"height": "250px"
					}).appendTo(image_holder);
				}

				image_holder.show();
				reader.readAsDataURL($(this)[0].files[i]);
			}

		});
	});
</script>