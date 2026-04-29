<?php

namespace Rice\Basic\Infrastructure\Enum\ReturnCode;

interface SystemErrorCode
{
    public const SYSTEM_EXECUTION_ERROR = 'B0001';
    public const SYSTEM_EXECUTION_TIMEOUT = 'B0100';
    public const SYSTEM_ORDER_PROCESSING_TIMEOUT = 'B0101';
    public const SYSTEM_DISASTER_TOLERANCE_FUNCTION_IS_TRIGGERED = 'B0200';
    public const SYSTEM_CURRENT_LIMITING = 'B0210';
    public const SYSTEM_FUNCTION_DEGRADATION = 'B0220';
    public const SYSTEM_RESOURCE_EXCEPTION = 'B0300';
    public const SYSTEM_RESOURCES_EXHAUSTED = 'B0310';
    public const SYSTEM_DISK_SPACE_EXHAUSTED = 'B0311';
    public const SYSTEM_MEMORY_EXHAUSTED = 'B0312';
    public const FILE_HANDLE_EXHAUSTED = 'B0313';
    public const SYSTEM_CONNECTION_POOL_EXHAUSTED = 'B0314';
    public const SYSTEM_THREAD_POOL_EXHAUSTED = 'B0315';
    public const SYSTEM_RESOURCE_ACCESS_EXCEPTION = 'B0320';
    public const SYSTEM_FAILED_TO_READ_THE_DISK_FILE = 'B0321';
}