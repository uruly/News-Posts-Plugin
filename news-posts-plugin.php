<?php
/**
 * @package News_Posts_Plugin
 * @version 1.0
 */

/*
Plugin Name: News_Posts_Plugin
Description: ニュース用の投稿を追加する
Version: 1.0
Author: Reo
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NSPP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

//ファイルの読み込み
require_once( NSPP_PLUGIN_DIR . '/class.news-posts.php' );
