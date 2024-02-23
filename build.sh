#!/bin/bash
SCRIPT="CloudBackup"
DATE=$(date +%Y.%m.%d)

if [ ! -z $1 ]; then
	VER="$1"

else
	for VER in {001..999}; do
		if [ ! -e $SCRIPT.$DATE-$VER.txz ]; then
			break;
		fi
	done

fi


FILE=$SCRIPT.$DATE-$VER.txz

cd src
tar -vcJf ../$FILE usr
cd ..

MD5=$(md5sum $FILE | awk '{print $1}')


sed -ri 's/(<!ENTITY version.*").*(">)/\1'$DATE'-'$VER'\2/g' $SCRIPT.plg
sed -ri 's/(<!ENTITY md5.*").*(">)/\1'$MD5'\2/g' $SCRIPT.plg
