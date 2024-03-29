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
		// Delete object cache for single entity.
		crud_test_team_member_purge_cache( $id );
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

		// Delete object cache for count row.
		crud_test_team_member_purge_cache();

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

	$last_changed = wp_cache_get_last_changed( 'crud-team-member' );
	$key          = md5( serialize( array_diff_assoc( $args, $defaults ) ) );
	$cache_key    = "all:$key:$last_changed";

		$sql = $wpdb->prepare(
			"SELECT * FROM `{$wpdb->prefix}crud_test_team_members`
			ORDER BY {$args['orderby']} {$args['order']}
			LIMIT %d, %d",
			$args['offset'],
			$args['numbers']
		);

	$items = wp_cache_get( $cache_key, 'crud-team-member' );
	if ( false === $items ) {
		$items = $wpdb->get_results( $sql );
		wp_cache_set( $cache_key, $items, 'crud-team-member' );
	}
	return $items;
}

/**
 * Get number of members table rows.
 *
 * @return void
 */
function get_crud_team_members_table_row_count() {
	global $wpdb;
	// Get number of members table rows from cache.
	$count = wp_cache_get( 'team_members_table_row_count', 'crud-team-member' );
	// set number of members table rows to cache.
	if ( $count === false ) {
		$count = $wpdb->get_var( "SELECT COUNT(*) FROM `{$wpdb->prefix}crud_test_team_members`" );
		wp_cache_set( 'team_members_table_row_count', $count, 'crud-team-member' );
	}
	return $count;
}

/**
 * Get single row team members information.
 *
 * @param int $id id of the team member row.
 * @return object
 */
function crud_get_team_members_info( $id ) {
	global $wpdb;

	// set object caching settings for single entry.
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

	crud_test_team_member_purge_cache( $id );

	return $wpdb->delete(
		"{$wpdb->prefix}crud_test_team_members",
		array(
			'id' => $id,
		),
		array( '%d' )
	);

}

/**
 * Purge the cache for crud-test team members.
 *
 * @param int $info_id The ID of the team member.
 * @return void
 */
function crud_test_team_member_purge_cache( $info_id = null ) {
	$group = 'crud-team-member';
	if ( $info_id ) {
		// Delete object cache for single entry.
		wp_cache_get( 'crud-team-member-info-' . $info_id, 'crud-team-member' );
	}
	// Delete object cache for row count.
	wp_cache_delete( 'team_members_table_row_count', 'crud-team-member' );

	wp_cache_set( 'last_changed', microtime(), $group );
}



