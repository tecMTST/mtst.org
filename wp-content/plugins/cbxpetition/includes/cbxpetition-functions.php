<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function cbxpetition_petitionSignInfo( $petition_id = 0 ) {
	return CBXPetitionHelper::petitionSignInfo( $petition_id );
}


/**
 * get single petition expire date
 *
 * @param int $petition_id
 *
 * @return string
 */
function cbxpetition_petitionExpireDate( $petition_id = 0 ) {
	return CBXPetitionHelper::petitionExpireDate( $petition_id );
}

/**
 * get single petition media info data arr
 *
 * @param int $petition_id
 *
 * @return array|mixed
 */
function cbxpetition_petitionMediaInfo( $petition_id = 0 ) {
	return CBXPetitionHelper::petitionMediaInfo( $petition_id );
}

/**
 * get single petition banner image
 *
 * @param int $petition_id
 *
 * @return int|mixed|string
 */
function cbxpetition_petitionBannerImage( $petition_id = 0 ) {
	return CBXPetitionHelper::petitionBannerImage( $petition_id );
}

/**
 * get single petition signature target
 *
 * @param int $petition_id
 *
 * @return int|mixed|string
 */
function cbxpetition_petitionSignatureTarget( $petition_id = 0 ) {
	return CBXPetitionHelper::petitionSignatureTarget( $petition_id );
}

/**
 * get single petition video info
 *
 * @param int $petition_id
 *
 * @return array
 */
function cbxpetition_petitionVideoInfo( $petition_id = 0 ) {
	return CBXPetitionHelper::petitionVideoInfo( $petition_id );
}

/**
 * get single petition photos
 *
 * @param int $petition_id
 *
 * @return array
 */
function cbxpetition_petitionPhotos( $petition_id = 0 ) {
	return CBXPetitionHelper::petitionPhotos( $petition_id );
}

/**
 * get single petition letter info
 *
 * @param int $petition_id
 *
 * @return array|mixed
 */
function cbxpetition_petitionLetterInfo( $petition_id = 0 ) {
	return CBXPetitionHelper::petitionLetterInfo( $petition_id );
}

/**
 * get single petition letter
 *
 * @param int $petition_id
 *
 * @return mixed|string
 */
function cbxpetition_petitionLetter( $petition_id = 0 ) {
	return CBXPetitionHelper::petitionLetter( $petition_id );
}

/**
 * get single petition recipients
 *
 * @param int $petition_id
 *
 * @return array
 */
function cbxpetition_petitionRecipients( $petition_id = 0 ) {
	return CBXPetitionHelper::petitionRecipients( $petition_id );
}

/**
 * get single petition signature count
 *
 * @param int $petition_id
 *
 * @return int
 */
function cbxpetition_petitionSignatureCount( $petition_id = 0 ) {
	return CBXPetitionHelper::petitionSignatureCount( $petition_id );
}

/**
 * get single petition signature to target ratio
 *
 * @param int $petition_id
 *
 * @return int
 */
function cbxpetition_petitionSignatureTargetRatio( $petition_id = 0 ) {
	return CBXPetitionHelper::petitionSignatureTargetRatio( $petition_id );
}

/**
 * Get the template path.
 *
 * @return string
 */
function cbxpetition_template_path() {
	return apply_filters( 'cbxpetition_template_path', 'cbxpetition/' );
}//end method cbxpetition_template_path

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 * yourtheme/$template_path/$template_name
 * yourtheme/$template_name
 * $default_path/$template_name
 *
 * @param string $template_name Template name.
 * @param string $template_path Template path. (default: '').
 * @param string $default_path  Default path. (default: '').
 *
 * @return string
 */
function cbxpetition_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	if ( ! $template_path ) {
		$template_path = cbxpetition_template_path();
	}

	if ( ! $default_path ) {
		$default_path = CBXPETITION_ROOT_PATH . 'templates/';
	}

	// Look within passed path within the theme - this is priority.
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name,
		)
	);

	// Get default template/.
	if ( ! $template ) {
		$template = $default_path . $template_name;
	}

	// Return what we found.
	return apply_filters( 'cbxpetition_locate_template', $template, $template_name, $template_path );
}//end function cbxpetition_locate_template

/**
 * Like wc_get_template, but returns the HTML instead of outputting.
 *
 * @see   wc_get_template
 * @since 2.5.0
 *
 * @param string $template_name Template name.
 * @param array  $args          Arguments. (default: array).
 * @param string $template_path Template path. (default: '').
 * @param string $default_path  Default path. (default: '').
 *
 * @return string
 */
function cbxpetition_get_template_html( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	ob_start();
	cbxpetition_get_template( $template_name, $args, $template_path, $default_path );

	return ob_get_clean();
}//end function cbxpetition_get_template_html

/**
 * Get other templates (e.g. product attributes) passing attributes and including the file.
 *
 * @param string $template_name Template name.
 * @param array  $args          Arguments. (default: array).
 * @param string $template_path Template path. (default: '').
 * @param string $default_path  Default path. (default: '').
 */
function cbxpetition_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	if ( ! empty( $args ) && is_array( $args ) ) {
		extract( $args ); // @codingStandardsIgnoreLine
	}

	$located = cbxpetition_locate_template( $template_name, $template_path, $default_path );

	if ( ! file_exists( $located ) ) {
		/* translators: %s template */
		wc_doing_it_wrong( __FUNCTION__, sprintf( __( '%s does not exist.', 'cbxpetition' ), '<code>' . $located . '</code>' ), '1.0.0' );

		return;
	}

	// Allow 3rd party plugin filter template file from their plugin.
	$located = apply_filters( 'cbxpetition_get_template', $located, $template_name, $args, $template_path, $default_path );

	do_action( 'cbxpetition_before_template_part', $template_name, $template_path, $located, $args );

	include $located;

	do_action( 'cbxpetition_after_template_part', $template_name, $template_path, $located, $args );
}//end function cbxpetition_get_template