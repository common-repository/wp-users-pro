<?php
class WpUsersProAdmin extends WpUsersProCommon 
{

	var $options;
	var $wp_all_pages = false;
	var $wpuserspro_default_options;
	var $valid_c;
	
	var $ajax_prefix = 'wpuserspro';	
	var $table_prefix = 'wpuserspro';
	
	var $notifications_email = array();

	function __construct() {
	
		/* Plugin slug and version */
		$this->slug = 'wpuserspro';
		
		$this->set_default_email_messages();				
		$this->update_default_option_ini();		
		$this->set_font_awesome();
		
		
		add_action('admin_menu', array(&$this, 'add_menu'), 11);
	
		add_action('admin_enqueue_scripts', array(&$this, 'add_styles'), 9);
		add_action('admin_head', array(&$this, 'admin_head'), 9 );
		add_action('admin_init', array(&$this, 'admin_init'), 9);
		add_action('admin_init', array(&$this, 'do_valid_checks'), 9);
				
		add_action( 'wp_ajax_'.$this->ajax_prefix.'_save_fields_settings', array( &$this, 'save_fields_settings' ));
				
		add_action( 'wp_ajax_'.$this->ajax_prefix.'_add_new_custom_profile_field', array( &$this, 'add_new_custom_profile_field' ));
		add_action( 'wp_ajax_'.$this->ajax_prefix.'_delete_profile_field', array( &$this, 'delete_profile_field' ));
		add_action( 'wp_ajax_'.$this->ajax_prefix.'_sort_fileds_list', array( &$this, 'sort_fileds_list' ));
		
		//user to get all fields
		add_action( 'wp_ajax_'.$this->ajax_prefix.'_reload_custom_fields_set', array( &$this, 'reload_custom_fields_set' ));
		
		//used to edit a field
		add_action( 'wp_ajax_'.$this->ajax_prefix.'_reload_field_to_edit', array( &$this, 'reload_field_to_edit' ));			
		add_action( 'wp_ajax_'.$this->ajax_prefix.'_custom_fields_reset', array( &$this, 'custom_fields_reset' ));			
		add_action( 'wp_ajax_'.$this->ajax_prefix.'_create_uploader_folder', array( &$this, 'create_uploader_folder' ));
		add_action( 'wp_ajax_'.$this->ajax_prefix.'_reset_email_template', array( &$this, 'reset_email_template' ));
		
	}
	
	function admin_init() 
	{
		
		$this->tabs = array(
		    'main' => __('Dashboard','wp-users-pro'),
			'membership' => __('Subscriptions Plans','wp-users-pro'),
			'users' => __('Members','wp-users-pro'),	
			'subscriptions' => __('Subscriptions','wp-users-pro'),
			'orders' => __('Orders','wp-users-pro'),						
			'fields' => __('Fields','wp-users-pro'),
			'settings' => __('Settings','wp-users-pro'),				
			'mail' => __('Notifications','wp-users-pro'),
			'gateway' => __('Gateways','wp-users-pro'),		
			'help' => __('Doc','wp-users-pro'),
			'pro' => __('GO PRO!','wp-users-pro'),
		);
		
		$this->tabs_icons = array(
		    'main' => '',
			'tickets' => '',
			'departments' => '',
			'priority' =>'',
			'users' =>'',						
			'fields' => '',
			'settings' => '',				
			'mail' => '',		
			'help' => '',
		);		
		$this->default_tab = 'main';			
		
		$this->default_tab_membership = 'main';
		
		
	}
	
	public function update_default_option_ini () 
	{
		$this->options = get_option('wpuserspro_options');		
		$this->bup_set_default_options();
		
		if (!get_option('wpuserspro_options')) 
		{
			
			update_option('wpuserspro_options', $this->wpuserspro_default_options );
		}
		
		if (!get_option('wpuserspro_pro_active')) 
		{
			
			update_option('wpuserspro_pro_active', true);
		}	
		
		
	}
	
	
	function get_me_wphtml_editor($meta, $content, $rows)
	{
		// Turn on the output buffer
		ob_start();
		
		$editor_id = $meta;				
		$editor_settings = array('media_buttons' => false , 'textarea_rows' => $rows , 'teeny' =>true); 
		wp_editor( $content, $editor_id , $editor_settings);
		
		// Store the contents of the buffer in a variable
		$editor_contents = ob_get_clean();
		
		// Return the content you want to the calling function
		return $editor_contents;
	
	}
	
	
	public function display_warning_messages() 
	{
		global $wpuserspro;
			
		$account_page_id = $wpuserspro->get_option('my_account_page');		
		$my_account_url = get_permalink($account_page_id);
		
		//rate plugin		
		$rate_plugin = get_option('wpuserspro_rate_message');
		
		$message ='';
		
		$message_SAMPLE = '<div id="message" class="updated easywpm-message wc-connect">
				<a class="easywpm-message-close notice-dismiss" href="#" message-id="13"> '.__('Dismiss','wp-users-pro').'</a>
			
				<p><strong>'.__("IMPORTANT: Member Account:",'wp-users-pro').'</strong> – '.__("It's very important that you set the member's accuont page.",'wp-users-pro').'</p>
				
				<p class="submit">
					
					<a href="admin.php?page=wpuserspro-pages" class="button-secondary" > '.__('Set Members Account Page','wp-users-pro').'</a>
				</p>
	      </div>';
		
			
		if($my_account_url=="" )
		{
		
			$message .= '<div id="message" class="updated easywpm-message wc-connect">				
			
				<p><strong>'.__("IMPORTANT: Member Account:",'wp-users-pro').'</strong> – '.__("It's very important that you set the member's accuont page.",'wp-users-pro').'</p>
				
				<p class="submit">
					
					<a href="admin.php?page=wpuserspro-pages" class="button-secondary" > '.__('Set Members Account Page','wp-users-pro').'</a>
				</p>
	      </div>';
			
			
		
		
		}
		
		if($rate_plugin=="" )
		{
		
		/*	$message .= '<div id="message" class="updated easywpm-message-green wc-connect">				
			<a class="easywpm-message-close notice-dismiss" href="#" message-id="wpuserspro_rate_message"> '.__('Dismiss','wp-users-pro').'</a>
			
				<p><strong>'.__("Do you find this plugin useful?",'wp-users-pro').'</strong> – '.__("We offer free support, we love to do that, please consider leaving a 5 stars review on WordPress. That motivates us a lot to keep offering the best support for free.",'wp-users-pro').'</p>
				
				<p class="submit">
					
					<a href="https://wordpress.org/support/plugin/wp-users-pro/reviews/#new-post" class="button-secondary" > '.__('Rate Plugin!','wp-users-pro').'</a>
				</p>
	      </div>'; */
			
			
		
		
		}
		
		
		echo $message;	
		
		
		
		
	}
	
		
		
	
	
	function get_pending_verify_requests_count()
	{
		$count = 0;
		
		
		if ($count > 0){
			return '<span class="upadmin-bubble-new">'.$count.'</span>';
		}
	}
	
	function get_pending_verify_requests_count_only(){
		$count = 0;
		
		
		if ($count > 0){
			return $count;
		}
	}
	
	
	
	
	function admin_head(){
		$screen = get_current_screen();
		$slug = $this->slug;
		
	}

