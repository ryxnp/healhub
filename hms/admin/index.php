<?php
session_start();
error_reporting(0);
include("include/config.php");

// Set maximum login attempts and lockout duration
define('MAX_LOGIN_ATTEMPTS', 3);
define('LOCKOUT_DURATION', 600); // 10 minutes in seconds

// Initialize login attempts if not set
if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 0;
    $_SESSION['lockout_time'] = 0;
}

// Check if user is currently locked out
if ($_SESSION['attempts'] >= MAX_LOGIN_ATTEMPTS) {
    if (time() < $_SESSION['lockout_time']) {
        $remaining_time = ($_SESSION['lockout_time'] - time()) / 60; // Convert to minutes
        $error_message = "Too many failed login attempts. Please try again in " . ceil($remaining_time) . " minute(s).";
        $locked_out = true; // User is locked out
    } else {
        // Reset attempts after lockout duration has passed
        $_SESSION['attempts'] = 0;
    }
}

if (isset($_POST['submit']) && !isset($locked_out)) {
    // Sanitize user inputs
    $uname = mysqli_real_escape_string($con, $_POST['username']);
    $upassword = md5($_POST['password']); // Consider using password_hash() instead of md5()

    // Prepare SQL statement to prevent SQL injection
    $stmt = mysqli_prepare($con, "SELECT * FROM admin WHERE username=? AND password=?");
    mysqli_stmt_bind_param($stmt, 'ss', $uname, $upassword);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $num = mysqli_fetch_array($result);

    if ($num > 0) {
        // Successful login
        $_SESSION['login'] = $uname;
        $_SESSION['id'] = $num['id'];
        header("location:dashboard.php");
        exit();
    } else {
        // Increment attempt count on failed login
        $_SESSION['attempts'] += 1;

        if ($_SESSION['attempts'] == MAX_LOGIN_ATTEMPTS) {
            // Lockout user after maximum attempts reached
            $_SESSION['lockout_time'] = time() + LOCKOUT_DURATION; // Set lockout time
            $error_message = "Too many failed login attempts. Please try again in 10 minutes.";
        } else {
            // Provide feedback on failed login attempt
            $error_message = "Invalid username or password. Attempt " . $_SESSION['attempts'] . " of " . MAX_LOGIN_ATTEMPTS . ".";
        }

        // Log the failed attempt
        $uip = $_SERVER['REMOTE_ADDR'];
        mysqli_query($con, "INSERT INTO userlog(username, userip, status) VALUES('$uname', '$uip', '0')");
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
	<title>Admin-Login</title>
	<link rel="icon" href="assets/images/logo.png" type="image/x-icon" />

	<meta charset="utf-8" />
	<meta name="viewport"
		content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta content="" name="description" />
	<meta content="" name="author" />
	<link
		href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic"
		rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
	<link href="vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
	<link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
	<link href="vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
	<link rel="stylesheet" href="assets/css/styles.css">
	<link rel="stylesheet" href="assets/css/plugins.css">
	<link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
</head>

<body class="login">
	<div class="row">
		<div class="main-login col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
			<div class="logo margin-top-30">
				<h2>Admin Login</h2>
			</div>

			<div class="box-login">
			<form class="form-login" method="post">
				<fieldset>
					<legend>
						Sign in to your account
					</legend>
					<p>
						Please enter your email and password to log in.<br />
						<span style="color:red;"><?php echo htmlentities($error_message ?? ''); ?></span>
					</p>
					<div class="form-group">
						<span class="input-icon">
							<input type="text" class="form-control" name="username" placeholder="Email" 
								<?php echo isset($locked_out) ? 'disabled' : ''; ?> required>
							<i class="fa fa-user"></i>
						</span>
					</div>
					<div class="form-group form-actions">
						<span class="input-icon">
							<input type="password" class="form-control password" name="password"
								placeholder="Password" <?php echo isset($locked_out) ? 'disabled' : ''; ?> required>
							<i class="fa fa-lock"></i>
						</span>
					</div>
					<div class="form-actions">
						<button type="submit" class="btn btn-primary pull-right" name="submit"
								<?php echo isset($locked_out) ? 'disabled' : ''; ?>>
							Login <i class="fa fa-arrow-circle-right"></i>
						</button>
					</div>
				</fieldset>
			</form>

				<div class="copyright">
					<span class="text-bold text-uppercase">Healhub Management System</span>
					<br>
					<a href="../../index.php">Back to Home</a>
				</div>

			</div>

		</div>
	</div>
	<script src="vendor/jquery/jquery.min.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="vendor/modernizr/modernizr.js"></script>
	<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
	<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
	<script src="vendor/switchery/switchery.min.js"></script>
	<script src="vendor/jquery-validation/jquery.validate.min.js"></script>

	<script src="assets/js/main.js"></script>

	<script src="assets/js/login.js"></script>
	<script>
		jQuery(document).ready(function () {
			Main.init();
			Login.init();
		});
	</script>

</body>
<!-- end: BODY -->

</html>