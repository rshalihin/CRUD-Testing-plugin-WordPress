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
				'version' => filemtime( CRUD_DIR_PATH . '/js/crud.js' ),
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
				'version' => filemtime( CRUD_DIR_PATH . '/css/crud.css' ),
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
			wp_register_script( $handle, $style['src'], $deps, $style['version'], 'all' );
		}
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
				'version' => filemtime( CRUD_DIR_PATH . '/js/crud-backend.js' ),
				'deps'    => array( 'jquery' ),
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
				'version' => filemtime( CRUD_DIR_PATH . '/css/crud-backend.css' ),
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
			wp_register_script( $handle, $style['src'], $deps, $style['version'], 'all' );
		}
	}
}
