<?php


namespace AppBundle\Entity;

// Validaciones
use Symfony\Component\Validator\Constraints as Assert;
// ORM
use Doctrine\ORM\Mapping as ORM;


/**
 * Description of a game between two players
 *
 * @ORM\Entity
 * @ORM\Table(name="game")
 */
class Game {

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
    protected $division;
    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     */
    protected $group_id;
     /**
     * @ORM\ManyToOne(targetEntity="Player", inversedBy="games_a")
     * @ORM\JoinColumn(name="player_a_id", referencedColumnName="id")
     */
    protected $player_a;
    /**
     * @ORM\ManyToOne(targetEntity="Player", inversedBy="games_b")
     * @ORM\JoinColumn(name="player_b_id", referencedColumnName="id")
     */
    protected $player_b;
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
    protected $date_to_play;
    protected $played;
    

    function __construct() {
       $this->setWins(0);
       $this->setLoses(0);
       $this->played = false;
    }


    function getId() {
        return $this->id;
    }

    function getDateToPlay() {
        return $this->date_to_play;
    }


    function getPlayerA() {
        return $this->player_a;
    }

    function getPlayerB() {
        return $this->player_b;
    }

    function getGroupId() {
        return $this->group_id;
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


    function setDivision($param) {
        $this->division = $param;
    }

    function setGroupId($param) {
        $this->group_id = $param;
    }

    function setWins($wins) {
        $this->wins = $wins;
    }

    function setLoses($loses) {
        $this->loses = $loses;
    }


    function setDateToPlay($date) {
        $this->date_to_play = $date;
    }

    /* The whole object of the player */
    function setPlayerA($player_a) {
        $this->player_a = $player_a;
    }

    function setPlayerB($player_b) {
        $this->player_b = $player_b;
    }

}
