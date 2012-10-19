<?php
require_once('../../../../config/config.inc.php');

$challenge = new Challenge();
$challenge->startChallenge();
$pwd = $challenge->getDictionaryWord();
$token = $challenge->getToken();

if(isset($_POST['submit'])) {
	$code = util::getPost('password');
	if($code == $pwd) {
		$challenge->mark();
		CTF::showAchieved();
	} else {
		CTF::error("Code is not correct");
	}
}
$passphrase = "The password for this exercise is $pwd";
?>

<center><?php echo Encode::brailleEncode($passphrase); ?></center><hr/>
<form autocomplete="off" method="post">
    <input type="hidden" name="action" value="login" />
    <table>
        <tr><td>Code</td><td>:</td><td><input type="text" name="password" /></td></tr>
        <tr><td colspan=2/><td><input type="submit" class="button" name="submit" value="Submit" /></td></tr>
    </table>
</form>
<?php $challenge->stopChallenge(); ?>