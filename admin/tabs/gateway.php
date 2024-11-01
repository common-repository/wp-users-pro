<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpuserspro,   $wpuserspro_stripe;
?>
<h3><?php _e('Payment Gateways Settings','wp-users-pro'); ?></h3>
<form method="post" action="">
<input type="hidden" name="wpuserspro_update_settings" />


<?php if(isset($wpuserspro_stripe))
{?>
<div class="easywpmembers-sect  easywpmembers-welcome-panel ">
  <h3><?php _e('Stripe Settings','wp-users-pro'); ?></h3>
  
  <p><?php _e("Stripe is a payment gateway for mechants. If you don't have a Stripe account, you can <a href='https://stripe.com/'> sign up for one account here</a> ",'wp-users-pro'); ?></p>
  
  <p><?php _e('Here you can configure Stripe if you wish to accept credit card payments directly in your website. Find your Stripe API keys here <a href="https://dashboard.stripe.com/account/apikeys">https://dashboard.stripe.com/account/apikeys</a>','wp-users-pro'); ?></p>
  
  
  <table class="form-table">
<?php 

$this->create_plugin_setting(
                'checkbox',
                'gateway_stripe_active',
                __('Activate Stripe','wp-users-pro'),
                '1',
                __('If checked, Stripe will be activated as payment method','wp-users-pro'),
                __('If checked, Stripe will be activated as payment method','wp-users-pro')
        ); 


$this->create_plugin_setting(
        'input',
        'test_secret_key',
        __('Test Secret Key','wp-users-pro'),array(),
        __('You can get this on stripe.com','wp-users-pro'),
        __('You can get this on stripe.com','wp-users-pro')
);

$this->create_plugin_setting(
        'input',
        'test_publish_key',
        __('Test Publishable Key','wp-users-pro'),array(),
        __('You can get this on stripe.com','wp-users-pro'),
        __('You can get this on stripe.com','wp-users-pro')
);

$this->create_plugin_setting(
        'input',
        'live_secret_key',
        __('Live Secret Key','wp-users-pro'),array(),
        __('You can get this on stripe.com','wp-users-pro'),
        __('You can get this on stripe.com','wp-users-pro')
);

$this->create_plugin_setting(
        'input',
        'live_publish_key',
        __('Live Publishable Key','wp-users-pro'),array(),
        __('You can get this on stripe.com','wp-users-pro'),
        __('You can get this on stripe.com','wp-users-pro')
);


$this->create_plugin_setting(
        'input',
        'signing_secret',
        __('Signing secret','wp-users-pro'),array(),
        __('You can get this on Stripe - WebHooks link','wp-users-pro'),
        __('You can get this on Stripe - WebHooks link','wp-users-pro')
);


$this->create_plugin_setting(
        'input',
        'gateway_stripe_currency',
        __('Currency','wp-users-pro'),array(),
        __('Please enter the currency, example USD.','wp-users-pro'),
        __('Please enter the currency, example USD.','wp-users-pro')
);

$this->create_plugin_setting(
        'textarea',
        'gateway_stripe_success_message',
        __('Custom Message','wp-users-pro'),array(),
        __('Input here a custom message that will be displayed to the client once the booking has been confirmed at the front page.','wp-users-pro'),
        __('Input here a custom message that will be displayed to the client once the booking has been confirmed at the front page.','wp-users-pro')
);

$this->create_plugin_setting(
                'checkbox',
                'gateway_stripe_success_active',
                __('Custom Success Page Redirect ','wp-users-pro'),
                '1',
                __('If checked, the users will be taken to this page once the payment has been confirmed','wp-users-pro'),
                __('If checked, the users will be taken to this page once the payment has been confirmed','wp-users-pro')
        ); 


$this->create_plugin_setting(
            'select',
            'gateway_stripe_success',
            __('Success Page','wp-users-pro'),
            $this->get_all_sytem_pages(),
            __("Select the sucess page. The user will be taken to this page if the payment was approved by stripe.",'wp-users-pro'),
            __('Select the sucess page. The user will be taken to this page if the payment was approved by stripe.','wp-users-pro')
    );


$this->create_plugin_setting(
	'select',
	'enable_live_key',
	__('Mode','wp-users-pro'),
	array(
		1 => __('Production Mode','wp-users-pro'), 
		2 => __('Test Mode (Sandbox)','wp-users-pro')
		),
		
	__('.','wp-users-pro'),
  __('.','wp-users-pro')
       );
	   



		
?>
</table>

  
</div>

<?php }?>


