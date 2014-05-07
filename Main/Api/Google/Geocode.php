<?php
/**
 * Helper for Google API Geocodes
 * @package Main_Api_Google
 * @author Mirek Ratman
 * @version 1.0
 * @since 2014-04-25
 * @license The MIT License (MIT)
 * @copyright 2014 creoLIFE.pl
	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
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
  public function __construct( $address = '', $language = 'en' ){
    $addressValidator = new Zend_Validate_Regex(array('pattern' => '/[a-zA-Z0-9\s\-\_\,]+/'));
    $languageValidator = new Zend_Validate_Regex(array('pattern' => '/[a-z]{,3}/'));

		if ( !$languageValidator->isValid( $language )) {
      $this->urlParams['language'] = $language;
    }

    if ($addressValidator->isValid( $address )) {
      $this->urlParams['address'] = $address;

      $query = $this->apiRestUrl . '?' . http_build_query($this->urlParams);
      $this->json = @file_get_contents( $query );
      $decoded = json_decode($this->json);

      if( isset($decoded->status) && $decoded->status !== 'ZERO_RESULTS' ){
        $this->result = $decoded->results[0];
      }
    }
  }

  /**
    * Method will get full response JSON object
    * @method getResultJson
    * @return float
    */
  public function getResultJson(){
    return $this->json;
  }

    /**
    * Method will get lattitude from response
    * @method getLattitude
    * @return float
    */
  public function getLattitude(){
   	return isset($this->result->geometry->location->lat) ? $this->result->geometry->location->lat : null;
  }

  /**
    * Method will get longitude from response
    * @method getLongitude
    * @return float
    */
  public function getLongitude(){
   	return isset($this->result->geometry->location->lng) ? $this->result->geometry->location->lng : null;
  }

  /**
    * Method will get street long name
    * @method getStreetLongName
    * @return string
    */
  public function getStreetLongName(){
  	foreach( $this->result->address_components as $a ){
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
    foreach( $this->result->address_components as $a ){
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
  	foreach( $this->result->address_components as $a ){
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
    foreach( $this->result->address_components as $a ){
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
    foreach( $this->result->address_components as $a ){
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
    foreach( $this->result->address_components as $a ){
    	if( $a->types[0] === 'locality' ){
    		return $a->short_name;
      }
    }
    return null;
  }

}
