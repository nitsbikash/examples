<?php

echo $st = date("Y-m-d",$_POST['start']/1000); echo "--";
echo $end = date("Y-m-d",$_POST['end']/1000);
echo "<pre>"; print_r($_POST); exit;


$st = date("Y-m-d h:i:s",$_POST['start']/1000); 
$end = date("Y-m-d h:i:s",$_POST['end']/1000);


// Values received via ajax
$title = $_POST['title'];
$start = $st;
$end = $end;

// connection to the database
try {
$bdd = new PDO('mysql:host=localhost;dbname=fullcalendar', 'root', 'root');
} catch(Exception $e) {
exit('Unable to connect to database.');
}

// insert the records
$sql = "INSERT INTO evenement (title, start, end) VALUES (:title, :start, :end )";
$q = $bdd->prepare($sql);
$q->execute(array(':title'=>$title, ':start'=>$start, ':end'=>$end));
?>
