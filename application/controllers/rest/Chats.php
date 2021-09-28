<?php
require_once( APPPATH .'libraries/REST_Controller.php' );

/**
 * REST API for Notification
 */
class Chats extends API_Controller
{
	/**
	 * Constructs Parent Constructor
	 */
	function __construct()
	{
		// call the parent
		parent::__construct( 'Chat' );

	}

	/**
	 * Add Chat History
	 */
	function add_post()
	{
		// validation rules for chat history
		$rules = array(
			array(
	        	'field' => 'item_id',
	        	'rules' => 'required'
	        ),
	        array(
	        	'field' => 'buyer_user_id',
	        	'rules' => 'required'
	        ),
	        array(
	        	'field' => 'seller_user_id',
	        	'rules' => 'required'
	        )

        );

        $item_id = $this->post('item_id');
        $seller_user_id = $this->post('seller_user_id');
        $buyer_user_id = $this->post('buyer_user_id');

        $status = $this->Item->get_one($item_id)->status;
        $chk_seller_info = $this->User->get_one($seller_user_id);
        $chk_buyer_info = $this->User->get_one($buyer_user_id);

        if ($status == -1 || $chk_seller_info->is_empty_object == 1 || $chk_buyer_info->is_empty_object == 1) {
        	$this->error_response( get_msg( 'cannot_chat' ));
        }


		// exit if there is an error in validation,
        if ( !$this->is_valid( $rules )) exit;
        $type = $this->post('type');

        $chat_data = array(

        	"item_id" => $this->post('item_id'), 
        	"buyer_user_id" => $this->post('buyer_user_id'), 
        	"seller_user_id" => $this->post('seller_user_id')

        );

        $chat_data_count = $this->Chat->count_all_by($chat_data);

        if ($chat_data_count > 1) {
        	$this->Chat->delete_by($chat_data);
        }

        $chat_history_data = $this->Chat->get_one_by($chat_data);


        if($chat_history_data->id == "") {

        	if ( $type == "to_buyer" ) {

		    	$buyer_unread_count = $chat_history_data->buyer_unread_count;
		    	
		    	$chat_data = array(

		        	"item_id" => $this->post('item_id'), 
		        	"buyer_user_id" => $this->post('buyer_user_id'), 
		        	"seller_user_id" => $this->post('seller_user_id'),
		        	"buyer_unread_count" => $buyer_unread_count + 1,
		        	"added_date" => date("Y-m-d H:i:s"),
		        	"offer_status" => 1

		        );

		    	} elseif ( $type == "to_seller" ) {

		    	$seller_unread_count = $chat_history_data->seller_unread_count;
		    	
		    	$chat_data = array(

		        	"item_id" => $this->post('item_id'), 
		        	"buyer_user_id" => $this->post('buyer_user_id'), 
		        	"seller_user_id" => $this->post('seller_user_id'),
		        	"seller_unread_count" => $seller_unread_count + 1,
		        	"added_date" => date("Y-m-d H:i:s"),
		        	"offer_status" => 1

		        );

		    	}

	        if ( !$this->Chat->save($chat_data)) {
	        	
	        	$this->error_response( get_msg( 'err_chat_history_save' ));
	        
	        } else {

	        	$obj = $this->Chat->get_one_by($chat_data);
				$this->ps_adapter->convert_chathistory( $obj );
				$this->custom_response( $obj );

	        }

	    } else {

	    	if ( $type == "to_buyer" ) {

		    	$buyer_unread_count = $chat_history_data->buyer_unread_count;
		    	$is_accept = $chat_history_data->is_accept;
		    	$offer_status = $chat_history_data->offer_status;
		    	
		    	$chat_data = array(

		        	"item_id" => $this->post('item_id'), 
		        	"buyer_user_id" => $this->post('buyer_user_id'), 
		        	"seller_user_id" => $this->post('seller_user_id'),
		        	"buyer_unread_count" => $buyer_unread_count + 1,
		        	"added_date" => date("Y-m-d H:i:s"),
		        	"offer_status" => $offer_status,
		        	"is_accept" => $is_accept

		        );

		    	} elseif ( $type == "to_seller" ) {

		    	$seller_unread_count = $chat_history_data->seller_unread_count;
		    	
		    	$chat_data = array(

		        	"item_id" => $this->post('item_id'), 
		        	"buyer_user_id" => $this->post('buyer_user_id'), 
		        	"seller_user_id" => $this->post('seller_user_id'),
		        	"seller_unread_count" => $seller_unread_count + 1,
		        	"added_date" => date("Y-m-d H:i:s"),
		        	"offer_status" => $offer_status,
		        	"is_accept" => $is_accept

		        );

		    	}
	    	

	    	if ( $this->Chat->save($chat_data,$chat_history_data->id)) {
	        	
	        	$obj = $this->Chat->get_one_by($chat_data);
				$this->ps_adapter->convert_chathistory( $obj );
				$this->custom_response( $obj );
	        
	        }

	    }


	}


