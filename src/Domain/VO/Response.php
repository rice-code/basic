<?php

namespace Rice\Basic\Domain\VO;

use Rice\Basic\Infrastructure\Enum\ReturnCode\ReturnCodeEnum;

class Response extends BaseVO
{
    protected bool $success = false;
    protected string $errCode = ReturnCodeEnum::OK;
    protected string $errMessage = '';
    protected array $data = [];

    public function getSuccess(): bool
    {
        return $this->success;
    }

    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    public function setErrCode(string $errCode): void
    {
        $this->errCode = $errCode;
    }

    public function getErrCode(): string
    {
        return $this->errCode;
    }

    public function getErrMessage(): string
    {
        return $this->errMessage;
    }

    public function setErrMessage(string $errMessage): void
    {
        $this->errMessage = $errMessage;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public static function buildSuccess($data = []): self
    {
        $resp = new self();
        $resp->setSuccess(true);
        $resp->setData($data);

        return $resp;
    }

    public static function buildFailure(string $errCode, string $errMessage, array $data = []): self
    {
        $resp = new self();
        $resp->setSuccess(false);
        $resp->setErrCode($errCode);
        $resp->setErrMessage($errMessage);
        $resp->setData($data);

        return $resp;
    }
}