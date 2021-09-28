<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Likes Controller
 */

class Thumbnail_generators extends BE_Controller {
		/**
	 * Construt required variables
	 */
	function __construct() {

		parent::__construct( MODULE_CONTROL, 'Thumbnail_Generators' );
		$this->load->library( 'PS_Image' );
		///start allow module check 
		$conds_mod['module_name'] = $this->router->fetch_class();
		
		$module_id = $this->Module->get_one_by($conds_mod)->module_id;
		
		$logged_in_user = $this->ps_auth->get_user_info();

		$user_id = $logged_in_user->user_id;
		if(empty($this->User->has_permission( $module_id,$user_id )) && $logged_in_user->user_is_sys_admin!=1){
			return redirect( site_url('/admin/') );
		}
		///end check
	}

	/**
	 * Load About Entry Form
	 */

	function index( ) {

		if ( $this->is_POST()) {
		// if the method is post

			// save user info
			$this->createThumbs( );
			
		}

		//Get Paid_config Object
		$this->data['pconfig'] = $this->Paid_config->get_one( $id );

		$this->load_form($this->data);

	}

	function createThumbs( ) {
		
		// get the path to the current directory
		$path = 'uploads/';
		$path_info = pathinfo($_SERVER['SCRIPT_NAME']);
		$url = $path_info['dirname'];
		 
		// create an array to store image names in.
		$images = array();
		$dir = new DirectoryIterator($path);

		foreach( $dir as $entry ){
			//check file exist
			if( $entry->isFile() ){
				$img_path = $entry->getPathname();
				$img_size = getimagesize($img_path);
				$org_img_width = $img_size[0];
				$ord_img_height = $img_size[1];

				//setting from backend config
				$thumb_img_landscape_width_config = $this->Backend_config->get_one("be1")->landscape_thumb_width; //setting
				$thumb_img_portrait_height_config = $this->Backend_config->get_one("be1")->potrait_thumb_height; //setting
				$thumb_img_square_width_config   = $this->Backend_config->get_one("be1")->square_thumb_height; //setting

				$thumb2x_img_landscape_width = $this->Backend_config->get_one("be1")->landscape_thumb2x_width; //setting
				$thumb2x_img_portrait_height = $this->Backend_config->get_one("be1")->potrait_thumb2x_height; //setting
				$thumb2x_img_square_width   = $this->Backend_config->get_one("be1")->square_thumb2x_height; //setting

				$thumb3x_img_landscape_width = $this->Backend_config->get_one("be1")->landscape_thumb3x_width; //setting
				$thumb3x_img_portrait_height = $this->Backend_config->get_one("be1")->potrait_thumb3x_height; //setting
				$thumb3x_img_square_width  = $this->Backend_config->get_one("be1")->square_thumb3x_height; //setting

				
				if($org_img_width > $ord_img_height) {
					$org_img_type = "L";
				} else if ($org_img_width < $ord_img_height) {
					$org_img_type = "P";
				} else {
					$org_img_type = "S";
				}
				
				if( $org_img_type == "L" ) {
					$thumb1x_img_ratio = round($thumb_img_landscape_width_config / $org_img_width,3);
					$thumb2x_img_ratio = round($thumb2x_img_landscape_width / $org_img_width,3);
					$thumb3x_img_ratio = round($thumb3x_img_landscape_width / $org_img_width,3);

					$thumb1x_width = $thumb_img_landscape_width_config;
					$thumb1x_height = round($ord_img_height * $thumb1x_img_ratio, 0);

					$thumb2x_width = $thumb2x_img_landscape_width;
					$thumb2x_height = round($ord_img_height * $thumb2x_img_ratio, 0);

					$thumb3x_width = $thumb3x_img_landscape_width;
					$thumb3x_height = round($ord_img_height * $thumb3x_img_ratio, 0);
				}
				
				if( $org_img_type == "P" ) {
					$thumb1x_img_ratio = round($thumb_img_portrait_height_config / $ord_img_height,3);
					$thumb2x_img_ratio = round($thumb2x_img_portrait_height / $ord_img_height,3);
					$thumb3x_img_ratio = round($thumb3x_img_portrait_height / $ord_img_height,3);

					$thumb1x_width = round($uploaded_data['image_width'] * $thumb1x_img_ratio, 0);
					$thumb1x_height = $thumb_img_portrait_height_config;

					$thumb2x_width = round($uploaded_data['image_width'] * $thumb2x_img_ratio, 0);
					$thumb2x_height = $thumb2x_img_portrait_height;

					$thumb3x_width = round($uploaded_data['image_width'] * $thumb3x_img_ratio, 0);
					$thumb3x_height = $thumb3x_img_portrait_height;
				}

				if( $org_img_type == "S" ) {
					$thumb1x_img_ratio = round($thumb_img_square_width_config / $uploaded_data['image_width'],3);
					$thumb2x_img_ratio = round($thumb2x_img_square_width / $uploaded_data['image_width'],3);
					$thumb3x_img_ratio = round($thumb3x_img_square_width / $uploaded_data['image_width'],3);

					$thumb1x_width = $thumb_img_square_width_config;
					$thumb1x_height = round($uploaded_data['image_height'] * $thumb1x_img_ratio, 0);

					$thumb2x_width = $thumb2x_img_square_width;
					$thumb2x_height = round($uploaded_data['image_height'] * $thumb2x_img_ratio, 0);

					$thumb3x_width = $thumb3x_img_square_width;
					$thumb3x_height = round($uploaded_data['image_height'] * $thumb3x_img_ratio, 0);
				}

				$this->ps_image->create_thumbnail( $img_path, $thumb1x_width, $thumb1x_height );
				$this->ps_image->create_thumbnail_2x( $img_path, $thumb2x_width, $thumb2x_height );
				$this->ps_image->create_thumbnail_3x( $img_path, $thumb3x_width, $thumb3x_height );
			}//End
		}//End foreach
		
		
	}

}