	function add_styles()
	{
		
		 global $wp_locale, $wpuserspro , $pagenow;
		 
		 if('customize.php' != $pagenow )
        {
		 
			wp_register_style('wpuserspro_admin', wpuserspro_url.'admin/css/admin.css');
			wp_enqueue_style('wpuserspro_admin');
			
			wp_register_style('wpuserspro_datepicker', wpuserspro_url.'admin/css/datepicker.css');
			wp_enqueue_style('wpuserspro_datepicker');
			
			
			/*google graph*/		
			wp_register_script('wpuserspro_jsgooglapli', 'https://www.gstatic.com/charts/loader.js');
			wp_enqueue_script('wpuserspro_jsgooglapli');
			
							
				
			//color picker		
			 wp_enqueue_style( 'easywpm-color-picker' );	
				 
			 wp_register_script( 'wpuserspro_color_picker', wpuserspro_url.'admin/scripts/color-picker-js.js', array( 
				'easywpm-color-picker'
			) );
			wp_enqueue_script( 'wpuserspro_color_picker' );
			
			
			wp_register_script( 'wpuserspro_admin',wpuserspro_url.'admin/scripts/admin.js', array( 
				'jquery','jquery-ui-core','jquery-ui-draggable','jquery-ui-droppable',	'jquery-ui-sortable', 'jquery-ui-tabs', 'jquery-ui-autocomplete', 'jquery-ui-widget', 'jquery-ui-position'	), null );
			wp_enqueue_script( 'wpuserspro_admin' );
			
			
			/* Font Awesome */
			wp_register_style( 'wpuserspro_font_awesome', wpuserspro_url.'css/css/font-awesome.min.css');
			wp_enqueue_style('wpuserspro_font_awesome');
			
			// Using imagesLoaded? Do this.
			//wp_enqueue_script('imagesloaded',  wpuserspro_url.'js/qtip/imagesloaded.pkgd.min.js' , null, false, true);
			
			// Add the styles first, in the <head> (last parameter false, true = bottom of page!)
			wp_enqueue_style('qtip', wpuserspro_url.'js/qtip/jquery.qtip.min.css' , null, false, false);
			wp_enqueue_script('qtip',  wpuserspro_url.'js/qtip/jquery.qtip.min.js', array('jquery', 'imagesloaded'), false, true);
			
		}
		
		$date_picker_format = $wpuserspro->get_date_picker_format();
		 
		
		 wp_localize_script( 'wpuserspro_admin', 'wpuserspro_admin_v98', array(
            'msg_cate_delete'  => __( 'Are you totally sure that you wan to delete this category?', 'wp-users-pro' ),
			'msg_department_delete'  => __( 'Are you totally sure that you wan to delete this department?', 'wp-users-pro' ),
			
			'msg_trash_ticket'  => __( 'Are you totally sure that you wan to send this ticket to the trash?', 'wp-users-pro' ),
			
			'are_you_sure'  => __( 'Are you totally sure?', 'wp-users-pro' ),
			'set_new_priority'  => __( 'Set a new priority', 'wp-users-pro' ),
			
			
			
			'msg_department_edit'  => __( 'Edit Department', 'wp-users-pro' ),
			'msg_department_add'  => __( 'Add Department', 'wp-users-pro' ),
			'msg_department_input_title'  => __( 'Please input a name', 'wp-users-pro' ),
			
			'msg_priority_edit'  => __( 'Edit Priority', 'wp-users-pro' ),
			'msg_priority_add'  => __( 'Add Priority', 'wp-users-pro' ),
			'msg_priority_input_title'  => __( 'Please input a name', 'wp-users-pro' ),
			
			'msg_ticket_empty_reply'  => '<div class="easywpm-ultra-error"><span><i class="fa fa-ok"></i>'.__('ERROR!. Please write a message ',"wp-users-pro").'</span></div>' ,
			
			'msg_ticket_submiting_reply'  => '<div class="easywpm-ultra-wait"><span><i class="fa fa-ok"></i>'.__(' <img src="'.wpuserspro_url.'/templates/images/loaderB16.gif" width="16" height="16" /> &nbsp; Please wait ... ',"wp-users-pro").'</span></div>' ,
			'msg_wait'  => __( '<img src="'.wpuserspro_url.'/templates/images/loaderB16.gif" width="16" height="16" /> &nbsp; Please wait ... ', 'wp-users-pro' ) ,
			
			'msg_site_edit'  => __( 'Edit Product', 'wp-users-pro' ),
			'msg_site_add'  => __( 'Add Product', 'wp-users-pro' ),
			
			'msg_note_edit'  => __( 'Edit Note', 'wp-users-pro' ),
			
			'msg_category_edit'  => __( 'Edit Category', 'wp-users-pro' ),
			'msg_category_add'  => __( 'Add Category', 'wp-users-pro' ),
			
			'msg_category_input_title'  => __( 'Please input a title', 'wp-users-pro' ),
			'msg_category_delete'  => __( 'Are you totally sure that you wan to delete this service?', 'wp-users-pro' ),
			'msg_user_delete'  => __( 'Are you totally sure that you wan to delete this user?', 'wp-users-pro' ),
			
			'msg_status_change'  => __( 'Please set a new status', 'wp-users-pro' ),
			'msg_priority_change'  => __( 'Please set a new priority', 'wp-users-pro' ),
			
			'message_wait_staff_box'     => __("Please wait ...","wp-users-pro"),
			'msg_input_site_name'  => __( 'Please input a name', 'wp-users-pro' ),
			
			'msg_input_note_name'  => __( 'Please input a name', 'wp-users-pro' ),
			
			'date_picker_date_format'     => $date_picker_format

           
            
        ) );
		
		
		//localize our js
		$date_picker_array = array(
					'closeText'         => __( 'Done', "bookingup" ),
					'currentText'       => __( 'Today', "bookingup" ),
					'prevText' =>  __('Prev',"bookingup"),
		            'nextText' => __('Next',"bookingup"),				
					'monthNames'        => array_values( $wp_locale->month ),
					'monthNamesShort'   => array_values( $wp_locale->month_abbrev ),
					'monthStatus'       => __( 'Show a different month', "wp-users-pro" ),
					'dayNames'          => array_values( $wp_locale->weekday ),
					'dayNamesShort'     => array_values( $wp_locale->weekday_abbrev ),
					'dayNamesMin'       => array_values( $wp_locale->weekday_initial ),					
					// get the start of week from WP general setting
					'firstDay'          => get_option( 'start_of_week' ),
					// is Right to left language? default is false
					'isRTL'             => $wp_locale->is_rtl(),
				);
				
				
				wp_localize_script('wpuserspro_admin', 'EASYWPMDatePicker', $date_picker_array);
				
		
	}
	
	public  function convertFormat( $source_format, $to )
    {
		global $bookingultrapro ;
		
        switch ( $source_format ) 
		{
            case 'date':
                $php_format = get_option( 'date_format', 'Y-m-d' );
                break;
            case 'time':
                $php_format = get_option( 'time_format', 'H:i' );
                break;
            default:
                $php_format = $source_format;
        }
		
		 switch ( $to ) {
            case 'fc' :
			
                $replacements = array(
                    'd' => 'DD',   '\d' => '[d]',
                    'D' => 'ddd',  '\D' => '[D]',
                    'j' => 'D',    '\j' => 'j',
                    'l' => 'dddd', '\l' => 'l',
                    'N' => 'E',    '\N' => 'N',
                    'S' => 'o',    '\S' => '[S]',
                    'w' => 'e',    '\w' => '[w]',
                    'z' => 'DDD',  '\z' => '[z]',
                    'W' => 'W',    '\W' => '[W]',
                    'F' => 'MMMM', '\F' => 'F',
                    'm' => 'MM',   '\m' => '[m]',
                    'M' => 'MMM',  '\M' => '[M]',
                    'n' => 'M',    '\n' => 'n',
                    't' => '',     '\t' => 't',
                    'L' => '',     '\L' => 'L',
                    'o' => 'YYYY', '\o' => 'o',
                    'Y' => 'YYYY', '\Y' => 'Y',
                    'y' => 'YY',   '\y' => 'y',
                    'a' => 'a',    '\a' => '[a]',
                    'A' => 'A',    '\A' => '[A]',
                    'B' => '',     '\B' => 'B',
                    'g' => 'h',    '\g' => 'g',
                    'G' => 'H',    '\G' => 'G',
                    'h' => 'hh',   '\h' => '[h]',
                    'H' => 'HH',   '\H' => '[H]',
                    'i' => 'mm',   '\i' => 'i',
                    's' => 'ss',   '\s' => '[s]',
                    'u' => 'SSS',  '\u' => 'u',
                    'e' => 'zz',   '\e' => '[e]',
                    'I' => '',     '\I' => 'I',
                    'O' => '',     '\O' => 'O',
                    'P' => '',     '\P' => 'P',
                    'T' => '',     '\T' => 'T',
                    'Z' => '',     '\Z' => '[Z]',
                    'c' => '',     '\c' => 'c',
                    'r' => '',     '\r' => 'r',
                    'U' => 'X',    '\U' => 'U',
                    '\\' => '',
                );
                return strtr( $php_format, $replacements );
			}
	}
	
