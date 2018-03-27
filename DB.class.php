<?php
/*
*Company DB for the project
* The class file acts as a model for the project
* The class file has all the database functions
*
*/
class DB 
{
  private $connection;
  private $dbh;
  
  function __construct() 
  {
    require ("dbinfo.php");
    //connection with PDO object
    try
    {
			$this->dbh = new PDO ("mysql:host=$host; dbname=$db",$user,$pass);
      $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
		catch (PDOException $e)
    {
			die("Bad database connection");
		}
  }
  
  function insertNews($data) 
  {
    try
		{
      $stmt = $this->dbh->prepare("INSERT INTO news (newsID, 	newsTitle, newsContent, publishDate,polarity,companyName) VALUES (:ID, :Title, :Content, :PublishDate, :polarity, :companyName)");
      $stmt->execute($data);
      return true;
    }
		catch(PDOException $e)
		{
			echo $e->getMessage();
			die();
		}
  }//function getAllPeopleAsTable()
  
  
  function updateProductQty($data)
  {
    try
		{
      $stmt = $this->dbh->prepare("UPDATE products SET itemQty = (itemQty)-1  WHERE id = :id");
      $stmt->execute($data);  
      return true;
    }
		catch(PDOException $e)
		{
			echo $e->getMessage();
			die();
		}
  }
  
  function getNews()
  {
    try
		{
      $stmt = $this->dbh->prepare("SELECT * from news");
      $stmt->execute();
      $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $data;
    }
		catch(PDOException $e)
		{
			echo $e->getMessage();
			die();
		}
  }
  
  function getNewsforCompany($company)
  {
    try
		{
      $stmt = $this->dbh->prepare("SELECT * from news WHERE `companyName` = '".$company."'");
      $stmt->execute();
      $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
      return $data;
    }
		catch(PDOException $e)
		{
			echo $e->getMessage();
			die();
		}
  }

  
  function countNews()
  {
    try
		{
      $countArray = array();
      $companies = ["aapl" , "googl", "msft", "fb"];
      foreach($companies as $name){
        $stmt = $this->dbh->prepare("SELECT count(*) as count from news WHERE `companyName` = '".$name."'");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $countArray[$name] = $data[0]["count"];
      }
     
      return $countArray;
    }
		catch(PDOException $e)
		{
			echo $e->getMessage();
			die();
		}
  }



}

?>