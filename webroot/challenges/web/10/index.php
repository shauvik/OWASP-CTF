<?php
require_once('../../../../config/config.inc.php');

$challenge = new Challenge();
$challenge->startChallenge();
$pwd = $challenge->getDictionaryWord();
$token = $challenge->getToken();

if (isset($_POST['submit'])) {
	$uid = util::getPost('username');
	$passwd = util::getPost('password');
	if ($uid = "admin" && $passwd == $pwd) {
		$challenge->mark();
		CTF::showAchieved();
	}
}
?>
<a href="show.php?filename=example.php" style="color:blue;">You can look at a PHP example here</a>
<br/><br/>
<hr/>
<br/>
<form autocomplete="off" method="post">
    <table>
        <tr><td>Username</td><td>:</td><td><input type="text" name="username" /></td></tr>
        <tr><td>Password</td><td>:</td><td><input type="password" name="password" /></td></tr>
        <tr><td colspan=2/><td><input type="submit" class="button" name="submit" value="Submit"/> <?php $challenge->nextButton(); ?></td></tr>
    </table>
</form>
<?php $challenge->stopChallenge(); ?>