	function add_menu() 
	{
		global $wpuserspro_activation ;
        
        $pending_count =0;
		
		
	
		$pending_title = esc_attr( sprintf(__( '%d new manual activation requests','wp-users-pro'), $pending_count ) );
		if ($pending_count > 0)
		{
			$menu_label = sprintf( __( 'WP Users Pro %s','wp-users-pro' ), "<span class='update-plugins count-$pending_count' title='$pending_title'><span class='update-count'>" . number_format_i18n($pending_count) . "</span></span>" );
			
		} else {
			
			$menu_label = __('WP Users Pro','wp-users-pro');
		}
		
		add_menu_page( __('WP Users Pro','wp-users-pro'), $menu_label, 'manage_options', $this->slug, array(&$this, 'admin_page'), wpuserspro_url .'admin/images/small_logo_16x16.png', '159.140');
		
		//
		
		
		if(!isset($wpuserspro_activation))
		{
		
			add_submenu_page( $this->slug, __('More Functionality!','wp-users-pro'), __('More Functionality!','wp-users-pro'), 'manage_options', 'wpuserspro&tab=pro', array(&$this, 'admin_page') );
		
		}
		
		if(isset($wpuserspro_activation))
		{
			add_submenu_page( $this->slug, __('Licensing','wp-users-pro'), __('Licensing','wp-users-pro'), 'manage_options', 'wpuserspro&tab=licence', array(&$this, 'admin_page') );
		
		
		}
		
		do_action('wpuserspro_admin_menu_hook');
		
			
	}
	
	

	function admin_tabs( $current = null ) {
		
		global $easywpmcomplement, $wpuserspro_custom_fields;
        
        $custom_badge = '';
		
			$tabs = $this->tabs;
			$links = array();
			if ( isset ( $_GET['tab'] ) ) {
				$current = sanitize_text_field($_GET['tab']);
			} else {
				$current = $this->default_tab;
			}
			foreach( $tabs as $tab => $name ) :
			
			
			    if($tab=="pro"){
					
					$custom_badge = 'easywpm-pro-tab-bubble ';
					
				}
				
				if($tab=="fields" && !isset($wpuserspro_custom_fields)){continue;}
				
				if(isset($easywpmcomplement) && $tab=="pro"){continue;}
				
				
				if ( $tab == $current ) :
					$links[] = "<a class='nav-tab nav-tab-active ".$custom_badge."' href='?page=".$this->slug."&tab=$tab'><span class='wptu-adm-tab-legend'>".$name."</span></a>";
				else :
					$links[] = "<a class='nav-tab ".$custom_badge."' href='?page=".$this->slug."&tab=$tab'><span class='wptu-adm-tab-legend'>".$name."</span></a>";
				endif;
			endforeach;
			foreach ( $links as $link )
				echo $link;
	}

	
	
	function do_action(){
		global $wpuserspro;
				
		
	}
		
	
	/* set a global option */
	function wpuserspro_set_option($option, $newvalue)
	{
		$settings = get_option('wpuserspro_options');		
		$settings[$option] = $newvalue;
		update_option('wpuserspro_options', $settings);
	}
	
	/* default options */
	function bup_set_default_options()
	{
	
		$this->wpuserspro_default_options = array(									
						
						'messaging_send_from_name' => get_option('blogname'),
						
						'bup_noti_admin' => 'yes',
						'bup_noti_staff' => 'yes',
						'bup_noti_client' => 'yes',
						'messaging_send_from_email' => get_option( 'admin_email' ),
						'company_name' => get_option('blogname'),	
						
						'allowed_extensions' => 'jpg,png,gif,jpeg,pdf,doc,docx,xls',	
						
																		
						'email_reset_link_message_body' => $this->get_email_template('email_reset_link_message_body'),
						'email_reset_link_message_subject' => __('Password Reset','wp-users-pro'),
			
			
						'email_password_change_member_body' => $this->get_email_template('email_password_change_member_body'),
						'email_password_change_member_subject' => __('Password Reset Confirmation','wp-users-pro'),
			
						'email_registration_body' => $this->get_email_template('email_registration_body'),
						'email_registration_subject' => __('Your Account Details','wp-users-pro'),
						
						'email_package_upgrade_body' => $this->get_email_template('email_package_upgrade_body'),
						'email_package_upgrade_subject' => __('Purchase Confirmation','wp-users-pro'),
						
						'email_package_upgrade_admin_body' => $this->get_email_template('email_package_upgrade_admin_body'),
						'email_package_upgrade_admin_subject' => __('Purchase Confirmation','wp-users-pro'),
						
						'email_package_renewal_body' => $this->get_email_template('email_package_renewal_body'),
						'email_package_renewal_subject' => __('Membership Renewal Notification','wp-users-pro'),
						
						'email_package_renewal_admin_body' => $this->get_email_template('email_package_renewal_admin_body'),
						'email_package_renewal_admin_subject' => __('Membership Renewal Notification','wp-users-pro'),
						
				);
		
	}
	
