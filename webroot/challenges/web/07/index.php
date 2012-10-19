<?php
/*
 * solution: add header X_FORWARDED_FOR: 192.168.102.123
 */
require_once('../../../../config/config.inc.php');

$challenge = new Challenge();
$challenge->startChallenge();
$pwd = $challenge->getDictionaryWord();
$token = $challenge->getToken();

$tmp= substr($token, strlen($token)-8);
$ip = hexdec(substr($tmp, 0,2)).".".hexdec(substr($tmp,2,2)).".".hexdec(substr($tmp,4,2)).".".hexdec(substr($tmp,6,2));

echo "<br/><br/><h3>Login information.</h3><br/>";
if (util::getIP() != $ip) {
	CTF::error( "Not allowed. Access only allowed from ipaddress <i><b>$ip</b></i>.");
} else {
	$challenge->mark();
	CTF::showAchieved();
}
$challenge->stopChallenge(); ?>