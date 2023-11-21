<?php
require ('Models/UserDataSet.php');
require("logincontroller.php");

$lat = $_POST['lat'];
var_dump($lat);
$long = $_POST['long'];
$users->setLocation($lat, $long,$_SESSION["login"] );
