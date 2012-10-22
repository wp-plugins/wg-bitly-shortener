<?php
/**
 * @author Erik Hedberg (erik@webbgaraget.se)
 */
class BitLyHash
{
	/**
	 * Constructor
	 *
	 * @param string $username 
	 * @param string $apikey 
	 * @param string $data 
	 * @author Erik Hedberg (erik@webbgaraget.se)
	 */
	function __construct($username, $apikey, $short_url)
	{
		$this->username = $username;
		$this->apikey = $apikey;
		$this->short_url = $short_url;

		$info = $this->getInfo();
		if ($info)
		{
			$this->hash = $info['user_hash'];
			$this->title = $info['title'];
			$this->created_by = $info['created_by'];			
		}
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
	private function doApiCall($action, $params)
	{
		$params = $params + array(
			'login' => $this->username,
			'apiKey' => $this->apikey,
			'x_login' => $this->username,
			'x_apiKey' => $this->apikey
		);
		
		$params['format'] = 'json';
		$querystring = '';
		$separator = '?';
		foreach($params as $name => $value)
		{
			$querystring .= $separator . $name . '=' . $value;
			$separator = '&';
		}
		
		$url = 'http://api.bitly.com/v3/' . $action . $querystring;
		$response = self::curl_get_result($url);
		$response = json_decode($response, true);
		if ($response['status_txt'] != 'OK')
		{
			return false;
		}
		return $response;
	}

	
	/**
	 * Get number of clicks
	 *
	 * @return mixed
	 * @author Erik Hedberg (erik@webbgaraget.se)
	 */
	public function getClicks()
	{
		$params = array(
			'shortUrl' => $this->short_url
		);
		$response = $this->doApiCall('clicks', $params);
		if ($response)
		{
			return array(
				'user' => $response['data']['clicks'][0]['user_clicks'],
				'global' => $response['data']['clicks'][0]['global_clicks']
			);	
		}
		else
		{
			return array('user' => -1, 'global' => -1);
		}
	}

	/**
	 * Get referrers
	 *
	 * @return mixed
	 * @author Erik Hedberg (erik@webbgaraget.se)
	 */
	public function getReferrers()
	{
		$params = array(
			'shortUrl' => $this->short_url
		);
		$response = $this->doApiCall('referrers', $params);
		if ($response)
		{
			return $response['data']['referrers'][0];
		}
		else
		{
			return array();
		}
	}
	
	/**
	 * Get country stats
	 *
	 * @return mixed
	 * @author Erik Hedberg (erik@webbgaraget.se)
	 */
	public function getCountries()
	{
		$params = array(
			'shortUrl' => $this->short_url
		);
		$response = $this->doApiCall('countries', $params);
		if ($response)
		{
			return $response['data']['countries'][0];
		}
		else
		{
			return array();
		}
	}

	/**
	 * Get number of clicks per minute
	 *
	 * @return mixed
	 * @author Erik Hedberg (erik@webbgaraget.se)
	 */
	public function getClicksByMinute()
	{
		$params = array(
			'shortUrl' => $this->short_url
		);
		$response = $this->doApiCall('clicks_by_minute', $params);
		if ($response)
		{
			return $response['data']['clicks_by_minute'][0]['clicks'];
		}
		else
		{
			return array();
		}
	}

	/**
	 * Get number of clicks by day
	 *
	 * @return mixed
	 * @author Erik Hedberg (erik@webbgaraget.se)
	 */
	public function getClicksByDay()
	{
		$params = array(
			'shortUrl' => $this->short_url
		);
		$response = $this->doApiCall('clicks_by_day', $param);
		if ($response)
		{
			return $response['data']['clicks_by_day'][0]['clicks'];
		}
		else
		{
			return array();
		}
	}

	/**
	 * Get info
	 *
	 * @return mixed
	 * @author Erik Hedberg (erik@webbgaraget.se)
	 */
	private function getInfo()
	{
		$params = array(
			'shortUrl' => $this->short_url
		);
		$response = $this->doApiCall('info', $params);
		if ($response)
		{
			return $response['data']['info']['0'];
		}
		else
		{
			return array();
		}
	}
}