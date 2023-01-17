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

    /**
     * 文件命名空间分析
     * @param $classNamespace
     * @param $rowData
     * @return bool
     */
    public function analysis($classNamespace, $rowData): bool
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

    /**
     * 分析文件命名空间执行入口
     * @param string $namespace
     * @param string $path
     * @return $this
     */
    public function execute(string $namespace, string $path): self
    {
        $file = (new File($path));
        $row  = $file->readLine();
        while ($row->valid()) {
            $isDone = $this->analysis($namespace, $row->current());
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
