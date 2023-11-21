<?php

class UserData implements JsonSerializable {
    
    protected $_username, $_firstName, $_lastName, $email, $password, $imgPath, $longitude, $latitude;
    
    public function __construct($dbRow) {
        $this->_username = $dbRow['username'];
        $this->_firstName = $dbRow['first_name'];
        $this->_lastName = $dbRow['last_name'];
        $this->email = $dbRow['email'];
        $this->password = $dbRow['password'];
        $this->imgPath = $dbRow['imagePath'];
        $this->longitude = $dbRow['longitude'];
        $this->latitude = $dbRow['latitude'];
    }

    public function getUsername() {
        return $this->_username;
    }
   
    public function getFirstName() {
       return $this->_firstName;
    }
    
    public function getLastName() {
       return $this->_lastName;
    }
    
    public function getEmail() {
       return $this->email;
    }
    
    public function getPassword() {
       return $this->password;
    }

    public function getImgPath() {
        return $this->imgPath;
    }

    public function getLongitude() {
        return $this->longitude;
    }

    public function getLatitude() {
        return $this->latitude;
    }

    public function jsonSerialize()
    {
        return [
        '_username'=>$this->_username,
//        '_firstName'=>$this->_firstName,
//        '_lastName'=>$this->_lastName,
//        'email'=>$this->email,
        ];
    }
}


