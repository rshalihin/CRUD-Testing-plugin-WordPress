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

	if ( isset( $data['id'] ) ) {
		$id = $data['id'];
		unset( $data['id'] );

		$updated = $wpdb->update(
			"{$prefix}crud_test_team_members",
			$data,
			array( 'id' => $id ),
			array(
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%d',
				'%s',
			),
			array( '%d' )
		);
		wp_cache_delete( 'crud-team-member-info-' . $id, 'crud-team-member' );
		return $updated;

	} else {
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

/**
 * Get single row team members information.
 *
 * @param int $id id of the team member row.
 * @return object
 */
function crud_get_team_members_info( $id ) {
	global $wpdb;

	$information = wp_cache_get( 'crud-team-member-info-' . $id, 'crud-team-member' );
	if ( false === $information ) {
		$information = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM `{$wpdb->prefix}crud_test_team_members`
								WHERE id = %d",
				$id
			)
		);
		wp_cache_set( 'crud-team-member-info-' . $id, $information, 'crud-team-member' );
	}

	return $information;
}

/**
 * Delete Team members information
 *
 * @param int $id id of the team member row.
 * @return object
 */
function crud_delete_team_members_info( $id ) {
	global $wpdb;
	return $wpdb->delete(
		"{$wpdb->prefix}crud_test_team_members",
		array(
			'id' => $id,
		),
		array( '%d' )
	);
}



