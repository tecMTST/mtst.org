<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBXPetition
 * @subpackage CBXPetition/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    CBXPetition
 * @subpackage CBXPetition/includes
 * @author     Codeboxr <info@codeboxr.com>
 */
class CBXPetition_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		delete_option( 'cbxpetition_flush_rewrite_rules' );
	}//end method deactivate

}//end method CBXPetition_Deactivator
