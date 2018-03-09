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
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in GetDeals_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The GetDeals_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/getdeals-admin.css', array(), $this->version, 'all' );

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
		 * defined in GetDeals_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The GetDeals_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/getdeals-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function menu_links() {

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
		
		// check user capabilities
		if ( !current_user_can( 'manage_options' ) ) {
			return;
		}

		// add error/update messages
		/*if ( isset( $_GET[ 'settings-updated' ] ) ) {
			add_settings_error( 'getdeals_messages', 'getdeals_message', 'Settings Saved', 'updated' );
		}

		// show error/update messages
		settings_errors( 'getdeals_messages' );*/
		
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

		?>

		<textarea name="getdeals_configuration[<?php echo esc_attr( $field ); ?>]" rows="10" cols="50" class="large-text code"><?php echo $value; ?></textarea>

		<?php

	}

	public function add_help_screen_to_books(){

		/*$current_screen = get_current_screen();

		if ( $current_screen->id == 'settings_page_getdeals' ) {

			$current_screen->add_help_tab( array(
				'id'        => 'help_api_credentials',
				'title'     => 'API Credentials',
				'content'   => '',
			) );

			$current_screen->add_help_tab( array(
				'id'        => 'help_custom_css',
				'title'     => 'Custom CSS',
				'content'   => '',
			) );

		}*/

	}

}
