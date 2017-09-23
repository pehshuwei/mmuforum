<!DOCTYPE html> 
<?php 
include("dataconnection.php");

$error = "";
$login_error = "";

//check whether user has logged in
if(isset($_SESSION['authenticated'])){
	header("Location: home.php");   
}
else
{
    //SIGN UP
	if(isset($_POST["signUpBtn"]))
	{
		$user_name = $_POST["signup_name"];
		$user_email = $_POST["signup_email"];
		$user_pwd = $_POST["signup_pwd"];
		$signup_faculty = $_POST["signup_faculty"];

		switch($signup_faculty)
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

		//check email
		$sql_checkemail = "select * from user where user_email = '$user_email'";
		$check_email = mysqli_query($conn,$sql_checkemail);


		if ($row=mysqli_fetch_assoc($check_email)) 
		{
			$error = "Email already exists.";
			$user_email = "";
		}
		else
		{
			$user_status = 'VISITOR';
			$sql_insertsignup = "insert into user(user_name, user_email, user_pwd, faculty, user_status)
			values('$user_name', '$user_email', '$user_pwd', '$faculty', '$user_status')";
			mysqli_query($conn,$sql_insertsignup);
			mysqli_close($conn);
			$_SESSION['signUpSuccess'] = true;
			header("Location: login.php");	
		}
	}

	//LOGIN
	if(isset($_POST["loginBtn"]))
	{
		$user_email = $_POST["login_email"];
		$user_pwd = $_POST["login_pwd"];

		$sql_loginCheck = "select * from user where user_email = '$user_email' and user_pwd = '$user_pwd'";
		$check_user = mysqli_query($conn,$sql_loginCheck);

		if($row=mysqli_fetch_assoc($check_user))
		{
			$_SESSION['user_id'] = $row['user_id'];
			$_SESSION['authenticated'] = true;
			if($row['user_status'] == 'ADMIN' || $row['user_status'] == 'STUDENT')
			{
				$_SESSION['verified'] = true;
			}
			header("Location: home.php");	
		}
		else
		{
			$login_error = "Invalid username or password.";
		}
	}
}
?>

<html>
<head>
	<title>
		LOGIN | MMU FORUM
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

			<!-- and navigate ====================-->
			<div class=" nav navbar-nav navbar-right col-md-1 col-sm-7 col-xs-7" >
				<ul class="nav nav-pills">
					<li><a href="home.php">HOME</a></li>
				</ul>
			</div>
		</div>
	</nav>
	<div class="container" style="margin-top:50px;">

		<div class="row">
			<P class="signUpSuccess"><?php if(isset($_SESSION['signUpSuccess'])){ echo 'Your sign up is successful! Login now!'; } ?><?php if(isset($_SESSION['updatePwdSuccess'])){ echo 'Your password has changed! Login again!'; } ?></P>
		</div>

		<!-- LOGIN -->
		<div class="col-md-6 col-sm-6 no-padng">   
			<div class="model-l">
				<div class="l">Login</div><br>                  
				<form method="post" action="" id="loginForm" class="log-frm" name="loginForm"> 

					<div class="form-group <?php if($login_error){echo 'has-error';}?>" id="login_email">
						<label class="control-label">EMAIL</label>
						<input type="email" class="form-control" name="login_email" id="login_email_input" placeholder="Email" required/>
					</div>

					<div class="form-group <?php if($login_error){echo 'has-error';}?>">
						<label class="control-label" id="login_pwd">PASSWORD</label>
						<input type="password" class="form-control" name="login_pwd" id="login_name_input" placeholder="Password" required/>
					</div>

					<div class="form-group <?php if($login_error){echo 'has-error';}?>">
						<span class="help-block"><?php echo $login_error; ?></span>
					</div>

					<div class="form-group">
						<br><input type="submit" name="loginBtn" value="LOGIN" class="btn btn-primary active pull-right"/>
					</div>
				</form>
			</div>
		</div>    

		<!-- SIGN UP -->
		<div class="col-md-6 col-sm-6 no-padng">
			<div class="model-r">
				<div class="r">Sign Up</div><br>

				<form method="post" action="" id="signUpForm" class="log-frm" name="signUpForm" onsubmit="return signUpFormValidation()">  

					<div class="form-group" id="signup_name">
						<label class="control-label">NAME</label>
						<input type="text" class="form-control" name="signup_name" id="signup_name_input" placeholder="Name" value="<?php echo isset($user_name)?$user_name:""; ?>" required/>
						<span id="signup_name_error" class="help-block"></span>
					</div>
					<div class="form-group <?php if($error){echo 'has-error';}?>" id="signup_email">
						<label class="control-label">EMAIL</label>
						<input type="text" class="form-control" name="signup_email" id="signup_email_input" placeholder="Email" value="<?php echo isset($user_email)?$user_email:""; ?>"  required/>
						<span id="signup_email_error" class="help-block"><?php echo $error; ?></span>
					</div>
					<div class="form-group">
						<label class="control-label">FACULTY</label>
						<select class="form-control" name="signup_faculty">
							<option value="0">NONE</option>
							<option value="1">FOM</option>
							<option value="2">FOE</option>
							<option value="3">FCM</option>
							<option value="4">FCI</option>
							<option value="5">FAC</option>
							<option value="6">CDP</option>
						</select>
					</div>
					<div class="form-group" id="signup_pwd">
						<label class="control-label">PASSWORD</label>
						<input type="password" class="form-control" name="signup_pwd" id="signup_pwd_input" placeholder="Password" required/>
						<span id="signup_pwd_error" class="help-block"></span>
					</div>
					<div class="form-group" id="signup_cpwd">
						<label class="control-label">CONFIRM PASSWORD</label>
						<input type="password" class="form-control" name="signup_cpwd" id="signup_cpwd_input" placeholder="Confirm your password" required/>
						<span id="signup_cpwd_error" class="help-block"></span>
					</div>

					<div class="form-group">
						<input type="submit" name="signUpBtn" value="SIGN UP" class="btn btn-info active pull-right"/>
					</div>

				</form>
			</div>
		</div>
	</div>

	<script data-align="right" data-overlay="false" id="keyreply-script" src="//keyreply.com/chat/widget.js" data-color="#E4392B" data-apps="JTdCJTIyZmFjZWJvb2slMjI6JTIyMTAwMDAwMzU0Njc5MjA0JTIyLCUyMmVtYWlsJTIyOiUyMnNodXdlaS5wZWhAZ21haWwuY29tJTIyJTdE"></script>

