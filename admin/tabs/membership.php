<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpuserspro;

$all_packages = $wpuserspro->membership->get_all();	
?>




<div class="easywpmembers-sect  easywpmembers-welcome-panel">  


<div class="easywpmembers-top-options-book">            
            	                
                <a class="easywpmembers-btn-top1-book" href="?page=wpuserspro&tab=membership-add" title="<?php _e('Create New', 'wp-users-pro'); ?>"><span><i class="fa fa-plus fa-2x"></i><?php _e('Create New', 'wp-users-pro'); ?></span></a>                     
                                
           </div>
           

<h3><?php _e('Subscription Plans', 'wp-users-pro'); ?></h3>


 <?php	if (!empty($all_packages)){ ?>
       
           <table width="100%" class="">
            <thead>
                <tr>
                    <th width="4%" ><?php _e('ID', 'wp-users-pro'); ?></th>
                     <th width="4%" ><?php _e('Order', 'wp-users-pro'); ?></th>
                    <th width="10%"><?php _e('Name', 'wp-users-pro'); ?></th> 
                     
                      <th width="12%" ><?php _e('Type', 'wp-users-pro'); ?></th>  
                      
                        <th width="8%" ><?php _e('Initial Payment', 'wp-users-pro'); ?></th> 
                        <th width="18%"><?php _e('Agreement', 'wp-users-pro'); ?></th>
                    
                    
                     
                     <th width="7%"><?php _e('Status', 'wp-users-pro'); ?></th>
                    <th width="10%"><?php _e('Actions', 'wp-users-pro'); ?></th>
                </tr>
            </thead>
            
            <tbody>
            
            <?php 
			$filter_name= '';
			$phone= '';
			foreach($all_packages as $package) {
				
				$recurring_amount = 'N/A';
				if( $package->membership_type=='recurring'){
					
					$recurring_amount = $wpuserspro->get_formated_amount_with_currency($package->membership_subscription_amount);
				
				}				
				
				$initial_amount = $wpuserspro->get_formated_amount_with_currency($package->membership_initial_amount);
				
				//get payment formated
				$formated_agreement =  $wpuserspro->get_formated_agreement($package);
				
				
				if($package->membership_status == 1){					
					$status=__('Active', 'wp-users-pro'); 
				}else{
					$status=__('Deactivated', 'wp-users-pro'); 
				}
				
					
			?>
              

                <tr >
                    <td ><?php echo $package->membership_id; ?></td>
                     <td ><?php echo $package->membership_order; ?></td>
                     <td><?php echo $package->membership_name; ?>     </td>
                     <td><?php echo $package->membership_type; ?>     </td>
                       <td ><?php echo $initial_amount; ?> </td>                     
                           
                      <td ><?php echo $formated_agreement ; ?></td>                   
                   
                    <td><?php echo  $status; ?></td>                  
                     
                      
                   <td> <a href="?page=wpuserspro&tab=membership-edit&id=<?php echo $package->membership_id?>" class="easywpm-appointment-edit-module" title="<?php _e('Edit','wp-users-pro'); ?>"><i class="fa fa-edit"></i></a>
                   
                  
                   &nbsp;<a href="#" class="easywpm-appointment-delete-module" ticket-id="<?php echo$package->membership_id?>" title="<?php _e('Delete','wp-users-pro'); ?>"><i class="fa fa-trash-o"></i></a>
                  
              
                  
                   
                   </td>
                </tr>
                
                
                <?php
					}
					
					} else {
			?>
			<p><?php _e('There are no subscription packages at the moment.','wp-users-pro'); ?></p>
			<?php	} ?>

            </tbody>
        </table>

</div>