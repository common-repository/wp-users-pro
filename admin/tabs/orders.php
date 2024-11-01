<?php
global $wpuserspro, $wp_locale;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$how_many_upcoming_app = 20;


$howmany = 5;

$currency_symbol =  $wpuserspro->get_option('paid_membership_symbol');
$date_format =  $wpuserspro->get_int_date_format();
$time_format =  $wpuserspro->get_time_format();
$datetime_format =  $wpuserspro->get_date_to_display();


$howmany = "";
$year = "";
$month = "";
$day = "";
$special_filter = "";
$bup_staff_calendar = "";

$bp_status ="";
$bp_keyword ="";



if(isset($_GET["howmany"]))
{
  $howmany = sanitize_text_field($_GET["howmany"]);		
}

if(isset($_GET["bp_month"]))
{
  $month = sanitize_text_field($_GET["bp_month"]);		
}

if(isset($_GET["bp_day"]))
{
  $day = sanitize_text_field($_GET["bp_day"]);		
}

if(isset($_GET["bp_year"]))
{
  $year = sanitize_text_field($_GET["bp_year"]);		
}

if(isset($_GET["bp_status"]))
{
  $bp_status = sanitize_text_field($_GET["bp_status"]);		
}

if(isset($_GET["special_filter"]))
{
  $special_filter = sanitize_text_field($_GET["special_filter"]);		
}

if(isset($_GET["bp_sites"]))
{
  $bp_sites = sanitize_text_field($_GET["bp_sites"]);		
}

if(isset($_GET["bp_keyword"]))
{
  $bp_keyword = sanitize_text_field($_GET["bp_keyword"]);		
}

$orders =$wpuserspro->order->get_all_filtered();

        
?>

<div class="easywpmembers-welcome-panel">

