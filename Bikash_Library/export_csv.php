<?php
ob_start();
session_start();

$hostname="localhost";
$dbname="uploadcsv";
$username="root";
$password="root";


$link=mysql_connect($hostname,$username,$password) or die("Error in Connection. Check Server Configuration.");
mysql_select_db($dbname,$link) or die("Database not Found. Please Create the Database.");

?>
<?php 
if(isset($_POST['submit']))
{
    $query = mysql_query('select * from students');
    $output='';

    $output .='Name, Email';

    $output .="\n";

    if(mysql_num_rows($query)>0)
    {
        while($result = mysql_fetch_array($query))
        {	
           $name = $result['name'];
           $email = $result['email'];
           
            $output .='"'.$name.'","'.$email.'"';
            $output .="\n";
        }
    }



    $filename = "myFile".time().".csv";

    header('Content-type: application/csv');

    header('Content-Disposition: attachment; filename='.$filename);



    echo $output;

    //echo'<pre>';

    //print_r($result);

    exit;
	
	
}
?>
<!DOCTYPE html>
<html>
    
    <head>
        <title>Upload Student By CSV</title>
        <!-- Bootstrap -->
        
    </head>
    
    <body>
         <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">              
                                      <fieldset>
                                        <legend>Export Students By CSV</legend>
                                       
                                       
                                        
                                        <div class="form-actions" style="margin-top:120px;">
                                          <button type="submit" class="btn btn-primary"  name="submit">Export</button>
                                        </div>
                                      </fieldset>
                                    </form>
    </body>

</html>
