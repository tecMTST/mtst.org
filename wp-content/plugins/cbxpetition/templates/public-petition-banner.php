<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing petition banner
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

if ( $cbxpetition_banner != '' ) {
	do_action('cbxpetition_banner_before', $petition_id);
	echo '<div class="cbxpetition_banner_wrapper">
                <a href="' . esc_url( get_the_permalink( $petition_id ) ) . '"><img src="' . esc_url($cbxpetition_banner) . '" alt="petition-cover" /></a>
           </div>';
	do_action('cbxpetition_banner_after', $petition_id);
}