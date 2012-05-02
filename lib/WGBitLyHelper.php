<?php

class WGBitlyHelper
{
    /**
     * Get domain used for shortening, regardless of PRO or not.
     *
     * @return void
     * @author Erik Hedberg (erik@webbgaraget.se)
     */
    static function get_domain()
    {
        $domain = get_option( 'wg-bitly-domain' );
        $pro_domain = get_option( 'wg-bitly-pro-domain' );
        
        return $domain == -1 ? $pro_domain : $domain;
	}
  
	/**
	 * Check if we're using a pro domain.
	 *
	 * @return void
	 * @author Erik Hedberg (erik@webbgaraget.se)
	 */
	static function using_pro_domain()
	{
	    return ( get_option( 'wg-bitly-domain' ) == -1 );
	}

	/**
	 * Get the shortened link
	 *
	 * @param string $post_id 
	 * @return void
	 * @author Erik Hedberg (erik@webbgaraget.se)
	 */
	static function get_short_link( $post_id )
	{
		if ( !$post_id )
		{
			global $post;
			$post_id = $post->ID;
		}
		$hash = unserialize( get_post_meta( $post_id, 'wg-bitly-hash', true) );
		if ( $hash->short_url )
		{
			return $hash->short_url;
		}
		return null;
	}
	
	/**
	 * Outputs a link with the shortened URL
	 *
	 * @param string $post_id 
	 * @return void
	 * @author Erik Hedberg (erik@webbgaraget.se)
	 */
	static function the_short_link( $post_id = null )
	{
		if ( !$post_id )
		{
			global $post;
			$post_id = $post->ID;
		}
		$short = self::get_short_link( $post_id );
		?>
		<?php if ( $short ): ?>
			<a href="<?php echo $short; ?>"><?php echo $short ?></a>
		<?php endif; ?>
		<?php
	}
}