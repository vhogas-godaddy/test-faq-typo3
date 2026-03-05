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
use TYPO3\CMS\Core\Error\Http\PageNotFoundException;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Http\ForwardResponse;

/**
 * CategoryController
 */
class CategoryController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * categoryRepository
     *
     * @var HostEuropeGmbh\HosteuropeFaq\Domain\Repository\CategoryRepository
     */
    public $categoryRepository = NULL;

    /**
     * questionRepository
     *
     * @var HostEuropeGmbh\HosteuropeFaq\Domain\Repository\QuestionRepository
     *
     */
    public $questionRepository = NULL;


    public function __construct()
    {
        $this->categoryRepository = GeneralUtility::makeInstance(\HostEuropeGmbh\HosteuropeFaq\Domain\Repository\CategoryRepository::class);
        $this->questionRepository = GeneralUtility::makeInstance(\HostEuropeGmbh\HosteuropeFaq\Domain\Repository\QuestionRepository::class);
    }

    /**
     * action list
     *
     *
     * @return void
     */
    public function routerAction()
    {
        if (!$this->request->hasArgument('slug')) {
            return new ForwardResponse('list');
        } else {
            $slug = $this->request->getArgument('slug');
            $question = NULL;

            if (!strlen($slug[0])) {
                return new ForwardResponse('list');
            }


            if (is_numeric($slug[0])) {
                $entity = $this->questionRepository->findOneByFaqid($slug[0]);

                if ($entity) {
                    return $this->redirect('router', null, null, array('slug' => $entity->getLinkarguments()), $pageUid = null, $delay = 0, $statusCode = 301);
                }
            }


            $category = $this->categoryRepository->findRootCategory($slug[0]);

            if (!$category) {
                throw new PageNotFoundException();
            }

            for ($i = 1; $i <= 4; $i++) {
                if (isset($slug[$i]) and strlen($slug[$i])) {
                    $slug_found = FALSE;

                    //Check Categorie
                    $entities = $this->categoryRepository->findByParent($category->getUid());
                    foreach ($entities as $entity) {
                        if ($entity->getSlug() == $slug[$i]) {
                            $slug_found = TRUE;
                            $category = $entity;
                            continue;
                        }
                    }


                    if (!$slug_found) {
                        $entities = $this->questionRepository->findByPid($category->getPid());
                        foreach ($entities as $entity) {
                            $lastSlug = isset($slug[$i + 1]) && $slug[$i + 1] ? false : true;
                            if ($entity->getSlug() == $slug[$i] && $lastSlug) {
                                return (new ForwardResponse('frage'))->withArguments(['question' => $entity]);
                            }
                        }
                    }

                    //SLug konnte nicht aufgelöst werden
                    if (!$slug_found) {
                        throw new PageNotFoundException();
                    }
                }
            }
            return (new ForwardResponse('show'))->withArguments(['category' => $category->getUid()]);
        }
    }


    /**
     * action list
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function listAction(): \Psr\Http\Message\ResponseInterface
    {
        $this->_displayCancelCookieHint();

        $categories = $this->categoryRepository->findByParent(0);
        $this->view->assign('categories', $categories);

        return $this->htmlResponse();
    }


    /**
     * action show
     *
     * @param \HostEuropeGmbh\HosteuropeFaq\Domain\Model\Category $category
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function showAction(\HostEuropeGmbh\HosteuropeFaq\Domain\Model\Category $category): \Psr\Http\Message\ResponseInterface
    {

        $this->_displayCancelCookieHint();

        $this->view->assign('category', $category);
        $categories = $this->_getCategoryTree();
        $this->view->assign('root_categories', $this->_getCategoryTree());

        if ($category->getParent()) {
            $this->view->assign('questions', $this->questionRepository->findByPid($category->getPid()));
        } else {
            // Show
            $childs = $this->categoryRepository->findByParent($category->getUid());
            $pids = array();
            foreach ($childs as $child) {
                $pids[] = $child->getPid();
            }

            $this->view->assign('questions', $this->questionRepository->findTop($pids));
        }

        return $this->htmlResponse();
    }

    /**
     * action show
     *
     * @param \HostEuropeGmbh\HosteuropeFaq\Domain\Model\Question $question
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function frageAction(\HostEuropeGmbh\HosteuropeFaq\Domain\Model\Question $question): \Psr\Http\Message\ResponseInterface
    {
        $this->_displayCancelCookieHint();

        $this->view->assign('recaptcha', getenv('RECAPTCHA_SITE_KEY'));
        $this->view->assign('question', $question);
        $category = $this->categoryRepository->findOneByPid($question->getPid());
        if ($category->getParent()) {
            $parent_category = $category->getParent();
        } else {
            $parent_category = NULL;
        }

        $this->view->assign('category', $category);
        $this->view->assign('root_categories', $this->_getCategoryTree());
        return $this->htmlResponse();
    }

    protected function _getCategoryTree()
    {
        $categories = $this->categoryRepository->findByParent(0);

        $response = array();
        foreach ($categories as $category) {
            $childs = $this->categoryRepository->findByParent($category->getUid());

            $response[] = array(
                'entity' => $category,
                'childs' => $childs
            );
        }

        return $response;

    }

    protected function sendAction()
    {

        $arguments = $this->request->getArguments();
        $qUid = intval($arguments['q']);

        if (!$qUid) {
            return die(json_encode(array('status' => 400, 'code' => 4001)));
        }

        /**
         * @var Question $question
         */
        $question = $this->questionRepository->findByUid($qUid);

        if (!$question) {
            return die(json_encode(array('status' => 400, 'code' => 4002)));
        }

        $gRecaptchaResponse = $_POST['g-recaptcha-response'];

        if (!$gRecaptchaResponse) {
            return die(json_encode(array('status' => 400, 'code' => 4003)));
        }


        //CHeck Captcha
        require_once(__DIR__ . '/../../Resources/Private/lib/src/autoload.php');
        $recaptcha = new \ReCaptcha\ReCaptcha(getenv('RECAPTCHA_SECRET_KEY'));

        $resp = $recaptcha->verify($gRecaptchaResponse, $_SERVER['REMOTE_ADDR']);
        if ($resp->isSuccess()) {

        } else {
            $errors = $resp->getErrorCodes();
            return die(json_encode(array('status' => 400, 'code' => 4004, 'errors' => $errors)));
        }


        $question->setCountsend(intval($question->getCountsend() + 1));

        $this->questionRepository->update($question);


        $persistenceManager = $this->objectManager->get("TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager");
        $persistenceManager->persistAll();


        //Read Contents
        $name = $arguments['name'];
        $kundennummer = $arguments['kundennummer'];
        $email = $arguments['email'];
        $kommentar = $arguments['kommentar'];


        $uri = $this->uriBuilder->setCreateAbsoluteUri(true)->uriFor('router', array('slug' => $question->getLinkarguments()), 'Controller', 'HosteuropeFaq', 'Main');
        $uri = str_replace("/faaqqss/router/Controller/", "/faq/", $uri);

        $body = "Bitte bearbeiten Sie diese FAQ-Anfrage so schnell wie möglich.\n\nFormular Daten:\n++++++++++++
