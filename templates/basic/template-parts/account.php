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

<h1><?php _e('My Account','wp-users-pro')?></h1>


    
<div class="easywpm-main-app-list" id="easywpm-backend-landing-1">

     
     <h2><i class="fa fa-lock"></i> <?php _e('Update your Password','wp-users-pro')?> </h2>
       <div class="easywpm-common-cont">                      
                                           
                     
                       <form method="post" name="wptu-close-account" >
                       <p><?php  _e('Type your New Password','wp-ticket-ultra');?></p>
                 			 <p><input type="password" name="p1" id="p1" /></p>
                            
                             <p><?php  _e('Re-type your New Password','wp-ticket-ultra');?></p>
                 			 <p><input type="password"  name="p2" id="p2" /></p>
                            
                         <p>
                                                  
                         <button name="wptu-backenedb-eset-password" id="wptu-backenedb-eset-password" class="wptu-button-submit-changes" ><?php  _e('RESET PASSWORD','wp-ticket-ultra');?>	</button>
                         
                         </p>
                         
                         <p id="wptu-p-reset-msg"></p>
               		  </form> 
                                           
                     </div>
                     
                     
           <h2> <i class="fa fa-envelope-o"></i> <?php  _e('Update Your Email','wp-ticket-ultra');?>  </h2> 
           
                   <div class="easywpm-common-cont">                                           
                     
                       <form method="post" name="wptu-change-email" >
                       <p><?php  _e('Type your New Email','wp-ticket-ultra');?></p>
                 			 <p><input type="text" name="bup_email" id="bup_email" value="<?php echo $user_email?>" /></p>
                                                        
                         <p>
                                                  
                         <button name="easywpm-backenedb-update-email" id="easywpm-backenedb-update-email" class="wptu-button-submit-changes"><?php  _e('CHANGE EMAIL','wp-ticket-ultra');?>	</button>
                         
                         </p>                         
                         <p id="easywpm-p-changeemail-msg"></p>
               		  </form>
                      
                      </div>

</div>
