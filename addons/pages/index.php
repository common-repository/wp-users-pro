<?php
global $wpuserspro;

define('wpuserspro_profiles_url',plugin_dir_url(__FILE__ ));
define('wpuserspro_profiles_path',plugin_dir_path(__FILE__ ));

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(isset($wpuserspro)){

	/* administration */
	if (is_admin()){
		foreach (glob(wpuserspro_profiles_path . 'admin/*.php') as $filename) { include $filename; }
	}
	
}