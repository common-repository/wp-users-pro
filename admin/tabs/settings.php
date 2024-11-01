<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpuserspro, $wpuserspro_activation, $wpuserspro_aweber, $wpuserspro_mailchimp, $wpuserspro_recaptcha;
?>
<h3><?php _e('Plugin Settings','wp-users-pro'); ?></h3>
<form method="post" action="">
<input type="hidden" name="wpuserspro_update_settings" />


<div id="easywpm-bupro-settings" class="easywpmembers-multi-tab-options">

<ul class="nav-tab-wrapper bup-nav-pro-features">

<li class="nav-tab bup-pro-li"><a href="#tabs-1" title="<?php _e('General','wp-users-pro'); ?>"><?php _e('General','wp-users-pro'); ?></a></li>

<li class="nav-tab bup-pro-li"><a href="#tabs-messaging" title="<?php _e('Advanced Messaging Settings','wp-users-pro'); ?>"><?php _e('Advanced Messaging Settings','wp-users-pro'); ?></a></li>
<li class="nav-tab bup-pro-li"><a href="#tabs-loggedin-protection" title="<?php _e('Posts & Pages Protection ','wp-users-pro'); ?>"><?php _e('Posts & Pages Protection','wp-users-pro'); ?></a></li>


<li class="nav-tab bup-pro-li"><a href="#tabs-bup-newsletter" title="<?php _e('Newsletter','wp-users-pro'); ?>"><?php _e('Newsletter','wp-users-pro'); ?> </a></li>

<li class="nav-tab bup-pro-li"><a href="#tabs-easywpmembers-recaptcha" title="<?php _e('reCaptcha','wp-users-pro'); ?>"><?php _e('reCaptcha','wp-users-pro'); ?> </a></li>



</ul>



<div id="tabs-easywpmembers-recaptcha">


<div class="easywpmembers-sect  easywpmembers-welcome-panel">
  <h3><?php _e('reCaptcha','wp-users-pro'); ?></h3>
  
  <?php if(!isset($wpuserspro_recaptcha)){
	  
	  $html = '<div class="easywpmembers-ultra-warning">'. __("Please make sure that WP Users reCaptcha (Add-on) plugin is active.", 'wp-users-pro').'</div>';
	  
	  echo $html ;
	  ?>
  
  
  
  <?php }?>
  
    
  <p><?php _e('This is a free add-on which was developed to help you to protect your ticket system against spammers.','wp-users-pro'); ?></p>
  
    <p><?php _e("You can get the Site Key and Secret Key on Google reCaptcha Dashboard",'wp-users-pro'); ?>. <a href="https://www.google.com/recaptcha/admin" target="_blank"> <?php _e("Click here",'wp-users-pro'); ?> </a> </p>
    
    <p><?php _e("You may check the reCaptcha setup tutorial as well. ",'wp-users-pro'); ?> <a href="http://docs.wpuserspro.com/installing-recaptcha/" target="_blank"> <?php _e("Click here",'wp-users-pro'); ?> </a> </p>
  
  
  
  <table class="form-table">
<?php


	$this->create_plugin_setting(
			'input',
			'recaptcha_site_key',
			__('Site Key:','wp-users-pro'),array(),
			__('Enter your site key here.','wp-users-pro'),
			__('Enter your site key here.','wp-users-pro')
	);
	
	$this->create_plugin_setting(
			'input',
			'recaptcha_secret_key',
			__('Secret Key:','wp-users-pro'),array(),
			__('Enter your site secret here.','wp-users-pro'),
			__('Enter your site secret here.','wp-users-pro')
	);

	
?>
</table>
</div>


<div class="easywpmembers-sect  easywpmembers-welcome-panel">
  <h3><?php _e('Where to display?','wp-users-pro'); ?></h3>
  
    
  <p><?php _e('Select what forms will be protected by reCaptcha','wp-users-pro'); ?></p>
  
  <table class="form-table">
