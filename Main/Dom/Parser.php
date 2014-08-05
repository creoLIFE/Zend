<?php
/**
 * Dom parser wrapper
 * @package Main_Dom
 * @author Mirek Ratman
 * @version 1.0.3
 * @since 2014-07-31
 * @license The MIT License (MIT)
 * @copyright 2014 creoLIFE.pl
  Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
  The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

class Main_Dom_Parser
{

    /**
    * @var $parserType - type of parser (supported: simple_html_dom, DOMXPath)
    */
    public $parser;

    /**
    * Class constructor
    * @method __construct
    */
    public function __construct( $parser = 'simple_html_dom') {
        try{
            switch( $parser ){
                case "simple_html_dom":
                    $this->parser = new Main_Dom_Simplehtmldom;
                break;
                case "domxpath":
                    $this->parser new Main_Dom_Domxpath;
                break;
            }
        }
        catch( Exception $e){
            print_r($e);
        }
    }
}

