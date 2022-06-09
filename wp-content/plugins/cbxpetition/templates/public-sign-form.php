<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing petition sign form
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
?>

<?php

$current_user_info = null;
$guest             = true;
if ( is_user_logged_in() ) {
	$guest = false;

	$current_user      = wp_get_current_user();
	$user_id           = $current_user->ID;
	$user_display_name = $current_user->display_name;
	$user_display_name = CBXPetitionHelper::userDisplayNameAlt( $current_user, $user_display_name );
	$user_email        = $current_user->user_email;
	$log_out_url       = wp_logout_url( get_permalink() );
}

$validation_errors_status = false;
$validation_errors        = array();

if ( array_key_exists( 'cbxpetition_validation_errors', $_SESSION ) && isset( $_SESSION['cbxpetition_validation_errors'] ) ) {
	$validation_errors_status = true;
	$validation_errors        = $_SESSION['cbxpetition_validation_errors'];
}
?>

    <div class="cbxpetition_signform_wrapper">
        <h3><?php esc_html_e( 'Sign this Petition', 'cbxpetition' ); ?></h3>
		<?php
		if ( array_key_exists( 'cbxpetition_validation_success', $_SESSION ) && isset( $_SESSION['cbxpetition_validation_success'] ) ) {
			if ( isset( $_SESSION['cbxpetition_validation_success']['success_arr']['messages'] ) && sizeof( $_SESSION['cbxpetition_validation_success']['success_arr']['messages'] ) > 0 ) {
				$messages = $_SESSION['cbxpetition_validation_success']['success_arr']['messages'];
				foreach ( $messages as $message ) {
					echo '<div class="cbxpetition-alert cbxpetition-alert-' . $message['type'] . '" role="alert"><p>' . $message['text'] . '</p></div>';
				}
			}

			if ( isset( $_SESSION['cbxpetition_validation_success']['error_arr']['messages'] ) && sizeof( $_SESSION['cbxpetition_validation_success']['error_arr']['messages'] ) > 0 ) {
				$messages = $_SESSION['cbxpetition_validation_success']['error_arr']['messages'];
				foreach ( $messages as $message ) {
					echo '<div class="cbxpetition-alert cbxpetition-alert-' . $message['type'] . '" role="alert"><p>' . $message['text'] . '</p></div>';
				}
			}
		}
		?>
        <div class="cbxpetition-success-messages"></div>
        <div class="cbxpetition-error-messages"></div>

        <form action="#" data-ajax="1" data-busy="0" class="cbxpetition-signform" method="POST" novalidate="novalidate">
			<?php
			if ( $validation_errors_status ) { ?>
                <div class="cbxpetition-error text-center">
					<?php
					if ( array_key_exists( 'top_errors', $validation_errors ) ) {
						$top_errors = $validation_errors['top_errors'];

						if ( is_array( $top_errors ) && sizeof( $top_errors ) > 0 ) {
							foreach ( $top_errors as $key => $val ) {
								echo '<p>' . $val . '</p>';
							}
						}
					}
					?>
                </div>
			<?php } ?>
			<?php if ( $guest ): ?>
                <div class="cbxpetition-signform-field">
                    <label class="cbxpetition-signform-field-label" for="cbxpetition-fname"><?php esc_html_e( 'First Name', 'cbxpetition' ); ?></label>
                    <input type="text" class="cbxpetition-signform-field-input cbxpetition-signform-field-text cbxpetition-signform-field-fname"
                           name="fname" id="cbxpetition-fname" placeholder="<?php esc_html_e( 'First Name', 'cbxpetition' ); ?>"
                           value="" required data-rule-required="true" data-rule-minlength="2"/>
                </div>
                <div class="cbxpetition-signform-field">
                    <label class="cbxpetition-signform-field-label" for="cbxpetition-lname"><?php esc_html_e( 'Last Name', 'cbxpetition' ); ?></label>
                    <input type="text" class="cbxpetition-signform-field-input cbxpetition-signform-field-text cbxpetition-signform-field-lname"
                           name="lname" id="cbxpetition-lname" placeholder="<?php esc_html_e( 'Last Name', 'cbxpetition' ); ?>"
                           value="" required data-rule-required="true" data-rule-minlength="2"/>

                </div>

                <div class="cbxpetition-signform-field">
                    <label class="cbxpetition-signform-field-label" for="cbxpetition-email"><?php esc_html_e( 'Email', 'cbxpetition' ); ?></label>

                    <input type="email" class="cbxpetition-signform-field-input cbxpetition-signform-field-email cbxpetition-signform-field-email"
                           name="email" id="cbxpetition-email" placeholder="<?php esc_html_e( 'Email', 'cbxpetition' ); ?>"
                           value="" required data-rule-required="true" data-rule-email="true"/>

                </div>
			<?php else: ?>
                <p>
					<?php echo sprintf( __( 'You are logged in as <strong>%s</strong>, <a href="%s">Logout?</a>', 'cbxpetition' ), esc_html($user_display_name), esc_url( $log_out_url ) ); ?>
                </p>

			<?php endif; ?>


            <div class="cbxpetition-signform-field">
                <label class="cbxpetition-signform-field-label" for="cbxpetition-comment"><?php esc_html_e( 'Comment', 'cbxpetition' ); ?></label>
                <textarea cols="30" rows="6" class="cbxpetition-signform-field-input cbxpetition-signform-field-textarea cbxpetition-signform-field-comment"
                          name="comment" id="cbxpetition-comment" placeholder="<?php esc_html_e( 'I’m signing because… (optional)', 'cbxpetition' ); ?>"></textarea>

            </div>
            <div class="cbxpetition-signform-field">
                <label class="cbxpetition-signform-field-label" for="cbxpetition-privacy">
                    <input required data-rule-required="true" type="checkbox" name="privacy" id="cbxpetition-privacy" class="cbxpetition-signform-field-input cbxpetition-signform-field-checkbox cbxpetition-signform-field-privacy" value="1"/>
					<?php echo sprintf(__( 'YES, I agree with <a href="%s" target="_blank">Privacy policy</a>', 'cbxpetition' ), get_privacy_policy_url()); ?>
                </label>

            </div>
            <p class="text-center">
                <button type="submit" class="cbxpetition-submit"><?php esc_html_e( 'Sign This', 'cbxpetition' ); ?></button>
            </p>

            <input type="hidden" name="id" value="<?php echo intval($petition_id) ?>"/>
            <input type="hidden" name="cbxpetition_sign_submit" value="1"/>
			<?php wp_nonce_field( 'cbxpetition_nonce', 'cbxpetition_token' ); ?>
        </form>
    </div>

<?php
if ( isset( $_SESSION['cbxpetition_validation_errors'] ) ) {
	unset( $_SESSION['cbxpetition_validation_errors'] );
}

if ( isset( $_SESSION['cbxpetition_validation_success'] ) ) {
	unset( $_SESSION['cbxpetition_validation_success'] );
}
