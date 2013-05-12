<?php
/**
 * Barbu Parisien -  La marque
 *
 * Class for the game
 */


/**
 * 
 */
Class Game {

	var $registered_players;
	var $la_marque;

	/**
	 * Creat a new game
	 * 
	 * @param array $registered_players   array of register user id 
	 * @param array  $la_marque          
	 */
	public function __construct( $registered_players, $la_marque = array() ){

		$this->registered_players = $registered_players;
		$this->la_marque = $la_marque;

	}

	/**
	 * Set the new value that the user put in the game form
	 * 
	 * @param array   $request array of values from the form
	 * @param integer $id      id of the post ( game )
	 */
	public function set_la_marque( $request = array(), $id = -1 ){

		//Wee need a player id
		if( ! isset( $request['player_id'] ) )
			return false;

		if( $id == -1 ){
			$id = get_the_ID();
		}


		foreach ($request as $coup => $result) {

			if( ! empty( $result ) && $coup != 'player_id')
				$games_marque[ $request['player_id'] ][$coup] = $result;
		}

		$this->la_marque = $games_marque;

		update_post_meta( $id, 'games_obj', $this );

		return $this;
	}

}