<?php


	$this->create_plugin_setting(
                'checkbox',
                'recaptcha_display_registration',
                __('Registration Form','wp-users-pro'),
                '1',
                __('If checked, the reCaptcha will be displayed in the registration form.','wp-users-pro'),
                __('If checked, the reCaptcha will be displayed in the registration form.','wp-users-pro')
        );
		
	$this->create_plugin_setting(
                'checkbox',
                'recaptcha_display_loginform',
                __('Login Form','wp-users-pro'),
                '1',
                __('If checked, the reCaptcha will be displayed in the login form.','wp-users-pro'),
                __('If checked, the reCaptcha will be displayed in the login form.','wp-users-pro')
        );
		
	
	$this->create_plugin_setting(
                'checkbox',
                'recaptcha_display_forgot_password',
                __('Forgot Password Form','wp-users-pro'),
                '1',
                __('If checked, the reCaptcha will be displayed in the forgot password form.','wp-users-pro'),
                __('If checked, the reCaptcha will be displayed in the forgot password form.','wp-users-pro')
        ); 
		
	
	$this->create_plugin_setting(
                'checkbox',
                'recaptcha_display_comments_native',
                __('Comments','ultimate-captcha'),
                '1',
                __('If checked, the reCaptcha will be displayed in the comments form.','wp-users-pro'),
                __('If checked, the reCaptcha will be displayed in the comments form.','wp-users-pro')
        ); 
	
?>
</table>
</div>

<p class="submit">
	<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes','wp-users-pro'); ?>"  />
</p>

  
</div>


<div id="tabs-1">




<div class="easywpmembers-sect  easywpmembers-welcome-panel">
   
   
   <h3><?php _e('Registration Settings','wp-users-pro'); ?></h3>  
   <p><?php _e('These settings allow you to define rules on how the members can register in your website.','wp-users-pro'); ?></p>
 
 
   <table class="form-table">
 <?php
 
 
$this->create_plugin_setting(
	'select',
	'registration_rules',
	__('Registration Type','wp-users-pro'),
	array(
		
		4 => __('Paid Subscriptions - Enables the Subscriptions Features','wp-users-pro'),
		1=> __('Disable Paid Subscriptions - This will remove the Payment Options','wp-users-pro')),
		
		
	__('Please note: If you disable the Paid Subscriptions the subscriptions plans and the payment methods will be removed from the reistration form.','wp-users-pro'),
  __('Please note: If you disable the Paid Subscriptions the subscriptions plans and the payment methods will be removed from the reistration form.','wp-users-pro')
       );
	   

?>
 
 </table>
 
  
   
 
 

  
</div>


<div class="easywpmembers-sect  easywpmembers-welcome-panel">
  <h3><?php _e('Miscellaneous  Settings','wp-users-pro'); ?></h3>
  
  <p><?php _e('.','wp-users-pro'); ?></p>
  
  
  <p style="text-align:right" class="easywpmembers-timestamp-features"> <?php _e('Site Time: ','wp-users-pro')?><?php echo date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) )?> (Offset: <?php echo get_option('gmt_offset');?>) | <?php _e('GMT: ','wp-users-pro')?>  <?php echo date( 'Y-m-d H:i:s', current_time( 'timestamp', 1 ) )?></p>
  
  
  <table class="form-table">
<?php 


$this->create_plugin_setting(
        'input',
        'company_name',
        __('Company Name:','wp-users-pro'),array(),
        __('Enter your company name here.','wp-users-pro'),
        __('Enter your company name here.','wp-users-pro')
);

$this->create_plugin_setting(
        'input',
        'company_phone',
        __('Company Phone Nunber:','wp-users-pro'),array(),
        __('Enter your company phone number here.','wp-users-pro'),
        __('Enter your company phone number here.','wp-users-pro')
);

