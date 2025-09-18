<?php

namespace Rice\Basic\Support\Observers;

use Rice\Basic\Contracts\ExceptionObserverInterface;

/**
 * 异常告警观察者
 * 负责发送异常告警通知（如钉钉、邮件等）.
 */
class ExceptionAlertObserver implements ExceptionObserverInterface
{
    /**
     * {@inheritDoc}
     */
    public function handle(\Exception $e): void
    {
        // 构建告警内容
        $alertContent = [
            'title'   => '异常告警',
            'message' => $e->getMessage(),
            'code'    => $e->getCode(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
        ];

        // 发送告警通知
        // 注意：这里是一个简化实现，实际项目中应该根据需求实现具体的告警发送逻辑
        // 例如通过钉钉机器人、邮件服务或其他告警系统发送
        $this->sendAlert($alertContent);
    }

    /**
     * 发送告警通知.
     *
     * @param array $content 告警内容
     */
    protected function sendAlert(array $content): void
    {
        // 实际项目中应该实现具体的告警发送逻辑
        // 这里只是一个示例实现
        // 例如：使用邮件服务发送告警
        // Mail::to('admin@example.com')->send(new ExceptionAlertMail($content));

        // 或者使用钉钉机器人发送告警
        // $dingtalkClient = new DingtalkClient();
        // $dingtalkClient->sendMarkdownMessage($content['title'], $content['message']);

        // 在开发环境中，可以暂时将告警信息写入日志
        error_log('Alert: ' . json_encode($content, JSON_UNESCAPED_UNICODE));
    }
}
