<?php
/*
Plugin Name: WP Users Pro
Plugin URI: https://wpuserspro.com
Description: Users & Subscriptions Plugin. Recurring Payments, PayPal, Strip. Partial and Full content Protection. Protect Pages, Posts, Images.
Version: 1.1.2
Author: WP Users Pro
Text Domain: wp-users-pro
Domain Path: /languages
Author URI: https://wpuserspro.com/
*/

define('wpuserspro_url',plugin_dir_url(__FILE__ ));
define('wpuserspro_path',plugin_dir_path(__FILE__ ));
define('WPUPRO_PLUGIN_SETTINGS_URL',"?page=wpuserspro&tab=main");
define('WPUPRO_PLUGIN_WELCOME_URL',"?page=wpuserspro&tab=welcome");

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$plugin = plugin_basename(__FILE__);

/* Loading Function */
require_once (wpuserspro_path . 'functions/functions.php');

/* Init */
define('wpuserspro_pro_url','https://wpuserspro.com/');


function wpuserspro_load_textdomain() 
{     	  
	   $locale = apply_filters( 'plugin_locale', get_locale(), 'wp-users-pro' );	   
       $mofile = wpuserspro_path . "languages/wp-users-pro-$locale.mo";
			
		// Global + Frontend Locale
		load_textdomain( 'wp-users-pro', $mofile );
		load_plugin_textdomain( 'wp-users-pro', false, dirname(plugin_basename(__FILE__)).'/languages/' );
}

/* Load plugin text domain (localization) */
add_action('init', 'wpuserspro_load_textdomain');	
		
/* Master Class  */
require_once (wpuserspro_path . 'classes/wpuserspro.class.php');

// Helper to activate a plugin on another site without causing a fatal error by
register_activation_hook( __FILE__, 'wpuserspro_activation');
 
function  wpuserspro_activation( $network_wide ) 
{
	$plugin_path = '';
	$plugin = "wp-users-pro/index.php";	
	
	if ( is_multisite() && $network_wide ) // See if being activated on the entire network or one blog
	{ 
		activate_plugin($plugin_path,NULL,true);
			
	} else { // Running on a single blog		   	
			
		activate_plugin($plugin_path,NULL,false);		
		
	}
}

$wpuserspro = new WPUsersPro();
$wpuserspro->plugin_init();

register_activation_hook(__FILE__, 'wpuserspro_my_plugin_activate');
add_action('admin_init', 'wpuserspro_my_plugin_redirect');

function wpuserspro_my_plugin_activate() 
{
    add_option('wpuserspro_plugin_do_activation_redirect', true);
}

function wpuserspro_my_plugin_deactivate() 
{

}

function wpuserspro_my_plugin_redirect() 
{
    if (get_option('wpuserspro_plugin_do_activation_redirect', false)) {
        delete_option('wpuserspro_plugin_do_activation_redirect');
		
		if (! get_option('wpuserspro_ini_setup')) 
		{
			wp_redirect(WPUPRO_PLUGIN_WELCOME_URL);
        
		}else{
				
			wp_redirect(WPUPRO_PLUGIN_WELCOME_URL);
			
		}
    }
}

require_once wpuserspro_path . 'addons/pages/index.php';
require_once wpuserspro_path . 'addons/wp-users-pro-validation/index.php';