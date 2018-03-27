<?php 
error_reporting(0);
include('vendor/autoload.php');
require('stopWords.php');
require('CSVFile.php');
//loading classes for tokenizers
use NlpTools\Tokenizers\WhitespaceAndPunctuationTokenizer;
use NlpTools\Tokenizers\WhitespaceTokenizer;

//loading classes for FeatureFactories
use NlpTools\FeatureFactories\FunctionFeatures;
use NlpTools\FeatureFactories\DataAsFeatures;

//loading classes for StopWords and Normalizer
use NlpTools\Utils\StopWords;
use NlpTools\Utils\Normalizers\Normalizer;

//loading classes for Documents
use NlpTools\Documents\Document;
use NlpTools\Documents\WordDocument;
use NlpTools\Documents\TrainingSet;
use NlpTools\Documents\TokensDocument;
use NlpTools\Documents\DocumentInterface;
use NlpTools\Documents\TrainingDocument;

//loading classes for FeatureBasedNB
use NlpTools\Models\FeatureBasedNB;

//loading classes for MultinomialNBClassifier
use NlpTools\Classifiers\MultinomialNBClassifier;

//loading classes for PorterStemmer
use NlpTools\Stemmers\PorterStemmer;

  class MarketSniper
  {
    // property declaration
    var $dictTerms = array();
    var $trainingSet =  array();
    var $testingSet =  array();
    
    public function __construct() {
      //$this->laughranDict(); 
    }
    
    public function getTrainingSet(){
      return $this->trainingSet;
    }
    
    public function getTestingSet(){
      return $this->testingSet;
    }
    
    public function getFeatureArray($class, DocumentInterface $doc)
    {
        $tokens = $doc->getDocumentData();
        $tokens = array_count_values($tokens);

        foreach ($tokens as $tok=>&$v) {
            $v = min($v, 4);
        }

        return $tokens;
    }
    /*
    Read CSV data form the path as the argument 
    and determine whether it is a tarining set or not
    */
    public function ReadCSVData($path,$training = 1){
      $csvData = array();
      $csv = new CSVFile($path);

      foreach ($csv as $line){
        if($training == 1){
          $this->trainingSet[] = array($line['class'],strip_tags($line['text']));
        }else{
          $this->testingSet[] = array($line['class'],strip_tags($line['text']));
        }
      }
    }
  }

  
  
  function computation($newsArray = array(),$companyName){
    require('stopWords.php');
    require('DB.class.php');
    //object for the corealgorithm csv builder class
    $MarketSnip = new MarketSniper(); 
    $db = new DB();

    //read the training data for the news 
    $MarketSnip->ReadCSVData("dataset/training.csv");
    $training = $MarketSnip->getTrainingSet();

    //read the testing data for the news 
   
    $testing = $newsArray;

    // will hold the training documents
    $trainingSetObj = new TrainingSet(); 

    // will split into tokens
    $tokenObj = new WhitespaceAndPunctuationTokenizer();
     
    $featuresObj = new DataAsFeatures(); 

    //stemmer object
    $stemmer = new PorterStemmer(); 
    $feats = new FunctionFeatures();

    //stop words tranformation object
    $stop = new StopWords($stopWords);

    //stemmer tranformation object
    $normalize = Normalizer::factory("English");

    // Training the data from the csv for the NBclassifier
    foreach ($training as $document)
    {
        //tokenizing document text with WhitespaceAndPunctuationTokenizer
        $tokenText = $tokenObj->tokenize($document[1]);
        $tokenDoc = new TokensDocument(explode(" ", $document[1]));
        
        //adding the stopWord tranformation for the tokens
        $tokenDoc->applyTransformation($stop);
        
        //adding the normalize tranformation for the tokens
        $tokenDoc->applyTransformation($normalize);
        $data = $tokenDoc->getDocumentData();
        
        //stemming the data to the root base form
        $stemDocArray = $stemmer->stemAll($data);
        $trainingSetObj->addDocument($document[0], // class
          new TokensDocument($stemDocArray));// The actual document
    }
    // train a Naive Bayes model
    $model = new FeatureBasedNB(); 
    $model->train($featuresObj,$trainingSetObj);

    // Classify the data on the basis of the trained data model for NB
    $cls = new MultinomialNBClassifier($featuresObj,$model);
    
    foreach ($testing as $tdocument)
    {   
        //tokenizing document text with WhitespaceAndPunctuationTokenizer
        $tokenTestText = $tokenObj->tokenize($tdocument['Content']);
        $tokenDoc = new TokensDocument(explode(" ", $tdocument['Content']));
        
        //adding the stopWord tranformation for the tokens
        $tokenDoc->applyTransformation($stop);
        
        //adding the normalize tranformation for the tokens
        $tokenDoc->applyTransformation($normalize);
        $data = $tokenDoc->getDocumentData();
        
        //stemming the data to the root base form
        $stemDocArray = $stemmer->stemAll($data);
        
        // predict if it is positive or a negative class
        $prediction = $cls->classify(
            array('negative','positive'), // all possible classes
            new TokensDocument($stemDocArray) // The document
            );
        //echo "For document id  the prediction is ".$prediction." <br/>";
        $tdocument['polarity'] = $prediction ;
        $tdocument['companyName'] = $companyName ;
        $db->insertNews($tdocument);
        //exit();
    }
  }

  function getNews(){
    require_once('DB.class.php');
    $db = new DB();
    $new = $db->getNews();
    return $new;
  }
  
  function countNews(){
    require_once('DB.class.php');
    $db = new DB();
    $data = $db->countNews();
    return $data;
  }
  
  function computationtest(){
    require_once('stopWords.php');
    //object for the corealgorithm csv builder class
    $MarketSnip = new MarketSniper();

    //read the training data for the news 
    $MarketSnip->ReadCSVData("dataset/training.csv");
    $training = $MarketSnip->getTrainingSet();

    //read the testing data for the news 
    $MarketSnip->ReadCSVData("dataset/testing.csv" , 0);
    $testing = $MarketSnip->getTestingSet();

    // will hold the training documents
    $trainingSetObj = new TrainingSet(); 

    // will split into tokens
    $tokenObj = new WhitespaceAndPunctuationTokenizer();
     
    $featuresObj = new DataAsFeatures(); 

    //stemmer object
    $stemmer = new PorterStemmer(); 
    $feats = new FunctionFeatures();

    //stop words tranformation object
    $stop = new StopWords($stopWords);

    //stemmer tranformation object
    $normalize = Normalizer::factory("English");

    // Training the data from the csv for the NBclassifier
    foreach ($training as $document)
    {
        //tokenizing document text with WhitespaceAndPunctuationTokenizer
        $tokenText = $tokenObj->tokenize($document[1]);
        $tokenDoc = new TokensDocument(explode(" ", $document[1]));
        
        //adding the stopWord tranformation for the tokens
        $tokenDoc->applyTransformation($stop);
        
        //adding the normalize tranformation for the tokens
        $tokenDoc->applyTransformation($normalize);
        $data = $tokenDoc->getDocumentData();
        
        //stemming the data to the root base form
        $stemDocArray = $stemmer->stemAll($data);
        $trainingSetObj->addDocument($document[0], // class
          new TokensDocument($stemDocArray));// The actual document
    }
    // train a Naive Bayes model
    $model = new FeatureBasedNB(); 
    $model->train($featuresObj,$trainingSetObj);

    // Classify the data on the basis of the trained data model for NB
    $cls = new MultinomialNBClassifier($featuresObj,$model);
    $correct = 0;
    //positive class
    $p_tp = 0;//true positive
    $p_fp = 0;//false psotive
    $p_fn = 0;//false negative

    //negative class
    $n_tp = 0;//true positve
    $n_fp = 0;//false psotive
    $n_fn = 0;//false negative

    $docID = 0;
    foreach ($testing as $tdocument)
    {   
        //tokenizing document text with WhitespaceAndPunctuationTokenizer
        $tokenTestText = $tokenObj->tokenize($tdocument[1]);
        $tokenDoc = new TokensDocument(explode(" ", $tdocument[1]));
        
        //adding the stopWord tranformation for the tokens
        $tokenDoc->applyTransformation($stop);
        
        //adding the normalize tranformation for the tokens
        $tokenDoc->applyTransformation($normalize);
        $data = $tokenDoc->getDocumentData();
        
        //stemming the data to the root base form
        $stemDocArray = $stemmer->stemAll($data);
        
        // predict if it is positive or a negative class
        $prediction = $cls->classify(
            array('negative','positive'), // all possible classes
            new TokensDocument($stemDocArray) // The document
            );
        //if ($docID < 11){
          echo "For document id ".$docID." (".$tdocument[0].") the prediction is ".$prediction." <br/>";
        //}
        
        if ($prediction==$tdocument[0])
        {
          if($prediction == 'positive')//check for positive class
          {
            $p_tp++;
          }
          else//check for negative class
          {
            $n_tp++;
          }
          $correct ++;    
        }
        else
        {
          if($prediction == 'positive')
          {
            $p_fp++;
            $n_fn++;
          }
          else
          {
            $n_fp++;
            $p_fn++;
          }
            
        }
        $docID++;
      }

    // echo "------ Limiting the data for representational purpose.";
    echo  "</br>";   echo  "</br>";
    $precision = $p_tp/($p_tp+$p_fp);
    echo "The precision value for class positive ".$precision;
    echo  "</br>"; 
    $recall = $p_tp/($p_tp+$p_fn);
    echo "The recall value for class positive ".$recall;
    echo  "</br>"; 
    echo "The F-measure value for class positive ".(2*$precision*$recall)/($precision+$recall);
    echo  "</br>";echo  "</br>";  

    $recall = $n_tp/($n_tp+$n_fp);
    echo "The precision value for class negative ".$recall;
    echo  "</br>";
    $precision = $p_tp/($p_tp+$n_fn);
    echo "The recall value for class negative ".$precision;
    echo  "</br>";
    echo "The F-measure value for class negative ".(2*$precision*$recall)/($precision+$recall);
    echo  "</br>"; echo  "</br>"; 

    printf("Accuracy: %.2f\n", 100*$correct / count($testing));
}

?>