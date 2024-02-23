<?php

$scriptName = "CloudBackup";
include_once( "./plugins/$scriptName/includes/rclone.php" );

// Load the rclone config
Rclone::load();

// Get the oAuthName from the query string
$oAuthName = $_GET['name'];

// Delete the provider to the rclone config
$result = Rclone::deleteProvider($oAuthName);

echo json_encode($result);
?>
