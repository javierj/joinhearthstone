<?php

namespace AppBundle\Controller\bbdd;

use AppBundle\Entity\Player;

class PlayersFacade 
{
	private $em;

    function __construct($doctrineManager) {
    	$this->em = $doctrineManager;
    }

    /*
	* return: array qith names of th diferent divisions
    */
    function divisionsName() {
    	$query = $this->em->createQuery('SELECT DISTINCT u.division FROM AppBundle\Entity\Player u');
        $divisions = $query->getResult();
        return $divisions;
    }


    /**
    All groups in the DDBB
    */
	function allGroups() {
    	 $query = $this->em->createQuery('SELECT DISTINCT u.groupname FROM AppBundle\Entity\Player u');
         $groups = $query->getResult();
        return $groups;
    }

    /**
    All groups of a division
    */
    function groupsName($division) {
         $query = $this->em->createQuery('SELECT DISTINCT u.groupname 
                                FROM AppBundle\Entity\Player u
                                WHERE u.division = :division'
                                )->setParameter('division', $division);
         $groups = $query->getResult();
        return $groups;
    }

    /**
    Sorted by wins
    */
    function playersByDivisionAndGroup($division, $group) {
    	 $where = array('division' => $division, 'groupname' => $group);
                $users = $this->em->getRepository('AppBundle\Entity\Player')->findBy($where, array('wins' => 'DESC'));
        return $users;
    }

    function allPlayers() {
        return $this->em->getRepository('AppBundle\Entity\Player')->findAll();
    }

}