	public function set_default_email_messages()
	{
		$line_break = "\r\n";	
				
		//Staff Password Reset	
		$email_body =  '{{wpuserspro_user_name}},'.$line_break.$line_break;
		$email_body .= __("Please use the following link to reset your password.","wp-users-pro") . $line_break.$line_break;			
		$email_body .= "{{wpuserspro_reset_link}}".$line_break.$line_break;
		$email_body .= __('If you did not request a new password delete this email.','wp-users-pro'). $line_break.$line_break;	
			
		$email_body .= __('Best Regards!','wp-users-pro'). $line_break;
		$email_body .= '{{wpuserspro_company_name}}'. $line_break;
		$email_body .= '{{wpuserspro_company_phone}}'. $line_break;
		$email_body .= '{{wpuserspro_company_url}}'. $line_break. $line_break;		
	    $this->notifications_email['email_reset_link_message_body'] = $email_body;
		
		$email_body =  '{{wpuserspro_user_name}},'.$line_break.$line_break;
		$email_body .= __("Your password has been updated successfully","wp-users-pro") . $line_break.$line_break;			
			
		$email_body .= __('Best Regards!','wp-users-pro'). $line_break;
		$email_body .= '{{wpuserspro_company_name}}'. $line_break;
		$email_body .= '{{wpuserspro_company_phone}}'. $line_break;
		$email_body .= '{{wpuserspro_company_url}}'. $line_break. $line_break;		
	    $this->notifications_email['email_password_change_member_body'] = $email_body;
		
		
				
		//User Registration Email
		$email_body =  __('Hello ','wp-users-pro') .'{{wpuserspro_client_name}},'.$line_break.$line_break;
		$email_body .= __("Thank you for your registration. Your login details for your account are as follows:","wp-users-pro") . $line_break.$line_break;
		$email_body .= __('Username: {{wpuserspro_user_name}}','wp-users-pro') . $line_break;
		$email_body .= __('Password: {{wpuserspro_user_password}}','wp-users-pro') . $line_break;
		$email_body .= __("Please use the following link to login to your account.","wp-users-pro") . $line_break.$line_break;			
		$email_body .= "{{wpuserspro_login_link}}".$line_break.$line_break;
			
		$email_body .= __('Best Regards!','wp-users-pro'). $line_break;
		$email_body .= '{{wpuserspro_company_name}}'. $line_break;
		$email_body .= '{{wpuserspro_company_phone}}'. $line_break;
		$email_body .= '{{wpuserspro_company_url}}'. $line_break. $line_break;
		
	    $this->notifications_email['email_registration_body'] = $email_body;
		
		//User Package Purchase 
		$email_body =  __('Hello ','wp-users-pro') .'{{wpuserspro_client_name}},'.$line_break.$line_break;
		$email_body .= __("Thank you very much for your purchase. This email contains details about your recent purchase and subscription, please keep it as receipt.","wp-users-pro") . $line_break.$line_break;
		$email_body .= "--------------------------------------------" . $line_break.$line_break;
		$email_body .= __("Subscription Details. :","wp-users-pro") . $line_break.$line_break;

		$email_body .= __('Subscription: {{wpuserspro_subscription_name}}','wp-users-pro') . $line_break;
		$email_body .= __('ID: {{wpuserspro_subscription_id}}','wp-users-pro') . $line_break;
		$email_body .= __('Amount: {{wpuserspro_subscription_amount}}','wp-users-pro') . $line_break;
		$email_body .= __('Period: {{wpuserspro_period}}','wp-users-pro') . $line_break;
		$email_body .= __('Agreement: {{wpuserspro_subscription_agreement}}','wp-users-pro') . $line_break. $line_break;

		$email_body .= __('Best Regards!','wp-users-pro'). $line_break;
		$email_body .= '{{wpuserspro_company_name}}'. $line_break;
		$email_body .= '{{wpuserspro_company_phone}}'. $line_break;
		$email_body .= '{{wpuserspro_company_url}}'. $line_break. $line_break;
		
	    $this->notifications_email['email_package_upgrade_body'] = $email_body;
		
		//Admin Notification Package Purchase 
		$email_body =  __('Hello Admin, ','wp-users-pro') .$line_break.$line_break;
		$email_body .= __("This email is to notify you that a new subscription has been purchased. Please keep this email as a receipt.","wp-users-pro") . $line_break.$line_break;
		$email_body .= "--------------------------------------------" . $line_break.$line_break;
		$email_body .= __("Subscription Details. :","wp-users-pro") . $line_break.$line_break;

		$email_body .= __('Subscription: {{wpuserspro_subscription_name}}','wp-users-pro') . $line_break;
		$email_body .= __('ID: {{wpuserspro_subscription_id}}','wp-users-pro') . $line_break;
		$email_body .= __('Amount: {{wpuserspro_subscription_amount}}','wp-users-pro') . $line_break;
		$email_body .= __('Client: {{wpuserspro_client_name}}','wp-users-pro') . $line_break;
		$email_body .= __('Period: {{wpuserspro_period}}','wp-users-pro') . $line_break;
		$email_body .= __('Agreement: {{wpuserspro_subscription_agreement}}','wp-users-pro') . $line_break. $line_break;

		$email_body .= __('Best Regards!','wp-users-pro'). $line_break;
		$email_body .= '{{wpuserspro_company_name}}'. $line_break;
		$email_body .= '{{wpuserspro_company_phone}}'. $line_break;
		$email_body .= '{{wpuserspro_company_url}}'. $line_break. $line_break;
		
	    $this->notifications_email['email_package_upgrade_admin_body'] = $email_body;
		
		//Admin Notification Package Renewal 
		$email_body =  __('Hello Admin, ','wp-users-pro') .$line_break.$line_break;
		$email_body .=  __('This emails is a confirmation that a subscription has been renewed successfully. Please keep this email as receipt of the renewal. ','wp-users-pro') .$line_break.$line_break;
		$email_body .= "--------------------------------------------" . $line_break.$line_break;
		$email_body .= __("Subscription Renewal Details. :","wp-users-pro") . $line_break.$line_break;
		$email_body .= __('Subscription: {{wpuserspro_subscription_name}}','wp-users-pro') . $line_break;
		$email_body .= __('ID: {{wpuserspro_subscription_id}}','wp-users-pro') . $line_break;
		$email_body .= __('Merchant ID: {{wpuserspro_subscription_profile_id}}','wp-users-pro') . $line_break;
		$email_body .= __('Amount: {{wpuserspro_subscription_amount}}','wp-users-pro') . $line_break;
		$email_body .= __('Client: {{wpuserspro_client_name}}','wp-users-pro') . $line_break;
		$email_body .= __('Period: {{wpuserspro_period}}','wp-users-pro') . $line_break. $line_break;
		
			
		$email_body .= __('Best Regards!','wp-users-pro'). $line_break;
		$email_body .= '{{wpuserspro_company_name}}'. $line_break;
		$email_body .= '{{wpuserspro_company_phone}}'. $line_break;
		$email_body .= '{{wpuserspro_company_url}}'. $line_break. $line_break;		
	    $this->notifications_email['email_package_renewal_admin_body'] = $email_body;
		
		//Client Notification Package Renewal 
		$email_body =  __('Hello ','wp-users-pro') .'{{wpuserspro_client_name}},'.$line_break.$line_break;
		$email_body .= __("Thank you very much for renewing your subscription. This email contains useful information about the subscription renewal.","wp-users-pro") . $line_break.$line_break;
		$email_body .= "--------------------------------------------" . $line_break.$line_break;
		$email_body .= __("Subscription Renewal Details. :","wp-users-pro") . $line_break.$line_break;
		$email_body .= __('Subscription: {{wpuserspro_subscription_name}}','wp-users-pro') . $line_break;
		$email_body .= __('Amount: {{wpuserspro_subscription_amount}}','wp-users-pro') . $line_break;
		$email_body .= __('Period: {{wpuserspro_period}}','wp-users-pro') . $line_break. $line_break;
			
		$email_body .= __('Best Regards!','wp-users-pro'). $line_break;
		$email_body .= '{{wpuserspro_company_name}}'. $line_break;
		$email_body .= '{{wpuserspro_company_phone}}'. $line_break;
		$email_body .= '{{wpuserspro_company_url}}'. $line_break. $line_break;		
	    $this->notifications_email['email_package_renewal_body'] = $email_body;		
		
			
	
	}
	
	public function get_email_template($key)
	{
		return $this->notifications_email[$key];
	
	}
	
	public function set_font_awesome()
	{
		        /* Store icons in array */
        $this->fontawesome = array(
                'cloud-download','cloud-upload','lightbulb','exchange','bell-alt','file-alt','beer','coffee','food','fighter-jet',
                'user-md','stethoscope','suitcase','building','hospital','ambulance','medkit','h-sign','plus-sign-alt','spinner',
                'angle-left','angle-right','angle-up','angle-down','double-angle-left','double-angle-right','double-angle-up','double-angle-down','circle-blank','circle',
                'desktop','laptop','tablet','mobile-phone','quote-left','quote-right','reply','github-alt','folder-close-alt','folder-open-alt',
                'adjust','asterisk','ban-circle','bar-chart','barcode','beaker','beer','bell','bolt','book','bookmark','bookmark-empty','briefcase','bullhorn',
                'calendar','camera','camera-retro','certificate','check','check-empty','cloud','cog','cogs','comment','comment-alt','comments','comments-alt',
                'credit-card','dashboard','download','download-alt','edit','envelope','envelope-alt','exclamation-sign','external-link','eye-close','eye-open',
                'facetime-video','film','filter','fire','flag','folder-close','folder-open','gift','glass','globe','group','hdd','headphones','heart','heart-empty',
                'home','inbox','info-sign','key','leaf','legal','lemon','lock','unlock','magic','magnet','map-marker','minus','minus-sign','money','move','music',
                'off','ok','ok-circle','ok-sign','pencil','picture','plane','plus','plus-sign','print','pushpin','qrcode','question-sign','random','refresh','remove',
                'remove-circle','remove-sign','reorder','resize-horizontal','resize-vertical','retweet','road','rss','screenshot','search','share','share-alt',
                'shopping-cart','signal','signin','signout','sitemap','sort','sort-down','sort-up','spinner','star','star-empty','star-half','tag','tags','tasks',
                'thumbs-down','thumbs-up','time','tint','trash','trophy','truck','umbrella','upload','upload-alt','user','volume-off','volume-down','volume-up',
                'warning-sign','wrench','zoom-in','zoom-out','file','cut','copy','paste','save','undo','repeat','text-height','text-width','align-left','align-right',
                'align-center','align-justify','indent-left','indent-right','font','bold','italic','strikethrough','underline','link','paper-clip','columns',
                'table','th-large','th','th-list','list','list-ol','list-ul','list-alt','arrow-down','arrow-left','arrow-right','arrow-up','caret-down',
                'caret-left','caret-right','caret-up','chevron-down','chevron-left','chevron-right','chevron-up','circle-arrow-down','circle-arrow-left',
                'circle-arrow-right','circle-arrow-up','hand-down','hand-left','hand-right','hand-up','play-circle','play','pause','stop','step-backward',
                'fast-backward','backward','forward','step-forward','fast-forward','eject','fullscreen','resize-full','resize-small','phone','phone-sign',
                'facebook','facebook-sign','twitter','twitter-sign','github','github-sign','linkedin','linkedin-sign','pinterest','pinterest-sign',
                'google-plus','google-plus-sign','sign-blank'
        );
        asort($this->fontawesome);
		
	
	
	}
	
		
	
