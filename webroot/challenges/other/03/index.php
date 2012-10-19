<?php
require_once('../../../../config/config.inc.php');

$challenge = new Challenge();
$challenge->startChallenge();
$pwd = $challenge->getDictionaryWord();
$token = $challenge->getToken();

$user = "admin";
$sessionhack=base64_encode($user."/".$pwd);
if (isset($_POST['submit'])) {
	$code = util::getPost('password');
	if ($code == $pwd) {
		$challenge->mark();
		CTF::showAchieved();
	}
}
?>

<br/><br/><applet code="Applet1.class" height=60><param name="session" value="<?php echo $sessionhack; ?>" /></applet>
<hr/>
<form autocomplete="off" method="post">
    <input type="hidden" name="action" value="login" />
    <table>
        <tr><td>Code</td><td>:</td><td><input type="text" name="password" /></td></tr>
        <tr><td colspan=2/><td><input type="submit" class="button" name="submit" value="Submit" /></td></tr>
    </table>
</form>

<?php $challenge->stopChallenge(); ?>