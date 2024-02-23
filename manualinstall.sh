#!/bin/bash

mkdir -p /usr/local/emhttp/plugins/CloudBackup/
cp -R /mnt/user/Parents\ Files/unraid_dev/UnraidCloudBackup/src/usr/local/emhttp/plugins/CloudBackup/* /usr/local/emhttp/plugins/CloudBackup/

inotifywait -m -r -e modify,close_write,moved_to,create,delete .| 

while read -r directory events filename; do
    echo Change Detected...Updating $(date)
    cp -R /mnt/user/Parents\ Files/unraid_dev/UnraidCloudBackup/src/usr/local/emhttp/plugins/CloudBackup/* /usr/local/emhttp/plugins/CloudBackup/

done
