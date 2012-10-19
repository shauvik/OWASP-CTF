<?php
class Encode {
	var $message;
	var $key;
	function Encode($message ="", $key="") {
		$this->message = $message;
		$this->key = $key;
	}

	public static function morseCode($message) {
		$alphabet = array("a" => ' .-', 'b' => ' -...', "c" => ' -.-.', 'd' => ' -..', 'e' => ' .', 'f' => ' ..-.', 'g' => ' --.', 'h' => ' ....', 'i' => ' ..', 'j' => ' .---', 'k' => ' -.-', 'l' => ' .-..',
		            'm' => ' --', 'n' => ' -.', 'o' => ' ---', 'p' => ' .--.', 'q' => ' --.-', 'r' => ' .-.', 's' => ' ...', 't' => ' -', 'u' => ' ..-', 'v' => ' ...-', 'w' => ' .--', 'x' => ' -..-',
		            'y' => ' -.--', 'z' => ' --..', '0' => ' -----', '1' => ' .----', '2' => ' ..---', '3' => ' ...--', '4' => ' ....-', '5' => ' ....', '6' => ' -....', '7' => ' --...',
		            '8' => ' ---..', '9' => ' ----.', '.' => ' .-.-.-', ',' => ' --..--', '?' => ' ..--..',' '=>'');
		$result = "";
		$txt = strtolower($message);
		$array = preg_split('//', $txt, -1, PREG_SPLIT_NO_EMPTY);
		foreach ($array as $char) {
			$morse = $alphabet[$char];
			$result .= $morse;
		}

		return $result;
	}

	public static function brailleEncode($message) {
		$alphabet = array('a' => '&#10241;', 'b' => '&#10243;', 'c' => '&#10249;', 'd' => '&#10265;', 'e' => '&#10257;', 'f' => '&#10251;', 'g' => '&#10267;', 'h' => '&#10259;', 'i' => '&#10250;',
	            'j' => '&#10266;', 'k' => '&#10245;', 'l' => '&#10247;', 'm' => '&#10253;', 'n' => '&#10269;', 'o' => '&#10261;', 'p' => '&#10255;', 'q' => '&#10271;', 'r' => '&#10263;',
	            's' => '&#10254;', 't' => '&#10270;', 'u' => '&#10277;', 'v' => '&#10279;', 'w' => '&#10298;', 'x' => '&#10285;', 'y' => '&#10301;', 'z' => '&#10293;');
		$nmbrs = array('1' => '&#10241;', '2' => '&#10243;', '3' => '&#10249;', '4' => '&#10265;', '5' => '&#10257;', '6' => '&#10251;', '7' => '&#10267;', '8' => '&#10259;', '9' => '&#10250;', '0' => '&#10266;');
		$uppercase = '&#10272;';
		$number = '&#10300;';

		$result = "";
		foreach (preg_split('//', $message, -1, PREG_SPLIT_NO_EMPTY) as $letter) {
			if (array_key_exists($letter, $nmbrs)) {
				$result = $result . $number . $nmbrs[$letter];
			} else {
				$result = $result . $letter;
			}
		}

		foreach ($alphabet as $letter => $braille) {
			$result = str_replace($letter, $braille, $result);
			$result = str_replace(strtoupper($letter), $uppercase . $braille, $result);
		}

		return $result;
	}

	private function calcHam($bin) {
		$result = "000000000000";
		$result[2] = $bin[0];
		$result[4] = $bin[1];
		$result[5] = $bin[2];
		$result[6] = $bin[3];
		$result[8] = $bin[4];
		$result[9] = $bin[5];
		$result[10] = $bin[6];
		$result[11] = $bin[7];

		$result[0] = ($result[2] + $result[4] + $result[6] + $result[8] + $result[10] + $result[12]) & 1;
		$result[1] = ($result[2] + $result[5] + $result[6] + $result[9] + $result[10]) & 1;
		$result[3] = ($result[4] + $result[5] + $result[6] + $result[11]) & 1;
		$result[7] = ($result[8] + $result[9] + $result[10] + $result[11]) & 1;

		return $result;
	}
	
	function hamCalc() {
		$result = array();
		for ($pos = 0; $pos < strlen($message); $pos++) {
			$result[] = calcHam(sprintf("%08b", ord($text[$pos])));
		}
		return $result;
	}

	private function bitflip($string,$pos) {
		$val = (int) $string[$pos];
		$string[$pos] = $val xor 1;
		
		return $string;
	}
	
	function hamMistake($array) {
		for ($pos = 0; $pos < strlen($message); $pos++) {
			if(( ord($message[$pos]) & 16) == 1) {
				$bitpos = rand(0, 11);
				$array[$pos] = bitflip($array[$pos],$bitpos);
			}
		}
		return $array;
	}
}
?>