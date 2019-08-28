<?php

namespace FourPayApi;

use GuzzleHttp\Psr7\Request;
use Http\Adapter\Guzzle6\Client;
use Psr\Http\Message\ResponseInterface;

class API
{

    private const BASE_URL = 'https://www.mobilepay.de/mobilepay/mobilepay?';
    /** @var Client */
    private $adapter;
    /** @var API */
    private static $instance;
    /** @var string */
    private $servicename;
    /** @var string */
    private $password;

    private function __construct(string $servicename, string $password)
    {
        $this->servicename = $servicename;
        $this->password = $password;
        $this->adapter = Client::createWithConfig(['timeout' => 5]);
    }

    public static function getInstance(string $servicename, string $password): API
    {
        if (!self::$instance) {
            self::$instance = new API($servicename, $password);
        }
        return self::$instance;
    }

    public function authorizeSms(int $amount, string $type, string $msisdn, ?string $mccmnc, string $callbackurl, string $stopsubcallbackurl, ?string $txt1 = null, ?string $txt2=null, ?string $txt3=null, bool $details = false)
    {
        return $this->makeGetRequest([
            'command' => 'smsauthorize',
            'servicename' => $this->servicename,
            'password' => $this->password,
            'amount' => $amount,
            'type' => $type,
            'msisdn' => $msisdn,
            'mccmnc' => $mccmnc,
            'callbackurl' => $callbackurl,
            'stopsubcallbackurl' => $stopsubcallbackurl,
            'details' => $details,
            'txt1' => $txt1,
            'txt2' => $txt2,
            'txt3' => $txt3,
        ]);
    }

    public function authorizeWeb(int $amount, string $type, string $msisdn, string $okurl, string $errorurl, ?string $mccmnc, string $stopsubcallbackurl, ?string $txt1 = null, ?string $txt2=null, ?string $txt3=null, bool $details = false)
    {
        return $this->makeGetRequest([
            'command' => 'webauthorize',
            'servicename' => $this->servicename,
            'password' => $this->password,
            'amount' => $amount,
            'type' => $type,
            'msisdn' => $msisdn,
            'mccmnc' => $mccmnc,
            'details' => $details,
            'okurl' => $okurl,
            'errorurl' => $errorurl,
            'stopsubcallbackurl' => $stopsubcallbackurl,
            'txt1' => $txt1,
            'txt2' => $txt2,
            'txt3' => $txt3,
        ]);
    }

    public function validateWebPin(string $txid, string $pin, ?string $txt1 = null, ?string $txt2=null, ?string $txt3=null, bool $details = false)
    {
        return $this->makeGetRequest([
            'command' => 'webvalidatepin',
            'servicename' => $this->servicename,
            'password' => $this->password,
            'txid' => $txid,
            'pin' => $pin,
            'details' => $details,
            'txt1' => $txt1,
            'txt2' => $txt2,
            'txt3' => $txt3,
        ]);
    }

    public function authorizeWap(int $amount, string $type, string $msisdn, ?string $mccmnc, string $okurl, string $errorurl, string $stopsubcallbackurl, string $description, string $gtc, string $imprint, string $contact, string $faq, ?string $txt1 = null, ?string $txt2=null, ?string $txt3=null, bool $details = false)
    {
        return $this->makeGetRequest([
            'command' => 'wapauthorize',
            'servicename' => $this->servicename,
            'password' => $this->password,
            'type' => $type,
            'amount' => $amount,
            'msisdn' => $msisdn,
            'mccmnc' => $mccmnc,
            'okurl' => $okurl,
            'errorurl' => $errorurl,
            'stopsubcallbackurl' => $stopsubcallbackurl,
            'detail' => $details,
            'description' => $description,
            'gtc' => $gtc,
            'imprint' => $imprint,
            'contact' => $contact,
            'faq' => $faq,
            'txt1' => $txt1,
            'txt2' => $txt2,
            'txt3' => $txt3,
        ]);
    }

    public function bill(string $txid, int $amount, bool $details = false)
    {
        return $this->makeGetRequest([
                'command' => 'bill',
                'servicename' => $this->servicename,
                'password' => $this->password,
                'txid' => $txid,
                'amount'=> $amount,
                'detail' => $details
            ]
        );
    }

    public function stopSubscription(string $txid, bool $details = false)
    {
        return $this->makeGetRequest([
                'command' => 'stopsubscription',
                'servicename' => $this->servicename,
                'password' => $this->password,
                'txid' => $txid,
                'detail' => $details
            ]
        );
    }

    public function getMno(string $txid, bool $details = false)
    {
        return $this->makeGetRequest([
                'command' => 'getmno',
                'servicename' => $this->servicename,
                'password' => $this->password,
                'txid' => $txid,
                'detail' => $details
            ]
        );
    }

    public function refund(string $txid, ?int $amount = null, bool $details = false)
    {
        return $this->makeGetRequest([
                'command' => 'refund',
                'servicename' => $this->servicename,
                'password' => $this->password,
                'txid' => $txid,
                'detail' => $details,
                'amount'=> $amount
            ]
        );
    }

    public function bulkBill(string $txidlist, string $bulkId, string $callbackurl, bool $details = false)
    {
        return $this->makeGetRequest([
                'command' => 'billbulk',
                'servicename' => $this->servicename,
                'password' => $this->password,
                'txidlist' => $txidlist,
                'detail' => $details,
                'bulkuid'=> $bulkId,
                'callbackurl' => $callbackurl
            ]
        );
    }

    private function makeGetRequest(array $params): ?ResponseInterface
    {
        $request = new Request('GET', self::BASE_URL.http_build_query($params));
        try {
            return $this->adapter->sendRequest($request);
        } catch (\Http\Client\Exception $e) {
            echo $e->getTraceAsString();
        } catch (\Exception $e) {
            echo $e->getTraceAsString();
        }

        return null;
    }
}
