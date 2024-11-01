var $ = jQuery;


jQuery(document).ready(function($) {
	
	
	 $("#easywpm-registration-form").validationEngine({promptPosition: 'inline'});
	
	jQuery("#uultra-add-new-custom-field-frm").slideUp();	 
	jQuery( "#tabs-bupro" ).tabs({collapsible: false	});
	jQuery( "#easywpm-bupro-settings" ).tabs({collapsible: false	});	
	jQuery( ".bup-datepicker" ).datepicker({changeMonth:true,changeYear:true,yearRange:"1940:2027"});
	
	// Adding jQuery Datepicker
	jQuery(function() {
			
			var uultra_date_format =  jQuery('#uultra_date_format').val();			
			if(uultra_date_format==''){uultra_date_format='dd/mm/yy'}	
		
			jQuery( ".easywpm-datepicker" ).datepicker({ showOtherMonths: true, dateFormat: uultra_date_format,  minDate: 0 });
		
			jQuery("#ui-datepicker-div").wrap('<div class="ui-datepicker-wrapper" />');
		});
		
		
	
	jQuery(document).on("click", ".easywpm-message-close", function(e) {
		
		e.preventDefault();	

		
		var message_id =  jQuery(this).attr("message-id");
		
		jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "wpuserspro_hide_admin_messages","message_id": message_id 
						 },
						
						success: function(data){
							
							window.location.reload();
							
							}
					});
			
	});
		
		
	jQuery(document).on("click", ".easywpm-location-front", function(e) {						
			
			var dep_id =  jQuery(this).attr("depto-id");
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_get_custom_department_fields", "department_id": dep_id },
					
					success: function(data){							
							
																
						jQuery("#wp-custom-fields-public").html(data);											
										    
						

						}
				});			
			
			 
				
        });
		
		
	

	
	/* 	Close Open File Uploader */		
	jQuery(document).on("click", "#easywpm-ticket-file-uploader-btn", function(e) {
				
		
		jQuery("#wp-file-uploader-front").slideToggle();
		e.preventDefault();	
					
	});	
	
	/* 	Close Open Reply Box Uploader */		
	jQuery(document).on("click", "#easywpm-ticket-reply-btn", function(e) {
				
		
		jQuery("#wp-add-reply-box").slideToggle();
		e.preventDefault();	
					
	});	
	
	
	/* 	mark as resolved*/
	jQuery(document).on("click", "#easywpm-user-mark-as-resolved", function(e) {
			
				e.preventDefault();		
			
				jQuery("#bup-spinner").show();				  
				var ticket_id =  jQuery(this).attr("ticket-id");
				
				jQuery.ajax({
							type: 'POST',
							url: ajaxurl,
							data: {"action": "wpuserspro_mark_as_resolved_confirm", 
							"ticket_id": ticket_id  },
							
							success: function(data){							
							
								location.reload();
								
							}
				});
				
		});
		
		/* 	mark as resolved*/
		jQuery(document).on("click", "#easywpm-user-mark-as-closed", function(e) {
			
				e.preventDefault();		
			
				jQuery("#bup-spinner").show();				  
				var ticket_id =  jQuery(this).attr("ticket-id");
				
				jQuery.ajax({
							type: 'POST',
							url: ajaxurl,
							data: {"action": "wpuserspro_mark_as_closed_confirm", 
							"ticket_id": ticket_id  },
							
							success: function(data){					
															 
								location.reload();
								
							}
				});
				
		});	
		
	
	
	jQuery(document).on("click", "#easywpm-edit-subscription", function(e) {
		
	
		var subscription_id =  jQuery(this).attr("subscription-id");
		
		jQuery("#bup-spinner").show();
		
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_subscription_edit", "subscription_id": subscription_id},
					
					success: function(data){	
					
					
						var res = data;
						jQuery("#easywpm-edit-subscription-details" ).html( res );
						jQuery("#easywpm-edit-subscription-details" ).dialog( "open" );
						
						jQuery( ".easywpm-datepicker" ).datepicker({ 
								showOtherMonths: true, 
								dateFormat: wpuserspro_admin_v98.date_picker_date_format, 
								closeText: EASYWPMDatePicker.closeText,
								currentText: EASYWPMDatePicker.currentText,
								monthNames: EASYWPMDatePicker.monthNames,
								monthNamesShort: EASYWPMDatePicker.monthNamesShort,
								dayNames: EASYWPMDatePicker.dayNames,
								dayNamesShort: EASYWPMDatePicker.dayNamesShort,
								dayNamesMin: EASYWPMDatePicker.dayNamesMin,
								firstDay: EASYWPMDatePicker.firstDay,
								isRTL: EASYWPMDatePicker.isRTL,
								minDate: 0
							 });
							 
						jQuery("#ui-datepicker-div").wrap('<div class="ui-datepicker-wrapper" />');
						jQuery("#bup-spinner").hide();						

					}
				});				
			
				
			
    		e.preventDefault();		 
				
    });
	
	jQuery( "#easywpm-edit-subscription-details" ).dialog({
			autoOpen: false,			
			width: '400px', // overcomes width:'auto' and maxWidth bug
   			
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {
			"Submit": function() {				
				
				var ret;
				var subscription_id=   jQuery("#subscription_id").val();
				var date_from=   jQuery("#date_from").val();
				var date_to=   jQuery("#date_to").val();
				var membership_status=   jQuery("#membership_status").val();
				var make_lifetime = jQuery("#easywpm-makelifetime").prop("checked");
				
				if(make_lifetime){ make_lifetime = '1'; }else{make_lifetime = '0';};
				
				
				
				if(date_from==''){alert(wptu_admin_v98.msg_input_private_name); return;}
					
				jQuery("#easywpm-err-message" ).html( '' );		
							
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "wpuserspro_edit_subscription_confirm", 
						"subscription_id": subscription_id, 
						"date_from": date_from , 
						"date_to": date_to , 
						"membership_status": membership_status , 
						"make_lifetime": make_lifetime },
						
						success: function(data){
							
							
							var res =jQuery.parseJSON(data);				
							
							if(res.response=='OK')	
							{
																
								jQuery("#easywpm-edit-subscription-details" ).dialog( "close" );	
								window.location.reload();								
														
							}else{ //ERROR
							
								jQuery("#easywpm-message-err" ).html( res.content );	
							
							}
							
													
												
							
							
						}
					});
				
				
				
			
			},
			
			"Cancel": function() {
				
				
				jQuery( this ).dialog( "close" );
			},
			
			
			},
			close: function() {
			
			
			}
	});
		
	jQuery(document).on("click", ".easywpm-delete-privatecrede-btn", function(e) {
		
		var private_ticket_id =  jQuery(this).attr("ticket-id");
		var private_id =  jQuery(this).attr("credential-id");
		
		doIt=confirm(wpuserspro_admin_v98.are_you_sure);
		  
		if(doIt)
		{		
	
			jQuery("#easywpm-spinner").show();
				
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "wpuserspro_private_credentials_delete", "ticket_id": private_ticket_id , "private_id": private_id},
						
						success: function(data){						
							
							var res = data;
							wpuserspro_load_private_credentials(private_ticket_id);
							jQuery("#easywpm-spinner").hide();						
	
						}
					});	
			
			
		}
			
				
			
    		e.preventDefault();		 
				
    });
	
	jQuery(document).on("click", ".easywpm-private-edit-note", function(e) {
		
	
		var note_ticket_id =  jQuery(this).attr("note-ticket-id");
		var note_id =  jQuery(this).attr("note-id");
		
		jQuery("#bup-spinner").show();
		
		jQuery('#easywpm-private-notes-add-modify').dialog('option', 'title', wpuserspro_admin_v98.msg_note_edit);
			
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_notes_form", "ticket_id": note_ticket_id,  "note_id": note_id},
					
					success: function(data){						
						
						var res = data;
						jQuery("#easywpm-private-notes-add-modify" ).html( res );
						jQuery("#easywpm-private-notes-add-modify" ).dialog( "open" );
						jQuery("#bup-spinner").hide();						

					}
				});				
			
    		e.preventDefault();		 
				
    });
	
	
	jQuery(document).on("click", ".easywpm-private-edit-credentials", function(e) {
		
	
		var ticket_id =  jQuery(this).attr("private-ticket-id");
		var private_id =  jQuery(this).attr("private-id");
		
		jQuery("#bup-spinner").show();
		
	
			
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_credentials_form", "private_ticket_id": ticket_id,  "private_id": private_id},
					
					success: function(data){						
						
						var res = data;
						jQuery("#easywpm-private-credentials-add-modify" ).html( res );
						jQuery("#easywpm-private-credentials-add-modify" ).dialog( "open" );
						jQuery("#bup-spinner").hide();						

					}
				});				
			
				
			
    		e.preventDefault();		 
				
    });
	
	jQuery(document).on("click", "#easywpm-display-current-user-tickets, #easywpm-display-current-user-tickets-buble", function(e) {
		
	
		var client_id =  jQuery(this).attr("user-id");
		
		jQuery("#bup-spinner").show();
			
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_tickets_by_client", "client_id": client_id},
					
					success: function(data){						
						
						var res = data;
						jQuery("#easywpm-ticket-total-by-users-box" ).html( res );
						jQuery("#easywpm-ticket-total-by-users-box" ).dialog( "open" );
						jQuery("#bup-spinner").hide();						

					}
				});				
			
				
			
    		e.preventDefault();		 
				
    });
	
	/* open user tickets */	
	jQuery( "#easywpm-ticket-total-by-users-box" ).dialog({
			autoOpen: false,			
			width: '850px', // overcomes width:'auto' and maxWidth bug
   			
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {
						
			"Close": function() {
				
				
				jQuery( this ).dialog( "close" );
			},
			
			
			},
			close: function() {
			
			
			}
	});
	
	
	
	
	jQuery(document).on("click", "#easywpm-delete-reply-file", function(e) {
			
		
			var reply_id =  jQuery(this).attr("reply-id");
			var attachment_id =  jQuery(this).attr("attachment-id");
			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_delete_reply_file", "reply_id": reply_id , "attachment_id": attachment_id },
					
					success: function(data){
						
						
						jQuery("#easywpm-attached-image-id-"+attachment_id).hide();
												
																
						
					}
				});
			
			
    		e.preventDefault();
			 
				
        });
	
	
	
	 // This sends a reset link to a staff member
	jQuery(document).on("click", "#easywpm-save-acc-send-reset-link-staff", function(e) {
			
			var staff_id =  jQuery(this).attr("easywpm-staff-id");		
						
			jQuery("#easywpm-err-message" ).html( '' );	
			jQuery("#easywpm-loading-animation-acc-resetlink-staff" ).show( );		
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_send_welcome_email_to_staff",
					"staff_id": staff_id
					 
					 },
					
					success: function(data){					
						
						var res = data;	
						jQuery("#easywpm-loading-animation-acc-resetlink-staff" ).hide( );						
						jQuery("#easywpm-acc-resetlink-staff-message" ).html( res );						
							
						
						
						}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
	
	/* 	Close Open Sections in Dasbhoard */

	jQuery(document).on("click", ".easywpmembers-widget-home-colapsable", function(e) {
		
		e.preventDefault();
		var widget_id =  jQuery(this).attr("widget-id");		
		var iconheight = 20;
		
		
		if(jQuery("#easywpmembers-main-cont-home-"+widget_id).is(":visible")) 
	  	{
					
			jQuery( "#easywpmembers-close-open-icon-"+widget_id ).removeClass( "fa-sort-asc" ).addClass( "fa-sort-desc" );
			
		}else{
			
			jQuery( "#easywpmembers-close-open-icon-"+widget_id ).removeClass( "fa-sort-desc" ).addClass( "fa-sort-asc" );			
	 	 }
		
		
		jQuery("#easywpmembers-main-cont-home-"+widget_id).slideToggle();	
					
		return false;
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
				alert("You must make a selection first");
				return false;
			}
			
			
			jQuery('#easywpm-cropping-avatar-wait-message').html(message_wait_availability);
			
			
			
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "wpuserspro_crop_avatar_user_profile_image", "x1": x1 , "y1": y1 , "w": w , "h": h  , "image_id": image_id , "user_id": user_id},
				
				success: function(data){					
					//redirect				
					var site_redir = jQuery('#site_redir').val();
					window.location.replace(site_redir);	
								
					
					
					}
			});
			
					
					
		     	
			 return false;
    		e.preventDefault();
			 

				
        });
	jQuery(document).on("click", "#easywpm-btn-delete-user-avatar", function(e) {
			
			e.preventDefault();
			
			var user_id =  jQuery(this).attr("user-id");
			var redirect_avatar =  jQuery(this).attr("redirect-avatar");
			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_delete_user_avatar", "user_id": user_id },
					
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
			
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 
				
        });
		
	
	function refresh_my_avatar ()
		{
			
			 jQuery.post(ajaxurl, {
							action: 'refresh_avatar'}, function (response){									
																
							jQuery("#uu-backend-avatar-section").html(response);
							//$( "#uu-upload-avatar-box" ).slideUp("slow");
									
									
					
			});
			
		}
	
	
	jQuery(document).on("click", "#bup_re_schedule", function(e) {
			
			
			
			if ($(this).is(":checked")) 
			{
                $("#bup-availability-box").slideDown();
				$("#bup-availability-box-btn").slideDown();
				
            } else {
				
				$("#bup-availability-box-btn").slideUp();				
                $("#bup-availability-box").slideUp();
            }			
			
				 
				
        });
		
	jQuery(document).on("click", "#easywpm-btn-validate-copy", function(e) {	
	
	
		 e.preventDefault();
		 
		 var p_ded =  $('#p_serial').val();
		 
		 jQuery("#loading-animation").slideDown();
		
		jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "wpuserspro_vv_c_de_a", 
						"p_s_le": p_ded },
						
						success: function(data){
							
							jQuery("#loading-animation").slideUp();							
						
								jQuery("#bup-validation-results").html(data);
								jQuery("#bup-validation-results").slideDown();								
								setTimeout("hidde_noti('bup-validation-results')", 6000)
								
								window.location.reload();
							
							}
					});
			
		 	
		
				
		return false;
	});
		
	
	/* 	FIELDS CUSTOMIZER -  ClosedEdit Field Form */
	
	jQuery(document).on("click", ".easywpm-btn-close-edition-field", function(e) {	
		e.preventDefault();
		var block_id =  jQuery(this).attr("data-edition");		
		jQuery("#easywpm-edit-fields-bock-"+block_id).slideUp();				
	});
	
	/* 	FIELDS CUSTOMIZER -  Add New Field Form */
	jQuery('#easywpm-add-field-btn').on('click',function(e)
	{
		
		e.preventDefault();
			
		jQuery("#easywpm-add-new-custom-field-frm").slideDown();				
		return false;
	});
	
	/* 	FIELDS CUSTOMIZER -  Add New Field Form */
	jQuery('#easywpm-close-add-field-btn').on('click',function(e){
		
		e.preventDefault();
			
		jQuery("#easywpm-add-new-custom-field-frm").slideUp();				
		return false;
	});
	
	
	/* 	FIELDS CUSTOMIZER -  Edit Field Form */
	jQuery('#wpuserspro__custom_registration_form').on('change',function(e)
	{		
		e.preventDefault();
		wpuserspro_reload_custom_fields_set();
					
	});
	
	
	/* Delete Users */
	jQuery('#ubp-staff-member-delete').on('click',function(e)
	{
		e.preventDefault();	
			  
		  var staff_id =  jQuery(this).attr("staff-id");	
		  
		  
		  var doIt = false;
		
		  doIt=confirm(wpuserspro_admin_v98.msg_user_delete);
		  
		  if(doIt)
		  {
			  jQuery("#bup-spinner").show();
			  
				jQuery.ajax({
							type: 'POST',
							url: ajaxurl,
							data: {"action": "bup_delete_staff_admin", 
							"staff_id": staff_id 
							 },
							
							success: function(data){				
							
								
								var staff_id = data;								
								jQuery("#bup-spinner").hide();
								wpuserspro_load_staff_list_adm();
								wpuserspro_load_staff_details(staff_id);			
							
							}
					});
				
				
				}
			
		   	
				
		
	});
	
	
	/* 	Update Details */
	jQuery('#easywpm-btn-user-details-confirm').on('click',function(e)
	{
		e.preventDefault();	
			  
		  var staff_id =  jQuery(this).attr("data-field");	
		  
		  var staff_id =  jQuery('#staff_id').val();
		  var display_name =  jQuery('#reg_display_name').val();
		  var reg_telephone =  jQuery('#reg_telephone').val();
		  
		  var reg_email =  jQuery('#reg_email').val();
		  var reg_email2 =  jQuery('#reg_email2').val();
		  
		  jQuery("#easywpm-edit-details-message").html(message_wait_availability);	 
		
			jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "wpuserspro_update_staff_admin", 
						"staff_id": staff_id , 
						"display_name": display_name ,
						"reg_email": reg_email , 
						"reg_email2": reg_email2 , 
						"reg_telephone": reg_telephone },
						
						success: function(data){							
						
							jQuery("#easywpm-edit-details-message").html(data);				
						
							
							
							
							}
				});
			
		   	
				
		
	});
	
	/* 	FIELDS CUSTOMIZER - Delete Field */
	jQuery('.easywpm-delete-profile-field-btn').on('click',function(e)
	{
		e.preventDefault();
		
		var doIt = false;
		
		doIt=confirm(custom_fields_del_confirmation);
		  
		  if(doIt)
		  {
			  
			  var p_id =  jQuery(this).attr("data-field");	
			  var custom_form =  jQuery('#wpuserspro__custom_registration_form').val();
		
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "wpuserspro_delete_profile_field", 
						"_item": p_id , "custom_form": custom_form },
						
						success: function(data){					
						
							jQuery("#bup-sucess-delete-fields-"+p_id).slideDown();
						    setTimeout("hidde_noti('bup-sucess-delete-fields-" + p_id +"')", 1000);
							jQuery( "#"+p_id ).addClass( "bup-deleted" );
							setTimeout("hidde_noti('" + p_id +"')", 1000);
							
							//reload fields list added 08-08-2014						
							wpuserspro_reload_custom_fields_set();		
							
							
							}
					});
			
		  }
		  else{
			
		  }		
		
				
		return false;
	});
	
	
	/* 	FIELDS CUSTOMIZER - Add New Field Data */
	jQuery('#easywpm-btn-add-field-submit').on('click',function(e){
		e.preventDefault();
		
		
		var _position = $("#uultra_position").val();		
		var _type =  $("#uultra_type").val();
		var _field = $("#uultra_field").val();		
		
		var _meta_custom = $("#uultra_meta_custom").val();		
		var _name = $("#uultra_name").val();
		var _tooltip =  $("#uultra_tooltip").val();	
		var _help_text =  $("#uultra_help_text").val();		
	
		
		var _can_edit =  $("#uultra_can_edit").val();		
		var _allow_html =  $("#uultra_allow_html").val();
				
		var _private = $("#uultra_private").val();
		var _required =  $("#uultra_required").val();		
		var _show_in_register = $("#uultra_show_in_register").val();
		
		var _choices =  $("#uultra_choices").val();	
		var _predefined_options =  $("#uultra_predefined_options").val();		
		var custom_form =  $('#wpuserspro__custom_registration_form').val();	
				
		var _icon =  $('input:radio[name=uultra_icon]:checked').val();
		
				
		jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "wpuserspro_add_new_custom_profile_field", 
						"_position": _position , 
						"_type": _type ,
						"_field": _field ,
						"_meta_custom": _meta_custom ,
						"_name": _name  ,						
						"_tooltip": _tooltip ,
						
						"_help_text": _help_text ,	
						
						"_can_edit": _can_edit ,"_allow_html": _allow_html  ,
						"_private": _private, 
						"_required": _required  ,
						"_show_in_register": _show_in_register ,						
						"_choices": _choices,  
						"_predefined_options": _predefined_options , 
						"custom_form": custom_form,						
						"_icon": _icon },
						
						success: function(data){		
						
													
							jQuery("#easywpm-sucess-add-field").slideDown();
							setTimeout("hidde_noti('easywpm-sucess-add-field')", 3000)		
							//alert("done");
							window.location.reload();
							 							
							
							
							}
					});
			
		 
		
				
		return false;
	});
	
	/* 	FIELDS CUSTOMIZER - Update Field Data */
	jQuery(document).on("click", ".easywpm-btn-submit-field", function(e) {
		
		e.preventDefault();
		
		var key_id =  jQuery(this).attr("data-edition");	
		
		jQuery('#p_name').val()		  
		
		var _position = $("#uultra_" + key_id + "_position").val();		
		var _type =  $("#uultra_" + key_id + "_type").val();
		var _field = $("#uultra_" + key_id + "_field").val();		
		var _meta =  $("#uultra_" + key_id + "_meta").val();
		var _meta_custom = $("#uultra_" + key_id + "_meta_custom").val();		
		var _name = $("#uultra_" + key_id + "_name").val();
				
		var _tooltip =  $("#uultra_" + key_id + "_tooltip").val();	
		var _help_text =  $("#uultra_" + key_id + "_help_text").val();		
				
		var _can_edit =  $("#uultra_" + key_id + "_can_edit").val();		
		
		var _required =  $("#uultra_" + key_id + "_required").val();		
		var _show_in_register = $("#uultra_" + key_id + "_show_in_register").val();
				
		var _choices =  $("#uultra_" + key_id + "_choices").val();	
		var _predefined_options =  $("#uultra_" + key_id + "_predefined_options").val();		
		var _icon =  $('input:radio[name=uultra_' + key_id +'_icon]:checked').val();
		
		var custom_form =  $('#wpuserspro__custom_registration_form').val();
		
		jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "wpuserspro_save_fields_settings", 
						"_position": _position , "_type": _type ,
						"_field": _field ,
						"_meta": _meta ,
						"_meta_custom": _meta_custom  
						,"_name": _name  ,											
						
						"_tooltip": _tooltip ,
						"_help_text": _help_text ,												
						"_icon": _icon ,						
						"_required": _required  ,
						"_show_in_register": _show_in_register ,						
						"_choices": _choices, 
						"_predefined_options": _predefined_options,
						"pos": key_id  , 
						"custom_form": custom_form 
						
																	
						},
						
						success: function(data){		
						
												
						jQuery("#easywpm-sucess-fields-"+key_id).slideDown();
						setTimeout("hidde_noti('easywpm-sucess-fields-" + key_id +"')", 1000);
						
						wpuserspro_reload_custom_fields_set();		
						
							
							}
					});
			
	});
	
	
	/* 	FIELDS CUSTOMIZER -  Edit Field Form */
		
	jQuery(document).on("click", ".easywpm-btn-edit-field", function(e) {
		
		e.preventDefault();
		var block_id =  jQuery(this).attr("data-edition");			
		
		var custom_form = jQuery('#wpuserspro__custom_registration_form').val();
		
		jQuery("#bup-spinner").show();
		
		jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "wpuserspro_reload_field_to_edit", 
						"pos": block_id, "custom_form": custom_form},
						
						success: function(data){
							
							
							jQuery("#easywpm-edit-fields-bock-"+block_id).html(data);							
							jQuery("#easywpm-edit-fields-bock-"+block_id).slideDown();							
							jQuery("#bup-spinner").hide();								
							
							
							}
					});
		
					
		return false;
	});
    
	
	jQuery(document).on("click", "#bup-adm-check-avail-btn", function(e) {
			
			e.preventDefault();			
			
			var b_category=   jQuery("#bup-category").val();
			var b_date=   jQuery("#bup-start-date").val();
			var b_staff=   jQuery("#bup-staff").val();	
			
			jQuery("#bup-steps-cont-res" ).html( message_wait_availability );		
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "ubp_check_adm_availability", "b_category": b_category, "b_date": b_date , "b_staff": b_staff },
					
					success: function(data){
						
						
						var res = data;								
						jQuery("#bup-steps-cont-res").html(res);					    
						

						}
				});			
			
			 return false;
    		e.preventDefault();		 
				
        });
	
		jQuery(document).on("click", "#bup-adm-check-avail-btn-edit", function(e) {
			
			e.preventDefault();			
			
			var b_category=   jQuery("#bup-category").val();
			var b_date=   jQuery("#bup-start-date").val();
			var b_staff=   jQuery("#bup-staff").val();	
			
			jQuery("#bup-steps-cont-res-edit" ).html( message_wait_availability );		
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "ubp_check_adm_availability_admin", "b_category": b_category, "b_date": b_date , "b_staff": b_staff },
					
					success: function(data){
						
						
						var res = data;								
						jQuery("#bup-steps-cont-res-edit").html(res);					    
						

						}
				});			
			
			 return false;
    		e.preventDefault();		 
				
        });
		
	
	jQuery(document).on("click", ".bup-btn-book-app", function(e) {
			
			e.preventDefault();			
			
			var date_to_book =  jQuery(this).attr("bup-data-date");
			var service_and_staff_id =  jQuery(this).attr("bup-data-service-staff");
			var time_slot =  jQuery(this).attr("bup-data-timeslot");
			
			jQuery("#bup_time_slot").val(time_slot);
			jQuery("#bup_booking_date").val(date_to_book);
			jQuery("#bup_service_staff").val(service_and_staff_id);
			
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "appointment_get_selected_time", 
						   "bup_booking_date": date_to_book,
						   "bup_service_staff": service_and_staff_id,
						   "bup_time_slot": time_slot},
					
					success: function(data){						
						
						var res = data;							
						jQuery("#bup-steps-cont-res").html(res);						

						}
				});				
			
				
			 return false;
    		e.preventDefault();		 
				
    });
	
	jQuery(document).on("click", ".bup-btn-book-app-admin", function(e) {
			
			e.preventDefault();			
			
			var date_to_book =  jQuery(this).attr("bup-data-date");
			var service_and_staff_id =  jQuery(this).attr("bup-data-service-staff");
			var time_slot =  jQuery(this).attr("bup-data-timeslot");
			
			jQuery("#bup_time_slot").val(time_slot);
			jQuery("#bup_booking_date").val(date_to_book);
			jQuery("#bup_service_staff").val(service_and_staff_id);
			
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "appointment_get_selected_time", 
						   "bup_booking_date": date_to_book,
						   "bup_service_staff": service_and_staff_id,
						   "bup_time_slot": time_slot},
					
					success: function(data){						
						
						var res = data;							
						jQuery("#bup-steps-cont-res-edit").html(res);						

						}
				});				
			
				
			
    		e.preventDefault();		 
				
    });
	
	
	jQuery(document).on("click", ".easywpm-load-services-by-cate", function(e) {
		
		e.preventDefault();
		var category_id =  jQuery(this).attr("data-id");			
		
		wpuserspro_load_services(category_id);
		
			
					
	});
	
	
		
	jQuery(document).on("change", "#bup-category", function(e) {
			
			e.preventDefault();			
			
			var b_category=   jQuery("#bup-category").val();
			
			$('#bup-staff').prop('disabled', 'disabled');
			
			$('#bup-staff option:first-child').attr("selected", "selected");
			$('#bup-staff option:first-child').text(wpuserspro_admin_v98.message_wait_staff_box);
							
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "get_cate_dw_admin_ajax", "b_category": b_category},
					
					success: function(data){						
						
						var res = data;						
						jQuery("#bup-staff-booking-list").html(res);					    
						

						}
				});			
			
			 return false;
    		e.preventDefault();		 
				
        });
	
	
	/* open staff member form */	
	jQuery( "#easywpm-staff-editor-box" ).dialog({
			autoOpen: false,			
			width: '400', // overcomes width:'auto' and maxWidth bug
   			maxWidth: 900,
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {
			"Add": function() {				
				
				var ret;
				
				var staff_name=   jQuery("#staff_name").val();
				var staff_email=   jQuery("#staff_email").val();
				var staff_nick=   jQuery("#staff_nick").val();	
				jQuery("#bup-err-message" ).html( '' );		
							
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "wpuserspro_add_staff_confirm", "staff_name": staff_name, "staff_email": staff_email , "staff_nick": staff_nick },
						
						success: function(data){
							
							
							var res = data;						
							
							if(isInteger(res))	
							{
								//load staff								
								wpuserspro_load_staff_adm(res);																
								jQuery("#easywpm-staff-editor-box" ).dialog( "close" );
								
														
							}else{
							
								jQuery("#easywpm-err-message" ).html( res );	
							
							}				
													
							 
							
							
							}
					});
				
				
				
			
			},
			
			"Cancel": function() {
				
				
				jQuery( this ).dialog( "close" );
			},
			
			
			},
			close: function() {
			
			
			}
	});
	
	
	/* open client member form */	
	jQuery( "#easywpm-client-new-box" ).dialog({
			autoOpen: false,			
			width: '400px', // overcomes width:'auto' and maxWidth bug
   			
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {
			"Add": function() {				
				
				var ret;
				
				var client_name=   jQuery("#client_name").val();
				var client_last_name=   jQuery("#client_last_name").val();
				var client_email=   jQuery("#client_email").val();
					
				jQuery("#easywpm-err-message" ).html( '' );		
							
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "wpuserspro_add_client_confirm", "client_name": client_name, "client_last_name": client_last_name , "client_email": client_email },
						
						success: function(data){					
							
													
							var res =jQuery.parseJSON(data);				
							
							if(res.response=='OK')	
							{
																
								jQuery("#wpuserspro_client_id" ).val(res.user_id);	
								jQuery("#easywpmclientsel" ).val(res.content);	
								jQuery("#easywpm-client-new-box" ).dialog( "close" );							
														
							}else{ //ERROR
							
								jQuery("#easywpm-add-client-message" ).html( res.content );	
							
							}				
							
						}
					});
				
				
				
			
			},
			
			"Cancel": function() {
				
				
				jQuery( this ).dialog( "close" );
			},
			
			
			},
			close: function() {
			
			
			}
	});
	
	//this adds the user and loads the user's details	
	jQuery(document).on("click", "#easywpm-save-acc-settings-staff", function(e) {
			
			var staff_id =  jQuery(this).attr("ubp-staff-id");		
			
			var bup_per_backend_access=   jQuery("#wpuserspro_per_backend_access").val();
			var bup_upload_picture=   jQuery("#wpuserspro_upload_picture").val();			
			var bup_role=   jQuery("#wpuserspro_role_key").val();			
			var wpuserspro_per_bugs_access=   jQuery("#wpuserspro_per_bugs_access").val();
			var wpuserspro_woo_orders_access=   jQuery("#wpuserspro_woo_orders_access").val();
			
				
				
			jQuery("#bup-err-message" ).html( '' );	
			jQuery("#easywpm-loading-animation-acc-setting-staff" ).show( );		
			
			
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_update_user_account_settings",
					"staff_id": staff_id,
					"wpuserspro_per_backend_access": bup_per_backend_access,
					 "wpuserspro_upload_picture": bup_upload_picture ,
					"role": bup_role,
					"bugs": wpuserspro_per_bugs_access,
					"woo_orders": wpuserspro_woo_orders_access
					 
					 },
					
					success: function(data){
						
						
						var res = data;		
						
						jQuery("#bup-err-message" ).html( res );						
						jQuery("#easywpm-loading-animation-acc-setting-staff" ).hide( );	
						
						
						}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
	
	/* change priority status */	
	jQuery( "#easywpm-ticket-change-priority-box" ).dialog({
			autoOpen: false,			
			width: '400px', // overcomes width:'auto' and maxWidth bug
   			
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {
			"Submit": function() {				
				
				var ret;
				
				var ticket_id=   jQuery("#ticket_id").val();
				var ticket_priority=   jQuery("#ticket_priority").val();				
				var notify_user = jQuery("#easywpm-change-ticket-status").prop("checked");
				
				if(ticket_priority==''){alert(wpuserspro_admin_v98.msg_priority_change); return;} 
			
				if(notify_user){ notify_user = '1'};
								
				jQuery("#easywpm-err-message" ).html( '' );		
							
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "wpuserspro_update_ticket_priority_confirm", "ticket_id": ticket_id, "ticket_priority": ticket_priority , "notify_user": notify_user },
						
						success: function(data){
							
							var res =jQuery.parseJSON(data);					
							
							
							jQuery("#easywpm-ticket-priority-label" ).html( res.content );																
							jQuery("#easywpm-ticket-change-priority-box" ).dialog( "close" );					
							
						}
					});
				
				
				
			
			},
			
			"Cancel": function() {
				
				
				jQuery( this ).dialog( "close" );
			},
			
			
			},
			close: function() {
			
			
			}
	});
	
		jQuery(document).on("click", "#easywpm-add-privatecrede-btn", function(e) {
		
		var ticket_id =  jQuery(this).attr("ticket-id");
		
		jQuery("#bup-spinner").show();
		
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_credentials_form", "private_ticket_id": ticket_id},
					
					success: function(data){						
						
						var res = data;
						jQuery("#easywpm-private-credentials-add-modify" ).html( res );
						jQuery("#easywpm-private-credentials-add-modify" ).dialog( "open" );
						jQuery("#bup-spinner").hide();						

					}
				});				
			
				
			
    		e.preventDefault();		 
				
    });
	
	jQuery(document).on("click", "#easywpm-add-note-btn", function(e) {
		
		var ticket_id =  jQuery(this).attr("ticket-id");
		
		jQuery("#bup-spinner").show();
		
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_notes_form", "ticket_id": ticket_id},
					
					success: function(data){						
						
						var res = data;
						jQuery("#easywpm-private-notes-add-modify" ).html( res );
						jQuery("#easywpm-private-notes-add-modify" ).dialog( "open" );
						jQuery("#bup-spinner").hide();						

					}
				});				
			
				
			
    		e.preventDefault();		 
				
    });
	
	/* change ticket status */	
	jQuery( "#easywpm-ticket-change-status-box" ).dialog({
			autoOpen: false,			
			width: '400px', // overcomes width:'auto' and maxWidth bug
   			
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {
			"Submit": function() {				
				
				var ret;
				
				var ticket_id=   jQuery("#ticket_id").val();
				var ticket_status=   jQuery("#ticket_status").val();				
				var notify_user = jQuery("#easywpm-change-ticket-status").prop("checked");
				
				if(ticket_status==''){alert(wpuserspro_admin_v98.msg_status_change); return;} 
			
				if(notify_user){ notify_user = '1'};
								
				jQuery("#easywpm-err-message" ).html( '' );		
							
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "wpuserspro_update_ticket_status_confirm", "ticket_id": ticket_id, "ticket_status": ticket_status , "notify_user": notify_user },
						
						success: function(data){
							
							var res =jQuery.parseJSON(data);					
							
							
							jQuery("#easywpm-ticket-status-label" ).html( res.content );							
							jQuery("#easywpm-gen-info-cont").css({ 'background-color': res.color });
																		
							jQuery("#easywpm-ticket-change-status-box" ).dialog( "close" );					
							
						}
					});
				
				
				
			
			},
			
			"Cancel": function() {
				
				
				jQuery( this ).dialog( "close" );
			},
			
			
			},
			close: function() {
			
			
			}
	});
	
	/* open owner form */	
	jQuery( "#easywpm-owner-edit-box" ).dialog({
			autoOpen: false,			
			width: '400px', // overcomes width:'auto' and maxWidth bug
   			
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {
			
			"Cancel": function() {
				
				
				jQuery( this ).dialog( "close" );
			},
			
			
			},
			close: function() {
			
			
			}
	});
	
	
	jQuery(document).on("click", "#easywpm-update-ticket-status", function(e) {
			
			e.preventDefault();	
			
			var ticket_id =  jQuery(this).attr("ticket-id");
			var staff_id =  jQuery(this).attr("staff-id");
			
			jQuery("#bup-spinner").show();	
			
		
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_ticket_status_get_actions", "ticket_id": ticket_id, "staff_id": staff_id},
					
					success: function(data){						
						
						var res = data;
						jQuery("#easywpm-ticket-change-status-box" ).html( res );
						jQuery("#easywpm-ticket-change-status-box" ).dialog( "open" );
						
						jQuery("#bup-spinner").hide();
										

					}
				});				
			
				
				
    });
	
	jQuery(document).on("click", "#easywpm-update-ticket-priority", function(e) {
			
			e.preventDefault();	
			
			var ticket_id =  jQuery(this).attr("ticket-id");
			var staff_id =  jQuery(this).attr("staff-id");
			
			jQuery("#bup-spinner").show();	
			
		
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_ticket_priority_get_actions", "ticket_id": ticket_id, "staff_id": staff_id},
					
					success: function(data){						
						
						var res = data;
						jQuery("#easywpm-ticket-change-priority-box" ).html( res );
						jQuery("#easywpm-ticket-change-priority-box" ).dialog( "open" );
						
						jQuery("#bup-spinner").hide();
										

					}
				});				
			
				
				
    });
	
	jQuery(document).on("click", ".easywpm-owner-actions-load", function(e) {
			
			e.preventDefault();	
			
			var ticket_id =  jQuery(this).attr("ticket-id");
			var staff_id =  jQuery(this).attr("staff-id");	
			
			
			jQuery("#easywpm-change-actions-id-"+staff_id ).html( wpuserspro_admin_v98.msg_wait );
			
			
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_update_owner_get_actions", "ticket_id": ticket_id, "staff_id": staff_id},
					
					success: function(data){						
						
						var res = data;
						jQuery("#easywpm-change-actions-id-"+staff_id ).html( res );
						jQuery("#easywpm-change-actions-id-"+staff_id ).show();
										

					}
				});				
			
				
			
    		e.preventDefault();		 
				
    });
	
	jQuery(document).on("click", ".easywpm-confirm-ownerchange-btn", function(e) {
			
			e.preventDefault();	
			
			var ticket_id =  jQuery(this).attr("ticket-id");
			var staff_id =  jQuery(this).attr("staff-id");
			
			var add_depto = jQuery("#easywpm-change-owner-act-add-to-depto").prop("checked");
			var share_ticket = jQuery("#easywpm-change-owner-act-share-ticket").prop("checked");
			
			if(add_depto){ add_depto = '1'};			
			if(share_ticket){ share_ticket = '1'};
			
			
			//jQuery("#easywpm-change-actions-id-"+staff_id ).html( wpuserspro_admin_v98.msg_wait );
			
			
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_update_owner_confirm", "ticket_id": ticket_id, "staff_id": staff_id, "add_depto": add_depto , "share_ticket": share_ticket},
					
					success: function(data){						
						
						var res = data;
						jQuery("#easywpm-owner-label").html( res );
						jQuery("#easywpm-owner-edit-box" ).dialog( "close" );	
														

					}
				});				
			
				
				
    });
	
	
	jQuery(document).on("click", "#easywpm-update-ticket-owner", function(e) {
			
			e.preventDefault();	
			
			var ticket_id =  jQuery(this).attr("ticket-id");
			jQuery("#bup-spinner").show();
			
			
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_update_owner_get_form", "ticket_id": ticket_id},
					
					success: function(data){						
						
						var res = data;
						jQuery("#easywpm-owner-edit-box" ).html( res );
						jQuery("#easywpm-owner-edit-box" ).dialog( "open" );	
						jQuery("#bup-spinner").hide();					

					}
				});				
			
				
			
    		e.preventDefault();		 
				
    });
	
	jQuery(document).on("click", "#easywpm-btn-client-new-admin", function(e) {
			
			e.preventDefault();		
			
			
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_client_get_add_form"},
					
					success: function(data){						
						
						var res = data;
						jQuery("#easywpm-client-new-box" ).html( res );
						jQuery("#easywpm-client-new-box" ).dialog( "open" );						

					}
				});				
			
				
			
    		e.preventDefault();		 
				
    });
	
	
	
		
	
	
	/* add Payment */	
	jQuery( "#bup-confirmation-cont" ).dialog({
			autoOpen: false,			
			width: '300', //   			
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {			
			
			"Ok": function() {				
				
				jQuery( this ).dialog( "close" );
			}
			
			
			},
			close: function() {
			
			
			}
	});
	
	
	
	
	/* add break */	
	jQuery( "#bup-breaks-new-box" ).dialog({
			autoOpen: false,			
			width: '300', //   			
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {			
			
			"Cancel": function() {				
				
				jQuery( this ).dialog( "close" );
			},
			
			"Save": function() {				
				
				var bup_payment_amount=   jQuery("#bup_payment_amount").val();
				var bup_payment_transaction=   jQuery("#bup_payment_transaction").val();
				var bup_payment_date=   jQuery("#bup_payment_date").val();
				var bup_booking_id=   jQuery("#bup_appointment_id").val();
				var bup_payment_status=   jQuery("#bup_payment_status").val();	
				
				var bup_payment_id=   jQuery("#bup_payment_id").val();			
				
				if(bup_payment_amount==''){alert(err_message_payment_amount); return;}
				if(bup_payment_date==''){alert(err_message_payment_date); return;}	
							
				
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "bup_staff_break_add_confirm", 
						       "bup_payment_amount": bup_payment_amount,
							   "bup_payment_transaction": bup_payment_transaction,
							   "bup_payment_date": bup_payment_date,
							   "bup_booking_id": bup_booking_id,
							   "bup_payment_id": bup_payment_id,
							   "bup_payment_status": bup_payment_status },
						
						success: function(data){	
							
							jQuery("#bup-new-payment-cont" ).html( data );
							jQuery("#bup-new-payment-cont" ).dialog( "close" );	
							bup_load_appointment_payments(bup_booking_id);						
							
							
							}
					});
					
					
				
			
			}
			
			
			},
			close: function() {
			
			
			}
	});
	
	

	
	/* add Payment */	
	jQuery( "#bup-new-payment-cont" ).dialog({
			autoOpen: false,			
			width: '300', //   			
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {			
			
			"Cancel": function() {				
				
				jQuery( this ).dialog( "close" );
			},
			
			"Save": function() {				
				
				var bup_payment_amount=   jQuery("#bup_payment_amount").val();
				var bup_payment_transaction=   jQuery("#bup_payment_transaction").val();
				var bup_payment_date=   jQuery("#bup_payment_date").val();
				var bup_booking_id=   jQuery("#bup_appointment_id").val();
				var bup_payment_status=   jQuery("#bup_payment_status").val();	
				
				var bup_payment_id=   jQuery("#bup_payment_id").val();			
				
				if(bup_payment_amount==''){alert(err_message_payment_amount); return;}
				if(bup_payment_date==''){alert(err_message_payment_date); return;}	
							
				
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "bup_admin_payment_confirm", 
						       "bup_payment_amount": bup_payment_amount,
							   "bup_payment_transaction": bup_payment_transaction,
							   "bup_payment_date": bup_payment_date,
							   "bup_booking_id": bup_booking_id,
							   "bup_payment_id": bup_payment_id,
							   "bup_payment_status": bup_payment_status },
						
						success: function(data){	
							
							jQuery("#bup-new-payment-cont" ).html( data );
							jQuery("#bup-new-payment-cont" ).dialog( "close" );	
							bup_load_appointment_payments(bup_booking_id);						
							
							
							}
					});
					
					
				
			
			}
			
			
			},
			close: function() {
			
			
			}
	});
	
	/* add note */	
	jQuery( "#bup-new-note-cont" ).dialog({
			autoOpen: false,			
			width: '300', //   			
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {			
			
			"Cancel": function() {				
				
				jQuery( this ).dialog( "close" );
			},
			
			"Save": function() {				
				
				var bup_note_title=   jQuery("#bup_note_title").val();
				var bup_note_text=   jQuery("#bup_note_text").val();
				var bup_note_id=   jQuery("#bup_note_id").val();
				var bup_booking_id=   jQuery("#bup_appointment_id").val();
								
				if(bup_note_title==''){alert(err_message_note_title); return;}
				if(bup_note_text==''){alert(err_message_note_text); return;}	
							
				
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "bup_admin_note_confirm", 
						       "bup_note_title": bup_note_title,
							   "bup_booking_id": bup_booking_id,
							   "bup_note_text": bup_note_text,
							   "bup_note_id": bup_note_id},
						
						success: function(data){	
							
							jQuery("#bup-new-note-cont" ).html( data );
							jQuery("#bup-new-note-cont" ).dialog( "close" );	
							bup_load_appointment_notes(bup_booking_id);						
							
							
							}
					});
					
					
				
			
			}
			
			
			},
			close: function() {
			
			
			}
	});
	
	jQuery(document).on("click", ".bup-payment-deletion", function(e) {
			
			e.preventDefault();
			
			var appointment_id = jQuery(this).attr("bup-appointment-id");			
			var payment_id =  jQuery(this).attr("bup-payment-id");	 						
    		
			doIt=confirm(err_message_payment_delete);
		  
		    if(doIt)
		    {
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "bup_delete_payment",  "payment_id": payment_id ,  "appointment_id": appointment_id },
						
						success: function(data){	
						
							bup_load_appointment_payments(appointment_id);	
						
						
							
							}
					});
				
				
			}
			
    		e.preventDefault();
			 
				
        });
	
	
	jQuery(document).on("click", ".bup-note-deletion", function(e) {
			
			e.preventDefault();
			
			var appointment_id = jQuery(this).attr("bup-appointment-id");			
			var note_id =  jQuery(this).attr("bup-note-id");	 						
    		
			doIt=confirm(err_message_note_delete);
		  
		    if(doIt)
		    {
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "bup_delete_note",  "note_id": note_id ,  "appointment_id": appointment_id },
						
						success: function(data){	
						
							bup_load_appointment_notes(appointment_id);	
						
						
							
							}
					});
				
				
			}
			
    		e.preventDefault();
			 
				
        });
	
	jQuery(document).on("click", ".bup-payment-edit", function(e) {
			
			e.preventDefault();
			
			var appointment_id = jQuery(this).attr("bup-appointment-id");			
			var payment_id =  jQuery(this).attr("bup-payment-id");	 						
    		
			
			jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "bup_get_payment_form",  "payment_id": payment_id ,  "appointment_id": appointment_id },
						
						success: function(data){	
						
						
							jQuery("#bup-new-payment-cont" ).html( data );	
							jQuery("#bup-new-payment-cont" ).dialog( "open" );	
							
							var uultra_date_format =  jQuery('#uultra_date_format').val();									
							if(uultra_date_format==''){uultra_date_format='dd/mm/yy';}	
						
							jQuery( ".bupro-datepicker" ).datepicker({ showOtherMonths: true, dateFormat: uultra_date_format});
						
							jQuery("#ui-datepicker-div").wrap('<div class="ui-datepicker-wrapper" />');				
						
							
							}
					});			
				

    		e.preventDefault();
			 
				
        });
	
	//
	jQuery(document).on("click", "#bup-adm-add-payment", function(e) {
			
			e.preventDefault();	
			jQuery("#bup-spinner").show();		
			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_get_payment_form"},
					
					success: function(data){					
												
						jQuery("#bup-new-payment-cont" ).html( data );	
						jQuery("#bup-new-payment-cont" ).dialog( "open" );	
						jQuery("#bup-spinner").hide();	
						
						
						var uultra_date_format =  jQuery('#uultra_date_format').val();
									
						if(uultra_date_format==''){uultra_date_format='dd/mm/yy';}	
					
						jQuery( ".bupro-datepicker" ).datepicker({ showOtherMonths: true, dateFormat: uultra_date_format});
					
						jQuery("#ui-datepicker-div").wrap('<div class="ui-datepicker-wrapper" />');				
						
									     
						
						
						}
				});
			
			
			
    		e.preventDefault();
			 
				
    });
	
	
	jQuery(document).on("click", ".bup-breaks-add", function(e) {
			
			e.preventDefault();	
						
			var day_id = jQuery(this).attr("day-id");
			var staff_id=   jQuery("#staff_id").val();
			
			jQuery("#bup-break-add-break-" +day_id).show();			
			jQuery("#bup-break-add-break-" +day_id).html( message_wait_availability );
			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_get_break_add", 
							"day_id": day_id,
							"staff_id": staff_id},
					
					success: function(data){								
												
						jQuery("#bup-break-add-break-" +day_id).html( data );
									
												
						
												
						
						}
				});
			
			
			
    		e.preventDefault();
			 
				
    });
	
	//confirm break addition
	jQuery(document).on("click", ".bup-button-submit-breaks", function(e) {
			
			e.preventDefault();	
						
			var day_id = jQuery(this).attr("day-id");
			var staff_id=   jQuery("#staff_id").val();	
			
			var bup_from=   jQuery("#bup-break-from-"+day_id).val();
			var bup_to=   jQuery("#bup-break-to-"+day_id).val();
			
		
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_break_add_confirm", 
							"day_id": day_id,
							"staff_id": staff_id,
							"from": bup_from,
							"to": bup_to},
					
					success: function(data){
						
						var res = data	;												
						jQuery("#bup-break-message-add-" +day_id).html( data );
						bup_reload_staff_breaks(staff_id, day_id);
												
						
						}
				});
			
			
			
    		e.preventDefault();
			 
				
    });
	
	jQuery(document).on("click", "#bup-adm-add-note", function(e) {
			
			e.preventDefault();	
			jQuery("#bup-spinner").show();		
			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_get_note_form"},
					
					success: function(data){					
												
						jQuery("#bup-new-note-cont" ).html( data );	
						jQuery("#bup-new-note-cont" ).dialog( "open" );	
						jQuery("#bup-spinner").hide();	
						
												
						
						}
				});
			
			
			 // Cancel the default action
			
    		e.preventDefault();
			 
				
    });
	
	jQuery(document).on("click", ".bup-note-edit", function(e) {
			
			e.preventDefault();	
			jQuery("#bup-spinner").show();	
			
			var note_id = jQuery(this).attr("bup-note-id");	
			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_get_note_form",
					       "note_id": note_id},
					
					success: function(data){					
												
						jQuery("#bup-new-note-cont" ).html( data );	
						jQuery("#bup-new-note-cont" ).dialog( "open" );	
						jQuery("#bup-spinner").hide();	
						
												
						
						}
				});
			
			
			 // Cancel the default action
			
    		e.preventDefault();
			 
				
    });
	
	/* edit appointment */	
	jQuery( "#bup-appointment-edit-box" ).dialog({
			autoOpen: false,			
			width: '880', // overcomes width:'auto' and maxWidth bug
   			
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {			
			
			"Close": function() {				
				jQuery("#bup-appointment-edit-box" ).html('');
				jQuery( this ).dialog( "close" );
			}			
			
			},
			close: function() {
				
				jQuery("#bup-appointment-edit-box" ).html('');
			
			
			}
	});
	
	
	
	jQuery(document).on("click", "#bup-adm-update-info", function(e) {
			
			e.preventDefault();	
			jQuery("#bup-spinner").show();
			
			
			var booking_id =  jQuery("#bup_appointment_id").val();	
			var serial_data = $('.bup-custom-field').serialize();
			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_update_booking_info", "custom_fields": serial_data, "booking_id": booking_id},
					
					success: function(data){					
												
						jQuery("#bup-confirmation-cont" ).html( gen_message_infoupdate_conf);	 
						jQuery("#bup-confirmation-cont" ).dialog( "open" );	
						jQuery("#bup-spinner").hide();	
						
						
												
						
						}
				});
			
			
    		e.preventDefault();
			 
				
    });
	
	
	
	jQuery(document).on("click", "#easywpm-add-sites-btn", function(e) {
			
			e.preventDefault();	
			jQuery("#bup-spinner").show();
			
			jQuery('#easywpm-site-add-site-box').dialog('option', 'title', wpuserspro_admin_v98.msg_site_add);
			
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_get_site_add_form"},
					
					success: function(data){					
												
						jQuery("#easywpm-site-add-department-box" ).html( data);	 
						jQuery("#easywpm-site-add-department-box" ).dialog( "open" );	
						jQuery("#bup-spinner").hide();	
						
						
												
						
						}
				});
			
			
    		e.preventDefault();
			 
				
    });
	
	jQuery(document).on("click", ".easywpm-edit-department-btn", function(e) {
			
			e.preventDefault();	
			jQuery("#bup-spinner").show();
			
			var department_id =  jQuery(this).attr("department-id");
			jQuery('#easywpm-department-add-department-box').dialog('option', 'title', wpuserspro_admin_v98.msg_department_edit);
			
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_get_department_add_form",
						"department_id": department_id},
					
					success: function(data){					
												
						jQuery("#easywpm-department-add-department-box" ).html( data);	 
						jQuery("#easywpm-department-add-department-box" ).dialog( "open" );	
						jQuery("#bup-spinner").hide();	
						
						
												
						
						}
				});
			
			
    		e.preventDefault();
			 
				
    });
	
	
	jQuery(document).on("click", ".easywpm-edit-product-btn", function(e) {
			
			e.preventDefault();	
			jQuery("#bup-spinner").show();
			
			var product_id =  jQuery(this).attr("product-id");
						
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_get_site_add_form",
						"product_id": product_id},
					
					success: function(data){					
												
						jQuery("#easywpm-edit-product-box" ).html( data);	 
						jQuery("#easywpm-edit-product-box" ).dialog( "open" );	
						jQuery("#bup-spinner").hide();	
						
						
												
						
						}
				});
			
			
    		e.preventDefault();
			 
				
    });
	
	
	

	
	
	// on window resize run function
	$(window).resize(function () {
		//fluidDialog();
	});
	
	// catch dialog if opened within a viewport smaller than the dialog width
	$(document).on("dialogopen", ".ui-dialog", function (event, ui) {
		//fluidDialog();
	});
	
	function fluidDialog()
	 {
		var $visible = $(".ui-dialog:visible");
		// each open dialog
		$visible.each(function () 
		{
			var $this = $(this);
			
			var dialog = $this.find(".ui-dialog-content").data("dialog");
			
			// if fluid option == true
			if (dialog.options.fluid) {
				var wWidth = $(window).width();
				// check window width against dialog width
				if (wWidth < dialog.options.maxWidth + 50) {
					// keep dialog from filling entire screen
					$this.css("max-width", "90%");
				} else {
					// fix maxWidth bug
					$this.css("max-width", dialog.options.maxWidth);
				}
				//reposition dialog
				dialog.option("position", dialog.options.position);
			}
		});
	
	}


	/* open service form */	
	jQuery( "#easywpm-category-editor-box" ).dialog({
			autoOpen: false,																							
			width: 550,
			modal: true,
			buttons: {
			"Update": function() {				
				
				var category_id=   jQuery("#easywpm-category-id").val();
				var category_title=   jQuery("#easywpm-title").val();				
				
				var department_id =  jQuery("#bup-departments" ).val();				
				var category_color =  jQuery("#easywpm-category-color" ).val();
				var category_font_color =  jQuery("#easywpm-category-font-color" ).val();
			
				
				if(category_title==''){alert(wpuserspro_admin_v98.msg_category_input_title); return;}
				
				jQuery.ajax({
							type: 'POST',
							url: ajaxurl,
							data: {"action": "wpuserspro_update_category",  "category_id": category_id ,
							"category_title": category_title,
							"department_id": department_id,
							
							"category_color": category_color,
							"category_font_color": category_font_color,
							 },
							
							success: function(data){	
							
								jQuery("#easywpm-category-editor-box" ).dialog( "close" );				
								wpuserspro_load_services();
							
								
								
								}
						});
			
			},
			
			"Cancel": function() {
				
				
				jQuery( this ).dialog( "close" );
			},
			
			
			},
			close: function() {
			
			
			}
	});	
	
	/* open priority form */	
	jQuery( "#easywpm-priority-add-priority-box" ).dialog({
			autoOpen: false,																							
			width: 500,
			modal: true,
			buttons: {
			"Save": function() {
				
				var priority_title=   jQuery("#easywpm-title").val();
				var priority_id=   jQuery("#easywpm-priority-id").val(); 
				var priority_color =  jQuery("#easywpm-priority-color" ).val();
				var reply_within=   jQuery("#easywpm-reply-within").val();
				var resolve_within=   jQuery("#easywpm-resolve-within").val();
				var visibility=   jQuery("#easywpm-priority-private").val();
				
				
				if(priority_title==''){alert(wpuserspro_admin_v98.msg_priority_input_title); return;}
				
				jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_add_priority_confirm",
					"priority_title":priority_title,
					"priority_color":priority_color,
					"priority_id": priority_id,
					"reply_within": reply_within,
					"resolve_within": resolve_within,
					"visibility": visibility},
					
					success: function(data){		
								
						jQuery("#bup-spinner").hide();						
						jQuery("#easywpm-priority-add-priority-box" ).dialog( "close" );						
						wpuserspro_load_priorities();										
						
						}
				});				
				
				
			
			},
			
			"Cancel": function() {
				
				
				jQuery( this ).dialog( "close" );
			},
			
			
			},
			close: function() {
			
			
			}
	});
	
	/* open delete form */	
	jQuery( "#easywpm-priority-delete-box" ).dialog({
			autoOpen: false,																							
			width: 500,
			modal: true,
			buttons: {
						
			"Cancel": function() {
				
				
				jQuery( this ).dialog( "close" );
			},
			
			
			},
			close: function() {
			
			
			}
	});
	
	/* open delete form */	
	jQuery( "#easywpm-department-delete-box" ).dialog({
			autoOpen: false,																							
			width: 500,
			modal: true,
			buttons: {
						
			"Cancel": function() {
				
				
				jQuery( this ).dialog( "close" );
			},
			
			
			},
			close: function() {
			
			
			}
	});
	
	/* open delete form */	
	jQuery( "#easywpm-delete-product-box" ).dialog({
			autoOpen: false,																							
			width: 500,
			modal: true,
			buttons: {
						
			"Cancel": function() {
				
				
				jQuery( this ).dialog( "close" );
			},
			
			
			},
			close: function() {
			
			
			}
	});
	
	/* open department form */	
	jQuery( "#easywpm-department-add-department-box" ).dialog({
			autoOpen: false,																							
			width: 500,
			modal: true,
			buttons: {
			"Save": function() {
				
				var department_title=   jQuery("#easywpm-title").val();
				var department_id=   jQuery("#wpuserspro_department_id").val();
				var department_site_id=   jQuery("#easywpm-sites").val();
				var department_color =  jQuery("#easywpm-category-color" ).val();
				
				if(department_title==''){alert(wpuserspro_admin_v98.msg_department_input_title); return;}
				
				jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_add_department_confirm",
					"department_title":department_title,
					"department_color":department_color,
					"department_id": department_id,
					"department_site_id": department_site_id},
					
					success: function(data){		
								
						jQuery("#bup-spinner").hide();						
						jQuery("#easywpm-department-add-department-box" ).dialog( "close" );						
						wpuserspro_load_departments();
						
						}
				});				
				
				
			
			},
			
			"Cancel": function() {
				
				
				jQuery( this ).dialog( "close" );
			},
			
			
			},
			close: function() {
			
			
			}
	});
	
	
	/* open product form */	
	jQuery( "#easywpm-edit-product-box" ).dialog({
			autoOpen: false,																							
			width: 500,
			modal: true,
			buttons: {
			"Save": function() {
				
				var product_title=   jQuery("#easywpm-site-name").val();
				var product_id=   jQuery("#wpuserspro_product_id").val();
								
				if(product_title==''){alert(wpuserspro_admin_v98.msg_input_site_name); return;}
				
				jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_add_site_confirm",
					"product_name":product_title,
					"product_id":product_id},
					
					success: function(data){		
								
						jQuery("#bup-spinner").hide();						
						jQuery("#easywpm-edit-product-box" ).dialog( "close" );						
						wpuserspro_load_sites();
						
						}
				});				
				
				
			
			},
			
			"Cancel": function() {
				
				
				jQuery( this ).dialog( "close" );
			},
			
			
			},
			close: function() {
			
			
			}
	});
	
	/* edit department form */	
	jQuery( "#easywpm-department-edit-department-box" ).dialog({
			autoOpen: false,																							
			width: 500,
			modal: true,
			buttons: {
			"Save": function() {
				
				var department_title=   jQuery("#easywpm-title").val();
				var department_id=   jQuery("#easywpm-department-id").val(); 
				var department_site_id=   jQuery("#easywpm-sites").val();
				var department_color =  jQuery("#easywpm-category-color" ).val();
				
								
				if(department_title==''){alert(wpuserspro_admin_v98.msg_department_input_title); return;}
				
				jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_add_department_confirm",
					"department_title":department_title,
					"department_color":department_color,
					"department_id": department_id,
					"department_site_id": department_site_id},
					
					success: function(data){		
								
						jQuery("#bup-spinner").hide();						
						jQuery("#easywpm-department-edit-department-box" ).dialog( "close" );						
						wpuserspro_load_departments();
						
						
												
						
						}
				});				
				
				
			
			},
			
			"Cancel": function() {
				
				
				jQuery( this ).dialog( "close" );
			},
			
			
			},
			close: function() {
			
			
			}
	});
	
	/* open category form */	
	jQuery( "#easywpm-site-add-department-box" ).dialog({
			autoOpen: false,																							
			width: 300,
			modal: true,
			buttons: {
			"Save": function() {
				
				var product_name=   jQuery("#easywpm-site-name").val();
				var product_id=   jQuery("#wpuserspro_site_id").val();
				
				if(product_name==''){alert(wpuserspro_admin_v98.msg_input_site_name); return;}
				
				jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_add_site_confirm",
					"product_name":product_name,
					"product_id": product_id},
					
					success: function(data){		
								
						jQuery("#bup-spinner").hide();						
						jQuery("#easywpm-site-add-department-box" ).dialog( "close" );						
						wpuserspro_load_sites();
						
						
												
						
						}
				});				
				
				
			
			},
			
			"Cancel": function() {
				
				
				jQuery( this ).dialog( "close" );
			},
			
			
			},
			close: function() {
			
			
			}
	});
	
	

		
	//this adds the user and loads the user's details	
	jQuery(document).on("click", "#ubp-save-glogal-business-hours", function(e) {
			
			e.preventDefault();			
			
			var bup_mon_from=   jQuery("#bup-mon-from").val();
			var bup_mon_to=   jQuery("#bup-mon-to").val();			
			var bup_tue_from=   jQuery("#bup-tue-from").val();
			var bup_tue_to=   jQuery("#bup-tue-to").val();			
			var bup_wed_from=   jQuery("#bup-wed-from").val();
			var bup_wed_to=   jQuery("#bup-wed-to").val();			
			var bup_thu_from=   jQuery("#bup-thu-from").val();
			var bup_thu_to=   jQuery("#bup-thu-to").val();			
			var bup_fri_from=   jQuery("#bup-fri-from").val();
			var bup_fri_to=   jQuery("#bup-fri-to").val();			
			var bup_sat_from=   jQuery("#bup-sat-from").val();
			var bup_sat_to=   jQuery("#bup-sat-to").val();			
			var bup_sun_from=   jQuery("#bup-sun-from").val();
			var bup_sun_to=   jQuery("#bup-sun-to").val();
			
			
			
				
			jQuery("#bup-err-message" ).html( '' );	
			jQuery("#bup-loading-animation-business-hours" ).show( );		
			
			
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "ubp_update_global_business_hours", 
					"bup_mon_from": bup_mon_from, "bup_mon_to": bup_mon_to ,
					"bup_tue_from": bup_tue_from, "bup_tue_to": bup_tue_to ,
					"bup_wed_from": bup_wed_from, "bup_wed_to": bup_wed_to ,
					"bup_thu_from": bup_thu_from, "bup_thu_to": bup_thu_to ,
					"bup_fri_from": bup_fri_from, "bup_fri_to": bup_fri_to ,
					"bup_sat_from": bup_sat_from, "bup_sat_to": bup_sat_to ,
					"bup_sun_from": bup_sun_from, "bup_sun_to": bup_sun_to ,
					 
					 },
					
					success: function(data){
						
						
						var res = data;		
						
						jQuery("#bup-err-message" ).html( res );						
						jQuery("#bup-loading-animation-business-hours" ).hide( );		
						
						
						
						
						}
				});
			
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 
				
        });
		
		//this adds the user and loads the user's details	
	jQuery(document).on("click", ".easywpmembers_restore_template", function(e) {
			
			
			var template_id =  jQuery(this).attr("b-template-id");
			jQuery("#easywpmembers_email_template").val(template_id);
			jQuery("#easywpmembers_reset_email_template").val('yes');
			
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_reset_email_template", 
					"email_template": template_id					
					
					 
					 },
					
					success: function(data){
						
						
						var res = data;								
						$("#b_frm_settings").submit();				
						
						
						}
				});
			
			
			 
				
        });
		
		//this adds the user and loads the user's details	
	jQuery(document).on("click", "#ubp-save-glogal-business-hours-staff", function(e) {
			
			e.preventDefault();			
			
			var staff_id =  jQuery(this).attr("ubp-staff-id");
			
			var bup_mon_from=   jQuery("#bup-mon-from").val();
			var bup_mon_to=   jQuery("#bup-mon-to").val();			
			var bup_tue_from=   jQuery("#bup-tue-from").val();
			var bup_tue_to=   jQuery("#bup-tue-to").val();			
			var bup_wed_from=   jQuery("#bup-wed-from").val();
			var bup_wed_to=   jQuery("#bup-wed-to").val();			
			var bup_thu_from=   jQuery("#bup-thu-from").val();
			var bup_thu_to=   jQuery("#bup-thu-to").val();			
			var bup_fri_from=   jQuery("#bup-fri-from").val();
			var bup_fri_to=   jQuery("#bup-fri-to").val();			
			var bup_sat_from=   jQuery("#bup-sat-from").val();
			var bup_sat_to=   jQuery("#bup-sat-to").val();			
			var bup_sun_from=   jQuery("#bup-sun-from").val();
			var bup_sun_to=   jQuery("#bup-sun-to").val();			
				
			jQuery("#bup-err-message" ).html( '' );	
			jQuery("#bup-loading-animation-business-hours" ).show( );		
			
			
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "ubp_update_staff_business_hours", 
					"staff_id": staff_id,					
					"bup_mon_from": bup_mon_from, "bup_mon_to": bup_mon_to ,
					"bup_tue_from": bup_tue_from, "bup_tue_to": bup_tue_to ,
					"bup_wed_from": bup_wed_from, "bup_wed_to": bup_wed_to ,
					"bup_thu_from": bup_thu_from, "bup_thu_to": bup_thu_to ,
					"bup_fri_from": bup_fri_from, "bup_fri_to": bup_fri_to ,
					"bup_sat_from": bup_sat_from, "bup_sat_to": bup_sat_to ,
					"bup_sun_from": bup_sun_from, "bup_sun_to": bup_sun_to ,
					 
					 },
					
					success: function(data){
						
						
						var res = data;		
						
						jQuery("#bup-err-message" ).html( res );						
						jQuery("#bup-loading-animation-business-hours" ).hide( );		
						
						
						
						
						}
				});
			
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 
				
        });
		
	
	    var $form   = $('#business-hours');
		jQuery(document).on("click", ".bup_select_start", function(e) {	
		//$('.bup_select_start').on('change', function () {
			
			var $row = $(this).parent(),
				$end_select = $('.bup_select_end', $row),
				$start_select = $(this);
	
			if ($start_select.val()) {
				$end_select.show();
				$('span', $row).show();
	
				var start_time = $start_select.val();
	
				$('span > option', $end_select).each(function () {
					$(this).unwrap();
				});
	
				// Hides end time options with value less than in the start time
				$('option', $end_select).each(function () {
					if ($(this).val() <= start_time) {
						$(this).wrap("<span>").parent().hide();
					}
				});
				
			
				if (start_time >= $end_select.val()) {
					$('option:visible:first', $end_select).attr('selected', true);
				}
			} else { // OFF
			
				$end_select.hide();
				$('span', $row).hide();
			}
		}).each(function () {
			var $row = $(this).parent(),
				$end_select = $('.bup_select_end', $row);
	
			$(this).data('default_value', $(this).val());
			$end_select.data('default_value', $end_select.val());
	
			// Hides end select for "OFF" days
			if (!$(this).val()) {
				$end_select.hide();
				$('span', $row).hide();
			}
		}).trigger('change');

	
	
	//this adds the user and loads the user's details	
	jQuery(document).on("click", "#ubp-edit-staff-service-btn", function(e) {
			
			e.preventDefault();
			
			
			var staff_id=   jQuery("#staff_id").val();
				
			jQuery("#bup-err-message" ).html( '' );		
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "ubp_add_staff_confirm", "staff_name": staff_name, "staff_email": staff_email , "staff_nick": staff_nick },
					
					success: function(data){
						
						
						var res = data;						
															
					     
						
						
						}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
		
	/* 	Delete Service */
	jQuery('.ubp-service-delete').on('click',function(e)
	{
		e.preventDefault();
		
		var doIt = false;
		
		doIt=confirm(wpuserspro_admin_v98.msg_service_delete);
		  
		  if(doIt)
		  {
			  
			  var service_id =  jQuery(this).attr("service-id");	
			 
			  jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "bup_delete_service", 
						"service_id": service_id  },
						
						success: function(data){
							
							wpuserspro_load_services();							
							
							
						}
					});
			
		  }
		  else{
			
		  }		
		
				
		return false;
	});
	
	/* 	Delete department */
	jQuery(document).on("click", ".easywpm-department-delete", function(e) {
		
		
			jQuery("#bup-spinner").show();
			  
			var department_id =  jQuery(this).attr("department-id");	
			 
			jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "wpuserspro_delete_department_form", 
						"department_id": department_id  },
						
						success: function(data){
							
							jQuery("#easywpm-department-delete-box" ).html( data);	 
							jQuery("#easywpm-department-delete-box" ).dialog( "open" );	
							jQuery("#bup-spinner").hide();						
							
							
						}
					});
			
		  	
	});
	
	/* 	Delete product */
	jQuery(document).on("click", ".easywpm-product-delete", function(e) {
		
		
			jQuery("#bup-spinner").show();
			  
			var product_id =  jQuery(this).attr("product-id");	
			 
			jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "wpuserspro_delete_product_form", 
						"product_id": product_id  },
						
						success: function(data){
							
							jQuery("#easywpm-delete-product-box" ).html( data);	 
							jQuery("#easywpm-delete-product-box" ).dialog( "open" );	
							jQuery("#bup-spinner").hide();						
							
							
						}
					});
			
		  	
	});
	
	/* 	Delete priority */
	jQuery(document).on("click", ".easywpm-priority-delete", function(e) {
		
		
			jQuery("#bup-spinner").show();
			  
			var priority_id =  jQuery(this).attr("priority-id");	
			 
			jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "wpuserspro_delete_priority_form", 
						"priority_id": priority_id  },
						
						success: function(data){
							
							jQuery("#easywpm-priority-delete-box" ).html( data);	 
							jQuery("#easywpm-priority-delete-box" ).dialog( "open" );	
							jQuery("#bup-spinner").hide();						
							
							
						}
					});
			
		  	
	});
	
	
	/* 	delete ticket reply */
	jQuery(document).on("click", ".easywpm-del-reply", function(e) {
		
		
			e.preventDefault();		
		
			jQuery("#bup-spinner").show();
			  
			var reply_id =  jQuery(this).attr("reply-id");
			
			jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "wpuserspro_delete_ticket_reply_confirm", 
						"reply_id": reply_id  },
						
						success: function(data){							
						
							jQuery("#bup-spinner").hide();								 
							jQuery("#easywpm-reply-unique-id-box-"+reply_id).hide();
							
						}
					});
			
		  	
	});
	
	
	
	/* 	Conf delete product */
	jQuery(document).on("click", "#easywpm-product-del-conf-btn", function(e) {
		
			e.preventDefault();
		
		
			jQuery("#bup-spinner").show();
			  
			var product_id =  jQuery(this).attr("product-id");
			
			jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "wpuserspro_delete_product_confirm", 
						"product_id": product_id  },
						
						success: function(data){							
								 
							jQuery("#easywpm-delete-product-box" ).dialog( "close" );	
							wpuserspro_load_sites();
							
						}
					});
			
		  	
	});
	
	
	/* 	Conf delete department */
	jQuery(document).on("click", "#easywpm-department-del-conf-btn", function(e) {
		
		
			jQuery("#bup-spinner").show();
			  
			var department_id =  jQuery(this).attr("department-id");
			var department_assign =  jQuery(this).attr("department-assign");
			var new_department_id =  jQuery('#ticket_department').val();
			
			if(new_department_id=='' && department_assign==1)	{alert(wpuserspro_admin_v98.set_new_priority);return;}
			//alert(new_priority_id);
			//return
			 
			jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "wpuserspro_delete_department_confirm", 
						"department_id": department_id, "new_department_id": new_department_id  },
						
						success: function(data){							
								 
							jQuery("#easywpm-department-delete-box" ).dialog( "close" );	
							wpuserspro_load_departments();
							
						}
					});
			
		  	
	});
	
	/* 	Conf delete priority */
	jQuery(document).on("click", "#easywpm-priority-del-conf-btn", function(e) {
		
		
			jQuery("#bup-spinner").show();
			  
			var priority_id =  jQuery(this).attr("priority-id");
			var priority_assign=  jQuery(this).attr("priority-assign");
			var new_priority_id =  jQuery('#ticket_priority').val();
			
			if(new_priority_id=='' && priority_assign=='1')	{alert(wpuserspro_admin_v98.set_new_priority);return;}
			//alert(new_priority_id);
			//return
			 
			jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "wpuserspro_delete_priority_confirm", 
						"priority_id": priority_id, "new_priority_id": new_priority_id  },
						
						success: function(data){							
								 
							jQuery("#easywpm-priority-delete-box" ).dialog( "close" );	
							wpuserspro_load_priorities();
							
						}
					});
			
		  	
	});
	
	
	/* 	Trash Ticket */
	jQuery(document).on("click", ".easywpm-trash-ticket", function(e) {

		e.preventDefault();
		
		var doIt = false;
		
		doIt=confirm(wpuserspro_admin_v98.msg_trash_ticket);
		  
		  if(doIt)
		  {
			  
			  var ticket_id =  jQuery(this).attr("ticket-id");	
			 
			  jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "wpuserspro_trash_ticket", 
						"ticket_id": ticket_id  },
						
						success: function(data){
							
							///wpuserspro_load_departments();	
							
								
							
							jQuery("#wpuserspro_ticket_row_id_"+ticket_id).hide();				
							
							
						}
					});
			
		  }
		  else{
			
		  }		
		
	});
	
	
	/* 	Delete department */
	jQuery(document).on("click", ".easywpm-department-delete-conf", function(e) {

		e.preventDefault();
		
		var doIt = false;
		
		doIt=confirm(wpuserspro_admin_v98.msg_department_delete);
		  
		  if(doIt)
		  {
			  
			  var department_id =  jQuery(this).attr("department-id");	
			 
			  jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "wpuserspro_delete_department", 
						"department_id": department_id  },
						
						success: function(data){
							
							wpuserspro_load_departments();							
							
							
						}
					});
			
		  }
		  else{
			
		  }		
		
	});
		
	
	/* 	Delete category */
	jQuery(document).on("click", ".easywpm-category-delete", function(e) {

		e.preventDefault();
		
		var doIt = false;
		
		doIt=confirm(wpuserspro_admin_v98.msg_cate_delete);
		  
		  if(doIt)
		  {
			  
			  var cate_id =  jQuery(this).attr("category-id");	
			 
			  jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "bup_delete_category", 
						"cate_id": cate_id  },
						
						success: function(data){
							
							wpuserspro_load_departments();							
							
							
						}
					});
			
		  }
		  else{
			
		  }		
		
	});
		
	function isInteger(x) {
        return x % 1 === 0;
    }
	
	
	jQuery(document).on("click", "#easywpm-add-staff-btn", function(e) {
			
			e.preventDefault();	
			
			jQuery("#bup-spinner").show();		
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_get_new_staff" },
					
					success: function(data){								
					
						jQuery("#easywpm-staff-editor-box" ).html( data );							
						jQuery("#easywpm-staff-editor-box" ).dialog( "open" );
						jQuery("#bup-spinner").hide();
							
						
						
						}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
		
	
	
	jQuery(document).on("click", ".ubp-break-delete-btn", function(e) {
			
			e.preventDefault();		
			
			var break_id =  jQuery(this).attr("break-id");
			var day_id =  jQuery(this).attr("day-id");
			var staff_id =  jQuery("#staff_id" ).val();	
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "bup_delete_break",
					"break_id": break_id,
					"staff_id": staff_id },
					
					success: function(data){
						
						bup_reload_staff_breaks (staff_id , day_id)							
						
						}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
		
	jQuery(document).on("click", "#easywpm-add-priority-btn", function(e) {
			
		
			var priority_id =  jQuery(this).attr("priority-id");
			
			jQuery('#easywpm-priority-add-priority-box').dialog('option', 'title', wpuserspro_admin_v98.msg_priority_add);			
			jQuery("#bup-spinner").show();
				
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_get_priority_add_form",  "priority_id": priority_id },
					
					success: function(data){		
					
					
						jQuery("#easywpm-priority-add-priority-box" ).html( data );							
						jQuery("#easywpm-priority-add-priority-box" ).dialog( "open" );
						jQuery('.color-picker').wpColorPicker();						
						jQuery("#bup-spinner").hide();	
						
						
						}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
		
	
	
	
	jQuery(document).on("click", "#easywpm-add-department-btn", function(e) {
			
			e.preventDefault();
			
			var department_id =  jQuery(this).attr("department-id");
			var site_id =  jQuery("#site_id" ).val();
			
			jQuery('#easywpm-department-add-department-box').dialog('option', 'title', wpuserspro_admin_v98.msg_department_add);			
			jQuery("#bup-spinner").show();
				
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_get_department_add_form",  "department_id": department_id ,  "site_id": site_id },
					
					success: function(data){		
					
					
						jQuery("#easywpm-department-add-department-box" ).html( data );							
						jQuery("#easywpm-department-add-department-box" ).dialog( "open" );
						jQuery('.color-picker').wpColorPicker();						
						jQuery("#bup-spinner").hide();	
						
						
						}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
		
		
	
	jQuery(document).on("click", ".easywpm-admin-edit-priority", function(e) {
			
		
			var priority_id =  jQuery(this).attr("priority-id");
			
			jQuery('#easywpm-priority-add-priority-box').dialog('option', 'title', wpuserspro_admin_v98.msg_priority_edit);			
						
		
			jQuery("#bup-spinner").show();
				
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_get_priority_add_form",  "priority_id": priority_id },
					
					success: function(data){					
					
						jQuery("#easywpm-priority-add-priority-box" ).html( data );							
						jQuery("#easywpm-priority-add-priority-box" ).dialog( "open" );
						jQuery('.color-picker').wpColorPicker();					
						jQuery("#bup-spinner").hide();	
						
						
						}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
	

	
	jQuery(document).on("click", ".easywpm-admin-edit-department", function(e) {
			
			e.preventDefault();
			
			var department_id =  jQuery(this).attr("department-id");
						
		
			jQuery("#bup-spinner").show();
				
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_get_department_add_form",  "department_id": department_id },
					
					success: function(data){					
					
						jQuery("#easywpm-department-edit-department-box" ).html( data );							
						jQuery("#easywpm-department-edit-department-box" ).dialog( "open" );
						jQuery('.color-picker').wpColorPicker();					
						jQuery("#bup-spinner").hide();	
						
						
						}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
		
		jQuery(document).on("click", ".easywpm-staff-load", function(e) {
			
			e.preventDefault();
			
			var staff_id =  jQuery(this).attr("staff-id");			
			wpuserspro_load_staff_member(staff_id);	
				
    		
    		e.preventDefault();
			 
				
        });
		
				
		jQuery(document).on("click", ".ubp-service-cate", function(e) {
			
			
			var ischecked = $(this).is(":checked");			
			var service_id = $(this).val();
			
			if(ischecked)
			{
				 $("#bup-price-"+service_id).prop("disabled",false);	
				 $("#bup-qty-"+service_id).prop("disabled",false);	
			
			}else{
				
				$("#bup-price-"+service_id).prop("disabled",true);	
				$("#bup-qty-"+service_id).prop("disabled",true);	
			}
			
		});
		
		jQuery(document).on("click", ".easywpm-service-cate-parent", function(e) {
			
			var ischecked = $(this).is(":checked");			
			var service_id = $(this).val();
			
			if(ischecked)
			{
				jQuery('.bup-service-cate-'+service_id).each(function () {
						  
					$(this).prop('checked',1);										
					$("#bup-price-"+$(this).val()).prop("disabled",false);	
					$("#bup-qty-"+$(this).val()).prop("disabled",false);
								
				 });	
			
			}else{
			
				jQuery('.bup-service-cate-'+service_id).each(function () {
						  
					$(this).prop('checked',0);										
					$("#bup-price-"+$(this).val()).prop("disabled",true);	
					$("#bup-qty-"+$(this).val()).prop("disabled",true);
								
				 });
			}
			
	});
	
	
		
		jQuery(document).on("click", "#easywpm-admin-edit-staff-service-save", function(e) {
			
			e.preventDefault();
			
			var staff_id =  jQuery('#staff_id').val();
			var service_list = ubp_get_checked_services();
			
			jQuery("#easywpm-loading-animation-services" ).html( message_wait_availability );	
				
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_update_staff_services",  "service_list": service_list,  "staff_id": staff_id },
					
					success: function(data){
						
						jQuery("#easywpm-loading-animation-services" ).html('');			
					
					
						
						}
				});
			
			
    		e.preventDefault();
			 
				
        });
		
		jQuery(document).on("click", "#bup-admin-edit-staff-location-save", function(e) {
			
			e.preventDefault();
			
			var staff_id =  jQuery('#staff_id').val();
			var location_list = ubp_get_checked_locations();
			
			jQuery("#bup-loading-animation-services" ).html( message_wait_availability );	
				
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "ubp_update_staff_locations",  "location_list": location_list,  "staff_id": staff_id },
					
					success: function(data){
						
						jQuery("#bup-loading-animation-services" ).html('');			
					
					
						
						}
				});
			
			
    		e.preventDefault();
			 
				
        });
		
		
		
		
		function ubp_get_checked_services ()	
		{
			
			var checkbox_value = "";
			jQuery(".ubp-cate-service-checked").each(function () {
				
				var ischecked = $(this).is(":checked");
			   
				if (ischecked) 
				{
					//get price and quantity
					var bup_price = jQuery("#bup-price-"+$(this).val()).val();
					var bup_qty = jQuery("#bup-qty-"+$(this).val()).val();
					checkbox_value += $(this).val() + "-" + bup_price + "-" + bup_qty + "|";
				}
				
				
			});
			
			return checkbox_value;		
		}
		
		
		
		function ubp_get_checked_locations ()	
		{
			
			var checkbox_value = "";
			jQuery(".ubp-location-checked").each(function () {
				
				var ischecked = $(this).is(":checked");
			   
				if (ischecked) 
				{
					
					checkbox_value += $(this).val()+ "|";
				}
				
				
			});
			
			return checkbox_value;		
		}
		
		
		
		/* 	FIELDS CUSTOMIZER -  restore default */
	jQuery('#bup-restore-fields-btn').on('click',function(e)
	{
		
		e.preventDefault();
		
		doIt=confirm(custom_fields_reset_confirmation);
		  
		  if(doIt)
		  {
			
			var uultra_custom_form = jQuery('#wpuserspro__custom_registration_form').val();
			  
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "custom_fields_reset", 
						"p_confirm": "yes"  , 		"bup_custom_form": uultra_custom_form },
						
						success: function(data){
							
							jQuery("#fields-mg-reset-conf").slideDown();			
						
							 window.location.reload();						
							
							
							}
					});
			
		  }
			
					
		return false;
	});
	
	
	
	
	/* 	WIDGETS CUSTOMIZER -  Close Open Widget */
	jQuery('.easywpm-widgets-icon-close-open, .easywpm-staff-details-header').on('click',function(e)
	{
		
		e.preventDefault();
		var widget_id =  jQuery(this).attr("widget-id");		
		var iconheight = 20;
		
		
		if(jQuery("#easywpm-widget-adm-cont-id-"+widget_id).is(":visible")) 
	  	{
			
			jQuery("#easywpm-widgets-icon-close-open-id-"+widget_id).css('background-position', '0px 0px');
			
			
			
		}else{
			
			jQuery("#easywpm-widgets-icon-close-open-id-"+widget_id).css('background-position', '0px -'+iconheight+'px');			
	 	 }
		
		
		jQuery("#easywpm-widget-adm-cont-id-"+widget_id).slideToggle();	
					
		return false;
	});
	
	/* 	FIELDS CUSTOMIZER -  ClosedEdit Field Form */
	jQuery('.uultra-btn-close-edition-field').on('click',function(e)
	{
		
		e.preventDefault();
		var block_id =  jQuery(this).attr("data-edition");		
		jQuery("#uu-edit-fields-bock-"+block_id).slideUp();				
		return false;
	});
	
	
	jQuery(document).on("click", "#easywpm-btn-app-confirm", function(e) {
			
			e.preventDefault();				
			var frm_validation  = $("#easywpm-registration-form").validationEngine('validate');	
			
			//check if user is a staff member trying to purchase an own service
			
			if(frm_validation)
			{
							
				
					//alert('other then submit');
					
					$("#easywpm-registration-form").submit();
				
				
				
				
			}else{
				
				
				
			}
			
			
									
    		e.preventDefault();		 
				
        });
	
			
	

	
});





function bup_reload_staff_breaks (staff_id , day_id)	
{
	
	jQuery.post(ajaxurl, {
							action: 'bup_get_current_staff_breaks',
							'staff_id': staff_id,
							'day_id': day_id
									
							}, function (response){									
																
							jQuery("#bup-break-adm-cont-id-"+day_id).html(response);
							
							//jQuery("#bup-spinner").hide();
							
		 });
}


function wpuserspro_load_sites ()	
	{
		jQuery("#bup-spinner").show();
		  jQuery.post(ajaxurl, {
							action: 'wpuserspro_display_sites'
									
							}, function (response){									
																
							jQuery("#easywpm-sites-list").html(response);							
							jQuery("#bup-spinner").hide();
							
		 });
}

function wpuserspro_load_priorities ()	
	{
		jQuery("#bup-spinner").show();
		  jQuery.post(ajaxurl, {
							action: 'wpuserspro_display_priorities'
									
							}, function (response){									
																
							jQuery("#easywpm-priorities-list").html(response);							
							jQuery("#bup-spinner").hide();
							
		 });
}


function wpuserspro_load_departments ()	
	{
		jQuery("#bup-spinner").show();
		  jQuery.post(ajaxurl, {
							action: 'wpuserspro_display_departments'
									
							}, function (response){									
																
							jQuery("#easywpm-departments-list").html(response);							
							jQuery("#bup-spinner").hide();
							
		 });
}

function wpuserspro_load_services (department_id)	
{
		jQuery("#bup-spinner").show();
		
		jQuery.post(ajaxurl, {
							action: 'wpuserspro_display_admin_categories',
							'department_id': department_id
									
							}, function (response){									
																
							jQuery("#bup-services-list").html(response);							
							jQuery("#bup-spinner").hide();
							
		 });
}


function wpuserspro_load_staff_member (staff_id)	
	{
		jQuery("#bup-spinner").show();
		  jQuery.post(ajaxurl, {
							action: 'wpuserspro_get_staff_details_ajax', 'staff_id': staff_id
									
							}, function (response){									
																
							jQuery("#easywpm-staff-details" ).html( response );	
														
							jQuery("#bup-spinner").hide();
							
		 });
}




function get_disabled_modules_list ()	
{
	
	var checkbox_value = "";
    jQuery(".uultra-my-modules-checked").each(function () {
		
        var ischecked = $(this).is(":checked");
       
	    if (ischecked) 
		{
            checkbox_value += $(this).val() + "|";
        }
		
		
    });
	
	return checkbox_value;		
}

function sortable_user_menu()
{
	 var itemList = jQuery('#uultra-user-menu-option-list');
	 
	 itemList.sortable({
		  cursor: 'move',
          update: function(event, ui) {
           // $('#loading-animation').show(); // Show the animate loading gif while waiting

            opts = {
                url: ajaxurl, // ajaxurl is defined by WordPress and points to /wp-admin/admin-ajax.php
                type: 'POST',
                async: true,
                cache: false,
                dataType: 'json',
                data:{
                    action: 'uultra_sort_user_menu_ajax', // Tell WordPress how to handle this ajax request
                    order: itemList.sortable('toArray').toString() // Passes ID's of list items in  1,3,2 format
                },
                success: function(response) {
                   // $('#loading-animation').hide(); // Hide the loading animation
				   uultra_reload_user_menu_customizer();
				  				   
                    return; 
                },
                error: function(xhr,textStatus,e) {  // This can be expanded to provide more information
                    alert(e);
                    // alert('There was an error saving the updates');
                  //  $('#loading-animation').hide(); // Hide the loading animation
                    return; 
                }
            };
            jQuery.ajax(opts);
        }
    }); 
	
}

function wpuserspro_reload_custom_fields_set ()	
{
	
	jQuery("#bup-spinner").show();
	
	 var custom_form =  jQuery('#wpuserspro__custom_registration_form').val();
		
		jQuery.post(ajaxurl, {
							action: 'wpuserspro_reload_custom_fields_set', 'custom_form': custom_form
									
							}, function (response){									
																
							jQuery("#uu-fields-sortable").html(response);							
							sortable_fields_list();
							
							jQuery("#bup-spinner").hide();
							
																
														
		 });
		
}
function sortable_fields_list ()
{
	var itemList = jQuery('#uu-fields-sortable');	 
	var wpuserspro_custom_form =  jQuery('#wpuserspro__custom_registration_form').val();
   
    itemList.sortable({
		cursor: 'move',
        update: function(event, ui) {
        jQuery("#easywpm-spinner").show(); // Show the animate loading gif while waiting

            opts = {
                url: ajaxurl, // ajaxurl is defined by WordPress and points to /wp-admin/admin-ajax.php
                type: 'POST',
                async: true,
                cache: false,
                dataType: 'json',
                data:{
                    action: 'wpuserspro_sort_fileds_list', // Tell WordPress how to handle this ajax request
					'wpuserspro_custom_form': wpuserspro_custom_form, // Tell WordPress how to handle this ajax request
                    order: itemList.sortable('toArray').toString() // Passes ID's of list items in  1,3,2 format
                },
                success: function(response) {
                   // $('#loading-animation').hide(); // Hide the loading animation
				   wpuserspro_reload_custom_fields_set();
                    return; 
                },
                error: function(xhr,textStatus,e) {  // This can be expanded to provide more information
                    alert(e);
                    // alert('There was an error saving the updates');
                  //  $('#loading-animation').hide(); // Hide the loading animation
                    return; 
                }
            };
            jQuery.ajax(opts);
        }
    }); 
	
	
}




function bup_load_staff_under_category(appointment_id)	
{
	
	var b_category=   jQuery("#bup-category").val();
							
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "get_cate_dw_admin_ajax", "b_category": b_category, "appointment_id": appointment_id},
					
					success: function(data){						
						
						var res = data;						
						jQuery("#bup-staff-booking-list").html(res);					    
						

						}
				});	
	
}

