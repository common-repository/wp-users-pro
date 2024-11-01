<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpuserspro;
 $label_pro ="";

?>
<h3><?php _e('Advanced Email Options','wp-users-pro'); ?></h3>
<form method="post" action="" id="b_frm_settings" name="b_frm_settings">
<input type="hidden" name="wpuserspro_update_settings" />
<input type="hidden" name="easywpmembers_reset_email_template" id="easywpmembers_reset_email_template" />
<input type="hidden" name="email_template" id="email_template" />


  <p><?php _e('Here you can control how WP Users Pro will send the notification to your users.','wp-users-pro'); ?></p>


<div class="easywpmembers-sect  easywpmembers-welcome-panel">  
   <table class="form-table">
<?php 
 

$this->create_plugin_setting(
        'input',
        'messaging_send_from_name',
        __('Send From Name','wp-users-pro'),array(),
        __('Enter the your name or company name here.','wp-users-pro'),
        __('Enter the your name or company name here.','wp-users-pro')
);

$this->create_plugin_setting(
        'input',
        'messaging_send_from_email',
        __('Send From Email','wp-users-pro'),array(),
        __('Enter the email address to be used when sending emails.','wp-users-pro'),
        __('Enter the email address to be used when sending emails.','wp-users-pro')
);

$this->create_plugin_setting(
	'select',
	'bup_smtp_mailing_mailer',
	__('Mailer:','wp-users-pro'),
	array(
		'mail' => __('Use the PHP mail() function to send emails','wp-users-pro'),
		'smtp' => __('Send all emails via SMTP','wp-users-pro'), 
		'mandrill' => __('Send all emails via Mandrill','wp-users-pro'),
		'third-party' => __('Send all emails via Third-party plugin','wp-users-pro'), 
		
		),
		
	__('Specify which mailer method the pluigin should use when sending emails.','wp-users-pro'),
  __('Specify which mailer method the pluigin should use when sending emails.','wp-users-pro')
       );
	   
$this->create_plugin_setting(
                'checkbox',
                'bup_smtp_mailing_return_path',
                __('Return Path','wp-users-pro'),
                '1',
                __('Set the return-path to match the From Email','wp-users-pro'),
                __('Set the return-path to match the From Email','wp-users-pro')
        ); 
?>
 </table>

 
 </div>
 
 
 
 <div class="easywpmembers-sect  easywpmembers-welcome-panel">
 
 <h3><?php _e('SMTP Settings','wp-users-pro'); ?></h3>
  <p> <strong><?php _e('This options should be set only if you have chosen to send email via SMTP','wp-users-pro'); ?></strong></p>
 
  <table class="form-table">
 <?php
$this->create_plugin_setting(
        'input',
        'bup_smtp_mailing_host',
        __('SMTP Host:','wp-users-pro'),array(),
        __('Specify host name or ip address.','wp-users-pro'),
        __('Specify host name or ip address.','wp-users-pro')
); 

$this->create_plugin_setting(
        'input',
        'bup_smtp_mailing_port',
        __('SMTP Port:','wp-users-pro'),array(),
        __('Specify Port.','wp-users-pro'),
        __('Specify Port.','wp-users-pro')
); 


$this->create_plugin_setting(
	'select',
	'bup_smtp_mailing_encrytion',
	__('Encryption:','wp-users-pro'),
	array(
		'none' => __('No encryption','wp-users-pro'),
		'ssl' => __('Use SSL encryption','wp-users-pro'), 
		'tls' => __('Use TLS encryption','wp-users-pro'), 
		
		),
		
	__('Specify the encryption method.','wp-users-pro'),
  __('Specify the encryption method.','wp-users-pro')
       );
	   
$this->create_plugin_setting(
	'select',
	'bup_smtp_mailing_authentication',
	__('Authentication:','wp-users-pro'),
	array(
		'false' => __('No. Do not use SMTP authentication','wp-users-pro'),
		'true' => __('Yes. Use SMTP Authentication','wp-users-pro'), 
		
		),
		
	__('Specify the authentication method.','wp-users-pro'),
  __('Specify the authentication method.','wp-users-pro')
       );

$this->create_plugin_setting(
        'input',
        'bup_smtp_mailing_username',
        __('Username:','wp-users-pro'),array(),
        __('Specify Username.','wp-users-pro'),
        __('Specify Username.','wp-users-pro')
); 

$this->create_plugin_setting(
        'input',
        'bup_smtp_mailing_password',
        __('Password:','wp-users-pro'),array(),
        __('Input Password.','wp-users-pro'),
        __('Input Password.','wp-users-pro')
); 


 ?>
 
 </table>
 
 
 </div>
 