	/*This Function Change the Profile Fields Order when drag/drop */	
	public function sort_fileds_list() 
	{
		global $wpdb;
	
		$order = explode(',', sanitize_text_field($_POST['order']));
		$counter = 0;
		$new_pos = 10;
		
		//multi fields		
		$custom_form = sanitize_text_field($_POST["wpuserspro_custom_form"]);
		
		$custom_form = 'wpuserspro_profile_fields_'.$custom_form;		
		$fields = get_option($custom_form);			
		$fields_set_to_update =$custom_form;
		
		$new_fields = array();
		
		$fields_temp = $fields;
		ksort($fields);
		
		foreach ($fields as $field) 
		{
			
			$fields_temp[$order[$counter]]["position"] = $new_pos;			
			$new_fields[$new_pos] = $fields_temp[$order[$counter]];				
			$counter++;
			$new_pos=$new_pos+10;
		}
		
		ksort($new_fields);		
		
		
		update_option($fields_set_to_update, $new_fields);		
		die(1);
		
    }
	/*  delete profile field */
    public function delete_profile_field() 
	{						
		
		if($_POST['_item']!= "")
		{
			
			//multi fields		
			$custom_form = sanitize_text_field($_POST["custom_form"]);
			
			if($custom_form!="")
			{
				$custom_form = 'wpuserspro_profile_fields_'.$custom_form;		
				$fields = get_option($custom_form);			
				$fields_set_to_update =$custom_form;
				
			}else{
				
				$fields = get_option('wpuserspro_profile_fields');
				$fields_set_to_update ='wpuserspro_profile_fields';
			
			}
			
			$pos = sanitize_text_field($_POST['_item']);
			
			unset($fields[$pos]);
			
			ksort($fields);
			print_r($fields);
			update_option($fields_set_to_update, $fields);
			
		
		}
	
	}
	
	
	 /* create new custom profile field */
    public function add_new_custom_profile_field() 
	{				
		
		
		if($_POST['_meta']!= "")
		{
			$meta = sanitize_text_field($_POST['_meta']);
		
		}else{
			
			$meta = sanitize_text_field($_POST['_meta_custom']);
		}
		
		//if custom fields
		
		
		//multi fields		
		$custom_form = sanitize_text_field( $_POST["custom_form"]);
		
		if($custom_form!="")
		{
			$custom_form = 'wpuserspro_profile_fields_'.$custom_form;		
			$fields = get_option($custom_form);			
			$fields_set_to_update =$custom_form;
			
		}else{
			
			$fields = get_option('wpuserspro_profile_fields');
			$fields_set_to_update ='wpuserspro_profile_fields';
		
		}
		
		$min = min(array_keys($fields)); 
		
		$pos = $min-1;
		
		$fields[$pos] =array(
			  'position' => $pos,
				'icon' => sanitize_text_field($_POST['_icon']),
				'type' => sanitize_text_field($_POST['_type']),
				'field' => sanitize_text_field($_POST['_field']),
				'meta' => sanitize_text_field($meta),
				'name' => sanitize_text_field($_POST['_name']),				
				'tooltip' => sanitize_text_field($_POST['_tooltip']),
				'help_text' => sanitize_text_field($_POST['_help_text']),							
				'can_edit' => sanitize_text_field($_POST['_can_edit']),
				'allow_html' => sanitize_text_field($_POST['_allow_html']),
				'can_hide' => sanitize_text_field($_POST['_can_hide']),				
				'private' => sanitize_text_field($_POST['_private']),
				'required' => sanitize_text_field($_POST['_required']),
				'show_in_register' => sanitize_text_field($_POST['_show_in_register']),
				'predefined_options' => sanitize_text_field($_POST['_predefined_options']),				
				'choices' => sanitize_text_field($_POST['_choices']),												
				'deleted' => 0
				

			);			
					
			ksort($fields);
			print_r($fields);			
		   update_option($fields_set_to_update, $fields);         


    }
	


