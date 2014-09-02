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
     * @param string $exception - exception object
     * @return string
     */
    static public function display( $exception ){
        $out = array();

        $out[] = 'Message: ' . empty($exception->exception->getMessage()) ? 'No message defined' : $exception->exception->getMessage();
        $out[] = '<hr>';
        $out[] = 'Module: ' . empty($exception->request->getModuleName()) ? 'No module defined' : $exception->request->getModuleName();
        $out[] = 'Controller: ' . empty($exception->request->getControllerName()) ? 'No controller defined' : $exception->request->getControllerName();
        $out[] = 'Action: ' . empty($exception->request->getActionName()) ? 'No action defined' : $exception->request->getActionName();
        $out[] = '<hr>';
        $out[] = 'Error object: ' . $exception;

		return implode('<br/>',$out);
	}
}
