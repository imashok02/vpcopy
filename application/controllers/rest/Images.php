<?php
require_once( APPPATH .'libraries/REST_Controller.php' );

/**
 * REST API for News
 */
class Images extends API_Controller
{


	/**
	 * Constructs Parent Constructor
	 */
	function __construct()
	{
		parent::__construct( 'Image' );
		$this->load->library( 'PS_Image' );
		$this->load->library( 'PS_Delete' );
	}

	function upload_post()
	{
		
		$platform_name = $this->post('platform_name');
		if ( !$platform_name ) {
			$this->custom_response( get_msg('required_platform') ) ;
		}
		
		$user_id = $this->post('user_id');

		if($platform_name == "ios") {
			
			
			if ( !$user_id ) {
				$this->custom_response( get_msg('user_id_required') );
			}
			
			$uploaddir = 'uploads/';
			
			$path_parts = pathinfo( $_FILES['pic']['name'] );
			//$filename = $path_parts['filename'] . date( 'YmdHis' ) .'.'. $path_parts['extension'];
			$filename = $path_parts['filename'] .'.'. $path_parts['extension'];

			//if (move_uploaded_file($_FILES['pic']['tmp_name'], $uploaddir . $filename)) {
			if ($this->ps_image->upload( $_FILES )) {
				//call to image reseize

			  // $this->image_resize_calculation( FCPATH. $uploaddir . $filename );

			   $user_data = array( 'user_profile_photo' => $filename );
				   if ( $this->User->save( $user_data, $user_id ) ) {
					   	
					   	$user = $this->User->get_one( $user_id );

					   	$this->ps_adapter->convert_user( $user );
					   	
					   	$this->custom_response( $user );
				   } else {
					   	$this->error_response( get_msg('file_na') );
				   }
			   
			} else {
			   $this->error_response( get_msg('file_na') );
				
			}
			
		} else {

			$uploaddir = 'uploads/';
			
			$path_parts = pathinfo( $_FILES['file']['name'] );
			//$filename = $path_parts['filename'] . date( 'YmdHis' ) .'.'. $path_parts['extension'];
			$filename = $path_parts['filename'] .'.'. $path_parts['extension'];


			//if (move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir . $filename)) {
			$upload_data = $this->ps_image->upload( $_FILES );
			
			$filename = $upload_data[0]["file_name"];


			if ( count($upload_data) > 0 ) {

				//call to image reseize

			   //$this->image_resize_calculation( FCPATH. $uploaddir . $filename );
			   $user_data = array( 'user_profile_photo' => $filename );
				   if ( $this->User->save( $user_data, $user_id ) ) {

					   	$user = $this->User->get_one( $user_id );

					   	$this->ps_adapter->convert_user( $user );
					   	
					   	$this->custom_response( $user );

				   } else {
					   	$this->error_response( get_msg('file_na') );
				   }
			   
			} else {
			   $this->error_response( get_msg('file_na') );
				
			}
		}
		
	}

