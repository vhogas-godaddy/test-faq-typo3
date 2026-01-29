<?php
return array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:hosteurope_faq/Resources/Private/Language/locallang_db.xlf:tx_hosteuropefaq_domain_model_question',
		'label' => 'headline',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'sortby' => 'sorting',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,

		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'security' => array(
			'ignorePageTypeRestriction' => true,
		),
		'searchFields' => 'headline,content,show_top,show_form,show_vote,alias,seotitle,seodescription,downloads,links,categories,related_categories,related_questions,',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('hosteurope_faq') . 'Resources/Public/Icons/tx_hosteuropefaq_domain_model_question.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, headline, slug, faqid, content, prio,show_top, show_form, show_vote, alias, seotitle, seodescription, downloads, links, categories, related_categories, related_questions, countja, countnein, countview,countsend',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, headline, slug, faqid, content;;;richtext:rte_transform[mode=ts_links], prio,show_top, show_form, show_vote,--div--;SEO & Alias,  alias, seotitle, seodescription,  --div--;Related Content,downloads, links,related_categories, related_questions, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, starttime, endtime, countja, countnein, countview,countsend'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
	
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.language',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages', -1),
					array('LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.default_value', 0)
				),
			),
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_hosteuropefaq_domain_model_question',
				'foreign_table_where' => 'AND tx_hosteuropefaq_domain_model_question.pid=###CURRENT_PID### AND tx_hosteuropefaq_domain_model_question.sys_language_uid IN (-1,0)',
			),
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),

		't3ver_label' => array(
			'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 255,
			)
		),
	
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'starttime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'endtime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),

		'headline' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:hosteurope_faq/Resources/Private/Language/locallang_db.xlf:tx_hosteuropefaq_domain_model_question.headline',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			),
		),

		'faqid' => array(
			'exclude' => 1,
			'label' => 'Main FAQ ID (for shortlink: he.de/faq/1234)',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,integer'
			),
		),

		'prio' => array(
			'exclude' => 1,
			'label' => 'Priorität in Suche',
			'config' => array(
				'type' => 'select',
				'minitems' => 1,
				'items' => array(
					array('Wichtig', 10),
					array('Normal', 0),
					array('Unwichtig', -10)
				),
			),
		),

		'countja' => array(
			'exclude' => 1,
			'label' => 'Counter: Yes-Votes',
			'readOnly' => true,
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'readOnly' => true,
				'eval' => 'trim,integer'
			),
		),
		'countnein' => array(
			'exclude' => 1,
			'label' => 'Counter: Nein-Votes',
			'readOnly' => true,
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'readOnly' => true,
				'eval' => 'trim,integer'
			),
		),
		'countview' => array(
			'exclude' => 1,
			'label' => 'Counter: Views',
			'readOnly' => true,
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'readOnly' => true,
				'eval' => 'trim,integer'
			),
		),
		'countsend' => array(
			'exclude' => 1,
			'label' => 'Counter: Kommentare/Anfragen',
			'readOnly' => true,
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'readOnly' => true,
				'eval' => 'trim,integer'
			),
		),


		'slug' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:hosteurope_faq/Resources/Private/Language/locallang_db.xlf:tx_hosteuropefaq_domain_model_question.slug',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'nospace,lower,unique,required'
			),
		),
		'content' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:hosteurope_faq/Resources/Private/Language/locallang_db.xlf:tx_hosteuropefaq_domain_model_question.content',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim',
				'wizards' => array(
					'RTE' => array(
						'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_rte.gif',
						'notNewRecords'=> 1,
						'RTEonly' => 1,
						'module' => array(
							'name' => 'wizard_rte',
						),
						'title' => 'LLL:EXT:cms/locallang_ttc.xlf:bodytext.W.RTE',
						'type' => 'script'
					)
				)
			),
		),
		'show_top' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:hosteurope_faq/Resources/Private/Language/locallang_db.xlf:tx_hosteuropefaq_domain_model_question.show_top',
			'config' => array(
				'type' => 'check',
				'default' => 0
			)
		),
		'show_form' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:hosteurope_faq/Resources/Private/Language/locallang_db.xlf:tx_hosteuropefaq_domain_model_question.show_form',
			'config' => array(
				'type' => 'check',
				'default' => 0
			)
		),
		'show_vote' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:hosteurope_faq/Resources/Private/Language/locallang_db.xlf:tx_hosteuropefaq_domain_model_question.show_vote',
			'config' => array(
				'type' => 'check',
				'default' => 0
			)
		),
		'alias' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:hosteurope_faq/Resources/Private/Language/locallang_db.xlf:tx_hosteuropefaq_domain_model_question.alias',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			)
		),
		'seotitle' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:hosteurope_faq/Resources/Private/Language/locallang_db.xlf:tx_hosteuropefaq_domain_model_question.seotitle',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'seodescription' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:hosteurope_faq/Resources/Private/Language/locallang_db.xlf:tx_hosteuropefaq_domain_model_question.seodescription',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			)
		),
		'downloads' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:hosteurope_faq/Resources/Private/Language/locallang_db.xlf:tx_hosteuropefaq_domain_model_question.downloads',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_hosteuropefaq_domain_model_download',
				'MM' => 'tx_hosteuropefaq_question_download_mm',
				'maxitems' => 9999,
				'appearance' => array(
					'collapseAll' => 0,
					'levelLinksPosition' => 'top',
					'showSynchronizationLink' => 1,
					'showPossibleLocalizationRecords' => 1,
					'showAllLocalizationLink' => 1
				),
			),
		),
		'links' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:hosteurope_faq/Resources/Private/Language/locallang_db.xlf:tx_hosteuropefaq_domain_model_question.links',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_hosteuropefaq_domain_model_link',
				'foreign_field' => 'question',
				'maxitems' => 9999,
				'appearance' => array(
					'collapseAll' => 0,
					'levelLinksPosition' => 'top',
					'showSynchronizationLink' => 1,
					'showPossibleLocalizationRecords' => 1,
					'showAllLocalizationLink' => 1
				),
			),

		),
		'categories' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:hosteurope_faq/Resources/Private/Language/locallang_db.xlf:tx_hosteuropefaq_domain_model_question.categories',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectTree',
				'foreign_table' => 'tx_hosteuropefaq_domain_model_category',
				'MM' => 'tx_hosteuropefaq_question_category_mm',
				'size' => 10,
				'autoSizeMax' => 50,
				'minitems' => 1,
				'maxitems' => 9999,
				'multiple' => 1,
				'treeConfig' => array(
					'parentField' => 'parent',
					'appearance' => array(
						'expandAll' => TRUE,
						'showHeader' => TRUE,
					),
				),
				'wizards' => array(
					'_PADDING' => 1,
					'_VERTICAL' => 1,
					'edit' => array(
						'module' => array(
							'name' => 'wizard_edit',
						),
						'type' => 'popup',
						'title' => 'Edit',
						'icon' => 'edit2.gif',
						'popup_onlyOpenIfSelected' => 1,
						'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
						),
					'add' => Array(
						'module' => array(
							'name' => 'wizard_add',
						),
						'type' => 'script',
						'title' => 'Create new',
						'icon' => 'add.gif',
						'params' => array(
							'table' => 'tx_hosteuropefaq_domain_model_category',
							'pid' => '###CURRENT_PID###',
							'setValue' => 'prepend'
						),
					),
				),
			),
		),
		'related_categories' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:hosteurope_faq/Resources/Private/Language/locallang_db.xlf:tx_hosteuropefaq_domain_model_question.related_categories',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectTree',
				'foreign_table' => 'tx_hosteuropefaq_domain_model_category',
				'MM' => 'tx_hosteuropefaq_question_relatedcategories_category_mm',
				'size' => 10,
				'autoSizeMax' => 30,
				'maxitems' => 9999,
				'multiple' => 1,
				'treeConfig' => array(
					'parentField' => 'parent',
					'appearance' => array(
						'expandAll' => TRUE,
						'showHeader' => TRUE,
					),
				),
				'wizards' => array(
					'_PADDING' => 1,
					'_VERTICAL' => 1,
					'edit' => array(
						'module' => array(
							'name' => 'wizard_edit',
						),
						'type' => 'popup',
						'title' => 'Edit',
						'icon' => 'edit2.gif',
						'popup_onlyOpenIfSelected' => 1,
						'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
						),
					'add' => Array(
						'module' => array(
							'name' => 'wizard_add',
						),
						'type' => 'script',
						'title' => 'Create new',
						'icon' => 'add.gif',
						'params' => array(
							'table' => 'tx_hosteuropefaq_domain_model_category',
							'pid' => '###CURRENT_PID###',
							'setValue' => 'prepend'
						),
					),
				),
			),
		),
		'related_questions' => array(
			'exclude' => 1,
			'label' => 'Diese Artikel könnten Sie auch interessieren:',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'foreign_table' => 'tx_hosteuropefaq_domain_model_question',
				'MM' => 'tx_hosteuropefaq_question_question_mm',
				'size' => 10,
				'autoSizeMax' => 30,
				'maxitems' => 9999,
				'multiple' => 0,
				'wizards' => array(
					'_PADDING' => 1,
					'_VERTICAL' => 1,
					'edit' => array(
						'module' => array(
							'name' => 'wizard_edit',
						),
						'type' => 'popup',
						'title' => 'Edit',
						'icon' => 'edit2.gif',
						'popup_onlyOpenIfSelected' => 1,
						'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
						),
					'add' => Array(
						'module' => array(
							'name' => 'wizard_add',
						),
						'type' => 'script',
						'title' => 'Create new',
						'icon' => 'add.gif',
						'params' => array(
							'table' => 'tx_hosteuropefaq_domain_model_question',
							'pid' => '###CURRENT_PID###',
							'setValue' => 'prepend'
						),
					),
				),
			),
		),
		
	),
);