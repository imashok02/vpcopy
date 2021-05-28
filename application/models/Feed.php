<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model class for feed table
 */
class Feed extends PS_Model {

	/**
	 * Constructs the required data
	 */
	function __construct() 
	{
		parent::__construct( 'bs_feeds', 'id', 'feed' );
	}

	/**
	 * Implement the where clause
	 *
	 * @param      array  $conds  The conds
	 */
	function custom_conds( $conds = array())
	{
		
		// default where clause
		if ( !isset( $conds['no_publish_filter'] )) {
			$this->db->where( 'status', 1 );
		}

		// main category id condition
		if ( isset( $conds['main_cat_id'] )) {


			if ($conds['main_cat_id'] != "" || $conds['main_cat_id'] != 0) {
		// 		echo "<pre>";
		// print_r($conds['main_cat_id']);
		// exit();

				$this->db->where( 'main_cat_id', $conds['main_cat_id'] );	

			}			
		}

		// feed_name condition
		if ( isset( $conds['name'] )) {
			$this->db->where( 'name', $conds['name'] );
		}

		// feed_desc condition
		if ( isset( $conds['description'] )) {
			$this->db->where( 'description', $conds['description'] );
		}

		// searchterm
		if ( isset( $conds['searchterm'] )) {
			$this->db->group_start();
			$this->db->like( 'name', $conds['searchterm'] );
			$this->db->or_like( 'name', $conds['searchterm'] );
			$this->db->group_end();
		}

		$this->db->order_by( 'added_date', 'desc' );
	}
}