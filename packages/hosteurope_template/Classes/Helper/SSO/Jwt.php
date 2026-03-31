<?php
namespace HostEuropeGmbh\HosteuropeTemplate\Helper\SSO;

/**
 * @link https://github.com/bshaffer/oauth2-server-php/blob/develop/src/OAuth2/Encryption/Jwt.php
 */

class Jwt
{
    /**
     * @param $payload
     * @param $key
     * @param string $algo
     * @return string
     */
    public function encode($payload, $key, $algo = 'HS256')
    {
        $header = $this->generateJwtHeader($payload, $algo);
        $segments = array(
            $this->urlSafeB64Encode(json_encode($header)),
            $this->urlSafeB64Encode(json_encode($payload))
        );

        $signing_input = implode('.', $segments);
        $signature = $this->sign($signing_input, $key, $algo);
        $segments[] = $this->urlsafeB64Encode($signature);
        return implode('.', $segments);
    }

    /**
     * @param string      $jwt
     * @param null        $key
     * @param array|bool  $allowedAlgorithms
     * @return bool|mixed
     */
    public function decode($jwt, $key = null, $allowedAlgorithms = true)
    {
        if (!strpos($jwt, '.')) {
            return false;
        }
        $tks = explode('.', $jwt);
        if (count($tks) != 3) {
            return false;
        }

        list($headb64, $payloadb64, $cryptob64) = $tks;

        if (null === ($header = json_decode(self::urlSafeB64Decode($headb64), true))) {
            return false;
        }

        if (null === $payload = json_decode(self::urlSafeB64Decode($payloadb64), true)) {
            return false;
        }

        $sig = self::urlSafeB64Decode($cryptob64);

        if ((bool) $allowedAlgorithms) {
            if (!isset($header['alg'])) {
                return false;
            }
            // check if bool arg supplied here to maintain BC
            if (is_array($allowedAlgorithms) && !in_array($header['alg'], $allowedAlgorithms)) {
                return false;
            }
            if (!$this->verifySignature($sig, "$headb64.$payloadb64", $key, $header['alg'])) {
                return false;
            }
        }

        return $payload;
    }

    /**
     *
     * Create a public key represented in PEM format from RSA modulus and exponent information
     *
     * @param string $n the RSA modulus encoded in Base64
     * @param string $e the RSA exponent encoded in Base64
     * @return string the RSA public key represented in PEM format
     */
    public static function createPemFromModulusAndExponent($n, $e)
    {
        $modulus = self::urlsafeB64Decode($n);
        $publicExponent = self::urlsafeB64Decode($e);
        $components = array(
            'modulus' => pack('Ca*a*', 2, self::encodeLength(strlen($modulus)), $modulus),
            'publicExponent' => pack('Ca*a*', 2, self::encodeLength(strlen($publicExponent)), $publicExponent)
        );
        $RSAPublicKey = pack(
            'Ca*a*a*',
            48,
            self::encodeLength(strlen($components['modulus']) + strlen($components['publicExponent'])),
            $components['modulus'],
            $components['publicExponent']
        );
        // sequence(oid(1.2.840.113549.1.1.1), null)) = rsaEncryption.
        $rsaOID = pack('H*', getenv('PACK_JWT_SEQUENCE')); // hex version of MA0GCSqGSIb3DQEBAQUA
        $RSAPublicKey = chr(0) . $RSAPublicKey;
        $RSAPublicKey = chr(3) . self::encodeLength(strlen($RSAPublicKey)) . $RSAPublicKey;
        $RSAPublicKey = pack(
            'Ca*a*',
            48,
            self::encodeLength(strlen($rsaOID . $RSAPublicKey)),
            $rsaOID . $RSAPublicKey
        );
        $RSAPublicKey = "-----BEGIN PUBLIC KEY-----\r\n" .
            chunk_split(base64_encode($RSAPublicKey), 64) .
            '-----END PUBLIC KEY-----';

        return $RSAPublicKey;
    }

    /**
     * DER-encode the length
     *
     * DER supports lengths up to (2**8)**127, however, we'll only support lengths up to (2**8)**4
     *
     * @access private
     * @param int $length
     * @return string
     */
    private static function encodeLength($length)
    {
        if ($length <= 0x7F) {
            return chr($length);
        }

        $temp = ltrim(pack('N', $length), chr(0));
        return pack('Ca*', 0x80 | strlen($temp), $temp);
    }