$this->create_plugin_setting(
        'input',
        'allowed_extensions',
        __('Allowed Extensions:','wp-users-pro'),array(),
        __('Enter the allowed extensions separated by commas. Example:  jpg,png,gif,jpeg,pdf,doc,docx,xls','wp-users-pro'),
        __('Enter the allowed extensions separated by commas. Example: jpg,png,gif,jpeg,pdf,doc,docx,xls','wp-users-pro')
);

	   

 $data = array(
		 				'm/d/Y' => date('m/d/Y'),
                        'm/d/y' => date('m/d/y'),
                        'Y/m/d' => date('Y/m/d'),
                        'dd/mm/yy' => date('d/m/Y'),
                        'Y-m-d' => date('Y-m-d'),
                        'd-m-Y' => date('d-m-Y'),
                        'm-d-Y' => date('m-d-Y'),
                        'F j, Y' => date('F j, Y'),
                        'j M, y' => date('j M, y'),
                        'j F, y' => date('j F, y'),
                        'l, j F, Y' => date('l, j F, Y')
                    );
					
		 $data_time = array(
		 				'5' => 5,
                        '10' =>10,
                        '12' => 12,
                        '15' => 15,
                        '20' => 20,
                        '30' =>30,                       
                        '60' =>60
                       
                    );
		
		$data_time_format = array(
		 				
                        'H:i' => date('H:i'),
                        'h:i A' => date('h:i A')
                    );
		 $days_availability = array(
		 				'7' => 7,
                        '10' =>10,
                        '15' => 15,
                        '20' => 20,
                        '25' => 25,
                        '30' =>30,                       
                        '35' =>35,
						'40' =>40,
                       
                    );
   
		$data_picker = array(
		 				'm/d/Y' => date('m/d/Y'),
						'd/m/Y' => date('d/m/Y')
                    );
		$this->create_plugin_setting(
            'select',
            'date_format',
            __('Date Format:','wp-users-pro'),
            $data,
            __('Select the date format to be used','wp-users-pro'),
            __('Select the date format to be used','wp-users-pro')
    );
	
	$this->create_plugin_setting(
            'select',
            'date_picker_format',
            __('Date Picker Format:','wp-users-pro'),
            $data_picker,
            __('Select the date format to be used on the Date Picker','wp-users-pro'),
            __('Select the date format to be used on the Date Picker','wp-users-pro')
    );
	
	$this->create_plugin_setting(
            'select',
            'time_format',
            __('Display Time Format:','wp-users-pro'),
            $data_time_format,
            __('Select the time format to be used','wp-users-pro'),
            __('Select the time format to be used','wp-users-pro')
    );
	
	
	
	
	$this->create_plugin_setting(
	'select',
	'bup_override_avatar',
	__('Use Easy WP Members Avatar','wp-users-pro'),
	array(
		'no' => __('No','wp-users-pro'), 
		'yes' => __('Yes','wp-users-pro'),
		),
		
	__('If you select "yes", Easy WP Members will override the default WordPress Avatar','wp-users-pro'),
  __('If you select "yes",  Easy WP Members will override the default WordPress Avatar','wp-users-pro')
       );
	
	$this->create_plugin_setting(
	'select',
	'avatar_rotation_fixer',
	__('Auto Rotation Fixer','wp-users-pro'),
	array(
		'no' => __('No','wp-users-pro'), 
		'yes' => __('Yes','wp-users-pro'),
		),
		
	__("If you select 'yes',  Easy WP Members will Automatically fix the rotation of JPEG images using PHP's EXIF extension, immediately after they are uploaded to the server. This is implemented for iPhone rotation issues",'wp-users-pro'),
  __("If you select 'yes',  Easy WP Members will Automatically fix the rotation of JPEG images using PHP's EXIF extension, immediately after they are uploaded to the server. This is implemented for iPhone rotation issues",'wp-users-pro')
       );
	   
	   $this->create_plugin_setting(
        'input',
        'media_avatar_width',
        __('Avatar Width:','wp-users-pro'),array(),
        __('Width in pixels','wp-users-pro'),
        __('Width in pixels','wp-users-pro')
);

$this->create_plugin_setting(
        'input',
        'media_avatar_height',
        __('Avatar Height','wp-users-pro'),array(),
        __('Height in pixels','wp-users-pro'),
        __('Height in pixels','wp-users-pro')
);
	
	
	
	 								
	
	  
		
