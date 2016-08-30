<?php
			$sandbox = TRUE;
			$environment = "sandbox";
		
			function PPHttpPost($methodName_, $nvpStr_) {
				 global $environment;
				 // Set up your API credentials, PayPal end point, and API version.
				 $API_UserName = urlencode('sourab.paul-facilitator_api1.xigmapro.com');
				 $API_Password = urlencode('N8H56EETXWQ4XEDZ');
				 $API_Signature = urlencode('AFcWxV21C7fd0v3bYYYRCpSSRl31AwiKBi9nZVR-A.bldosJQwm1r8Rs');
				 $API_Endpoint = "https://api-3t.paypal.com/nvp";
				 if("sandbox" === $environment || "beta-sandbox" === $environment) {
				  //$API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
				  $API_Endpoint="https://api-3t.sandbox.paypal.com/nvp";
				 }
				 $version = urlencode('56.0');
				 // Set the API operation, version, and API signature in the request.
				 $nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";
				 // Set the curl parameters.
				 $ch = curl_init();
				 curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
				 curl_setopt($ch, CURLOPT_VERBOSE, 1);
				 // Turn off the server and peer verification (TrustManager Concept).
				 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
				 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				 curl_setopt($ch, CURLOPT_POST, 1);
				 // Set the request as a POST FIELD for curl.
				 curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
				 // Get response from the server.
				 $httpResponse = curl_exec($ch);
				 if(!$httpResponse) {
				  	exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
				 }
				 // Extract the response details.
				 $httpResponseAr = explode("&", $httpResponse);
				 $httpParsedResponseAr = array();
				 foreach ($httpResponseAr as $i => $value) {
					  $tmpAr = explode("=", $value);
					  if(sizeof($tmpAr) > 1) {
					   	$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
					  }
				 }
				 if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
				  	exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
				 }
				 return $httpParsedResponseAr;
			}
			// Set request-specific fields.
			//$paymentType = urlencode($_POST['Authorization']);    // or 'Sale'
			$paymentType = "Sale";
			//$firstName =urlencode($_POST['firstname']);
			//$lastName = urlencode($_POST['lastname']);
			$creditCardType = 'visa';
			$creditCardNumber = '4032031532037800';
			$expDateMonth = '10';
			// Month must be padded with leading zero
			$padDateMonth = urlencode(str_pad($expDateMonth, 2, '0', STR_PAD_LEFT));
			$expDateYear = '2020';
			$cvv2Number = '123';
			//$address1 = urlencode($_POST['address']);
			//$address2 = '';
			//$city = urlencode($_POST['city']);
			//$state = urlencode($_POST['state']);
			//$zip = urlencode($_POST['zip']);
			$country = 'US'; // US or other valid country code
			$amount = '100'; //actual amount should be substituted here
			$currencyID = 'USD';// or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
			
			// Add request-specific fields to the request string.
			
			$nvpStr = "&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber".
			"&EXPDATE=".$padDateMonth.$expDateYear."&CVV2=$cvv2Number&COUNTRYCODE=$country&CURRENCYCODE=$currencyID";
			
			// Execute the API operation; see the PPHttpPost function above.
			$httpParsedResponseAr = PPHttpPost('DoDirectPayment', $nvpStr);     	
			      	      	

			echo '<pre>';
			if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
				exit('Direct Payment Completed Successfully: '.print_r($httpParsedResponseAr, true));
			} else  {
			    exit('DoDirectPayment failed: ' . print_r($httpParsedResponseAr, true));
			}  
      	
      	?>
  
