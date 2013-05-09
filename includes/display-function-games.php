<?php
/*
 * Barbu Parisien -  La marque
 *
 * Add function to display the forms and "la marque" for games
 *
 */

/**
 * Retrun a string whith the HTML of the games forms
 * 
 * @param  array  $values
 * @return string
 */
function bplm_get_the_games_form( $saved_value = array() ){

	// Add Scripts and Style
	wp_enqueue_script( 'bplm_js' );
	wp_enqueue_style( 'bplm_screen' );

	$registered_player = get_post_meta( get_the_ID(), 'registered_player', true );

	$player_list = '<ul class="tabs">';
	$forms = '';

	foreach ($registered_player as $player_id ) {
		$user = get_user_by('id', $player_id);
		$player_list .= "<li><a href='#tab_". $player_id ."'>$user->display_name</a></li>";
		$forms .= bplm_get_players_forms( $player_id, $registered_player, $saved_value );
	}

	$player_list .= '</ul>';
	$forms = '<div class="games-forms">' . $player_list . $forms . '</div>';

	return $forms;
}

function bplm_get_players_forms( $id, $registered_player = array(), $saved_value = array() ){


	$user = get_user_by('id', $id);

	$form_id = 'player_' . $id;

	$input_name = array(
			'barbu'     => $form_id. '_barbu_input',
			'coeur'     => $form_id. '_coeurs_num',
			'coeur_as'  => $form_id. '_coeur_as',
			'dame'      => $form_id. '_dames_num',
			'levees'    => $form_id. '_levees_num',
			'avant_der' => $form_id. '_avder_input',
			'der'       => $form_id. '_der_input',
			'atout'     => $form_id. '_atout_num',
			'reussite_1'	=> $form_id. '_reussite_1',
			'reussite_2'	=> $form_id. '_reussite_2',
			'reussite_3'	=> $form_id. '_reussite_3',
			'reussite_4'	=> $form_id. '_reussite_4',
											
		);
	// Saved result
	foreach ($input_name as $names ) {
		$values[$names] = isset( $saved_value[$id][$names] ) ? $saved_value[$id][$names] : '';
	}

	// Tabs
	$forms = "
		<div id='tab_". $id ."''>
			<form id='$form_id' method='post'>
				<div class='games-forms'>
					<ul class='tabs'>
						<li><a href='#". $form_id ."_barbu'>Le Barbue</a></li>
						<li><a href='#". $form_id ."_coeurs'>Les Coeurs</a></li>
						<li><a href='#". $form_id ."_dames'>Les Dames</a></li>
						<li><a href='#". $form_id ."_levees'>Les Levées</a></li>
						<li><a href='#". $form_id ."_deux-der'>Les Deux der</a></li>
						<li><a href='#". $form_id ."_atout'>L'Atout</a></li>
						<li><a href='#". $form_id ."_reussite'>La Réussite</a></li>
					</ul>

					<div id='". $form_id ."_barbu'>
						<p>Il faut éviter de ramasser le roi de coeur lorsqu'on remporte une levée, sous peine d'une pénalité de 20 points</p>			
						<p>
							Qui a ramasser le roi de coeur ?
						</p>
						<p>".
							bplm_get_players_radio( $input_name['barbu'], $registered_player, $values[$input_name['barbu']]) 
							."
						</p>	
					</div>

					<div id='". $form_id ."_coeurs'>
						<p>il faut éviter de prendre des Coeurs dans ses levées, sous peine d'une pénalité de 2 points par Coeur et de 6 points pour l'As de Cœur. </p>
						<p>
							Combien chaque joueurs a t'il récupérer de Coeurs (y compris l'as)?
						</p>
						<p>".
							bplm_get_players_numbers( $input_name['coeur'], $registered_player, $values[$input_name['coeur']], 13) 
							."
						</p>
						<p>
							Qui a ramasser l'as coeur ?
						</p>
						<p>".
							bplm_get_players_radio( $input_name['coeur_as'], $registered_player, $values[$input_name['coeur_as']]) 
							."
						</p>
					</div>

					<div id='". $form_id ."_dames'>
						<p>il faut éviter de ramasser des Dames dans ses levées, sous peine d'une pénalité de 6 points par Dame.</p>
						<p>
							Combien chaque joueurs a t'il récupérer de dames ?
						</p>
						<p>".
							bplm_get_players_numbers( $input_name['dame'], $registered_player, $values[$input_name['dame']], 4) 
							."
						</p>
					</div>

					<div id='". $form_id ."_levees'>
						<p>il faut éviter de faire des levées, sous peine d'une pénalité de 2 points par levée.</p>
						<p>
							Combien chaque joueurs a t'il fait de levées ?
						</p>
						<p>".
							bplm_get_players_numbers( $input_name['levees'], $registered_player, $values[$input_name['levees']], 11) 
							."
						</p>
					</div>

					<div id='". $form_id ."_deux-der'>
						<p>il ne faut réaliser ni l'avant-dernière levée, pénalisée de 10 points, ni la dernière levée, pénalisée de 20 points.</p>
						<p>
							Qui a ramassé l'avant dernière levée' ?
						</p>
						<p>".
							bplm_get_players_radio( $input_name['avant_der'], $registered_player, $values[$input_name['avant_der']]) 
							."
						</p>	
						<p>
							Qui a ramassé la dernière levée ?
						</p>
						<p>".
							bplm_get_players_radio( $input_name['der'], $registered_player, $values[$input_name['der']] ) 
							."
						</p>	
					</div>

					<div id='". $form_id ."_atout'>
						<p>il faut réaliser le plus de levées possible.	Chaque levée réalisée rapporte 5 points</p>
						<p>il faut éviter de faire des levées, sous peine d'une pénalité de 2 points par levée.</p>
						<p>
							Combien chaque joueurs a t'il fait de levées ?
						</p>
						<p>".
							bplm_get_players_numbers( $input_name['atout'], $registered_player, $values[$input_name['atout']], 13) 
							."
						</p>
					</div>

					<div id='". $form_id ."_reussite'>
						<p>Le joueur qui se débarrasse le premier de toutes ses cartes marque 45 points, le deuxième 20 points, le troisième 10 points et le dernier se voit infliger une pénalité de 10 points.</p>
						<p>
							Qui a fini en premier ? <br/>".
							bplm_get_players_radio( $input_name['reussite_1'], $registered_player, $values[$input_name['reussite_1']] ) .
							"
						</p>
						<p>
							Qui a fini en second ? <br/>".
							bplm_get_players_radio( $input_name['reussite_2'], $registered_player, $values[$input_name['reussite_2']] ) .
							"
						</p>
						<p>
							Qui a fini en troisième ? <br/>".
							bplm_get_players_radio( $input_name['reussite_3'], $registered_player, $values[$input_name['reussite_3']] ) .
							"
						</p>
						<p>
							Qui a fini en dernier ? <br/>".
							bplm_get_players_radio( $input_name['reussite_4'], $registered_player, $values[$input_name['reussite_4']] ) .
							"
						</p>
					</div>
				</div>
				<input type='hidden' name='player_id' value='$id'>
				<input type='submit' value='Valider $user->display_name'>
			</form>
		</div>
		";

		return $forms;
}

