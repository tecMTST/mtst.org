<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBXPetition
 * @subpackage CBXPetition/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    CBXPetition
 * @subpackage CBXPetition/includes
 * @author     Codeboxr <info@codeboxr.com>
 */
class CBXPetition {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      CBXPetition_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 * cbxlatesttweets
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = CBXPETITION_PLUGIN_NAME;
		$this->version     = CBXPETITION_PLUGIN_VERSION;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		//$this->define_template_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - CBXPetition_Loader. Orchestrates the hooks of the plugin.
	 * - CBXPetition_i18n. Defines internationalization functionality.
	 * - CBXPetition_Admin. Defines all hooks for the admin area.
	 * - CBXPetition_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxpetition-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxpetition-i18n.php';

		/**
		 * The class responsible for defining settings functionality of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxpetition-settings.php';


		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxpetition-helper.php';

		////require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxpetition-template.php';
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/cbxpetition-template-functions.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/cbxpetition-functions.php';
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/cbxpetition-hooks.php';


		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/BlueimpFileUploadHandler.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/BlueimpFileUploadHandlerCustom.php';


		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Html2Text.php';
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Html2TextException.php';
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/emogrifier.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxpetition-mailtemplate.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxpetition-mailhelper.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxpetition-mailalert.php';


		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cbxpetition-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cbxpetition-public.php';

		$this->loader = new CBXPetition_Loader();
	}


	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the CBXPetition_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new CBXPetition_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new CBXPetition_Admin( $this->get_plugin_name(), $this->get_version() );

		//add new post type- cbxpetition
		$this->loader->add_action( 'init', $plugin_admin, 'create_post_type' );

		//adding the setting action/initialize the setting
		$this->loader->add_action( 'admin_init', $plugin_admin, 'admin_init' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'petition_sign_edit' );

		//Add admin menu action hook; for adding setting menu
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menus' );
		$this->loader->add_filter( 'plugin_action_links_' . CBXPETITION_BASE_NAME, $plugin_admin, 'plugin_action_links' );
		$this->loader->add_filter( 'plugin_row_meta', $plugin_admin, 'plugin_row_meta',10, 4 );
		$this->loader->add_filter( 'set-screen-option', $plugin_admin, 'cbxpetition_sign_results_per_page', 10, 3 );

		// add meta box and hook save meta box
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'cbxpetition_metabox_display_callback' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'cbxpetition_save_petition_meta', 10, 2 );

		//custom column header in listing for forms
		$this->loader->add_filter( 'manage_cbxpetition_posts_columns', $plugin_admin, 'columns_header' ); // show or remove extra column
		$this->loader->add_action( 'manage_cbxpetition_posts_custom_column', $plugin_admin, 'custom_column_row', 10, 2 ); // modify column's row data to display
		$this->loader->add_filter( 'manage_edit-cbxpetition_sortable_columns', $plugin_admin, 'custom_column_sortable' );
		$this->loader->add_filter( 'post_row_actions', $plugin_admin, 'row_actions_petition_listing', 10, 2 );


		//on user delete
		$this->loader->add_action( 'delete_user', $plugin_admin, 'on_user_delete_sign_delete' );


		//$this->loader->add_action('init', $plugin_admin, 'cbx_petition_custom_post_type');
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		//file upload and delete

		//admin rating form edit - file upload and delete
		$this->loader->add_action( 'wp_ajax_petition_admin_photo_upload', $plugin_admin, 'petition_admin_photo_upload' );//admin  //ok
		$this->loader->add_action( 'wp_ajax_petition_admin_photo_delete', $plugin_admin, 'petition_admin_photo_delete' );//admin //ok

		$this->loader->add_action( 'wp_ajax_petition_admin_banner_upload', $plugin_admin, 'petition_admin_banner_upload' );//admin  //ok
		$this->loader->add_action( 'wp_ajax_petition_admin_banner_delete', $plugin_admin, 'petition_admin_banner_delete' );//admin //ok

		$this->loader->add_action( 'admin_init', $plugin_admin, 'signature_delete_after_delete_post_init' );

		$this->loader->add_action( 'upgrader_process_complete', $plugin_admin, 'plugin_upgrader_process_complete', 10, 2 );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'plugin_activate_upgrade_notices' );


	}//end method define_admin_hooks

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new CBXPetition_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_filter( 'the_content', $plugin_public, 'auto_integration' ); //auto integration petition features
		$this->loader->add_action( 'init', $plugin_public, 'init_shortcodes' ); //shortcode init
		$this->loader->add_action( 'widgets_init', $plugin_public, 'init_widgets' ); //widget register init

		$this->loader->add_action( 'template_redirect', $plugin_public, 'init_session' );
		$this->loader->add_action( 'template_redirect', $plugin_public, 'guest_email_validation' );


		$this->loader->add_action( 'wp_ajax_cbxpetition_sign_submit', $plugin_public, 'petition_sign_submit' );
		$this->loader->add_action( 'wp_ajax_nopriv_cbxpetition_sign_submit', $plugin_public, 'petition_sign_submit' );

		$this->loader->add_action( 'wp_ajax_cbxpetition_load_more_signs', $plugin_public, 'petition_load_more_signs' );
		$this->loader->add_action( 'wp_ajax_nopriv_cbxpetition_load_more_signs', $plugin_public, 'petition_load_more_signs' );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
	}//end method define_public_hooks

	/**
	 * Set Templates
	 */
	/*private function define_template_hooks() {

		$template = new CBXPetition_Template();

		$this->loader->add_filter( 'template_include', $template, 'cbxpetition_template_loader', 99 );

	}*/


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    CBXPetition_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
