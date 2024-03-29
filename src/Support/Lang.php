<?php

namespace Rice\Basic\Support;

use Rice\Basic\PathManager;
use Rice\Basic\Support\Traits\Singleton;

class Lang
{
    use Singleton;

    private string $locale   = 'zh-CN';
    private string $fileName = 'base';

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

        $content = [];

        if (file_exists($langPath)) {
            $content = json_decode(file_get_contents($langPath), true, 512, JSON_THROW_ON_ERROR);
        }

        return $content;
    }
}
