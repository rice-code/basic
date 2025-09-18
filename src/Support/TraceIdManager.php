<?php

namespace Rice\Basic\Support;

use Rice\Basic\Support\Utils\UUID;
use Rice\Basic\Support\Traits\Singleton;

/**
 * 追踪ID管理器类
 * 负责生成、存储和管理全局唯一的追踪ID，用于跨系统、跨服务的请求追踪
 * 采用单例模式确保整个应用生命周期内使用同一个追踪ID.
 */
class TraceIdManager
{
    use Singleton;

    /**
     * 当前请求的追踪ID.
     *
     * @var string
     */
    private string $traceId = '';

    /**
     * 生成一个新的追踪ID
     * 使用UUID v4算法生成全局唯一标识符.
     *
     * @return string 生成的追踪ID
     */
    private function generateTraceId(): string
    {
        return UUID::v4();
    }

    /**
     * 获取当前的追踪ID
     * 如果尚未生成，则自动生成一个新的追踪ID.
     *
     * @return string 当前的追踪ID
     */
    public function getTraceId(): string
    {
        if (empty($this->traceId)) {
            $this->traceId = $this->generateTraceId();
        }

        return $this->traceId;
    }

    /**
     * 手动设置追踪ID
     * 通常用于从外部系统（如HTTP请求头）接收追踪ID.
     *
     * @param string $traceId 要设置的追踪ID
     * @return $this
     */
    public function setTraceId(string $traceId): self
    {
        $this->traceId = $traceId;

        return $this;
    }

    /**
     * 重置追踪ID
     * 生成并设置一个新的追踪ID.
     *
     * @return string 新生成的追踪ID
     */
    public function resetTraceId(): string
    {
        $this->traceId = $this->generateTraceId();

        return $this->traceId;
    }

    /**
     * 检查是否已经设置了追踪ID.
     *
     * @return bool 追踪ID是否已设置
     */
    public function hasTraceId(): bool
    {
        return !empty($this->traceId);
    }
}
