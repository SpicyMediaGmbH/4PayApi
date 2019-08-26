<?php


namespace Responses;


use JMS\Serializer\Annotation as Serializer;

abstract class BaseResponse
{
    /**
     * @var int
     * @Serializer\Type("integer")
     */
    private $statuscode;
    /**
     * @var string
     * @Serializer\Type("text")
     */
    private $statustext;

    /**
     * @var string | null
     * @Serializer\Type("string")
     */
    private $bookingmessage;


    /**
     * @return int
     */
    public function getStatuscode(): int
    {
        return $this->statuscode;
    }

    /**
     * @return string
     */
    public function getStatustext(): string
    {
        return $this->statustext;
    }

    /**
     * @return string|null
     */
    public function getBookingmessage(): ?string
    {
        return $this->bookingmessage;
    }

    public function isSuccess(): bool
    {
        return $this->statuscode === 100 || $this->statuscode === 600;
    }
}
