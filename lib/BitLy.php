<?php
/**
 * Static class used to shorten URL and get a BitLyHash object as handle
 * @author Erik Hedberg (erik@webbgaraget.se)
 */
require_once( 'BitLyHash.php' );
class BitLy
{
	/**
	 * Validate username and apiKey
	 *
	 * @param string $username 
	 * @param string $apikey 
	 * @return boolean
	 * @author Erik Hedberg
	 */
	static function validate($username, $apikey)
	{
		$params = array(
			'login' => $username,
			'apiKey' => $apikey,
			'x_login'  => $username,
			'x_apiKey' => $apikey
		);
		$valid = self::doApiCall('validate', $params);
		return ($valid['data']['valid'] == 1);
	}
	
	/**
	 * Shorten URL and get BitLyHash
	 *
	 * @param string $username 
	 * @param string $apikey 
	 * @param string $url 
	 * @param string $domain 
	 * @return BitLyHash
	 * @author Erik Hedberg
	 */
	static function shorten($username, $apikey, $url, $domain = 'bit.ly')
	{
		if (!self::validate($username, $apikey) || !in_array($domain, array('bit.ly', 'j.mp', 'bitly.com')))
		{
			return false;
		}
		
		$params = array(
			'login' => $username,
			'apiKey' => $apikey,
			'x_login' => $username,
			'x_apiKey' => $apikey,
			'longUrl' => urlencode($url),
			'domain' => $domain
		);
		
		$response = self::doApiCall('shorten', $params);
		return new BitLyHash($username, $apikey, $response['data']['url']);
	}
	
	/**
	 * undocumented function
	 *
	 * @param string $url 
	 * @return mixed
	 * @author Erik Hedberg (erik@webbgaraget.se)
	 */
	private static function curl_get_result($url)
	{
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	
	/**
	 * Do call to bit.ly API v3 and return response as JSON.
	 *
	 * @param string $action 
	 * @param string $params 
	 * @return array
	 * @author Erik Hedberg
	 */
	private static function doApiCall($action, $params)
	{
		$querystring = '';
		$separator = '?';
		foreach($params as $name => $value)
		{
			$querystring .= $separator . $name . '=' . $value;
			$separator = '&';
		}
		$querystring .= $separator . 'format=json';
		
		$url = 'http://api.bitly.com/v3/' . $action . $querystring;
		$response = self::curl_get_result($url);
		$response = json_decode($response, true);
		if ($response['status_txt'] != 'OK')
		{
			return false;
		}
		return $response;
	}

}