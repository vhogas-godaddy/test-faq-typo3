<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'HostEurope Template',
    'description' => 'TYPO3 Template for Host Europe GmbH',
    'category' => 'templates',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-13.4.99',
            'fluid_styled_content' => '13.4.0-13.4.99',
            'rte_ckeditor' => '13.4.0-13.4.99',
        ],
        'conflicts' => [
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'HostEuropeGmbh\\HosteuropeTemplate\\' => 'Classes',
        ],
    ],
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 1,
    'author' => 'Vlad-Marius Hogas',
    'author_email' => 'vhogas@godaddy.com',
    'author_company' => 'Host Europe GmbH',
    'version' => '1.0.0',
];
