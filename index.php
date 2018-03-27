<?php
error_reporting(E_ALL);
require('marketSniper_controller.php');
if(!empty($_GET)){
  
  $companyName = $_GET['companyName'];
    
  set_time_limit ( 500000 );
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_URL, 'http://myallies.com/api/news/'.$companyName);
  $result = curl_exec($ch);
  curl_close($ch);
  

  $obj = json_decode($result, true);
  

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
  
  computation($csv,$companyName);
 
}//end of if empty

?>
<!DOCTYPE html>
<html>
   <head>
      <title>marketSniper</title>
      <meta charset="utf-8">
      <meta name="description" content="Traveling HTML5 Template" />
      <meta name="author" content="Design Hooks" />
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
      <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Raleway:400,700" rel="stylesheet" />
      <link href="img/favicon.png" type="image/x-icon" rel="shortcut icon" />
      <link href="css/screen.css" rel="stylesheet" />
      <link href="css/jquery.dataTables.min.css" rel="stylesheet" />
      <link rel="stylesheet" href="js/fancybox/jquery.fancybox.css" type="text/css" media="screen" />
      <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">
      
      <!-- Scripts -->
      <script src="js/jquery.js"></script>
      <script src="https://code.jquery.com/jquery-latest.js" type="text/javascript"></script>
      <script type="text/javascript" charset="utf8" src="js/jquery.dataTables.min.js"></script>
      <script type="text/javascript" src="js/fancybox/jquery.fancybox.js"></script>
      <script>
      function getdata(stock){
        $.get( "proxy.php", { cname: stock}, function( data ) {
          $.fancybox( data, {
            title : stock
          });
          $('#tablefancy').DataTable();
        });
      }
        
      
      </script>
      
   </head>
   <body class="home" id="page">
      <!-- Header -->
      <header class="main-header">
         
      </header>

      <!-- Main Content -->
      <div class="content-box">
         <!-- Hero Section -->
         <section class="section section-hero">
            <div class="hero-box">
               <div class="container">
                  <div class="hero-text align-center">
                     <h1>marketSniper</h1>
                     <p>Find your news now and start investing !!</p>
                  </div>

                  <form class="destinations-form" action="" method="get">
                     <div class="input-line">
                        <input type="text" name="companyName" value="" class="form-input check-value" placeholder="Nasdaq Listings" />
                        <button type="submit" class="form-submit btn btn-special">Find news</button>
                     </div>
                  </form>
               </div>
            </div>
            <?php
              $count = countNews();
            ?>
            <!-- Statistics Box -->
            <div class="container">
               <div class="statistics-box">
                  <div class="statistics-item">
                     <span class="value"><?php echo $count['aapl'];?></span>
                     <p class="title">Apple News Listing</p>
                  </div>

                  <div class="statistics-item">
                     <span class="value"><?php echo $count['fb'];?></span>
                     <p class="title">Facebook News Listing</p>
                  </div>

                  <div class="statistics-item">
                     <span class="value"><?php echo $count['msft'];?></span>
                     <p class="title">Microsoft News Listing</p>
                  </div>

                  <div class="statistics-item">
                     <span class="value"><?php echo $count['googl'];?></span>
                     <p class="title">Google News Listing</p>
                  </div>
               </div>
            </div>
         </section>

         <!-- Destinations Section -->
         <section class="section section-destination">
            <!-- Title -->
            <div class="section-title">
               <div class="container">
                  <h2 class="title">Explore our latest news with sentimental polarity.</h2>
                  <p class="sub-title">Traditional economic theorist believe that the stability of the market data is impractical to predict above an accuracy of 50 percent in a perfect market world. However studies show that the market stock prices are predictable and follow a similar trait as per the occurrences of certain events in the marketing events or news. Our Services will help predicting this behaviour. </p>
               </div>
            </div>

            <!-- Content -->
            <div class="container">
            
            
            <table class="table table-bordered" id="myTable">
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
                $news = getNews();

                if(!empty($news)){
                  foreach($news as $item){
                    echo '<tr>';
                    echo '<td>'.$item['newsTitle'].'</td>';
                    echo '<td>'.ucfirst($item['companyName']).'</td>';
                    echo "<td style='cursor:pointer;' onclick='getdesc(this)'>".$item['newsContent'].'</td>';
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
               <!--<div class="align-center">
                  <a href="#" class="btn btn-default btn-load-destination"><span class="text">Explore more destinations</span><i class="icon-spinner6"></i></a>
               </div>-->
            </div>
         </section>

         <!-- Parallax Box -->
         <div class="parallax-box">
            <div class="container">
               <div class="text align-center">
                  <h1>MarketSniper</h1>
                  <p>We do the work for you !!</p>
                </div>
            </div>
         </div>

         <!-- Boats Section -->
         <section class="section section-boats">
            <!-- Title -->
            <div class="section-title">
               <div class="container">
                  <h2 class="title">Featured News</h2>
                  <p class="sub-title">Take a look at these Big Four !!!!</p>
               </div>
            </div>

            <!-- Content -->
            <div class="container">
               <div class="row">
                  <div class="col-sm-12 col-xs-24" onclick="getdata('aapl')">
                     <div class="boat-box">
                        <div class="box-cover">
                           <img src="img/boat-1.jpg" alt="destination image" />
                        </div>

                        <span class="boat-price">Apple</span>

                        <div class="box-details">
                           <div class="box-meta">
                              <h4 class="boat-name">Apple</h4>
                              <ul class="clean-list boat-meta">
                                 <li class="location">Cupertino, CA</li>
                                 <li class="berths">USA</li>
                              </ul>
                           </div>
                        </div>
                     </div>
                  </div>

                  <div class="col-sm-12 col-xs-24" onclick="getdata('fb')">
                     <div class="boat-box">
                        <div class="box-cover">
                           <img src="img/boat-2.jpg" alt="destination image" />
                        </div>

                        <span class="boat-price">Facebook</span>

                        <div class="box-details">
                           <div class="box-meta">
                              <h4 class="boat-name">Facebook</h4>
                              <ul class="clean-list boat-meta">
                                 <li class="location">Menlo Park, CA</li>
                                 <li class="berths">USA</li>
                              </ul>
                           </div>
                        </div>
                     </div>
                  </div>

                  <div class="col-sm-12 col-xs-24" onclick="getdata('msft')">
                     <div class="boat-box">
                        <div class="box-cover">
                           <img src="img/boat-3.jpg" alt="destination image" />
                        </div>

                        <span class="boat-price">Microsoft</span>

                        <div class="box-details">
                           <div class="box-meta">
                              <h4 class="boat-name">Microsoft</h4>
                              <ul class="clean-list boat-meta">
                                 <li class="location">Redmond, WA</li>
                                 <li class="berths">USA</li>
                              </ul>
                           </div>
                        </div>
                     </div>
                  </div>

                  <div class="col-sm-12 col-xs-24" onclick="getdata('googl')">
                     <div class="boat-box">
                        <div class="box-cover">
                           <img src="img/boat-4.jpg" alt="destination image" />
                        </div>

                        <span class="boat-price">Google</span>

                        <div class="box-details">
                           <div class="box-meta">
                              <h4 class="boat-name">Google</h4>
                              <ul class="clean-list boat-meta">
                                 <li class="location">Mountain View, CA</li>
                                 <li class="berths">USA</li>
                              </ul>
                           </div>
                        </div>
                     </div>
                  </div>

                  
               </div>
            </div>
         </section>
      </div>

      <!-- Footer -->
      <footer class="main-footer">
         <div class="container">
            <div class="row">
               <div class="col-md-5">
                  <div class="widget widget_links">
                     <h5 class="widget-title">Top News</h5>
                     <ul>
                        <li><a href="#">Apple</a></li>
                        <li><a href="#">Facebook</a></li>
                        <li><a href="#">Microsoft</a></li>
                        <li><a href="#">Google</a></li>
                     </ul>
                  </div>
               </div>
               <div class="col-md-9">
                  <div class="widget widget_social">
                     <h5 class="widget-title">Subscribe to our newsletter</h5>
                     <form class="subscribe-form">
                        <div class="input-line">
                           <input type="text" name="subscribe-email" value="" placeholder="Your email address" />
                        </div>
                        <button type="button" name="subscribe-submit" class="btn btn-special no-icon">Subscribe</button>
                     </form>

                     <ul class="clean-list social-block">
                        <li>
                           <a href="#"><i class="icon-facebook"></i></a>
                        </li>
                        <li>
                           <a href="#"><i class="icon-twitter"></i></a>
                        </li>
                        <li>
                           <a href="#"><i class="icon-google-plus"></i></a>
                        </li>
                     </ul>
                  </div>
               </div>

               <div class="col-md-5">
                  <div class="widget widget_links">
                     
                  </div>
               </div>

               

               <div class="col-md-5">
                  <div class="widget widget_links">
                     <h5 class="widget-title">Contact us</h5>
                     
                  </div>
               </div>
            </div>
         </div>
      </footer>

      
     <script>
      $(document).ready(function(){
        $('#myTable').DataTable();
      });
      
      function getdesc(element){
        $.fancybox( $(element).html(), {
            title : "Description"
          });
      }
      </script>
      <script src="js/functions.js"></script>
   </body>
</html>
