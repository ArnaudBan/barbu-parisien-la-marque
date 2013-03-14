<?php
/*
Plugin Name: Barbu Parisien -  La marque
Plugin URI: http://arnaudban.me
Description: Plugin WordPress qui comptes les points pour le jeu de carte du barbu parisien
Version: 1.0
Author: ArnaudBan
Author URI: http://arnaudban.me
License: GPL2

Copyright 2013  ArnaudBan  (email : arnaud.banvillet@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


// Add a CPT Games
require_once plugin_dir_path(__FILE__) . 'includes/custom-post-type-game.php';


// Activation hook
function bplm_activation(){

	// The 4 default gamers ---------------
	// Verifie if the option already exist
	$option_default_user = get_option('bplm_default_gamers');

	if( $option_default_user ){

		// If the option exist verifie if the default gamers steel exist
		foreach( $option_default_user as $key => $user_id ){

			$steel_exist = new WP_User( $user_id );

			if( ! $steel_exist->exists() ){

				$steel_exist->set_role('subscriber');
				$steel_exist->user_login = 'joueur_'. $key;
				$steel_exist->user_pass = 'joueur_'. $key;

				// Creat user
				$user_id = wp_insert_user( $steel_exist );

				$option_default_user[$key] = $user_id;
			}
		}


	// If the option doenst existe we creat the 4 default user and the option
	} else {

		for( $i = 0; $i < 4; $i++ ){
			$args = array(
				'user_pass'  => 'joueur_' . $i,
				'user_login' => 'joueur_' . $i,
				'role'       => 'subscriber',
			);
			$user_id = wp_insert_user( $args );
			$option_default_user[] = $user_id;
		}

	}

	update_option('bplm_default_gamers', $option_default_user);

}
register_activation_hook( __FILE__ , 'bplm_activation' );
