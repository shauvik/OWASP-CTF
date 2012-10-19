<?php
require_once('../config/config.inc.php');
$challenge = new Challenge();
$array = $BASE_ARRAY;
$array['title']="OWASP Capture the Flag";

$loginpage = "https://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'];
$loginpage = str_replace("index", "login", $loginpage);

if(isset($_SESSION[Challenge::PLAYER])) {
	$array['ranking'] = $challenge->getRank();
	$array['login']= '<a class="white" href="'.$loginpage.'?action=logout">Logout</a>';
} else {
	$array['ranking'] = "You have to login to show your rank";
	$array['login'] = '<a class="white" href="'.$loginpage.'">Login</a>';
}
$challenge->header($array) ?>
<div id="content">
	<div id="big-logo">
		<div id="challenges">
		<?php $challenge->buildChallenges(); ?>
		</div>
	</div>
</div>


<?php CTF::footer(); ?>