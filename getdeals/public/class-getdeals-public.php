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

	public function enqueue_scripts() {

		wp_enqueue_script( 'getdeals-unserialize', plugin_dir_url( __FILE__ ) . 'js/unserialize.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'getdeals-script', plugin_dir_url( __FILE__ ) . 'js/getdeals.js', array( 'jquery' ), $this->version, false );

		$options = get_option( 'getdeals_configuration' );
		$email = esc_html( $options[ 'email' ] ); // GD-API-Email
		$token = esc_html( $options[ 'token' ] ); // GD-API-Token
		wp_add_inline_script( 'getdeals-script', 'getdeals("' . $email . '", "' . $token . '");' );

	}

	public function getdeals_custom_css() {

		echo '<style type="text/css">
.gd-loader {
	width: 50px;
    height: 50px;
    border-width: 4px;
    border-style: solid;
    border-color: #f3f3f3;
    border-top-color: #3498db;
    border-radius: 50%;
    animation: spin 1.5s linear infinite;
}
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
[class^="gd-api-status-"] {
	display: none;
}
</style>';

		$option = get_option( 'getdeals_configuration' );
		echo '<style type="text/css">' . $option[ 'custom-css' ] . '</style>';

	}

	public function getdeals_search_form( $atts ) {

		$atts = shortcode_atts( array(
			'action' => '',
		), $atts );

		$html = <<<HTML
<form action="{$atts[ 'action' ]}" class="gd-search-form">
	<input type="search" name="q" class="gd-search-field-q" placeholder="What are you looking for?" />
	<input type="hidden" name="category" value="all" class="gd-search-field-category" />
	<input type="hidden" name="sort" value="relevance" class="gd-search-field-sort" />
	<input type="hidden" name="page" value="1" class="gd-search-field-page" />
	<span class="gd-search-button"><input type="submit" value="Search" /></span>
</form>
HTML;

		return $html;

	}

	public function getdeals_search_results() {

		$html = <<<HTML
<div class="gd-search-results">
	
</div>
<div class="gd-search-status">
	<div class="gd-api-status-load">
		<div class="gd-loader"></div>
	</div>
	<div class="gd-api-status-more">
		<button class="gd-load-more-button">Load More Results</button>
	</div>
	<div class="gd-api-status-last">
		<button class="gd-no-more-button" disabled>No More Results</button>
	</div>
	<div class="gd-api-status-none">
		<p class="gd-status-title">No Results Found</p>
		<p>We couldn't find any results for your search query. Please try a different search query.</p>
	</div>
	<div class="gd-api-status-error">
		<p class="gd-status-title">Unknown Error</p>
		<p>Something went wrong while getting the results. Please try again.</p>
		<button class="gd-try-again-button">Try Again</button>
	</div>
</div>
HTML;

		return $html;

	}

}
