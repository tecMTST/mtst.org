<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing petition stat display
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

echo '<div class="cbxpetition_stat_wrapper">';
if ( $show_count ) {
	echo '<p class="cbxpetition_stat_count">' . sprintf( __( '<span class="cbxpetition_stat_count_target">Target: %d</span> <span class="cbxpetition_stat_count_received">Signatures Received: %d</span>', 'cbxpetition' ), $target, $signature_count ) . '</p>';
}

if ( $show_progress ) {
	echo '<div class="cbxpetition-progress-wrapper">
	                <div class="cbxpetition-progress">
	                    <div class="cbxpetition-progress-value" style="width: ' . intval($signature_ratio) . '%;"></div>
	                </div>
	                <span class="cbxpetition-progress-ratio">' . esc_html($signature_ratio) . '%</span>
	                <div class="clear clearfix"></div>
	            </div>';
}

echo '</div>';