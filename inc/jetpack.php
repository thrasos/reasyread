<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package easyread
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function easyread_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'type'      => 'click',
		'container' => 'main',
		'footer'    => 'page',
	) );
}
add_action( 'after_setup_theme', 'easyread_jetpack_setup' );
