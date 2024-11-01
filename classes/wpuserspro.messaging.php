<?php
class WPUsersProMessaging extends WPUsersProCommon 
{
	var $mHeader;
	var $mEmailPlainHTML;
	var $mHeaderSentFromName;
	var $mHeaderSentFromEmail;
	var $mCompanyName;
	
	var $include_ticket_subject;
	var $include_ticket_number;
	

	function __construct() 
	{
		$this->setContentType();
		$this->setFromEmails();				
		$this->set_headers();	
		
	}
	
	function setFromEmails() 
	{
		global $easywpm;
			
		$from_name =  $this->get_option('messaging_send_from_name'); 
		$from_email = $this->get_option('messaging_send_from_email'); 	
		if ($from_email=="")
		{
			$from_email =get_option('admin_email');
			
		}		
		$this->mHeaderSentFromName=$from_name;
		$this->mHeaderSentFromEmail=$from_email;
		
		
    }
	
	function setContentType() 
	{
		global $wpuserspro;			
				
		$this->mEmailPlainHTML="text/html";
    }
	
	/* get setting */
	function get_option($option) 
	{
		$settings = get_option('wpuserspro_options');
		if (isset($settings[$option])) 
		{
			return $settings[$option];
			
		}else{
			
		    return '';
		}
		    
	}
	
	public function set_headers() 
	{   			
		//Make Headers aminnistrators	
		$headers[] = "Content-type: ".$this->mEmailPlainHTML."; charset=UTF-8";
		$headers[] = "From: ".$this->mHeaderSentFromName." <".$this->mHeaderSentFromEmail.">";
		$headers[] = "Organization: ".$this->mCompanyName;	
		$this->mHeader = $headers;		
    }
	
	
	public function  send ($to, $subject, $message)
	{
		global $wpuserspro , $phpmailer;
		
		$message = nl2br($message);
		//check mailing method	
		$bup_emailer = $wpuserspro->get_option('bup_smtp_mailing_mailer');
		
		if($bup_emailer=='mail' || $bup_emailer=='' ) //use the defaul email function
		{
			$err = wp_mail( $to , $subject, $message, $this->mHeader);
			
			//echo $err. 'message: '.$message;
		
		}elseif($bup_emailer=='mandrill' && is_email($to)){ //send email via Mandrill
		
			$this->send_mandrill( $to , $recipient_name, $subject, $message);
		
		}elseif($bup_emailer=='third-party' && is_email($to)){ //send email via Third-Party
		
			if (function_exists('wpuserspro_third_party_email_sender')) 
			{
				
				wpuserspro_third_party_email_sender($to , $subject, $message);				
				
			}
			
		}elseif($bup_emailer=='smtp' &&  is_email($to)){ //send email via SMTP
		
			// Make sure the PHPMailer class has been instantiated 
			// (copied verbatim from wp-includes/pluggable.php)
			// (Re)create it, if it's gone missing
			if ( !is_object( $phpmailer ) || !is_a( $phpmailer, 'PHPMailer' ) ) {
				//require_once ABSPATH . WPINC . '/class-phpmailer.php';
                require_once ABSPATH . WPINC . '/PHPMailer/PHPMailer.php';
				require_once ABSPATH . WPINC . '/class-smtp.php';
				$phpmailer = new PHPMailer( true );
			}
			
			
			$phpmailer->IsSMTP(); // use SMTP
			
			
			// Empty out the values that may be set
			$phpmailer->ClearAddresses();
			$phpmailer->ClearAllRecipients();
			$phpmailer->ClearAttachments();
			$phpmailer->ClearBCCs();			
			
			// Set the mailer type as per config above, this overrides the already called isMail method
			$phpmailer->Mailer = $bup_emailer;
						
			$phpmailer->From     = $wpuserspro->get_option('messaging_send_from_email');
			$phpmailer->FromName =  $wpuserspro->get_option('messaging_send_from_name');
			
			//Set the subject line
			$phpmailer->Subject = $subject;			
			$phpmailer->CharSet     = 'UTF-8';
			
			//Set who the message is to be sent from
			//$phpmailer->SetFrom($phpmailer->FromName, $phpmailer->From);
			
			//Read an HTML message body from an external file, convert referenced images to embedded, convert HTML into a basic plain-text alternative body
			
			
			// Set the Sender (return-path) if required
			if ($wpuserspro->get_option('bup_smtp_mailing_return_path')=='1')
				$phpmailer->Sender = $phpmailer->From; 
			
			// Set the SMTPSecure value, if set to none, leave this blank
			$uultra_encryption = $wpuserspro->get_option('bup_smtp_mailing_encrytion');
			$phpmailer->SMTPSecure = $uultra_encryption == 'none' ? '' : $uultra_encryption;
			
			// If we're sending via SMTP, set the host
			if ($bup_emailer == "smtp")
			{				
				// Set the SMTPSecure value, if set to none, leave this blank
				$phpmailer->SMTPSecure = $uultra_encryption == 'none' ? '' : $uultra_encryption;
				
				// Set the other options
				$phpmailer->Host = $wpuserspro->get_option('bup_smtp_mailing_host');
				$phpmailer->Port = $wpuserspro->get_option('bup_smtp_mailing_port');
				
				// If we're using smtp auth, set the username & password
				if ($wpuserspro->get_option('bup_smtp_mailing_authentication') == "true") 
				{
					$phpmailer->SMTPAuth = TRUE;
					$phpmailer->Username = $wpuserspro->get_option('bup_smtp_mailing_username');
					$phpmailer->Password = $wpuserspro->get_option('bup_smtp_mailing_password');
				}
				
			}
			
			//html plain text			
			$phpmailer->IsHTML(true);	
			$phpmailer->MsgHTML($message);	
			
			//Set who the message is to be sent to
			$phpmailer->AddAddress($to);
			
			//$phpmailer->SMTPDebug = 2;	
			
			//Send the message, check for errors
			if(!$phpmailer->Send()) {
			  echo "Mailer Error: " . $phpmailer->ErrorInfo;
			  exit();
			} else {
			//  echo "Message sent!";
			  
			 
			}
			
		
			//exit;

		
		}
		
		
		
	}
	
