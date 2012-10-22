<?php
require_once( 'WGSettingsView.php' );
require_once( dirname( __FILE__ ) . '/../lib/BitLy.php' );
require_once( dirname( __FILE__ ) . '/../lib/WGBitLyHelper.php' );
class WGBitLySettingsView extends WGSettingsView
{
	function __construct()
	{
		// parent::__construct();
		add_action( 'admin_menu', array( &$this, 'add_admin_page' ) );
		add_action( 'admin_init', array( &$this, 'setup_settings' ) );
	}
	
	function add_admin_page()
	{
		// add_menu_page( 'Webbgaratet bit.ly shortener', 'bit.ly shortener', 'manage_options', 'wg-bitly', array( &$this, 'render_options_page' ) );		
		add_submenu_page( 'tools.php', 'Webbgaraget bit.ly shortener', 'WG bit.ly', 'manage_options', 'wg-bitly', array( &$this, 'render_options_page' ) );
	}
	
	function setup_settings()
	{
		// Bit.ly username
		register_setting( 'wg-bitly-options', 'wg-bitly-login');
		// API key
		register_setting( 'wg-bitly-options', 'wg-bitly-apikey');
		// Preferred domain
		register_setting( 'wg-bitly-options', 'wg-bitly-domain');
		// Pro domain
		register_setting( 'wg-bitly-options', 'wg-bitly-pro-domain');
		// Show in dashboard
		register_setting( 'wg-bitly-options', 'wg-bitly-dashboard');
		add_settings_section( 'wg-bitly-section', 'Settings', array( &$this, 'render_options_section' ), 'wg-bitly' );
		add_settings_field( 'wg-bitly-login', 'Username', array( &$this, 'render_input_login' ), 'wg-bitly', 'wg-bitly-section');
		add_settings_field( 'wg-bitly-apikey', 'API Key', array( &$this, 'render_input_apikey' ), 'wg-bitly', 'wg-bitly-section');
		add_settings_field( 'wg-bitly-domain', 'Domain', array( &$this, 'render_input_domain' ), 'wg-bitly', 'wg-bitly-section');
		add_settings_field( 'wg-bitly-dashboard', 'Show statistics in dashboard', array( &$this, 'render_checkbox_dashboard' ), 'wg-bitly', 'wg-bitly-section');
	}
	
	function render_options_page()
	{
	?>
		<h2>Webbgaraget bit.ly shortener</h2>
		<form action="options.php" method="post">
		<?php settings_fields( 'wg-bitly-options' ); ?>
		<?php do_settings_sections( 'wg-bitly' ); ?>
		<input name="Submit" type="submit" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
		</form></div>

	<?php
	}
	
	function render_options_section()
	{
	?>
		Enter your <a href="http://bit.ly">bit.ly</a> user credentials to allow the plugin to shorten URLs. The credentials can be found at the <a href="https://bitly.com/a/your_api_key/">API page</a>.
	<?php
		$login = get_option( 'wg-bitly-login');
		$apikey = get_option( 'wg-bitly-apikey' );
		$domain = get_option( 'wg-bitly-domain' );
		$pro_domain = get_option( 'wg-bitly-pro-domain' );
		$valid_credentials = BitLy::validate( $login, $apikey );
		$valid_pro_domain = BitLy::validate( $login, $apikey, $pro_domain );
	?>
		<?php if ( !$valid_credentials ) : ?>
			<div class="error">The username and API key don't match.</div>
		<?php else: ?>
		    <?php if ( WGBitLyHelper::using_pro_domain() && !$valid_pro_domain ) : ?>
			    <div class="error">Chosen domain is not a valid pro domain.</div>
		    <?php endif; ?>
		<?php endif; ?>
	<?php
	}
	
	function render_input_login()
	{
		$login = get_option( 'wg-bitly-login' );
		?>
		<input id="wg-bitly-login" name="wg-bitly-login" size="40" type="text" value="<?php echo $login; ?>" />
		<?php
	}
	
	function render_input_apikey()
	{
		$key = get_option( 'wg-bitly-apikey' );
		?>
		<input id="wg-bitly-apikey" name="wg-bitly-apikey" size="40" type="text" value="<?php echo $key; ?>" />
		<?php
	}
	
	function render_input_domain()
	{
		$set_domain = get_option( 'wg-bitly-domain' );
		$pro_domain = get_option( 'wg-bitly-pro-domain' );
		$domains = array( 'bit.ly', 'j.mp', 'bitly.com' );
		?>
		<select id="wg-bitly-domain" name="wg-bitly-domain">
		<?php foreach ( $domains as $domain): ?>
		    <?php if ( $set_domain == $domain ): ?>
		        <?php $selected = ' selected'; ?>
		    <?php else: ?>
		        <?php $selected = ''; ?>
		    <?php endif; ?>
			<?php $selected = ( $set_domain == $domain ) ? ' selected' : ''; ?>
			<option value="<?php echo $domain; ?>"<?php echo $selected; ?>><?php echo $domain; ?></option>
		<?php endforeach; ?>
		    <option value="-1"<?php echo ( $set_domain == -1 ? ' selected' : '' ); ?>>Pro domain</option>
		</select>
        <input type="text" placeholder="Pro domain" id="wg-bitly-pro-domain" name="wg-bitly-pro-domain" value="<?php echo ( $set_domain == -1 ? $pro_domain : ''); ?>" />
	<?php
	}
	
	function render_checkbox_dashboard()
	{
		$dashboard = get_option( 'wg-bitly-dashboard' );
		?>
		<input type="checkbox" name="wg-bitly-dashboard" id="wg-bitly-dashboard"<?php echo ($dashboard) ? 'checked' : ''; ?>/>
		<?php
	}
}
new WGBitLySettingsView();