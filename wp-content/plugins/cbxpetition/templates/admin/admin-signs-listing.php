<?php
/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the petition sign listing
 *
 * @link       http://codeboxr.com
 * @since      1.0.7
 *
 * @package    cbxpetition
 * @subpackage cbxpetition/admin/templates
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}
?>

<?php
$cbxpetitionsign = new CBXPetitionSign_List_Table();

//Fetch, prepare, sort, and filter CBXPetitionSign_List_Table data
$cbxpetitionsign->prepare_items();
?>

<div class="wrap cbxpetition_sign_wrapper">
    <h1 class="wp-heading-inline">
		<?php esc_html_e( 'Petition: Sign Listing', 'cbxpetition' ); ?>
    </h1>
    <a href="<?php echo admin_url( 'edit.php?post_type=cbxpetition' ); ?>"
       class="page-title-action"><?php echo esc_html__( 'Add New Petition', 'cbxpetition' ); ?></a>

    <div id="poststuff">
        <div id="post-body" class="metabox-holder">
            <!-- main content -->
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <div class="postbox">
                        <div class="inside">
                            <form id="cbxpetition_signs" method="post">
								<?php $cbxpetitionsign->views(); ?>
                                <input type="hidden" name="page" value="<?php echo esc_attr( wp_unslash( $_REQUEST['page'] ) ) ?>"/>
                                <input type="hidden" name="post_type" value="cbxpetition"/>
								<?php $cbxpetitionsign->search_box( esc_html__( 'Search Sign', 'cbxpetition' ), 'petitionsignsearch' ); ?>

								<?php $cbxpetitionsign->display() ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br class="clear">
    </div>
</div>