	public function  send_mandrill ($to, $recipient_name, $subject, $message_html)
	{
		global $wpuserspro , $phpmailer;
		require_once(wpuserspro_path."libs/mandrill/Mandrill.php");
		
		$from_email     = $wpuserspro->get_option('messaging_send_from_email');
		$from_name =  $wpuserspro->get_option('messaging_send_from_name');
		$api_key =  $wpuserspro->get_option('bup_mandrill_api_key');
		
					
		$text_html =  $message_html;
		$text_txt =  "";
			
		
		try {
				$mandrill = new Mandrill($api_key);
				$message = array(
					'html' => $text_html,
					'text' => $text_txt,
					'subject' => $subject,
					'from_email' => $from_email,
					'from_name' => $from_name,
					'to' => array(
						array(
							'email' => $to,
							'name' => $recipient_name,
							'type' => 'to'
						)
					),
					'headers' => array('Reply-To' => $from_email, 'Content-type' => $this->mEmailPlainHTML),
					'important' => false,
					'track_opens' => null,
					'track_clicks' => null,
					'auto_text' => null,
					'auto_html' => null,
					'inline_css' => null,
					'url_strip_qs' => null,
					'preserve_recipients' => null,
					'view_content_link' => null,
					/*'bcc_address' => 'message.bcc_address@example.com',*/
					'tracking_domain' => null,
					'signing_domain' => null,
					'return_path_domain' => null
					/*'merge' => true,
					'global_merge_vars' => array(
						array(
							'name' => 'merge1',
							'content' => 'merge1 content'
						)
					),
					
					
					/*'google_analytics_domains' => array('example.com'),
					'google_analytics_campaign' => 'message.from_email@example.com',
					'metadata' => array('website' => 'www.example.com'),*/
					
				);
				$async = false;
				$ip_pool = 'Main Pool';
				$send_at = date("Y-m-d H:i:s");
				//$result = $mandrill->messages->send($message, $async, $ip_pool, $send_at);
				$result = $mandrill->messages->send($message, $async);
				//print_r($result);
				
			} catch(Mandrill_Error $e) {
				// Mandrill errors are thrown as exceptions
				echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
				// A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
				throw $e;
			}
	}
	
