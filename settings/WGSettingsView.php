<?php
/*
 * TODO: Funktioner för att registrera subvyer hos huvudmenyns vy
 */
class WGSettingsView
{
	function __construct()
	{
		add_action( 'admin_menu', array( &$this, 'add_main_admin_page' ) );
	}
	
	function add_main_admin_page()
	{
		add_menu_page( 'Webbgaraget', 'Webbgaraget', 'manage_options', 'wg-options', array( &$this, 'render_main_options_page' ) );
	}
	
	function render_main_options_page()
	{
		
	}
}