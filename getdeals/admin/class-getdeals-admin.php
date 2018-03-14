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
				submit_button( 'Save Settings' );
			?></form>
		</div>
		
		<?php
	
	}

	public function section_api_credentials( $args ) {
		
		?>

		<p class="notice notice-info">
			<b>Note: You will be needing a verified GitHub account for this.</b><br>
			Visit <a href="https://getdeals.co.in/developers" target="_blank">https://getdeals.co.in/developers</a> to get your credentials. Sign in to GetDeals using your GitHub account. Click <code>Generate Token</code> to generate a new GetDeals API token. Your GitHub email address along with the GetDeals API token together make up the GetDeals API credentials.
		</p>

		<?php

	}

	public function fields_api_credentials( $args ) {
		
		$field = $args[ 'field' ];
		$options = get_option( 'getdeals_configuration' );
		$value = $options[ $field ];
		
		?>
		
		<input type="text" name="getdeals_configuration[<?php echo esc_attr( $field ); ?>]" value="<?php echo esc_attr( $value ); ?>" class="regular-text" />

		<?php
	
	}

	public function section_customize( $args ) {
		
	}

	public function field_custom_css( $args ) {

		$field = $args[ 'field' ];
		$options = get_option( 'getdeals_configuration' );
		$value = $options[ $field ];

		?>

		<textarea name="getdeals_configuration[<?php echo esc_attr( $field ); ?>]" rows="10" cols="50" class="large-text code"><?php echo $value; ?></textarea>

		<?php

	}

}
