<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Player;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;

use AppBundle\Controller\bbdd\PlayersFacade;
use AppBundle\Controller\logic\Groups;

/**
 * Description of PlayerController
 *
 * @author IWT2
 */
class SeasonOverviewController extends Controller {
     /**
     
     */
    public function viewAction(Request $request)
    {  
        // 1. Reuperar todos los grupos
        // 2. para cada grupo, jugadores ordenados

        $facade = new PlayersFacade($this->getDoctrine()->getManager());

        $divisions = $facade->divisionsName();
    
        $i_divisions = 0;
        $plain_divisions = array();
       
        foreach ($divisions as $d) {
            $groups = $facade->allGroups();
            //$groups = $facade->groupsName($d); <-- Este es el que hay que usar, pero no me funciona

            $plain_groups = array();
            $i_groups = 0;
            foreach ($groups as $g) {
                $users = $facade->playersByDivisionAndGroup($d['division'], $g['groupname']);

//return new Response('<html><body>Done!'.count($users).'<br/></body></html> '); 
                $players_info = array();
                $i_players = 0;
                foreach($users as $u) {
                    $players_info[$i_players] = array('name' => $u->getName(), 'wins' => $u->getWins(), 'loses'=>$u->getLoses(), 'promotion' =>'--');
                    $i_players++;
                }

                //$players_info[0]['promotion'] = "Asciende";

                // Cambiamos las propomociones
                $top = min(Groups::promoNumber($d, $g), count($players_info));
                for ($i = 0; $i < $top; $i++) {
                    $players_info[$i]['promotion'] = "Asciende";
                }

                $top = min(Groups::dropNumber($d, $g), count($players_info));
                for ($i = 0; $i < $top; $i++) {
                    $players_info[count($players_info)-$i-1]['promotion'] = "Desciende";
                }

                $plain_groups[$i_groups] = array('name' => $g['groupname'], 'players' =>$players_info);
                $i_players++;    
            }
            $plain_divisions[$i_divisions]= array('name'=>$d['division'], 'groups'=>$plain_groups);
            $i_divisions++;

        }

        // Pasar user a un array más plano para verlo en la plantilla.


        //return new Response('<html><body>Done!<br/></body></html> '); 
        $cache = array('divisions' =>$plain_divisions ); 
        return $this->render('games/clasification.html.twig',  $cache);
    
    }
}
