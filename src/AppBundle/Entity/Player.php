<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

// Validaciones
use Symfony\Component\Validator\Constraints as Assert;
// ORM
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use AppBundle\Entity\Passwd;


/**
 * Description of Player
 *
 * @ORM\Entity
 * @ORM\Table(name="player")
 */
class Player {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    protected $name;
    /**
     * name#battle_tag
     * @ORM\Column(type="string", length=50)
     * @Assert\Regex(
     *     pattern="/[a-zA-Z]+#[0-9]{4}/",
     *     message="Your battletag should be user#number"
     * )
     */
    protected $gameId;
    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    protected $email;
    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\NotBlank()
     */
    protected $password;
     /**
      * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     */
    protected $country;
   /**
    Gameskill used to determine the begining division/groups for players.
    * 0 - Llevo poco tiempo / Tengo pocas cartas
      1 - Rango 16 - 20 en ladder
      2 - Tango 11 - 15
      3 - Rango 6 - 10
      4 - Rango 1-5
      5 - Leyenda
      * @ORM\Column(type="integer")
    */ 
    protected $gameskill;

     /**
      * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     */
    protected $division;
        /**
     * @ORM\Column(type="string", length=100)
     */
    protected $groupname;
     /**
     * @ORM\Column(type="integer")
     */
    protected $wins;
     /**
     * @ORM\Column(type="integer")
     */
    protected $loses;
    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $creation_date;
    /**
     * @ORM\OneToMany(targetEntity="Game", mappedBy="player_a")
     */
    protected $games_a;
    /**
     * @ORM\OneToMany(targetEntity="Game", mappedBy="player_b")
     */
    protected $games_b;
    

    function __construct() {
        $this->gameskill = 0;
        $this->setGroupname('Default');
        $this->setWins(0);
       $this->setLoses(0);
       $this->setCreationDate(date('d/m/Y h:i:s a'));
       $this->games_a = new ArrayCollection();
       $this->games_b = new ArrayCollection();
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

    function getGameskill() {
        return $this->gameskill;
    }

    function getGroupname() {
        return $this->groupname;
    }

    function getDivision() {
        return $this->division;
    }

    function getWins() {
        return $this->wins;
    }

    function getLoses() {
        return $this->loses;
    }

    function getGames() {
        $games = new ArrayCollection();
        for ($i = 0; $i < count( $this->games_a); $i++) {
            $games->add($this->games_a[$i]);
        }
        for ($i = 0; $i < count( $this->games_b); $i++) {
            $games->add($this->games_b[$i]);
        }
 
        return $games;
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

    /** Encrypt passwd */
    function encryptPasswd() {
        $this->setPassword(Passwd::encrypt($this->getPassword()));
    }

    function setPassword($p) {
        $this->password = $p;
    }

    function setGameskill($param) {
        $this->gameskill = $param;
    }

    function setDivision($param) {
        $this->division = $param;
    }

    /**
    En verdad es el id del grupo, por ejemplo 1, 2, 3, etc.
    */
    function setGroupname($param) {
        $this->groupname = $param;
    }

    function setCountry($param) {
        $this->country = $param;
    }

    function setWins($wins) {
        $this->wins = $wins;
    }

    function setLoses($loses) {
        $this->loses = $loses;
    }

    function setCreationDate($date) {
        $this->creation_date = $date;
    }
    
    function getCreationDate() {
        return $this->creation_date;
    }

    public function addWins($wins) {
        $this->wins += $wins;
    }

    public function addLoses($loses) {
        $this->loses += $loses;
    }
    
}
