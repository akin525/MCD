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
$refid= mysqli_real_escape_string($con,$_GET["reference"]);
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.paystack.co/transaction/verify/$refid",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer sk_test_280c68e08f76233b476038f04d92896b4749eec3",
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
    echo $response;
}
$data=json_decode($response, true);
$amount=$data["data"]["amount"]/100;



$query="SELECT * FROM users where email = '".$_SESSION['login_user']."'";
$result = mysqli_query($con,$query);
while($row = mysqli_fetch_array($result))
{
    $depositor="$row[username]";
    $email="$row[email]";
    $name="$row[name]";
}

$query="SELECT * FROM  wallet WHERE username='".$loggedin_session."'";
$result = mysqli_query($con,$query);
while($row = mysqli_fetch_array($result))
{
    $ubalance=$row["balance"];
}

$fwallet=$ubalance+$amount;





$query=mysqli_query($con,"insert into deposit (status, username, amount, payment_ref,  iwallet, fwallet, date) values (1,'$depositor', '$amount', '$refid', '$ubalance', '$fwallet', CURRENT_TIMESTAMP)");
$result=mysqli_query($con,"update wallet set balance=balance+$amount WHERE username='$depositor'");
$query="SELECT * FROM deposit where username = '".$loggedin_session."'";
$result = mysqli_query($con,$query);
while($row = mysqli_fetch_array($result))
{
    $depositor=$row["amount"];
    $iwallet=$row["iwallet"];

}

$mail= "info@efemobilemoney.com";
$to = $email;
$from = $mail;
//$name = $_REQUEST['name'];
//$subject = $_REQUEST['subject'];
//$number = $_REQUEST['phone_no'];
//$cmessage = $_REQUEST['message'];

$headers = "From: $from";
$headers = "From: " . $from . "\r\n";
$headers .= "Reply-To: ". $from . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

$subject = "From EFE MOBILE MONEY.";

$logo = '<img src="public/images/logo/logo.png" alt="logo">';
$link = '#';

$body = "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><title>Express Mail</title></head><body>";
$body .= "<table style='width: 100%;'>";
$body .= "<thead style='text-align: center;'><tr><td style='border:none;' colspan='2'>";
$body .= "<a href='{$link}'><img src='{$logo}' alt=''></a><br><br>";
$body .= "<p style='border:none;'><strong>Wallet Summary<strong>";
$body .= "<p style='border:none;'><strong>Name:</strong> {$name}</p>";
$body .="<div class=card float_left>";
$body .= " <tr>
                                                <td class=invest_td1>Tansaction Id</td>

                                                <td class=invest_td1>: NGN{$re}</td>
                                            </tr>
                                            </tbody>wallet ";
$body .= "<tbody>
                                            <tr>
                                                <td class=invest_td1>Early Payments</td>
                                                <td class=invest_td1>: NGN {$amount}</td>
                                            </tr>";
$body .= "<tr>
                                                <td class=invest_td1>Matured Deposit</td>
                                                <td class=invest_td1>: NGN{$iwallet}</td>
                                            </tr>";
$body .= " <tr>
                                                <td class=invest_td1>Released Deposit</td>

                                                <td class=invest_td1>: NGN{$fwallet}</td>
                                            </tr>
                                            </tbody>wallet ";
$body .= "</tr>";
// 	$body .= "<tr><td style='border:none;'><strong>Subject:</strong> {$csubject}</td></tr>";
$body .= "<tr><td></td></tr>";
//$body .= "<tr><td colspan='2' style='border:none;'>{$cmessage}</td></tr>";
$body .= "</tbody></table>";
$body .= "</body></html>";

$send = mail($to, $subject, $body, $headers);
echo "<script language='javascript'>window.location = 'dashboard.php';</script>";
?>


