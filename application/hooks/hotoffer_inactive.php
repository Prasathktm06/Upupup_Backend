<?php

date_default_timezone_set('Asia/Kolkata');


$servername = "localhost";
$username = "migrate2_jinson";
$password = "jinsonjose007";
$db='migrate2_partnerup_db';

$conn = new mysqli($servername, $username, $password,$db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$date=date('Y-m-d');
$sql = "SELECT id,date,status  FROM hot_offer WHERE date <  '$date' && status=1";
$result = $conn->query($sql);
 while($row = mysqli_fetch_assoc($result)) {
 	$id=$row['id'];
     
     if($row[date] < $date){
     
     $sql3 = "UPDATE `hot_offer` SET `status`=0 WHERE id =$id" ;

     $conn->query($sql3);
 }

   }



 ?>