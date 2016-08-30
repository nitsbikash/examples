<?php 

	if(isset($_POST['submit'])){
		$from   = $_POST['from_currency']; /*change it to your required currencies */
		$to     = $_POST['to_currency'];
		$url = 'http://finance.yahoo.com/d/quotes.csv?e=.csv&f=sl1d1t1&s='. $from . $to .'=X';
		$handle = @fopen($url, 'r');
		 
		if ($handle) {
			$result = fgets($handle, 4096);
			fclose($handle);
		}
		$allData = explode(',',$result); /* Get all the contents to an array */
		$currencyValue = $allData[1];
		 
		$responseTxt = 'Value of 1 '.$from.' in '.$to. ' is ' .$currencyValue;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Currency Conversion Using PHP and Yahoo Finance API</title>
<style type="text/css">
.web{
	font-family:tahoma;
	size:12px;
	top:10%;
	border:1px solid #CDCDCD;
	border-radius:10px;
	padding:10px;
	width:45%;
	margin:auto;
	height:100%;
}
h1{
	margin:3px 0;
	font-size:13px;
	text-decoration:underline;
}
.tLink{
	font-family:tahoma;
	size:12px;
	padding-left:10px;
	text-align:center;
}
.success{
	color:#009900;
}
.error{
	color:#FF0000;
}
.talign_right{
	text-align:right;
}
.username_availability_result{
	display:block;
	width:auto;
	float:left;
	padding-left:10px;
}
.input{
	float:left;
}
</style>
</head>
<body>

<div class='tLink'><strong>Tutorial Link:</strong> <a href='http://www.stepblogging.com/how-to-get-currency-rate-using-php-and-yahoo-finance-api/'target='_blank'>Click Here</a></div><br/>
<form method='POST'>
<div class='web'>
	<h1>Currency Conversion Using PHP and Yahoo Finance API</h1> <br />
	From Currency : 
	<select name='from_currency'>
		<option value='USD'>USD</option>
		<option value='INR'>INR</option>
		<option value='EUR'>EUR</option>
		<option value='GBP'>GBP</option>
		<option value='CAD'>CAD</option>
	</select>
	To Currency : 
	<select name='to_currency'>
		<option value='USD'>USD</option>
		<option value='INR'>INR</option>
		<option value='EUR'>EUR</option>
		<option value='GBP'>GBP</option>
		<option value='CAD'>CAD</option>
	</select>
	<input type='submit' name='submit' value='SUBMIT' />
	<?php
		if(isset($responseTxt)){
			echo '<br /><span style="color:#FF0000;">'.$responseTxt.'</span><br>';
		}
	?>
</div>
</form>
</body>
</html>


