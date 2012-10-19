<?php
class CTF {

	const CONTENT      = "<!-- CONTENT -->";

	const CREATEPLAYER = "CREATE TABLE players (id MEDIUMINT NOT NULL AUTO_INCREMENT,name varchar(60) NOT NULL,password varchar(100) NOT NULL, email varchar(100), PRIMARY KEY(id))";
	const CREATESCORE  = "CREATE TABLE scoreboard (pid MEDIUMINT,level VARCHAR(20),obtained BIGINT,FOREIGN KEY (pid) REFERENCES players(id) ON DELETE CASCADE,PRIMARY KEY(pid,level))";
	const CREATEMAIL = "CREATE TABLE mailbox ( mailid MEDIUMINT NOT NULL AUTO_INCREMENT, userid MEDIUMINT NOT NULL, mfrom VARCHAR(100) NOT NULL, mto VARCHAR(100) NOT NULL, mdate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, msubject VARCHAR(500), mbody VARCHAR(1024), PRIMARY KEY(mailid), FOREIGN KEY (userid) REFERENCES players(id) ON DELETE CASCADE)";

	public static function header($tags=array()) {
		$content=file_get_contents(CONFIGDIR .'/template.html');
		$index=strpos($content,CTF::CONTENT);
		$content = substr($content,0,$index);
		foreach($tags as $tag=>$data){

			$content=str_replace('##'.$tag.'##',$data,$content);
		}
		//remove non filled tags
		$content=preg_replace('/##.*\##/',"",$content);
		echo $content;
	}

	public static function footer($tags=array()) {
		$content=file_get_contents(CONFIGDIR . '/template.html');
		$index=strpos($content,CTF::CONTENT);
		$content =  substr($content,$index+strlen(CTF::CONTENT));
		foreach($tags as $tag=>$data){
			$content=str_replace('##'.$tag.'##',$data,$content);
		}
		// remove non filled tags
		$content=preg_replace('/##.*\##/',"",$content);
		echo $content;
	}

	public static function getPlayerOverview() {
		global $ignoreThesePlayers;

		$query = "select distinct name, count(obtained) as total,max(obtained) as lastentry from players p LEFT JOIN scoreboard s ON p.id = s.pid group by name ORDER BY total DESC,lastentry ASC";
		if (!empty($ignoreTheseUsers)) {
			$query = "select distinct name, count(obtained) as total,max(obtained) as lastentry from players p LEFT JOIN score s ON p.id = s.pid where name not in ('";
			$query .= implode("','", $ignoreThesePlayers);
			$query .= "') group by name ORDER BY total DESC,lastentry ASC";
		}
		$db = new MySQL(HOST,DB_USER,DB_PASSWORD,DB_NAME);
		return $db->query($query);
	}

	public static function buildScoreboard() {
		$result = CTF::getPlayerOverview();
		$count = 0;
		echo '<div id="scoreboard"><h1>Scoreboard</h1><hr/><table width="50%">';
		echo '<tr><th/><th align="left" width="120">Position</th><th align="left">Name</th><th align="right" width="80">Points</th></tr> ';
		while($row = $result->fetch()) {
			$username = $row['name'];
			$score = $row['total'];
			$count++;
			if ($count == 1) {
				echo "<tr class=\"pos1\"><td><img src=\"images/gold_cup.png\"></td><td>$count</td><td>$username</td><td align=\"right\">$score</td></tr>";
			} elseif ($count == 2) {
				echo "<tr class=\"pos2\"><td><img src=\"images/silver_cup.png\"></td><td>$count</td><td>$username</td><td align=\"right\">$score</td></tr>";
			} elseif ($count == 3) {
				echo "<tr class=\"pos3\"><td><img src=\"images/bronze_cup.png\"></td><td>$count</td><td>$username</td><td align=\"right\">$score</td></tr>";
			} else {
				echo "<tr><td/><td>$count</td><td>$username</td><td align=\"right\">$score</td></tr>";
			}
		}
		echo "</table></div>";
	}

