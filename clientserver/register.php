<?php
require ('Models/UserDataSet.php');
$view = new stdClass();
$view->pageTitle = 'Create an account';

require('Views/register.phtml');

if ($_POST!=null) {

    //gets the info from the form
    $reg = new UserDataSet();
    $username = $_POST['username'];
    $email = $_POST['email'];
    $fName = $_POST['first_name'];
    $lName = $_POST['last_name'];
    $password = $_POST['psw'];
    $passCheck = $_POST['psw-repeat'];
    $human = false;
    //var_dump($_POST['robotcheck']);
    if (isset($_POST['robotcheck']))
    {
        $human = true;
    }
    //checks the username provided is unique
    $check = $reg->checkPrimaryKey($username);
    $message = '';
    //uploads the image
    $target_dir = "images/";
    if ($_FILES['img']['name'] == null) {
        $upload = 1;
        $target_file = null;
    } else {
        $target_file = $target_dir . basename($_FILES["img"]["name"]);
        //var_dump($_FILES["img"]["name"]);
        $upload = 0; // whether the file will be uploaded, no by default
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Check if image file is an actual image or fake image

        $imgCheck = getimagesize($_FILES["img"]["tmp_name"]);
        if ($imgCheck !== false) {
            $upload = 1; // sets upload to yes
        } else {
            $message = "File is not an image.";
            $upload = 0;
        }
    }
    //if all checks are passed then new user is entered into database and they are redirected to log in screen
    if ($check == true && $password == $passCheck && $upload == 1 && $human == true) {
        if (move_uploaded_file($_FILES["img"]["tmp_name"], $target_file) != null) {

            //if image upload was successful a new account is made with the uploaded image
            $reg->insertNewUser($username, $email, $password, $fName, $lName, $target_file);
            header("Location:login.php");

        } else {

            //if no image was uploaded they are given a default image and redirected to the login page
            $reg->insertNewUser($username, $email, $password, $fName, $lName, 'images/default.jpg');
            header("Location:login.php");

        }
        //warning messages informing them why the request did not go through
    } else if ($passCheck != $password) {
        $message = $message . '<br>The passwords provided do not match.';
    }

     else if ($human == false) {
         $message = $message . 'No robots';}
    else $message = $message . '<br>The username you have selected has been taken';


    echo "<div class='text-center'><h4>$message</h4></div>";
}





