<?php
require_once("logincontroller.php");
require_once('Models/UserDataSet.php');


$user = new UserDataSet();

$post = json_decode(file_get_contents("php://input"), true);

if (isset($post['lat']) && isset($post['long']) && isset($_SESSION["login"])) {
    $lat = $post['lat'];
    $long = $post['long'];

    $user->setLocation($lat, $long, $_SESSION["login"]);
}