	/**
	 * Update Price 
	 */
	function update_price_post()
	{
		// validation rules for chat history
		$rules = array(
			array(
	        	'field' => 'item_id',
	        	'rules' => 'required'
	        ),
	        array(
	        	'field' => 'buyer_user_id',
	        	'rules' => 'required'
	        ),
	        array(
	        	'field' => 'seller_user_id',
	        	'rules' => 'required'
	        ),
	        array(
	        	'field' => 'nego_price',
	        	'rules' => 'required'
	        )
        );

		// exit if there is an error in validation,
        if ( !$this->is_valid( $rules )) exit;

        $type = $this->post('type');

        $chat_data = array(

        	"item_id" => $this->post('item_id'), 
        	"buyer_user_id" => $this->post('buyer_user_id'), 
        	"seller_user_id" => $this->post('seller_user_id')

        );

        $chat_history_data = $this->Chat->get_one_by($chat_data);


        if($chat_history_data->id == "") {

        	if ( $type == "to_buyer" ) {

        		//prepare data for noti
		    	$user_ids[] = $this->post('buyer_user_id');


	        	$devices = $this->Noti->get_all_device_in($user_ids)->result();
	        	//print_r($devices);die;	

				$device_ids = array();
				if ( count( $devices ) > 0 ) {
					foreach ( $devices as $device ) {
						$device_ids[] = $device->device_token;
					}
				}


				$user_id = $this->post('buyer_user_id');
	       		$user_name = $this->User->get_one($user_id)->user_name;

		    	$price = $this->post('nego_price');
	       		if ( $price == 0) {
		    		$data['message'] = "Offer Rejected!";
		    		$offer_status = 4;
		    	} else {
		    		$data['message'] = "Make Offer!";
		    		$offer_status = 2;
		    	}
		    	$data['buyer_user_id'] = $this->post('buyer_user_id');
		    	$data['seller_user_id'] = $this->post('seller_user_id');
		    	$data['sender_name'] = $user_name;
		    	$data['item_id'] = $this->post('item_id');

        		
		    	$buyer_unread_count = $chat_history_data->buyer_unread_count;
		    	
		    	$chat_data = array(

		        	"item_id" => $this->post('item_id'), 
		        	"buyer_user_id" => $this->post('buyer_user_id'), 
		        	"seller_user_id" => $this->post('seller_user_id'),
		        	"buyer_unread_count" => $buyer_unread_count + 1,
		        	"added_date" => date("Y-m-d H:i:s"),
		        	"nego_price" => $this->post('nego_price'),
		        	"offer_status" => $offer_status,
		        	"is_accept" => 0

		        );


		       

	    	} elseif ( $type == "to_seller" ) {

	    		//prepare data for noti
		    	$user_ids[] = $this->post('seller_user_id');


	        	$devices = $this->Noti->get_all_device_in($user_ids)->result();
	        	//print_r($devices);die;	

				$device_ids = array();
				if ( count( $devices ) > 0 ) {
					foreach ( $devices as $device ) {
						$device_ids[] = $device->device_token;
					}
				}


				$user_id = $this->post('seller_user_id');
	       		$user_name = $this->User->get_one($user_id)->user_name;

		    	$price = $this->post('nego_price');
	       		if ( $price == 0) {
		    		$data['message'] = "Offer Rejected!";
		    		$offer_status = 4;
		    	} else {
		    		$data['message'] = "Make Offer!";
		    		$offer_status = 2;
		    	}
		    	$data['buyer_user_id'] = $this->post('buyer_user_id');
		    	$data['seller_user_id'] = $this->post('seller_user_id');
		    	$data['sender_name'] = $user_name;
		    	$data['item_id'] = $this->post('item_id');

		    	$seller_unread_count = $chat_history_data->seller_unread_count;
		    	
		    	$chat_data = array(

		        	"item_id" => $this->post('item_id'), 
		        	"buyer_user_id" => $this->post('buyer_user_id'), 
		        	"seller_user_id" => $this->post('seller_user_id'),
		        	"seller_unread_count" => $seller_unread_count + 1,
		        	"added_date" => date("Y-m-d H:i:s"),
		        	"nego_price" => $this->post('nego_price'),
		        	"offer_status" => $offer_status,
		        	"is_accept" => 0

		        );

	    	}

	       	//sending noti
	    	$status = send_android_fcm_chat( $device_ids, $data );

	        $this->Chat->save($chat_data);	
	        $obj = $this->Chat->get_one_by($chat_data);
			$this->ps_adapter->convert_chathistory( $obj );
			$this->custom_response( $obj );


	    } else {

		    	if ( $type == "to_buyer" ) {


		    	//prepare data for noti
		    	$user_ids[] = $this->post('buyer_user_id');


	        	$devices = $this->Noti->get_all_device_in($user_ids)->result();
	        	//print_r($devices);die;	

				$device_ids = array();
				if ( count( $devices ) > 0 ) {
					foreach ( $devices as $device ) {
						$device_ids[] = $device->device_token;
					}
				}


				$user_id = $this->post('buyer_user_id');
	       		$user_name = $this->User->get_one($user_id)->user_name;

		    	$price = $this->post('nego_price');
	       		if ( $price == 0) {
		    		$data['message'] = "Offer Rejected!";
		    		$offer_status = 4;
		    	} else {
		    		$data['message'] = "Make Offer!";
		    		$offer_status = 2;
		    	}
		    	$data['buyer_user_id'] = $this->post('buyer_user_id');
		    	$data['seller_user_id'] = $this->post('seller_user_id');
		    	$data['sender_name'] = $user_name;
		    	$data['item_id'] = $this->post('item_id');


		    	$buyer_unread_count = $chat_history_data->buyer_unread_count;
		    	
		    	$chat_data = array(

		        	"item_id" => $this->post('item_id'), 
		        	"buyer_user_id" => $this->post('buyer_user_id'), 
		        	"seller_user_id" => $this->post('seller_user_id'),
		        	"buyer_unread_count" => $buyer_unread_count + 1,
		        	"added_date" => date("Y-m-d H:i:s"),
		        	"nego_price" => $this->post('nego_price'),
		        	"offer_status" => $offer_status,
		        	"is_accept" => 0

		        );

		    	} elseif ( $type == "to_seller" ) {


		    	$user_ids[] = $this->post('seller_user_id');

	        	$devices = $this->Noti->get_all_device_in($user_ids)->result();
	        


				$device_ids = array();
				if ( count( $devices ) > 0 ) {
					foreach ( $devices as $device ) {
						$device_ids[] = $device->device_token;
					}
				}


				$user_id = $this->post('seller_user_id');
	       		$user_name = $this->User->get_one($user_id)->user_name;

		    	$price = $this->post('nego_price');
	       		if ( $price == 0) {
		    		$data['message'] = "Offer Rejected!";
		    		$offer_status = 4;
		    	} else {
		    		$data['message'] = "Make Offer!";
		    		$offer_status = 2;
		    	}
		    	$data['buyer_user_id'] = $this->post('buyer_user_id');
		    	$data['seller_user_id'] = $this->post('seller_user_id');
		    	$data['sender_name'] = $user_name;
		    	$data['item_id'] = $this->post('item_id');

		    	$seller_unread_count = $chat_history_data->seller_unread_count;
		    	
		    	$chat_data = array(

		        	"item_id" => $this->post('item_id'), 
		        	"buyer_user_id" => $this->post('buyer_user_id'), 
		        	"seller_user_id" => $this->post('seller_user_id'),
		        	"seller_unread_count" => $seller_unread_count + 1,
		        	"added_date" => date("Y-m-d H:i:s"),
		        	"nego_price" => $this->post('nego_price'),
		        	"offer_status" => $offer_status,
		        	"is_accept" => 0

		        );

		    	}

	    	

	    	if( !$this->Chat->Save( $chat_data,$chat_history_data->id )) {

	    		$this->error_response( get_msg( 'err_price_update' ));

	    	
	    	} else {

	    		//sending noti
	    		$status = send_android_fcm_chat( $device_ids, $data );

	    		$obj = $this->Chat->get_one_by($chat_data);
				$this->ps_adapter->convert_chathistory( $obj );
				$this->custom_response( $obj );
	    	}

	    }
	}

