# Marketsniper, Sentimental analysis on the financial news data | NLP tools PHP                                

An application to analyze 23 million news datasets extracted from myallies.com and Cityfalcon to predict the sentiments to gain insights of the financial data news using natural language using SQL, jQuery, and PHP.

## Developers
Sagar Sawant

## Getting Started
### Xampp Installation
Install Xampp from the given below link which provides the Apache environment to create a local server on your machine which enables to run the PHP scripts on you Personnel computer. https://sourceforge.net/projects/xampp/

Open the Xampp control panel, in the XAMPP control panel, click on ‘Start’ under ‘Actions’ for the Apache module. This instructs XAMPP to start the Apache webserver.

You can test the Xampp Apache module by opening the http://localhost in the browser.

Copy the file structure as it in the htdocs folder is present in the root directory of the xampp installation folder.

## Project root Directory
Dataset – The dataset folder contains the csv file. These are the training and the testing documents for the news classification and prediction system.

Dictionaries – The folder contains the LoughranMcDonald_MasterDictionary_2014 which will be used for the further prediction of the polarity on our own algorithm which is the future prospect of the project.

Vendor dir – It contains all the natural language processing classes for the PHP 5.3 kit. The directory contains all the classes available in the NLP tools data kit.

CSVFile.php – Class to read the csv file and returns as an array to the caller function.

marketSniper_controller.php – This is the controller of the project which has all the main and important functionality of the project including the NB classifier.

NewsGrabber.php – The news Grabber script grabs the news items with the help of the myallies.com api, we have generated a test script to obtain the latest Twitter news form the api which can be downloaded just by running the script in the browser. This script provides an automated csv file which can be used for the project.

stopWords.php – This caontains the array of the stop words which is used to refined the data from the tokens.

### Project Implementation
Run the NewsGrabber.php file to have an initial load of The Twitter Company news list, just for the showcase purposes.

The project takes the data from the training.csv and the test.csv files which uses the Naïve Bayes classifier algorithm to make prediction and classify the documents as per the classes. Once the project directory is placed in the htdocs folder you may check the data output by opening the following url in browser (http://localhost/kpt/marketSniper_controller.php)
