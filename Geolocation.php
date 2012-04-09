<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @package		Simple Yahoo Placefinder Geolocation Class for CodeIgniter
 * @author		Jassen Michaels
 * @copyright	Yahoo Developer Network (YDN)
 * @license
 * @link		http://developer.yahoo.com/geo/placefinder/
 */

// ------------------------------------------------------------------------

class Geolocate
{
	/*
	 * Yahoo Placefinder API - Geolocation
	*
	* Sample URL - Get Latitude and Longitude by address
	* http://where.yahooapis.com/geocode?q=1600+Pennsylvania+Avenue,+Washington,+DC&appid=[yourappidhere]
	*
	* Sample URL - Get nearest address by point
	* http://where.yahooapis.com/geocode?q=38.898717,+-77.035974&gflags=R&appid=[yourappidhere]
	*
	* Get a Yahoo Application ID here:
	* http://developer.yahoo.com/dashboard/createKey.html
	*/
	 
	private $_appid = '';   // Your Yahoo Application ID - Not requrired to run API as long as < 50,000 calls per day
	private $_baseURL = 'http://where.yahooapis.com/geocode?q=';
	 
	private $_latitude = '';   // Response Latitude
	private $_longitude = '';   // Response Longitude
	private $_quality = '';   // Response percentage of accuracy
	private $_zip = '';   // Response Zip / Postal Code
	private $_county = '';   // Response County
	private $_city = '';   // Response City
	private $_state = '';   // Response State
	 
	private $_data = '';   // URL data string to be sent in API request
	private $_response = '';   // Response from Yahoo
	private $_XMLObj = '';   // Response broken up into XML Object
	 

	/*
	 |
	|   METHOD TO RETREIVE GEOLOCATION DATA FROM ADDRESS
	|
	*/

	function Location_by_address($address,$city,$state)
	{

		/*
		 *   BEGIN BUILDING OF URL TO BE SENT TO YAHOO - SEE ABOVE FOR REQUIRED FORMAT
		*/
		 
		/*  BREAK APART ADDRESS AND FORMAT FOR URL  */
		$wordChunks = explode(" ", $address);
		$num = count($wordChunks);
		 
		$i = 0; $this->_data = $this->_baseURL;   //Set base URL string

		while($i < $num)
		{
			$this->_data = $this->_data . $wordChunks[$i]. '+';
			$i = $i + 1;
		}
		 
		/*   BREAK APART CITY AND FORMAT FOR URL   */
		$this->_data = $this->_data . ",+";
		$wordChunks = explode(" ", $city);
		 
		for($i = 0; $i < count($wordChunks); $i++)
		{
			$this->_data = $this->_data . $wordChunks[$i]."+";
		}
		 
		/*   ADD STATE TO URL   */
		$this->_data = $this->_data . ",+";
		$this->_data = $this->_data  . $state;
		 
		/* INITIALIZE CURL SESSION FOR API CALL */
		$session = curl_init($this->_data);
		curl_setopt($session, CURLOPT_HEADER, false);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
		$this->_response = curl_exec($session);
		curl_close($session);

		/* USE SIMPLEXML TO PARSE RESPONSE STRING */
		$this->_XMLObj = simplexml_load_string($this->_response);
		$this->_XMLObj = array($this->_XMLObj);

		/* SET RETURNED VALUES FROM API CALL */
		$this->_latitude = $this->_XMLObj[0]->Result[0]->latitude[0];
		$this->_longitude = $this->_XMLObj[0]->Result[0]->longitude[0];
		$this->_quality = $this->_XMLObj[0]->Result[0]->quality[0];
		$this->_zip = $this->_XMLObj[0]->Result[0]->uzip[0];
		$this->_county = $this->_XMLObj[0]->Result[0]->county[0];
		$this->_city = $this->_XMLObj[0]->Result[0]->city[0];
		$this->_state = $this->_XMLObj[0]->Result[0]->state[0];

	}


	/*
	 |
	|   GETTER METHODS
	|
	*/

	public function getLatitude()
	{
		return $this->_latitude;
	}

	public function getLongitude()
	{
		return $this->_longitude;
	}

	public function getQuality()
	{
		return $this->_quality;
	}

	public function getZip()
	{
		return $this->_zip;
	}

	public function getCounty()
	{
		return $this->_county;
	}

	public function getCity()
	{
		return $this->_city;
	}

	public function getState()
	{
		return $this->_state;
	}

}
/* End of file Geolocate.php */
/* Location: ./system/application/libraries/Geolocate.php */
