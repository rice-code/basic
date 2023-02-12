<?php

namespace Rice\Basic\Support\Abstracts;

use GuzzleHttp\RequestOptions;
use Rice\Basic\Entity\BaseEntity;
use GuzzleHttp\Client;

abstract class GuzzleClient
{
    protected Client $client;
    protected array $option;
    protected BaseEntity $entity;
    public function __construct(BaseEntity $entity)
    {
        $this->client = new Client();
        $this->entity = $entity;
    }

    abstract public function send();

    public function 
}