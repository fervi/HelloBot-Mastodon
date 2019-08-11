<?php

$imieniny_db = new SQLite3('data/imieniny.sqlite');
$cities = file("data/cities");
$currencies = file("data/currencies");
include("weatherfunction.php");

// Hello
$hello = file("lang/pl/hello.txt");
$hello = trim($hello[rand(0, count($hello)-1)]);

// Introduce yourself
$whoami = file("lang/pl/whoami.txt");
$whoami = trim($whoami[rand(0, count($whoami)-1)]);

// Name days
$today = date("d.m");
$today_query = $imieniny_db->querySingle("SELECT imiona FROM imieniny WHERE data=$today LIMIT 1", TRUE);
$imieniny_titlebar = "--- Imieniny:";
$imieniny = "Dzisiaj imieniny ma: ".$today_query['imiona'].'.';

// Joke
$joke_titlebar = "--- Kawał:";
$joke = trim(str_replace("'", "\'", (str_replace("\n", '\\n', (trim(shell_exec('/usr/games/fortune')))))));

// Weather
$weather_titlebar = "--- Pogoda:";
$weather = "";
foreach($cities as $city) {
$weather_error = 0;

do {
    $temp = trim(weather($city));
    $weather_error++;
    if($weather_error==10) { $temp = 999; }
} while ($temp == "-273.2°C");

if($temp==999) { $temp = "BrakInfo"; }

$weather = $weather.str_replace(", PL", "", trim($city)).' - '.$temp.'\n';
}

// Cryptocurrency exchange rate
$cmc_titlebar = "--- Kursy kryptowalut:";
$cmc = "";
$api = json_decode(file("https://coinpaprika.com/ajax/coins/")[0], TRUE);
for($i=0; $i<=(count($api)-1); $i++) {
for($j=0; $j<=(count($currencies)-1); $j++) {
if(trim($currencies[$j])==$api[$i]["url_name"]  ) { $cmc = $cmc.$api[$i]["name"].' - '.$api[$i]["price_stats"]["usd"]["price_formatted"].' ('.$api[$i]["price_stats"]["usd"]["change_24h"].'%)\n'; }
}}

// Concentrate and post
$cat = trim($hello.'\n\n'.$whoami.'\n\n'.$imieniny_titlebar.'\n'.$imieniny.'\n\n'.$joke_titlebar.'\n'.$joke.'\n\n'.$weather_titlebar.'\n'.$weather.'\n\n'.$cmc_titlebar.'\n'.$cmc);
echo shell_exec("echo '$cat' | toot post");
