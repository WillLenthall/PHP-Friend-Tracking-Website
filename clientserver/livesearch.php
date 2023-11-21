<?php
require_once("logincontroller.php");
require_once("Models/UserDataSet.php");

$user = new UserDataSet();
$results =[];
$post = json_decode(file_get_contents("php://input"), true);

if (isset($_SESSION["login"])) {
    $results = $user->fetchSomeUsers($_SESSION["login"],$post["input"]);
}
else {
    $nobody = "";
    $results = $user->fetchSomeUsers($nobody,$post["input"]);
}

$results = array_slice($results, 0, 6); //limits results, should do in SQL but function used elsewhere
$uName =[];                                           //and using a variable to set limit was causing issues
foreach ($results as $r) {
    array_push($uName, $r->getUsername());
}

    $hints = json_encode($uName);       //only returns publicly available usernames so no need to hide
    echo $hints;                           //did not figure out images
  //  echo trim($string, '"');        //removes quotation marks