<?php if(isset($bupcomplement))
{?>
<div class="easywpmembers-sect  easywpmembers-welcome-panel" style="display:none">
  <h3><?php _e('Authorize.NET AIM Settings','wp-users-pro'); ?></h3>
  
  <p><?php _e(" ",'wp-users-pro'); ?></p>
  
  <p><?php _e(' ','wp-users-pro'); ?></p>
  
  
  <table class="form-table">
<?php 

$this->create_plugin_setting(
                'checkbox',
                'gateway_authorize_active',
                __('Activate Authorize','wp-users-pro'),
                '1',
                __('If checked, Authorize will be activated as payment method','wp-users-pro'),
                __('If checked, Authorize will be activated as payment method','wp-users-pro')
        ); 



$this->create_plugin_setting(
        'input',
        'authorize_login',
        __('API Login ID','wp-users-pro'),array(),
        __('You can get this on authorize.net','wp-users-pro'),
        __('You can get this on authorize.net','wp-users-pro')
);

$this->create_plugin_setting(
        'input',
        'authorize_key',
        __('API Transaction Key','wp-users-pro'),array(),
        __('You can get this on authorize.net','wp-users-pro'),
        __('You can get this on authorize.net','wp-users-pro')
);


$this->create_plugin_setting(
        'input',
        'authorize_currency',
        __('Currency','wp-users-pro'),array(),
        __('Please enter the currency, example USD.','wp-users-pro'),
        __('Please enter the currency, example USD.','wp-users-pro')
);

$this->create_plugin_setting(
        'textarea',
        'gateway_authorize_success_message',
        __('Custom Message','wp-users-pro'),array(),
        __('Input here a custom message that will be displayed to the client once the booking has been confirmed at the front page.','wp-users-pro'),
        __('Input here a custom message that will be displayed to the client once the booking has been confirmed at the front page.','wp-users-pro')
);

$this->create_plugin_setting(
                'checkbox',
                'gateway_authorize_success_active',
                __('Custom Success Page Redirect ','wp-users-pro'),
                '1',
                __('If checked, the users will be taken to this page once the payment has been confirmed','wp-users-pro'),
                __('If checked, the users will be taken to this page once the payment has been confirmed','wp-users-pro')
        ); 


$this->create_plugin_setting(
            'select',
            'gateway_authorize_success',
            __('Success Page','wp-users-pro'),
            $this->get_all_sytem_pages(),
            __("Select the sucess page. The user will be taken to this page if the payment was approved by Authorize.net ",'wp-users-pro'),
            __('Select the sucess page. The user will be taken to this page if the payment was approved by Authorize.net','wp-users-pro')
    );


$this->create_plugin_setting(
	'select',
	'authorize_mode',
	__('Mode','wp-users-pro'),
	array(
		1 => __('Production Mode','wp-users-pro'), 
		2 => __('Test Mode (Sandbox)','wp-users-pro')
		),
		
	__('.','wp-users-pro'),
  __('.','wp-users-pro')
       );
	   



		
?>
</table>

  
</div>

<?php }?>

<div class="easywpmembers-sect  easywpmembers-welcome-panel">
  <h3><?php _e('PayPal','wp-users-pro'); ?></h3>
  
  <p><?php _e('Here you can configure PayPal if you wish to accept paid registrations','wp-users-pro'); ?></p>
    <p><?php _e("Please note: You have to set a right currency <a href='https://developer.paypal.com/docs/classic/api/currency_codes/' target='_blank'> check supported currencies here </a> ",'wp-users-pro'); ?></p>
  
  
  <table class="form-table">
<?php 

$this->create_plugin_setting(
                'checkbox',
                'gateway_paypal_active',
                __('Activate PayPal','wp-users-pro'),
                '1',
                __('If checked, PayPal will be activated as payment method','wp-users-pro'),
                __('If checked, PayPal will be activated as payment method','wp-users-pro')
        ); 

$this->create_plugin_setting(
	'select',
	'uultra_send_ipn_to_admin',
	__('The Paypal IPN response will be sent to the admin','wp-users-pro'),
	array(
		'no' => __('No','wp-users-pro'), 
		'yes' => __('Yes','wp-users-pro'),
		),
		
	__("If 'yes' the admin will receive the whole Paypal IPN response. This helps to troubleshoot issues.",'wp-users-pro'),
  __("If 'yes' the admin will receive the whole Paypal IPN response. This helps to troubleshoot issues.",'wp-users-pro')
       );

$this->create_plugin_setting(
        'input',
        'gateway_paypal_email',
        __('PayPal Email Address','wp-users-pro'),array(),
        __('Enter email address associated to your PayPal account.','wp-users-pro'),
        __('Enter email address associated to your PayPal account.','wp-users-pro')
);

$this->create_plugin_setting(
        'input',
        'gateway_paypal_sandbox_email',
        __('Paypal Sandbox Email Address','wp-users-pro'),array(),
        __('This is not used for production, you can use this email for testing.','wp-users-pro'),
        __('This is not used for production, you can use this email for testing.','wp-users-pro')
);

