function closeChallenge() { 
	document.getElementById('overlay').style.display = 'none'; 
	document.getElementById('challengecontainer').style.display = 'none'; 
}

function closeMail() { 
	document.getElementById('overlay').style.display = 'none'; 
	document.getElementById('mail').style.display = 'none'; 
}
function setMail(mailId) {
	document.getElementById('overlay').style.display = 'block'; 
	document.getElementById('mail').style.display = 'block'; 
	document.getElementById('mailmessage').innerHTML = "";
	getMail(mailId);
}

function getMail(mailId) {
	var xmlHttpReq = false;
	var self = this;
	// Mozilla/Safari
	if (window.XMLHttpRequest) {
		self.xmlHttpReq = new XMLHttpRequest();
	}
	// IE
	else if (window.ActiveXObject) {
		self.xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
	}
	self.xmlHttpReq.open('POST', 'getMail.php', true);
	self.xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	self.xmlHttpReq.onreadystatechange = function() {
		if (self.xmlHttpReq.readyState == 4) {
			updateMail(self.xmlHttpReq.responseText);
		}
	}
	self.xmlHttpReq.send('m='+escape(mailId));
}

function updateMail(str){
	document.getElementById("mailmessage").innerHTML = str;
}  