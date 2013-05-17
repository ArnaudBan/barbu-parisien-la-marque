<?php
/**
 * Barbu Parisien -  La marque
 *
 * Add shortcode
 */


/**
 * Shortcode to add a new game
 * 
 * @return string from to creats new game
 */
function bpml_creat_game_form(){

	// Only a logged user can creat a game
	if( ! is_user_logged_in() ){
		return '<p>' . __('You have to be logged in to create a new game', 'barbu-parisien') . '</p>';
	}

	if( isset( $_REQUEST['bplm_player'] ) 
			&& wp_verify_nonce( $_REQUEST['bplm_create_game_nonce'], plugin_basename( __FILE__ ) ) ){

			$game_form = '';
			$current_user = wp_get_current_user();

			$new_game_args = array(
					'post_type' 	=> 'game',
					'post_title' 	=> date( get_option( 'date_format' ) ) . ' - ' . $current_user->display_name,
					'post_status' => 'publish',
				);
			$game_id = wp_insert_post( $new_game_args );

			if( $game_id ){
				$game = new Game( $_REQUEST['bplm_player'] );
				update_post_meta( $game_id, 'games_obj', $game);
				$game_form = '<p><a href="'. get_permalink( $game_id ) .'">' . __('Game created, start ! ', 'barbu-parisien') . '</a></p>';
			} else {
				$game_form ='<p>' . __('we could not create a new game', 'barbu-parisien') . '</p>';

			}

	} else {

		$default_players = get_option('bplm_default_gamers');
		$all_users = get_users();

		$game_form = '<form method="post">';
		$game_form .= '<p>';
		

		$game_form .= wp_nonce_field( plugin_basename( __FILE__ ), 'bplm_create_game_nonce', true, false );

		foreach ($default_players as $i => $player) {
			
			$game_form .= '<select name="bplm_player[' . $i . ']" >';

			if( $all_users ){
				foreach( $all_users as $user){
					$game_form .= "<option value='$user->user_nicename' ";
					$game_form .= selected($user->user_nicename, $default_players[$i], false);
					$game_form .= ' >';
					$game_form .= $user->display_name; 
					$game_form .= '</option>';
				}
			}
			$game_form .= '</select>';
		}

		$game_form .= '</p>';

		$game_form .= '<p><input type="submit" value="' . __('Add New') .'" /></p>';

		$game_form .= '</form>';
	}


 	return $game_form;
}
add_shortcode( 'create-game', 'bpml_creat_game_form' );