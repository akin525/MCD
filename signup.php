<?php include ("fun.php"); ?>
<?php include ("include/database.php");

$query="SELECT * FROM settings";


$result = mysqli_query($con,$query);

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'])) {
// Collect the data from post method of form submission //
$name = mysqli_real_escape_string($con, $_POST['name']);
$password = mysqli_real_escape_string($con, $_POST['password']);
$password2 = mysqli_real_escape_string($con, $_POST['password2']);
$email = mysqli_real_escape_string($con, $_POST['email']);
$phone = mysqli_real_escape_string($con, $_POST['phone']);
$username = mysqli_real_escape_string($con, $_POST['username']);
//$refer= mysqli_real_escape_string($con, $_POST['refer']);


$status = "OK";
$msg = "";

if (!isset($username) or strlen($username) < 6) {
    $msg = $msg . "Username Should Contain Minimum 6 CHARACTERS.<br />";
    $status = "NOTOK";
}

if (!ctype_alnum($username)) {
    $msg = $msg . "Username Should Contain Alphanumeric Chars Only.<br />";
    $status = "NOTOK";
}

$remail = mysqli_query($con, "SELECT COUNT(*) FROM users WHERE email = '$email'");
$re = mysqli_fetch_row($remail);
$nremail = $re[0];
if ($nremail == 1) {
    $msg = $msg  .  "E-Mail Id Already Registered. Please try another one<br />";
    $status = "NOTOK";
}

if (strlen($password) < 8) {
    $msg = $msg . "Password Must Be More Than 8 CHARACTERS Length.<br />";
    $status = "NOTOK";
}

if (strlen($email) < 1) {
    $msg = $msg . "Please Enter Your Email Id.<br />";
    $status = "NOTOK";
}

if ($password <> $password2) {
    $msg = $msg . "Both Passwords Are Not Matching.<br />";
    $status = "NOTOK";
}
$sql = "SELECT username FROM users WHERE username='{$username}'";
$result = mysqli_query($con,$sql) or die("Query unsuccessful") ;
if (mysqli_num_rows($result) > 0) {
    $msg = $msg . "user id already Registered. please try another one<br />";
    $status = "NOTOK";
}
//Test if it is a shared client
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
//Is it a proxy address
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
//The value of $ip at this point would look something like: "192.0.34.166"
//$ip = ip2long($ip);
//The $ip would now look something like: 1073732954
$token = bin2hex(openssl_random_pseudo_bytes(32));
if ($status == "OK") {
//echo mysqli_query($con,"insert into `users`(`active`,`username`,`password`,`fname`,`email`,`ipaddress`,`mobile`,`country`) values(1,'$username','$passmd','$name','$email','$ip','$phone','$country')");
mysqli_query($con, "INSERT INTO `users` (`username`, `email` ,`password`, `name`, `phone`) VALUES ('$username', '$email', '$password', '$name', '$phone')");
mysqli_query($con,"insert INTO wallet (username,balance) values('$username',0)");
//mysqli_query($con,"INSERT INTO referal (`username`, `newuserid`, amount) value ('$refer', '$username', 100)");
    $suss= "<div class='card'>
                    <button type='button' class='close' data-dismiss='alert'>&times;</button>
                    <i class='fa fa-ban-circle'></i><strong>Account Registration successful : </br></strong>A mail has been sent to $email containing your login details for record purpose. Check your spam folder if message is not found in your inbox. $password</div>";
    //printing error if found in validation
    print "
				<script language='javascript'>
window.location = 'dashboard.php';
</script>
";
}else{
    $errormsg= "<div class='card'>
                    <button type='button' class='close' data-dismiss='alert'>&times;</button>
                    <i class='fa fa-ban-circle'></i><strong>Please Fix Below Errors : </br></strong>".$msg."</div>"; //printing error if found in validation
}
}
?>


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
                                    <a href="index.php"><img src="images/logo-full.png" alt=""></a>
                                </div>
                                <h4 class="text-center mb-4">Sign up your account</h4>
                                <?php
                                if($_SERVER['REQUEST_METHOD'] == 'POST' && ($status=="NOTOK"))
                                {
                                    print $errormsg;

                                }
                                ?>

                                <?php
                                if($_SERVER['REQUEST_METHOD'] == 'POST' && ($status=="OK"))
                                {
                                    print $suss;

                                }
                                ?>
                                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, "utf-8"); ?>"method="post">
                                    <div class="mb-3">
                                        <label class="mb-1"><strong>FullName</strong></label>
                                        <input type="text" class="form-control" name="name" id="inputName" required autocomplete="name"/>
                                    </div>
                                    <div class="mb-3">
                                        <label class="mb-1"><strong>Username</strong></label>
                                        <input type="text"name="username" class="form-control" spellcheck="false" placeholder="Username" required autocomplete="username"/>
                                    </div>
                                    <div class="mb-3">
                                        <label class="mb-1"><strong>Email</strong></label>
                                        <input type="email" name="email" class="form-control" spellcheck="false" placeholder="Enter Your Email" required autocomplete="email"/>
                                    </div>
                                    <div class="mb-3">
                                        <label class="mb-1"><strong>Phone-No</strong></label>
                                        <input type="number" name="phone" class="form-control" spellcheck="false" placeholder="Enter Your phone no" required autocomplete="phone"/>
                                    </div>
                                    <div class="mb-3">
                                        <label class="mb-1"><strong>Password</strong></label>
                                        <input type="password" id="password" class="form-control"name="password">

                                        <div class="mb-3">
                                            <label>Confirm Password :</label>
                                            <input type="password" id="password_confirmation" class="form-control" name="password2">
                                        </div>
                                    <div class="text-center mt-4">
                                        <button type="submit" class="btn btn-primary btn-block">Sign me up</button>
                                    </div>
                                </form>
                                <div class="new-account mt-3">
                                    <p>Already have an account? <a class="text-primary" href="index.php">Sign in</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
