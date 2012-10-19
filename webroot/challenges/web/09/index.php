<?php
/*
 * This should work:
 * uid = admin'+--+-
 * pwd = 123
 */
require_once('../../../../config/config.inc.php');

$challenge = new Challenge();
$challenge->startChallenge();
$pwd = $challenge->getDictionaryWord();
$token = $challenge->getToken();
$createSQL = "CREATE TABLE players (id MEDIUMINT NOT NULL AUTO_INCREMENT,name varchar(60) NOT NULL,password varchar(100) NOT NULL,PRIMARY KEY(id))";
$error = "";

$dbname='wcdb'.$challenge->getChallenge().$challenge->getUser();
$dbname=str_replace('-', '', $dbname);
$db = new MySQL('localhost','wcuid'.$challenge->getUser(),'wcpwd#sldi$v0x8'.$token,strtolower($dbname));

if($db->testTable("SELECT * FROM players LIMIT 0,1", $createSQL)) {
	$db->query("INSERT INTO players(name,password) VALUES('admin','$token')");
	
}
if (isset($_GET['submit'])) {
	$uid = htmlspecialchars(strip_tags($_GET['username']));
	$passwd = htmlspecialchars(strip_tags($_GET['password']));

	$sql = "SELECT password FROM players where name='admin'";
	$result = $db->query($sql);
	$tbl = $result->fetch();
	$pwd = $tbl['password'];	
	if ($uid == "admin" && $passwd == $pwd) {
		$challenge->mark();
		CTF::showAchieved();
		$db->query("DROP database ".'webchallengedb'.$challenge->getUser());
	} else {
		CTF::error("To bad, please try again. Query: " . str_replace("-", "&#45;", htmlentities($db->lastquery, ENT_QUOTES)) . " ");
	}
}
?>
You have to log in as admin.
<br/><br/>
<?= $error; ?>
<form autocomplete="off">
    <table>
        <tr><td>Username</td><td>:</td><td><input type="text" name="username" /></td></tr>
        <tr><td>Password</td><td>:</td><td><input type="password" name="password" /></td></tr>
        <tr><td colspan=2/><td><input type="submit" class="button" name="submit" value="Submit"/> <?php $challenge->nextButton(); ?></td></tr>
    </table>
</form>
<?php $challenge->stopChallenge(); ?>