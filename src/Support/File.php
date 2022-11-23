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

    public function close()
    {
        if (!is_null($this->handle)) {
            fclose($this->handle);
            $this->handle = null;
        }
    }

    public function readLine()
    {
        while (!feof($this->handle)) {
            yield trim(fgets($this->handle));
        }
        fclose($this->handle);
        $this->handle = null;
    }
}
