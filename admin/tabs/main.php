<?php
global $wpuserspro, $wp_locale;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$how_many_upcoming_app = 20;


$howmany = 5;

$currency_symbol =  $wpuserspro->get_option('paid_membership_symbol');
$date_format =  $wpuserspro->get_int_date_format();
$time_format =  $wpuserspro->get_time_format();
$datetime_format =  $wpuserspro->get_date_to_display();


$last_subscriptions = $wpuserspro->membership->get_latest_subscriptions(5);
$latest_orders = $wpuserspro->order->get_latest_orders(5);

$sales_today = $wpuserspro->order->get_sales_total('today');
$sales_week = $wpuserspro->order->get_sales_total('week');

/*Gross sales*/

$gross_today = $wpuserspro->order->get_gross_total('today');
$gross_week = $wpuserspro->order->get_gross_total('week');
$gross_month = $wpuserspro->order->get_gross_total('month');

//active subscriptions
$active_subscriptions_total = $wpuserspro->membership->get_all_active_memberships();
$expired_subscriptions_total = $wpuserspro->membership->get_all_expired_memberships();


$result_users = count_users();
$total_members =$result_users['total_users'];

//total subscriptions plan
$total_subscriptions_plan = $wpuserspro->membership->get_total_active_subscription_plans();


echo $this->display_warning_messages();
        
?>