    // save form
    public function save_fields_settings() 
	{		
		
		$pos = sanitize_text_field($_POST['pos']); 
		
		if($_POST['_meta']!= "")
		{
			$meta = sanitize_text_field($_POST['_meta']);
		
		}else{
			
			$meta = sanitize_text_field($_POST['_meta_custom']);
		}
		
		//if custom fields
		
		//multi fields		
		$custom_form = sanitize_text_field($_POST["custom_form"]);
		
		if($custom_form!="")
		{
			$custom_form = 'wpuserspro_profile_fields_'.$custom_form;		
			$fields = get_option($custom_form);			
			$fields_set_to_update =$custom_form;
			
		}else{
			
			$fields = get_option('wpuserspro_profile_fields');
			$fields_set_to_update ='wpuserspro_profile_fields';
		
		}
		
		$fields[$pos] =array(
			  'position' => $pos,
				'icon' => sanitize_text_field($_POST['_icon']),
				'type' => sanitize_text_field($_POST['_type']),
				'field' => sanitize_text_field($_POST['_field']),
				'meta' => sanitize_text_field($meta),
				'name' => sanitize_text_field($_POST['_name']),
				'ccap' => sanitize_text_field($_POST['_ccap']),
				'tooltip' => sanitize_text_field($_POST['_tooltip']),
				'help_text' => sanitize_text_field($_POST['_help_text']),
				'social' =>  sanitize_text_field($_POST['_social']),
				'is_a_link' =>  sanitize_text_field($_POST['_is_a_link']),
				'can_edit' => sanitize_text_field($_POST['_can_edit']),
				'allow_html' => sanitize_text_field($_POST['_allow_html']),				
				'required' => sanitize_text_field($_POST['_required']),
				'show_in_register' => sanitize_text_field($_POST['_show_in_register']),
				
				'predefined_options' => sanitize_text_field($_POST['_predefined_options']),				
				'choices' => sanitize_text_field($_POST['_choices']),												
				'deleted' => 0,
				'show_to_user_role' => sanitize_text_field($_POST['_show_to_user_role']),
                'edit_by_user_role' => sanitize_text_field($_POST['_edit_by_user_role'])
			);
			
			
						
			print_r($fields);
			
		    update_option($fields_set_to_update , $fields);
		
         


    }
	
		
	/*This load a custom field to be edited Implemented on 08-08-2014*/
	function reload_field_to_edit()	
	{
		global $wpuserspro;
		
		//get field
		$pos = sanitize_text_field($_POST["pos"]);
		
		
		//multi fields		
		$custom_form = sanitize_text_field($_POST["custom_form"]);
		
		if($custom_form!="")
		{
			$custom_form = 'wpuserspro_profile_fields_'.$custom_form;		
			$fields = get_option($custom_form);			
			$fields_set_to_update =$custom_form;
			
		}else{
			
			$fields = get_option('wpuserspro_profile_fields');
			$fields_set_to_update ='wpuserspro_profile_fields';
		
		}
		
		$array = $fields[$pos];
		
		
		extract($array); $i++;

		if(!isset($required))
		       $required = 0;

		    if(!isset($fonticon))
		        $fonticon = '';				
				
			if ($type == 'seperator' || $type == 'separator') {
			   
				$class = "separator";
				$class_title = "";
			} else {
			  
				$class = "profile-field";
				$class_title = "profile-field";
			}
		
		
		?>
		
		

				<p>
					<label for="uultra_<?php echo $pos; ?>_position"><?php _e('Position','wp-users-pro'); ?>
					</label> <input name="uultra_<?php echo $pos; ?>_position"
						type="text" id="uultra_<?php echo $pos; ?>_position"
						value="<?php echo $pos; ?>" class="small-text" /> <i
						class="uultra_icon-question-sign uultra-tooltip2"
						title="<?php _e('Please use a unique position. Position lets you place the new field in the place you want exactly in Profile view.','wp-users-pro'); ?>"></i>
				</p>

				<p>
					<label for="uultra_<?php echo $pos; ?>_type"><?php _e('Field Type','wp-users-pro'); ?>
					</label> <select name="uultra_<?php echo $pos; ?>_type"
						id="uultra_<?php echo $pos; ?>_type">
						<option value="usermeta" <?php selected('usermeta', $type); ?>>
							<?php _e('Profile Field','wp-users-pro'); ?>
						</option>
						<option value="separator" <?php selected('separator', $type); ?>>
							<?php _e('Separator','wp-users-pro'); ?>
						</option>
					</select> <i class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('You can create a separator or a usermeta (profile field)','wp-users-pro'); ?>"></i>
				</p> 
				
				<?php if ($type != 'separator') { ?>

				<p class="uultra-inputtype">
					<label for="uultra_<?php echo $pos; ?>_field"><?php _e('Field Input','wp-users-pro'); ?>
					</label> <select name="uultra_<?php echo $pos; ?>_field"
						id="uultra_<?php echo $pos; ?>_field">
						<?php
						
						 foreach($wpuserspro->allowed_inputs as $input=>$label) { ?>
						<option value="<?php echo $input; ?>"
						<?php selected($input, $field); ?>>
							<?php echo $label; ?>
						</option>
						<?php } ?>
					</select> <i class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('When user edit profile, this field can be an input (text, textarea, image upload, etc.)','wp-users-pro'); ?>"></i>
				</p>

				
				<p>
					<label for="uultra_<?php echo $pos; ?>_meta_custom"><?php _e('Custom Meta Field','wp-users-pro'); ?>
					</label> <input name="uultra_<?php echo $pos; ?>C"
						type="text" id="uultra_<?php echo $pos; ?>_meta_custom"
						value="<?php if (!isset($all_meta_for_user[$meta])) echo $meta; ?>" />
					<i class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('Enter a custom meta key for this profile field if do not want to use a predefined meta field above. It is recommended to only use alphanumeric characters and underscores, for example my_custom_meta is a proper meta key.','wp-users-pro'); ?>"></i>
				</p> <?php } ?>

				
                
                
                <p>
					<label for="uultra_<?php echo $pos; ?>_name"><?php _e('Label / Name','wp-users-pro'); ?>
					</label> <input name="uultra_<?php echo $pos; ?>_name" type="text"
						id="uultra_<?php echo $pos; ?>_name" value="<?php echo $name; ?>" />
					<i class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('Enter the label / name of this field as you want it to appear in front-end (Profile edit/view)','wp-users-pro'); ?>"></i>
				</p>
                
                

			<?php if ($type != 'separator' ) { ?>

				
				<p>
					<label for="uultra_<?php echo $pos; ?>_tooltip"><?php _e('Tooltip Text','wp-users-pro'); ?>
					</label> <input name="uultra_<?php echo $pos; ?>_tooltip" type="text"
						id="uultra_<?php echo $pos; ?>_tooltip"
						value="<?php echo $tooltip; ?>" /> <i
						class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('A tooltip text can be useful for social buttons on profile header.','wp-users-pro'); ?>"></i>
				</p> 
                
               <p>
               
               <label for="uultra_<?php echo $pos; ?>_help_text"><?php _e('Help Text','wp-users-pro'); ?>
                </label><br />
                    <textarea class="uultra-help-text" id="uultra_<?php echo $pos; ?>_help_text" name="uultra_<?php echo $pos; ?>_help_text" title="<?php _e('A help text can be useful for provide information about the field.','wp-users-pro'); ?>" ><?php echo $help_text; ?></textarea>
                    <i class="uultra-icon-question-sign uultra-tooltip2"
                                title="<?php _e('Show this help text under the profile field.','wp-users-pro'); ?>"></i>
                              
               </p> 
				
				
				
                
               				
				<?php 
				if(!isset($can_edit))
				    $can_edit = '1';
				?>
				<p>
					<label for="uultra_<?php echo $pos; ?>_can_edit"><?php _e('User can edit','wp-users-pro'); ?>
					</label> <select name="uultra_<?php echo $pos; ?>_can_edit"
						id="uultra_<?php echo $pos; ?>_can_edit">
						<option value="1" <?php selected(1, $can_edit); ?>>
							<?php _e('Yes','wp-users-pro'); ?>
						</option>
						<option value="0" <?php selected(0, $can_edit); ?>>
							<?php _e('No','wp-users-pro'); ?>
						</option>
					</select> <i class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('Users can edit this profile field or not.','wp-users-pro'); ?>"></i>
				</p> 
				
				<?php if (!isset($array['allow_html'])) { 
				    $allow_html = 0;
				} ?>
								
				
				
				<?php 
				if(!isset($required))
				    $required = '0';
				?>
				<p>
					<label for="uultra_<?php echo $pos; ?>_required"><?php _e('This field is Required','wp-users-pro'); ?>
					</label> <select name="uultra_<?php echo $pos; ?>_required"
						id="uultra_<?php echo $pos; ?>_required">
						<option value="0" <?php selected(0, $required); ?>>
							<?php _e('No','wp-users-pro'); ?>
						</option>
						<option value="1" <?php selected(1, $required); ?>>
							<?php _e('Yes','wp-users-pro'); ?>
						</option>
					</select> <i class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('Selecting yes will force user to provide a value for this field at registration and edit profile. Registration or profile edits will not be accepted if this field is left empty.','wp-users-pro'); ?>"></i>
				</p> <?php } ?> <?php

				/* Show Registration field only when below condition fullfill
				1) Field is not private
				2) meta is not for email field
				3) field is not fileupload */
				if(!isset($private))
				    $private = 0;

				if(!isset($meta))
				    $meta = '';

				if(!isset($field))
				    $field = '';


				//if($type == 'separator' ||  ($private != 1 && $meta != 'user_email' ))
				if($type == 'separator' ||  ($private != 1 && $meta != 'user_email' ))
				{
				    if(!isset($show_in_register))
				        $show_in_register= 0;
						
					 if(!isset($show_in_widget))
				        $show_in_widget= 0;
				    ?>
				<p>
					<label for="uultra_<?php echo $pos; ?>_show_in_register"><?php _e('Show on Registration Form','wp-users-pro'); ?>
					</label> <select name="uultra_<?php echo $pos; ?>_show_in_register"
						id="uultra_<?php echo $pos; ?>_show_in_register">
						<option value="0" <?php selected(0, $show_in_register); ?>>
							<?php _e('No','wp-users-pro'); ?>
						</option>
						<option value="1" <?php selected(1, $show_in_register); ?>>
							<?php _e('Yes','wp-users-pro'); ?>
						</option>
					</select> <i class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('Show this profile field on the registration form','wp-users-pro'); ?>"></i>
				</p>    
               
                
                 <?php } ?>
                 
			<?php if ($type != 'seperator' || $type != 'separator') { ?>

		  <?php if (in_array($field, array('select','radio','checkbox')))
				 {
				    $show_choices = null;
				} else { $show_choices = 'uultra-hide';
				
				
				} ?>

				<p class="uultra-choices <?php echo $show_choices; ?>">
					<label for="uultra_<?php echo $pos; ?>_choices"
						style="display: block"><?php _e('Available Choices','wp-users-pro'); ?> </label>
					<textarea name="uultra_<?php echo $pos; ?>_choices" type="text" id="uultra_<?php echo $pos; ?>_choices" class="large-text"><?php if (isset($array['choices'])) echo trim($choices); ?></textarea>
                    
                    <?php
                    
					if($wpuserspro->uultra_if_windows_server())
					{
						echo ' <p>'.__('<strong>PLEASE NOTE: </strong>Enter values separated by commas, example: 1,2,3. The choices will be available for front end user to choose from.').' </p>';					
					}else{
						
						echo ' <p>'.__('<strong>PLEASE NOTE:</strong> Enter one choice per line please. The choices will be available for front end user to choose from.').' </p>';
					
					
					}
					
					?>
                    <p>
                    
                    
                    </p>
					<i class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('Enter one choice per line please. The choices will be available for front end user to choose from.','wp-users-pro'); ?>"></i>
				</p> <?php //if (!isset($array['predefined_loop'])) $predefined_loop = 0;
				
				if (!isset($predefined_options)) $predefined_options = 0;
				
				 ?>

				<p class="uultra_choices <?php echo $show_choices; ?>">
					<label for="uultra_<?php echo $pos; ?>_predefined_options" style="display: block"><?php _e('Enable Predefined Choices','wp-users-pro'); ?>
					</label> 
                    <select name="uultra_<?php echo $pos; ?>_predefined_options"id="uultra_<?php echo $pos; ?>_predefined_options">
						<option value="0" <?php selected(0, $predefined_options); ?>>
							<?php _e('None','wp-users-pro'); ?>
						</option>
						<option value="countries" <?php selected('countries', $predefined_options); ?>>
							<?php _e('List of Countries','wp-users-pro'); ?>
						</option>
                        
                        <option value="age" <?php selected('age', $predefined_options); ?>>
							<?php _e('Age','wp-users-pro'); ?>
						</option>
					</select> <i class="uultra-icon-question-sign uultra-tooltip2"
						title="<?php _e('You can enable a predefined filter for choices. e.g. List of countries It enables country selection in profiles and saves you time to do it on your own.','wp-users-pro'); ?>"></i>
				</p>

				
				<div class="clear"></div> 
				
				<?php } ?>


  <div class="easywpm-ultra-success easywpm-notification" id="bup-sucess-fields-<?php echo $pos; ?>"><?php _e('Success ','wp-users-pro'); ?></div>
				<p>
                
               
                 
				<input type="button" name="submit"	value="<?php _e('Update','wp-users-pro'); ?>"						class="button button-primary easywpm-btn-submit-field"  data-edition="<?php echo $pos; ?>" /> 
                   <input type="button" value="<?php _e('Cancel','wp-users-pro'); ?>"
						class="button button-secondary easywpm-btn-close-edition-field" data-edition="<?php echo $pos; ?>" />
				</p>
                
      <?php
	  
	  die();
		
	}
	
