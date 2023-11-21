<?php

require_once ('Database.php');
require_once ('UserData.php');
require_once ('FriendsData.php');
class UserDataSet {
    protected $_dbHandle, $_dbInstance;
        
    public function __construct() {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    public function fetchAllUsers($username, $offset, $limit) {
        //returns all users in the database
        $sqlQuery = "SELECT * FROM user WHERE username != ? LIMIT ?,?;";

        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement

        $statement->bindParam(1,$username);
        $statement->bindParam(2,$offset, PDO::PARAM_INT);
        $statement->bindParam(3,$limit, PDO::PARAM_INT);
        //var_dump($statement);
        $statement->execute(); // execute the PDO statement
        
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new UserData($row);
        }
        return $dataSet;
    }



    public function fetchSomeUsers($username, $string) {
        //search query which appends parameters
        $sqlQuery = "SELECT * FROM user WHERE (Lower(username) LIKE ? AND Lower(username) != ?) OR (LOWER(first_name) LIKE ?) OR (LOWER(last_name) LIKE ?) OR (LOWER(email) LIKE ?) LIMIT 15;";
        //var_dump($sqlQuery);
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement

        //set everything to lower case for more forgiving search
        $lowerCase = strtolower($string);

        $statement->bindParam(1,$lowerCase);
        $statement->bindParam(2,$username);
        $statement->bindParam(3,$lowerCase);
        $statement->bindParam(4,$lowerCase);
        $statement->bindParam(5,$lowerCase);
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new UserData($row);
        }