function wpuserspro_load_staff_adm(staff_id )	
{

	setTimeout("wpuserspro_load_staff_list_adm()", 1000);
	setTimeout("wpuserspro_load_staff_details(" + staff_id +")", 1000);
	
}

function wpuserspro_load_staff_list_adm()	
{
	jQuery("#bup-spinner").show();
	
    jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_get_staff_list_admin_ajax"},
					
					success: function(data){					
						
						var res = data;						
						jQuery("#easywpm-staff-list").html(res);
						jQuery("#bup-spinner").hide();					    
						
												

						}
				});	
	
}

function wpuserspro_load_staff_details(staff_id)	
{
	jQuery("#bup-spinner").show();	
    jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "wpuserspro_get_staff_details_admin", "staff_id": staff_id},
					
					success: function(data){					
						
						var res = data;						
						jQuery("#easywpm-staff-details").html(res);					
						jQuery( "#tabs-bupro" ).tabs({collapsible: false	});						
						jQuery("#bup-spinner").hide();	
										    
						

						}
				});	
	
}

function wpuserspro_load_private_credentials (ticket_id)	
{
	
	jQuery.post(ajaxurl, {
							action: 'wpuserspro_load_private_credentials','ticket_id': ticket_id
							
									
							}, function (response){									
																
							jQuery("#easywpm-private-credentials-list").html(response);
							
							//jQuery("#bup-spinner").hide();
							
		 });
}

