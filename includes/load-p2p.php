<?php

/*
 * Load Post 2 posts connection
 *
 * Include posts 2 posts core and Scribu framework
 * Create user to game connection
 */
require_once plugin_dir_path(__FILE__) . 'scb-framework/load.php';

scb_init( 'bplm_add_connections_init' );

function bplm_add_connections_init() {
	add_action( 'plugins_loaded', 'bplm_load_p2p_core', 20 );
	add_action( 'init', 'bplm_add_posts_connection' );
}

function bplm_load_p2p_core() {
	if ( function_exists( 'p2p_register_connection_type' ) )
		return;

	// Define text domaine
	define( 'P2P_TEXTDOMAIN', 'barbu-parisien' );

	require_once plugin_dir_path(__FILE__) . 'p2p-core/init.php';

	// TODO: can't use activation hook
	add_action( 'admin_init', array( 'P2P_Storage', 'install' ) );
}

// Add connections
function bplm_add_posts_connection() {
	p2p_register_connection_type( array(
		'name'     => 'users_to_games',
		'from'     => 'user',
		'to'       => 'game',
		'sortable' => true,
		)
	);
}
