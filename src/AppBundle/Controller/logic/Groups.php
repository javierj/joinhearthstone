<?php

namespace AppBundle\Controller\logic;


class Groups
{
	/* el skill 0 se iría a La Forja, que es una división aparte de las demás */
	static $skills = array(
                'Tengo pocas cartas' => 0,
                'Rango 16-20' => 1,
                'Rango 11-15' => 2,
                'Rango 6-10' => 3,
                'Rango 1-5' => 4,
                'Leyenda' => 5
    );

    static $forgue_division = "d";

    static $players_per_group = 8;

	/**
	Change this method to open/closes inscription to a new season
	*/
	public static function isRegistrationOpen() {
        return TRUE;
    }

    /**
    Number of players promoted to a superior division at the end of a season
    */
    public static function promoNumber($division, $group) {
    	if ($division == Groups::$forgue_division) {
    		return 0;
    	}
    	return 2;
    }

        /**
    Number of players droped to an inferior division at the end of a season
    */
    public static function dropNumber($division, $group) {
    	if ($division == Groups::$forgue_division) {
    		return 0;
    	}
    	return 2;
    }

}
