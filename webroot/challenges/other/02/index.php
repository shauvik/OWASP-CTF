<?php
require_once('../../../../config/config.inc.php');

$challenge = new Challenge();
$challenge->startChallenge();
$pwd = $challenge->getDictionaryWord();
$token = $challenge->getToken();
$sid = session_id();
$outputfile = "Temp/test$sid.jpg";

if (isset($_POST['submit'])) {
	$code = util::getPost('password');
	if ($code == $pwd) {
		$challenge->mark();
		CTF::showAchieved();
		unlink($outputfile);
	}
}

// Code to add password inside of the image
function println(/* fmt, args... */) {
	$args = func_get_args();
	$fmt = array_shift($args);
	//vprintf($fmt . "\n", $args);
}


if (!file_exists($outputfile)) {
	$argv = array("this", "hacker_inside_intel.jpg", $outputfile, "the password=$pwd");
	require_once('Pel/PelDataWindow.php');
	require_once('Pel/PelJpeg.php');
	require_once('Pel/PelTiff.php');

	$prog = array_shift($argv);
	$error = false;

	if (isset($argv[0]) && $argv[0] == '-d') {
		Pel::$debug = true;
		array_shift($argv);
	}

	if (isset($argv[0])) {
		$input = array_shift($argv);
	} else {
		$error = true;
	}

	if (isset($argv[0])) {
		$output = array_shift($argv);
	} else {
		$error = true;
	}

	$description = implode(' ', $argv);

	ini_set('memory_limit', '32M');

	println('Reading file "%s".', $input);
	$data = new PelDataWindow(file_get_contents($input));

	if (PelJpeg::isValid($data)) {
		$jpeg = $file = new PelJpeg();

		$jpeg->load($data);

		$exif = $jpeg->getExif();

		if ($exif == null) {
			println('No APP1 section found, added new.');

			$exif = new PelExif();
			$jpeg->setExif($exif);

			$tiff = new PelTiff();
			$exif->setTiff($tiff);
		} else {
			println('Found existing APP1 section.');
			$tiff = $exif->getTiff();
		}
	} elseif (PelTiff::isValid($data)) {
		$tiff = $file = new PelTiff();
		$tiff->load($data);
	} else {
		println('Unrecognized image format! The first 16 bytes follow:');
		PelConvert::bytesToDump($data->getBytes(0, 16));
		exit(1);
	}

	$ifd0 = $tiff->getIfd();

	if ($ifd0 == null) {
		println('No IFD found, adding new.');
		$ifd0 = new PelIfd(PelIfd::IFD0);
		$tiff->setIfd($ifd0);
	}

	$desc = $ifd0->getEntry(PelTag::IMAGE_DESCRIPTION);

	if ($desc == null) {
		println('Added new IMAGE_DESCRIPTION entry with "%s".', $description);

		$desc = new PelEntryAscii(PelTag::IMAGE_DESCRIPTION, $description);

		$ifd0->addEntry($desc);
	} else {
		println('Updating IMAGE_DESCRIPTION entry from "%s" to "%s".',
		$desc->getValue(), $description);

		$desc->setValue($description);
	}

	println('Writing file "%s".', $output);
	file_put_contents($output, $file->getBytes());
}
?>

<?php echo "<img src=\"Temp/test$sid.jpg\" height=200 width=200>"; ?>

<form autocomplete="off" method="post">
    <input type="hidden" name="action" value="login" />
    <table>
        <tr><td>Code</td><td>:</td><td><input type="text" name="password" /></td></tr>
        <tr><td colspan=2/><td><input type="submit" class="button" name="submit" value="Submit" /></td></tr>
    </table>
</form>
<?php $challenge->stopChallenge(); ?>