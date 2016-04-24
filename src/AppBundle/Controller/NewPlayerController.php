<?php

/*
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Player;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

// Tipos de controles para el formulario
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\HttpFoundation\Response;

use AppBundle\Controller\logic\Groups;



/**
 * Description of PlayerController
 *
 * @author IWT2
 */
class NewPlayerController extends Controller {
     /**
     
     */

     function createPlayer() {
         // Player inicial a completar en el formulario
        $player = new Player();
        $player->setName('Name');
        $player->setEmail('Email@a.es');
        $player->setGameId('Full game id user#id');
        $player->setPassword('Password');
        $player->setDivision('Unset');
        $player->setCountry('Spain');

        return $player;
    }

    function playerForm($player) {
        $form = $this->createFormBuilder($player)
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add('gameId', TextType::class)
            ->add('password', PasswordType::class)
            // Cambiar el array de skills y usar el de Groups
            ->add('gameskill', ChoiceType::class, array('choices'  => array(
                'Tengo pocas cartas' => 0,
                'Rango 16-20' => 1,
                'Rango 11-15' => 2,
                'Rango 6-10' => 3,
                'Rango 1-5' => 4,
                'Leyenda' => 5
    )))
            ->add('acept_rules', CheckboxType::class, array('label' => 'He leido y acepto el reglamento', 'mapped' => false))
            ->add('save', SubmitType::class, array('label' => 'Add Player'))
            ->getForm();

        return $form;
    }

    public function store($player) {
        $player->encryptPasswd();
        $em = $this->getDoctrine()->getManager();
            $em->persist($player);
            $em->flush();
    }

    /**
    Returns tru if the email is in the playes' database
    */
    public function existEmail($form) {
        $email = $form->get('email')->getData();
        $repository = $this->getDoctrine()->getRepository('AppBundle:Player');
        return ($repository->findOneBy(array('email' => $email)) != null);
    }

    public function newAction(Request $request)
    {

        // Verificamos si elr egsitro está abierto
        if (Groups::isRegistrationOpen() == FALSE) {
            return $this->render('default/msg.html.twig', array(
                'text' => "Las inscripciones ya están cerradas",
            ));
               
        }

        // Player inicial a completar en el formulario
        $player = $this->createPlayer();

        // Creación del formulario.
        $form = $this->playerForm($player);

        // Vmos si ya hemos recibido repsuesta del formulario
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->existEmail($form)) {
                return $this->render('default/msg.html.twig', array(
                    'text' => "Ese email ya existe!",
                ));
            }
           
            // ... perform some action, such as saving the task to the database
            $this->store($player);
           
            //return $this->redirectToRoute('task_success');
             return $this->render('default/msg.html.twig', array(
                    'text' => "Te has inscrito correctamente.",
                ));
               
        }


        return $this->render('games/newplayer.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}

/*
otas sobre la RE:

gameid = "rincew#1234"

ex="[a-zA-Z]+"

re.search(ex, gameid)
Out[5]: <_sre.SRE_Match object; span=(0, 6), match='rincew'>

ex="[a-zA-Z]+#"

re.search(ex, gameid)
Out[7]: <_sre.SRE_Match object; span=(0, 7), match='rincew#'>

ex="[a-zA-Z]+#[0-9]{4}"

re.search(ex, gameid)
Out[9]: <_sre.SRE_Match object; span=(0, 11), match='rincew#1234'>

re.search(ex, "rincew")

re.search(ex, "rincew#12")

re.search(ex, "rincew1234")

*/
