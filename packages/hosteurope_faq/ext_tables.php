<?php
defined('TYPO3') or die('Access denied.');

$extensionName = 'HosteuropeFaq';

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$extensionName,
	'Main',
	'HE -FAQ'
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$extensionName,
	'Apiview',
	'HE -FAQ - API View Counter'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$extensionName,
	'Apivote',
	'HE -FAQ - API Vote Counter'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$extensionName,
	'Apisend',
	'HE -FAQ - API Send'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$extensionName,
	'Search',
	'HE - Suche'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$extensionName,
	'Suggest',
	'HE - Suggest'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('hosteurope_faq', 'Configuration/TypoScript', 'Hosteurope FAQ');
