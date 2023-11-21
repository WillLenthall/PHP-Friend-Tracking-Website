<?php
require ('Models/UserDataSet.php');
require_once ("Models/LocationClass.php");

session_start();
$view= new stdClass();
//var_dump($_SESSION);
$loc = new LocationClass();
$view->find = $loc->getLocationData();
if (isset($_POST["loginbutton"])) {
    $username = $_POST["username"];   // retrieve username and password from form
    $password = $_POST["password"];
    //echo $username;
    //echo $password;
    $login = new UserDataSet();
    $correct = $login->correctDetails($username,$password); //checks username and password match those in database

    //var_dump($correct);
    if ($correct == true) {
        $userSesh = $username;              //can use sessions to make more variables accessible in tri2
        $_SESSION["login"] = $userSesh;
        header("Location:index.php"); // redirects to home page
    }
    else
    {
        echo'<div class="label-warning"><p>Error in username and password</p></div>';
    }
}

if (isset($_POST["logoutbutton"]))
{
    //logs user out
    //echo "logout user";
    unset($_SESSION["login"]);
    session_destroy();
    header("Location:index.php"); // redirects to homepage
}
