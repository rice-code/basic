<?php

namespace Rice\Basic\Components\DTO;

/**
 * @method self setPage(int $page)
 * @method string getPage() 获取页码
 * @method self setPerPage(int $perPage)
 * @method string getPerPage() 获取每页条数
 */
class PageDTO extends BaseDTO
{
    /**
     * 页码
     * @var int
     */
    protected $page = 1;
    /**
     * 每页条数.
     * @var int
     */
    protected $perPage = 20;
}