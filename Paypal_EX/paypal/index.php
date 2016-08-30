<html>
<body>
<?php 

if(!empty($_GET))
{
	$clientId = 'ARcd6fQ3XLiN_L9ntuiDRr4OxJ-qqYMzq67fjhzpr0_gYsUzOQNBI68p8CFQwWNGbdAugwJGUylwvLCO';
		$clientSecret = 'EEWnSKUylJG6cpevk2Sj7zFdToKvz8xpOoS39JyvEYksr46zVwOgPraMKy7P9VHI_QHIzFz4aFftb-uA';
		$requestData = '?grant_type=authorization_code&code='.$_GET['code'].'&return_url=http://107.170.152.166/team4/niwi/users/edit_profile/';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://api.sandbox.paypal.com/v1/identity/openidconnect/tokenservice'.$requestData);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_USERPWD, $clientId.":".$clientSecret);
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

$result = curl_exec($ch);
if(empty($result))die("Error: No response.");
else
{
    $json = json_decode($result);
    //print_r($json->access_token);
    echo "<pre>";
    print_r($result);
    exit;
}

curl_close($ch);

$ch = curl_init();
$header = array();
$header[] = "Content-Type:application/json";
$header[] = "Authorization:Bearer ".$json->access_token;
	curl_setopt($ch, CURLOPT_URL, 'https://api.sandbox.paypal.com/v1/identity/openidconnect/userinfo/?schema=openid');
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_USERPWD, $clientId.":".$clientSecret);
//curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

$result = curl_exec($ch);
var_dump($result);
if(empty($result))die("Error: No response.");
else
{
    $json = json_decode($result);
    print_r($json);
}

curl_close($ch);

		/* // setting some variables here.
		$clientId = 'ARcd6fQ3XLiN_L9ntuiDRr4OxJ-qqYMzq67fjhzpr0_gYsUzOQNBI68p8CFQwWNGbdAugwJGUylwvLCO';
		$clientSecret = 'EEWnSKUylJG6cpevk2Sj7zFdToKvz8xpOoS39JyvEYksr46zVwOgPraMKy7P9VHI_QHIzFz4aFftb-uA';
		$requestData = '?grant_type=authorization_code&code='.$_GET['code'].'&return_url=http://107.170.152.166/team4/paypal/';

		// here we exchange the authorization code with access and refresh tokens.
		$response = \Httpful\Request::get('https://api.sandbox.paypal.com/v1/identity/openidconnect/tokenservice' . $requestData)
		->authenticateWith($clientId, $clientSecret)
	    ->send();

	    $jsonResponse = json_decode($response->raw_body);

	    // checking out for errors.
	    if(isset($jsonResponse->error))
	    {
	    	die('Error: just got some problems during operations. Try again.');
	    }
 */
	    // getting user data, using the Identity APIs.
	    /* $response = \Httpful\Request::get('https://api.sandbox.paypal.com/v1/identity/openidconnect/userinfo/?schema=openid')
	    ->contentType("application/json")
	    ->authorization($jsonResponse->access_token)
		->authenticateWith($clientId, $clientSecret)
	    ->send();

	    // user data is here!
	    $user = json_decode($response->raw_body);
		
	print_r($user); */
} ?>
<span id='lippButton'>test</span>
</body>

<script src='https://www.paypalobjects.com/js/external/api.js'></script>
<script>
paypal.use( ['login'], function (login) {
  login.render ({
    "appid":"ARcd6fQ3XLiN_L9ntuiDRr4OxJ-qqYMzq67fjhzpr0_gYsUzOQNBI68p8CFQwWNGbdAugwJGUylwvLCO",
    "authend":"sandbox",
    "scopes":"profile email address phone",
    "containerid":"lippButton",
    "locale":"en-us",
    "returnurl":"http://107.170.152.166/team4/niwi/users/edit_profile/"
  });
},function(data){
	console.log("Paypalsssssssss",data); 
});
</script>
</html>
