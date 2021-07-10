<?php session_start(); include ("fun.php"); ?>
<?php
include_once ("include/database.php");
// Inialize session
//session_start();
if (isset($_SESSION['email'])) {

}
$sql="SELECT maintain FROM  settings WHERE sno=0";
if ($result = mysqli_query($con, $sql)) {

    /* fetch associative array */
    while ($row = mysqli_fetch_row($result)) {
        $main= $row[0];
    }
    if($main==2 || $main==3)
    {
        print "<script language='javascript'>window.location = '404.php';</script>";
    }

}

if($_SERVER['REQUEST_METHOD'] == 'POST') {

// Collect the data from post method of form submission //
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
//    $password = md5($password);

    $query = "SELECT * from users where email='$email' and password='$password'";
//    $query= "SELECT * FROM `admin` WHERE email='$email' and password='$password'";
    $result = $con->query($query);
    $count = mysqli_num_rows($result);
//    echo $count;
//    return;

// If result matched $myusername and $mypassword, table row must be 1 row
    if ($count == 1) {
        $result = mysqli_query($con, $query);

//        while ($row = mysqli_fetch_array($result)) {
//            $status = $row['active'];
//        }
//
//        if ($status == 0) {
//            $errormsg = "<div class='alert alert-danger'>
//    <button type='button' class='close' data-dismiss='alert'>&times;</button>
//    <i class='fa fa-ban-circle'></i><strong>You have been banned from accessing this portal </br></strong></div>"; //printing error if found in validation
//        } else {
        $_SESSION['email'] = $email;
        $_SESSION['login_user']=$email;
        print "
                    <script language='javascript'>
                        window.location = 'dashboard.php';
                    </script>
                    ";
//        }
    } else {
        $errormsg = "<div class='alert alert-danger'>
    <button type='button' class='close' data-dismiss='alert'>&times;</button>
    <i class='fa fa-ban-circle'></i><strong>Incorrect login details </br></strong></div>"; //printing error if found in validation
    }

}

?>

<!-- page-title area end -->

<center><?php
    if(!empty($errormsg))
    {
        print $errormsg;

    }
    ?>
</center>
<body class="vh-100">
<div class="authincation h-100">
    <div class="container h-100">
        <div class="row justify-content-center h-100 align-items-center">
            <div class="col-md-6">
                <div class="authincation-content">
                    <div class="row no-gutters">
                        <div class="col-xl-12">
                            <div class="auth-form">
                                <div class="text-center mb-3">
                                    <a href="index.html"><img src="images/logo-full.png" alt=""></a>
                                </div>
                                <h4 class="text-center mb-4">Sign in your account</h4>
                                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, "utf-8"); ?>" method="post">
                                    <div class="mb-3">
                                        <label class="mb-1"><strong>Email</strong></label>
                                        <input type="email" name="email" class="form-control" spellcheck="false" placeholder="email" class="form-control" required autocomplete="current-email">
                                    </div>
                                    <div class="mb-3">
                                        <label class="mb-1"><strong>Password</strong></label>
                                        <input type="password" name="password"spellcheck="false" placeholder="Password" class="form-control" required autocomplete="current-password">
                                    </div>
                                    <div class="row d-flex justify-content-between mt-4 mb-2">
                                        <div class="mb-3">
                                            <div class="form-check custom-checkbox ms-1">
                                                <input type="checkbox" class="form-check-input" id="basic_checkbox_1">
                                                <label class="form-check-label" for="basic_checkbox_1">Remember my preference</label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <a href="page-forgot-password.html">Forgot Password?</a>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-block">Sign Me In</button>
                                    </div>
                                </form>
                                <div class="new-account mt-3">
                                    <p>Don't have an account? <a class="text-primary" href="signup.php">Sign up</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