<div class="easywpmembers-welcome-panel">


	<h2><?php _e('Sales Summary','wp-users-pro')?> <span class="easywpmembers-widget-backend-colspan"><a href="#" title="<?php _e('Close','wp-users-pro')?> " class="easywpmembers-widget-home-colapsable" widget-id="0"><i class="fa fa-sort-asc" id="easywpmembers-close-open-icon-0"></i></a></span></h2>
    
     <div class="easywpmembers-main-sales-summary" id="easywpmembers-main-cont-home-0">
     
     
          <div class="easywpmembers-main-dashcol-1" >
          	 <div id='easywpm-gcharthome' style="width: 100%; height: 180px;">
          	 </div>
          </div>
          
          <div class="easywpmembers-main-dashcol-2" >
          	
            
             <div class="easywpmembers-main-quick-summary" >
          
         	   <ul>
                   <li>                    
                     
                      <p style="color: #3C0"> <?php echo $sales_today?></p>  
                       <small><?php _e('Today','wp-users-pro')?> </small>                  
                    </li>
                
                	<li>                    
                     
                      <p style="color:"> <?php echo $sales_week?></p> 
                       <small><?php _e('This Week','wp-users-pro')?> </small>                   
                    </li>
              </ul>
              
            </div>
          
          </div>
          
          <div class="easywpmembers-main-dashcol-3" >
     
     		 <div class="easywpmembers-main-ticket-summary" >
             
             	<ul>
                
                   <li>                    
                      <small><?php _e('Members','wp-users-pro')?> </small>
                      <p style="color: #333"> <?php echo $total_members?></p>                    
                    </li>
                
                	<li>                    
                      <small><?php _e('Active Subscriptions','wp-users-pro')?> </small>
                      <p style="color:"> <?php echo $active_subscriptions_total?></p>                    
                    </li>
                    
                    <li> 
                    
                    <a href="#" title="<?php _e('Open','wp-users-pro')?>">                   
                      <small><?php _e('Subscription Plans','wp-users-pro')?> </small>
                      <p style="color:"> <?php echo $total_subscriptions_plan?></p>  
                      
                      </a>                  
                    </li>
                    
                    <li>     
                    
                       <a href="#" title="<?php _e('Pending','wp-users-pro')?>">               
                      <small><?php _e('Expired','wp-users-pro')?> </small>
                      <p style="color: #F90000"> <?php echo $expired_subscriptions_total?></p> 
                      
                       </a>                     
                    </li>
                    
                                  
                </ul>             
             </div>
             
            
             </div>
     
     	
     
     </div>
     
      
    
 <div class="easywpmembers-main-blocksec" >
 
     <div class="easywpmembers-main-2-col-1" >
	<h2><?php _e('Latest Subscriptions','wp-users-pro')?> <span class="easywpmembers-widget-backend-colspan"><a href="#" title="<?php _e('Close','wp-users-pro')?> " class="easywpmembers-widget-home-colapsable" widget-id="1"><i class="fa fa-sort-asc" id="easywpmembers-close-open-icon-1"></i></a></span></h2>
    	 <div class="easywpmembers-main-app-list" id="easywpmembers-main-cont-home-1"> 
        
        <?php	if (!empty($last_subscriptions)){ ?>
       
           <table width="100%" class="">
            <thead>
                <tr>
                    <th width="4%" ><?php _e('#', 'wp-users-pro'); ?></th>
                    <th width="13%"><?php _e('Started', 'wp-users-pro'); ?></th> 
                    <th width="13%"><?php _e('Member', 'wp-users-pro'); ?></th> 
                     <th width="13%"><?php _e('Name', 'wp-users-pro'); ?></th>
                     <th width="14%"><?php _e('Valid From', 'wp-users-pro'); ?></th>
                    <th width="14%"><?php _e('Valid To', 'wp-users-pro'); ?></th>                   
                     <th width="14%" ><?php _e('Status', 'wp-users-pro'); ?></th>
                    <th width="5%"><?php _e('Actions', 'wp-users-pro'); ?></th>
                </tr>
            </thead>
            
            <tbody>
            
            <?php 
			$filter_name= '';
			$phone= '';
			foreach($last_subscriptions as $subscription) {
				
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
                      <td ><?php echo $subscription->display_name; ?> </td>    
                      <td ><?php echo $subscription->membership_name; ?> </td>
                      <td ><?php echo $date_from; ?></td>
                      <td><?php echo $date_to; ?> </td>                      
                      <td ><?php echo $wpuserspro->membership->get_subscription_status_legend($subscription->subscription_status); ?></td>
                      <td> <a href="?page=wpuserspro&tab=subscriptions-edit&id=<?php echo $subscription->subscription_id?>" class="easywpm-appointment-edit-module"  title="<?php _e('Edit','wp-users-pro'); ?>"><i class="fa fa-edit"></i></a>
                   
                                 
                   
                   </td>
                </tr>
                
                
                <?php
					}
					
					} else {
			?>
			<p><?php _e("There are no recent subscriptions subscriptions",'wp-users-pro'); ?></p>
			<?php	} ?>

            </tbody>
        </table>
        
        </div>
        
        <h2><?php _e('Latest Payments','wp-users-pro')?> <span class="easywpmembers-widget-backend-colspan"><a href="#" title="<?php _e('Close','wp-users-pro')?> " class="easywpmembers-widget-home-colapsable" widget-id="32"><i class="fa fa-sort-asc" id="easywpmembers-close-open-icon-32"></i></a></span></h2>
    	 <div class="easywpmembers-main-app-list" id="easywpmembers-main-cont-home-32"> 
        
       <?php	if (!empty($latest_orders)){ ?>
       
           <table width="100%" class="">
            <thead>
                <tr>
                    <th width="5%" ><?php _e('#', 'wp-users-pro'); ?></th>
                    <th width="14%"><?php _e('Date', 'wp-users-pro'); ?></th> 
                    <th width="8%"><?php _e('Method', 'wp-users-pro'); ?></th>
                    <th width="13%"><?php _e('Transaction ID', 'wp-users-pro'); ?></th>
                     <th width="5%" ><?php _e('Subscription', 'wp-users-pro'); ?></th>
                    <th width="12%"><?php _e('Amount', 'wp-users-pro'); ?></th>
                   
                   
                </tr>
            </thead>
            
            <tbody>
            
            <?php 
			$filter_name= '';
			$phone= '';
			$i = 0;
			foreach($latest_orders as $payment) {
				
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
                    <td ><?php echo $payment->order_id; ?></td>
                     
                     <td><?php echo $date_created; ?>   </td>     
                      <td ><?php echo $payment->order_method_name; ?> </td>
                      <td ><?php echo $payment->order_txt_id; ?></td> 
                      <td ><?php echo $payment->order_subscription_id; ?> </td>                                      
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
         
         
         <div class="easywpmembers-main-2-col-2" >
            	<h2><?php _e('Gross Incomes Summary','wp-users-pro')?> <span class="easywpmembers-widget-backend-colspan"><a href="#" title="<?php _e('Close','wp-users-pro')?> " class="easywpmembers-widget-home-colapsable" widget-id="6"><i class="fa fa-sort-asc" id="easywpmembers-close-open-icon-6"></i></a></span></h2>
                <div class="easywpmembers-main-app-list" id="easywpmembers-main-cont-home-6">
                
                
                    <div class="easywpm-income" >
              
                       <ul>
                           <li>                    
                             
                              <p style="color: #333"> <?php echo $gross_today?></p>  
                               <small><?php _e('Today Incomes','wp-users-pro')?> </small>                  
                            </li>
                        
                            <li>                    
                             
                              <p style="color:"> <?php echo $gross_week?></p> 
                               <small><?php _e('This Week Incomes','wp-users-pro')?> </small>                   
                            </li>
                            
                             <li>                    
                             
                              <p style="color:"> <?php echo $gross_month?></p> 
                               <small><?php _e('This Month Incomes','wp-users-pro')?> </small>                   
                            </li>
                            
                      </ul>
                  
                  </div>
                  
                   <div id='easywpm-grossdaily' style="width: 100%; height: 180px;">
          		 </div>
                  
                   <div id='easywpm-grossmonthly' style="width: 100%; height: 180px;">
          		 </div>
                 
                  
             
             
                </div>
                
                
          </div>    
         
         </div>
        

</div>

<?php

$sales_val= $wpuserspro->order->get_graph_total_monthly();
$sales_val_daily= $wpuserspro->order->get_graph_total_daily();


$sales_gross_monthly_val= $wpuserspro->order->get_graph_total_gross_by_month();

$months_array = array_values( $wp_locale->month );
$current_month = date("m");
$current_month_legend = $months_array[$current_month -1];

?>

<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
		  
        var data = google.visualization.arrayToDataTable([
          ["<?php _e('Day','wp-users-pro')?>", "<?php _e('Sales','wp-users-pro')?>"],
         <?php echo $sales_val?>
        ]);

        var options = {
        
          hAxis: {title: '<?php printf(__( 'Month: %s', 'wp-users-pro' ),
    $current_month_legend);?> ',  titleTextStyle: {color: '#333'},  textStyle: {fontSize: '9'}},
          vAxis: {minValue: 0},		 
		  legend: { position: "none" }
        };

        var chart_1 = new google.visualization.AreaChart(document.getElementById('easywpm-gcharthome'));
        chart_1.draw(data, options);
		
		//gross montlhly sales
		 var data = google.visualization.arrayToDataTable([
          ["<?php _e('Day','wp-users-pro')?>", "<?php _e('Sales','wp-users-pro')?>"],
         <?php echo $sales_gross_monthly_val?>
        ]);

        var options = {
		  title: "<?php _e('Current Month Gross Sales','wp-users-pro')?>",        
          hAxis: {title: '<?php printf(__( 'Year: %s', 'wp-users-pro' ),
    date("Y"));?> ',  titleTextStyle: {color: '#333'},  textStyle: {fontSize: '9'}},
          vAxis: {minValue: 0},		 
		  legend: { position: "none" }
        };

        var chart_2 = new google.visualization.AreaChart(document.getElementById('easywpm-grossmonthly'));
        chart_2.draw(data, options);
		
		
		//gross daily sales		
		 var data = google.visualization.arrayToDataTable([
          ["<?php _e('Day','wp-users-pro')?>", "<?php _e('Sales','wp-users-pro')?>"],
         <?php echo $sales_val_daily?>
        ]);

        var options = {
			
		 title: "<?php _e('Current Month Daily Gross Sales','wp-users-pro')?>", 
        
          hAxis: {title: '<?php printf(__( '%s', 'wp-users-pro' ),
    $current_month_legend);?> ',  titleTextStyle: {color: '#333'},  textStyle: {fontSize: '8'}},
          vAxis: {minValue: 0},		 
		  legend: { position: "none" }
        };

        var chart_3 = new google.visualization.AreaChart(document.getElementById('easywpm-grossdaily'));
        chart_3.draw(data, options);
		
		
		
		
		
		
      }
    </script>

     
