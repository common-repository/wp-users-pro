<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpuserspro ;

$current_user = $wpuserspro->user->get_user_info();
$user_id = $current_user->ID;

$currency_symbol =  $wpuserspro->get_option('paid_membership_symbol');
$date_format =  $wpuserspro->get_int_date_format();
$time_format =  $wpuserspro->get_time_format();
$datetime_format =  $wpuserspro->get_date_to_display();
$active_subscriptions =  $wpuserspro->user->get_my_active_subscriptions($user_id);


?>

<h1><?php _e('Dashboard','wp-users-pro')?></h1>

<h2><?php _e('Active Subscriptions','wp-users-pro')?> <span class="easywpm-widget-backend-colspan"><a href="#" title="<?php _e('Close','wp-users-pro')?> " class="easywpm-widget-backend-colapsable" widget-id="0"><i class="fa fa-sort-asc" id="easywpm-close-open-icon-0"></i></a></span></h2>
    
<div class="easywpm-main-app-list" id="easywpm-backend-landing-1">


 <?php	if (!empty($active_subscriptions)){ ?>
       
           <table width="100%" class="">
            <thead>
                <tr>
                    <th width="4%" ><?php _e('#', 'easy-wp-members'); ?></th>
                    <th width="13%"><?php _e('Started', 'easy-wp-members'); ?></th> 
                     <th width="13%"><?php _e('Name', 'easy-wp-members'); ?></th>
                     <th width="14%"><?php _e('Valid From', 'easy-wp-members'); ?></th>
                    <th width="14%"><?php _e('Valid To', 'easy-wp-members'); ?></th>                   
                     <th width="14%" ><?php _e('Status', 'easy-wp-members'); ?></th>
                    <th width="7%"><?php _e('Actions', 'easy-wp-members'); ?></th>
                </tr>
            </thead>
            
            <tbody>
            
            <?php 
			$filter_name= '';
			$phone= '';
			foreach($active_subscriptions as $subscription) {
				
				$date_created=  date($date_format, strtotime($subscription->subscription_date ));
				
				$date_from=  date($date_format, strtotime($subscription->subscription_start_date  ));
				$date_to=  date($date_format, strtotime($subscription->subscription_end_date  ));
				
				if($subscription->subscription_lifetime==1)		
				{
					$date_to=__("Lifetime",'wp-users-pro');
					
				}
				
				
			?>
              

                <tr>
                    <td ><?php echo $subscription->subscription_id; ?></td>
                     <td><?php echo $date_created; ?>   </td>     
                      <td ><?php echo $subscription->membership_name; ?> </td>
                      <td ><?php echo $date_from; ?></td>
                      <td><?php echo $date_to; ?> </td>                      
                      <td ><?php echo $wpuserspro->membership->get_subscription_status_legend($subscription->subscription_status); ?></td>
                      <td> <a href="?module=subscription_detail&id=<?php echo $subscription->subscription_id?>" class="wptu-appointment-edit-module" appointment-id="<?php echo  $subscription->subscription_id?>" title="<?php _e('Edit','wp-users-pro'); ?>"><i class="fa fa-edit"></i></a>
                   
                                 
                   
                   </td>
                </tr>
                
                
                <?php
					}
					
					} else {
			?>
			<p><?php _e("You don't have active subscriptions",'wp-users-pro'); ?></p>
			<?php	} ?>

            </tbody>
        </table>
        

</div>
