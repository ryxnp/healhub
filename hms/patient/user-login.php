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
    } else {
        // Reset attempts after lockout duration has passed
        $_SESSION['attempts'] = 0;
    }
}

if (isset($_POST['submit']) && $_SESSION['attempts'] < MAX_LOGIN_ATTEMPTS) {
    // Sanitize user inputs
    $puname = mysqli_real_escape_string($con, $_POST['username']);
    $ppwd = md5($_POST['password']); // Consider using password_hash() instead of md5()

    // Prepare SQL statement to prevent SQL injection
    $stmt = mysqli_prepare($con, "SELECT * FROM users WHERE email=? AND password=?");
    mysqli_stmt_bind_param($stmt, 'ss', $puname, $ppwd);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $num = mysqli_fetch_array($result);

    if ($num > 0) {
        // Successful login
        $_SESSION['login'] = $puname;
        $_SESSION['id'] = $num['id'];
        $pid = $num['id'];
        $uip = $_SERVER['REMOTE_ADDR'];
        $status = 1;

        // Log successful login
        mysqli_query($con, "INSERT INTO userlog(uid, username, userip, status) VALUES('$pid', '$puname', '$uip', '$status')");
        
        header("location:../../index.php");
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
        mysqli_query($con, "INSERT INTO userlog(username, userip, status) VALUES('$puname', '$uip', '0')");
        
        header("location:user-login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>User Login</title>
    <link rel="icon" href="assets/images/logo.png" type="image/x-icon" />
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
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
                <a href="../index.php">
                    <h2> HMS | Patient Login</h2>
                </a>
            </div>

            <div class="box-login">
                <form class="form-login" method="post">
                    <fieldset>
                        <legend>Sign in to your account</legend>
                        <p>
                            Please enter your email and password to log in.<br />
                            <span style="color:red;">
                                <?php 
                                    if (isset($error_message)) {
                                        echo $error_message; 
                                    }
                                ?>
                            </span>
                        </p>
                        <div class="form-group">
                            <span class="input-icon">
                                <input type="text" class="form-control" name="username" placeholder="Email" required <?php echo ($_SESSION['attempts'] >= MAX_LOGIN_ATTEMPTS) ? 'disabled' : ''; ?>>
                                <i class="fa fa-user"></i> 
                            </span>
                        </div>
                        <div class="form-group form-actions">
                            <span class="input-icon">
                                <input type="password" class="form-control password" name="password" placeholder="Password" required <?php echo ($_SESSION['attempts'] >= MAX_LOGIN_ATTEMPTS) ? 'disabled' : ''; ?>>
                                <i class="fa fa-lock"></i>
                            </span><a href="forgot-password.php">Forgot Password?</a>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary pull-right" name="submit" <?php echo ($_SESSION['attempts'] >= MAX_LOGIN_ATTEMPTS) ? 'disabled' : ''; ?>>Login <i class="fa fa-arrow-circle-right"></i></button>
                        </div>
                        <div class="new-account">Don't have an account yet? 
                            <a href="registration.php">Create an account</a>
                        </div>
                    </fieldset>
                </form>

                <div class="copyright">
                    &copy; Healhub Management System. 
                    <br><a href="../../index.php">Back to Home</a>
                </div>

            </div>

        </div>
    </div>

    <!-- Scripts -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/modernizr/modernizr.js"></script>
    <script src="vendor/jquery-cookie/jquery.cookie.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="vendor/switchery/switchery.min.js"></script>
    <script src="vendor/jquery-validation/jquery.validate.min.js"></script>

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/login.js"></script>

</body>

</html>