<?php
/**
 * Plugin Name: WPArtisan Filename Sanitizer
 * Description: Sanitize media filenames to remove non-latin special characters and accents
 * Author: WPArtisan
 * Author URI: https://wpartisan.me
 * Version: 0.0.1
 * Plugin URI: https://wpartisan.me/plugins/wpa-filename-sanitizer
 *
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Define the current version
define( 'WPAFS_VERSION', '0.0.1' );

/**
 * Produces cleaner filenames for uploads
 *
 * @param  string $filename The name of the file to sanitize
 * @return string
 */
function wpartisan_sanitize_file_name( $filename ) {

	$sanitized_filename = remove_accents( $filename ); // Convert to ASCII

	// Standard replacements
	$invalid = array(
		' '   => '-',
		'%20' => '-',
		'_'   => '-',
	);
	$sanitized_filename = str_replace( array_keys( $invalid ), array_values( $invalid ), $sanitized_filename );

	$sanitized_filename = preg_replace('/[^A-Za-z0-9-\. ]/', '', $sanitized_filename); // Remove all non-alphanumeric except .
	$sanitized_filename = preg_replace('/\.(?=.*\.)/', '', $sanitized_filename); // Remove all but last .
	$sanitized_filename = preg_replace('/-+/', '-', $sanitized_filename); // Replace any more than one - in a row
	$sanitized_filename = str_replace('-.', '.', $sanitized_filename); // Remove last - if at the end
	$sanitized_filename = strtolower( $sanitized_filename ); // Lowercase

	return $sanitized_filename;
}
add_filter( 'sanitize_file_name', 'wpartisan_sanitize_file_name', 10, 1 );
