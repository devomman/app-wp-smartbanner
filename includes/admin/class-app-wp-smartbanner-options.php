<?php
/**
 * Description of what this module (or file) is doing.
 *
 * @package App_WP_Smartbanner
 */

/**
 * Class App_WP_Smartbanner
 */
class App_WP_Smartbanner_Options extends App_WP_Smartbanner {

	/**
	 * The smartbanner options.
	 *
	 * @var array
	 */
	private $smartbanner_options = array();

	/**
	 * Constructor for the App_WP_Smartbanner_Options class.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @return  void
	 */
	public function __construct() {
		$this->smartbanner_options = get_option( 'app_wp_smartbanner_options_fields' );
		add_action( 'admin_menu', array( $this, 'smartbanner_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'smartbanner_page_init' ) );
	}

	/**
	 * Function to add the plugin page.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @return  void
	 */
	public function smartbanner_add_plugin_page() {
		add_menu_page(
			_x( 'Smartbanner', 'backend', 'app-wp-smartbanner' ), // title.
			_x( 'Smartbanner', 'backend', 'app-wp-smartbanner' ), // menu_title.
			'manage_options', // capability.
			'app-wp-smartbanner-settings', // menu_slug.
			array( $this, 'smartbanner_create_admin_page' ), // function.
			'dashicons-smartphone', // icon_url.
			100 // position.
		);
	}

	/**
	 * Function to create the admin page.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @return  void
	 */
	public function smartbanner_create_admin_page() { ?>
		<div class="wrap">
			<h2><?php echo esc_html( _x( 'Smartbanner', 'backend', 'app-wp-smartbanner' ) ); ?></h2>
			<p><?php echo esc_html( _x( 'Customize the smartbanner settings.', 'backend', 'app-wp-smartbanner' ) ); ?></p>
			<?php settings_errors(); ?>
			<form method="post" action="options.php">
				<?php
					settings_fields( 'smartbanner_option_group' );
					do_settings_sections( 'smartbanner-admin' );
					submit_button( _x( 'Save', 'backend', 'app-wp-smartbanner' ) );
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Function to register and add settings fields.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @return  void
	 */
	public function smartbanner_page_init() {

		register_setting(
			'smartbanner_option_group',
			'app_wp_smartbanner_options_fields',
			array( $this, 'smartbanner_sanitize' )
		);

		add_settings_section(
			'smartbanner_setting_section',
			_x( 'Settings', 'backend', 'app-wp-smartbanner' ),
			array( $this, 'smartbanner_section_info' ),
			'smartbanner-admin'
		);

		add_settings_field(
			'app_name',
			_x( 'App name', 'backend', 'app-wp-smartbanner' ),
			array( $this, 'app_name_callback' ),
			'smartbanner-admin',
			'smartbanner_setting_section'
		);

		add_settings_field(
			'author_name',
			_x( 'Author name', 'backend', 'app-wp-smartbanner' ),
			array( $this, 'author_name_callback' ),
			'smartbanner-admin',
			'smartbanner_setting_section'
		);

		add_settings_field(
			'price',
			_x( 'Price label', 'backend', 'app-wp-smartbanner' ),
			array( $this, 'price_callback' ),
			'smartbanner-admin',
			'smartbanner_setting_section'
		);

		add_settings_field(
			'view_label',
			_x( 'View label', 'backend', 'app-wp-smartbanner' ),
			array( $this, 'view_label_callback' ),
			'smartbanner-admin',
			'smartbanner_setting_section'
		);

		add_settings_field(
			'close_label',
			_x( 'Close label', 'backend', 'app-wp-smartbanner' ),
			array( $this, 'close_label_callback' ),
			'smartbanner-admin',
			'smartbanner_setting_section'
		);

		add_settings_field(
			'show_on_ios',
			_x( 'Show on iOs', 'backend', 'app-wp-smartbanner' ),
			array( $this, 'show_on_ios_callback' ),
			'smartbanner-admin',
			'smartbanner_setting_section'
		);

		add_settings_field(
			'apple_app_store_url',
			sprintf( '%s *', _x( 'Apple App Store url', 'backend', 'app-wp-smartbanner' ) ),
			array( $this, 'apple_app_store_url_callback' ),
			'smartbanner-admin',
			'smartbanner_setting_section'
		);

		add_settings_field(
			'apple_app_store_icon_url',
			sprintf( '%s *', _x( 'Apple App Store Icon url', 'backend', 'app-wp-smartbanner' ) ),
			array( $this, 'apple_app_store_icon_url_callback' ),
			'smartbanner-admin',
			'smartbanner_setting_section'
		);

		add_settings_field(
			'apple_app_store_tagline',
			_x( 'Apple App Store tagline', 'backend', 'app-wp-smartbanner' ),
			array( $this, 'apple_app_store_tagline_callback' ),
			'smartbanner-admin',
			'smartbanner_setting_section'
		);

		add_settings_field(
			'show_on_android',
			_x( 'Show on Android', 'backend', 'app-wp-smartbanner' ),
			array( $this, 'show_on_android_callback' ),
			'smartbanner-admin',
			'smartbanner_setting_section'
		);

		add_settings_field(
			'google_play_store_url',
			sprintf( '%s *', _x( 'Google Play Store url', 'backend', 'app-wp-smartbanner' ) ),
			array( $this, 'google_play_store_url_callback' ),
			'smartbanner-admin',
			'smartbanner_setting_section'
		);

		add_settings_field(
			'google_play_store_icon_url',
			sprintf( '%s *', _x( 'Google Play Store Icon url', 'backend', 'app-wp-smartbanner' ) ),
			array( $this, 'google_play_store_icon_url_callback' ),
			'smartbanner-admin',
			'smartbanner_setting_section'
		);

		add_settings_field(
			'google_play_store_tagline',
			_x( 'Google Play Store tagline', 'backend', 'app-wp-smartbanner' ),
			array( $this, 'google_play_store_tagline_callback' ),
			'smartbanner-admin',
			'smartbanner_setting_section'
		);

		add_settings_field(
			'widget_display_position',
			_x( 'Widget display position', 'backend', 'app-wp-smartbanner' ),
			array( $this, 'widget_display_position_callback' ),
			'smartbanner-admin',
			'smartbanner_setting_section'
		);

		add_settings_field(
			'widget_display_position_offset',
			_x( 'Widget display position offset', 'backend', 'app-wp-smartbanner' ),
			array( $this, 'widget_display_position_offset_callback' ),
			'smartbanner-admin',
			'smartbanner_setting_section'
		);

	}

	/**
	 * Function sanitize input.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @param array $input The submitted values.
	 * @return array $sanitary_values The sanitized values.
	 */
	public function smartbanner_sanitize( $input ) {
		$sanitary_values = array();
		if ( isset( $input['app_name'] ) ) {
			$sanitary_values['app_name'] = esc_html( $input['app_name'] );
		}

		if ( isset( $input['author_name'] ) ) {
			$sanitary_values['author_name'] = esc_html( $input['author_name'] );
		}

		if ( isset( $input['price'] ) ) {
			$sanitary_values['price'] = esc_html( $input['price'] );
		}

		if ( isset( $input['view_label'] ) ) {
			$sanitary_values['view_label'] = esc_html( $input['view_label'] );
		}

		if ( isset( $input['close_label'] ) ) {
			$sanitary_values['close_label'] = esc_html( $input['close_label'] );
		}

		if ( isset( $input['show_on_ios'] ) ) {
			$sanitary_values['show_on_ios'] = $input['show_on_ios'];
		}

		if ( isset( $input['apple_app_store_url'] ) ) {
			$sanitary_values['apple_app_store_url'] = esc_html( $input['apple_app_store_url'] );
		}

		if ( isset( $input['apple_app_store_icon_url'] ) ) {
			$sanitary_values['apple_app_store_icon_url'] = esc_html( $input['apple_app_store_icon_url'] );
		}

		if ( isset( $input['apple_app_store_tagline'] ) ) {
			$sanitary_values['apple_app_store_tagline'] = esc_html( $input['apple_app_store_tagline'] );
		}

		if ( isset( $input['show_on_android'] ) ) {
			$sanitary_values['show_on_android'] = $input['show_on_android'];
		}

		if ( isset( $input['google_play_store_url'] ) ) {
			$sanitary_values['google_play_store_url'] = esc_html( $input['google_play_store_url'] );
		}

		if ( isset( $input['google_play_store_icon_url'] ) ) {
			$sanitary_values['google_play_store_icon_url'] = esc_html( $input['google_play_store_icon_url'] );
		}

		if ( isset( $input['google_play_store_tagline'] ) ) {
			$sanitary_values['google_play_store_tagline'] = esc_html( $input['google_play_store_tagline'] );
		}

		if ( isset( $input['widget_display_position'] ) ) {
			$sanitary_values['widget_display_position'] = esc_html( $input['widget_display_position'] );
		}

		if ( isset( $input['widget_display_position_offset'] ) ) {
			$sanitary_values['widget_display_position_offset'] = esc_html( $input['widget_display_position_offset'] );
		}

		return $sanitary_values;
	}

	/**
	 * Function section info.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @return string
	 */
	public function smartbanner_section_info() {
		return '';
	}

	/**
	 * Function app_name_callback.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @return void
	 */
	public function app_name_callback() {
		printf(
			'<input class="regular-text" type="text" name="app_wp_smartbanner_options_fields[app_name]" id="app_name" placeholder="%s" value="%s">',
			$this->get_default( 'app_name' ),
			! empty( $this->smartbanner_options['app_name'] ) ? esc_attr( $this->smartbanner_options['app_name'] ) : ''
		);
	}

	/**
	 * Function author_name_callback.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @return void
	 */
	public function author_name_callback() {
		printf(
			'<input class="regular-text" type="text" name="app_wp_smartbanner_options_fields[author_name]" id="author_name" placeholder="%s" value="%s">',
			$this->get_default( 'author_name' ),
			! empty( $this->smartbanner_options['author_name'] ) ? esc_attr( $this->smartbanner_options['author_name'] ) : ''
		);
	}

	/**
	 * Function price_callback.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @return void
	 */
	public function price_callback() {
		printf(
			'<input class="regular-text" type="text" name="app_wp_smartbanner_options_fields[price]" id="price" placeholder="%s" value="%s">',
			$this->get_default( 'price' ),
			! empty( $this->smartbanner_options['price'] ) ? esc_attr( $this->smartbanner_options['price'] ) : ''
		);
	}

	/**
	 * Function view_label_callback.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @return void
	 */
	public function view_label_callback() {
		printf(
			'<input class="regular-text" type="text" name="app_wp_smartbanner_options_fields[view_label]" id="view_label" placeholder="%s" value="%s">',
			$this->get_default( 'view_label' ),
			! empty( $this->smartbanner_options['view_label'] ) ? esc_attr( $this->smartbanner_options['view_label'] ) : ''
		);
	}

	/**
	 * Function close_label_callback.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @return void
	 */
	public function close_label_callback() {
		printf(
			'<input class="regular-text" type="text" name="app_wp_smartbanner_options_fields[close_label]" id="close_label" placeholder="%s" value="%s">',
			$this->get_default( 'close_label' ),
			! empty( $this->smartbanner_options['close_label'] ) ? esc_attr( $this->smartbanner_options['close_label'] ) : ''
		);
	}

	/**
	 * Function show_on_ios_callback.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @return void
	 */
	public function show_on_ios_callback() {
		printf(
			'<input type="checkbox" name="app_wp_smartbanner_options_fields[show_on_ios]" id="show_on_ios" value="show_on_ios" %s> <label for="show_on_ios">Show the smartbanner on iOS devices?</label>',
			( ! empty( $this->smartbanner_options['show_on_ios'] ) && 'show_on_ios' === $this->smartbanner_options['show_on_ios'] ) ? 'checked' : ''
		);
	}

	/**
	 * Function apple_app_store_url_callback.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @return void
	 */
	public function apple_app_store_url_callback() {
		printf(
			'<input class="large-text" type="url" name="app_wp_smartbanner_options_fields[apple_app_store_url]" id="apple_app_store_url" placeholder="%s" value="%s" required>',
			$this->get_default( 'apple_app_store_url' ),
			! empty( $this->smartbanner_options['apple_app_store_url'] ) ? esc_attr( $this->smartbanner_options['apple_app_store_url'] ) : ''
		);
	}

	/**
	 * Function apple_app_store_icon_url_callback.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @return void
	 */
	public function apple_app_store_icon_url_callback() {
		printf(
			'<input class="large-text" type="url" name="app_wp_smartbanner_options_fields[apple_app_store_icon_url]" id="apple_app_store_icon_url" placeholder="%s" value="%s" required>',
			$this->get_default( 'apple_app_store_icon_url' ),
			! empty( $this->smartbanner_options['apple_app_store_icon_url'] ) ? esc_attr( $this->smartbanner_options['apple_app_store_icon_url'] ) : ''
		);
	}

	/**
	 * Function apple_app_store_tagline_callback.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @return void
	 */
	public function apple_app_store_tagline_callback() {
		printf(
			'<input class="regular-text" type="text" name="app_wp_smartbanner_options_fields[apple_app_store_tagline]" id="apple_app_store_tagline" placeholder="%s" value="%s">',
			$this->get_default( 'apple_app_store_tagline' ),
			! empty( $this->smartbanner_options['apple_app_store_tagline'] ) ? esc_attr( $this->smartbanner_options['apple_app_store_tagline'] ) : ''
		);
	}

	/**
	 * Function show_on_android_callback.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @return void
	 */
	public function show_on_android_callback() {
		printf(
			'<input type="checkbox" name="app_wp_smartbanner_options_fields[show_on_android]" id="show_on_android" value="show_on_android" %s> <label for="show_on_android">Show the smartbanner on Android devices?</label>',
			( ! empty( $this->smartbanner_options['show_on_android'] ) && 'show_on_android' === $this->smartbanner_options['show_on_android'] ) ? 'checked' : ''
		);
	}

	/**
	 * Function google_play_store_url_callback.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @return void
	 */
	public function google_play_store_url_callback() {
		printf(
			'<input class="large-text" type="url" name="app_wp_smartbanner_options_fields[google_play_store_url]" id="google_play_store_url" placeholder="%s" value="%s" required>',
			$this->get_default( 'google_play_store_url' ),
			! empty( $this->smartbanner_options['google_play_store_url'] ) ? esc_attr( $this->smartbanner_options['google_play_store_url'] ) : ''
		);
	}

	/**
	 * Function google_play_store_icon_url_callback.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @return void
	 */
	public function google_play_store_icon_url_callback() {
		printf(
			'<input class="large-text" type="url" name="app_wp_smartbanner_options_fields[google_play_store_icon_url]" id="google_play_store_icon_url" placeholder="%s" value="%s" required>',
			$this->get_default( 'google_play_store_icon_url' ),
			! empty( $this->smartbanner_options['google_play_store_icon_url'] ) ? esc_attr( $this->smartbanner_options['google_play_store_icon_url'] ) : ''
		);
	}

	/**
	 * Function google_play_store_tagline_callback.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @return void
	 */
	public function google_play_store_tagline_callback() {
		printf(
			'<input class="regular-text" type="text" name="app_wp_smartbanner_options_fields[google_play_store_tagline]" id="google_play_store_tagline" placeholder="%s" value="%s">',
			$this->get_default( 'google_play_store_tagline' ),
			! empty( $this->smartbanner_options['google_play_store_tagline'] ) ? esc_attr( $this->smartbanner_options['google_play_store_tagline'] ) : ''
		);
	}

	/**
	 * Function widget_display_position_callback.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @return void
	 */
	public function widget_display_position_callback() {
		$value = isset( $this->smartbanner_options['widget_display_position'] ) ? $this->smartbanner_options['widget_display_position'] : $this->get_default( 'widget_display_position' );
		printf(
			'<select name="app_wp_smartbanner_options_fields[widget_display_position]" id="widget_display_position">
                <option value="top" %s>%s</option>
                <option value="bottom" %s>%s</option>
            </select>',
			selected( $value, 'top', false ),
			esc_html( _x( 'Top', 'backend', 'app-wp-smartbanner' ) ),
			selected( $value, 'bottom', false ),
			esc_html( _x( 'Bottom', 'backend', 'app-wp-smartbanner' ) )
		);
	}

	/**
	 * Function widget_display_position_offset.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @return void
	 */
	public function widget_display_position_offset_callback() {
		printf(
			'<input class="small-text" type="text" name="app_wp_smartbanner_options_fields[widget_display_position_offset]" id="widget_display_position_offset" placeholder="%s" value="%s">',
			$this->get_default( 'widget_display_position_offset' ),
			! empty( $this->smartbanner_options['widget_display_position_offset'] ) ? esc_attr( $this->smartbanner_options['widget_display_position_offset'] ) : ''
		);
	}

	/**
	 * Function get_default.
	 *
	 * @date    08/12/2023
	 * @since   1.0
	 *
	 * @param string $key The key to look for.
	 * @return string
	 */
	public function get_default( $key ) {
		if ( ! empty( parent::$defaults[ $key ] ) ) {
			return parent::$defaults[ $key ];
		}
		return '';
	}

}
