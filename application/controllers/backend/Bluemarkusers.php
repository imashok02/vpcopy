<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Bluemarkusers Controller
 */
class Bluemarkusers extends BE_Controller {

	/**
	 * Construt required variables
	 */
	function __construct() {

		parent::__construct( MODULE_CONTROL, 'Blue Mark Users' );
		///start allow module check by MN
		$selected_shop_id = $this->session->userdata('selected_shop_id');
		$shop_id = $selected_shop_id['shop_id'];
		
		$conds_mod['module_name'] = $this->router->fetch_class();
		$module_id = $this->Module->get_one_by($conds_mod)->module_id;
		
		$logged_in_user = $this->ps_auth->get_user_info();

		$user_id = $logged_in_user->user_id;
		if(empty($this->User->has_permission( $module_id,$user_id )) && $logged_in_user->user_is_sys_admin!=1){
			return redirect( site_url('/admin/dashboard/index/'.$shop_id) );
		}
		///end check
	}

	/**
	 * List down the registered users
	 */
	function index() {

        $conds['no_publish_unpublish_filter'] = 1;
        $conds['order_by'] = 1;
        $conds['order_by_field'] = "updated_date";
        $conds['order_by_type'] = "desc";

		// get rows count
		$this->data['rows_count'] = $this->Blue_mark->count_all_by($conds);
		// get categories
		$this->data['bluemarks'] = $this->Blue_mark->get_all_by( $conds , $this->pag['per_page'], $this->uri->segment( 4 ) );

		// load index logic
		parent::index();
	}

	/**
	 * Searches for the first match.
	 */
	function search() {
		

		// breadcrumb urls
		$this->data['action_title'] = get_msg( 'purchased_user_search' );

		// condition with search term
		if ($this->input->post('submit') != NULL ) {

			$conds = array( 'searchterm' => $this->searchterm_handler( $this->input->post( 'searchterm' )));


			if($this->input->post('searchterm') != "") {
				$conds['searchterm'] = $this->input->post('searchterm');
				$this->data['searchterm'] = $this->input->post('searchterm');
				$this->session->set_userdata(array("searchterm" => $this->input->post('searchterm')));
			} else {

				$this->session->set_userdata(array("searchterm" => NULL));
			}

            if ($this->input->post('is_verify_blue_mark') != "" || $this->input->post('is_verify_blue_mark') != '0') {
                $conds['is_verify_blue_mark'] = $this->input->post('is_verify_blue_mark');
                $this->data['is_verify_blue_mark'] = $this->input->post('is_verify_blue_mark');
                $this->session->set_userdata(array("is_verify_blue_mark" => $this->input->post('is_verify_blue_mark')));
            } else {
                $this->session->set_userdata(array("is_verify_blue_mark" => NULL));
            }

			// no publish filter
			$conds['no_publish_filter'] = 1;

		} else {
//			//read from session value
			if($this->session->userdata('searchterm') != NULL){
				$conds['searchterm'] = $this->session->userdata('searchterm');
				$this->data['searchterm'] = $this->session->userdata('searchterm');
			}

            if($this->session->userdata('is_verify_blue_mark') != NULL){
                $conds['is_verify_blue_mark'] = $this->session->userdata('is_verify_blue_mark');
                $this->data['is_verify_blue_mark'] = $this->session->userdata('is_verify_blue_mark');
            }

			// no publish filter
			$conds['no_publish_filter'] = 1;
		}

		if (empty($conds['searchterm']) && empty($conds['is_verify_blue_mark'])) {

			// pagination
			$this->data['rows_count'] = $this->Blue_mark->count_all_by( $conds );

			// search data
			$this->data['bluemarks'] = $this->Blue_mark->get_all_by( $conds, $this->pag['per_page'], $this->uri->segment( 4 ) );

		} else {
			// pagination
			$this->data['rows_count'] = $this->User->count_all_by( $conds );

			// search data
			$this->data['bluemarks'] = $this->User->get_all_by( $conds, $this->pag['per_page'], $this->uri->segment( 4 ) );
		}



        // load add list
		parent::search();
	}

	
	/**
 	* Update the existing one
	*/
	function edit( $id ) {

	// breadcrumb urls
	$this->data['action_title'] = get_msg( 'purchased_prd_view' );

	// load user
	$this->data['bluemark'] = $this->Blue_mark->get_one( $id );

	//passing the data to view
	$this->data['id'] = $id;
	// call the parent edit logic
	parent::edit( $id );
	}

