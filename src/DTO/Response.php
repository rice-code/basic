<?php


namespace Rice\Basic\Dto;


class Response extends DTOBase
{
    private $success;

    private $errCode;

    private $errMessage;

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function setSuccess(bool $success)
    {
        $this->success = $success;
    }

    public function getErrCode(): string
    {
        return $this->errCode;
    }

}