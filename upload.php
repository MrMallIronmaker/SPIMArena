<?php

require "user.php";


$error_string = "";
if ($_FILES)
{
	$target_dir = "pending/";
	$bots_dir = "bots/";
	$bots_file = $bots_dir . $netId;
	$target_file = $target_dir . $netId;
	$uploadOk = 1;

	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 50000) {
    	$error_string = "<p style='color:red'>Sorry, your file is too large.</p>";
    	$uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk === 0) {
	// if everything is ok, try to upload file
	} else {
	    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    	        $error_string = "<p style='color:green'>The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded and renamed as " . $netId . ".</p>";
		shell_exec("./bot_test.sh " . $netId . " > out_bot_test.txt 2>&1 &");
    	    } else {
        	$error_string = "<p style='color:red'>Sorry, there was an error uploading your file.</p>";
    	    }
	}
}

?>

 <!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="stylesheet.css"/>
</head>
<body>

<?php
require("header.php");
?>
<div class="bodyer">
<p>
<form class="pretty" action="upload.php" method="POST" enctype="multipart/form-data">
    <label>Select bot to upload:</label>
	<input type="file" name="fileToUpload" id="fileToUpload"/>
	<label>&nbsp</label><input type="submit" value="Upload Bot" name="submit"/>
</form>
<?php echo $error_string; ?>
<br>
<h1>Pending Robots:</h1>
<?php $files=array_diff(scandir("pending"), array(".", "..", "index.php", ".gitignore"));
	if($files)
	{
		foreach ($files as $file)
		{
			echo($file . "<br>");
		}
	}
	else
	{
		echo("No pending bots.");
	}
?>
<br><br>
Check <a href="leaderboard.php">the leaderboard</a> to see if your bot passed a simple test. The name will be your netID.<br><br>
Basically, we check for some sort of debug output between six and eight seconds 
of running the bot. 
If your bot does that, it will 
pass the test. If QtSpimbot cannot parse the file correctly, you've got a problem.
Note that this test is mostly unrelated to the baseline test for 60% credit.
</p></div>
</body>
</html> 

