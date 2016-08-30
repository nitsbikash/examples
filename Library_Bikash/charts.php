	<?php

include("includes/user_utils.php");?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<meta charset=utf-8 />
<title>Morris.js Line Chart Example</title>
</head>
<body >
	

	
	
	
  <?php 
 

    /**
	 * INSERT INTO `cron` (`id`, `cron_time`, `images_processed`) VALUES
(1, '2015-02-05 05:05:34', 2),
(2, '2015-02-05 04:00:02', 5),
(3, '2015-02-04 15:05:34', 8),
(4, '2015-02-04 12:05:34', 5),
(5, '2015-02-04 22:05:34', 4),
(6, '2015-02-04 21:05:34', 3);
	 * 
	 * 
	 * 
	 * 
     * Fetches all rows from a MySQL result set as an array of arrays
     *
     * Requires PHP >= 4.3.0
     *
     * @param   $result       MySQL result resource
     * @param   $result_type  Type of array to be fetched
     *                        { MYSQL_NUM | MYSQL_ASSOC | MYSQL_BOTH }
     * @return  mixed
     */
    function mysql_fetch_all ($result, $result_type = MYSQL_BOTH)
    {
        if (!is_resource($result) || get_resource_type($result) != 'mysql result')
        {
            trigger_error(__FUNCTION__ . '(): supplied argument is not a valid MySQL result resource', E_USER_WARNING);
            return false;
        }
        if (!in_array($result_type, array(MYSQL_ASSOC, MYSQL_BOTH, MYSQL_NUM), true))
        {
            trigger_error(__FUNCTION__ . '(): result type should be MYSQL_NUM, MYSQL_ASSOC, or MYSQL_BOTH', E_USER_WARNING);
            return false;
        }
        $rows = array();
        while ($row = mysql_fetch_array($result, $result_type))
        {
            $rows[] = $row;
        }
        return $rows;
    }

  
//  $rows = '';
$query = "SELECT * FROM cron ORDER BY id DESC LIMIT 0,10";
$result = mysql_query($query);
$num_rows=mysql_num_rows($result);
//
$total_rows =  $num_rows;
//$rows = mysql_fetch_array($result);

if($result) 
{
    $rows = mysql_fetch_all($result, MYSQL_ASSOC);
}

 //$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
//echo 'san';
//echo $row['cron_time'];
//$rows['cron_time']=$row['cron_time'];
//echo json_encode($rows);

//if($result) 
//{
//    $rows = $rows.mysql_fetch_array($result);
//}

?>
  
 



<div id="morris-line-chart"></div>

<script>
 
Morris.Line({
    // ID of the element in which to draw the chart.
    element: 'morris-line-chart',
 
    // Chart data records -- each entry in this array corresponds to a point
    // on the chart.
    data: <?php echo json_encode($rows);?>,
 
    // The name of the data record attribute that contains x-values.
    xkey: 'cron_time',
 
    // A list of names of data record attributes that contain y-values.
    ykeys: ['images_processed'],
 
    // Labels for the ykeys -- will be displayed when you hover over the
    // chart.
    labels: ['Images Processed'],
 
    lineColors: ['#0b62a4'],
    xLabels: 'hour',
 
    // Disables line smoothing
    smooth: true,
    resize: true
});
</script>
</body>
</html>