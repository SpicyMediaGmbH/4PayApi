<?php


namespace FourPayApi\Responses;

use JMS\Serializer\Annotation as Serializer;

class SmsAuthorizeResponse extends BaseResponse
{
    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $txid;

    /**
     * @var string | null
     * @Serializer\Type("string")
     */
    private $provider;
    /**
     * @var string | null
     * @Serializer\Type("string")
     */
    private $mcc;
    /**
     * @var string | null
     * @Serializer\Type("string")
     */
    private $mnc;
    /**
     * @var string | null
     * @Serializer\Type("string")
     */
    private $mtid;

    /**
     * @return string
     */
    public function getTxid(): string
    {
        return $this->txid;
    }

    /**
     * @return string|null
     */
    public function getProvider(): ?string
    {
        return $this->provider;
    }

    /**
     * @return string|null
     */
    public function getMcc(): ?string
    {
        return $this->mcc;
    }

    /**
     * @return string|null
     */
    public function getMnc(): ?string
    {
        return $this->mnc;
    }

    /**
     * @return string|null
     */
    public function getMtid(): ?string
    {
        return $this->mtid;
    }
}