?>
</table>



</div>


<p class="submit">
	<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes','wp-users-pro'); ?>"  />
</p>




</div>




<div id="tabs-messaging">

<div class="easywpmembers-sect  easywpmembers-welcome-panel">
   
   
   <h3><?php _e('General Rules','wp-users-pro'); ?></h3>  
   <p><?php _e('These settings allow you to define rules on how the users and admin are notified when a new subscription is purchased from the front-end.','wp-users-pro'); ?></p>
 
 
   <table class="form-table">
 <?php
 
 
 $this->create_plugin_setting(
	'select',
	'noti_admin',
	__('Send Email Notifications to Admin?:','wp-users-pro'),
	array(
		'yes' => __('YES','wp-users-pro'),
		'no' => __('NO','wp-users-pro') 
		),
		
	__('This allows you to block email notifications that are sent to the admin.','wp-users-pro'),
  __('This allows you to block email notifications that are sent to the admin.','wp-users-pro')
       );
	   
$this->create_plugin_setting(
	'select',
	'noti_client',
	__('Send Email Notifications to Clients?:','wp-users-pro'),
	array(
		'yes' => __('YES','wp-users-pro'),
		'no' => __('NO','wp-users-pro') 
		),
		
	__('This allows you to block email notifications that are sent to the clients.','wp-users-pro'),
  __('This allows you to block email notifications that are sent to the clients.','wp-users-pro')
       ); 

?>
 
 </table>
 

  
</div>


<div class="easywpmembers-sect  easywpmembers-welcome-panel">
   
   
   <h3><?php _e('New Membership Purchase Notifications','wp-users-pro'); ?></h3>  
   <p><?php _e('These settings allow you to define rules on how the users and admin are notified when a new subscription is purchased.','wp-users-pro'); ?></p>
 
 
   <table class="form-table">
 <?php
 
 
 $this->create_plugin_setting(
	'select',
	'noti_membership_purchase_package_client',
	__('Send Email Notifications to Client?:','wp-users-pro'),
	array(
		'yes' => __('YES','wp-users-pro'),
		'no' => __('NO','wp-users-pro') 
		),
		
	__('This allows you to block email notifications that are sent to the admin.','wp-users-pro'),
  __('This allows you to block email notifications that are sent to the admin.','wp-users-pro')
       );
	   
$this->create_plugin_setting(
	'select',
	'noti_membership_purchase_package_admin',
	__('Send Email Notifications to Admin?:','wp-users-pro'),
	array(
		'yes' => __('YES','wp-users-pro'),
		'no' => __('NO','wp-users-pro') 
		),
		
	__('This allows you to block email notifications that are sent to the clients.','wp-users-pro'),
  __('This allows you to block email notifications that are sent to the clients.','wp-users-pro')
       );
  

?>
 
 </table>
 

  
</div>

<div class="easywpmembers-sect  easywpmembers-welcome-panel">
   
   
   <h3><?php _e('Membership Renewal Notifications','wp-users-pro'); ?></h3>  
   <p><?php _e('These settings allow you to define rules on how the users and admin are notified when a new subscription is renewed.','wp-users-pro'); ?></p>
 
 
   <table class="form-table">
 <?php
 
 
 $this->create_plugin_setting(
	'select',
	'noti_membership_renewal_package_client',
	__('Send Email Notifications to Client?:','wp-users-pro'),
	array(
		'yes' => __('YES','wp-users-pro'),
		'no' => __('NO','wp-users-pro') 
		),
		
	__('This allows you to block email notifications that are sent to the admin.','wp-users-pro'),
  __('This allows you to block email notifications that are sent to the admin.','wp-users-pro')
       );
	   
$this->create_plugin_setting(
	'select',
	'noti_membership_renewal_package_admin',
	__('Send Email Notifications to Admin?:','wp-users-pro'),
	array(
		'yes' => __('YES','wp-users-pro'),
		'no' => __('NO','wp-users-pro') 
		),
		
	__('This allows you to block email notifications that are sent to the clients.','wp-users-pro'),
  __('This allows you to block email notifications that are sent to the clients.','wp-users-pro')
       );
  

