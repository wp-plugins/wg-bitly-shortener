<?php
require_once( dirname( __FILE__ ) . '/lib/BitLy.php' );
class WGBitLyDashboard
{
	function __construct()
	{
        add_action( 'admin_init', array( $this, 'load_widget' ) );
	}
	
	function load_widget()
	{
		$show = get_option( 'wg-bitly-dashboard' );
		$valid = BitLy::validate( get_option( 'wg-bitly-login' ), get_option( 'wg-bitly-apikey' ) );
		if ( $show && $valid )
		{
			add_action( 'wp_dashboard_setup', array( &$this, 'add_widget' ) );
			add_action( 'admin_enqueue_scripts', array( &$this, 'load_js') );
		}
	}
	
	function load_js()
	{
		wp_enqueue_script( 'jquery-template', WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)) . '/js/jquery.tmpl.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'wg-bitly-dashboard', WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)) . '/js/dashboard.js', array( 'jquery', 'jquery-template' ), '1.0', true );
	}
	
	function add_widget()
	{
		wp_add_dashboard_widget( 'wg-bitly-dashboard', 'Webbgaraget bit.ly stats', array( &$this, 'render_dashboard_box' ) );
	}
	
	function render_dashboard_box()
	{
		?>
		<table class="widefat">
			<thead>
				<tr>
					<th>Date</th>
					<th>Post</th>
					<th>Clicks</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="3"><a href="#" class="more">Load more</a></td>
				</tr>
			<tbody>
			</tbody>
		</table>
		<?php
	}

}
new WGBitLyDashboard();