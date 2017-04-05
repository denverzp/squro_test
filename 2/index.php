<?php
/**
 * Use for CLI
 * run 
 * php index.php http://uri
 */

//show all errors
error_reporting(E_ALL);

require_once(__DIR__ . '/vendor/autoload.php');

//check arguments
if($argc < 2 || empty($argv[1]) ){
    die('Missing required arguments!');
}

//remove incorrect characters
$url = filter_var($argv[1], FILTER_SANITIZE_URL);

//validate URI
if(false === filter_var($url, FILTER_VALIDATE_URL)){
    die('Incorrect URI!');
}

$links = new \App\Links($url);

//load HTML
$html = $links->loadHTML();

//if error load html
if(false === $html){  
    die('Cannot load this URI');
}

//find links
$links->findLinks($html);

//show links
foreach ($links->find as $link){
    echo $link , PHP_EOL;
}