<?php
global $wpuserspro;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$currency_symbol =  $wpuserspro->get_option('paid_membership_symbol');
$date_format =  $wpuserspro->get_int_date_format();
$time_format =  $wpuserspro->get_time_format();
$datetime_format =  $wpuserspro->get_date_to_display();

if(isset($_GET['id']) && $_GET['id']!=''){
	
	$user_id = sanitize_text_field($_GET['id']);
	
	// Get user
	$user = get_user_by( 'id', $user_id );	
	
	if(!isset($user->ID)){
		
		 echo '<div class="easywpmembers-ultra-warning"><span><i class="fa fa-check"></i>'.__("Oops! Invalid User.",'wp-users-pro').'</span></div>';
 		 exit;
		
	
	}
	

}else{
	
	  echo '<div class="easywpmembers-ultra-warning"><span><i class="fa fa-check"></i>'.__("Oops! Invalid ID.",'wp-users-pro').'</span></div>';
 	  exit;
	
	
}


			
$date_created=  date($date_format, strtotime($user->user_registered ));	

//get active subscriptions
$user_memberships =$wpuserspro->membership->get_all_user_active_memberships($user_id);
$active_membership_count = count($user_memberships);

$user_expired_memberships =$wpuserspro->membership->get_all_user_expired_memberships($user_id);
$expired_membership_count = count($user_expired_memberships);




	

		

?>

<div class="easywpmembers-welcome-panel">

<h1><?php _e('Member: ','wp-users-pro')?><strong><?php echo $user->display_name?></strong>  </h1>

	  <div class="easywpm-subscriptiondetail-header-details" >
      
          <ul class="order_details">
          
				<li>
					<?php _e('ID:','wp-users-pro')?>	<strong><?php echo $user_id?></strong>
				</li>
                
                

				<li>
					<?php _e('Register Date:','wp-users-pro')?><strong><?php echo $date_created;?></strong>
				</li>

					<li >
							<?php _e('Active Subscriptions:','wp-users-pro')?><strong><?php echo $active_membership_count;?></strong>
					</li>
                    
                  <li>
							<?php _e('Expired Subscriptions:','wp-users-pro')?><strong><?php echo $expired_membership_count;?></strong>
				  </li>
				
				
                
               

				
				
		</ul>
     
     		    	
     
      </div>
      
      
 
  <h2><?php _e('Subscriptions','wp-users-pro')?> </h2>
    
<div class="easywpm-main-app-list" id="easywpm-backend-landing-1">

<?php

// get member's plans
$all_subscriptions =  $wpuserspro->user->get_my_subscriptions($user_id);

?>


 <?php	if (!empty($all_subscriptions)){ ?>
       
           <table width="100%" class="">
            <thead>
                <tr>
                    <th width="4%" ><?php _e('#', 'wp-users-pro'); ?></th>
                    <th width="13%"><?php _e('Started', 'wp-users-pro'); ?></th> 
                     <th width="13%"><?php _e('Name', 'wp-users-pro'); ?></th>
                     <th width="14%"><?php _e('Valid From', 'wp-users-pro'); ?></th>
                    <th width="14%"><?php _e('Valid To', 'wp-users-pro'); ?></th>                   
                     <th width="14%" ><?php _e('Status', 'wp-users-pro'); ?></th>
                    <th width="7%"><?php _e('Actions', 'wp-users-pro'); ?></th>
                </tr>
            </thead>
            
            <tbody>
            
            <?php 
			$filter_name= '';
			$phone= '';
			foreach($all_subscriptions as $subscription) {
				
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
                      <td> <a href="?page=wpuserspro&tab=subscriptions-edit&id=<?php echo $subscription->subscription_id; ?>" class="easywpm-appointment-edit-module" appointment-id="<?php echo  $subscription->subscription_id?>" title="<?php _e('Edit','wp-users-pro'); ?>"><i class="fa fa-edit"></i></a>
                   
                                 
                   
                   </td>
                </tr>
                
                
                <?php
					}
					
					} else {
			?>
			<p><?php _e("You don't have subscriptions",'wp-users-pro'); ?></p>
			<?php	} ?>

            </tbody>
        </table>
        

</div>
      
      
      <h2><?php _e('Payments','wp-users-pro')?> </h2>
    
<div class="easywpm-main-app-list" id="easywpm-backend-landing-1">


<?php

// get member's plans
$all_payments =  $wpuserspro->order->get_subscription_payments_by_user($user_id);

?>


 <?php	if (!empty($all_payments)){ ?>
       
           <table width="100%" class="">
            <thead>
                <tr>
                    <th width="4%" ><?php _e('#', 'wp-users-pro'); ?></th>
                    <th width="8%"><?php _e('Date', 'wp-users-pro'); ?></th> 
                     <th width="8%"><?php _e('Plan', 'wp-users-pro'); ?></th> 
                    <th width="13%"><?php _e('Payment Method', 'wp-users-pro'); ?></th>
                    <th width="4%"><?php _e('Plan ID', 'wp-users-pro'); ?></th>
                    <th width="12%"><?php _e('Transaction ID', 'wp-users-pro'); ?></th>
                    <th width="4%"><?php _e('Amount', 'wp-users-pro'); ?></th>
                   
                   
                </tr>
            </thead>
            
            <tbody>
            
            <?php 
			$filter_name= '';
			$phone= '';
			$i = 0;
			foreach($all_payments as $payment) {
				
				$date_created=  date($datetime_format, strtotime($payment->order_date ));
				
				$i++;
				
				if( $payment->membership_type=='recurring')
				{									
					$amount = $wpuserspro->get_formated_amount_with_currency($payment->order_amount_subscription);
								
				}else{
					
					$amount = $wpuserspro->get_formated_amount_with_currency($payment->order_amount);
				
				}
				
			?>
              

                <tr>
                    <td ><?php echo $i; ?></td>
                     <td><?php echo $date_created; ?>   </td>  
                      <td ><?php echo $payment->membership_name; ?> </td>   
                      <td ><?php echo $payment->order_method_name; ?> </td>
                      <td ><a href="?page=wpuserspro&tab=subscriptions-edit&id=<?php echo $payment->subscription_id; ?>"><?php echo $payment->subscription_id; ?> </a></td>
                      <td ><?php echo $payment->order_txt_id; ?></td>                                       
                      <td ><?php echo $amount; ?></td>
                      
                </tr>
                
                
                <?php
					}
					
					} else {
			?>
			<p><?php _e("There are no payments for this user",'wp-users-pro'); ?></p>
			<?php	} ?>

            </tbody>
        </table>
        

</div>

	
    
 
</div>


<div id="bup-spinner" class="easywpmembers-spinner" style="display:none">
            <span> <img src="<?php echo wpuserspro_url?>admin/images/loaderB16.gif" width="16" height="16" /></span>&nbsp; <?php echo __('Please wait ...','wp-ticket-ultra')?>
	</div>