        return $dataSet;
    }

    public function checkPrimaryKey($pKey)
    {
        //checks username is unique when registering for an account
        $sqlPKeyCheck = "SELECT * From user WHERE username = ?"; // checks that the primary key is unique
        $checkStatement = $this->_dbHandle->prepare($sqlPKeyCheck); // prepare a PDO statement

        $checkStatement->bindParam(1,$pKey);

        $checkStatement->execute(); // execute the PDO statement

        $row = $checkStatement->fetch();

        $pass = false;
        $data = new UserData($row);
        if($data->getUsername() == null)
        {
            $pass = true;
        }

        return $pass;

    }

    public function insertNewUser($username, $email, $password, $first_name, $last_name, $imagePath)
    {
        //inserts new user into database
            $sqlQuery = "INSERT INTO user (username, first_name, last_name, email, password, imagePath, longitude, latitude) values (?,?,?,?,?,?,?,?);";
            $statement = $this->_dbHandle->prepare($sqlQuery); // prepares PDO statement

            $hashPWD = password_hash($password, PASSWORD_DEFAULT);
            $long = 0.0;
            $lat = 0.0;

            $statement->bindParam(1,$username);
            $statement->bindParam(2,$first_name);
            $statement->bindParam(3, $last_name);
            $statement->bindParam(4, $email);
            $statement->bindParam(5,$hashPWD);
            $statement->bindParam(6,$imagePath);
            $statement->bindParam(7,$long);
            $statement->bindParam(8,$lat);


            $statement->execute(); // execute the PDO statement

    }

    public function correctDetails($username, $password)
    {
        //checks username and password are correct
        $sqlQuery = "SELECT * FROM user WHERE username = ?;";
        //echo $sqlQuery;
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement

        $statement->bindParam(1,$username);

        $statement->execute(); // execute the PDO statement
        error_reporting(E_ERROR | E_PARSE); //Warning messages appeared but still functions as intended so suppressed. Fix later if time allows

        $row = $statement->fetch();
        $account = new UserData($row);
        //var_dump($account->getPassword());
        //$row returns false if username wrong so add if not false semester 2
        if (password_verify($password, $account->getPassword()))
        {
            return true;
        }
        else return false;
    }

    public function fetchFriends($username)
    {
        // returns all users who are friends with the current user
        $sqlQuery = "SELECT * FROM user WHERE user.username != ? AND user.username IN (SELECT friend1 from relationships WHERE friend2 = ? AND status =2) OR user.username IN (SELECT friend2 from relationships WHERE friend1 = ? AND status =2) LIMIT 10;";
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement


        $statement->bindParam(1,$username);
        $statement->bindParam(2,$username);
        $statement->bindParam(3,$username);

        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new UserData($row);
        }
        return $dataSet;
    }

    public function fetchPending($username)
    {
        //var_dump($username);
        // Selects other users with a pending relationship with current user
        $sqlQuery = "SELECT * FROM user WHERE user.username != ? AND user.username IN (SELECT friend1 from relationships WHERE friend2 = ? AND status !=2) OR user.username IN (SELECT friend2 from relationships WHERE friend1 = ? AND status !=2) LIMIT 10;";
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement

        $statement->bindParam(1,$username);
        $statement->bindParam(2,$username);
        $statement->bindParam(3,$username);
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new UserData($row);
        }
        //var_dump($dataSet);
        return $dataSet;
    }

    public function fetchSomeRelation($username)
    {
        //fetches users who are at some stage in a virtual relationship with a specified user
        $sqlQuery = "SELECT * FROM user WHERE user.username != ? AND user.username IN (SELECT friend1 from relationships WHERE friend2 = ?) OR user.username IN (SELECT friend2 from relationships WHERE friend1 = ?) LIMIT 10;";
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement

        $statement->bindParam(1,$username);
        $statement->bindParam(2,$username);
        $statement->bindParam(3,$username);

        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new UserData($row);
        }
        return $dataSet;
    }
    public function fetchOthers($username)
    {
        //returns all users who have no relationship with the current user
        $sqlQuery = "SELECT * FROM user WHERE username != ? AND user.username NOT IN (SELECT username FROM user WHERE user.username != ? AND user.username IN (SELECT friend1 from relationships WHERE friend2 = ?) OR user.username IN (SELECT friend2 from relationships WHERE friend1 = ?))LIMIT 10;";
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement

        $statement->bindParam(1,$username);
        $statement->bindParam(2,$username);
        $statement->bindParam(3,$username);
        $statement->bindParam(4,$username);

        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new UserData($row);
        }
        return $dataSet;
    }


    public function isRelation($user1, $user2)
    {
        //checks if two users have a relationship
        $sqlQuery = "SELECT user.username FROM user, relationships WHERE user.username != ? AND user.username=? AND ((relationships.friend1 = ? AND relationships.friend2 = ?) OR (relationships.friend1 = ? AND relationships.friend2 = ?)) GROUP BY user.username;";
        //var_dump($sqlQuery);
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement

        $statement->bindParam(1,$user1);
        $statement->bindParam(2,$user2);
        $statement->bindParam(3,$user1);
        $statement->bindParam(4,$user2);
        $statement->bindParam(5,$user2);
        $statement->bindParam(6,$user1);

        $statement->execute(); // execute the PDO statement


        $output = $statement->fetch();
        //var_dump($output["username"]);
        error_reporting(E_ERROR | E_PARSE); //Warning message appearing because sql statement outputs duplicate data. Suppressed messages because working regardless. Come back later if time allows

        return $output["username"];
    }

    public function setLocation($float1, $float2, $username) {

        $sqlQuery = "UPDATE user SET user.latitude = ?, user.longitude = ? WHERE user.username = ?;";
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        //var_dump($float1);
        //var_dump($float2);
        //var_dump($username);

        $statement->bindParam(1,$float1);
        $statement->bindParam(2,$float2);
        $statement->bindParam(3,$username);

        $statement->execute(); // execute the PDO statement

        $sqlQuery = "SELECT user.longitude FROM user WHERE user.username = '$username';";
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $output = $statement->fetch();
        return $output;
    }
    
    //public function populateTable()
    //{
    //    for ($i = 0; $i <=1000; $i++)
    //    {
    //        $this->insertNewUser('user'.$i, $i.'@x.co.uk', $i, 'John', 'Doe', 'images/default.jpg');
    //    }
    //}

}


