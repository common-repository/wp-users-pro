<?php
// General Functions for Plugin

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!defined('PHP_EOL')) {
    switch (strtoupper(substr(PHP_OS, 0, 3))) {
        // Windows
        case 'WIN':
            define('PHP_EOL', "\r\n");
			//echo "IS WINDOW SERVER";
            break;

        // Mac
        case 'DAR':
            define('PHP_EOL', "\r");
            break;

        // Unix
        default:
            define('PHP_EOL', "\n");
    }
}

//echo "OSD: " .PHP_OS;






function wpuserspro_not_null($value)
{
	if (is_array($value))
	{
		if (sizeof($value) > 0)
			return true;
		else
			return false;
	}
	else
	{
		if ( (is_string($value) || is_int($value)) && ($value != '') && ($value != 'NULL') && (strlen(trim($value)) > 0))
			return true;
		else
			return false;
	}
} 




function wpuserspro_get_value($key='')
{
	if($key!='')
	{
        $val_key =  sanitize_text_field($_GET[$key]);
        
		if(isset($val_key) && not_null($val_key))
		{
			if(!is_array($val_key))
				return trim($val_key);
			else
				return $val_key;
		}

		else
			return '';
	}
	else
		return '';
}





function wpuserspro_post_value($key='')
{
	if($key!='')
	{
        
        $val_key =  sanitize_text_field($_POST[$key]);
        
		if(isset($val_key) && not_null($val_key))
		{
			if(!is_array($val_key))
				return trim($val_key);
			else
				return $val_key;
		}
		else
			return '';
	}
	else
		return '';
}

