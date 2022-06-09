<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing petition letter
 *
 * @link       http://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBXPetition
 * @subpackage CBXPetition/templates
 */



if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( is_array( $petition_letter ) && sizeof( $petition_letter ) > 0 ):
	do_action('cbxpetition_letter_before', $petition_id);

	echo '<div class="cbxpetition_letter_wrapper">';

	$recipients = isset( $petition_letter['recipients'] ) ? $petition_letter['recipients'] : array();

	if ( is_array( $recipients ) && sizeof( $recipients ) > 0 ):

		echo '<h2 class="cbxpetition_letter_note">' . esc_html__( 'The Letter Will be sent to:', 'cbxpetitio' ) . '</h2>';
		echo '<div id="cbxpetition_letter_recipients">';

		foreach ( $recipients as $recipient ) {
			$name        = isset( $recipient['name'] ) ? $recipient['name'] : '';
			$designation = isset( $recipient['designation'] ) ? $recipient['designation'] : '';
			$email       = isset( $recipient['email'] ) ? $recipient['email'] : '';

			echo '<div class="cbxpetition_letter_recipient">';
			echo '<p class="recipient_name">' . esc_attr( $name ) . '</p>';
			echo '<p class="recipient_designation">' . esc_attr( $designation ) . '</p>';
			echo '</div>';
		}
		echo '</div>';
	endif;

	$letter = isset( $petition_letter['letter'] ) ? sanitize_textarea_field( $petition_letter['letter'] ) : '';
	if ( $letter != '' ) {
		echo '<div id="cbxpetition_letter">';
		echo wpautop( $letter );
		echo '</div>';
	}

	echo '</div>';

	do_action('cbxpetition_letter_after', $petition_id);

endif;