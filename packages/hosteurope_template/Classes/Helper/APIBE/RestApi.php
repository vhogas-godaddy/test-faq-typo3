<?php
namespace HostEuropeGmbh\HosteuropeTemplate\Helper\APIBE;

class RestApi
{
    /**
     * @var string
     */
    private $url = 'https://api-be.intern.hosteurope.de/';

    /**
     * @var string
     */
    private $requestMethod = 'GET';

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
     * @var array
     */
    private $options = array();

    /**
     * @var array
     */
    public static $allowedRequestMethods = array('GET', 'PUT', 'POST', 'PATCH', 'DELETE');


    /**
     * @var array
     */
    public static $allowedRequestsWithData = array('POST', 'PATCH', 'PUT');

    /**
     * Class constructor
     *
     * @param string $url
     * @param array $options
     */
    public function __construct($url = '', $options = array())
    {
        // set up the url
        if (getenv('DOCKER_DEVELOP_BOX') != false) {
            $this->url = 'https://dockerdev.api-be.intern.dockerheg.com/';
        }

        $this->setApiOptions($options);
    }

    /**
     * @name setUrl
     *
     * @param string url
     * @return void
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function setApiOptions($options = array())
    {
        $this->options = $options;

        $this->setRequestMethod();
        $this->setRequestData();
        $this->setRequestHeaders();
        $this->setTryouts();
    }

    /**
     * @name setTryouts
     *
     * @param string method
     */
    private function setTryouts()
    {
        $tryOuts = isset($this->options['tryOuts']) ? (int)$this->options['tryOuts'] : 1;
        $this->tryOuts = empty($tryOuts) ? 1 : $tryOuts;
    }

    /**
     * @name setRequestMetho
     *
     * @param string method
     */
    private function setRequestMethod()
    {
        $requestMethod = isset($this->options['requestMethod']) ? $this->options['requestMethod'] : 'GET';

        if (!in_array(strtoupper($requestMethod), self::$allowedRequestMethods)) {
            $this->requestMethod = 'GET';
        } else {
            $this->requestMethod = strtoupper($requestMethod);
        }
    }

    /**
     * @name setRequestData
     *
     * @param array $requestData
     */
    private function setRequestData()
    {
        $requestData = isset($this->options['requestData']) ? $this->options['requestData'] : array();
        $this->requestData = $requestData;
    }

    /**
     * @name setRequestHeaders
     *
     * @param array $requestHeaders
     */
    private function setRequestHeaders()
    {
        $requestHeaders = isset($this->options['requestHeaders']) ? $this->options['requestHeaders'] : array();

        if (!is_array($requestHeaders)) {
            $requestHeaders = array();
        }

        $this->requestHeaders = $requestHeaders;
    }

    public static function addXRayId($curlHeaders)
    {
        $headers = getallheaders();

        if (!empty($headers) && isset($headers['X-Ray-Id'])) {
            $curlHeaders['X-Ray-Id'] = $headers['X-Ray-Id'];
        }

        return $curlHeaders;
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
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->requestMethod);

        $this->requestHeaders = self::addXRayId($this->requestHeaders);


        if (!empty($this->requestHeaders)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $this->requestHeaders);
        }

        if (!empty($this->requestData)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $this->requestData);
        }

        // SSL check disabled as PPE is not having a valid cert
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);


        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);

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
                $success = true;
                break;
            }
        }

        // finally close the curl if it eventually failed
        if ($success == false) {
            curl_close($curl);
            return false;
        } else {
            return $this->response;
        }
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
            'url' => $this->url,
            'method' => $this->requestMethod,
            'options' => $this->options
        );
    }

    /**
     * @name isValidHttpCode
     *
     * @param integer $httpCode
     * @return boolean
     */
    private function isValidHttpCode($httpCode = 0)
    {
        $httpCode = (int)$httpCode;

        if ($httpCode >= 200 && $httpCode < 300) {
            return true;
        }

        return false;
    }

}
