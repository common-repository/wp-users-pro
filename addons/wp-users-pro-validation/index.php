<?php
/*
Plugin Name: WP Users Pro Licensing (Add-on)
Plugin URI: https://wpuserspro.com
Description: Add-on for for WP Users Pro. Allows you to validate your copy.
Version: 1.0.0
Author: WP Users Pro
Author URI: https://wpuserspro.com/
*/

define('wpuserspro_validation_url',plugin_dir_url(__FILE__ ));
define('wpuserspro_validation_path',plugin_dir_path(__FILE__ ));

$plugin = plugin_basename(__FILE__);


/* Master Class  */
require_once (wpuserspro_validation_path . 'classes/wpuserspro.validation.class.php');

register_activation_hook( __FILE__, 'wpuserspro_validation');

function wpuserspro_validation( $network_wide ) 
{
	$plugin = "wp-users-pro-validaton/index.php";
	$plugin_path = '';	
	
	if ( is_multisite() && $network_wide ) // See if being activated on the entire network or one blog
	{ 
		activate_plugin($plugin_path,NULL,true);			
		
	} else { // Running on a single blog		   	
			
		activate_plugin($plugin_path,NULL,false);		
		
	}
}
global $wpuserspro_activation;
$wpuserspro_activation = new WPUsersProValidation();