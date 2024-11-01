<?php
class WPUsersProUser
{
	var $table_prefix = 'wpuserspro';
	var $ajax_p = 'wpuserspro';
	
	function __construct() 
	{
		$this->current_page = $_SERVER['REQUEST_URI'];
		
		add_action( 'wp_ajax_'.$this->ajax_p.'_subscriptions_by_member', array( &$this, 'subscriptions_by_member' ));


		
	
	}
	
	public function get_user_info()
	{
		$current_user = wp_get_current_user();
		return $current_user;

		
	}
	
	public function subscriptions_by_member()
	{	
	
		global $wpdb, $wpuserspro;	
		
		$html = '';		
		
		$user_id =  sanitize_text_field($_POST['client_id']);	
		
		$currency_symbol =  $wpticketultra->get_option('paid_membership_symbol');
		$date_format =  $wpuserspro->get_int_date_format();
		$time_format =  $wpuserspro->get_time_format();
		$datetime_format =  $wpuserspro->get_date_to_display();
	
		
		$html .= '<div class="easywpm-welcome-panel">' ;
		
		$subscriptions_rows = $this->get_my_subscriptions($user_id);
		
		if (!empty($subscriptions_rows))
		
		{
		
		
		$html .= ' <table width="100%" class="">
            <thead>
                <tr>
                    <th width="4%" class="bp_table_row_hide" >'.__('#', 'wp-ticket-ultra').'</th>
                    <th width="13%">'.__('Date', 'wp-ticket-ultra').'</th> ';
                    
                    
                                         
                     $html .= '  <th width="14%" id="wptu-ticket-col-department">'.__('Department', 'wp-ticket-ultra').'</th>
                   
                    
                    <th width="14%" id="wptu-ticket-col-staff">'.__('Last Replier', 'wp-ticket-ultra').'</th>
                   
                   
                     <th width="18%">'.__('Subject', 'wp-ticket-ultra').'</th>
                     <th width="12%"  id="wptu-ticket-col-lastupdate">'.__('Last Update', 'wp-ticket-ultra').'</th>
                    <th width="10%">'.__('Priority', 'wp-ticket-ultra').'</th>
                    
                     
                     <th width="14%" id="wptu-ticket-col-status">'.__('Status', 'wp-ticket-ultra').'</th>
					 <th width="14%" id="wptu-ticket-col-actions">'.__('Actions', 'wp-ticket-ultra').'</th>
                    
                </tr>
            </thead>
            
            <tbody>';
            
           
			$filter_name= '';
			$phone= '';
			foreach($subscriptions_rows as $ticket) {
				
				
				
								

               $html .= ' <tr>
                    <td class="bp_table_row_hide">'.$ticket->ticket_id.'</td>
                     <td>'. $date_submited.' </td>';        
                     
                                         
					  $html .= '  <td id="wptu-ticket-col-department">'.$ticket->department_name.' </td>
                     
                      <td id="wptu-ticket-col-staff">'.$last_replier_label .'</td>
                   
                    <td>'.$wpticketultra->cut_string($ticket->ticket_subject,20).' </td>
                     <td  id="wptu-ticket-col-lastupdate">'. $nice_time_last_update.'</td>
                    
                    
                    <td>'.  $priority_legend.'</td>                  
                     
                      <td id="wptu-ticket-col-status">'.$status_legend.'</td>
                   <td> <a href="?page=wpticketultra&tab=ticketedit&see&id='.$ticket->ticket_id.'" class="wptu-appointment-edit-module" appointment-id="'.$ticket->ticket_id.'" title="'.__('Edit','wp-ticket-ultra').'"><i class="fa fa-edit"></i></a>
                   
				
                  
                    </td> </tr>';         
                
              
				} //end for each
				
				 $html .= ' </tbody> </table>';
		
					
			}else{
			
					$html .= " <p>".__("There are no subscriptions .","wp-users-pro")."</p>";
			} 

          
		
		$html .= '</div>' ;	
		
	
			
		echo $html ;		
		die();		
	
	}
	
