<?php
/**
 * Barbu Parisien -  La marque
 *
 * Function to saved, calculate and get games meta
 */


function update_games_meta( $request = array(), $id = -1 ){

	//Wee need a player id
	if( ! isset( $request['player_id'] ) )
		return false;

	if( $id == -1 ){
		$id = get_the_ID();
	}

	$games_marque = get_post_meta( $id, 'games_marque', true );

	if( ! $games_marque ){
		$games_marque = array();
	}

	foreach ($request as $coup => $result) {

		if( ! empty( $result ) && $coup != 'player_id')
			$games_marque[ $request['player_id'] ][$coup] = $result;
	}

	$has_update = update_post_meta( $id, 'games_marque', $games_marque );

	return $has_update ? $games_marque : $has_update;
}