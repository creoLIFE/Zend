<?php
/**
 * DOMXPath parser wrapper
 * @package Main_Dom
 * @author Mirek Ratman
 * @version 1.0.0
 * @since 2014-07-31
 * @license The MIT License (MIT)
 * @copyright 2014 creoLIFE.pl
  Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
  The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

class Main_Dom_Domxpath
{

    /**
    * Method will execute file_get_html() function from Simple_Html_Dom
    * @method getHtmlFromFile
    * @param string $fileName - name of file to read
    * @return mixed
    */
    static public function getHtmlFromFile( $fileName ) {
        $doc = new DOMDocument();
        $doc->loadHTMLFile($fileName);
        return new DOMXpath($doc);
    }

    /**
    * Method will execute str_get_html() function from Simple_Html_Dom
    * @method getHtmlFromString
    * @param string $string - string to read
    * @return mixed
    */
    static public function getHtmlFromString( $string ) {
        $doc = new DOMDocument();
        $doc->loadHTML($string);
        return new DOMXpath($doc);
    }
}