?>
 
 </table>
 

  
</div>





<p class="submit">
	<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes','wp-users-pro'); ?>"  />
</p>


</div>



<div id="tabs-loggedin-protection">

<div class="easywpmembers-sect  easywpmembers-welcome-panel">
   
   
   <h3><?php _e('Global Protection Settings','wp-users-pro'); ?></h3>  
    <p><?php _e("In this section you can manage Posts & Pages Protection module settings.",'wp-users-pro'); ?></p>
   <p><?php _e("This module will let you block pages and any post types and make them visible only to logged in users.",'wp-users-pro'); ?></p>
  
  
  <h4><?php _e("Posts & Pages Protection Activation.",'wp-users-pro'); ?></h4>
  <table class="form-table">
<?php 



$this->create_plugin_setting(
	'select',
	'activate_post_protection_modules',
	__('Protection Active','wp-users-pro'),
	array(
		'no' => __('No','wp-users-pro'), 
		'yes' => __('Yes','wp-users-pro'),
		),
		
	__("By selecting 'yes' the options to protect posts and pages will be enabled.",'wp-users-pro'),
  __("By selecting 'yes' the options to protect posts and pages will be enabled.",'wp-users-pro')
       );
	   
	   
	   $protections_method = array(
		'loggedin' => __('Logged in users only','wp-users-pro'), 
		'membership' => __('Membership only','wp-users-pro'),
		'role' => __('Only Certain Roles','wp-users-pro')
		);
	   
	   $this->create_plugin_setting(
	'select',
	'post_protection_method',
	__('Protection Method','wp-users-pro'),
	$protections_method,
		
	__("By selecting 'Logged in users only' the pots/pages will be visible to logged in usrers only.",'wp-users-pro'),
  __("By selecting 'Logged in users only' the pots/pages will be visible to logged in usrers only.",'wp-users-pro')
       );
	   
	   
  
		
?>
</table>
   <h4><?php _e("Set up the behaviour of locked posts.",'wp-users-pro'); ?></h4>
  <table class="form-table">
<?php 



$this->create_plugin_setting(
	'select',
	'uultra_loggedin_hide_complete_post',
	__('Hide Complete Posts?','wp-users-pro'),
	array(
		'no' => __('No','wp-users-pro'), 
		'yes' => __('Yes','wp-users-pro'),
		),
		
	__("By selecting 'yes' will hide posts if the user has no access.  <strong>Please note: </strong> a 404 error message will be displayed since the post will be completely locked out.",'wp-users-pro'),
  __("By selecting 'yes' will hide posts if the user has no access",'wp-users-pro')
       );

$this->create_plugin_setting(
	'select',
	'uultra_loggedin_hide_post_title',
	__('Hide Post Title?','wp-users-pro'),
	array(
		'no' => __('No','wp-users-pro'), 
		'yes' => __('Yes','wp-users-pro'),
		),
		
	__("By selecting 'yes' will show the text which is defined at 'Post title' if user has no access.",'wp-users-pro'),
  __("By selecting 'yes' will show the text which is defined at 'Post title' if user has no access.",'wp-users-pro')
       );
	   
$this->create_plugin_setting( 
        'input',
        'uultra_loggedin_post_title',
        __('Post Title:','wp-users-pro'),array(),
        __('This will be the displayed text as post title if user has no access.','wp-users-pro'),
        __('This will be the displayed text as post title if user has no access.','wp-users-pro')
);  


