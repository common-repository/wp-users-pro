<?php
global $wpuserspro, $wpuserspro_staff_profile;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<form method="post" action="">
<input type="hidden" name="update_settings" />

<div class="easywpmembers-sect  easywpmembers-welcome-panel ">

 <h3><?php _e('WP Users Pro Pages','wp-users-pro'); ?></h3>
        
              <p><?php _e('Here you can set your custom pages for the members.','wp-users-pro'); ?></p>
        
  <table class="form-table">
<?php 



	$wpuserspro->admin->create_plugin_setting(
            'select',
            'registration_page',
            __('Registration Page','wp-users-pro'),
            $wpuserspro->admin->get_all_sytem_pages(),
            __('Make sure you have the <code>[wpuserspro_user_signup]</code> shortcode on this page.','wp-users-pro'),
            __('This page is where users will be able to sign up to your website.','wp-users-pro')
    );

	
	$wpuserspro->admin->create_plugin_setting(
            'select',
            'my_account_page',
            __('My Account Page','wp-users-pro'),
            $wpuserspro->admin->get_all_sytem_pages(),
            __('Make sure you have the <code>[wpuserspro_account]</code> shortcode on this page.','wp-users-pro'),
            __('This page is where users and staff members will be able to manage their appointments.','wp-users-pro')
    );
	
	$wpuserspro->admin->create_plugin_setting(
            'select',
            'user_login_page',
            __('Users Login Page','wp-users-pro'),
            $wpuserspro->admin->get_all_sytem_pages(),
            __('Make sure you have the <code>[wpuserspro_user_login]</code> shortcode on this page.','wp-users-pro'),
            __('This page is where users and staff members & clients will be able to recover to login to their accounts.','wp-users-pro')
    );
	
	
		$wpuserspro->admin->create_plugin_setting(
            'select',
            'password_reset_page',
            __('Password Recover Page','wp-users-pro'),
            $wpuserspro->admin->get_all_sytem_pages(),
            __('Make sure you have the <code>[wpuserspro_user_recover_password]</code> shortcode on this page.','wp-users-pro'),
            __('This page is where users and staff members will be able to recover their passwords.','wp-users-pro')
    );
	
	
			
	$wpuserspro->admin->create_plugin_setting(
	'select',
	'hide_admin_bar',
	__('Hide WP Admin Tool Bar?','wp-users-pro'),
	array(
		0 => __('NO','wp-users-pro'), 		
		1 => __('YES','wp-users-pro')),
		
	__('If checked, User will not see the WP Admin Tool Bar','wp-users-pro'),
  __('If checked, User will not see the WP Admin Tool Bar','wp-users-pro')
       );
	   
	     
	
	   
		
?>
</table>      
   

             

</div>

<p class="submit">
	<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes','wp-users-pro'); ?>"  />
	
</p>

</form>

