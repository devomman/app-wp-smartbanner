<?php
/**
 * Description of what this module (or file) is doing.
 *
 * @package App_WP_Smartbanner
 */

/**
 * Class App_WP_Smartbanner
 *
 * @package App_WP_Smartbanner
 */
class App_WP_Smartbanner {

	/**
	 * The plugin version number.
	 *
	 * @var string
	 */
	public $version = '1.0';

	/**
	 * The plugin settings array.
	 *
	 * @var array
	 */
	public $settings = array();

	/**
	 * The plugin options array.
	 *
	 * @var array
	 */
	public $options = array();

	/**
	 * Storage for class instances.
	 *
	 * @var array
	 */
	public $instances = array();

	/**
	 * Storage for general meta fields.
	 *
	 * @var array
	 */
	public $general_meta = array(
		'smartbanner:title'       => 'app_name',
		'smartbanner:author'      => 'author_name',
		'smartbanner:price'       => 'price',
		'smartbanner:button'      => 'view_label',
		'smartbanner:close-label' => 'close_label',
	);

	/**
	 * Storage for ios meta fields.
	 *
	 * @var array
	 */
	public $ios_meta = array(
		'smartbanner:button-url-apple'   => 'apple_app_store_url',
		'smartbanner:icon-apple'         => 'apple_app_store_icon_url',
		'smartbanner:price-suffix-apple' => 'apple_app_store_tagline',
	);

	/**
	 * Storage for android meta fields.
	 *
	 * @var array
	 */
	public $android_meta = array(
		'smartbanner:button-url-google'   => 'google_play_store_url',
		'smartbanner:icon-google'         => 'google_play_store_icon_url',
		'smartbanner:price-suffix-google' => 'google_play_store_tagline',
	);

	/**
	 * Storage for default meta values.
	 *
	 * @var array
	 */
	public static $defaults = array();

	/**
	 * A dummy constructor to ensure App_WP_Smartbanner is only setup once.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @return  void
	 */
	public function __construct() {
		// Do nothing.
	}

	/**
	 * Initialize
	 *
	 * Sets up the App_WP_Smartbanner plugin.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @return  void
	 */
	public function initialize() {

		// Define constants.
		$this->define( 'APP_WP_SMARTBANNER', true );
		$this->define( 'APP_WP_SMARTBANNER_PATH', plugin_dir_path( __FILE__ ) );
		$this->define( 'APP_WP_SMARTBANNER_BASENAME', plugin_basename( __FILE__ ) );
		$this->define( 'APP_WP_SMARTBANNER_VERSION', $this->version );

		// Define settings.
		$this->settings = array(
			'name'     => _x( 'WP Smartbanner', 'backend', 'app-wp-smartbanner' ),
			'slug'     => dirname( APP_WP_SMARTBANNER ),
			'version'  => APP_WP_SMARTBANNER_VERSION,
			'basename' => APP_WP_SMARTBANNER_BASENAME,
			'path'     => APP_WP_SMARTBANNER_PATH,
			'file'     => __FILE__,
			'url'      => plugin_dir_url( __FILE__ ),
		);

		// Include admin.
		if ( is_admin() ) {
			require_once 'includes/admin/class-app-wp-smartbanner-options.php';
			new App_WP_Smartbanner_Options();
		}

		// Add actions.
		add_action( 'init', array( $this, 'init' ), 10 );
		add_action( 'wp_head', array( $this, 'wp_head_meta' ), 99 );
		add_action( 'wp_enqueue_scripts', array( $this, 'smartbanner_scripts' ), 10 );
		add_action( 'admin_enqueue_scripts', array( $this, 'smartbanner_admin_scripts' ), 10 );

		// Filters.
		add_filter( 'plugin_action_links_app-wp-smartbanner/app-wp-smartbanner.php', array( $this, 'add_plugin_page_settings_link' ), 10, 1 );
	}