function wpuserspro_load_private_notes (ticket_id)	
{
	
	jQuery("#bup-spinner").show();
	
	jQuery.post(ajaxurl, {
							action: 'wpuserspro_load_private_notes','ticket_id': ticket_id
							
									
							}, function (response){									
																
							jQuery("#easywpm-private-notes-list").html(response);
							
							jQuery("#bup-spinner").hide();
							
		 });
}




function wpuserspro_set_auto_c()
{
	  $("#easywpmclientsel").autocomplete({
		  
	  
	  source: function( request, response ) {
			  $.ajax({
				  url: ajaxurl,
				  dataType: "json",
				  data: {
					  action: 'wpuserspro_autocomple_clients_tesearch',
					  term: this.term
				  },
				  
				  success: function( data ) {
					  
					  response( $.map( data.results, function( item ) {
					  return {
						  id: item.id,
						  label: item.label,
						  value: item.label
					  }
					   }));
					   
					   
					  
				  },
				  
				  error: function(jqXHR, textStatus, errorThrown) 
				  {
					  console.log(jqXHR, textStatus, errorThrown);
				  }
				  
			  });
		  },
	  
		  minLength: 2,			
		  
		  // optional (if other layers overlap autocomplete list)
		  open: function(event, ui) {
			  
			  var dialog = $(this).closest('.ui-dialog');
			  if(dialog.length > 0){
				  $('.ui-autocomplete.ui-front').zIndex(dialog.zIndex()+1);
			  }
		  },
		  
		  select: function( event, ui ) {
			  
			  ui.item.ur;			  
			  jQuery( "#wpuserspro_client_id" ).val(ui.item.id);
				  
		  }
	  
	  });
  
}

