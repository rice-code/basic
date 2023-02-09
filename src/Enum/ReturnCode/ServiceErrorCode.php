<?php

namespace Rice\Basic\Enum\ReturnCode;

interface ServiceErrorCode
{
    /**
     * @level 一级宏观错误码
     * @zh-CN 调用第三方服务出错
     */
    public const ERROR_CALLING_THIRD_PARTY_SERVICE = 'C0001';
    /**
     * @level 二级宏观错误码
     * @zh-CN 中间件服务出错
     */
    public const MIDDLEWARE_SERVICE_ERROR = 'C0100';
    /**
     * @zh-CN RPC 服务出错
     */
    public const RPC_SERVICE_ERROR = 'C0110';
    /**
     * @zh-CN RPC 服务未找到
     */
    public const RPC_SERVICE_NOT_FOUND = 'C0111';
    /**
     * @zh-CN RPC 服务未找到
     */
    public const RPC_SERVICE_IS_NOT_REGISTERED = 'C0112';
    /**
     * @zh-CN 接口不存在
     */
    public const INTERFACE_DOES_NOT_EXIST = 'C0113';
    /**
     * @zh-CN 消息服务出错
     */
    public const MESSAGE_SERVICE_ERROR = 'C0120';
    /**
     * @zh-CN 消息投递出错
     */
    public const MESSAGE_DELIVERY_ERROR = 'C0121';
    /**
     * @zh-CN 消息消费出错
     */
    public const MESSAGE_CONSUMPTION_ERROR = 'C0122';
    /**
     * @zh-CN 消息订阅出错
     */
    public const MESSAGE_SUBSCRIPTION_ERROR = 'C0123';
    /**
     * @zh-CN 消息分组未查到
     */
    public const MESSAGE_GROUPING_NOT_FOUND = 'C0124';
    /**
     * @zh-CN 缓存服务出错
     */
    public const CACHE_SERVICE_ERROR = 'C0130';
    /**
     * @zh-CN key 长度超过限制
     */
    public const KEY_LENGTH_EXCEEDS_THE_LIMIT = 'C0131';
    /**
     * @zh-CN value 长度超过限制
     */
    public const LENGTH_OF_VALUE_EXCEEDS_THE_LIMIT = 'C0132';
    /**
     * @zh-CN 存储容量已满
     */
    public const STORAGE_CAPACITY_IS_FULL = 'C0133';
    /**
     * @zh-CN 不支持的数据格式
     */
    public const UNSUPPORTED_DATA_FORMAT = 'C0134';
    /**
     * @zh-CN 配置服务出错
     */
    public const ERROR_CONFIGURING_SERVICE = 'C0140';
    /**
     * @zh-CN 网络资源服务出错
     */
    public const NETWORK_RESOURCE_SERVICE_ERROR = 'C0150';
    /**
     * @zh-CN VPN 服务出错
     */
    public const VPN_SERVICE_ERROR = 'C0151';
    /**
     * @zh-CN CDN 服务出错
     */
    public const CDN_SERVICE_ERROR = 'C0152';
    /**
     * @zh-CN 域名解析服务出错
     */
    public const DOMAIN_NAME_RESOLUTION_SERVICE_ERROR = 'C0153';
    /**
     * @zh-CN 网关服务出错
     */
    public const GATEWAY_SERVICE_ERROR = 'C0154';
    /**
     * @level 二级宏观错误码
     * @zh-CN 第三方系统执行超时
     */
    public const THIRD_PARTY_SYSTEM_EXECUTION_TIMEOUT = 'C0200';
    /**
     * @zh-CN RPC 执行超时
     */
    public const RPC_EXECUTION_TIMEOUT = 'C0210';
    /**
     * @zh-CN 消息投递超时
     */
    public const MESSAGE_DELIVERY_TIMEOUT = 'C0220';
    /**
     * @zh-CN 缓存服务超时
     */
    public const CACHE_DELIVERY_TIMEOUT = 'C0230';
    /**
     * @zh-CN 配置服务超时
     */
    public const CONFIGURE_DELIVERY_TIMEOUT = 'C0240';
    /**
     * @zh-CN 数据库服务超时
     */
    public const DATABASE_SERVICE_TIMEOUT = 'C0250';
    /**
     * @level 二级宏观错误码
     * @zh-CN 数据库服务出错
     */
    public const DATABASE_SERVICE_ERROR = 'C0300';
    /**
     * @zh-CN 表不存在
     */
    public const TABLE_DOES_NOT_EXIST = 'C0311';
    /**
     * @zh-CN 列不存在
     */
    public const COLUMN_DOES_NOT_EXIST = 'C0312';
    /**
     * @zh-CN 多表关联中存在多个相同名称的列
     */
    public const MULTIPLE_COLUMNS_WITH_THE_SAME_NAME_EXIST_IN_THE_MULTI_TABLE_ASSOCIATION = 'C0321';
    /**
     * @zh-CN 数据库死锁
     */
    public const DEAD_LOCK = 'C0331';
    /**
     * @zh-CN 主键冲突
     */
    public const PRIMARY_KEY_CONFLICT = 'C0341';
    /**
     * @level 二级宏观错误码
     * @zh-CN 第三方容灾系统被触发
     */
    public const THIRD_PARTY_DISASTER_RECOVERY_SYSTEM_IS_TRIGGERED = 'C0400';
    /**
     * @zh-CN 第三方系统限流
     */
    public const THIRD_PARTY_SYSTEM_CURRENT_LIMIT = 'C0401';
    /**
     * @zh-CN 第三方功能降级
     */
    public const THIRD_PARTY_FUNCTION_DEGRADATION = 'C0402';
    /**
     * @level 二级宏观错误码
     * @zh-CN 通知服务出错
     */
    public const NOTIFICATION_SERVICE_ERROR = 'C0500';
    /**
     * @zh-CN 短信提醒服务失败
     */
    public const SMS_REMINDER_SERVICE_FAILED = 'C0501';
    /**
     * @zh-CN 语音提醒服务失败
     */
    public const VOICE_REMINDER_SERVICE_FAILED = 'C0502';
    /**
     * @zh-CN 邮件提醒服务失败
     */
    public const EMAIL_REMINDER_SERVICE_FAILED = 'C0503';
}