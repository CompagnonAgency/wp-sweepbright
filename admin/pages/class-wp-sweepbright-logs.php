<?php

/**
 * WP_SweepBright_Logs.
 *
 * This class contains logic for the logging.
 *
 * @package    WP_SweepBright_Contact
 */

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class WP_SweepBright_Logs extends WP_List_Table {

	/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Log', 'sp' ),
			'plural'   => __( 'Logs', 'sp' ),
			'ajax'     => false
		] );

	}

	public static function get_term_id_by_slug() {
		$term_id = false;
		if (get_term_by('slug', 'wp_sweepbright_logs', 'wp_log_type')) {
			$term_id = get_term_by('slug', 'wp_sweepbright_logs', 'wp_log_type')->term_id;
		}
		return $term_id;
	}


	/**
	 * Retrieve posts data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_posts( $per_page = 20, $page_number = 1 ) {

		global $wpdb;
		$term_id = WP_SweepBright_Logs::get_term_id_by_slug();

		$sql = "SELECT * FROM {$wpdb->prefix}posts
			LEFT JOIN $wpdb->term_relationships ON
			($wpdb->posts.ID = $wpdb->term_relationships.object_id)
			LEFT JOIN $wpdb->term_taxonomy ON
			($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
			WHERE $wpdb->posts.post_type = 'wp_log'
			AND $wpdb->term_taxonomy.taxonomy = 'wp_log_type'
			AND $wpdb->term_taxonomy.term_id = $term_id
			AND $wpdb->posts.post_status = 'publish'";

		$sql .= ' ORDER BY wp_posts.post_date DESC';
		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


		$result = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $result;
	}


	/**
	 * Delete a post record.
	 *
	 * @param int $id post ID
	 */
	public static function delete_post( $id ) {
		global $wpdb;

		$wpdb->delete(
			"{$wpdb->prefix}posts",
			[ 'ID' => $id ],
			[ '%d' ]
		);
	}


	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;
		$term_id = WP_SweepBright_Logs::get_term_id_by_slug();

		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}posts
			LEFT JOIN $wpdb->term_relationships ON
			($wpdb->posts.ID = $wpdb->term_relationships.object_id)
			LEFT JOIN $wpdb->term_taxonomy ON
			($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
			WHERE $wpdb->posts.post_type = 'wp_log'
			AND $wpdb->term_taxonomy.taxonomy = 'wp_log_type'
			AND $wpdb->term_taxonomy.term_id = $term_id
			AND $wpdb->posts.post_status = 'publish'";

		return $wpdb->get_var( $sql );
	}


	/** Text displayed when no post data is available */
	public function no_items() {
		_e( 'No logs available.', 'sp' );
	}


	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'post_title':
				return $this->column_name($item);
			case 'action':
				return get_post_meta($item['ID'], '_wp_log_wp_sweepbright_action', true);
			case 'status':
				return get_post_meta($item['ID'], '_wp_log_wp_sweepbright_status', true);
			case 'date':
				return get_post_meta($item['ID'], '_wp_log_wp_sweepbright_date', true);
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['ID']
		);
	}


	/**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_name( $item ) {

		$delete_nonce = wp_create_nonce( 'sp_delete_post' );

		if ($item['post_title'] !== '-') {
			$title = '<strong><a href="/wp-admin/post.php?post='.$item['post_parent'].'&action=edit">' . $item['post_title'] . '</a></strong>';
		} else {
			$title = '<strong>-</strong>';
		}

		$actions = [
			'delete' => sprintf( '<a href="?page=%s&action=%s&post=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['ID'] ), $delete_nonce )
		];

		return $title . $this->row_actions( $actions );
	}


	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	public function get_columns() {
		$columns = [
			'cb'      => '<input type="checkbox" />',
			'post_title'    => __( 'Estate', 'text_domain' ),
			'action' => __( 'Action', 'text_domain' ),
			'status' => __( 'Status', 'text_domain' ),
			'date' => __( 'Date', 'text_domain' ),
		];

		return $columns;
	}


	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
		);

		return $sortable_columns;
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = [
			'bulk-delete' => 'Delete'
		];

		return $actions;
	}

	public function get_hidden_columns() {
      return array();
  }


	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {
		/** Process bulk action */
		$this->process_bulk_action();

		$columns = $this->get_columns();
    $hidden = $this->get_hidden_columns();
    $sortable = $this->get_sortable_columns();
    $this->_column_headers = array($columns, $hidden, $sortable);

		$per_page     = $this->get_items_per_page( 'posts_per_page', 20 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );

		$this->items = self::get_posts( $per_page, $current_page );
	}

	public function process_bulk_action() {
		//Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {

			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

			if ( ! wp_verify_nonce( $nonce, 'sp_delete_post' ) ) {
				die( 'Go get a life script kiddies' );
			}
			else {
				if ($_GET['post']) {
					self::delete_post( absint( $_GET['post'] ) );
				}
			}

		}

		// If the delete bulk action is triggered
		if ( isset($_POST['bulk-delete']) && ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
		     || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
		) {
			$delete_ids = esc_sql( $_POST['bulk-delete'] );

			// loop over the array of record IDs and delete them
			foreach ( $delete_ids as $id ) {
				self::delete_post( $id );

			}
		}
	}

}

class SP_Plugin {

	// class instance
	static $instance;

	// post WP_List_Table object
	public $posts_obj;

	// class constructor
	public function __construct() {
		$this->plugin_menu();
	}

	public function plugin_menu() {
		$this->posts_obj = new WP_SweepBright_Logs();
		$this->plugin_settings_page();

	}

	/**
	 * Plugin settings page
	 */
	public function plugin_settings_page() {
		?>
		<div class="wrap">
			<h2>Logs</h2>

			<div id="poststuff">
				<div id="post-body" class="metabox-holder">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<form method="post">
								<?php
								$this->posts_obj->prepare_items();
								$this->posts_obj->display();
								?>
							</form>
						</div>
					</div>
				</div>
				<br class="clear">
			</div>
		</div>
	<?php
	}

	/** Singleton instance */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}

SP_Plugin::get_instance();
