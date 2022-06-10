<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'CBXPetitionSignformWidget' ) ):
	/**
	 * Class CBXPetition Summary Widget
     *
     * @since 1.0.2
	 */
	class CBXPetitionSignformWidget extends WP_Widget {
		/**
		 * CBXPetitionSignformWidget constructor.
		 */
		public function __construct() {
			parent::__construct( 'cbxpetition_signform',
				esc_html__( 'CBX Petition Sign Form', 'cbxpetition' ),
				array(
					'description' => esc_html__( 'Single Petition Sign Form Widget for CBX Petition', 'cbxpetition' ),
				) );
		}//end of constructor method

		/**
		 * @param array $instance
		 *
		 * @return string|void
		 */
		public function form( $instance ) {
			$title = isset( $instance['title'] ) ? $instance['title'] : esc_html__( 'CBX Petition Summary', 'cbxpetition' );



			$petition_id = isset( $instance['petition_id'] ) ? intval( $instance['petition_id'] ) : 0;



			?>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ) ?>"><?php echo esc_html__( 'Title:', 'cbxpetition' ) ?></label>
                <input type="text" class="" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ) ?>"
                       id="<?php echo esc_attr( $this->get_field_id( 'title' ) ) ?>"
                       value="<?php echo $title; ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'petition_id' ) ) ?>"><?php echo esc_html__( 'Petition ID:', 'cbxpetition' ) ?></label>
                <input type="text" class="" name="<?php echo esc_attr( $this->get_field_name( 'petition_id' ) ) ?>"
                       id="<?php echo esc_attr( $this->get_field_id( 'petition_id' ) ) ?>"
                       value="<?php echo intval( $petition_id ); ?>"/><br/>
				<?php
				$petition = get_post( $petition_id );
				if ( $petition !== null ) {
					echo sprintf( __( 'Showing petition sign form for <a target="_blank" href="%s"><strong>%s</strong></a>', 'cbxpetition' ), get_permalink( $petition_id ), get_the_title( $petition_id ) );
				} else {
					echo __( 'Seems <strong>Petition ID</strong> doesn\'t belong to any valid Petition', 'cbxpetition' );
				}
				?>
            </p>
			<?php
		}// end of form method

		/**
		 * Update widget
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {
			echo $args['before_widget'];
			$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : esc_html__( 'Business Hours', 'cbxpetition' );
			if ( isset( $title ) ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			$petition_id = isset( $instance['petition_id'] ) ? intval( $instance['petition_id'] ) : 0;

			$user_id = intval( get_current_user_id() );

			if ( $petition_id == 0 ) {
				echo '<p class="cbxpetition-info cbxpetition-info-notfound">' . esc_html__( 'No valid petition id found.',
						'cbxpetition' ) . '</p>';
			}

			$expire_date = get_post_meta( $petition_id, '_cbxpetition_expire_date', true );

			if ( $expire_date == '' ) {
				echo  '<p class="cbxpetition-info cbxpetition-info-datenotset">' . esc_html__( 'Sorry, petition did not start yet.', 'cbxpetition' ) . '</p>';
			} elseif ( $expire_date != '' ) {

				$expire_date = new DateTime( $expire_date );
				$now_date    = new DateTime( 'now' );


				if ( $expire_date < $now_date ) {
					echo '<p class="cbxpetition-info cbxpetition-info-alreadysigned">' . esc_html__( 'Sorry, petition expired to take more sign.','cbxpetition' ) . '</p>';
				}

			}

			$is_petition_signed_by_user = CBXPetitionHelper::isPetitionSignedByUser( $petition_id, $user_id );

			if ( $is_petition_signed_by_user !== false ) {
				echo '<p class="cbxpetition-info cbxpetition-info-alreadysigned">' . esc_html__( 'You signed the petition, thank you.','cbxpetition' ) . '</p>';
			}
			else{
				$output = '';
				ob_start();
				include( cbxpetition_locate_template( 'public-sign-form.php' ) );
				$output = ob_get_contents();
				ob_end_clean();

				echo $output;
            }




			echo $args['after_widget'];
		}//end method widget

		/**
		 * Update Widget
		 *
		 * @param array $new_instance
		 * @param array $old_instance
		 *
		 * @return array
		 */
		public function update( $new_instance, $old_instance ) {

			$instance = $old_instance;

			$instance['title']       = isset( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
			$instance['petition_id'] = isset( $new_instance['petition_id'] ) ? intval( $new_instance['petition_id'] ) : 0;


			return $instance;
		}// end of update method

	}//end class CBXPetitionSignformWidget
endif;