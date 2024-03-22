<?php

namespace MyCrud\Testing;

/**
 * Admin class handling.
 */
class Installer {

	/**
	 * Run upon successful installation.
	 */
	public function run_installer() {
		$this->add_version();
		$this->create_table();
	}

	/**
	 * Add version information to database.
	 *
	 * @return void
	 */
	public function add_version() {
		$install = get_option( 'crud_test_install' );
		if ( ! $install ) {
			update_option( 'crud_test_install', time() );
		}
		update_option( 'crud_test_version', CRUD_VERSION );
	}

	/**
	 * Create a table in database after installation.
	 *
	 * @return void
	 */
	public function create_table() {
		global $wpdb;
		$prefix          = $wpdb->prefix;
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS `{$prefix}crud_test_team_members` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `name` VARCHAR(100) NOT NULL , `designation` VARCHAR(100) NOT NULL , `email` VARCHAR(255) NOT NULL , `phone` VARCHAR(30) NOT NULL , `address` VARCHAR(255) NULL , `bio` TEXT NULL , `create_at` DATETIME NOT NULL , `create_by` BIGINT(20) UNSIGNED NOT NULL , PRIMARY KEY (`id`)) $charset_collate;";

		if ( ! function_exists( 'dbDelta' ) ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		}
		dbDelta( $sql );
	}

}