$this->create_plugin_setting(
	'select',
	'uultra_loggedin_post_content_before_more',
	__('Show post content before &lt;!--more--&gt; tag?','wp-users-pro'),
	array(
		'no' => __('No','wp-users-pro'), 
		'yes' => __('Yes','wp-users-pro'),
		),
		
	__('By selecting "Yes"  will display the post content before the &lt;!--more--&gt; tag and after that the defined text at "Post content". If no &lt;!--more--&gt;  is set he defined text at "Post content" will shown.','wp-users-pro'),
  __('By selecting "Yes"  will display the post content before the &lt;!--more--&gt; tag and after that the defined text at "Post content". If no &lt;!--more--&gt;  is set he defined text at "Post content" will shown.','wp-users-pro')
       );


$this->create_plugin_setting(
        'textarea',
        'uultra_loggedin_post_content',
        __('Post Content','wp-users-pro'),array(),
        __('This content will be displayed if user has no access. ','wp-users-pro'),
        __('This content will be displayed if user has no access. ','wp-users-pro')
);


$this->create_plugin_setting(
	'select',
	'uultra_loggedin_hide_post_comments',
	__('Hide Post Comments?','wp-users-pro'),
	array(
		'no' => __('No','wp-users-pro'), 
		'yes' => __('Yes','wp-users-pro'),
		),
		
	__("By selecting 'yes' will show the text which is defined at 'Post comment text' if user has no access.",'wp-users-pro'),
  __("By selecting 'yes' will show the text which is defined at 'Post comment text' if user has no access.",'wp-users-pro')
       );
	  
$this->create_plugin_setting( 
        'input',
        'uultra_loggedin_post_comment_content',
        __('Post Comment Text:','wp-users-pro'),array(),
        __('This will be displayed text as post comment text if user has no access.','wp-users-pro'),
        __('This will be displayed text as post comment text if user has no access.','wp-users-pro')
);  
$this->create_plugin_setting(
	'select',
	'uultra_loggedin_allow_post_comments',
	__('Allows Post Comments?','wp-users-pro'),
	array(
		'no' => __('No','wp-users-pro'), 
		'yes' => __('Yes','wp-users-pro'),
		),
		
	__("By selecting 'yes' allows users to comment on locked posts",'wp-users-pro'),
  __("By selecting 'yes' allows users to comment on locked posts",'wp-users-pro')
       );	  
		

?>
</table>
 

 <h4><?php _e("Set up the behaviour of locked pages.",'wp-users-pro'); ?></h4>
  <table class="form-table">
<?php 


$this->create_plugin_setting(
	'select',
	'uultra_loggedin_hide_complete_page',
	__('Hide Complete Pages?','wp-users-pro'),
	array(
		'no' => __('No','wp-users-pro'), 
		'yes' => __('Yes','wp-users-pro'),
		),
		
	__("By selecting 'yes' will hide pages if the user has no access. <strong>Please note: </strong> a 404 error message will be displayed since the page will be completely locked out.",'wp-users-pro'),
  __("By selecting 'yes' will hide pages if the user has no access",'wp-users-pro')
       );

$this->create_plugin_setting(
	'select',
	'uultra_loggedin_hide_page_title',
	__('Hide Page Title?','wp-users-pro'),
	array(
		'no' => __('No','wp-users-pro'), 
		'yes' => __('Yes','wp-users-pro'),
		),
		
	__("By selecting 'yes' will show the text which is defined at 'Page title' if user has no access.",'wp-users-pro'),
  __("By selecting 'yes' will show the text which is defined at 'Page title' if user has no access.",'wp-users-pro')
       );
	   
$this->create_plugin_setting( 
        'input',
        'uultra_loggedin_page_title',
        __('Page Title:','wp-users-pro'),array(),
        __('This will be the displayed text as page title if user has no access.','wp-users-pro'),
        __('This will be the displayed text as page title if user has no access.','wp-users-pro')
);  


$this->create_plugin_setting(
        'textarea',
        'uultra_loggedin_page_content',
        __('Page Content','wp-users-pro'),array(),
        __('This content will be displayed if user has no access. ','wp-users-pro'),
        __('This content will be displayed if user has no access. ','wp-users-pro')
);


