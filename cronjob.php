<?php
error_reporting(E_ALL);
require('marketSniper_controller.php');
  set_time_limit ( 500000 );
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_URL, 'http://myallies.com/api/news/');
  $result = curl_exec($ch);
  curl_close($ch);
  

  $obj = json_decode($result, true);
  //echo $obj[0]['Company']['Name'];
  $csv = array();
  
  foreach($obj as $key => $value){
    $newsID = $value['NewsID'];
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, 'http://myallies.com/api/newsitem/'.$newsID);
    $result1 = curl_exec($curl);
    $test = json_decode($result1,true);
    $csv[]= $test;
    
    curl_close($curl);
    
    //exit();
  }//end of foreach
  
  computation($csv,$companyName="Company");
 
?>