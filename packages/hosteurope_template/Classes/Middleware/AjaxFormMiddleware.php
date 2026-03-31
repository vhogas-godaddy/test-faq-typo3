<?php

declare(strict_types=1);

namespace HostEuropeGmbh\HosteuropeTemplate\Middleware;

use HostEuropeGmbh\HosteuropeTemplate\Controller\AjaxController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AjaxFormMiddleware implements MiddlewareInterface
{

    public function __construct(
        private readonly AjaxController $ajaxController,
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $ajaxType = strtolower((string)($request->getQueryParams()['ajaxtype'] ?? ''));
        if (!empty($ajaxType)) {
            return $this->ajaxController->handleAction($request);
        }

        return $handler->handle($request);
    }
}
