<?php

namespace Rice\Basic\Support;

use Rice\Basic\Support\Traits\Singleton;

class FileNamespace
{
    use Singleton;

    public const START_PATTERN = '/^namespace\s+(.*);$/';
    public const END_PATTERN   = '/^class\s*(.*)$/';
    public const USE_PATTERN   = '/^use\s*([\S]+)[\s*;](?:AS|as)?\s*(\w*)[;]?$/';

    protected array $uses = [];

    protected array $alias = [];

    public function analysisNamespaces($classNamespace, $rowData): bool
    {
        $matches = [];
        if (preg_match(self::START_PATTERN, $rowData, $matches)) {
            $this->uses[$classNamespace]['this'] = $matches[1] ?? '';
        } elseif (preg_match(self::USE_PATTERN, $rowData, $matches)) {
            $useNamespace = $matches[1] ?? '';
            $as           = $matches[2] ?? '';
            if ($useNamespace) {
                $words   = explode('\\', $useNamespace);
                $objName = array_pop($words);

                if (!empty($as)) {
                    $this->alias[$classNamespace][$as] = $objName;
                }

                $this->uses[$classNamespace][$objName] = implode('\\', $words);
            }
        } elseif (preg_match(self::END_PATTERN, $rowData, $matches)) {
            return true;
        }

        return false;
    }

    public function matchNamespace($namespace, $path): self
    {
        $file = (new File($path));
        $row  = $file->readLine();
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

    public function getUses(): array
    {
        return $this->uses;
    }

    public function getAlias(): array
    {
        return $this->alias;
    }
}
