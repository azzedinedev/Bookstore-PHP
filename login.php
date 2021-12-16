<?php
	include(dirname(__FILE__)."/objects/db/class.db.php");
	include(dirname(__FILE__)."/objects/pagination/class.pagination.php");
	include(dirname(__FILE__)."/objects/session/class.session.php");
	include(dirname(__FILE__)."/objects/mail/class.mail.php");
	include(dirname(__FILE__)."/objects/auth/class.auth.php");
	include(dirname(__FILE__)."/modules/configDB.php");
	
	//Login	
	if ( ( isset($_GET['reset']) and $_GET['reset'] == 'password' ) and ( isset($_GET['token']) and !empty($_GET['token']) ) ){ 
		$formIsToResetPassword = true;
	}
	
	if ( ( isset($_GET['disconnect']) and $_GET['disconnect'] == '1' ) ){ 		
		$auth->disconnect();
	}

	$formIsToValidate == false;
	if ( ( isset($_POST['typeloginpage']) and $_POST['typeloginpage'] == 'login' ) ){ 		
		if ( ( isset($_POST['username']) and !empty($_POST['username']) ) and ( isset($_POST['password']) and !empty($_POST['password']) ) ){ 		
			$sqlLogin = "SELECT * FROM account WHERE login = '".$database->escapeDB($_POST['login'])."' AND password = '".$database->escapeDB($_POST['password'])."' AND status = '1' ";
			$numLogin = $database->DB_num_rows($sqlLogin);
			if( $numLogin == 0 ){
				$auth->connect($_POST['username'],$_POST['password']);
			}else{
				$formIsToValidate == true;
			}
		}
	}else{
		if ( ( isset($_POST['typeloginpage']) and $_POST['typeloginpage'] == 'register' ) ){
			if( !empty($_POST['login']) and !empty($_POST['password']) and !empty($_POST['email']) and !empty($_POST['firstname']) and !empty($_POST['lastname']) ) {
				//Insert account
				$token = md5($_POST['login']."-".$_POST['password']);
				$sqlInserAccount = "INSERT INTO `account` (`login`,`password`,`email`,`role`,`token`,`status`) 
				VALUES ('".$database->escapeDB($_POST['login'])."','".$database->escapeDB($_POST['password'])."','".$database->escapeDB($_POST['email'])."','user','".$database->escapeDB($token)."','1');";
				$database->DB_res($sqlInserAccount);

				$accountID = $database->insertid();

				$sqlInserUser = "INSERT INTO `user` (`firstname`,`middleInitial`,`lastname`,`Phone1`,`Phone2`,`credit_card_name`,`credit_card_number`,`credit_card_security_code`,`credit_card_expiry_date`,`account_id`) 
				VALUES ('".$database->escapeDB($_POST['firstname'])."',
				'".$database->escapeDB($_POST['middleInitial'])."',
				'".$database->escapeDB($_POST['lastname'])."',
				'".$database->escapeDB($_POST['Phone1'])."',
				'".$database->escapeDB($_POST['Phone2'])."',
				'".$database->escapeDB($_POST['credit_card_name'])."',
				'".$database->escapeDB($_POST['credit_card_number'])."',
				'".$database->escapeDB($_POST['credit_card_security_code'])."',
				'".$database->escapeDB($_POST['credit_card_expiry_date'])."',
				'".$database->escapeDB($accountID)."'
				
			);";
				$database->DB_res($sqlInserAccount);

			}else{
				//error on register
			}
		}else{
			if ( ( isset($_POST['typeloginpage']) and $_POST['typeloginpage'] == 'token' ) ){ 		
				if ( isset($_POST['token']) and !empty($_POST['token']) ) { 		
					$sqlUpdateToken = "UPDATE `account` SET `status` = '0' WHERE token = ".$database->escapeDB($_POST['token']);				
					$database->DB_res($sqlUpdateToken);
				}
			}else{
				if ( ( isset($_POST['typeloginpage']) and $_POST['typeloginpage'] == 'disconnect' ) ){ 		
					$auth->disconnect();
				}else{
					if ( ( isset($_POST['typeloginpage']) and $_POST['typeloginpage'] == 'forgote' ) ){ 		
						$sqlForgotPassword = "SELECT US.*, AC.token FROM user AS US INNER JOIN account AS AC ON AC.account_id = US.account_id WHERE UC.email = '".$database->escapeDB($_POST['email'])."' ";
						$rowForgotPassword = $database->DB_fetch($sqlForgotPassword);
						$auth->dbfield_firstname = 'login';
						$authUsername = $database->stripDB($rowForgotPassword['login']);
						$authFirstname = $database->stripDB($rowForgotPassword['firstname']);
						$authLastname = $database->stripDB($rowForgotPassword['lastname']);
						$auth->dbfield_mail = $database->stripDB($_POST['email']);
						$auth->auth_subject_mail_pass = "Instruction to reset your password on the Bookstore";
						$auth->from_mail = "noreply-bookstore@yopmail.com";
						$auth->mail_from_name = "Bookstore administrator";
						$linkReset = 'http://localhost/bookstore/reset=password&token='.$database->stripDB($rowForgotPassword['token']);
						$auth->auth_body_mail_pass = '
						<table width="564" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff" align="center" style=" color:#666; border: 1px solid #BBB; margin: 50px auto; font-size: 12px; line-height: 18px; font-family: Arial;">
							<tr>
								<td style="padding:20px;">
									Hello '.$authFirstname.' '.$authLastname.',<br>
									<p>
									<b>A request has been recieved to change the password for your Bookstore account.<br>
									Click on the link below to create a new password.
									</b>
									</p>
									<a style="background-color:#FE980F; color:#FFFFFF;font-size:18px;height:35px; line-height:35px; text-align:center;font-weight: bold;" align="center" href="'.$linkReset.'">RESET PASSWORD</a>
									<p>
									Thank you,<br>
									The bookstore Team
									</p>
								</td>
							</tr>
							<tr>
								<td style="padding:10px; font-family:Arial; line-height: 16px; color:#999; border-top:1px dotted #999; background:#555; text-align:center;">
									BOOKSTORE the online shop
								</td>
							</tr>
						
						</table>						
						';
						//$auth->mailNewPass($authUsername);
						
					}else{
						if ( ( isset($_POST['typeloginpage']) and $_POST['typeloginpage'] == 'reset' ) ){ 		
							$sqlUpdatePassword = "UPDATE `account` SET `password` = '".$_POST['password']."', `status` = '0' WHERE token = ".$database->escapeDB($_POST['token']);				
							$database->DB_res($sqlUpdatePassword);						
						}
					}	
				}	
			}
		}
	}
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Login | BOOKSTORE</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/prettyPhoto.css" rel="stylesheet">
    <link href="css/price-range.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
	<link href="css/main.css" rel="stylesheet">
	<link href="css/responsive.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->       
    <link rel="shortcut icon" href="images/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">