	/**
	 * Update count
	 */
	function reset_count_post()
	{
		// validation rules for chat history
		$rules = array(
			array(
	        	'field' => 'item_id',
	        	'rules' => 'required'
	        ),
	        array(
	        	'field' => 'buyer_user_id',
	        	'rules' => 'required'
	        ),
	        array(
	        	'field' => 'seller_user_id',
	        	'rules' => 'required'
	        ),
	        array(
	        	'field' => 'type',
	        	'rules' => 'required'
	        )
        );


		// exit if there is an error in validation,
        if ( !$this->is_valid( $rules )) exit;

        $chat_data = array(

        	"item_id" => $this->post('item_id'), 
        	"buyer_user_id" => $this->post('buyer_user_id'), 
        	"seller_user_id" => $this->post('seller_user_id')

        );

        $chat_history_data = $this->Chat->get_one_by($chat_data);


        if($chat_history_data->id == "") {
	        	
	        $this->error_response( get_msg( 'err_chat_history_not_exist' ));


	    } else {
	    	
	    	if($this->post('type') == "to_seller") {

		    	$chat_data_update = array(

		        	"item_id" => $this->post('item_id'), 
		        	"buyer_user_id" => $this->post('buyer_user_id'), 
		        	"seller_user_id" => $this->post('seller_user_id'),
		        	"seller_unread_count" => 0

		        );

		    } else if($this->post('type') == "to_buyer") {

		    	$chat_data_update = array(

		        	"item_id" => $this->post('item_id'), 
		        	"buyer_user_id" => $this->post('buyer_user_id'), 
		        	"seller_user_id" => $this->post('seller_user_id'),
		        	"buyer_unread_count" => 0

		        );
		    }

	    	if( !$this->Chat->Save( $chat_data_update,$chat_history_data->id )) {

	    		$this->error_response( get_msg( 'err_count_update' ));

	    	
	    	} else {

	    		$obj = $this->Chat->get_one_by($chat_data);
				$this->ps_adapter->convert_chathistory( $obj );
				$this->custom_response( $obj );

	    	}


	    }


	}

