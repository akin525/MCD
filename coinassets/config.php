 <?php
 include_once ("./include/database.php");

 $query="SELECT * FROM  crypto where name='Bitcoin' ";
 $result = mysqli_query($con,$query);

while($row = mysqli_fetch_array($result))
{
 $btca="$row[api]";
 $btcs="$row[secret]";

 }
 ?>
  <?php
 $query="SELECT * FROM  crypto where name='Litecoin' ";
 $result = mysqli_query($con,$query);

while($row = mysqli_fetch_array($result))
{
 $lita="$row[api]";
 $lits="$row[secret]";

 }
 ?>
 
  <?php
 $query="SELECT * FROM  crypto where name='Dogecoin' "; 
 $result = mysqli_query($con,$query);

while($row = mysqli_fetch_array($result))
{
 $doga="$row[api]";
 $dogs="$row[secret]";
 
 } 
 ?>
 
 <?php
 $query="SELECT * FROM  crypto where name='Blockio' "; 
 $result = mysqli_query($con,$query);

while($row = mysqli_fetch_array($result))
{
 $block="$row[secret]";
 
 } 
 ?>
 

<?php
$gateway = array();
// Enter here API Key for Bitcoin Network from your https://block.io account
$gateway['bitcoin_api_key'] = $btca;
$gateway['litecoin_api_key'] = $lita;
$gateway['dogecoin_api_key'] = $doga;

// Enter here address where to receive Bitcoin/Litecoin/Dogecoin, address must be from your Block.io account
$gateway['bitcoin_address'] = $btcs;
$gateway['litecoin_address'] = $lits;
$gateway['dogecoin_address'] = $dogs;

// Enter here your Secret PIN of your https://block.io account, need to confirm transactions
$gateway['secret'] = $block;

// Enter here how many confirmations need to verify payment
$gateway['bitcoin_confirmations'] = '2';
$gateway['litecoin_confirmations'] = '2';
$gateway['dogecoin_confirmations'] = '2';
?>