	/**
	 * Add the plugin settings link on the plugins overview.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @param array $links The plugin links object.
	 * @return array $links
	 */
	public function add_plugin_page_settings_link( $links ) {
		$links[] = sprintf(
			'<a href="%s">%s</a>',
			admin_url( 'admin.php?page=app-wp-smartbanner-settings' ),
			_x( 'Configuration', 'backend', 'app-wp-smartbanner' )
		);
		return $links;
	}

	/**
	 * Load the public facing scripts and styles.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @return  void
	 */
	public function smartbanner_scripts() {
		if ( $this->has_setting( 'url' ) ) {
			wp_enqueue_style( 'smartbanner', $this->get_setting( 'url' ) . 'assets/css/smartbanner.min.css', array(), $this->get_setting( 'version' ) );
			wp_enqueue_script( 'smartbanner', $this->get_setting( 'url' ) . 'assets/js/smartbanner.min.js', array(), $this->get_setting( 'version' ), true );

			// Add custom postioning css.
			$custom_css = sprintf(
				'.smartbanner{%s: %s !important;}',
				esc_html( $this->get_option( 'widget_display_position' ) ),
				esc_html( $this->get_option( 'widget_display_position_offset' ) )
			);
			wp_add_inline_style( 'smartbanner', $custom_css );
		}
	}

	/**
	 * Load the admin facing script.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @param string $hook The hook contains the current request page.
	 * @return  void
	 */
	public function smartbanner_admin_scripts( $hook ) {
		if ( 'toplevel_page_smartbanner' === $hook && $this->has_setting( 'url' ) ) {
			wp_enqueue_script( 'smartbanner-admin', $this->get_setting( 'url' ) . 'assets/js/smartbanner-admin.min.js', array( 'jquery' ), $this->get_setting( 'version' ), true );
		}
	}

	/**
	 * Add the meta data to the head tag.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @return  void
	 */
	public function wp_head_meta() {

		$platforms = array();

		if ( $this->has_option( 'show_on_ios' ) ) {
			$platforms[] = 'ios';
			foreach ( $this->ios_meta as $key => $option ) {
				if ( $this->has_option( $option ) ) {
					printf( '<meta name="%s" content="%s">%s', esc_html( $key ), esc_html( $this->get_option( $option ) ), PHP_EOL );
				}
			}
		}

		if ( $this->has_option( 'show_on_android' ) ) {
			$platforms[] = 'android';
			foreach ( $this->android_meta as $key => $option ) {
				if ( $this->has_option( $option ) ) {
					printf( '<meta name="%s" content="%s">%s', esc_html( $key ), esc_html( $this->get_option( $option ) ), PHP_EOL );
				}
			}
		}

		if ( ! empty( $platforms ) ) {
			echo '<!-- Start SmartBanner configuration -->' . PHP_EOL;
			echo '<meta name="smartbanner:disable-positioning" content="true">' . PHP_EOL;
			foreach ( $this->general_meta as $key => $option ) {
				if ( $this->has_option( $option ) ) {
					printf( '<meta name="%s" content="%s">%s', esc_html( $key ), esc_html( $this->get_option( $option ) ), PHP_EOL );
				}
			}
			printf( '<meta name="smartbanner:enabled-platforms" content="%s">%s', implode( ',', array_map( 'esc_html', $platforms ) ), PHP_EOL );
			echo '<meta name="smartbanner:disable-positioning" content="true">';
			echo '<!-- End SmartBanner configuration -->' . PHP_EOL;
		}

	}

