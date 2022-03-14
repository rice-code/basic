<?php


namespace Rice\Basic\dto;


class Response extends DTO
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