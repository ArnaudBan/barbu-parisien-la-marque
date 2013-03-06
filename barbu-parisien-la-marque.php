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


// Include Post 2 post core and scribu framework
require_once plugin_dir_path(__FILE__) . 'includes/load-p2p.php';