    /* Update accept or not
    */


    function update_accept_post()
	{
		// validation rules for chat history
		$rules = array(
			array(
	        	'field' => 'item_id',
	        	'rules' => 'required'
	        ),
	        array(
	        	'field' => 'buyer_user_id',
	        	'rules' => 'required'
	        ),
	        array(
	        	'field' => 'seller_user_id',
	        	'rules' => 'required'
	        ),
	        array(
	        	'field' => 'nego_price',
	        	'rules' => 'required'
	        )
        );

		// exit if there is an error in validation,
        if ( !$this->is_valid( $rules )) exit;
        $type = $this->post('type');

        $chat_data = array(

        	"item_id" => $this->post('item_id'), 
        	"buyer_user_id" => $this->post('buyer_user_id'), 
        	"seller_user_id" => $this->post('seller_user_id')

        );

        $chat_history_data = $this->Chat->get_one_by($chat_data);
        //print_r($chat_history_data);die;

        // is_accept checking by seller_id and item_id
        $accept_checking_data = array(

        	"item_id" => $this->post('item_id'), 
        	"buyer_user_id" => $this->post('buyer_user_id'),
        	"seller_user_id" => $this->post('seller_user_id'),

        );
        //print_r($accept_checking_data);die;
        $accept_checking_result = $this->Chat->get_all_by($accept_checking_data)->result();
        //print_r($accept_checking_result);die;


        $accept_result_flag = 0;

        foreach ($accept_checking_result as $rst) {
	    		
    		if ($rst->is_accept == 1) {
    			$accept_result_flag = 1;
    			break;
    		}
    		

    	}
    	//print_r($accept_result_flag);die;

    	if( $accept_result_flag == 1 ) {
    		$this->error_response( get_msg( 'err_accept_offer' ));
    	} else {


	        if($chat_history_data->id == "") {

	        	if ( $type == "to_buyer" ) {

	        		//prepare data for noti
			    	$user_ids[] = $this->post('buyer_user_id');


		        	$devices = $this->Noti->get_all_device_in($user_ids)->result();
		        	//print_r($devices);die;	

					$device_ids = array();
					if ( count( $devices ) > 0 ) {
						foreach ( $devices as $device ) {
							$device_ids[] = $device->device_token;
						}
					}


					$user_id = $this->post('buyer_user_id');
		       		$user_name = $this->User->get_one($user_id)->user_name;
		       		$price = $this->post('nego_price');
		       		
			    	$data['message'] = "Offer Accepted!";
			    	$data['buyer_user_id'] = $this->post('buyer_user_id');
			    	$data['seller_user_id'] = $this->post('seller_user_id');
			    	$data['sender_name'] = $user_name;
			    	$data['item_id'] = $this->post('item_id');

			    	$buyer_unread_count = $chat_history_data->buyer_unread_count;
			    	
			    	$chat_data = array(

			        	"item_id" => $this->post('item_id'), 
			        	"buyer_user_id" => $this->post('buyer_user_id'), 
			        	"seller_user_id" => $this->post('seller_user_id'),
			        	"buyer_unread_count" => $buyer_unread_count + 1,
			        	"added_date" => date("Y-m-d H:i:s"),
			        	"nego_price" => $this->post('nego_price'),
			        	"is_accept" => 1,
			        	"offer_status" => 3

			        );

			    	} elseif ( $type == "to_seller" ) {

			    	//prepare data for noti
			    	$user_ids[] = $this->post('seller_user_id');


		        	$devices = $this->Noti->get_all_device_in($user_ids)->result();
		        	//print_r($devices);die;	

					$device_ids = array();
					if ( count( $devices ) > 0 ) {
						foreach ( $devices as $device ) {
							$device_ids[] = $device->device_token;
						}
					}


					$user_id = $this->post('seller_user_id');
		       		$user_name = $this->User->get_one($user_id)->user_name;

			    	$data['message'] = "Offer Accepted!";
			    	$data['buyer_user_id'] = $this->post('buyer_user_id');
			    	$data['seller_user_id'] = $this->post('seller_user_id');
			    	$data['sender_name'] = $user_name;
			    	$data['item_id'] = $this->post('item_id');	

			    	$seller_unread_count = $chat_history_data->seller_unread_count;
			    	
			    	$chat_data = array(

			        	"item_id" => $this->post('item_id'), 
			        	"buyer_user_id" => $this->post('buyer_user_id'), 
			        	"seller_user_id" => $this->post('seller_user_id'),
			        	"seller_unread_count" => $seller_unread_count + 1,
			        	"added_date" => date("Y-m-d H:i:s"),
			        	"nego_price" => $this->post('nego_price'),
			        	"is_accept" => 1,
			        	"offer_status" => 3


			        );

			    	}

			    //sending noti
		    	$status = send_android_fcm_chat( $device_ids, $data );	

		        $this->Chat->save($chat_data);	
		        $obj = $this->Chat->get_one_by($chat_data);
				$this->ps_adapter->convert_chathistory( $obj );
				$this->custom_response( $obj );


		    } else {


		    	//print_r($chat_history_data->is_accept);die;
		    	$conds_chat['buyer_user_id'] = $chat_history_data->buyer_user_id;
		    	$conds_chat['seller_user_id'] = $chat_history_data->seller_user_id;
		    	$conds_chat['item_id'] = $chat_history_data->item_id;

		    	$chats = $this->Chat->get_all_by($conds_chat)->result();

		    	//print_r($chats);die;

		    	$accept_flag = 0;	

		    	foreach ($chats as $chat) {
		    		
		    		if ($chat->is_accept == 1) {
		    			$accept_flag = 1;
		    			break;
		    		}
		    		

		    	}
		    	
		    	if( $accept_flag == 1 ) {

		    		$this->error_response( get_msg( 'err_accept_offer' ));
		    	}

		    	else {

		    		if ( $type == "to_buyer" ) {

		    		//prepare data for noti
			    	$user_ids[] = $this->post('buyer_user_id');


		        	$devices = $this->Noti->get_all_device_in($user_ids)->result();
		        	//print_r($devices);die;	

					$device_ids = array();
					if ( count( $devices ) > 0 ) {
						foreach ( $devices as $device ) {
							$device_ids[] = $device->device_token;
						}
					}


					$user_id = $this->post('buyer_user_id');
		       		$user_name = $this->User->get_one($user_id)->user_name;
		       		
		       		$data['message'] = "Offer Accepted!";
			    	$data['buyer_user_id'] = $this->post('buyer_user_id');
			    	$data['seller_user_id'] = $this->post('seller_user_id');
			    	$data['sender_name'] = $user_name;
			    	$data['item_id'] = $this->post('item_id');

			    	$buyer_unread_count = $chat_history_data->buyer_unread_count;


			    	$chat_data = array(

			        	"item_id" => $this->post('item_id'), 
			        	"buyer_user_id" => $this->post('buyer_user_id'), 
			        	"seller_user_id" => $this->post('seller_user_id'),
			        	"buyer_unread_count" => $buyer_unread_count + 1,
			        	"added_date" => date("Y-m-d H:i:s"),
			        	"nego_price" => $this->post('nego_price'),
			        	"is_accept"	 => 1,
			        	"offer_status" => 3	

			        );


			    	} elseif ( $type == "to_seller" ) {

			    	//prepare data for noti
			    	$user_ids[] = $this->post('seller_user_id');


		        	$devices = $this->Noti->get_all_device_in($user_ids)->result();
		        	//print_r($devices);die;	

					$device_ids = array();
					if ( count( $devices ) > 0 ) {
						foreach ( $devices as $device ) {
							$device_ids[] = $device->device_token;
						}
					}


					$user_id = $this->post('seller_user_id');
		       		$user_name = $this->User->get_one($user_id)->user_name;

			    	$data['message'] = "Offer Accepted!";
			    	$data['buyer_user_id'] = $this->post('buyer_user_id');
			    	$data['seller_user_id'] = $this->post('seller_user_id');
			    	$data['sender_name'] = $user_name;
			    	$data['item_id'] = $this->post('item_id');	

			    	$seller_unread_count = $chat_history_data->seller_unread_count;
			    	
			    	$chat_data = array(

			        	"item_id" => $this->post('item_id'), 
			        	"buyer_user_id" => $this->post('buyer_user_id'), 
			        	"seller_user_id" => $this->post('seller_user_id'),
			        	"seller_unread_count" => $seller_unread_count + 1,
			        	"added_date" => date("Y-m-d H:i:s"),
			        	"nego_price" => $this->post('nego_price'),
			        	"is_accept"	 => 1,
			        	"offer_status" => 3

			        );

			    	}
		    	}

		    	if( !$this->Chat->Save( $chat_data,$chat_history_data->id )) {

		    		$this->error_response( get_msg( 'err_accept_update' ));

		    	
		    	} else {

		    		//sending noti
		    		$status = send_android_fcm_chat( $device_ids, $data );
		    		$obj = $this->Chat->get_one_by($chat_data);
					$this->ps_adapter->convert_chathistory( $obj );
					$this->custom_response( $obj );

		    	}
		    }

	    }

	}

