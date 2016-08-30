<?php 
	 $val = getLnt('741222');
	 echo "<pre>";print_r($val);

	function getLnt($zip){
		$url = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($zip)."&sensor=false";
		$result_string = file_get_contents($url);
		$result = json_decode($result_string, true);
		//$result1[]=$result['results'][0];
		//$result2[]=$result1[0]['geometry'];
		//$result3[]=$result2[0]['location'];
		//return $result3[0];
		return $result;
	}





?>


<!DOCTYPE html>
<html>
<head>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.35.2/css/bootstrap-dialog.min.css" rel="stylesheet" 
type="text/css" />
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"> </script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"> </script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.35.2/js/bootstrap-dialog.min.js"></script>
  <meta charset="utf-8" />
  <title>Bootstrap Dialog Test</title>
</head>
<body>

  <div style="padding:20px;">

    <p><button type="button" id="btnold">Old Dialog</button></p>

    <p><button type="button" id="btnnew">New Dialog</button></p>

  </div>

  <script>
 $(document).ready(function() {

     $("#btnold").click(function(){
         alert("This is the Old Dialog.");
     });

     $("#btnnew").click(function(){
        BootstrapDialog.show({
            title: 'Your New Dialog',
            message: 'This is the new dialog.',
            buttons: [{
                label: 'Close',
                action: function(dialogItself){
                    dialogItself.close();
                }
            }]
        });
     });

 });
  </script>
</body>
</html>

