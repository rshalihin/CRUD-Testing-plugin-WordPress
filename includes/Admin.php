<?php

namespace MyCrud\Testing;

/**
 * Admin class handling.
 */
class Admin {

	/**
	 * Admin constructor.
	 */
	public function __construct() {
		$crud_team_member = new Admin\TeamInfo();
		new Admin\Menu( $crud_team_member );
		$this->dispatch_function( $crud_team_member );
	}

	/**
	 * Dispatch functionality.
	 *
	 * @return void
	 */
	public function dispatch_function( $crud_team_member ) {
		add_action( 'admin_init', array( $crud_team_member, 'crud_testing_team_members_from_handle' ) );
	}

}

