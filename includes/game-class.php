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

		if( $id == -1 ){
			$id = get_the_ID();
		}
		
		$this->la_marque = array_merge( $this->la_marque, $request);

		update_post_meta( $id, 'games_obj', $this );

		return $this;
	}


	public function get_players_radio( $slug, $manche ){

		$registered_players = $this->registered_players;
		$selected_player = isset( $this->la_marque[$slug][$manche] ) ? $this->la_marque[$slug][$manche] : false;

		$checkbox = '';
		foreach ($registered_players as $player ) {
			$user = get_user_by('slug', $player);
			$checkbox .= "<label for='". $slug. "-". $player. "-". $manche ."'>$user->display_name</label>";
			$checkbox .= "<input id='". $slug. "-". $player. "-". $manche ."' name='". $slug. "[". $manche ."]' value='$player' type='radio' ". checked( $player, $selected_player, false ) ." />";
		}

		return $checkbox;
	}




	/**
	 * [bplm_get_players_numbers description]
	 * @param  string  $name               [description]
	 * @param  array   $registered_players [description]
	 * @param  array   $values             [description]
	 * @param  integer $max                [description]
	 * @return string                      [description]
	 */
	public function get_players_numbers( $slug, $manche, $max = 13 ){

		$numbers_input = '';
		foreach ( $this->registered_players as $player ) {

			$value = isset( $this->la_marque[$slug][$manche][$player] ) ? $this->la_marque[$slug][$manche][$player] : false;

			$user = get_user_by('slug', $player);
			$numbers_input .= "<label for='". $slug. "-". $player. "-". $manche ."'>$user->display_name</label>";
			$numbers_input .= '<input id="'. $slug. '-'. $player. '-'. $manche .'" name="' . $slug.'['. $manche .'][' .$player .']"';
			$numbers_input .= ' type="number" max="'. $max . '" min="0" step="1" value="'.$value.'"/>';
		}

		return $numbers_input;

	}



	public function get_la_marque( $player_slug ){

		$coups = array(
				'barbue'     => 'Le Barbue',
				'coeur'      => 'Les Coeurs',
				'dame'       => 'Les Dames',
				'levees'     => 'Les Levées',
				'der'        => 'Les Deux der',
				'atout'      => 'L\'Atoute',
				'reussite_1' => 'La Réussite',
				'total'      => 'Total',
			);

		$tab = '<table>';

		$tab .= '<thead>';
		$tab .= '<tr><th></th>';

		foreach ($this->registered_players as $player) {
			$user = get_user_by('slug', $player);
			$tab .= "<th>$user->display_name</th>";
		}
		$tab .= "<th>Total</th>";

		$tab .= '</tr>';
		$tab .= '</thead>';

		$tab .= '<tdody>';


		foreach ($coups as $key => $coup ) {

			$tab .= '<tr>';
			$tab .= "<td>$coup</td>";
			
			$total_ligne = '-';
			
			foreach ( $this->registered_players as $player ) {

				$score = '-';

				if( isset( $this->la_marque[$player_slug][$key] ) ){

					switch ( $key ) {
						case 'barbue':
							if( $this->la_marque[$player_slug][$key] == $player ){
								$score = -20;
							} else {
								$score = 0;
							}
							break;

						case 'coeur':
		
							$score = 0;
							if( isset( $this->la_marque[$player_slug][$key][$player] ) ){
								$score = $this->la_marque[$player_slug][$key][$player] * -2;
							}
							
							if( $this->la_marque[$player_slug]['coeur_as'] == $player ){
								$score -= 6;
							}

							break;

						case 'dame':
		
							$score = 0;
							if( isset( $this->la_marque[$player_slug][$key][$player] ) ){
								$score = $this->la_marque[$player_slug][$key][$player] * -6;
							}
							break;

						case 'levees':
		
							$score = 0;
							if( isset( $this->la_marque[$player_slug][$key][$player] ) ){
								$score = $this->la_marque[$player_slug][$key][$player] * -2;
							}
							break;

						case 'der':
		
							$score = 0;
							if( $this->la_marque[$player_slug][$key] == $player ){
								$score -= 20;
							}
							if( $this->la_marque[$player_slug]['avant_der'] == $player ){
								$score -= 10;
							}
							break;

						case 'atout':
		
							$score = 0;
							if( isset( $this->la_marque[$player_slug][$key][$player] ) ){
								$score = $this->la_marque[$player_slug][$key][$player] * 5;
							}
							break;

						case 'reussite_1':
							$points = array( 45, 20, 10, -10 );
							$key_overwrite = 'reussite';

							foreach ($points as $i => $value) {
								if( $this->la_marque[$player_slug][$key_overwrite .'_' . ( $i + 1 )] == $player ){
									$score = $value;
								}
							}
							break;

						case 'total':
							
							$score = $this->la_marque[$player_slug][$key][$player];
							break;
						
						default:
							break;
					}

					$total_ligne += intval( $score );


					if( $key != 'total' ){ // Pour ne pas ajouter mulitplier par deux le total

						if( isset( $this->la_marque[$player_slug]['total'][$player] ) )
							$this->la_marque[$player_slug]['total'][$player] += intval( $score );
						else 
							$this->la_marque[$player_slug]['total'][$player] = intval( $score );
					}

				}  
				$tab .= "<td>$score</td>";
			}		
			$tab .= "<td>$total_ligne</td>";
			$tab .=	'</tr>';

		}
		$tab .= '</tdody>';
		$tab .= '</table>';

		update_post_meta( get_the_ID() , 'games_obj', $this );

		return $tab;

	}

	public function get_total(){

		$tab = '<table>';

		$tab .= '<thead>';
		$tab .= '<tr><th></th>';

		foreach ($this->registered_players as $player) {
			$user = get_user_by('slug', $player);
			$tab .= "<th>$user->display_name</th>";
		}
		$tab .= '</tr>';
		$tab .= '</thead>';

		$tab .= '<tbody>';
		$tab .= '<tr>';
		$tab .= '<td>Totaux</td>';
		foreach ($this->registered_players as $player) {
			$total = 0;
			foreach ($this->la_marque as $manche ) {
				if( isset( $manche['total'][$player] ) )
					$total += $manche['total'][$player];
			}
			$tab .= "<td>$total</td>";
		}
		$tab .= '</tr>';
		$tab .= '</tbody>';
		$tab .= '</table>';

		//$tab = 'yes';

		return $tab;

	}

}