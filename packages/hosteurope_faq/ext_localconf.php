<?php
defined('TYPO3') or die('Access denied.');

use \HostEuropeGmbh\HosteuropeFaq\Controller\CategoryController;
use \HostEuropeGmbh\HosteuropeFaq\Controller\QuestionController;
use \HostEuropeGmbh\HosteuropeFaq\Controller\SearchController;
use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$extensionName = 'HosteuropeFaq';

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$extensionName,
	'Main',
    array(
        CategoryController::class => array('router', 'list', 'show', 'frage'),
        QuestionController::class => array('show' , 'list' ),
    ),
    // non-cacheable actions
    array(
        CategoryController::class => 'router',
        QuestionController::class => '',

    )
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$extensionName,
	'Apiview',
	array(
        CategoryController::class => 'view',

	),
	// non-cacheable actions
	array(
        CategoryController::class => 'view',

	)
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$extensionName,
	'Apivote',
	array(
        CategoryController::class => 'vote',

	),
	// non-cacheable actions
	array(
        CategoryController::class => 'vote',

	)
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$extensionName,
	'Apisend',
	array(
        CategoryController::class => 'send',

	),
	// non-cacheable actions
	array(
        CategoryController::class => 'send',

	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$extensionName,
	'Search',
	array(
        SearchController::class => 'search',

	),
	// non-cacheable actions
	array(
        SearchController::class => 'search',

	)
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$extensionName,
	'Suggest',
	array(
        SearchController::class => 'suggest',

	),
	// non-cacheable actions
	array(
        SearchController::class => 'suggest',

	)
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = \HostEuropeGmbh\HosteuropeFaq\Hooks\Hook::class;
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['linkhandler']['generateLink'][] = \HostEuropeGmbh\HosteuropeFaq\Hooks\Linkhandler::class;
$GLOBALS['TYPO3_CONF_VARS']['FE']['typolinkBuilder']['record'] = \HostEuropeGmbh\HosteuropeFaq\Hooks\RecordLinkBuilder::class;

// ke_search custom indexer for FAQ questions
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['registerIndexerConfiguration'][] = \HostEuropeGmbh\HosteuropeFaq\Indexer\FaqIndexerConfiguration::class;
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['customIndexer'][] = \HostEuropeGmbh\HosteuropeFaq\Indexer\FaqIndexer::class;

