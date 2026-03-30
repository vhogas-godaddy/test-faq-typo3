<?php

declare(strict_types=1);

namespace HostEuropeGmbh\HosteuropeTemplate\Controller;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class AjaxController
{
    private const SUPPORTED_TYPES = ['newsletter', 'contact'];

    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
    ) {}

    public function handleAction(ServerRequestInterface $request): ResponseInterface
    {
        $ajaxType = strtolower((string)($request->getQueryParams()['ajaxtype'] ?? ''));
        if (!in_array($ajaxType, self::SUPPORTED_TYPES, true)) {
            return $this->jsonResponse(['success' => false], 400);
        }

        return match ($ajaxType) {
            'newsletter' => $this->newsletterAction($request),
            'contact' => $this->contactAction($request),
        };
    }

    private function newsletterAction(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        $firstname = $data['newsletter']['firstname'] ?? '';
        $lastname = $data['newsletter']['lastname'] ?? '';
        $email = $data['newsletter']['email'] ?? '';

        $firstname = htmlspecialchars(trim($firstname));
        $lastname = htmlspecialchars(trim($lastname));
        $email = htmlspecialchars(trim($email));

        if (empty($firstname) || empty($lastname) || empty($email)) {
            return $this->jsonResponse(['success' => false, 'message' => 'Missing required fields'], 400);
        }

        if (!GeneralUtility::validEmail($email)) {
            return $this->jsonResponse(['success' => false, 'message' => 'Invalid email address'], 400);
        }

        require_once( __DIR__ . '/../../Resources/Private/Libs/pmapi/pmapirequest.class.inc' );
        require_once( __DIR__ . '/../../Resources/Private/Libs/pmapi/pmapiauthhash.class.inc' );
        require_once( __DIR__ . '/../../Resources/Private/Libs/pmapi/pmapisubscriber.class.inc' );

        $request = new \PMAPIRequest( new \PMAPIAuthHash( PMAPI_UID, PMAPI_CID, PMAPI_HASH ) );

        $args = array(
            'email'     => $email,
            'list_id'   => PMAPI_LIST_ID,
            'confirmed' => 0,
        );

        if ( strlen( $firstname ) ) {
            $args['firstname'] = $firstname;
        }
        if ( strlen( $lastname ) ) {
            $args['lastname'] = $lastname;
        }

        $response = $request->subscriber->post( $args );

        if ($response->isError) {
            return $this->jsonResponse(['success' => false, 'message' => $response->error], 400);
        } else {
            $subscriberId = $response->response['response']['data']['id'];

            $subscriber = new \PMAPISubscriber($request,$subscriberId);

            if($subscriber){
                $response = $subscriber->sendOptInEmail(PMAPI_LIST_ID);
            }
        }

        return $this->jsonResponse(['success' => true, 'message' => 'Subscriber added successfully']);
    }

    private function contactAction(ServerRequestInterface $request): ResponseInterface
    {
        return $this->jsonResponse(['success' => true]);
    }

    private function jsonResponse(array $payload, int $status = 200): ResponseInterface
    {
        $response = $this->responseFactory->createResponse($status)
            ->withHeader('Content-Type', 'application/json; charset=utf-8');
        $response->getBody()->write(json_encode($payload, JSON_THROW_ON_ERROR));

        return $response;
    }
}
