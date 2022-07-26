<?php	
if ($_POST) {
	
include "functions/func.inc.php";


    $sessionredirecturl = base_url('dashboard/success');
		$purpose         = htmlspecialchars($_POST['purpose']);  
		$holdername      = htmlspecialchars($_POST['Holdername']);  
		$dsCardNumber    = htmlspecialchars($_POST['cardNumber']);  
		$dsExpiryDate    = htmlspecialchars($_POST['dsExpiryDate']);  
		$cvc             =  htmlspecialchars($_POST['cvvNumber']);  
		$expiryDate      = explode("/",$dsExpiryDate);
		$expirationmonth = $expiryDate[0];
		$expirationyear  = $expiryDate[1];

		$token       = requestBasic();

		if ( !empty($token['access_token'] ) ) {
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
		if (!empty($chargeToken['redirectToACSForm'])) {
			
			echo ($chargeToken['redirectToACSForm'] );
			$session = array(
				'access_token'=> $token['access_token'],
				'chargeToken' => $chargeToken['chargeToken'],
				'purpose'     => $purpose,
			 );
			$this->session->set_userdata($session);
			die(); 
		}else{
			 ms(array(
				"status"  => "error",
				"message" => "Redirect To ACSForm is empty! contact your payment provider"
			));
			die();
		}
		redirect(base_url('dashboard/success'));
		}
	?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
   <head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
 
<title>send Transfer money </title>
<meta name="description" content="This professional design html template is for build a Money Transfer and online payments website.">
<meta name="author" content="harnishdesign.net">

<!-- Web Fonts============================== -->
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Rubik:300,300i,400,400i,500,500i,700,700i,900,900i' type='text/css'>

<!-- Stylesheet========================= -->
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="css/all.min.css" />
   
<link rel="stylesheet" type="text/css" href= "css/stylesheet.css" />

</head>
 
<body>
    <div id="page-overlay" class="visible incoming">
      <div class="loader-wrapper-outer">
        <div class="loader-wrapper-inner">
          <div class="lds-double-ring">
          </div>
        </div>
      </div>
    </div>

<!-- Document Wrapper    ===================== -->
<div id="main-wrapper"> 
  <!-- Header ======== -->
  <header id="header">
    <div class="container">
      <div class="header-row">
        <div class="header-column justify-content-start"> 
          <!-- Logo
          ============================= -->
           
          <!-- Logo end --> 
          <!-- Collapse Button
          ============================== -->
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#header-nav"> <span></span> <span></span> <span></span> </button>
          <!-- Collapse Button end --> 
          
          <!-- Primary Navigation================= -->
        
        
          <!-- Primary Navigation end --> 
        </div>
        <div class="header-column justify-content-end"> 
          <!-- My Profile =============== -->
                 <nav class="login-signup navbar navbar-expand">
            <ul class="navbar-nav">
              <li class="dropdowns language"> <a class="dropdown-toggle" href="#"></a>
                 
              </li>
         
              
            </ul>
          </nav>
          <!-- My Profile end --> 
        </div>
      </div>
    </div>
  </header>
  <!-- Header End --> 
  
  <!-- Secondary menu
  ============================================= -->
  <div class="bg-primary">
    <div class="container d-flex justify-content-center">
      <ul class="nav secondary-nav">
        <li class="nav-item"> <a class="nav-link" >Session Send Money</a></li>
 
      </ul>
    </div>
  </div>
  <!-- Secondary menu end --> 
  
  <!-- Content ============== -->
  <div id="content" class="py-4">
    <div class="container"> 
      
      <!-- Steps Progress bar -->
      <h2 class="font-weight-400 text-center mt-3">Send Money</h2>
      <p class="lead text-center mb-4">Send your money on anytime, anywhere in the world.</p>
    <div class="row">
        <div class="col-md-9 col-lg-7 col-xl-6 mx-auto">
          <div class="bg-white shadow-sm rounded p-3 pt-sm-4 pb-sm-5 px-sm-5 mb-4">
            <h3 class="text-5 font-weight-400 mb-3 mb-sm-4">Personal Details Payment</h3>
            <hr class="mx-n3 mx-sm-n5 mb-4">
              <form id="process" method="post" action="ajax.response.php" data-redirect="http://localhost/vivawallet/index.php" >
              
              <div id="add-new-recipient" class=""  >
                <div class="modal-body p-4">
                 <!--  <form id="addCard" method="post"> -->
                    <div class="form-group">
                      <label for="cardType">Purpose</label>
                      <select id="purpose" name="purpose" class="custom-select" required="">
                        <option value="">Select..</option>
                        <option value="Business">Business</option>
                        <option value="Child Support">Child Support</option>
                        <option value="Family Help">Family Help</option>
                        <option value="Mother Help">Mother Help</option>
                        <option value="Friend pay a Bill">Friend pay a Bill</option>
                        <option value="Payments">Payments</option>
                        <option value="Other Reason">Other Reason</option>
                      </select>
                    </div>
                
                    <div class="form-group">
                      <label for="cardNumber">Card Number</label>
                      <input type="text" name="cardNumber" onkeypress='validate(event)' value="5459655560969723"  class="form-control" data-bv-field="cardnumber" id="cardNumber" required   placeholder="Card Number">
                    </div>
                    <div class="form-row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="expiryDate">Expiry Date</label>
                          <input type="text" id="dsExpiryDate"  name="dsExpiryDate"  name="expiryDate"  class="form-control" data-bv-field="expiryDate" required value="03/2023" placeholder="MM/YYYY">
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="cvvNumber">CVV <span class="text-info ml-1" data-toggle="tooltip" data-original-title="For Visa/Mastercard, the three-digit CVV number is printed on the signature panel on the back of the card immediately after the card's account number. For American Express, the four-digit CVV number is printed on the front of the card above the card account number."><i class="fas fa-question-circle"></i></span></label>
                          <input type="number"  id="cvvNumber"  name="cvvNumber" value="373" onkeydown="limit(this);" onkeyup="limit(this);"  class="form-control" data-bv-field="cvvnumber" required  placeholder="CVV (3 digits)">
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="cardHolderName">Card Holder Name</label>
                      <input type="text" name="Holdername"  class="form-control" data-bv-field="cardholdername" id="cardHolderName" required value="maria da silva test" placeholder="Card Holder Name">
                    </div>

                    <div class="form-group">
                      <label for="cardHolderName">Value </label>
                      <input type="text" name="amount"  class="form-control" data-bv-field="cardholdername" id="cardHolderName" required value="10" placeholder="value to send">
                    </div>
                    <button class="btn btn-primary btn-block">PAY</button>
                  
                </div>
                 <div class="alert alert-danger alert-dismissible fade show" role="alert" id="result_notification_pay_e" >
                    <span id="result_notification_pay_elemente">  </span>
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                 <div class="alert alert-success alert-dismissible fade show" role="alert" id="result_notification_pay_s" >
                    <span id="result_notification_pay_elements">  </span>
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
          </div>
            
            </form>
          </div>
          <!-- Recent Activity End --> 
        </div>
        <!-- Middle Panel End --> 
      </div>
    </div>
  </div>
  <!-- Footer ==================== -->
  <!-- Footer =============================== -->
     <footer id="footer">
    <div class="container">
 
    </div>
  </footer>
  <!-- Footer end --> 
</div>
<!-- Document Wrapper end --> 
  
<!-- Back to Top============= --> 
 
<!-- Script --> 
<script src="js/jquery/jquery.min.js"></script>  
<script src="js/bootstrap/js/bootstrap.bundle.min.js"></script> 
<script src="js/theme.js"></script>
  
<script src="js/general.js"></script>
 
<script type="text/javascript">
function limit(element){
    var max_chars = 3;
    if(element.value.length > max_chars) {
        element.value = element.value.substr(0, max_chars);
    }
}

function validate_int(myEvento) {
    if ((myEvento.charCode >= 48 && myEvento.charCode <= 57) || myEvento.keyCode == 9 || myEvento.keyCode == 10 || myEvento.keyCode == 13 || myEvento.keyCode == 8 || myEvento.keyCode == 116 || myEvento.keyCode == 46 || (myEvento.keyCode <= 40 && myEvento.keyCode >= 37)) {
        dato = true;
    } else {
         dato = false;
    }
    return dato;
}

function date_mask() {
    var myMask = "__/____";
    var myCaja = document.getElementById("dsExpiryDate");
    var myText = "";
    var myNumbers = [];
    var myOutPut = ""
    var theLastPos = 1;
    myText = myCaja.value;

    for (var i = 0; i < myText.length; i++) {
      if (!isNaN(myText.charAt(i)) && myText.charAt(i) != " ") {
        myNumbers.push(myText.charAt(i));
      }
    }
   
    for (var j = 0; j < myMask.length; j++) {
      if (myMask.charAt(j) == "_") {  
        if (myNumbers.length == 0)
          myOutPut = myOutPut + myMask.charAt(j);
        else {
          myOutPut = myOutPut + myNumbers.shift();
          theLastPos = j + 1; 
        }
      } else {
        myOutPut = myOutPut + myMask.charAt(j);
      }
    }
    document.getElementById("dsExpiryDate").value = myOutPut;
    document.getElementById("dsExpiryDate").setSelectionRange(theLastPos, theLastPos);
}
document.getElementById("dsExpiryDate").onkeypress = validate_int;
document.getElementById("dsExpiryDate").onkeyup = date_mask;
</script>
</body>
</html>
