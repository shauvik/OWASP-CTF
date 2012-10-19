<?php
/*
 * look at server strings, drop the "HTTP_"  in the request
 */
require_once('../../../../config/config.inc.php');

$challenge = new Challenge();
$challenge->startChallenge();
$pwd = $challenge->getDictionaryWord();
$token = $challenge->getToken();

$forwarded = $_SERVER['HTTP_X_FORWARDED_FOR'];
$useragent = $_SERVER['HTTP_USER_AGENT'];
$via = $_SERVER['HTTP_VIA'];

$failed = "<td align=\"right\" style=\"color: red\">[FAILED]</td>";
$ok = "<td align=\"right\" style=\"color: green\">[OK]</td>";

// adjust, should be contains, not equals for useragent and via
if ($forwarded == "owasp.org" && strpos($useragent,"Commodore64") !== FALSE  &&strpos($via,"nowhere.org") !== FALSE) {
	$challenge->mark();
	CTF::showAchieved();
}

?>
<br/><br/>
<h3>Hey, where are you from?</h3>
<br />
When all the HTTP vars are [OK] you have found the answer!<br /><br /><br />
<center>
    <table>
        <tr><td>Forwarded: (owasp.org)</td><td><?php echo ($forwarded == "owasp.org") ? $ok : $failed; ?></td></tr>
        <tr><td>OS: (Commodore64)</td><td><?php echo (strpos($useragent,"Commodore64") !== FALSE) ? $ok : $failed; ?></td></tr>
        <tr><td>Via: (nowhere.org)</td><td><?php echo (strpos($via,"nowhere.org") !== FALSE) ? $ok : $failed; ?></td></tr>
        <tr><td colspan=2>&nbsp;</td></tr>
    </table>
</center>
<?php $challenge->stopChallenge(); ?>