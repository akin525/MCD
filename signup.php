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
    $msg = $msg . "<h4>Username Should Contain Minimum 6 CHARACTERS.<br /></h4>";
    $status = "NOTOK";
}

if (!ctype_alnum($username)) {
    $msg = $msg . "<h4>Username Should Contain Alphanumeric Chars Only.<br /></h4>";
    $status = "NOTOK";
}

$remail = mysqli_query($con, "SELECT COUNT(*) FROM users WHERE email = '$email'");
$re = mysqli_fetch_row($remail);
$nremail = $re[0];
if ($nremail == 1) {
    $msg = $msg  .  "<h4>E-Mail Id Already Registered. Please try another one<br /></h4>";
    $status = "NOTOK";
}

if (strlen($password) < 8) {
    $msg = $msg . "<h4>Password Must Be More Than 8 CHARACTERS Length.<br /></h4>";
    $status = "NOTOK";
}

if (strlen($email) < 1) {
    $msg = $msg . "<h4>Please Enter Your Email Id.<br /></h4>";
    $status = "NOTOK";
}

if ($password <> $password2) {
    $msg = $msg . "<h4>Both Passwords Are Not Matching.<br /></h4>";
    $status = "NOTOK";
}
$sql = "SELECT username FROM users WHERE username='{$username}'";
$result = mysqli_query($con,$sql) or die("Query unsuccessful") ;
if (mysqli_num_rows($result) > 0) {
    $msg = $msg . "<h4>user id already Registered. please try another one<br /></h4>.";
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

    $to = $email;
    $from = "mcd@gmail.com";
//    $name = $_REQUEST['name'];
//    $subject = $_REQUEST['subject'];
//    $number = $_REQUEST['phone_no'];
//    $cmessage = $_REQUEST['message'];

    $headers = "From: $from";
    $headers = "From: " . $from . "\r\n";
    $headers .= "Reply-To: ". $from . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    $subject = "From EFE MOBILE MONEY.";

    $logo = '<img src="public/images/logo/logo.png" alt="logo">';
    $link = '#';

    $body = '<html><body>';
    $body .= '<h1>Thanks for Registration</h1>';
    $body .= '<h1>Users Detail:</h1>';
    $body .= '</body></html>';

    $body = '<html><body>';
    $body .= '<img src="public/images/logo/logo.png" alt="logo"/>';
    $body .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
    $body .= "<tr style='background: #eee;'><td><strong>Full-Name:</strong> </td><td>{$name}</td></tr>";
    $body .= "<tr><td><strong>Email:</strong> </td><td>{$email}</td></tr>";
    $body .= "<tr><td><strong>Your Password:</strong> </td><td>{$password}</td></tr>";
    $body .= "<tr><td><strong>Wallet Balance:</strong> </td><td>NGN 0.00</td></tr>";
    $body .= "</table>";
    $body .= "</body></html>";


    $send = mail($to, $subject, $body, $headers);
    $suss= "<div class='card'>
                    <button type='button' class='close' data-dismiss='alert'>&times;</button>
                    <i class='fa fa-ban-circle'></i><strong>Account Registration successful : </br></strong>A mail has been sent to $email containing your login details for record purpose. Check your spam folder if message is not found in your inbox. $password</div>";
    //printing error if found in validation
    print "
				<script language='javascript'>
				let message = 'Account Registration successful : A mail has been sent to $email containing your login details for record purpose. Check your spam folder if message is not found in your inbox. ';
                                    alert(message);
window.location = 'dashboard.php';
</script>
";
}else{
    $errormsg= "<center><div class='card'>
                    <i class='fa fa-ban-circle'></i><h4>Please Fix Below Errors : </br></h4>".$msg."</div></center>"; //printing error if found in validation
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
