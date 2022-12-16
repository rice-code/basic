<?php

namespace Rice\Basic\Support\Generate;

use Rice\Basic\PathManager;

class FileGenerate
{
    protected $jsonFilePath;

    protected $dirPath;

    protected $tpl = null;

    /**
     * Create a new command instance.
     *
     * @param $jsonFilePath
     * @param $dirPath
     */
    public function __construct($jsonFilePath, $dirPath)
    {
        $this->jsonFilePath = $jsonFilePath;
        $this->dirPath      = $dirPath;
        $this->tpl          = file_get_contents(
            PathManager::getInstance()->template . DIRECTORY_SEPARATOR . 'Class.php.tpl'
        );
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $jsonObj = json_decode(file_get_contents($this->jsonFilePath), false);

        $this->generateDTOFile($jsonObj);
    }

    protected function generateDTOFile($obj, $alias = 'Alias', $type = 'DTO', $namespace = 'Tests\Generate'): void
    {
        $className     = $obj->_class_name ?? $alias;
        $type          = $obj->_type       ?? $type;
        $namespace     = $obj->_namespace  ?? $namespace;

        unset($obj->_class_name, $obj->_type, $obj->_namespace);

        $fields = [];
        foreach ($obj as $k => $v) {
            switch (gettype($v)) {
                case 'object':
                    $this->generateDTOFile($v, $className . ucfirst($k), $type, $namespace);
                    $fields[] = [
                        'type' => $className . ucfirst($k) . $type,
                        'name' => $k,
                    ];

                    break;
                case 'boolean':
                case 'integer':
                case 'double':
                case 'string':
                    $fields[] = [
                        'type' => gettype($v),
                        'name' => $k,
                    ];

                    break;
                case 'array':
                    $fields[] = [
                        'type' => 'array',
                        'name' => $k,
                    ];

                    if (!empty($v)) {
                        $this->generateDTOFile($v[0], $className, $type, $namespace);
                    }

                    break;
            }
        }

        $res = str_replace(
            [
                '{$type}',
                '{$namespace}',
                '{$className}',
                '{$properties}',
            ],
            [
                $type,
                $namespace,
                $className . $type,
                $this->generateProperties($fields),
            ],
            $this->tpl);

        file_put_contents($this->dirPath . $className . $type . '.php', $res);
    }

    protected $propertyfmt = <<<EOF
/**
 * @var %s
 */
protected $%s;
EOF;

    private function generateProperties(array $fields): string
    {
        $str = '';
        foreach ($fields as $field) {
            $str .= sprintf($this->propertyfmt, $field['type'], $field['name']);
            $str .= PHP_EOL . PHP_EOL;
        }

        return $str;
    }
}
