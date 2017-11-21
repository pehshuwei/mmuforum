<!DOCTYPE HTML> 
<?php 
include("dataconnection.php");

$division_id = $_REQUEST['division_id'];
$error_title = "";
$error_desc = "";
$itemprice = "";
$error_image = "";

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

			//get category
			$sql_category = "select * from category where division_id='$division_id'";
			$category = mysqli_query($conn,$sql_category);

			//topicCreate
			if(isset($_POST["topicCreateBtn"]))
			{
				$topic_title = htmlspecialchars($_POST['create_title'], ENT_QUOTES);
				$topic_desc = htmlspecialchars($_POST['create_desc'], ENT_QUOTES);
				$create_itemprice = $_POST['create_itemprice'];
				$category_id = $_POST['create_category'];

				//format price input
				if(isset($create_itemprice))
				{
					$itemprice = number_format($create_itemprice, 2, '.', '');
					$itemprice = $itemprice;
					$topic_itemprice = $itemprice;
				}
				else
				{
					$topic_itemprice = "";
					$itemprice = "";
				}

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
					if($division_id=='SHOP')
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
							$sql_inserttopic = "insert into topic(topic_title, topic_desc, topic_timestamp, topic_itemprice, topic_imgname, topic_img,  user_id, division_id, category_id) 
							values('$topic_title', '$topic_desc', now(), '$topic_itemprice', '$name', '$image', '$user_id', '$division_id', '$category_id')";

							//get topic id
							if (mysqli_query($conn,$sql_inserttopic)) {
								$topic_id = mysqli_insert_id($conn);
							}
							mysqli_close($conn);
							header('location: topic.php?topic_id='.$topic_id);
						}
					}
					else
					{
						$image = addslashes($_FILES['image']['tmp_name']);
						$name = addslashes($_FILES['image']['name']);
						$image = file_get_contents($image);
						$image = base64_encode($image);
						$sql_inserttopic = "insert into topic(topic_title, topic_desc, topic_timestamp, topic_itemprice, topic_imgname, topic_img, user_id, division_id, category_id) 
							values('$topic_title', '$topic_desc', now(), '$topic_itemprice', '$name', '$image', '$user_id', '$division_id', '$category_id')";

						//get topic id
						if (mysqli_query($conn,$sql_inserttopic)) {
							$topic_id = mysqli_insert_id($conn);
						}
						mysqli_close($conn);
						header('location: topic.php?topic_id='.$topic_id);
					}
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
		<?php if($division_id=="SHOP"){echo 'SELL AN ITEM';}else{echo 'CREATE TOPIC';}?> | MMU FORUM
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
			<li class="active"><?php if($division_id=="SHOP"){echo 'Sell An Item';}else{echo 'Create Topic';}?></li>
		</ul>
	</div>
</div>

<!-- Page Content -->
<div class="container">

	<!-- page header ==================== -->
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h1><?php if($division_id=="SHOP"){echo 'SELL AN ITEM';}else{echo 'CREATE TOPIC';}?></h1>
			</div>
		</div>
	</div>


	<!--topic -->
	<div class="row">
		<div class="col-md-12">
			<div class="well">
				<form class="form-horizontal" method="post" action=""  enctype="multipart/form-data">

					<!-- topic title ==================== -->
					<div class="form-group <?php if($error_title){echo 'has-error';}?>">
						<div class="col-md-12">
							<input type="text" class="form-control" name="create_title" placeholder="<?php if($division_id=="SHOP"){echo 'ITEM NAME';}else{echo 'TITLE';}?>" maxlength="100" value="<?php echo isset($topic_title)?$topic_title:"";?>" required/>
							<span class="help-block"><?php if($error_title){echo $error_title;}?></span>
						</div>
					</div>

					
					<div class="form-group <?php if($error_desc){echo 'has-error';}?>">
						<!-- image ==================== -->
						<div class="col-md-3">
								<label class="control-label">IMAGE</label>
								<div id="topic_image_preview"></div>
								<br/>
								<input type="file" name="image" accept=".jpg, .png" id="topic_image"/>
							</div>
						<?php
						if($division_id=='SHOP') 
						{
							echo '';
						}
						if($error_image)
							{
								echo '<span class="text-primary">'.$error_image.'</span>';
							}
						?>
						
						
						<!-- topic description -->
						<div class="col-md-9">
							<textarea placeholder="DESCRIPTION" class="form-control" name="create_desc" maxlength="1000" rows="10" required><?php echo isset($topic_desc)?$topic_desc:"";?></textarea>
							<span class="help-block"><?php if($error_desc){echo $error_desc;}?></span>
						</div>
					</div>


					<div class="form-group">
						<!-- category ==================== -->
						<div class="col-md-4">
							<label class="control-label">CATEGORY</label>
							<select class="form-control" name="create_category">
								<option>NONE</option>
								<?php
									while($row_cat=mysqli_fetch_assoc($category)) 
									{
										echo '<option value="'.$row_cat['category_id'].'">'.$row_cat['category'].'</option>';
									}
								?>
							</select>
							<!-- <p class="text-danger">Don't have what you need? <a href="category.php?division_id=<?php echo $division_id;?>">Add here!</a></p> -->
						</div>

						<!-- price ==================== -->
						<div class="col-md-4">
							<?php 
							if($division_id=="SHOP")
								{	echo '<label class="control-label">RM</label>
							<input type="number" value="'.$itemprice.'" placeholder="0.00" max="1000000.00" min="0.50" step="0.05" data-number-to-fixed="2" data-number-stepfactor="100" class="form-control" name="create_itemprice" required/>';
						}
						?>										
						</div>

						<!-- sell button ==================== -->
						<div class="col-md-4">
							<label class="control-label"><?php if($division_id=="SHOP"){echo 'Submit to get approval from admin.';}else{echo '_____________';}?></label>
							<input class="btn btn-primary btn-block" type="submit" name="topicCreateBtn" value="<?php if($division_id=="SHOP"){echo 'SUBMIT ITEM';}else{echo 'POST';}?>" />
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
		$("#topic_image").on('change', function() 
		{
			var countFiles = $(this)[0].files.length;
			var imgPath = $(this)[0].value;
			var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
			var image_holder = $("#topic_image_preview");
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
						"width": "250px"
					}).appendTo(image_holder);
				}

				image_holder.show();
				reader.readAsDataURL($(this)[0].files[i]);
			}

		});
	});

</script>	
