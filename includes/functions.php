<?php

/**
 * Insert team members information.
 *
 * @param array $args
 * @return init|\WP_Error
 */
function crud_test_team_member_info_insert( $args = array() ) {

	global $wpdb;
	if ( empty( $args['name'] ) ) {
		return new \WP_Error( 'no-name', 'Please provide a name' );
	}
	$defaults = array(
		'name'        => '',
		'designation' => '',
		'email'       => '',
		'phone'       => '',
		'address'     => '',
		'bio'         => '',
		'created_by'  => get_current_user_id(),
		'created_at'  => current_time( 'mysql' ),
	);
	$data     = wp_parse_args( $args, $defaults );
	$prefix   = $wpdb->prefix;

	$inserted = $wpdb->insert(
		"{$prefix}crud_test_team_members",
		$data,
		array(
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%d',
			'%s',
		)
	);

	if ( ! $inserted ) {
		return new \WP_Error( 'failed-to-insert', __( 'Unable to insert data' ) );
	}
	return $inserted;
}

/**
 * Get the info about the team members from the database.
 *
 * @param array $args pagination array.
 * @return array
 */
function get_crud_team_members_info( $args = array() ) {

	global $wpdb;
	$defaults = array(
		'numbers' => 20,
		'offset'  => 0,
		'orderby' => 'id',
		'order'   => 'ASC',
	);
	$args     = wp_parse_args( $args, $defaults );

	$items = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT * FROM `{$wpdb->prefix}crud_test_team_members`
			ORDER BY {$args['orderby']} {$args['order']}
			LIMIT %d, %d",
			$args['offset'],
			$args['numbers']
		)
	);
	return $items;
}

/**
 * Get number of members table rows.
 *
 * @return void
 */
function get_crud_team_members_table_row_count() {
	global $wpdb;
	return $wpdb->get_var( "SELECT COUNT(*) FROM `{$wpdb->prefix}crud_test_team_members`" );
}


