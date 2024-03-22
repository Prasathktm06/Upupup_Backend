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
$sql = "SELECT id,dates,location_id,status  FROM hot_offer_setting WHERE dates =  '$date'";
$result = $conn->query($sql);
 while($row = mysqli_fetch_assoc($result)) {
 	$id=$row['id'];
 	$location_id=$row['location_id'];
   if($row[dates]==$date){
     
     $sql1 = "UPDATE `hot_offer_setting` SET `status`=1 WHERE id =$id" ;

     $conn->query($sql1);
     $sql2 = "UPDATE `hot_offer_setting` SET `status`=0 WHERE id !=$id && location_id=$location_id" ;

     $conn->query($sql2);
 }
   }



 ?>