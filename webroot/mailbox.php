<?php
require_once('../config/config.inc.php');
$challenge = new Challenge();
$array = $BASE_ARRAY;
$array['otherpage']= '<a class="white" href="/mailbox.php">Mailbox</a>';
$challenge->header($array);

CTF::showAllMail($challenge->getUser());


?>
</div></div>
<div id="main-footer">
		<table width="100%">
			<tr>
				<td>This site is partly made possible by me :)</td>
				<td align="right">OWASP - CTF 2010</td>
			</tr>
			<tr>
				<td>Thanks to my wife, <a href="http://www.securityskills.nl" class="white">securityskills.nl</a> and other sites of which I copied stuff :)</td>
			</tr>
		</table>
	</div>
<!-- mail starts here -->
<div id="overlay" style="display:none;"></div>
<div id="mail" style="display:none;">
    <div class="closing"><a href="javascript:closeMail()">X</a></div>
    <div id="mailmessage">
        
    </div>
</div>
</body>
</html>