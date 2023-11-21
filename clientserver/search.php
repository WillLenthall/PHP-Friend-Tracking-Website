<?php
require_once("logincontroller.php");
require_once("Models/FriendsDataSet.php");
$view = new stdClass();
$view->pageTitle = 'Search';

$users = new UserDataSet();

//Changes the view based on whether a user is logged in or not
if (isset($_SESSION["login"]))
{
    $loc = new LocationClass();
    $view->setLoc = $loc->getAndSetLocation();
    $view->friendish = $users->fetchSomeRelation($_SESSION["login"]); // fetches users other than the logged in user
    $view->others = $users->fetchOthers($_SESSION["login"]);
}

else {
    $nobody = '';
    $view->users = $users->fetchAllUsers($nobody, 0, 15); // fetches all users
}



//creates a new row in the relationships table when a user adds another user
if (isset($_POST['add'])) {

    $relationship = new FriendsDataSet();
    $name =$_POST['add'];
    $name = substr($name, 4);
    //var_dump($name);
    $relationship->createRelationship($_SESSION["login"], $name);
    header("Location:search.php"); //refresh the page

    }


//carries out the search query
if (isset($_POST['submit'])) {
if (isset($_POST['livesearch'])) {   //priorities the select box over the text box for searching
    $search = $_POST['livesearch'];
    $search = '%'.$search.'%';
}
else {
    $search = $_POST['search'];
    $search = '%' . $search . '%';
}
    //search for when logged in as it works differently if user is logged on. Tt allows adding and filters users who are already in a relationship
    if (isset($_SESSION["login"])) {
        $tempFriend = array();
        $tempOther = array();
        $view->users = $users->fetchSomeUsers($_SESSION["login"], $search);
        foreach($view->users as $person)
            {
                //var_dump($person->getUsername());


                //allocates users to one of two tables based on whether they have already initiated a friendship
                $relation = $users->isRelation($_SESSION["login"],$person->getUsername());
                if ($relation == $person->getUsername())
                {
                    array_push($tempFriend, $person);
                }
                else array_push($tempOther, $person);;
            }
        //var_dump($tempFriend);
        $view->friendish = $tempFriend;
        $view->others = $tempOther;


    }
    //search if nobody is logged in
    else
    {
        $nobody = ''; //It did not like when i used null
        $view->users = $users->fetchSomeUsers($nobody, $search);
    }
}
require('Views/search.phtml');