	function upload_item_post()
	{


		$item_id = $this->post('item_id');
		$files = $this->post('file');
		$img_id = $this->post('img_id');

			if (trim($img_id) == "") {

				$path_parts = pathinfo( $_FILES['file']['name'] );

				if(strtolower($path_parts['extension']) != "jpeg" && strtolower($path_parts['extension']) != "png" && strtolower($path_parts['extension']) != "jpg") {


					$uploaddir = 'uploads/';
					$uploaddir_thumb = 'uploads/thumbnail/';

					$path_parts = pathinfo( $_FILES['file']['name'] );
					
					$filename = $path_parts['filename'] . date( 'YmdHis' ) .'.'. $path_parts['extension'];



					// upload image to "uploads" folder
					if (move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir . $filename)) {

						//move uploaded image to thumbnail folder
						if(copy($uploaddir . $filename,$uploaddir_thumb . $filename)){
						    //copy success file
						    $item_img_data = array( 
							   	'img_parent_id'=> $item_id,
								'img_path' => $filename,
								'img_type' => "item",
								'img_width'=> 0,
								'img_height'=> 0,
								'ordering' => $this->post('ordering')
						   	);
						}

					}

				} else {
					//if image is JPG or PNG (Not heic format)	
					$upload_data = $this->ps_image->upload( $_FILES );


					foreach ( $upload_data as $upload ) {
					   	$item_img_data = array( 
						   	'img_parent_id'=> $item_id,
							'img_path' => $upload['file_name'],
							'img_type' => "item",
							'img_width'=> $upload['image_width'],
							'img_height'=> $upload['image_height'],
							'ordering' => $this->post('ordering')
					   	);
					}


				}


				    if ( $this->Image->save( $item_img_data) ) {

					   	//for deeplinking image url update by PP
						$description = $this->Item->get_one($item_id)->description;
						$title = $this->Item->get_one($item_id)->title;
						$conds_img = array( 'img_type' => 'item', 'img_parent_id' => $item_id, 'ordering' => '1' );
				        $images = $this->Image->get_all_by( $conds_img )->result();
						$img = $this->ps_image->upload_url . $images[0]->img_path;
						$deep_link = deep_linking_shorten_url($description,$title,$img,$item_id);
						$itm_data = array(
							'dynamic_link' => $deep_link
						);
						$this->Item->save($itm_data,$item_id);


				   		$conds['img_path'] = $item_img_data['img_path'];
				   		$img_id = $this->Image->get_one_by($conds)->img_id;
					   	$image = $this->Image->get_one( $img_id );

					   	$this->ps_adapter->convert_image( $image );
					   	
					   	$this->custom_response( $image );
				    } else {
					   	$this->error_response( get_msg('file_na') );
				    }
				
				   
			} else {
				
				$path_parts = pathinfo( $_FILES['file']['name'] );
				
				if($path_parts['extension'] == "heic" or $path_parts['extension'] == "HEIC") {
					
					$uploaddir = 'uploads/';
					$uploaddir_thumb = 'uploads/thumbnail/';

					$path_parts = pathinfo( $_FILES['file']['name'] );
					
					$filename = $path_parts['filename'] . date( 'YmdHis' ) .'.'. $path_parts['extension'];



					// upload image to "uploads" folder
					if (move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir . $filename)) {

						//move uploaded image to thumbnail folder
						if(copy($uploaddir . $filename,$uploaddir_thumb . $filename)){
						    //copy success file
						    $item_img_data = array( 
							   	'img_parent_id'=> $item_id,
								'img_path' => $filename,
								'img_type' => "item",
								'img_width'=> 0,
								'img_height'=> 0,
								'ordering' => $this->post('ordering')
						   	);
						}

					}

				} else {

					// upload images
					$upload_data = $this->ps_image->upload( $_FILES );

					foreach ( $upload_data as $upload ) {
					   	$item_img_data = array( 
					   		'img_id' => $img_id,
						   	'img_parent_id'=> $item_id,
							'img_path' => $upload['file_name'],
							'img_width'=> $upload['image_width'],
							'img_height'=> $upload['image_height'],
							'ordering' => $this->post('ordering')
					   	);
					}

				}



