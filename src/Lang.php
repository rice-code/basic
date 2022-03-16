<?php

namespace Rice\Basic;

use Rice\Basic\Support\Traits\Sington;

class Lang
{
    use Sington;

    private $locale = 'zh-CN';
    private $fileName = 'base';
    private $key = '';

    /**
     * @param string $locale
     * @return Lang
     */
    public function setLocale(string $locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @param string $fileName
     * @return Lang
     */
    public function setFileName(string $fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @param string $key
     * @return Lang
     */
    public function setKey(string $key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    public function getMessage(): string
    {
        $messages = require __DIR__ . '..' .
            DIRECTORY_SEPARATOR . 'Lang' . DIRECTORY_SEPARATOR .
            $this->locale . DIRECTORY_SEPARATOR . $this->fileName . '.php';

        return $messages[$this->key];
    }
}