Frage: " . $question->getHeadline() . "
Frontend-URL: " . $uri . "
Name:  " . $name . "
Kundennummer: " . $kundennummer . "
E-Mail-Adresse:" . $email . " 
Kommentar:
" . $kommentar . "
++++++++++++\n\n
\n\n\n\nClient-Info:\n
++++++++++++
IP: " . $_SERVER['REMOTE_ADDR'] . "
Date: " . date("m.d.Y H:I") . "
Referrer: " . $_SERVER['HTTP_REFERER'] . "
User-Agent:" . $_SERVER['HTTP_USER_AGENT'] . "
++++++++++++
";
        /*
    $mail = GeneralUtility::makeInstance( 'TYPO3\\CMS\\Core\\Mail\\MailMessage' );



    $mail
      // Give the message a subject
      ->setSubject('FAQ Kommentar:  '.$question->getHeadline() )
      // Set the From address with an associative array
      ->setFrom( array( 'support@hosteurope.de' => 'hosteurope.de' ) )
      // Set the To addresses with an associative array
      ->setTo( 'faq-kommentare@hosteurope.de') //,'faq-kommentare@hosteurope.de'
      //->setTo( 'CarstenD@Dittmann-Media.de')
      // Give it a body
      ->setBody( $body )
      // And finally do send it
      ->send();
        */
        $apibe = $this->objectManager->get('HostEuropeGmbh\\HosteuropeTemplate\\Helper\\APIBE\\Handler');

        $res = $apibe->sendContactMail('faq-kommentare@hosteurope.de', 'support@hosteurope.de', 'FAQ Kommentar:  ' . $question->getHeadline(), $body);
        if ($res) {
            return die(json_encode(array('status' => 200, 'code' => 2000)));
        } else {
            return die(json_encode(array('status' => 400, 'code' => 0)));
        }

    }


    protected function voteAction()
    {
        $arguments = $this->request->getArguments();
        $qUid = intval($arguments['q']);
        $vote = intval($arguments['v']);

        if (!$qUid) {
            return die(json_encode(array('status' => 400, 'code' => 4001)));
        }

        /**
         * @var Question $question
         */
        $question = $this->questionRepository->findByUid($qUid);

        if (!$question) {
            return die(json_encode(array('status' => 400, 'code' => 4002)));
        }

        if ($vote == 1) {
            //Vote JA
            $question->setCountja(intval($question->getCountja()) + 1);
        } else {
            //Vote Nein
            $question->setCountnein(intval($question->getCountnein()) + 1);
        }

        $this->questionRepository->update($question);


        $persistenceManager = $this->objectManager->get("TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager");
        $persistenceManager->persistAll();

        return die(json_encode(array('status' => 200, 'code' => 2000)));
    }


    protected function viewAction()
    {
        $arguments = $this->request->getArguments();
        $qUid = intval($arguments['q']);


        if (!$qUid) {
            return die(json_encode(array('status' => 400, 'code' => 4001)));
        }

        /**
         * @var Question $question
         */
        $question = $this->questionRepository->findByUid($qUid);

        if (!$question) {
            return die(json_encode(array('status' => 400, 'code' => 4002)));
        }

        $question->setCountview($question->getCountview() + 1);


        $this->questionRepository->update($question);


        $persistenceManager = $this->objectManager->get("TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager");
        $persistenceManager->persistAll();

        return die(json_encode(array('status' => 200, 'code' => 2000)));
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