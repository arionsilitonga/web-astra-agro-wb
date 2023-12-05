#!/bin/bash
count=1
while read line; do
    if [ "$line" != "EOF" ]; then
        output="$line"
        echo "output:" "$line"
        echo "panjang karakter:" ${#output}
        # if [ ${#output} -eq $1 ]; then 
        #     echo "$line" #| php test.php
        #     break
        # fi
    else
        break
    fi
    
    if [ $count -eq 3 ]; then
        break
    fi
    count=$(( $count + 1 ))
done < /dev/ttyUSB1