	public function create_standard_form_fields ($form_name )	
	{		
	
		/* These are the basic profile fields */
		$fields_array = array(
			80 => array( 
			  'position' => '50',
				'type' => 'separator', 
				'name' => __('Appointment Info','wp-users-pro'),
				'private' => 0,
				'show_in_register' => 1,
				'deleted' => 0,
				'show_to_user_role' => 0
			),			
			
			170 => array( 
			  'position' => '200',
				'icon' => 'pencil',
				'field' => 'textarea',
				'type' => 'usermeta',
				'meta' => 'special_notes',
				'name' => __('Comments','wp-users-pro'),
				'can_hide' => 0,
				'can_edit' => 1,
				'show_in_register' => 1,
				'private' => 0,
				'social' => 0,
				'deleted' => 0,
				'allow_html' => 1,				
				'help_text' => ''
			
			)
		);
		
		/* Store default profile fields for the first time */
		if (!get_option($form_name))
		{
			if($form_name!="")
			{
				update_option($form_name,$fields_array);
			
			}
			
		}	
	}
	
	/*Loads all field list */	
	function reload_custom_fields_set ()	
	{
		
		global $wpuserspro;
		
		$custom_form = sanitize_text_field($_POST["custom_form"]);
		$custom_form = 'wpuserspro_profile_fields_'.$custom_form;	
		
		$fields = get_option($custom_form);
		
		if(!is_array($fields)){$fields = array();}
		ksort($fields);		
		
		$i = 0;
		foreach($fields as $pos => $array) 
		{
		    extract($array); $i++;

		    if(!isset($required))
		        $required = 0;

		    if(!isset($fonticon))
		        $fonticon = '';
				
				
			if ($type == 'seperator' || $type == 'separator') {
			   
				$class = "separator";
				$class_title = "";
			} else {
			  
				$class = "profile-field";
				$class_title = "profile-field";
			}
		    ?>
            
          <li class="easywpm-profile-fields-row <?php echo $class_title?>" id="<?php echo $pos; ?>">
            
            
            <div class="heading_title  <?php echo $class?>">
            
            <h3>
            <?php
			
			if (isset($array['name']) && $array['name'])
			{
			    echo  stripslashes($array['name']);
			}
			?>
            
            <?php
			if ($type == 'separator') {
				
			    echo __(' - Separator','wp-users-pro');
				
			} else {
				
			    echo __(' - Profile Field','wp-users-pro');
				
			}
			?>
            
            </h3>
            
            
              <div class="options-bar">
             
                 <p>                
                    <input type="submit" name="submit" value="<?php _e('Edit','wp-users-pro'); ?>"						class="button easywpm-btn-edit-field button-primary" data-edition="<?php echo $pos; ?>" /> <input type="button" value="<?php _e('Delete','wp-users-pro'); ?>"	data-field="<?php echo $pos; ?>" class="button button-secondary easywpm-delete-profile-field-btn" />
                    </p>
            
             </div>
            
            
          

            </div>
            
             
             <div class="easywpm-ultra-success easywpm-notification" id="easywpm-sucess-delete-fields-<?php echo $pos; ?>"><?php _e('Success! This field has been deleted ','wp-users-pro'); ?></div>
            
           
        
          <!-- edit field -->
          
          <div class="user-ultra-sect-second uultra-fields-edition user-ultra-rounded"  id="easywpm-edit-fields-bock-<?php echo $pos; ?>">
        
          </div>
          
          
          <!-- edit field end -->

       </li>


	<?php
	
	}
		
		die();
		
	
	}
		
	// update settings
    function update_settings() 
	{
		foreach($_POST as $key => $value) 
		{
            if ($key != 'submit')
			{
				if (strpos($key, 'html_') !== false)
                {
                }else{
					
                 }
                    
                    ///suggested by WP Team 05-12-2020                       
                    $value =  sanitize_text_field( $value);
					
					$this->wpuserspro_set_option($key, $value) ; 
					
					//special setting for page
					if($key=="wpuserspro_my_account_page")
					{						
						//echo "Page : " . $value;
						 update_option('wpuserspro_my_account_page',$value);				 
					}  
            }
        }
		
		//get checks for each tab		
		
		 if ( isset ( $_GET['tab'] ) )
		 {			 
			  $current = sanitize_text_field($_GET['tab']);
				
          } else {
               $current =  sanitize_text_field($_GET['page']);
				
          }	 
            
		$special_with_check = $this->get_special_checks($current);
         
        foreach($special_with_check as $key)
        {           
                if(!isset($_POST[$key]))
				{			
                    $value= '0';
					
				 } else {
					 
					  $value= sanitize_text_field($_POST[$key]);
				}	 	
			$this->wpuserspro_set_option($key, $value) ;  
			
        }
         
      $this->options = get_option('wpuserspro_options');

        echo '<div class="updated"><p><strong>'.__('Settings saved.','wp-users-pro').'</strong></p></div>';
    }
	