    /**
     * @param string      $jwt
     * @return bool|mixed
     */
    public function decodeHeader($jwt)
    {
        if (!strpos($jwt, '.')) {
            return false;
        }
        $tks = explode('.', $jwt);
        if (count($tks) != 3) {
            return false;
        }

        list($headb64) = $tks;

        if (null === ($header = json_decode(self::urlSafeB64Decode($headb64), true))) {
            return false;
        }

        return $header;
    }

    /**
     * @param string      $jwt
     * @return bool|mixed
     */
    public function decodePayload($jwt)
    {
        if (!strpos($jwt, '.')) {
            return false;
        }
        $tks = explode('.', $jwt);
        if (count($tks) != 3) {
            return false;
        }

        list($headb64, $payloadb64) = $tks;

        if (null === $payload = json_decode(self::urlSafeB64Decode($payloadb64), true)) {
            return false;
        }

        return $payload;
    }

    /**
     * @param $signature
     * @param $input
     * @param $key
     * @param string $algo
     * @return bool
     * @throws InvalidArgumentException
     */
    private function verifySignature($signature, $input, $key, $algo = 'HS256')
    {
        // use constants when possible, for HipHop support
        switch ($algo) {
            case'HS256':
            case'HS384':
            case'HS512':
                return $this->hash_equals(
                    $this->sign($input, $key, $algo),
                    $signature
                );
            case 'RS256':
                return openssl_verify($input, $signature, $key, 'sha256')  === 1;
            case 'RS384':
                return @openssl_verify($input, $signature, $key, defined('OPENSSL_ALGO_SHA384') ? OPENSSL_ALGO_SHA384 : 'sha384') === 1;
            case 'RS512':
                return @openssl_verify($input, $signature, $key, defined('OPENSSL_ALGO_SHA512') ? OPENSSL_ALGO_SHA512 : 'sha512') === 1;
            default:
                throw new \Exception("Unsupported or invalid signing algorithm.");
        }
    }

    /**
     * @param $input
     * @param $key
     * @param string $algo
     * @return string
     * @throws Exception
     */
    private function sign($input, $key, $algo = 'HS256')
    {
        switch ($algo) {
            case 'HS256':
                return hash_hmac('sha256', $input, $key, true);
            case 'HS384':
                return hash_hmac('sha384', $input, $key, true);
            case 'HS512':
                return hash_hmac('sha512', $input, $key, true);
            case 'RS256':
                return $this->generateRSASignature($input, $key, 'sha256');
            case 'RS384':
                return $this->generateRSASignature($input, $key, defined('OPENSSL_ALGO_SHA384') ? OPENSSL_ALGO_SHA384 : 'sha384');
            case 'RS512':
                return $this->generateRSASignature($input, $key, defined('OPENSSL_ALGO_SHA512') ? OPENSSL_ALGO_SHA512 : 'sha512');
            default:
                throw new \Exception("Unsupported or invalid signing algorithm.");
        }
    }

    /**
     * @param $input
     * @param $key
     * @param string $algo
     * @return mixed
     * @throws Exception
     */
    private function generateRSASignature($input, $key, $algo)
    {
        if (!openssl_sign($input, $signature, $key, $algo)) {
            throw new \Exception("Unable to sign data.");
        }

        return $signature;
    }

    /**
     * @param string $data
     * @return string
     */
    public function urlSafeB64Encode($data)
    {
        $b64 = base64_encode($data);
        $b64 = str_replace(array('+', '/', "\r", "\n", '='),
            array('-', '_'),
            $b64);
        return $b64;
    }

    /**
     * @param string $b64
     * @return mixed|string
     */
    public static function urlSafeB64Decode($b64)
    {
        $b64 = str_replace(array('-', '_'),
            array('+', '/'),
            $b64);
        return base64_decode($b64);
    }

    /**
     * Override to create a custom header
     */
    protected function generateJwtHeader($payload, $algorithm)
    {
        return array(
            'typ' => 'JWT',
            'alg' => $algorithm,
        );
    }

    /**
     * @param string $a
     * @param string $b
     * @return bool
     */
    protected function hash_equals($a, $b)
    {
        if (function_exists('hash_equals')) {
            return hash_equals($a, $b);
        }
        $diff = strlen($a) ^ strlen($b);
        for ($i = 0; $i < strlen($a) && $i < strlen($b); $i++) {
            $diff |= ord($a[$i]) ^ ord($b[$i]);
        }
        return $diff === 0;
    }
}