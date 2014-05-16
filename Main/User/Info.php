<?php
/**
 * Abstract Class of user info 
 * @package Main/User
 * @copyright Copyright (c) 2006-2014 creoLIFE
 * @author Mirek Ratman
 * @version 1.0
 * @since 2014-03-10
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
