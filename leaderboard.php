<?php

$bots = array();
$wins = array();
$losses = array();
$ties = array();
$newwins = array();
$newlosses = array();
$newties = array();
$files = array_diff(scandir('bots/'), array(".", "..", "index.php"));
$index = 0;
foreach($files as $file) {
	$bots[$index++] = $file;
	$wins[$file] = 0;
	$losses[$file] = 0;
	$ties[$file] = 0;
	$newwins[$file] = 0;
	$newlosses[$file] = 0;
	$newties[$file] = 0;
}

$files = scandir('results/');
foreach($files as $file) {
	if (!file_exists('results/' . $file . '/winner.txt')) {
		continue;
	}
	// somehow scan the file
	// grab winner from LAST line of file
	$myfile = fopen('results/' . $file . '/winner.txt', "r") or die("Unable to open file!");
	$line = fgets($myfile);
	$winner = substr(substr($line, strpos($line, ':') + 2), 0, -1);
	$line = fgets($myfile);
	$bot1 = substr($line, strpos($line, ':') + 2, -1);
	$line = fgets($myfile);
	$bot2 = substr($line, strpos($line, ':') + 2, -1); // -1 to trim the last char
	fclose($myfile);
	// add to arrays
	if ($winner == $bot1)
	{
		$wins[$bot1] = $wins[$bot1] + 1;
		$losses[$bot2] = 1 + $losses[$bot2];
		if (filemtime("results/" . $file) > filemtime("bots/" . $bot1))
			$newwins[$bot1] = $newwins[$bot1] + 1;
		if (filemtime("results/" . $file) > filemtime("bots/" . $bot2))
			$newlosses[$bot2] = $newlosses[$bot2] + 1;
	}
	else if ($winner == $bot2)
	{
		$wins[$bot2] = $wins[$bot2] + 1;
		$losses[$bot1] = $losses[$bot1] + 1;
		if (filemtime("results/" . $file) > filemtime("bots/" . $bot2))
			$newwins[$bot2] = $newwins[$bot2] + 1;
		if (filemtime("results/" . $file) > filemtime("bots/" . $bot1))
			$newlosses[$bot1] = $newlosses[$bot1] + 1;
	}
	else
	{
		$ties[$bot1] += 1;
		$ties[$bot2] += 1;
		if (filemtime("results/" . $file) > filemtime("bots/" . $bot1))
			$newties[$bot1] = $newties[$bot1] + 1;
		if (filemtime("results/" . $file) > filemtime("bots/" . $bot2))
			$newties[$bot2] = $newties[$bot2] + 1;
	}
}

?>

<html>
<head>
<script src="sorttable.js"></script>
<link rel="stylesheet" type="text/css" href="stylesheet.css">

</head>
<body>

<?php
require("header.php");
?>
<div class="bodyer">
The first three columns show the record of the
most current version of the robot.<br>
Click a header to sort by that header.
<br><br>
<table class="sortable">
<tr><th>Bot Name</th>
<th>Wins</th><th>Losses</th><th>Ties</th>
<th>All Wins</th><th>All Losses</th>
<th>All Ties</th><th>Modified Time</th></tr>
<?php foreach($bots as $bot)
{
	echo("<tr><td>" . $bot . "</td>");
	echo("<td>" . $newwins[$bot] . "</td>");
	echo("<td>" . $newlosses[$bot] . "</td>");
	echo("<td>" . $newties[$bot] . "</td>");
	echo("<td>" . $wins[$bot] . "</td>");
	echo("<td>" . $losses[$bot] . "</td>");
	echo("<td>" . $ties[$bot] . "</td>");
	echo("<td>" . date("m-d-y, H:i:s", filemtime("bots/" . $bot) ) . "</td></tr>");
}
?>

</table>
</div>
</body>
</html>
