<?php


namespace Responses;


use JMS\Serializer\Annotation as Serializer;

class BillResponse extends BaseResponse
{
    /**
     * @var string | null
     * @Serializer\Type("string")
     */
    private $msisdn;
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
     * @return string|null
     */
    public function getMsisdn(): ?string
    {
        return $this->msisdn;
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
}
