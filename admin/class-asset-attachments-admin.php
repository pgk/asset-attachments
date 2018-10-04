<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://kountanis.com
 * @since      1.0.0
 *
 * @package    Asset_Attachments
 * @subpackage Asset_Attachments/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Asset_Attachments
 * @subpackage Asset_Attachments/admin
 * @author     Panos Kountanis <panosktn@gmail.com>
 */
class Asset_Attachments_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Asset_Attachments_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Asset_Attachments_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name . '-admin', plugin_dir_url( __FILE__ ) . 'css/asset-attachments-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Asset_Attachments_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Asset_Attachments_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name . '-admin', plugin_dir_url( __FILE__ ) . 'js/asset-attachments-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function add_asset_mime_types( $mime_types = array() ) {
		$mime_types['js'] = 'text/javascript';
		$mime_types['css'] = 'text/css';
		$mimes['svg'] = 'image/svg+xml';
		$mimes['json'] = 'application/json';
		return $mime_types;
	}

	public function add_asset_meta_box() {
		add_meta_box(
			'asset-attachments',
			__( 'Asset Attachments', 'asset-attachment' ),
			array( $this, 'att_metabox_cb')
		);

	}

	public function on_save_post( $post_id ) {
		// Check if our nonce is set.
		if ( ! isset( $_POST['asset_attachement_nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['asset_attachement_nonce'], 'asset_attachement_nonce' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		if ( ! isset( $_POST['asset_attachement_ids'] ) ) {
				return;
		}

		$cleaned_attachements = asset_attachments()->clean_attachment_ids( $_POST['asset_attachement_ids'] );
		asset_attachments()->save_for_post( $post_id, $cleaned_attachements );
	}

	public function att_metabox_cb( $post ) {
		$asset_attachment_ids = asset_attachments()->get_for_id( $post->ID ) ;
		include_once dirname( __FILE__ ) . '/partials/admin-display.php';
	}

}
