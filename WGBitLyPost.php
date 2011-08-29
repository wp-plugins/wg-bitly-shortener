<?php

require_once( dirname( __FILE__ ) . '/lib/BitLy.php' );
require_once( dirname( __FILE__ ) . '/lib/WGBitLyHelper.php' );

class WGBitLyPost
{
	function __construct()
	{
		add_action( 'post_updated', array( &$this, 'generate_short_url' ) );
		add_action( 'add_meta_boxes', array( &$this, 'add_meta_box' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'load_js') );
	}
	
	function load_js()
	{
		wp_enqueue_script( 'jquery-template', WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)) . '/js/jquery.tmpl.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'wg-bitly-post', WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)) . '/js/post.js', array( 'jquery', 'jquery-template' ), '1.0', true );
	}
	
	function generate_short_url( $post_id )
	{
		$post = get_post( $post_id );
		
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		{
			return;
		}
		
		if ( !in_array( $post->post_status, array( 'publish', 'private', 'future' ) ) )
		{
			return;	
		}
		
		$hash = get_post_meta( $post->ID, 'wg-bitly-hash', true );
		if ( $hash )
		{
			return;
		}
		
		$login = get_option( 'wg-bitly-login');
		$apikey = get_option( 'wg-bitly-apikey' );
		$domain = WGBitlyHelper::get_domain();
		$valid = BitLy::validate( $login, $apikey, $domain );
		
		if ( $valid )
		{
			$hash = Bitly::shorten( $login, $apikey, get_permalink( $post->ID ), $domain );
			add_post_meta( $post->ID, 'wg-bitly-hash', serialize( $hash ) );
		}
	}
	
	function add_meta_box()
	{
		add_meta_box( 'wg-bitly', 'Webbgaraget bit.ly short URL', array( &$this, 'render_meta_box' ), 'post', 'normal', 'high' );
	}
	
	function render_meta_box( $post )
	{

		$valid = BitLy::validate( get_option( 'wg-bitly-login' ), get_option( 'wg-bitly-apikey' ) );
		if (!$valid)
		{
			echo "The username and API key don't match.";
			return;
		}
		
		if ( WGBitLyHelper::using_pro_domain() && !BitLy::validate( get_option( 'wg-bitly-login' ), get_option( 'wg-bitly-apikey' ), WGBitLyHelper::get_domain() ) )
		{
		    echo "Pro domain not valid.";
		    return;
		}
		
		if ( !in_array( $post->post_status, array( 'publish', 'private', 'future' ) ) )
		{
			echo "The shortened URL will be generated once the post is published.";
			return;
		}
		
		$hash = unserialize( get_post_meta( $post->ID, 'wg-bitly-hash', true) );

		if ( $hash->short_url )
		{
			?>
			<p>
				<b>Short URL:</b> <a href="<?php echo $hash->short_url; ?>"><?php echo $hash->short_url; ?></a>
			</p>
			<div class="stats" data-post="<?php echo $post->ID; ?>"></div>
			<?php
		}
		else
		{
			echo "No short URL generated";
		}
	}
}
new WGBitLyPost();