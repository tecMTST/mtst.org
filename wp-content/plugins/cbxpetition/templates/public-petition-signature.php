<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing petition single signature display
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

echo '<div class="cbxpetition_signature_item">';
do_action( 'cbxpetition_signature_item_start', $petition_id, $petition_sign );

$name = '';
if ( $petition_sign['f_name'] != '' ) {
	$name = wp_unslash( $petition_sign['f_name'] );
	if ( $petition_sign['l_name'] != '' ) {
		$name .= ' ' . wp_unslash( $petition_sign['l_name'] );
	}
}

echo '<div class="signature-card">
	                
	                <div class="signature-info">
	                	<div class="signature-thumb-photo">' . get_avatar( sanitize_email( $petition_sign['email'] ) , 60) . '</div>
	                    <h3 class="signature-name">' . esc_attr( $name ) . '</h3>
	                    <span class="signature-date-time">' . CBXPetitionHelper::dateShowingFormat( $petition_sign['add_date'] ) . esc_html__( ' at ',
		'cbxpetition' ) . CBXPetitionHelper::timeShowingFormat( $petition_sign['add_date'] ) . '</span>                    	                    
	                </div>
	                <div class="signature-message-wrap">
                        <div class="signature-message">' . wpautop( wp_unslash( $petition_sign['comment'] ) ) . '</div>	                
					</div>	                                
	           <div class="clear clearfix"></div></div>';
do_action( 'cbxpetition_signature_item_end', $petition_id, $petition_sign );
echo '</div>';