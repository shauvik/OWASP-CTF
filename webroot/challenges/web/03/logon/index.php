<?php
require_once('../../../../../config/config.inc.php');

$challenge = new Challenge();
$challenge->startChallenge();
$pwd = $challenge->getDictionaryWord();
$token = $challenge->getToken();
$challenge->mark();
CTF::showAchieved();
$challenge->stopChallenge(); ?>