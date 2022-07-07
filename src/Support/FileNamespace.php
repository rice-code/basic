<?php

namespace Rice\Basic\Support;

use Rice\Basic\Support\Traits\Singleton;

class FileNamespace
{
    use Singleton;

    const START_PATTERN = '/^namespace\s+(.*);$/';
    const END_PATTERN = '/^class\s*(.*)$/';
    const USE_PATTERN = '/^use\s*([\S]+)[\s*;](?:AS|as)?\s*(\w*)[;]?$/';

    protected $objectMap = [];

    public function analysisNamespaces($namespace, $rowData): bool
    {
        $matches = [];
        if (preg_match(self::START_PATTERN, $rowData, $matches)) {
            $this->objectMap[$namespace]['this'] = $matches[1] ?? '';
        } elseif (preg_match(self::USE_PATTERN, $rowData, $matches)) {
            $useNamespace = $matches[1] ?? '';
            $alias = $matches[2] ?? '';
            if ($useNamespace) {
                $words = explode('\\', $useNamespace);
                $alias = array_pop($words);
                $this->objectMap[$namespace][$alias] = implode('\\', $words);
            }
        } elseif (preg_match(self::END_PATTERN, $rowData, $matches)) {
            return true;
        }

        return false;
    }

    public function matchNamespace($namespace, $path): FileNamespace
    {
        $file = (new File($path));
        $row = $file->readLine();
        while ($row->valid()) {
            $isDone = $this->analysisNamespaces($namespace, $row->current());
            if ($isDone) {
                break;
            }
            $row->next();
        }
        $file->close();
        return $this;
    }

    public function getNamespaces(): array
    {
        return $this->objectMap;
    }
}