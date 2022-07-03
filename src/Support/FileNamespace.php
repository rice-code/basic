<?php

namespace Rice\Basic\Support;

class FileNamespace
{
    const START_PATTERN = '/^namespace\s+(.*);$/';
    const END_PATTERN = '/^class\s*.*$/';
    const USE_PATTERN = '/^use\s*([\w\\\\]+)[\s*;](?:AS|as)?\s*(\w*)[;]?$/';

    public $isRead = false;
    protected $objectMap = [];

    public function analysisNamespaces($rowData): bool
    {
        var_dump(111111111111111111);
        var_dump($rowData);
        $matches = [];
        if (!$this->isRead && preg_match(self::START_PATTERN, $rowData, $matches)) {
            var_dump($matches);
            $this->objectMap['this'] = $matches[1] ?? '';
            $this->isRead = true;
        } elseif ($this->isRead && preg_match(self::USE_PATTERN, $rowData, $matches)) {
            var_dump($matches);
            $useNamespace = $matches[1] ?? '';
            $alias = $matches[2] ?? '';
            if ($useNamespace) {
                $words = explode('\\', $useNamespace);
                $alias = array_pop($words);
                $this->objectMap[$alias] = implode('\\', $words);
            }
        } elseif ($this->isRead && preg_match(self::END_PATTERN, $rowData, $matches)) {
            return false;
        }

        return true;
    }

    public function matchNamespace($path): FileNamespace
    {
        $file = (new File($path));
        $row = $file->readLine();
        while ($row->valid()) {
            $isDone = $this->analysisNamespaces($row->current());
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