    /**
	 * Update Price 
	 */
	function item_sold_out_post()
	{
		// validation rules for chat history
		$rules = array(
			array(
	        	'field' => 'item_id',
	        	'rules' => 'required'
	        ),
	        array(
	        	'field' => 'buyer_user_id',
	        	'rules' => 'required'
	        ),
	        array(
	        	'field' => 'seller_user_id',
	        	'rules' => 'required'
	        )
        );


		// exit if there is an error in validation,
        if ( !$this->is_valid( $rules )) exit;
        $item_id = $this->post('item_id');
        $buyer_user_id = $this->post('buyer_user_id');
        $seller_user_id = $this->post('seller_user_id');
        $item_sold_out = array(

        	"is_sold_out" => 1, 

        );

        $this->Item->save($item_sold_out,$item_id);
        $conds['item_id'] = $item_id;
        $conds['buyer_user_id'] = $buyer_user_id;
        $conds['seller_user_id'] = $seller_user_id;
        
        $obj = $this->Chat->get_one_by($conds);

        $this->ps_adapter->convert_chathistory( $obj );
        $this->custom_response($obj);
    }


    /**
	 * Reset is_accept 
	 */
	function reset_accept_post()
	{
		// validation rules for chat history
		$rules = array(
			array(
	        	'field' => 'item_id',
	        	'rules' => 'required'
	        ),
	        array(
	        	'field' => 'buyer_user_id',
	        	'rules' => 'required'
	        ),
	        array(
	        	'field' => 'seller_user_id',
	        	'rules' => 'required'
	        )
        );


		// exit if there is an error in validation,
        if ( !$this->is_valid( $rules )) exit;

        $chat_data = array(

        	"item_id" => $this->post('item_id'), 
        	"buyer_user_id" => $this->post('buyer_user_id'), 
        	"seller_user_id" => $this->post('seller_user_id')

        );

        $chat_history_data = $this->Chat->get_one_by($chat_data);


        if($chat_history_data->id == "") {
	        	
	        $this->error_response( get_msg( 'err_chat_history_not_exist' ));


	    } else {
	    	
	    	$chat_data = array(

	        	"item_id" => $this->post('item_id'), 
	        	"buyer_user_id" => $this->post('buyer_user_id'), 
	        	"seller_user_id" => $this->post('seller_user_id'),
	        	"is_accept" => 0

	        );

	    	if( !$this->Chat->Save( $chat_data,$chat_history_data->id )) {

	    		$this->error_response( get_msg( 'err_accept_update' ));

	    	
	    	} else {

	    		$this->success_response( get_msg( 'accept_reset_success' ));


	    	}


	    }


	}

