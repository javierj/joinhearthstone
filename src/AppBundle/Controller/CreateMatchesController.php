<?php

/*
Use this controller to create matches after create the groups with the players.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\GameResultForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;

use AppBundle\Controller\logic\Groups;
use AppBundle\Controller\bbdd\PlayersFacade;


/**

 */
class CreateMatchesController  extends Controller {

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



    public function creategamesAction(Request $request)
    {
        $facade = new PlayersFacade($this->getDoctrine());

        // Cambiar esto para otras divisione so para asignar por skill
        $division = 1;

        // Obtener jugadores
        

        // Obtener fechas
        
        $initial_date="01/01/2016";
        $increment =  [7,];
        // Generar calendario


        $this->getDoctrine()->getManager()->flush();

        $resp = "<html><body> <p> Players:".count($players)
            ."</p> <p>Playesr_x_group:".Groups::$players_per_group
            ."</p> <p>Groups created:".$this->groups_created
            ."</p> <p>Unasigned players:".$unasigned
            ."</p><br/></body></html>";

        return new Response($resp);

    }

   
}


   class ArrayOfSets {
    
    private $keys;
    private $values;

    function __construct() {
        $this->sets = array();
        $this->values = array();
    }

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


