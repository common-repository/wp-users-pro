if(typeof $ == 'undefined'){
	var $ = jQuery;
}
(function($) {
    jQuery(document).ready(function () { 
	
	   "use strict";	   
	   
	   $("#easywpm-client-registration-form").validationEngine({promptPosition: 'inline'});	
	   
	   jQuery(document).on("click", "#easywpm-btn-conf-upgrade", function(e) {
			
			var frm_validation  = $("#easywpm-client-registration-form").validationEngine('validate');	
			
			if(frm_validation)
			{
				
				var myRadioPayment = $('input[name=wpuserspro_payment_method]');
				var payment_method_selected = myRadioPayment.filter(':checked').val();				
				var payment_method =  jQuery("#wpuserspro_payment_method_stripe_hidden").val();
								
				if(payment_method=='stripe' && payment_method_selected=='stripe')
				{					
					var wait_message = '<div class="wpuserspro_wait">' + wpuserspro_pro_front.wait_submit + '</div>';				
					jQuery('#easywpm-stripe-payment-errors').html(wait_message);					
					wpuserspro_stripe_process_card();
				
				} else if (payment_method=='stripe' && payment_method_selected=='authorize') {
					
				
				}else{
					
					jQuery("#easywpm-message-submit-booking-conf").html(wpuserspro_pro_front.message_wait);					
					$('#easywpm-btn-conf-signup').prop('disabled', 'disabled');								
					$("#easywpm-client-registration-form").submit();	
				
				
				}						
				
				
			}else{
				
				
				
			}
			
			
									
    		e.preventDefault();		 
				
        });  
	   
	   jQuery(document).on("click", "#easywpm-btn-conf-signup", function(e) {
			
			var frm_validation  = $("#easywpm-client-registration-form").validationEngine('validate');	
			
			if(frm_validation)
			{
				//alert('submit');
				
				var myRadioPayment = $('input[name=wpuserspro_payment_method]');
				var payment_method_selected = myRadioPayment.filter(':checked').val();				
				var payment_method =  jQuery("#wpuserspro_payment_method_stripe_hidden").val();
								
				if(payment_method=='stripe' && payment_method_selected=='stripe')
				{
					
					var wait_message = '<div class="wpuserspro_wait">' + wpuserspro_pro_front.wait_submit + '</div>';				
					jQuery('#easywpm-stripe-payment-errors').html(wait_message);					
					wpuserspro_stripe_process_card();
					
					
				
				} else if (payment_method=='stripe' && payment_method_selected=='authorize') {
					
				
				}else{
					
					jQuery("#easywpm-message-submit-booking-conf").html(wpuserspro_pro_front.message_wait);					
					$('#easywpm-btn-conf-signup').prop('disabled', 'disabled');								
					$("#easywpm-client-registration-form").submit();	
				
				
				}						
				
				
			}else{
				
				
				
			}
			
			
									
    		e.preventDefault();		 
				
        });
		
		jQuery(document).on("click", ".wpuserspro_payment_options", function(e) {
		
			
			var payment_method =  jQuery(this).attr("data-method");
			
			if(payment_method=='stripe')
			{
				$(".easywpm-profile-field-cc").slideDown();
				
				
			}else{
				
				$(".easywpm-profile-field-cc").slideUp();
							
			}			
			
		
				
       });
	   
	   jQuery(document).on("click", ".easywpm-front-check-package", function(e) {
		
			
			var is_free=  jQuery(this).attr("is-free-package");
			
			if(is_free=='1')
			{
				
				$(".easywpm-profile-field-cc").slideUp();				
				$("#easywpm-payment-header").slideUp();	
				$("#easypm-method-paypal").slideUp();
				$("#easypm-method-stripe").slideUp();	
				
				$('.wpuserspro_payment_options').prop('checked', false);
				
				
			}else{
				
				$(".easywpm-profile-field-cc").slideDown();				
				$("#easywpm-payment-header").slideDown();
				$("#easypm-method-paypal").slideDown();
				$("#easypm-method-stripe").slideDown();	
					
				
				
							
			}			
			
		
				
       });
		
 
       
    }); //END READY
})(jQuery);







