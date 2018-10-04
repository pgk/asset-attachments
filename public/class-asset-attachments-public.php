<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://kountanis.com
 * @since      1.0.0
 *
 * @package    Asset_Attachments
 * @subpackage Asset_Attachments/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Asset_Attachments
 * @subpackage Asset_Attachments/public
 * @author     Panos Kountanis <panosktn@gmail.com>
 */
class Asset_Attachments_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/asset-attachments-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/asset-attachments-public.js', array( 'jquery' ), $this->version, false );
		global $post;
		if ( isset( $post ) && ! is_single() ) {
			return;
		}
		$asset_attachment_ids = asset_attachments()->get_for_id( $post->ID ) ;
		// var_dump($asset_attachment_ids); die;
		foreach ( $asset_attachment_ids as $asset_attachment_id ) {
			$mime = get_post_mime_type($asset_attachment_id);

			if ( in_array( $mime, array( 'javascript', 'text/javascript', 'application/javascript' ) ) ) {
				$url = wp_get_attachment_url( $asset_attachment_id );
				wp_enqueue_script( $this->plugin_name . '-asset-attachment1-' . $post->ID . '-' . $asset_attachment_id, $url, array(), $this->version, false );
			}

			if ( in_array( $mime, array( 'css', 'text/css' ) ) ) {
				$url = wp_get_attachment_url( $asset_attachment_id );
				wp_enqueue_style( $this->plugin_name . '-asset-attachment1-' . $post->ID . '-' . $asset_attachment_id, $url, array(), $this->version, false );
			}
		}
	}

}
