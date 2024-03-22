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


     $sql = "DELETE FROM offer WHERE `end` < '$date'";
     $conn->query($sql);



 ?>
