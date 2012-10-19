<?php

class util {
	public static function escape($value) {
		$return = '';
		for ($i = 0; $i < strlen($value); ++$i) {
			$char = $value[$i];
			$ord = ord($char);
			if ($char !== "'" && $char !== "\"" && $char !== '\\' && $ord >= 32 && $ord <= 126)
			$return .= $char;
			else
			$return .= '\\x' . dechex($ord);
		}
		return $return;
	}

	public static function process($var) {
		if(!is_array($var)) {
			$result = trim($var);
			$result = htmlentities($result,ENT_QUOTES);
			
			return $result;
		} else {
			return $var;
		}
	}

	public static function getPost($var, $default=false) {
		return (isset($_POST[$var])) ? util::process($_POST[$var]) : $default;
	}

	public static function getGet($var, $default=false) {
		return (isset($_GET[$var])) ? util::process($_GET[$var]) : $default;
	}

	public static function getSession($var, $default=false) {
		return (isset($_SESSION[$var])) ? util::process($_SESSION[$var]) : $default;
	}
	
	public static function getCookie($var, $default=false) {
	return (isset($_COOKIE[$var])) ? util::process($_COOKIE[$var]) : $default;
	}

	public static function getIP() {
		if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
		$ip = getenv("HTTP_CLIENT_IP");
		else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
		$ip = getenv("HTTP_X_FORWARDED_FOR");
		else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
		$ip = getenv("REMOTE_ADDR");
		else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
		$ip = $_SERVER['REMOTE_ADDR'];
		else
		$ip = "unknown";
		return($ip);
	}
	
	public static function forward($url) {
		header("Location: $url");
	}
	
	public static function ascii2hex($ascii) {
		$hex = '';
		for ($i = 0; $i < strlen($ascii); $i++) {
			$byte = strtoupper(dechex(ord($ascii{$i})));
			$byte = str_repeat('0', 2 - strlen($byte)) . $byte;
			$hex.="%" . $byte;
		}
		return $hex;
	}
}

?>