<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model class for category table
 */
class Subcategory extends PS_Model {

	/**
	 * Constructs the required data
	 */
	function __construct() 
	{
		parent::__construct( 'bs_subcategories', 'id', 'subcat' ,'main_cat_id');
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

		// echo "<pre>";
		// print_r($conds);
		// exit();

		// category id condition
		if ( isset( $conds['cat_id'] )) {
			
			if ($conds['cat_id'] != "" || $conds['cat_id'] != 0) {
				
				$this->db->where( 'cat_id', $conds['cat_id'] );	

			}			
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



		// sub category id condition
		if ( isset( $conds['id'] )) {
			$this->db->where( 'id', $conds['id'] );	
		}

		// sub cat_name condition
		if ( isset( $conds['name'] )) {
			$this->db->where( 'name', $conds['name'] );
		}

		// search_term
		if ( isset( $conds['searchterm'] )) {
			
			if ($conds['searchterm'] != "") {
				$this->db->group_start();
				$this->db->like( 'name', $conds['searchterm'] );
				$this->db->or_like( 'name', $conds['searchterm'] );
				$this->db->group_end();
			}
			
		}

		// order_by
		if ( isset( $conds['order_by_field'] )) {

			$order_by_field = $conds['order_by_field'];
			$order_by_type = $conds['order_by_type'];

			$this->db->order_by( 'bs_subcategories.'.$order_by_field, $order_by_type );
		} else {


			$this->db->order_by( 'added_date' );
		}

		$this->db->order_by( 'added_date', 'desc' );

	}
}
	