<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBXPetition
 * @subpackage CBXPetition/admin
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    CBXPetition
 * @subpackage CBXPetition/admin
 * @author     Codeboxr <info@codeboxr.com>
 */
class CBXPetition_Admin {

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
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		//get plugin base file name
		$this->plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $plugin_name . '.php' );

		//get instance of setting api
		$this->settings_api = new CBXPetition_Settings();
	}

	/**
	 * Create petition custom post type, taxonomies
	 */
	public function create_post_type() {
		CBXPetitionHelper::create_cbxpetition_post_type();

		// Check the option we set on activation.
		if ( get_option( 'cbxpetition_flush_rewrite_rules' ) == 'true' ) {
			flush_rewrite_rules();
			delete_option( 'cbxpetition_flush_rewrite_rules' );
		}

	}//end method create_cbxpetition_post_type


	/**
	 * Initialize setting
	 */
	public function admin_init() {
		//set the settings
		$this->settings_api->set_sections( $this->get_settings_sections() );
		$this->settings_api->set_fields( $this->get_settings_fields() );
		//initialize settings
		$this->settings_api->admin_init();

		//add_action( 'delete_post', array( $this, 'on_petition_delete_sign_delete' ), 10 );
		add_action( 'before_delete_post', array( $this, 'on_petition_delete_sign_delete' ), 10 );
	}//end method admin_init

	/**
	 * Global Setting Sections and titles
	 *
	 * @return type
	 */
	public function get_settings_sections() {
		return apply_filters( 'cbxpetition_setting_sections',
			array(
				array(
					'id'    => 'cbxpetition_general',
					'title' => esc_html__( 'Basic Setting', 'cbxpetition' ),
				),
				array(
					'id'    => 'cbxpetition_email',
					'title' => esc_html__( 'Global Email Template', 'cbxpetition' ),
				),
				array(
					'id'    => 'cbxpetition_email_admin',
					'title' => esc_html__( 'Admin Email Alert', 'cbxpetition' ),
				),
				array(
					'id'    => 'cbxpetition_email_user',
					'title' => esc_html__( 'User Email Alert', 'cbxpetition' ),
				),
				array(
					'id'    => 'cbxpetition_tools',
					'title' => esc_html__( 'Tools', 'cbxpetition' ),
				),
			) );
	}//end method get_settings_sections


	/**
	 * Global Setting Fields
	 *
	 * @return array
	 */
	public function get_settings_fields() {
		global $wpdb;

		$table_names = CBXPetitionHelper::allTablesArr();
		$table_html  = '<p id="cbxpetition_plg_gfig_info"><strong>' . esc_html__( 'Following database tables will be reset/deleted.', 'cbxpetition' ) . '</strong></p>';

		$table_counter = 1;

		foreach ( $table_names as $key => $value ) {
			$table_html .= '<p>' . str_pad( $table_counter, 2, '0', STR_PAD_LEFT ) . '. ' . $wpdb->prefix . $key . ' - (<code>' . $value . '</code>)</p>';
			$table_counter ++;
		}

		$table_html .= '<p><strong>' . esc_html__( 'Following option values created by this plugin will be deleted from wordpress option table',
				'cbxpetition' ) . '</strong></p>';


		$option_values = CBXPetitionHelper::getAllOptionNames();
		$table_counter = 1;
		foreach ( $option_values as $key => $value ) {
			$table_html .= '<p>' . str_pad( $table_counter, 2, '0', STR_PAD_LEFT ) . '. ' . $value['option_name'] . ' - ' . $value['option_id'] . ' - (<code style="overflow-wrap: break-word; word-break: break-all;">' . $value['option_value'] . '</code>)</p>';

			$table_counter ++;
		}

		$email_templates             = CBXPetitionHelper::email_templates();
		$email_template_admin        = $email_templates['new_sign_admin_email_alert'];
		$email_template_user_alert   = $email_templates['new_sign_user_email_alert'];
		$email_template_sign_approve = $email_templates['sign_approve_user_alert'];

		$settings_builtin_fields = array(
			'cbxpetition_general'     => array(
				'enable_auto_integration'          => array(
					'name'    => 'enable_auto_integration',
					'label'   => esc_html__( 'Enable Auto Integration', 'cbxpetition' ),
					'desc'    => esc_html__( 'Show petition features before or after content', 'cbxpetition' ),
					'type'    => 'checkbox',
					'default' => 'on',
				),
				'auto_integration_before'          => array(
					'name'     => 'auto_integration_before',
					'label'    => esc_html__( 'Auto Integration Before Content', 'cbxpetition' ),
					'desc'     => esc_html__( 'Which shortcode/blocks will be added before content', 'cbxpetition' ),
					'type'     => 'multicheck',
					'default'  => array( 'cbxpetition_banner', 'cbxpetition_stat' ),
					'options'  => apply_filters( 'cbxpetition_auto_integration_before',
						array(
							'cbxpetition_banner'     => esc_html__( 'Banner', 'cbxpetition' ),
							'cbxpetition_stat'       => esc_html__( 'Statistics', 'cbxpetition' ),
							'cbxpetition_video'      => esc_html__( 'Video', 'cbxpetition' ),
							'cbxpetition_photos'     => esc_html__( 'Photos', 'cbxpetition' ),
							'cbxpetition_letter'     => esc_html__( 'Letter', 'cbxpetition' ),
							'cbxpetition_signform'   => esc_html__( 'Signature Form', 'cbxpetition' ),
							'cbxpetition_signatures' => esc_html__( 'Signature Listing', 'cbxpetition' ),
						)
					),
					'sortable' => 1,
				),
				'auto_integration_after'           => array(
					'name'     => 'auto_integration_after',
					'label'    => esc_html__( 'Auto Integration After Content', 'cbxpetition' ),
					'desc'     => esc_html__( 'Which shortcode/blocks will be added after content', 'cbxpetition' ),
					'type'     => 'multicheck',
					'default'  => array(
						'cbxpetition_video',
						'cbxpetition_photos',
						'cbxpetition_letter',
						'cbxpetition_signform',
						'cbxpetition_signatures',
					),
					'options'  => apply_filters( 'cbxpetition_auto_integration_after',
						array(
							'cbxpetition_banner'     => esc_html__( 'Banner', 'cbxpetition' ),
							'cbxpetition_stat'       => esc_html__( 'Statistics', 'cbxpetition' ),
							'cbxpetition_video'      => esc_html__( 'Video', 'cbxpetition' ),
							'cbxpetition_photos'     => esc_html__( 'Photos', 'cbxpetition' ),
							'cbxpetition_letter'     => esc_html__( 'Letter', 'cbxpetition' ),
							'cbxpetition_signform'   => esc_html__( 'Signature Form', 'cbxpetition' ),
							'cbxpetition_signatures' => esc_html__( 'Signature Listing', 'cbxpetition' ),
						)
					),
					'sortable' => 1,

				),
				'default_state'                    => array(
					'name'    => 'default_state',
					'label'   => esc_html__( 'Default Sign Status', 'cbxpetition' ),
					'desc'    => esc_html__( 'What will be status when a new sign is requested?', 'cbxpetition' ),
					'type'    => 'select',
					'default' => 'approved',
					'options' => CBXPetitionHelper::getPetitionSignStates(),
				),
				'guest_activation'                 => array(
					'name'    => 'guest_activation',
					'label'   => esc_html__( 'Guest Email Activation', 'cbxpetition' ),
					'desc'    => __( 'Enable/Disable (To make this feature work need to enable user email notification on and user email template should have the tag syntax <code>{activation_link}</code>)',
						'cbxpetition' ),
					'type'    => 'checkbox',
					'default' => 'on',
				),
				'sign_limit'                       => array(
					'name'              => 'sign_limit',
					'label'             => esc_html__( 'Signature Listing Limit', 'cbxpetition' ),
					'desc'              => esc_html__( 'Default signature listing limit', 'cbxpetition' ),
					'type'              => 'text',
					'default'           => 20,
					'sanitize_callback' => 'absint',
				),
				'photos_information'               => array(
					'name'    => 'photos_information',
					'label'   => esc_html__( 'Petition Photo(s) Configuration', 'cbxpetition' ),
					'type'    => 'title',
					'default' => '',
				),
				'max_photo_limit'                  => array(
					'name'              => 'max_photo_limit',
					'label'             => esc_html__( 'Petition Photo Limit', 'cbxpetition' ),
					'desc'              => esc_html__( 'Maximum number of photos allowed in petition', 'cbxpetition' ),
					'type'              => 'text',
					'default'           => 10,
					'sanitize_callback' => 'absint',
				),
				'max_file_size'                    => array(
					'name'              => 'max_file_size',
					'label'             => esc_html__( 'Petition Photo Max File Size(MB)', 'cbxpetition' ),
					'desc'              => esc_html__( 'What will be the maximum photo size in MB?', 'cbxpetition' ),
					'type'              => 'text',
					'default'           => '1',
					'sanitize_callback' => 'absint',
				),
				'allowable_file_extensions'        => array(
					'name'    => 'allowable_file_extensions',
					'label'   => esc_html__( 'Petition Photo Extensions', 'cbxpetition' ),
					'desc'    => esc_html__( 'Photo extensions that are allowable to upload, if all unchecked then jpg, jpeg, gif, png are allowed.', 'cbxpetition' ),
					'type'    => 'file_extensions_checker',
					'default' => CBXPetitionHelper::imageExtArr(),
				),
				'thumb_max_width'                  => array(
					'name'              => 'thumb_max_width',
					'label'             => esc_html__( 'Petition Photo Thumbnail max width', 'cbxpetition' ),
					'desc'              => esc_html__( 'What will be the maximum width of thumbnail photo?', 'cbxpetition' ),
					'type'              => 'text',
					'default'           => '400',
					'sanitize_callback' => 'absint',
				),
				'thumb_max_height'                 => array(
					'name'              => 'thumb_max_height',
					'label'             => esc_html__( 'Petition Photo Thumbnail max height', 'cbxpetition' ),
					'desc'              => esc_html__( 'What will be the maximum height of thumbnail photo?', 'cbxpetition' ),
					'type'              => 'text',
					'default'           => '400',
					'sanitize_callback' => 'absint',
				),
				'photo_max_width'                  => array(
					'name'              => 'photo_max_width',
					'label'             => esc_html__( 'Petition Photo(s) max width', 'cbxpetition' ),
					'desc'              => esc_html__( 'What will be the maximum width of photo?', 'cbxpetition' ),
					'type'              => 'text',
					'default'           => 800,
					'sanitize_callback' => 'absint',
				),
				'photo_max_height'                 => array(
					'name'              => 'photo_max_height',
					'label'             => esc_html__( 'Petition Photo(s) max height', 'cbxpetition' ),
					'desc'              => esc_html__( 'What will be the maximum height of photo?', 'cbxpetition' ),
					'type'              => 'text',
					'default'           => 800,
					'sanitize_callback' => 'absint',
				),
				'banner_information'               => array(
					'name'    => 'banner_information',
					'label'   => esc_html__( 'Petition Banner Configuration', 'cbxpetition' ),
					'type'    => 'title',
					'default' => '',
				),
				'banner_max_file_size'             => array(
					'name'              => 'banner_max_file_size',
					'label'             => esc_html__( 'Petition Banner Max File Size(MB)', 'cbxpetition' ),
					'desc'              => esc_html__( 'What will be the maximum banner size in MB?', 'cbxpetition' ),
					'type'              => 'text',
					'default'           => 2,
					'sanitize_callback' => 'absint',
				),
				'banner_allowable_file_extensions' => array(
					'name'    => 'banner_allowable_file_extensions',
					'label'   => esc_html__( 'Petition Banner Extensions', 'cbxpetition' ),
					'desc'    => esc_html__( 'Banner extensions that are allowable to upload, if all unchecked then jpg, jpeg, gif, png are allowed.', 'cbxpetition' ),
					'type'    => 'file_extensions_checker',
					'default' => CBXPetitionHelper::imageExtArr(),
				),
				'banner_max_width'                 => array(
					'name'              => 'banner_max_width',
					'label'             => esc_html__( 'Petition Banner max width', 'cbxpetition' ),
					'desc'              => esc_html__( 'What will be the maximum width of banner?', 'cbxpetition' ),
					'type'              => 'text',
					'default'           => 1500,
					'sanitize_callback' => 'absint',
				),
				'banner_max_height'                => array(
					'name'              => 'banner_max_height',
					'label'             => esc_html__( 'Petition Banner max height', 'cbxpetition' ),
					'desc'              => esc_html__( 'What will be the maximum height of banner?', 'cbxpetition' ),
					'type'              => 'text',
					'default'           => 400,
					'sanitize_callback' => 'absint',
				),

			),
			'cbxpetition_email'       => array(
				'headerimage'         => array(
					'name'    => 'headerimage',
					'label'   => esc_html__( 'Header Image', 'cbxpetition' ),
					'desc'    => esc_html__( 'Url To email you want to show as email header.Upload Image by media uploader.',
						'cbxpetition' ),
					'type'    => 'file',
					'default' => '',
				),
				'footertext'          => array(
					'name'    => 'footertext',
					'label'   => esc_html__( 'Footer Text', 'cbxpetition' ),
					'desc'    => esc_html__( 'The text to appear at the email footer.', 'cbxpetition' ),
					'type'    => 'wysiwyg',
					'default' => '{sitename}',
				),
				'basecolor'           => array(
					'name'    => 'basecolor',
					'label'   => esc_html__( 'Base Color', 'cbxpetition' ),
					'desc'    => esc_html__( 'The base color of the email.', 'cbxpetition' ),
					'type'    => 'color',
					'default' => '#557da1',
				),
				'backgroundcolor'     => array(
					'name'    => 'backgroundcolor',
					'label'   => esc_html__( 'Background Colour', 'cbxpetition' ),
					'desc'    => esc_html__( 'The background color of the email.', 'cbxpetition' ),
					'type'    => 'color',
					'default' => '#f5f5f5',
				),
				'bodybackgroundcolor' => array(
					'name'    => 'bodybackgroundcolor',
					'label'   => esc_html__( 'Body Background Color', 'cbxpetition' ),
					'desc'    => esc_html__( 'The background colour of the main body of email.', 'cbxpetition' ),
					'type'    => 'color',
					'default' => '#fdfdfd',
				),
				'bodytextcolor'       => array(
					'name'    => 'bodytextcolor',
					'label'   => esc_html__( 'Body Text Color', 'cbxpetition' ),
					'desc'    => esc_html__( 'The body text colour of the main body of email.', 'cbxpetition' ),
					'type'    => 'color',
					'default' => '#505050',
				),
			),
			'cbxpetition_email_admin' => array(
				'new_sign_admin_email_alert' => array(
					'name'    => 'new_sign_admin_email_alert',
					'label'   => esc_html__( 'New Sign Admin Email Alert', 'cbxpetition' ),
					'desc'    => esc_html__( 'Admin gets email for new sign request', 'cbxpetition' ),
					'type'    => 'title',
					'default' => '',
				),
				'status'                     => array(
					'name'    => 'status',
					'label'   => esc_html__( 'Enable/Disable', 'cbxpetition' ),
					'desc'    => esc_html__( 'Enable this email notification', 'cbxpetition' ),
					'type'    => 'checkbox',
					'default' => '',
				),
				'format'                     => array(
					'name'    => 'format',
					'label'   => esc_html__( 'E-mail Format', 'cbxpetition' ),
					'desc'    => esc_html__( 'Select the format of the E-mail.', 'cbxpetition' ),
					'type'    => 'select',
					'default' => 'html',
					'options' => CBXPetitionHelper::email_type_formats(),
				),
				'to'                         => array(
					'name'    => 'to',
					'label'   => esc_html__( 'To Email', 'cbxpetition' ),
					'desc'    => esc_html__( 'To Email Address.', 'cbxpetition' ),
					'type'    => 'text',
					'default' => get_bloginfo( 'admin_email' ),
				),
				'reply_to'                   => array(
					'name'    => 'reply_to',
					'label'   => esc_html__( 'Reply To', 'cbxpetition' ),
					'desc'    => __( 'Reply To Email Address. Syntax available - <code>{user_email}</code>',
						'cbxpetition' ),
					'type'    => 'text',
					'default' => '{user_email}',
				),
				'subject'                    => array(
					'name'    => 'subject',
					'label'   => esc_html__( 'Subject', 'cbxpetition' ),
					'type'    => 'text',
					'default' => $email_template_admin['subject'],
				),
				'heading'                    => array(
					'name'    => 'heading',
					'label'   => esc_html__( 'Email Body Heading', 'cbxpetition' ),
					'type'    => 'text',
					'default' => $email_template_admin['heading'],
				),
				'body'                       => array(
					'name'    => 'body',
					'label'   => esc_html__( 'Body', 'cbxpetition' ),
					'desc'    => __( 'Email Body.  Syntax available - <code>{first_name}, {last_name}, {user_email}, {comment}, {comment}, {petition}, {signature_edit_url}</code>',
						'cbxpetition' ),
					'type'    => 'wysiwyg',
					'default' => $email_template_admin['body'],
				),
				'cc'                         => array(
					'name'    => 'cc',
					'label'   => esc_html__( 'CC', 'cbxpetition' ),
					'desc'    => esc_html__( 'Email CC, for multiple use comma.', 'cbxpetition' ),
					'type'    => 'text',
					'default' => '',
				),
				'bcc'                        => array(
					'name'    => 'bcc',
					'label'   => esc_html__( 'BCC', 'cbxpetition' ),
					'desc'    => esc_html__( 'Email BCC, for multiple use comma', 'cbxpetition' ),
					'type'    => 'text',
					'default' => '',
				),
			),
			'cbxpetition_email_user'  => array(
				'new_sign_user_email_alert'       => array(
					'name'    => 'new_sign_user_email_alert',
					'label'   => esc_html__( 'New Sign User Email Alert', 'cbxpetition' ),
					'desc'    => esc_html__( 'User gets email for new sign request', 'cbxpetition' ),
					'type'    => 'title',
					'default' => '',
				),
				'new_status'                      => array(
					'name'    => 'new_status',
					'label'   => esc_html__( 'Enable/Disable', 'cbxpetition' ),
					'desc'    => esc_html__( 'Enable this email notification', 'cbxpetition' ),
					'type'    => 'checkbox',
					'default' => '',
				),
				'new_format'                      => array(
					'name'    => 'new_format',
					'label'   => esc_html__( 'E-mail Format', 'cbxpetition' ),
					'desc'    => esc_html__( 'Select the format of the E-mail.', 'cbxpetition' ),
					'type'    => 'select',
					'default' => 'html',
					'options' => CBXPetitionHelper::email_type_formats(),
				),
				'new_to'                          => array(
					'name'    => 'new_to',
					'label'   => esc_html__( 'To Email', 'cbxpetition' ),
					'desc'    => __( 'To Email Address. Syntax available - <code>{user_email}</code>', 'cbxpetition' ),
					'type'    => 'text',
					'default' => '{user_email}',
				),
				'new_reply_to'                    => array(
					'name'    => 'new_reply_to',
					'label'   => esc_html__( 'Reply To', 'cbxpetition' ),
					'desc'    => esc_html__( 'Reply To Email Address.', 'cbxpetition' ),
					'type'    => 'text',
					'default' => get_bloginfo( 'admin_email' ),
				),
				'new_subject'                     => array(
					'name'    => 'new_subject',
					'label'   => esc_html__( 'Subject', 'cbxpetition' ),
					'type'    => 'text',
					'default' => $email_template_user_alert['subject'],
				),
				'new_heading'                     => array(
					'name'    => 'new_heading',
					'label'   => esc_html__( 'Email Body Heading', 'cbxpetition' ),
					'type'    => 'text',
					'default' => $email_template_user_alert['heading'],
				),
				'new_body'                        => array(
					'name'    => 'new_body',
					'label'   => esc_html__( 'Email Body', 'cbxpetition' ),
					'desc'    => __( 'Email content user will receive when they make an initial sign request. Syntax available - <code>{petition}, {first_name}, {last_name}, {user_email}, {comment}, {status}, {activation_link}</code>', 'cbxpetition' ),
					'type'    => 'wysiwyg',
					'default' => $email_template_user_alert['body'],
				),
				'sign_approve_user_alert_heading' => array(
					'name'    => 'sign_approve_user_alert_heading',
					'label'   => esc_html__( 'Sign Approve Email Alert', 'cbxpetition' ),
					'desc'    => esc_html__( 'User gets email when sign is approved', 'cbxpetition' ),
					'type'    => 'heading',
					'default' => '',
				),
				'sign_approve_user_alert'         => array(
					'name'    => 'sign_approve_user_alert',
					'label'   => esc_html__( 'Enable/Disable', 'cbxpetition' ),
					'desc'    => esc_html__( 'Enable this email notification', 'cbxpetition' ),
					'type'    => 'checkbox',
					'default' => '',
				),
				'sign_approve_user_format'        => array(
					'name'    => 'sign_approve_user_format',
					'label'   => esc_html__( 'E-mail Format', 'cbxpetition' ),
					'desc'    => esc_html__( 'Select the format of the E-mail.', 'cbxpetition' ),
					'type'    => 'select',
					'default' => 'html',
					'options' => CBXPetitionHelper::email_type_formats(),
				),
				'sign_approve_user_to'            => array(
					'name'    => 'sign_approve_user_to',
					'label'   => esc_html__( 'To Email', 'cbxpetition' ),
					'desc'    => __( 'To Email Address. Syntax available - <code>{user_email}</code>', 'cbxpetition' ),
					'type'    => 'text',
					'default' => '{user_email}',
				),
				'sign_approve_user_reply_to'      => array(
					'name'    => 'sign_approve_user_reply_to',
					'label'   => esc_html__( 'Reply To', 'cbxpetition' ),
					'desc'    => __( 'Reply To Email Address.', 'cbxpetition' ),
					'type'    => 'text',
					'default' => get_bloginfo( 'admin_email' ),
				),
				'sign_approve_user_subject'       => array(
					'name'    => 'sign_approve_user_subject',
					'label'   => esc_html__( 'Subject', 'cbxpetition' ),
					'type'    => 'text',
					'default' => $email_template_sign_approve['subject'],
				),
				'sign_approve_user_heading'       => array(
					'name'    => 'sign_approve_user_heading',
					'label'   => esc_html__( 'Email Body Heading', 'cbxpetition' ),
					'type'    => 'text',
					'default' => $email_template_sign_approve['heading'],
				),
				'sign_approve_user_body'          => array(
					'name'    => 'sign_approve_user_body',
					'label'   => esc_html__( 'Email Body', 'cbxpetition' ),
					'desc'    => __( 'Email content user will receive when admin confirmed sign request. Syntax available - <code>{petition}, {first_name}, {last_name}, {user_email}, {comment}, {status}</code>',
						'cbxpetition' ),
					'type'    => 'wysiwyg',
					'default' => $email_template_sign_approve['body'],
				),
			),
			'cbxpetition_tools'       => array(
				'delete_global_config' => array(
					'name'    => 'delete_global_config',
					'label'   => esc_html__( 'On Uninstall delete plugin data', 'cbxpetition' ),
					'desc'    => '<p>' . __( 'Delete Global Config data and custom table created by this plugin on uninstall.',
							'cbxpetition' ) . '</p>' . '<p>' . __( '<strong>Please note that this process can not be undone and it is recommended to keep full database backup before doing this.</strong>',
							'cbxpetition' ) . '</p>' . $table_html,
					'type'    => 'radio',
					'options' => array(
						'yes' => esc_html__( 'Yes', 'cbxpetition' ),
						'no'  => esc_html__( 'No', 'cbxpetition' ),
					),
					'default' => 'no',
				),
			),
		);

		$settings_fields = array(); //final setting array that will be passed to different filters

		$sections = $this->get_settings_sections();


		foreach ( $sections as $section ) {
			if ( ! isset( $settings_builtin_fields[ $section['id'] ] ) ) {
				$settings_builtin_fields[ $section['id'] ] = array();
			}
		}

		foreach ( $sections as $section ) {
			$settings_fields[ $section['id'] ] = apply_filters( 'cbxpetition_global_' . $section['id'] . '_fields',
				$settings_builtin_fields[ $section['id'] ] );
		}

		$settings_fields = apply_filters( 'cbxpetition_global_fields', $settings_fields ); //final filter if need

		return $settings_fields;
	}//end method get_settings_fields

	/**
	 * Add Admin menu
	 */
	public function admin_menus() {

		$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : '';

		//petition sign listing
		$sign_listing_page_hook = add_submenu_page( 'edit.php?post_type=cbxpetition',
			esc_html__( 'Signs Listing', 'cbxpetition' ),
			esc_html__( 'Signatures', 'cbxpetition' ),
			'manage_options',
			'cbxpetitionsigns',
			array(
				$this,
				'display_sign_listing_page',
			) );


		//add screen option save option
		if ( $page == 'cbxpetitionsigns' && ! isset( $_REQUEST['view'] ) ) {
			add_action( "load-$sign_listing_page_hook", array( $this, 'cbxpetition_signlisting_screen' ) );
		}

		//add settings for this plugin
		$setting_page_hook = add_submenu_page( 'edit.php?post_type=cbxpetition',
			esc_html__( 'Global Setting', 'cbxpetition' ),
			esc_html__( 'Global Setting', 'cbxpetition' ),
			'manage_options',
			'cbxpetitionsettings',
			array( $this, 'display_plugin_admin_settings' ) );

		/*global $submenu;
		if(isset($submenu['cbxmcratingreviewreviewlist'][0][0])){
			$submenu['cbxmcratingreviewreviewlist'][0][0] = esc_html__('User Reviews', 'cbxmcratingreview');
		}*/
	}//end method admin_menus


	/**
	 * Set options sign log listing result
	 *
	 * @param $new_state
	 * @param $option
	 * @param $value
	 *
	 * @return mixed
	 */
	public function cbxpetition_sign_results_per_page( $new_state, $option, $value ) {
		if ( 'cbxpetition_sign_results_per_page' == $option ) {
			return $value;
		}

		return $new_state;
	}

	/**
	 * Add screen option for sign listing
	 */
	public function cbxpetition_signlisting_screen() {
		$option = 'per_page';
		$args   = array(
			'label'   => esc_html__( 'Number of signs per page:', 'cbxpetition' ),
			'default' => 50,
			'option'  => 'cbxpetition_sign_results_per_page',
		);
		add_screen_option( $option, $args );
	}//end method cbxpetition_signlisting_screen

	/**
	 * Petition sign listing page
	 */
	public function display_sign_listing_page() {
		if ( isset( $_GET['view'] ) && isset( $_GET['id'] ) && sanitize_text_field( wp_unslash( $_GET['view'] ) ) == 'addedit' ) {

			global $wpdb;
			$log_id = ( isset( $_GET['id'] ) && intval( $_GET['id'] ) > 0 ) ? intval( $_GET['id'] ) : 0;

			if ( $log_id == 0 ) {
				echo esc_html__( 'No signature found', 'cbxpetition' );
			}

			$sign_info = null;
			if ( $log_id > 0 ) {
				global $wpdb;
				$cbxpetition_signs_table = $wpdb->prefix . 'cbxpetition_signs';

				$sign_info = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $cbxpetition_signs_table WHERE id = %d", $log_id ), ARRAY_A );

				if ( ! is_null( $sign_info ) && is_array( $sign_info ) ) {
					$comment = isset( $sign_info['comment'] ) ? stripslashes( $sign_info['comment'] ) : '';
					$state   = isset( $sign_info['state'] ) ? $sign_info['state'] : '';

					$cbxpetition_state_arr = CBXPetitionHelper::getPetitionSignStates();

					include( cbxpetition_locate_template( 'admin/admin-sign-edit.php' ) );
				} else {
					echo esc_html__( 'Invalid signature or not found', 'cbxpetition' );
				}
			}


		} else {
			if ( ! class_exists( 'CBXPetitionSign_List_Table' ) ) {
				require_once( plugin_dir_path( __FILE__ ) . '/../includes/class-cbxpetition-signatures.php' );
				include( cbxpetition_locate_template( 'admin/admin-signs-listing.php' ) );
			}
		}
		//require_once( $template_name );
	}//end method display_sign_listing_page

	/**
	 * Display settings
	 * @global type $wpdb
	 */
	public function display_plugin_admin_settings() {
		global $wpdb;

		$plugin_data = get_plugin_data( plugin_dir_path( __DIR__ ) . '/../' . $this->plugin_basename );

		include( cbxpetition_locate_template( 'admin/admin-settings-display.php' ) );
	}//end method display_plugin_admin_settings

	/**
	 * Hook custom meta box
	 */
	function cbxpetition_metabox_display_callback() {
		//add meta box in left side to show petition setting
		add_meta_box( 'petitioncustom_meta_box',           // $id
			esc_html__( 'CBX Petition Options', 'cbxpetition' ),    // $title
			array( $this, 'cbxpetition_metabox_display' ),  // $callback
			'cbxpetition',                                // $page
			'normal',                                     // $context
			'high' );                                      // $priority

		//add meta box in right col to show the result
		add_meta_box( 'petitionresult_meta_box',
			esc_html__( 'Petition Result', 'cbxpetition' ),
			array(
				$this,
				'cbxpetition_metaboxresult_display',
			),
			'cbxpetition',
			'side',
			'low' );

		//add meta box in right col to show the shortcode
		add_meta_box( 'petitionshortcode_meta_box',
			esc_html__( 'Shortcode', 'cbxpetition' ),
			array(
				$this,
				'cbxpetition_metaboxshortcode_display',
			),
			'cbxpetition',
			'side',
			'low' );
	}//end method cbxpetition_metabox_display_callback

	/**
	 * Show cbxpetition meta box in petition edit screen
	 */
	function cbxpetition_metabox_display() {
		global $post;

		$prefix = '_cbxpetition_';

		if ( isset( $post->ID ) && $post->ID > 0 ) {
			// include petition meta form
			$setting = $this->settings_api;

			include( cbxpetition_locate_template( 'admin/admin-metabox.php' ) );
		}
	}//end method cbxpetition_metabox_display

	/**
	 * Renders metabox in right col to show result
	 */
	function cbxpetition_metaboxresult_display() {
		global $post, $pagenow;

		$petition_output = '';
		if ( $pagenow == 'post.php' ) {
			$post_id                            = intval( $post->ID );
			$cbxpetition_signature_target       = cbxpetition_petitionSignatureTarget( $post_id );
			$cbxpetition_signature_count        = cbxpetition_petitionSignatureCount( $post_id );
			$cbxpetition_signature_target_ratio = cbxpetition_petitionSignatureTargetRatio( $post_id );
			$cbxpetition_expire_date            = cbxpetition_petitionExpireDate( $post_id );

			$petition_output .= '<p>' . sprintf( esc_html__( 'Signatures: %d of %d (%s)', 'cbxpetition' ),
					$cbxpetition_signature_count,
					$cbxpetition_signature_target,
					$cbxpetition_signature_target_ratio . '%' ) . '</p>';

			$petition_output .= '<p>' . sprintf( esc_html__( 'Expiry Date: %s', 'cbxpetition' ),
					$cbxpetition_expire_date ) . '</p>';
		}

		echo $petition_output;
	}//end method cbxpetition_metaboxresult_display

	/**
	 * Renders metabox in right col to show shortcode with copy to clipboard
	 */
	function cbxpetition_metaboxshortcode_display() {
		global $post;
		$post_id = $post->ID;

		echo '<span class="cbxpetitionshortcode">[cbxpetition petition_id="' . intval( $post_id ) . '"]</span>';
		echo '<div class="clear"></div>';
	}//end method cbxpetition_metaboxshortcode_display

	/**
	 * cbx_petition meta data save
	 */
	function cbxpetition_save_petition_meta( $post_id, $post ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// If this is just a revision, don't save
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		$post_type = get_post_type( $post_id );
		if ( "cbxpetition" != $post_type ) {
			return;
		}

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'cbxpetition' == sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) ) {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		$cbxpetitionmeta = isset( $_POST['cbxpetitionmeta'] ) ? $_POST['cbxpetitionmeta'] : array();


		// get signature target of the post
		$signature_target = isset( $cbxpetitionmeta['signature-target'] ) ? intval( $cbxpetitionmeta['signature-target'] ) : 0;
		update_post_meta( $post_id, '_cbxpetition_signature_target', $signature_target );

		// get expire date of the post
		$expire_date = isset( $cbxpetitionmeta['expire-date'] ) ? $cbxpetitionmeta['expire-date'] : '';
		update_post_meta( $post_id, '_cbxpetition_expire_date', $expire_date );

		// media info of the post
		$media_info      = array();
		$banner_image    = null;
		$petition_photos = null;

		// get banner image
		$media_info['banner-image'] = isset( $cbxpetitionmeta['banner-image'] ) ? sanitize_text_field( $cbxpetitionmeta['banner-image'] ) : 0;

		// get video url
		$media_info['video-url'] = isset( $cbxpetitionmeta['video-url'] ) ? sanitize_text_field( $cbxpetitionmeta['video-url'] ) : '';

		// get video title
		$media_info['video-title'] = isset( $cbxpetitionmeta['video-title'] ) ? sanitize_text_field( $cbxpetitionmeta['video-title'] ) : '';

		// get video description
		$video_description               = isset( $cbxpetitionmeta['video-description'] ) ? sanitize_textarea_field( $cbxpetitionmeta['video-description'] ) : '';
		$media_info['video-description'] = wp_kses( $video_description, CBXPetitionHelper::allowedHtmlTags() );


		// get petition photos
		$petition_photos = isset( $cbxpetitionmeta['petition-photos'] ) ? wp_unslash( $cbxpetitionmeta['petition-photos'] ) : array();


		$media_info['petition-photos'] = $petition_photos;

		// serialize media info and save to database
		update_post_meta( $post_id, '_cbxpetition_media_info', $media_info );


		// petition letter of the post
		$letter = array();


		$petition_letter  = isset( $cbxpetitionmeta['letter'] ) ? sanitize_textarea_field( $cbxpetitionmeta['letter'] ) : '';
		$letter['letter'] = wp_kses( $petition_letter, CBXPetitionHelper::allowedHtmlTags() );

		// get petition recipients
		$petition_recipient   = isset( $cbxpetitionmeta['recipients'] ) ? $cbxpetitionmeta['recipients'] : array();
		$letter['recipients'] = CBXPetitionHelper::recipient_checkRecipient( $petition_recipient );

		update_post_meta( $post_id, '_cbxpetition_letter', $letter );
	}//end cbxpetition_save_petition_meta

	/**
	 * Check recipient
	 *
	 * @param $recipients
	 *
	 * @return array|null
	 */
	public function recipient_checkRecipient( $recipients ) {
		return CBXPetitionHelper::recipient_checkRecipient( $recipients );
	}//end method recipient_checkRecipient

	/**
	 * Listing of incoming posts Column Header
	 *
	 * @param $columns
	 *
	 * @return mixed
	 */
	public function columns_header( $columns ) {
		//unset native date col
		unset( $columns['date'] );

		//add cols
		$columns['shortcode']        = esc_html__( 'Shortcode', 'cbxpetition' );
		$columns['signature_target'] = esc_html__( 'Signature Target', 'cbxpetition' );
		$columns['signature_received'] = esc_html__( 'Signature Received', 'cbxpetition' );
		$columns['expire_date']      = esc_html__( 'Expire Date', 'cbxpetition' );

		return $columns;
	}//end method columns_header


	/**
	 * Listing of form each row of post type.
	 *
	 * @param $column
	 * @param $post_id
	 */
	public function custom_column_row( $column, $post_id ) {
		$setting['signature_target'] = get_post_meta( $post_id, '_cbxpetition_signature_target', true );
		$setting['expire_date']      = get_post_meta( $post_id, '_cbxpetition_expire_date', true );

		$signature_count = intval( CBXPetitionHelper::petitionSignatureCount( $post_id ) );

		switch ( $column ) {
			case 'shortcode':
				echo '<span class="cbxpetitionshortcode">[cbxpetition petition_id="' . intval( $post_id ) . '"]</span>';
				break;
			case 'signature_target':
				echo intval( $setting['signature_target'] );
				break;
			case 'signature_received':
				echo intval( $signature_count );
				break;
			case 'expire_date':
				if($setting['expire_date'] != ''){
					echo CBXPetitionHelper::dateShowingFormat( $setting['expire_date'] );
				}
				else esc_html_e('Not Set Yet', 'cbxpetition');
		}
	}//end method custom_column_row

	/**
	 * Sortable count column
	 *
	 * @param $columns
	 *
	 * @return mixed
	 */
	public function custom_column_sortable( $columns ) {
		$columns['signature_target'] = 'signature_target';
		$columns['expire_date']      = 'expire_date';

		return $columns;
	}//end method custom_column_sortable


	/**
	 *
	 *
	 * @param $actions
	 * @param $page_object
	 *
	 * @return mixed
	 */
	public function row_actions_petition_listing( $actions, $post ) {
		if ( $post->post_type === 'cbxpetition' ) {
			$post_id                      = intval( $post->ID );
			$actions['cbxpetition_signs'] = '<a href="' . admin_url( 'edit.php?post_type=cbxpetition&page=cbxpetitionsigns&petition_id=' . intval($post_id) ) . '">' . esc_html__( 'Signatures',
					'cbxpetition' ) . '</a>';
		}

		return $actions;

	}//end method row_actions_petition_listing

	/**
	 * Petition Edit submit
	 */
	public function petition_sign_edit() {
		//if backend sign edit form submit and also nonce verified then go
		if ( ( isset( $_POST['cbxpetition_sign_edit'] ) && intval( $_POST['cbxpetition_sign_edit'] ) == 1 ) &&
		     ( isset( $_POST['cbxpetition_token'] ) && wp_verify_nonce( $_POST['cbxpetition_token'],
				     'cbxpetition_nonce' ) )
		) {

			global $wpdb;
			$settings = new CBXPetition_Settings();

			$current_user = wp_get_current_user();
			$user_id      = $current_user->ID;

			$petition_signs_table = $wpdb->prefix . 'cbxpetition_signs';

			$post_data = wp_unslash( $_POST ); //all needed fields of $_POST is sanitized below

			// sanitization
			$log_id  = isset( $post_data['id'] ) ? intval( $post_data['id'] ) : 0;
			$comment = isset( $post_data['comment'] ) ? sanitize_textarea_field( $post_data['comment'] ) : '';
			$state   = isset( $post_data['state'] ) ? sanitize_text_field( $post_data['state'] ) : '';

			$page_url = admin_url( 'edit.php?post_type=cbxpetition&page=cbxpetitionsigns' );
			$page_url = add_query_arg( 'view', 'addedit', $page_url );

			// validation
			$hasError          = false;
			$validation_errors = [];

			if ( $log_id == 0 ) {
				$validation_errors['top_errors'][] = esc_html__( 'Sorry! Invalid signature id',
					'cbxpetition' );
			} else {
				if ( $state == '' ) {
					$validation_errors['top_errors'][] = esc_html__( 'Please provide sign state.', 'cbxpetition' );
				}
			}

			if ( sizeof( $validation_errors ) > 0 ) {
				$hasError = true;

				$_SESSION['cbxpetition_sign_edit_validation_errors'] = $validation_errors;

				$page_url = add_query_arg( array( 'id' => $log_id, ), $page_url );
				wp_safe_redirect( $page_url );
				exit;
			}

			$data_safe['comment'] = $comment;
			$data_safe['state']   = $state;

			$messages    = array();
			$success_arr = array();

			//update
			if ( $log_id > 0 ) {

				//get the old data for the sign log
				$log_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $petition_signs_table WHERE id = %d", $log_id ),
					ARRAY_A );

				$data_safe['mod_by']   = $user_id;
				$data_safe['mod_date'] = current_time( 'mysql' );

				$where        = array(
					'id' => $log_id,
				);
				$where_format = array( '%d' );

				$col_data_format = array(
					'%s', //comment
					'%s', //status
					'%d', //mod_by
					'%s'  //mod_date
				);

				if ( $wpdb->update( $petition_signs_table, $data_safe, $where, $col_data_format, $where_format ) !== false ) {

					$log_data['comment']  = $comment;
					$log_data['state']    = $state;
					$log_data['mod_date'] = current_time( 'mysql' );
					$log_data['mod_date'] = current_time( 'mysql' );

					$message    = array(
						'text' => esc_html__( 'Signature updated successfully.', 'cbxpetition' ),
						'type' => 'success',
					);
					$messages[] = $message;

					//$meta = get_option( 'cbxpetition_email_user', array() );

					//if no status change then we skip sending any email
					$old_state = $log_data['state'];
					$new_state = $state;
					if ( $old_state !== $new_state && $new_state == 'approved' ) {

						do_action( 'cbxpetition_sign_log_status_to_' . $new_state,
							$log_data,
							$old_state,
							$new_state );

						do_action( 'cbxpetition_sign_log_status_from_' . $old_state . '_to_' . $new_state,
							$log_data,
							$old_state,
							$new_state );

						// mail part
						$sign_approve_user_alert = $settings->get_option( 'sign_approve_user_alert', 'cbxpetition_email_user', '' );
						if ( $sign_approve_user_alert == 'on' ) {
							$user_email_status = CBXPetitionMailAlert::sendSignApprovedEmailAlert( $log_data, $old_state, $new_state );
							if ( $user_email_status === true ) {

								$message    = array(
									'text' => esc_html__( 'Signature approved email sent successfully.', 'cbxpetition' ),
									'type' => 'success',
								);
								$messages[] = $message;
							} else {
								$message    = array(
									'text' => esc_html__( 'Signature approved email sent failed. ', 'cbxpetition' ),
									'type' => 'danger',
								);
								$messages[] = $message;
							}
						}

					}

				} else {
					$message    = array(
						'text' => esc_html__( 'Sorry! Some problem during updating, please try again.', 'cbxpetition' ),
						'type' => 'danger',
					);
					$messages[] = $message;
				}

			}

			$success_arr['messages'] = $messages;

			$_SESSION['cbxpetition_sign_edit_validation_success'] = $success_arr;

			$page_url = add_query_arg( array( 'id' => $log_id ), $page_url );

			wp_safe_redirect( $page_url );
			exit;


		}//end submit request
	}//end method petition_sign_edit


	/**
	 * delete petition related extra entry
	 *
	 * @param $petition_id
	 */
	public function on_petition_delete_sign_delete( $petition_id ) {

		// We check if the global post type isn't ours and just return
		global $post_type;
		global $wpdb;

		$petition_signs_table = $wpdb->prefix . "cbxpetition_signs";

		$petition_signs = CBXPetitionHelper::getSignListingData( '', $petition_id, 0, 'all', 'DESC', 'id', - 1 );

		if ( is_array( $petition_signs ) && sizeof( $petition_signs ) > 0 ) {
			foreach ( $petition_signs as $log_info ) {
				$id = intval( $log_info['id'] );
				do_action( 'cbxpetition_sign_delete_before', $log_info, $id, $petition_id );

				//now delete
				$sql           = $wpdb->prepare( "DELETE FROM $petition_signs_table WHERE id=%d", intval($id) );
				$delete_status = $wpdb->query( $sql );

				if ( $delete_status !== false ) {
					do_action( 'cbxpetition_sign_delete_after', $log_info, $id, $petition_id );
				}
			}
		}
	}//end method on_petition_delete_sign_delete

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles( $hook ) {
		global $post_type, $post;

		$page = isset( $_GET['page'] ) ? esc_attr( wp_unslash( $_GET['page'] ) ) : '';

		if ( $page == 'cbxpetitionsettings' ) {
			wp_register_style( 'select2',
				plugin_dir_url( __FILE__ ) . '../assets/js/select2/css/select2.min.css',
				array(),
				$this->version );
			wp_register_style( 'cbxpetition-setting',
				plugin_dir_url( __FILE__ ) . '../assets/css/cbxpetition-setting.css',
				array( 'select2' ),
				$this->version );

			wp_enqueue_style( 'select2' );
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'cbxpetition-setting' );
		}

		if ( ( $hook == 'post.php' || $hook == 'post-new.php' || $hook == 'edit.php' ) && $post_type == 'cbxpetition' ) {
			wp_register_style( 'flatpickr-min',
				plugin_dir_url( __FILE__ ) . '../assets/js/flatpickr/flatpickr.min.css',
				array(),
				$this->version );
			wp_register_style( 'cbxpetition-admin',
				plugin_dir_url( __FILE__ ) . '../assets/css/cbxpetition-admin.css',
				array( 'flatpickr-min' ),
				$this->version );

			wp_enqueue_style( 'flatpickr-min' );
			wp_enqueue_style( 'cbxpetition-admin' );
		}
	}//end method enqueue_styles

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook ) {
		global $post_type, $post;

		$page = isset( $_GET['page'] ) ? esc_attr( wp_unslash( $_GET['page'] ) ) : '';

		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		$setting = $this->settings_api;

		//photo
		$photo_max_number_of_files = intval( $setting->get_option( 'max_photo_limit', 'cbxpetition_general', 10 ) );
		$photo_max_file_size       = intval( $setting->get_option( 'max_file_size', 'cbxpetition_general', 1 ) ); //in mb
		$photo_max_file_size       = $photo_max_file_size * 1000000;

		$photo_accept_file_types = '(\.|\/)(gif|jpe?g|png)$';

		$photo_allowable_file_ext = $setting->get_option( 'allowable_file_extensions', 'cbxpetition_general', array() );
		if ( is_array( $photo_allowable_file_ext ) && sizeof( $photo_allowable_file_ext ) > 0 ) {
			$allowable_file_ext_string = join( '|', $photo_allowable_file_ext );
			$photo_accept_file_types   = '(\.|\/)(' . $allowable_file_ext_string . ')$';
		}
		//end photo


		//banner
		$banner_max_file_size = intval( $setting->get_option( 'banner_max_file_size', 'cbxpetition_general', 2 ) );
		$banner_max_file_size = $banner_max_file_size * 1000000;

		$banner_accept_file_types = '(\.|\/)(gif|jpe?g|png)$';

		$banner_allowable_file_ext = $setting->get_option( 'banner_allowable_file_extensions', 'cbxpetition_general', array() );
		if ( is_array( $banner_allowable_file_ext ) && sizeof( $banner_allowable_file_ext ) > 0 ) {
			$allowable_file_ext_string = join( '|', $banner_allowable_file_ext );
			$banner_accept_file_types  = '(\.|\/)(' . $allowable_file_ext_string . ')$';
		}
		//end banner


		//wp_enqueue_script( 'wp-color-picker' );


		//wp_register_script( 'jquery-sortable', plugin_dir_url( __FILE__ ) . '../assets/js/jquery-sortable/jquery-sortable' . $suffix . '.js',	array( 'jquery' ), $this->version, true );
		wp_register_script( 'select2', plugin_dir_url( __FILE__ ) . '../assets/js/select2/js/select2.min.js', array( 'jquery' ), $this->version, true );
		//wp_register_script( 'chosen-order-jquery',	plugin_dir_url( __FILE__ ) . '../assets/js/chosen.order.jquery.min.js', array('jquery', 'chosen-jquery'),$this->version,	true );

		wp_register_script( 'flatpickr',
			plugin_dir_url( __FILE__ ) . '../assets/js/flatpickr/flatpickr.min.js',
			array( 'jquery' ),
			$this->version,
			true );
		wp_register_script( 'mustache',
			plugin_dir_url( __FILE__ ) . '../assets/js/mustache/mustache' . $suffix . '.js',
			array( 'jquery' ),
			$this->version,
			true );

		wp_register_script( 'cbxpetition-setting',
			plugin_dir_url( __FILE__ ) . '../assets/js/cbxpetition-setting.js',
			array(
				'jquery',
				//'jquery-sortable',
				'jquery-ui-sortable',
				'select2',
				'wp-color-picker',
			),
			$this->version,
			true );

		$setting_js_vars = apply_filters( 'cbxpetition_setting_js_vars',
			array(
				'please_select' => esc_html__( 'Please Select', 'cbxpetition' ),
				//'upload_title'  => esc_html__( 'Select Media File', 'cbxpetition' ),
				'upload_btn'    => esc_html__( 'Upload', 'cbxpetition' ),
				'upload_title'  => esc_html__( 'Select Media', 'cbxpetition' ),
			) );
		wp_localize_script( 'cbxpetition-setting', 'cbxpetition_setting', $setting_js_vars );

		if ( $page == 'cbxpetitionsettings' ) {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_media();
			//wp_enqueue_script( 'jquery-sortable' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'select2' );
			//wp_enqueue_script( 'chosen-order-jquery');
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_script( 'cbxpetition-setting' );
		}

		//var_dump($hook);

		if ( ( $hook == 'post.php' || $hook == 'post-new.php' || $hook == 'edit.php' ) && $post_type == 'cbxpetition' && $page != 'cbxpetitionsettings' ) {

			$photo_mode = 0;

			if ( $hook == 'post.php' || $hook == 'post-new.php' ) {
				$photo_mode = 1;
				wp_register_script( 'jquery-ui-widget', plugin_dir_url( __FILE__ ) . '../assets/js/jqueryfileupload/vendor/jquery.ui.widget.js', array( 'jquery' ), $this->version, true );
				wp_register_script( 'load-image-all', plugin_dir_url( __FILE__ ) . '../assets/js/jqueryfileupload/jssaveas/load-image.all.min.js', array( 'jquery' ), $this->version, true );
				wp_register_script( 'canvas-to-blob', plugin_dir_url( __FILE__ ) . '../assets/js/jqueryfileupload/jssaveas/canvas-to-blob.min.js', array( 'jquery' ), $this->version, true );
				wp_register_script( 'jquery-iframe-transport', plugin_dir_url( __FILE__ ) . '../assets/js/jqueryfileupload/jquery.iframe-transport.js', array( 'jquery' ), $this->version, true );
				wp_register_script( 'jquery-fileupload', plugin_dir_url( __FILE__ ) . '../assets/js/jqueryfileupload/jquery.fileupload.js', array( 'jquery' ), $this->version, true );
				wp_register_script( 'jquery-fileupload-process', plugin_dir_url( __FILE__ ) . '../assets/js/jqueryfileupload/jquery.fileupload-process.js', array( 'jquery' ), $this->version, true );

				wp_register_script( 'jquery-fileupload-validate', plugin_dir_url( __FILE__ ) . '../assets/js/jqueryfileupload/jquery.fileupload-validate.js', array( 'jquery' ), $this->version, true );
				$cbxpetition_admin_photo_dep = array(
					'jquery-ui-widget',
					'load-image-all',
					'canvas-to-blob',
					'jquery-iframe-transport',
					'jquery-fileupload',
					'jquery-fileupload-process',
					'jquery-fileupload-validate',
				);
			}


			$cbxpetition_admin_js_dep = array(
				'jquery',
				'flatpickr',
				'mustache',
				'select2',
				'wp-color-picker',
				'jquery-ui-sortable',
			);

			if ( $photo_mode ) {


				$cbxpetition_admin_js_dep = array_merge( $cbxpetition_admin_js_dep, $cbxpetition_admin_photo_dep );

			}

			wp_register_script( 'cbxpetition-admin',
				plugin_dir_url( __FILE__ ) . '../assets/js/cbxpetition-admin.js',
				$cbxpetition_admin_js_dep,
				$this->version,
				true );

			// Localize the script with new data
			$cbxpetition_admin_js_vars = apply_filters( 'cbxpetition_admin_js_vars',
				array(
					'ajaxurl'                   => admin_url( 'admin-ajax.php' ),
					'nonce'                     => wp_create_nonce( 'cbxpetition' ),
					'upload_btn'                => esc_html__( 'Upload', 'cbxpetition' ),
					'upload_title'              => esc_html__( 'Select Media File', 'cbxpetition' ),
					'delete'                    => esc_html__( 'Delete', 'cbxpetition' ),
					'delete_confirm'            => esc_html__( 'Are you sure to delete? After delete if you save the post once this information will be lost forever.', 'cbxpetition' ),
					'photo_mode'                => $photo_mode,
					'delete_text'               => esc_html__( 'Delete', 'cbxpetition' ),
					'sort_text'                 => esc_html__( 'Sort', 'cbxpetition' ),
					'blueimp'                   => array(
						'maxNumberOfFiles' => esc_html__( 'Maximum number of files exceeded', 'cbxpetition' ),
						'acceptFileTypes'  => esc_html__( 'File type not allowed', 'cbxpetition' ),
						'maxFileSize'      => esc_html__( 'File is too large', 'cbxpetition' ),
						'minFileSize'      => esc_html__( 'File is too small', 'cbxpetition' ),
					),
					'photo_max_number_of_files' => $photo_max_number_of_files,
					'photo_max_filesize'        => $photo_max_file_size,
					'photo_accept_file_types'   => $photo_accept_file_types,
					'banner_accept_file_types'  => $banner_accept_file_types,
					'banner_max_filesize'       => $banner_max_file_size,
					'file_upload_failed'        => esc_html__( 'Sorry, file upload failed.', 'cbxpetition' ),

				) );

			wp_localize_script( 'cbxpetition-admin', 'cbxpetitionObj', $cbxpetition_admin_js_vars );

			wp_enqueue_script( 'jquery' );
			wp_enqueue_media();
			wp_enqueue_script( 'flatpickr' );
			//wp_enqueue_script( 'jquery-sortable' );

			wp_enqueue_script( 'jquery-ui-sortable' );

			if ( $hook == 'post.php' || $hook == 'post-new.php' ) {
				/*wp_enqueue_script('jquery-ui-widget');
				wp_enqueue_script('load-image-all');
				wp_enqueue_script('canvas-to-blob');
				wp_enqueue_script('jquery-iframe-transport');
				wp_enqueue_script('jquery-fileupload');
				wp_enqueue_script('jquery-fileupload-process');
				wp_enqueue_script('jquery-fileupload-validate');*/

				foreach ( $cbxpetition_admin_photo_dep as $handle ) {
					wp_enqueue_script( $handle );
				}
			}


			wp_enqueue_script( 'mustache' );
			wp_enqueue_script( 'select2' );
			wp_enqueue_script( 'wp-color-picker' );


			wp_enqueue_script( 'cbxpetition-admin' );

		}
	}//end method enqueue_scripts

	/**
	 * Petition photo upload via ajax
	 */
	public function petition_admin_photo_upload() {

		check_ajax_referer( 'cbxpetition', 'security' );

		$submit_data = wp_unslash( $_POST ); //necessary fields are sanitized below

		$setting = $this->settings_api;

		//$review_id = isset( $submit_data['log_id'] ) ? intval( $submit_data['log_id'] ) : 0;
		$form_id = isset( $submit_data['form_id'] ) ? intval( $submit_data['form_id'] ) : 0;

		//we should allow file upload in admin end without any checking if file enabled or not.
		$attachable = true;

		if ( $form_id == 0 ) {
			$attachable = false;
		}

		/*$enable_attachment_form = $setting->get_option( 'enable_attachment_form', 'cbxpetition_general', array() );
		if ( ! is_array( $enable_attachment_form ) ) {
			$enable_attachment_form = array();
		}

		if ( sizeof( $enable_attachment_form ) == 0 ) {
			$attachable = false;
		};

		if ( ! in_array( $form_id, $enable_attachment_form ) ) {
			$attachable = false;
		}*/

		//$enable_photo     = $setting->get_option( 'enable_photo', 'cbxpetition_general', 'on' );
		$thumb_max_width  = intval( $setting->get_option( 'thumb_max_width', 'cbxpetition_general', 400 ) );
		$thumb_max_height = intval( $setting->get_option( 'thumb_max_height', 'cbxpetition_general', 400 ) );

		$photo_max_width  = intval( $setting->get_option( 'photo_max_width', 'cbxpetition_general', 800 ) );
		$photo_max_height = intval( $setting->get_option( 'photo_max_height', 'cbxpetition_general', 800 ) );


		//if the upload dir for cbxpetition is not created then then create it
		$dir_info = CBXPetitionHelper::checkUploadDir();


		if ( $attachable && is_array( $dir_info ) && sizeof( $dir_info ) > 0 && array_key_exists( 'folder_exists', $dir_info ) && $dir_info['folder_exists'] == 1 ) {

			$options = array(
				'param_name'     => 'cbxpetitionmeta_photo_files',
				'script_url'     => admin_url( 'admin-ajax.php' ),
				'upload_dir'     => $dir_info['cbxpetition_base_dir'] . $form_id . '/',
				'upload_url'     => $dir_info['cbxpetition_base_url'] . $form_id . '/',
				'print_response' => false,
				'image_versions' => array(
					// The empty image version key defines options for the original image:
					''          => array(
						// Automatically rotate images based on EXIF meta data:
						'auto_orient' => true,
						'max_width'   => $photo_max_width,
						'max_height'  => $photo_max_height,
					),
					// Uncomment the following to create medium sized images:
					/*
					'medium' => array(
						'max_width' => 800,
						'max_height' => 600
					),
					*/
					'thumbnail' => array(
						// Uncomment the following to use a defined directory for the thumbnails
						// instead of a subdirectory based on the version identifier.
						// Make sure that this directory doesn't allow execution of files if you
						// don't pose any restrictions on the type of uploaded files, e.g. by
						// copying the .htaccess file from the files directory for Apache:
						//'upload_dir' => dirname($this->get_server_var('SCRIPT_FILENAME')).'/thumb/',
						//'upload_url' => $this->get_full_url().'/thumb/',
						// Uncomment the following to force the max
						// dimensions and e.g. create square thumbnails:
						'crop'       => false,
						'max_width'  => $thumb_max_width,
						'max_height' => $thumb_max_height,
					),
				),
			);

			$error_messages = array(
				1                     => esc_html__( 'The uploaded file exceeds the upload_max_filesize directive in php.ini', 'cbxpetition' ),
				2                     => esc_html__( 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form', 'cbxpetition' ),
				3                     => esc_html__( 'The uploaded file was only partially uploaded', 'cbxpetition' ),
				4                     => esc_html__( 'No file was uploaded', 'cbxpetition' ),
				6                     => esc_html__( 'Missing a temporary folder', 'cbxpetition' ),
				7                     => esc_html__( 'Failed to write file to disk', 'cbxpetition' ),
				8                     => esc_html__( 'A PHP extension stopped the file upload', 'cbxpetition' ),
				'post_max_size'       => esc_html__( 'The uploaded file exceeds the post_max_size directive in php.ini', 'cbxpetition' ),
				'max_file_size'       => esc_html__( 'File is too big', 'cbxpetition' ),
				'min_file_size'       => esc_html__( 'File is too small', 'cbxpetition' ),
				'accept_file_types'   => esc_html__( 'Filetype not allowed', 'cbxpetition' ),
				'max_number_of_files' => esc_html__( 'Maximum number of files exceeded', 'cbxpetition' ),
				'max_width'           => esc_html__( 'Image exceeds maximum width', 'cbxpetition' ),
				'min_width'           => esc_html__( 'Image requires a minimum width', 'cbxpetition' ),
				'max_height'          => esc_html__( 'Image exceeds maximum height', 'cbxpetition' ),
				'min_height'          => esc_html__( 'Image requires a minimum height', 'cbxpetition' ),
				'abort'               => esc_html__( 'File upload aborted', 'cbxpetition' ),
				'image_resize'        => esc_html__( 'Failed to resize image', 'cbxpetition' ),
			);


			$upload_handler = new CBXPetitionBlueimpFileUploadHandlerCustom( $options, true, $error_messages );

			$response_obj = $upload_handler->response;

			//$result = json_decode($response_obj);
			if ( is_array( $response_obj ) && isset( $response_obj['cbxpetitionmeta_photo_files'][0]->name ) ) {
				$filename = wp_unslash( $response_obj['cbxpetitionmeta_photo_files'][0]->name );

				$media_info      = get_post_meta( $form_id, '_cbxpetition_media_info', true );
				$petition_photos = isset( $media_info['petition-photos'] ) ? wp_unslash( $media_info['petition-photos'] ) : array();

				if ( ! in_array( $filename, $petition_photos ) ) {
					$petition_photos[]             = $filename;
					$media_info['petition-photos'] = $petition_photos;
					update_post_meta( $form_id, '_cbxpetition_media_info', $media_info );
				}
			}

			echo wp_json_encode( $response_obj );
			wp_die();
		}//end good to process
	}//end method petition_admin_photo_upload

	/**
	 * Petition photo delete via ajax
	 */
	public function petition_admin_photo_delete() {

		check_ajax_referer( 'cbxpetition', 'security' );
		$submit_data = wp_unslash( $_POST ); //data is sanitized later below from $submit_data variable

		$setting = $this->settings_api;

		$filename = isset( $submit_data['filename'] ) ? sanitize_text_field( $submit_data['filename'] ) : '';

		//$review_id = isset( $submit_data['log_id'] ) ? intval( $submit_data['log_id'] ) : 0;
		$form_id = isset( $submit_data['form_id'] ) ? intval( $submit_data['form_id'] ) : 0;

		$attachable = true;

		if ( $form_id == 0 ) {
			$attachable = false;
		}

		/*$enable_attachment_form = $setting->get_option( 'enable_attachment_form', 'cbxpetition_general', array() );
		if ( ! is_array( $enable_attachment_form ) ) {
			$enable_attachment_form = array();
		}

		if ( sizeof( $enable_attachment_form ) == 0 ) {
			$attachable = false;
		};

		if ( ! in_array( $form_id, $enable_attachment_form ) ) {
			$attachable = false;
		}


		$setting = $this->getSetting();
		$enable_photo              = $setting->get_option( 'enable_photo', 'cbxpetition_general', 'on' );*/

		$current_user = wp_get_current_user();

		//if photo enabled and user has capability to manage options then we will allow to delete
		if ( $attachable && is_user_logged_in() && $filename != '' && user_can( $current_user, 'manage_options' ) ) {
			$media_info      = get_post_meta( $form_id, '_cbxpetition_media_info', true );
			$petition_photos = isset( $media_info['petition-photos'] ) ? wp_unslash( $media_info['petition-photos'] ) : array();


			if ( in_array( $filename, $petition_photos ) ) {

				foreach ( array_keys( $petition_photos, $filename, true ) as $key ) {
					unset( $petition_photos[ $key ] );
				}

				$media_info['petition-photos'] = $petition_photos;
				update_post_meta( $form_id, '_cbxpetition_media_info', $media_info );
			}


			$ok_to_progress = 0;


			$dir_info = CBXPetitionHelper::checkUploadDir();

			//$deleted = @unlink( $dir_info['cbxpetition_base_dir'] .$review_id.'/'. $filename );
			$deleted = wp_delete_file( $dir_info['cbxpetition_base_dir'] . $form_id . '/' . $filename );

			//@unlink( $dir_info['cbxpetition_base_dir'].$review_id.'/thumbnail/' . $filename);
			wp_delete_file( $dir_info['cbxpetition_base_dir'] . $form_id . '/thumbnail/' . $filename );

			$ok_to_progress = 1;


			echo wp_json_encode( $ok_to_progress );
			wp_die();
		}
	}//end method petition_admin_photo_delete


	/**
	 * Petition banner upload via ajax
	 */
	public function petition_admin_banner_upload() {

		check_ajax_referer( 'cbxpetition', 'security' );

		$submit_data = wp_unslash( $_POST ); //all needed fields of $_POST has been sanitized below

		$setting = $this->settings_api;

		//$review_id = isset( $submit_data['log_id'] ) ? intval( $submit_data['log_id'] ) : 0;
		$form_id = isset( $submit_data['form_id'] ) ? intval( $submit_data['form_id'] ) : 0;

		//we should allow file upload in admin end without any checking if file enabled or not.
		$attachable = true;

		if ( $form_id == 0 ) {
			$attachable = false;
		}

		/*$enable_attachment_form = $setting->get_option( 'enable_attachment_form', 'cbxpetition_general', array() );
		if ( ! is_array( $enable_attachment_form ) ) {
			$enable_attachment_form = array();
		}

		if ( sizeof( $enable_attachment_form ) == 0 ) {
			$attachable = false;
		};

		if ( ! in_array( $form_id, $enable_attachment_form ) ) {
			$attachable = false;
		}*/

		//$enable_photo     = $setting->get_option( 'enable_photo', 'cbxpetition_general', 'on' );
		$banner_max_width  = intval( $setting->get_option( 'banner_max_width', 'cbxpetition_general', 1500 ) );
		$banner_max_height = intval( $setting->get_option( 'banner_max_height', 'cbxpetition_general', 400 ) );


		//if the upload dir for cbxpetition is not created then then create it
		$dir_info = CBXPetitionHelper::checkUploadDir();


		if ( $attachable && is_array( $dir_info ) && sizeof( $dir_info ) > 0 && array_key_exists( 'folder_exists', $dir_info ) && $dir_info['folder_exists'] == 1 ) {

			$options = array(
				'param_name'     => 'cbxpetitionmeta_banner_file',
				'script_url'     => admin_url( 'admin-ajax.php' ),
				'upload_dir'     => $dir_info['cbxpetition_base_dir'] . $form_id . '/',
				'upload_url'     => $dir_info['cbxpetition_base_url'] . $form_id . '/',
				'print_response' => false,
				'image_versions' => array(
					// The empty image version key defines options for the original image:
					'' => array(
						// Automatically rotate images based on EXIF meta data:
						'auto_orient' => true,
						'max_width'   => $banner_max_width,
						'max_height'  => $banner_max_height,
					),
					// Uncomment the following to create medium sized images:
					/*
					'medium' => array(
						'max_width' => 800,
						'max_height' => 600
					),
					*/
					/*'thumbnail' => array(
						// Uncomment the following to use a defined directory for the thumbnails
						// instead of a subdirectory based on the version identifier.
						// Make sure that this directory doesn't allow execution of files if you
						// don't pose any restrictions on the type of uploaded files, e.g. by
						// copying the .htaccess file from the files directory for Apache:
						//'upload_dir' => dirname($this->get_server_var('SCRIPT_FILENAME')).'/thumb/',
						//'upload_url' => $this->get_full_url().'/thumb/',
						// Uncomment the following to force the max
						// dimensions and e.g. create square thumbnails:
						'crop'       => false,
						'max_width'  => $thumb_max_width,
						'max_height' => $thumb_max_height
					)*/
				),
			);

			$error_messages = array(
				1                     => esc_html__( 'The uploaded file exceeds the upload_max_filesize directive in php.ini', 'cbxpetition' ),
				2                     => esc_html__( 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form', 'cbxpetition' ),
				3                     => esc_html__( 'The uploaded file was only partially uploaded', 'cbxpetition' ),
				4                     => esc_html__( 'No file was uploaded', 'cbxpetition' ),
				6                     => esc_html__( 'Missing a temporary folder', 'cbxpetition' ),
				7                     => esc_html__( 'Failed to write file to disk', 'cbxpetition' ),
				8                     => esc_html__( 'A PHP extension stopped the file upload', 'cbxpetition' ),
				'post_max_size'       => esc_html__( 'The uploaded file exceeds the post_max_size directive in php.ini', 'cbxpetition' ),
				'max_file_size'       => esc_html__( 'File is too big', 'cbxpetition' ),
				'min_file_size'       => esc_html__( 'File is too small', 'cbxpetition' ),
				'accept_file_types'   => esc_html__( 'Filetype not allowed', 'cbxpetition' ),
				'max_number_of_files' => esc_html__( 'Maximum number of files exceeded', 'cbxpetition' ),
				'max_width'           => esc_html__( 'Image exceeds maximum width', 'cbxpetition' ),
				'min_width'           => esc_html__( 'Image requires a minimum width', 'cbxpetition' ),
				'max_height'          => esc_html__( 'Image exceeds maximum height', 'cbxpetition' ),
				'min_height'          => esc_html__( 'Image requires a minimum height', 'cbxpetition' ),
				'abort'               => esc_html__( 'File upload aborted', 'cbxpetition' ),
				'image_resize'        => esc_html__( 'Failed to resize image', 'cbxpetition' ),
			);


			$upload_handler = new CBXPetitionBlueimpFileUploadHandlerCustom( $options, true, $error_messages );

			$response_obj = $upload_handler->response;

			//$result = json_decode($response_obj);
			if ( is_array( $response_obj ) && isset( $response_obj['cbxpetitionmeta_banner_file'][0]->name ) ) {
				$filename = sanitize_text_field( $response_obj['cbxpetitionmeta_banner_file'][0]->name );

				$media_info = get_post_meta( $form_id, '_cbxpetition_media_info', true );
				//$petition_banner = isset( $media_info['banner-image'] ) ? wp_unslash($media_info['banner-image']) : '';


				$media_info['banner-image'] = $filename;
				update_post_meta( $form_id, '_cbxpetition_media_info', $media_info );

			}

			echo wp_json_encode( $response_obj );
			wp_die();
		}//end good to process
	}//end method petition_admin_banner_upload

	/**
	 * Review rating file delete via ajax from admin side
	 */
	public function petition_admin_banner_delete() {

		check_ajax_referer( 'cbxpetition', 'security' );
		$submit_data = wp_unslash( $_POST ); //all needed fields of $_POST is sanitized below

		$setting = $this->settings_api;

		$filename = isset( $submit_data['filename'] ) ? sanitize_text_field( $submit_data['filename'] ) : '';

		//$review_id = isset( $submit_data['log_id'] ) ? intval( $submit_data['log_id'] ) : 0;
		$form_id = isset( $submit_data['form_id'] ) ? intval( $submit_data['form_id'] ) : 0;

		$attachable = true;

		if ( $form_id == 0 ) {
			$attachable = false;
		}

		/*$enable_attachment_form = $setting->get_option( 'enable_attachment_form', 'cbxpetition_general', array() );
		if ( ! is_array( $enable_attachment_form ) ) {
			$enable_attachment_form = array();
		}

		if ( sizeof( $enable_attachment_form ) == 0 ) {
			$attachable = false;
		};

		if ( ! in_array( $form_id, $enable_attachment_form ) ) {
			$attachable = false;
		}


		$setting = $this->getSetting();
		$enable_photo              = $setting->get_option( 'enable_photo', 'cbxpetition_general', 'on' );*/

		$current_user = wp_get_current_user();

		//if photo enabled and user has capability to manage options then we will allow to delete
		if ( $attachable && is_user_logged_in() && $filename != '' && user_can( $current_user, 'manage_options' ) ) {
			$media_info                 = get_post_meta( $form_id, '_cbxpetition_media_info', true );
			$media_info['banner-image'] = '';
			update_post_meta( $form_id, '_cbxpetition_media_info', $media_info );


			$ok_to_progress = 0;


			$dir_info = CBXPetitionHelper::checkUploadDir();

			//$deleted = @unlink( $dir_info['cbxpetition_base_dir'] .$review_id.'/'. $filename );
			$deleted = wp_delete_file( $dir_info['cbxpetition_base_dir'] . $form_id . '/' . $filename );

			$ok_to_progress = 1;

			echo wp_json_encode( $ok_to_progress );
			wp_die();
		}
	}//end method petition_admin_banner_delete

	/***
	 * Delete sign on user delete
	 *
	 * @param $user_id
	 */
	public function on_user_delete_sign_delete( $user_id ) {
		global $wpdb;
		$petition_signs_table = $wpdb->prefix . "cbxpetition_signs";

		$data = CBXPetitionHelper::getSignListingData( '',
			0,
			intval( $user_id ),
			'all',
			'DESC',
			'id',
			- 1 );


		if ( $data !== null && is_array( $data ) && sizeof( $data ) > 0 ) {
			foreach ( $data as $log_info ) {

				$id          = intval( $log_info['id'] );
				$petition_id = intval( $log_info['petition_id'] );

				do_action( 'cbxpetition_sign_delete_before', $log_info, $id, $petition_id );

				if ( $log_info !== null && sizeof( $log_info ) > 0 ) {
					//now delete
					$sql           = $wpdb->prepare( "DELETE FROM $petition_signs_table WHERE id=%d", intval($id) );
					$delete_status = $wpdb->query( $sql );

					if ( $delete_status !== false ) {
						do_action( 'cbxpetition_sign_delete_after', $log_info, $id, $petition_id );
					}

				}
			}
		}//end if found data
	}//end method on_user_delete_sign_delete

	/**
	 * Post delete hook init
	 */
	public function signature_delete_after_delete_post_init() {
		add_action( 'delete_post', array( $this, 'signature_delete_after_delete_post' ), 10 );
	}//end method signature_delete_after_delete_post_init

	/**
	 * On post  delete delete signatures
	 *
	 * @param $post_id
	 */
	public function signature_delete_after_delete_post( $petition_id ) {
		global $wpdb;
		$petition_signs_table = $wpdb->prefix . "cbxpetition_signs";


		$petition_id = intval( $petition_id );

		$data = CBXPetitionHelper::getSignListingData( '',
			$petition_id,
			0,
			'all',
			'DESC',
			'id',
			- 1 );


		if ( $data !== null && is_array( $data ) && sizeof( $data ) > 0 ) {
			foreach ( $data as $log_info ) {

				$id = intval( $log_info['id'] );

				do_action( 'cbxpetition_sign_delete_before', $log_info, $id, $petition_id );

				if ( $log_info !== null && sizeof( $log_info ) > 0 ) {
					//now delete
					$sql           = $wpdb->prepare( "DELETE FROM $petition_signs_table WHERE id=%d", intval($id) );
					$delete_status = $wpdb->query( $sql );

					if ( $delete_status !== false ) {
						do_action( 'cbxpetition_sign_delete_after', $log_info, $id, $petition_id );
					}

				}
			}
		}//end if found data

		//delete petition photo folder for that petition
		CBXPetitionHelper::deletePetitionPhotosFolder( $petition_id );
	}//end method signature_delete_after_delete_post


	/**
	 * If we need to do something in upgrader process is completed for poll plugin
	 *
	 * @param $upgrader_object
	 * @param $options
	 */
	public function plugin_upgrader_process_complete( $upgrader_object, $options ) {
		if ( $options['action'] == 'update' && $options['type'] == 'plugin' ) {
			foreach ( $options['plugins'] as $each_plugin ) {
				if ( $each_plugin == CBXPETITION_BASE_NAME ) {
					CBXPetitionHelper::create_tables();
					set_transient( 'cbxpetition_upgraded_notice', 1 );
					break;
				}
			}
		}

	}//end method plugin_upgrader_process_complete

	/**
	 * Show a notice to anyone who has just installed the plugin for the first time
	 * This notice shouldn't display to anyone who has just updated this plugin
	 */
	public function plugin_activate_upgrade_notices() {
		// Check the transient to see if we've just activated the plugin
		if ( get_transient( 'cbxpetition_activated_notice' ) ) {
			echo '<div class="notice notice-success is-dismissible"><p>' . sprintf( esc_html__( 'Thanks for installing/deactivating CBX Petition V%s - Codeboxr Team', 'cbxpetition' ), CBXPETITION_PLUGIN_VERSION ) . '</p></div>';
			// Delete the transient so we don't keep displaying the activation message
			delete_transient( 'cbxpetition_activated_notice' );

			$this->pro_addon_compatibility_campaign();

		}

		// Check the transient to see if we've just activated the plugin
		if ( get_transient( 'cbxpetition_upgraded_notice' ) ) {
			echo '<div class="notice notice-success is-dismissible"><p>' . sprintf( esc_html__( 'Thanks for upgrading CBX Petition V%s , enjoy the new features and bug fixes - Codeboxr Team', 'cbxpetition' ), CBXPETITION_PLUGIN_VERSION ) . '</p></div>';
			// Delete the transient so we don't keep displaying the activation message
			delete_transient( 'cbxpetition_upgraded_notice' );

			$this->pro_addon_compatibility_campaign();

		}
	}//end method plugin_activate_upgrade_notices

	/**
	 * Check plugin compatibility and pro addon install campaign
	 */
	public function pro_addon_compatibility_campaign(){

		if(!function_exists('is_plugin_active')){
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		//if the pro addon is active or installed
		if(in_array('cbxpollproaddon/cbxpollproaddon.php', apply_filters('active_plugins', get_option('active_plugins'))) || defined( 'CBXPETITIONPROADDON_PLUGIN_NAME' )){
			//plugin is activated

			$plugin_version = CBXPETITIONPROADDON_PLUGIN_NAME;


			/*if(version_compare($plugin_version,'1.0.11', '<=') ){
				echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'CBX Petition Pro Addon Vx.x.x or any previous version is not compatible with CBX Petition Vx.x.x or later. Please update CBX Petition Pro Addon to version x.x.0 or later  - Codeboxr Team', 'cbxpetition' ) . '</p></div>';
			}*/
		}
		else{
			echo '<div class="notice notice-success is-dismissible"><p>' . sprintf(__( 'CBX Petition Pro Addon has frontend petition submission features and more controls, <a target="_blank" href="%s">try it</a>  - Codeboxr Team', 'cbxpetition' ), 'https://codeboxr.com/product/cbx-petition-for-wordpress/') . '</p></div>';
		}

	}//end method pro_addon_compatibility_campaign

	/**
	 * Show action links on the plugin screen.
	 *
	 * @param   mixed $links Plugin Action links.
	 *
	 * @return  array
	 */
	public function plugin_action_links( $links ) {
		$action_links = array(
			'settings' => '<a style="color: #673ab7 !important;" href="' . admin_url( 'edit.php?post_type=cbxpetition&page=cbxpetitionsettings' ) . '" aria-label="' . esc_attr__( 'View settings', 'cbxpetition' ) . '">' . esc_html__( 'Settings', 'cbxpetition' ) . '</a>',
		);

		return array_merge( $action_links, $links );
	}//end method plugin_action_links

	/**
	 * Filters the array of row meta for each/specific plugin in the Plugins list table.
	 * Appends additional links below each/specific plugin on the plugins page.
	 *
	 * @access  public
	 *
	 * @param   array $links_array An array of the plugin's metadata
	 * @param   string $plugin_file_name Path to the plugin file
	 * @param   array $plugin_data An array of plugin data
	 * @param   string $status Status of the plugin
	 *
	 * @return  array       $links_array
	 */
	public function plugin_row_meta( $links_array, $plugin_file_name, $plugin_data, $status ) {
		if ( strpos( $plugin_file_name, CBXPETITION_BASE_NAME ) !== false ) {
			$links_array[] = '<a target="_blank" style="color:red !important; font-weight: bold;" href="https://codeboxr.com/product/cbx-petition-for-wordpress/" aria-label="' . esc_attr__( 'Try Pro',
					'cbxpetition' ) . '">' . esc_html__( 'Try Pro', 'cbxpetition' ) . '</a>';
		}

		return $links_array;
	}//end plugin_row_meta


}//end class CBXPetition_Admin
