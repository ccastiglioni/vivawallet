 
function validate(evt) {
      var theEvent = evt || window.event;

      if (theEvent.type === 'paste') {
          key = event.clipboardData.getData('text/plain');
      } else {
          var key = theEvent.keyCode || theEvent.which;
          key = String.fromCharCode(key);
      }
      var regex = /[0-9]|\./;
      if( !regex.test(key) ) {
        theEvent.returnValue = false;
        if(theEvent.preventDefault) theEvent.preventDefault();
      }
}

function ShowModal(elem){

    var dataId = $(elem).data("id");
    var hrefpost = $(elem).data("href");
  
      $.ajax({
      type:'POST',
      url: hrefpost,
      data: {id:dataId} ,
      //   data:{id:$("#estados").val()},
      success:function(vardata){
          var json = JSON.parse(vardata);
       }
    });
    
}

function ajaxcalc() {
 
    event.preventDefault();
    _that       = $(this);
    _action     = _that.data("action");
    _data       = _that.serialize();
    $.ajax({
     type: "POST",
     url: _action,
     data: _data,
     success: function(_result)
     {  
          var json = JSON.parse(_result);
          if (json.status=='success') {

             var finalcalc = 0;
             var sum  = 0;
             var num = json.sendAmount;
             if (num > 500) {
                 finalcalc = (1 / 100) * num;
                 label = "";
             }else if( num > 50  && num  <= 500 ){
                finalcalc = 5;
                label = "";
             }else if(num <= 50){
                //actionForm Important: Minimum value per shipment is £ 50.00
                 label = "Important: Minimum value per shipment is £ 50.00"
                 finalcalc = 0;
             }
           
             sum = Number(num) + Number(finalcalc);
       
            setTimeout(function () {
                $("#js-send-field input[name=reciveAmount]").val('..');
                $("#fee-return").text('..');
                $("#label-return").text('..');
            }, 150);
            setTimeout(function () {
                $("#js-send-field input[name=reciveAmount]").val('...');
                $("#label-return").text('...');
            }, 250);
            setTimeout(function () {
                $("#js-send-field input[name=reciveAmount]").val('....');
                $("#label-return").text('..');
                $("#fee-return").text('....');
            }, 320);
                
            setTimeout(function () {
                $("#js-send-field input[name=reciveAmount]").val(json.resultset);
                $("#fee-return").text("£ "+ finalcalc.toFixed(2) );
                $("#total-return").text( parseFloat(sum).toFixed(2) );
                $("#label-return").text( label );
            }, 350);
          }else{
             $("#js-send-field input[name=reciveAmount]").val('');
              $("#fee-return").text(json.mgs);
              $("#reciveAmount").val();

          }
      }
    });
}
 $(".actionForm").on("keyup",ajaxcalc);
 $("#actionformIndex").on("change",ajaxcalc);
 
 // hide success mensagem 
$("#success-alert").hide();
$("#success-alert-profile").hide();
$("#success-alert-email").hide();
$("#success-alert-phone").hide();
$("#success-accountsettings").hide();
 
// hide error mensagem
$("#result_notification").hide(); 
$("#error_accountsettings").hide(); 
$("#result_notification-profile").hide(); 
$("#error_notification-email").hide(); 
$("#error_notification-phone").hide(); 
 

$(document).on("submit", ".actionForm-destination, .actionForm-profile, .actionForm-personal, .actionForm-phone, .actionForm-email, .actionForm-accountsettings", function(){
   // pageOverlay.show();
    event.preventDefault();
    _that       = $(this);
    _action     = _that.attr("action");
    _redirect   = _that.data("redirect");
    _success_css= _that.data("success");
    _error_css  = _that.data("error");
    _data       = _that.serialize();
    _typeClass  =_that.attr('class');
    
    $.post(_action, _data, function(_result){
        if ( _result ) {
            _result = JSON.parse(_result);
            if( _result.status == 'success' ){
                var whichUrl = _redirect.split("dashboard/");
             
                $("#"+_success_css).fadeTo(2500, 500).slideUp(500, function() {
                      $("#"+_success_css).slideUp(500);
                 });
                 
                setTimeout(function(){
                    if (whichUrl[1] == "do.destination") {
                       $('#add-new-card-details').modal('hide');
                    }else{
                       window.location.href = _redirect;
                    }
                }, 3000);

              }else{          
                    $('#respose-profile').text(_result.message);
                    $("#"+_error_css).fadeTo(10000, 500).slideUp(500, function() {
                    $("#"+_error_css).slideUp(500);
                });
                  $('#respose').text(_result.message);
              }

        }else{
            setTimeout(function(){
                $("#result_notification").html(_result);
            }, 1500)
        }
    })
    return false;
})

$("#result_notification_pay_e").hide();
$("#result_notification_pay_s").hide();
$(document).on("submit", "#process", function(){
   // pageOverlay.show();
    event.preventDefault();
    _that       = $(this);
    _action     = _that.attr("action");
    _redirect   = _that.data("redirect");
    _data       = _that.serialize();
        
      $.ajax({
        type:'POST',
        url: _action,
        data: _data,
            beforeSend: function(){
                $("#page-overlay").css({'display':'block'});
            },
            success: function(data)
            {  
                var result = JSON.parse(data);
                 if (result.status == 'success') {
                    $("#page-overlay").css({'display':'none'});
                    $("#result_notification_pay_s").show();
                    $("#result_notification_pay_elements").html('<strong>Transfer carried out successfully!</strong>'+result.message);
                     
                }else if(result.status == 'error'){
                   $("#page-overlay").css({'display':'none'});
                   $("#result_notification_pay_e").show();
                   $("#result_notification_pay_elemente").html('Error: '+result.message);    
                }    
             
            },
            error: function(data )
            {   
                 console.debug(data)  
                $("#result_notification_pay").show();
                $("#result_notification_pay_element").html('ajax error!');
            }
        });
    
});


 