$this->create_plugin_setting(
	'select',
	'uultra_loggedin_hide_page_comments',
	__('Hide Page Comments?','wp-users-pro'),
	array(
		'no' => __('No','wp-users-pro'), 
		'yes' => __('Yes','wp-users-pro'),
		),
		
	__("By selecting 'yes' will show the text which is defined at 'Page comment text' if user has no access.",'wp-users-pro'),
  __("By selecting 'yes' will show the text which is defined at 'Page comment text' if user has no access.",'wp-users-pro')
       );
	  
	  
	  	  
$this->create_plugin_setting( 
        'input',
        'uultra_loggedin_page_comment_content',
        __('Page Comment Text:','wp-users-pro'),array(),
        __('This will be displayed text as page comment text if user has no access.','wp-users-pro'),
        __('This will be displayed text as page comment text if user has no access.','wp-users-pro')
);  
$this->create_plugin_setting(
	'select',
	'uultra_loggedin_allow_page_comments',
	__('Allows Page Comments?','wp-users-pro'),
	array(
		'no' => __('No','wp-users-pro'), 
		'yes' => __('Yes','wp-users-pro'),
		),
		
	__("By selecting 'yes' allows users to comment on locked pages",'wp-users-pro'),
  __("By selecting 'yes' allows users to comment on locked pages",'wp-users-pro')
       );	 
  
		
?>
</table>

<h4><?php _e("Other Settings.",'wp-users-pro'); ?></h4>
  <table class="form-table">
<?php 



$this->create_plugin_setting(
	'select',
	'uultra_loggedin_protect_feed',
	__('Hide Post Feed?','wp-users-pro'),
	array(
		'no' => __('No','wp-users-pro'), 
		'yes' => __('Yes','wp-users-pro'),
		),
		
	__("By selecting 'yes' will show the text which is defined at 'Post title' if user has no access.",'wp-users-pro'),
  __("By selecting 'yes' will show the text which is defined at 'Post title' if user has no access.",'wp-users-pro')
       );
	   
  
		
?>
</table>
  
</div>


<p class="submit">
	<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes','wp-users-pro'); ?>"  />
</p>


</div>



