<?php
/*
 * Barbu Parisien -  La marque
 * CPT games
 */

/*
 * register post type game
 */
function bplm_add_cpt_game(){

	$games_labels = array(
		'name'               => __( 'Games', 'barbu-parisien'),
		'singular_name'      => __( 'Game', 'barbu-parisien' ),
		'add_new'            => __( 'Add New', 'barbu-parisien' ),
		'add_new_item'       => __( 'Add New Game', 'barbu-parisien' ),
		'edit_item'          => __( 'Edit Game', 'barbu-parisien' ),
		'new_item'           => __( 'New Game', 'barbu-parisien' ),
		'all_items'          => __( 'All Games', 'barbu-parisien' ),
		'view_item'          => __( 'View game', 'barbu-parisien' ),
		'search_items'       => __( 'Search Games', 'barbu-parisien' ),
		'not_found'          => __( 'No games found', 'barbu-parisien' ),
		'not_found_in_trash' => __( 'No games found in trash', 'barbu-parisien' ),
		'menu_name'          => __( 'Games', 'barbu-parisien' ),
	);

	$game_args = array(
		'labels'               => $games_labels,
		'public'               => true,
		'show_ui'              => true,
		'show_in_menu'         => true,
		'query_var'            => true,
		'rewrite'              => array( 'slug' => __('games', 'barbu-parisien' ) ),
		'capability_type'      => 'post',
		'has_archive'          => true,
		'hierarchical'         => false,
		'register_meta_box_cb' => 'bplm_add_game_metabox',
		'menu_position'        => 20,
		'supports'             => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
	);

	register_post_type( 'game', $game_args );

}

add_action('init', 'bplm_add_cpt_game');


/*
 * Add metabox to meals
 */
function bplm_add_game_metabox(){
	add_meta_box(
			'bplm_la_marque_meta',
			__( 'La marque', 'barbu-parisien' ),
			'bplm_la_marque_metabox_content',
			'game',
			'advanced',
			'low'
	);
	add_meta_box(
			'bplm_gamers_meta',
			__( '4 Players', 'barbu-parisien' ),
			'bplm_players_metabox_content',
			'game',
			'side',
			'high'
	);
}


/*
 * Display metabox "La marque"
 */
function bplm_la_marque_metabox_content(){

	wp_nonce_field( plugin_basename( __FILE__ ), 'bplm_la_marque_metabox_nonce' );

	echo 'display la marque';
}


/**
 * Display metabox "4 players"
 */
function bplm_players_metabox_content( $post ){

	$game = get_post_meta( $post->ID, 'games_obj', true );

	// First time we set the default players
	if( ! $game ){
		$game = get_option('bplm_default_gamers');
	}

	$all_users = get_users();


	wp_nonce_field( plugin_basename( __FILE__ ), 'bplm_gamers_metabox_nonce' );

	for( $i = 0; $i < 4; $i++ ){
		?>
		<p>

			<label for="bplm_player_<?php echo $i ?>">
				<?php printf( __('Player n°%d', 'barbu-parisien' ) , ( $i + 1 )); ?>
			</label>
			<select name="bplm_player[<?php echo $i ?>]" id="bplm_player_<?php echo $i ?>">
				<?php
				if( $all_users ){
					foreach( $all_users as $user){

						?>
						<option value="<?php echo $user->user_nicename ?>" <?php selected($user->user_nicename, $game->registered_players[$i], true); ?>>
							<?php echo $user->display_name; ?>
						</option>
						<?php
					}
				}
				?>
			</select>
		</p>
		<?php
	}
}

/**
 * Save the metabox
 *
 * @param int $post_id
 */
function bpml_save_metabox( $post_id ) {

	// verify this came from the our screen and with proper authorization,
	if ( isset( $_POST['bplm_gamers_metabox_nonce'] ) && wp_verify_nonce( $_POST['bplm_gamers_metabox_nonce'], plugin_basename( __FILE__ ) ) ){
		// Check permissions
		if ( current_user_can( 'edit_page', $post_id ) && isset( $_POST['bplm_player'] )){

			$game = get_post_meta( $post_id, 'games_obj', true );

			// If meta already existe
			if( $game ){
				$game->registered_players = $_POST['bplm_player'];

			// Create game
			} else {
				$game = new Game( $_POST['bplm_player'] );
			}

			update_post_meta( $post_id, 'games_obj', $game);
		}
	}
}
add_action( 'save_post', 'bpml_save_metabox' );