	//--- Parse Custom Fields
	public function  parse_custom_fields($content, $user )
	{
		global $wpuserspro, $wptucomplement;
		
		if(isset($wptucomplement))
		{
			
			preg_match_all("/\[([^\]]*)\]/", $content, $matches);
			$results = $matches[1];			
			$custom_fields_col = array();
			
			foreach ($results as $field){
				
				//clean field
				$clean_field = str_replace("wpuserspro_CUSTOM_", "", $field);
				$custom_fields_col[] = $clean_field;
			
			}
			
			foreach ($custom_fields_col as $field)
			{
				//get field data from booking table				
				$field_data = $wpuserspro->get_user_meta($usr->ID, $field);
				//replace data in template				
				$content = str_replace("[EASY_WPM_CUSTOM_".$field."]", $field_data, $content);				
			}
			
		}
		
		return $content;
		
	}
	
	
	//--- Reset Link	
	public function  send_reset_link($receiver, $link)
	{
		global $wpuserspro;
		
		$admin_email =get_option('admin_email'); 
		$company_name = $this->get_option('company_name');
		$company_phone = $this->get_option('company_phone');
		
		$u_email = $receiver->user_email;
		
		$template_client =stripslashes($this->get_option('email_reset_link_message_body'));
		$subject = $this->get_option('email_reset_link_message_subject');
		
		$template_client = str_replace("{{wpuserspro_staff_name}}", $receiver->display_name,  $template_client);				
		$template_client = str_replace("{{wpuserspro_reset_link}}", $link,  $template_client);
		
		$template_client = str_replace("{{wpuserspro_company_name}}", $company_name,  $template_client);
		$template_client = str_replace("{{wpuserspro_company_phone}}", $company_phone,  $template_client);
		$template_client = str_replace("{{wpuserspro_company_url}}", $site_url,  $template_client);	
		
		$this->send($u_email, $subject, $template_client);				
		
	}
	
	//--- Send Client Renewal Notice to the client
	public function  send_client_renewal_notice($receiver, $package, $subscription)
	{
		global $wpuserspro;
		
		$admin_email =get_option('admin_email'); 
		$company_name = $this->get_option('company_name');
		$company_phone = $this->get_option('company_phone');
		
		$u_email = $receiver->user_email;
		
		$template_c =stripslashes($this->get_option('email_package_renewal_body'));
		$subject = $this->get_option('email_package_renewal_subject');
		
		/*Update Expiration Dates*/							
		$valid_periods = array();
		$valid_periods  = $wpuserspro->membership->get_periods($package);
		$new_period = 	 $valid_periods['starts'].'/'. $valid_periods['ends'];
		
		if( $package->membership_type=='recurring'){			
			$amount =  $wpuserspro->get_formated_amount_with_currency($package->membership_subscription_amount);
	
	    }else{			
			$amount =  $wpuserspro->get_formated_amount_with_currency($package->membership_initial_amount);
		}
		
		$template_c = str_replace("{{wpuserspro_client_name}}", $receiver->display_name,  $template_c);
		$template_c = str_replace("{{wpuserspro_subscription_name}}", $package->membership_name,  $template_c);	
		$template_c = str_replace("{{wpuserspro_subscription_amount}}",$amount,  $template_c);			
		$template_c = str_replace("{{wpuserspro_period}}", $new_period,  $template_c);
		
		$template_c = str_replace("{{wpuserspro_company_name}}", $company_name,  $template_c);
		$template_c = str_replace("{{wpuserspro_company_phone}}", $company_phone,  $template_c);
		$template_c = str_replace("{{wpuserspro_company_url}}", $site_url,  $template_c);
		
		$template_c = $this->parse_custom_fields($template_c,$receiver);			
		
		$this->send($u_email, $subject, $template_c);				
		
	}
	
