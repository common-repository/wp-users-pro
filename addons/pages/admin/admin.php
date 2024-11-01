<?php
class WPUsersProPage {

	var $options;

	function __construct() {
		
		
		$this->ini_module();
	
		/* Plugin slug and version */
		$this->slug = 'wpuserspro';
		$this->subslug = 'wpuserspro-pages';
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		$this->plugin_data = get_plugin_data(wpuserspro_profiles_path . 'index.php', false, false);
		$this->version = $this->plugin_data['Version'];
		
		/* Priority actions */
		add_action('admin_menu', array(&$this, 'add_menu'), 11);
		add_action('admin_enqueue_scripts', array(&$this, 'add_styles'), 9);
		add_action('admin_head', array(&$this, 'admin_head'), 9 );
		add_action('admin_init', array(&$this, 'admin_init'), 9);
		
		
	}
	
	
	
	
		
	public function ini_module()
	{
		global $wpdb;		   		  		   
		
	}
	
	function admin_init() 
	{
	
		$this->tabs = array(
			'manage' => __('Users Pages','wp-users-pro')
			
		);
		$this->default_tab = 'manage';		
		
	}		
	
	function admin_head(){

	}

	function add_styles(){
	
		wp_register_script( 'wpuserspro_pages_js', wpuserspro_profiles_url . 'admin/scripts/admin.js', array( 
			'jquery'
		) );
		wp_enqueue_script( 'wpuserspro_pages_js' );
	
		wp_register_style('wpuserspro_profiles_css', wpuserspro_profiles_url . 'admin/css/admin.css');
		wp_enqueue_style('uwpuserspro_profiles_css');
		
	}
	
	function add_menu()
	{
		add_submenu_page( 'wpuserspro', __('User Pages','wp-users-pro'), __('User Pages','wp-users-pro'), 'manage_options', $this->subslug, array(&$this, 'admin_page') );
		
	
		
	}

	function admin_tabs( $current = null ) {
			$tabs = $this->tabs;
			$links = array();
			if ( isset ( $_GET['tab'] ) ) {
				$current = sanitize_text_field($_GET['tab']);
			} else {
				$current = $this->default_tab;
			}
			foreach( $tabs as $tab => $name ) :
				if ( $tab == $current ) :
					$links[] = "<a class='nav-tab nav-tab-active' href='?page=".$this->subslug."&tab=$tab'>$name</a>";
				else :
					$links[] = "<a class='nav-tab' href='?page=".$this->subslug."&tab=$tab'>$name</a>";
				endif;
			endforeach;
			foreach ( $links as $link )
				echo $link;
	}

	function get_tab_content() {
		$screen = get_current_screen();
		if( strstr($screen->id, $this->subslug ) ) {
			if ( isset ( $_GET['tab'] ) ) {
				$tab = sanitize_text_field($_GET['tab']);
			} else {
				$tab = $this->default_tab;
			}
			require_once wpuserspro_profiles_path.'admin/panels/'.$tab.'.php';
		}
	}
	
	
	
	function admin_page() {
		
		
		global $wpuserspro;
		
		
		if (isset($_POST['update_settings']) &&  isset($_POST['reset_email_template']) && $_POST['reset_email_template']=='') {
            $wpuserspro->admin->update_settings();
        }
		
		if (isset($_POST['update_ucaptcha_slugs']) && $_POST['update_ucaptcha_slugs']=='bup_slugs')
		{
		   $wpuserspro->admin->update_settings();
          // $ultimatecaptcha->create_rewrite_rules();
			echo '<div class="updated"><p><strong>'.__('Rewrite Rules were Saved.','wp-users-pro').'</strong></p></div>';
        }
	
		
				
	?>
	
		<div class="wrap <?php echo $this->slug; ?>-admin">
        
           <h2>WP Users Pro- <?php _e('CUSTOM PAGES SETTINGS','wp-users-pro'); ?></h2>
           
           <div id="icon-users" class="icon32"></div>
			
						
			<h2 class="nav-tab-wrapper"><?php $this->admin_tabs(); ?></h2>

			<div class="<?php echo $this->slug; ?>-admin-contain">
				
				<?php $this->get_tab_content(); ?>
				
				<div class="clear"></div>
				
			</div>
			
		</div>

	<?php }

}
global $wpuserspro_page;
$wpuserspro_page = new WPUsersProPage();