<?php

namespace AppBundle\Entity;

// Validaciones
use Symfony\Component\Validator\Constraints as Assert;

use AppBundle\Entity\Passwd;


/**
 * Login of Player

 */
class PlayerLogin {

    protected $id;

    protected $name;
    protected $gameId;
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    protected $email;
    /**
     * @Assert\NotBlank()
     */
    protected $password;
    protected $country;
    protected $division;
    protected $groupId;
    

    function __construct() {
    }


    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getGameId() {
        return $this->gameId;
    }

    function getEmail() {
        return $this->email;
    }

    function getPassword() {
        return $this->password;
    }

    function getCountry() {
        return $this->country;
    }

    function getGroupId() {
        return $this->groupId;
    }


    function getWins() {
        return $this->wins;
    }

    function getLoses() {
        return $this->loses;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setGameId($game_id) {
        $this->gameId = $game_id;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setPassword($p) {
        $this->password = $p;
        //$this->password = Passwd::encrypt($p);
    }

    function setDivision($param) {
        $this->division = $param;
    }

    function setGroupId($param) {
        $this->groupId = $param;
    }

    function setCountry($param) {
        $this->country = $param;
    }

}
