<?php
namespace HostEuropeGmbh\HosteuropeTemplate\Helper\CD;

use HostEuropeGmbh\HosteuropeTemplate\Helper\BackupHelper;

class AuthHandler
{

    private $curlHeaders = array(
        'Content-Type: application/x-www-form-urlencoded'
    );

    /**
     * @var array
     */
    private $auth = array();

    /**
     * @var AuthHandler
     */
    public static $instance = null;

    /**
     * @var string
     */
    private $accessToken = array();

    /**
     * @var string
     */
    private $accessTokenExpires = array();

    /**
     * @var string
     */
    const API_URL_SANDBOX = 'https://isso-sandbox.ba.heg.com/auth/realms/BusinessFabricSandbox/protocol/openid-connect/token';

    /**
     * @var string
     */
    const API_URL_LIVE = 'https://security.ba.heg.com/auth/realms/BusinessFabric/protocol/openid-connect/token';

    /**
     * @var string
     */
    private $url = null;

    /**
     * Overiding php clone -- singleton purpose
     * @access private
     */
    private function __clone()
    {

    }

    /**
     * Singleton purpose (do not allow unserialize)
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton!");
    }

    /**
     * @name getInstance
     *
     * @return AuthHandler
     */
    public static function getInstance($realmOptions = array())
    {
        if (self::$instance == null) {
            self::$instance = new AuthHandler($realmOptions);
        }

        if (!empty($realmOptions)) {
            self::$instance->setAuthAndUrl($realmOptions);
        }

        return self::$instance;
    }

    /**
     * Class constructor
     *
     * @param array $realmOptions
     */
    private function __construct($realmOptions)
    {
        if (!empty($realmOptions)) {
            $this->setAuthAndUrl($realmOptions);
        } else {
// setting up the credentials
            if (getenv('DOCKER_DEVELOP_BOX') != false) {
                $this->url = self::API_URL_SANDBOX;
            } else {
                $this->url = self::API_URL_LIVE;
            }
            $this->auth = array(
                'grant_type' => 'client_credentials',
                'client_id' => getenv('CD_CLIENT_ID'),
                'client_secret' => getenv('CD_CLIENT_SECRET'),
            );
        }
    }

    /**
     * @name setAuthAndUrl
     *
     * @param array $realmOptions
     * @return void
     */
    private function setAuthAndUrl($realmOptions = array())
    {
        if (isset($realmOptions['auth'])) {
            $this->auth = $realmOptions['auth'];
        }

        if (isset($realmOptions['url'])) {
            $this->url = $realmOptions['url'];
        }
    }

    /**
     * @name getBearer
     *
     * @return string
     */
    public function getBearer()
    {
        $token = self::getSavedValidToken();
        if ($token) {
            return $token;
        }

        // perform the curl call
        $url = $this->url;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->auth));
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->curlHeaders);

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($curl);
        $info = curl_getinfo($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (!empty($response)) {
            $response = @json_decode($response, true);

            if ($response && isset($response['access_token'])) {
                BackupHelper::saveBackup('cd-access', 'token', array('token' => $response['access_token'], 'expires' => time()+$response['expires_in']-30));

                return $response['access_token'];
            }
        }


        return false;
    }

    private static function getSavedValidToken()
    {
        $data = BackupHelper::getBackup('cd-access', 'token');

        if ($data) {
            if (!isset($data->expires) || !isset($data->token)) {
                return false;
            }
            if (time() > $data->expires) {
                return false;
            }

            return $data->token;
        }

        return false;
    }
}