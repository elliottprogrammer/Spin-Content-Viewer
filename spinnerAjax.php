<?php
// Get $_POST values
$spin = isset($_POST['spin']) ? $_POST['spin'] : false;
$bracket = isset($_POST['bracket']) ? explode(',', $_POST['bracket']) : false;

$selectedCity = isset($_POST['city']) ? $_POST['city'] : false;
$selectedState = isset($_POST['state']) ? $_POST['state'] : false;

$spin_seperator = '|';
include('arrays.php');
include('func.php');

if ($spin) {
	if ($bracket) {
		$output = Spinner::flat($spin, false, false, $bracket[0], $bracket[1], $spin_seperator);
	} else {
		$output = Spinner::flat($spin, false, false, '\[\[\[', '\]\]\]', '|');
	}

	$output = grammarFix(smartReplace($output, $replacement));
}

echo json_encode($output);

?>