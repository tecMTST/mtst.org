<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


/**
 * Class CBXPetitionSign_List_Table
 */
class CBXPetitionSign_List_Table extends WP_List_Table {

	function __construct() {

		//Set parent defaults
		parent::__construct( array(
			'singular' => 'cbxpetitionsign',     //singular name of the listed records
			'plural'   => 'cbxpetitionsigns',    //plural name of the listed records
			'ajax'     => false      //does this table support ajax?
		) );
	}

	/**
	 * Callback for column 'State'
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_state( $item ) {
		$state_key = esc_attr( $item['state'] );

		return CBXPetitionHelper::getPetitionSignState( $state_key );
	}

	/**
	 * Callback for column 'User Email'
	 *
	 * @param array $item
	 *
	 * @return string
	 */

	function column_email( $item ) {
		$user_email = stripslashes( $item['email'] );

		$user_info = get_user_by( 'email', $item['email'] );

		if ( $user_info !== false ) {

			if ( current_user_can( 'edit_user', $user_info->ID ) ) {
				$user_email = $user_email . '<a target="_blank" title="' . esc_html__( 'View User',
						'cbxpetition' ) . '" href="' . get_edit_user_link( $user_info->ID ) . '">' . ' (' . esc_html__( 'View',
						'cbxpetition' ) . ')' . '</a>';
			}
		} else {
			$user_email = $user_email . esc_html__( '(Guest)', 'cbxpetition' );
		}

		return $user_email;
	}

	/**
	 * Callback for column 'Sign Comment'
	 *
	 * @param array $item
	 *
	 * @return string
	 */

	function column_comment( $item ) {
		$comment = wp_unslash( sanitize_textarea_field( $item['comment'] ) );
		if ( strlen( $comment ) > 25 ) {
			$comment = substr( $comment, 0, 25 ) . '...';
		}

		$comment = '<p class="cbxpetition-comment-expand">' . $comment . '</p>';

		return $comment;
	}

	/**
	 * Callback for column 'petition_id'
	 *
	 * @param array $item
	 *
	 * @return string
	 */

	function column_petition_id( $item ) {
		$petition_id = intval( $item['petition_id'] );

		$edit_url = esc_url( get_edit_post_link( $petition_id ) );

		$edit_link = $item['petition_id'];
		if ( ! is_null( $edit_url ) ) {
			$edit_link = '<a target="_blank" href="' . get_permalink( $petition_id ) . '">' . get_the_title( $petition_id ) . '</a>' . ' (' . esc_html__( 'ID:',
					'cbxpetition' ) . $petition_id . ')' . '<a href="' . $edit_url . '" title="' . esc_html__( 'Edit Petition',
					'cbxpetition' ) . '">' . esc_html__( ' (Edit)', 'cbxpetition' ) . '</a>';
		}

		return $edit_link;
	}

	/**
	 * Callback for column 'ID'
	 *
	 * @param array $item
	 *
	 * @return string
	 */

