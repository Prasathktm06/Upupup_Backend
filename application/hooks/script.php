<?php 
$files = glob('/home/appzozxu/public_html/app.appzoc.com/upupup_developer/pics/temp/*'); // get all file names
foreach($files as $file){ // iterate files
  if(is_file($file))
    unlink($file); // delete file
}


 ?>