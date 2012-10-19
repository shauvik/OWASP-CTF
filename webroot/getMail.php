<?php
session_start();
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once("../config/config.inc.php");
$challenge = new Challenge();


if (isset($_POST['m'])) {
    $mail = util::getPost('m');
    $db = new MySQL(HOST,DB_USER,DB_PASSWORD,DB_NAME);
    $sql = "SELECT mfrom,mto,msubject,mbody,mdate FROM mailbox m,players u WHERE u.id=m.userid AND u.name='" . $challenge->getUser() . "' AND m.mailid=$mail";
	// echo $sql;
    $result = $db->query($sql);
    $row = $result->fetch();
	extract($row);
    $text = <<<EOT
    <div  id="message">
            <!-- mail starts here -->
            <table id="mailheader" cellpadding="15" cellspacing="3">
                <tr><td align="right">To:</td><td>&nbsp;</td><td>$mto</td></tr>
                <tr><td align="right">From:</td><td>&nbsp;</td><td>$mfrom</td></tr>
                <tr><td align="right">Date:</td><td>&nbsp;</td><td>$mdate</td></tr>
                <tr><td align="right">Subject:</td><td>&nbsp;</td><td>$msubject</td></tr>
            </table>
            <hr/>
            <div id="mailbody">$mbody</div>
            <!-- mail ends here -->
        </div>
EOT;
    echo $text;
}
?>
