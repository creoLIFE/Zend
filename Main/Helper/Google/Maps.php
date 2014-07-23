<?php
/**
 * Helper for parameters
 * @package Main_Helper_Google
 * @author Mirek Ratman
 * @version 1.0.0
 * @since 2014-07-22
 * @license The MIT License (MIT)
 * @copyright 2014 creoLIFE.pl
 * @see http://www.mullie.eu/geographic-searches/
  Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
  The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

class Main_Helper_Google_Maps
{
    /**
    * Method will calculate max and min longitude and lattitude from given point
    * @method getMinMaxNearby
    * @param [float] $lon - longitude of given location
    * @param [float] $lat - lattitude of given location
    * @param [string] $distance - max distance from given point
    * @return string
    */
    public function getMinMaxNearby( $lon, $lat, $distance = 5, $unit = 'km' ){
        if ($unit == 'km') $radius = 6371.009; // in kilometers
        elseif ($unit == 'mi') $radius = 3958.761; // in miles

        return array(
            // latitude boundaries
            'maxLat' => (float) $lat + rad2deg($distance / $radius),
            'minLat'=> (float) $lat - rad2deg($distance / $radius),
            // longitude boundaries (longitude gets smaller when latitude increases)
            'maxLng' => (float) $lon + rad2deg($distance / $radius / cos(deg2rad((float) $lat))),
            'minLng' => (float) $lon - rad2deg($distance / $radius / cos(deg2rad((float) $lat)))
        );
   	}

    /**
    * Method will calculate distance between 2 points
    * @method getDistance
    * @param [float] $lon1 - longitude of given main location
    * @param [float] $lat1 - lattitude of given main location
    * @param [float] $lon2 - longitude of given 2nd location
    * @param [float] $lat2 - lattitude of given 2nd location
    * @param [string] $distance - max distance from given point
    * @return string
    */
    public static function getDistance($lon1, $lat1, $lon2, $lat2, $unit = 'km')
    {
        if ($unit == 'km') $radius = 6371.009; // in kilometers
        elseif ($unit == 'mi') $radius = 3958.761; // in miles

        // convert degrees to radians
        $lat1 = deg2rad((float) $lat1);
        $lng1 = deg2rad((float) $lng1);
        $lat2 = deg2rad((float) $lat2);
        $lng2 = deg2rad((float) $lng2);

        // great circle distance formula
        return $radius * acos(sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($lng1 - $lng2));
    }
}
