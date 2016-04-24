<?php

namespace Tests\AppBundle\Util;


class CalculatorTest extends \PHPUnit_Framework_TestCase
{
    public function testNoPlayers()
    {
    	$res = $this->createGroups(array(), 1);
        $this->assertEquals(0, $res);
    }


    public function testAllPlayersInGroup()
    {
    	$players = array(new Player(), new Player());
    	$this->assertEquals(2, count($players));
    	
    	$res = $this->createGroups($players, 1, 1);
        $this->assertEquals(1, $players[0]->getGroupname());
        $this->assertEquals(1, $players[0]->getDivision());
        $this->assertEquals(2, $players[1]->getGroupname());
        $this->assertEquals(1, $players[1]->getDivision());
        $this->assertEquals(0, $res);
    }

    public function testRemainingPlayersInNewGroup()
    {
    	$players = array(new Player(), new Player(), new Player());
  		$division = 1;
    	
    	/* Pedimo 1 grupo de 2,pero el jugador que se queda fuera se mete en el grupo */
    	$res = $this->createGroups($players, $division, 2);
        $this->assertEquals(0, $res);
        $this->assertEquals(1, $players[0]->getGroupname());
        $this->assertEquals(1, $players[1]->getGroupname());
        $this->assertEquals(2, $players[2]->getGroupname());
    }

    public function testForceGroup()
    {
    	$players = array(new Player(), new Player(), new Player(), new Player());
  		$division = 1;
    	
    	/* Pedimo 1 grupo de 2,pero el jugador que se queda fuera se mete en el grupo */
    	$res = $this->createGroups($players, $division, 3);
        $this->assertEquals(0, $res);
        $this->assertEquals(1, $players[0]->getGroupname());
        $this->assertEquals(1, $players[1]->getGroupname());
        $this->assertEquals(1, $players[2]->getGroupname()); 
        $this->assertEquals(1, $players[3]->getGroupname()); // Forced group
    }


    public function testFourGroup()
    {
    	$players = array(new Player(), new Player(), new Player(), new Player());
  		$division = 1;
    	
    	/* Pedimo 1 grupo de 2,pero el jugador que se queda fuera se mete en el grupo */
    	$res = $this->createGroups($players, $division, 8);
        $this->assertEquals(0, $res);
        $this->assertEquals(1, $players[0]->getGroupname());
        $this->assertEquals(2, $players[1]->getGroupname());
        $this->assertEquals(3, $players[2]->getGroupname()); 
        $this->assertEquals(4, $players[3]->getGroupname()); 
    }


    //---------------------------------------------------

    function createGroups($players, $division, $players_per_group = 8) {

        $group = 1;
        // Tomar este valor de Groups
        //$players_per_group = 8;

        $enought_players = count($players) >= $players_per_group;
        $player_index = 0;
        $total_players = count($players);
        $unasigned_players = count($players);

        while($enought_players) {

            for($i = 0; $i < $players_per_group; $i++) {
                
                $p = $players[$player_index];
                $player_index++;
                $p->setGroupname($group);
                $p->setDivision($division);
            }
            $group++;
            $unasigned_players = $total_players - $player_index;
            $enought_players = ($unasigned_players >= $players_per_group);
        }

        // Asignamos jugadores en los grupos restantes.
        if ($unasigned_players >= ($players_per_group -1)) {
        	 for($i = 0; $i < $unasigned_players; $i++) {
                $p = $players[$player_index];
                $player_index++;
                $p->setGroupname($group);
                $p->setDivision($division);
            }
            $unasigned_players = 0;
        } else {
        	$group = 1;
        	while($unasigned_players > 0) {
        		$p = $players[$player_index];
                $player_index++;
                $p->setGroupname($group);
                $p->setDivision($division);
                $group++;
                $unasigned_players--;
        	} 	
        }

        return $unasigned_players;
    }

}

    //--------------------------------------

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
     * @ORM\Column(type="string", length=30)
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

    /** New !!! **/
    function getDivision() {
        return $this->division;
    }

    function getGroupname() {
        return $this->groupname;
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