$this->create_plugin_setting(
        'input',
        'gateway_paypal_currency',
        __('Currency','wp-users-pro'),array(),
        __('Please enter the currency, example USD.','wp-users-pro'),
        __('Please enter the currency, example USD.','wp-users-pro')
);


$this->create_plugin_setting(
                'checkbox',
                'gateway_paypal_success_active',
                __('Custom Success Page Redirect ','wp-users-pro'),
                '1',
                __('If checked, the users will be taken to this page once the payment has been confirmed','wp-users-pro'),
                __('If checked, the users will be taken to this page once the payment has been confirmed','wp-users-pro')
        ); 


$this->create_plugin_setting(
            'select',
            'gateway_paypal_success',
            __('Success Page','wp-users-pro'),
            $this->get_all_sytem_pages(),
            __("Select the sucess page. The user will be taken to this page if the payment was approved by stripe.",'wp-users-pro'),
            __('Select the sucess page. The user will be taken to this page if the payment was approved by stripe.','wp-users-pro')
    );
	
	
	$this->create_plugin_setting(
                'checkbox',
                'gateway_paypal_cancel_active',
                __('Custom Cancellation Page Redirect ','wp-users-pro'),
                '1',
                __('If checked, the users will be taken to this page if the payment is cancelled at PayPal website','wp-users-pro'),
                __('If checked, the users will be taken to this page if the payment is cancelled at PayPal website','wp-users-pro')
        ); 
		
		
		$this->create_plugin_setting(
            'select',
            'gateway_paypal_cancel',
            __('Cancellation Page','wp-users-pro'),
            $this->get_all_sytem_pages(),
            __("Select the cancellation page. The user will be taken to this page if the payment is cancelled at PayPal Website",'wp-users-pro'),
            __('Select the cancellation page. The user will be taken to this page if the payment is cancelled at PayPal Website','wp-users-pro')
    );


$this->create_plugin_setting(
	'select',
	'gateway_paypal_mode',
	__('Mode','wp-users-pro'),
	array(
		1 => __('Production Mode','wp-users-pro'), 
		2 => __('Test Mode (Sandbox)','wp-users-pro')
		),
		
	__('.','wp-users-pro'),
  __('.','wp-users-pro')
       );
	   





		
?>
</table>

  
</div>


<div class="easywpmembers-sect  easywpmembers-welcome-panel ">
  <h3><?php _e('Bank Deposit/Cash Other','wp-users-pro'); ?></h3>
  
  <p><?php _e('Here you can configure the information that will be sent to the client. This could be your bank account details.','wp-users-pro'); ?></p>
  
  
  <table class="form-table">
<?php 

$this->create_plugin_setting(
                'checkbox',
                'gateway_bank_active',
                __('Activate Bank Deposit','wp-users-pro'),
                '1',
                __('If checked, Bank Payment Deposit will be activated as payment method','wp-users-pro'),
                __('If checked, Bank Payment Deposit will be activated as payment method','wp-users-pro')
        ); 


$this->create_plugin_setting(
        'input',
        'gateway_bank_label',
        __('Custom Label','wp-users-pro'),array(),
        __('Example: Bank Deposit , Cash, Wire etc.','wp-users-pro'),
        __('Example: Bank Deposit , Cash, Wire etc.','wp-users-pro')
);


$this->create_plugin_setting(
        'textarea',
        'gateway_bank_success_message',
        __('Custom Message','wp-users-pro'),array(),
        __('Input here a custom message that will be displayed to the client once the booking has been confirmed at the front page.','wp-users-pro'),
        __('Input here a custom message that will be displayed to the client once the booking has been confirmed at the front page.','wp-users-pro')
);



$this->create_plugin_setting(
                'checkbox',
                'gateway_bank_success_active',
                __('Custom Success Page Redirect ','wp-users-pro'),
                '1',
                __('If checked, the users will be taken to this page ','wp-users-pro'),
                __('If checked, the users will be taken to this page ','wp-users-pro')
        ); 


$this->create_plugin_setting(
            'select',
            'gateway_bank_success',
            __('Success Page','wp-users-pro'),
            $this->get_all_sytem_pages(),
            __("Select the sucess page. The user will be taken to this page on purchase confirmation",'wp-users-pro'),
            __('Select the sucess page. The user will be taken to this page on purchase confirmation','wp-users-pro')
    );
	
	$data_status = array(
		 				'0' => 'Pending',
                        '1' =>'Approved'
                       
                    );
$this->create_plugin_setting(
            'select',
            'gateway_bank_default_status',
            __('Default Status for Local Payments','wp-users-pro'),
            $data_status,
            __("Set the default status a subscription will have when using local payment method. ",'wp-users-pro'),
            __('et the default status a subscription will have when using local payment method.','wp-users-pro')
    );	

		
?>
</table>

  
</div>



<p class="submit">
	<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes','wp-users-pro'); ?>"  />
	
</p>

</form>