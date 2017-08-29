<!DOCTYPE html> 
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
				    <!--<form class="navbar-form no-margin no-border no-padding" role="search" >
							    <div class="form-group">
							        <input type="text" class="form-control" placeholder="Search">
							    </div>
						    </form>
						</li>
						<li><button type="submit" class="btn btn-default">Search</button></li>-->
						<li><a href="home.html" >HOME</a></li>
					</ul>
				</div>
			</div>
		</nav>
		<br>
		<br>
		<div class="container">

			<div class="col-md-6 col-sm-6 no-padng">   
				<div class="model-l">
					<div class="l">Login</div><br>                  
					<form method="post" id="logFrm" class="log-frm" name="logFrm"> 
						<ul>                                                     
							<div class="form-group">
								<label class="control-label">USERNAME</label>
								<input type="text" class="form-control" name="login_name" placeholder="Username" required/>
							</div>

							<div class="form-group">
								<label class="control-label">PASSWORD</label>
								<input type="password" class="form-control" name="login_pwd" placeholder="Password" required/>
								<a class="pull-right">Forgot your password?</a>
							</div>
							
							<div class="form-group">
								<br><button type="button" name="logBtn" class="btn btn-primary active pull-right">LOGIN</button>
							</div>




						</form>
					</div>
				</div>    
				<div class="col-md-6 col-sm-6 no-padng">
					<div class="model-r">
						<div class="r">Sign Up</div><br>

						<form method="post" action="" id="signUpForm" class="log-frm" name="signUpForm">  

							<div class="form-group" id="signup_name">
								<label class="control-label">NAME</label>
								<input type="text" class="form-control" name="signup_name" id="signup_name_input" placeholder="Name" required/>
								<span id="signup_name_error" class="help-block"></span>
							</div>
							<div class="form-group" id="signup_email">
								<label class="control-label">EMAIL</label>
								<input type="text" class="form-control" name="signup_email" id="signup_email_input" placeholder="Email" required/>
								<span id="signup_email_error" class="help-block"></span>
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

							<!--
							<div class="form-group">
								<input type="submit" name="signUpBtn" class="btn btn-info active pull-right" value="SIGN UP">
							</div>
							-->

							<div class="form-group">
								<button type="button" onclick="submitSignUp()" name="signUpBtn" class="btn btn-info active pull-right">SIGN UP</button>
							</div>

						</form>
					</div>
				</div>
			</div>

			<script data-align="right" data-overlay="false" id="keyreply-script" src="//keyreply.com/chat/widget.js" data-color="#E4392B" data-apps="JTdCJTIyZmFjZWJvb2slMjI6JTIyMTAwMDAwMzU0Njc5MjA0JTIyLCUyMmVtYWlsJTIyOiUyMnNodXdlaS5wZWhAZ21haWwuY29tJTIyJTdE"></script>

	</body>
</html>

<script type="text/javascript">
	
	function submitSignUp()
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
		}
		else  
		{
			document.getElementById("signup_name").className = "form-group";
			document.getElementById("signup_name_error").innerHTML = " ";
		}

		//check email
		if(document.getElementById("signup_email_input").value.match(emailformat))  
		{
			document.getElementById("signup_email").className = "form-group";
			document.getElementById("signup_email_error").innerHTML = " ";
		}
		else  
		{
			document.getElementById("signup_email").className += " has-error";
			document.getElementById("signup_email_error").innerHTML = "You have entered an invalid email address!";
			return false;
		}

		//check password
		if(document.getElementById("signup_pwd_input").value.match(passwordformat))
		{
			document.getElementById("signup_pwd").className = "form-group";
			document.getElementById("signup_pwd_error").innerHTML = " ";
		}  
		else  
		{   
			document.getElementById("signup_pwd").className += " has-error";
			document.getElementById("signup_pwd_error").innerHTML = "Must be 6 to 16 characters which contain at least one numeric digit, one uppercase and one lowercase letter";
			return false;
		}  

		//check confirm password
		if(document.getElementById("signup_pwd_input").value != document.getElementById("signup_cpwd_input").value)
		{
			document.getElementById("signup_cpwd").className += " has-error";
			document.getElementById("signup_cpwd_error").innerHTML = "Please make sure your passwords match.";
			return false;
		}	
		else
		{
			document.getElementById("signup_cpwd").className = "form-group";
			document.getElementById("signup_cpwd_error").innerHTML = " ";
		}


		document.signUpForm.submit();
	}

</script>

<?php 
	include("dataconnection.php");

	if(isset($_POST["signUpBtn"]))
		{
			$user_name = $_POST["signup_name"];
			$user_email = $_POST["signup_email"];
			$user_pwd = $_POST["signup_pwd"];
			$user_cpwd = $_POST["signup_cpwd"];
			$signup_faculty = $_POST["signup_faculty"];

			switch($signup_faculty)
			{
				case '0': $faculty = "NONE"; break;
				case '1': $faculty = "Faculty of Management"; break;
				case '2': $faculty = "Faculty of Engineering"; break; 
				case '3': $faculty = "Facutly of Creative Multimedia"; break;
				case '4': $faculty = "Faculty of Computing and Informatics"; break;
				case '5': $faculty = "Faculty of Applied Communication"; break;
				case '6': $faculty = "Centre of Diploma"; break;
				default : $faculty = "NONE"; break;
			}

			$sql1 = "insert into user(user_name, user_email, user_pwd, user_cpwd, faculty)
					values('$user_name', '$user_email', '$user_pwd', '$user_cpwd', '$faculty')";
			mysqli_query($conn,$sql1);
			?>
			<script>alert("Hi <?php echo $user_name?>, your sign up is successful!");</script>
			<?php
			mysqli_close($conn);
		}
?>