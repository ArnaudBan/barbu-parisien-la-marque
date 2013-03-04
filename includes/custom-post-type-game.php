<?php
/*
 * Barbu Parisien -  La marque
 * CPT games
 */

/*
 * register post type meals
 */
function bplm_add_cpt_meals(){

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

add_action('init', 'bplm_add_cpt_meals');


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
}


/*
 * Display metabox "La marque"
 */
function bplm_la_marque_metabox_content(){

	echo 'display la marque';
}