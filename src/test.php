<?php

namespace Tests\AppBundle\Util;


class ScheludeCreatorTest extends \PHPUnit_Framework_TestCase
{
    public function testNoGroups()
    {
    	$groups = $this->createSchedule(array());
        $this->assertEquals(0, count($groups));

        $players = array(new Player());
		$groups = $this->createSchedule($players);
        $this->assertEquals(0, count($groups));

    }


    public function testOneMatch()
    {
    	$players = array(new Player(), new Player());
    	
    	
    	$res = $this->createSchedule($players);
        $this->assertEquals(1, count($res));
    }

    public function test4Matches()
    {
    	$players = array(new Player(), new Player(), new Player(), new Player());
  		
    	$res = $this->createSchedule($players);
        $this->assertEquals(6, count($res));
    }

    public function testAssignDates()
    {
    	$players = array(new Player(), new Player(), new Player(), new Player());
    	$dates = $this->createDates(count($players));
    	$dateSet = new ArrayOfSets();
    	$dateSet->addArrayKeys($dates);
  		
    	$res = $this->createSchedule($players, $dateSet);
        $this->assertEquals("01/01/2016", $res[0]->getDateToPlay());
        $this->assertEquals("08/01/2016", $res[1]->getDateToPlay());
        $this->assertEquals("15/01/2016", $res[2]->getDateToPlay()); // Player 1 vs Player 3 
        $this->assertEquals("15/01/2016", $res[3]->getDateToPlay()); // Player 2 vs Player 3
    }


    //----- Creation fo dates testing

    function testNoDates() {
    	$dates = $this->createDates(1);
    	$this->assertEquals(0, count($dates));
    }

    function testInitialDate() {
    	$dates = $this->createDates(2);
    	$this->assertEquals(1, count($dates));
    	$this->assertEquals("01/01/2016", $dates[0]);
    }

    function testThreeDatesDate() {
    	$dates = $this->createDates(4);
    	$this->assertEquals("01/01/2016", $dates[0]);
    	$this->assertEquals("08/01/2016", $dates[1]);
    	$this->assertEquals("15/01/2016", $dates[2]);
    }

    function test2IncrementsDate() {
    	$dates = $this->createDates(4, "01/01/2016", [2, 3]);
    	$this->assertEquals("01/01/2016", $dates[0]);
    	$this->assertEquals("03/01/2016", $dates[1]);
    	$this->assertEquals("06/01/2016", $dates[2]);
    }

    //------

    function testCreateArrayOfSets() {
    	$dates = $this->createDates(4);
    	$aos = new ArrayOfSets();
    	$aos->addArrayKeys($dates);

    	$this->assertEquals($aos->size(), count($dates));
    }

    function testGesDate() {
    	$dates = $this->createDates(4);
    	$aos = new ArrayOfSets();
    	$aos->addArrayKeys($dates);

    	$res = $aos->assignDate("a", "b");
    	$this->assertEquals($dates[0], $res);
    	$this->assertEquals(2, $aos->valueSize(0));

    }

    function testReoeatedDate() {
    	$dates = $this->createDates(4);
    	$aos = new ArrayOfSets();
    	$aos->addArrayKeys($dates);

    	$res = $aos->assignDate("a", "b");
    	$this->assertEquals("01/01/2016", $res);
    	$res = $aos->assignDate("a", "b");
    	$this->assertEquals("08/01/2016", $res);
    }


    //---------------------------------------------------

    /**
    	Creates an array of game objects using players
    	$players must be all players in the same group and division
    	Initialdate: mm/dd/yyyy
    **/
    function createSchedule($players, $dateSet = NULL) {
    	$games = array();
    	$games_index = 0;

    	for($index_a = 0; $index_a < (count($players) -1); $index_a++) {
    		$player_a = $players[$index_a];
    		for($index_b = ($index_a+1); $index_b < (count($players)); $index_b++) {
    			$player_b = $players[$index_b];
    			$game = new Game();
    			$game->setDivision($player_a->getDivision());
    			$game->setGroupId($player_a->getGroupname());
    			if ($dateSet != NULL) {
    				$game->setDateToPlay($dateSet->assignDate($player_a->getId(), $player_b->getId()));
    			} else {
    				$game->setDateToPlay('Unset');
    			}
    			$game->setPlayerA($player_a->getId());
    			$game->setPlayerB($player_b->getId());
    			$games[$games_index] = $game;
    			$games_index++;
    		}
    

    	}
    	return $games;
    }

    function createDates($player_number, $initial_date="01/01/2016", $increment =  [7,]) {
    	$initial_timestamp = strtotime($initial_date);
    	$dates = array();
    	$d_index = 0;
    	$i_index = 0;
    	$days = 0;
    	for($i = 0; $i < ($player_number -1); $i++) {
    		$date_string = "+".$days." day";
    		$new_timestamp = strtotime($date_string, $initial_timestamp);
    		$new_date = date("d/m/Y", $new_timestamp);
    		$dates[$d_index++] = $new_date;
    		$days += $increment[$i_index++];
    		if ($i_index == count($increment)) {
    			$i_index = 0;
    		}
    	}
    	return $dates;
    }


 
}


class ArrayOfSets {
	
	private $keys;
	private $values;

	function __construct() {
    	$this->sets = array();
    	$this->values = array();
    	
    }
/*
    public function addSet($key) {
    	$this->sets[$this->index] = $key;
    	$this->values[$this->index] = array();
    }
*/

    public function addArrayKeys($keys) {
    	for ($i = 0; $i < count($keys); $i++) {
    		$this->sets[$i] = $keys[$i];
    		$this->values[$i] = array();
    	}
    }

    public function assignDate($id_a, $id_b) {
    	$date = NULL;
    	for($i = 0; $i < count($this->sets); $i++) {
    		if ( (in_array($id_a, $this->values[$i]) == FALSE) && (in_array($id_b, $this->values[$i]) == FALSE) ) {
    			$date = $this->sets[$i];
    			$this->values[$i][count($this->values[$i])] = $id_a;
    			$this->values[$i][count($this->values[$i])] = $id_b;
    			break;
    		}
    	}
    	return $date;
    	
    }

    public function size() {
    	return count($this->sets);
    }

    public function valueSize($i) {
    	return count($this->values[$i]);
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

//--------------------------------------

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
      * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     */
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
        $this->setGroupId('No');
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


    function setDivision($param) {
        $this->division = $param;
    }

    function setGroupId($param) {
        $this->groupId = $param;
    }

    function setWins($wins) {
        $this->wins = $wins;
    }

    function setLoses($loses) {
        $this->loses = $loses;
    }

    /* Change */
    function setDateToPlay($date) {
        $this->date_to_play = $date;
    }
    
    /* Deleted
    function getCreationDate() {
        return $this->creation_date;
    }*/

    /* Id of the player */
    function setPlayerA($player_a) {
        $this->player_a = $player_a;
    }

    function setPlayerB() {
        return $this->player_b;
    }

}