<div class="easywpmembers-sect  easywpmembers-welcome-panel">
  <h3><?php _e('User Registration Email','wp-users-pro'); ?> <?php echo $label_pro?> <span class="easywpmembers-main-close-open-tab"><a href="#" title="<?php _e('Close','wp-users-pro'); ?>" class="easywpmembers-widget-home-colapsable" widget-id="666"><i class="fa fa-sort-desc" id="easywpmembers-close-open-icon-666"></i></a></span></h3>
  
  <p><?php _e('This message will be sent to the user and it includes the password.','wp-users-pro'); ?></p>
<div class="easywpmembers-messaging-hidden" id="easywpmembers-main-cont-home-666">  
  
   <table class="form-table">

<?php 


$this->create_plugin_setting(
        'input',
        'email_registration_subject',
        __('Subject:','wp-users-pro'),array(),
        __('Set Email Subject.','wp-users-pro'),
        __('Set Email Subject.','wp-users-pro')
); 

$this->create_plugin_setting(
        'textarearich',
        'email_registration_body',
        __('Message','wp-users-pro'),array(),
        __('Set Email Message here.','wp-users-pro'),
        __('Set Email Message here.','wp-users-pro')
);
	
?>

<tr>

<th></th>
<td><input type="button" value="<?php _e('RESTORE DEFAULT TEMPLATE','wp-users-pro'); ?>" class="easywpmembers_restore_template button" b-template-id='email_registration_body'></td>

</tr>	
</table> 
</div>


</div>



<div class="easywpmembers-sect  easywpmembers-welcome-panel">
  <h3><?php _e('Members Password Reset','wp-users-pro'); ?> <span class="easywpmembers-main-close-open-tab"><a href="#" title="<?php _e('Close','wp-users-pro'); ?>" class="easywpmembers-widget-home-colapsable" widget-id="20123"><i class="fa fa-sort-desc" id="easywpmembers-close-open-icon-20123"></i></a></span></h3>
  
  <p><?php _e('This message is sent when the password is changed by the members on the front-end','wp-users-pro'); ?></p>
<div class="easywpmembers-messaging-hidden" id="easywpmembers-main-cont-home-20123">  
  
   <table class="form-table">

<?php 


$this->create_plugin_setting(
        'input',
        'email_password_change_member_subject',
        __('Subject:','wp-users-pro'),array(),
        __('Set Email Subject.','wp-users-pro'),
        __('Set Email Subject.','wp-users-pro')
); 

$this->create_plugin_setting(
        'textarearich',
        'email_password_change_member_body',
        __('Message','wp-users-pro'),array(),
        __('Set Email Message here.','wp-users-pro'),
        __('Set Email Message here.','wp-users-pro')
);
	
?>

<tr>

<th></th>
<td><input type="button" value="<?php _e('RESTORE DEFAULT TEMPLATE','wp-users-pro'); ?>" class="easywpmembers_restore_template button" b-template-id='email_password_change_member_body'></td>

</tr>	
</table> 
</div>


</div>

<div class="easywpmembers-sect  easywpmembers-welcome-panel">
  <h3><?php _e('Subscription - User Email New Subscription Purchase','wp-users-pro'); ?> <?php echo $label_pro?> <span class="easywpmembers-main-close-open-tab"><a href="#" title="<?php _e('Close','wp-users-pro'); ?>" class="easywpmembers-widget-home-colapsable" widget-id="669"><i class="fa fa-sort-desc" id="easywpmembers-close-open-icon-669"></i></a></span></h3>
  
  <p><?php _e('This message will be sent to the user when purchasing a new package within his/her account.','wp-users-pro'); ?></p>
<div class="easywpmembers-messaging-hidden" id="easywpmembers-main-cont-home-669">  
  
   <table class="form-table">

<?php 


$this->create_plugin_setting(
        'input',
        'email_package_upgrade_subject',
        __('Subject:','wp-users-pro'),array(),
        __('Set Email Subject.','wp-users-pro'),
        __('Set Email Subject.','wp-users-pro')
); 

$this->create_plugin_setting(
        'textarearich',
        'email_package_upgrade_body',
        __('Message','wp-users-pro'),array(),
        __('Set Email Message here.','wp-users-pro'),
        __('Set Email Message here.','wp-users-pro')
);
	
?>

<tr>

<th></th>
<td><input type="button" value="<?php _e('RESTORE DEFAULT TEMPLATE','wp-users-pro'); ?>" class="easywpmembers_restore_template button" b-template-id='email_package_upgrade_body'></td>

</tr>	
</table> 
</div>


</div>


