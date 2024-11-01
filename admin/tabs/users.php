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

$all_usu =$wpuserspro->user->get_all_filtered();

        
?>

<div class="easywpmembers-welcome-panel">

<h1><?php _e('MEMBERS','wp-users-pro')?></h1>

	<h2><?php _e('All members','wp-users-pro')?> <span class="easywpmembers-widget-backend-colspan"><a href="#" title="<?php _e('Close','wp-users-pro')?> " class="easywpmembers-widget-home-colapsable" widget-id="0"><i class="fa fa-sort-asc" id="easywpmembers-close-open-icon-0"></i></a></span></h2>
    
     <div class="easywpmembers-main-sales-summary" id="easywpmembers-main-cont-home-0">  
     
     
      <div class="easywpmembers-tickets-module-filters">
         
          <form action="" method="get">
         <input type="hidden" name="page" value="wpuserspro" />
         <input type="hidden" name="tab" value="subscriptions" />
         
         
          <input type="text" name="bp_keyword" id="bp_keyword" value="<?php echo $bp_keyword?>" placeholder="<?php _e('input some text here','wp-users-pro'); ?>" />
          
         
              <select name="bp_month" id="bp_month">
               <option value="" selected="selected"><?php _e('All Months','wp-users-pro'); ?></option>
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
               <option value="" selected="selected"><?php _e('All Days','wp-users-pro'); ?></option>
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
               <option value="" selected="selected"><?php _e('All Years','wp-users-pro'); ?></option>
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
               
          </select>
          
                       <button name="easywpm-btn-ticket-filter-appo" id="easywpm-btn-ticket-filter-appo" class="easywpm-button-submit-filter" type="submit"><?php _e('Filter','wp-users-pro')?>	</button>
                               
                
            
        
        
         </form>
         
                 
         
         </div>
           
         
             	
     
     </div>
     
     <div class="easywpmembers-main-sales-summary" > 
     
      <?php	if (!empty($all_usu)){ ?>
       
           <table width="100%" class="">
            <thead>
                <tr>
                    <th width="4%" ><?php _e('#', 'wp-users-pro'); ?></th>                    
                    <th width="8%"><?php _e('Registration Date', 'wp-users-pro'); ?></th>                    
                     <th width="8%"><?php _e('Name', 'wp-users-pro'); ?></th>
                      <th width="8%"><?php _e('Username', 'wp-users-pro'); ?></th>
                       <th width="8%"><?php _e('Email', 'wp-users-pro'); ?></th>
                                    
                     <th width="10%" ><?php _e('Subscriptions', 'wp-users-pro'); ?></th>
                    <th width="5%"><?php _e('Actions', 'wp-users-pro'); ?></th>
                </tr>
            </thead>
            
            <tbody>
            
            <?php 
			$filter_name= '';
			$phone= '';
			foreach($all_usu as $user) {
				
				$date_created=  date($date_format, strtotime($user->user_registered ));
				
				//howmany subsriptions
				
		
				
			?>
              

                <tr>
                    <td ><a href="?page=easywpmembers&tab=users-edit&id=<?php echo $user->ID?>" class="easywpm-appointment-edit-module"  title="<?php _e('Edit','wp-users-pro'); ?>"><?php echo $user->ID; ?></a></td> 
                    
                     <td><?php echo $date_created; ?>   </td> 
                      <td ><?php echo $user->display_name; ?> </td> 
                      <td ><?php echo $user->user_login; ?> </td> 
                      <td ><?php echo $user->user_email; ?> </td>   
                                               
                      <td ><a href="#" class="easywpm-user-subscriptions-module" user-id=<?php echo $user->ID?>  title="<?php _e('See Subscriptions','wp-users-pro'); ?>"><?php echo $user->ID; ?></a></td>
                      <td> <a href="?page=wpuserspro&tab=users-edit&id=<?php echo $user->ID?>" class="easywpm-appointment-edit-module"  title="<?php _e('Edit','wp-users-pro'); ?>"><i class="fa fa-edit"></i></a>
                   
                                 
                   
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
     
      
    
 
</div>

