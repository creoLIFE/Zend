<?php
/**
 * Class provide pagination functionality
 * @category Main
 * @package Main_Paginator
 * @copyright Copyright (c) 2006-2012 creoLIFE
 * @author Mirek Ratman
 * @version 1.1
  */

class Main_Paginator
{

	/**
	* Current element
	* @var [integer]
	*/
	public $current;

	/**
	* First element
	* @var [integer]
	*/
	public $first;

	/**
	* Last element
	* @var [integer]
	*/
	public $last;

	/**
	* Previous element
	* @var [integer]
	*/
	public $previous;

	/**
	* Next element
	* @var [integer]
	*/
	public $next;

	/**
	* Status indicator. False if no pages found of page outside defined range
	* @var [boolean]
	*/
	public $status = false;

	/**
	* List of pages
	* @var [array]
	*/
	public $pages;

	/**
	* count of all pages
	* @var [integer]
	*/
	public $countAll;

	/**
	* count of pages
	* @var [integer]
	*/
	public $countPages;

	/**
	* go to page functionality
	* @var [boolean]
	*/
	public $goToPage = false;

	/**
	* Zend_Router definition for paginator URLs
	* @var [string]
	*/
	public $zendRouterDefinition = false;

	/**
	* Class constructor
	* @param [integer] $limit - set numbers of elements per page
	* @param [integer] $page - set current page
	* @param [integer] $count - number of all items 
	* @param [integer] $maxItems - set maximum number of items that will be on pagination list 
	* @param [string] $zendRouterDefinition - definition of Zend_Router definition for paginator URLs
	* @return [mixed]
	*/
	public function __construct( $limit = 1, $page = 0, $count = 0, $maxItems = 9, $zendRouterDefinition = '' ){

		//Initialize validators
		$intValidator = new Zend_Validate_Int();

		//define when script should add break element
		$break = 2;

		if( $maxItems <= 4 ){
			$break = 1;
		}
		if( $maxItems <= 3 ){
			$maxItems = 3;
		}

		$page = (int) $page;
		$count = (int) $count;

		$this->zendRouterDefinition = $zendRouterDefinition;

		if( $intValidator->isValid($limit) && $intValidator->isValid($page) ){
			if( $count > 0 ){

				//Define first page
				$firstPage = 1;
				$this->first['type'] = 'link';
				$this->first['page'] = $firstPage;
				$this->first['title'] = '1';
				$this->first['current'] = $firstPage >= $page ? 1 : 0;

				//Define last page
				$lastPage = (int) round($count/$limit) > 0 ? ceil($count/$limit) : 1;
				$this->last['type'] = 'link';
				$this->last['page'] = $lastPage;
				$this->last['title'] = $lastPage;
				$this->last['current'] = $lastPage <= $page ? 1 : 0;

				//Define current page
				$page = $page < 1 ? 1 : ($page >= $lastPage ? $lastPage : $page);
				$this->current['type'] = 'link';
				$this->current['page'] = $page;
				$this->current['realPage'] = $page - 1;
				$this->current['title'] = $page;
				$this->current['current'] = 1;

				//Define previous page
				$prev = (int)( $page - 1 < 1 ? 1 : ($page >= $lastPage ? $lastPage - 1 : $page -1) );
				$this->previous['type'] = 'link';
				$this->previous['page'] = $prev;
				$this->previous['title'] = $prev;
				$this->previous['current'] = 0;

				//Define next page
				$next = (int) ($page + 1 > $lastPage ? $lastPage : $page + 1 );
				$this->next['type'] = 'link';
				$this->next['page'] = $next;
				$this->next['link'] = $next;
				$this->next['current'] = 0;

				$forFrom = $page - round( $maxItems / 2 ) + 1;
				$forTo = $page + round( $maxItems / 2 ) - 1;

				if( $forFrom < 1 ){
					$forTo = $forTo + abs($forFrom);
					$forFrom = 1;
				}

				if( $forTo > $lastPage){
					$forFrom = $forFrom - ($forTo - $lastPage);
					$forTo = $lastPage;
				}

				if( $count < $maxItems ){
					$forFrom = 1;
					$forTo = 1;
				}

				//Define pages list
				for( $i = $forFrom; $i <= $forTo; $i++ ){
					$list['type'] = 'link';
					$list['page'] = (int) $i;
					$list['title'] = $i;
					$list['current'] = $i == $page ? 1 : 0;
					$this->pages[] = $list;
				}

				//Define break page
				$pageBreak['type'] = 'break';
				$pageBreak['page'] = '';
				$pageBreak['title'] = '..';
				$pageBreak['current'] = 0;

				if( $page > round( $maxItems / 2 ) ){
					$ta = array_slice($this->pages, -1 * $limit + 3);
					unset($this->pages);
					$this->pages = array_merge( array($this->first), array($pageBreak), $ta );
				}

				if( $page < $lastPage - round( $maxItems / 2 ) ){
					$ta = array_slice($this->pages, 0, $limit - 3);
					unset($this->pages);
					$this->pages = array_merge( $ta, array($pageBreak), array($this->last) );
				}

				//Define count of all pages
				$this->countAll = $forTo;
				
				//Define count of displayed pages
				$this->countPages = count($this->pages);

				//Update status
				if( count($count) > 0 ){
					$this->status = 1;
				}
				if( $page > $lastPage ){
					$this->status = 0;
				}

			}
		}
		else{
			return false;
		}
	}
}
