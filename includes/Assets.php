<?php

namespace MyCrud\Testing;

/**
 * Admin class handling.
 */
class Assets {

	/**
	 * Enqueue assets.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'crud_enqueue_assets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'crud_enqueue_admin_assets' ) );
	}

	/**
	 * Get all js scripts for frontend.
	 *
	 * @return array
	 */
	public function get_frontend_scripts() {
		$frontend_scripts = array(
			'crud-frontend-scripts' => array(
				'src'     => CRUD_PLUGIN_ASSET . '/js/crud.js',
				'version' => filemtime( CRUD_DIR_PATH . '/assets/js/crud.js' ),
				'deps'    => array( 'jquery' ),
			),
			'enquiry-script'       => array(
				'src'     => CRUD_PLUGIN_ASSET . '/js/enquiry.js',
				'version' => filemtime( CRUD_DIR_PATH . '/assets/js/enquiry.js' ),
				'deps'    => array( 'jquery' ),
			),
		);
		return $frontend_scripts;
	}

		/**
		 * Get all js styles for frontend.
		 *
		 * @return array
		 */
	public function get_frontend_styles() {
		$frontend_style = array(
			'crud-frontend-style' => array(
				'src'     => CRUD_PLUGIN_ASSET . '/css/crud.css',
				'version' => filemtime( CRUD_DIR_PATH . '/assets/css/crud.css' ),
			),
			'enquiry-style'      => array(
				'src'     => CRUD_PLUGIN_ASSET . '/css/enquiry.css',
				'version' => filemtime( CRUD_DIR_PATH . '/assets/css/enquiry.css' ),
			),
		);
		return $frontend_style;
	}

	/**
	 * Register all scripts for the frontend.
	 *
	 * @return void
	 */
	public function crud_enqueue_assets() {
		$scripts = $this->get_frontend_scripts();
		foreach ( $scripts as $handle => $script ) {
			$deps = isset( $script['deps'] ) ? $script['deps'] : false;
			wp_register_script( $handle, $script['src'], $deps, $script['version'], true );
		}

		$styles = $this->get_frontend_styles();
		foreach ( $styles as $handle => $style ) {
			$deps = isset( $style['deps'] ) ? $style['deps'] : false;
			wp_register_style( $handle, $style['src'], $deps, $style['version'] );
		}
		wp_localize_script(
			'enquiry-script',
			'crudTestEnquiry',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'error' => __( 'Something went wrong', 'crud-test' ),
			)
		);
	}


	/**
	 * Get all js scripts for backend.
	 *
	 * @return array
	 */
	public function get_backend_scripts() {
		$backend_scripts = array(
			'crud-backend-scripts' => array(
				'src'     => CRUD_PLUGIN_ASSET . '/js/crud-backend.js',
				'version' => filemtime( CRUD_DIR_PATH . '/assets/js/crud-backend.js' ),
				'deps'    => array( 'jquery' ),
			),
			'crud-admin-scripts' => array(
				'src'     => CRUD_PLUGIN_ASSET . '/js/admin.js',
				'version' => filemtime( CRUD_DIR_PATH . '/assets/js/admin.js' ),
				'deps'    => array( 'jquery', 'wp-util' ),
			),
		);
		return $backend_scripts;
	}

		/**
		 * Get all js styles for backend.
		 *
		 * @return array
		 */
	public function get_backend_styles() {
		$backend_style = array(
			'crud-backend-style' => array(
				'src'     => CRUD_PLUGIN_ASSET . '/css/crud-backend.css',
				'version' => filemtime( CRUD_DIR_PATH . '/assets/css/crud-backend.css' ),
			),
		);
		return $backend_style;
	}

	/**
	 * Register all scripts for the backend.
	 *
	 * @return void
	 */
	public function crud_enqueue_admin_assets() {
		$scripts = $this->get_backend_scripts();
		foreach ( $scripts as $handle => $script ) {
			$deps = isset( $script['deps'] ) ? $script['deps'] : false;
			wp_register_script( $handle, $script['src'], $deps, $script['version'], true );
		}

		$styles = $this->get_backend_styles();
		foreach ( $styles as $handle => $style ) {
			$deps = isset( $style['deps'] ) ? $style['deps'] : false;
			wp_register_style( $handle, $style['src'], $deps, $style['version'], 'all' );
		}

		wp_localize_script(
			'crud-admin-scripts',
			'crudTestAdmin',
			array(
				'nonce'   => wp_create_nonce( 'crud_test_ajax_admin_nonce' ),
				'confirm' => __( 'Are you sure ?', 'crud-test ' ),
				'error'   => __( 'Something went wrong', 'crud-test' ),
			),
		);
	}
}