			   	if ( $this->Image->save( $item_img_data, $img_id ) ) {
			   		
			   		//for deeplinking image url update by PP
					$description = $this->Item->get_one($item_id)->description;
					$title = $this->Item->get_one($item_id)->title;
					$conds_img = array( 'img_type' => 'item', 'img_parent_id' => $item_id, 'ordering' => '1' );
			        $images = $this->Image->get_all_by( $conds_img )->result();
					$img = $this->ps_image->upload_url . $images[0]->img_path;
					$deep_link = deep_linking_shorten_url($description,$title,$img,$item_id);
					$itm_data = array(
						'dynamic_link' => $deep_link
					);
					$this->Item->save($itm_data,$item_id);
				   	
				   	$image = $this->Image->get_one( $img_id );

				   	$this->ps_adapter->convert_image( $image );
				   	
				   	$this->custom_response( $image );
			   	} else {
				   	$this->error_response( get_msg('file_na') );
			   	}
			
			}

	}

	/** Chat image upload api */

	function chat_image_upload_post()
	{
		
		$sender_id = $this->post('sender_id');
		$type = $this->post('type');
		 $chat_data = array(

        	"item_id" => $this->post('item_id'), 
        	"buyer_user_id" => $this->post('buyer_user_id'), 
        	"seller_user_id" => $this->post('seller_user_id')

        );

		$chat_history_data = $this->Chat->get_one_by($chat_data);
		$is_user_online = $this->post('is_user_online');
		//////
		if($chat_history_data->id == "") {


			if ( $type == "to_buyer" ) {

				

		    	$buyer_unread_count = $chat_history_data->buyer_unread_count;

		    	if ($is_user_online == '1') {
		    		//if user is online, no need to send noti and no need to add unread count

		    		$chat_data = array(

			        	"item_id" => $this->post('item_id'), 
			        	"buyer_user_id" => $this->post('buyer_user_id'), 
			        	"seller_user_id" => $this->post('seller_user_id'),
			        	"buyer_unread_count" => $buyer_unread_count,
			        	"added_date" => date("Y-m-d H:i:s"),

			        );
		    	} else {
		    		//if user is offline, send noti and add unread count

		    		$chat_data = array(

			        	"item_id" => $this->post('item_id'), 
			        	"buyer_user_id" => $this->post('buyer_user_id'), 
			        	"seller_user_id" => $this->post('seller_user_id'),
			        	"buyer_unread_count" => $buyer_unread_count + 1,
			        	"added_date" => date("Y-m-d H:i:s"),

			        );

			        //prepare data for noti
			    	$user_ids[] = $this->post('buyer_user_id');
			    	$devices = $this->Noti->get_all_device_in($user_ids)->result();

			    	$device_ids = array();
			    	if ( count( $devices ) > 0 ) {
						foreach ( $devices as $device ) {
							$device_ids[] = $device->device_token;
						}
					}

					$user_id = $this->post('seller_user_id');
		       		$user_name = $this->User->get_one($user_id)->user_name;

		       		$data['message'] = "Image!";
			    	$data['buyer_user_id'] = $this->post('buyer_user_id');
			    	$data['seller_user_id'] = $this->post('seller_user_id');
			    	$data['sender_name'] = $user_name;
			    	$data['item_id'] = $this->post('item_id');

	    			$status = send_android_fcm_chat( $device_ids, $data );

		    	}

		    	

			} elseif ( $type == "to_seller" ) {
				

		    	$seller_unread_count = $chat_history_data->seller_unread_count;

		    	if ($is_user_online == '1') {
		    		//if user is online, no need to send noti and no need to add unread count

		    		$chat_data = array(

			        	"item_id" => $this->post('item_id'), 
			        	"buyer_user_id" => $this->post('buyer_user_id'), 
			        	"seller_user_id" => $this->post('seller_user_id'),
			        	"seller_unread_count" => $seller_unread_count,
			        	"added_date" => date("Y-m-d H:i:s"),

			        );

		    	} else {
		    		//if user is offline, send noti and add unread count

		    		$chat_data = array(

			        	"item_id" => $this->post('item_id'), 
			        	"buyer_user_id" => $this->post('buyer_user_id'), 
			        	"seller_user_id" => $this->post('seller_user_id'),
			        	"seller_unread_count" => $seller_unread_count + 1,
			        	"added_date" => date("Y-m-d H:i:s"),

		        	);

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


					$user_id = $this->post('buyer_user_id');
		       		$user_name = $this->User->get_one($user_id)->user_name;

			    	$data['message'] = "Image!";
			    	$data['buyer_user_id'] = $this->post('buyer_user_id');
			    	$data['seller_user_id'] = $this->post('seller_user_id');
			    	$data['sender_name'] = $user_name;
			    	$data['item_id'] = $this->post('item_id');	

	    			$status = send_android_fcm_chat( $device_ids, $data );


		    	}

		    	

			}

			$this->Chat->Save( $chat_data );
	    	if ( !$sender_id ) {
				$this->custom_response( get_msg('sender_id_required') ) ;
			}
			
			//$sender_id = $this->post('sender_id');

			
				
			$uploaddir = 'uploads/';
			
			$path_parts = pathinfo( $_FILES['file']['name'] );
			$filename = $path_parts['filename'] . date( 'YmdHis' ) .'.'. $path_parts['extension'];
			
			//print_r($filename); die;

			

			if (move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir . $filename)) {
			   	
				$data = getimagesize($uploaddir . $filename);
				$width = $data[0];
				$height = $data[1];
				//call to image reseize
			
			   	//$this->image_resize_calculation( FCPATH. $uploaddir . $filename );

			   	$img_data = array( 
			   		
			   		'img_parent_id' => $sender_id, 
			   		'img_type'      => "chat",
			   		'img_path'      => $filename,
			   		'img_width'     => $width,
			   		'img_height'    => $height 

			   	);

			   //	print_r($img_data); die;

			   if ( $this->Image->save( $img_data ) ) {

			   		//print_r($img_data['img_id']);

				   	$image = $this->Image->get_one( $img_data['img_id'] );

				   	//$this->ps_adapter->convert_image( $image );
				   	
				   	$this->custom_response( $image );

			   } else {
				   	$this->error_response( get_msg('file_na') );
			   }
			   
			} else {
			   $this->error_response( get_msg('file_na') );
				
			}
			


		} else {

			if ( $type == "to_buyer" ) {
				

		    	$buyer_unread_count = $chat_history_data->buyer_unread_count;

		    	if ($is_user_online == '1') {

		    		//if user is online, no need to send noti and no need to add unread count

		    		$chat_data = array(

			        	"item_id" => $this->post('item_id'), 
			        	"buyer_user_id" => $this->post('buyer_user_id'), 
			        	"seller_user_id" => $this->post('seller_user_id'),
			        	"buyer_unread_count" => $buyer_unread_count,
			        	"added_date" => date("Y-m-d H:i:s"),

			        );

		    	} else {
		    		//if user is offline, send noti and add unread count

			    	$chat_data = array(

			        	"item_id" => $this->post('item_id'), 
			        	"buyer_user_id" => $this->post('buyer_user_id'), 
			        	"seller_user_id" => $this->post('seller_user_id'),
			        	"buyer_unread_count" => $buyer_unread_count + 1,
			        	"added_date" => date("Y-m-d H:i:s"),

		        	);

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


					$user_id = $this->post('seller_user_id');
		       		$user_name = $this->User->get_one($user_id)->user_name;

			    	$data['message'] = "Image!";
			    	$data['buyer_user_id'] = $this->post('buyer_user_id');
			    	$data['seller_user_id'] = $this->post('seller_user_id');
			    	$data['sender_name'] = $user_name;
			    	$data['item_id'] = $this->post('item_id');

	    			$status = send_android_fcm_chat( $device_ids, $data );


		    	}

		    	

			} elseif ( $type == "to_seller" ) {
					

		    	$seller_unread_count = $chat_history_data->seller_unread_count;

		    	if ($is_user_online == '1') {
		    		//if user is online, no need to send noti and add unread count

		    		$chat_data = array(

			        	"item_id" => $this->post('item_id'), 
			        	"buyer_user_id" => $this->post('buyer_user_id'), 
			        	"seller_user_id" => $this->post('seller_user_id'),
			        	"seller_unread_count" => $seller_unread_count,
			        	"added_date" => date("Y-m-d H:i:s"),

			        );

		    	} else {
		    		//if user is offline, send noti and add unread count

		    		$chat_data = array(

			        	"item_id" => $this->post('item_id'), 
			        	"buyer_user_id" => $this->post('buyer_user_id'), 
			        	"seller_user_id" => $this->post('seller_user_id'),
			        	"seller_unread_count" => $seller_unread_count + 1,
			        	"added_date" => date("Y-m-d H:i:s"),

		        	);

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


					$user_id = $this->post('buyer_user_id');
		       		$user_name = $this->User->get_one($user_id)->user_name;

			    	$data['message'] = "Image!";
			    	$data['buyer_user_id'] = $this->post('buyer_user_id');
			    	$data['seller_user_id'] = $this->post('seller_user_id');
			    	$data['sender_name'] = $user_name;
			    	$data['item_id'] = $this->post('item_id');

	    			$status = send_android_fcm_chat( $device_ids, $data );

		    	}

		    	

			}

			if( !$this->Chat->Save( $chat_data,$chat_history_data->id )) {

	    		$this->error_response( get_msg( 'err_accept_update' ));

	    	
	    	} else {


	    		if ( !$sender_id ) {
					$this->custom_response( get_msg('sender_id_required') ) ;
				}
				
				//$sender_id = $this->post('sender_id');

				
					
				$uploaddir = 'uploads/';
				
				$path_parts = pathinfo( $_FILES['file']['name'] );
				$filename = $path_parts['filename'] . date( 'YmdHis' ) .'.'. $path_parts['extension'];
				
				//print_r($filename); die;

				

				if (move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir . $filename)) {
				   	
					$data = getimagesize($uploaddir . $filename);
					$width = $data[0];
					$height = $data[1];

					//call to image reseize
			
			   		//$this->image_resize_calculation( FCPATH. $uploaddir . $filename );

				   	$img_data = array( 
				   		
				   		'img_parent_id' => $sender_id, 
				   		'img_type'      => "chat",
				   		'img_path'      => $filename,
				   		'img_width'     => $width,
				   		'img_height'    => $height 

				   	);

				   //	print_r($img_data); die;

				   if ( $this->Image->save( $img_data ) ) {

				   		//print_r($img_data['img_id']);

					   	$image = $this->Image->get_one( $img_data['img_id'] );

					   	//$this->ps_adapter->convert_image( $image );
					   	
					   	$this->custom_response( $image );

				   } else {
					   	$this->error_response( get_msg('file_na') );
				   }
				   
				} else {
				   $this->error_response( get_msg('file_na') );
					
				}
		

	    	}


		}

		
	}

	/** Delete Item Image **/

	function delete_item_image_post(){

		$rules = array(
			array(
	        	'field' => 'img_id',
	        	'rules' => 'required'
	        )
	    );    

	    // exit if there is an error in validation,
        if ( !$this->is_valid( $rules )) exit;

        $img_id = $this->post('img_id');

        $conds_img['img_id'] = $img_id;

        $conds_itm_img['img_parent_id'] = $this->Image->get_one_by($conds_img)->img_parent_id;

        $img_count = $this->Image->count_all_by($conds_itm_img);



        if ( !$this->ps_delete->delete_images_by( array( 'img_id' => $img_id ))) {

        	$this->error_response( get_msg( 'err_model' ));

        	
        }else{

        	if ($img_count > 1) {
	        	for ($i=0; $i < $img_count ; $i++) { 
	        		$conds_itm_img['order_by'] = 1;
	        		$itm_images = $this->Image->get_all_by($conds_itm_img)->result();

	        		$j = $i + 1;

	        		if ($itm_images[$i]->ordering != $j) {
	        			$itm_images[$i]->ordering = $j;

	        			$img_data = array(
	        				"ordering" => $itm_images[$i]->ordering
	        			);

	        			$this->Image->save($img_data,$itm_images[$i]->img_id);
	        		}
	        	}
	        }

        	$this->success_response( get_msg( 'success_img_delete' ));

        }

	}
	
	/**
	 * Convert Object
	 */
	function convert_object( &$obj )
	{
		// call parent convert object
		parent::convert_object( $obj );

		// convert customize category object
		$this->ps_adapter->convert_image( $obj );
	}

