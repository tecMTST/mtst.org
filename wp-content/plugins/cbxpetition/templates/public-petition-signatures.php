<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing petition signature listing
 *
 * @link       http://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBXPetition
 * @subpackage CBXPetition/templates
 */
?>

<?php

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( is_array( $petition_signs ) && sizeof( $petition_signs ) > 0 ) {

	do_action( 'cbxpetition_signature_before', $petition_id );

	echo '<div class="cbxpetition_signature_wrapper">';
	echo '<h2 class="cbxpetition_signature_listing_heading">' . esc_html__( 'Reasons for signing', 'cbxpetition' ) . '</h2>';
	echo '<p class="cbxpetition_signature_listing_total">' . sprintf( esc_html__( 'Total Signatures: %d', 'cbxpetition' ), intval($petition_count) ) . '</p>';

	do_action( 'cbxpetition_signature_items_before', $petition_id );

	echo '<div class="cbxpetition_signature_items">';
	foreach ( $petition_signs as $petition_sign ) {
		include( cbxpetition_locate_template( 'public-petition-signature.php' ) );
	}
	echo '</div>';
	do_action( 'cbxpetition_signature_items_after', $petition_id );

	if ( $petition_count > $perpage ) {
		$max_pages = ceil( $petition_count / $perpage );
		echo '<p class="cbxpetition_load_more_signs_wrap clearfix clear"><a href="#" class="cbxpetition_load_more_signs" data-busy="0" data-petition-id="' . $petition_id . '" data-perpage="' . intval( $perpage ) . '" data-order="DESC" data-orderby="id" data-page="1" data-maxpage="' . intval( $max_pages ) . '">' . esc_html__( 'Load More',
				'cbxpetition' ) . '</a></p>';
	}

	echo '</div>';

	do_action( 'cbxpetition_signature_after', $petition_id );
}