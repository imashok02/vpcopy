<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model class for about table
 */
class Blue_mark extends PS_Model {

	/**
	 * Constructs the required data
	 */
	function __construct() 
	{
		parent::__construct( 'bs_blue_mark_users', 'id', 'blue' );
	}

	/**
	 * Implement the where clause
	 *
	 * @param      array  $conds  The conds
	 */
	function custom_conds( $conds = array())
	{

        // order by
        if (isset($conds['order_by'])) {

            $order_by_field = $conds['order_by_field'];
            $order_by_type = $conds['order_by_type'];

            $this->db->order_by('bs_blue_mark_users.' . $order_by_field, $order_by_type);
        }

        // status id condition
//        if (isset($conds['status_id'])) {
//
//            if ($conds['status_id'] != "") {
//                if ($conds['status_id'] != '0') {
//
//                    $this->db->where('status', $conds['status_id']);
//                }
//            }
//        }

		// about_id condition
		if ( isset( $conds['id'] )) {
			$this->db->where( 'id', $conds['id'] );
		}

		// user_id condition
		if ( isset( $conds['user_id'] )) {
			$this->db->where( 'user_id', $conds['user_id'] );
		}

		// note condition
		if ( isset( $conds['note'] )) {
			$this->db->where( 'note', $conds['note'] );
		}



	}
}