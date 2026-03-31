<?php
namespace HostEuropeGmbh\HosteuropeTemplate\Helper\SSO;

use HostEuropeGmbh\HosteuropeTemplate\Helper\CD\CustomerDetailsRead;

class Handler {
    /**
     * @var string
     */
    const AUTH_COOKIE_NAME = 'auth_idp';

    /**
     * @name loginHtmlRender
     * @return string
     */
    public function loginHtmlRender()
    {
        $html = '';
        $loginData = false;
        $shopperId = false;

        $cookieToken = $this->getCookieToken();

        if ($cookieToken) {
            $cookieData = $this->verifySignature($cookieToken);

            if (isset($cookieData['e2s']['shopperId'])) {
                $shopperId = $cookieData['e2s']['shopperId'];
            } else if (isset($cookieData['shopperId'])) {
                $shopperId = $cookieData['shopperId'];
            }

            if ($shopperId) {
                if (!$loginData = $GLOBALS["TSFE"]->fe_user->getKey("ses","login_data_".$shopperId)) {
                    $loginData = CustomerDetailsRead::getInstance()->search($shopperId);
                    if ($loginData) {
                        $GLOBALS['TSFE']->fe_user->setKey("ses", "login_data_" . $shopperId, $loginData);
                    }
                }
            }
        }

        $html .= '<i>Login</i>';
        $html .= $this->subMenuHtml(false);

        return $html;
    }

    public function loginAjaxRequest()
    {

      $loginData = false;
      $shopperId = false;

      $cookieToken = $this->getCookieToken();

      if ($cookieToken) {
        $cookieData = $this->verifySignature($cookieToken);

        if (isset($cookieData['e2s']['shopperId'])) {
          $shopperId = $cookieData['e2s']['shopperId'];
        } else if (isset($cookieData['shopperId'])) {
          $shopperId = $cookieData['shopperId'];
        }

        if ($shopperId) {
          if (!$loginData = $GLOBALS["TSFE"]->fe_user->getKey("ses","login_data_".$shopperId)) {
            $loginData = CustomerDetailsRead::getInstance()->search($shopperId);
            if ($loginData) {
              $GLOBALS['TSFE']->fe_user->setKey("ses", "login_data_" . $shopperId, $loginData);
            }
          }
        }
      }

      if ($loginData) {
        $name = $this->getCustomerName($loginData);

        $shortname = mb_strlen($name) > 7 ? mb_substr($name, 0 , 7).'...' : $name;

        return array(
            'name' => $name,
            'shortname' => $shortname
        );
      }

      return false;
    }

    private function getCustomerName($data)
    {
        $name = false;

        if (isset($data['firstName']) && $data['firstName']) {
            $name = $data['firstName'];
        } else if (isset($data['lastName']) && $data['lastName']) {
            $name = $data['lastName'];
        } else if(isset($data['userName']) && $data['userName']) {
            $name = $data['userName'];
        }

        return $name;
    }

    private function subMenuHtml($login = false)
    {
        if (getenv('DOCKER_DEVELOP_BOX') != FALSE) {
            $menuHtml = '<div class="menu">
                <div class="item"><a href="https://dockerdev.kis.hosteurope.de/" target="_blank" title="KIS Login">KIS Login</a></div>
                <div class="item"><a href="https://webmailer.hosteurope.de/" target="_blank" title="Webmailer Login">Webmailer Login</a></div>
                <div class="item"><a href="http://portal.office.com/" target="_blank" title="Office 365 Login">Office 365 Login</a></div>
                <div class="item"><a href="https://control.hosteurope.de/" target="_blank" title="ControlPanel">ControlPanel</a></div>';
        } else {
            $menuHtml = '<div class="menu">
                <div class="item"><a href="https://kis.hosteurope.de/" target="_blank" title="KIS Login">KIS Login</a></div>
                <div class="item"><a href="https://webmailer.hosteurope.de/" target="_blank" title="Webmailer Login">Webmailer Login</a></div>
                <div class="item"><a href="http://portal.office.com/" target="_blank" title="Office 365 Login">Office 365 Login</a></div>
                <div class="item"><a href="https://control.hosteurope.de/" target="_blank" title="ControlPanel">ControlPanel</a></div>';
        }

        if ($login) {
            $currentUri = \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REQUEST_URI');
            list($logoutLink) = explode('?',$currentUri);

            $menuHtml .= '<div class="item"><a href="'.$logoutLink.'?logout=1" title="Logout">Logout</a></div>';
        }

        $menuHtml .= '</div>';

        return $menuHtml;
    }

    private function getCookieToken()
    {
        return isset($_COOKIE[self::AUTH_COOKIE_NAME]) ? $_COOKIE[self::AUTH_COOKIE_NAME]: false;
    }

    /**
     * @name verifySignature
     *
     * @param string $jwtToken
     * @return boolean
     */
    private function verifySignature($jwtToken)
    {
        $jwtObject = new Jwt();

        // Check if token exist in the session it means that is already validated
        if ($jwtToken == $GLOBALS["TSFE"]->fe_user->getKey("ses","auth_idp")) {
            return $jwtObject->decodePayload($jwtToken);
        }

        $dataHeader = $jwtObject->decodeHeader($jwtToken);

        if (! $dataHeader['kid']) {
            return false;
        }

        $apiClass = new Request('key/' . $dataHeader['kid']);
        $apiClass->setTryOuts(3);
        $response = $apiClass->send();

        if ( ! empty($response) && isset($response['data']) && isset($response['data']['n'])) {
            $publicKey = Jwt::createPemFromModulusAndExponent($response['data']['n'], $response['data']['e']);

            $data = $jwtObject->decode($jwtToken, $publicKey);

            if ($data) {
                $GLOBALS['TSFE']->fe_user->setKey("ses","auth_idp", $jwtToken);
            }

            return $data;
        }

        return false;
    }
}