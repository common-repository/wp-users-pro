<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpuserspro, $wpuserspro_wooco ;

$current_user = $wpuserspro->user->get_user_info();
$user_id = $current_user->ID;

$currency_symbol =  $wpuserspro->get_option('paid_membership_symbol');
$date_format =  $wpuserspro->get_int_date_format();
$time_format =  $wpuserspro->get_time_format();
$datetime_format =  $wpuserspro->get_date_to_display();

$active_subscriptions =  $wpuserspro->user->get_my_active_subscriptions($user_id);

?>

<h1><?php _e('Order Details','wp-users-pro')?></h1>


    
<div class="easywpm-main-app-list">

     
    <?php echo  $wpuserspro_wooco->frm_order_details(); ?>
</div>
