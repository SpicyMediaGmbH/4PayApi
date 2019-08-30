<?php

namespace FourPayApi;

use EndyJasmi\Cuid;
use FourPayApi\Responses\BaseResponse;
use FourPayApi\Responses\BulkBillResponse;
use FourPayApi\Responses\RefundResponse;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use FourPayApi\Responses\BillResponse;
use FourPayApi\Responses\GetMnoResponse;
use FourPayApi\Responses\SmsAuthorizeResponse;
use FourPayApi\Responses\StopSubscriptionResponse;
use FourPayApi\Responses\WapAuthorizeResponse;
use FourPayApi\Responses\WebAuthorizeResponse;
use FourPayApi\Responses\WebValidatePinResponse;

class FourPayApiClient
{
    /** @var string */
    private $servicename;
    /** @var string */
    private $password;
    /** @var SerializerInterface */
    private $serializer;

    /**
     * SmsPaymentClient constructor.
     * @param string $servicename
     * @param string $password
     */
    public function __construct(string $servicename, string $password)
    {
        $this->servicename = $servicename;
        $this->password = $password;
        $this->serializer = SerializerBuilder::create()->build();
    }

    public function authorizeSms(int $amount, string $type, string $msisdn, ?string $mccmnc, string $callbackurl, string $stopsubcallbackurl, ?string $txt1 = null, ?string $txt2=null, ?string $txt3=null, bool $details = false): SmsAuthorizeResponse
    {
        $response = API::getInstance($this->servicename, $this->password)->authorizeSms($amount,$type,$msisdn, $mccmnc,$callbackurl,$stopsubcallbackurl, $txt1, $txt2, $txt3, $details);
        if (!$response) {
            throw new \Exception('shit happened');
        }

        return $this->serializer->deserialize($response->getBody()->getContents(),SmsAuthorizeResponse::class,'xml');
    }

    public function authorizeWeb(int $amount, string $type, string $msisdn, string $okurl, string $errorurl, ?string $mccmnc, string $stopsubcallbackurl, ?string $txt1 = null, ?string $txt2=null, ?string $txt3=null, bool $details = false): WebAuthorizeResponse
    {
        $response = API::getInstance($this->servicename, $this->password)->authorizeWeb($amount, $type, $msisdn, $okurl, $errorurl, $mccmnc, $stopsubcallbackurl, $txt1, $txt2, $txt3, $details);
        if (!$response) {
            throw new \Exception('shit happened');
        }

        return $this->serializer->deserialize($response->getBody()->getContents(),WebAuthorizeResponse::class,'xml');
    }

    public function validateWebPin(string $txid, string $pin, ?string $txt1 = null, ?string $txt2=null, ?string $txt3=null, bool $details = false): WebValidatePinResponse
    {
        $response = API::getInstance($this->servicename, $this->password)->validateWebPin($txid, $pin);
        if (!$response) {
            throw new \Exception('shit happened');
        }

        return $this->serializer->deserialize($response->getBody()->getContents(),WebValidatePinResponse::class,'xml');
    }

    public function authorizeWap(int $amount, string $type, ?string $msisdn, ?string $mccmnc, string $okurl, string $errorurl, string $stopsubcallbackurl, string $description, string $gtc, string $imprint, string $contact, string $faq, ?string $txt1 = null, ?string $txt2=null, ?string $txt3=null, bool $details = false): WapAuthorizeResponse
    {
        $response = API::getInstance($this->servicename, $this->password)->authorizeWap($amount, $type, $msisdn, $mccmnc, $okurl, $errorurl, $stopsubcallbackurl, $description, $gtc, $imprint, $contact, $faq, $txt1, $txt2, $txt3, $details);
        if (!$response) {
            throw new \Exception('shit happened');
        }

        return $this->serializer->deserialize($response->getBody()->getContents(),WapAuthorizeResponse::class,'xml');
    }

    public function bill(string $txid, int $amount, bool $details = false): BillResponse
    {
        $response = API::getInstance($this->servicename, $this->password)->bill($txid, $amount, $details);
        if (!$response) {
            throw new \Exception('shit happened');
        }

        return $this->serializer->deserialize($response->getBody()->getContents(),BillResponse::class,'xml');
    }

    public function stopSubscription(string $txid, bool $details = false): StopSubscriptionResponse
    {
        $response = API::getInstance($this->servicename, $this->password)->stopSubscription($txid, $details);
        if (!$response) {
            throw new \Exception('shit happened');
        }

        return $this->serializer->deserialize($response->getBody()->getContents(),StopSubscriptionResponse::class,'xml');
    }

    public function getMno(string $txid, bool $details = false): GetMnoResponse
    {
        $response = API::getInstance($this->servicename, $this->password)->getMno($txid, $details);
        if (!$response) {
            throw new \Exception('shit happened');
        }

        return $this->serializer->deserialize($response->getBody()->getContents(),GetMnoResponse::class,'xml');
    }

    public function refund(string $txid, ?int $amount = null, bool $details = false): RefundResponse
    {
        $response = API::getInstance($this->servicename, $this->password)->refund($txid, $amount,$details);
        if (!$response) {
            throw new \Exception('shit happened');
        }

        return $this->serializer->deserialize($response->getBody()->getContents(),RefundResponse::class,'xml');
    }

    public function bulkbill(array $txids, string $callbackUrl, ?string $bulkuid = null ,bool $details = false): BulkBillResponse
    {
        if (count($txids) > 500) {
            throw new \InvalidArgumentException('txids can not have more than 500 ids');
        }

        if (!$bulkuid) {
            $bulkuid = Cuid::make();
        }

        $response = API::getInstance($this->servicename, $this->password)->bulkBill(implode(';',$txids),$bulkuid,$callbackUrl,$details);
        if (!$response) {
            throw new \Exception('shit happened');
        }

        return $this->serializer->deserialize($response->getBody()->getContents(),BulkBillResponse::class,'xml');
    }

    public function responseToJson(BaseResponse $response)
    {
        return $this->serializer->serialize($response,'json');
    }
}
