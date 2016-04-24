<?php

/*

 */

namespace AppBundle\Controller;

use AppBundle\Entity\GameResultForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

// Tipos de controles para el formulario
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\HttpFoundation\Response;


/**

 */
class SubmitGameController  extends Controller {

    function submitForm($entity) {
        return $this->createFormBuilder($entity)
            ->add('gameIdPlayerA', TextType::class, array('label' => 'Player A Battle#Tag: '))
            ->add('winsPlayerA', IntegerType::class, array('label' => 'Wins Player A: '))
            ->add('gameIdPlayerB', TextType::class, array('label' => 'Player B Battle#Tag: '))
            ->add('winsPlayerB', IntegerType::class, array('label' => 'Wins Player B: '))
            ->add('submitGame', SubmitType::class, array('label' => 'Submit Game'))
            ->getForm();
        
    }

     /**
     
     */
    public function submitgameAction(Request $request)
    {
        $match_result = new GameResultForm();

        // Creación del formulario
        $form = $this->submitForm($match_result);

        // Vmos si ya hemos recibido repsuesta del formulario

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Update players:
            $em = $this->getDoctrine()->getManager();
            $players_repo = $em->getRepository('AppBundle:Player');

            $player_a = $players_repo->findOneBy(array('gameId' => $match_result->getGameIdPlayerA(),));
            if ($player_a == NULL) {
                 return $this->render('default/msg.html.twig', array(
                'text' => "No existe este battletag: ".$match_result->getGameIdPlayerA(),
            ));
            }
            $player_a->addWins($match_result->getWinsPlayerA());
            $player_a->addLoses($match_result->getWinsPlayerB());
        

            // Do the same with player B
            $player_b = $players_repo->findOneBy(array('gameId' => $match_result->getGameIdPlayerB(),));
             if ($player_b == NULL) {
                 return $this->render('default/msg.html.twig', array(
                'text' => "No existe este battletag: ".$match_result->getGameIdPlayerB(),
            ));
            }
            $player_b->addWins($match_result->getWinsPlayerB());
            $player_b->addLoses($match_result->getWinsPlayerA());

            // Retrive the match object and update it.
            $games_repo = $em->getRepository('AppBundle:Game');
            $game = $games_repo->findOneBy(array('player_a' => $player_a->getId(), 'player_b'=>$player_b->getId()));
            $game->setWins($match_result->getWinsPlayerA());
            $game->setLosess($match_result->getWinsPlayerB());

            $em->flush();


            return $this->render('default/msg.html.twig', array(
                'text' => "Partido registrado",
            ));
        }


        return $this->render('management/submitmatch.html.twig', array(
            'form' => $form->createView(),
        ));
    }

   
}
