#!/bin/sh
#stty /dev/ttyUSB0 9600
# while true; do
#  cat /dev/ttyUSB0 | awk '{print $0 > "/dev/stderr"; if (/^g/) { print "update kWh.rrd N:" $5;fflush();}}'  < /dev/ttyUSB0
#  sleep 1
# done


stty -F /dev/ttyUSB0 9600
cat /dev/ttyUSB0 | tee | grep --max-count=1 -F "g:~#" 

# stty -F /dev/ttyUSB0 9600 -echo -icrnl raw onlcr
# cat /dev/ttyUSB0 | tee /dev/tty | grep --max-count=12 -F "root@ramfs:"
