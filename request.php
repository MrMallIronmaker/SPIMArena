<?php
require "user.php";

$error_string = "";
if ($_POST){
	if ($netId == "" || $_POST["bot2"] == "")
	{
		$error_string = "Bots not found.";
		goto end;
	}
	// check if bots exist
	if (!file_exists("bots/" . $netId) || !file_exists("bots/" . $_POST["bot2"]))
	{
		$error_string = "Bots not found.";
		goto end;
	}
	// check if too many requests have been made
	$lines = file("requests.txt");
	$count = 0;
	foreach($lines as $line) {
		if (strpos($line, $netId) === 0)
		{
			$count++;
		}
	}
	$REQUEST_LIMIT = 5;
	if ($count >= $REQUEST_LIMIT) 
	{
		$error_string = "Too many requests made. Request limit: " . $REQUEST_LIMIT;
		goto end;
	}	
	file_put_contents("requests.txt", $netId . " vs. " . $_POST["bot2"]. "\n", FILE_APPEND | LOCK_EX);
	shell_exec("./request.sh > /dev/null 2>&1 &");
}
end:
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="stylesheet.css">

</head>
<body>

<?php
require("header.php");
?>

<div class="bodyer">
<h1>Request Faceoff</h1><br>
<form style="text-align:center" action="request.php" method="POST">
<label>Bot to challenge: </label><input type="text" name="bot2">
<label>&nbsp</label><input type="submit" value="Faceoff!">
</form>
<br>

<?php echo $error_string ?>

<h1>Pending Requests</h1>
<?php
$lines = file("requests.txt");
foreach($lines as $line) {
echo "<br>";
print htmlspecialchars($line);
}
if (empty($lines)) {
	echo "No pending requests.";
}
?>
</div>

</body>
</html>
