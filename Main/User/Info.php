<?php
/**
 * User basic info class
 * @package Main_User
 * @author Mirek Ratman
 * @version 1.0.1
 * @since 2014-05-02
 * @license The MIT License (MIT)
 * @copyright 2014 creoLIFE.pl
  Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
  The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

class Main_User_Info
{
	/**
	* Get user IP
	* @method getIp
	* @return string
	*/
	public static function getIp(){
		
		$remoteAddr = $_SERVER["REMOTE_ADDR"];

		if( !empty($_SERVER["HTTP_CLIENT_IP"]) )
		{
			//check for ip from share internet
			$remoteAddr = $_SERVER["HTTP_CLIENT_IP"];
		}
		elseif( !empty($_SERVER["HTTP_X_FORWARDED_FOR"]) )
		{
			// Check for the Proxy User
			$remoteAddr = $_SERVER["HTTP_X_FORWARDED_FOR"];
		}
		
		return $remoteAddr;
	}


	/**
	* Get user hostname information from IP
	* @method getHostname
	* @param string $hostname - user hostname to decode
	* @return string
	*/
	public static function getHostname( $hostname = false ){
		if( $hostname ){
			return gethostbyname($hostname);
		}
		return self::getIp() == '127.0.0.1' ? 'localhost' : gethostbyaddr( self::getIp() );
	}

}
