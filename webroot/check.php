<?php
require_once('../config/config.inc.php');
$challenge = new Challenge("check");



$otherTokens = array("6badb90f0c53590068e5033ea02eabb5",
"776366e339d3a0c9df55cc6546810c43",
"59b9a45c76f7f00960e0f40640019123",
"b0a115c79ffe8709e43ed22529083971",
"f74abedfccd42867948e2273bbe29e94",
"b954b0cf05d0df4d4cb10d11caed1f88",
"882d08f378dfc15b12557d6e3e607070",
"81b75b90eebc83f2524c80f694fbbff2",
"4f270b8ad4556c88ab18e7176f212bb9",
"af10c15cb715f425c20bee882cc8c914",
"cd029afa8cd31acc6655b133f9d3f6e0",
"a94db1ca882e0c2eda2e62036b00e46094bca55d",
"ac46c0c590fec26c744f8f0462471572af9034d4",
"980717ff50e7f0f29c414841b6c05f8bdc4e0ae2",
"7bcb4f425a96b9fa075233020d90fdf0927d0b4f",
"MyV01c3ismyPASSword!",
"47ed1378be26ca1e5700ec58d7c835c9",
"4d1a11eabab62fb01eee47db7cc5c956",
"fad8673cbb9446ac8031625250b067ae"
);
$array = $BASE_ARRAY;
$array['title']="CTF Login";

if(isset($_SESSION[Challenge::PLAYER])) {
	$array['ranking'] = $challenge->getRank();
	$array['login']= '<a href="login.php?action=logout">Logout</a>';
} else {
	$array['ranking'] = "You have to login to have a rank";
	$array['login'] = 'Login';
}

echo CTF::header($array);
echo '<div id="challengecontainer"><div id="challengeframe">';
$output="";
$db = new MySQL(HOST,DB_USER,DB_PASSWORD,DB_NAME);
if(isset($_SESSION['player'])) {
	if(isset($_GET['t'])) {
		$t = util::getGet('t');
		$token = $challenge->getToken();
		echo "t=$t;token=$token";
		if (true === ($t == $token)) {
			$challenge->mark();
			util::forward(WEBROOT . "/index.php");
		} 
	} else {
		if(isset($_POST['action'])) {

			$token = util::getPost('token');
			$validtoken = $challenge->getToken();

			if(($validToken === $token)||in_array($token, $otherTokens)) {
				$output= "valid token";
				// token is valid
				//$row = $result->fetch();
				$user = util::getSession('player');
				$sql = "INSERT INTO scoreboard SELECT id,'$token',now()+0 FROM players WHERE name='$user'";
				//$sql = "INSERT INTO scoreboard VALUES($user,'$token',now()+1)";
				$result = $db->query($sql);
			} else {
				$output= "not valid";
			}
		}
	}
} else {
	$_SESSION['referrer']=$_SERVER['REQUEST_URI'];
	header("Location:login.php");
}


if($output != "") {
	CTF::message($output);
}
?>

<div id="check">
<br/><br/>
<center>
	<form method="post">
		<table>
			<tr>
				<td>Token</td>
				<td>:</td>
				<td><input type="text" name="token" autocomplete="off" /></td>
			</tr>
			
			<tr>
				<td></td>
				<td colspan=2 align=right><button type="submit" name="action" value="submit">Submit</button></td>
			</tr>
		</table>
	</form>
	</center>
</div>
</div>
</div>




<?php
echo CTF::footer(); ?>