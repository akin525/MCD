<?php
$dbname="mcd";
$con = new mysqli("localhost", "root", "", "mcd");
if ($con->connect_errno) {
    echo "Failed to connect to MySQL: (" . $con->connect_errno . ") " . $con->connect_error;
}
?>
