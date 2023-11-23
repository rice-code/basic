<?php

namespace Rice\Basic\Components\VO;

class PageResponse extends Response
{
    /**
     * 页码
     * @var int
     */
    protected int $page;
    /**
     * 每页条数.
     * @var int
     */
    protected int $perPage;

    /**
     * 总条数.
     * @var int
     */
    protected int $totalCount;

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function setPerPage(int $perPage): void
    {
        $this->perPage = $perPage;
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    public function setTotalCount(int $totalCount): void
    {
        $this->totalCount = $totalCount;
    }

    public static function buildSuccess($data = [], $total = 0, $page = 1, $perPage = 20): self
    {
        $resp = new self();
        $resp->setSuccess(true);
        $resp->setData($data);
        $resp->setTotalCount($total);
        $resp->setPage($page);
        $resp->setPerPage($perPage);

        return $resp;
    }
}
