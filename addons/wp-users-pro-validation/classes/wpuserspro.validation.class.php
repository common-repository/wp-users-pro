<?php
class WPUsersProValidation
{
	var $ajax_p = 'wpuserspro';
	var $table_prefix = 'wpuserspro';
	var $sucess_message = '';
	
	
		
	public function __construct()
	{
		
		/* Plugin slug and version */
		$this->slug = 'wpuserspro';
		$this->subslug = 'wpuserspro-validation';
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		$this->plugin_data = get_plugin_data( wpuserspro_validation_path . 'index.php', false, false);
		$this->version = $this->plugin_data['Version'];
		
		add_action( 'wp_ajax_'.$this->ajax_p.'_vv_c_de_a', array( &$this, 'wpuserspro_vv_c_de_a' ));
			
	
				
    }
	
	
	public function do_valid_checks()
	{
		
		global $wpuserspro_activation ;
		
		$va = get_option('wpuserspro_c_key');
		
		if(isset($wpuserspro_activation))		
		{		
			if($va=="")
			{
				//
				$this->valid_c = "no";
			
			}
		}
	}
	
	public function wpuserspro_vv_c_de_a () 
	{		
		global $wpuserspro, $wpdb ;
		
		 	
		$p = sanitize_text_field($_POST["p_s_le"]);		
		
		//validate ulr
		
		$domain = $_SERVER['SERVER_NAME'];		
		$server_add = $_SERVER['SERVER_ADDR'];
		
		
		$url = wpuserspro_pro_url."check_l_p.php";	
		
		//echo "URL " .$url;
		$response = wp_remote_post(
            $url,
            array(
                'body' => array(
                    'd'   => $domain,
                    'server_ip'     => $server_add,
                    'sial_key' => $p,
					'action' => 'validate',
					
                )
            )
        );
		
		
		$response = json_decode($response["body"]);
		
		$message =$response->{'message'}; 
		$result =$response->{'result'}; 
		$expiration =$response->{'expiration'};
		$serial =$response->{'serial'};
		
		//validate
		
		if ( is_multisite() ) // See if being activated on the entire network or one blog
		{		
			
	 
			// Get this so we can switch back to it later
			$current_blog = $wpdb->blogid;
			// For storing the list of activated blogs
			$activated = array();
			
			// Get all blogs in the network and activate plugin on each one
			
			$args = array(
				'network_id' => $wpdb->siteid,
				'public'     => null,
				'archived'   => null,
				'mature'     => null,
				'spam'       => null,
				'deleted'    => null,
				'limit'      => 100,
				'offset'     => 0,
			);
			$blog_ids = wp_get_sites( $args ); 
		   // print_r($blog_ids);
		
		
			foreach ($blog_ids as $key => $blog)
			{
				$blog_id = $blog["blog_id"];

				switch_to_blog($blog_id);				
				
				if($result =="OK")
				{
					update_option('wpuserspro_c_key',$serial );
					update_option('wpuserspro_c_expiration',$expiration );
					
					$html = '<div class="easywpmembers-ultra-success">'. __("Congratulations!. Your copy has been validated", 'wp-users-pro').'</div>';
				
				}elseif($result =="EXP"){
					
					update_option('wpuserspro_c_key',"" );
					update_option('wpuserspro_c_expiration',$expiration );
					
					$html = '<div class="easywpmembers-ultra-error">'. __("We are sorry the serial key you have used has expired", 'wp-users-pro').'</div>';
				
				}elseif($result =="NO"){
					
					//update_option('wpuserspro_c_key',"" );
					//update_option('wpuserspro_c_expiration',$expiration );
					
					$html = '<div class="easywpmembers-ultra-error">'. __("We are sorry your serial key is not valid", 'wp-users-pro').'</div>';
				
				}
				
				
			} //end for each
			
			//echo "current blog : " . $current_blog;
			// Switch back to the current blog
			switch_to_blog($current_blog); 
			
			
		}else{
			
			//this is not a multisite
			
			if($result =="OK")
			{
				update_option('wpuserspro_c_key',$serial );
				update_option('wpuserspro_c_expiration',$expiration );
				
				$html = '<div class="easywpmembers-ultra-success">'. __("Congratulations!. Your copy has been validated", 'wp-users-pro').'</div>';
			
			}elseif($result =="EXP"){
				
				update_option('wpuserspro_c_key',"" );
				update_option('wpuserspro_c_expiration',$expiration );
				
				$html = '<div class="easywpmembers-ultra-error">'. __("We are sorry the serial key you have used has expired", 'wp-users-pro').'</div>';
			
			}elseif($result =="NO"){
				
				$html = '<div class="easywpmembers-ultra-error">'. __("We are sorry your serial key is not valid", 'wp-users-pro').'</div>';
			
			}
			
			
		}
		
		//
		//echo "Domain: " .$domain;
		echo $html;		 
		
		die();
		
	}
	
}
?>