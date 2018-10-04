<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://kountanis.com
 * @since      1.0.0
 *
 * @package    Asset_Attachments
 * @subpackage Asset_Attachments/includes
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
 * @package    Asset_Attachments
 * @subpackage Asset_Attachments/includes
 * @author     Panos Kountanis <panosktn@gmail.com>
 */
class Asset_Attachments {
	const META_KEY = '_asset_attachment_ids';

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Asset_Attachments_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	private static $_instance = null;

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
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'asset-attachments';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	public static function instance() {
		if ( null === self::$_instance ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Asset_Attachments_Loader. Orchestrates the hooks of the plugin.
	 * - Asset_Attachments_i18n. Defines internationalization functionality.
	 * - Asset_Attachments_Admin. Defines all hooks for the admin area.
	 * - Asset_Attachments_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-asset-attachments-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-asset-attachments-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-asset-attachments-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-asset-attachments-public.php';

		$this->loader = new Asset_Attachments_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Asset_Attachments_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Asset_Attachments_i18n();

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
		if ( ! is_admin() ) {
			return $this;
		}

		$plugin_admin = new Asset_Attachments_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_asset_meta_box' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'on_save_post' );
		$this->loader->add_filter( 'upload_mimes', $plugin_admin, 'add_asset_mime_types', 1, 1 );
		return $this;
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Asset_Attachments_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

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
	 * @return    Asset_Attachments_Loader    Orchestrates the hooks of the plugin.
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

	public function get_for_id( $post_id ) {
		// $found = true;
		// $cached = wp_cache_get( $this->get_cache_key( $post_id ), '', false, &$found );
		// if ( false !== $found ) {
		// 	return $this->clean_attachment_ids( explode( ',', $cached ) );
		// }
		$asset_attachment_ids = get_post_meta( $post_id, Asset_Attachments::META_KEY, true );

		if ( empty( $asset_attachment_ids ) ) {
			return array();
		}

		// wp_cache_set( $this->get_cache_key( $post_id ), implode( ',' $asset_attachment_ids) );

		return $this->clean_attachment_ids( explode( ',', $asset_attachment_ids ) );
	}

	public function clean_attachment_ids( $asset_attachment_ids ) {
		$cleaned_attachements = array();
		foreach ( $asset_attachment_ids as $asset_attachement_id ) {
			if ( ! is_numeric( $asset_attachement_id ) ) {
				continue;
			}

			$asset_attachement_id = absint( $asset_attachement_id );
			if ( false === wp_get_attachment_url( $asset_attachement_id ) ) {
				continue;
			}

			$cleaned_attachements[] = $asset_attachement_id;
		}

		return $cleaned_attachements;
	}

	private function get_cache_key( $post_id ) {
		return "_asset_attachment_ids_$post_id";
	}

	public function save_for_post( $post_id, $asset_attachment_ids ) {
		$asset_attachment_ids = $this->clean_attachment_ids( $asset_attachment_ids );
		update_post_meta( $post_id, Asset_Attachments::META_KEY, implode( ',', $asset_attachment_ids ) );
		// wp_cache_delete( $this->get_cache_key( $post_id ) );
	}
}