// 	function image_resize_calculation( $path )
// 	{

// echo "fasfafafa";die;
// 		// Start 

// 		$uploaded_file_path = $path;

// 		list($width, $height) = getimagesize($uploaded_file_path);
// 		$uploaded_img_width = $width;
// 		$uploaded_img_height = $height;

// 		$org_img_type = "";

// 		$org_img_landscape_width_config = $this->Backend_config->get_one("be1")->landscape_width; //setting
// 		$org_img_portrait_height_config = $this->Backend_config->get_one("be1")->potrait_height; //setting
// 		$org_img_square_width_config   = $this->Backend_config->get_one("be1")->square_height; //setting

		
// 		$thumb_img_landscape_width_config = $this->Backend_config->get_one("be1")->landscape_thumb_width; //setting
// 		$thumb_img_portrait_height_config = $this->Backend_config->get_one("be1")->potrait_thumb_height; //setting
// 		$thumb_img_square_width_config   = $this->Backend_config->get_one("be1")->square_thumb_height; //setting

// 		$thumb2x_img_landscape_width = $this->Backend_config->get_one("be1")->landscape_thumb2x_width; //setting
// 		$thumb2x_img_portrait_height = $this->Backend_config->get_one("be1")->potrait_thumb2x_height; //setting
// 		$thumb2x_img_square_width   = $this->Backend_config->get_one("be1")->square_thumb2x_height; //setting

