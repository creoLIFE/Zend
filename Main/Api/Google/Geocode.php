<?php
/**
 * Helper for Google API Geocodes
 * @package Main_Api_Google
 * @copyright Copyright (c) 2006-2014 creoLIFE
 * @author Mirek Ratman
 * @version 1.0
 * #since 2014-04-25
 */
defined("_APP_ACCESS") or die(header("HTTP/1.0 404 Not Found"));


class Main_Api_Google_Geocode
{
    /**
	 * @var $apiRestUrl
	 */
	private $apiRestUrl = 'http://maps.google.com/maps/api/geocode/json';

    /**
	 * @var $urlParams
	 */
	private $urlParams = array(
								'sensor' 	=> 'false',
								'address'	=> ''
							);

    /**
	 * @var $json
	 */
	private $json = null;

    /**
	 * @var $result
	 */
	private $result = null;


    /**
    * Class constructor
    * @method __construct
    * @param string $address - address to check
    * @param string $language - language of responded data
    */
    public function __construct( $address = '', $language = 'en' )
    {
    	$addressValidator = new Zend_Validate_Regex(array('pattern' => '/[a-zA-Z0-9\s\-\_\,]+/'));
    	$languageValidator = new Zend_Validate_Regex(array('pattern' => '/[a-z]{,3}/'));

		if ( !$languageValidator->isValid( $language )) {
			$this->urlParams['language'] = $language;
		}

        if ($addressValidator->isValid( $address )) {
	    	$this->urlParams['address'] = $address;
	    	
			$query = $this->apiRestUrl . '?' . http_build_query($this->urlParams);
			//debug($query);

	    	$this->json = @file_get_contents( $query );
	    	$this->result = json_decode($this->json);
	    	debug($this->result);
		}
    }

    /**
    * Method will get lattitude from response
    * @method getLattitude
    * @return float
    */
    public function getLattitude(){
    	//Define
    	$result = $this->result->results[0];

    	return isset($result->geometry->location->lat) ? $result->geometry->location->lat : null;
	}

    /**
    * Method will get longitude from response
    * @method getLongitude
    * @return float
    */
    public function getLongitude(){
    	//Define
    	$result = $this->result->results[0];

    	return isset($result->geometry->location->lng) ? $result->geometry->location->lng : null;
	}

    /**
    * Method will get street long name
    * @method getStreetLongName
    * @return string
    */
    public function getStreetLongName(){
    	//Define
    	$result = $this->result->results[0];

    	foreach( $result->address_components as $a ){
    		if( $a->types[0] === 'route' ){
    			return $a->long_name;
    		}
    	}

    	return null;
	}

    /**
    * Method will get street short name
    * @method getStreetShortName
    * @return string
    */
    public function getStreetShortName(){
    	//Define
    	$result = $this->result->results[0];

    	foreach( $result->address_components as $a ){
    		if( $a->types[0] === 'route' ){
    			return $a->short_name;
    		}
    	}
    	
    	return null;
	}

    /**
    * Method will get streen short name
    * @method getSublocalityLongName
    * @return string
    */
    public function getSublocalityLongName(){
    	//Define
    	$result = $this->result->results[0];

    	foreach( $result->address_components as $a ){
    		if( $a->types[0] === 'sublocality' ){
    			return $a->long_name;
    		}
    	}
    	
    	return null;
	}

    /**
    * Method will get streen short name
    * @method getSublocalityShortName
    * @return string
    */
    public function getSublocalityShortName(){
    	//Define
    	$result = $this->result->results[0];

    	foreach( $result->address_components as $a ){
    		if( $a->types[0] === 'sublocality' ){
    			return $a->short_name;
    		}
    	}
    	
    	return null;
	}

    /**
    * Method will get streen short name
    * @method getLocalityLongName
    * @return string
    */
    public function getLocalityLongName(){
    	//Define
    	$result = $this->result->results[0];

    	foreach( $result->address_components as $a ){
    		if( $a->types[0] === 'locality' ){
    			return $a->long_name;
    		}
    	}
    	
    	return null;
	}

    /**
    * Method will get streen short name
    * @method getLocalityShortName
    * @return string
    */
    public function getLocalityShortName(){
    	//Define
    	$result = $this->result->results[0];

    	foreach( $result->address_components as $a ){
    		if( $a->types[0] === 'locality' ){
    			return $a->short_name;
    		}
    	}
    	
    	return null;
	}

}
