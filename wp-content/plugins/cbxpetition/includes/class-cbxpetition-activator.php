<?php

/**
 * Fired during plugin activation
 *
 * @link       http://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBXPetition
 * @subpackage CBXPetition/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    CBXPetition
 * @subpackage CBXPetition/includes
 * @author     Codeboxr <info@codeboxr.com>
 */
class CBXPetition_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		//check if can activate plugin
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$plugin = isset( $_REQUEST['plugin'] ) ? wp_unslash( $_REQUEST['plugin'] ) : '';
		check_admin_referer( "activate-plugin_{$plugin}" );

		CBXPetitionHelper::create_tables();

		add_option( 'cbxpetition_flush_rewrite_rules', 'true' );

		set_transient( 'cbxpetition_activated_notice', 1 );

	}//end method activate
}//end class CBXPetition_Activator
