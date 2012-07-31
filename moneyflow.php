<?php
/*
Plugin Name: Money Flow
Plugin URI: https://github.com/ashfame/Moneyflow
Description: Take notes of how much money I need to give/take from others
Author: Ashfame
Author URI: http://blog.ashfame.com/
Version: 0.1
License: GPLv2
*/

if ( is_admin() )
	require plugin_dir_path( __FILE__ ).'admin.php';