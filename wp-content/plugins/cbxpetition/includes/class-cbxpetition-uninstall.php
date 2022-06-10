<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<?php

/**
 * Fired during plugin uninstall
 *
 * @link       https://www.codeboxr.com
 * @since      1.0.0
 *
 * @package    CBXPetition
 * @subpackage CBXPetition/includes
 */

/**
 * Fired during plugin uninstall.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    CBXPetition
 * @subpackage CBXPetition/includes
 * @author     Codeboxr <info@codeboxr.com>
 */
class CBXPetition_Uninstall {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function uninstall() {


		global $wpdb;

		$settings = new CBXPetition_Settings();


		$delete_global_config = $settings->get_option( 'delete_global_config', 'cbxpetition_tools', 'no' );
		if ( $delete_global_config == 'yes' ) {
			//delete plugin global options
			$prefix = 'cbxpetition_';
			$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '{$prefix}%'" );

			//delete tables created by this plugin
			$table_name = array();

			//tables
			$table_name[] = $table_pz_petition_signs = $wpdb->prefix . 'cbxpetition_signs';


			$sql = "DROP TABLE IF EXISTS " . implode( ', ', $table_name );
			$val = $wpdb->query( $sql );

		}

	}//end method uninstall

}//end class CBXPetition_Uninstall
