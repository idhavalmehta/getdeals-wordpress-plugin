<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://about.me/idhavalmehta
 * @since      1.0.0
 *
 * @package    GetDeals
 * @subpackage GetDeals/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    GetDeals
 * @subpackage GetDeals/admin
 * @author     Dhaval Mehta <idhavalmehta@gmail.com>
 */
class GetDeals_Admin {

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
	 * Creates the menu for wp-admin sidebar.
	 *
	 * @since    1.0.0
	 */
	public function create_menu() {

		add_submenu_page( 
			'options-general.php', 
			'GetDeals', 
			'GetDeals', 
			'manage_options', 
			'getdeals', 
			array( $this, 'page_getdeals' )
		);

	}

	/**
	 * Register settings for the wp-admin page.
	 *
	 * @since    1.0.0
	 */
	public function register_settings() {

		register_setting( 
			'getdeals', 
			'getdeals_configuration'
		);

		add_settings_section( 
			'api_credentials',
			'API Credentials', 
			array( $this, 'section_api_credentials' ), 
			'getdeals'
		);

		add_settings_field(
			'GD-API-Email',
			'GD-API-Email',
			array( $this, 'fields_api_credentials' ),
			'getdeals',
			'api_credentials',
			array( 'field' => 'email' )
		);

		add_settings_field(
			'GD-API-Token',
			'GD-API-Token',
			array( $this, 'fields_api_credentials' ),
			'getdeals',
			'api_credentials',
			array( 'field' => 'token' )
		);

		add_settings_section( 
			'customize',
			'Customization', 
			array( $this, 'section_customize' ), 
			'getdeals'
		);

		add_settings_field(
			'custom-css',
			'Custom CSS',
			array( $this, 'field_custom_css' ),
			'getdeals',
			'customize',
			array( 'field' => 'custom-css' )
		);

	}

	/**
	 * Output the settings page.
	 *
	 * @since    1.0.0
	 */
	public function page_getdeals() {
		
		if ( !current_user_can( 'manage_options' ) ) {
			return;
		}
		
		?>
		
		<div class="wrap">
			<h1>GetDeals</h1>
			<form action="options.php" method="POST"><?php
				settings_fields( 'getdeals' );
				do_settings_sections( 'getdeals' );
				submit_button( 'Save Configuration' );
			?></form>
			<hr>
			<h2>How To Use</h2>
			<p>GetDeals registers 2 shortcodes for you to use in your posts, pages and sidebars:</p>
			<ol>
				<li>[getdeals-search-form]</li>
				<li>[getdeals-search-results]</li>
			</ol>
			<p>The [getdeals-search-form] shortcode displays the search form and the [getdeals-search-results] shortcode displays the search results. The default behaviour of the search form is to show the search results on the same page as the search form. But, that can be changed to show the search results on a different page.</p>
			<p>The [getdeals-search-form] shortcode has 1 attribute called "action". It is similar to the "action" attribute of HTML forms. By specifying the the URL of the results page as the "action", you can show the search results on a different page. Just make sure that the results page contains [getdeals-search-results] shortcode.</p>
			<p><b><u>Example Use Cases</u></b></p>
			<ol>
				<li>
					<p>Search form and search results on same page:</p>
					<p>[getdeals-search-form]<br>[getdeals-search-results]</p>
				</li>
				<li>		
					<p>Search form on "page-1" and search results on "page-2":</p>
					<p>Page 1: [getdeals-search-form action="/page-2"]<br>Page 2: [getdeals-search-results]</p>
				</li>
				<li>
					<p>Search form in sidebar and search results on "page":</p>
					<p>In this case, you need to create a new text widget and add it to the sidebar.</p>
					<p>Widget: [getdeals-search-form action="/page"]<br>Page: [getdeals-search-results]</p>
					<p>In case the shortcode in the text widget does not work, you need to add the following lines to your theme's functions.php file:</p>
					<pre>add_filter( 'widget_text', 'shortcode_unautop' );
add_filter( 'widget_text', 'do_shortcode', 11 );</pre>
				</li>
			</ol>
		</div>
		
		<?php
	
	}

	/**
	 * Callback for API Credentials section.
	 *
	 * @since    1.0.0
	 * @param      array    $args       Arguments passed to add_settings_section function.
	 */
	public function section_api_credentials( $args ) {
		
		?>

		<p><b>Note: You will be needing a verified GitHub account for this.</b></p>
		<p>Visit <a href="https://getdeals.co.in/developers" target="_blank">https://getdeals.co.in/developers</a> to get your credentials. Sign in to GetDeals using your GitHub account. Click <code>Generate Token</code> to generate a new GetDeals API token. Your GitHub email address along with the GetDeals API token together make up the GetDeals API credentials.</p>

		<?php

	}

	/**
	 * Output API Credentials fields.
	 *
	 * @since    1.0.0
	 * @param      array    $args       Arguments passed to add_settings_field function.
	 */
	public function fields_api_credentials( $args ) {
		
		$field = $args[ 'field' ];
		$options = get_option( 'getdeals_configuration' );
		$value = $options[ $field ];
		
		?>
		
		<input type="text" name="getdeals_configuration[<?php echo esc_attr( $field ); ?>]" value="<?php echo esc_attr( $value ); ?>" class="regular-text" />

		<?php
	
	}

	/**
	 * Callback for Customize section.
	 *
	 * @since    1.0.0
	 * @param      array    $args       Arguments passed to add_settings_section function.
	 */
	public function section_customize( $args ) {

		?>

		<p>Default stylesheet containing all the elements: <a href="https://github.com/getdeals/wordpress-plugin/wiki/default-stylesheet" target="_blank">GitHub</a></p>

		<?php
		
	}

	/**
	 * Output Customization fields.
	 *
	 * @since    1.0.0
	 * @param      array    $args       Arguments passed to add_settings_field function.
	 */
	public function field_custom_css( $args ) {

		$field = $args[ 'field' ];
		$options = get_option( 'getdeals_configuration' );
		$value = $options[ $field ];

		?>

		<textarea name="getdeals_configuration[<?php echo esc_attr( $field ); ?>]" rows="10" cols="50" class="large-text code"><?php echo $value; ?></textarea>

		<?php

	}

}
