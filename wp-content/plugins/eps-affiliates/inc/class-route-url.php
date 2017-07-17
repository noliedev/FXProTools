<?php
class Afl_route_url {
	public function __construct(){
		add_action( 'wp_router_generate_routes', 'afl_route_dashboard_menus', 20 );
	}

	public function afl_route_dashboard_menus( $router ) { 
    $route_args = array(
      'path' => '^new-demo-route',
      'query_vars' => array( ),
      'page_callback' => 'afl_route_dashboard_menus_callback',
      'page_arguments' => array( ),
      'access_callback' => true,
      'title' => __( 'Demo Route' ),
      'template' => array(
                  'page.php',
              dirname( __FILE__ ) . '/page.php'
      )
    );
    $router->add_route( 'demo-route-id', $route_args );
	}

	function afl_route_dashboard_menus_callback( ) {
	  return "Congrats! Your demo callback is fully functional. Now make it do something fancy";
	}
}
$obj = new Afl_route_url;