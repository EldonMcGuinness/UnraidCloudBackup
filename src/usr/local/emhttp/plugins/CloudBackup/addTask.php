<?php

$scriptName = "CloudBackup";
include_once( "./plugins/$scriptName/includes/Cron.php" );

$result = [
    "status" => null,
    "message" => null
];


// Load the Cron config
Cron::load( "/boot/config/plugins/CloudBackup/monitor.cron" );

// Create the job
$job = [
    "schedule" => $_GET['schedule'],
    "command" => "/usr/bin/rclone " . $_GET['action'] . " " . $_GET['source'] . " " . $_GET['provider'] . ":" . $_GET['destination'] . " --config /boot/config/plugins/CloudBackup/rclone.conf --drive-chunk-size 128M --log-file /var/log/CloudBackup.rclone.".$_GET['provider'].".log --log-level INFO --stats 30s",
];

if ($job['command'] == null) {
  $result = [
    "status" => "error",
    "message" => "No command provided"
  ];
}

if ($job['schedule'] == null) {
  $result = [
    "status" => "error",
    "message" => "No schedule provided"
  ];
}

// Add the job to the Cron config
if (Cron::addJob( $job )){
    $result = [
        "status" => "success",
        "message" => "Task added successfully"
    ];
}else{
    $result = [
        "status" => "error",
        "message" => "Failed to add task"
    ];
}

echo json_encode($result);
?>