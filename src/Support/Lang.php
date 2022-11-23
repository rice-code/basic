<?php

namespace Rice\Basic\Support;

use Rice\Basic\Support\Traits\Singleton;

class Lang {
    use Singleton;

    private $locale     = 'zh-CN';
    private $fileName   = 'common';
    private $key        = '';
    protected $messages = [];

    /**
     * @param string $locale
     * @return Lang
     */
    public function setLocale(string $locale): self {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocale(): string {
        return $this->locale;
    }

    /**
     * @param string $fileName
     * @return Lang
     */
    public function setFileName(string $fileName): self {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * @return string
     */
    public function getFileName(): string {
        return $this->fileName;
    }

    /**
     * @param string $key
     * @return Lang
     */
    public function setKey(string $key): self {
        $this->key = $key;

        return $this;
    }

    /**
     * @return string
     */
    public function getKey(): string {
        return $this->key;
    }

    public function loadFile(): self {
        $this->messages = require __DIR__ . DIRECTORY_SEPARATOR . '..' .
            DIRECTORY_SEPARATOR . 'Lang' . DIRECTORY_SEPARATOR .
            $this->locale . DIRECTORY_SEPARATOR . $this->fileName . '.php';

        return $this;
    }

    public function getMessage(string $key): string {
        return $this->messages[$key];
    }

    public function existKey(string $key): bool {
        return isset($this->messages[$key]);
    }
}
