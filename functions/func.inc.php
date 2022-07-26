<?php 
define("LOGIN", "6i7griyhtzde6qjg3ce7b1qy42a32bv03l3gnye1x70s3.apps.vivapayments.com");
define("PASSWORD" , "iYMWP5ce97xZh26y88Q027Tjds5nGH");
//prod:
///define("BASE_URL_API" ,"https://api.vivapayments.com/");

//demo:
define("BASE_URL_API" ,"https://demo-api.vivapayments.com/");

//producao:
//define("BASE_URL" , "https://accounts.vivapayments.com/connect/token");

// demo:
define("BASE_URL" , "https://demo-accounts.vivapayments.com/connect/token");

define("CURRENCY_CODE" , "826");
define("WEBSITEAPP", "5851");

if(!function_exists('requestBasic')) { 
   function requestBasic(){
		//$credentials = "generic_acquiring_client.apps.vivapayments.com:generic_acquiring_client"; 
    
		$login = LOGIN;
		$password = PASSWORD;
		$credentials = "$login:$password";
		
		$ch = curl_init();
		$options = array(
		    CURLOPT_URL => BASE_URL,
		    CURLOPT_POST => 1,
		    CURLOPT_HEADER => false,
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
		    CURLOPT_USERPWD => $credentials,
		    CURLOPT_HTTPHEADER => array(
		        'Accept: application/json',
		        'Content-Type: application/x-www-form-urlencoded'
		    ),
		    CURLOPT_POSTFIELDS => "grant_type=client_credentials"
		);

		curl_setopt_array($ch, $options);
		$result = curl_exec($ch);
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
	
		return json_decode($result,true);
  }
}


if(!function_exists('requestBearer')) { 
   function requestBearer($accesstoken, $cvc, $amount, $number ,$holdername,$expirationyear,$expirationmonth, $sessionredirecturl){
     $url = BASE_URL_API."nativecheckout/v2/chargetokens";

   	 $postfields = json_encode(array(
   	 	"Cvc"    =>  $cvc,
   	 	"Amount" => $amount,
   	 	"Number" =>$number,
   	 	"Holdername" =>$holdername, 
   	 	"ExpirationYear" =>$expirationyear,   
   	 	"ExpirationMonth" =>$expirationmonth, 
   	 	"SessionRedirectUrl" =>$sessionredirecturl 
   	 ));
 
	  $ch = curl_init();
	  curl_setopt_array($ch, array(
	  CURLOPT_URL =>$url,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS =>$postfields,
	 
	  CURLOPT_HTTPHEADER => array(
	      "Content-Type: application/json",
	      "Authorization: Bearer {$accesstoken}",
	      "Content-Length: ". strlen($postfields)),
	  ));

	$result = curl_exec($ch);
	curl_close($ch);
	return json_decode($result,true);
	 
  }
}

if(!function_exists('curlTransactions')) { 
   function curlTransactions($token , $chargeToken, $amount,$mail,$phone,$name,$merchantTrns ,$customerTrns){
 	   if(isset($token) && isset($chargeToken) ){

			    $url = BASE_URL_API.'nativecheckout/v2/transactions';
 			  
			    $headerArgs = array(
			        "Authorization: Bearer ".$token,
			        "Content-Type: application/json"
			    );
			  
			    $postArgs = json_encode(array(
			        'amount' => $amount,
			        'chargeToken' => $chargeToken,
			        'installments' => 1,
			        'preauth' => false,
			        'sourceCode' => WEBSITEAPP,
			        'merchantTrns' => $merchantTrns,
			        'customerTrns' => $customerTrns,
			        'currencyCode' => CURRENCY_CODE,
			        'customer' => array(
			            'email' => $mail,
			            'phone' => $phone,
			            'fullname' => $name,
			            'requestLang' => 'en',
			            'countryCode' => 'GB'
		            )
		         ));  
			  
			    $curl = curl_init();
			    curl_setopt($curl, CURLOPT_URL, $url);
			    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
			    curl_setopt($curl, CURLOPT_ENCODING, "");
			    curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
			    curl_setopt($curl, CURLOPT_TIMEOUT, 0);
			    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
			    curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
			    curl_setopt($curl, CURLOPT_POSTFIELDS, $postArgs);
			    curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArgs);

			    $result = curl_exec($curl);
			    curl_close($curl);
	          //  $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		 
			return json_decode($result,true);

      }
   }
}


if(!function_exists('ms')){ 
    function ms($array){
        print_r(json_encode($array));
        exit(0);
    }
}


 ?>