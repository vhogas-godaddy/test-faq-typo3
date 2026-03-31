<?php
namespace HostEuropeGmbh\HosteuropeTemplate\Helper\CD;

use \TYPO3\CMS\Core\Utility\GeneralUtility;

class CustomerDetailsRead {
    /**
     * @var string
     */
    const API_URL_SANDBOX = 'https://cd-read.businessfabric.cloud/api/v1/';

    /**
     * @var string
     */
    const API_URL_LIVE = 'https://cd-read.emeaint.godaddy.com/api/v1/';

    /**
     * @var string
     */
    private $url = null;

    /**
     * @var CustomerDetailsRead
     */
    public static $instance = null;

    /**
     * Overiding php clone -- singleton purpose
     * @access private
     */
    private function __clone() {}

    /**
     * Singleton purpose (do not allow unserialize)
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton!");
    }

    /**
     * Class constructor
     */
    private function __construct()
    {
        // setting up the credentials
        if (getenv('DOCKER_DEVELOP_BOX') != false) {
            $this->url = self::API_URL_SANDBOX;
        } else {
            $this->url = self::API_URL_LIVE;
        }
    }

    /**
     * @name getInstance
     *
     * @return CustomerDetailsRead
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new CustomerDetailsRead();
        }

        return self::$instance;
    }

    /**
     * @name search
     */
    public function search($shopperId = null)
    {
        if (empty($shopperId)) {
            return false;
        }

        $queryString = "?";

        $data = array();

        if ( ! empty($shopperId)) {
            $data['shopperId'] = $shopperId;
        }

        $queryString .= http_build_query($data);

        $api = new RestApi(array(
            'url' => $this->url,
            'urlPart' => 'customers/search' . $queryString,
            'method' => 'GET',
            'postData' => array()
        ));

        $response = $api->sendRequest();

        if (!$response) {
            $logger = GeneralUtility::makeInstance('TYPO3\CMS\Core\Log\LogManager')->getLogger(__CLASS__);

            $logger->error("CustomerDetailsRead->search : ", $api->getLastCurlInfo());
        }

        return $response;
    }
}