<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://about.me/idhavalmehta
 * @since      1.0.0
 *
 * @package    GetDeals
 * @subpackage GetDeals/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    GetDeals
 * @subpackage GetDeals/public
 * @author     Dhaval Mehta <idhavalmehta@gmail.com>
 */
class GetDeals_Public {

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
	
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/getdeals-public.css', array(), $this->version, 'all' );

	}

	public function enqueue_scripts() {

		wp_enqueue_script( 'getdeals-unserialize', plugin_dir_url( __FILE__ ) . 'js/unserialize.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'getdeals-script', plugin_dir_url( __FILE__ ) . 'js/getdeals.js', array( 'jquery' ), $this->version, false );

		$options = get_option( 'getdeals_configuration' );
		$email = esc_html( $options[ 'email' ] ); // GD-API-Email
		$token = esc_html( $options[ 'token' ] ); // GD-API-Token
		wp_add_inline_script( 'getdeals-script', 'getdeals("' . $email . '", "' . $token . '");' );

	}

	public function register_shortcodes() {



	}

}
