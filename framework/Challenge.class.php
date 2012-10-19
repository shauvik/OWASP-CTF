<?php
class Challenge {
	const PLAYER = 'player';
	const CHALLENGE = 'challenge';
	const TOKEN = 'token';
	const WORD = 'word';
	var $user = "";
	var $challenge = "";
	var $db = null;
	var $challengeType;
	var $challengeNmbr;

	function Challenge($challenge=null) {
		$this->db = new MySQL(HOST,DB_USER,DB_PASSWORD,DB_NAME);
		$this->db->testTable("SELECT * FROM players",CTF::CREATEPLAYER);
		$this->db->testTable("SELECT * FROM scoreboard LIMIT 1",CTF::CREATESCORE);
		if(isset($_SESSION[Challenge::PLAYER])) {
			$this->user = util::getSession(Challenge::PLAYER);
		}
		if($challenge == null) {
			$bt = debug_backtrace();
			$end = end($bt);
			$caller = $end['file'];
			$caller = str_replace("\\", "/", $caller); // quick hack to include Windows systems
			$explode = explode("/", dirname($caller));
			$chall = array_search("challenges", $explode);
			if(!$chall===false) {
				$this->challengeType = $explode[$chall + 1];
				$this->challengeNmbr = $explode[$chall + 2];
				$this->challenge = strtoupper($explode[$chall + 1]) . "-" . $explode[$chall + 2];
				$_SESSION[Challenge::CHALLENGE] = $this->challenge;
			}
			//echo $this->challenge;
		} else {
			$this->challenge = $_SESSION[Challenge::CHALLENGE];
		}
	}

	function getNetworkPort() {
		$sql = "SELECT port FROM NetworkChallenges WHERE name='challenge".$this->challengeNmbr."'";
		//echo $sql;
		$result =  $this->db->query($sql);
		//print_r($result);
		$row = $result->fetch();
		//print_r($row);
		return $row['port'];
	}

	function getNetworkPassword() {
		$sql = "SELECT password from NetworkChallenges WHERE name='challenge".$this->challengeNmbr."'";
		$result =  $this->db->query($sql);
		$row = $result->fetch();
		return $row['password'];
	}
	
	function getUser() {
		return $this->user;
	}

	function getChallenge() {
		return $this->challenge;
	}

	function mark() {
		$sql = "INSERT INTO scoreboard SELECT id,'$this->challenge',now()+0 FROM players WHERE name='$this->user'";
		#echo($sql);
        @$this->db->query($sql);
	}

	function getRank() {
		$sql = "SELECT DISTINCT  name, COUNT(obtained) AS total, MAX(obtained) AS last FROM players p LEFT JOIN scoreboard s ON p.id=s.pid GROUP BY name ORDER BY total DESC, last ASC";
		$result =  $this->db->query($sql);
		$rank = 0;
		$players = 0;
		$points = 0;
		while($row = $result->fetch()) {
			$players += 1;
			if($row['name'] == $this->user) {
				$rank = $players;
				$points = $row['total'];
			}
		}
		$output = "Your rank is $rank of $players players (with $points ". (($points==1)?'point':'points').')';
		return $output;
	}

	function getToken() {
		$array = util::getSession(Challenge::TOKEN);
		if (!isset($array[$this->challenge])) {
			$token = md5(util::getIP() . "owasp" . date("Ymdhis"));
			$array[$this->challenge] = $token;
			$_SESSION[Challenge::TOKEN] = $array;
		}
		return $array[$this->challenge];
	}

	function getDictionaryWord() {
		$array = util::getSession(Challenge::WORD);
		if (!isset($array[$this->challenge])) {
			$words = file(DICTIONARY);
			shuffle($words);
			$word = $words[0];
			$array[$this->challenge] = trim($word);
			$_SESSION[Challenge::WORD] = $array;
		}
		return $array[$this->challenge];
	}

	function mail($to, $from, $subject, $body) {
		//$sql = "INSERT into mailbox(userid,mfrom,msubject,mto,mbody,mdate) select userid,'$from','$subject','$to','$body',now() from users where username='$user' AND email='$to'";
		$this->db->testTable("SELECT * FROM mailbox",CTF::CREATEMAIL);
		$sql = "INSERT INTO mailbox(userid,mfrom,msubject,mto,mbody,mdate) select id,'$from','$subject','$to','$body',now() from players where name='$this->user'";
		
		$this->db->query($sql);
	}

