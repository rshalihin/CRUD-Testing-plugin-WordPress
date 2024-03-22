<?php

/**
 * Handles Admin menu items.
 *
 * @package crud-testing
 */

namespace MyCrud\Testing\Admin;

/**
 * Menu handler class
 */
class Menu {

	/**
	 * TeamInfo class.
	 *
	 * @var mixed
	 */
	public $crud_team_member;


	/**
	 * Menu constructor.
	 */
	public function __construct( $crud_team_member ) {
		$this->crud_team_member = $crud_team_member;
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
	}

	/**
	 * Add menu in the admin panel.
	 *
	 * @return void
	 */
	public function add_admin_menu() {

		$capability  = 'manage_options';
		$parent_slug = 'crud-team-members';

		$hook = add_menu_page( __( 'Team Members', 'crud-test' ), __( 'CRUD Team', 'crud-test' ), $capability, $parent_slug, array( $this->crud_team_member, 'crud_testing_team_members_page' ), 'dashicons-groups' );
		add_submenu_page( $parent_slug, __( 'Team Members', 'crud-test' ), __( 'Team Members', 'crud-test' ), $capability, $parent_slug, array( $this->crud_team_member, 'crud_testing_team_members_page' ) );
		add_submenu_page( $parent_slug, __( 'Crud Settings', 'crud-test' ), __( 'Crud Settings', 'crud-test' ), $capability, 'crud-team-members-settings', array( $this, 'crud_team_members_settings' ) );
	}




	/**
	 * Team members settings page.
	 *
	 * @return void
	 */
	public function crud_team_members_settings() {
		echo 'Hello form Crud Settings';
	}
}
