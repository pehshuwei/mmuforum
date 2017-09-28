<!DOCTYPE HTML> 
<?php 
include("dataconnection.php");
$error_image = "";

//check whether user has logged in
if(isset($_SESSION['authenticated'])==false)
{
	header('Location: home.php');   
}
else
{
	$user_id = $_REQUEST["user_id"];
	$label = "";
	$error_email = "";
	$error_oldpwd = "";
	$error_newpwd ="";
	$error_cnewpwd = "";

	//check whether user_id exists
	if ($_SESSION['user_id'] == $user_id)
	{
		$sql_searchprofile = "select * from user where user_id = '$user_id'";
		$search_profile = mysqli_query($conn,$sql_searchprofile);
		$row = mysqli_fetch_assoc($search_profile);

		if($row['user_status'] == 'VISITOR')
			{	$label = 'label-default';}
		else if($row['user_status'] == 'PENDING')
			{	$label = 'label-warning';}
		else if($row['user_status'] == 'MMU-ians')
			{	$label = 'label-success';}
		else if($row['user_status'] == 'BLOCKED')
			{	$label = 'label-primary';}
		else if($row['user_status'] == 'ADMIN')
			{	$label = 'label-info';}		

		//profileEdit
		if(isset($_POST['profileEditBtn']))
		{
			$user_name = htmlspecialchars($_POST['edit_name'], ENT_QUOTES);
			$user_email = $_POST['edit_email'];
			$edit_faculty = $_POST['edit_faculty'];
			$user_about = htmlspecialchars($_POST['edit_about'], ENT_QUOTES);
			$user_link = $_POST['edit_link'];

			switch($edit_faculty)
			{
				case '0': $faculty = "NONE"; break;
				case '1': $faculty = "FOM"; break;
				case '2': $faculty = "FOE"; break; 
				case '3': $faculty = "FCM"; break;
				case '4': $faculty = "FCI"; break;
				case '5': $faculty = "FAC"; break;
				case '6': $faculty = "CDP"; break;
				default : $faculty = "NONE"; break;
			}

			//check whether user is admin
			//user
			if($user_id!=1 && $user_id!=2 && $user_id!=3)
			{
				$sql_updateprofile = "update user set user_name='$user_name', faculty='$faculty', user_about='$user_about', user_link='$user_link' where user_id='$user_id'";
				mysqli_query($conn,$sql_updateprofile);
				mysqli_close($conn);
				header('location: profile.php?user_id='.$user_id);
			}//admin
			else
			{
				$sql_checkemail = "select * from user where user_email = '$user_email'";
				$check_email = mysqli_query($conn,$sql_checkemail);

				//check email
				if ($row=mysqli_fetch_assoc($check_email)) 
				{
					$error_email = "Email already exists.";
				}
				else
				{
					$sql_updateprofile = "update user set user_name='$user_name', user_email='$user_email', faculty='$faculty', user_about='$user_about', user_link='$user_link' where user_id='$user_id'";
					mysqli_query($conn,$sql_updateprofile);
					mysqli_close($conn);
					header('location: profile.php?user_id='.$user_id);
				}
			}
		}

		//passwordEdit
		if(isset($_POST['passwordEditBtn']))
		{
			$edit_oldpwd = htmlspecialchars($_POST['edit_oldpwd'], ENT_QUOTES);
			$edit_newpwd = htmlspecialchars($_POST['edit_newpwd'], ENT_QUOTES);
			$edit_cnewpwd = htmlspecialchars($_POST['edit_cnewpwd'], ENT_QUOTES);
			$pwdformat = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/';


			//check old password
			if($edit_oldpwd!=$row['user_pwd']) 
			{
				$error_oldpwd = "Invalid old password.";
				$edit_oldpwd = "";
				$edit_newpwd = "";
				$edit_cnewpwd = "";
			}
			else if(!preg_match($pwdformat,$edit_newpwd))
			{
				$error_oldpwd = "";
				$error_newpwd = "Must be 6 to 16 characters which contain at least one numeric digit, one uppercase and one lowercase letter";
				$edit_newpwd = "";
				$edit_cnewpwd = "";
			}
			else if($edit_cnewpwd!=$edit_newpwd)
			{
				$error_oldpwd = "";
				$error_newpwd = "";
				$error_cnewpwd = "Password not match.";
				$edit_cnewpwd = "";
			}
			else
			{
				$sql_updatepassword = "update user set user_pwd='$edit_newpwd' where user_id='$user_id'";
				mysqli_query($conn,$sql_updatepassword);
				mysqli_close($conn);
				session_destroy();
				session_start();
				$_SESSION['updatePwdSuccess'] = true;
				header('location: login.php');
			}
		}

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
				
				$sql_insertimage = "update user set user_dpname='$name', user_dp='$image' where user_id='$user_id'";
				$insertimage = mysqli_query($conn,$sql_insertimage);
				if($insertimage)
				{
					?>
					<script type="text/javascript">alert('Image uploaded');</script>
					<?php
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
		header("Location: home.php");
	}

}



?>
<html>
<head>
	<title>
		Edit Profile | MMU FORUM
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
					</a>
					<span class="font-size-20px">F<small>ORUM</small></span>
				</a>
			</div>				

			<!-- navigate ==================== -->
			<div class=" nav navbar-nav navbar-right col-md-2 col-sm-7 col-xs-7 no-padding" >
				<ul class="nav nav-pills">
					<li><a href="home.php" >HOME</a></li>
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
					<?php
					if (isset($_SESSION['verified'])) {
						echo '<li><a href="division.php?division_id=SHOP">SHOP</a></li>';
					}
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
				<li><a href="profile.php?user_id=<?php echo $user_id;?>">PROFILE</a></li>
				<li class="active">PROFILE EDIT</li>
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
						<!-- profile picture -->
						<form class="form-horizontal" method="post" enctype="multipart/form-data">
							<div class="col-md-3 pull-left">
								<label class="control-label">PROFILE PICTURE</label>
								<div class="form-group">
									<div id="profile_image_preview"><img class="img-circle profile-pic" src="<?php if($row['user_dp']){echo 'data:image;base64,'.$row['user_dp'];}else{echo 'img/default.png';}?>" height="200px"/></div>
									<br/>
									<input type="file" name="image" accept=".jpg, .png" id="profile_image"/>
									<?php
									if($error_image)
									{
										echo '<span class="text-primary">'.$error_image.'</span>';
									}
									?>
								</div>
								
								<div class="form-group">
									<div class="col-md-12">
										<input type="submit" name="submitPhotoBtn" class="btn btn-primary btn-block" value="Confirm"/>
									</div>
								</div>
							</div>	
						</form>

						<!-- profileEditForm =================== -->
						<form id="profileEditForm" class="form-horizontal" method="post" action="" onsubmit="return profileEditValidation()">
							<div class="col-md-8 pull-right">
								<div class="form-group" id="edit_name">
									<label class="control-label">NAME</label>
									<input type="text" class="form-control" name="edit_name" id="edit_name_input" maxlength="20" value="<?php echo htmlspecialchars($row['user_name'], ENT_QUOTES);?>" required/>
									<span id="edit_name_error" class="help-block"></span>
								</div>

								<div class="form-group <?php if($error_email){echo 'has-error';}?>" id="edit_email">
									<label class="control-label">EMAIL</label>
									<input type="email" class="form-control" name="edit_email" id="edit_email_input" <?php if($user_id!=1&&$user_id!=2&&$user_id!=3){echo 'disabled=""';}?> maxlength="50" value="<?php echo $row['user_email'];?>" required/>
									<span id="edit_email_error" class="help-block"><?php echo $error_email; ?></span>
								</div>

								<div class="form-group">
									<label class="control-label">FACULTY</label>
									<select class="form-control" name="edit_faculty">
										<option value="0" <?php if($row['faculty']=='NONE'){echo 'selected';}?>>NONE</option>
										<option value="1"<?php if($row['faculty']=='FOM'){echo 'selected';}?>>FOM</option>
										<option value="2"<?php if($row['faculty']=='FOE'){echo 'selected';}?>>FOE</option>
										<option value="3"<?php if($row['faculty']=='FCM'){echo 'selected';}?>>FCM</option>
										<option value="4"<?php if($row['faculty']=='FCI'){echo 'selected';}?>>FCI</option>
										<option value="5"<?php if($row['faculty']=='FAC'){echo 'selected';}?>>FAC</option>
										<option value="6"<?php if($row['faculty']=='CDP'){echo 'selected';}?>>CDP</option>
									</select>
								</div>

								<div class="form-group">
									<label class="control-label">ABOUT</label>
									<textarea class="form-control" rows="3" maxlength="100" name="edit_about"><?php echo htmlspecialchars($row['user_about'], ENT_QUOTES);?></textarea>
								</div>

								<div class="form-group">
									<label class="control-label">LINK</label>
									<input type="text" class="form-control" name="edit_link" maxlength="50" value="<?php echo $row['user_link'];?>" placeholder=""/>
									<span class="text-info">Drop a link to your social media profile to let others contact you! *Especially for shop owner :D</span>
								</div>

								<div class="form-group">
									STATUS | <span class="label <?php echo $label;?>"><?php echo $row['user_status'];?></span>  
									<?php 
									if($row['user_status']=='VISITOR')
									{
										echo '<a href="changeStatus.php" target="_blank" rel="noopener">Change status</a>
											<p class="text-info">____________________
											<br>Why have to change status?
											<br>With status <span class="label label-default">VISITOR</span>, you can only create topics and comments.
											<br>With status <span class="label label-success">MMU-ians</span>, you can access to SHOP selling activities.</p>';
									}
									?>
								</div>

								<div class="form-group">
									<input type="submit" name="profileEditBtn" value="SAVE CHANGES" class="btn btn-primary pull-right"/>
								</div>
								<hr>
							</div>
						</form>

						<!-- passwordEditForm =================== -->
						<form id="passwordEditForm" class="form-horizontal"  method="post" action="">
							<div class="col-md-8 pull-right">
								<div class="form-group <?php if($error_oldpwd){echo 'has-error';}?>">
									<label class="control-label">CHANGE PASSWORD</label>
									<input type="password" class="form-control" name="edit_oldpwd" placeholder="Enter your old password" value="<?php echo isset($edit_oldpwd)?$edit_oldpwd:""; ?>" required/>
									<span class="help-block"><?php echo $error_oldpwd; ?></span>
								</div>

								<div class="form-group <?php if($error_newpwd){echo 'has-error';}?>">
									<label class="control-label">NEW PASSWORD</label>
									<input type="password" class="form-control" name="edit_newpwd" placeholder="Enter your new password" value="<?php echo isset($edit_newpwd)?$edit_newpwd:""; ?>" required/>
									<span class="help-block"><?php echo $error_newpwd; ?></span>
								</div>

								<div class="form-group <?php if($error_cnewpwd){echo 'has-error';}?>">
									<label class="control-label">CONFIRM NEW PASSWORD</label>
									<input type="password" class="form-control" name="edit_cnewpwd" placeholder="Renter your new password" value="<?php echo isset($edit_cnewpwd)?$edit_cnewpwd:""; ?>" required/>
									<span class="help-block"><?php echo $error_cnewpwd; ?></span>
								</div>

								<div class="form-group">
									<input type="submit" name="passwordEditBtn" value="CHANGE PASSWORD" class="btn btn-primary pull-right"/>
								</div>
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
	//image upload
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
						"height": "200px"
					}).appendTo(image_holder);
				}

				image_holder.show();
				reader.readAsDataURL($(this)[0].files[i]);
			}

		});
	});

	//profileEditValidation
	function profileEditValidation()
	{
		var name = document.getElementById("edit_name_input").value;
		var emailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

		//check name
		if(name.length<6 || name.length>20)  
		{ 	
			document.getElementById("edit_name").className += " has-error";
			document.getElementById("edit_name_error").innerHTML = "Must be 6 to 20 characters";
			return false;
		}//check email
		else if(emailformat.test(document.getElementById("edit_email_input").value) == false)  
		{
			document.getElementById("edit_name").className = "form-group";
			document.getElementById("edit_name_error").innerHTML = " ";
			document.getElementById("edit_email").className += " has-error";
			document.getElementById("edit_email_error").innerHTML = "You have entered an invalid email address!";
			return false;
		}
		else
		{
			document.getElementById("edit_name").className = "form-group";
			document.getElementById("edit_name_error").innerHTML = " ";
			document.getElementById("edit_email").className = "form-group";
			document.getElementById("edit_email_error").innerHTML = " ";
			return true;
		}
	}
</script>