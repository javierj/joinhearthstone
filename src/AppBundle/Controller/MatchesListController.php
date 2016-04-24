<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\PlayerLogin;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

// Tipos de controles para el formulario
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\HttpFoundation\Response;


/**
 * Description of PlayerController
 *
 * @author IWT2
 */
class MatchesListController extends Controller {
     
     

     /**
     
     */
    public function viewAction(Request $request)
    {
        $player_login = new PlayerLogin();
        $player_login->setEmail('Email@a.es');
        $player_login->setPassword('Password');

        // Creación del formulario
        $form = $this->loginForm($player_login);

        // Vmos si ya hemos recibido repsuesta del formulario

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ... perform some action, such as saving the task to the database
            $player = $this->retrievePlayer($player_login);

            //return $this->redirectToRoute('task_success');
            if ($player == null) {
                //return new Response('<html><body>No player<br/></body></html> ');
                 return $this->render('default/msg.html.twig', array(
                    'text' => "No existe un jugador con ese Email / Clave",
                ));
            }
            //return new Response('<html><body>Done! '.$player->getGames()->count().' <br/></body></html> ');

            $games_arr = Array();
            $games = $player->getGames();
            for ($i = 0; $i < count( $games); $i++) {
                $games_arr[$i] = Array('player_a'=>$games[$i]->getPlayerA()->getName(), 
                    'player_a_gameid' => $this->gameidById($games[$i]->getPlayerA()->getId()),
                    'player_b_id'=>$games[$i]->getPlayerB()->getName(), 
                    'player_b_gameid' => $this->gameidById($games[$i]->getPlayerB()->getId()),
                    'date_to_play'=>$games[$i]->getDateToPlay() );
            }
            return $this->render('games/games.html.twig', array('games' => $games_arr));
        }


        return $this->render('games/playerlogin.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    function retrievePlayer($player_login) {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Player');
        return $repository->findOneBy(array('email' => $player_login->getEmail(), 'password' => $player_login->getPassword()));
    }

    function gameidById($player_id) {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Player');
        $player = $repository->findOneBy(array('id' => $player_id));
        return $player->getGameId();
    }

    function loginForm($player_login) {
          // Creación del formulario
        $form = $this->createFormBuilder($player_login)
            ->add('email', EmailType::class, array('label' => 'Email: '))
            ->add('password', PasswordType::class, array('label' => 'Password: '))
            ->add('View Matches', SubmitType::class, array('label' => 'View Matches'))
            ->getForm();
        return $form;
     }
}
