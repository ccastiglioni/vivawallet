<?php 
      include "functions/func.inc.php";

		$amount = (int) str_replace('.', '',str_replace(',', '', $_POST['amount'] ));
		
		 
		$purpose       = htmlspecialchars($_POST['purpose']);  
	 

		//$dsCardType      = htmlspecialchars($_POST['dsCardType']);  
		//$dsCardFlag      = htmlspecialchars($_POST['dsCardFlag']);  
		$holdername      = htmlspecialchars($_POST['Holdername']);  
		$dsCardNumber    = htmlspecialchars($_POST['cardNumber']);  
		$dsExpiryDate    = htmlspecialchars($_POST['dsExpiryDate']);  
		$cvc             = htmlspecialchars($_POST['cvvNumber']);  
		$expiryDate      = explode("/",$dsExpiryDate);
		$expirationmonth = $expiryDate[0];
		$expirationyear  = $expiryDate[1];
		$sessionredirecturl = 'http://localhost/vivawallet/index.php';

		$token       = requestBasic();


		if ( !empty($token['access_token'] )){
		  $chargeToken = requestBearer( $token['access_token'] , $cvc, $amount, $dsCardNumber ,$holdername, $expirationyear, $expirationmonth, $sessionredirecturl); 
		}else{
			 ms(array(
				"status"  => "error",
				"message" => "Access_token are empty!"
			));
			die();
		}

		if (isset($chargeToken["status"])) {
			 if($chargeToken["status"] == "400" || $chargeToken["status"] == "401"  || $chargeToken["status"] == "403"  || $chargeToken["status"] == "404"  || $chargeToken["status"] == "405" || $chargeToken["status"] == "500" || $chargeToken["status"] == "502" || $chargeToken["status"] == "504" || $chargeToken["status"] == "503" ){ 
				ms(array("status"  => "error","message" => $chargeToken["message"]));
				exit();
			}
		} 

		if (isset($chargeToken['redirectToACSForm'])) {
			 $session = array(
				'access_token'=> $token['access_token'],
				'chargeToken' => $chargeToken['chargeToken'],
				'purpose'     => $purpose,
			 );

			 ms(array(
				"status"  => "success",
				"message" => $chargeToken['redirectToACSForm']
			));
			die();
		}else{
			  ms(array(
				"status"  => "error",
				"message" => "Redirect To ACSForm is empty! contact your payment provider"
			));
			die();
		}       
	



 ?>