	public function get_special_checks($tab) 
	{
		$special_with_check = array();
		
		if($tab=="settings")
		{				
		
		 $special_with_check = array( 'uultra_loggedin_activated', 'private_message_system','redirect_backend_profile','redirect_backend_registration', 'redirect_registration_when_social','redirect_backend_login', 'social_media_fb_active',  'social_media_google', 'twitter_connect',  'mailchimp_active', 'mailchimp_auto_checked',  'aweber_active', 'aweber_auto_checked','recaptcha_display_registration', 'recaptcha_display_loginform' ,'recaptcha_display_ticketform','recaptcha_display_forgot_password');
		 
		}elseif($tab=="gateway"){
			
			 $special_with_check = array('gateway_paypal_active', 'gateway_bank_active', 'gateway_stripe_active', 'gateway_stripe_success_active' ,'gateway_bank_success_active', 'gateway_free_success_active',  'gateway_paypal_success_active' ,  'appointment_cancellation_active');
		
		}elseif($tab=="mail"){
			
			 $special_with_check = array('bup_smtp_mailing_return_path', 'bup_smtp_mailing_html_txt');
		 
		
		
		}
		
		if($tab=="easywpm-passwordstrength")
		{				
		
			 $special_with_check = array('registration_password_ask','registration_password_ask_confirmation', 'registration_password_lenght','registration_password_1_letter_1_number' ,'registration_password_one_uppercase','registration_password_one_lowercase');		
		 
		}
	
	return  $special_with_check ;
	
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

	
	function initial_setup() {
		
		global $wpuserspro, $wpdb, $wpusersprocomplement ;
		
		$inisetup   = get_option('wpuserspro_ini_setup');
		
		if (!$inisetup) 
		{					
			update_option('wpuserspro_ini_setup', true);
		}
		
		
	}
	
	function include_tab_content() {
		
		global $wpuserspro, $wpdb, $wpusersprocomplement ;
		
		$screen = get_current_screen();
		
		if( strstr($screen->id, $this->slug ) ) 
		{
			if ( isset ( $_GET['tab'] ) ) 
			{
				$tab = sanitize_text_field($_GET['tab']);
				
			} else {
				
				$tab = $this->default_tab;
			}
			
			//
			
			
			if (! get_option('wpuserspro_ini_setup')) 
			{
				//this is the first time
				$this->initial_setup();
				
				$tab = "welcome";				
				require_once (wpuserspro_path.'admin/tabs/'.$tab.'.php');				
				
				
			}else{
			
				if($this->valid_c=="" )
				{
					require_once (wpuserspro_path.'admin/tabs/'.$tab.'.php');			
				
				}else{ //no validated
					
					$tab = "licence";				
					require_once (wpuserspro_path.'admin/tabs/'.$tab.'.php');
					
				}
			
			}
			
			
		}
	}
	
	function reset_email_template() 	
	{
		global  $wpuserspro;
		
		$template = sanitize_text_field($_POST['email_template']);
		$new_template = $this->get_email_template($template);
		$this->wpuserspro_set_option($template, $new_template);
		die();
	}
	
	function get_sites_drop_down_admin($department_id = null)
	{
		global  $wpuserspro;
		
		$html = '';
		
		$site_rows = $wpuserspro->site->get_all();		
		
		$html .= '<select name="wpuserspro__custom_registration_form" id="wpuserspro__custom_registration_form">';
		$html .= '<option value="" selected="selected">'.__('Select a Department','wp-users-pro').'</option>';
		
		foreach ( $site_rows as $site )
		{		
			
			$html .= '<optgroup label="'.$site->site_name.'" >';
			
			//get services						
			$deptos_rows = $wpuserspro->department->get_all_departments($site->site_id);
			foreach ( $deptos_rows as $depto )
			{
				$selected = '';
				if($depto->department_id==$service_id){$selected = 'selected';}
				$html .= '<option value="'.$depto->department_id.'" '.$selected.' >'.$depto->department_name.'</option>';
				
			}
			
			$html .= '</optgroup>';
			
		}
		
		$html .= '</select>';
		
		return $html;
	
	}
	
	function admin_page() 
	{
		global $wpuserspro;

		if (isset($_POST['wpuserspro_update_settings']) &&  $_POST['wpuserspro_reset_email_template']=='') {
            $this->update_settings();
        }
		
		if (isset($_POST['wpuserspro_update_settings']) && $_POST['wpuserspro_reset_email_template']=='yes' && $_POST['wpuserspro_email_template']!='') {
           
			echo '<div class="updated"><p><strong>'.__('Email Template has been restored.','wp-users-pro').'</strong></p></div>';
        }
		
		
		if (isset($_POST['update_wpuserspro_slugs']) && $_POST['update_wpuserspro_slugs']=='bup_slugs')
		{
           $wpuserspro->create_rewrite_rules();
           flush_rewrite_rules();
			echo '<div class="updated"><p><strong>'.__('Rewrite Rules were Saved.','wp-users-pro').'</strong></p></div>';
        }
        
         
           		
	?>

 <div class="wpuserspro-message-blue wc-connect">				
					
				 
				
				
     
                 <table width="100%" border="1">
                  <tbody>
                    <tr>
                      <td width="10%"  rowspan="3">
                          
                          <img width="100px" src="<?php echo wpuserspro_url?>admin/images/logo-welcome.png"  />
                        
                      </td>
                      <td width="63%" >
                          <h2>WP USERS PRO - <?php _e('The best solution for protecting your digital content.','wp-users-pro')?><span class="wpuserspro-go-pro-topbutton"><a href="?page=wpuserspro&tab=pro" class="button button-secondary wpuserspro-btn-red-pro" ><i class="uultra-icon-plus"></i>&nbsp;&nbsp; <?php _e('Why Go Pro?','wp-users-pro')?>
                </a></span></h2>
                        </td>
                        
                      <td width="27%" >
                          
                     </td>
                    </tr>
                    <tr>
                      <td><?php _e('This powerful plugin gives you even more control of what your members can see based on the different subscription levels you offer.','wp-users-pro')?></td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>                    
                        </td>
                      <td>&nbsp;</td>
                    </tr>
                  </tbody>
            </table>
     
	      </div>
           
	
		<div class="wrap <?php echo $this->slug; ?>-admin"> 
            
          
            
            
            
        
           <?php if (get_option('wpuserspro_ini_setup')) 
				{?>
            
                <h2 class="nav-tab-wrapper"><?php $this->admin_tabs(); ?>
                </h2>  
                
            <?php } ?>       
            

			<div class="<?php echo $this->slug; ?>-admin-contain">    
            
				<?php 		
				
					$this->include_tab_content(); 
				?>
				
				<div class="clear"></div>
                
                
				
			</div>
            
            <div class="clear"><?php
			
			$link = "<a href='https://wordpress.org/support/plugin/wp-users-pro/reviews/?filter=5' target='_blank'> 5 stars </a>";
			printf(__("If you like <strong>WP Users Pro<strong> please consider leaving us a %s rating. A huge thank you from the WP Users Pro Team in advanced.",'wp-users-pro'), $link)?></div>
			
		</div>

	<?php }

}

$key = "admin";
$this->{$key} = new WpUsersProAdmin();