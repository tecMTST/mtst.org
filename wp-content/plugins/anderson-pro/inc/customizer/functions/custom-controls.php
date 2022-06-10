<?php
/**
 * Theme Customizer Functions
 *
 */

/*========================== CUSTOMIZER CONTROLS FUNCTIONS ==========================*/

// Add simple heading option to the theme customizer
if ( class_exists( 'WP_Customize_Control' ) ) :

    class Anderson_Pro_Customize_Header_Control extends WP_Customize_Control {

        public function render_content() {
            ?>

			<label>
				<span class="customize-control-title"><?php echo wp_kses_post( $this->label ); ?></span>
			</label>

            <?php
        }
    }

	class Anderson_Pro_Customize_Description_Control extends WP_Customize_Control {

        public function render_content() {
            ?>

			<span class="description"><?php echo wp_kses_post( $this->label ); ?></span>

            <?php
        }
    }

	class Anderson_Pro_Customize_Font_Control extends WP_Customize_Control {

		private $local_fonts = false;
        private $google_fonts = false;
		public $l10n = array();

		// critical for JS constructor
		public $type = 'anderson_pro_custom_font';

		public function __construct( $manager, $id, $args = array(), $options = array() ) {

            // Make Buttons translateable.
			$this->l10n = array(
				'previous' => esc_html__( 'Previous Font', 'anderson-pro' ),
				'next'     => esc_html__( 'Next Font', 'anderson-pro' ),
				'standard' => esc_html_x( 'Default', 'default font button', 'anderson-pro' ),
			);

			// Set Fonts.
			$this->local_fonts = anderson_pro_get_local_fonts();
			$this->google_fonts = anderson_pro_get_google_fonts();

			parent::__construct( $manager, $id, $args );
		}

		public function enqueue() {

			// Register and Enqueue Custom Font JS Constructor
			wp_enqueue_script( 'anderson-pro-custom-font-control', plugins_url('/js/custom-font-control.js', dirname(dirname(dirname(__FILE__)) )), array( 'customize-controls' ), '20180412', true );

		}

		public function render_content() {

			$l10n = json_encode( $this->l10n );

			if ( ! empty( $this->local_fonts ) && ! empty( $this->google_fonts ) ) :

            ?>
                <label>
                    <span class="customize-control-title" data-l10n="<?php echo esc_attr( $l10n ); ?>" data-font="<?php echo esc_attr( $this->setting->default ); ?>">
						<?php echo esc_html( $this->label ); ?>
					</span>
					<div class="customize-font-select-control">
                        <select <?php $this->link(); ?>>
							<optgroup label="<?php esc_html_e( 'Local Fonts', 'anderson-pro' ); ?>">
								<?php
								foreach ( $this->local_fonts as $k => $v ) :
									printf( '<option value="%s" %s>%s</option>', $k, selected( $this->value(), $k, false ), $v );
								endforeach;
								?>
							</optgroup>

							<optgroup label="<?php esc_html_e( 'Google Web Fonts', 'anderson-pro' ); ?>">
	  							<?php
								foreach ( $this->google_fonts as $k => $v ) :
									printf( '<option value="%s" %s>%s</option>', $k, selected( $this->value(), $k, false ), $v );
								endforeach;
								?>
							</optgroup>
						</select>
					</div>
					<div class="actions"></div>
				</label>

            <?php
			endif;
		}
	}

endif;
