<?php

/**
 * Plugin Name: Admin Hide Posts, Comments, Dashboard widgets
 * Description: Hide "posts" post type, comments, and all dashboard widgets.
 * Version: 0.0.1
 */


class Admin_Hide_Posts_Comments
 {

	private static $instance;

	public $plugin_dir_url;
	public $plugin_dir_path;
	public $plugin_data;

	private function __construct() {
	}

	private function setup() {
		add_action( 'wp_dashboard_setup', [ $this, 'remove_dashboard_widgets' ], 999 );
		add_action( 'wp_before_admin_bar_render', [ $this, 'remove_admin_bar_items' ] );
		add_action( 'admin_menu', [ $this, 'remove_admin_menus' ] );
	}

	public function remove_dashboard_widgets() {
		global $wp_meta_boxes;
		$wp_meta_boxes['dashboard'] = [];
	}

	public function remove_admin_bar_items() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'comments' );
		$wp_admin_bar->remove_menu( 'new-content' );
		$wp_admin_bar->remove_menu( 'updates' );
		$wp_admin_bar->remove_menu( 'itsec_admin_bar_menu' );
	}

	public function remove_admin_menus() {
		remove_menu_page( 'edit-comments.php' );
		remove_menu_page( 'edit.php' );
		remove_menu_page( 'link-manager.php' );
	}

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
			self::$instance->setup();
		}

		return self::$instance;
	}

	public function plugin_data() {
		if ( empty( $this->plugin_data ) ) {
			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}

			$this->plugin_data = get_plugin_data( __FILE__ );
			$this->plugin_data['plugin_dir_url']       = plugin_dir_url( __FILE__ );
			$this->plugin_data['plugin_dir_path']      = plugin_dir_path( __FILE__ );
		}

		return $this->plugin_data;
	}

}

function Admin_Hide_Posts_Comments() {
	return Admin_Hide_Posts_Comments::instance();
}

Admin_Hide_Posts_Comments();