// 		$thumb3x_img_landscape_width = $this->Backend_config->get_one("be1")->landscape_thumb3x_width; //setting
// 		$thumb3x_img_portrait_height = $this->Backend_config->get_one("be1")->potrait_thumb3x_height; //setting
// 		$thumb3x_img_square_width  = $this->Backend_config->get_one("be1")->square_thumb3x_height; //setting

// 		// $org_img_landscape_width_config = 1000; //setting
// 		// $org_img_portrait_height_config = 1000; //setting
// 		// $org_img_square_width_config   = 1000; //setting

		
// 		// $thumb_img_landscape_width_config = 200; //setting
// 		// $thumb_img_portrait_height_config = 200; //setting
// 		// $thumb_img_square_width_config   = 200; //setting


// 		$need_resize = 0; //Flag
			
// 		$org_img_ratio = 0; 
// 		$thumb_img_ratio = 0;

// 		if($uploaded_img_width > $uploaded_img_height) {
// 			$org_img_type = "L";
// 		} else if ($uploaded_img_width < $uploaded_img_height) {
// 			$org_img_type = "P";
// 		} else {
// 			$org_img_type = "S";
// 		}


// 		if( $org_img_type == "L" ) {
// 			echo 1;
// 			//checking width because of Landscape Image
// 			if( $org_img_landscape_width_config < $uploaded_img_width ) {

