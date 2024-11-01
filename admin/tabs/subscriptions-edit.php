<?php
global $wpuserspro;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$currency_symbol =  $wpuserspro->get_option('paid_membership_symbol');
$date_format =  $wpuserspro->get_int_date_format();
$time_format =  $wpuserspro->get_time_format();
$datetime_format =  $wpuserspro->get_date_to_display();

if(isset($_GET['id']) && $_GET['id']!=''){
	
	$subscription_id = sanitize_text_field($_GET['id']);
	
	// Get Subscription
	$subscription = $wpuserspro->order->get_subscription($subscription_id);
	$subscription_user_id = $subscription->subscription_user_id;
	
	

}else{
	
	  echo '<div class="easywpm-ultra-warning"><span><i class="fa fa-check"></i>'.__("Oops! Invalid Subscription.",'wp-users-pro').'</span></div>';
 	  exit;
	
	
}


			
$package = $wpuserspro->membership->get_one($subscription->subscription_package_id );
$subscription_payments =  $wpuserspro->order->get_subscription_payments($subscription_id);

$date_created=  date($date_format, strtotime($subscription->subscription_date ));				
$date_from=  date($date_format, strtotime($subscription->subscription_start_date  ));
$date_to=  date($date_format, strtotime($subscription->subscription_end_date  ));	


if($subscription->subscription_lifetime==1)		
{
	$date_to=__("Lifetime",'wp-users-pro');					
}


$type_legend = __('One-time','wp-users-pro');
if( $package->membership_type=='recurring')
{
	$type_legend = __('Recurring ','wp-users-pro');
					
}				
				
$initial_amount = $wpuserspro->get_formated_amount_with_currency($package->membership_initial_amount);
				
//get payment formated
$formated_agreement =  $wpuserspro->get_formated_agreement($package);	

?>

<div class="easywpmembers-welcome-panel">

<h1><?php _e('Membership: ','wp-users-pro')?><strong><?php echo $package->membership_name?></strong> 
<span class="easywpm-sub-tasks"><button class="easywpm-btn-quick-actions-btn close" title="<?php _e('Edit Membership: ','wp-users-pro')?>" subscription-id="<?php echo $subscription->subscription_id;?>" id="easywpm-edit-subscription"><i class="fa fa-edit"></i><?php _e('Edit Membership','wp-users-pro')?> </button></span></h1>


	  <div class="easywpm-subscriptiondetail-header-details" >
      
          <ul class="order_details">
          
				<li>
					<?php _e('ID:','wp-users-pro')?>	<strong><?php echo $subscription_id?></strong>
				</li>
                
                <?php if( $package->membership_type=='recurring'){?>
                
                <li>
					<?php _e('Profile ID:','wp-users-pro')?>	<strong><?php echo $subscription->subscription_merchant_id ?></strong>
				</li>
                
                <?php }?>

				<li>
					<?php _e('Date:','wp-users-pro')?><strong><?php echo $date_created;?></strong>
				</li>

					<li >
							<?php _e('Starts:','wp-users-pro')?><strong><?php echo $date_from;?></strong>
					</li>
                    
                  <li>
							<?php _e('Ends:','wp-users-pro')?><strong><?php echo $date_to;?></strong>
				  </li>
				
				<li >
						<?php _e('Type:','wp-users-pro')?><strong><span ><?php echo $type_legend;?></span></strong>
				</li>
                
                <li >
						<?php _e('Status:','wp-users-pro')?><strong><span ><?php echo $wpuserspro->membership->get_subscription_status_legend($subscription->subscription_status);?></span></strong>
				</li>

				<li class="easywpmwoopaymentmethod" >
							<?php _e('Subscription Agreement:','wp-users-pro')?>	<strong><?php echo $formated_agreement;?></strong>
			    </li>
				
		</ul>
     
     		    	
     
      </div>
      
      
      <h2><?php _e('Payments','wp-users-pro')?> </h2>
    
<div class="easywpm-main-app-list" id="easywpm-backend-landing-1">


 <?php	if (!empty($subscription_payments)){ ?>
       
           <table width="100%" class="">
            <thead>
                <tr>
                    <th width="4%" ><?php _e('#', 'wp-users-pro'); ?></th>
                    <th width="13%"><?php _e('Date', 'wp-users-pro'); ?></th> 
                    <th width="13%"><?php _e('Payment Method', 'wp-users-pro'); ?></th>
                    <th width="13%"><?php _e('Transaction ID', 'wp-users-pro'); ?></th>
                    <th width="14%"><?php _e('Amount', 'wp-users-pro'); ?></th>
                   
                   
                </tr>
            </thead>
            
            <tbody>
            
            <?php 
			$filter_name= '';
			$phone= '';
			$i = 0;
			foreach($subscription_payments as $payment) {
				
				$date_created=  date($datetime_format, strtotime($payment->order_date ));
				
				$i++;
				
				if( $package->membership_type=='recurring')
				{									
					$amount = $wpuserspro->get_formated_amount_with_currency($payment->order_amount_subscription);
								
				}else{
					
					$amount = $wpuserspro->get_formated_amount_with_currency($payment->order_amount);
				
				}
				
			?>
              

                <tr>
                    <td ><?php echo $i; ?></td>
                     <td><?php echo $date_created; ?>   </td>     
                      <td ><?php echo $payment->order_method_name; ?> </td>
                      <td ><?php echo $payment->order_txt_id; ?></td>                                       
                      <td ><?php echo $amount; ?></td>
                      
                </tr>
                
                
                <?php
					}
					
					} else {
			?>
			<p><?php _e("There are no payments for this subscription",'wp-users-pro'); ?></p>
			<?php	} ?>

            </tbody>
        </table>
        

</div>

	
    
 
</div>

   <div id="easywpm-edit-subscription-details" title="<?php _e('Edit Membership','wp-users-pro')?>"></div>


<div id="bup-spinner" class="easywpmembers-spinner" style="display:none">
            <span> <img src="<?php echo wpuserspro_url?>admin/images/loaderB16.gif" width="16" height="16" /></span>&nbsp; <?php echo __('Please wait ...','wp-ticket-ultra')?>
	</div>


