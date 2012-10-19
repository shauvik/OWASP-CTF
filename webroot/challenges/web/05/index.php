<?php
/*
 * sha1 => http://sha1.web-max.ca/
 */
require_once('../../../../config/config.inc.php');

$challenge = new Challenge();
$challenge->startChallenge();
$pwd = $challenge->getDictionaryWord();
$token = $challenge->getToken();

if (isset($_POST['submit'])) {
	$uid = util::getPost('username');
	$pwd = util::getPost('password');
	if ($uid == "admin" && $pwd == $token) {
		$challenge->mark();
		CTF::showAchieved();
	} else {
		CTF::error("Username/password is not correct");
	}
}
?>
Inlognaam = "admin"<br/><br/>
Wachtwoord = "<?php echo sha1($token); ?>"<br/><br/>

<form autocomplete="off" method="post">
    <table>
        <tr><td>Username</td><td>:</td><td><input type="text" name="username" /></td></tr>
        <tr><td>Password</td><td>:</td><td><input type="password" name="password" /></td></tr>
        <tr><td colspan=2/><td><input type="submit" class="button" name="submit" value="Submit" /> <?php $challenge->nextButton(); ?></td></tr>
    </table>
</form>
<?php $challenge->stopChallenge(); ?>