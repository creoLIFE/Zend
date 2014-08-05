<?php
/**
 * Browser headers class
 * @package Main_Http
 * @author Mirek Ratman
 * @version 1.0.2
 * @since 2014-07-18
 * @license The MIT License (MIT)
 * @copyright 2014 creoLIFE.pl
  Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
  The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

defined("_APP_ACCESS") or die(header('HTTP/1.0 404 Not Found'));

class Main_Http_Headers extends Main_Http_Useragent
{
  /**
  * Get random header info for browser
  * @method getRandomHeaderAsArray
  * @return array
  */
  public function getRandomHeaderAsArray(){
            return array(
                  'User-Agent: ' . parent::getRandomUserAgent(),
                  'Accept-encoding: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                  'Accept-Language: en-gb,en;q=0.5',
                  'Accept-Encoding: gzip, deflate',
                  'Connection: keep-alive'
            );
  }

  /**
  * Get random header info for browser
  * @method getRandomHeaderAsString
  * @return string
  */
  public function getRandomHeaderAsString(){
        return join("\n", array(
            'User-Agent: ' . parent::getRandomUserAgent(),
            'Accept-encoding: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language: en-gb,en;q=0.5',
            'Accept-Encoding: gzip, deflate',
            'Connection: keep-alive'
        ));
  }

}