	/**
	 * Delete All Chat History
	 */
	function delete_chat_history_post()
	{
		
		// delete categories and images
		if ( !$this->Chat->delete_all()) {

			// set error message
			$this->error_response( get_msg( 'error_delete_chat_history' ));
			// rollback

			
		}
		
		$this->success_response( get_msg( 'success_delete_chat_history' ));
		
	}

	/**
	 * Reset Soldout
	 */

	function reset_sold_out_post()
	{
		// validation rules for chat history
		$rules = array(
			array(
	        	'field' => 'item_id',
	        	'rules' => 'required'
	        ),
	        array(
	        	'field' => 'buyer_user_id',
	        	'rules' => 'required'
	        ),
	        array(
	        	'field' => 'seller_user_id',
	        	'rules' => 'required'
	        )
        );


		// exit if there is an error in validation,
        if ( !$this->is_valid( $rules )) exit;

        $chat_data = array(

        	"item_id" => $this->post('item_id'), 
        	"buyer_user_id" => $this->post('buyer_user_id'), 
        	"seller_user_id" => $this->post('seller_user_id')

        );

        $chat_history_data = $this->Chat->get_one_by($chat_data);


        if($chat_history_data->id == "") {
	        	
	        $this->error_response( get_msg( 'err_chat_history_not_exist' ));


	    } else {
	    	
	    	$chat_data = array(

	        	"item_id" => $this->post('item_id'), 
	        	"buyer_user_id" => $this->post('buyer_user_id'), 
	        	"seller_user_id" => $this->post('seller_user_id'),
	        	"is_accept" => 0

	        );

	    	if( !$this->Chat->Save( $chat_data,$chat_history_data->id )) {

	    		$this->error_response( get_msg( 'err_accept_update' ));

	    	
	    	} else {

	    		$item_data = array(
	    			"is_sold_out" => 0
	    		);

	    		if( !$this->Item->Save( $item_data, $this->post('item_id') )) {

		    		$this->error_response( get_msg( 'err_soldout_reset' ));

		    	} else {

		    		$this->success_response( get_msg( 'soldout_reset_success' ));

		    	}


	    	}


	    }

	}


	/**
	 * get chat history
	 */

	function get_chat_history_post()
	{
		// validation rules for chat history
		$rules = array(
			array(
	        	'field' => 'item_id',
	        	'rules' => 'required'
	        ),
	        array(
	        	'field' => 'buyer_user_id',
	        	'rules' => 'required'
	        ),
	        array(
	        	'field' => 'seller_user_id',
	        	'rules' => 'required'
	        )
        );


		// exit if there is an error in validation,
        if ( !$this->is_valid( $rules )) exit;

        $chat_data = array(

        	"item_id" => $this->post('item_id'), 
        	"buyer_user_id" => $this->post('buyer_user_id'), 
        	"seller_user_id" => $this->post('seller_user_id')

        );

        $obj = $this->Chat->get_one_by($chat_data);

        $this->ps_adapter->convert_chathistory( $obj );
		$this->custom_response( $obj );

    }

