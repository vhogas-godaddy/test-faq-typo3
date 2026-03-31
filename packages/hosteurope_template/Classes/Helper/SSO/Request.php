<?php
namespace HostEuropeGmbh\HosteuropeTemplate\Helper\SSO;


class Request {
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $method = 'GET';

    /**
     * @var array
     */
    private $requestHeaders = array();

    /**
     * @var array
     */
    private $requestData = array();

    /**
     * @var array
     */
    private $response;

    /**
     * @var integer
     */
    private $httpCode;

    /**
     * @var array
     */
    private $curlInfo;

    /**
     * @var string
     */
    private $curlError;

    /**
     * @var integer
     */
    private $tryOuts = 1;

    /**
     * @var string
     */
    const API_BASE = 'https://sso.godaddy.com/v1/api/';

    /**
     * @var string
     */
    const API_BASE_DEV = 'https://sso.ote-godaddy.com/v1/api/';

    /**
     * @var array
     */
    public static $allowedRequestMethods = array('GET', 'POST', 'PATCH', 'DELETE');

    /**
     * @var array
     */
    public static $allowedRequestsWithData = array('POST', 'PATCH');

    /**
     * Class constructor
     *
     * @param string $url
     * @param string $requestMethod @see allowedRequestMethods
     * @param array $requestData
     * @param array $requestHeaders
     * @param integer $tryOuts
     */
    public function __construct($url = '', $requestMethod = 'GET', $requestData = array(), $requestHeaders = array(), $tryOuts = 1)
    {
        // set up the url
        $this->buildUrl($url);
        // set up the request data , type && headers
        $this->setRequestMethod($requestMethod);
        $this->setRequestData($requestData);
        $this->setRequestHeaders($requestHeaders);

        // set how many type should be repeated before failing
        $tryOuts = (int)$tryOuts;
        $this->tryOuts = empty($tryOuts) ? 1 : $tryOuts;
    }

    /**
     * @name buildUrl
     *
     * @param string url
     * @throws Exception
     */
    public function buildUrl($url = '')
    {
        if (empty($url)) {
            throw new \Exception("Empty url passed !", 500);
        }

        if (getenv('DOCKER_DEVELOP_BOX') != false) {
            $this->url = self::API_BASE_DEV . $url;
        } else {
            $this->url = self::API_BASE . $url;
        }
    }

    /**
     * @name setTryOuts
     *
     * @param int $tryOuts
     */
    public function setTryOuts($tryOuts = 1)
    {
        $this->tryOuts = $tryOuts;
    }

    /**
     * @name setRequestMethod
     *
     * @param string method
     * @throws Exception
     */
    public function setRequestMethod($method = '')
    {
        if ( ! in_array(strtoupper($method), self::$allowedRequestMethods)) {
            throw new \Exception("Invalid request method", 500);
        }

        $this->method = strtoupper($method);
    }

    /**
     * @name setRequestData
     *
     * @param array $requestData
     * @throws Exception
     */
    public function setRequestData($requestData = array())
    {
        if ( ! empty($requestData) && ! is_array($requestData)) {
            throw new \Exception("Request data must be passed as array when not empty", 1);
        }

        $this->requestData = $requestData;
    }

    /**
     * @name setRequestHeaders
     *
     * @param array $requestHeaders
     * @throws Exception
     */
    public function setRequestHeaders($requestHeaders = array())
    {
        if ( ! is_array($requestHeaders)) {
            throw new \Exception("Param requestHeaders must be passed as array", 1);
        }

        $defaultRequestHeaders = self::builDefaultHeaders();
        $this->requestHeaders = array_merge($defaultRequestHeaders, $requestHeaders);
    }

    /**
     * @name builDefaultHeaders
     *
     * @return array
     */
    public static function builDefaultHeaders()
    {
        $headers = array();
        $headers[] = 'Accept: application/json';

        return $headers;
    }

    /**
     * @name send
     *
     * @return boolean
     */
    final public function send()
    {
        $success = false;
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->method);

        if ($this->requestHeaders != '') {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $this->requestHeaders);
        }

        if ( ! empty($this->requestData) && in_array($this->method, self::$allowedRequestsWithData)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($this->requestData));
        }

        //curl_setopt($curl, CURLOPT_PROXY, 'http://proxy.hosteurope.de');
        //curl_setopt($curl, CURLOPT_PROXYPORT, 3128);

        // @todo -> When openssl will be upgraded comment out or remove this two lines
        //curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        //curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);

        for ($i = 0; $i < $this->tryOuts; $i++) {
            $response = curl_exec($curl);
            $httpCode = (int)curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $curlInfo = curl_getinfo($curl);
            $curlError = curl_error($curl);

            $this->curlInfo = $curlInfo;
            $this->httpCode = $httpCode;
            $this->response = json_decode($response, true);
            $this->curlError = $curlError;

            if ($this->isValidHttpCode($httpCode)) {
                curl_close($curl);
                $success = $this->response;
                break;
            }
        }

        // finally close the curl if it eventually failed
        if ($success == false) {
            curl_close($curl);
        }

        return $success;
    }

    /**
     * @name getLastCurlInfo
     *
     * @return array
     */
    public function getLastCurlInfo()
    {
        return array(
            'curlInfo' => $this->curlInfo,
            'httpCode' => $this->httpCode,
            'response' => $this->response,
            'url' => $this->url
        );
    }

    /**
     * @name isValidHttpCode
     *
     * @param integer $httpCode
     * @return boolean
     */
    public function isValidHttpCode($httpCode = 0)
    {
        $httpCode = (int)$httpCode;

        if ($httpCode >= 200 && $httpCode < 300) {
            return true;
        }

        return false;
    }
}