jQuery(document).ready(function($) {
	
		/* 	Close Open Sections in Main Admin Page */		
	jQuery(document).on("click", ".easywpm-widget-backend-colapsable", function(e) {	
	
		
		var widget_id =  jQuery(this).attr("widget-id");		
		var iconheight = 20;
		
		
		if(jQuery("#easywpm-backend-landing-"+widget_id).is(":visible")) 
	  	{					
			
			jQuery( "#easywpm-close-open-icon-"+widget_id ).removeClass( "fa-sort-asc" ).addClass( "fa-sort-desc" );
			
		}else{			
			
			jQuery( "#easywpm-close-open-icon-"+widget_id ).removeClass( "fa-sort-desc" ).addClass( "fa-sort-asc" );			
	 	 }
		
		
		jQuery("#easywpm-backend-landing-"+widget_id).slideToggle();	
					
	});
		
	/* 	Close Open Sections in Dasbhoard */		
	jQuery(document).on("click", ".easywpm-widget-home-colapsable", function(e) {	
	
		
		e.preventDefault();
		var widget_id =  jQuery(this).attr("widget-id");		
		var iconheight = 20;
		
		if(jQuery("#easywpm-staff-box-cont-"+widget_id).is(":visible")) 
	  	{
					
			jQuery( "#easywpm-close-open-icon-"+widget_id ).removeClass( "fa-sort-desc" ).addClass( "fa-sort-asc" );
			
		}else{
			
			jQuery( "#easywpm-close-open-icon-"+widget_id ).removeClass( "fa-sort-asc" ).addClass( "fa-sort-desc" );			
	 	 }
		
		
		jQuery("#easywpm-staff-box-cont-"+widget_id).slideToggle();	
					
		return false;
	});
	
		jQuery(document).on("click", "#easywpm-btn-book-app-confirm-resetlink", function(e) {		
			
				var p1= $("#user_login_reset").val()	;
				var p2= $("#user_password_reset_2").val()	;
				var u_key= $("#wpuserspro_reset_key").val()	;
				
				jQuery("#easywpm-pass-reset-message").html(wpuserspro_profile_v98.msg_wait);
				
									
				jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_confirm_reset_password", "p1": p1, "p2": p2, "key": u_key },
					
					success: function(data){						
					
						jQuery("#easywpm-pass-reset-message").html(data);											
						
						}
				});
			
			
    		e.preventDefault();
				
        });
	
			//this will crop the avatar and redirect
	jQuery(document).on("click touchstart", "#easywpm-confirm-avatar-cropping", function(e) {
			
			e.preventDefault();			
			
			var x1 = jQuery('#x1').val();
			var y1 = jQuery('#y1').val();
			
			
			var w = jQuery('#w').val();
			var h = jQuery('#h').val();
			var image_id = $('#image_id').val();
			var user_id = $('#user_id').val();				
			
			if(x1=="" || y1=="" || w=="" || h==""){
				alert(bup_profile_v98.msg_make_selection);
				return false;
			}
			
			
			jQuery('#easywpm-cropping-avatar-wait-message').html(wpuserspro_profile_v98.msg_wait_cropping);
			
			
			
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "wpuserspro_crop_avatar_user_profile_image_staff", "x1": x1 , "y1": y1 , "w": w , "h": h  , "image_id": image_id , "user_id": user_id},
				
				success: function(data){					
					//redirect				
					var site_redir = jQuery('#site_redir').val();
					window.location.replace(site_redir);	
								
					
					
					}
			});
			
					
					
		     	
    		e.preventDefault();
			 

				
        });
	jQuery(document).on("click", "#btn-delete-user-avatar", function(e) {
			
			e.preventDefault();
			
			var user_id =  jQuery(this).attr("user-id");
			var redirect_avatar =  jQuery(this).attr("redirect-avatar");
			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_delete_user_avatar_staff" },
					
					success: function(data){
												
						refresh_my_avatar();
						
						if(redirect_avatar=='yes')
						{
							var site_redir = jQuery('#site_redir').val();
							window.location.replace(site_redir);
							
						}else{
							
							refresh_my_avatar();
							
						}
											
						
					}
				});
			
			
    		e.preventDefault();
			 
				
        });
		
	
	function refresh_my_avatar ()
		{
			
			 jQuery.post(ajaxurl, {
							action: 'refresh_avatar'}, function (response){									
																
							jQuery("#uu-backend-avatar-section").html(response);
									
									
					
			});
			
		}
		

}); 
	