    /**
	 * Offer list Api
	 */

	function offer_list_post()
	{
		// validation rules for chat history
		// $rules = array(
	 //        array(
	 //        	'field' => 'seller_user_id',
	 //        	'rules' => 'required'
	 //        )
  //       );


		// // exit if there is an error in validation,
  //       if ( !$this->is_valid( $rules )) exit;
  //       $chat_data = array(

  //       	"seller_user_id" => $this->post('seller_user_id')

  //       );
  //       $chats = $this->Chat->get_all_by($chat_data);
  //       foreach ($chats->result() as $ch) {
  //       	$nego_price = $ch->nego_price;
  //       	$is_accept = $ch->is_accept;
  //       	if ($nego_price != 0 && $is_accept != 0) {
  //       		$result .= $ch->id .",";
	 //        }
  //       }
  //       $id_from_his = rtrim($result,",");
		// $result_id = explode(",", $id_from_his);
		// $obj = $this->Chat->get_multi_info($result_id)->result();

		// $this->ps_adapter->convert_chathistory( $obj );
		// $this->custom_response( $obj );

		// add flag for default query
		$this->is_get = true;

		// get the post data
		$user_id = $this->post('user_id');
		$return_type 	 = $this->post('return_type');

		$users = global_user_check($user_id);

		$limit = $this->get( 'limit' );
		$offset = $this->get( 'offset' );

		

			// get limit & offset

			if ( $return_type == "buyer") {
				
			//pph modified @ 22 June 2019

			/* For User Block */

			//user block check with user_id
			$conds_login_block['from_block_user_id'] = $user_id;
			$login_block_count = $this->Block->count_all_by($conds_login_block);

			// user blocked existed by user id
			if ($login_block_count > 0) {
				// get the blocked user by user id
				$to_block_user_datas = $this->Block->get_all_by($conds_login_block)->result();

				foreach ( $to_block_user_datas as $to_block_user_data ) {

					$to_block_user_id .= "'" .$to_block_user_data->to_block_user_id . "',";
			
				}

				// get block user's chat list

				$result_users = rtrim($to_block_user_id,',');
				$conds_user['buyer_user_id'] = $result_users;

				$chat_users = $this->Chat->get_all_in_chat_buyer( $conds_user )->result();


				foreach ( $chat_users as $chat_user ) {

					$chat_ids .= $chat_user->id .",";
				
				}

				// get all chat id without block user's list

				$results = rtrim($chat_ids,',');
				$chat_id = explode(",", $results);
				$conds['chat_id'] = $chat_id;


			}	

				$conds['seller_user_id'] = $user_id;
				$conds['nego_price'] = '0' ;



			if ( !empty( $limit ) && !empty( $offset )) {
			// if limit & offset is not empty
				
				$chats = $this->Chat->get_all_chat($conds,$limit, $offset)->result();
			} else if ( !empty( $limit )) {
			// if limit is not empty

				
				$chats = $this->Chat->get_all_chat($conds, $limit )->result();
			} else {
			// if both are empty
				
				$chats = $this->Chat->get_all_chat($conds)->result();
			}
			//print_r($chats);die;
			if (!empty($chats)) {
				foreach ( $chats as $chat ) {

				$id .= "'" .$chat->id . "',";
			
				}
			}	
			
			if ($id == "") {
				$this->error_response($this->config->item( 'record_not_found'));
			} else {

				$result = rtrim($id,',');
				$conds['$id'] = $result;

				$obj = $this->Chat->get_all_in_chat($conds)->result();
				$this->ps_adapter->convert_chathistory( $obj );
				$this->custom_response( $obj );

			}

			

		} else if ( $return_type == "seller") {

			//$conds['seller_user_id'] = $user_id;
			//pph modified @ 22 June 2019

			/* For User Block */

			//user block check with user_id
			$conds_login_block['from_block_user_id'] = $user_id;
			$login_block_count = $this->Block->count_all_by($conds_login_block);

			// user blocked existed by user id
			if ($login_block_count > 0) {
				// get the blocked user by user id
				$to_block_user_datas = $this->Block->get_all_by($conds_login_block)->result();

				foreach ( $to_block_user_datas as $to_block_user_data ) {

					$to_block_user_id .= "'" .$to_block_user_data->to_block_user_id . "',";
			
				}

				// get block user's chat list

				$result_users = rtrim($to_block_user_id,',');
				$conds_user['seller_user_id'] = $result_users;

				$chat_users = $this->Chat->get_all_in_chat_seller( $conds_user )->result();


				foreach ( $chat_users as $chat_user ) {

					$chat_ids .= $chat_user->id .",";
				
				}

				// get all chat id without block user's list

				$results = rtrim($chat_ids,',');
				$chat_id = explode(",", $results);
				$conds['chat_id'] = $chat_id;

			}

			/* For Item Report */

				//item report check with login_user_id
				$conds_report['reported_user_id'] = $user_id;
				$reported_data_count = $this->Itemreport->count_all_by($conds_report);

				// item reported existed by login user
				if ($reported_data_count > 0) {
					// get the reported item data
					$item_reported_datas = $this->Itemreport->get_all_by($conds_report)->result();

					foreach ( $item_reported_datas as $item_reported_data ) {

						$item_ids .= "'" .$item_reported_data->item_id . "',";
				
					}

					// get block user's item

					$result_reports = rtrim($item_ids,',');
					$conds_item['item_id'] = $result_reports;

					$item_reports = $this->Chat->get_all_in_chat_item( $conds_item )->result();

					foreach ( $item_reports as $item_report ) {

						$ids .= $item_report->id .",";
					
					}

					// get all item without block user's item

					$result_items = rtrim($ids,',');
					$reported_item_id = explode(",", $result_items);
					$conds['item_id'] = $reported_item_id;
				}

				$conds['buyer_user_id'] = $user_id;
				$conds['nego_price'] = '0' ;

			//print_r($conds);die;
				
			if ( !empty( $limit ) && !empty( $offset )) {
			// if limit & offset is not empty
				
				$chats = $this->Chat->get_all_chat($conds,$limit, $offset)->result();
			} else if ( !empty( $limit )) {
			// if limit is not empty

				
				$chats = $this->Chat->get_all_chat($conds, $limit )->result();
			} else {
			// if both are empty
				
				$chats = $this->Chat->get_all_chat($conds)->result();
			}
			
			if (!empty($chats)) {
				foreach ( $chats as $chat ) {

				$id .= "'" .$chat->id . "',";
			
				}
			}

			if ($id == "") {
				$this->error_response($this->config->item( 'record_not_found'));
			} else {

				$result = rtrim($id,',');
				$conds['$id'] = $result;

				$obj = $this->Chat->get_all_in_chat($conds)->result();
				$this->ps_adapter->convert_chathistory( $obj );
				$this->custom_response( $obj );

			}

		}


    }

