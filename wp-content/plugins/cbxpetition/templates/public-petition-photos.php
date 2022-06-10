<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing petition photos
 *
 * @link       http://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBXPetition
 * @subpackage CBXPetition/templates
 */
?>

<?php
/**
 * Provide a public view for the plugin
 *
 * This file is used to markup the public facing form
 *
 * @link       http://codeboxr.com
 * @since      1.0.0
 *
 * @package    cbxpetition
 * @subpackage cbxpetition/public/templates
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( is_array( $petition_photos ) && sizeof( $petition_photos ) > 0 ):

	do_action('cbxpetition_photos_before', $petition_id);

	$dir_info = CBXPetitionHelper::checkUploadDir();

	echo '<div class="cbxpetition_photos_wrapper">';
	echo '<div id="cbxpetition_photos">';

	foreach ( $petition_photos as $filename ) {
		$petition_photo_url       = $dir_info['cbxpetition_base_url'] . $petition_id . '/' . $filename;
		$petition_photo_thumb_url = $dir_info['cbxpetition_base_url'] . $petition_id . '/thumbnail/' . $filename;

		echo '<div class="cbxpetition_photo">';
		echo '<a href="' . esc_url( $petition_photo_url ) . '" data-gall="cbxpetition_photo_background-' . $petition_id . '" class="venobox cbxpetition_photo_background" style="background-image: url(\'' . esc_url( $petition_photo_url ) . '\');"></a>';
		echo '</div>';
	}

	echo '</div>';
	echo '</div>';

	do_action('cbxpetition_photos_after', $petition_id);
endif;