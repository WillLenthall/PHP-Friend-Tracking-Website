<?php
require_once("logincontroller.php");
require_once("Models/FriendsDataSet.php");

$view = new stdClass();
$view->pageTitle = 'Find new friends';


$pendFriends = new UserDataSet();
$view->pendFriends = $pendFriends->fetchPending($_SESSION["login"]);

$view->friender = new FriendsDataSet();

$loc = new LocationClass();
$view->setLoc = $loc->getAndSetLocation();



if (isset($_POST['submit']))
{

    //var_dump($_POST['submit']);
    $other = $_POST['submit'];  //I was struggling to pass the username through the button so went with this crude approach
    $other = substr($other, 9); //removes the first 9 characters "befriend " in this case
    //var_dump($_SESSION["login"], $other);
    $view->friender->acceptRequest($_SESSION["login"], $other); //changes the relationship status to 2 (friends)
    header("Location:newPeople.php"); //refresh the page
}

if (isset($_POST["decline"]))
{
    $other = $_POST['decline'];  //I was struggling to pass the username through the button so went with this crude approach
    $other = substr($other, 7);

    $view->friender->declineRequest($_SESSION["login"], $other); // deletes the row containing these two users in relationships table
    header("Location:newPeople.php"); //refresh the page
}

require('Views/newPeople.phtml');