<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBXPetition
 * @subpackage CBXPetition/public
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    CBXPetition
 * @subpackage CBXPetition/public
 * @author     Codeboxr <info@codeboxr.com>
 */
class CBXPetition_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of the plugin.
	 * @param      string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		//get instance of setting api
		$this->settings_api = new CBXPetition_Settings();
	}

	/**
	 * Init session
	 */
	public function init_session() {

		//if session is not started, let's start it
		/*if ( ! session_id() ) {
			session_start();
		}*/

		/**
		 * Start sessions if not exists
		 *
		 * @author     Ivijan-Stefan Stipic <creativform@gmail.com>
		 */
		if ( version_compare( PHP_VERSION, '7.0.0', '>=' ) ) {
			if ( function_exists( 'session_status' ) && session_status() == PHP_SESSION_NONE ) {
				session_start( array(
					'cache_limiter'  => 'private_no_expire',
					'read_and_close' => false,
				) );
			}
		} elseif ( version_compare( PHP_VERSION, '5.4.0', '>=' ) && version_compare( PHP_VERSION, '7.0.0', '<' ) ) {
			if ( function_exists( 'session_status' ) && session_status() == PHP_SESSION_NONE ) {
				session_cache_limiter( 'private_no_expire' );
				session_start();
			}
		} else {
			if ( session_id() == '' ) {
				if ( version_compare( PHP_VERSION, '4.0.0', '>=' ) ) {
					session_cache_limiter( 'private_no_expire' );
				}
				session_start();
			}
		}

	}//end method init_session

	public function auto_integration( $content ) {
		global $post;

		$post_type = $post->post_type;
		if ( $post_type !== 'cbxpetition' ) {
			return $content;
		}

		$settings = $this->settings_api;

		$before_content = '';
		$after_content  = '';

		$enable_auto_integration = $settings->get_option( 'enable_auto_integration', 'cbxpetition_general', 'on' );

		if ( $enable_auto_integration == 'on' && is_singular( 'cbxpetition' ) ) {

			$auto_integration_before = $settings->get_option( 'auto_integration_before', 'cbxpetition_general', array() );
			$auto_integration_after  = $settings->get_option( 'auto_integration_after', 'cbxpetition_general', array() );

			//$before_content .= do_shortcode( '[cbxpetition_banner]' );
			//$before_content .= do_shortcode( '[cbxpetition_stat]' );
			if ( is_array( $auto_integration_before ) && sizeof( $auto_integration_before ) > 0 ) {
				foreach ( $auto_integration_before as $short_key ) {

					$before_content .= do_shortcode( '[' . esc_html( $short_key ) . ']' );
				}
			}


			/*$after_content .= do_shortcode( '[cbxpetition_signform]' );
			$after_content .= do_shortcode( '[cbxpetition_video]' );
			$after_content .= do_shortcode( '[cbxpetition_photos]' );
			$after_content .= do_shortcode( '[cbxpetition_letter]' );
			$after_content .= do_shortcode( '[cbxpetition_signatures]' );*/

			if ( is_array( $auto_integration_after ) && sizeof( $auto_integration_after ) > 0 ) {
				foreach ( $auto_integration_after as $short_key ) {
					if (in_array( $short_key, $auto_integration_before ) ) {
						continue;
					}

					$after_content .= do_shortcode( '[' . esc_html( $short_key ) . ']' );
				}
			}
		}

		return $before_content . $content . $after_content;

	}//end method auto_integration;

	/**
	 * Init all shortcodes
	 */
	public function init_shortcodes() {
		//add shortcode
		add_shortcode( 'cbxpetition', array( $this, 'cbxpetition_display' ) );
		add_shortcode( 'cbxpetition_summary', array( $this, 'cbxpetition_summary_display' ) );

		add_shortcode( 'cbxpetition_signform', array( $this, 'cbxpetition_signform_display' ) );
		add_shortcode( 'cbxpetition_video', array( $this, 'cbxpetition_video_display' ) );
		add_shortcode( 'cbxpetition_photos', array( $this, 'cbxpetition_photos_display' ) );
		add_shortcode( 'cbxpetition_letter', array( $this, 'cbxpetition_letter_display' ) );
		add_shortcode( 'cbxpetition_banner', array( $this, 'cbxpetition_banner_display' ) );
		add_shortcode( 'cbxpetition_signatures', array( $this, 'cbxpetition_signature_display' ) );
		add_shortcode( 'cbxpetition_stat', array( $this, 'cbxpetition_stat_display' ) );
	}//end method init_shortcodes

	/**
	 * Register Widget
	 */
	public function init_widgets() {

		require_once plugin_dir_path(dirname(__FILE__)) . 'widgets/classic_widgets/class-cbxpetition-summary-widget.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'widgets/classic_widgets/class-cbxpetition-signform-widget.php';

		register_widget( 'CBXPetitionSummaryWidget' ); //petition summary widget
		register_widget( 'CBXPetitionSignformWidget' ); //petition signform widget

		/*register_widget( 'CBXPetitionLatestWidget' ); //latest petition widget
		register_widget( 'PetitionsToExpireWidget' ); //petition about to expire widget
		register_widget( 'CompletedPetitionsWidget' ); //recently completed petitions widget
		*/
	}//end method init_widgets


	/**
	 * Petition details shortcode callback function
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	public function cbxpetition_display($atts){

		global $post;

		$petition_id = ( $post->post_type === 'cbxpetition' ) ? intval( $post->ID ) : 0;

		// normalize attribute keys, lowercase
		$atts = array_change_key_case((array)$atts, CASE_LOWER);

		$atts = shortcode_atts( array(
			'petition_id' => $petition_id,
			'sections' => apply_filters('cbxpetition_shortcode_default_sections', 'cbxpetition_banner,cbxpetition_stat,cbxpetition_video,cbxpetition_photos,cbxpetition_letter,cbxpetition_signform,cbxpetition_signatures')
		),$atts,'cbxpetition' );

		extract( $atts );

		$user_id = intval( get_current_user_id() );

		$petition_id = intval($petition_id);

		if ( $petition_id == 0 ) {
			return '<p class="cbxpetition-info cbxpetition-info-notfound">' . esc_html__( 'No valid petition id found.',
					'cbxpetition' ) . '</p>';
		}
		else{

			$sections = sanitize_text_field($sections);
			$sections = explode(',', $sections);

			$output = '';


			if(is_array($sections) && sizeof($sections) > 0){
				$output .= apply_filters('cbxpetition_details_before', '', $petition_id, $atts);

				$output .= '<div class="cbxpetition_details_wrap">';

				$output .= apply_filters('cbxpetition_details_start', '', $petition_id, $atts);

				foreach ($sections as $section){
					$section = trim(strtolower($section));
					$output .= do_shortcode('['.$section.' petition_id="'.$petition_id.'"]');
				}
				$output .= apply_filters('cbxpetition_details_end', '', $petition_id, $atts);

				$output .= '</div>';

				$output .= apply_filters('cbxpetition_details_after', '', $petition_id, $atts);
			}

			return $output;
		}

	}//end method cbxpetition_display

	/**
	 * Petition Summary display
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	public function cbxpetition_summary_display($atts){
		global $post;

		$petition_id = ( $post->post_type === 'cbxpetition' ) ? intval( $post->ID ) : 0;

		// normalize attribute keys, lowercase
		$atts = array_change_key_case((array)$atts, CASE_LOWER);

		$atts = shortcode_atts( array(
			'petition_id' => $petition_id,
			'sections' => apply_filters('cbxpetition_summary_shortcode_default_sections', 'title,content,stat,expire_date')
		),$atts,'cbxpetition_summary' );

		extract( $atts );

		//$user_id = intval( get_current_user_id() );

		$petition_id = intval($petition_id);

		if ( $petition_id == 0 ) {
			return '<p class="cbxpetition-info cbxpetition-info-notfound">' . esc_html__( 'No valid petition id found.',
					'cbxpetition' ) . '</p>';
		}
		else{

			$output = '';

			$petition           = get_post( $petition_id );

			if($petition !== null) {

				$sections = sanitize_text_field($sections);
				$sections_t = explode(',', $sections);
				$sections = array();

				foreach ($sections_t as $section){
					$section = trim(strtolower($section));
					$sections[] = $section;
				}

				$output .= apply_filters('cbxpetition_summary_before', '', $petition_id, $atts);
				$output .= '<div class="cbxpetition_summary_wrap">';
				$output .= apply_filters('cbxpetition_summary_start', '', $petition_id, $atts);

				$post_title = get_the_title($petition_id);
				$post_link = get_permalink($petition_id);

				if(in_array('title', $sections)){
					//title
					$output .= '<h2 class="cbxpetition_summary_title"><a href="'.esc_url($post_link).'">'.esc_attr($post_title).'</a></h2>';
				}

				if(in_array('content', $sections)){
					$post_content = $petition->post_content;

					$post_content = apply_filters('the_content', $post_content);
					$post_content = str_replace(']]>', ']]&gt;', $post_content);

					//https://wordpress.stackexchange.com/questions/245046/format-content-value-from-db-outside-of-wordpress-filters/245057#245057
					$post_content = strip_shortcodes($post_content);
					$post_content = wp_trim_words($post_content);

					//description
					if($post_content != ''){
						$output .= '<div class="cbxpetition_summary_content">';
						$output .= wpautop($post_content);
						$output .= '</div>';
					}
				}


				if(in_array('stat', $sections)){
					$target          = intval( CBXPetitionHelper::petitionSignatureTarget( $petition_id ) );
					$signature_count = intval( CBXPetitionHelper::petitionSignatureCount( $petition_id ) );
					$signature_ratio = floatval( CBXPetitionHelper::petitionSignatureTargetRatio( $petition_id ) );


					ob_start();
					$show_count = 1;
					$show_progress = 1;

					include( cbxpetition_locate_template( 'public-petition-stat.php' ) );
					$output .= ob_get_contents();
					ob_end_clean();
				}


				if(in_array('expire_date', $sections)){
					$expire_date = get_post_meta( $petition_id, '_cbxpetition_expire_date', true );

					$expire_info = '';

					if ( $expire_date == '' ) {
						$expire_info =  esc_html__( 'Sorry, Petition expire date is not set yet.', 'cbxpetition' );
					} elseif ( $expire_date != '' ) {

						$expire_date = new DateTime( $expire_date );
						$now_date    = new DateTime( 'now' );

						$date_format = get_option('date_format');
						$time_format = get_option('time_format');
						$date_time_format = $date_format;


						if ( $expire_date < $now_date ) {
							$expire_info = sprintf(esc_html__( 'Sorry, petition already expired on %s','cbxpetition' ), $expire_date->format('Y-m-d H:i:s')) ;
						}
						else $expire_info = $expire_date->format(apply_filters('', $date_time_format, $date_format, $time_format));
					}
					$output .= '<p>'.esc_html__('Expire Date', 'cbxpetition').' : '.$expire_info.'</p>';
				}


				$output .= apply_filters('cbxpetition_summary_end', '', $petition_id, $atts);
				$output .= '</div>';
				$output .= apply_filters('cbxpetition_summary_after', '', $petition_id, $atts);
			}

			return $output;
		}

	}//end method cbxpetition_summary_display

	/**
	 * Shortcode callback for petition sign form
	 */
	public function cbxpetition_signform_display( $atts ) {
		global $post;

		// normalize attribute keys, lowercase
		$atts = array_change_key_case((array)$atts, CASE_LOWER);

		$petition_id = ( $post->post_type === 'cbxpetition' ) ? intval( $post->ID ) : 0;

		$atts = shortcode_atts( array(
			'petition_id' => $petition_id,
		),$atts, 'cbxpetition_signform' );


		extract( $atts );

		$user_id = intval( get_current_user_id() );

		if ( $petition_id == 0 ) {
			return '<p class="cbxpetition-info cbxpetition-info-notfound">' . esc_html__( 'No valid petition id found.',
					'cbxpetition' ) . '</p>';
		}

		$expire_date = get_post_meta( $petition_id, '_cbxpetition_expire_date', true );

		if ( $expire_date == '' ) {
			return '<p class="cbxpetition-info cbxpetition-info-datenotset">' . esc_html__( 'Sorry, petition did not start yet.', 'cbxpetition' ) . '</p>';
		} elseif ( $expire_date != '' ) {

			$expire_date = new DateTime( $expire_date );
			$now_date    = new DateTime( 'now' );


			if ( $expire_date < $now_date ) {
				return '<p class="cbxpetition-info cbxpetition-info-alreadysigned">' . esc_html__( 'Sorry, petition already expired',
						'cbxpetition' ) . '</p>';
			}

		}

		$is_petition_signed_by_user = CBXPetitionHelper::isPetitionSignedByUser( $petition_id, $user_id );

		if ( $is_petition_signed_by_user !== false ) {
			return '<p class="cbxpetition-info cbxpetition-info-alreadysigned">' . esc_html__( 'You signed the petition, thank you.',
					'cbxpetition' ) . '</p>';
		}

		$output = '';
		ob_start();
		include( cbxpetition_locate_template( 'public-sign-form.php' ) );
		$output = ob_get_contents();
		ob_end_clean();

		return $output;

	}//end method cbxpetition_signform_display

	/**
	 * Shortcode callback for petition video display
	 *
	 * @param $atts
	 *
	 * @return false|string
	 */
	public function cbxpetition_video_display( $atts ) {
		global $post;

		// normalize attribute keys, lowercase
		$atts = array_change_key_case((array)$atts, CASE_LOWER);

		$petition_id = ( $post->post_type === 'cbxpetition' ) ? intval( $post->ID ) : 0;

		$atts = shortcode_atts( array(
			'petition_id' => $petition_id,
		),
			$atts,
			'cbxpetition_video' );


		extract( $atts );

		if ( $petition_id == 0 ) {
			return esc_html__( 'No valid petition id found.', 'cbxpetition' );
		}

		$videos = CBXPetitionHelper::petitionVideoInfo( $petition_id );

		$output = '';
		ob_start();
		include( cbxpetition_locate_template( 'public-petition-video.php' ) );
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}//end method cbxpetition_video_display

	/**
	 * Shortcode call back for petition photos display
	 *
	 * @param $atts
	 *
	 * @return false|string
	 */
	public function cbxpetition_photos_display( $atts ) {
		global $post;

		// normalize attribute keys, lowercase
		$atts = array_change_key_case((array)$atts, CASE_LOWER);

		$petition_id = ( $post->post_type === 'cbxpetition' ) ? intval( $post->ID ) : 0;

		$atts = shortcode_atts( array(
			'petition_id' => $petition_id,
		),
			$atts,
			'cbxpetition_photos' );


		extract( $atts );

		if ( $petition_id == 0 ) {
			return esc_html__( 'No valid petition id found.', 'cbxpetition' );
		}

		$petition_photos = CBXPetitionHelper::petitionPhotos( $petition_id );

		$output = '';
		ob_start();
		include( cbxpetition_locate_template( 'public-petition-photos.php' ) );
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}//end method cbxpetition_photos_display

	/**
	 * Shortcode call back for petition letter display
	 *
	 * @param $atts
	 *
	 * @return false|string
	 */
	public function cbxpetition_letter_display( $atts ) {
		global $post;

		// normalize attribute keys, lowercase
		$atts = array_change_key_case((array)$atts, CASE_LOWER);

		$petition_id = ( $post->post_type === 'cbxpetition' ) ? intval( $post->ID ) : 0;

		$atts = shortcode_atts( array(
			'petition_id' => $petition_id,
		),
			$atts,
			'cbxpetition_letter' );


		extract( $atts );

		if ( $petition_id == 0 ) {
			return esc_html__( 'No valid petition id found.', 'cbxpetition' );
		}

		$petition_letter = CBXPetitionHelper::petitionLetterInfo( $petition_id );


		$output = '';
		ob_start();
		include( cbxpetition_locate_template( 'public-petition-letter.php' ) );
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}//end method cbxpetition_letter_display

	/**
	 * Shortcode call back for petition banner display
	 *
	 * @param $atts
	 *
	 * @return false|string
	 */
	public function cbxpetition_banner_display( $atts ) {
		global $post;
		// normalize attribute keys, lowercase
		$atts = array_change_key_case((array)$atts, CASE_LOWER);

		$petition_id = ( $post->post_type === 'cbxpetition' ) ? intval( $post->ID ) : 0;

		$atts = shortcode_atts( array(
			'petition_id' => $petition_id,
		),
			$atts,
			'cbxpetition_banner' );


		extract( $atts );

		if ( $petition_id == 0 ) {
			return esc_html__( 'No valid petition id found.', 'cbxpetition' );
		}

		$cbxpetition_banner = cbxpetition_petitionBannerImage( $petition_id );


		$output = '';
		ob_start();
		include( cbxpetition_locate_template( 'public-petition-banner.php' ) );
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}//end method cbxpetition_banner_display

	/**
	 * Shortcode call back for petition signature display
	 *
	 * @param $atts
	 *
	 * @return false|string
	 */
	public function cbxpetition_signature_display( $atts ) {
		global $post;

		// normalize attribute keys, lowercase
		$atts = array_change_key_case((array)$atts, CASE_LOWER);

		$settings = $this->settings_api;

		$perpage     = $settings->get_option( 'sign_limit', 'cbxpetition_general', 20 );
		$petition_id = ( $post->post_type === 'cbxpetition' ) ? intval( $post->ID ) : 0;

		$atts = shortcode_atts( array(
			'petition_id' => $petition_id,
			'perpage'     => intval( $perpage ),
			'order'       => 'DESC',
			'orderby'     => 'id',
		),
			$atts,
			'cbxpetition_signatures' );


		extract( $atts );

		if ( $petition_id == 0 ) {
			return esc_html__( 'No valid petition id found.', 'cbxpetition' );
		}


		$page           = 1;
		$petition_signs = CBXPetitionHelper::getSignListingData( '',
			$petition_id,
			0,
			'approved',
			'DESC',
			'id',
			$perpage,
			$page );
		$petition_count = CBXPetitionHelper::getSignListingDataCount( '', $petition_id, 0, 'approved', $perpage, $page );

		$output = '';

		ob_start();
		include( cbxpetition_locate_template( 'public-petition-signatures.php' ) );
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}//end method cbxpetition_signature_display


	/**
	 * Shortcode call back for petition stat display
	 *
	 * @param $atts
	 *
	 * @return false|string
	 */
	public function cbxpetition_stat_display( $atts ) {
		global $post;

		// normalize attribute keys, lowercase
		$atts = array_change_key_case((array)$atts, CASE_LOWER);

		$settings    = $this->settings_api;
		$petition_id = ( $post->post_type === 'cbxpetition' ) ? intval( $post->ID ) : 0;

		$atts = shortcode_atts( array(
			'petition_id'   => $petition_id,
			'show_count'    => 1,
			'show_progress' => 1,
		),
			$atts,
			'cbxpetition_stat' );

		extract( $atts );

		if ( $petition_id == 0 ) {
			return esc_html__( 'No valid petition id found.', 'cbxpetition' );
		}

		$target          = intval( CBXPetitionHelper::petitionSignatureTarget( $petition_id ) );
		$signature_count = intval( CBXPetitionHelper::petitionSignatureCount( $petition_id ) );
		$signature_ratio = floatval( CBXPetitionHelper::petitionSignatureTargetRatio( $petition_id ) );

		$output = '';
		ob_start();
		include( cbxpetition_locate_template( 'public-petition-stat.php' ) );
		$output = ob_get_contents();
		ob_end_clean();

		return $output;

	}//end method cbxpetition_stat_display

	/**
	 * Signature ajax listing load more
	 */
	public function petition_load_more_signs() {
		check_ajax_referer( 'cbxpetition_nonce', 'security' );

		$settings = $this->settings_api;
		$perpage  = $settings->get_option( 'sign_limit', 'cbxpetition_general', 20 );

		$submit_data = wp_unslash( $_REQUEST ); //all fields are sanitized below

		$petition_id = isset( $submit_data['petition_id'] ) ? intval( $submit_data['petition_id'] ) : 0;
		$page        = isset( $submit_data['page'] ) ? intval( $submit_data['page'] ) : 1;
		$perpage     = isset( $submit_data['perpage'] ) ? intval( $submit_data['perpage'] ) : $perpage;
		$order       = isset( $submit_data['order'] ) ? sanitize_text_field( $submit_data['page'] ) : 'DESC';
		$orderby     = isset( $submit_data['page'] ) ? sanitize_text_field( $submit_data['orderby'] ) : 'id';

		if ( ! in_array( $order, array( 'DESC', 'ASC' ) ) ) {
			$order = 'DESC';
		}

		$output = '';
		ob_start();
		if ( $petition_id > 0 && $page > 1 ) {
			$petition_signs = CBXPetitionHelper::getSignListingData( '',
				$petition_id,
				0,
				'approved',
				$order,
				$orderby,
				$perpage,
				$page );
			if ( is_array( $petition_signs ) && sizeof( $petition_signs ) > 0 ) {
				foreach ( $petition_signs as $petition_sign ) {
					include( cbxpetition_locate_template( 'public-petition-signature.php' ) );
				}
			}
		}

		$output = ob_get_contents();
		ob_end_clean();

		$response = array(
			'listing' => $output,
		);

		echo json_encode( $response );
		wp_die();
	}//end method petition_load_more_signs


	/**
	 * store petition sign by ajax request
	 */
	public function petition_sign_submit() {
		//if frontend sign submit and also nonce verified then go
		if ( ( isset( $_POST['cbxpetition_sign_submit'] ) && intval( $_POST['cbxpetition_sign_submit'] ) == 1 ) &&
		     ( isset( $_POST['cbxpetition_token'] ) && wp_verify_nonce( $_POST['cbxpetition_token'],
				     'cbxpetition_nonce' ) )
		) {

			$settings = $this->settings_api;

			global $wpdb;


			$petition_signs_table = $wpdb->prefix . "cbxpetition_signs";
			$post_data            = wp_unslash( $_POST ); //data is sanitized later below using $post_data

			$user_id = 0;
			$guest   = true;

			if ( is_user_logged_in() ) {
				$guest = false;

				$current_user      = wp_get_current_user();
				$user_id           = intval( $current_user->ID );
				//$user_display_name = $current_user->display_name;
				//$user_display_name = CBXPetitionHelper::userDisplayNameAlt($current_user, $user_display_name);

				$first_name = $current_user->first_name;
				$last_name  = $current_user->last_name;

				if ( $first_name == '' && $last_name == '' ) {
					$first_name = $current_user->display_name;
				}

				$email = $current_user->user_email;

			} else {
				$first_name = isset( $post_data['fname'] ) ? sanitize_text_field( wp_unslash( $post_data['fname'] ) ) : '';
				$last_name  = isset( $post_data['lname'] ) ? sanitize_text_field( wp_unslash( $post_data['lname'] ) ) : '';
				$email      = isset( $post_data['email'] ) ? sanitize_email( $post_data['email'] ) : '';
			}

			$privacy = isset( $post_data['privacy'] ) ? intval( $post_data['privacy'] ) : 0;

			// sanitization
			$petition_id = isset( $post_data['id'] ) ? intval( $post_data['id'] ) : 0;
			$comment     = isset( $post_data['comment'] ) ? sanitize_textarea_field( $post_data['comment'] ) : '';

			$page_url = home_url( add_query_arg( null, null ) );

			// validation
			$hasError          = false;
			$validation_errors = [];

			if ( $petition_id == 0 ) {
				$validation_errors['top_errors'][] = esc_html__( 'Invalid petition, petition doesn\'t exists or expired.', 'cbxpetition' );
			} else {
				if ( $guest ) {
					if ( strlen( $first_name ) < 2 ) {
						$validation_errors['cbxpetition-fname'] = esc_html__( 'First name is required and needs at least 3 characters.', 'cbxpetition' );
					}

					if ( strlen( $last_name ) < 2 ) {
						$validation_errors['cbxpetition-lname'] = esc_html__( 'Last name is required and needs at least 3 characters.', 'cbxpetition' );
					}
				}


				if ( ! is_email( $email ) ) {
					$validation_errors['cbxpetition-email'] = esc_html__( 'Email is required and needs valid email address', 'cbxpetition' );
				} elseif ( ! is_user_logged_in() ) {
					if ( email_exists( $email ) ) {
						$validation_errors['cbxpetition-email'] = esc_html__( 'Email already exists to any registered user', 'cbxpetition' );
					} elseif ( CBXPetitionHelper::isPetitionSignedByGuest( $petition_id, $email ) ) {
						$validation_errors['cbxpetition-email'] = esc_html__( 'This petition has been signed using this email', 'cbxpetition' );
					}
				}


				if ( $privacy == 0 ) {
					$validation_errors['cbxpetition-privacy'] = esc_html__( 'Email already exists to any registered user', 'cbxpetition' );
				}
			}

			$validation_errors = apply_filters( 'cbxpetition_sign_validation_errors',
				$validation_errors,
				$post_data,
				$petition_id );

			if ( sizeof( $validation_errors ) > 0 ) {

				//if ajax
				if ( isset( $_POST['ajax'] ) && $_POST['ajax'] == true ) {
					$response_validation_errors['error'] = $validation_errors;
					echo wp_json_encode( $response_validation_errors ); //ajax
					wp_die(); //ajax
				} else {
					$_SESSION['cbxpetition_validation_errors'] = $validation_errors;

					wp_safe_redirect( $page_url );
					exit;
				}
			}

			//data validated and now good to add/update

			$data_safe['petition_id'] = $petition_id;
			$data_safe['f_name']      = $first_name;
			$data_safe['l_name']      = $last_name;
			$data_safe['email']       = $email;
			$data_safe['comment']     = $comment;

			$default_state    = $settings->get_option( 'default_state', 'cbxpetition_general', 'approved' );
			$guest_activation = $settings->get_option( 'guest_activation', 'cbxpetition_general', '' );

			$activation_code = null;
			//$cbxpetition_sign_form = get_option( 'cbxpetition_general', null );

			if ( $guest_activation == 'on' && $user_id == 0 ) {
				$default_state   = 'unverified';
				$activation_code = wp_generate_password( $length = 12,
					false,
					false );//used for email activation, if email activation enabled then we use it, after activation we delete this value like password activation

			}

			$data_safe['state']      = $default_state;
			$data_safe['activation'] = $activation_code;

			//insert
			$data_safe['add_by']   = $user_id;
			$data_safe['add_date'] = current_time( 'mysql' );

			$data_safe = apply_filters( 'cbxpetition_sign_data_before_insert', $data_safe, $petition_id );

			$col_data_format = array(
				'%d', //petition_id
				'%s', //f_name
				'%s', //l_name
				'%s', //email
				'%s', //comment
				'%s', //state
				'%s', //activation
				'%d', //add_by
				'%s'  //add_date
			);
			$col_data_format = apply_filters( 'cbxpetition_sign_col_data_format_before_insert',
				$col_data_format,
				$data_safe,
				$petition_id );

			$success_arr  = array();
			$error_arr    = array();
			$response_arr = array();

			do_action( 'cbxpetition_sign_before_insert', $petition_id, $data_safe );

			$show_form = 1;

			if ( $wpdb->insert( $petition_signs_table, $data_safe, $col_data_format ) !== false ) {
				$show_form = 0;

				$sign_id         = $wpdb->insert_id;
				$data_safe['id'] = $sign_id;

				$log_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $petition_signs_table WHERE id = %d", $sign_id ),
					ARRAY_A );

				do_action( 'cbxpetition_sign_after_insert', $petition_id, $data_safe, $sign_id );

				$single_message = array(
					'text' => esc_html__( 'Your signature request stored successfully. Thank you!', 'cbxpetition' ),
					'type' => 'success',
				);
				$success_arr[]  = $single_message;

				// mail part

				// admin mail
				$admin_email_alert = $settings->get_option( 'status', 'cbxpetition_email_admin', '' );

				if ( $admin_email_alert == 'on' ) {
					$admin_email_status = CBXPetitionMailAlert::sendSignAdminEmailAlert( $log_data );
				}

				// user mail
				$user_email_alert = $settings->get_option( 'new_status', 'cbxpetition_email_user', '' );
				if ( $user_email_alert == 'on' ) {
					$user_email_status = CBXPetitionMailAlert::sendSignUserEmailAlert( $log_data );

					if ( $user_email_status === true ) {

						$single_message = array(
							'text' => esc_html__( 'Confirmation Email sent Successfully.', 'cbxpetition' ),
							'type' => 'success',
						);
						$success_arr[]  = $single_message;
					} else {
						$single_message = array(
							'text' => esc_html__( 'Confirmation Email sent failed. ', 'cbxpetition' ),
							'type' => 'danger',
						);
						$error_arr[]    = $single_message;
					}
				}


			} else {
				//failed to insert
				$single_message = array(
					'text' => esc_html__( 'Sorry! Problem during signing request, please check again and try again.',
						'cbxpetition' ),
					'type' => 'danger',
				);
				$error_arr[]    = $single_message;
			}

			$success_arr = apply_filters( 'cbxpetition_sign_success_messages', $success_arr, $petition_id );
			$error_arr   = apply_filters( 'cbxpetition_sign_error_messages', $error_arr, $petition_id );

			$response_arr['success_arr']['messages'] = $success_arr;
			$response_arr['error_arr']['messages']   = $error_arr;
			$response_arr['show_form']               = $show_form;

			if ( isset( $_POST['ajax'] ) && $_POST['ajax'] == true ) {
				echo wp_json_encode( $response_arr ); //ajax
				wp_die(); //ajax
			} else {
				$_SESSION['cbxpetition_validation_success'] = $response_arr;

				wp_safe_redirect( $page_url );
				exit;
			}
		}
	}//end method petition_sign_submit

	/**
	 * public template_redirect callback to process guest email activation
	 */
	public function guest_email_validation() {
		//Guest email verification: if guest email user redirect back to site by clicking activation link
		if ( isset( $_GET['cbxpetitionsign_verification'] ) && intval( $_GET['cbxpetitionsign_verification'] ) == 1 && isset( $_GET['activation_code'] ) && $_GET['activation_code'] != '' ) {

			$settings = $this->settings_api;
			global $wpdb;
			$petition_signs_table = $wpdb->prefix . "cbxpetition_signs";

			$activation_code = sanitize_text_field( wp_unslash($_GET['activation_code']) );

			$sign_info = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $petition_signs_table WHERE activation = %s",
				$activation_code )
			);

			//if sign log found
			if ( $sign_info !== null ) {

				$log_id      = intval( $sign_info->id );
				$petition_id = intval( $sign_info->petition_id );

				//$default_state         = 'pending';
				$default_state = $settings->get_option( 'default_state', 'cbxpetition_general', 'approved' );


				$update_status = $wpdb->update(
					$petition_signs_table,
					array(
						'activation' => '',
						'state'      => $default_state,
						'mod_date'   => current_time( 'mysql' ),
					),
					array(
						'activation' => $activation_code,
						'id'         => $log_id,

					),
					array(
						'%s',
						'%s',
						'%s',
					),
					array(
						'%s',
						'%d',
					)
				);

				//sign log found and updated
				if ( $update_status !== false && intval( $update_status ) > 0 ) {
					echo sprintf( __( 'Signature validated successfully. No email will be sent to inform this. Site admin will check your request and signature confirmation will be set as per system setting. <a href="%s">Click to go petition page</a>.',
						'cbxpetition' ),
						get_permalink( $petition_id ) );
					exit();
				} else {
					//failed to update sign log
					echo sprintf( __( 'Sorry, signature found but validation failed.<a href="%s">Click to go petition page</a>.',
						'cbxpetition' ),
						get_permalink( $petition_id ) );
					exit();
				}

			} else {
				//sign log not found or already activated
				echo sprintf( __( 'Sorry, signature not found or already validated. <a href="%s">Click to go home</a>.',
					'cbxpetition' ),
					home_url() );
				exit();
			}
		}
	}//end method guest_email_validation




	/**
	 * add custom post type cbxpetition to recent posts widgets
	 *
	 * @param $params
	 *
	 * @return mixed
	 */
	/*public function widget_posts_args_add_custom_cbxpetition( $params ) {
		$params['post_type'] = array( 'post', 'cbxpetition' );

		return $params;
	}*/


	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		CBXPetitionHelper::cbxpetition_public_styles();

	}//end method enqueue_styles

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		CBXPetitionHelper::cbxpetition_public_scripts();

	}//end method enqueue_scripts

}//end class CBXPetition_Public