	//--- Send Admin Renewal Notice to the client
	public function  send_admin_renewal_notice($receiver, $package, $subscription)
	{
		global $wpuserspro;
		
		$admin_email =get_option('admin_email'); 
		$company_name = $this->get_option('company_name');
		$company_phone = $this->get_option('company_phone');
		
		$u_email = $receiver->user_email;
		
		$template_c =stripslashes($this->get_option('email_package_renewal_admin_body'));
		$subject = $this->get_option('email_package_renewal_admin_subject');
		
		/*Update Expiration Dates*/							
		$valid_periods = array();
		$valid_periods  = $wpuserspro->membership->get_periods($package);
		$new_period = 	 $valid_periods['starts'].'/'. $valid_periods['ends'];
		
		if( $package->membership_type=='recurring'){			
			$amount =  $wpuserspro->get_formated_amount_with_currency($package->membership_subscription_amount);
	
	    }else{			
			$amount =  $wpuserspro->get_formated_amount_with_currency($package->membership_initial_amount);
		}
		
		$template_c = str_replace("{{wpuserspro_client_name}}", $receiver->display_name,  $template_c);
		$template_c = str_replace("{{wpuserspro_subscription_name}}", $package->membership_name,  $template_c);	
		$template_c = str_replace("{{wpuserspro_subscription_amount}}",$amount,  $template_c);	
		$template_c = str_replace("{{wpuserspro_subscription_id}}",$subscription->subscription_id,  $template_c);
		$template_c = str_replace("{{wpuserspro_subscription_profile_id}}",$subscription->subscription_merchant_id ,  $template_c);		
		$template_c = str_replace("{{wpuserspro_period}}", $new_period,  $template_c);
		
		$template_c = str_replace("{{wpuserspro_company_name}}", $company_name,  $template_c);
		$template_c = str_replace("{{wpuserspro_company_phone}}", $company_phone,  $template_c);
		$template_c = str_replace("{{wpuserspro_company_url}}", $site_url,  $template_c);
		
		$template_c = $this->parse_custom_fields($template_c,$receiver);			
		
		$this->send($admin_email, $subject, $template_c);				
		
	}
	
	
	//--- Send Client Purchase Notice to the client
	public function  send_client_purchase_notice($receiver, $package, $subscription)
	{
		global $wpuserspro;
		
		$admin_email =get_option('admin_email'); 
		$company_name = $this->get_option('company_name');
		$company_phone = $this->get_option('company_phone');
		
		$u_email = $receiver->user_email;
		
		$template_c =stripslashes($this->get_option('email_package_upgrade_body'));
		$subject = $this->get_option('email_package_upgrade_subject');
		
		/*Update Expiration Dates*/							
		$valid_periods = array();
		$valid_periods  = $wpuserspro->membership->get_periods($package);
		$new_period = 	 $valid_periods['starts'].'/'.$valid_periods['ends'];
		
		if( $package->membership_type=='recurring'){			
			$amount =  $wpuserspro->get_formated_amount_with_currency($package->membership_subscription_amount);
	
	    }else{			
			$amount =  $wpuserspro->get_formated_amount_with_currency($package->membership_initial_amount);
		}
		
		//get payment formated
		$formated_agreement =  $wpuserspro->get_formated_agreement($package);
		
		$template_c = str_replace("{{wpuserspro_client_name}}", $receiver->display_name,  $template_c);
		$template_c = str_replace("{{wpuserspro_subscription_name}}", $package->membership_name,  $template_c);	
		$template_c = str_replace("{{wpuserspro_subscription_amount}}",$amount,  $template_c);			
		$template_c = str_replace("{{wpuserspro_subscription_id}}",$subscription->subscription_id,  $template_c);
		$template_c = str_replace("{{wpuserspro_period}}", $new_period,  $template_c);
		$template_c = str_replace("{{wpuserspro_subscription_agreement}}", $new_period,  $template_c);
		
		$template_c = str_replace("{{wpuserspro_company_name}}", $company_name,  $template_c);
		$template_c = str_replace("{{wpuserspro_company_phone}}", $company_phone,  $template_c);
		$template_c = str_replace("{{wpuserspro_company_url}}", $site_url,  $template_c);
		
		$template_c = $this->parse_custom_fields($template_c,$receiver);			
		
		$this->send($u_email, $subject, $template_c);				
		
	}
	
