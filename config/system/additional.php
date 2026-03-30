<?php

setlocale( LC_TIME, "de_DE.UTF-8" );

$hostname           = $_SERVER['HTTP_HOST'];


switch ( $hostname ) {
    default: 
    case in_array( $hostname,
        array(
            'hosteurope.de',
            'www.hosteurope.de',
            'stage2.www.hosteurope.de',
            'server30.www.hosteurope.de',
            'server31.www.hosteurope.de',
            'ww2.hosteurope.de',
            'faqedit.hosteurope.de'
        ) ):

        define( 'PMAPI_CID', 38499 ); // Company ID
        define( 'PMAPI_UID', 13311 ); // User ID
        define( 'PMAPI_HASH', '' ); // API access hash
        define( 'PMAPI_LIST_ID', 72735 ); //
        define( 'PMAPI_LIST_ID_BLOG', 72736 ); //


        $GLOBALS['TYPO3_CONF_VARS']['BE']['lockSSL'] = 0;

        /**
         * Adding this 3 for test only
         */
        $GLOBALS['TYPO3_CONF_VARS']['BE']['lockIP'] = 0;
        $GLOBALS['TYPO3_CONF_VARS']['BE']['sessionTimeout'] = 3600 * 24;
        $GLOBALS['TYPO3_CONF_VARS']['BE']['lockHashKeyWords'] = '';

        /**
         * Ip restrictions for Heritage VPN
         */
        if ($hostname == 'faqedit.hosteurope.de') {
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['reverseProxyIP'] = '*';
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['reverseProxyHeaderMultiValue'] = 'last';
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['reverseProxySSL'] = '*';
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['trustedHostsPattern'] = 'faqedit.hosteurope.de';
            $GLOBALS['TYPO3_CONF_VARS']['BE']['IPmaskList'] = '10.83.*.*,10.38.*.*,62.138.161.*,132.148.54.*,160.153.26.*,166.62.16.*';
        }

        if ($hostname == 'storefront.hosteurope.de') {
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['reverseProxyIP'] = '*';
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['reverseProxyHeaderMultiValue'] = 'last';
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['reverseProxySSL'] = '*';
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['trustedHostsPattern'] = 'storefront.hosteurope.de';
            $GLOBALS['TYPO3_CONF_VARS']['BE']['IPmaskList'] = '10.83.*.*,10.38.*.*,62.138.161.*,132.148.54.*,160.153.26.*,166.62.16.*';
        }

        #MYSQL
        $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['charset'] = 'utf8mb4';
        $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['driver'] = 'mysqli';
        $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['dbname'] = getenv('DATABASE_NAME') !== FALSE ?  getenv('DATABASE_NAME') : 'typo3';
        $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['host']     = getenv('DATABASE_HOST') !== FALSE ?  getenv('DATABASE_HOST') : 'localhost';
        $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['password'] = getenv('DATABASE_PASSWORD') !== FALSE ?  getenv('DATABASE_PASSWORD') : '';
        $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['user'] = getenv('DATABASE_USERNAME') !== FALSE ?  getenv('DATABASE_USERNAME') : 'root';

        #DEBUG
        $GLOBALS['TYPO3_CONF_VARS']['FE']['debug']                 = '0';
        $GLOBALS['TYPO3_CONF_VARS']['BE']['debug']                 = '0';
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['devIPmask']            = '';
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['displayErrors']        = '0';
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['enableDeprecationLog'] = '';
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['sqlDebug']             = '0';
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['systemLogLevel']       = '2';
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['clearCacheSystem']     = '1';
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['features']['security.backend.enforceReferrer'] = 0;

        $GLOBALS['TYPO3_CONF_VARS']['cache_hash'] = array(
            'frontend' => 'TYPO3\CMS\Core\Cache\Backend\SimpleFileBackend',
            'backend'  => 'TYPO3\CMS\Core\Cache\Backend\VariableFrontend',
            'options'  => array(
                'defaultLifetime' => '0',
            ),
        );


        $GLOBALS['TYPO3_CONF_VARS']['LOG']['writerConfiguration'] = array(
            // configuration for ERROR level log entries
            \TYPO3\CMS\Core\Log\LogLevel::ERROR => array(
                // add a FileWriter
                'TYPO3\\CMS\\Core\\Log\\Writer\\PhpErrorLogWriter' => array()
            ),
            /*
            \TYPO3\CMS\Core\Log\LogLevel::INFO => array(
                // add a FileWriter
                'TYPO3\\CMS\\Core\\Log\\Writer\\PhpErrorLogWriter' => array()
            )
            */
        );

        \TYPO3\CMS\Core\Utility\GeneralUtility::flushInternalRuntimeCaches();

        break;

    case in_array($hostname,
        array(
            'dockerlocal.hosteurope.de',
            'dockerlocal.hosteurope2.de'
        )):

        define( 'PMAPI_CID', 38499 ); // Company ID
        define( 'PMAPI_UID', 13311 ); // User ID
        define( 'PMAPI_HASH', '' ); // API access hash
        define( 'PMAPI_LIST_ID', 72735 ); //
        define( 'PMAPI_LIST_ID_BLOG', 72736 ); //

        #DEBUG
        $GLOBALS['TYPO3_CONF_VARS']['FE']['debug']                 = '1';
        $GLOBALS['TYPO3_CONF_VARS']['BE']['debug']                 = '1';
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['devIPmask']            = '172.20.0.10';
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['displayErrors']        = '1';
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['enableDeprecationLog'] = '';
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['sqlDebug']             = '1';
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['systemLogLevel']       = '1';
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['clearCacheSystem']     = '1';

        $GLOBALS['TYPO3_CONF_VARS']['BE']['lockSSL'] = 0;

        #MYSQL
        $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['charset'] = 'utf8mb4';
        $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['driver'] = 'mysqli';
        $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['dbname'] = getenv('DATABASE_NAME') !== FALSE ?  getenv('DATABASE_NAME') : 'typo3';
        $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['host']     = getenv('DATABASE_HOST') !== FALSE ?  getenv('DATABASE_HOST') : 'localhost';
        $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['password'] = getenv('DATABASE_PASSWORD') !== FALSE ?  getenv('DATABASE_PASSWORD') : '';
        $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['user'] = getenv('DATABASE_USERNAME') !== FALSE ?  getenv('DATABASE_USERNAME') : 'root';

        $GLOBALS['TYPO3_CONF_VARS']['cache_hash'] = array(
            'frontend' => 'TYPO3\CMS\Core\Cache\Backend\SimpleFileBackend',
            'backend'  => 'TYPO3\CMS\Core\Cache\Backend\VariableFrontend',
            'options'  => array(
                'defaultLifetime' => '0',
            ),
        );

        $GLOBALS['TYPO3_CONF_VARS']['LOG']['writerConfiguration'] = array(
            // configuration for ERROR level log entries
            \TYPO3\CMS\Core\Log\LogLevel::ERROR => array(
                // add a FileWriter
                'TYPO3\\CMS\\Core\\Log\\Writer\\FileWriter' => array(
                    // configuration for the writer
                    'logFile' => 'typo3temp/logs/typo3_error_'.date('Y-m-d').'.log'
                ),
            ),
            \TYPO3\CMS\Core\Log\LogLevel::INFO => array(
                // add a FileWriter
                'TYPO3\\CMS\\Core\\Log\\Writer\\FileWriter' => array(
                    // configuration for the writer
                    'logFile' => 'typo3temp/logs/typo3_info_'.date('Y-m-d').'.log'
                ),
            )
        );

        break;
}

if (isset($_GET['logout'])) {
    $currentUrl = \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL');
    list($redirectUrl) = explode('?', $currentUrl);

    unset($_COOKIE['auth_idp']);
    setcookie('auth_idp', '', time() - 3600, '/', '.hosteurope.de', true, true);
    header('Location: '.$redirectUrl);
}
