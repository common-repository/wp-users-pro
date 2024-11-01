<?php
class WPUsersProMembership
{
	var $table_prefix = 'wpuserspro';
	var $ajax_p = 'wpuserspro';
	
	var $sucess_message;
	
	var $errors = array();
	
	function __construct() 
	{
		$this->ini_db();
		$this->include_for_validation = array('text','fileupload',
		'textarea','select','radio','checkbox','password');	
		
		add_action( 'init', array($this, 'handle_init' ) );		
		add_action( 'wp_ajax_'.$this->ajax_p.'_subscription_edit', array( &$this, 'subscription_edit' ));
		add_action( 'wp_ajax_'.$this->ajax_p.'_edit_subscription_confirm', array( &$this, 'edit_subscription_confirm' ));
				
	
	}
	
	public function ini_db()
	{
		global $wpdb;	

				
		$query = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . ''.$this->table_prefix.'_membership_packages (
				`membership_id` bigint(20) NOT NULL auto_increment,							
				`membership_name` varchar(200) NOT NULL,									
				`membership_description` longtext ,
				`membership_role` varchar(300)  NULL,
				`membership_role_to_assign` varchar(150)  NULL,	
				`membership_access_categories` varchar(300)  NULL,
				`membership_registration_form` varchar(100)  NULL,
				`membership_stripe_id` varchar(300) NULL,		
				`membership_type` varchar(60) NOT NULL,
				`membership_number_of_times` varchar(60) NULL,
				`membership_every`  int(11) NOT NULL DEFAULT "0",
				`membership_lifetime`  int(1) NOT NULL DEFAULT "0",
				`membership_time_period` varchar(60)  NULL,
				`membership_trial_every`  int(11) NOT NULL DEFAULT "0",
				`membership_trial_time_period` varchar(60)  NULL,
				`membership_initial_amount` decimal(11,2) NULL,
				`membership_subscription_amount` decimal(11,2)  NULL,	
				`membership_approvation` int(11) NOT NULL DEFAULT "0",
				`membership_public_visible` int(1) NOT NULL DEFAULT "1",
				`membership_order` int(11) NOT NULL DEFAULT "0",
				`membership_status` int(11) NOT NULL DEFAULT "1",						 			
				PRIMARY KEY (`membership_id`)
			) COLLATE utf8_general_ci;';
	
	
		$wpdb->query( $query );	
		
