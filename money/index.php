<?php
    $from   = 'USD';
    $to     = 'INR';
    $url = 'http://finance.yahoo.com/d/quotes.csv?e=.csv&f=sl1d1t1&s='. $from . $to .'=5';
    $handle = fopen($url, 'r');
 
    if ($handle) {
        $result = fgets($handle, 4096);
        fclose($handle);
    }
 
    $allData = explode(',',$result); 
    $currencyValue = $allData[1];
 
    $responseTxt = 'Value of 1 '.$from.' in '.$to. ' is ' .$currencyValue;
?>