	//--- Send Admin Purchase Notice
	public function  send_admin_purchase_notice($receiver, $package, $subscription)
	{
		global $wpuserspro;
		
		$admin_email =get_option('admin_email'); 
		$company_name = $this->get_option('company_name');
		$company_phone = $this->get_option('company_phone');
		
		$u_email = $receiver->user_email;
		
		$template_c =stripslashes($this->get_option('email_package_upgrade_admin_body'));
		$subject = $this->get_option('email_package_upgrade_admin_subject');
		
		/*Update Expiration Dates*/							
		$valid_periods = array();
		$valid_periods  = $wpuserspro->membership->get_periods($package);
		$new_period = 	 $valid_periods['starts'].'/'. $valid_periods['ends'];
		
		if( $package->membership_type=='recurring'){			
			$amount =  $wpuserspro->get_formated_amount_with_currency($package->membership_subscription_amount);
	
	    }else{			
			$amount =  $wpuserspro->get_formated_amount_with_currency($package->membership_initial_amount);
		}
		
		//get payment formated
		$formated_agreement =  $wpuserspro->get_formated_agreement($package);
		
		$template_c = str_replace("{{wpuserspro_client_name}}", $receiver->display_name,  $template_c);
		$template_c = str_replace("{{wpuserspro_subscription_name}}", $package->membership_name,  $template_c);	
		$template_c = str_replace("{{wpuserspro_subscription_amount}}",$amount,  $template_c);			
		$template_c = str_replace("{{wpuserspro_period}}", $new_period,  $template_c);
		$template_c = str_replace("{{wpuserspro_subscription_id}}",$subscription->subscription_id,  $template_c);
		$template_c = str_replace("{{wpuserspro_subscription_agreement}}", $formated_agreement,  $template_c);
		$template_c = str_replace("{{wpuserspro_company_name}}", $company_name,  $template_c);
		$template_c = str_replace("{{wpuserspro_company_phone}}", $company_phone,  $template_c);
		$template_c = str_replace("{{wpuserspro_company_url}}", $site_url,  $template_c);
		
		$template_c = $this->parse_custom_fields($template_c,$receiver);			
		
		$this->send($admin_email, $subject, $template_c);				
		
	}

	
	//--- Registration Link
	public function  send_client_registration_link($receiver, $link, $password)
	{
		global $wpuserspro;
		
		$admin_email =get_option('admin_email'); 
		$company_name = $this->get_option('company_name');
		$company_phone = $this->get_option('company_phone');
		
		$u_email = $receiver->user_email;
		
		$template_client =stripslashes($this->get_option('email_registration_body'));
		$subject = $this->get_option('email_registration_subject');
		
		$template_client = str_replace("{{wpuserspro_client_name}}", $receiver->display_name,  $template_client);
		$template_client = str_replace("{{wpuserspro_user_name}}", $receiver->user_login,  $template_client);	
		$template_client = str_replace("{{wpuserspro_user_password}}", $password,  $template_client);			
		$template_client = str_replace("{{wpuserspro_login_link}}", $link,  $template_client);
		
		$template_client = str_replace("{{wpuserspro_company_name}}", $company_name,  $template_client);
		$template_client = str_replace("{{wpuserspro_company_phone}}", $company_phone,  $template_client);
		$template_client = str_replace("{{wpuserspro_company_url}}", $site_url,  $template_client);
		
		$template_client = $this->parse_custom_fields($template_client,$receiver);			
		
		$this->send($u_email, $subject, $template_client);				
		
	}
	
	
	//--- New Password Backend
	public function  send_new_password_to_user($staff, $password1)
	{
		global $wpuserspro;
				
		$admin_email =get_option('admin_email'); 
		$company_name = $this->get_option('company_name');
		$company_phone = $this->get_option('company_phone');
		
		//get templates	
		$template_client =stripslashes($this->get_option('email_password_change_member_body'));
		
		$site_url =site_url("/");
	
		$subject_client = $this->get_option('email_password_change_member_subject');				
		//client		
		$template_client = str_replace("{{wpuserspro_user_name}}", $staff->display_name,  $template_client);	
		$template_client = str_replace("{{wpuserspro_company_name}}", $company_name,  $template_client);
		$template_client = str_replace("{{wpuserspro_company_phone}}", $company_phone,  $template_client);
		$template_client = str_replace("{{wpuserspro_company_url}}", $site_url,  $template_client);										
		//send to client
		$this->send($staff->user_email, $subject_client, $template_client);		
		
	}
	
	
	
	
	
	
	public function  paypal_ipn_debug( $message)
	{
		global $wpuserspro;
		$admin_email =get_option('admin_email');		
		$this->send($admin_email, "IPN notification", $message);					
		
	}
	
	public function  custom_email_message( $message, $subject)
	{
		global $wpuserspro;
		$admin_email =get_option('admin_email');		
		$this->send($admin_email,  $subject, $message);					
		
	}
	
		

}

$key = "messaging";
$this->{$key} = new WPUsersProMessaging();
