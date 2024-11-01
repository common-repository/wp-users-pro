<?php
global $wpuserspro;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$va = get_option('wpuserspro_c_key');

///echo "licence ".$va;
$domain = $_SERVER['SERVER_NAME'];
	
?>

 <div class="easywpmembers-sect easywpmembers-welcome-panel ">
 
 
  <?php if($va!='' ){ //user is running a validated copy?>
  
  <h3><?php _e('Congratulations!','wp-users-pro'); ?></h3>
   <p><?php _e("Your copy has been validated. You should be able to update the plugin through your WP Update sections. Also, you should start receiving an notice every time the plugin is updated.",'wp-users-pro'); ?></p>

   <?php }else{?>
        
        <h3><?php _e('Validate your copy','wp-users-pro'); ?></h3>
        <p><?php _e("Please fill out the form below with the serial number generated when you registered your domain through your account at wpuserspro.com",'wp-users-pro'); ?></p>
        
        <p> <?php _e('INPUT YOUR SERIAL KEY','wp-users-pro'); ?></p>
         <p><input type="text" name="p_serial" id="p_serial" style="width:200px" /></p>
        
        
        <p class="submit">
	<input type="submit" name="submit" id="easywpm-btn-validate-copy" class="button button-primary " value="<?php _e('CLICK HERE TO VALIDATE YOUR COPY','wp-users-pro'); ?>"  /> &nbsp; <span id="loading-animation">  <img src="<?php echo wpuserspro_url?>admin/images/loaderB16.gif" width="16" height="16" /> &nbsp; <?php _e('Please wait ...','wp-users-pro'); ?> </span>
	
       </p>
       
       
        <?php }?>
       
       <p id='bup-validation-results'>
       
       </p>
                     
       
    
</div>  

