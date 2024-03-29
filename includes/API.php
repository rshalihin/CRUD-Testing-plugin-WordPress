<?php

namespace MyCrud\Testing;

/**
 * API class handling.
 */
class API {
    /**
     * API constructor.
     */
    public function __construct() {
        add_action( 'rest_api_init', array( $this, 'register_api' ) );
    }
    
    /**
     * Register API routes.
     */
    public function register_api() {
        $crud_team = new API\Information();
        $crud_team->register_routes();
    }
}