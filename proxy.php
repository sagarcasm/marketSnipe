<?php

require_once('DB.class.php');
if(!empty($_GET)){
  $db = new DB();
  $company = $_GET['cname'];
  //echo $company;
  $new = $db->getNewsforCompany($company);
  ?>
  <table class="table table-bordered" style="padding:10px;" id="tablefancy">
    <thead>
      <tr>
        <th>Heading</th>
        <th>Nasdaq Listing</th>
        <th>Description</th>
        <th>Date</th>
        <th>Polarity Rank</th>
      </tr>
    </thead>
    <tbody>
      
       <?php
        if(!empty($new)){
          foreach($new as $item){
            echo '<tr>';
            echo '<td>'.$item['newsTitle'].'</td>';
            echo '<td>'.ucfirst($item['companyName']).'</td>';
            echo '<td>'.$item['newsContent'].'</td>';
            echo '<td>'.$item['publishDate'].'</td>';
            if($item['polarity'] == 'positive'){
              echo '<td><i style="color:#1fa67a" class="fa fa-thumbs-o-up fa-3x" aria-hidden="true"></i></td>';
            }else{
              echo '<td><i style="color:#e7434e" class="fa fa-thumbs-o-down fa-3x" aria-hidden="true"></i></td>';
            }
            echo '</tr>';
          }
       }
       ?>
   </tbody>
  </table>
 <?php 
 
}?>