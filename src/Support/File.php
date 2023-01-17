<?php

namespace Rice\Basic\Support;

class File
{
    private $handle;

    public function __construct($path, $mode = 'rb')
    {
        $this->handle = fopen($path, $mode);
    }

    public function __destruct()
    {
        if (!is_null($this->handle)) {
            fclose($this->handle);
        }
    }

    public function close(): void
    {
        if (!is_null($this->handle)) {
            fclose($this->handle);
            $this->handle = null;
        }
    }

    public function readLine(): \Generator
    {
        while (!feof($this->handle)) {
            yield trim(fgets($this->handle));
        }
        fclose($this->handle);
        $this->handle = null;
    }
}