</head><!--/head-->

<body>
	<header id="header"><!--header-->

			
	<div class="header-middle"><!--header-middle-->
		<div class="container">
			<div class="row">
				<div class="col-sm-4">
					<div class="logo pull-left">
						<a href="index.php"><img src="images/home/logo.png" alt="" /></a>
					</div>						
				</div>
				
				<div class="col-sm-8">
					<?php include('modules/top-menu.php'); ?>
				</div>
			</div>
		</div>
	</div><!--/header-middle-->

	<?php include('modules/main-menu.php'); ?>

	</header>
	
	<section id="form"><!--form-->
		<div class="container">
			<div class="row">
				<?php 
					if( $auth->getUser() == "" ){ 
				?>
				<div class="col-sm-4 col-sm-offset-1">
					<?php 
					if( $formIsToResetPassword == false ){ 
						if( $formIsToValidate == false ){ 
					?>
					<div class="login-form"><!--login form-->
						<h2>Login to your account</h2>
						<form action="login.php" method="post">
							<input name="username" type="text" placeholder="User name" />
							<input name="password"  type="password" placeholder="Password" />
							<input name="typeloginpage"  type="hidden" value="login" />
							<button type="submit" class="btn btn-default">Login</button>
						</form>
					</div><!--/login form-->
					<?php }else{ ?>
						<div class="login-form"><!--validation form-->
						<h2>Login to your account</h2>
						<form action="login.php" method="post">
							<input name="token" type="text" required placeholder="Put the validation code here" />
							<input name="typeloginpage" type="hidden" value="token" />
							<button type="submit" class="btn btn-default">Login</button>
						</form>
					</div><!--/validation form-->
					<?php } ?>

					<h2 class="or">OR</h2>

					<div class="login-form"><!--login form-->
						<h2>Forgote yor password ?</h2>
						<span>
							Dont't worry! Enter your email below and we'll email you with instructions on how to reset your password.
						</span>
						<form action="login.php" method="post">
							<input name="email" type="email" required placeholder="Your email here" />
							<input name="typeloginpage"  type="hidden" value="forgote" />
							<button type="submit" class="btn btn-default">Login</button>
						</form>
					</div><!--/login form-->

					<?php }else{ ?>
						<div class="reset-form"><!--reset form-->
						<h2>Reset your password</h2>
						<form action="login.php" method="post">
							<input name="password" type="text" required placeholder="Your new password here" />
							<input name="token" type="text" required placeholder="Your validation code here" value="<?php echo $_GET['token']; ?>" />
							<input name="typeloginpage" type="hidden" value="reset" />
							<button type="submit" class="btn btn-default">Login</button>
						</form>
					</div><!--/reset form-->
					<?php } ?>


				</div>

				<div class="col-sm-1">
					<h2 class="or">OR</h2>
				</div>
				
				<div class="col-sm-4">
					<div class="signup-form"><!--sign up form-->
						<h2>New User Signup!</h2>
						<form action="login.php" method="post">
							<input name="login" type="text" placeholder="Name" required/>
							<input name="email" type="email" placeholder="Email Address" required/>
							<input name="password" type="password" placeholder="Password" required/>

							<input name="firstname" type="text" placeholder="firstname" required/>
							<input name="middleInitial" type="text" placeholder="middleInitial" />
							<input name="lastname" type="text" placeholder="lastname" required/>
							<input name="Phone1" type="text" placeholder="Phone 1" />
							<input name="Phone2" type="text" placeholder="Phone 2" />
							<input name="credit_card_name" type="text" placeholder="Credit card holder" />
							<input name="credit_card_number" type="text" placeholder="Credit card number" />
							<input name="credit_card_security_code" type="number" placeholder="Security code of credit card" />
							<input name="credit_card_expiry_date" type="text" placeholder="Expiry date of credit card" pattern="\d{1,2}/\d{4}" />			

							<input name="typeloginpage" type="hidden" value="register" />
							<button type="submit" class="btn btn-default">Signup</button>
						</form>
					</div><!--/sign up form-->

				</div>
				<?php }else{ ?>
				<div class="col-sm-4 col-sm-offset-1">
					<div class="login-form"><!--Deconnect form-->
						<h2>You are logged as (<?php echo $auth->getName(); ?>)</h2>
						<form action="login.php" method="post">
							<input type="hidden" name="typeloginpage" value="disconnect"> 
							<button type="submit" class="btn btn-default">Deconnect</button>
						</form>
					</div><!--/Deconnect form-->
				</div>	
				<?php } ?>
			</div>
		</div>
	</section><!--/form-->
	
	<!--Footer-->
	<?php include('modules/footer.php'); ?>
	

  
    <script src="js/jquery.js"></script>
	<script src="js/price-range.js"></script>
    <script src="js/jquery.scrollUp.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.prettyPhoto.js"></script>
    <script src="js/main.js"></script>
</body>
</html>