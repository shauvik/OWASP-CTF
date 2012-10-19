<?php

class ADFGVXEncoder extends Encoder {

	var $substitution = array('A'=>'AA','B'=>'AD','C'=>'AF','D'=>'AG','E'=>'AV','F'=>'AX',
	                      'G'=>'DA','H'=>'DD','I'=>'DF','J'=>'DG','K'=>'DV','L'=>'DX',
						  'M'=>'FA','N'=>'FD','O'=>'FF','P'=>'FG','Q'=>'FV','R'=>'FX',
						  'S'=>'GA','T'=>'GD','U'=>'GF','V'=>'GG','W'=>'GV','X'=>'GX',
						  'Y'=>'VA','Z'=>'VD','0'=>'VF','1'=>'VG','2'=>'VV','3'=>'VZ',
						  '4'=>'XA','5'=>'XD','6'=>'XF','7'=>'XG','8'=>'XV','9'=>'XX');

	public function encode() {
		$text = strtoupper($this->input);
		$array = preg_split('//', $text, -1, PREG_SPLIT_NO_EMPTY);	
		$partially_done = "";
		foreach ($array as $char) {
			$code = $this->substitution[$char];
			$partially_done .= $code;
		}

		$KEY_sorted = preg_split("//",$this->special, -1, PREG_SPLIT_NO_EMPTY);
		sort($KEY_sorted);echo "<br/>";
		$keydup = $this->special;
		$sorting= array();
		foreach($KEY_sorted as $char) {
			$pos = strpos($keydup, $char);
			$keydup[$pos]=' ';
			$sorting[] = $pos;
		}
		

		$size = strlen($this->special);
		$row = -1;
		for ($j=0; $j<strlen($partially_done); $j++){
			if($j%$size == 0) $row+=1;
			$table[$row][$sorting[$j%$size]] = $partially_done[$j];
		}

		$rows = count($table);
		$encoded = "";
		for($j=0; $j<$size;$j++) {
			for($r=0; $r<$rows;$r++) {
			if(isset($table[$r][$j]))
			$encoded .= $table[$r][$j];
			}
		}

		return $encoded;
	}
}


/*
2-4-9-10-7-6-8-3-1-11-5 (ACCEEFIIRTT)
a=>9; c=>1; c=>8; e=>2; e=>11; f=>6; i=>5; i=>7; r=>3; t=>4; t=>10; 

Array ( 
[0] => Array ( [8] => A [0] => V [7] => A [1] => X [10] => A [5] => D [4] => X [6] => D [2] => A [3] => V [9] => X ) 
[1] => Array ( [8] => V [0] => A [7] => G [1] => X [10] => X [5] => X [4] => F [6] => X [2] => D [3] => X [9] => A ) 
[2] => Array ( [8] => X [0] => G [7] => X [1] => X [10] => X [5] => F [4] => X [6] => G [2] => X [3] => A [9] => X ) 
[3] => Array ( [8] => F [0] => A [7] => D [1] => V [10] => G [5] => A [4] => D [6] => X [2] => G [3] => X [9] => D ) 
[4] => Array ( [8] => A [0] => X [7] => X [1] => X [10] => A [5] => V [4] => X [6] => F [2] => A [3] => A [9] => A ) 
[5] => Array ( [8] => F [0] => A [7] => V [1] => X [10] => G [5] => A [4] => A [6] => X [2] => D ) 
) 

*/

?>