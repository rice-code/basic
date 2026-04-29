<?php

namespace Rice\Basic\Infrastructure\Enum\ReturnCode;

interface ServiceErrorCode
{
    public const ERROR_CALLING_THIRD_PARTY_SERVICE = 'C0001';
    public const MIDDLEWARE_SERVICE_ERROR = 'C0100';
    public const RPC_SERVICE_ERROR = 'C0110';
    public const RPC_SERVICE_NOT_FOUND = 'C0111';
    public const RPC_SERVICE_IS_NOT_REGISTERED = 'C0112';
    public const INTERFACE_DOES_NOT_EXIST = 'C0113';
    public const MESSAGE_SERVICE_ERROR = 'C0120';
    public const MESSAGE_DELIVERY_ERROR = 'C0121';
    public const MESSAGE_CONSUMPTION_ERROR = 'C0122';
    public const MESSAGE_SUBSCRIPTION_ERROR = 'C0123';
    public const MESSAGE_GROUPING_NOT_FOUND = 'C0124';
    public const CACHE_SERVICE_ERROR = 'C0130';
    public const KEY_LENGTH_EXCEEDS_THE_LIMIT = 'C0131';
    public const LENGTH_OF_VALUE_EXCEEDS_THE_LIMIT = 'C0132';
    public const STORAGE_CAPACITY_IS_FULL = 'C0133';
    public const UNSUPPORTED_DATA_FORMAT = 'C0134';
    public const ERROR_CONFIGURING_SERVICE = 'C0140';
    public const NETWORK_RESOURCE_SERVICE_ERROR = 'C0150';
    public const VPN_SERVICE_ERROR = 'C0151';
    public const CDN_SERVICE_ERROR = 'C0152';
    public const DOMAIN_NAME_RESOLUTION_SERVICE_ERROR = 'C0153';
    public const GATEWAY_SERVICE_ERROR = 'C0154';
    public const THIRD_PARTY_SYSTEM_EXECUTION_TIMEOUT = 'C0200';
    public const RPC_EXECUTION_TIMEOUT = 'C0210';
    public const MESSAGE_DELIVERY_TIMEOUT = 'C0220';
    public const CACHE_DELIVERY_TIMEOUT = 'C0230';
    public const CONFIGURE_DELIVERY_TIMEOUT = 'C0240';
    public const DATABASE_SERVICE_TIMEOUT = 'C0250';
    public const DATABASE_SERVICE_ERROR = 'C0300';
    public const TABLE_DOES_NOT_EXIST = 'C0311';
    public const COLUMN_DOES_NOT_EXIST = 'C0312';
    public const MULTIPLE_COLUMNS_WITH_THE_SAME_NAME_EXIST_IN_THE_MULTI_TABLE_ASSOCIATION = 'C0321';
    public const DEAD_LOCK = 'C0331';
    public const PRIMARY_KEY_CONFLICT = 'C0341';
    public const THIRD_PARTY_DISASTER_RECOVERY_SYSTEM_IS_TRIGGERED = 'C0400';
    public const THIRD_PARTY_SYSTEM_CURRENT_LIMIT = 'C0401';
    public const THIRD_PARTY_FUNCTION_DEGRADATION = 'C0402';
    public const NOTIFICATION_SERVICE_ERROR = 'C0500';
    public const SMS_REMINDER_SERVICE_FAILED = 'C0501';
    public const VOICE_REMINDER_SERVICE_FAILED = 'C0502';
    public const EMAIL_REMINDER_SERVICE_FAILED = 'C0503';
}