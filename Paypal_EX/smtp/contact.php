<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SMTP email sending example</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>

	<div>
	    <div></div>
		<?php
		
		 if(isset($_POST['submit']))
		 if($_POST['submit']!="")
		 include("send_mail.php");  // you must set the configuration of SMTP at send_mail.php file 
		 
		 ?>
		
		<div>
		     <form name="frm" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data">
			<div> Name:</div><div><input name="fullname" type="text" value=""/></div>
			<div> Phone:</div><div><input name="phone" type="text" value=""/></div>
			<div> Email:</div><div><input name="email" type="text" value=""/></div>
			<div> Comment:</div><div><textarea name="comment"></textarea></div>
			<div>&nbsp;</div><div><input type="submit" name="submit" value="Submit" /></div>
			</form>
		</div>
	    	
	</div>
</body>
</html>