	function column_id( $item ) {
		$edit_url  = esc_url( admin_url( 'edit.php?post_type=cbxpetition&page=cbxpetitionsigns&view=addedit&id=' . $item['id'] ) );
		$edit_link = $item['id'] . '<a href="' . $edit_url . '" target="_blank" title="' . esc_html__( 'Edit Signature',
				'cbxpetition' ) . '">' . esc_html__( ' (Edit)', 'cbxpetition' ) . '</a>';

		return $edit_link;
	}


	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="%1$s[]" value="%2$s" />',
			/*$1%s*/
			$this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
			/*$2%s*/
			$item['id']                //The value of the checkbox should be the record's id
		);
	}

	function column_default( $item, $column_name ) {

		switch ( $column_name ) {
			case 'id':
				return $item[ $column_name ];
			case 'petition_id':
				return $item[ $column_name ];
			case 'f_name':
				return $item[ $column_name ];
			case 'l_name':
				return $item[ $column_name ];
			case 'email':
				return $item[ $column_name ];
			case 'comment':
				return $item[ $column_name ];
			case 'state':
				return $item[ $column_name ];
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}

	/**
	 * Add extra markup in the toolbars before or after the list
	 *
	 * @param string $which , helps you decide if you add the markup after (bottom) or before (top) the list
	 */
	function extra_tablenav( $which ) {
		if ( $which == "top" ) {
			$petition_id = isset( $_REQUEST['petition_id'] ) ? intval( $_REQUEST['petition_id'] ) : 0;

			//get the petitions
			//$all_petitions = CBXPetitionHelper::getAllPetitions();


			?>

            <!-- petition dropdown filter -->
            <!--<div class="alignleft actions">
					<label for="petition_id"
						   class="screen-reader-text"><?php /*esc_html_e( 'Filter by Petition', 'cbxpetition' ) */ ?></label>
					<select class="form-control form" name="petition_id" id="petition_id">
						<option <?php /*echo ( $petition_id == 0 ) ? ' selected="selected" ' : ''; */ ?>
							value="0"><?php /*esc_html_e( 'Select Petition', 'cbxpetition' ); */ ?></option>
						<?php
			/*							foreach ( $all_petitions as $post_id => $post_title ):
											$selected = ( ( $petition_id > 0 && $petition_id == $post_id ) ? ' selected="selected" ' : '' );
											echo '<option  ' . $selected . ' value="' . $post_id . '">' . stripslashes( $post_title) . ' (' . esc_html__( 'ID:', 'cbxpetition' ) . $post_id . ')</option>';
											*/ ?>
							<?php /*endforeach; */ ?>
					</select>
					<input type="submit" name="filter_action" id="post-query-submit" class="button"
						   value="<?php /*esc_html_e( 'Filter', 'cbxpetition' ) */ ?>" />
				</div>-->

            <!-- log export view through hook -->
			<?php
			do_action( 'cbxpetition_sign_log_filter_extra' );
		}
	}


	function get_columns() {
		$columns = array(
			'cb'          => '<input type="checkbox" />', //Render a checkbox instead of text
			'id'          => esc_html__( 'ID', 'cbxpetition' ),
			'petition_id' => esc_html__( 'Petition', 'cbxpetition' ),
			'f_name'      => esc_html__( 'First Name', 'cbxpetition' ),
			'l_name'      => esc_html__( 'Last Name', 'cbxpetition' ),
			'email'       => esc_html__( 'Email', 'cbxpetition' ),
			'comment'     => esc_html__( 'Comment', 'cbxpetition' ),
			'state'       => esc_html__( 'State', 'cbxpetition' ),
		);

		return $columns;
	}


	function get_sortable_columns() {
		$sortable_columns = array(
			'id'          => array( 'id', false ), //true means it's already sorted
			'petition_id' => array( 'petition_id', false ),
			'f_name'      => array( 'f_name', false ),
			'l_name'      => array( 'l_name', false ),
			'email'       => array( 'email', false ),
			'state'       => array( 'state', false ),
		);

		return $sortable_columns;
	}


	/**
	 * Petition Bulk actions
	 *
	 * @return array|mixed|void
	 */
	function get_bulk_actions() {
		$bulk_actions           = CBXPetitionHelper::getPetitionSignStates();
		$bulk_actions['delete'] = esc_html__( 'Delete', 'cbxpetition' );

		$bulk_actions = apply_filters( 'cbxpetition_sign_state_bulk_action', $bulk_actions );

		return $bulk_actions;
	}//end method get_bulk_actions

	function process_bulk_action() {

		global $wpdb;
		$settings = new CBXPetition_Settings();

		$new_status = $current_action = $this->current_action();

		if ( $new_status == - 1 ) {
			return;
		}

		if ( ! empty( $_REQUEST['cbxpetitionsign'] ) ) {
			$petition_signs_table = $wpdb->prefix . "cbxpetition_signs";

			$current_user = wp_get_current_user();
			$user_id      = $current_user->ID;


			$results = wp_unslash( $_REQUEST['cbxpetitionsign'] );
			foreach ( $results as $id ) {
				$id = (int) $id;

				//at first keep the log record

				$log_info    = CBXPetitionHelper::petitionSignInfo( $id );
				$petition_id = intval( $log_info['petition_id'] );

				if ( 'delete' === $current_action ) {


					do_action( 'cbxpetition_sign_delete_before', $log_info, $id, $petition_id );

					if ( $log_info !== null && sizeof( $log_info ) > 0 ) {
						//now delete
						$sql           = $wpdb->prepare( "DELETE FROM $petition_signs_table WHERE id=%d", $id );
						$delete_status = $wpdb->query( $sql );

						if ( $delete_status !== false ) {
							do_action( 'cbxpetition_sign_delete_after', $log_info, $id, $petition_id );
						}

					}
				} else {
					$old_status = esc_attr( $log_info['state'] );
					if ( ! is_null( $log_info ) && $new_status != $old_status ) {


						$update = $wpdb->update(
							$petition_signs_table,
							array(
								'state'    => $new_status,
								'mod_by'   => $user_id,
								'mod_date' => current_time( 'mysql' ),
							),
							array( 'id' => $id ),
							array(
								'%s', //status
								'%d', //mod_by
								'%s'  //mod_date
							),
							array( '%d' )
						);

						if ( $update !== false ) {
							$log_info['state']    = $new_status;
							$log_info['mod_by']   = $user_id;
							$log_info['mod_date'] = current_time( 'mysql' );

							do_action( 'cbxpetition_sign_status_to_' . $new_status, $log_info, $old_status, $new_status );
							do_action( 'cbxpetition_sign_status_from_' . $old_status . '_to_' . $new_status, $log_info, $old_status, $new_status );
						}


						if ( $new_status == 'approved' ) {

							$user_sign_approve_user_alert = $settings->get_option( 'sign_approve_user_alert', 'cbxpetition_email_user', '' );
							if ( $user_sign_approve_user_alert == 'on' ) {
								$user_email_status = CBXPetitionMailAlert::sendSignApprovedEmailAlert( $log_info, $old_status, $new_status );
							}

						}

					}
				}
			}
		}
	}//end method process_bulk_action


	function prepare_items() {
		global $wpdb; //This is used only if making any database queries

		$user   = get_current_user_id();
		$screen = get_current_screen();

		$current_page = $this->get_pagenum();

		$option_name = $screen->get_option( 'per_page', 'option' ); //the core class name is WP_Screen


		$per_page = intval( get_user_meta( $user, $option_name, true ) );

		if ( $per_page == 0 ) {
			$per_page = intval( $screen->get_option( 'per_page', 'default' ) );
		}

		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );

		$this->process_bulk_action();

		$search      = ( isset( $_REQUEST['s'] ) && $_REQUEST['s'] != '' ) ? sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) : '';
		$order       = ( isset( $_REQUEST['order'] ) && $_REQUEST['order'] != '' ) ? sanitize_text_field( $_REQUEST['order'] ) : 'DESC';
		$orderby     = ( isset( $_REQUEST['orderby'] ) && $_REQUEST['orderby'] != '' ) ? sanitize_text_field( wp_unslash( $_REQUEST['orderby'] ) ) : 'id';
		$petition_id = isset( $_REQUEST['petition_id'] ) ? intval( $_REQUEST['petition_id'] ) : 0;
		$status      = isset( $_REQUEST['state'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['state'] ) ) : 'all';

		$data = CBXPetitionHelper::getSignListingData( $search,
			$petition_id,
			0,
			$status,
			$order,
			$orderby,
			$per_page,
			$current_page );

		$total_items = intval( CBXPetitionHelper::getSignListingDataCount( $search,
			$petition_id,
			0,
			$status,
			$per_page,
			$current_page ) );

		$this->items = $data;

		/**
		 * REQUIRED. We also have to register our pagination options & calculations.
		 */
		$this->set_pagination_args( array(
			'total_items' => $total_items,                  //WE have to calculate the total number of items
			'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
			'total_pages' => ceil( $total_items / $per_page )   //WE have to calculate the total number of pages
		) );

	}

	/**
	 * Generates content for a single row of the table
	 *
	 * @since  3.1.0
	 * @access public
	 *
	 * @param object $item The current item
	 */
	public function single_row( $item ) {
		$row_class = 'cbxpetition_row';
		$row_class = apply_filters( 'cbxpetition_row_class', $row_class, $item );
		echo '<tr id="cbxpetition_row_' . $item['id'] . '" class="' . $row_class . '">';
		$this->single_row_columns( $item );
		echo '</tr>';
	}


	/**
	 * Message to be displayed when there are no items
	 *
	 * @since  3.1.0
	 * @access public
	 */
	public function no_items() {
		echo '<div class="notice notice-warning inline "><p>' . esc_html__( 'No petition sign found. Please change your search criteria for better result.',
				'cbxpetition' ) . '</p></div>';
	}
}//end method CBXPetitionSign_List_Table