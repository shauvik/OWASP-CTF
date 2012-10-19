<?php
require_once('../config/config.inc.php');

$array = $BASE_ARRAY;
$array['title']="OWASP Capture the Flag Scoreboard";
$array['meta']='<meta http-equiv="REFRESH" content="10;scoreboard.php">';
echo CTF::header($array);
echo '<div id="content"><div id="small-logo"><center>';
echo CTF::buildScoreboard();
echo '</center></div></div>';
echo CTF::footer();