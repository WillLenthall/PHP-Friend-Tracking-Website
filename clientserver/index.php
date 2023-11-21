<?php

//require_once ('Models/UserDataSet.php');
require("logincontroller.php");
$view = new stdClass();
$view->pageTitle = 'Homepage';



$users = new UserDataSet();
//$total = count($view->users = $users->fetchAllUsers($_SESSION["login"],0,9999999999));

$limit = 15;    // Remnants of pagination attempt, left in case I will use later


$offset=0;
if (isset($_SESSION["login"])) {
    $loc = new LocationClass();
    $view->setLoc = $loc->getAndSetLocation();
    $view->users = $users->fetchAllUsers($_SESSION["login"], $offset, $limit); // fetches users other than the logged in user
}



else
{
    $nobody = '';
    $view->users = $users->fetchAllUsers($nobody, 0, 15); // fetches all users
}


require_once('Views/index.phtml');
