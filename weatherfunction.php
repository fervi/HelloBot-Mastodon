<?php

function weather($city) {
$apikey = 'PASTE YOUR API KEY HERE';
$getweather = file('http://api.openweathermap.org/data/2.5/weather?q='.$city.'&APPID='.$apikey);

$weather = json_decode($getweather[0], TRUE);
return round(($weather["main"]["temp"])-273.15, 1).'°C';
}
?>