<?php
namespace HostEuropeGmbh\HosteuropeTemplate\Helper\CD;

class RestApi {

    /**
     * @var string
     */
    private $url = null;

    /**
     * @var string
     */
    private $method = 'GET';

    /**
     * @var array
     */
    private $postData = array();

    /**
     * @var array
     */
    private $curlHeaders = array();

    /**
     * @var mixed
     */
    private $response = null;

    /**
     * @var mixed
     */
    private $curlInfo = null;

    /**
     * @var mixed
     */
    private $httpCode = null;

    /**
     * @var array
     */
    private $options = array();

    /**
     * Class constructor
     */
    public function __construct($options = array())
    {
        if ( ! isset($options['url'])) {
            throw new \Exception("Bad call of CD RestApi. You must pass an endpoint", 500);
        }

        $this->options = $options;
        $this->url = $options['url'];
        $this->method = $options['method'];

        if (isset($options['urlPart']) && ! empty($options['urlPart'])) {
            $this->url .= $options['urlPart'];
        }

        if (isset($options['postData']) && is_array($options['postData'])) {
            $this->postData = $options['postData'];
        }

        if (isset($options['realmOptions']) && is_array($options['realmOptions'])) {
            $realmOptions = $options['realmOptions'];
        } else {
            $realmOptions = array();
        }

        /**
         * Adding/getting the auth bearer
         */
        $auth = AuthHandler::getInstance($realmOptions);

        $bearer = $auth->getBearer();

        if (empty($bearer)) {
            throw new \Exception("Cannot get api auth bearer", 500);
        }

        $this->curlHeaders = array(
            'Authorization: Bearer ' . $bearer,
            'Content-Type: application/json'
        );
    }

    /**
     * @name sendRequest
     */
    public function sendRequest()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->method);

        if ( ! empty($this->postData)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($this->postData));
        }

        if ( ! empty($this->curlHeaders)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $this->curlHeaders);
        }

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($curl);
        $this->curlInfo = curl_getinfo($curl);
        $this->httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (php_sapi_name() == "cli") {
            // echo "\r\n";
            //         	echo $this->url;
            //         	echo "\r\n";
            //         	print_r($this->curlHeaders);
            //         	echo "\r\n";
            //         	print_r($this->info);
            //         	echo "\r\n";
            //         	echo $response;
            //         	echo "\r\n";
        }

        if ($this->isValidHttpCode()) {
            $this->response = @json_decode($response, true);
            return $this->response;
        }

        return false;
    }

    /**
     * @name getLastCurlInfo
     *
     * @return array
     */
    public function getLastCurlInfo()
    {
        return array(
            'url' => $this->url,
            'method' => $this->method,
            'curlHeaders' => $this->curlHeaders,
            'curlInfo' => $this->curlInfo,
            'httpCode' => $this->httpCode,
            'response' => $this->response,
            'options' => $this->options
        );
    }

    /**
     * @name isValidHttpCode
     *
     * @return boolean
     */
    public function isValidHttpCode()
    {
        $httpCode = (int)$this->httpCode;

        if ($httpCode >= 200 && $httpCode < 300) {
            return true;
        }

        return false;
    }
}