	public static function login($username,$password, $db=false ) {
		
		if($db === false) {
			echo "other db";
			$db = new MySQL(HOST,DB_USER,DB_PASSWORD,DB_NAME);
		}
		$sql = "SELECT * FROM players WHERE name='$username' AND password='".md5($password)."'";
		echo "sql=$sql";
		$result = $db->query($sql);
		echo $result instanceof MySQLResult;
		if(!$result instanceof MySQLResult)
		return false;
		$row = $result->fetch();
		return ($result->size() === 1)?$row['id']:false;
	}

	public static function register($name,$password,$email) {
		$db = new MySQL(HOST,DB_USER,DB_PASSWORD,DB_NAME);

		$db->testTable("SELECT COUNT(*) FROM players",CTF::CREATEPLAYER);
		$db->testTable("SELECT * FROM scoreboard LIMIT 1",CTF::CREATESCORE);

		$sql = "SELECT * FROM players WHERE name='$name'";
		$result = $db->query($sql);
		if($result->size() === 0) {
			$sql = "INSERT INTO players (name,email,password) VALUES('".htmlentities($name,ENT_QUOTES)."','".
			htmlentities($email,ENT_QUOTES)."','".md5($password)."')";
			$result = $db->query($sql);
			return true;
		} else {
			return false;
		}
	}

	public static function checkDB() {
		$db = new MySQL(HOST,DB_USER,DB_PASSWORD,DB_NAME);

		$db->testTable("SELECT COUNT(*) FROM players",CTF::CREATEPLAYER);
		$db->testTable("SELECT * FROM scoreboard LIMIT 1",CTF::CREATESCORE);
	}

	static function message($text="", $type="info") {
		if(!empty($text)) {
			echo "<div id=\"$type\">$text</div>";
		}
	}

	static function showAchieved() {
		CTF::message("You made it!!","achieved");
	}

	static function error($message) {
		CTF::message($message,"error");
	}
	
	static function showCode($filename) {
		$source = file_get_contents($filename);
		$source = preg_replace('%require.*;%i','',$source);
		$source = preg_replace('%global.*;%i','',$source);
		$source = preg_replace('%/\s*\*\s\s+.*?\*/\s*%s', "// Removed some stuff, not challenge related\n", $source);
 		$source = preg_replace("/\/\/ noShow>.*?\/\/ noShow</si", "// Removed some stuff, not challenge related", $source);
		$geshi = new GeSHi($source, "php");
		$geshi->get_stylesheet();
		$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
		echo "<div id=\"code\" with=\"80%\">";
		echo $geshi->parse_code();
		echo "</div>";
		exit;
		
	}
	
	    static function showAllMail($user) {
        $pre = <<<EOT
<div id="content" class="small-logo">
    <div id="small-logo">
	<div id="mailbox">
		<table width="65%" id="mailboxtable">
			<thead>
				<tr><th align="left" >From</th><th align="left">Subject</th><th align="left">Date</th></tr>
			</thead>
			<tbody>
EOT;
        $post = <<<EOT
			</tbody>
		</table>
	</div>
        </div>
</div>
EOT;
        echo $pre;
        // $db = new CTFdb();
		$db = new MySQL(HOST,DB_USER,DB_PASSWORD,DB_NAME);
		// return $db->query($query);
        $sql = "SELECT mailid, mfrom,msubject,mdate FROM mailbox m,players u WHERE u.id=m.userid AND u.name='$user'";
        echo "<!-- SQL: $sql -->";
        $result = $db->query($sql);

		while($row=$result->fetch()) {

			extract($row);
			echo "<tr onclick=\"javascript:setMail($mailid);\"><td>$mfrom</td><td>$msubject</td><td align=\"left\">$mdate</td></tr>";
		}
        // while (list($mailid, $from, $subject, $date) = $result->fetch()) {
            // echo "<tr onclick=\"javascript:setMail($mailid);\"><td>$from</td><td>$subject</td><td align=\"left\">$date</td></tr>";
        // }
        echo $post;
    }
}
?>