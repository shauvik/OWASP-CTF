<?php

class NihilistEncoder extends Encoder {

	var $table = array('A'=>'11','B'=>'12','C'=>'13','D'=>'14','E'=>'15','F'=>'21',
					  'G'=>'22','H'=>'23','I'=>'24','J'=>'24','K'=>'25','L'=>'31',
					  'M'=>'32','N'=>'33','O'=>'34','P'=>'35','Q'=>'41','R'=>'42',
					  'S'=>'43','T'=>'44','U'=>'45','V'=>'51','W'=>'52','X'=>'53',
					  'Y'=>'54','Z'=>'55');
	public function encode() {
		$key = $this->special;
		while(strlen($key) < strlen($this->input)) {
			$key .= $this->special;
		}
		$key = $this->transform($key);
		$msg = $this->transform($this->input);
		$sets = count($msg);
		
		$val = array();
		for($j=0;$j<$sets;$j++) {
			$val[] = $msg[$j]+$key[$j];
		}
		return join(",",$val);
	}
	
	private function transform($input) {
		$text = strtoupper($input);
		$array = preg_split('//', $text, -1, PREG_SPLIT_NO_EMPTY);	
		$partially_done_0 = "";
		$partially_done_1 = "";
		foreach ($array as $char) {
			$code = $this->table[$char];
			$partially_done_0 .= $code[0];
			$partially_done_1 .= $code[1];
		}
		$partially_done = $partially_done_0.$partially_done_1;
		$partially_done = preg_replace("/(..)/","$1 ",$partially_done);
		$subs = explode(" ",trim($partially_done));
		return $subs;
	}
}
?>