<?php

namespace AppBundle\Entity;

// Validaciones
use Symfony\Component\Validator\Constraints as Assert;

use AppBundle\Entity\Passwd;


/**
 * Match result submittion

 */
class GameResultForm {

    /**
     * @Assert\NotBlank()
      * @Assert\Regex(
     *     pattern="/[a-zA-Z]+#[0-9]{4}/",
     *     message="Your battletag should be user#number"
     * )
     */
    protected $gameIdPlayerA;
    /**
     * @Assert\NotBlank()
     */
    protected $winsPlayerA;
    /**
     
     */
    protected $losesPlayerA;
    
    /**
     * @Assert\NotBlank()
      * @Assert\Regex(
     *     pattern="/[a-zA-Z]+#[0-9]{4}/",
     *     message="Your battletag should be user#number"
     * )
     */
    protected $gameIdPlayerB;
    /**
     * @Assert\NotBlank()
     */
    protected $winsPlayerB;
    /**
    
     */
    protected $loses_playerB;

    function __construct() {
    }

    function getGameIdPlayerA() {
        return $this->gameIdPlayerA;
    }

    function getWinsPlayerA() {
        return $this->winsPlayerA;
    }

    
    function getLosesPlayerA() {
        return $this->losesplayerA;
    }


    function getGameIdPlayerB() {
        return $this->gameIdPlayerB;
    }

    function getWinsPlayerB() {
        return $this->winsPlayerB;
    }

    
    function getLosesPlayerB() {
        return $this->losesPlayerB;
    }

    function setGameIdPlayerA($param) {
        $this->gameIdPlayerA = $param;
    }

    function setWinsPlayerA($param) {
        $this->winsPlayerA = $param;
    }

    
    function setLosesPlayerA($param) {
        $this->losesplayerA=$param;
    }


    function setGameIdPlayerB($param) {
        $this->gameIdPlayerB = $param;
    }

    function setWinsPlayerB($param) {
        $this->winsPlayerB = $param;
    }

    
    function setLosesPlayerB($param) {
        $this->losesPlayerB = $param;
    }

}