</body>
</html>

<script type="text/javascript">

	function signUpFormValidation()
	{
		var name = document.getElementById("signup_name_input").value;
		var emailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;  
		var passwordformat = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/;

		//check name
		if(name.length<6 || name.length>20)  
		{ 	
			document.getElementById("signup_name").className += " has-error";
			document.getElementById("signup_name_error").innerHTML = "Must be 6 to 20 characters";
			return false;
		}//check email
		else if(emailformat.test(document.getElementById("signup_email_input").value) == false)  
		{
			document.getElementById("signup_name").className = "form-group";
			document.getElementById("signup_name_error").innerHTML = " ";
			document.getElementById("signup_email").className += " has-error";
			document.getElementById("signup_email_error").innerHTML = "You have entered an invalid email address!";
			return false;
		}//check password
		else if(passwordformat.test(document.getElementById("signup_pwd_input").value) == false)
		{
			document.getElementById("signup_name").className = "form-group";
			document.getElementById("signup_name_error").innerHTML = " ";
			document.getElementById("signup_email").className = "form-group";
			document.getElementById("signup_email_error").innerHTML = " ";
			document.getElementById("signup_pwd").className += " has-error";
			document.getElementById("signup_pwd_error").innerHTML = "Must be 6 to 16 characters which contain at least one numeric digit, one uppercase and one lowercase letter";
			return false;
		}//check confirm password
		else if(document.getElementById("signup_pwd_input").value != document.getElementById("signup_cpwd_input").value)
		{
			document.getElementById("signup_name").className = "form-group";
			document.getElementById("signup_name_error").innerHTML = " ";
			document.getElementById("signup_email").className = "form-group";
			document.getElementById("signup_email_error").innerHTML = " ";
			document.getElementById("signup_pwd").className = "form-group";
			document.getElementById("signup_pwd_error").innerHTML = " ";
			document.getElementById("signup_cpwd").className += " has-error";
			document.getElementById("signup_cpwd_error").innerHTML = "Please make sure your passwords match.";
			return false;
		}	
		else
		{
			document.getElementById("signup_name").className = "form-group";
			document.getElementById("signup_name_error").innerHTML = " ";
			document.getElementById("signup_email").className = "form-group";
			document.getElementById("signup_email_error").innerHTML = " ";
			document.getElementById("signup_pwd").className = "form-group";
			document.getElementById("signup_pwd_error").innerHTML = " ";
			document.getElementById("signup_cpwd").className = "form-group";
			document.getElementById("signup_cpwd_error").innerHTML = " ";
			return true;
		}

	}
	
</script>


