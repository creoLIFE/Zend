<?php
/**
 * Zend Error "beautifer"
 * @category Main
 * @package Main_Error
 * @copyright Copyright (c) 2006-2012 creoLIFE
 * @author Mirek Ratman
 * @version 1.0
  */

class Main_Error
{

    /**
     * Method will "beautify" exception info
     * @param string $e - exception object
     * @return string
     */
    static public function display( $e ){
        $out = new StdClass;

        $tmp[] = 'Message: ' . $e->exception->getMessage();
        $tmp[] = '<hr>';
        $tmp[] = 'Module: ' . $e->request->getModuleName();
        $tmp[] = 'Controller: ' . $e->request->getControllerName();
        $tmp[] = 'Action: ' . $e->request->getActionName();
        $tmp[] = 'File: ' . $e->exception->getFile();
        $tmp[] = 'Line: ' . $e->exception->getLine();
        $tmp[] = 'Code: ' . $e->exception->getCode();
        $tmp[] = '<hr>';
        $tmp[] = 'Trace:';
        $tmp[] = $e->exception->getTraceAsString();
        $tmp[] = '<hr>';

		$out->info = implode('<br/>',$tmp);
        $out->details = $e;

        return $out;
	}
}
