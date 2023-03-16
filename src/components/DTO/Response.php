<?php

namespace Rice\Basic\components\DTO;

class Response extends BaseDTO
{
    private bool $success;

    private string $errCode;

    private string $errMessage;

    private array $data = [];

    /**
     * @return bool
     */
    public function getSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param bool $success
     */
    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    /**
     * @param string $errCode
     */
    public function setErrCode(string $errCode): void
    {
        $this->errCode = $errCode;
    }

    /**
     * @return string
     */
    public function getErrCode(): string
    {
        return $this->errCode;
    }

    /**
     * @return string
     */
    public function getErrMessage(): string
    {
        return $this->errMessage;
    }

    /**
     * @param string $errMessage
     */
    public function setErrMessage(string $errMessage): void
    {
        $this->errMessage = $errMessage;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
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

    public static function buildFailure(string $errCode, string $errMessage): self
    {
        $resp = new self();
        $resp->setSuccess(false);
        $resp->setErrCode($errCode);
        $resp->setErrMessage($errMessage);

        return $resp;
    }
}