/**
 * Return the 4 players in a radio input
 * 
 * @param  string  $name               the name of the input. Use also for the id
 * @param  array   $registered_players array of 4 id number of 4 registered users
 * @param  integer $selected_player    the id of the players that must be selected
 * @return string                      
 */
function bplm_get_players_radio( $name, $registered_players = array(), $selected_player = -1){

	if( empty( $registered_players) ){
		$registered_players = get_post_meta( get_the_ID(), 'registered_player', true );
	}

	$checkbox = '';
	foreach ($registered_players as $player ) {
		$user = get_user_by('id', $player);
		$checkbox .= "<label for='". $name. "-". $player ."'>$user->display_name</label>";
		$checkbox .= "<input id='". $name. "-". $player ."' name='$name' value='$player' type='radio' ". checked( $player, $selected_player, false ) ." />";
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
function bplm_get_players_numbers( $name, $registered_players = array(), $values, $max = 54 ){

	if( empty( $registered_players) ){
		$registered_players = get_post_meta( get_the_ID(), 'registered_player', true );
	}

	$numbers_input = '';
	foreach ($registered_players as $player ) {

		$value = isset( $values[$player] ) ? $values[$player] : '';

		$user = get_user_by('id', $player);
		$numbers_input .= "<label for='". $name. "-". $player ."'>$user->display_name</label>";
		$numbers_input .= '<input id="'. $name. '-'. $player .'" name="' . $name.'[' .$player .']"';
		$numbers_input .= ' type="number" max="'. $max . '" min="0" step="1" value="'.$value.'"/>';
	}

	return $numbers_input;

}