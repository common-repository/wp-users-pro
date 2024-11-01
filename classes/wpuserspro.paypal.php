<?php
class WPUsersProPaypal 
{
	

	function __construct() 
	{
		//status   0 - pending
		//         1 - active
		//         2 - cancelled
		//         3 - recurring payment failed
		//         4 - recurring agreement expired
		
		add_action( 'init', array($this, 'handle_init' ) );		
			
	}
	
	public function handle_init()
	{
		if (isset($_POST['txn_id']) && $_GET['easywpmipncall']=='') 
		{			
			$this->handle_paypal_ipn($_POST);		
		}
		
		if (isset($_GET['easywpmipncall'])) 
		{
			$this->handle_paypal_ipn_sb($_POST);		
		}		
	}
	
	
	/*handle ipn responses*/
	public function handle_paypal_ipn_sb($paypal_response)
	{
	   	global $wpdb,  $wpuserspro;
		
		$req = 'cmd=_notify-validate';

		// Read the post from PayPal system and add 'cmd'
		$fullipnA = array();
		foreach ($_POST as $key => $value)
		{
            $value = sanitize_text_field($value);
			$fullipnA[$key] = $value;
		
			$encodedvalue = urlencode(stripslashes($value));
			$req .= "&$key=$encodedvalue";
		}
		
		$fullipn =$this->Array2Str(" : ", "\n", $fullipnA);
		
		$txn_type =  sanitize_text_field($_POST['txn_type']);
		$subscr_id =  sanitize_text_field($_POST['subscr_id']);
		$amount_subscription =  sanitize_text_field($_POST['amount3']);
		$payment_amount =  sanitize_text_field($_POST['mc_gross']);
		$payment_currency =  sanitize_text_field($_POST['mc_currency']);
		$txn_id =  sanitize_text_field($_POST['txn_id']);

		
		$custom = explode("|",  sanitize_text_field($_POST['custom']));		
		$type = $custom[0];
		$custom_key = $custom[1];
		
		if($this->check_ipn()) {
						
			//get subscription			
			$subscription = $wpuserspro->order->get_subscription_merchant($subscr_id);				
			$subscription_id=  $subscription->subscription_id;			
			$user_id =  $subscription->subscription_user_id;
			
			$package = $wpuserspro->membership->get_one($subscription->subscription_package_id );
			
			$site_date = date( 'Y-m-d', current_time( 'timestamp', 0 ) );			
			
			if($txn_type=="subscr_signup" ){ //profile created sign up			
			
					//$wpuserspro->messaging->paypal_ipn_debug("Sign UP IPN : ".$fullipn);
					
					// Get Subscription
					$subscription = $wpuserspro->order->get_subscription_with_key($custom_key);
					
					if ($subscription->subscription_id=="")    
					{
						$errors .= " --- Subscription Key Not VAlid: " .$custom_key;
						
					}				
										
					$subscription_id = 	$subscription->subscription_id;				
					$user_id = $subscription->subscription_user_id;					
					
					/*Update Subscription With Merchant Subscription ID*/						
					$wpuserspro->order->update_subscription_merchant_id($subscription_id,$subscr_id);
					
					if ($type=="ini"){															
						
						/*Send login details to the client*/				
						$user_pass = wp_generate_password( 8, false);
						wp_set_password( $user_pass, $user_id );
						
						$login_link_id = $wpuserspro->get_option('user_login_page');				
						$login_link = get_page_link($login_link_id);		
									
						$user = get_user_by( 'id', $user_id );
						$wpuserspro->messaging->send_client_registration_link($user, $login_link, $user_pass);
				
					}elseif($type=="upgrade"){
					

					} //end if type
			
				
			}elseif($txn_type=="subscr_cancel"){
				
				$wpuserspro->messaging->paypal_ipn_debug("CANCELLED IPN : ".$fullipn);
				
				/*Update Subscription*/						
				$wpuserspro->order->update_subscription_status($subscription_id,2);
				$wpuserspro->order->update_subscription_cancelation_date($subscription_id,$site_date);
				
				/*Notify Admin*/
				
				/*Notify Client*/
				
			
			}elseif($txn_type=="subscr_eot"){
				
				// Recurring payment agreement expired		
				$errors .= " --- Payment Expired";	
				
				
				/*Update Subscription*/						
				$wpuserspro->order->update_subscription_status($subscription_id,4);
				$wpuserspro->order->update_subscription_cancelation_date($subscription_id,$site_date);
				
				/*Notify Admin*/
				
				/*Notify Client*/
				
				
			
			}elseif($txn_type=="subscr_failed"){
				
				// Subscription payment failed			
				$errors .= " --- Payment Expired";	
							
			
			}elseif($txn_type=="recurring_payment_failed"){
				
				//Recurring payment failed				
				$errors .= " --- Recurring Payment Failed";
				
				$wpuserspro->order->update_subscription_status($subscription_id,3);
				
			
			}elseif($txn_type=="recurring_payment"){
				
				//Recurring payment received				
								
				//update status				
				$wpuserspro->order->update_subscription_status($subscription_id,1);
				
				//add payment				
			
				
			}	
			
			
		} //endif verified
		
	
	}
	
