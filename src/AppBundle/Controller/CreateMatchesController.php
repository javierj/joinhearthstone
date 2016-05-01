<?php

/*
Use this controller to create matches after create the groups with the players.
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Controller\logic\Groups;
use AppBundle\Controller\bbdd\PlayersFacade;

use AppBundle\Entity\Game;


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
                $game->setPlayerA($player_a);
                $game->setPlayerB($player_b);
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



    public function creatematchesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $facade = new PlayersFacade($em);

        // Cambiar esto para otras divisione o para asignar por skill
        $division = 1; /*$facade->divisionsName();*/

        // Obtener fechas
        $initial_date=Groups::$initial_date;
        $increment =  Groups::$increment;

        // Obtener jugadores
        $groups = $facade->groupsName($division);
        foreach ($groups as $g) {
            $players = $facade->playersByDivisionAndGroup($division, $g);
            $dates = $this->createDates(count($players));
            $dateSet = new ArrayOfSets();
            $dateSet->addArrayKeys($dates);

            $matches = $this->createSchedule($players, $dateSet);
        }

        foreach($matches as $game) {
            $em->persist($game);
        }
        $em->flush();

        $resp = "<html><body> <p> Initial date:".$initial_date
            ."</p> <p>Dates:".count($dates)
            ."</p> <p>Players in last group:".count($players)
            ."</p> <p>Matches created:".count($matches)
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

    /* A borrar */
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


