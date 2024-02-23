<?php

$scriptName = "CloudBackup";
include_once( "./plugins/$scriptName/includes/Cron.php" );

// Load the rclone config
Cron::load("/boot/config/plugins/CloudBackup/monitor.cron");

// Get the oAuthName from the query string
$id = $_GET['id'];

// Delete the provider to the rclone config
$result = Cron::deleteJobById( $id );

echo json_encode($result);
?>
