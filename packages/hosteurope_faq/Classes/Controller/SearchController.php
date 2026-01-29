<?php
namespace HostEuropeGmbh\HosteuropeFaq\Controller;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use HostEuropeGmbh\HosteuropeFaq\Domain\Model\Question;
use HostEuropeGmbh\HosteuropeTemplate\Helper\Elasticsearch\Resources\Content;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * SearchController
 */
class SearchController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * categoryRepository
	 *
	 * @var \HostEuropeGmbh\HosteuropeFaq\Domain\Repository\CategoryRepository
	 * @inject
	 */
	protected $categoryRepository = null;

	/**
	 * questionRepository
	 *
	 * @var \HostEuropeGmbh\HosteuropeFaq\Domain\Repository\QuestionRepository
	 * @inject
	 */
	protected $questionRepository = null;


	protected function _search( $query, $s = null ) {
        $this->_displayCancelCookieHint();

		$s_language_uid = $GLOBALS['TSFE']->sys_language_uid;
		$pageid         = intval( $GLOBALS['TSFE']->id );
		$this->view->assign( 'pid', $pageid );

		$return_object = array();

		$expand_mode = 0;


		$search_result = \HostEuropeGmbh\HosteuropeTemplate\Helper\Elasticsearch\Resource::search(
			$query,
			$label = "",
			$categories = array(),
			$s_language_uid,
			$offset = 0,
			$limit = 100,
			$expand = 0,
			$sort_by = null,
			$sort = "asc",
			$highlight = true );

		$suggest_option = false;


		//Erweitern um *
		if ( $search_result['hits']['total']['value'] == 0 ) {
			$expand_mode ++;

			$search_result = \HostEuropeGmbh\HosteuropeTemplate\Helper\Elasticsearch\Resource::search(
				$query,
				$label = "",
				$categories = array(),
				$s_language_uid,
				$offset = 0,
				$limit = 100,
				$expand_mode
			);

			if ( isset( $search_result['suggest']['suggestion'][0] ) ) {
				$suggest_option = $search_result['suggest']['suggestion'][0]['options'][0]['text'];

			}
		}
		$this->view->assign( 'suggest_option', $suggest_option );

		$expand_mode = 0;
		$return_object['all'] = array(
			'name'    => 'Alle Ergebnisse',
			'results' => $search_result['hits']['hits'],
			'total'   => $search_result['hits']['total']['value'],

		);
		if(is_null($s) OR $s == "a" OR $s == ""){
			$return_object['all']['active'] = true;
		}else{
			$return_object['all']['active'] = false;
		}


		$search_result = \HostEuropeGmbh\HosteuropeTemplate\Helper\Elasticsearch\Resource::search(
			$query,
			'question',
			$categories = array(),
			$s_language_uid,
			$offset = 0,
			$limit = 100,
			$expand_mode );
			
		if ( $search_result['hits']['total']['value'] > 0 ) {
			$return_object['question'] = array(
				'name'    => 'FAQ',
				'results' => $search_result['hits']['hits'],
				'total'   => $search_result['hits']['total']['value'],
			);
			if($s == "q"){
				$return_object['question']['active'] = true;
			}else{
				$return_object['question']['active'] = false;
			}


		}

		$search_result = \HostEuropeGmbh\HosteuropeTemplate\Helper\Elasticsearch\Resource::search(
			$query,
			'products',
			$categories = array(),
			$s_language_uid,
			$offset = 0,
			$limit = 100,
			$expand_mode );
			
			
			
		if ( $search_result['hits']['total']['value'] > 0 ) {
			$return_object['products'] = array(
				'name'    => 'Produkte',
				'results' => $search_result['hits']['hits'],
				'total'   => $search_result['hits']['total']['value'],
			);
			if($s == "p"){
				$return_object['products']['active'] = true;
			}else{
				$return_object['products']['active'] = false;
			}
		}

		$search_result = \HostEuropeGmbh\HosteuropeTemplate\Helper\Elasticsearch\Resource::search(
			$query,
			'other',
			$categories = array(),
			$s_language_uid,
			$offset = 0,
			$limit = 100,
			$expand_mode );
		if ( $search_result['hits']['total']['value'] > 0 ) {
			$return_object['other'] = array(
				'name'    => 'Sonstiges',
				'results' => $search_result['hits']['hits'],
				'total'   => $search_result['hits']['total']['value'],
			);
			if($s == "o"){
				$return_object['other']['active'] = true;
			}else{
				$return_object['other']['active'] = false;
			}
		}

		return $return_object;
	}


	public function _parse(){
		$content = $this->content;
		$lines = explode("\n",$content);

		$redirects = array();
		$last_numbers = "";
		$last_uri = "";
		foreach($lines as $line){

			if(is_numeric(substr($line,0,1))){
				$last_numbers = "$line";
			}elseif(substr($line,0,1) == "h"){
				$last_uri = $line;
			}elseif(strlen($line) == 0){



				$last_numbers = str_replace("$","",$last_numbers);
				$numbers = explode("|",$last_numbers);

				foreach($numbers as $number){
					$redirects[] = "\"https://faq.hosteurope.de/index.php?cpid=".$number."\";\"".$last_uri."\"";
				}

				$last_numbers = "";
				$last_uri = "";

			}




		}

		foreach($redirects as $redirect){
			echo $redirect."\n";

		}

		die("ENDE");

	}

	public function _parse2(){
		$questions = $this->questionRepository->findAll();

		$lines = array();
		/**
		 * @var Question $question
		 */
		foreach ( $questions as $question ) {

			$line = array();
			$line['typo3_id'] = $question->getUid();
			$line['name'] = $question->getHeadline();
			$line['alias'] = str_replace("\n",",",$question->getAlias());
			$line['alias'] = str_replace("\r","",$line['alias']);
			$line['alias'] = str_replace("\t","",$line['alias']);
			$line['seotitle'] = $question->getSeotitle();
			$line['seodescription'] = $question->getSeodescription();
			$line['slug'] = $question->getSlug();
			$line['url'] = str_replace("https://he.dittmannmedia.com/Suche/faaqqss/router/Controller/","https://www.hosteurope.de/faq/",$this->uriBuilder->setCreateAbsoluteUri( true )->uriFor( 'router',
				array( 'slug' => $question->getLinkarguments() ),
				'Controller',
				'HosteuropeFaq',
				'Main' ));

			$lines[] = $line;
		}


		foreach($lines as $line){
			echo "\"".implode("\",\"",$line)."\"\n";
		}
		die();
	}


	public function suggestAction(){

		$query    = htmlspecialchars( $_GET['q'] );

		$s_language_uid = $GLOBALS['TSFE']->sys_language_uid;
		$pageid         = intval( $GLOBALS['TSFE']->id );


		$search_result = \HostEuropeGmbh\HosteuropeTemplate\Helper\Elasticsearch\Resource::search(
			$query,
			$label = "",
			$categories = array(),
			$s_language_uid,
			$offset = 0,
			$limit = 100,
			$expand_mode = 0,
			$sort_by = null,
			$sort = "asc",
			$highlight = true,
			$suggestion_size = 10
		);

		$suggest_option = array();

		if($search_result['suggest']['suggestion'][0]['options']) {
			foreach ( $search_result['suggest']['suggestion'][0]['options'] as $suggest ) {
				$suggest_option[] = $suggest['text'];
			}
		}

		echo json_encode($suggest_option);

		die("");
	}



	public function searchAction() {
		if ( isset( $_GET['index'] ) ) {
			$this->_index();
			die( "indexed" );
		}


		$q       = htmlspecialchars( $_GET['q'] );

		if(!$q AND isset($_GET['tx_indexedsearch_pi2']['search']['sword'])){
			$q       = htmlspecialchars( $_GET['tx_indexedsearch_pi2']['search']['sword'] );
		}
		if(!$q AND isset($_POST['tx_indexedsearch_pi2']['search']['sword'])){
			$q       = htmlspecialchars( $_POST['tx_indexedsearch_pi2']['search']['sword'] );
		}
		$s       = htmlspecialchars( $_GET['s'] );
		$results = null;

		if ( strlen( $q ) ) {
			$results = $this->_search( $q,$s );
		}		

		$this->view->assign( 'q', $q );
		$this->view->assign( 's', $s );
		$this->view->assign( 'results', $results );

	}

	public function indexAction() {
		$this->_index();
	}


	protected function _index() {
		\HostEuropeGmbh\HosteuropeTemplate\Helper\Elasticsearch\Resources\Question::initMapping( 'hosteurope.de' );
		\HostEuropeGmbh\HosteuropeTemplate\Helper\Elasticsearch\Resources\Question::deleteAll( 'hosteurope.de' );
				
		Content::indexAll( 'hosteurope.de' );
		

		$questions = $this->questionRepository->findAll();

		/**
		 * @var Question $quoestion
		 */
		foreach ( $questions as $question ) {

			$es_object          = $question->getIndex();
			$es_object['s_url'] = str_replace("faaqqss/router/Controller/","", '/faq/'.implode('/', $es_object['linkArguments']) );

			//@TODO ... entscheidung welcher index
			\HostEuropeGmbh\HosteuropeTemplate\Helper\Elasticsearch\Resources\Question::indexData( 'hosteurope.de',
				$question->getUid(),
				$es_object );

		}		
		
	}

    private function _displayCancelCookieHint(){
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        /**
         * Marketing cookie - show info to customer to activate support
         */
        $baseUrl = '../' . ExtensionManagementUtility::siteRelPath('hosteurope_template');

        $pageRenderer->addCssFile($baseUrl . 'Resources/Public/css/extra.css');

        if (isset($_COOKIE['OPTOUTMULTI']) && ! empty($_COOKIE['OPTOUTMULTI'])) {
            $cookieValue = $_COOKIE['OPTOUTMULTI'];
            if (strrpos($cookieValue, '%7') !== false) {
                $cookieValue = urldecode($cookieValue);
            }

            if ( ! empty($cookieValue) && strrpos($cookieValue, 'c3:1') !== false) {
                // show extra message
                $this->view->assign('showMarketingCookieInfo', true);
            }

        }
    }

}