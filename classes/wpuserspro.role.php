<?php
class WPUsersProRole
{
	var $table_prefix = 'wpuserspro';
	var $ajax_p = 'wpuserspro';
	
	function __construct() 
	{
		
	
	}
	
	public function get_available_user_roles(){
        global $wp_roles;
        $user_roles = array();

        if ( ! isset( $wp_roles ) ) 
            $wp_roles = new WP_Roles(); 

        $skipped_roles = array('administrator');

        foreach( $wp_roles->role_names as $role => $name ) {
			
            if(!in_array($role, $skipped_roles)){
				
                $user_roles[$role] = $name;
            }
        }

        return $user_roles;
    }
	
	public function get_available_roles(){
        global $wp_roles;
        $user_roles = array();

        if ( ! isset( $wp_roles ) ) 
            $wp_roles = new WP_Roles(); 

       // $skipped_roles = array('administrator');

        foreach( $wp_roles->role_names as $role => $name ) {
			
           // if(!in_array($role, $skipped_roles)){
				
                $user_roles[$role] = $name;
            //}
        }

        return $user_roles;
    }
	
	public function get_package_roles($package = null) 
	{
		global $wpdb, $wpuserspro;
		
		$display = "";				
		$allowed_user_roles = $this->get_available_user_roles();		
		$meta= 'wpuserspro_subscription_roles[]';
		
		
		$selected_roles = array();
		
		if($package!=null){
			
			if($package->membership_role!=''){
				$selected_roles = explode(',',$package->membership_role );		
			}
		
		}
		
           foreach ($allowed_user_roles as $key => $val)
		   {
			  $sel ="";
			   if(in_array($key,$selected_roles)) 
			   {
				   $sel = 'checked="checked"';
				  
			   }
			   
			   $display .= '<label>
   					 <input type="checkbox" name="'. $meta .'" value="'. $key .'" id="'.$meta.'"  '. $sel .'/>'. $val .'</label>';
			 
           }
          
		
									
		return  $display;
	
	
	}
	
	
	
}
$key = "role";
$this->{$key} = new WPUsersProRole();
?>