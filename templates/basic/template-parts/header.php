<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpuserspro, $wpuserspro_wooco ;

$current_user = $wpuserspro->user->get_user_info();
$user_id = $current_user->ID;

$user_type_legend = "";


?>


<div class="easywpm-top-header">   
    
    	<?php echo $wpuserspro->profile->get_user_avatar_top($user_id);?>   
        
        
        <div class="easywpm-staff-profile-name">
        	<h1><?php echo $current_user->display_name?></h1>
            <small><?php echo $user_type_legend?></small>
        </div>
        
        <div class="easywpm-top-options-book">            
            	                
               
                                
           </div>
        
        
        <div class="easywpm-top-options"> 
             <ul>            
             
                 <li><?php echo $wpuserspro->profile->get_user_backend_menu_new('main', 'Main','fa-home');?></li>
                                              
                
                  <?php if( isset($wpuserspro_wooco) ){?>
                 
                	 <li><?php echo $wpuserspro->profile->get_user_backend_menu_new('orders_list', 'Orders','fa-list');?></li>   
                 
                  <?php } ?>  
                  
                   <li><?php echo $wpuserspro->profile->get_user_backend_menu_new('subscriptions', 'Subscriptions','fa-list');?></li>   
                 
                  <li><?php echo $wpuserspro->profile->get_user_backend_menu_new('account', 'Account','fa-address-card-o');?></li>
                  
                 
                  <li><?php echo $wpuserspro->profile->get_user_backend_menu_new('logout', 'Logout','fa-sign-out');?></li>
            
             </ul>
         
         </div> 
             
    </div>


