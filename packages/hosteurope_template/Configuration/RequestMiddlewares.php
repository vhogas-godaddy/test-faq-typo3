<?php

declare(strict_types=1);

use HostEuropeGmbh\HosteuropeTemplate\Middleware\AjaxFormMiddleware;

return [
    'frontend' => [
        'hosteurope/template/ajax-form' => [
            'target' => AjaxFormMiddleware::class,
            'after' => [
                'typo3/cms-frontend/page-resolver',
            ],
            'before' => [
                'typo3/cms-frontend/page-argument-validator',
            ],
        ],
    ],
];
