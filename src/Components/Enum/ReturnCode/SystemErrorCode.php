<?php

namespace Rice\Basic\components\Enum\ReturnCode;

interface SystemErrorCode
{
    /**
     * @level 一级宏观错误码
     * @zh-CN 系统执行出错
     */
    public const SYSTEM_EXECUTION_ERROR = 'B0001';
    /**
     * @level 二级宏观错误码
     * @zh-CN 系统执行超时
     */
    public const SYSTEM_EXECUTION_TIMEOUT = 'B0100';
    /**
     * @zh-CN 系统订单处理超时
     */
    public const SYSTEM_ORDER_PROCESSING_TIMEOUT = 'B0101';
    /**
     * @level 二级宏观错误码
     * @zh-CN 系统容灾功能被触发
     */
    public const SYSTEM_DISASTER_TOLERANCE_FUNCTION_IS_TRIGGERED = 'B0200';
    /**
     * @zh-CN 系统限流
     */
    public const SYSTEM_CURRENT_LIMITING = 'B0210';
    /**
     * @zh-CN 系统功能降级
     */
    public const SYSTEM_FUNCTION_DEGRADATION = 'B0220';
    /**
     * @level 二级宏观错误码
     * @zh-CN 系统资源异常
     */
    public const SYSTEM_RESOURCE_EXCEPTION = 'B0300';
    /**
     * @zh-CN 系统资源耗尽
     */
    public const SYSTEM_RESOURCES_EXHAUSTED = 'B0310';
    /**
     * @zh-CN 系统磁盘空间耗尽
     */
    public const SYSTEM_DISK_SPACE_EXHAUSTED = 'B0311';
    /**
     * @zh-CN 系统内存耗尽
     */
    public const SYSTEM_MEMORY_EXHAUSTED = 'B0312';
    /**
     * @zh-CN 文件句柄耗尽
     */
    public const FILE_HANDLE_EXHAUSTED = 'B0313';
    /**
     * @zh-CN 系统连接池耗尽
     */
    public const SYSTEM_CONNECTION_POOL_EXHAUSTED = 'B0314';
    /**
     * @zh-CN 系统线程池耗尽
     */
    public const SYSTEM_THREAD_POOL_EXHAUSTED = 'B0315';
    /**
     * @zh-CN 系统资源访问异常
     */
    public const SYSTEM_RESOURCE_ACCESS_EXCEPTION = 'B0320';
    /**
     * @zh-CN 系统读取磁盘文件失败
     */
    public const SYSTEM_FAILED_TO_READ_THE_DISK_FILE = 'B0321';
}
