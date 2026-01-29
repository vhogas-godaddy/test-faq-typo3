<?php

$temporaryColumns = array(
	'alias' => array(
		'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.alias',
		'exclude' => '1',
		'displayCond' => 'VERSION:IS:false',
		'config'  => array(
			'eval' => 'nospace',
			'max' => 32,
			'size' => 50,
			'softref' => 'notify',
			'type' => 'input'
		),
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
	'pages',
	$temporaryColumns
);