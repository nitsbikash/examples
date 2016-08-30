<?php
require_once 'IP2Location.php';

error_reporting(E_ALL);
echo ini_get("memory_limit")."\n";
ini_set("memory_limit","800M");
ini_set('max_execution_time', 0);

//Timer started
$m_start = microtime(true);

//Load file using FILE_IO method for testing
//$db = new \IP2Location\Database('./databases/IP2LOCATION-LITE-DB11.BIN', \IP2Location\Database::FILE_IO);
$db = new \IP2Location\Database('./databases/IP2LOCATION-LITE-DB11.IPV6.BIN', \IP2Location\Database::FILE_IO);

//Get IP address for testing
$ip_list = explode("\r\n", file_get_contents('ip_list.txt'));

//Run the testing
$ip_data = array();
foreach($ip_list as $ip){
        //get all records
        $records = $db->lookup($ip, \IP2Location\Database::ALL);

        array_push($ip_data, array('ipaddr'=>$records['ipAddress'],
                                   'countryCode'=>$records['countryCode'],
                                   'regionName'=>$records['regionName'],
                                    'cityName'=>$records['cityName'],
                                     'lat'=>$records['latitude'],
                                      'long'=>$records['longitude']
                                    ));
}

//Timer stopped
$m_end = microtime(true);

//Get the expected result data for validation
$ip_result = explode("\r\n", file_get_contents('ip_result.txt'));
if (count($ip_data) != count($ip_result)){
        echo 'The ip list and result count was not tally. Please recheck your code.' . "\n";
        return;
}
else{
        //Validate the result
        $idx=0;
        foreach($ip_result as $row){
                $row_data = explode("\t", $row);
                if ($row_data[0] != $ip_data[$idx]['countryCode'] || $row_data[1] != $ip_data[$idx]['regionName'] || $row_data[2] != $ip_data[$idx]['cityName']){
                        echo 'Error found at record #' . ($idx+1) . "\n";
                        //return;
                }

                $idx+=1;
        }
}

echo memory_get_usage()."\n";

//Output the time taken (Note: Time taken not including the time used for result validation)
$time_taken = $m_end - $m_start;
echo 'Time taken: ' . $time_taken . ' seconds' . "\n";
file_put_contents('testing_report.txt', 'Time taken: ' . $time_taken . ' seconds' . "\n");
?>