		$query = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . ''.$this->table_prefix.'_membership_post_rel (
				`post_rel_id` bigint(20) NOT NULL auto_increment,	
				`post_rel_post_id`  int(11) NOT NULL DEFAULT "0",
				`post_rel_membership_id`  int(11) NOT NULL DEFAULT "0",
				PRIMARY KEY (`post_rel_id`)
			) COLLATE utf8_general_ci;';
	
		$wpdb->query( $query );	
		
		$query = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . ''.$this->table_prefix.'_role_post_rel (
				`role_rel_id` bigint(20) NOT NULL auto_increment,	
				`role_rel_post_id`  int(11) NOT NULL DEFAULT "0",
				`role_rel_role_id` varchar(300)  NULL,
				PRIMARY KEY (`role_rel_id`)
			) COLLATE utf8_general_ci;';
	
		$wpdb->query( $query );	
		
		
	}
	
	
	function handle_init() 
	{
		
		if (isset($_POST['wpuserspro_create_membership'])) {
			$this->handle_creation();
		}
		
		if (isset($_POST['wpuserspro_edit_membership'])) {
			$this->handle_edition();
		}
		
	}
	
	public function subscription_edit()
	{
		global $wpdb, $wpuserspro;	
		
		$html = '';		
		
		$date_format =  $wpuserspro->get_int_date_format();		
		$subscription_id =  sanitize_text_field($_POST['subscription_id']);		
		
		// Get Subscription
		$subscription = $wpuserspro->order->get_subscription($subscription_id);
		
		
		$date_from=  date($date_format, strtotime($subscription->subscription_start_date  ));
		$date_to=  date($date_format, strtotime($subscription->subscription_end_date  ));	
		
		$checked_lifetime = '';		
		if($subscription->subscription_lifetime==1)		
		{
			$date_to=__("Lifetime",'wp-users-pro');	
			$checked_lifetime = 'checked="checked"';				
		}
		
		
		
		$html .= '<div class="easywpm-membership-edit-form">' ;
	
			$html .= '<p>'.__('Start Date:','wp-users-pro').'</p>' ;	
			$html .= '<p><input class="easywpm-datepicker" type="text" id="date_from" value="'.$date_from.'" placeholder="'.__('Please input start date for this membership','wp-users-pro').'" ></p>' ;
			$html .= '<p>'.__('Expiration Date:','wp-users-pro').'</p>' ;	
			$html .= '<p><input class="easywpm-datepicker" type="text" id="date_to" value="'.$date_to.'" placeholder="'.__('Please input expiration date for this membership','wp-users-pro').'" ></p>' ;
			$html .= '<p>'.__('Status:','wp-users-pro').'</p>' ;	
			$html .='<div class="wptu-ticket-action-details-choices">	'; //status
		
			$html .= $this->get_all_status_listbox($subscription->subscription_status );	
			$html .='</div>';
		
			$html .= '<h4>'.__('Lifetime:','wp-users-pro').'</h4>' ;	
			
			$html .= '<input class="" title="'.__('Make Lifetime','wp-users-pro').'"  '.$checked_lifetime.' name="easywpm-makelifetime" id="easywpm-makelifetime" value="1" easywpm-makelifetime type="checkbox"><label for="easywpm-makelifetime">'.__('Make Lifetime Subscription','wp-users-pro').'</label>';
			$html .= '<input type="hidden" id="subscription_id" value="'.$subscription_id.'">' ;
			
			$html .= '<p id="easywpm-message-err"></p>' ;	
			
		$html .= '</div>' ;	
		
				
			
		echo $html ;		
		die();		
	
	}
	
	public function edit_subscription_confirm()
	{
		global $wpdb, $wpuserspro;			
		
		$subscription_id =  sanitize_text_field($_POST['subscription_id']);
		$date_from =  sanitize_text_field($_POST['date_from']);
		$date_to =  sanitize_text_field($_POST['date_to']);
		$membership_status =  sanitize_text_field($_POST['membership_status']);
		$make_lifetime =  sanitize_text_field($_POST['make_lifetime']);	
		
		$date_format = $wpuserspro->get_date_format_conversion();	
		$date_f = DateTime::createFromFormat($date_format, $date_from);
		$date_t = DateTime::createFromFormat($date_format, $date_to);
		
		$date_from_f =  $date_f->format('Y-m-d');	
		$date_to_f =  $date_t->format('Y-m-d');	
		
		if($subscription_id==''){	
			$response = array('response' => 'ERROR', 'content' => __('ID missed','wp-users-pro'));
				
		}elseif($date_from=='' || $date_to==''){
			$response = array('response' => 'ERROR', 'content' =>__('Please input start and end date','wp-users-pro'));
		
		}elseif($date_to < $date_from){
				$response = array('response' => 'ERROR', 'content' =>__('End date should be greater than start date
','wp-users-pro'));		
				
		}elseif($membership_status==''){
			$response = array('response' => 'ERROR', 'content' =>__('Please set a status','wp-users-pro'));
			
		}else{	
			
	
			$sql = $wpdb->prepare('UPDATE  ' . $wpdb->prefix . $this->table_prefix.'_subscriptions 
			SET subscription_start_date  =%s , subscription_end_date  =%s, subscription_status  =%s , subscription_lifetime  =%s WHERE subscription_id = %d ;',
			array($date_from_f, $date_to_f, $membership_status, $make_lifetime,  $subscription_id));	
				
			$results = $wpdb->query($sql);			
			$response = array('response' => 'OK', 'content' => $message);
		
		
		}		
			
		echo json_encode($response) ;				
		die();		
	
	}
	

	
	public function get_all_status_listbox ($status_id = NULL) 
	{
		global $wpdb, $wpuserspro;
		
		$html ='';
		
				
		$status_list = $wpuserspro->membership_status_options;		
		
		$html .= '<select name="membership_status" id="membership_status" data-errormessage-value-missing="'.__(' * This field is required!','wp-users-pro').'" class="validate[required]">';
		$html .= '<option value="" selected="selected">'.__('Select Status','wp-users-pro').'</option>';
		
		foreach ( $status_list as $key => $value )
		{
			$selected = '';
			
			$id = $value['value'];
			$text = $value['text'];			
			if($status_id==$id){$selected='selected="selected"';}			
			$html .= '<option  value="'.$id.'" '.$selected.' >'.$text.'</option>';
				
		}
		
		$html .= '</select>';
		
		return $html;
		
	}
	
	
	function handle_creation(){
		
		global $wpdb, $wpuserspro;
		
		$category_list = array();
		
		
		if($_POST['wpuserspro_subscription_name']=='')
		{
			$this->errors[] = __('<strong>ERROR:</strong> Please input Name.','wp-users-pro');
		
		}elseif($_POST['wpuserspro_subscription_desc']==''){
			
			$this->errors[] = __('<strong>ERROR:</strong> Please input a description.','wp-users-pro');
			
		}elseif($_POST['wpuserspro_subscription_initial_payment']=='' && $_POST['wpuserspro_subscription_type']=='onetime'){
			
			$this->errors[] = __('<strong>ERROR:</strong> Please input Initial Payment.','wp-users-pro');
			
		}elseif($_POST['wpuserspro_subscription_reccurring_amount']=='' && $_POST['wpuserspro_subscription_type']=='recurring'){
			
			$this->errors[] = __('<strong>ERROR:</strong> Please input Recurring Amount.','wp-users-pro');
			
		}else{
			
			if(!isset($_POST['wpuserspro_subscription_every']) || $_POST['wpuserspro_subscription_every']==''){
			
				$membership_every = 0;
			}else{
				
				$membership_every = sanitize_text_field($_POST['wpuserspro_subscription_every']);
			}
			
			//cateroies			
			if(isset($_POST['wpuserspro_subscription_categories'])){
			
				$category_list =  sanitize_text_field($_POST['wpuserspro_subscription_categories']);
			}
			
			$categories = '';
			$count_cate = count($category_list);
			$i=0;
			if($count_cate>0){
				
				foreach($category_list as  $category)
				{
					$i++;	
					$categories .= $category;
					
					if($i <$count_cate){
						
						$categories = $categories.',';						
					}
					
									
				
				}
			}
			
			//roles			
			if(isset($_POST['wpuserspro_subscription_roles'])){
			
				$rol_list =  sanitize_text_field($_POST['wpuserspro_subscription_roles']);
			}
			
			$roles = '';
			$count_role = count($rol_list);
			
			$i=0;
			
			if($count_role>0){
				
				foreach($rol_list as  $role)
				{
					$i++;					
					$roles .= $role;
					
					if($i <$count_role){
						
						$roles = $roles.',';				
					
					}				
				}
			}
			
			//we can create the membership				
			$new_record = array('membership_id' => NULL,	
								'membership_name' => $wpuserspro->get_post_value('wpuserspro_subscription_name'),	
								'membership_description' =>  $wpuserspro->get_post_value('wpuserspro_subscription_desc'),
								'membership_role' => $roles,		//one or multiple roles	
								'membership_role_to_assign' => $wpuserspro->get_post_value('wpuserspro_subscription_role_to_assign'),		//one or multiple roles	
								'membership_stripe_id' => $wpuserspro->get_post_value('membership_stripe_id'),	//this is the plan id from stripe								
								'membership_access_categories' => $categories, //multiple IDS
								'membership_registration_form' => $wpuserspro->get_post_value('wpuserspro_subscription_registration_form'),		//one or multiple roles	
								'membership_type' =>  $wpuserspro->get_post_value('wpuserspro_subscription_type'),		//one-time or recurring
								'membership_lifetime' =>  $wpuserspro->get_post_value('wpuserspro_subscription_lifetime'),	//lifetime
								'membership_number_of_times' =>  $wpuserspro->get_post_value('wpuserspro_subscription_number_of_times'),		
								'membership_time_period' =>  $wpuserspro->get_post_value('wpuserspro_subscription_period'),
								'membership_every' =>  $membership_every,										
								'membership_initial_amount' =>  $wpuserspro->get_post_value('wpuserspro_subscription_initial_payment'),									
								'membership_subscription_amount' =>  $wpuserspro->get_post_value('wpuserspro_subscription_reccurring_amount'),	
								'membership_approvation' =>  $wpuserspro->get_post_value('wpuserspro_subscription_requires_approvation'), // not required by default	
								'membership_public_visible' =>  $wpuserspro->get_post_value('wpuserspro_subscription_public_visible'),	
								'membership_order' => $wpuserspro->get_post_value('wpuserspro_display_order'),  
								'membership_status' =>  $wpuserspro->get_post_value('wpuserspro_status'),   	 
								
									
								);	
								
																	
			$wpdb->insert( $wpdb->prefix .$this->table_prefix.'_membership_packages', $new_record, 
			array( '%d', '%s' , '%s', '%s', '%s', '%s', '%s','%s', '%s', '%s', 
			'%s', '%s', '%s', '%s', '%s', '%s', '%s'  , '%s' ));
			
			//$wpdb->show_errors = true;			
			//$wpdb->print_error();
			
			$this->sucess_message = '<div class="easywpmembers-ultra-success"><span><i class="fa fa-check"></i>'.__("The subscription has been created successfully.",'wp-users-pro').'</span></div>';
					
		
		
		
		}
	
	
	
	}
	
	function handle_edition(){
		
		global $wpdb, $wpuserspro;
		
		
		if($_POST['wpuserspro_subscription_name']=='')
		{
			$this->errors[] = __('<strong>ERROR:</strong> Please input a Name.','wp-users-pro');
		
		
		}elseif(!isset($_POST['subscription_id']) || $_POST['subscription_id']==''){
			
			$this->errors[] = __('<strong>ERROR:</strong> Please input subscription ID.','wp-users-pro');
		
		}elseif($_POST['wpuserspro_subscription_desc']==''){
			
			$this->errors[] = __('<strong>ERROR:</strong> Please input a description.','wp-users-pro');
			
		}elseif($_POST['wpuserspro_subscription_initial_payment']==''  && $_POST['wpuserspro_subscription_type']=='onetime'){
			
			$this->errors[] = __('<strong>ERROR:</strong> Please input Initial Payment.','wp-users-pro');
			
		}elseif($_POST['wpuserspro_subscription_reccurring_amount']=='' && $_POST['wpuserspro_subscription_type']=='recurring'){
			
			$this->errors[] = __('<strong>ERROR:</strong> Please input Recurring Amount.','wp-users-pro');
			
		}else{
			
			
			//subscription_id			
			if(isset($_POST['subscription_id'])){
			
				$subscription_id =  sanitize_text_field($_POST['subscription_id']);
			}
			
			
			
			if(!isset($_POST['wpuserspro_subscription_every']) || $_POST['wpuserspro_subscription_every']==''){
			
				$membership_every = 0;
			}else{
				
				$membership_every = sanitize_text_field($_POST['wpuserspro_subscription_every']);
				
			
			}
			
			//cateroies			
			if(isset($_POST['wpuserspro_subscription_categories'])){
			
				$category_list = sanitize_text_field( $_POST['wpuserspro_subscription_categories']);
			}
			
			$categories = '';
			$count_cate = count($category_list);
			$i=0;
			if($count_cate>0){
				
				foreach($category_list as  $category)
				{
					$i++;	
					$categories .= $category;
					
					if($i <$count_cate){
						
						$categories = $categories.',';						
					}
					
									
				
				}
			}
			
			//roles			
			if(isset($_POST['wpuserspro_subscription_roles'])){
			
				$rol_list =  sanitize_text_field($_POST['wpuserspro_subscription_roles']);
			}
			
			$roles = '';
			$count_role = count($rol_list);
			
			$i=0;
			
			if($count_role>0){
				
				foreach($rol_list as  $role)
				{
					$i++;					
					$roles .= $role;
					
					if($i <$count_role){
						
						$roles = $roles.',';				
					
					}				
				}
			}
			
			//we can create the membership				
																	
			$sql = $wpdb->prepare('UPDATE  ' . $wpdb->prefix . $this->table_prefix.'_membership_packages
			 SET membership_name =%s,  membership_description =%s , 
			  membership_role =%s  ,  membership_role_to_assign =%s  , 
			  membership_stripe_id =%s ,  membership_access_categories =%s,
			  membership_registration_form =%s,  membership_type =%s ,  membership_lifetime =%s,
			  membership_number_of_times =%s,  membership_time_period =%s,
			  membership_every =%s , membership_initial_amount=%s , membership_subscription_amount=%s,
			  membership_approvation=%s,  membership_public_visible=%s, membership_order=%s , membership_status=%s
			  
			  
			  WHERE membership_id = %d ;',
			 array( $wpuserspro->get_post_value('wpuserspro_subscription_name'),
			        $wpuserspro->get_post_value('wpuserspro_subscription_desc'),
					$roles,
					$wpuserspro->get_post_value('wpuserspro_subscription_role_to_assign'),
					$wpuserspro->get_post_value('membership_stripe_id'),
					$categories,
					$wpuserspro->get_post_value('wpuserspro_subscription_registration_form'),
					$wpuserspro->get_post_value('wpuserspro_subscription_type'),
					$wpuserspro->get_post_value('wpuserspro_subscription_lifetime'),
					$wpuserspro->get_post_value('wpuserspro_subscription_number_of_times'),
					$wpuserspro->get_post_value('wpuserspro_subscription_period'),
					$membership_every,
					$wpuserspro->get_post_value('wpuserspro_subscription_initial_payment'),
					$wpuserspro->get_post_value('wpuserspro_subscription_reccurring_amount'),
					$wpuserspro->get_post_value('wpuserspro_subscription_requires_approvation'),
					$wpuserspro->get_post_value('wpuserspro_subscription_public_visible'),
					$wpuserspro->get_post_value('wpuserspro_display_order'),
					$wpuserspro->get_post_value('wpuserspro_status'),
			 $subscription_id));
		
			$results = $wpdb->query($sql); 
			
			//$wpdb->show_errors = true;			
			//$wpdb->print_error();
			
			$this->sucess_message = '<div class="easywpmembers-ultra-success"><span><i class="fa fa-check"></i>'.__("The subscription has been modified successfully.",'wp-users-pro').'</span></div>';
					
		
		
		
		}
	
	
	
	}
	
	public function post_membership_del($post_id)
	{
		global $wpdb;
		
		$group_id =  sanitize_text_field($_POST["group_id"]);		
		$query = "DELETE FROM " .  $wpdb->prefix . $this->table_prefix ."_membership_post_rel  WHERE  `post_rel_post_id` = '$post_id'  ";					
		$wpdb->query( $query );	
	}
	
	public function post_role_del($post_id)
	{
		global $wpdb;
		
		$group_id =  sanitize_text_field($_POST["group_id"]);		
		$query = "DELETE FROM " .  $wpdb->prefix . $this->table_prefix ."_role_post_rel  WHERE  `role_rel_post_id` = '$post_id'  ";					
		$wpdb->query( $query );	
	}
	
	public function save_post_role_rel($post_id, $role_id)
	{
		global $wpdb;	

		$new_array = array(
							'role_rel_id'     => null,
							'role_rel_post_id'   => $post_id,
							'role_rel_role_id'   => $role_id						
							
						);						
				
		$wpdb->insert(  $wpdb->prefix . $this->table_prefix  . '_role_post_rel', $new_array, array( '%d', '%s' , '%s'));
		
	}
	
	public function save_post_membership_rel($post_id, $membership_id)
	{
		global $wpdb;	

		$new_array = array(
							'post_rel_id'     => null,
							'post_rel_post_id'   => $post_id,
							'post_rel_membership_id'   => $membership_id						
							
						);						
				
		$wpdb->insert(  $wpdb->prefix . $this->table_prefix  . '_membership_post_rel', $new_array, array( '%d', '%s' , '%s'));
		
	}
	
	public function get_all_post_roles ($post_id) 
	{
		global $wpdb;
		
		$groups_list = array();
		
		$sql = ' SELECT * FROM ' .  $wpdb->prefix . $this->table_prefix . '_role_post_rel WHERE role_rel_post_id= "'.$post_id.'"  ' ;
		$res = $wpdb->get_results($sql);
		
		if ( !empty( $res ) )
		{
			foreach ( $res as $membership )
			{
				$groups_list[] = $membership->role_rel_role_id;
			
			}					
				
				
		}
		
		return $groups_list;	
	
	}
	
	public function get_all_post_memberships ($post_id) 
	{
		global $wpdb;
		
		$groups_list = array();
		
		$sql = ' SELECT * FROM ' .  $wpdb->prefix . $this->table_prefix . '_membership_post_rel WHERE post_rel_post_id= "'.$post_id.'"  ' ;
		$res = $wpdb->get_results($sql);
		
		if ( !empty( $res ) )
		{
			foreach ( $res as $membership )
			{
				$groups_list[] = $membership->post_rel_membership_id;
			
			}					
				
				
		}
		
		return $groups_list;	
	
	}
	
	function is_user_in_role( $user_id, $role  )
	{
		return in_array( $role, $this->get_all_user_roles_array( $user_id ) );
	}
	
	function get_all_user_roles_array ($user_id ) 
	{
		$user = new WP_User( $user_id );
		
		$html = array();;

		if ( !empty( $user->roles ) && is_array( $user->roles ) ) 
		{
			foreach ( $user->roles as $role )
				$html[]= $role;
		}
		
		return $html;
		
	}
	
	/*This returns all the active membership packages of a user*/
	public function get_all_user_expired_memberships ($user_id) 
	{
		global $wpdb;
		
		//status 1 or 2		
		$site_date = date( 'Y-m-d', current_time( 'timestamp', 0 ) );		
		$membership_list = array();	
			  
		$sql = ' SELECT sub.*, package.* FROM ' . $wpdb->prefix . $this->table_prefix . '_subscriptions sub  ' ;		
		$sql .= " RIGHT JOIN ".$wpdb->prefix . $this->table_prefix ."_membership_packages package ON (package.membership_id = sub.subscription_package_id )";		
		$sql .= " WHERE (package.membership_id = sub.subscription_package_id 
		          AND sub.subscription_user_id = '".$user_id."'   ";
		$sql .= " AND  sub.subscription_end_date < '".$site_date."') ";	
		
		  
		$res = $wpdb->get_results($sql);
		
		if ( !empty( $res ) )
		{
			foreach ( $res as $package )
			{
					
				$membership_list[] = $package->membership_id;			
							
			
			}				
		}
		
		return $membership_list;	
	
	}
	
	/*This returns all the active packages of a user*/
	public function get_all_user_active_memberships ($user_id) 
	{
		global $wpdb;
		
		//status 1 or 2		
		$site_date = date( 'Y-m-d', current_time( 'timestamp', 0 ) );		
		$membership_list = array();	
			  
		$sql = ' SELECT sub.*, package.* FROM ' . $wpdb->prefix . $this->table_prefix . '_subscriptions sub  ' ;		
		$sql .= " RIGHT JOIN ".$wpdb->prefix . $this->table_prefix ."_membership_packages package ON (package.membership_id = sub.subscription_package_id )";		
		$sql .= " WHERE package.membership_id = sub.subscription_package_id AND sub.subscription_user_id = '".$user_id."' AND sub.subscription_status IN(1,2) ";	
		  
		$res = $wpdb->get_results($sql);
		
		if ( !empty( $res ) )
		{
			foreach ( $res as $package )
			{
				//check if expired or is a lifetime membership			
				if($package->subscription_end_date >=$site_date ||$package->subscription_lifetime == '1' ){
					
					$membership_list[] = $package->membership_id;				
				}			
			
			}				
		}
		
		return $membership_list;	
	
	}
	
	function do_i_have_a_this_plan($user_id, $subscription_plan){
		
		//is this user allowed to see this post.				
		$user_memberships =$this->get_all_user_active_memberships($user_id);
		
		foreach ($user_memberships as $membership)
		{					
			if($membership== $subscription_plan)
			{
				return true; //user belongs to this group					
			}				
		
		}
		
		$res = false;	
	
	}
	
	function get_active_subscrition_plan($user_id, $subscription_plan){
		
		//is this user allowed to see this post.				
		$user_memberships =$this->get_all_user_active_subscription_plans($user_id);
		foreach ($user_memberships as $membership)
		{
			
			if($membership->subscription_package_id== $subscription_plan)
			{
				return $membership; //user belongs to this group					
			}				
		
		}
		
			
	}
	
	/*This returns all the active subscriptions of this user*/
	public function get_all_user_active_subscription_plans ($user_id) 
	{
		global $wpdb;
		
		//status 1 or 2		
		$site_date = date( 'Y-m-d', current_time( 'timestamp', 0 ) );		
		$membership_list = array();	
			  
		$sql = ' SELECT sub.*, package.* FROM ' . $wpdb->prefix . $this->table_prefix . '_subscriptions sub  ' ;		
		$sql .= " RIGHT JOIN ".$wpdb->prefix . $this->table_prefix ."_membership_packages package ON (package.membership_id = sub.subscription_package_id )";		
		$sql .= " WHERE package.membership_id = sub.subscription_package_id AND sub.subscription_user_id = '".$user_id."' AND sub.subscription_status IN(1,2) ";	
		  
		$res = $wpdb->get_results($sql);
		
		if ( !empty( $res ) )
		{
			foreach ( $res as $package )
			{
				//check if expired or is a lifetime membership			
				if($package->subscription_end_date >=$site_date ||$package->subscription_lifetime == '1' ){
					
					$membership_list[] = $package;				
				}			
			
			}				
		}
		
		return $membership_list;	
	
	}
	
	
	/*This returns all the active membership of the website*/
	public function get_all_active_memberships () 
	{
		global $wpdb;
		
		//status 1 or 2 or lifetime
		$total = 0;
		
		$site_date = date( 'Y-m-d', current_time( 'timestamp', 0 ) );		
		$membership_list = array();	
			  
		$sql = ' SELECT count(*) as total, sub.*, package.*, usu.* FROM ' . $wpdb->prefix . $this->table_prefix . '_subscriptions sub  ' ;		
		$sql .= " RIGHT JOIN ".$wpdb->prefix . $this->table_prefix ."_membership_packages package ON (package.membership_id = sub.subscription_package_id )";		
		$sql .= " RIGHT JOIN ".$wpdb->users ." usu ON (usu.ID = sub.subscription_user_id)";
		$sql .= " WHERE (package.membership_id = sub.subscription_package_id  AND sub.subscription_status IN(1,2) ";	
		$sql .= " AND sub.subscription_end_date >='".$site_date."')  ";	
		
		$sql .= " OR (package.membership_id = sub.subscription_package_id  AND sub.subscription_status IN(1,2) ";	
		$sql .= " AND sub.subscription_lifetime  ='1' )  ";	

		
		//echo $sql;
 
		$res = $wpdb->get_results($sql);
		
		if ( !empty( $res ) )
		{
			foreach ( $res as $row )
			{
				$total = $row->total; 
					
			
			}				
		}
		
		return $total;	
	
	}
	
	
	/*This returns the total of active subscription plans*/
	public function get_total_active_subscription_plans () 
	{
		global $wpdb;
		
		
		$total = 0;
		$sql = ' SELECT count(*) as total FROM ' . $wpdb->prefix . $this->table_prefix . '_membership_packages sub  ' ;		
		$sql .= " WHERE sub.membership_status = '1' ";	
		$res = $wpdb->get_results($sql);
		
		if ( !empty( $res ) )
		{
			foreach ( $res as $row )
			{
				$total = $row->total; 
					
			
			}				
		}
		
		return $total;	
	
	}
	
	/*This returns all the active membership of the website*/
	public function get_all_expired_memberships () 
	{
		global $wpdb;
		
		//status 1 or 2
		$total = 0;
		
		$site_date = date( 'Y-m-d', current_time( 'timestamp', 0 ) );		
		$membership_list = array();	
			  
		$sql = ' SELECT count(*) as total, sub.*, package.*, usu.* FROM ' . $wpdb->prefix . $this->table_prefix . '_subscriptions sub  ' ;		
		$sql .= " RIGHT JOIN ".$wpdb->prefix . $this->table_prefix ."_membership_packages package ON (package.membership_id = sub.subscription_package_id )";		
		$sql .= " RIGHT JOIN ".$wpdb->users ." usu ON (usu.ID = sub.subscription_user_id)";
		$sql .= " WHERE package.membership_id = sub.subscription_package_id  AND sub.subscription_status IN(1,2) ";	
		$sql .= " AND sub.subscription_end_date <'".$site_date."' AND sub.subscription_lifetime  <> '1' ";	
		
		//echo $sql;
 
		$res = $wpdb->get_results($sql);
		
		if ( !empty( $res ) )
		{
			foreach ( $res as $row )
			{
				$total = $row->total; 
					
			
			}				
		}
		
		return $total;	
	
	}
	
	
	/*This returns all the latest subscriptions*/
	public function get_latest_subscriptions ($howmany) 
	{
		global $wpdb;
		
		$site_date = date( 'Y-m-d', current_time( 'timestamp', 0 ) );		
		$membership_list = array();	
			  
		$sql = ' SELECT sub.*, package.*, usu.* 
		FROM ' . $wpdb->prefix . $this->table_prefix . '_subscriptions sub  ' ;	
			
		$sql .= " RIGHT JOIN ".$wpdb->prefix . $this->table_prefix ."_membership_packages package ON (package.membership_id = sub.subscription_package_id )";		
		$sql .= " RIGHT JOIN ".$wpdb->users ." usu ON (usu.ID = sub.subscription_user_id)";

		$sql .= " WHERE package.membership_id = sub.subscription_package_id  ";	
		$sql .= " AND usu.ID = sub.subscription_user_id ";			
		$sql .= " ORDER BY sub.subscription_id DESC";			
	   	$sql .= " LIMIT $howmany"; 
 
		$res = $wpdb->get_results($sql);
		
		return $res;	
	
	}
	
	/*Get all*/
	public function get_all_filtered ()
	{
		global $wpdb,  $wpuserspro;
		
			
		$keyword = "";
		$month = "";
		$day = "";
		$year = "";
		$howmany = "";
		$ini = "";
		
		$special_filter='';
        $bp_status='';

		
		if(isset($_GET["bp_keyword"]))
		{
			$keyword = sanitize_text_field($_GET["bp_keyword"]);		
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
		
		if(isset($_GET["howmany"]))
		{
			$howmany = sanitize_text_field($_GET["howmany"]);		
		}
		
		if(isset($_GET["bp_special_filter"]))
		{
			$special_filter = sanitize_text_field($_GET["bp_special_filter"]);		
		}
		
		if(isset($_GET["bp_status"]))
		{
			$bp_status = sanitize_text_field($_GET["bp_status"]);		
		}
		
		if(isset($_GET["bp_sites"]))
		{
			$bp_sites = sanitize_text_field($_GET["bp_sites"]);		
		}
		
		$uri= $_SERVER['REQUEST_URI'] ;
		$url = explode("&ini=",$uri);
		
		if(is_array($url ))
		{
			//print_r($url);
			if(isset($url["1"]))
			{
				$ini = $url["1"];
			    if($ini == ""){$ini=1;}
			
			}
		
		}		
		
		if($howmany == ""){$howmany=50;}
		
		$sql = ' SELECT count(*) as total, sub.*, package.*, usu.* 
		FROM ' . $wpdb->prefix . $this->table_prefix . '_subscriptions sub  ' ;	
			
		$sql .= " RIGHT JOIN ".$wpdb->prefix . $this->table_prefix ."_membership_packages package ON (package.membership_id = sub.subscription_package_id )";		
		$sql .= " RIGHT JOIN ".$wpdb->users ." usu ON (usu.ID = sub.subscription_user_id)";

		$sql .= " WHERE package.membership_id = sub.subscription_package_id  ";	
		$sql .= " AND usu.ID = sub.subscription_user_id ";			
		
		
		if($bp_status !='')	
		{
			//display only ticket assigned to the staff'de partment			
			$sql .= " AND sub.subscription_status = '".$bp_status."' ";
			
		}
		
		if($keyword !='')	
		{				
			$sql .= " AND (usu.display_name LIKE '%".$keyword."%' OR sub.subscription_merchant_id  LIKE '%".$keyword."%')";
			
		}
		
		if($day!=""){$sql .= " AND DAY(sub.subscription_date ) = '$day'  ";	}
		if($month!=""){	$sql .= " AND MONTH(sub.subscription_date ) = '$month'  ";	}		
		if($year!=""){$sql .= " AND YEAR(sub.subscription_date ) = '$year'";}	
		
		$orders = $wpdb->get_results($sql );
		$orders_total = $this->fetch_result($orders);
		$orders_total = $orders_total->total;
		$this->total_result = $orders_total ;
		
		$total_pages = $orders_total;
				
		$limit = "";
		$current_page = $ini;
		$target_page =  site_url()."/wp-admin/admin.php?page=bookingultra&tab=appointments";
		
		$how_many_per_page =  $howmany;
		
		$to = $how_many_per_page;
		
		//caluculate from
		$from = $this->calculate_from($ini,$how_many_per_page,$orders_total );
		
		//get all			
		$sql = ' SELECT sub.*, package.*, usu.* 
		FROM ' . $wpdb->prefix . $this->table_prefix . '_subscriptions sub  ' ;	
			
		$sql .= " RIGHT JOIN ".$wpdb->prefix . $this->table_prefix ."_membership_packages package ON (package.membership_id = sub.subscription_package_id )";		
		$sql .= " RIGHT JOIN ".$wpdb->users ." usu ON (usu.ID = sub.subscription_user_id)";

		$sql .= " WHERE package.membership_id = sub.subscription_package_id  ";	
		$sql .= " AND usu.ID = sub.subscription_user_id ";			
		
		
		if($bp_status !='')	
		{
			//display only ticket assigned to the staff'de partment			
			$sql .= " AND sub.subscription_status = '".$bp_status."' ";
			
		}
		
		if($keyword !='')	
		{				
			$sql .= " AND (usu.display_name LIKE '%".$keyword."%' OR sub.subscription_merchant_id  LIKE '%".$keyword."%')";
			
		}
		
		if($day!=""){$sql .= " AND DAY(sub.subscription_date ) = '$day'  ";	}
		if($month!=""){	$sql .= " AND MONTH(sub.subscription_date ) = '$month'  ";	}		
		if($year!=""){$sql .= " AND YEAR(sub.subscription_date ) = '$year'";}	
		
		$sql .= " ORDER BY sub.subscription_id DESC";		
		
	    if($from != "" && $to != ""){	$sql .= " LIMIT $from,$to"; }
	 	if($from == 0 && $to != ""){	$sql .= " LIMIT $from,$to"; }
		
		//echo $sql;
		
					
		$rows = $wpdb->get_results($sql );
		
		return $rows ;
		
	
	}
	
	public function calculate_from($ini, $howManyPagesPerSearch, $total_items)	
	{
		if($ini == ""){$initRow = 0;}else{$initRow = $ini;}
		
		if($initRow<= 1) 
		{
			$initRow =0;
		}else{
			
			if(($howManyPagesPerSearch * $ini)-$howManyPagesPerSearch>= $total_items) {
				$initRow = $totalPages-$howManyPagesPerSearch;
			}else{
				$initRow = ($howManyPagesPerSearch * $ini)-$howManyPagesPerSearch;
			}
		}
		
		
		return $initRow;
		
		
	}
	
	
	public function fetch_result($results)
	{
		if ( empty( $results ) )
		{
		
		
		}else{
			
			
			foreach ( $results as $result )
			{
				return $result;			
			
			}
			
		}
		
	}
	
	public function get_subscription_status_legend ($status) 
	{
		global $wpdb, $wpuserspro;
		
		//status   0 - pending
		//         1 - active
		//         2 - cancelled
		//         3 - recurring payment failed
		//         4 - recurring agreement expired
		
		$ret = '';
		
		if($status==0){
			
			$ret = __( 'Pending', 'wp-users-pro' );
		
		}elseif($status==1){
			
			$ret = __( 'Active', 'wp-users-pro' );
		
		}elseif($status==2){
			
			$ret = __( 'Cancelled', 'wp-users-pro' );
			
		}elseif($status==3){
			
			$ret = __( 'Recurring Failed', 'wp-users-pro' );
			
		}elseif($status==4){
			
			$ret = __( 'Expired', 'wp-users-pro' );
			
		}
		
		return $ret;
		
	
	}
	
	public function get_periods ($package) 
	{
		global $wpdb, $wpuserspro;
		
		 $ini_date = date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) );
		
		 $NewDate = new DateTime($ini_date);
		 $to_sum=  $package->membership_every;  
		 
		 if($package->membership_time_period =='M'){ //months
		 
		 	$period = 'P'.$to_sum.'M';			
			$NewDate->add(new DateInterval($period));
			$valid_until = $NewDate->format('Y-m-d H:i:s');
			 
		 }elseif($package->membership_time_period =='D'){ //days
		 
		 
		 	$period = 'P'.$to_sum.'D';			
			$NewDate->add(new DateInterval($period));
			$valid_until = $NewDate->format('Y-m-d H:i:s');
			 
		 }elseif($package->membership_time_period =='W'){ //weeks
		 
		 	$period = 'P'.$to_sum.'W';			
			$NewDate->add(new DateInterval($period));
			$valid_until = $NewDate->format('Y-m-d H:i:s');
		 
		 
		 }elseif($package->membership_time_period =='Y'){ //years
		 
		 	$period = 'P'.$to_sum.'Y';			
			$NewDate->add(new DateInterval($period));
			$valid_until = $NewDate->format('Y-m-d H:i:s');	  
		  
		 }
		 
		
		return array('starts' => $ini_date, 'ends' =>$valid_until);
		
		
	}
	
	public function get_all () 
	{
		global $wpdb, $wpuserspro;
		
		$sql = ' SELECT * FROM ' . $wpdb->prefix . $this->table_prefix.'_membership_packages ORDER BY membership_order    ' ;		
		$res = $wpdb->get_results($sql);
		return $res; 
	}
	
	public function get_all_public () 
	{
		global $wpdb, $wpuserspro;
		
		$sql = ' SELECT * FROM ' . $wpdb->prefix . $this->table_prefix.'_membership_packages WHERE membership_status = 1 AND membership_public_visible = 1 ORDER BY membership_order    ' ;		
		$res = $wpdb->get_results($sql);
		return $res; 
	}
	
	public function get_all_active () 
	{
		global $wpdb, $wpuserspro;
		
		$sql = ' SELECT * FROM ' . $wpdb->prefix . $this->table_prefix.'_membership_packages WHERE membership_status = 1 ORDER BY membership_order    ' ;		
		$res = $wpdb->get_results($sql);
		return $res; 
	}
	
	/*Get All Packages*/
	public function get_use_backend_packages ( $custom_form= NULL)
	{
		global $wpdb,  $wpuserspro;
		
		$currency_symbol =  $wpuserspro->get_option('paid_membership_symbol');
		
		
		$html = "";
		
		$packages = $this->get_all_public();
		
		if ( empty( $packages ) )
			{
				$html.= '<p>' .__( 'You have no packages yet.', 'wp-users-pro' ). '</p>';
			
		}else{
				
				
				$html .= "<div class='easywpm-packages-backend-list'>" ;
				$html .= "<ul>" ;
				
				$n = count( $packages );
				$num_unread = 0;
				
				$default_checked = 0;
				
				$plan_id = "";
				
				if(isset($_GET["plan_id"]) && $_GET["plan_id"]>0)
				{
					$plan_id =sanitize_text_field($_GET["plan_id"]);
				
				}
				
				//display only selected
				
				$display_only_selected =  $wpuserspro->get_option('membership_display_selected_only');
				$only_selected = false;
				if( $display_only_selected== 1)
				{
					$only_selected = true;
						
				}
				
				foreach ( $packages as $package )
				{
					
					$custom_form = str_replace('wpuserspro_profile_fields_','',$custom_form);
					
					if($custom_form!='' && $custom_form!=$package->package_registration_form){continue;}		
					
					
					$checked = '';
					
					$required_class = ' validate[required]';
					
					if( $plan_id== $package->membership_id  && $package->package_amount!=0)
					{
						$checked = 'checked="checked"';
						
					}
					
					$amount = $currency_symbol.$package->package_amount;
					
					
					if( $package->membership_type=='recurring'){
					
						$amount = $wpuserspro->get_formated_amount_with_currency($package->membership_subscription_amount);
				
					}else{
						
						$amount = $wpuserspro->get_formated_amount_with_currency($package->membership_initial_amount);

					}
					
					$amount =  $wpuserspro->get_formated_agreement($package);			
				
				
					
					$is_free = '0';
					
					if($package->membership_type=='onetime' && $package->membership_initial_amount ==0)
					{
						$is_free = '1';
					
					}
					
					
					if($only_selected )
					{
						
						if($package->membership_id==$plan_id || $plan_id=="")
						{							
							
							$html.= '<li> 
							
							<div class="easywpm-package-list-front ">							
							<span class="uultra-package-title">
							
							<input type="radio" name="wpuserspro_package_id" value="'.$package->package_id.'" id="RadioGroup1_'.$package->package_id.'"  '.$checked.' class="uultra-front-end-memberpackages '.$required_class.' easywpm-front-check-package" is-free-package="'.$is_free.'"/><label for="RadioGroup1_'.$package->package_id.'">'.$package->package_name.' </label></span>
							
							<span class="uultra-package-cost">'.$amount.' </span></div>
							<div class="uultra-package-desc">
							<p>'.$package->membership_description.'</p>
							</div>		
								
							</li>';
						
						}else{
							
						}
					
					}else{
						
						
					$html.= '<li> 
					
					<div class="easywpm-package-list-front ">	
					
					<span class="uultra-package-title">
					<input type="radio" name="wpuserspro_package_id" value="'.$package->membership_id.'" id="RadioGroup1_'.$package->membership_id.'"  '.$checked.'  class="uultra-front-end-memberpackages '.$required_class.' easywpm-front-check-package" is-free-package="'.$is_free.'"/><label for="RadioGroup1_'.$package->membership_id.'">'.$package->membership_name.' </label></span>
					
					<span class="uultra-package-cost">'.$amount.' </span></div>
					<div class="uultra-package-desc">
					<p>'.$package->membership_description.'</p>
					</div>		
						
	     			</li>';
						
					} 
					
					
		 
		 $default_checked++;
				
				
				} // end while
				
				
				
				$html .= "</ul>" ;
				$html .= "</div>" ;
		}
		
		return $html;
		
	}
	
	/*Get All Active Plans List Box*/
	public function get_sub_plans_box ( $selected= NULL)
	{
		global $wpdb,  $wpuserspro;
		
		$html = '';
		
		$plans = $this->get_all_active();
		
		$html .= '<select name="drip_plan" id="drip_plan">';
				
		if($selected==''){
		 $html .= '<option value="" selected="selected"  >' .__('Choose Subscription Plan', 'wp-users-pro') . '</option>';
		}
		  
		  foreach($plans as $plan) {	
		  
			  $sel = '';
			  
			  if($plan->membership_id==$selected ){$sel ='selected="selected"';}
			  
			  $html .= '<option value="' . $plan->membership_id. '" '.$sel.' >' . $plan->membership_name. '</option>';
		  }
	  
		$html .= '</select>';
		
	
		return $html;
	}
	
	/*Get All Packages*/
	public function get_public_packages ( $custom_form= NULL)
	{
		global $wpdb,  $wpuserspro;
		
		$currency_symbol =  $wpuserspro->get_option('paid_membership_symbol');
		
		
		$html = "";
		
		$packages = $this->get_all_public();
		
		if ( empty( $packages ) )
			{
				$html.= '<p>' .__( 'You have no packages yet.', 'wp-users-pro' ). '</p>';
			
		}else{
				
				
				$html .= "<div class='easywpm-packages-front-list'>" ;
				$html .= "<ul>" ;
				
				$n = count( $packages );
				$num_unread = 0;
				
				$default_checked = 0;
				
				$plan_id = "";
				
				if(isset($_GET["plan_id"]) && $_GET["plan_id"]>0)
				{
					$plan_id =sanitize_text_field($_GET["plan_id"]);
				
				}
				
				//display only selected
				
				$display_only_selected =  $wpuserspro->get_option('membership_display_selected_only');
				$only_selected = false;
				if( $display_only_selected== 1)
				{
					$only_selected = true;
						
				}
				
				foreach ( $packages as $package )
				{
					
					$custom_form = str_replace('wpuserspro_profile_fields_','',$custom_form);
					
					if($custom_form!='' && $custom_form!=$package->package_registration_form){continue;}		
					
					
					$checked = '';
					
					$required_class = ' validate[required]';
					
					if( $plan_id== $package->membership_id  && $package->package_amount!=0)
					{
						$checked = 'checked="checked"';
						
					}
                    
                    $p_ammount = "";
                    
                    if( isset($package->package_amount))
					{
						 $p_ammount = $package->package_amount;
						
					}
					
					$amount = $currency_symbol.$p_ammount;
					
					
					if( $package->membership_type=='recurring'){
					
						$amount = $wpuserspro->get_formated_amount_with_currency($package->membership_subscription_amount);
				
					}else{
						
						$amount = $wpuserspro->get_formated_amount_with_currency($package->membership_initial_amount);

					}
					
					$amount =  $wpuserspro->get_formated_agreement($package);			
				
				
					
					$is_free = '0';
					
					if($package->membership_type=='onetime' && $package->membership_initial_amount ==0)
					{
						$is_free = '1';
					
					}
					
					
					if($only_selected )
					{
						
						if($package->membership_id==$plan_id || $plan_id=="")
						{							
							
							$html.= '<li> 
							
							<div class="easywpm-package-list-front ">							
							<span class="uultra-package-title">
							
							<input type="radio" name="wpuserspro_package_id" value="'.$package->package_id.'" id="RadioGroup1_'.$package->package_id.'"  '.$checked.' class="uultra-front-end-memberpackages '.$required_class.' easywpm-front-check-package" is-free-package="'.$is_free.'"/><label for="RadioGroup1_'.$package->package_id.'"><span><span></span></span>'.$package->package_name.' </label></span>
							
							<span class="uultra-package-cost">'.$amount.' </span></div>
							<div class="uultra-package-desc">
							<p>'.$package->membership_description.'</p>
							</div>		
								
							</li>';
						
						}else{
							
						}
					
					}else{
						
						
					$html.= '<li> 
					
					<div class="easywpm-package-list-front ">	
					
					<span class="uultra-package-title">
					<input type="radio" name="wpuserspro_package_id" value="'.$package->membership_id.'" id="RadioGroup1_'.$package->membership_id.'"  '.$checked.'  class="uultra-front-end-memberpackages '.$required_class.' easywpm-front-check-package" is-free-package="'.$is_free.'"/><label for="RadioGroup1_'.$package->membership_id.'"><span><span></span></span>'.$package->membership_name.' </label></span>
					
					<span class="uultra-package-cost">'.$amount.' </span></div>
					<div class="uultra-package-desc">
					<p>'.$package->membership_description.'</p>
					</div>		
						
	     			</li>';
						
					} 
					
					
		 
		 $default_checked++;
				
				
				} // end while
				
				
				
				$html .= "</ul>" ;
				$html .= "</div>" ;
		}
		
		return $html;
		
	}
	
	public function is_initial_payment ($id) 
	{
		global $wpdb, $wpuserspro;
		
		$sql = ' SELECT * FROM ' . $wpdb->prefix .$this->table_prefix. '_orders' ;
		$sql .= ' WHERE order_subscription_id  = "'.(int)$id.'" AND order_ini = 1 ' ;			
				
		$res = $wpdb->get_results($sql);
		
		if ( !empty( $res ) )
		{
		
			foreach ( $res as $row )
			{
				return false;			
			
			}
			
		}else{
			
			return true;
			
			
		}
	
	}
	
	
	/*Get errors display*/
	function get_errors() {
		
		$display = null;
		if (isset($this->errors) && count($this->errors)>0) {
			
			$display .= '<div class="easywpmembers-ultra-error">';
			foreach($this->errors as $newError) {
				
				$display .= '<span class="easywpmembers-error-block"><i class="easywpmembers-icon-remove"></i>'.$newError.'</span>';
			
			}
			
			$display .= '</div>';
			
		} else {
		
						
		}
		return $display;
	}
	
	public function get_one ($id) 
	{
		global $wpdb, $wpuserspro;
		
		$sql = ' SELECT * FROM ' . $wpdb->prefix .$this->table_prefix. '_membership_packages' ;
		$sql .= ' WHERE membership_id = "'.(int)$id.'"' ;			
				
		$res = $wpdb->get_results($sql);
		
		if ( !empty( $res ) )
		{
		
			foreach ( $res as $row )
			{
				return $row;			
			
			}
			
		}	
	
	}
	
}
$key = "membership";
$this->{$key} = new WPUsersProMembership();
?>