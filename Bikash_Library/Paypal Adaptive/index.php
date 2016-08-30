<?php
 include('paypal/config.php');
        include('paypal/paypal.class.php');
        include('paypal/paypal.adaptive.class.php');
        
         $PayPalConfig = array(
                            'Sandbox'               => 'TRUE',
                            'DeveloperAccountEmail' => 'nits.soutik@gmail.com',
                            'ApplicationID'         => 'APP-80W284485P519543T',
                            'DeviceID'              => '',
                            'IPAddress'             => $_SERVER['REMOTE_ADDR'],
                            'APIUsername'           => 'jb-us-seller_api1.paypal.com',
                            'APIPassword'           => 'WX4WTU3S8MY44S7F',
                            'APISignature'          => 'AFcWxV21C7fd0v3bYYYRCpSSRl31A7yDhhsPUU2XhtMoZXsWHFxu-RWy',
                            'APISubject'            => '',
                          );
        
                        $PayPal = new PayPal_Adaptive($PayPalConfig);
                        
                        $PayRequestFields = array(
				'ActionType'     => 'PAY',
		                'CancelURL'      => 'http://contractorphd.com',
		                'CurrencyCode'   => 'USD', 
                                'PaymentType'    => 'SERVICE', 
		                'ReturnURL'      => 'http://contractorphd.com/escrow/client/',
		                'SenderEmail'    => 'lovelybrotherbum@gmail.com',
                        );

                        $Receivers = array();
                        
                        $Receiver = array(
				'Amount'         => '20', 							
				'Email'          => 'nits.arpita@gmail.com',
				'InvoiceID'      => '',
                                'PaymentType'    => 'SERVICE', 
                                'PaymentSubType' => '',
                                'Primary'        => 'false',
                                'Phone'          => array('CountryCode' => '', 'PhoneNumber' => '', 'Extension' => '')
			);
                        array_push($Receivers,$Receiver);
                        
                        $Receiver = array(
				'Amount'         => '10', 							
				'Email'          => 'nit.abhishekb@gmail.com',
				'InvoiceID'      => '',
                                'PaymentType'    => 'SERVICE', 
                                'PaymentSubType' => '',
                                'Primary'        => 'false',
                                'Phone'          => array('CountryCode' => '', 'PhoneNumber' => '', 'Extension' => '')
			);
                        array_push($Receivers,$Receiver);
                        
                        
                        $SenderIdentifierFields = array(
                            'UseCredentials' => ''	
                            );
								
                        $AccountIdentifierFields = array(
                            'Email' => ''
                            );
                        
                        $PayPalRequestData = array(
                            'PayRequestFields'        => $PayRequestFields, 
                            'Receivers'               => $Receivers, 
                            'SenderIdentifierFields'  => $SenderIdentifierFields, 
                            'AccountIdentifierFields' => $AccountIdentifierFields
                            );
                        
                        $payResponse = $PayPal->Pay($PayPalRequestData); 



if ($payResponse['Ack']=='Success') {
    
    $fund['Fund']['pay_key']=$payResponse['PayKey'];
    $fund['Fund']['paid_through']='paypal';
    $fund['Fund']['payment_exec_status']=$payResponse['PaymentExecStatus'];
    //$this->Fund->id=$fund_id;
    //$this->Fund->save($fund);
   
    return redirect($payResponse['RedirectURL']);
}
else {
     echo "<pre>";
    print_r($payResponse);
    exit;
    echo "Faild";
}
exit;



function check_payment($paykey)
{
    $PayPalConfig = array(
                                        'Sandbox'               => 'TRUE',
                                        'DeveloperAccountEmail' => 'nits.soutik@gmail.com',
                                        'ApplicationID'         => 'APP-80W284485P519543T',
                                        'DeviceID'              => '',
                                        'IPAddress'             => $_SERVER['REMOTE_ADDR'],
                                        'APIUsername'           => 'jb-us-seller_api1.paypal.com',
                                        'APIPassword'           => 'WX4WTU3S8MY44S7F',
                                        'APISignature'          => 'AFcWxV21C7fd0v3bYYYRCpSSRl31A7yDhhsPUU2XhtMoZXsWHFxu-RWy',
                                        'APISubject'            => ''
					);

    $PayPal = new PayPal_Adaptive($PayPalConfig);
	
	$PaymentDetailsFields = array(
            // Add Your PayKey
					'PayKey' => $paykey, 
				);
	$PayPalRequestData = array(
		            'PaymentDetailsFields'=>$PaymentDetailsFields
		        );
	$PayPalResult = $PayPal->PaymentDetails($PayPalRequestData);
//pr($PayPalResult);exit;
          
        echo "success";
        echo "<pre>";
        print_r($PayPalResult);
        exit;
}
?>