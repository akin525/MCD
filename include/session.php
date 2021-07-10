<?php
session_start();
include('database.php');
$user_check=$_SESSION['login_user'];
$loggedin_session=$user_check;

if(!isset($user_check)) {
    echo "Go back";
    print "
                    <script language='javascript'>
                        window.location = './index.php';
                    </script>
                    ";
}
$ses_sql=mysqli_query($con,"select * from `users` where `email`='$user_check'");
//$ses_sql=mysqli_query($con,"SELECT * FROM `admin` WHERE email='$user_check'");
//if ($ses_sql->num_rows > 0) {
//    // output data of each row
//    while($row = $ses_sql->fetch_assoc()) {
//        echo "id: " . $row["id"]. " - Name: " . $row["name"]. " " . $row["email"]. "<br>";
//    }
//} else {
//    echo "0 results";
$row=mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
//echo $row['username'];
$loggedin_session=$row['username'];
$loggedin_id=$row['id'];
$loggedin_name=$row['name'];
$loggedin_phone=$row{'phone'}
?>