<?php
return array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:hosteurope_faq/Resources/Private/Language/locallang_db.xlf:tx_hosteuropefaq_domain_model_category',
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
		'searchFields' => 'headline,subline,content,parent,show_form,show_vote,alias,icon,seotitle,seodescription,downloads,links,related_categories,related_questions,',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('hosteurope_faq') . 'Resources/Public/Icons/tx_hosteuropefaq_domain_model_category.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, headline, slug, subline, content, parent, show_form, show_vote, alias, icon, seotitle, seodescription, downloads, links, related_categories, related_questions,crossselling_teaser',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, headline, slug, subline, content;;;richtext:rte_transform[mode=ts_links], parent, show_form, show_vote, icon, --div--;SEO & Alias, seotitle, seodescription, alias,--div--;Related Content, downloads, links ,related_categories, related_questions,crossselling_teaser, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, starttime, endtime'),
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
				'foreign_table' => 'tx_hosteuropefaq_domain_model_category',
				'foreign_table_where' => 'AND tx_hosteuropefaq_domain_model_category.pid=###CURRENT_PID### AND tx_hosteuropefaq_domain_model_category.sys_language_uid IN (-1,0)',
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
			'exclude' => 0,
			'label' => 'LLL:EXT:hosteurope_faq/Resources/Private/Language/locallang_db.xlf:tx_hosteuropefaq_domain_model_category.headline',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			),
		),

		'slug' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:hosteurope_faq/Resources/Private/Language/locallang_db.xlf:tx_hosteuropefaq_domain_model_category.slug',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'nospace,lower,unique,required'
			),
		),
		'subline' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:hosteurope_faq/Resources/Private/Language/locallang_db.xlf:tx_hosteuropefaq_domain_model_category.subline',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			)
		),
		'content' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:hosteurope_faq/Resources/Private/Language/locallang_db.xlf:tx_hosteuropefaq_domain_model_category.content',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim',
				'wizards' => array(
					'RTE' => array(
						'icon' => 'wizard_rte2.gif',
						'notNewRecords'=> 1,
						'RTEonly' => 1,
						'module' => array(
							'name' => 'wizard_rich_text_editor',
							'urlParameters' => array(
								'mode' => 'wizard',
								'act' => 'wizard_rte.php'
							)
						),
						'title' => 'LLL:EXT:cms/locallang_ttc.xlf:bodytext.W.RTE',
						'type' => 'script'
					)
				)
			),
		),
		'parent' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:hosteurope_faq/Resources/Private/Language/locallang_db.xlf:tx_hosteuropefaq_domain_model_category.parent',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_hosteuropefaq_domain_model_category',
				'size' => 10,
				'renderType' => 'selectTree',
				'treeConfig' => array(
					'expandAll' => true,
					'parentField' => 'parent',
					'appearance' => array(
						'showHeader' => TRUE,
					),
				),
			)
		),
		'show_form' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:hosteurope_faq/Resources/Private/Language/locallang_db.xlf:tx_hosteuropefaq_domain_model_category.show_form',
			'config' => array(
				'type' => 'check',
				'default' => 0
			)
		),
		'show_vote' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:hosteurope_faq/Resources/Private/Language/locallang_db.xlf:tx_hosteuropefaq_domain_model_category.show_vote',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'alias' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:hosteurope_faq/Resources/Private/Language/locallang_db.xlf:tx_hosteuropefaq_domain_model_category.alias',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			)
		),
		'icon' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:hosteurope_faq/Resources/Private/Language/locallang_db.xlf:tx_hosteuropefaq_domain_model_category.icon',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'seotitle' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:hosteurope_faq/Resources/Private/Language/locallang_db.xlf:tx_hosteuropefaq_domain_model_category.seotitle',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'seodescription' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:hosteurope_faq/Resources/Private/Language/locallang_db.xlf:tx_hosteuropefaq_domain_model_category.seodescription',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			)
		),
		'downloads' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:hosteurope_faq/Resources/Private/Language/locallang_db.xlf:tx_hosteuropefaq_domain_model_category.downloads',
			'config' => array(
				'type' => 'inline',
				//'renderType' => 'selectMultipleSideBySide',
				'foreign_table' => 'tx_hosteuropefaq_domain_model_download',
				'MM' => 'tx_hosteuropefaq_category_download_mm',
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
			'label' => 'LLL:EXT:hosteurope_faq/Resources/Private/Language/locallang_db.xlf:tx_hosteuropefaq_domain_model_category.links',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_hosteuropefaq_domain_model_link',
				'foreign_field' => 'category',
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
		'related_categories' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:hosteurope_faq/Resources/Private/Language/locallang_db.xlf:tx_hosteuropefaq_domain_model_category.related_categories',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectTree',
				'foreign_table' => 'tx_hosteuropefaq_domain_model_category',
				'MM' => 'tx_hosteuropefaq_category_category_mm',
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
			'label' => 'LLL:EXT:hosteurope_faq/Resources/Private/Language/locallang_db.xlf:tx_hosteuropefaq_domain_model_category.related_questions',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'foreign_table' => 'tx_hosteuropefaq_domain_model_question',
				'MM' => 'tx_hosteuropefaq_category_question_mm',
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
		'crossselling_teaser' => array(
			'exclude' => 1,
			'label' => 'Crossselling-Teaser (1 bis 2)',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'foreign_table' => 'tx_hosteuropecrossselling_domain_model_teaser',
				'MM' => 'tx_hosteuropefaq_category_crosssellingteaser_mm',
				'size' => 10,
				'autoSizeMax' => 30,
				'maxitems' => 2,
				'multiple' => 0
			),
		),
		
	),
);