// 				$need_resize = 1;
// 				$org_img_ratio = round($org_img_landscape_width_config / $uploaded_img_width,3);
// 				$thumb_img_ratio = round($thumb_img_landscape_width_config / $uploaded_img_width,3);

// 			} else {
// 				$thumb1x_img_ratio = round($thumb_img_landscape_width_config / $uploaded_img_width,3);
// 				$thumb2x_img_ratio = round($thumb2x_img_landscape_width / $uploaded_img_width,3);
// 				$thumb3x_img_ratio = round($thumb3x_img_landscape_width / $uploaded_img_width,3);

// 				$thumb1x_width = $thumb_img_landscape_width_config;
// 				$thumb1x_height = round($uploaded_img_height * $org_img_ratio, 0);

// 				$thumb2x_width = $thumb2x_img_landscape_width;
// 				$thumb2x_height = round($uploaded_img_height * $org_img_ratio, 0);

// 				$thumb3x_width = $thumb3x_img_landscape_width;
// 				$thumb3x_height = round($uploaded_img_height * $org_img_ratio, 0);
// 			}

// 		}
// 		print_r($org_img_type);die;
// 		if( $org_img_type == "P" ) {
// 			//checking width because of portrait Image
// 			if( $org_img_portrait_height_config < $uploaded_img_height ) {

// 				$need_resize = 1;
// 				$org_img_ratio = round($org_img_portrait_height_config / $uploaded_img_height,3);
// 				$thumb_img_ratio = round($thumb_img_portrait_height_config / $uploaded_img_height,3);
// 			}
			
// 		}

// 		if( $org_img_type == "S" ) {
// 			//checking width (or) hight because of square Image
// 			if( $org_img_square_width_config < $uploaded_img_width ) {

// 				$need_resize = 1;
// 				$org_img_ratio = round($org_img_square_width_config / $uploaded_img_width,3);
// 				$thumb_img_ratio = round($thumb_img_square_width_config / $uploaded_img_width,3);

// 			}
			
// 		}


// 		//if( $need_resize == 1 ) {
// 			//original image need to resize according to config width and height
			
// 			// resize for original image
// 			$new_image_path = FCPATH . "uploads/";
			
// 			if( $need_resize == 1 ) {
// 				$org_img_width  = round($uploaded_img_width * $org_img_ratio, 0);
// 				$org_img_height = round($uploaded_img_height * $org_img_ratio, 0);
// 			} else {
// 				$org_img_width = $org_img_width - 2;
// 				$org_img_height = $org_img_height - 2;
// 			}

// 			$this->ps_image->create_thumbnail( $uploaded_file_path, $org_img_width, $org_img_height, $new_image_path );
			
// 			print_r($thumb_img_ratio);die;
			
// 			// $thumb_img_width  = round($uploaded_img_width * $thumb_img_ratio, 0);
// 			// $thumb_img_height = round($uploaded_img_height * $thumb_img_ratio, 0);
			
			
// 			//resize for 1x,2x,3x thumbnail
// 			$new_image__thumb_path = FCPATH . "uploads/thumbnail/";
// 			$thumb_2x_path = FCPATH . "uploads/thumbnail2x/";
// 			$thumb_3x_path = FCPATH . "uploads/thumbnail3x/";
// 			$this->ps_image->create_thumbnail( $uploaded_file_path, $thumb1x_width, $thumb1x_height, $new_image__thumb_path );
// 			$this->ps_image->create_thumbnail_2x( $uploaded_file_path, $thumb2x_width, $thumb2x_height, $thumb_2x_path );
// 			$this->ps_image->create_thumbnail_3x( $uploaded_file_path, $thumb3x_width, $thumb3x_height, $thumb_3x_path );

			

