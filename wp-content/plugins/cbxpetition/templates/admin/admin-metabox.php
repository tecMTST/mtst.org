<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBXPetition
 * @subpackage CBXPetition/admin/templates
 */

$petition_id = intval( $post->ID );

$signature_target = intval( get_post_meta( $petition_id, $prefix . 'signature_target', true ) );
$expire_date      = get_post_meta( $petition_id, '_cbxpetition_expire_date', true );

echo '<h2 class="nav-tab-wrapper">
			<a href="#cbxpetition_meta_basic" class="nav-tab" id="cbxpetition_meta_basic-tab">' . esc_html__( 'Petition General', 'cbxpetition' ) . '</a>
			<a href="#cbxpetition_meta_media" class="nav-tab" id="cbxpetition_meta_media-tab">' . esc_html__( 'Media Info (Optional)', 'cbxpetition' ) . '</a>
			<a href="#cbxpetition_meta_letter" class="nav-tab" id="cbxpetition_meta_letter-tab">' . esc_html__( 'The Letter (Optional)', 'cbxpetition' ) . '</a>
		</h2>';

echo '<div class="metabox-holder">';

echo '<div id="cbxpetition_meta_basic" class="group">
				<p><strong>' . esc_html__( 'Signature Target (Required)', 'cbxpetition' ) . '</strong></p>
				<input class="regular-text" type="number" name="cbxpetitionmeta[signature-target]" pattern="[0-9 ]+" value="' . intval( $signature_target ) . '" size="10" /></p>
				
				<p><strong>' . esc_html__( 'Expire Date', 'cbxpetition' ) . '</strong><br />
				<input type="text" name="cbxpetitionmeta[expire-date]"  value="' . esc_attr( $expire_date ) . '"  class="regular-text cbxpetition-add-date" /></p>
			</div>';


include( cbxpetition_locate_template( 'admin/admin-metabox-media.php' ) );
include( cbxpetition_locate_template( 'admin/admin-metabox-letter.php' ) );


echo '</div>'; //.metabox-holder