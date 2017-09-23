<!DOCTYPE HTML> 
<?php
include("dataconnection.php");

$topic_id = $_REQUEST['topic_id'];
$error_comment = "";

if($topic_id)
{
	//get topic
	$sql_topic = "select * from topic where topic_id = '$topic_id'";
	$topic = mysqli_query($conn,$sql_topic);
	$row_topic = mysqli_fetch_assoc($topic);

	//check whether topic exists
	if (!$row_topic) 
	{
		header("Location: home.php");
	}
	else
	{
		//get topic
		$division_id = $row_topic['division_id'];
		$sql_division = "select * from division where division_id = '$division_id'";
		$division = mysqli_query($conn,$sql_division);
		$row_div = mysqli_fetch_assoc($division);

		//check user status when in SHOP div
		if($division_id=="SHOP" && $_SESSION['verified']==false)
		{
			header("Location: home.php");
		}
		else
		{
			//get topic
			$owner_id = $row_topic['user_id'];
			$sql_owner = "select user_name from user where user_id = '$owner_id'";
			$owner =  mysqli_query($conn,$sql_owner);
			$row_owner = mysqli_fetch_assoc($owner);

			//topicDelete
			if (isset($_POST['topicDeleteBtn'])) 
			{
				$sql_deletetopic = "delete from topic where topic_id = '$topic_id'";
				mysqli_query($conn,$sql_deletetopic);
				mysqli_close($conn);
				header('location: division.php?division_id='.$division_id);
			}

			//commentCreate
			if (isset($_POST["commentCreateBtn"])) 
			{
				$new_comment = $_POST['new_comment'];
				$newcomment_userid =  $_SESSION['user_id'];

				if(strlen($new_comment)<10)
				{
					$error_comment = "Please insert at least 10 characters.";		
				}
				else
				{
					$sql_insertcomment = "insert into comment(comment_text, comment_timestamp, user_id, topic_id) 
						values('$new_comment', now(), '$newcomment_userid', '$topic_id')";
					mysqli_query($conn,$sql_insertcomment);
					mysqli_close($conn);
					header('location: topic.php?topic_id='.$topic_id);
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
		<?php echo $row_topic['topic_title'];?> | MMU FORUM
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
				<li class="active text"><?php echo $row_topic['topic_title'];?></li>
			</ul>
		</div>
	</div>

	<!-- body ==================== -->
	<div class="container">
		<!-- topic title & desc & details -->
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-body text">
						<h1><?php echo $row_topic['topic_title'];?></h1>
						<blockquote><p><?php echo nl2br($row_topic['topic_desc']);?></p></blockquote>
						<p class="text-primary"><?php if($division_id=="SHOP"){echo 'RM '.$row_topic['topic_itemprice'];}?></p>
						<?php 
						if($division_id=="SHOP")
							{	echo '';
						}
						?>
					</div>
					<div class="panel-body text">
						<?php
						if($owner_id == $_SESSION['user_id'])
						{
							echo '<form method="post" action="" onsubmit="return topicDeleteConfirmation()";>
							<div class="form-group">
								<input type="submit" class="btn btn-danger btn-sm pull-left" name="topicDeleteBtn" value="DELETE"/>
							</div>
						</form>';
						}
						?>
						<p class="pull-right">By <a href="profile.php?user_id=<?php echo $user_id?>"><?php echo $row_owner['user_name'];?></a>	| <?php echo $row_topic['topic_timestamp'];?> | <span class="label label-primary">XX Comments</span></p>
					</div>
				</div>				
			</div>
		</div>
		<!-- comments ============== -->
		<div class="row">
			<div class="col-md-12">
				<div class="page-header">
					<h1>XX COMMENTS</h1>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="well">
					<?php
						//get comment
						$sql_comment = "select * from comment where topic_id = '$topic_id'";
						$comment = mysqli_query($conn,$sql_comment);

						while($row_comment=mysqli_fetch_assoc($comment))
						{
							//get comment owner
							$comm_owner_id = $row_comment['user_id'];
							$sql_comm_owner = "select user_name from user where user_id = '$comm_owner_id'";
							$comm_owner =  mysqli_query($conn,$sql_comm_owner);
							$row_comm_owner = mysqli_fetch_assoc($comm_owner);

							echo 
							'<div class="panel panel-default">
								<div class="panel-body">
									<div class="col-md-1 icon-user">
										<img class="img-circle comment-dp" src="img/cat.jpg">
									</div>
									<div class="col-md-11">
										<h4><a href="profile.php?user_id='.$comm_owner_id.'">'.$row_comm_owner['user_name'].'</a></h4>
										<p class="lead text">'. nl2br($row_comment['comment_text']).'</p>
										<p><i>Posted on</i> '.$row_comment['comment_timestamp'].'</p>
									</div>
								</div>
							</div>';
						}
					?>
				</div>			
			</div>
		</div>
		<!-- create comment -->
		<div class="row">
			<div class="col-md-12">
				<div class="page-header">
					<h1>NEW COMMENT</h1>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="well">
					<form class="form-horizontal" name="commentCreateForm" method="post" action="">
						<div class="form-group <?php if($error_comment){echo 'has-error';}?>">
							<div class="col-md-12">
								<textarea placeholder="Write your comment here." class="form-control" name="new_comment" maxlength="500" rows="3" required><?php echo isset($new_comment)?$new_comment:"";?></textarea>
								<span class="help-block"><?php if($error_comment){echo $error_comment;}?></span>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-3 pull-right">
								<input type="submit" name="commentCreateBtn" value="COMMENT" class="btn btn-primary btn-block"/>
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

<script type="text/javascript">
	
	function topicDeleteConfirmation()
	{
		if(confirm("Do you want to delete this topic? Action cannot be undo."))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
</script>