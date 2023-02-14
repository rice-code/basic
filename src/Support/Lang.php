<?php

namespace Rice\Basic\Support;

use Rice\Basic\PathManager;
use Rice\Basic\Support\Traits\Singleton;

class Lang
{
    use Singleton;

    private $locale   = 'zh-CN';
    private $fileName = 'base';

    /**
     * @param string $locale
     * @return Lang
     */
    public function setLocale(string $locale): self
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
    public function setFileName(string $fileName): self
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

    public function loadFile(): array
    {
        $langPath = PathManager::getInstance()->lang . $this->locale . DIRECTORY_SEPARATOR . $this->fileName . '.json';

        return json_decode(file_get_contents($langPath), true);
    }
}
