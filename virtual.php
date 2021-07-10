<?php include_once("include/session.php");
// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['login_user'])) {
    print "
<script language='javascript'>
    window.location = '../login.php';
</script>
";
}

//$topay= mysqli_real_escape_string($con,$_GET["amount"]);
// $refid= mysqli_real_escape_string($con,$_GET["reference"]);

$query = "SELECT * FROM  users WHERE username='" . $loggedin_session . "'";
$result = mysqli_query($con, $query);

$row = mysqli_fetch_array($ses_sql, MYSQLI_ASSOC);
while ($row = mysqli_fetch_array($result)) {
    $username = $row["username"];
    $name = $row["name"];
    $email = $row["email"];
}


$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://sandbox.monnify.com/api/v1/auth/login",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_HTTPHEADER => array(
        "Authorization: Basic TUtfVEVTVF9LUFoyQjJUQ1hLOkJERkJZUUtRSEVHR1NCOFJFODI3VlRGODhYVEJQVDJN",
        "Cache-Control: no-cache",
    ),
));
//curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
//curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0)

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {

    echo "cURL Error #:" . $err;
} else {
//    echo $response;
}
$data=json_decode($response, true);
$token=$data["responseBody"]["accessToken"];

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL =>  "https://sandbox.monnify.com/api/v2/bank-transfer/reserved-accounts",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS =>  "{\"accountReference\": \"$username.\",\"accountName\": \"$name.\", \"currencyCode\": \"NGN\", \"contractCode\": \"6925684256\", \"customerEmail\": \"akinlabisamson15@gmail.com\", \"customerName\": \"$username\", \"getAllAvailableBanks\": false, \"preferredBanks\": [\"035\"] }",
    CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json",
        "Authorization: Bearer " . $token
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err) {
    echo "cURL Error #:" . $err;
} else {
    echo $response;
}


$data=json_decode($response, true);
$account=$data["responseBody"]["accounts"][0]["bankName"];
$number=$data["responseBody"]["accounts"][0]{"accountNumber"};


//echo "update wallet SET account_name=$account, account_no=$number, where username='".$loggedin_session."'";
$result=mysqli_query($con,"update wallet SET account_name='$account', account_no=$number where username='".$loggedin_session."'");

$query="SELECT * FROM users where email = '".$_SESSION['login_user']."'";
$result = mysqli_query($con,$query);
while($row = mysqli_fetch_array($result))
{

    $n="$row[name]";
}

$query="SELECT * FROM  wallet WHERE username='".$loggedin_session."'";
$result = mysqli_query($con,$query);
while($row = mysqli_fetch_array($result))
{
    $a=$row["account_name"];
    $b=$row["account_no"];
}

$mail= "info@efemobilemoney.com";
$to = $email;
$from = $mail;
$name = $_REQUEST['name'];
//$subject = $_REQUEST['subject'];
//$number = $_REQUEST['phone_no'];
//$cmessage = $_REQUEST['message'];

$headers = "From: $from";
$headers = "From: " . $from . "\r\n";
$headers .= "Reply-To: ". $from . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

$subject = "From EFE MOBILE MONEY.";

$logo = '<img src=public/images/logo/logo.png alt=logo>';
$link = '#';

$body = "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><title>Express Mail</title></head><body>";
$body .= "<table style='width: 100%;'>";
$body .= "<thead style='text-align: center;'><tr><td style='border:none;' colspan='2'>";
$body .= "<a href='{$link}'><img src='{$logo}' alt=''></a><br><br>";
$body .= "<p style='border:none;'><strong>New Virtual Account Generated For You<strong>";
$body .= "<p style='border:none;'><strong>Account Name:</strong> {$n}</p>";
$body .= "<p style='border:none;'><strong>Bank Name:</strong> {$a}</p>";
$body .= "<p style='border:none;'><strong>Account Number:</strong> {$b}</p>";
//$body .= "<p style='border:none;'><strong>Wallet Balance:</strong>#0.00</p>";
$body .= "</tr>";
// 	$body .= "<tr><td style='border:none;'><strong>Subject:</strong> {$csubject}</td></tr>";
$body .= "<tr><td></td></tr>";
//$body .= "<tr><td colspan='2' style='border:none;'>{$cmessage}</td></tr>";
$body .= "</tbody></table>";
$body .= "</body></html>";

$send = mail($to, $subject, $body, $headers);

echo "<script language='javascript'>window.location = 'dashboard.php';</script>";
?>


