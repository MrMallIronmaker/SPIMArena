<?php
	// get netid
	$netId = substr($_SERVER["eppn"], 0, strpos($_SERVER["eppn"], '@'));
	// get hash_name from netid
	$words_file = file("words");
	$binary = hash("md5", $netId, TRUE);
	$dec = unpack('L', $binary)[1];
	$hash1 = $dec % 1024;
	$hash2 = ($dec / 1024) % 1024;
	$hash_name = $words_file[$hash1 + 1] . $words_file[$hash2 + 1];
?>
