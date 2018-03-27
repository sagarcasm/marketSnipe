<?php 
set_time_limit ( 500000 );
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'http://myallies.com/api/news/twtr');
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result, true);

$f = fopen('output.csv', 'w');

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
}

convert_to_csv($csv, 'data_as_csv.csv', ',');

function convert_to_csv($input_array, $output_file_name, $delimiter)
{
  $temp_memory = fopen('php://memory', 'w');
  // loop through the array
  foreach ($input_array as $line) {
  // use the default csv handler
  fputcsv($temp_memory, $line, $delimiter);
  }

  fseek($temp_memory, 0);
  // modify the header to be CSV format
  header('Content-Type: application/csv');
  header('Content-Disposition: attachement; filename="' . $output_file_name . '";');
  // output the file to be downloaded
  fpassthru($temp_memory);
}

//echo $result->access_token;


?>