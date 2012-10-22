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
}
?>