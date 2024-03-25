<?php
/**
 * CRUD Test
 *
 * @package           crud-testing
 * @author            Md. Readush Shalihin
 * @copyright         2024 shapedplugins
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       CRUD Test
 * Plugin URI:        https://example.com/plugin-name
 * Description:       WordPress crud system create with Standard coding (PHPCS).
 * Version:           0.1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Md. Readush Shalihin
 * Author URI:        https://example.com
 * Text Domain:       crud-test
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Plugin main class.
 */
final class CRUD_TEST {

	/**
	 * CRUD_TEST class constructor.
	 */
	private function __construct() {

		$this->define_constants();
		register_activation_hook( __FILE__, array( $this, 'crud_test_activate' ) );
		add_action( 'plugins_loaded', array( $this, 'plugins_init' ) );
	}

	/**
	 * Singleton instance of CRUD_TEST.
	 *
	 * @return \CRUD_TEST
	 */
	public static function init() {
		static $instance = false;
		if ( ! $instance ) {
			$instance = new self();
		}
		return $instance;
	}

	/**
	 * Define plugin constants.
	 *
	 * @return void
	 */
	public function define_constants() {

		if ( ! defined( 'CRUD_VERSION', ) ) {
			define( 'CRUD_VERSION', '0.1.0' );
		}
		if ( ! defined( 'CRUD_DIR_PATH' ) ) {
			define( 'CRUD_DIR_PATH', __DIR__ );
		}
		if ( ! defined( 'CRUD_PLUGIN_FILE' ) ) {
			define( 'CRUD_PLUGIN_FILE', __FILE__ );
		}
		if ( ! defined( 'CRUD_PLUGIN_URL' ) ) {
			define( 'CRUD_PLUGIN_URL', plugin_dir_url( CRUD_PLUGIN_FILE ) );
		}
		if ( ! defined( 'CRUD_PLUGIN_ASSET' ) ) {
			define( 'CRUD_PLUGIN_PATH', CRUD_PLUGIN_URL . 'assets' );
		}
	}


	/**
	 * Run After activating the plugin.
	 *
	 * @return void
	 */
	public function crud_test_activate() {
		$installer = new MyCrud\Testing\Installer();
		$installer->run_installer();
	}

	/**
	 * Fire after plugins loaded successfully.
	 */
	public function plugins_init() {
		if ( is_admin() ) {
			new MyCrud\Testing\Admin();
		} else {
			new MyCrud\Testing\Frontend();
		}

	}
}

if ( ! function_exists( 'run_crud' ) ) {

	/**
	 * Run Plugins main class
	 *
	 * @return \CRUD_TEST
	 */
	function run_crud() {
		return CRUD_TEST::init();
	}
}
run_crud();
