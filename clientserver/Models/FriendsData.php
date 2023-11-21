<?php

class FriendsData {
    protected $idrelationships, $friend1, $friend2, $status;

    public function __construct($dbRow) {
        $this->idrelationships = $dbRow['idrelationships'];
        $this->friend1 = $dbRow['friend1'];
        $this->friend2 = $dbRow['friend2'];
        $this->status = $dbRow['status'];
    }

    public function getIDRelationships()
    {
        return $this->idrelationships;
    }

    public function getFriend1()
    {
        return $this->friend1;
    }

    public function getFriend2()
    {
        return $this->friend2;
    }

    public function getStatus()
    {
        return $this->status;
    }

}