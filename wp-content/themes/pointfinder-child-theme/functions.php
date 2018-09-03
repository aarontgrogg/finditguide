<?php
/*
Theme Name: PointFinder Child Theme
Description: PointFinder Child Theme for your Customizations
Author: Aaron T. Grogg, AdvantiPro GmbH
Template: twentyseventeen
Text Domain:  pointfinder-child
Version: 1.0
*/


$ap_parent_style = 'twentyseventeen';

/**
 * 	Enqueue Parent & Child CSS
 */
	function ap_kill_default_css() {
		wp_dequeue_style( $ap_parent_style.'-style-css' );
	}
	add_action( 'wp_print_styles', 'ap_kill_default_css', 999 );
	if ( ! function_exists( 'ap_enqueue_theme_styles' ) ) :
		function ap_enqueue_theme_styles() {
		    // parent theme Template
		    // enqueue parent CSS
		    wp_enqueue_style( $ap_parent_style, get_template_directory_uri() . '/style.css' );
		    // enqueue child CSS, if any
		    wp_enqueue_style( 'pointfinder-child', get_stylesheet_directory_uri() . '/style.css', array( $ap_parent_style ) );
		}
	endif;
	add_action( 'wp_enqueue_scripts', 'ap_enqueue_theme_styles' );

/**
 * Enqueue custom JS
 */
    if ( ! function_exists( 'ap_enqueue_custom_js' )) :
		function ap_enqueue_custom_js() {
			wp_register_script('ap_custom_js', get_stylesheet_directory_uri() . '/scripts.js', array('jquery'), '1.1', true);
			wp_enqueue_script('ap_custom_js');
		}
    endif; // ap_enqueue_custom_js
	//add_action( 'wp_enqueue_scripts', 'ap_enqueue_custom_js' );

/**
 * 	Remove version info from head and feeds
 *	http://digwp.com/2009/07/remove-wordpress-version-number/
 */
	if ( ! function_exists( 'ap_complete_version_removal' ) ) :
		function ap_complete_version_removal() {
			return '';
		}
	endif;
	add_filter( 'the_generator', 'ap_complete_version_removal' );

/**
 * 	Remove ?ver=X.X.X from CSS and JS URLs
 *	https://wordpress.org/support/topic/get-rid-of-ver-on-the-end-of-cssjs-files
 */
	if ( ! function_exists( 'ap_remove_cssjs_ver' ) ) :
		function ap_remove_cssjs_ver( $src ) {
			if( strpos( $src, '?ver=' ) ) {
				$src = remove_query_arg( 'ver', $src );
			}
			return $src;
		}
	endif;
	add_filter( 'style_loader_src', 'ap_remove_cssjs_ver', 10, 2 );
	add_filter( 'script_loader_src', 'ap_remove_cssjs_ver', 10, 2 );

/**
 * 	Remove the damned emjoi crap that was added to WP Core as of 4.2
 *	http://wordpress.stackexchange.com/questions/185577/disable-emojicons-introduced-with-wp-4-2
 */
	if ( ! function_exists( 'ap_disable_wp_emojicons' ) ) :
		function ap_disable_wp_emojicons() {
			// all actions related to emojis
			remove_action( 'admin_print_styles', 'print_emoji_styles' );
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
			remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
			remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
			remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
			// filter to remove TinyMCE emojis
			add_filter( 'tiny_mce_plugins', 'ap_disable_emojicons_tinymce' );
		}
	endif;
	// And just in case you use tiny_mce...
	if ( ! function_exists( 'ap_disable_emojicons_tinymce' ) ) :
		function ap_disable_emojicons_tinymce( $plugins ) {
			if ( is_array( $plugins ) ) {
				return array_diff( $plugins, array( 'wpemoji' ) );
			} else {
				return array();
			}
		}
	endif;
	add_action( 'init', 'ap_disable_wp_emojicons' );

