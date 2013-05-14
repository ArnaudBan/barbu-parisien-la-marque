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
function bplm_get_the_games_form( $game ){

	// Add Scripts and Style
	wp_enqueue_script( 'bplm_js' );
	wp_enqueue_style( 'bplm_screen' );

	$registered_player = $game->registered_players;

	$player_list = '<ul class="tabs">';
	$forms = '';

	foreach ($registered_player as $player_nicename ) {

		$user = get_user_by('slug', $player_nicename);

		$player_list .= "<li><a href='#tab_". $player_nicename ."'>$user->display_name</a></li>";
		$forms .= bplm_get_players_forms( $player_nicename, $game );

	}

	$player_list .= '</ul>';
	$forms = '<div class="games-forms">' . $player_list . $forms . '</div>';
	$forms .= '<div class="total-partie">'. $game->get_total() . '</div>';

	return $forms;
}

function bplm_get_players_forms( $slug, $game ){


	$user = get_user_by('slug', $slug);

	$form_id = $slug;


	// Tabs
	$forms = "
		<div id='tab_". $slug ."''>
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
							$game->get_players_radio( $slug, 'barbue' ) 
							."
						</p>	
					</div>

					<div id='". $form_id ."_coeurs'>
						<p>il faut éviter de prendre des Coeurs dans ses levées, sous peine d'une pénalité de 2 points par Coeur et de 6 points pour l'As de Cœur. </p>
						<p>
							Combien chaque joueurs a t'il récupérer de Coeurs (y compris l'as)?
						</p>
						<p>".
							$game->get_players_numbers( $slug, 'coeur' ) 
							."
						</p>
						<p>
							Qui a ramasser l'as coeur ?
						</p>
						<p>".
							$game->get_players_radio( $slug, 'coeur_as' )
							."
						</p>
					</div>

					<div id='". $form_id ."_dames'>
						<p>il faut éviter de ramasser des Dames dans ses levées, sous peine d'une pénalité de 6 points par Dame.</p>
						<p>
							Combien chaque joueurs a t'il récupérer de dames ?
						</p>
						<p>".
							$game->get_players_numbers( $slug, 'dame', 4) 
							."
						</p>
					</div>

					<div id='". $form_id ."_levees'>
						<p>il faut éviter de faire des levées, sous peine d'une pénalité de 2 points par levée.</p>
						<p>
							Combien chaque joueurs a t'il fait de levées ?
						</p>
						<p>".
							$game->get_players_numbers( $slug, 'levees', 11) 
							."
						</p>
					</div>

					<div id='". $form_id ."_deux-der'>
						<p>il ne faut réaliser ni l'avant-dernière levée, pénalisée de 10 points, ni la dernière levée, pénalisée de 20 points.</p>
						<p>
							Qui a ramassé l'avant dernière levée' ?
						</p>
						<p>".
							$game->get_players_radio( $slug, 'avant_der' ) 
							."
						</p>	
						<p>
							Qui a ramassé la dernière levée ?
						</p>
						<p>".
							$game->get_players_radio( $slug, 'der' ) 
							."
						</p>	
					</div>

					<div id='". $form_id ."_atout'>
						<p>il faut réaliser le plus de levées possible.	Chaque levée réalisée rapporte 5 points</p>
						<p>
							Combien chaque joueurs a t'il fait de levées ?
						</p>
						<p>".
							$game->get_players_numbers( $slug, 'atout', 13) 
							."
						</p>
					</div>

					<div id='". $form_id ."_reussite'>
						<p>Le joueur qui se débarrasse le premier de toutes ses cartes marque 45 points, le deuxième 20 points, le troisième 10 points et le dernier se voit infliger une pénalité de 10 points.</p>
						<p>
							Qui a fini en premier ? <br/>".
							$game->get_players_radio( $slug, 'reussite_1' ) .
							"
						</p>
						<p>
							Qui a fini en second ? <br/>".
							$game->get_players_radio( $slug,'reussite_2' ) .
							"
						</p>
						<p>
							Qui a fini en troisième ? <br/>".
							$game->get_players_radio( $slug,'reussite_3' ) .
							"
						</p>
						<p>
							Qui a fini en dernier ? <br/>".
							$game->get_players_radio( $slug,'reussite_4' ) .
							"
						</p>
					</div>
				</div>
				<input type='submit' value='Valider $user->display_name'>
			</form>
			<div class='la-marque'>". $game->get_la_marque( $slug ) . "</div>
		</div>
		";

		return $forms;
}
