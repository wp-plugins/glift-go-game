<?php

/** This file provides backwards compatibility with the [sgf] shortcode
 * which was used by the old EidoGo for WordPress plugin.
 */

add_shortcode( 'sgf', 'eidogo_do_shortcode' );

// sanitize, explicitly validate and arrange shortcode data
// we will translate any attributes into Glift format, then call glift_create
function eidogo_do_shortcode( $atts, $content, $tag ) {

	// did our shortcode send any data?
	if ( $atts ) {
		// if so then clean it up
		$clean_atts = array_map( 'sanitize_text_field', $atts );

		// look for sgf data
		if ( isset( $clean_atts['sgfurl'] ) ) {
			$glift_atts['sgf'] = $clean_atts['sgfurl'];

		// no sgfurl, so do we have $content?
		} elseif ( $content ) {
			$clean_content = sanitize_text_field( $content );
			$glift_atts['sgf'] = $clean_content;

		} else {
			// we don't have any data, so return false
			return FALSE;
		}

		/* sgfDefaults */
		if ( isset( $clean_atts['theme'] ) &&
		'problem' == $clean_atts['theme'] )
		$glift_atts['widgettype'] = 'STANDARD_PROBLEM';


	// we didn't receive any shortcode atts, so let's look for content
	} elseif ( $content ) {
		$clean_content = sanitize_text_field( $content );
		$glift_atts['sgf'] = $clean_content;

	} else {
		// we don't have any data, so return false
		return FALSE;
	}

	// we have some data, so create the Glift object and output as HTML
	return glift_create( $glift_atts, FALSE, FALSE ); // only pass $glift_atts
}
