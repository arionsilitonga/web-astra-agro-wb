#!/bin/bash
#stty -F /dev/ttyUSB0 9600 -echo -icrnl raw
count=1
while read line; do
    if [ "$line" != "EOF" ]; then
        output="$line"
        # echo "output:" "$line"
        # echo "panjang karakter:" ${#output}
        # if [ ${#output} -eq $1 ]; then 
        #     echo "$line" #| php test.php
        #     break
        # fi
        echo "$line"
        
    else
        break
    fi
    
    if [ $count -eq 1 ]; then
        break
    fi
    count=$(( $count + 1 ))
done < /dev/ttyUSB0
#!/bin/sh
# stty -F /dev/ttyUSB0 9600

# cd /home/masterwb/wbread
# while true; do
#  cat /dev/ttyUSB0 | awk '{ print $0 > "/dev/stderr"; if (/^g/) { print "update kWh.rrd N:" $5 } ; }' | php test.php
#  sleep 1
# done