#!/bin/sh
#/bin/rm -f capture.txt; 
#( echo "poweroff"; sleep 0.4; echo 0.5 -ne "\x01x\r" ) | sudo minicom -D /dev/ttyUSB0;    


stty -F /dev/ttyUSB0 9600
cat /dev/ttyUSB0 | tee | grep --max-count=1 -F "g:~#" 