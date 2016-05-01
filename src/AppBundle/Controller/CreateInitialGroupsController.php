<?php

/*
Use this controller fro create groups for first time only.
After gropus creates, use another controler to update groups
between seasons.
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Controller\logic\Groups;
use AppBundle\Controller\bbdd\PlayersFacade;


/**

 */
class CreateInitialGroupsController  extends Controller {

    private $groups_created;

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

        $this->groups_created = $group-1;
        return $unasigned_players;
    }



     /**
     
     */
    public function creategroupsAction(Request $request)
    {
        $facade = new PlayersFacade($this->getDoctrine()->getManager());

        // Cambiar esto para otras divisione so para asignar por skill
        $division = 1;

        // Cambiar sto para seleccionarv los jugadores que quiera asignar
        $players = $facade->allPlayers();

        // Cambiar esto para seleccionar el num d ejugadores por grupo
        $unasigned = $this->createGroups($players, $division, Groups::$players_per_group);


        $this->getDoctrine()->getManager()->flush();

        $resp = "<html><body> <p> Players:".count($players)
            ."</p> <p>Playesr_x_group:".Groups::$players_per_group
            ."</p> <p>Groups created:".$this->groups_created
            ."</p> <p>Unasigned players:".$unasigned
            ."</p><br/></body></html>";

        return new Response($resp);

    }

   
}
