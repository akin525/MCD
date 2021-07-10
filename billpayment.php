<?php
if(!isset($_SERVER['HTTP_REFERER'])){
    // redirect them to your desired location
    header('location:404.php');
    exit;
}

include_once ("include/session.php");

// Inialize session
//session_start();
// Check, if username session is NOT set then this page will jump to login page
if (!isset($loggedin_session)) {
    print "<script language='javascript'>	window.location = 'index.php';</script>";
}

$product_type="";
$topay= intval(mysqli_real_escape_string($con,$_GET["amount"]));
$refid= mysqli_real_escape_string($con,$_GET["refid"]);
$product= mysqli_real_escape_string($con,$_GET["product"]);
$productid=mysqli_real_escape_string($con,$_GET['productid']);
$phone=mysqli_real_escape_string($con,$_GET['number']);
$GLOBALS['recipient']=mysqli_real_escape_string($con,$_GET['number']);
$GLOBALS['method']=mysqli_real_escape_string($con,$_GET['method']);


$query="SELECT * FROM users where username = '".$loggedin_session."'";
$result = mysqli_query($con,$query);
while($row = mysqli_fetch_array($result))
{
    $payer="$row[username]";
}

$query="SELECT * FROM  products where  id = '$productid'";
$result = mysqli_query($con,$query);

while($row = mysqli_fetch_array($result)){
    $name="$row[name]";
    $title="$row[title]";
    $details="$row[details]";
    $dataplan="$row[dataplan]";
    $networkcode="$row[networkcode]";
    $product_type="$row[product_type]";
}

function pro($tran_stat, $product, $payer, $topay, $refid, $results, $con){
    $query="SELECT * FROM  wallet WHERE username='".$_SESSION['username']."'";
    $result = mysqli_query($con,$query);
    while($row = mysqli_fetch_array($result)){
        $balance="$row[balance]";
    }

//    $query=mysqli_query($con,"insert into bill_payment (status,product, username, amount, transactionid, paymentmethod, server_response) values ('$tran_stat','$product', '$payer', '$topay', '$refid', '". $GLOBALS['method']."', '$results')");

    if($tran_stat==0){
        $refund=$balance+$topay;
        $query=mysqli_query($con,"update wallet set balance='".$refund."' where username='".$loggedin_session."'");
    }
    echo "<script language='javascript'> window.location='myinvoice.php';</script>";
}

//start buying
if($product_type=="data"){
//    buy($networkcode, $product_type, $phone, $product, $payer, $topay, $refid, $con);
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.5starcompany.com.ng/mcd_reseller_v2.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('api' => 'MCDKEY_903sfjfi0ad833mk8537dhc03kbs120r0h9a','service' => 'data','coded' => $networkcode,'phone' => $phone),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;

    $data=json_decode($response, true);
    $success=$data["success"];
    $tran=$data["ref"];

    if($success==1) {
        $query = mysqli_query($con, "insert into bill_payment (product, username, amount, transactionid, paymentmethod,status) values ('$title', '$payer', '$topay', '$tran', 'Wallet Payment', '$success')");
        echo "<script language='javascript'> window.location='myinvoice.php';</script>";
    }
    if($success==0){
        echo "<script language='javascript'> window.location='mcderror.php';</script>";
    }
}

elseif ($product_type=="airtime") {
//    buy($networkcode, $product_type, $phone, $product, $payer, $topay, $refid, $con);
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.5starcompany.com.ng/mcd_reseller_v2.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('api' => 'MCDKEY_903sfjfi0ad833mk8537dhc03kbs120r0h9a','service' => 'airtime','coded' => $networkcode,'phone' => $phone,'amount' => $topay),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;
    $data=json_decode($response, true);
    $success=$data["success"];
    $tran=$data["ref"];
    if($success==1) {
        $query = mysqli_query($con, "insert into bill_payment (product, username, amount, transactionid, paymentmethod,status) values ('$title', '$payer', '$topay', '$tran', 'Wallet Payment', '$success')");
        echo "<script language='javascript'> window.location='myinvoice.php';</script>";
    }
    if($success==0){
        echo "<script language='javascript'> window.location='mcderror.php';</script>";
    }
}
elseif ($product_type=="paytv") {
//    buy($networkcode, $product_type, $phone, $product, $payer, $topay, $refid, $con);
}elseif ($product_type=="prepaid") {
    buy($networkcode, $product_type, $phone, $product, $payer, $topay, $refid, $con);
}else{
    $tran_stat="0";
    $results="Waiting for admin action";
    pro($tran_stat, $product, $payer, $topay, $refid, $results, $con);
}

//function buy($networkcode, $product_type, $phone, $product, $payer, $topay, $refid, $con){
//    $url=$GLOBALS['server'].'coded=' . $networkcode . '&phone=' . $phone . '&amount='.$topay . '&service=' . $product_type . '&refid=' . $refid . '&payer=' . $payer . '&token=873ey3uidvr3274';
//
//    // Perform transaction/initialize on our server to buy
//    $results = file_get_contents($url);
//    $str_arr = explode (",", $results);
//
//
//    if ($str_arr[0]==1) {
//        $tran_stat="1";
//        if($product_type=="prepaid"){
//            $token=$str_arr[4];
//            $product=$product." with token => ".$token;
//        }
//        pro($tran_stat, $product, $payer, $topay, $refid, $results, $con);
//
//    }else {
//        $tran_stat="0";
//        pro($tran_stat, $product, $payer, $topay, $refid, $results, $con);
//    }
//
//}//end buying data

?>
