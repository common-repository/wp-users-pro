<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpuserspro, $wpuserspro_stripe, $wpuserspro_recurring, $wpuserspro_onlyroles, $wpuserspro_assignrole;

if(isset($_GET['id']) && $_GET['id']!=''){
	
	$membership_id = sanitize_text_field($_GET['id']);
	
	// Get Subscription
	$package = $wpuserspro->membership->get_one($membership_id);
	

}else{
	
	  echo '<div class="easywpm-ultra-warning"><span><i class="fa fa-check"></i>'.__("Oops! Invalid Subscription.",'wp-users-pro').'</span></div>';
 	  exit;
	
	
}
		
?>

<form method="post" action="">
<input type="hidden" name="wpuserspro_edit_membership" />
<input type="hidden" name="subscription_id" value="<?php echo $membership_id;?>"/>

<?php wp_nonce_field( 'update_settings', 'wpuserspro_nonce_check' ); ?>

<div class="easywpmembers-sect  easywpmembers-welcome-panel"> 

<?php echo $wpuserspro->membership->get_errors();?> 
<?php echo $wpuserspro->membership->sucess_message;?> 



<h3><?php _e('Edit Subscription', 'wp-users-pro'); ?></h3>


           <table width="100%" class="">
                      
            <tbody>
          

                <tr >
                    <td ><?php _e('Name', 'wp-users-pro'); ?></td>
                     <td> <input name="wpuserspro_subscription_name" id="wpuserspro_subscription_name" value="<?php echo $package->membership_name ?>" type="text"> </td>        
                </tr>
                
                <tr >
                    <td ><?php _e('Description', 'wp-users-pro'); ?></td>
                     <td> <?php echo $wpuserspro->admin->get_me_wphtml_editor("wpuserspro_subscription_desc", $package->membership_description, 8);?> </td>        
                </tr>
                
                
                
            </tbody>
        </table>
        
                <h4><?php _e('Billing Details', 'wp-users-pro'); ?></h4>
        
         <table width="100%" class="">
                      
            <td width="24%">
            <tbody>
            
             <tr >
                    <td ><?php _e('Type', 'wp-users-pro'); ?></td>
                     <td> <select name="wpuserspro_subscription_type" id="wpuserspro_subscription_type">
             				<option value="onetime"  <?php if( $package->membership_type=='onetime'){ echo 'selected="selected"';}?> ><?php _e('One-Time', 'wp-users-pro'); ?></option>                             
                            
                            <?php if(isset($wpuserspro_recurring)){?> 
                            <option value="recurring" <?php if( $package->membership_type=='recurring'){ echo 'selected="selected"';}?>><?php _e('Recurring', 'wp-users-pro'); ?></option> 
                              <?php }?>             
             
           				   </select></td>        
                </tr>
                
                
                 <tr >
                    <td ><?php _e('Lifetime Membership?', 'wp-users-pro'); ?></td>
                     <td> <select name="wpuserspro_subscription_lifetime" id="wpuserspro_subscription_lifetime">
             				<option value="0" <?php if( $package->membership_lifetime =='0'){ echo 'selected="selected"';}?>><?php _e('NO', 'wp-users-pro'); ?></option>                             
                            <option value="1" <?php if( $package->membership_lifetime =='1'){ echo 'selected="selected"';}?>><?php _e('YES', 'wp-users-pro'); ?></option> 
                                        
             
           				   </select></td>        
                </tr>
          

                <tr >
                    <td ><?php _e('Initial Payment', 'wp-users-pro'); ?></td>
                     <td width="76%"> <input name="wpuserspro_subscription_initial_payment" id="wpuserspro_subscription_initial_payment" value="<?php echo $package->membership_initial_amount; ?>" type="text"> - <?php _e('Use this for one-time payments or setup payment for recurring subscriptions. Input 0 for free subscriptions', 'wp-users-pro'); ?></td>        
                </tr>
                
                 
                
                  
                <tr >
                    <td ><?php _e('Recurring Payment', 'wp-users-pro'); ?></td>
                     <td> <input name="wpuserspro_subscription_reccurring_amount" id="wpuserspro_subscription_reccurring_amount" value="<?php echo $package->membership_subscription_amount;?>" type="text">  </td>        
                </tr>
                
                  <tr >
                    <td ><?php _e('Every', 'wp-users-pro'); ?></td>
                     <td> <select name="wpuserspro_subscription_every" id="wpuserspro_subscription_every">
             				             
                                    
                         <?php
                          
                          $i = 1;			  
                          $html = '';              
                          while($i <=31){
                              
                              $sel = "";
							  if($i==$package->membership_every )	{$sel = 'selected="selected"';}	
							                
                              $html .= '<option value="'.$i.'" '.$sel.' >'.$i.'</option>';  
                          
                            $i++;
                          }
                         
                         $html .= '</select>' ;
                         
                         echo  $html;?> <select name="wpuserspro_subscription_period" id="wpuserspro_subscription_period">
             				<option value="M" <?php if( $package->membership_time_period  =='M'){ echo 'selected="selected"';}?>><?php _e('Month(s)', 'wp-users-pro'); ?></option>                             
                            <option value="W"  <?php if( $package->membership_time_period  =='W'){ echo 'selected="selected"';}?>><?php _e('Week(s)', 'wp-users-pro'); ?></option>
                            <option value="D"  <?php if( $package->membership_time_period  =='D'){ echo 'selected="selected"';}?>><?php _e('Day(s)', 'wp-users-pro'); ?></option> 
                            <option value="Y"  <?php if( $package->membership_time_period  =='Y'){ echo 'selected="selected"';}?>><?php _e('Year(s)', 'wp-users-pro'); ?></option>              
             
           				   </select></td>        
                </tr>
                
                                
                  <tr >
                    <td ><?php _e('Billing Cycle Limit', 'wp-users-pro'); ?></td>
                     <td> <input name="wpuserspro_subscription_cycle_period" id="wpuserspro_subscription_cycle_period" value="0" type="text"> - <?php _e('This is the total number of recurring billing cicles for this subscription. Set to zero if membership cycle is indefinite', 'wp-users-pro'); ?></td>        
                 </tr>
                 
                
                
                
            </tbody>
        </table>
        
        
        <?php if(isset($wpuserspro_stripe) && isset($wpuserspro_recurring)){
			
			?>
        
         <h4><?php _e('Stripe Settings ', 'wp-users-pro'); ?></h4>
         
          <table width="100%" class="">
                      
            <td width="24%"><tbody>         

          	      <tr >
                    <td ><?php _e('Choose Your Plan', 'wp-users-pro'); ?></td>
                     <td width="76%"><?php echo $wpuserspro_stripe->get_stripe_plans_drop_box($package->membership_stripe_id );?> </td>        
          	      </tr>
                
                    
        	    </tbody>
       	 </table>
        
        
        <?php }?>
        
         <h4><?php _e('Other Settings ', 'wp-users-pro'); ?></h4>
         
          <table width="100%" class="">
                      
            <td width="24%"><tbody>
          
 				 <tr >
                    <td ><?php _e('Requires Admin Approvation?', 'wp-users-pro'); ?></td>
                     <td> <select name="wpuserspro_subscription_requires_approvation" id="wpuserspro_subscription_requires_approvation">
             				<option value="0"  <?php if( $package->membership_approvation   =='0'){ echo 'selected="selected"';}?>><?php _e('NO', 'wp-users-pro'); ?></option>                             
                            <option value="1" <?php if( $package->membership_approvation   =='1'){ echo 'selected="selected"';}?>><?php _e('YES', 'wp-users-pro'); ?></option>
                                       
             
           				   </select> - <?php _e('If YES, The admin will have to approve this account.', 'wp-users-pro'); ?></td>        
                 </tr>
                 
                 
 				 <tr >
                    <td ><?php _e('Public Visible?', 'wp-users-pro'); ?></td>
                     <td> <select name="wpuserspro_subscription_public_visible" id="wpuserspro_subscription_public_visible">
             				 <option value="1" <?php if( $package->membership_public_visible    =='1'){ echo 'selected="selected"';}?>><?php _e('YES', 'wp-users-pro'); ?></option>
                            <option value="0" <?php if( $package->membership_public_visible    =='0'){ echo 'selected="selected"';}?>><?php _e('NO', 'wp-users-pro'); ?></option>                             
                           
                                       
             
           				   </select> - <?php _e("If NO, This subscription won't be visible on the public side.", 'wp-users-pro'); ?></td>        
                 </tr>
                 
                  <tr >
                    <td ><?php _e('Display Order', 'wp-users-pro'); ?></td>
                     <td width="76%"> <input name="wpuserspro_display_order" id="wpuserspro_display_order" value="<?php echo $package->membership_order;?>" type="text" value="0"> - <?php _e('Input the display ordering for this subscription. Example: 1,2,3', 'wp-users-pro'); ?></td>        
                </tr>
                
                
                <tr >
                    <td ><?php _e('Status', 'wp-users-pro'); ?></td>
                     <td> <select name="wpuserspro_status" id="wpuserspro_status">
             				 <option value="1"  <?php if( $package->membership_status    =='1'){ echo 'selected="selected"';}?>><?php _e('Active', 'wp-users-pro'); ?></option>
                            <option value="0"  <?php if( $package->membership_status    =='0'){ echo 'selected="selected"';}?>><?php _e('Deactivated', 'wp-users-pro'); ?></option>                             
                           
                                       
             
           				   </select> - <?php _e("If Deactivated, This subscription won't be available on the website.", 'wp-users-pro'); ?></td>        
                 </tr>
                 
                  <?php if(isset($wpuserspro_assignrole)){?>
                  <tr >
                    <td ><?php _e('Assign Role', 'wp-users-pro'); ?></td>
                     <td> 
                            <?php
							
							 $display = '';
							 
							 $allowed_user_roles = $wpuserspro->role->get_available_user_roles();
                            
							 $display .= '<select name="wpuserspro_subscription_role_to_assign" id="wpuserspro_subscription_role_to_assign">';
							 $display .= '<option value="" >'.__('Do not assign role', 'wp-users-pro').'</option>';
         
		
							   foreach ($allowed_user_roles as $key => $val)
							   {
								   $sel ="";
								   if($package->membership_role_to_assign  ==$key) 
								   {
									   $sel = 'selected="selected"';
									  
								   }
								   $display .= '<option value="' . $key . '" '.$sel.' >' . $val . '</option>';
							   }
							  
							  $display .= '</select>';
							  
							  echo $display;
							
							?>                           
                           
                                       
             
           				    - <?php _e("This role will be assigned automatically when the user signs up.", 'wp-users-pro'); ?></td>        
                 </tr>
                 
                 <?php }?>
                

                

                
            </tbody>
        </table>
        
         <h4><?php _e('Content Accessibility ', 'wp-users-pro'); ?></h4>
         
          <table width="100%" class="">
                      
            <td width="24%"><tbody>
          

                <tr >
                    <td ><?php _e('Categories', 'wp-users-pro'); ?></td>
                     <td width="76%"><?php echo  $wpuserspro->get_subscription_categories_admin($package);?> </td>        
                </tr>
                
                 <?php if(isset($wpuserspro_onlyroles)){?>
                
                 	 <tr >
                  	  <td ><?php _e('Roles', 'wp-users-pro'); ?></td>
                   	  <td><?php echo  $wpuserspro->role->get_package_roles($package);?></td>  
                     
                           
                	</tr>
                <?php }?>

                

                
            </tbody>
        </table>
        
  <p class="submit">
	<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes','wp-users-pro'); ?>"  />
</p>
      


</div>


</form>