	/**
	 * Completes the setup process on "init" of earlier.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @return  void
	 */
	public function init() {

		// Bail early if called directly from functions.php or plugin file.
		if ( ! did_action( 'plugins_loaded' ) ) {
			return;
		}

		// Load textdomain file.
		$this->app_wp_smartbanner_load_textdomain();

		// Define options.
		$this->options = get_option( 'app_wp_smartbanner_options_fields' );

		// Set translations for default values.
		self::$defaults = array(
			'app_name'                       => _x( 'Title', 'widget', 'app-wp-smartbanner' ),
			'author_name'                    => _x( 'Author', 'widget', 'app-wp-smartbanner' ),
			'price'                          => _x( 'Price', 'widget', 'app-wp-smartbanner' ),
			'view_label'                     => _x( 'Open', 'widget', 'app-wp-smartbanner' ),
			'close_label'                    => _x( 'Close', 'widget', 'app-wp-smartbanner' ),
			'apple_app_store_tagline'        => _x( 'Tagline', 'widget', 'app-wp-smartbanner' ),
			'google_play_store_tagline'      => _x( 'Tagline', 'widget', 'app-wp-smartbanner' ),
			'widget_display_position'        => 'bottom',
			'widget_display_position_offset' => '0px',
		);

	}

	/**
	 * Loads the plugin's translated strings similar to load_plugin_textdomain().
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @param   string $domain The plugin's current text domain.
	 * @return  bool
	 */
	public function app_wp_smartbanner_load_textdomain( $domain = 'app-wp-smartbanner' ) {

		// Get locale.
		$locale = determine_locale();

		// Create .mo filename.
		$mofile = $domain . '-' . $locale . '.mo';

		// Try to load from the languages directory first.
		if ( load_textdomain( $domain, WP_LANG_DIR . '/plugins/' . $mofile ) ) {
			return true;
		}

		// Load from plugin lang folder.
		return load_textdomain( $domain, APP_WP_SMARTBANNER_PATH . 'languages/' . $mofile );
	}

	/**
	 * Defines a constant if doesnt already exist.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @param   string $name The constant name.
	 * @param   mixed  $value The constant value.
	 * @return  void
	 */
	public function define( $name, $value = true ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Returns true if a setting exists for this name.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @param   string $name The setting name.
	 * @return  boolean
	 */
	public function has_setting( $name ) {
		return isset( $this->settings[ $name ] );
	}

	/**
	 * Returns a setting or null if doesn't exist.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @param   string $name The setting name.
	 * @return  mixed
	 */
	public function get_setting( $name ) {
		return isset( $this->settings[ $name ] ) ? $this->settings[ $name ] : null;
	}

	/**
	 * Updates a setting for the given name and value.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @param   string $name The setting name.
	 * @param   mixed  $value The setting value.
	 * @return  true
	 */
	public function update_setting( $name, $value ) {
		$this->settings[ $name ] = $value;
		return true;
	}

	/**
	 * Returns true if an option exists for this name.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @param   string $name The option name.
	 * @return  boolean
	 */
	public function has_option( $name ) {
		return isset( $this->options[ $name ] );
	}

	/**
	 * Returns option or null if doesn't exist.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @param   string $name The option name.
	 * @return  mixed
	 */
	public function get_option( $name ) {
		$value = isset( $this->options[ $name ] ) ? $this->options[ $name ] : null;
		if ( empty( $value ) && isset( self::$defaults[ $name ] ) ) {
			return self::$defaults[ $name ];
		}
		return $value;
	}

	/**
	 * Sets option for the given name and value.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @param   string $name The option name.
	 * @param   mixed  $value The option value.
	 * @return  void
	 */
	public function set_option( $name, $value ) {
		$this->options[ $name ] = $value;
	}

	/**
	 * Returns an instance or null if doesn't exist.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @param   string $class The instance class name.
	 * @return  object
	 */
	public function get_instance( $class ) {
		$name = strtolower( $class );
		return isset( $this->instances[ $name ] ) ? $this->instances[ $name ] : null;
	}

	/**
	 * Creates and stores an instance of the given class.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @param   string $class The instance class name.
	 * @return  object
	 */
	public function new_instance( $class ) {
		$instance                 = new $class();
		$name                     = strtolower( $class );
		$this->instances[ $name ] = $instance;
		return $instance;
	}

}