	public function get_my_subscriptions($user_id)
	{
		
		global $wpdb,  $wpuserspro;
		
		$sql = 'SELECT membership.*, subs.*	 
		FROM ' . $wpdb->prefix . ''.$this->table_prefix . '_subscriptions subs ' ;
				
		$sql .= " RIGHT JOIN ". $wpdb->prefix.$this->table_prefix."_membership_packages membership ON (membership.membership_id = subs.subscription_package_id )";				
		$sql .= " WHERE membership.membership_id = subs.subscription_package_id  ";	
		$sql .= " AND subs.subscription_user_id  = '".$user_id."' ";
		$sql .= " ORDER BY  subs.subscription_id DESC";	
		$rows = $wpdb->get_results($sql );
		
		//echo $sql;
		
		return $rows ;		
		
		
	}
	
	public function get_my_active_subscriptions($user_id)
	{
		
		global $wpdb,  $wpuserspro;
		
		$sql = 'SELECT membership.*, subs.*	 
		FROM ' . $wpdb->prefix . ''.$this->table_prefix . '_subscriptions subs ' ;
				
		$sql .= " RIGHT JOIN ". $wpdb->prefix.$this->table_prefix."_membership_packages membership ON (membership.membership_id = subs.subscription_package_id )";				
		$sql .= " WHERE (membership.membership_id = subs.subscription_package_id AND subs.subscription_status = 1 ";	
		$sql .= " AND subs.subscription_user_id  = '".$user_id."') ";	
		
		$sql .= " OR  (membership.membership_id = subs.subscription_package_id AND subs.subscription_status = 2 AND subs.subscription_user_id  = '".$user_id."') ";
		$sql .= " ORDER BY  subs.subscription_id DESC";		
		$rows = $wpdb->get_results($sql );
		
		//echo $sql;
		
		return $rows ;		
		
		
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
		
		$sql = ' SELECT count(*) as total,  usu.* 
		FROM ' . $wpdb->users . ' usu  ' ;	
			
	//	$sql .= " RIGHT JOIN ".$wpdb->prefix . $this->table_prefix ."_membership_packages package ON (package.membership_id = sub.subscription_package_id )";		
		//$sql .= " RIGHT JOIN ".$wpdb->users ." usu ON (usu.ID = sub.subscription_user_id)";

		//$sql .= " WHERE package.membership_id = sub.subscription_package_id  ";	
		//$sql .= " AND usu.ID = sub.subscription_user_id ";			
		
		

		
		if($keyword !='')	
		{				
			$sql .= " AND (usu.display_name LIKE '%".$keyword."%')";
			
		}
		
		if($day!=""){$sql .= " AND DAY(usu.user_registered ) = '$day'  ";	}
		if($month!=""){	$sql .= " AND MONTH(usu.user_registered ) = '$month'  ";	}		
		if($year!=""){$sql .= " AND YEAR(usu.user_registered ) = '$year'";}	
		
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
		$sql = ' SELECT  usu.* 
		FROM ' .$wpdb->users . ' usu  ' ;	
			
	//	$sql .= " RIGHT JOIN ".$wpdb->prefix . $this->table_prefix ."_membership_packages package ON (package.membership_id = sub.subscription_package_id )";		
		//$sql .= " RIGHT JOIN ".$wpdb->users ." usu ON (usu.ID = sub.subscription_user_id)";

	//	$sql .= " WHERE package.membership_id = sub.subscription_package_id  ";	
		//$sql .= " AND usu.ID = sub.subscription_user_id ";			
		
		
		
		if($keyword !='')	
		{				
			$sql .= " AND (usu.display_name LIKE '%".$keyword."%' )";
			
		}
		
		if($day!=""){$sql .= " AND DAY(usu.user_registered ) = '$day'  ";	}
		if($month!=""){	$sql .= " AND MONTH(usu.user_registered ) = '$month'  ";	}		
		if($year!=""){$sql .= " AND YEAR(usu.user_registered ) = '$year'";}	
		
		$sql .= " ORDER BY usu.ID DESC";		
		
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
	
	
	
}
$key = "user";
$this->{$key} = new WPUsersProUser();
?>