<div id="tabs-bup-newsletter">
  
  <?php if(isset($wpuserspro_aweber) || isset($wpuserspro_mailchimp))
{?>


<div class="easywpmembers-sect easywpmembers-welcome-panel ">
<h3><?php _e('Newsletter Preferences','wp-users-pro'); ?></h3>
  
  <p><?php _e('Here you can activate your preferred newsletter tool.','wp-users-pro'); ?></p>

<table class="form-table">
<?php 
   
$this->create_plugin_setting(
	'select',
	'newsletter_active',
	__('Activate Newsletter','wp-users-pro'),
	array(
		'no' => __('No','wp-users-pro'), 
		'aweber' => __('AWeber','wp-users-pro'),
		'mailchimp' => __('MailChimp','wp-users-pro'),
		),
		
	__('Just set "NO" to deactivate the newsletter tool.','wp-users-pro'),
  __('Just set "NO" to deactivate the newsletter tool.','wp-users-pro')
       );

	
?>
</table>

<p class="submit">
	<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes','wp-users-pro'); ?>"  />
</p>


</div>


<?php }else{?>


<div class="easywpmembers-sect  easywpmembers-welcome-panel">

<p><?php _e('This function is available only on certain versions.','wp-users-pro'); ?>. Click <a href="https://wpuserspro.com/compare-packages.php">here</a> to compare packages </p>


</div>

<?php }?> 
  <?php if(isset($wpuserspro_aweber))
{?>


<div class="easywpmembers-sect easywpmembers-welcome-panel ">
<h3><?php _e('Aweber Settings','wp-users-pro'); ?></h3>
  
  <p><?php _e('This module gives you the capability to subscribe your clients automatically to any of your Aweber List when they submit a ticket.','wp-users-pro'); ?></p>
  
  
<table class="form-table">
<?php 
   
		
$this->create_plugin_setting(
        'input',
        'aweber_app_id',
        __('APP ID','wp-users-pro'),array(),
        __('Fill out this field with your AWeber APP ID.','wp-users-pro'),
        __('Fill out this field with your AWeber APP ID.','wp-users-pro')
);

$this->create_plugin_setting(
        'input',
        'aweber_consumer_key',
        __('Consumer Key','wp-users-pro'),array(),
        __('Fill out this field your AWeber Consumer Key.','wp-users-pro'),
        __('Fill out this field your AWeber Consumer Key.','wp-users-pro')
);

$this->create_plugin_setting(
        'input',
        'aweber_consumer_secret',
        __('Consumer Secret','wp-users-pro'),array(),
        __('Fill out this field your AWeber Consumer Secret.','wp-users-pro'),
        __('Fill out this field your AWeber Consumer Secret.','wp-users-pro')
);




$this->create_plugin_setting(
                'checkbox',
                'aweber_auto_text',
                __('Auto Checked Aweber','wp-users-pro'),
                '1',
                __('If checked, the user will not need to click on the AWeber checkbox. It will appear checked already.','wp-users-pro'),
                __('If checked, the user will not need to click on the AWeber checkbox. It will appear checked already.','wp-users-pro')
        );
$this->create_plugin_setting(
        'input',
        'aweber_text',
        __('Aweber Text','wp-users-pro'),array(),
        __('Please input the text that will appear when asking users to get periodical updates.','wp-users-pro'),
        __('Please input the text that will appear when asking users to get periodical updates.','wp-users-pro')
);

	$this->create_plugin_setting(
        'input',
        'aweber_header_text',
        __('Aweber Header Text','wp-users-pro'),array(),
        __('Please input the text that will appear as header when AWeber is active.','wp-users-pro'),
        __('Please input the text that will appear as header when AWeber is active.','wp-users-pro')
);
	
?>
</table>

<p class="submit">
	<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes','wp-users-pro'); ?>"  />
</p>


</div>

<?php }?> 


  <?php if(isset($wpuserspro_mailchimp))
{?>


<div class="easywpmembers-sect easywpmembers-welcome-panel ">
<h3><?php _e('MailChimp Settings','wp-users-pro'); ?></h3>
  
  <p><?php _e('.','wp-users-pro'); ?></p>
  
  
<table class="form-table">
<?php 
   
		
$this->create_plugin_setting(
        'input',
        'mailchimp_api',
        __('MailChimp API Key','wp-users-pro'),array(),
        __('Fill out this field with your MailChimp API key here to allow integration with MailChimp subscription.','wp-users-pro'),
        __('Fill out this field with your MailChimp API key here to allow integration with MailChimp subscription.','wp-users-pro')
);

$this->create_plugin_setting(
        'input',
        'mailchimp_list_id',
        __('MailChimp List ID','wp-users-pro'),array(),
        __('Fill out this field your list ID.','wp-users-pro'),
        __('Fill out this field your list ID.','wp-users-pro')
);



$this->create_plugin_setting(
                'checkbox',
                'mailchimp_auto_checked',
                __('Auto Checked MailChimp','wp-users-pro'),
                '1',
                __('If checked, the user will not need to click on the mailchip checkbox. It will appear checked already.','wp-users-pro'),
                __('If checked, the user will not need to click on the mailchip checkbox. It will appear checked already.','wp-users-pro')
        );
$this->create_plugin_setting(
        'input',
        'mailchimp_text',
        __('MailChimp Text','wp-users-pro'),array(),
        __('Please input the text that will appear when asking users to get periodical updates.','wp-users-pro'),
        __('Please input the text that will appear when asking users to get periodical updates.','wp-users-pro')
);

	$this->create_plugin_setting(
        'input',
        'mailchimp_header_text',
        __('MailChimp Header Text','wp-users-pro'),array(),
        __('Please input the text that will appear as header when mailchip is active.','wp-users-pro'),
        __('Please input the text that will appear as header when mailchip is active.','wp-users-pro')
);
	
?>
</table>

<p class="submit">
	<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes','wp-users-pro'); ?>"  />
</p>


</div>



<?php }?>  
  
  


</div>



</div>




</form>