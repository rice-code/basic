<?php

namespace Rice\Basic\Components\Entity;

use App\Contract\CacheContract;
use Rice\Basic\Components\Enum\KeyEnum;

class AnnotationEntity extends BaseEntity
{
    public $fileMtimes = [];

    public $fileUses = [];

    public $fileAlias = [];

    public static function build(?CacheContract $cache)
    {
        $entity = new self();

        $content = json_decode($cache ? $cache->get(KeyEnum::ANNOTATION_KEY) : '', true);

        $entity->fileMtimes = $content['mtimes'] ?? [];
        $entity->fileUses   = $content['uses']   ?? [];
        $entity->fileAlias  = $content['alias']  ?? [];

        return $entity;
    }

    public function hasChangeFile(): bool
    {
        // 当文件修改为空时，说明未进行缓存过任何文件
        if (! $this->fileMtimes) {
            return true;
        }

        foreach ($this->fileMtimes as $path => $time) {
            if (filemtime($path) !== (int) $time) {
                return true;
            }
        }

        return false;
    }

    public function getChangeFiles(): array
    {
        $changeFiles = [];
        foreach ($this->fileMtimes as $path => $time) {
            if (filemtime($path) !== (int) $time) {
                $changeFiles[] = $path;
            }
        }

        return $changeFiles;
    }

    public function setMtime($key, $value): self
    {
        $this->fileMtimes[$key] = $value;

        return $this;
    }

    public function delMtime($key): self
    {
        unset($this->fileMtimes[$key]);

        return $this;
    }

    public function setUses($key, $value): self
    {
        $this->fileUses[$key] = $value;

        return $this;
    }

    public function getUses(string $className = null)
    {
        return $className ? $this->fileUses[$className] : $this->fileUses;
    }

    public function delUses($key): self
    {
        unset($this->fileUses[$key]);

        return $this;
    }

    public function setAlias($key, $value): self
    {
        $this->fileAlias[$key] = $value;

        return $this;
    }

    public function getAlias(string $className = null)
    {
        return $className ? $this->fileAlias[$className] : $this->fileAlias;
    }

    public function delAlias($key): self
    {
        unset($this->fileAlias[$key]);

        return $this;
    }
}
