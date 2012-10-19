<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Viginere
 *
 * @author baanstev
 */
/**
* Example Implementation of the Vigenere Cipher in PHP
*/
class Vigenere {

    /* @param String $table Vigenere table characters */
    var $table;
    /* @param String $key Vigenere key */
    var $key;
    /* @param Int $mod Modulus (length of Vigenere table string above) */
    var $mod;

    /**
    * Constructor
    * @param String $key Vigenere Key
    * @param String $table Optional Character Table (Vigenere table)
    */
    function Vigenere($key = false, $table = false) {
        $this->table = $table ? $table : 'abcdefghijklmnopqrstuvwxyz';
        $this->mod = strlen($this->table);
        $this->key = $key ? $key : $this->generateKey();
    }

    /**
    * Generate a random Vigenere Key (one-time pad)
    */
    function generateKey() {
        $this->key = '';
        for ($i = 0; $i < $this->mod; $i++) {
            $this->key .= $this->table{rand(0, $this->mod)};
        }
        return $this->key;
    }

    /**
    * Get the Vigenere Key being Used
    */
    function getKey() {
        return $this->key;
    }

    /**
    * Encode a String with Vigenere Cipher
    * @param String $str String
    */
    function encrypt($str) {
        $enc_str = '';
        $len = strlen($str);
        for($i = 0; $i < $len; $i++) {
            $shift = $this->P($this->charAt($str, $i)) + $this->P($this->charAt($this->key, $i));
            $pos = $this->modulo($shift, $this->mod);
            $enc_str .= $this->A($pos);
        }
        return $enc_str;
    }

    /**
    * Decode a String encoded with Vigenere Cipher
    * @param String $str String
    */
    function decrypt($str) {
        $txt_str = '';
        $len = strlen($str);
        for($i = 0; $i < $len; $i++) {
            $shift = $this->P($this->charAt($str, $i)) - $this->P($this->charAt($this->key, $i));
            $pos = $this->modulo($shift, $this->mod);
            $txt_str .= $this->A($pos);
        }
        return $txt_str;
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
