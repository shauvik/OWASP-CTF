<?php

class VigenereEncoder extends Encoder {

var $table = 'abcdefghijklmnopqrstuvwxyz';
	public function encode() {
		$str = $this->input;
		$enc_str = '';
        $len = strlen($str);
        for($i = 0; $i < $len; $i++) {
            $shift = $this->P($this->charAt($str, $i)) + $this->P($this->charAt($this->special, $i));
            $pos = $this->modulo($shift, strlen($this->table));
            $enc_str .= $this->A($pos);
        }
        return $enc_str;
		
	}
	
	/*
    * Position in Table
    */
    function P($a) {
        return strpos($this->table, $a); // todo: catch chars not in table
    }

    /**
    * Alphabet at Position in Table
    */
    function A($p) {
        $p = $p >= 0 ? $p : strlen($this->table) + $p; // include negative positions
        return $this->table{$p};
    }

    /**
    * Alphabet at Position in a String with the string length as Modulus
    */
    function charAt($str, $i) {
        $i = $i%strlen($str);
        return $str{$i};
    }

    /**
    * Modulo
    */
    function modulo($n, $mod) {
        return $n%$mod;
    }
}
?>