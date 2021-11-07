<?php
require_once( APPPATH .'libraries/REST_Controller.php' );

/**
 * REST API for News
 */
class Itemlocations extends API_Controller
{

	/**
	 * Constructs Parent Constructor
	 */
	function __construct()
	{
		parent::__construct( 'Itemlocation' );
	}

	/**
	 * Default Query for API
	 * @return [type] [description]
	 */
	function default_conds()
	{
		$conds = array();

		if ( $this->is_get ) {
		// if is get record using GET method

			// get default setting for GET_ALL_CATEGORIES
			//$setting = $this->Api->get_one_by( array( 'api_constant' => GET_ALL_CATEGORIES ));

			// $conds['order_by'] = 1;
			// $conds['order_by_field'] = $setting->order_by_field;
			// $conds['order_by_type'] = $setting->order_by_type;
		}

		if ( $this->is_search ) {

			//$setting = $this->Api->get_one_by( array( 'api_constant' => SEARCH_WALLPAPERS ));

            if ( !empty($this->post('curr_location')) ) {
                
                $json2 = json_encode($this->post('curr_location'));
                // log_message('error', "post data");
                // log_message('error',$this->post('curr_location'));

                
                // // log_message("error", gettype($json1));
                $json = json_decode($json2, true);
                // log_message("error", $json);
                // log_message('error', json_last_error());
                // log_message("error", gettype($json));
                // log_message("error", $json);

                // $loc = array(
                //     'name' => $json['locality'],
                //     'ordering' => 1,
                //     'lat' => $json['coordinates']['latitude'],
                //     'lng' => $json['coordinates']['longitude'],
                //     'status' => 1,
                //     'addressLine' => $json['addressLine'],
                //     'countryName' => $json['countryName'],
                //     'countryCode' => $json['countryCode'],
                //     'featureName' => $json['featureName'],
                //     'postalCode' => $json['postalCode'],
                //     'city' => $json['locality'],
                //     'subLocality' => $json['subLocality'],
                //     'adminArea' => $json['adminArea'],
                //     'subAdminArea' => $json['subAdminArea'],
                //     'thoroughfare' => $json['thoroughfare'],
                //     'subThoroughfare' => $json['subThoroughfare']
                // );

                // // var_dump($loc);
                // // exit;

                // try {
                //     $this->Itemlocation->save($loc);
                // } catch(Exception $e) {
                //     echo 'Message: ' .$e->getMessage();
                // }

                $conds['custom_sql']= "closer_cities";
                $conds['lat'] = $json['coordinates']['latitude'];
                $conds['lng'] = $json['coordinates']['longitude'];

            }

            if($this->post('keyword') != "") {
                $conds['keyword']   = $this->post('keyword');
            }

            $conds['order_by'] = 1;
            $conds['order_by_field']    = $this->post('order_by');
            $conds['order_by_type']     = $this->post('order_type');

        }

        return $conds;
    }


    /**
     * Convert Object
     */
    function convert_object( &$obj )
    {

        // call parent convert object
        parent::convert_object( $obj );

    }
}