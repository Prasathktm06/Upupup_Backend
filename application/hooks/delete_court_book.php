<?php

date_default_timezone_set('Asia/Kolkata');

/*$ci=&get_instance();
$ci->load->model("court/court_model");
$ci->court_model->delete_court_book_daily();
exit;
$res=$ci->court_model->select_court_book_daily();
foreach ($res as $key => $value) {
$diff=	(strtotime(date('H:i:s'))-strtotime($value->time_stamp))/60;
$diff=(int)$diff;


    $ci->court_model->delete_court_book_daily($value->id);

}*/
$servername = "localhost";
$username = "migrate2_jinson";
$password = "jinsonjose007";
$db='migrate2_partnerup_db';

$conn = new mysqli($servername, $username, $password,$db);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$date=date('H:i:s');
$sql = "SELECT time_stamp,id FROM court_book WHERE time_stamp <  '$date'";
$result = $conn->query($sql);
 while($row = mysqli_fetch_assoc($result)) {
   $diff=	(strtotime($date)-strtotime($row['time_stamp']))/60;
   $diff=(int)$diff;

   if($diff>=2){
     $id=$row['id'];
     $sql = "DELETE FROM court_book WHERE id =$id";
     $conn->query($sql);
   }
 }


 ?>
