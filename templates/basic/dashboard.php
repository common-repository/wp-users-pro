<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpuserspro ;

$current_user = $wpuserspro->user->get_user_info();

$user_id = $current_user->ID;
$user_email = $current_user->user_email;
$howmany = 5;

$currency_symbol =  $wpuserspro->get_option('paid_membership_symbol');
$date_format =  $wpuserspro->get_int_date_format();
$time_format =  $wpuserspro->get_time_format();
$datetime_format =  $wpuserspro->get_date_to_display();


$module = "main";
$act= "";
$view= "";
$reply= "";


if(isset($_GET["module"])){	$module =  sanitize_text_field($_GET["module"]);	}
if(isset($_GET["act"])){$act =  sanitize_text_field($_GET["act"]);	}
if(isset($_GET["view"])){	$view =  sanitize_text_field($_GET["view"]);}
if(isset($_GET["reply"])){	$reply =  sanitize_text_field($_GET["reply"]);}


?>
<div class="easywpm-user-dahsboard-cont">


<?php //include header

echo $wpuserspro->profile->get_user_header();

?>


	
    <div class="easywpm-centered-cont">
    
    
		<?php
    
            echo $wpuserspro->profile->get_template_part($module);
        
        ?>
    
    
    </div>
   

</div>


	