	/** verify blue mark */

	function save( $id = false )
	{
        $data = array();

		// if 'is_verify_blue_mark' is checked,
		if ( $this->has_data( 'verify_blue_mark' )) {
			$user_data['is_verify_blue_mark'] = $this->get_data( 'verify_blue_mark' );
		}

        // update date
        if ( $this->has_data( 'verify_blue_mark' )) {
            $data['updated_date'] = date("Y-m-d H:i:s");
        }


        $this->Blue_mark->save( $data, $id );


        $user_id = $this->Blue_mark->get_one($id)->user_id;
		$user_name = $this->User->get_one($user_id)->user_name;

		if ( ! $this->User->save( $user_data, $user_id )) {
		// if there is an error in inserting user data,	

			// rollback the transaction
			$this->db->trans_rollback();

			// set error message
			$this->data['error'] = get_msg( 'err_model' );
			
			return;
		}

		// send noti it user

		if ($user_data['is_verify_blue_mark'] == '1') {
			$message = get_msg( 'verify_blue_mark_noti_approve' ); 
		} else {
			$message = get_msg( 'verify_blue_mark_noti_reject' ); 
		}

		$devices = $this->Noti->get_all_device_in($user_id)->result();

		$device_ids = array();
		if ( count( $devices ) > 0 ) {
			foreach ( $devices as $device ) {
				$device_ids[] = $device->device_token;
			}
		}

		$status = $this->send_android_fcm( $device_ids, $message );

		//// End - Send Noti /////

		if ( ! $this->check_trans()) {
        	
			// set flash error message
			$this->set_flash_msg( 'error', get_msg( 'err_model' ));
		} else {


			if ( !$status ) {
				$error_msg .= get_msg( 'noti_sent_fail' );
				$this->set_flash_msg( 'error', get_msg( 'noti_sent_fail' ) );
			}


			if ( $status ) {
				$this->set_flash_msg( 'success', get_msg( 'noti_sent_success' ) . ' ' . $user_name );
			}

		}

		// send email to user

		$this->load->library( 'PS_Mail' );

		if ($user_data['is_verify_blue_mark'] == '1') {
			$subject = get_msg( 'verify_blue_mark_noti_approve' ); 
		} else {
			$subject = get_msg( 'verify_blue_mark_noti_reject' ); 
		}

		$is_verify_blue_mark = $user_data['is_verify_blue_mark'];

		if ( !send_user_blue_mark_email( $user_id, $subject, $is_verify_blue_mark )) {

			$this->set_flash_msg( 'error', get_msg( 'verify_email_not_send_user' ) . ' ' . $user_name );

		
		}
		
		// redirect to list view
		redirect( $this->module_site_url() );
	}


	/**
	 * Determines if valid input.
	 *
	 * @return     boolean  True if valid input, False otherwise.
	 */
	function is_valid_input( $id = 0 ) 
	{
		
		return true;
	}

	/** send noti*/
	function send_android_fcm( $registatoin_ids, $message) 
    {
    	//Google cloud messaging GCM-API url
    	$url = 'https://fcm.googleapis.com/fcm/send';

    	$noti_arr = array(
    		'title' => get_msg('site_name'),
    		'body' => $message,
    		'sound' => 'default',
    		'message' => $message,
    		'flag' => 'verify_blue_mark',
	    	'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
    	);

    	$fields = array(
    		'sound' => 'default',
    		'notification' => $noti_arr,
    	    'registration_ids' => $registatoin_ids,
    	    'data' => array(
    	    	'message' => $message,
    	    	'flag' => 'verify_blue_mark',
    	    	'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
    	    )

    	);

    	
    	$fcm_api_key = $this->Backend_config->get_one('be1')->fcm_api_key;
    	define("GOOGLE_API_KEY", $fcm_api_key);  	
    		
    	$headers = array(
    	    'Authorization: key=' . GOOGLE_API_KEY,
    	    'Content-Type: application/json'
    	);
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_POST, true);
    	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);	
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    	$result = curl_exec($ch);				
    	if ($result === FALSE) {
    	    die('Curl failed: ' . curl_error($ch));
    	}
    	curl_close($ch);
    	return $result;
    }
		
}