    /**
    is user bought
    */
    function is_user_bought_post()
    {
    			// validation rules for chat history
		$rules = array(
			array(
	        	'field' => 'item_id',
	        	'rules' => 'required'
	        ),
	        array(
	        	'field' => 'buyer_user_id',
	        	'rules' => 'required'
	        ),
	        array(
	        	'field' => 'seller_user_id',
	        	'rules' => 'required'
	        )

        );

        $item_id = $this->post('item_id');
        $seller_user_id = $this->post('seller_user_id');
        $buyer_user_id = $this->post('buyer_user_id');

		// exit if there is an error in validation,
        if ( !$this->is_valid( $rules )) exit;




        /** send noti to seller */

        $item_name = $this->Item->get_one($item_id)->title;
        $buyer_name = $this->User->get_one($buyer_user_id)->user_name;
        $message = $buyer_name . ' ' . get_msg('bought_your_item') . ' ' . $item_name ;

        $devices = $this->Noti->get_all_device_in($seller_user_id)->result();

		$device_ids = array();
			if ( count( $devices ) > 0 ) {
				foreach ( $devices as $device ) {
					$device_ids[] = $device->device_token;
				}
			}

		$status = $this->send_android_fcm( $device_ids, $message );

		/** send noti to buyer */

        $message = get_msg('you_bought') . ' ' . $item_name ;

        $devices = $this->Noti->get_all_device_in($buyer_user_id)->result();

		$device_ids = array();
			if ( count( $devices ) > 0 ) {
				foreach ( $devices as $device ) {
					$device_ids[] = $device->device_token;
				}
			}

		$status = $this->send_android_fcm( $device_ids, $message );

		/** save bought data */

        $bought_data = array(

        	"item_id" => $this->post('item_id'), 
        	"buyer_user_id" => $this->post('buyer_user_id'), 
        	"seller_user_id" => $this->post('seller_user_id'),
        	"added_date" => date("Y-m-d H:i:s"),

        );

        if (!$this->User_bought->save($bought_data)) {
        	$this->success_response( get_msg( 'err_user_bought' ));
        } else {
        	$this->success_response( get_msg( 'success_user_bought' ));

        }


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
    		'flag' => 'bought',
	    	'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
    	);

    	$fields = array(
    		'sound' => 'default',
    		'notification' => $noti_arr,
    	    'registration_ids' => $registatoin_ids,
    	    'data' => array(
    	    	'message' => $message,
    	    	'flag' => 'bought',
    	    	'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
    	    )

    	);
    	
    	// $fields = array(
    	//     'registration_ids' => $registatoin_ids,
    	//     'data' => array(
    	//     'message' => $message
    	//     )
    	// );

    	// Update your Google Cloud Messaging API Key
    	//define("GOOGLE_API_KEY", "AIzaSyCCwa8O4IeMG-r_M9EJI_ZqyybIawbufgg");
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