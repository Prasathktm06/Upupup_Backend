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
$sql = "SELECT id,location_id,start_date,status,block_status  FROM buycoin_setting WHERE start_date =  '$date'";
$result = $conn->query($sql);
 while($row = mysqli_fetch_assoc($result)) {
 	$id=$row['id'];
 	$location_id=$row['location_id'];
 	$block_status=$row['block_status'];
 if($block_status==1){
     if($row[start_date]==$date){
     
     $sql1 = "UPDATE `buycoin_setting` SET `status`=1 WHERE id =$id" ;

     $conn->query($sql1);
    }
 }	
   
   }

$sql2 = "SELECT id,location_id,end_date,status  FROM buycoin_setting WHERE end_date <  '$date'";
$result2 = $conn->query($sql2);
 while($row2 = mysqli_fetch_assoc($result2)) {
 	$id=$row2['id'];
 	$location_id=$row2['location_id'];
   if($row2[end_date] < $date){
     
     $sql3 = "UPDATE `buycoin_setting` SET `block_status`=0 WHERE id =$id" ;
     $sql5 = "UPDATE `buycoin_setting` SET `status`=0 WHERE id =$id" ;

     $conn->query($sql3);
     $conn->query($sql5);
     $sql4 = "UPDATE `buycoin_setting` SET `block_status`=0 WHERE id =$id" ;
     $sql6 = "UPDATE `buycoin_setting` SET `status`=0 WHERE id =$id" ;

     $conn->query($sql4);
     $conn->query($sql6);
 }
   }



 ?>