<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class CBXPetitionHelper
 *
 */
class CBXPetitionHelper {

	/**
	 * Create tables
	 */
	public static function create_tables() {
		global $wpdb;


		$petition_signs_table = $wpdb->prefix . 'cbxpetition_signs';

		//db table migration if exists
		$charset_collate = '';
		if ( $wpdb->has_cap( 'collation' ) ) {
			if ( ! empty( $wpdb->charset ) ) {
				$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			}
			if ( ! empty( $wpdb->collate ) ) {
				$charset_collate .= " COLLATE $wpdb->collate";
			}
		}


		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );


		//create petition_signs table
		$petition_signs_table_sql = "CREATE TABLE $petition_signs_table (
                          id bigint(11) unsigned NOT NULL AUTO_INCREMENT,
                          petition_id bigint(11) unsigned NOT NULL DEFAULT 0 COMMENT 'petition id',
                          f_name varchar(255) DEFAULT NULL COMMENT 'signer first name',
                          l_name varchar(255) DEFAULT NULL COMMENT 'signer last name',
                          email varchar(100) NOT NULL COMMENT 'signer email',
                          comment text DEFAULT NULL COMMENT 'signer comment about petition',
                          state varchar(30) NOT NULL DEFAULT 'pending' COMMENT 'sign condition',
                          activation VARCHAR(255) DEFAULT NULL COMMENT 'activation code', 
                          add_by bigint(11) unsigned NOT NULL DEFAULT '0' COMMENT 'foreign key of user table. who added this, if uest zero',
                          mod_by bigint(11) unsigned NOT NULL DEFAULT '0' COMMENT 'foreign key of user table. who last modify this list',
                          add_date datetime DEFAULT NULL COMMENT 'add date',
                          mod_date datetime DEFAULT NULL COMMENT 'last modified date',
                          PRIMARY KEY (id)
                        ) $charset_collate; ";

		dbDelta( array( $petition_signs_table_sql ) );

	}//end method create_tables

	/**
	 * Create petition custom post type, taxonomies
	 */
	public static function create_cbxpetition_post_type() {
		$labels = array(
			'name'              => _x( 'Petitions', 'Post Type General Name', 'cbxpetition' ),
			'singular_name'     => _x( 'Petition', 'Post Type Singular Name', 'cbxpetition' ),
			'menu_name'         => esc_html__( 'CBX Petition', 'cbxpetition' ),
			'parent_item_colon' => esc_html__( 'Parent Item:', 'cbxpetition' ),
			'all_items'         => esc_html__( 'Petitions', 'cbxpetition' ),
			'view_item'         => esc_html__( 'View Petition', 'cbxpetition' ),
			'add_new_item'      => esc_html__( 'Create Petition', 'cbxpetition' ),
			'add_new'           => esc_html__( 'Create Petition', 'cbxpetition' ),
			'edit_item'         => esc_html__( 'Edit Petition', 'cbxpetition' ),
			'update_item'       => esc_html__( 'Update Petition', 'cbxpetition' ),
			'search_items'      => esc_html__( 'Search Petition', 'cbxpetition' ),
		);

		$args = array(
			'label'               => esc_html__( 'CBX Petition', 'cbxpetition' ),
			'description'         => esc_html__( 'CBX Petition', 'cbxpetition' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			//'menu_icon'           => 'dashicons-forms',
			'menu_icon'           => CBXPETITION_ROOT_URL.'assets/images/petition_transparent_24.png',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
		);
		register_post_type( 'cbxpetition', $args );

		// register cbxpetition_cat taxonomy
		$cbxpetition_cat_labels = array(
			'name'                       => _x( 'Categories', 'Taxonomy General Name', 'cbxpetition' ),
			'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'cbxpetition' ),
			'menu_name'                  => esc_html__( 'Categories', 'cbxpetition' ),
			'all_items'                  => esc_html__( 'All Categories', 'cbxpetition' ),
			'parent_item'                => esc_html__( 'Parent Category', 'cbxpetition' ),
			'parent_item_colon'          => esc_html__( 'Parent Category:', 'cbxpetition' ),
			'new_item_name'              => esc_html__( 'New Category Name', 'cbxpetition' ),
			'add_new_item'               => esc_html__( 'Add New Category', 'cbxpetition' ),
			'edit_item'                  => esc_html__( 'Edit Category', 'cbxpetition' ),
			'update_item'                => esc_html__( 'Update Category', 'cbxpetition' ),
			'view_item'                  => esc_html__( 'View Category', 'cbxpetition' ),
			'separate_items_with_commas' => esc_html__( 'Separate Categories with commas', 'cbxpetition' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Categories', 'cbxpetition' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used', 'cbxpetition' ),
			'popular_items'              => esc_html__( 'Popular Categories', 'cbxpetition' ),
			'search_items'               => esc_html__( 'Search Categories', 'cbxpetition' ),
			'not_found'                  => esc_html__( 'Not Found', 'cbxpetition' ),
			'no_terms'                   => esc_html__( 'No Categories', 'cbxpetition' ),
			'items_list'                 => esc_html__( 'Categories list', 'cbxpetition' ),
			'items_list_navigation'      => esc_html__( 'Categories list navigation', 'cbxpetition' ),
		);
		$cbxpetition_cat_args   = array(
			'labels'            => $cbxpetition_cat_labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
		);
		register_taxonomy( 'cbxpetition_cat', array( 'cbxpetition' ), $cbxpetition_cat_args );

		// register cbxpetition_tag taxonomy
		$cbxpetition_tag_labels = array(
			'name'                       => _x( 'Tags', 'Taxonomy General Name', 'cbxpetition' ),
			'singular_name'              => _x( 'Tag', 'Taxonomy Singular Name', 'cbxpetition' ),
			'menu_name'                  => esc_html__( 'Tags', 'cbxpetition' ),
			'all_items'                  => esc_html__( 'All Tags', 'cbxpetition' ),
			'parent_item'                => esc_html__( 'Parent Tag', 'cbxpetition' ),
			'parent_item_colon'          => esc_html__( 'Parent Tag:', 'cbxpetition' ),
			'new_item_name'              => esc_html__( 'New Tag Name', 'cbxpetition' ),
			'add_new_item'               => esc_html__( 'Add New Tag', 'cbxpetition' ),
			'edit_item'                  => esc_html__( 'Edit Tag', 'cbxpetition' ),
			'update_item'                => esc_html__( 'Update Tag', 'cbxpetition' ),
			'view_item'                  => esc_html__( 'View Tag', 'cbxpetition' ),
			'separate_items_with_commas' => esc_html__( 'Separate Tags with commas', 'cbxpetition' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Tags', 'cbxpetition' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used', 'cbxpetition' ),
			'popular_items'              => esc_html__( 'Popular Tags', 'cbxpetition' ),
			'search_items'               => esc_html__( 'Search Tags', 'cbxpetition' ),
			'not_found'                  => esc_html__( 'Not Found', 'cbxpetition' ),
			'no_terms'                   => esc_html__( 'No Tags', 'cbxpetition' ),
			'items_list'                 => esc_html__( 'Tags list', 'cbxpetition' ),
			'items_list_navigation'      => esc_html__( 'Tags list navigation', 'cbxpetition' ),
		);

		$cbxpetition_tag_args   = array(
			'labels'            => $cbxpetition_tag_labels,
			'hierarchical'      => false,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
		);

		register_taxonomy( 'cbxpetition_tag', array( 'cbxpetition' ), $cbxpetition_tag_args );
	}//end method create_cbxpetition_post_type

	/**
	 * HTML elements, attributes, and attribute values will occur in your output
	 * @return array
	 */
	public static function allowedHtmlTags() {
		$allowed_html_tags = array(
			'a'      => array(
				'href'  => array(),
				'title' => array(),
				//'class' => array(),
				//'data'  => array(),
				//'rel'   => array(),
			),
			'br'     => array(),
			'em'     => array(),
			'ul'     => array(//'class' => array(),
			),
			'ol'     => array(//'class' => array(),
			),
			'li'     => array(//'class' => array(),
			),
			'strong' => array(),
			'p'      => array(
				//'class' => array(),
				//'data'  => array(),
				//'style' => array(),
			),
			'span'   => array(
				//					'class' => array(),
				//'style' => array(),
			),
		);

		return apply_filters( 'cbxpetition_allowed_html_tags', $allowed_html_tags );
	}//end method allowedHtmlTags

	/**
	 * Get user display name
	 *
	 * @param null $user_id
	 *
	 * @return string
	 */
	public static function userDisplayName( $user_id = null ) {
		$current_user      = $user_id ? new WP_User( $user_id ) : wp_get_current_user();
		$user_display_name = $current_user->display_name;
		if ( $user_display_name != '' ) {
			return $user_display_name;
		}

		if ( $current_user->first_name ) {
			if ( $current_user->last_name ) {
				return $current_user->first_name . ' ' . $current_user->last_name;
			}

			return $current_user->first_name;
		}

		return esc_html__('Unnamed', 'cbxpetition');
	}//end method userDisplayName

	/**
	 * Get user display name alternative if display_name value is empty
	 *
	 * @param $current_user
	 * @param $user_display_name
	 *
	 * @return string
	 */
	public static function userDisplayNameAlt( $current_user, $user_display_name ) {
		if ( $user_display_name != '' ) {
			return $user_display_name;
		}

		if ( $current_user->first_name ) {
			if ( $current_user->last_name ) {
				return $current_user->first_name . ' ' . $current_user->last_name;
			}

			return $current_user->first_name;
		}

		return esc_html__('Unnamed', 'cbxpetition');
	}//end method userDisplayNameAlt

	public static function isPetitionSignedByUser( $petition_id = 0, $user_id = 0 ) {
		$petition_id = intval( $petition_id );
		$user_id     = intval( $user_id );

		if ( $petition_id == 0 || $user_id == 0 ) {
			return false;
		}

		global $wpdb;

		$petition_signs_table = $wpdb->prefix . "cbxpetition_signs";
		$sql                  = $wpdb->prepare( "SELECT * FROM $petition_signs_table WHERE petition_id=%d AND add_by=%d",
			$petition_id,
			$user_id );
		$log_info             = $wpdb->get_row( $sql, ARRAY_A );
		if ( is_null( $log_info ) ) {
			return false;
		}

		return true;

	}//end method isPetitionSignedByUser

	/**
	 * Is petition signe by guest user by email
	 *
	 * @param int    $petition_id
	 * @param string $email
	 *
	 * @return bool
	 */
	public static function isPetitionSignedByGuest( $petition_id = 0, $email = '' ) {
		$petition_id = intval( $petition_id );
		$email       = sanitize_email( $email );

		if ( $petition_id == 0 || $email == '' ) {
			return false;
		}

		if ( ! is_email( $email ) ) {
			return false;
		}

		global $wpdb;

		$petition_signs_table = $wpdb->prefix . "cbxpetition_signs";
		$sql                  = $wpdb->prepare( "SELECT * FROM $petition_signs_table WHERE petition_id=%d AND email=%s",
			$petition_id,
			$email );
		$log_info             = $wpdb->get_row( $sql, ARRAY_A );
		if ( is_null( $log_info ) ) {
			return false;
		}

		return true;

	}//end method isPetitionSignedByGuest

	/**
	 * Return human readable date using custom format
	 *
	 * @return string
	 */
	public static function dateShowingFormat( $date ) {
		return date( 'M j, Y', strtotime( $date ) );
	}

	/**
	 * Return human readable time using custom format
	 *
	 * @return string
	 */
	public static function timeShowingFormat( $date ) {
		return date( 'h:i a', strtotime( $date ) );
	}


	/**
	 * @return array
	 */
	public static function allTablesArr() {
		$all_tables_arr = array(
			'petition_signs' => 'Signature Table',
		);

		return apply_filters( 'cbxpetition_table_names', $all_tables_arr );
	}

	/**
	 * List all global option name with prefix cbxpetition_
	 */
	public static function getAllOptionNames() {
		global $wpdb;

		$prefix       = 'cbxpetition_';
		$option_names = $wpdb->get_results( "SELECT * FROM {$wpdb->options} WHERE option_name LIKE '{$prefix}%'",
			ARRAY_A );

		return apply_filters( 'cbxpetition_option_names', $option_names );
	}


	/**
	 * returns all petition sign states
	 * @return array
	 */
	public static function getPetitionSignStates() {

		$states = array(
			'unverified' => esc_html__( 'Unverified', 'cbxpetition' ),
			'pending'    => esc_html__( 'Pending', 'cbxpetition' ),
			'approved'   => esc_html__( 'Approved', 'cbxpetition' ),
			'unapproved' => esc_html__( 'Unapproved', 'cbxpetition' ),
		);

		return apply_filters( 'cbxpetition_sign_state', $states );
	}

	/**
	 * return petition sign state value corresponding key
	 *
	 * @param string $state_key
	 *
	 * @return mixed|string
	 */
	public static function getPetitionSignState( $state_key = '' ) {
		$state = '';
		if ( $state_key != '' ) {
			$states = CBXPetitionHelper::getPetitionSignStates();

			if ( is_array( $states ) && sizeof( $states ) > 0 ) {
				$state = isset( $states[ $state_key ] ) ? $states[ $state_key ] : $state_key;
			}
		}

		return $state;
	}//end method getPetitionSignState


	/**
	 * return all published petition
	 * @return array|null|object
	 */
	public static function getAllPetitions() {
		global $post;

		$all_petitions = array();

		$args = array(
			'posts_per_page' => - 1,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'post_type'      => 'cbxpetition',
			'post_status'    => 'publish',

		);

		$myposts = get_posts( $args );
		foreach ( $myposts as $post ) : setup_postdata( $post );
			$post_id                   = get_the_ID();
			$post_title                = get_the_title();
			$all_petitions[ $post_id ] = $post_title;
		endforeach;
		wp_reset_postdata();

		return $all_petitions;
	}//end method getAllPetitions


	/**
	 * Get petition sign Data
	 *
	 * @param string $search
	 * @param int    $petition_id
	 * @param int    $user_id
	 * @param string $state
	 * @param string $order
	 * @param string $orderby
	 * @param int    $perpage
	 * @param int    $page
	 *
	 * @return array|null|object
	 */
	public static function getSignListingData( $search = '', $petition_id = 0, $user_id = 0, $state = 'all', $order = 'DESC', $orderby = 'id', $perpage = 20, $page = 1 ) {
		global $wpdb;

		$petition_sign_table = $wpdb->prefix . "cbxpetition_signs";

		$sql_select = $join = $where_sql = $sortingOrder = '';
		$sql_select = "SELECT * FROM $petition_sign_table as signs";

		if ( $search != '' ) {
			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}
			$where_sql .= $wpdb->prepare( " (f_name LIKE '%%%s%%' OR l_name LIKE '%%%s%%' OR email LIKE '%%%s%%' OR comment LIKE '%%%s%%' )",
				$search,
				$search,
				$search,
				$search );
		}

		if ( intval( $petition_id ) > 0 ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'petition_id=%d', intval( $petition_id ) );
		}

		if ( intval( $user_id ) > 0 ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'add_by=%d', intval( $user_id ) );
		}

		if ( $state !== 'all' ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'state=%s', $state );
		}

		if ( $where_sql == '' ) {
			$where_sql = '1';
		}

		$limit_sql = '';

		if ( $perpage != - 1 ) {
			$start_point = ( $page * $perpage ) - $perpage;
			$limit_sql   = "LIMIT";
			$limit_sql   .= ' ' . $start_point . ',';
			$limit_sql   .= ' ' . $perpage;
		}


		$sortingOrder = " ORDER BY $orderby $order ";

		$data = $wpdb->get_results( "$sql_select  WHERE  $where_sql $sortingOrder $limit_sql", 'ARRAY_A' );

		return $data;
	}//end method getSignListingData

	/**
	 * Get total petition sign data count
	 *
	 * @param string $search
	 * @param int    $petition_id
	 * @param int    $user_id
	 * @param string $state
	 * @param int    $perpage
	 * @param int    $page
	 *
	 * @return null|string
	 */
	public static function getSignListingDataCount( $search = '', $petition_id = 0, $user_id = 0, $state = 'all', $perpage = 20, $page = 1 ) {
		global $wpdb;

		$petition_sign_table = $wpdb->prefix . "cbxpetition_signs";

		$sql_select = $join = $where_sql = $sortingOrder = '';

		$sql_select = "SELECT COUNT(*) FROM $petition_sign_table as signs";

		if ( $search != '' ) {
			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}
			$where_sql .= $wpdb->prepare( " (f_name LIKE '%%%s%%' OR l_name LIKE '%%%s%%' OR email LIKE '%%%s%%' OR comment LIKE '%%%s%%' )",
				$search,
				$search,
				$search,
				$search );
		}

		if ( intval( $petition_id ) > 0 ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'petition_id=%d', intval( $petition_id ) );
		}

		if ( intval( $user_id ) > 0 ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'add_by=%d', intval( $user_id ) );
		}

		if ( $state !== 'all' ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'state=%s', $state );
		}

		if ( $where_sql == '' ) {
			$where_sql = '1';
		}

		//$sortingOrder = " ORDER BY $orderby $order ";

		$count = $wpdb->get_var( "$sql_select $join  WHERE  $where_sql" );

		return $count;
	}//end method getSignListingDataCount


	/**
	 * petitions to expire for widget
	 */
	public static function petitionsToExpire( $perpage = 10 ) {

		$args = array(
			'post_type'      => 'cbxpetition',
			'post_status'    => 'publish',
			'posts_per_page' => $perpage,
			'meta_key'       => '_cbxpetition_expire_date',
			'orderby'        => '_cbxpetition_expire_date',
			'order'          => 'ASC',
			'meta_query'     => array(
				array(
					'key'     => '_cbxpetition_expire_date',
					//						'value'   => date( 'Y-m-d', strtotime( "+7 days" ) ),
					'value'   => date( 'Y-m-d H:i:s' ),
					'compare' => '>=',
				),
			),
		);

		$petitions_to_expire = get_posts( $args );

		return $petitions_to_expire;
	}

	/**
	 * petitions that are recently completed
	 */
	public static function completedPetitions( $perpage = 10 ) {

		$args = array(
			'post_type'      => 'cbxpetition',
			'post_status'    => 'publish',
			'posts_per_page' => $perpage,
			'meta_key'       => '_cbxpetition_expire_date',
			'orderby'        => '_cbxpetition_expire_date',
			'order'          => 'ASC',
			'meta_query'     => array(
				array(
					'key'     => '_cbxpetition_expire_date',
					'value'   => date( 'Y-m-d' ),
					'compare' => '<',
				),
			),
		);

		$completed_petitions = get_posts( $args );

		return $completed_petitions;
	}

	/**
	 * Petition Info
	 *
	 * @param $sign_id
	 *
	 * @return array|null|object|void
	 */
	public static function petitionSignInfo( $sign_id ) {
		global $wpdb;
		$petition_signs_table = $wpdb->prefix . "cbxpetition_signs";
		$sql                  = $wpdb->prepare( "SELECT * FROM $petition_signs_table WHERE id=%d ", intval( $sign_id ) );
		$log_info             = $wpdb->get_row( $sql, ARRAY_A );

		return $log_info;
	}//end method petitionSignInfo

	/**
	 * get single petition expire date
	 *
	 * @param int $petition_id
	 *
	 * @return string
	 */
	public static function petitionExpireDate( $petition_id = 0 ) {
		$expire_date = array();

		$petition_id = intval( $petition_id );
		if ( $petition_id > 0 ) {
			$expire_date = get_post_meta( $petition_id, '_cbxpetition_expire_date', true );
			$expire_date = CBXPetitionHelper::dateShowingFormat( $expire_date );
		}

		return $expire_date;
	}


	/**
	 * get single petition media info data arr
	 *
	 * @param int $petition_id
	 *
	 * @return array|mixed
	 */
	public static function petitionMediaInfo( $petition_id = 0 ) {
		$media_info = array();

		$petition_id = intval( $petition_id );
		if ( $petition_id > 0 ) {
			$media_info = get_post_meta( $petition_id, '_cbxpetition_media_info', true );
		}

		return $media_info;
	}//end method petitionMediaInfo

	/**
	 * get single petition banner image
	 *
	 * @param int $petition_id
	 *
	 * @return mixed|string
	 */
	public static function petitionBannerImage( $petition_id = 0 ) {
		$banner = '';

		$petition_id = intval( $petition_id );
		if ( $petition_id > 0 ) {
			$media_info = CBXPetitionHelper::petitionMediaInfo( $petition_id );
			$banner     = isset( $media_info['banner-image'] ) ? sanitize_text_field( $media_info['banner-image'] ) : '';
			if ( $banner != '' ) {
				$dir_info = CBXPetitionHelper::checkUploadDir();
				$banner   = $dir_info['cbxpetition_base_url'] . $petition_id . '/' . $banner;
			}
		}

		return $banner;
	}//end method petitionBannerImage

	/**
	 * get single petition signature target
	 *
	 * @param int $petition_id
	 *
	 * @return int|mixed|string
	 */
	public static function petitionSignatureTarget( $petition_id = 0 ) {
		$signature_target = 0;

		$petition_id = intval( $petition_id );

		if ( $petition_id > 0 ) {
			$signature_target = intval( get_post_meta( $petition_id, '_cbxpetition_signature_target', true ) );
		}

		return $signature_target;
	}//end method petitionSignatureTarget

	/**
	 * get single petition video info
	 *
	 * @param int $petition_id
	 *
	 * @return array
	 */
	public static function petitionVideoInfo( $petition_id = 0 ) {
		$video_info  = array();
		$petition_id = intval( $petition_id );
		if ( $petition_id > 0 ) {
			$media_info = CBXPetitionHelper::petitionMediaInfo( $petition_id );

			if ( ! is_array( $media_info ) ) {
				$media_info = array();
			}

			$video_info['video-url']         = isset( $media_info['video-url'] ) ? $media_info['video-url'] : '';
			$video_info['video-title']       = isset( $media_info['video-title'] ) ? $media_info['video-title'] : '';
			$video_info['video-description'] = isset( $media_info['video-description'] ) ? $media_info['video-description'] : '';
		}

		return $video_info;
	}//end method petitionVideoInfo

	/**
	 * get single petition photos
	 *
	 * @param int $petition_id
	 *
	 * @return array
	 */
	public static function petitionPhotos( $petition_id = 0 ) {
		$petition_photos = array();

		$petition_id = intval( $petition_id );
		if ( $petition_id > 0 ) {
			$media_info = CBXPetitionHelper::petitionMediaInfo( $petition_id );
			if ( ! is_array( $media_info ) ) {
				$media_info = array();
			}

			$petition_photos = isset( $media_info['petition-photos'] ) ? $media_info['petition-photos'] : array();
			if ( ! is_array( $petition_photos ) ) {
				$petition_photos = array();
			}
		}

		return $petition_photos;
	}//end method petitionPhotos

	/**
	 * get single petition letter info
	 *
	 * @param int $petition_id
	 *
	 * @return array|mixed
	 */
	public static function petitionLetterInfo( $petition_id = 0 ) {
		$letter_info = array();

		$petition_id = intval( $petition_id );
		if ( $petition_id > 0 ) {
			$letter_info = get_post_meta( $petition_id, '_cbxpetition_letter', true );
		}

		return $letter_info;
	}//end method petitionLetterInfo

	/**
	 * get single petition letter
	 *
	 * @param int $petition_id
	 *
	 * @return mixed|string
	 */
	public static function petitionLetter( $petition_id = 0 ) {
		$petition_letter = '';

		$petition_id = intval( $petition_id );
		if ( $petition_id > 0 ) {
			$letter_info = CBXPetitionHelper::petitionLetterInfo( $petition_id );

			if ( is_array( $letter_info ) && sizeof( $letter_info ) > 0 ) {
				if ( isset( $letter_info['letter'] ) ) {
					$petition_letter = $letter_info['letter'];
				}
			}
		}

		return $petition_letter;
	}//end method petitionLetter

	/**
	 * get single petition recipients
	 *
	 * @param int $petition_id
	 *
	 * @return array
	 */
	public static function petitionRecipients( $petition_id = 0 ) {
		$petition_recipients = array();

		$petition_id = intval( $petition_id );
		if ( $petition_id > 0 ) {
			$letter_info = CBXPetitionHelper::petitionLetterInfo( $petition_id );

			$petition_recipients = $letter_info['recipients'];
		}

		return $petition_recipients;
	}//end method petitionRecipients

	/**
	 * get single petition signature count
	 *
	 * @param int $petition_id
	 *
	 * @return int
	 */
	public static function petitionSignatureCount( $petition_id = 0 ) {
		$signature_count = 0;

		$petition_id = intval( $petition_id );

		if ( $petition_id > 0 ) {
			global $wpdb;
			$petition_signs_table = $wpdb->prefix . 'cbxpetition_signs';

			$sql_select = "SELECT COUNT(*) FROM $petition_signs_table as signs";

			$where_sql = $wpdb->prepare( "petition_id=%d AND state=%s", $petition_id, 'approved' );

			$signature_count = $wpdb->get_var( " $sql_select WHERE  $where_sql" );
		}

		return intval( $signature_count );
	}//end method petitionSignatureCount

	/**
	 * get single petition signature count
	 *
	 * @param int $petition_id
	 *
	 * @return int
	 */
	public static function petitionSignatureTargetRatio( $petition_id = 0 ) {
		$ratio = 0;

		$petition_id = intval( $petition_id );
		if ( $petition_id > 0 ) {
			$target          = intval( CBXPetitionHelper::petitionSignatureTarget( $petition_id ) );
			$signature_count = intval( CBXPetitionHelper::petitionSignatureCount( $petition_id ) );

			if ( $target > 0 && $signature_count > 0 ) {
				$ratio = ( $signature_count / $target ) * 100;
				$ratio = number_format( $ratio, 2 );
			}
		}

		return $ratio;
	}//end method petitionSignatureTargetRatio

	/**
	 * Get all the pages
	 *
	 * @return array page names with key value pairs
	 */
	public static function get_pages() {
		$pages         = get_pages();
		$pages_options = array();
		if ( $pages ) {
			foreach ( $pages as $page ) {
				$pages_options[ $page->ID ] = $page->post_title;
			}
		}

		return $pages_options;
	}//end method get_pages

	/**
	 * Get the user roles for voting purpose
	 *
	 * @param string $useCase
	 *
	 * @return array
	 */
	public static function user_roles( $plain = true, $include_guest = false, $ignore = array() ) {
		global $wp_roles;

		if ( ! function_exists( 'get_editable_roles' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/user.php' );

		}

		$userRoles = array();
		if ( $plain ) {
			foreach ( get_editable_roles() as $role => $roleInfo ) {
				if ( in_array( $role, $ignore ) ) {
					continue;
				}
				$userRoles[ $role ] = $roleInfo['name'];
			}
			if ( $include_guest ) {
				$userRoles['guest'] = esc_html__( "Guest", 'cbxpetition' );
			}
		} else {
			//optgroup
			$userRoles_r = array();
			foreach ( get_editable_roles() as $role => $roleInfo ) {
				if ( in_array( $role, $ignore ) ) {
					continue;
				}
				$userRoles_r[ $role ] = $roleInfo['name'];
			}

			$userRoles = array(
				'Registered' => $userRoles_r,
			);

			if ( $include_guest ) {
				$userRoles['Anonymous'] = array(
					'guest' => esc_html__( "Guest", 'cbxpetition' ),
				);
			}
		}

		return apply_filters( 'cbxpetition_userroles', $userRoles, $plain, $include_guest );
	}//end method user_roles

	/**
	 * count user petition post
	 */
	public static function count_user_petition_posts( $user_id = 0 ) {
		$user_id = intval( $user_id );
		if ( $user_id == 0 ) {
			return 0;
		}

		$count = 0;

		global $wpdb;

		$where = " WHERE ( ( post_type = 'cbxpetition' AND ( post_status = 'publish' OR post_status = 'pending' OR post_status = 'draft' ) ) ) AND post_author = %d ";


		$count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->posts $where", $user_id ) );


		return intval( $count );
	}//end method count_user_cbxpetition_posts

	/**
	 * paginate_links_as_bootstrap()
	 * JPS 20170330
	 * Wraps paginate_links data in Twitter bootstrap pagination component
	 *
	 * @param array $args      {
	 *                         Optional. {@see 'paginate_links'} for native argument list.
	 *
	 * @type string $nav_class classes for <nav> element. Default empty.
	 * @type string $ul_class  additional classes for <ul.pagination> element. Default empty.
	 * @type string $li_class  additional classes for <li> elements.
	 * }
	 * @return array|string|void String of page links or array of page links.
	 */
	public static function paginate_links_as_bootstrap( $args = '' ) {
		$args['type'] = 'array';
		$defaults     = array(
			'nav_class' => '',
			'ul_class'  => '',
			'li_class'  => '',
		);

		$args       = wp_parse_args( $args, $defaults );
		$page_links = paginate_links( $args );


		if ( $page_links ) {
			$r         = '';
			$nav_class = empty( $args['nav_class'] ) ? '' : 'class="' . $args['nav_class'] . '"';
			$ul_class  = empty( $args['ul_class'] ) ? '' : ' ' . $args['ul_class'];

			//$r .= '<nav '. $nav_class .' aria-label="navigation">' . "\n\t";
			$r .= '<div ' . $nav_class . ' aria-label="navigation">' . "\n\t";

			$r .= '<ul class="cbxpetition-pagination ' . $ul_class . '">' . "\n";
			foreach ( $page_links as $link ) {
				$li_classes = explode( " ", $args['li_class'] );
				strpos( $link, 'current' ) !== false ? array_push( $li_classes, 'active' ) : ( strpos( $link, 'dots' ) !== false ? array_push( $li_classes, 'disabled' ) : '' );
				$class = empty( $li_classes ) ? '' : 'class="' . join( " ", $li_classes ) . '"';
				$r     .= "\t\t" . '<li ' . $class . '>' . $link . '</li>' . "\n";
			}

			$r .= "\t</ul>";
			$r .= "\n</div>";

			return '<div class="clearfix"></div><div class="cbxpetition-pagination-wrap">' . $r . '</div><div class="clearfix"></div>';
		}
	}//end method paginate_links_as_bootstrap

	/**
	 * Check recipient
	 *
	 * @param $recipients
	 *
	 * @return array|null
	 */
	public static function recipient_checkRecipient( $recipients ) {
		$new_recipients = array();
		foreach ( $recipients as $recipient ) {
			if ( ( isset( $recipient['name'] ) && $recipient['name'] != '' ) || ( isset( $recipient['designation'] ) && $recipient['designation'] ) != '' ) {
				array_push( $new_recipients, $recipient );
			}
		}

		if ( sizeof( $new_recipients ) > 0 ) {
			return $new_recipients;
		} else {
			return array();
		}
	}//end method recipient_checkRecipient

	/**
	 * Email type formats
	 *
	 * @return mixed|void
	 */
	public static function email_type_formats() {
		return apply_filters( 'cbxpetition_email_type_formats',
			array(
				'html'      => esc_html__( 'Righ Html Email', 'cbxpetition' ),
				'multipart' => esc_html__( 'Rich Html with Attachment', 'cbxpetition' ),
				'plain'     => esc_html__( 'Plain Text', 'cbxpetition' ),
			) );
	}//end method email_type_formats

	/**
	 * Default email templates for petition related activities
	 *
	 * @return array
	 */
	public static function email_templates() {
		$templates = array();

		//admin gets email after user sign a petition
		$templates['new_sign_admin_email_alert'] = array(
			'body'    => esc_html__( 'Hi, Admin
                            
A new signature is made. Here is the details:

Petition: {petition}
First Name: {first_name}
Last Name: {last_name}
Email: {user_email}
Comment: {comment}
Signature Status: {status}

{signature_edit_url}

Thank you.',
				'cbxpetition' ),
			'subject' => esc_html__( 'New Petition Sign Notification', 'cbxpetition' ),
			'heading' => esc_html__( 'New Petition Sign', 'cbxpetition' ),
		);

		//user gets email after sign a petition
		$templates['new_sign_user_email_alert'] = array(
			'body'    => esc_html__( 'Hi, {user_name}
                            
We got a signature request for email address {user_email}.

Sign Details: 

Petition: {petition}
First Name: {first_name}
Last Name: {last_name}
Email: {user_email}
Comment: {comment}

Signature Status: {status}

{activation_link}


Thank you.',
				'cbxpetition' ),
			'subject' => esc_html__( 'Petition Sign Notification', 'cbxpetition' ),
			'heading' => esc_html__( 'Petition Signed', 'cbxpetition' ),
		);

		//user gets email when sign is approved
		$templates['sign_approve_user_alert'] = array(
			'body'    => esc_html__( 'Hi, {user_name}
                            
We got a signature request for email address {user_email}.

Sign Details: 

Petition: {petition}
First Name: {first_name}
Last Name: {last_name}
Email: {user_email}
Comment: {comment}

Signature Status: {status}

{activation_link}


Thank you.',
				'cbxpetition' ),
			'subject' => esc_html__( 'Email Confirmation Notification', 'cbxpetition' ),
			'heading' => esc_html__( 'Confirm Your Email', 'cbxpetition' ),
		);

		return apply_filters( 'cbxpetition_email_templates', $templates );
	}//end method email_templates

	/**
	 * all public styles enqueue in cbxpetition
	 */
	public static function cbxpetition_public_styles() {
		$version = CBXPETITION_PLUGIN_VERSION;
		wp_register_style( 'venobox', plugin_dir_url( __FILE__ ) . '../assets/js/venobox/venobox.css', array(), $version, 'all' );
		wp_register_style( 'cbxpetition-public',
			plugin_dir_url( __FILE__ ) . '../assets/css/cbxpetition-public.css',
			array( 'venobox' ),
			$version,
			'all' );


		wp_enqueue_style( 'venobox' );
		wp_enqueue_style( 'cbxpetition-public' );
	}//end method cbxpetition_public_styles


	/**
	 * all public scripts enqueue in cbxpetition
	 */
	public static function cbxpetition_public_scripts() {
		$version = CBXPETITION_PLUGIN_VERSION;

		wp_register_script( 'venobox', plugin_dir_url( __FILE__ ) . '../assets/js/venobox/venobox.min.js', array( 'jquery' ), $version, true );
		wp_register_script( 'readmore', plugin_dir_url( __FILE__ ) . '../assets/js/readmore/readmore.js', array( 'jquery' ), $version, true );
		wp_register_script( 'jquery-validate', plugin_dir_url( __FILE__ ) . '../assets/js/jquery.validate.min.js', array( 'jquery' ), $version, true );

		wp_register_script( 'cbxpetition-public',
			plugin_dir_url( __FILE__ ) . '../assets/js/cbxpetition-public.js',
			array(
				'jquery',
				'readmore',
				'venobox',
				'jquery-validate',
			),
			$version,
			true );

		// Localize the script with new data
		$translation_array = array(
			'ajaxurl'           => admin_url( 'admin-ajax.php' ),
			'nonce'             => wp_create_nonce( 'cbxpetition_nonce' ),
			'is_user_logged_in' => is_user_logged_in() ? 1 : 0,
			'validation'        => array(
				'required'    => esc_html__( 'This field is required.', 'cbxpetition' ),
				'remote'      => esc_html__( 'Please fix this field.', 'cbxpetition' ),
				'email'       => esc_html__( 'Please enter a valid email address.', 'cbxpetition' ),
				'url'         => esc_html__( 'Please enter a valid URL.', 'cbxpetition' ),
				'date'        => esc_html__( 'Please enter a valid date.', 'cbxpetition' ),
				'dateISO'     => esc_html__( 'Please enter a valid date ( ISO ).', 'cbxpetition' ),
				'number'      => esc_html__( 'Please enter a valid number.', 'cbxpetition' ),
				'digits'      => esc_html__( 'Please enter only digits.', 'cbxpetition' ),
				'equalTo'     => esc_html__( 'Please enter the same value again.', 'cbxpetition' ),
				'maxlength'   => esc_html__( 'Please enter no more than {0} characters.', 'cbxpetition' ),
				'minlength'   => esc_html__( 'Please enter at least {0} characters.', 'cbxpetition' ),
				'rangelength' => esc_html__( 'Please enter a value between {0} and {1} characters long.', 'cbxpetition' ),
				'range'       => esc_html__( 'Please enter a value between {0} and {1}.', 'cbxpetition' ),
				'max'         => esc_html__( 'Please enter a value less than or equal to {0}.', 'cbxpetition' ),
				'min'         => esc_html__( 'Please enter a value greater than or equal to {0}.', 'cbxpetition' ),
				'recaptcha'   => esc_html__( 'Please check the captcha.', 'cbxpetition' ),
			),
			'ajax'              => array(
				'loading'  => esc_html__( 'Please wait, loading', 'cbxpetition' ),
				'loadmore' => esc_html__( 'Load More', 'cbxpetition' ),
				'fail'     => esc_html__( 'Sorry, request failed, please submit again', 'cbxpetition' ),
			),
			'readmore'          => array(
				'moreLink' => '<a href="#">' . esc_html__( 'Read More', 'cbxpetition' ) . '</a>',
				'lessLink' => '<a href="#">' . esc_html__( 'Close', 'cbxpetition' ) . '</a>',
			),
		);
		wp_localize_script( 'cbxpetition-public', 'cbxpetition', $translation_array );


		wp_enqueue_script( 'venobox' );
		wp_enqueue_script( 'readmore' );
		wp_enqueue_script( 'jquery-validate' );
		wp_enqueue_script( 'cbxpetition-public' );

	}//end method cbxpetition_public_scripts

	/**
	 * Get the max photo limit 1 to 10 dropdown
	 *
	 * @return type
	 */
	public static function get_max_photo_limit() {
		$max_photo_limit_arr = array();
		for ( $i = 1; $i <= 10; $i ++ ) {
			$max_photo_limit_arr[ $i ] = $i;
		}

		return apply_filters( 'cbxpetition_max_photo_limit', $max_photo_limit_arr );
	}//end method get_max_photo_limit

	/**
	 * delete uploaded photos of the petition
	 *
	 * @param int $petition_id
	 */
	public static function deletePetitionPhotosFolder( $petition_id = 0 ) {
		$dir_info = CBXPetitionHelper::checkUploadDir();
		if ( absint( $petition_id ) > 0 && intval( $dir_info['folder_exists'] ) == 1 ) {

			global $wp_filesystem;
			require_once( ABSPATH . '/wp-admin/includes/file.php' );
			WP_Filesystem();

			//$dir_to_del       = wp_upload_dir()['basedir'] . '/cbxpetition/' . $review_id;
			//$dir_thumb_to_del = $dir_to_del . '/thumbnail';
			$dir_to_del = $dir_info['cbxpetition_base_dir'] . $petition_id;
			//$dir_thumb_to_del = $dir_to_del . '/thumbnail';

			//if dir exists then delete


			/*array_map( 'unlink', glob( "$dir_to_del/*.*" ) );
			array_map( 'unlink', glob( "$dir_thumb_to_del/*.*" ) );
			if ( @rmdir( $dir_thumb_to_del ) ) {
				@rmdir( $dir_to_del );
			}*/

			$wp_filesystem->delete( $dir_to_del, true, 'd' );
		}
	}//end method deletePetitionPhotosFolder

	/**
	 * make cbxpetition folder in uploads directory if not exist, return path info
	 *
	 * @return mixed|void
	 */
	public static function checkUploadDir() {
		$upload_dir = wp_upload_dir();

		//wordpress core base dir and url
		$upload_dir_basedir = $upload_dir['basedir'];
		$upload_dir_baseurl = $upload_dir['baseurl'];

		//cbxpetition base dir and base url
		$cbxpetition_base_dir = $upload_dir_basedir . '/cbxpetition/';
		$cbxpetition_base_url = $upload_dir_baseurl . '/cbxpetition/';

		//cbxpetition temp dir and temp url
		$cbxpetition_temp_dir = $upload_dir_basedir . '/cbxpetition/temp/';
		$cbxpetition_temp_url = $upload_dir_baseurl . '/cbxpetition/temp/';

		/*if ( ! class_exists( 'WP_Filesystem_Base' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php' );
		}*/

		global $wp_filesystem;
		require_once( ABSPATH . '/wp-admin/includes/file.php' );
		WP_Filesystem();

		$folder_exists = 1;
		//let's check if the cbxpetition folder exists in upload dir
		//if ( ! ( new WP_Filesystem_Base )->exists( $cbxpetition_temp_dir ) ) {
		if ( ! $wp_filesystem->exists( $cbxpetition_temp_dir ) ) {

			$created = wp_mkdir_p( $cbxpetition_temp_dir );
			if ( $created ) {
				$folder_exists = 1;
			} else {
				$folder_exists = 0;
			}
		}

		$dir_info = array(
			'folder_exists'        => $folder_exists,
			'upload_dir_basedir'   => $upload_dir_basedir,
			'upload_dir_baseurl'   => $upload_dir_baseurl,
			'cbxpetition_base_dir' => $cbxpetition_base_dir,
			'cbxpetition_base_url' => $cbxpetition_base_url,
			'cbxpetition_temp_dir' => $cbxpetition_temp_dir,
			'cbxpetition_temp_url' => $cbxpetition_temp_url,
		);

		return apply_filters( 'cbxpetition_dir_info', $dir_info );
	}//end method checkUploadDir

	/**
	 * acceptable image ext
	 * @return array
	 */
	public static function imageExtArr() {
		return array( 'jpg', 'jpeg', 'gif', 'png' );
	}//end method imageExtArr
}//end class CBXPetitionHelper