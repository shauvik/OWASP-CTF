<?php
require_once('../../../../config/config.inc.php');

$challenge = new Challenge();
$challenge->startChallenge();
$pwd = $challenge->getDictionaryWord();
$token = $challenge->getToken();


if (isset($_POST['submit'])) {
	$uid = util::getPost('username');
	$pwd = util::getPost('password');
	if (($uid == "admin" && $pwd == "admin") || ($uid == "root" && $pwd == "toor") || ($uid == "guest" && $pwd == "guest")) {
		$challenge->mark();
		CTF::showAchieved();
	} else {
		CTF::error("Username/password is not correct");
	}
}
?>
<form autocomplete="off" method="post">
    <table>
        <tr><td>Username</td><td>:</td><td><input type="text" name="username" /></td></tr>
        <tr><td>Password</td><td>:</td><td><input type="password" name="password" /></td></tr>
        <tr><td colspan=2/><td><input type="submit" class="button" name="submit" value="Submit" /> <?php $challenge->nextButton(); ?></td></tr>
    </table>
</form>
<?php $challenge->stopChallenge(); ?>