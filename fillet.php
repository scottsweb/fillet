<?php

/*
Plugin Name: Fillet
Plugin URI: https://bitbucket.com/cftp/fillet
Description: Safely embed iFrames in the WordPress text editor.
Version: 1.0
Author: Scott Evans (Code For The People)
Author URI: http://codeforthepeople.com
Text Domain: fillet
Domain Path: /assets/languages/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Copyright © 2014 Code for the People ltd

                _____________
               /      ____   \
         _____/       \   \   \
        /\    \        \___\   \
       /  \    \                \
      /   /    /          _______\
     /   /    /          \       /
    /   /    /            \     /
    \   \    \ _____    ___\   /
     \   \    /\    \  /       \
      \   \  /  \____\/    _____\
       \   \/        /    /    / \
        \           /____/    /___\
         \                        /
          \______________________/


This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

*/

class fillet {

	/**
	 * fillet
	 *
	 * @author Scott Evans
	 */
	function __construct() {

		add_shortcode( 'iframe', array( $this, 'fillet' ) );
		add_action( 'admin_init', array( $this, 'init' ) );

		if ( ! is_admin() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'fillet_js' ) );
		}
	}

	/**
	 * fillet
	 *
	 * Shortcode
	 *
	 * @param  string $atts
	 * @param  string $content
	 *
	 * @author Scott Evans
	 * @return string
	 */
	function fillet( $atts, $content = '' ) {

		global $wpdb, $post;

		extract( shortcode_atts(
			array(
				'url'    => '',
				'width'  => '',
				'height' => '',
			),
			$atts, 'fillet' ) );

		// check we have a url (return if not)
		if ( $url == '' ) {
			return;
		}

		// set url scheme and validate URL
		$url = set_url_scheme( esc_url( $url ) );

		// classes with filter
		$service = str_replace( array(
			'www.',
			'.com',
			'.net',
			'.co.uk',
			'.org',
		), '', parse_url( $url, PHP_URL_HOST ) );
		$classes = apply_filters( 'fillet_iframe_class', 'i-container fillet-embed ' . $service );

		// filter for custom attributes
		$attributes = apply_filters( 'fillet_iframe_attributes', 'allowfullscreen' );

		$ret = '<figure class="' . $classes . '">';
		$ret .= '<iframe src="' . $url . '" frameborder="0" ' . $attributes . ' ';

		if ( $width != '' ) {
			$ret .= 'width="' . $width . '"';
		}

		if ( $height != '' ) {
			$ret .= 'height="' . $height . '"';
		}

		$ret .= '></iframe>';
		$ret .= '</figure>';

		return $ret;
	}

	/**
	 * init
	 *
	 * Hook into admin init - add tinymce button
	 *
	 * @author Scott Evans
	 * @return void
	 */
	function init() {

		# hooks
		add_action( 'admin_enqueue_scripts', array( $this, 'fillet_mce_css' ) );
		add_action( 'admin_head', array( $this, 'fillet_mce' ) );
	}

	/**
	 * fillet_js
	 *
	 * JS to calculate aspect ratio and resize images proportionally.
	 * Dependencies: jQuery and jQuery.doTimeout plugin.
	 * Scripts need to be in the footer so they come after the content.
	 *
	 * @author William Turrell
	 * @return void
	 */
	function fillet_js() {
		wp_register_script( 'jquery-dotimeout', plugins_url( '/assets/js/jquery.ba-dotimeout.js', __FILE__ ), array( 'jquery' ), 1, true );
		wp_register_script( 'fillet-js', plugins_url( '/assets/js/fillet.js', __FILE__ ), array(
			'jquery',
			'jquery-dotimeout',
		), 1, true );
		wp_enqueue_script( 'jquery-dotimeout' );
		wp_enqueue_script( 'fillet-js' );
	}

	/**
	 * fillet_mce_css
	 *
	 * Style the editor button
	 *
	 * @author Scott Evans
	 * @return void
	 */
	function fillet_mce_css() {
		wp_enqueue_style( 'fillet', plugins_url( '/assets/css/fillet.css', __FILE__ ), 'dashicons', 1, 'screen' );
	}

	/**
	 * fillet_mce
	 *
	 * Load the required tools for adding button, check conditions first
	 *
	 * @author Scott Evans
	 * @return void
	 */
	function fillet_mce() {

		// check user permissions
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		// check if WYSIWYG is enabled
		if ( 'true' == get_user_option( 'rich_editing' ) ) {

			# filters
			add_filter( 'mce_buttons', array( $this, 'fillet_mce_button' ) );
			add_filter( 'mce_external_plugins', array( $this, 'fillet_mce_plugin' ) );
		}
	}

	/**
	 * fillet_mce_button
	 *
	 * Push a new button on to TinyMCE buttons array
	 *
	 * @author Scott Evans
	 *
	 * @param  array $buttons
	 *
	 * @return array
	 */
	function fillet_mce_button( $buttons ) {
		array_push( $buttons, 'fillet' );

		return $buttons;
	}

	/**
	 * fillet_mce_plugin
	 *
	 * Add fillet TinyMCE plugin JS
	 *
	 * @author Scott Evans
	 *
	 * @param  array $plugins
	 *
	 * @return array
	 */
	function fillet_mce_plugin( $plugins ) {
		$plugins['fillet'] = plugins_url( '/assets/js/fillet-mce.js', __FILE__ );

		return $plugins;
	}
}

global $fillet;
$fillet = new fillet();
