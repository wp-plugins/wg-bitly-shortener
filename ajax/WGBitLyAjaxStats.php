<?php

class WGBitLyAjaxStats
{
	function __construct()
	{
		add_action( 'wp_ajax_wg-bitly-stats', array( &$this, 'get_stats' ) );
	}
	
	
	function get_stats()
	{
		$response = array();
		if ( isset( $_POST['view'] ) )
		{
			$view = $_POST['view'];
			
			if ( $view == 'dashboard' )
			{
				if ( isset( $_POST['page'] ) )
				{
					$page = $_POST['page'];
				}
				else
				{
					$page = 1;
				}
				$params = array(
					'meta_key' => 'wg-bitly-hash',
					'order' => 'desc',
					'orderby' => 'date',
					'paged' => $page,
					'posts_per_page' => 10,
					'post_type' => 'post'
				);
				$query = new WP_Query( $params );

				$response = array();
				foreach ($query->posts as $post)
				{
					$hash = unserialize( get_post_meta( $post->ID, 'wg-bitly-hash', true) );
					if ( $hash )
					{
						$clicks = $hash->getClicks();
						$response['posts'][] = array(
							'id' => $post->ID,
							'date' => get_the_time( get_option( 'date_format' ), $post ),
							'title' => get_the_title( $post->ID ),
							'permalink' => get_permalink( $post ),
							'clicks' => $clicks['global']
						);
					}
				}
				
				if ( $query->max_num_pages <= $page )
				{
					$response['last'] = true;
				}
			}
			// Single post
			else
			{
				$post_id = $_POST['post'];
				$post = get_post( $post_id );
				if ( $post )
				{
					$hash = unserialize( get_post_meta( $post->ID, 'wg-bitly-hash', true) );
					if ( $hash )
					{
						$clicks = $hash->getClicks();
						$response[] = array(
							'id' => $post->ID,
							'title' => get_the_title( $post->ID ),
							'permalink' => get_permalink( $post ),
							'clicks' => $clicks['global']
						);
					}
				}
			}
		}
		header( 'Content-type: application/json' );
		echo json_encode( $response );
		exit();
	}
}

new WGBitLyAjaxStats();