	/*handle ipn*/
	public function handle_paypal_ipn($paypal_response)
	{
				
		global $wpdb,  $wpuserspro;
		
		$req = 'cmd=_notify-validate';

		// Read the post from PayPal system and add 'cmd'
		$fullipnA = array();
		foreach ($_POST as $key => $value)
		{
            $value = sanitize_text_field($value);
			$fullipnA[$key] = $value;
		
			$encodedvalue = urlencode(stripslashes($value));
			$req .= "&$key=$encodedvalue";
		}
		
		$fullipn =$this->Array2Str(" : ", "\n", $fullipnA);
			
		
		// Assign posted variables to local variables
		$item_name = sanitize_text_field($_POST['item_name']);
		$item_number = sanitize_text_field($_POST['item_number']);
		$payment_status = sanitize_text_field($_POST['payment_status']);
		$payment_amount = sanitize_text_field($_POST['mc_gross']);
		$payment_currency = sanitize_text_field($_POST['mc_currency']);
		$txn_id = sanitize_text_field($_POST['txn_id']);
		$receiver_email = sanitize_text_field($_POST['receiver_email']);
		$payer_email = sanitize_text_field($_POST['payer_email']);
		$txn_type = sanitize_text_field($_POST['txn_type']);
		$pending_reason = sanitize_text_field($_POST['pending_reason']);
		$payment_type = sanitize_text_field($_POST['payment_type']);
		$custom_key = sanitize_text_field($_POST['custom']);		
		$subscr_id = sanitize_text_field($_POST['subscr_id']);	
		
		//tweak for multi purchase
        
        $custom_d = sanitize_text_field($_POST['custom']);        
		$custom = explode("|",$custom_d );
                          		
		$type = $custom[0];
		$custom_key = $custom[1];			
		
		$site_date = date( 'Y-m-d', current_time( 'timestamp', 0 ) );	
		$site_date_time = date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) );
		
		if($this->check_ipn()) {			
			
			/*VALID TRANSACTION*/			
			$errors = "";
			
			$paypal_email = $wpuserspro->get_option("gateway_paypal_email");
			$paypal_currency_code = $wpuserspro->get_option("gateway_paypal_currency");
			$business_email = $paypal_email;			
		
			// Get Subscription
			$subscription = $wpuserspro->order->get_subscription_with_key($custom_key);
			
			if ($subscription->subscription_id=="")    
			{
				$errors .= " --- Subscription Key Not VAlid: " .$custom_key;
				
			}				
									
			$subscription_id = 	$subscription->subscription_id;				
			$user_id = $subscription->subscription_user_id;				
			$package = $wpuserspro->membership->get_one($subscription->subscription_package_id );
			
			
			if( $package->membership_type=='recurring'){
							
				$isrecurring = 1;					
				$amount_subscription = $package->membership_subscription_amount;
				$amount = $package->membership_initial_amount;
		
			}else{
				
				$isrecurring = 0;	
				$amount_subscription = $package->membership_subscription_amount;					
				$amount = $package->membership_initial_amount;					
			}
		
			/*Transaction Type*/			
			if($txn_type=="subscr_payment" && $subscr_id!='' ){				
			
				//get initial payment for this subscription.				
				if( $wpuserspro->membership->is_initial_payment($subscription_id)){
					
					$ini = 1 ;					
					$amount_subscription = $package->membership_subscription_amount;					
					$amount = $package->membership_initial_amount;				
				
				}else{
					
					$ini = 0 ;
					$amount_subscription = $package->membership_subscription_amount;					
					$amount = 0;			
				
				}
				
				//we create the order						
				$order_data = array(
				 'order_user_id' => $user_id,
				 'order_subscription_id' => $subscription_id,						 
				 'order_method_name' => 'paypal' ,
				 'order_key' => $transaction_key ,						 
				 'order_txt_id' => $txn_id ,						 
				 'order_status' =>1,
				 'order_ini' => $ini,
				 'order_date' => $site_date_time ,
				 'order_amount' => $amount,
				 'order_amount_subscription' => $amount_subscription ); 
									
				 $order_id = $wpuserspro->order->create_order($order_data);													
								
				 /*Update Subscription Status*/						
				 $wpuserspro->order->update_subscription_status($subscription_id,1);
				
				 /*Update Expiration Dates*/							
				 $valid_periods = array();
				 $valid_periods  = $wpuserspro->membership->get_periods($package);									
				 $wpuserspro->order->update_subscription_expiration($subscription_id,$valid_periods['starts'], $valid_periods['ends']);
			
				$client = get_user_by( 'id', $user_id);					 
					 
				 
				if($ini==1){		 
					 
					  //notify admin?						   
					   if( $wpuserspro->get_option('noti_membership_purchase_package_admin') !='no'){
						   
						   $wpuserspro->messaging->send_admin_purchase_notice($client, $package, $subscription);	
					   }
											   
					   //notify client?						   
					   if( $wpuserspro->get_option('noti_membership_purchase_package_client') !='no'){						   
						   
						   $wpuserspro->messaging->send_client_purchase_notice($client, $package, $subscription);				   	
					   
					   }					 
				  }
				 
				 
				 if($ini==0){
					 
				   //notify admin?						   
				   if( $wpuserspro->get_option('noti_membership_renewal_package_admin') !='no'){
					 
					 $wpuserspro->messaging->send_admin_renewal_notice($client, $package, $subscription);	
				   }
										 
				   //notify client?						   
				   if( $wpuserspro->get_option('noti_membership_renewal_package_client') !='no'){						   
					 
					 $wpuserspro->messaging->send_client_renewal_notice($client, $package, $subscription);				   	
				 
				   }					 
				 }
			  
					
				
	        }elseif($txn_type=="subscr_cancel"){
				
				$errors .= " --- Subscription canceled";	
			
			}elseif($txn_type=="subscr_eot"){
				
				// Recurring payment agreement expired			
				$errors .= " --- Payment Expired";					
			
			}elseif($txn_type=="subscr_failed"){
				
				// Subscription payment failed			
				$errors .= " --- Payment Expired";	
							
			
			}elseif($txn_type=="recurring_payment_failed"){
				
				//Recurring payment failed				
				$errors .= " --- Payment Failed";
				
			
			}elseif($txn_type=="recurring_payment"){
				
			
			}elseif($txn_type=="web_accept" || $txn_type=="cart"){ //this is a onetime payment
			
				//we create the order						
				$order_data = array(
				 'order_user_id' => $user_id,
				 'order_subscription_id' => $subscription_id,						 
				 'order_method_name' => 'paypal' ,
				 'order_key' => $transaction_key ,						 
				 'order_txt_id' => $txn_id ,						 
				 'order_status' =>1,
				 'order_ini' => 1,
				 'order_date' => $site_date_time ,
				 'order_amount' => $payment_amount,
				 'order_amount_subscription' =>0 ); 
									
				 $order_id = $wpuserspro->order->create_order($order_data);													
								
				 /*Update Subscription Status*/						
				 $wpuserspro->order->update_subscription_status($subscription_id,1);
				
				 /*Update Expiration Dates*/							
				 $valid_periods = array();
				 $valid_periods  = $wpuserspro->membership->get_periods($package);									
				 $wpuserspro->order->update_subscription_expiration($subscription_id,$valid_periods['starts'], $valid_periods['ends']);
				
				/*Update Subscription With Transaction ID*/						
				$wpuserspro->order->update_subscription_merchant_id($subscription_id,$txn_id);
				
				if ($type=="ini"){
									
					$user_pass = wp_generate_password( 8, false);
					wp_set_password( $user_pass, $user_id );
					
					$login_link_id = $wpuserspro->get_option('user_login_page');				
					$login_link = get_page_link($login_link_id);		
								
					$user = get_user_by( 'id', $user_id );
					$wpuserspro->messaging->send_client_registration_link($user, $login_link, $user_pass);
										
				}elseif($type=="upgrade"){	
				
				
				}
				
				$client = get_user_by( 'id', $user_id);	
				
				//notify admin?						   
			    if( $wpuserspro->get_option('noti_membership_purchase_package_admin') !='no'){
				   
				   $wpuserspro->messaging->send_admin_purchase_notice($client, $package, $subscription);	
			    }
									   
			    //notify client?						   
			    if( $wpuserspro->get_option('noti_membership_purchase_package_client') !='no'){						   
				   
				   $wpuserspro->messaging->send_client_purchase_notice($client, $package, $subscription);				   	
			   
			    }	
				
				
			}else{
				
				//sucesful transaction
				
				// check that payment_amount is correct		
				if ($payment_amount < $total_price)    
				{
					//$errors .= " --- Wrong Amount: Received $payment_amount$payment_currency; Expected: $total_price$paypal_currency_code";
					
				}
				
				// check currency						
				if ($payment_currency != $paypal_currency_code)
				{
					$errors .= " --- Wrong Currency - Received: $payment_amount$payment_currency; Expected: $total_price$paypal_currency_code";
					
				}
			}
			
			if ($errors=="")
			{
				
				
			}else{
				
				//$wpuserspro->messaging->paypal_ipn_debug("IPN ERRORS: ".$errors);
				
			
			}
			
			
		
		
		}else{
			
			//$wpuserspro->messaging->paypal_ipn_debug("IPN NOT VERIFIED: ".$fullipn);			
			
			/*This is not a valid transaction*/
		}
		
		if($wpuserspro->get_option("send_paypal_ipn_to_admin") =='yes')
		{						
			//
			$wpuserspro->messaging->paypal_ipn_debug("IPN OUTPUT-------: ".$fullipn);		
		
		}
		
	}
	

		
	function check_ipn() {
 
		 $ipn_response = !empty($_POST) ? $_POST : false;
	 
		 if ($ipn_response == false) {
			 
			 return false;
			 
		 }
	 
		 if ($ipn_response && $this->check_ipn_valid($ipn_response)) {
	 
			 header('HTTP/1.1 200 OK');
	 
			 return true;
		 }
	}
	
	function check_ipn_valid($ipn_response) {
		
		global $wpdb,  $wpuserspro;
				
		$mode = $wpuserspro->get_option("gateway_paypal_mode");
		
		if ($mode==1) 
		{
			$url ='https://www.paypal.com/cgi-bin/webscr';	
		
		}else{	
		
			$url ='https://www.sandbox.paypal.com/cgi-bin/webscr'; 	
		
		}
		  
		 // Get received values from post data
		  
		 $validate_ipn = array('cmd' => '_notify-validate');
	   
		 $validate_ipn += stripslashes_deep($ipn_response);
	 
		 // Send back post vars to paypal
	 
		 $params = array(
			 'body' => $validate_ipn,
			 'sslverify' => false,
			 'timeout' => 60,
			 'httpversion' => '1.1',
			 'compress' => false,
			 'decompress' => false,
			 'user-agent' => 'paypal-ipn/'
		  );
	 
		  // Post back to get a response
	 
		  $response = wp_safe_remote_post($url, $params);
	 
		  // check to see if the request was valid
	 
		  if (!is_wp_error($response) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 && strstr($response['body'], 'VERIFIED')) {
	 
			  return true;
	 
		  }
	 
		  return false;
	 
	}
	
	function StopProcess()
	{
	
		exit;
	}
	
	function Array2Str($kvsep, $entrysep, $a)
	{
		$str = "";
		foreach ($a as $k=>$v)
		{
			$str .= "{$k}{$kvsep}{$v}{$entrysep}";
		}
		return $str;
	}
	
	public function get_redir_cancel_trans($key)
	{
		global $wpuserspro, $wp_rewrite, $post ;
		
		$wp_rewrite = new WP_Rewrite();		
		require_once(ABSPATH . 'wp-includes/link-template.php');
		
		$url = '';
		$my_success_url = '';			
		$post_slug=$post->post_slug;	
		
		if($wpuserspro->get_option('gateway_paypal_cancel_active')=='1')		
		{			
			$sucess_page_id = $wpuserspro->get_option('gateway_paypal_cancel');
			$my_success_url = get_permalink($sucess_page_id);		
		}
		
		if($my_success_url=="")
		{
			$url = site_url("/").$post_slug.'?wpuserspro_payment_status=ok&wpuserspro_order_key='.$key;
				
		}else{
					
			$url = $my_success_url.'?wpuserspro_payment_status=ok&wpuserspro_order_key='.$key;				
				
		}		
		 		  
		return urlencode($url);		  
		 
	  }
	
	public function get_redir_success_trans($key)
	{
		global $wpuserspro, $wp_rewrite, $post ;
		
		$wp_rewrite = new WP_Rewrite();		
		require_once(ABSPATH . 'wp-includes/link-template.php');
		
		$url = '';
		$my_success_url = '';			
		$post_slug=$post->post_slug;	
		
		if($wpuserspro->get_option('gateway_paypal_success_active')=='1')		
		{			
			$sucess_page_id = $wpuserspro->get_option('gateway_paypal_success');
			$my_success_url = get_permalink($sucess_page_id);		
		}
		
		if($my_success_url=="")
		{
			$url = site_url("/").$post_slug.'?wpuserspro_payment_status=ok&wpuserspro_order_key='.$key;
				
		}else{
					
			$url = $my_success_url.'?wpuserspro_payment_status=ok&wpuserspro_order_key='.$key;				
				
		}		
		 		  
		return urlencode($url);		  
		 
	  }
	
	
	/*Get IPN*/
	public function get_ipn_link($package, $subscription_data, $tran_type)
	{	
		
		global $wpdb,  $wpuserspro, $wp_rewrite;
		
		$wp_rewrite = new WP_Rewrite();		
		
		extract($subscription_data);
		
		
		$paypal_email = $wpuserspro->get_option("gateway_paypal_email");
		$currency_code = $wpuserspro->get_option("gateway_paypal_currency");		
					
		$p_name = $package->membership_name;
		
		$package_period= $package->membership_every; //from 1-31
		$package_time_period= $package->membership_time_period; //days, months, years, weeks
		
		if( $package->membership_type=='recurring'){
							
			$isrecurring = 1;	
			$amount_setup = $package->membership_initial_amount;					
			$amount_subscription = $package->membership_subscription_amount;
					
		}else{
				
			$isrecurring = 0;						
			$amount = $package->membership_initial_amount;					
		}
						
		
		$transaction_key = $subscription_key;		
		$paypalcustom = $tran_type."|".$transaction_key;
		
		//get IPN Call Back URL:
		$web_url = site_url();
		$notify_url = $web_url."/?easywpmipncall";
		
		/*return sucess transaction - By default the user is taken to the backend*/		
		$sucess_url = $this->get_redir_success_trans($transaction_key);		
		$cancel_return = $this->get_redir_cancel_trans($transaction_key);			
				
		$mode = $wpuserspro->get_option("gateway_paypal_mode");
		
		if($mode==1)
		{			
			$mode = "www";			
			
		}else{
			
			$mode = "www.sandbox";
			$paypal_email = $wpuserspro->get_option("gateway_paypal_sandbox_email");
		
		}
		
		
		if($isrecurring=="1")
		{
			$type = "_xclick-subscriptions";			
			
			if($amount_setup>0)
			{
				//setup fee				
				$url = "https://".$mode.".paypal.com/webscr?cmd=".$type."&business=".$paypal_email."&currency_code=".$currency_code."&no_shipping=1&item_name=".$p_name."&return=".$sucess_url."&notify_url=".$notify_url."&custom=".$paypalcustom."&a1=".$amount_setup."&p1=".$package_period."&t1=".$package_time_period."&a3=".$amount_subscription."&p3=".$package_period."&t3=".$package_time_period."&src=1&sra=1"."&cancel_return=".$cancel_return;				
			
			}else{
				
				$url = "https://".$mode.".paypal.com/webscr?cmd=".$type."&business=".$paypal_email."&currency_code=".$currency_code."&no_shipping=1&item_name=".$p_name."&return=".$sucess_url."&notify_url=".$notify_url."&custom=".$paypalcustom."&a3=".$amount_subscription."&p3=".$package_period."&t3=".$package_time_period."&src=1&sra=1"."&cancel_return=".$cancel_return;			
			
			}			
			
		}
		
		//one time	
		if($isrecurring=="0")
		{
			$type = "_xclick";
			
			$url = "https://".$mode.".paypal.com/webscr?cmd=".$type."&business=".$paypal_email."&currency_code=".$currency_code."&no_shipping=1&item_name=".$p_name."&return=".$sucess_url."&notify_url=".$notify_url."&custom=".$paypalcustom."&amount=".$amount."&p3=".$package_period."&t3=".$package_time_period."&src=1&sra=1"."&cancel_return=".$cancel_return;
		}
		
		
		return $url;
		
	}
	
}
$key = "paypal";
$this->{$key} = new WPUsersProPaypal();