// 			//End Modify

// 		//}


// 		// End


// 	}


 	/** Get Item Gallery Image */

	function get_item_gallery_get()
	{
		// add flag for default query
		$this->is_get = true;

		// get limit & offset
		$limit = $this->get( 'limit' );
		$offset = $this->get( 'offset' );

		// get search criteria
		$default_conds = $this->default_conds();
		$user_conds = $this->get();
		$conds = array_merge( $default_conds, $user_conds );
		$conds['order_by'] = 1;
		
		if ( $limit ) {
			unset( $conds['limit']);
		}

		if ( $offset ) {
			unset( $conds['offset']);
		}

		if ( !empty( $limit ) && !empty( $offset )) {
			// if limit & offset is not empty

			$data = $this->model->get_all_by( $conds, $limit, $offset )->result();
		} else if ( !empty( $limit )) {
		// if limit is not empty

			$data = $this->model->get_all_by( $conds, $limit )->result();
		} else {
		// if both are empty

			$data = $this->model->get_all_by( $conds )->result();
		}

		$this->custom_response( $data , $offset );
	}

	/** Upload Video */

	function video_upload_post()
	{
		$item_id = $this->post('item_id');
		$uploaddir = 'uploads/';
		
		$path_parts = pathinfo( $_FILES['file']['name'] );

		//for space video file name
		$result_filename = preg_replace("/[^a-zA-Z0-9]+/", "", $path_parts['filename']);
		$res = $result_filename .'.'. $path_parts['extension'];

		$video = $res;

		if(isset($video)) {
			$img_id = $this->post('img_id');

            $_FILES['file']['name'] = $res;


			if (trim($img_id) == "") {

				if (move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir . $video)) {
					$video_data = array( 
					   	'img_parent_id'=> $item_id,
						'img_path' => $video,
						'img_type' => "video",
						'img_width'=> 0,
						'img_height'=> 0
				   	);
				   	if ( !$this->Image->save( $video_data ) ) {
				   		$this->error_response( get_msg('file_na') );
				   	}
					
				   	$video = $this->Image->get_one( $video_data['img_id'] );
				   	$this->custom_response( $video );

				}
			} else {


				if (move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir . $video)) {
					$video_data = array( 
                        'img_id' => $img_id,
					   	'img_parent_id'=> $item_id,
						'img_path' => $video,
						'img_type' => "video",
						'img_width'=> 0,
						'img_height'=> 0
				   	);
				   	if ( !$this->Image->save( $video_data,$img_id ) ) {
				   		$this->error_response( get_msg('file_na') );
				   	}
					
				   	$video = $this->Image->get_one( $video_data['img_id'] );

				   	$this->custom_response( $video );

				}

			}
		} else {
			$this->error_response( get_msg('file_na') );
		}
	}

	/**
	 * Delete Video by id and type
	 *
	 * @param      <type>  $conds  The conds
	 */
	function delete_videos_by( $conds )
	{
		/**
		 * Delete Video from folder
		 *
		 */
	
		$videos = $this->Image->get_all_by( $conds );
	
		if ( !empty( $videos )) {

			foreach ( $videos->result() as $vid ) {
				
				if ( ! $this->ps_image->delete_images( $vid->img_path ) ) {
				// if there is an error in deleting images

					$this->set_flash_msg( 'error', get_msg( 'err_del_image' ));
					return false;
				}
			}
		}

		/**
		 * Delete images from database table
		 */
		if ( ! $this->Image->delete_by( $conds )) {

			$this->set_flash_msg( 'error', get_msg( 'err_model' ));
			return false;
		}

		return true;
	}

	/** delete item video */

	function delete_video_and_icon_post(){

		$rules = array(
			array(
	        	'field' => 'img_id',
	        	'rules' => 'required'
	        )
	    );    

	    // exit if there is an error in validation,
        if ( !$this->is_valid( $rules )) exit;

        $img_id = $this->post('img_id');
        $img_data = $this->Image->get_one($img_id);
        //print_r($img_data->is_empty_object);die;

        $img_type = $this->Image->get_one($img_id)->img_type;

        if ($img_type == "video") {
        	$success_delete = get_msg( 'success_video_delete' );
        } else {
        	$success_delete = get_msg( 'success_video_icon_delete' );
        }

        if ($img_data->is_empty_object != 1) {
        	if ( !$this->ps_delete->delete_images_by( array( 'img_id' => $img_id ))) {

        	$this->error_response( get_msg( 'err_model' ));

        	
	        }else{

	        	$this->success_response( $success_delete );

	        }
        } else {
        	$this->error_response( get_msg( 'invalid_img_id' ));
        }

        

	}

	/** upload video icon */

	function upload_video_icon_post()
	{


		$item_id = $this->post('item_id');
		$files = $this->post('file');
		$img_id = $this->post('img_id');

			if (trim($img_id) == "") {

				$path_parts = pathinfo( $_FILES['file']['name'] );

				if(strtolower($path_parts['extension']) != "jpeg" && strtolower($path_parts['extension']) != "png" && strtolower($path_parts['extension']) != "jpg") {


					$uploaddir = 'uploads/';

					$path_parts = pathinfo( $_FILES['file']['name'] );
					
					$filename = $path_parts['filename'] . date( 'YmdHis' ) .'.'. $path_parts['extension'];



					// upload image to "uploads" folder
					if (move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir . $filename)) {

						//move uploaded image to thumbnail folder
					    $item_img_data = array( 
						   	'img_parent_id'=> $item_id,
							'img_path' => $filename,
							'img_type' => "video-icon",
							'img_width'=> 0,
							'img_height'=> 0,
							'ordering' => $this->post('ordering')
					   	);

					}

				} else {
					//if image is JPG or PNG (Not heic format)	
					$upload_data = $this->ps_image->upload( $_FILES );


					foreach ( $upload_data as $upload ) {
					   	$item_img_data = array( 
						   	'img_parent_id'=> $item_id,
							'img_path' => $upload['file_name'],
							'img_type' => "video-icon",
							'img_width'=> $upload['image_width'],
							'img_height'=> $upload['image_height'],
							'ordering' => $this->post('ordering')
					   	);
					}


				}


			    if ( $this->Image->save( $item_img_data) ) {

				   	$image = $this->Image->get_one( $item_img_data['img_id'] );

				   	$this->ps_adapter->convert_image( $image );
				   	
				   	$this->custom_response( $image );
			    } else {
				   	$this->error_response( get_msg('file_na') );
			    }
				
				   
			} else {
				
				$path_parts = pathinfo( $_FILES['file']['name'] );
				
				if($path_parts['extension'] == "heic" or $path_parts['extension'] == "HEIC") {
					
					$uploaddir = 'uploads/';

					$path_parts = pathinfo( $_FILES['file']['name'] );
					
					$filename = $path_parts['filename'] . date( 'YmdHis' ) .'.'. $path_parts['extension'];



					// upload image to "uploads" folder
					if (move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir . $filename)) {

					    //copy success file
					    $item_img_data = array( 
						   	'img_parent_id'=> $item_id,
							'img_path' => $filename,
							'img_type' => "video-icon",
							'img_width'=> 0,
							'img_height'=> 0,
							'ordering' => $this->post('ordering')
					   	);

					}

				} else {

					// upload images
					$upload_data = $this->ps_image->upload( $_FILES );

					foreach ( $upload_data as $upload ) {
					   	$item_img_data = array( 
					   		'img_id' => $img_id,
						   	'img_parent_id'=> $item_id,
							'img_path' => $upload['file_name'],
							'img_width'=> $upload['image_width'],
							'img_height'=> $upload['image_height'],
							'ordering' => $this->post('ordering')
					   	);
					}

				}



			   	if ( $this->Image->save( $item_img_data, $img_id ) ) {
			   		
				   	$image = $this->Image->get_one( $item_img_data['img_id'] );

				   	$this->ps_adapter->convert_image( $image );
				   	
				   	$this->custom_response( $image );
			   	} else {
				   	$this->error_response( get_msg('file_na') );
			   	}
			
			}

	}
}