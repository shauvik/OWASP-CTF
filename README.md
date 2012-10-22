OWASP CTF PROJECT
-----------------
Main project URL: https://www.owasp.org/index.php/Category:OWASP_CTF_Project
Customized for Owasp-Atlanta by Shauvik Roy Choudhary (@shauvik)

----------------------------------------------------------------------------

INSTRUCTIONS FOR SETUP

1. Checkout project source
git clone https://github.com/shauvik/OWASP-CTF.git

2. Add the challenges to the webroot/challenges/<type>/<challenge_id> folder. 

3. Configure apache to serve the webroot folder as the DocumentRoot

4. Create a Mysql Database and update the DB_* fields in the config/config.inc.php file.

5. If you need to use a password wordlist, update the passwords in config/words.txt file