/**
 * 	Add custom colors to TinyMCE Editor text color panel
 * 	http://mediendesign-quer.com/wordpress-tinymce-farbpalette-um-individuelle-farben-erweitern-so-gehts/
 */
	if ( ! function_exists( 'ap_tinymce_custom_colours' ) ) :
		function ap_tinymce_custom_colours($init) {
			// vorgegebene Farben
			$default_colours = '"000000", "Black",
								"993300", "Burnt orange",
								"333300", "Dark olive",
								"003300", "Dark green",
								"003366", "Dark azure",
								"000080", "Navy Blue",
								"333399", "Indigo",
								"333333", "Very dark gray",
								"800000", "Maroon",
								"FF6600", "Orange",
								"808000", "Olive",
								"008000", "Green",
								"008080", "Teal",
								"0000FF", "Blue",
								"666699", "Grayish blue",
								"808080", "Gray",
								"FF0000", "Red",
								"FF9900", "Amber",
								"99CC00", "Yellow green",
								"339966", "Sea green",
								"33CCCC", "Turquoise",
								"3366FF", "Royal blue",
								"800080", "Purple",
								"999999", "Medium gray",
								"FF00FF", "Magenta",
								"FFCC00", "Gold",
								"FFFF00", "Yellow",
								"00FF00", "Lime",
								"00FFFF", "Aqua",
								"00CCFF", "Sky blue",
								"993366", "Red violet",
								"FFFFFF", "White",
								"FF99CC", "Pink",
								"FFCC99", "Peach",
								"FFFF99", "Light yellow",
								"CCFFCC", "Pale green",
								"CCFFFF", "Pale cyan",
								"99CCFF", "Light sky blue",
								"CC99FF", "Plum"';
			// individuelle Farben
			$custom_colours =  '"f82e32", "Apex Red",
								"262626", "Apex Black",
								"f4f7fc", "Hellblau",
								"BFCDDB", "Hellblau-2",
								"A0A0A0", "Hellgrau-2",
								"C8C8C8", "Hellgrau-3",
								"000000", "Schwarz"';
			// vorgegebene + individuelle Farbpalette
			$init['textcolor_map'] = '['.$default_colours.','.$custom_colours.']';

			// 6. Reihe aktivieren für individuelle Farbpalette
			$init['textcolor_rows'] = 6;

			return $init;
		}

	endif; // ap_tinymce_custom_colours
	add_filter('tiny_mce_before_init', 'ap_tinymce_custom_colours');

/**
 * 	Add thumbnail support
 */
	if ( function_exists( 'add_theme_support' ) ) :
		add_theme_support( 'post-thumbnails' );
	endif;

/**
 *	Get the Featured Image credit
 *	https://cornershopcreative.com/getting-featured-image-information-out-of-wordpress/
 */
	if ( ! function_exists( 'ap_get_thumbnail_field' ) ) :
		function ap_get_thumbnail_field( $field = 'caption', $post_id = NULL, $suppress_filters = FALSE ) {
			if ( $post_id === NULL ) {
				global $post;
				$post_id = $post->ID;
			}
			$attachment_id = get_post_thumbnail_id( $post_id );
			if ( $attachment_id ) {
				$data = wp_prepare_attachment_for_js( $attachment_id );
				// We're getting a non-standard field
				if ( !array_key_exists($field, $data) ) {
					$meta = get_post_meta( $data['id'], $field );
					if ( !count($meta) ) return NULL; // field wasn't found
					$field = ( count($meta) == 1 ) ? maybe_unserialize( $meta ) : $meta ;
				}
				$field = $data[$field];
				if ( $suppress_filters || !is_string($field) ) return $field;
				return apply_filters('get_thumbnail_field', $field);
			}
			return NULL;
		}
	endif; // ap_get_thumbnail_field

/**
 * 	Add caption to Featured Image
 * 	http://www.sourcexpress.com/how-to-add-caption-to-the-featured-images-in-wordpress/
 */
	if ( ! function_exists( 'ap_modify_post_thumbnail_html' ) ) :
		function ap_modify_post_thumbnail_html($html, $post_id, $post_thumbnail_id, $size, $attr) {
			if ( $credit = ap_get_thumbnail_field('mediaCreditText') ) :
		    	$html .= '<p class="credit">' .$credit. '</p>';
			endif;
		    return $html;
		}
	endif; // ap_modify_post_thumbnail_html
	add_filter('post_thumbnail_html', 'ap_modify_post_thumbnail_html', 99, 5);

?>