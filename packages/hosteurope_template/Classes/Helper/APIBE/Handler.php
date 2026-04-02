<?php
namespace HostEuropeGmbh\HosteuropeTemplate\Helper\APIBE;

class Handler {

    protected $url = 'https://api-be.intern.hosteurope.de/';

    protected $api;

    protected $auth = array();

    public function __construct()
    {
        $this->api = new RestApi();
        $this->auth = array(
            'username' => getenv('APIBE_USERNAME'),
            'password' => getenv('APIBE_PASSWORD'),
        );
    }


    public function sendContactMail($to = '', $from = '', $subject = '', $body ='')
    {
        if (empty($to) || empty($from) || empty($subject) || empty($body)) {
            return false;
        }

        $requestHeaders = array(
            'Authorization: Basic ' . base64_encode($this->auth['username'] . ':' . $this->auth['password']),
            'Content-Type: application/json',
            'Accept: application/json'
        );

        $requestData = array(
            'action' => 'sendContactEmail',
            'to' => $to,
            'from' => $from,
            'subject' => $subject,
            'body' => $body
        );

        $this->api->setUrl($this->url . 'homepage2/newsletter/?mode');
        $this->api->setApiOptions(array(
            'tryOuts' => 2,
            'requestMethod' => 'PUT',
            'requestData' => json_encode($requestData),
            'requestHeaders' => $requestHeaders
        ));

        $result = $this->api->send();

        if ( ! empty($result) && $result['success'] == true) {
            return true;
        }

        return false;
    }
}