<?php

namespace HostEuropeGmbh\HosteuropeFaq\Controller;

use HostEuropeGmbh\HosteuropeFaq\Indexer\FaqIndexer;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class SearchController extends ActionController
{
    protected function _search(string $query): array
    {
        $this->_displayCancelCookieHint();

        $pageid = intval($GLOBALS['TSFE']->id);
        $this->view->assign('pid', $pageid);

        // Minimum character length for a search word to be included (must match MySQL ft_min_token_size)
        $searchWordLength = 3;
        // Multiplier for title relevance: higher values rank title matches above body-only matches
        $titleBoostFactor = 3;
        // Appends wildcard suffix (*) so "host" also matches "hosting", "hostname", etc.
        $enablePartSearch = true;
        // When true, all search words must be present (AND); when false, any word can match (OR)
        $enableExplicitAnd = false;

        $searchWords = preg_split('/\s+/', trim($query));
        $searchWords = array_filter($searchWords, fn($w) => mb_strlen($w) >= $searchWordLength);

        if (empty($searchWords)) {
            $suggest = $this->_suggest($query);
            return [
                'results' => [],
                'total' => 0,
                'suggest_option' => $suggest,
            ];
        }

        $wordsAgainst = implode(' ', array_map(
            function ($w) use ($enablePartSearch, $enableExplicitAnd) {
                $term = $w;
                if ($enablePartSearch) {
                    $term .= '*';
                }
                if ($enableExplicitAnd) {
                    $term = '+' . $term;
                }
                return $term;
            },
            $searchWords
        ));
        $scoreAgainst = implode(' ', $searchWords);

        $matchColumns = 'title,content,hidden_content';
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_kesearch_index');
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_kesearch_index');
        $queryBuilder->getRestrictions()->removeAll();

        $wordsAgainstQuoted = $connection->quote($wordsAgainst);
        $scoreAgainstQuoted = $connection->quote($scoreAgainst);

        $rows = $queryBuilder
            ->select('uid', 'title', 'abstract', 'params', 'targetpid', 'tags', 'type', 'hidden_content')
            ->addSelectLiteral(
                'MATCH (' . $matchColumns . ') AGAINST (' . $scoreAgainstQuoted . ')'
                . ' + (' . $titleBoostFactor . ' * MATCH (title) AGAINST (' . $scoreAgainstQuoted . '))'
                . ' AS score'
            )
            ->from('tx_kesearch_index')
            ->where(
                $queryBuilder->expr()->eq(
                    'type',
                    $queryBuilder->createNamedParameter(FaqIndexer::INDEXER_TYPE)
                )
            )
            ->andWhere(
                'MATCH (' . $matchColumns . ') AGAINST (' . $wordsAgainstQuoted . ' IN BOOLEAN MODE)'
            )
            ->orderBy('score', 'DESC')
            ->setMaxResults(100)
            ->executeQuery()
            ->fetchAllAssociative();

        $results = [];
        foreach ($rows as $row) {
            $targetPid = $row['targetpid'] ?: $pageid;
            $url = $this->buildFaqUrl($targetPid, $row['params']);

            $results[] = [
                'title' => $row['title'],
                'description' => $row['abstract'],
                'url' => $url,
                'hidden_content' => $row['hidden_content'],
            ];
        }

        $suggest = $this->_suggest($query);

        return [
            'results' => $results,
            'total' => count($results),
            'suggest_option' => $suggest,
        ];
    }

    protected function buildFaqUrl(int $targetPid, string $params): string
    {
        $cObj = GeneralUtility::makeInstance(\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer::class);
        $conf = [
            'parameter' => $targetPid,
            'additionalParams' => $params,
        ];
        $url = $cObj->typoLink_URL($conf);
        return $url ?: '#';
    }

    /**
     * Simple DB-based suggestion: find titles that partially match the query.
     */
    protected function _suggest(string $query): ?string
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_kesearch_index');

        $likePattern = '%' . $queryBuilder->escapeLikeWildcards($query) . '%';

        $row = $queryBuilder
            ->select('title')
            ->from('tx_kesearch_index')
            ->where(
                $queryBuilder->expr()->eq(
                    'type',
                    $queryBuilder->createNamedParameter(FaqIndexer::INDEXER_TYPE)
                ),
                $queryBuilder->expr()->like(
                    'title',
                    $queryBuilder->createNamedParameter($likePattern)
                )
            )
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchAssociative();

        if ($row && mb_strtolower($row['title']) !== mb_strtolower($query)) {
            return $row['title'];
        }

        return null;
    }

    public function suggestAction()
    {
        $query = htmlspecialchars($_GET['q'] ?? '');

        $suggestions = [];

        if (strlen($query) >= 2) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable('tx_kesearch_index');

            $likePattern = '%' . $queryBuilder->escapeLikeWildcards($query) . '%';

            $rows = $queryBuilder
                ->select('title')
                ->from('tx_kesearch_index')
                ->where(
                    $queryBuilder->expr()->eq(
                        'type',
                        $queryBuilder->createNamedParameter(FaqIndexer::INDEXER_TYPE)
                    ),
                    $queryBuilder->expr()->like(
                        'title',
                        $queryBuilder->createNamedParameter($likePattern)
                    )
                )
                ->setMaxResults(10)
                ->executeQuery()
                ->fetchAllAssociative();

            foreach ($rows as $row) {
                $suggestions[] = $row['title'];
            }
        }

        echo json_encode($suggestions);
        die('');
    }

    public function searchAction()
    {
        $q = htmlspecialchars($_GET['q'] ?? '');

        if (!$q && isset($_GET['tx_indexedsearch_pi2']['search']['sword'])) {
            $q = htmlspecialchars($_GET['tx_indexedsearch_pi2']['search']['sword']);
        }
        if (!$q && isset($_POST['tx_indexedsearch_pi2']['search']['sword'])) {
            $q = htmlspecialchars($_POST['tx_indexedsearch_pi2']['search']['sword']);
        }

        $results = null;
        $suggestOption = null;

        if (strlen($q)) {
            $searchData = $this->_search($q);
            $results = $searchData['results'];
            $suggestOption = $searchData['suggest_option'];
        }

        $pageid = intval($GLOBALS['TSFE']->id);
        $this->view->assign('pid', $pageid);
        $this->view->assign('q', $q);
        $this->view->assign('results', $results);
        $this->view->assign('total', $searchData['total'] ?? 0);
        $this->view->assign('suggest_option', $suggestOption);

        return $this->htmlResponse();
    }

    private function _displayCancelCookieHint(): void
    {
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $pageRenderer->addCssFile('EXT:hosteurope_template/Resources/Public/css/extra.css');

        if (isset($_COOKIE['OPTOUTMULTI']) && !empty($_COOKIE['OPTOUTMULTI'])) {
            $cookieValue = $_COOKIE['OPTOUTMULTI'];
            if (strrpos($cookieValue, '%7') !== false) {
                $cookieValue = urldecode($cookieValue);
            }

            if (!empty($cookieValue) && strrpos($cookieValue, 'c3:1') !== false) {
                $this->view->assign('showMarketingCookieInfo', true);
            }
        }
    }
}
