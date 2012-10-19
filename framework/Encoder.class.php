<?php

abstract class Encoder {
	var $input;
	var $special;
	
	public function setInput($input) {
		$this->input = $input;
	}
	
	public function setSpecial($special) {
	$this->special = $special;
	}
	
	abstract function encode();
}
?>