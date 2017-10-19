<!DOCTYPE HTML> 
<?php 
include("dataconnection.php");
$error_image = "";

if (isset($_SESSION['authenticated']))
{
	$user_id = $_SESSION['user_id'];
	$sql_user = "select * from user where user_id = '$user_id'";
	$user = mysqli_query($conn,$sql_user);
	$row_user=mysqli_fetch_assoc($user);

	if($row_user['user_status']=='VISITOR') 
	{
		//SUBMIT PHOTO
		if(isset($_POST['submitPhotoBtn']))
		{
			if(getimagesize($_FILES['image']['tmp_name'])==false)
			{
				$error_image = "Please select an image.";
			}
			else
			{
				$image = addslashes($_FILES['image']['tmp_name']);
				$name = addslashes($_FILES['image']['name']);
				$image = file_get_contents($image);
				$image = base64_encode($image);
				
				$sql_insertimage = "update user set user_idphotoname='$name', user_idphoto='$image' where user_id='$user_id'";
				$insertimage = mysqli_query($conn,$sql_insertimage);
				if($insertimage)
				{
					?>
					<script type="text/javascript">alert('Image uploaded');</script>
					<?php
					//update status
					$sql_updatestatus = "update user set user_status='PENDING' where user_id='$user_id'";
					mysqli_query($conn,$sql_updatestatus);
					mysqli_close($conn);
					header('location: profileEdit.php?user_id='.$user_id);
				}
				else
				{
					?>
					<script type="text/javascript">alert('Image not uploaded.');</script>
					<?php
				}
			}

			
		}
	}
	else
	{
		header('location: home.php');
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
		Change status | MMU FORUM
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
					<span class="home-nav">F<small>ORUM</small></span>
					</a>
					<span>Alpha</span>
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
								<li><a href="shopApproval.php">SHOP APPROVAL</a></li>
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
			<li><a href="profile.php?user_id=<?php echo $user_id;?>">Profile</a></li>
			<li><a href="profileEdit.php?user_id=<?php echo $user_id;?>">Edit Profile</a></li>
			<li class="active">Change status</li>
		</ul>
	</div>
</div>

<!-- body ==================== -->
<div class="container">
	<div class="col-md-8 col-md-offset-2">
		<div class="well well-lg">
			<!-- upload photo ==================== -->
			<form class="form-horizontal" method="post" enctype="multipart/form-data">
				<legend>Upload your ID's photo here...</legend>

				<div class="form-group">
					<div class="col-md-10 col-md-offset-1">
						<div id="id_image_preview"></div>
						<br/>
						<input type="file" name="image" accept=".jpg, .png" id="id_image"/>
						<?php
						if($error_image)
						{
							echo '<span class="text-primary">'.$error_image.'</span>';
						}
						?>
					</div>
				</div>

				<!-- submit button ==================== -->
				<div class="form-group">
					<div class="col-md-4 col-md-offset-4">
						<input type="submit" name="submitPhotoBtn" class="btn btn-primary btn-block" value="SUMBIT"/>
					</div>
				</div>
			</form>

		</div>
	</div>
</div>


<script data-align="right" data-overlay="false" id="keyreply-script" src="//keyreply.com/chat/widget.js" data-color="#E4392B" data-apps="JTdCJTIyZmFjZWJvb2slMjI6JTIyMTAwMDAwMzU0Njc5MjA0JTIyLCUyMmVtYWlsJTIyOiUyMnNodXdlaS5wZWhAZ21haWwuY29tJTIyJTdE"></script>


</body>
</html>

<!-- script for image preview ==================== -->
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
					$("<img />",
					{
						"src": e.target.result,
						"class": "thumb-image",
						"height": "350px"
					}).appendTo(image_holder);
				}

				image_holder.show();
				reader.readAsDataURL($(this)[0].files[i]);
			}

		});
	});
</script>