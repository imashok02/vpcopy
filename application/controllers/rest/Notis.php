<?php
require_once( APPPATH .'libraries/REST_Controller.php' );

/**
 * REST API for Notification
 */
class Notis extends API_Controller
{
	/**
	 * Constructs Parent Constructor
	 */
	function __construct()
	{
		// call the parent
		parent::__construct( 'Noti' );

	}

	/**
	* Register Device
	*/
	function register_post()
	{
		// validation rules for user register
		$rules = array(
			array(
	        	'field' => 'platform_name',
	        	'rules' => 'required'
	        ),
	        array(
	        	'field' => 'device_token',
	        	'rules' => 'required'
	        ),
	        array(
	        	'field' => 'user_id',
	        	'rules' => 'required'
	        )
        );

		// exit if there is an error in validation,
        if ( !$this->is_valid( $rules )) exit;
        $user_id = $this->post('user_id');
        if($this->post('platform_name') == "android") {

        	$noti_data = array(
	        	"device_token" => $this->post('device_token'), 
	        	"platform_name" => "android",
	        	"user_id" => $user_id
        	);

        } else {

        	$noti_data = array(
	        	"device_token" => $this->post('device_token'),
	        	"platform_name" => "IOS",
	        	"user_id" => $user_id
        	);
        }
        	
	        $noti = array(
	        	"device_token" => $noti_data['device_token']
	        );
        //print_r($noti);die;

        if ( $this->Noti->exists( $noti )) {
        // if the noti data is already existed, return success
        	$this->success_response( get_msg( 'token_already_exist '));
        }

        if ( !$this->Noti->save( $noti_data )) {
        // if there is error in inserting noti data, return error
        	// echo "asdfa";die;
        	$this->error_response( get_msg( 'err_noti_register' ));
        }

        // else, return success
        $this->success_response( get_msg( 'success_noti_register '));
	}

	/**
	* Register Device
	*/
	function unregister_post()
	{
		// validation rules for user register
		$rules = array(
			array(
	        	'field' => 'device_token',
	        	'rules' => 'required'
	        ),
	        array(
	        	'field' => 'user_id',
	        	'rules' => 'required'
	        )
        );

		// exit if there is an error in validation,
        if ( !$this->is_valid( $rules )) exit;
        $user_id = $this->post('user_id');
    	$noti_data = array(
        	"device_token" => $this->post('device_token'),
        	"user_id" => $user_id
    	);

    	if ( !$this->Noti->exists( $noti_data )) {
    	// if device id is not existed, return success

    		$this->success_response( get_msg( 'success_noti_unregister '));
    	}
    		
    	if ( !$this->Noti->delete_by( $noti_data )) {
    	// if there is an error in deleteing noti data, return error

    		$this->error_response( get_msg( 'err_noti_unregister' ));
    	}

    	// if no error, return success
    	$this->success_response( get_msg( 'success_noti_unregister '));
	}

	/**
	* To Update Read Status 
	*/
	function is_read_post()
	{
		// validation rules for user register
		$rules = array(
			array(
	        	'field' => 'noti_id',
	        	'rules' => 'required'
	        )

        );

		// exit if there is an error in validation,
        if ( !$this->is_valid( $rules )) exit;

        if( $this->post('user_id') == "" && $this->post('device_token') == "") {
        	$this->error_response( get_msg( 'err_in_noti_read' ));
        	exit;
        } 


    	$noti_user_data = array(
        	"noti_id" => $this->post('noti_id'),
        	"user_id" => $this->post('user_id'),
        	"device_token" => $this->post('device_token')
    	);

    	//print_r($noti_user_data); die;

    	if ( !$this->Notireaduser->exists( $noti_user_data )) {
    	// if device id is not existed, return success

    		//$this->error_response( get_msg( 'err_in_noti_read' ));
    		$this->Notireaduser->save( $noti_user_data );
    		
    	} 

    	$obj = new stdClass;
		$obj->id = $this->post('noti_id');
		
		$noti = $this->Noti_message->get_one( $obj->id );
		
		$this->ps_adapter->convert_noti( $noti );
		$this->custom_response_noti( $noti );
    		
    	
	}


	/**
	* Sending Message From FCM For Android
	*/
	// function send_android_fcm_chat( $registatoin_ids, $data) 
 //    {
 //    	//print_r($registatoin_ids); die;
 //    	// print_r($data); die;
 //    	$message = $data['message'];
	// 	$buyer_id = $data['buyer_user_id'];
	// 	$seller_id = $data['seller_user_id'];
	// 	$sender_name = $data['sender_name'];
	// 	$item_id = $data['item_id'];
 //    	//Google cloud messaging GCM-API url
 //    	$url = 'https://fcm.googleapis.com/fcm/send';
    	
  

 //    	$fields = array(
 //    	    'registration_ids' => $registatoin_ids,
 //    	    'data' => array(
 //    	    	'message' => $message,
 //    	    	'buyer_id' => $buyer_id,
 //    	    	'seller_id' => $seller_id,
 //    	    	'item_id' => $item_id,
 //    	    	'sender_name' => $sender_name,
 //    	    	'action' => "abc"
 //    	    )

 //    	);

    	

 //    	// Update your Google Cloud Messaging API Key
 //    	//define("GOOGLE_API_KEY", "AIzaSyAzKBPuzGuR0nlvY0AxPrXsEMBuRUxO4WE");
 //    	define("GOOGLE_API_KEY", $this->config->item( 'fcm_api_key' ));  	
    	
 //    	//print_r(GOOGLE_API_KEY); die;
 //    	//print_r($fields); die;
 //    	$headers = array(
 //    	    'Authorization: key=' . GOOGLE_API_KEY,
 //    	    'Content-Type: application/json'
 //    	);
 //    	$ch = curl_init();
 //    	curl_setopt($ch, CURLOPT_URL, $url);
 //    	curl_setopt($ch, CURLOPT_POST, true);
 //    	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
 //    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 //    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);	
 //    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 //    	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 //    	$result = curl_exec($ch);				
 //    	if ($result === FALSE) {
 //    	    die('Curl failed: ' . curl_error($ch));
 //    	}
 //    	curl_close($ch);

 //    	return $result;
 //    }

	/**
	 * Convert Object
	 */
	function convert_object( &$obj )
	{
		// call parent convert object
		parent::convert_object( $obj );
		// convert customize category object
		$noti_user_data = array(
        	"noti_id" => $obj->id,
        	"user_id" => $this->post('user_id'),
        	"device_token"  => $this->post('device_token')
    	);


    	if ( !$this->Notireaduser->exists( $noti_user_data )) {
    		
    		$obj->is_read = 0;
    	} else {
    		
    		$obj->is_read = 1;
    	}

		$this->ps_adapter->convert_noti( $obj );
	}

}