<?php

namespace AppBundle\Entity;

class Passwd {

public static function encrypt($plain_passwd) {
	return md5($plain_passwd);
}

public static function equals($encrypted_passwd, $plain_passwd) {
	return md5($plain_passwd) == $encrypted_passwd;	
}

}