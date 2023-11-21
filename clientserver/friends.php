<?php
require_once("logincontroller.php");
require_once ("Models/LocationClass.php");

$view = new stdClass();
$view->pageTitle = 'Friends';

$friends = new UserDataSet();
$view->friends = $friends->fetchFriends($_SESSION["login"]); // retrieves all users who are friends with the current user
$loc = new LocationClass();

$view->modal = $loc->getModal();
$view->getMap = $loc->mapBuild();
$view->markers = $loc->addMarkers($view->friends);
$view->markers2 = $loc->addMarkers2();
$view->setLoc = $loc->getAndSetLocation();




require('Views/friends.phtml');
