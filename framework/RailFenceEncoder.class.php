<?php

class RailFenceEncoder extends Encoder {
	public function encode() {
		$msg = $this->input;
		$len = strlen($msg);
		while($len%6 != 0) {
			$msg .= ' ';
			$len = strlen($msg);
		}
		
		$fence_0 = "";
		$fence_1 = "";
		$fence_2 = "";
		$fence_3 = "";
		for($j=0;$j<strlen($msg);$j+=6) {
			$fence_0 .= $msg[$j];
			$fence_1 .= $msg[$j+1];
			$fence_2 .= $msg[$j+2];
			$fence_3 .= $msg[$j+3];
			$fence_2 .= $msg[$j+4];
			$fence_1 .= $msg[$j+5];
		}
		
		return $fence_0.trim($fence_1).trim($fence_2).trim($fence_3);
	}
}

?>