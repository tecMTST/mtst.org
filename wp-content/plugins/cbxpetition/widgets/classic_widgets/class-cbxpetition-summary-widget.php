<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'CBXPetitionSummaryWidget' ) ):
	/**
	 * Class CBXPetition Summary Widget
     *
     * @since 1.0.2
	 */
	class CBXPetitionSummaryWidget extends WP_Widget {
		/**
		 * CBXPetitionSummaryWidget constructor.
		 */
		public function __construct() {
			parent::__construct( 'cbxpetition_summary',
				esc_html__( 'CBX Petition Summary', 'cbxpetition' ),
				array(
					'description' => esc_html__( 'Single Petition Summary Widget for CBX Petition', 'cbxpetition' ),
				) );
		}//end of constructor method

		/**
		 * @param array $instance
		 *
		 * @return string|void
		 */
		public function form( $instance ) {
			$title = isset( $instance['title'] ) ? $instance['title'] : esc_html__( 'CBX Petition Summary', 'cbxpetition' );

			$default_sections     = apply_filters( 'cbxpetition_summary_shortcode_default_sections', 'title,content,stat,expire_date' );
			$default_sections_arr = explode( ',', $default_sections );

			$petition_id = isset( $instance['petition_id'] ) ? intval( $instance['petition_id'] ) : 0;
			$sections    = isset( $instance['sections'] ) ? $instance['sections'] : $default_sections_arr;
			if ( ! is_array( $sections ) ) {
				$sections = array();
			}


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
					echo sprintf( __( 'Showing petition for <a target="_blank" href="%s"><strong>%s</strong></a>', 'cbxpetition' ), get_permalink( $petition_id ), get_the_title( $petition_id ) );
				} else {
					echo __( 'Seems <strong>Petition ID</strong> doesn\'t belong to any valid Petition', 'cbxpetition' );
				}
				?>
            </p>
            <p>
                <strong><?php esc_html_e( 'Petition Summary Sections', 'cbxpetition' ); ?></strong>
            </p>
            <p>
				<?php
				foreach ( $default_sections_arr as $default_section ) {
					$default_section = trim( strtolower( $default_section ) );

					$checked = in_array( $default_section, $sections ) ? ' checked ' : '';
					?>

                    <input <?php echo $checked; ?> type="checkbox" class="" name="<?php echo esc_attr( $this->get_field_name( 'sections' ) ) ?>[<?php echo esc_attr( $default_section ); ?>]"
                                                   id="<?php echo esc_attr( $this->get_field_id( 'sections' ) ); ?>-<?php echo esc_attr( $default_section ); ?>"
                                                   value="<?php echo esc_attr( $default_section ); ?>">
                    <label for="<?php echo esc_attr( $this->get_field_id( 'sections' ) ); ?>-<?php echo esc_attr( $default_section ); ?>"><?php echo ucwords( $default_section ); ?></label><br/>
					<?php
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
			$sections    = isset( $instance['sections'] ) ? $instance['sections'] : array();
			if ( ! is_array( $sections ) ) {
				$sections = array();
			}

			$sections = implode( ',', $sections );


			if ( $petition_id == 0 ) {
				echo '<p class="cbxpetition-info cbxpetition-info-notfound">' . esc_html__( 'No valid petition id found.', 'cbxpetition' ) . '</p>';
			} else {

				$output = '';

				$petition = get_post( $petition_id );

				if ( $petition !== null ) {

					$atts                = array();
					$atts['sections']    = $sections;
					$atts['petition_id'] = $petition_id;

					$sections   = sanitize_text_field( $sections );
					$sections_t = explode( ',', $sections );
					$sections   = array();

					foreach ( $sections_t as $section ) {
						$section    = trim( strtolower( $section ) );
						$sections[] = $section;
					}

					$output .= apply_filters( 'cbxpetition_summary_before', '', $petition_id, $atts );
					$output .= '<div class="cbxpetition_summary_wrap">';
					$output .= apply_filters( 'cbxpetition_summary_start', '', $petition_id, $atts );

					$post_title = get_the_title( $petition_id );
					$post_link  = get_permalink( $petition_id );

					if ( in_array( 'title', $sections ) ) {
						//title
						$output .= '<h2 class="cbxpetition_summary_title"><a href="' . esc_url( $post_link ) . '">' . esc_attr( $post_title ) . '</a></h2>';
					}

					if ( in_array( 'content', $sections ) ) {
						$post_content = $petition->post_content;

						$post_content = apply_filters( 'the_content', $post_content );
						$post_content = str_replace( ']]>', ']]&gt;', $post_content );

						//https://wordpress.stackexchange.com/questions/245046/format-content-value-from-db-outside-of-wordpress-filters/245057#245057
						$post_content = strip_shortcodes( $post_content );
						$post_content = wp_trim_words( $post_content );

						//description
						if ( $post_content != '' ) {
							$output .= '<div class="cbxpetition_summary_content">';
							$output .= wpautop( $post_content );
							$output .= '</div>';
						}
					}


					if ( in_array( 'stat', $sections ) ) {
						$target          = intval( CBXPetitionHelper::petitionSignatureTarget( $petition_id ) );
						$signature_count = intval( CBXPetitionHelper::petitionSignatureCount( $petition_id ) );
						$signature_ratio = floatval( CBXPetitionHelper::petitionSignatureTargetRatio( $petition_id ) );


						ob_start();
						$show_count    = 1;
						$show_progress = 1;

						include( cbxpetition_locate_template( 'public-petition-stat.php' ) );
						$output .= ob_get_contents();
						ob_end_clean();
					}


					if ( in_array( 'expire_date', $sections ) ) {
						$expire_date = get_post_meta( $petition_id, '_cbxpetition_expire_date', true );

						$expire_info = '';

						if ( $expire_date == '' ) {
							$expire_info = esc_html__( 'Sorry, Petition expire date is not set yet.', 'cbxpetition' );
						} elseif ( $expire_date != '' ) {

							$expire_date = new DateTime( $expire_date );
							$now_date    = new DateTime( 'now' );

							$date_format      = get_option( 'date_format' );
							$time_format      = get_option( 'time_format' );
							$date_time_format = $date_format;


							if ( $expire_date < $now_date ) {
								$expire_info = sprintf( esc_html__( 'Sorry, petition already expired on %s', 'cbxpetition' ), $expire_date->format( 'Y-m-d H:i:s' ) );
							} else {
								$expire_info = $expire_date->format( apply_filters( '', $date_time_format, $date_format, $time_format ) );
							}
						}
						$output .= '<p>' . esc_html__( 'Expire Date', 'cbxpetition' ) . ' : ' . $expire_info . '</p>';
					}


					$output .= apply_filters( 'cbxpetition_summary_end', '', $petition_id, $atts );
					$output .= '</div>';
					$output .= apply_filters( 'cbxpetition_summary_after', '', $petition_id, $atts );
				}

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
			$instance['sections']    = isset( $new_instance['sections'] ) ? $new_instance['sections'] : array();

			if ( ! is_array( $instance['sections'] ) ) {
				$instance['sections'] = array();
			}

			return $instance;
		}// end of update method

	}//end class CBXPetitionSummaryWidget
endif;