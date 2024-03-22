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
$sql = "SELECT id,location_id,start_date,end_date,status  FROM refer_bonus_setting WHERE start_date =  '$date'";
$result = $conn->query($sql);
 while($row = mysqli_fetch_assoc($result)) {
 	$id=$row['id'];
 	$location_id=$row['location_id'];
   if($row[start_date]==$date){
     
     $sql1 = "UPDATE `refer_bonus_setting` SET `status`=1 WHERE id =$id" ;

     $conn->query($sql1);
     $sql2 = "UPDATE `refer_bonus_setting` SET `status`=0 WHERE id !=$id && location_id=$location_id" ;

     $conn->query($sql2);
 }
   }

$sql3 = "SELECT id,location_id,start_date,end_date,status  FROM refer_bonus_setting WHERE end_date <  '$date'";
$result1 = $conn->query($sql3);
 while($row1 = mysqli_fetch_assoc($result1)) {
 	$id=$row1['id'];
 	$location_id=$row1['location_id'];
   if($row1[end_date] < $date){
     
     $sql4 = "UPDATE `refer_bonus_setting` SET `status`=0 WHERE id =$id" ;

     $conn->query($sql4);
 }
   }



 ?>