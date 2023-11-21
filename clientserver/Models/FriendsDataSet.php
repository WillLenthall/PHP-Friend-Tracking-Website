<?php
require_once ('UserDataSet.php');
require_once ('Database.php');
require_once ('FriendsData.php');

class FriendsDataSet
{
    protected $_dbHandle, $_dbInstance;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }



    public function createRelationship($friend1, $friend2)
    {
        $sqlQuery = "INSERT INTO relationships (friend1, friend2, status) VALUES (?,?, 1);";
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepares PDO statement

        $statement->bindParam(1,$friend1);
        $statement->bindParam(2,$friend2);

        $statement->execute(); // execute the PDO statement
    }

    public function whoSentReq($user1, $user2) // returns the sender of the friend request
    {
        $sqlQuery = "SELECT * FROM relationships WHERE (? = friend1 OR ? = friend2) AND (? = friend1 OR ? = friend2);";
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepares PDO statement

        $statement->bindParam(1,$user1);
        $statement->bindParam(2,$user1);
        $statement->bindParam(3,$user2);
        $statement->bindParam(4,$user2);

        $statement->execute(); // execute the PDO statement

        $row = $statement->fetch();
        //var_dump($row[1]);
        $friend = $row[1];
        return $friend;
    }

    public function acceptRequest($user1, $user2)
    {
        $sqlQuery = "UPDATE relationships SET status = 2 WHERE friend1=? AND friend2 = ?;";
        //var_dump($sqlQuery);
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepares PDO statement

        $statement->bindParam(1,$user2);
        $statement->bindParam(2,$user1);

        $statement->execute(); // execute the PDO statement
    }

    public function declineRequest($user1, $user2)
    {
        $sqlQuery = "DELETE FROM relationships WHERE friend1='$user2' AND friend2 = '$user1';";
        //var_dump($sqlQuery);
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepares PDO statement
        $statement->execute(); // execute the PDO statement
    }



}