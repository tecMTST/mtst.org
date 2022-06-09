<?php
/**
 * Theme Customizer Functions
 *
 */

/*========================== CUSTOMIZER CONTROLS FUNCTIONS ==========================*/

if ( class_exists( 'WP_Customize_Control' ) ) :

	// Title Control
    class Anderson_Customize_Header_Control extends WP_Customize_Control {

        public function render_content() {  ?>

			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			</label>

			<?php
        }
    }

	// Description Control
	class Anderson_Customize_Description_Control extends WP_Customize_Control {

        public function render_content() {  ?>

			<span class="description"><?php echo esc_html( $this->label ); ?></span>

			<?php
        }
    }

	// Upgrade Control
	class Anderson_Customize_Upgrade_Control extends WP_Customize_Control {

    public function render_content() {  ?>

      <div class="upgrade-pro-version">

				<span class="customize-control-title"><?php esc_html_e( 'Pro Version Add-on', 'anderson-lite' ); ?></span>

				<span class="textfield">
					<?php printf( esc_html__( 'Purchase the %s Pro Add-on and get additional features and advanced customization options.', 'anderson-lite' ), 'Anderson' ); ?>
				</span>

				<p>
					<a href="<?php echo esc_url( __( 'https://themezee.com/addons/anderson-pro/', 'anderson-lite' ) ); ?>?utm_source=customizer&utm_medium=button&utm_campaign=anderson&utm_content=pro-version" target="_blank" class="button button-secondary">
						<?php printf( esc_html__( 'Learn more about %s Pro', 'anderson-lite' ), 'Anderson' ); ?>
					</a>
				</p>

			</div>

			<div class="upgrade-plugins">

				<span class="customize-control-title"><?php esc_html_e( 'Recommended Plugins', 'anderson-lite' ); ?></span>

				<span class="textfield">
					<?php esc_html_e( 'Extend the functionality of your WordPress website with our free and easy to use plugins.', 'anderson-lite' ); ?>
				</span>

				<p>
					<a href="<?php echo admin_url( 'plugin-install.php?tab=search&type=author&s=themezee' ); ?>" class="button button-secondary">
						<?php esc_html_e( 'Install Plugins', 'anderson-lite' ); ?>
					</a>
				</p>

			</div>

			<?php
      }
    }

endif;


// Add a callback function to retrieve wether slider is activated or not
function anderson_slider_activated_callback( $control ) {

	// Check if Slider is turned on
	if ( $control->manager->get_setting('anderson_theme_options[slider_active_magazine]')->value() == 1 ) :
		return true;
	elseif ( $control->manager->get_setting('anderson_theme_options[slider_active]')->value() == 1 ) :
		return true;
	else :
		return false;
	endif;

}
