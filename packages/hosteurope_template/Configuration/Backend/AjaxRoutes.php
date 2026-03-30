<?php

declare(strict_types=1);

use HostEuropeGmbh\HosteuropeTemplate\Controller\AjaxController;

return [
    'hosteurope_template_ajax_handle' => [
        'path' => '/hosteurope-template/ajax/handle',
        'methods' => ['POST'],
        'target' => AjaxController::class . '::handleAction',
    ],
];
