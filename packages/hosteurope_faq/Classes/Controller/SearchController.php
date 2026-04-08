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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * SearchController
 */
class SearchController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * categoryRepository
     *
     * @var \HostEuropeGmbh\HosteuropeFaq\Domain\Repository\CategoryRepository
     */
    protected $categoryRepository = null;

    /**
     * questionRepository
     *
     * @var \HostEuropeGmbh\HosteuropeFaq\Domain\Repository\QuestionRepository
     */
    protected $questionRepository = null;

    public function __construct()
    {
        $this->categoryRepository = GeneralUtility::makeInstance(\HostEuropeGmbh\HosteuropeFaq\Domain\Repository\CategoryRepository::class);
        $this->questionRepository = GeneralUtility::makeInstance(\HostEuropeGmbh\HosteuropeFaq\Domain\Repository\QuestionRepository::class);
    }


    protected function _search($query, $s = null)
    {
        $this->_displayCancelCookieHint();

        $s_language_uid = 0;
        $pageid = intval($GLOBALS['TSFE']->id);
        $this->view->assign('pid', $pageid);

        $return_object = array();

        $expand_mode = 0;


//        $search_result = \HostEuropeGmbh\HosteuropeTemplate\Helper\Elasticsearch\Resource::search(
//            $query,
//            $label = "",
//            $categories = array(),
//            $s_language_uid,
//            $offset = 0,
//            $limit = 100,
//            $expand = 0,
//            $sort_by = null,
//            $sort = "asc",
//            $highlight = true);

        $suggest_option = false;

//
//        //Erweitern um *
//        if ($search_result['hits']['total']['value'] == 0) {
//            $expand_mode++;
//
//            $search_result = \HostEuropeGmbh\HosteuropeTemplate\Helper\Elasticsearch\Resource::search(
//                $query,
//                $label = "",
//                $categories = array(),
//                $s_language_uid,
//                $offset = 0,
//                $limit = 100,
//                $expand_mode
//            );
//
//            if (!empty($search_result['suggest']['suggestion'][0]['options'])) {
//
//                $suggest_option = $search_result['suggest']['suggestion'][0]['options'][0]['text'];
//
//            }
//        }
        $this->view->assign('suggest_option', $suggest_option);

//        $expand_mode = 0;
//        $return_object['all'] = array(
//            'name' => 'Alle Ergebnisse',
//            'results' => $search_result['hits']['hits'],
//            'total' => $search_result['hits']['total']['value'],
//
//        );
//        if (is_null($s) or $s == "a" or $s == "") {
//            $return_object['all']['active'] = true;
//        } else {
//            $return_object['all']['active'] = false;
//        }


        $search_result = \HostEuropeGmbh\HosteuropeTemplate\Helper\Elasticsearch\Resource::search(
            $query,
            'question',
            $categories = array(),
            $s_language_uid,
            $offset = 0,
            $limit = 150,
            $expand_mode);

        if ($search_result['hits']['total']['value'] > 0) {
            $return_object['question'] = array(
                'name' => 'FAQ',
                'results' => $search_result['hits']['hits'],
                'total' => $search_result['hits']['total']['value'],
            );
            if ($s == "q") {
                $return_object['question']['active'] = true;
            } else {
                $return_object['question']['active'] = false;
            }


        }

        return $return_object;
    }


    public function suggestAction()
    {

        $query = htmlspecialchars($_GET['q']);

        $s_language_uid = $GLOBALS['TSFE']->sys_language_uid;
        $pageid = intval($GLOBALS['TSFE']->id);


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

        if ($search_result['suggest']['suggestion'][0]['options']) {
            foreach ($search_result['suggest']['suggestion'][0]['options'] as $suggest) {
                $suggest_option[] = $suggest['text'];
            }
        }

        echo json_encode($suggest_option);

        die("");
    }


    public function searchAction()
    {
        if (isset($_GET['index'])) {
            $this->_index();
            die("indexed");
        }


        $q = htmlspecialchars($_GET['q']);

        if (!$q and isset($_GET['tx_indexedsearch_pi2']['search']['sword'])) {
            $q = htmlspecialchars($_GET['tx_indexedsearch_pi2']['search']['sword']);
        }
        if (!$q and isset($_POST['tx_indexedsearch_pi2']['search']['sword'])) {
            $q = htmlspecialchars($_POST['tx_indexedsearch_pi2']['search']['sword']);
        }
        $s = htmlspecialchars($_GET['s']);
        $results = null;

        if (strlen($q)) {
            $results = $this->_search($q, $s);
        }

        $this->view->assign('q', $q);
        $this->view->assign('s', $s);
        $this->view->assign('results', $results);

        return $this->htmlResponse();
    }

    public function indexAction()
    {
        $this->_index();
    }


    protected function _index()
    {
        \HostEuropeGmbh\HosteuropeTemplate\Helper\Elasticsearch\Resources\Question::initMapping('hosteurope.de');
        \HostEuropeGmbh\HosteuropeTemplate\Helper\Elasticsearch\Resources\Question::deleteAll('hosteurope.de');

        $questions = $this->questionRepository->findAll();

        /**
         * @var Question $quoestion
         */
        foreach ($questions as $question) {

            $es_object = $question->getIndex();
            $es_object['s_url'] = str_replace("faaqqss/router/Controller/", "", '/faq/' . implode('/', $es_object['linkArguments']));

            //@TODO ... entscheidung welcher index
            \HostEuropeGmbh\HosteuropeTemplate\Helper\Elasticsearch\Resources\Question::indexData('hosteurope.de',
                $question->getUid(),
                $es_object);

        }

    }

    private function _displayCancelCookieHint()
    {
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        /**
         * Marketing cookie - show info to customer to activate support
         */
        $pageRenderer->addCssFile('EXT:hosteurope_template/Resources/Public/css/extra.css');

        if (isset($_COOKIE['OPTOUTMULTI']) && !empty($_COOKIE['OPTOUTMULTI'])) {
            $cookieValue = $_COOKIE['OPTOUTMULTI'];
            if (strrpos($cookieValue, '%7') !== false) {
                $cookieValue = urldecode($cookieValue);
            }

            if (!empty($cookieValue) && strrpos($cookieValue, 'c3:1') !== false) {
                // show extra message
                $this->view->assign('showMarketingCookieInfo', true);
            }

        }
    }

}