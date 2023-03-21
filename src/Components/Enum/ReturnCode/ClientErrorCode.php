<?php

namespace Rice\Basic\Components\Enum\ReturnCode;

interface ClientErrorCode
{
    /**
     * @level 一级宏观错误码
     * @zh-CN 用户端错误
     */
    public const CLIENT_ERROR = 'A0001';

    /**
     * @level 二级宏观错误码
     * @zh-CN 用户注册错误
     */
    public const USER_REGISTRATION_ERROR = 'A0100';

    /**
     * @zh-CN 用户未同意隐私协议
     */
    public const THE_USER_DID_NOT_AGREE_TO_THE_PRIVACY_AGREEMENT = 'A0101';
    /**
     * @zh-CN 注册国家或地区受限
     */
    public const LIMITED_REGISTRATION_COUNTRY_OR_REGION = 'A0102';
    /**
     * @zh-CN 用户名校验失败
     */
    public const USER_NAME_VERIFICATION_FAILED = 'A0110';
    /**
     * @zh-CN 用户名已存在
     */
    public const USER_NAME_ALREADY_EXISTS = 'A0111';
    /**
     * @zh-CN 用户名包含敏感词
     */
    public const USER_NAME_CONTAINS_SENSITIVE_WORDS = 'A0112';
    /**
     * @zh-CN 用户名包含特殊字符
     */
    public const USER_NAME_CONTAINS_SPECIAL_CHARACTERS = 'A0113';
    /**
     * @zh-CN 密码校验失败
     */
    public const PASSWORD_VERIFICATION_FAILED = 'A0120';
    /**
     * @zh-CN 密码长度不够
     */
    public const THE_PASSWORD_LENGTH_IS_NOT_ENOUGH = 'A0121';
    /**
     * @zh-CN 密码强度不够
     */
    public const INSUFFICIENT_PASSWORD_STRENGTH = 'A0122';
    /**
     * @zh-CN 校验码输入错误
     */
    public const CHECK_CODE_INPUT_ERROR = 'A0130';
    /**
     * @zh-CN 短信校验码输入错误
     */
    public const SMS_VERIFICATION_CODE_INPUT_ERROR = 'A0131';
    /**
     * @zh-CN 邮件校验码输入错误
     */
    public const EMAIL_VERIFICATION_CODE_INPUT_ERROR = 'A0132';
    /**
     * @zh-CN 语音校验码输入错误
     */
    public const VOICE_VERIFICATION_CODE_INPUT_ERROR = 'A0133';
    /**
     * @zh-CN 用户证件异常
     */
    public const USER_CREDENTIAL_IS_ABNORMAL = 'A0140';
    /**
     * @zh-CN 用户证件类型未选择
     */
    public const USER_CREDENTIAL_TYPE_IS_NOT_SELECTED = 'A0141';
    /**
     * @zh-CN 大陆身份证编号校验非法
     */
    public const ILLEGAL_VERIFICATION_OF_MAINLAND_ID_CARD_NUMBER = 'A0142';
    /**
     * @zh-CN 护照编号校验非法
     */
    public const ILLEGAL_VERIFICATION_OF_PASSPORT_NUMBER = 'A0143';
    /**
     * @zh-CN 军官证编号校验非法
     */
    public const ILLEGAL_VERIFICATION_OF_OFFICER_CERTIFICATE_NUMBER = 'A0144';
    /**
     * @zh-CN 用户基本信息校验失败
     */
    public const VERIFICATION_OF_USER_BASIC_INFORMATION_FAILED = 'A0150';
    /**
     * @zh-CN 手机格式校验失败
     */
    public const MOBILE_PHONE_FORMAT_VERIFICATION_FAILED = 'A0151';
    /**
     * @zh-CN 地址格式校验失败
     */
    public const ADDRESS_FORMAT_VERIFICATION_FAILED = 'A0152';
    /**
     * @zh-CN 邮箱格式校验失败
     */
    public const MAILBOX_FORMAT_VERIFICATION_FAILED = 'A0153';
    /**
     * @level 二级宏观错误码
     * @zh-CN 用户登陆异常
     */
    public const USER_LOGIN_EXCEPTION = 'A0200';
    /**
     * @zh-CN 用户账户不存在
     */
    public const USER_ACCOUNT_DOES_NOT_EXIST = 'A0201';
    /**
     * @zh-CN 用户账户被冻结
     */
    public const USER_ACCOUNT_IS_FROZEN = 'A0202';
    /**
     * @zh-CN 用户账户已作废
     */
    public const USER_ACCOUNT_HAS_BEEN_VOIDED = 'A0203';
    /**
     * @zh-CN 用户密码错误
     */
    public const USER_PASSWORD_ERROR = 'A0210';
    /**
     * @zh-CN 用户输入密码次数超限
     */
    public const USER_ENTERS_THE_PASSWORD_EXCEEDS_THE_LIMIT = 'A0211';
    /**
     * @zh-CN 用户身份校验失败
     */
    public const USER_AUTHENTICATION_FAILED = 'A0220';
    /**
     * @zh-CN 用户指纹识别失败
     */
    public const USER_FINGERPRINT_IDENTIFICATION_FAILED = 'A0221';
    /**
     * @zh-CN 用户面容识别失败
     */
    public const USER_FACE_RECOGNITION_FAILED = 'A0222';
    /**
     * @zh-CN 用户未获得第三方登陆授权
     */
    public const USER_IS_NOT_AUTHORIZED_TO_LOG_IN_BY_A_THIRD_PARTY = 'A0223';
    /**
     * @zh-CN 用户登陆已过期
     */
    public const USER_LOGIN_HAS_EXPIRED = 'A0230';
    /**
     * @zh-CN 用户验证码错误
     */
    public const USER_VERIFICATION_CODE_ERROR = 'A0240';
    /**
     * @zh-CN 用户验证码尝试次数超限
     */
    public const USER_VERIFICATION_CODE_ATTEMPTS_EXCEEDS_THE_LIMIT = 'A0241';
    /**
     * @level 二级宏观错误码
     * @zh-CN 访问权限异常
     */
    public const ACCESS_PERMISSION_EXCEPTION = 'A0300';
    /**
     * @zh-CN 访问未授权
     */
    public const UNAUTHORIZED_ACCESS = 'A0301';
    /**
     * @zh-CN 正在授权中
     */
    public const AUTHORIZING = 'A0302';
    /**
     * @zh-CN 用户授权申请被拒绝
     */
    public const USER_AUTHORIZATION_APPLICATION_WAS_REJECTED = 'A0303';
    /**
     * @zh-CN 因访问对象隐私设置被拦截
     */
    public const BLOCKED_DUE_TO_ACCESS_OBJECT_PRIVACY_SETTINGS = 'A0310';
    /**
     * @zh-CN 授权已过期
     */
    public const AUTHORIZATION_HAS_EXPIRED = 'A0311';
    /**
     * @zh-CN 无权限使用 API
     */
    public const NO_PERMISSION_TO_USE_API = 'A0312';
    /**
     * @zh-CN 用户访问被拦截
     */
    public const USER_ACCESS_IS_BLOCKED = 'A0320';
    /**
     * @zh-CN 黑名单用户
     */
    public const BLACKLIST_USERS = 'A0321';
    /**
     * @zh-CN 账号被冻结
     */
    public const ACCOUNT_BLOCKED = 'A0322';
    /**
     * @zh-CN 非法 IP 地址
     */
    public const ILLEGAL_IP_ADDRESS = 'A0323';
    /**
     * @zh-CN 网关访问受限
     */
    public const GATEWAY_ACCESS_IS_RESTRICTED = 'A0324';
    /**
     * @zh-CN 地域黑名单
     */
    public const REGIONAL_BLACKLIST = 'A0325';
    /**
     * @zh-CN 服务已欠费
     */
    public const SERVICE_OVERDUE = 'A0330';
    /**
     * @zh-CN 用户签名异常
     */
    public const USER_SIGNATURE_EXCEPTION = 'A0340';
    /**
     * @zh-CN RSA 签名错误
     */
    public const RSA_SIGNATURE_ERROR = 'A0341';
    /**
     * @level 二级宏观错误码
     * @zh-CN 用户请求参数错误
     */
    public const USER_REQUEST_PARAMETER_ERROR = 'A0400';
    /**
     * @zh-CN 包含非法恶意跳转链接
     */
    public const CONTAINS_ILLEGAL_MALICIOUS_JUMP_LINKS = 'A0401';
    /**
     * @zh-CN 无效的用户输入
     */
    public const INVALID_USER_INPUT = 'A0402';
    /**
     * @zh-CN 请求必填参数为空
     */
    public const REQUIRED_PARAMETER_OF_THE_REQUEST_IS_EMPTY = 'A0410';
    /**
     * @zh-CN 用户订单号为空
     */
    public const USER_ORDER_NUMBER_IS_EMPTY = 'A0411';
    /**
     * @zh-CN 订购数量为空
     */
    public const ORDER_QUANTITY_IS_EMPTY = 'A0412';
    /**
     * @zh-CN 缺少时间戳参数
     */
    public const MISSING_TIMESTAMP_PARAMETER = 'A0413';
    /**
     * @zh-CN 非法的时间戳参数
     */
    public const ILLEGAL_TIMESTAMP_PARAMETER = 'A0414';
    /**
     * @zh-CN 请求参数值超出允许的范围
     */
    public const REQUEST_PARAMETER_VALUE_EXCEEDS_THE_ALLOWED_RANGE = 'A0420';
    /**
     * @zh-CN 参数格式不匹配
     */
    public const PARAMETER_FORMAT_DOES_NOT_MATCH = 'A0421';
    /**
     * @zh-CN 地址不在服务范围
     */
    public const ADDRESS_IS_NOT_IN_THE_SERVICE_SCOPE = 'A0422';
    /**
     * @zh-CN 时间不在服务范围
     */
    public const TIME_IS_NOT_WITHIN_THE_SCOPE_OF_SERVICE = 'A0423';
    /**
     * @zh-CN 金额超出限制
     */
    public const AMOUNT_EXCEEDS_LIMIT = 'A0424';
    /**
     * @zh-CN 数量超出限制
     */
    public const QUANTITY_EXCEEDS_THE_LIMIT = 'A0425';
    /**
     * @zh-CN 数量超出限制
     */
    public const TOTAL_NUMBER_OF_REQUESTS_FOR_BATCH_PROCESSING_EXCEEDS_THE_LIMIT = 'A0426';
    /**
     * @zh-CN 请求 JSON 解析失败
     */
    public const FAILED_TO_REQUEST_JSON_PARSING = 'A0427';
    /**
     * @zh-CN 用户输入内容非法
     */
    public const ILLEGAL_USER_INPUT = 'A0430';
    /**
     * @zh-CN 包含违禁敏感词
     */
    public const CONTAINS_PROHIBITED_SENSITIVE_WORDS = 'A0431';
    /**
     * @zh-CN 图片包含违禁信息
     */
    public const PICTURE_CONTAINS_PROHIBITED_INFORMATION = 'A0432';
    /**
     * @zh-CN 文件侵犯版权
     */
    public const DOCUMENT_INFRINGES_COPYRIGHT = 'A0433';
    /**
     * @zh-CN 用户操作异常
     */
    public const ABNORMAL_USER_OPERATION = 'A0440';
    /**
     * @zh-CN 用户支付超时
     */
    public const USER_PAYMENT_TIMEOUT = 'A0441';
    /**
     * @zh-CN 确认订单超时
     */
    public const ORDER_CONFIRMATION_TIMEOUT = 'A0442';
    /**
     * @zh-CN 订单已关闭
     */
    public const ORDER_CLOSED = 'A0443';
    /**
     * @level 二级宏观错误码
     * @zh-CN 用户请求服务异常
     */
    public const USER_REQUEST_SERVICE_EXCEPTION = 'A0500';
    /**
     * @zh-CN 请求次数超出限制
     */
    public const NUMBER_OF_REQUESTS_EXCEEDS_THE_LIMIT = 'A0501';
    /**
     * @zh-CN 请求并发数超出限制
     */
    public const CONCURRENT_REQUESTS_EXCEEDS_THE_LIMIT = 'A0502';
    /**
     * @zh-CN 用户操作请等待
     */
    public const PLEASE_WAIT_FOR_USER_OPERATION = 'A0503';
    /**
     * @zh-CN WebSocket 连接异常
     */
    public const WEBSOCKET_CONNECTION_EXCEPTION = 'A0504';
    /**
     * @zh-CN WebSocket 连接断开
     */
    public const WEBSOCKET_CONNECTION_DISCONNECTED = 'A0505';
    /**
     * @zh-CN 用户重复请求
     */
    public const USER_REPEATED_REQUEST = 'A0506';
    /**
     * @level 二级宏观错误码
     * @zh-CN 用户资源异常
     */
    public const USER_RESOURCE_EXCEPTION = 'A0600';
    /**
     * @zh-CN 账户余额不足
     */
    public const INSUFFICIENT_ACCOUNT_BALANCE = 'A0601';
    /**
     * @zh-CN 用户磁盘空间不足
     */
    public const INSUFFICIENT_USER_DISK_SPACE = 'A0602';
    /**
     * @zh-CN 用户内存空间不足
     */
    public const INSUFFICIENT_USER_MEMORY_SPACE = 'A0603';
    /**
     * @zh-CN 用户 OSS 容量不足
     */
    public const INSUFFICIENT_USER_OSS_CAPACITY = 'A0604';
    /**
     * @zh-CN 用户配额已用光
     */
    public const USER_QUOTA_IS_USED_UP = 'A0605';
    /**
     * @level 二级宏观错误码
     * @zh-CN 用户上传文件异常
     */
    public const USER_UPLOAD_FILE_EXCEPTION = 'A0700';
    /**
     * @zh-CN 用户上传文件类型不匹配
     */
    public const FILE_TYPE_UPLOADED_BY_THE_USER_DOES_NOT_MATCH = 'A0701';
    /**
     * @zh-CN 用户上传文件太大
     */
    public const FILE_UPLOADED_BY_THE_USER_IS_TOO_LARGE = 'A0702';
    /**
     * @zh-CN 用户上传图片太大
     */
    public const IMAGE_UPLOADED_BY_THE_USER_IS_TOO_LARGE = 'A0703';
    /**
     * @zh-CN 用户上传视频太大
     */
    public const VIDEO_UPLOADED_BY_THE_USER_IS_TOO_LARGE = 'A0704';
    /**
     * @zh-CN 用户上传压缩文件太大
     */
    public const COMPRESSED_FILE_UPLOADED_BY_THE_USER_IS_TOO_LARGE = 'A0705';
    /**
     * @level 二级宏观错误码
     * @zh-CN 用户当前版本异常
     */
    public const USER_CURRENT_VERSION_IS_ABNORMAL = 'A0800';
    /**
     * @zh-CN 用户安装版本与系统不匹配
     */
    public const USER_INSTALLED_VERSION_DOES_NOT_MATCH_THE_SYSTEM = 'A0801';
    /**
     * @zh-CN 用户安装版本过低
     */
    public const USER_INSTALLED_VERSION_IS_TOO_LOW = 'A0802';
    /**
     * @zh-CN 用户安装版本过高
     */
    public const USER_INSTALLED_VERSION_IS_TOO_HIGH = 'A0803';
    /**
     * @zh-CN 用户安装版本已过期
     */
    public const USER_INSTALLED_VERSION_HAS_EXPIRED = 'A0804';
    /**
     * @zh-CN 用户 API 请求版本不匹配
     */
    public const USER_API_REQUEST_VERSION_MISMATCH = 'A0805';
    /**
     * @zh-CN 用户 API 请求版本过高
     */
    public const USER_API_REQUEST_VERSION_IS_TOO_HIGH = 'A0806';
    /**
     * @zh-CN 用户 API 请求版本过低
     */
    public const USER_API_REQUEST_VERSION_IS_TOO_LOW = 'A0807';
    /**
     * @level 二级宏观错误码
     * @zh-CN 用户隐私未授权
     */
    public const USER_PRIVACY_IS_NOT_AUTHORIZED = 'A0900';
    /**
     * @zh-CN 用户隐私未签署
     */
    public const USER_PRIVACY_IS_NOT_SIGNED = 'A0901';
    /**
     * @zh-CN 用户摄像头未授权
     */
    public const USER_FRONT_CAMERA_IS_NOT_AUTHORIZED = 'A0902';
    /**
     * @zh-CN 用户相机未授权
     */
    public const USER_CAMERA_IS_NOT_AUTHORIZED = 'A0903';
    /**
     * @zh-CN 用户图片库未授权
     */
    public const USER_PICTURE_LIBRARY_IS_NOT_AUTHORIZED = 'A0904';
    /**
     * @zh-CN 用户文件未授权
     */
    public const USER_FILE_IS_NOT_AUTHORIZED = 'A0905';
    /**
     * @zh-CN 用户位置信息未授权
     */
    public const USER_LOCATION_INFORMATION_IS_NOT_AUTHORIZED = 'A0906';
    /**
     * @zh-CN 用户通讯录未授权
     */
    public const USER_ADDRESS_BOOK_IS_NOT_AUTHORIZED = 'A0907';
    /**
     * @level 二级宏观错误码
     * @zh-CN 用户设备异常
     */
    public const ABNORMAL_USER_EQUIPMENT = 'A1000';
    /**
     * @zh-CN 用户相机异常
     */
    public const USER_CAMERA_EXCEPTION = 'A1001';
    /**
     * @zh-CN 用户麦克风异常
     */
    public const ABNORMAL_USER_MICROPHONE = 'A1002';
    /**
     * @zh-CN 用户听筒异常
     */
    public const ABNORMAL_USER_HANDSET = 'A1003';
    /**
     * @zh-CN 用户扬声器异常
     */
    public const ABNORMAL_USER_SPEAKER = 'A1004';
    /**
     * @zh-CN 用户 GPS 定位异常
     */
    public const USER_GPS_POSITIONING_IS_ABNORMAL = 'A1005';
}
