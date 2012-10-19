<?php
require_once('../../../../config/config.inc.php');

$challenge = new Challenge();
$challenge->startChallenge();
$pwd = $challenge->getDictionaryWord();
$token = $challenge->getToken();
?>
<br/>Find the login system.
<?php $challenge->stopChallenge(); ?>