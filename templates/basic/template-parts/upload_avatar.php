<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpuserspro ;

$current_user = $wpuserspro->user->get_user_info();
$user_id = $current_user->ID;

$currency_symbol =  $wpuserspro->get_option('paid_membership_symbol');
$date_format =  $wpuserspro->get_int_date_format();
$time_format =  $wpuserspro->get_time_format();
$datetime_format =  $wpuserspro->get_date_to_display();





?>

<h1><?php _e('Upload Your Avatar','wp-users-pro')?></h1>


    
<div class="easywpm-main-app-list" id="easywpm-backend-landing-1">

    
      <?php if($user_id==''){?>	
             
             
                 <div class="easywpm-staff-left " id="wptu-staff-list">           	
            	 </div>
                 
                 <div class="easywpm-staff-right " id="wptu-staff-details">
                 </div>
            
            <?php }else{ //upload avatar?>
            
           <?php
            
            $crop_image = "";
    
            if(isset($_POST['crop_image'])){
                
                        
                 $crop_image = sanitize_text_field($_POST['crop_image']);
                
            }
		   
		   $crop_image = sanitize_text_field($crop_image);
		   if( $crop_image=='crop_image') //displays image cropper
			{
			
			 $image_to_crop = sanitize_text_field($_POST['image_to_crop']);
			 
			
			 ?>
             
             <div class="easywpm-staff-right-avatar " >
           		  <div class="pr_tipb_be">
                              
                            <?php echo $wpuserspro->profile->display_avatar_image_to_crop($image_to_crop, $user_id);?>                          
                              
                   </div>
                   
             </div>
            
           
		    <?php }else{  
			
			$user = get_user_by( 'id', $user_id );
			?> 
            
            <div class="easywpm-staff-right-avatar " >
            
           
                   <div class="easywpm-avatar-drag-drop-sector"  id="easywpm-drag-avatar-section">
                   
                   <h3> <?php echo $user->display_name?><?php _e("'s Picture",'wp-ticket-ultra')?></h3>
                        
                             <?php echo $wpuserspro->profile->get_user_pic( $user_id, 80, 'avatar', 'rounded', 'dynamic')?>

                                                    
                             <div class="uu-upload-avatar-sect">
                              
                                     <?php echo $wpuserspro->profile->avatar_uploader($user_id)?>  
                              
                             </div>
                             
                        </div>  
                    
             </div>
             
             
              <?php }  ?>
            
             <?php }?>
</div>
