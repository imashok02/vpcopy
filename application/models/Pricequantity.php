<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model class for Pricetype table
 */
class Pricequantity extends PS_Model {

	/**
	 * Constructs the required data
	 */
	function __construct() 
	{
		parent::__construct( 'bs_price_quantities', 'id', 'price_qty' );
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

		// id condition
		if ( isset( $conds['id'] )) {
			$this->db->where( 'id', $conds['id'] );
		}

		// name condition
		if ( isset( $conds['name'] )) {
			$this->db->where( 'name', $conds['name'] );
		}

		if ( isset( $conds['main_cat_id'] )) {
			$this->db->where( 'main_cat_id', $conds['main_cat_id'] );
		}

		if ( isset( $conds['cat_id'] )) {
			$this->db->where( 'cat_id', $conds['cat_id'] );
		}

		if ( isset( $conds['sub_cat_id'] )) {
			$this->db->where( 'sub_cat_id', $conds['sub_cat_id'] );
		}

		// searchterm
		if ( isset( $conds['searchterm'] )) {
			$this->db->like( 'name', $conds['searchterm'] );
		}

		// order_by
		if ( isset( $conds['order_by'] )) {

			$order_by_field = $conds['order_by_field'];
			$order_by_type = $conds['order_by_type'];

			$this->db->order_by( 'bs_price_quantities.'.$order_by_field, $order_by_type );
		} else {

			$this->db->order_by( 'added_date' );
		}
	}
}