	function buildChallenges() {
		$sql = "select perc/total as perc, level from (select count(distinct pid) as perc, count(distinct name) as total,level from scoreboard, players group by level) as tbl";
		$result = $this->db->query($sql);
		$levelPercs = array();
		while($row=$result->fetch()) {
			$levelPercs[$row['level']]=$row['perc'];
		}
		// print_r($levelPercs);
		// only mark challenges if player is set
		if(isset($_SESSION[Challenge::PLAYER])) {
			$sql = "SELECT level FROM scoreboard s, players p WHERE p.id=s.pid AND name='$this->user'";
			$result = $this->db->query($sql);
			$obtainedChallenges = array();
			while($row=$result->fetch()) {
				$obtainedChallenges[] = $row['level'];
			}
		}
		$style = <<<EOSTYLE

<style type="text/css">
ul.column {
	width: 100%;
	padding: 0 0 0 15px;
	margin: 10px 0 50px;
	list-style: none;
}

ul.column li {
	float: left;
	width: 60px;
	padding: 0;
	margin: 5px 0 0 0;
	display: inline;
	vertical-align: bottom;
}
</style><script type="text/javascript">
	$(document).ready(function() {

		$('a[href^="http://"]').attr({
			target : "_blank"
		});

		function smartColumns() {

			$("ul.column").css({
				'width' : "60%"
			});

			var colWrap = $("ul.column").width();
			var colNum = Math.floor(colWrap / 67);
			var colFixed = Math.floor(colWrap / colNum);

			$("ul.column").css({
				'width' : colWrap
			});
			$("ul.column li").css({
				'width' : colFixed,
				'text-align':'left',
				'height':'20px',
				'padding':'40px 10px 0 15px'
			});

		}

		smartColumns();

		$(window).resize(function() {
			smartColumns();

		});

	});
</script>

EOSTYLE;
/*
			$("ul.column li").css({
				'width' : colFixed,
				'text-align':'right',
				'height':'20px',
				'padding':'60px 10px 0 0'
			});
*/
		echo "$style<ul class=\"column\">\n";
		foreach (array_reverse(glob(CHALLENGES_DIR."/*", GLOB_ONLYDIR)) as $type) {
			$type = str_replace(CHALLENGES_DIR."/", "", $type);
			foreach (glob(CHALLENGES_DIR."/$type/*") as $number) {
				echo "<li ";
				$number = str_replace(CHALLENGES_DIR."/$type/", "", $number);
				echo "id=\"$type\"";
				$challenge = strtoupper($type) . "-" . $number;
				$perc = 0.0;
				$class = array();
				if(array_key_exists($challenge, $levelPercs)) {
					$perc = $levelPercs[$challenge];
					switch($perc) {
						case($perc < .5):
							$class[] = "p50";
							break;
						case($perc < .85):
							$class[] = "p85";
							break;
						case($perc < 1):
							$class[] = "p100";
							break;
					}
				} else {
					$class[] = "p0";
				}
				
				if(isset($_SESSION[Challenge::PLAYER])) {
					if ((array_search($challenge, $obtainedChallenges) === false) != true) {
						$class[] = "done";
					}
				}
				if(count($class) > 0 ){
					echo ' class="';
					echo join(" ",$class);
					echo '"';
				}
				echo "><a href=\"".CHALLENGES_DIR."/$type/$number/\" class=\"white\">$number</a></li>";
			}
		}
		echo "</ul>\n";
	}

	function header($array=array()) {
		if(!isset($array['title'])) {
			$arr = explode("-",$this->challenge);
			$array['title']="$arr[0] challenge level $arr[1]";
		}
		if(!isset($array['webroot'])) {
			$array['webroot'] = WEBROOT;
		}
		CTF::header($array);
	}

	function footer($array=array()) {
		CTF::footer($array);
	}

	function startChallenge($array=array()) {
		$this->header($array);
		echo '<div id="challengecontainer"><div id="challengeframe"> ';
		echo '<h1>'.$this->getLevelName().'</h1><br/>';
	}

	function stopChallenge($array=array()) {
		echo '</div></div>';
		$this->footer($array);
	}
	function nextButton() {
		$nextChallenge = $this->challengeNmbr+1;
		if(file_exists("../".$nextChallenge)) {
			echo "<input type=\"button\" class=\"button\" name=\"next\" onclick=\"location.href='../$nextChallenge/'\" value=\"Next\"/>";
		}
	}

	function getLevelName() {
		return ucfirst(strtolower($this->challengeType))." Level ".$this->challengeNmbr;
	}
}
?>
