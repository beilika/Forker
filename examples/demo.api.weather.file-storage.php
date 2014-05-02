<?php
/**************************************************
 * Example: Retrieving the city-weather using external api
 * Usage  : php examples/demo.api.weather.php 
 * Storage: File
 **************************************************/
require 'vendor/autoload.php';

use Forker\Forker;
use Forker\Storage\FileStorage;

$allCitiesWeather = "";

$urlApiWeather = "http://api.openweathermap.org/data/2.5/weather?q=%s&mode=xml";

$myTasks = array(
  'madrid'    => sprintf($urlApiWeather, 'Madrid'),
  'london'    => sprintf($urlApiWeather, 'London'),
  'new-york'  => sprintf($urlApiWeather, 'NewYork'),
  'barcelona' => sprintf($urlApiWeather, 'barcelona'),
  'lisboa'    => sprintf($urlApiWeather, 'lisboa'),
  'iasi'      => sprintf($urlApiWeather, 'iasi'),
);

// a way to keep our data
$storageSystem = new FileStorage;
$numberOfSubTasks = 6;

$forker = new Forker($storageSystem, $myTasks, $numberOfSubTasks);

$time_start = microtime(true);

$forker->map(function($url, $city, $emit) {
  echo "Retrieving weather in $city\n";
  
  $contents = file_get_contents($url);
  $emit($city, $contents);
});

$allCitiesWeather = $forker->fetch();

$time_end = microtime(true);
$time = $time_end - $time_start;

echo "it took {$time} seconds in paralel \n";

$time_start = microtime(true);

foreach($myTasks as $city => $url) {
  echo 'Retrieving weather in ' . $city . "\n";
  $allCitiesWeather[] = file_get_contents($url);
}

$time_end = microtime(true);
$time = $time_end - $time_start;

echo "it took {$time} seconds secuencially \n";