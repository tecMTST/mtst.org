<?php
/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the admin-facing signature edit
 *
 * @link       http://codeboxr.com
 * @since      1.0.7
 *
 * @package    cbxpetition
 * @subpackage cbxpetition/templates/admin
 *
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}
?>

    <!-- This file should primarily consist of HTML with a little bit of PHP. -->
    <div class="wrap">
        <div id='cbxpetitionloading' style='display:none'></div>
        <h2>
			<?php echo sprintf( esc_html__( 'Signature Edit: ID - %d', 'cbxpetition' ), $log_id ); ?>
        </h2>
        <p>
			<?php echo '<a class="button button-primary button-large" href="' . admin_url( 'edit.php?post_type=cbxpetition&page=cbxpetitionsigns' ) . '">' . esc_attr__( 'Back to Lists', 'cbxpetition' ) . '</a>'; ?>
        </p>
        <div id="poststuff">

            <div id="post-body" class="metabox-holder">

                <!-- main content -->
                <div id="post-body-content">
                    <div class="meta-box-sortables ui-sortable">
						<?php
						$validation_errors_status = false;
						$validation_errors        = array();

						if ( isset( $_SESSION['cbxpetition_sign_edit_validation_errors'] ) ) {
							$validation_errors_status = true;
							$validation_errors        = $_SESSION['cbxpetition_sign_edit_validation_errors'];
						}
						?>
                        <div class="postbox">
                            <h3><span><?php esc_html_e( 'Edit Sign', 'cbxpetition' ); ?></span></h3>
                            <div class="inside">
								<?php

								if ( isset( $_SESSION['cbxpetition_sign_edit_validation_success']['messages'] ) && sizeof( $_SESSION['cbxpetition_sign_edit_validation_success']['messages'] ) > 0 ) {
									$messages = $_SESSION['cbxpetition_sign_edit_validation_success']['messages'];

									foreach ( $messages as $message ) {
										echo '<div class="alert alert-' . $message['type'] . '" role="alert"><p>' . $message['text'] . '</p></div>';
									}
								}

								?>
								<?php if ( ! is_null( $sign_info ) ) : ?>
                                    <form class="cbxpetition_sign_edit_form" action="" method="post">
										<?php if ( $validation_errors_status ) { ?>
                                            <p class="cbxpetition_sign_error text-center">
												<?php
												if ( array_key_exists( 'top_errors', $validation_errors ) ) {
													$top_errors = $validation_errors['top_errors'];
													if ( is_array( $top_errors ) && sizeof( $top_errors ) ) {
														foreach ( $top_errors as $key => $val ) {
															echo '<div class="alert alert-danger" role="alert"><p>' . $val . '</p></div>';
														}
													}
												}
												?>
                                            </p>
										<?php } ?>

										<?php
										$petition_id = intval( $sign_info['petition_id'] );
										$first_name  = sanitize_text_field( $sign_info['f_name'] );
										$last_name   = sanitize_text_field( $sign_info['l_name'] );
										?>

                                        <table class="form-table">
                                            <tr valign="top">
                                                <th class="row-title" scope="row">
                                                    <label><?php esc_html_e( 'Petition', 'cbxpetition' ); ?></label>
                                                </th>
                                                <td>
													<?php echo '<a href="' . get_permalink( $petition_id ) . '">' . get_the_title( $petition_id ) . ' (' . sprintf( esc_html__( 'ID: %d', 'cbxpetition' ), $petition_id ) . ')</a>'; ?>
                                                </td>
                                            </tr>
                                            <tr valign="top">
                                                <th class="row-title" scope="row">
                                                    <label><?php esc_html_e( 'First Name', 'cbxpetition' ); ?></label>
                                                </th>
                                                <td>
													<?php echo esc_html($first_name); ?>
                                                </td>
                                            </tr>
                                            <tr valign="top">
                                                <th class="row-title" scope="row">
                                                    <label><?php esc_html_e( 'Last Name', 'cbxpetition' ); ?></label>
                                                </th>
                                                <td>
													<?php echo esc_html($last_name); ?>
                                                </td>
                                            </tr>
                                            <tr valign="top">
                                                <th class="row-title" scope="row">
                                                    <label><?php esc_html_e( 'Add Date', 'cbxpetition' ); ?></label>
                                                </th>
                                                <td>
													<?php echo CBXPetitionHelper::dateShowingFormat( $sign_info['add_date'] ) . esc_html__( ' at ',
															'cbxpetition' ) . CBXPetitionHelper::timeShowingFormat( $sign_info['add_date'] ); ?>
                                                </td>
                                            </tr>
											<?php if ( $sign_info['mod_date'] !== null ): ?>
                                                <tr valign="top">
                                                    <th class="row-title" scope="row">
                                                        <label><?php esc_html_e( 'Add Date', 'cbxpetition' ); ?></label>
                                                    </th>
                                                    <td>
														<?php echo CBXPetitionHelper::dateShowingFormat( $sign_info['mod_date'] ) . esc_html__( ' at ',
																'cbxpetition' ) . CBXPetitionHelper::timeShowingFormat( $sign_info['mod_date'] ); ?>
                                                    </td>
                                                </tr>
											<?php endif; ?>

                                            <tr valign="top">
                                                <th class="row-title" scope="row">
                                                    <label for="cbxpetition_sign_comment"><?php esc_html_e( 'Comment', 'cbxpetition' ); ?></label>
                                                </th>

                                                <td>
													<textarea
                                                            name="comment"
                                                            class="regular-text cbxpetition_sign_comment"
                                                            rows="4" cols="50"
                                                            required><?php echo esc_textarea($comment); ?></textarea>
                                                </td>
                                            </tr>

                                            <tr valign="top">
                                                <th class="row-title" scope="row">
                                                    <label for="cbxpetition_sign_state"><?php esc_html_e( 'Status', 'cbxpetition' ); ?></label>
                                                </th>
                                                <td>
                                                    <select name="state">
														<?php
														foreach ( $cbxpetition_state_arr as $state_key => $state_name ) {
															?><

                                                            <option value="<?php echo $state_key; ?>" <?php if ( $state_key == $state ) {
																echo 'selected="selected"';
															} ?> > <?php echo esc_attr( $state_name ); ?>
                                                            </option>
														<?php } ?>
                                                    </select>
                                                </td>
                                            </tr>

                                            <input type="hidden" name="id" value="<?php echo $log_id; ?>"/>
                                            <input type="hidden" name="cbxpetition_sign_edit" value="1"/>
											<?php wp_nonce_field( 'cbxpetition_nonce', 'cbxpetition_token' ); ?>

                                            <tr valign="top">
                                                <th class="row-title" scope="row"></th>
                                                <td>
                                                    <button type="submit" class="button-primary "><?php esc_html_e( 'Update Sign', 'cbxpetition' ); ?></button>
                                                </td>
                                            </tr>
                                        </table>

                                    </form>
								<?php else :
									echo '<div class="notice notice-error inline"><p>' . esc_html__( 'No data Found', 'cbxpetition' ) . '</p></div>';
									?>
								<?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>

<?php
if ( isset( $_SESSION['cbxpetition_sign_edit_validation_errors'] ) ) {
	unset( $_SESSION['cbxpetition_sign_edit_validation_errors'] );
}

if ( isset( $_SESSION['cbxpetition_sign_edit_validation_success'] ) ) {
	unset( $_SESSION['cbxpetition_sign_edit_validation_success'] );
}