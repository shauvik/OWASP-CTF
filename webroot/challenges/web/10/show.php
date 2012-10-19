<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../../../../config/config.inc.php');
$challenge = new Challenge();
$challenge->startChallenge();
$pwd = $challenge->getDictionaryWord();

if (isset($_GET['filename'])) {

    $filename = $_GET['filename'];

    // here be exploit %00
    if (strpos($filename, "example.php") === False) {
        echo "illegal, can only look at example";
    } else {
        // we only allow you to look at index.php
        if (substr($filename, 0, 9) === "index.php" || $filename === "example.php") {
            // hack for latest PHP version --- is not troubled by %00 byte  (anymore)
			//echo strlen($filename);

			if(strlen($filename) == 21) {
//            if(substr($filename,9,1) === 0x00) {
                $filename = substr($filename,0,9);
                }

            $source = file_get_contents($filename);
            $source = str_replace("\$challenge->getDictionaryWord();", "\"$pwd\";", $source);
            $source = preg_replace("/\/\*.*\*\/\n/si","",$source);
            $geshi = new GeSHi($source, "php");
            $geshi->get_stylesheet();

            $geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
            echo $geshi->parse_code();
        } else {
            echo "Hmmm, we ARE looking for the password, which is ONLY in index.php (get it?)";
        }
    }
}
?>
