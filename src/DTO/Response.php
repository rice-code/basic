<?php


namespace Rice\Basic\DTO;


class Response extends BaseDTO
{
    private $success;

    private $errCode;

    private $errMessage;
    
    /**
     * @return mixed
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * @param mixed $success
     */
    public function setSuccess($success): void
    {
        $this->success = $success;
    }

    /**
     * @return mixed
     */
    public function getErrCode()
    {
        return $this->errCode;
    }

    /**
     * @param mixed $errCode
     */
    public function setErrCode($errCode): void
    {
        $this->errCode = $errCode;
    }

    /**
     * @return mixed
     */
    public function getErrMessage()
    {
        return $this->errMessage;
    }

    /**
     * @param mixed $errMessage
     */
    public function setErrMessage($errMessage): void
    {
        $this->errMessage = $errMessage;
    }

}