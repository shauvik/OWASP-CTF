<?php
require_once('../../../../config/config.inc.php');

$challenge = new Challenge();
$challenge->startChallenge();
$word = $challenge->getDictionaryWord();
$token = $challenge->getToken();

$uid = util::getCookie('username');
$pwd = util::getCookie('password');
if ($uid == "administratodle" && $pwd == $word) {
	$challenge->mark();
	CTF::showAchieved();
}
?>
<h3>Cookie informatie op dit moment.</h3>
<br/>
$_COOKIE["username"] = <?= $uid; ?><br/>
$_COOKIE["password"] = <?= $pwd; ?><br/><br/>


<h3>Inlog informatie.</h3>
<br/>
username = "administratodle"<br/>
password = "<?= $word; ?>"<br/>
<?php $challenge->stopChallenge(); ?>