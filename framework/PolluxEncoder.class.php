<?php

class PolluxEncoder extends Encoder {
	public function encode() {
			$alphabet = array("a" => '.-', 'b'=> '-...', "c" => '-.-.', 'd'=> '-..', 'e'=> '.', 'f'=> '..-.', 'g'=> '--.', 'h'=> '....', 'i'=> '..', 'j'=> '.---', 'k'=> '-.-', 'l'=> '.-..',
		            'm'=> '--', 'n'=> '-.', 'o'=> '---', 'p'=> '.--.', 'q'=> '--.-', 'r'=> '.-.', 's'=> '...', 't'=> '-', 'u'=> '..-', 'v'=> '...-', 'w'=> '.--', 'x'=> '-..-',
		            'y'=> '-.--', 'z'=> '--..', '0'=> '-----', '1'=> '.----', '2'=> '..---', '3'=> '...--', '4'=> '....-', '5'=> '....', '6'=> '-....', '7'=> '--...',
		            '8'=> '---..', '9'=> '----.', '.'=> '.-.-.-', ','=> '--..--', '?'=> '..--..',' '=>'');
		$result = "";
		$txt = strtolower($this->input);
		$array = preg_split('//', $txt, -1, PREG_SPLIT_NO_EMPTY);
		foreach ($array as $char) {
			$morse = $alphabet[$char];
			for($k = 0;$k<strlen($morse); $k++) {
				switch($morse[$k]) {
					case '.':
						$result .= $this->getRandomchar(array(0,7,4));
						//0,7,4
						break;
					case '-':
						$result .= $this->getRandomchar(array(1,8,5));
						//1,8,5
						break;
				}
			}
			//2,9,6,3
			$result .= $this->getRandomchar(array(2,9,6,3));
		}
		
		return $result;
	}
	
	private function getRandomchar($array) {
		$len = count($array);
		$val = rand(0,$len-1);
		return $array[$val];
	}
}

?>