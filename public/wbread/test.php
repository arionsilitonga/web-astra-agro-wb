<?php
stream_set_blocking(STDIN, 0);
$line = trim(fgets(STDIN));

$file = 'wboutput.txt';
$current = file_get_contents($file);
$current .= $line;
file_put_contents($file, $current);

/*while($line = fgets(STDIN)){  
    echo $line;  
    $file = 'wboutput.txt'; 
    $current = file_get_contents($file); 
    $current .= $line; 
    file_put_contents($file, $current);  
}*/
?>
