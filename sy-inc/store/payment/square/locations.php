<?php
require 'autoload.php';
$access_token = "Bearer sandbox-sq0atb-YPXh-cCpQr-eO3me_L769Q";
$location_api = new \SquareConnect\Api\LocationApi();
$locations =  $location_api->listLocations($access_token);

$location_id = $locations["locations"][0]["id"];

print "<h1>id: $location_id</h1>";

print "<pre>";
print_r($locations);

?>