<div class="easywpmembers-sect  easywpmembers-welcome-panel">
  <h3><?php _e('Subscription - Admin Email New Subscription Purchase','wp-users-pro'); ?> <?php echo $label_pro?> <span class="easywpmembers-main-close-open-tab"><a href="#" title="<?php _e('Close','wp-users-pro'); ?>" class="easywpmembers-widget-home-colapsable" widget-id="667"><i class="fa fa-sort-desc" id="easywpmembers-close-open-icon-667"></i></a></span></h3>
  
  <p><?php _e('This message will be sent to the admin when a client buys a new package within his/her account.','wp-users-pro'); ?></p>
<div class="easywpmembers-messaging-hidden" id="easywpmembers-main-cont-home-667">  
  
   <table class="form-table">

<?php 


$this->create_plugin_setting(
        'input',
        'email_package_upgrade_admin_subject',
        __('Subject:','wp-users-pro'),array(),
        __('Set Email Subject.','wp-users-pro'),
        __('Set Email Subject.','wp-users-pro')
); 

$this->create_plugin_setting(
        'textarearich',
        'email_package_upgrade_admin_body',
        __('Message','wp-users-pro'),array(),
        __('Set Email Message here.','wp-users-pro'),
        __('Set Email Message here.','wp-users-pro')
);
	
?>

<tr>

<th></th>
<td><input type="button" value="<?php _e('RESTORE DEFAULT TEMPLATE','wp-users-pro'); ?>" class="easywpmembers_restore_template button" b-template-id='email_package_upgrade_admin_body'></td>

</tr>	
</table> 
</div>


</div>


<div class="easywpmembers-sect  easywpmembers-welcome-panel">
  <h3><?php _e('Subscription - Admin Email Subscription Renewal','wp-users-pro'); ?> <?php echo $label_pro?> <span class="easywpmembers-main-close-open-tab"><a href="#" title="<?php _e('Close','wp-users-pro'); ?>" class="easywpmembers-widget-home-colapsable" widget-id="567"><i class="fa fa-sort-desc" id="easywpmembers-close-open-icon-567"></i></a></span></h3>
  
  <p><?php _e('This message will be sent to the admin when a subscription is renewed.','wp-users-pro'); ?></p>
<div class="easywpmembers-messaging-hidden" id="easywpmembers-main-cont-home-567">  
  
   <table class="form-table">

<?php 


$this->create_plugin_setting(
        'input',
        'email_package_renewal_admin_subject',
        __('Subject:','wp-users-pro'),array(),
        __('Set Email Subject.','wp-users-pro'),
        __('Set Email Subject.','wp-users-pro')
); 

$this->create_plugin_setting(
        'textarearich',
        'email_package_renewal_admin_body',
        __('Message','wp-users-pro'),array(),
        __('Set Email Message here.','wp-users-pro'),
        __('Set Email Message here.','wp-users-pro')
);
	
?>

<tr>

<th></th>
<td><input type="button" value="<?php _e('RESTORE DEFAULT TEMPLATE','wp-users-pro'); ?>" class="easywpmembers_restore_template button" b-template-id='email_package_renewal_admin_body'></td>

</tr>	
</table> 
</div>


</div>

<div class="easywpmembers-sect  easywpmembers-welcome-panel">
  <h3><?php _e('Subscription - Client Email Subscription Renewal','wp-users-pro'); ?> <?php echo $label_pro?> <span class="easywpmembers-main-close-open-tab"><a href="#" title="<?php _e('Close','wp-users-pro'); ?>" class="easywpmembers-widget-home-colapsable" widget-id="568"><i class="fa fa-sort-desc" id="easywpmembers-close-open-icon-568"></i></a></span></h3>
  
  <p><?php _e('This message will be sent to the client when a subscription is renewd.','wp-users-pro'); ?></p>
<div class="easywpmembers-messaging-hidden" id="easywpmembers-main-cont-home-568">  
  
   <table class="form-table">

<?php 


$this->create_plugin_setting(
        'input',
        'email_package_renewal_subject',
        __('Subject:','wp-users-pro'),array(),
        __('Set Email Subject.','wp-users-pro'),
        __('Set Email Subject.','wp-users-pro')
); 

$this->create_plugin_setting(
        'textarearich',
        'email_package_renewal_body',
        __('Message','wp-users-pro'),array(),
        __('Set Email Message here.','wp-users-pro'),
        __('Set Email Message here.','wp-users-pro')
);
	
?>

<tr>

<th></th>
<td><input type="button" value="<?php _e('RESTORE DEFAULT TEMPLATE','wp-users-pro'); ?>" class="easywpmembers_restore_template button" b-template-id='email_package_renewal_body'></td>

</tr>	
</table> 
</div>


</div>











<p class="submit">
	<input type="submit" name="mail_setting_submit" id="mail_setting_submit" class="button button-primary" value="<?php _e('Save Changes','wp-users-pro'); ?>"  />

</p>

</form>