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
    private const SUPPORTED_TYPES = ['newsletter', 'contact'];

    public function __construct(
        private readonly AjaxController $ajaxController,
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $ajaxType = strtolower((string)($request->getQueryParams()['ajaxtype'] ?? ''));
        if (
            $request->getMethod() === 'POST'
            && in_array($ajaxType, self::SUPPORTED_TYPES, true)
        ) {
            return $this->ajaxController->handleAction($request);
        }

        return $handler->handle($request);
    }
}
