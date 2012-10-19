<?php
require_once('../config/config.inc.php');

if( $_SERVER['SERVER_PORT'] == 80) { 
        header('Location:https://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/'.basename($_SERVER['PHP_SELF'])); 
        die(); 
    } 

function showLogin($warning="",$logoff=false) {
	if(isset($_SESSION[Challenge::PLAYER])) {
		$output[] = '<form method="post" autocomplete="off"><table>';
		$output[] = '<tr><td><h2>Logoff</h2></td><td colspan=2 align="right" class="error"> '.$warning.'</td></tr><tr/>';
		$output[] = '<tr><td>Name</td><td>:</td><td><input type="text" name="name" disabled="true"/></td></tr>';
		$output[] = '<tr><td>Password</td><td>:</td><td><input type="password" name="password" disabled="true"/></td></tr>';
		$output[] = '<tr><td></td><td colspan=2 align=right><input class="button" type="submit" name="action" value="Register" disabled="true"/>';
		$output[] = '<button type="submit" name=action value=logoff>Logoff</button></td></tr>';
	} else {
		$output[] = '<form method="post" autocomplete="off"><table>';
		$output[] = '<tr><td><h2>Login&nbsp;</h2></td><td colspan=2 align="right" class="error"> '.$warning.'</td></tr><tr/>';
		$output[] = '<tr><td>Name</td><td>:</td><td><input type="text" name="name"/></td></tr>';
		$output[] = '<tr><td>Password</td><td>:</td><td><input type="password" name="password"/></td></tr>';
		$output[] = '<tr><td></td><td colspan=2 align=right><input class="button" type="submit" name="action" value="Register"/>';		
		$output[] = '<input class="button" type="submit" name="action" value="Login" tabindex="0"/></td></tr>';
	}
	$output[] = '</table></form>';

	return $output;
}

function showRegister($warning="") {
	$output[] = '<form method="post" name="complete"><table>';
	$output[] = '<tr><td><h2>Register</h2></td><td colspan=2 align="right" class="error"> '.$warning.'</td></tr><tr/>';
	$output[] = '<tr><td>Name</td><td>:</td><td><input type="text" name="name"/></td></tr>';
	$output[] = '<tr><td>Email</td><td>:</td><td><input type="text" name="email"/></td></tr>';
	$output[] = '<tr><td>Password</td><td>:</td><td><input type="password" name="password1" autocomplete="off"/></td></tr>';
	$output[] = '<tr><td>Password</td><td>:</td><td><input type="password" name="password2" autocomplete="off"/></td></tr>';
	$output[] = '<tr><td></td><td colspan=2 align=right><input class="button" type="submit" name="action" value="Register"/></td></tr>';
	$output[] = '</table></form>';

	return $output;
}

$action = strtolower(util::getGet('action'));
$button = strtolower(util::getPost('action'));

$output = showLogin("",isset($_SESSION[Challenge::PLAYER]));
if(!$button) {
	switch($action) {
		case 'login':
			$output = showLogin("",isset($_SESSION[Challenge::PLAYER]));
			break;
		case 'logoff':
			unset($_SESSION[Challenge::PLAYER]);
			$output[] = "You have been logged off";
			break;
		default:
			// ignore EVERYTHING else
			break;
	}
} else {
	switch($button){
		case 'logoff':
			unset($_SESSION[Challenge::PLAYER]);
			$output = showLogin("");
			$output[] = "You have been logged off";
			break;
		case 'login':
			extract($_POST);
			if(($id = CTF::login($name,$password)) != false) {
				$_SESSION[Challenge::PLAYER] = $name;
				$output = showLogin("",isset($_SESSION[Challenge::PLAYER]));
				//$output[] = "You are logged in";
				if(isset($_SESSION['referrer'])) {
					$location = util::getSession('referrer');
					unset($_SESSION['referrer']);
					header("Location:".$location);
				}
			} else {
				$output=showLogin("Unknown user",isset($_SESSION[Challenge::PLAYER]));
			}
			break;
		case 'doregister':
			$output = showRegister('');
			break;
		case 'register':
			if(util::getPost('password1') === false) {
				$output = showRegister('');
			} else {
				extract($_POST);
//				print_r($_POST);
				if("" == $email) {
//					echo "setting email";
					$email = "";
				}
				if(!"" == $name) {
					if($password1 != $password2) {
						// not all set
						$output = showRegister("Passwords not equal");
					} else {
						if(CTF::register($name,$password1,$email)) {
							$output=showLogin("",isset($_SESSION[Challenge::PLAYER]));
						} else {
							$output=showRegister("User already exists");
						}
					}
				} else {
					$output=showRegister("Name can't be empty");
				}
			}
			break;
		default:
			break;
	}
}

$challenge = new Challenge();
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
echo '<div id="content">';
echo '<div id="multi-logo">';
echo "<div id=\"login\">";
echo join('',$output);
echo "</div></div></div>";
echo CTF::footer();
?>