<h1><?php _e('ORDERS','wp-users-pro')?></h1>

	<h2> <span class="easywpmembers-widget-backend-colspan"><a href="#" title="<?php _e('Close','wp-users-pro')?> " class="easywpmembers-widget-home-colapsable" widget-id="0"><i class="fa fa-sort-asc" id="easywpmembers-close-open-icon-0"></i></a></span></h2>
    
     <div class="easywpmembers-main-sales-summary" id="easywpmembers-main-cont-home-0">  
     
     
      <div class="easywpmembers-tickets-module-filters">
         
          <form action="" method="get">
         <input type="hidden" name="page" value="wpuserspro" />
         <input type="hidden" name="tab" value="orders" />
         
         
          <input type="text" name="bp_keyword" id="bp_keyword" value="<?php echo $bp_keyword?>" placeholder="<?php _e('input some text here','wp-ticket-ultra'); ?>" />
          
         
              <select name="bp_month" id="bp_month">
               <option value="" selected="selected"><?php _e('All Months','wp-ticket-ultra'); ?></option>
               <?php
			  
			  $i = 1;
              
			  while($i <=12){
			  ?>
               <option value="<?php echo $i?>"  <?php if($i==$month) echo 'selected="selected"';?>><?php echo $i?></option>
               <?php 
			    $i++;
			   }?>
             </select>
             
             <select name="bp_day" id="bp_day">
               <option value="" selected="selected"><?php _e('All Days','wp-ticket-ultra'); ?></option>
               <?php
			  
			  $i = 1;
              
			  while($i <=31){
			  ?>
               <option value="<?php echo $i?>"  <?php if($i==$day) echo 'selected="selected"';?>><?php echo $i?></option>
               <?php 
			    $i++;
			   }?>
             </select>
             
             <select name="bp_year" id="bp_year">
               <option value="" selected="selected"><?php _e('All Years','wp-ticket-ultra'); ?></option>
               <?php
			  
			  $i = 2014;
              
			  while($i <=2020){
			  ?>
               <option value="<?php echo $i?>" <?php if($i==$year) echo 'selected="selected"';?> ><?php echo $i?></option>
               <?php 
			    $i++;
			   }?>
             </select>
                
           
                       
             <select name="howmany" id="howmany">
               <option value="50" <?php if(50==$howmany ||$howmany =="" ) echo 'selected="selected"';?>>50 <?php _e('Per Page','wp-users-pro'); ?></option>
                <option value="80" <?php if(80==$howmany ) echo 'selected="selected"';?>>80 <?php _e('Per Page','wp-users-pro'); ?></option>
                 <option value="100" <?php if(100==$howmany ) echo 'selected="selected"';?>>100 <?php _e('Per Page','wp-users-pro'); ?></option>
                  <option value="150" <?php if(150==$howmany ) echo 'selected="selected"';?>>150 <?php _e('Per Page','wp-users-pro'); ?></option>
                   <option value="200" <?php if(200==$howmany ) echo 'selected="selected"';?>>200 <?php _e('Per Page','wp-users-pro'); ?></option>
               <option value="400" <?php if(400==$howmany ) echo 'selected="selected"';?>>400 <?php _e('Per Page','wp-users-pro'); ?></option>
               <option value="500" <?php if(500==$howmany ) echo 'selected="selected"';?>>500 <?php _e('Per Page','wp-users-pro'); ?></option>
                <option value="600" <?php if(600==$howmany ) echo 'selected="selected"';?>>600 <?php _e('Per Page','wp-users-pro'); ?></option>
               
          </select>
          
                     <p>  <button name="easywpm-btn-ticket-filter-appo" id="easywpm-btn-ticket-filter-appo" class="easywpm-button-submit-filter" type="submit"><?php _e('Filter','wp-users-pro')?>	</button></p> 
                               
                
            
        
        
         </form>
         
                 
         
         </div>
           
         
             	
     
     </div>
     
     <div class="easywpmembers-main-sales-summary" > 
     
       <?php	if (!empty($orders)){ ?>
       
           <table width="100%" class="">
            <thead>
                <tr>
                	
                    <th width="4%" ><?php _e('#', 'wp-users-pro'); ?></th>
                    <th width="10%"><?php _e('Date', 'wp-users-pro'); ?></th>
                     <th width="8%"><?php _e('Member', 'wp-users-pro'); ?></th> 
                     <th width="8%"><?php _e('Membership', 'wp-users-pro'); ?></th>
                    <th width="8%"><?php _e('Payment Method', 'wp-users-pro'); ?></th>
                    <th width="13%"><?php _e('Transaction ID', 'wp-users-pro'); ?></th>
                    <th width="5%" ><?php _e('Subs. ID', 'wp-users-pro'); ?></th>
                    <th width="5%" ><?php _e('Type', 'wp-users-pro'); ?></th>
                    <th width="12%"><?php _e('Amount', 'wp-users-pro'); ?></th>
                   
                   
                </tr>
            </thead>
            
            <tbody>
            
            <?php 
			$filter_name= '';
			$phone= '';
			$i = 0;
			foreach($orders as $payment) {
				
				$date_created=  date($datetime_format, strtotime($payment->order_date ));
				
				$i++;
				
				if( $payment->membership_type=='recurring'){
														
					$amount = $wpuserspro->get_formated_amount_with_currency($payment->order_amount_subscription);
								
				}else{
					
					$amount = $wpuserspro->get_formated_amount_with_currency($payment->order_amount);
				
				}
				
				if( $payment->order_ini=='1'){		
											
					$payment_type = __('Initial', 'wp-users-pro');
								
				}else{
					
					$payment_type = __('Renewal', 'wp-users-pro');				
				}
				
			?>
              

                <tr>
               		
                    <td ><?php echo $payment->order_id; ?></td>
                     
                     <td><?php echo $date_created; ?>   </td>  
                     <td ><?php echo $payment->display_name; ?> </td> 
                      <td ><?php echo $payment->membership_name; ?> </td>   
                      <td ><?php echo $payment->order_method_name; ?> </td>
                      <td ><?php echo $payment->order_txt_id; ?></td> 
                      <td > <a href="?page=wpuserspro&tab=subscriptions-edit&id=<?php echo $payment->order_subscription_id?>" class="easywpm-appointment-edit-module"  title="<?php _e('Edit','wp-users-pro'); ?>"><?php echo $payment->order_subscription_id; ?></a> </td> 
                       <td><?php echo $payment_type; ?>   </td>                                     
                      <td ><?php echo $amount; ?></td>
                      
                </tr>
                
                
                <?php
					}
					
					} else {
			?>
			<p><?php _e("There are no recent payments",'wp-users-pro'); ?></p>
			<?php	} ?>

            </tbody>
        </table>
             	
     
     </div>
     
      
    
 
</div>