function wpuserspro_set_auto_staff()
{
	  $("#easywpmstaffsel").autocomplete({
		  
	  
	  source: function( request, response ) {
			  $.ajax({
				  url: ajaxurl,
				  dataType: "json",
				  data: {
					  action: 'wpuserspro_autocomple_clients_tesearch',
					  'type': 'staff',
					  term: this.term
				  },
				  
				  success: function( data ) {
					  
					  response( $.map( data.results, function( item ) {
					  return {
						  id: item.id,
						  label: item.label,
						  value: item.label
					  }
					   }));
					   
					   
					  
				  },
				  
				  error: function(jqXHR, textStatus, errorThrown) 
				  {
					  console.log(jqXHR, textStatus, errorThrown);
				  }
				  
			  });
		  },
	  
		  minLength: 2,			
		  
		  // optional (if other layers overlap autocomplete list)
		  open: function(event, ui) {
			  
			  var dialog = $(this).closest('.ui-dialog');
			  if(dialog.length > 0){
				  $('.ui-autocomplete.ui-front').zIndex(dialog.zIndex()+1);
			  }
		  },
		  
		  select: function( event, ui ) {
			  
			  ui.item.ur;			  
			  jQuery( "#wpuserspro_staff_id" ).val(ui.item.id);
				  
		  }
	  
	  });
  
}


function hidde_noti (div_d)
{
		jQuery("#"+div_d).slideUp();		
		
}
